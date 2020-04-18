<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml;

class MenuGroup extends \Magento\Backend\Block\Widget\Grid\Container
{

	protected function _construct()
	{
		$this->_blockGroup = 'Sm_MegaMenu';
		$this->_controller = 'adminhtml_menuGroup';
		$this->_headerText = __('Manager Menu');
		parent::_construct();

		if ($this->_isAllowedAction('Sm_MegaMenu::save')) {
			$this->buttonList->update('add', 'label', __('Add New Menu'));
		} else {
			$this->buttonList->remove('add');
		}
	}

	protected function _isAllowedAction($resourceId)
	{
		return $this->_authorization->isAllowed($resourceId);
	}
}