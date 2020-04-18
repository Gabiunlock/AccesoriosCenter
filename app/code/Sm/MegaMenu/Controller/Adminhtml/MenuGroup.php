<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

abstract class MenuGroup extends \Magento\Backend\App\Action
{
	protected $_coreRegistry = null;

	public function __construct(
		Context $context,
		Registry $registry
	)
	{
		$this->_coreRegistry = $registry;
		parent::__construct($context);
	}

	/**
	 * Init page
	 *
	 * @param \Magento\Backend\Model\View\Result\Page $resultPage
	 * @return \Magento\Backend\Model\View\Result\Page
	 */
	protected function _initAction($resultPage)
	{
		$resultPage->setActiveMenu('Sm_MegaMenu::megamenu_menugroup');
		$resultPage->addBreadcrumb(__('Manager Menu'), __('Manager Menu'))
			->addBreadcrumb(__('Menu Groups'), __('Menu Groups'));
		return $resultPage;
	}

	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Sm_MegaMenu::save');
	}
}