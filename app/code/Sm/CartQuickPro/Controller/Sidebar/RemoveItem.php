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


class RemoveItem extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Sidebar
     */
    protected $sidebar;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Sidebar $sidebar
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Sidebar $sidebar,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->sidebar = $sidebar;
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->resultPageFactory = $resultPageFactory;
        $this->_objectManager = $context->getObjectManager();
        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
		$result = [];
        if (!$this->getFormKeyValidator()->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/cart/');
        }
        $itemId = (int)$this->getRequest()->getParam('item_id');
		$params = $this->getRequest()->getParams();  
        try {
            $this->sidebar->checkQuoteItem($itemId);
            $this->sidebar->removeQuoteItem($itemId);

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
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
			$result['success'] = false;
			$result['messages'] =  $e->getMessage();
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $result['success'] = false;
			$result['messages'] =  $e->getMessage();
        }
		$cart = $this->_objectManager->get('Magento\Checkout\Model\Cart');
		$result['isAddToCartBtn'] =   (!isset($params['isCheckoutPage']) && $cart->getSummaryQty()) ? true : false ;
		return $this->_jsonResponse($result);
    }

    /**
     * Compile JSON response
     *
     * @param string $error
     * @return \Magento\Framework\App\Response\Http
     */
	protected function _jsonResponse($result)
    {
        return $this->getResponse()->representJson(
             $this->jsonHelper->jsonEncode($result)
        );
    } 
	 
    protected function jsonResponse($error = '')
    {
        $response = $this->sidebar->getResponseData($error);

        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }

    /**
     * @return \Magento\Framework\Data\Form\FormKey\Validator
     * @deprecated
     */
    private function getFormKeyValidator()
    {
        if (!$this->formKeyValidator) {
            $this->formKeyValidator = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\Data\Form\FormKey\Validator::class);
        }
        return $this->formKeyValidator;
    }
}
