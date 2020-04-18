<?php
/**
 *
 * SM Listing Tabs - Version 2.8.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace Sm\ListingTabs\Controller\Index;

use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action {
	/** @var  \Magento\Framework\View\Result\Page */
	protected $resultPageFactory;
	protected $jsonEncoder;
	protected $_layout;
	protected $response;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    private $cacheInterFace;

    private $_listingTabs;
	/**
	 * @param \Magento\Framework\App\Action\Context $context
	 */
	public function __construct(
		Context $context, 
		PageFactory $resultPageFactory,
		\Magento\Framework\Json\EncoderInterface $jsonEncoder,
		\Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
		\Magento\Framework\App\Response\Http $response,
        \Magento\Framework\App\CacheInterface $cacheInterFace,
        \Sm\ListingTabs\Block\ListingTabs $listingTabs
    )
	{
		$this->resultPageFactory = $resultPageFactory;
		$this->jsonEncoder = $jsonEncoder;
		$this->_layout = $layout;
		$this->response = $response;
        $this->cacheInterFace = $cacheInterFace;
        $this->_listingTabs = $listingTabs;
		parent::__construct($context);
	}

	/**
	 * Blog Index, shows a list of recent blog posts.
	 *
	 * @return \Magento\Framework\View\Result\PageFactory
	 */
	public function execute()
	{
		$isAjax = $this->getRequest()->isAjax();
		if ($isAjax){
			$layout =  $this->_layout;
            $listingTabs = $this->_listingTabs;
            $template_file = "Sm_ListingTabs::default_items.phtml";
            $listingTabs->setTemplate($template_file);
            $cacheKey = $listingTabs->getCacheKey();
            $cacheData = $this->cacheInterFace->load($cacheKey);
            $result = [];
            if ($cacheData) {
                $result['items_markup'] = $cacheData;
            }else{
                $layout->getUpdate()->load(['listingtabs_index_ajax']);
				$layout->generateXml();
	            $output = $layout->getOutput();
	            $result['items_markup'] =  $output;
            }
            return $this->getResponse()->representJson(
                $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
            );
        }
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend(__('SM Listing Tabs'));
		return $resultPage;
	}
}