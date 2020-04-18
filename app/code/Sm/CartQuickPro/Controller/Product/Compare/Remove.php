<?php
/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace  Sm\CartQuickPro\Controller\Product\Compare;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;

class Remove extends \Magento\Catalog\Controller\Product\Compare implements HttpPostActionInterface
{
     /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
	
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product\Compare\ItemFactory $compareItemFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        PageFactory $resultPageFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->_storeManager = $storeManager;
        $this->_compareItemFactory = $compareItemFactory;
        $this->_itemCollectionFactory = $itemCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_customerVisitor = $customerVisitor;
        $this->_catalogProductCompareList = $catalogProductCompareList;
        $this->_catalogSession = $catalogSession;
        $this->_formKeyValidator = $formKeyValidator;
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        parent::__construct(
				$context, 
				$compareItemFactory,
				$itemCollectionFactory,
				$customerSession,
				$customerVisitor,
				$catalogProductCompareList,
				$catalogSession,
				$storeManager,
				$formKeyValidator,
				$resultPageFactory,
				$productRepository
		);
		$this->_objectManager = $context->getObjectManager();
    }
	
    /**
     * Remove item from compare list
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $productId = (int)$this->getRequest()->getParam('product');
		$result = [];
		$params = $this->getRequest()->getParams();
        if ($productId) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                $product = $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                $product = null;
            }

            if ($product) {
                /** @var $item \Magento\Catalog\Model\Product\Compare\Item */
                $item = $this->_compareItemFactory->create();
                if ($this->_customerSession->isLoggedIn()) {
                    $item->setCustomerId($this->_customerSession->getCustomerId());
                } elseif ($this->_customerId) {
                    $item->setCustomerId($this->_customerId);
                } else {
                    $item->addVisitorId($this->_customerVisitor->getId());
                }

                $item->loadByProduct($product);
                /** @var $helper \Magento\Catalog\Helper\Product\Compare */
                $helper = $this->_objectManager->get('Magento\Catalog\Helper\Product\Compare');
                if ($item->getId()) {
                    $item->delete();
                    $productName = $this->_objectManager->get('Magento\Framework\Escaper')
                        ->escapeHtml($product->getName());
                    $this->messageManager->addSuccess(
                        __('You removed product %1 from the comparison list.', $productName)
                    );
                    $this->_eventManager->dispatch(
                        'catalog_product_compare_remove_product',
                        ['product' => $item]
                    );
                    $helper->calculate();
					if (isset($params['isComparePage'])){
						$_layout  = $this->_objectManager->get('Magento\Framework\View\LayoutInterface');
						$_layout->getUpdate()->load(['cartquickpro_product_compare_remove']);
						$_layout->generateXml();
						$_output = $_layout->getOutput();
						$result['content'] = $_output;
						$result['isComparePageContent'] =  true;
					}
					$result['messages'] =  __('You removed product %1 from the comparison list.', $productName);
					$result['success'] = true;
					
                }
            }
        }

		$compare = $this->_objectManager->get('Magento\Catalog\Helper\Product\Compare');
        $result['isCompareBtn'] =   (!isset($params['isComparePage']) && $compare->getItemCount()) ? true : false ;
		return $this->_jsonResponse($result);
    }
	
	protected function _jsonResponse($result)
    {
        return $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
        );
    }
}
