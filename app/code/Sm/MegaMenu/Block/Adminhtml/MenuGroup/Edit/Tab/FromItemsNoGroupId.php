<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\MenuGroup\Edit\Tab;

class FromItemsNoGroupId extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
	protected function _prepareForm()
	{
		/** @var \Magento\Framework\Data\Form $form */
		$form = $this->_formFactory->create();

		$form->setHtmlIdPrefix('menugroup_');
		$fieldset = $form->addFieldset('items_fieldset', ['legend' => __('New Group No Items')]);

		$this->setForm($form);
		parent::_prepareForm();
	}

	/**
	 * Prepare label for tab
	 *
	 * @return \Magento\Framework\Phrase
	 */
	public function getTabLabel()
	{
		return __('Menu Items No Group Id');
	}

	/**
	 * Prepare title for tab
	 *
	 * @return \Magento\Framework\Phrase
	 */
	public function getTabTitle()
	{
		return __('Menu Items No Group Id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function canShowTab()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isHidden()
	{
		return false;
	}

	/**
	 * Check permission for passed action
	 *
	 * @param string $resourceId
	 * @return bool
	 */
	protected function _isAllowedAction($resourceId)
	{
		return $this->_authorization->isAllowed($resourceId);
	}
}