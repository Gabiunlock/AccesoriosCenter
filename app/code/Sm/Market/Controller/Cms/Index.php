<?php
/*------------------------------------------------------------------------
# SM Market
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Market\Controller\Cms;

class Index extends \Magento\Cms\Controller\Index\Index
{

    /**
     * Renders CMS Home page
     *
     * @param string|null $coreRoute
     * @return \Magento\Framework\Controller\Result\Forward
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($coreRoute = null)
    {	
		$_mobile_detect = $this->_objectManager->get('Sm\Market\Helper\MobileDetect');
		/* $deviceType = ($_mobile_detect->isMobile() ? ($_mobile_detect->isTablet() ? 'tablet' : 'phone') : 'computer'); */
		$deviceType = ($_mobile_detect->isMobile() && $_mobile_detect->isTablet() == false) ? 'phone' : 'computer';
		//var_dump($deviceType); die('here');
		
		if ($deviceType == 'computer'){
			return parent::execute();
		}else{
			$home_pageId = $this->_objectManager->get(
				'Magento\Framework\App\Config\ScopeConfigInterface'
			)->getValue(
				\Magento\Cms\Helper\Page::XML_PATH_HOME_PAGE,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
			$_config = $this->_objectManager->get('Sm\Market\Helper\Data');
			$pageId = $_config->getGeneral('home_page_mobile');
			//var_dump('CMS HOME PAGE  ---'.$home_pageId); 
			//var_dump('CMS HOME PAGE FOR MOBILE ---'.$pageId);
			$resultPage = $this->_objectManager->get('Magento\Cms\Helper\Page')->prepareResultPage($this, $pageId);
			//var_dump($resultPage); die('123123');
			if (!$resultPage) {
				/** @var \Magento\Framework\Controller\Result\Forward $resultForward */
				$resultForward = $this->resultForwardFactory->create();
				$resultForward->forward('defaultIndex');
				return $resultForward;
			}
			return  $resultPage;
		}
		
    }
}
