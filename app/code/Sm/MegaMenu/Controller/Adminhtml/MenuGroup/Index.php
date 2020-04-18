<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuGroup;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Sm\MegaMenu\Controller\Adminhtml\MenuGroup
{
	protected $resultPageFactory;

	public function __construct(
		Context $context,
		Registry $coreRegistry,
		PageFactory $resultPageFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context, $coreRegistry);
	}

	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$this->_initAction($resultPage)->getConfig()->getTitle()->prepend(__('Manager Menu'));
		return $resultPage;
	}
}