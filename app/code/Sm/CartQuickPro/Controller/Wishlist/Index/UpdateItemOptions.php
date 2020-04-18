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

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Wishlist\Controller\WishlistProviderInterface;

class UpdateItemOptions extends \Magento\Wishlist\Controller\AbstractIndex
{
    /**
     * @var WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

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

    /**
     * @param Action\Context $context
     * @param Session $customerSession
     * @param WishlistProviderInterface $wishlistProvider
     * @param ProductRepositoryInterface $productRepository
     * @param Validator $formKeyValidator
     */
    public function __construct(
        Action\Context $context,
        Session $customerSession,
        WishlistProviderInterface $wishlistProvider,
        ProductRepositoryInterface $productRepository,
        Validator $formKeyValidator
    ) {
        $this->_customerSession = $customerSession;
        $this->wishlistProvider = $wishlistProvider;
        $this->productRepository = $productRepository;
        $this->formKeyValidator = $formKeyValidator;
        $this->_objectManager = $context->getObjectManager();
        parent::__construct($context);
    }

    /**
     * Action to accept new configuration for a wishlist item
     *
     * @return \Magento\Framework\Controller\Result\Redirect
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

        $productId = (int)$this->getRequest()->getParam('product');
        if (!$productId) {
            $resultRedirect->setPath('*/');
            return $resultRedirect;
        }

        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = null;
        }

        if (!$product || !$product->isVisibleInCatalog()) {
            $this->messageManager->addError(__('We can\'t specify a product.'));
            $resultRedirect->setPath('*/');
            return $resultRedirect;
        }

        try {
            $id = (int)$this->getRequest()->getParam('id');
            /* @var \Magento\Wishlist\Model\Item */
            $item = $this->_objectManager->create('Magento\Wishlist\Model\Item');
            $item->load($id);
            $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());
            if (!$wishlist) {
                $resultRedirect->setPath('*/');
                return $resultRedirect;
            }

            $buyRequest = new \Magento\Framework\DataObject($this->getRequest()->getParams());

            $wishlist->updateItem($id, $buyRequest)->save();

            $this->_objectManager->get('Magento\Wishlist\Helper\Data')->calculate();
            $this->_eventManager->dispatch(
                'wishlist_update_item',
                ['wishlist' => $wishlist, 'product' => $product, 'item' => $wishlist->getItem($id)]
            );

            $this->_objectManager->get('Magento\Wishlist\Helper\Data')->calculate();

            $message = __('%1 has been updated in your Wish List.', $product->getName());
            $this->messageManager->addSuccess($message);
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
            $this->messageManager->addError($e->getMessage());
			$result['success'] = false;
			$result['messages'] =  $e->getMessage();
        } catch (\Exception $e) {
            $this->messageManager->addError(__('We can\'t update your Wish List right now.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
			$result['success'] = false;
			$result['messages'] =  __('We can\'t update your Wish List right now.');
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
