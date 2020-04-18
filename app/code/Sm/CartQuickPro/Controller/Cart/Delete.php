<?php
/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace Sm\CartQuickPro\Controller\Cart;

use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Delete extends \Magento\Checkout\Controller\Cart
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param ProductRepositoryInterface $productRepository
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->_objectManager = $context->getObjectManager();
    }

    /**
     * Delete shopping cart item action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {	
		$result = [];
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $id = (int)$this->getRequest()->getParam('id');
		$params = $this->getRequest()->getParams();
        if ($id) {
            try {
                $this->cart->removeItem($id)->save();
				$result['success'] = true;
				$result['messages'] =  __('Item was removed successfully.');
				if (isset($params['isCheckoutPage'])){
					$_layout  = $this->_objectManager->get('Magento\Framework\View\LayoutInterface');
					$_layout->getUpdate()->load([ 'cartquickpro_checkout_cart_index', 'checkout_cart_item_renderers','checkout_item_price_renderers']);
					$_layout->generateXml();
					$_output = $_layout->getOutput();
					$result['content'] = $_output;
					$result['isPageCheckoutContent'] =  true;
				}
            } catch (\Exception $e) {
                $this->messageManager->addError(__('We can\'t remove the item.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
				$result['success'] = false;
				$result['messages'] =  __('We can\'t remove the item.');
            }
        }
       	$result['isAddToCartBtn'] =   (!isset($params['isCheckoutPage']) && $this->cart->getItemsCount()) ? true : false ;
		return $this->_jsonResponse($result);
    }

    /**
     * @param $result
     * @return mixed
     */
	protected function _jsonResponse($result)
    {
        return $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
        );
    }
}
