<?php
/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace Sm\CartQuickPro\Controller\Wishlist\Index;

use Magento\Framework\App\Action;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Controller\ResultFactory;

class Update extends \Magento\Wishlist\Controller\AbstractIndex
{
    /**
     * @var \Magento\Wishlist\Controller\WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $_formKeyValidator;

    /**
     * @var \Magento\Wishlist\Model\LocaleQuantityProcessor
     */
    protected $quantityProcessor;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor
    ) {
        $this->_formKeyValidator = $formKeyValidator;
        $this->wishlistProvider = $wishlistProvider;
        $this->quantityProcessor = $quantityProcessor;
        $this->_objectManager = $context->getObjectManager();
        parent::__construct($context);
    }

    /**
     * Update wishlist item comments
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws NotFoundException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $result = [];
		$params = $this->getRequest()->getParams();
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $wishlist = $this->wishlistProvider->getWishlist();
        if (!$wishlist) {
            throw new NotFoundException(__('Page not found.'));
        }

        $post = $this->getRequest()->getPostValue();
        if ($post && isset($post['description']) && is_array($post['description'])) {
            $updatedItems = 0;

            foreach ($post['description'] as $itemId => $description) {
                $item = $this->_objectManager->create('Magento\Wishlist\Model\Item')->load($itemId);
                if ($item->getWishlistId() != $wishlist->getId()) {
                    continue;
                }

                // Extract new values
                $description = (string)$description;

                if ($description == $this->_objectManager->get('Magento\Wishlist\Helper\Data')->defaultCommentString()
                ) {
                    $description = '';
                } elseif (!strlen($description)) {
                    $description = $item->getDescription();
                }

                $qty = null;
                if (isset($post['qty'][$itemId])) {
                    $qty = $this->quantityProcessor->process($post['qty'][$itemId]);
                }
                if ($qty === null) {
                    $qty = $item->getQty();
                    if (!$qty) {
                        $qty = 1;
                    }
                } elseif (0 == $qty) {
                    try {
                        $item->delete();
                    } catch (\Exception $e) {
                        $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                        $this->messageManager->addError(__('We can\'t delete item from Wish List right now.'));
						$result['success'] = false;
						$result['messages'] =  __('We can\'t delete item from Wish List right now.');
                    }
                }

                // Check that we need to save
                if ($item->getDescription() == $description && $item->getQty() == $qty) {
                    continue;
                }
                try {
                    $item->setDescription($description)->setQty($qty)->save();
                    $updatedItems++;
                } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __(
                            'Can\'t save description %1',
                            $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($description)
                        )
                    );
					$result['success'] = false;
					$result['messages'] =  __(
                            'Can\'t save description %1',
                            $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($description)
                        );
                }
            }
            // save wishlist model for setting date of last update
            if ($updatedItems) {
                try {
                    $wishlist->save();
                    $this->_objectManager->get('Magento\Wishlist\Helper\Data')->calculate();
					if (isset($params['isWishlistPage'])){
						$_layout  = $this->_objectManager->get('Magento\Framework\View\LayoutInterface');
						$_layout->getUpdate()->load(['cartquickpro_wishlist_index_index']);
						$_layout->generateXml();
						$_output = $_layout->getOutput();
						$result['content'] = $_output;
						$result['isWishlistPageContent'] =  true;
					}
					$result['success'] = true;
					$result['messages'] =  __('Update Wishlist success.');
                } catch (\Exception $e) {
                    $this->messageManager->addError(__('Can\'t update wish list'));
					$result['success'] = false;
					$result['messages'] =  __('Can\'t update wish list');
                }
            }else{
				$result['success'] = true;
				$result['messages'] =  __('Update Wishlist success.');
			}
        }else{
			$result['success'] = true;
			$result['messages'] =  __('Update Wishlist success.');
		}
        
		$result['isWishlistBtn'] =   (!isset($params['isWishlistPage']) && $wishlist->getItemsCount()) ? true : false ;
		return $this->_jsonResponse($result);
    }
	
	protected function _jsonResponse($result)
    {
        return $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
        );
    }
}
