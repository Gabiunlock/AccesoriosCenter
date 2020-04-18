<?php
/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace Sm\CartQuickPro\Controller\Sidebar;

use Magento\Checkout\Model\Sidebar;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;

class UpdateItemQty extends Action
{
    /**
     * @var Sidebar
     */
    protected $sidebar;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param Context $context
     * @param Sidebar $sidebar
     * @param LoggerInterface $logger
     * @param Data $jsonHelper
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        Sidebar $sidebar,
        LoggerInterface $logger,
        Data $jsonHelper
    ) {
        $this->sidebar = $sidebar;
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->_objectManager = $context->getObjectManager();
        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $result = [];
		$itemId = (int)$this->getRequest()->getParam('item_id');
        $itemQty = (int)$this->getRequest()->getParam('item_qty');
		$params = $this->getRequest()->getParams();
        try {
            $this->sidebar->checkQuoteItem($itemId);
            $this->sidebar->updateQuoteItem($itemId, $itemQty);
			$result['success'] = true;
			$result['messages'] =  __('Item was updated successfully.');
			if (isset($params['isCheckoutPage'])){
				$_layout  = $this->_objectManager->get('Magento\Framework\View\LayoutInterface');
				$_layout->getUpdate()->load([ 'cartquickpro_checkout_cart_index', 'checkout_cart_item_renderers','checkout_item_price_renderers']);
				$_layout->generateXml();
				$_output = $_layout->getOutput();
				$result['content'] = $_output;
				$result['isPageCheckoutContent'] =  true;
			}
        } catch (LocalizedException $e) {
			$result['success'] = false;
			$result['messages'] = $e->getMessage();
        } catch (\Exception $e) {
            $this->logger->critical($e);
			$result['success'] = false;
			$result['messages'] = $e->getMessage();
        }
		$cart = $this->_objectManager->get('Magento\Checkout\Model\Cart');
		$result['isAddToCartBtn'] =   (!isset($params['isCheckoutPage']) && $cart->getItemsCount()) ? true : false ;
		return $this->_jsonResponse($result);
    }

    /**
     * Compile JSON response
     *
     * @param string $error
     * @return Http
     */
	
	protected function _jsonResponse($result)
    {
        return $this->getResponse()->representJson(
             $this->jsonHelper->jsonEncode($result)
        );
    }
	
    protected function jsonResponse($error = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($this->sidebar->getResponseData($error))
        );
    }
}
