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
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Wishlist\Controller\WishlistProviderInterface;
use Magento\Wishlist\Model\Item;
use Magento\Wishlist\Model\Product\AttributeValueProvider;

class Remove extends \Magento\Wishlist\Controller\AbstractIndex
{
    /**
     * @var WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    private $attributeValueProvider;

    /**
     * @param Action\Context $context
     * @param WishlistProviderInterface $wishlistProvider
     * @param Validator $formKeyValidator
     */
    public function __construct(
        Action\Context $context,
        WishlistProviderInterface $wishlistProvider,
        Validator $formKeyValidator,
        AttributeValueProvider $attributeValueProvider = null
    ) {
        $this->wishlistProvider = $wishlistProvider;
        $this->formKeyValidator = $formKeyValidator;
        $this->attributeValueProvider = $attributeValueProvider
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(AttributeValueProvider::class);
        $this->_objectManager = $context->getObjectManager();
        parent::__construct($context);
    }

    /**
     * Remove item
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $result = [];
		$params = $this->getRequest()->getParams();
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('*/*/');
        }

        $id = (int)$this->getRequest()->getParam('item');
        $item = $this->_objectManager->create(Item::class)->load($id);
        if (!$item->getId()) {
            throw new NotFoundException(__('Page not found.'));
        }
        $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());
        if (!$wishlist) {
            throw new NotFoundException(__('Page not found.'));
        }
        try {
            $item->delete();
            $wishlist->save();
            $productName = $this->attributeValueProvider
                ->getRawAttributeValue($item->getProductId(), 'name');
            $this->messageManager->addComplexSuccessMessage(
                'removeWishlistItemSuccessMessage',
                [
                    'product_name' => $productName,
                ]
            );
			$message = __(
				'You removed product from your Wish List.'
			);
			$result['success'] = true;
			$result['messages'] =  $message;
			if (isset($params['isWishlistPage'])){
				$_layout  = $this->_objectManager->get('Magento\Framework\View\LayoutInterface');

				$_layout->getUpdate()->load(['cartquickpro_wishlist_index_index']);
				$_layout->generateXml();
				$_output = $_layout->getOutput();
				$result['content'] = $_output;
				$result['isWishlistPageContent'] =  true;
			}
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError(
                __('We can\'t delete the item from Wish List right now because of an error: %1.', $e->getMessage())
            );
			$result['success'] = false;
			$result['messages'] =   __('We can\'t delete the item from Wish List right now because of an error: %1.', $e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(__('We can\'t delete the item from the Wish List right now.'));
			$result['success'] = false;
			$result['messages'] =  __('We can\'t delete the item from the Wish List right now.');
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
