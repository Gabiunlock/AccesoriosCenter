<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\MenuGroup\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
	protected $_systemStore;

	public function __construct(
		Context $context,
		Registry $registry,
		FormFactory $formFactory,
		Store $systemStore,
		array $data = []
	) {
		$this->_systemStore = $systemStore;
		parent::__construct($context, $registry, $formFactory, $data);
	}

	protected function _prepareForm()
	{
		$model = $this->_coreRegistry->registry('megamenu_menugroup');

		/*
         * Checking if user have permissions to save information
         */
		if ($this->_isAllowedAction('Sm_MegaMenu::save')) {
			$isElementDisabled = false;
		} else {
			$isElementDisabled = true;
		}

		/** @var \Magento\Framework\Data\Form $form */
		$form = $this->_formFactory->create();

//		$form->setHtmlIdPrefix('menugroup_');
		$fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Group Information')]);

		if ($model->getId()) {
			$fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
		}

		$fieldset->addField(
			'title',
			'text',
			[
				'name' => 'title',
				'label' => __('Group Title'),
				'title' => __('Group Title'),
				'required' => true,
				'disabled' => $isElementDisabled
			]
		);

		$fieldset->addField(
			'status',
			'select',
			[
				'label'     => __('Status'),
				'title'     => __('Group Status'),
				'name'      => 'status',
				'required'  => false,
				'options'   => $model->getAvailableStatuses(),
				'disabled'  => $isElementDisabled
			]
		);
		if (!$model->getId()) {
			$model->setData('status', $isElementDisabled ? '0' : '1');
		}

		$fieldset->addField(
			'content',
			'textarea',
			[
				'label' => __('Content'),
				'title' => __('Group Content'),
				'style' => 'height:10em;',
				'name'  => 'content',
				'disabled' => $isElementDisabled
			]
		);

		$this->_eventManager->dispatch('adminhtml_menugroup_edit_tab_form_prepare_form', ['form' => $form]);

		$form->setValues($model->getData());
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
		return __('Menu Group');
	}

	/**
	 * Prepare title for tab
	 *
	 * @return \Magento\Framework\Phrase
	 */
	public function getTabTitle()
	{
		return __('Menu Group');
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