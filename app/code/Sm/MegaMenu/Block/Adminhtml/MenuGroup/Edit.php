<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\MenuGroup;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
	protected $_coreRegistry = null;

	public function __construct(
		Context $context,
		Registry $registry,
		array $data = []
	)
	{
		$this->_coreRegistry = $registry;
		parent::__construct($context, $data);
	}

	protected function _construct(){
		$this->_objectId = 'group_id';
		$this->_blockGroup = 'Sm_MegaMenu';
		$this->_controller = 'adminhtml_menuGroup';

		parent::_construct();
		if ($this->_isAllowedAction('Sm_MegaMenu::save')) {
			$this->buttonList->update('save', 'label', __('Save Groups'));
			$this->buttonList->add(
				'saveandcontinue',
				[
					'label' => __('Save and Continue Edit'),
					'class' => 'save save-form',
					'data_attribute' => [
						'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
					]
				],
				-100
			);
		} else {
			$this->buttonList->remove('save');
		}

		if ($this->_isAllowedAction('Sm_MegaMenu::menugroup_delete')) {
			$this->buttonList->update('delete', 'label', __('Delete Groups'));
		} else {
			$this->buttonList->remove('delete');
		}

		$this->buttonList->remove('back');
		$this->addButton(
			'back',
			[
				'label' => __('Back'),
				'onclick' => 'setLocation(\'' . $this->getBackUrl() . '\')',
				'class' => 'back back-form'
			],
			-1
		);

		$this->buttonList->remove('reset');
		$this->addButton(
			'reset',
			[
				'label' => __('Reset'),
				'onclick' => 'setLocation(window.location.href)',
				'class' => 'reset reset-form'
			],
			-1
		);
	}

	public function getHeaderText()
	{
		if ($this->_coreRegistry->registry('megamenu_menugroup')->getId()) {
			return __("Edit Groups '%1'", $this->escapeHtml($this->_coreRegistry->registry('megamenu_menugroup')->getTitle()));
		} else {
			return __('Add New Groups');
		}
	}

	protected function _getSaveAndContinueUrl()
	{
		return $this->getUrl('megamenu/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
	}

	protected function _isAllowedAction($resourceId)
	{
		return $this->_authorization->isAllowed($resourceId);
	}

	protected function _prepareLayout()
	{
		$this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('menugroup_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'menugroup_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'menugroup_content');
                }
            }
        ";
		return parent::_prepareLayout();
	}
}