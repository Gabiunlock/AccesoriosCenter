<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\MenuItems;

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

	protected function _construct()
	{
		$this->_blockGroup = 'Sm_MegaMenu';
		$this->_controller = 'adminhtml_menuGroup';
		$this->_mode = 'edit';

		$items = $this->_coreRegistry->registry('megamenu_menuitems');
		$group = $this->_coreRegistry->registry('megamenu_menugroup');
		$id = $this->getRequest()->getParam('id');

		$backUrl = $this->getUrl('*/menugroup/edit',
			[
				'id' => ($group->getGroupId() ? $group->getGroupId() : $this->getRequest()->getParam('gid'))
			]
		);

		$newUrl = $this->getUrl('*/menuitems/newaction',
			[
				'gid' => ($group->getGroupId() ? $group->getGroupId() : $this->getRequest()->getParam('gid'))
			]
		);

		$deleteUrl = $this->getUrl('*/menuitems/delete',
			[
				'gid'   => ($group->getGroupId() ? $group->getGroupId() : $this->getRequest()->getParam('gid')),
				'id'    => ($items->getItemsId() ? $items->getItemsId() : $this->getRequest()->getParam('id'))
			]
		);

		$duplicateUrl = $this->getUrl('*/menuitems/duplicate',
			[
				'gid'   => ($group->getGroupId() ? $group->getGroupId() : $this->getRequest()->getParam('gid')),
				'id'    => ($items->getItemsId() ? $items->getItemsId() : $this->getRequest()->getParam('id'))
			]
		);

		parent::_construct();
		if ($this->_isAllowedAction('Sm_MegaMenu::save')) {
			$this->buttonList->update('save', 'label', __('Save Items'));
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
		$this->buttonList->remove('back');
		$this->addButton(
			'backgroup',
			[
				'label' => __('Back'),
				'class' => 'back back-form',
				'onclick' => "setLocation('{$backUrl}')"
			],
			-1
		);

		$this->buttonList->remove('reset');
		$this->addButton(
			'resetitems',
			[
				'label' => __('Reset'),
				'onclick' => 'setLocation(window.location.href)',
				'class' => 'reset reset-form'
			],
			-1
		);

		if ($this->_isAllowedAction('Sm_MegaMenu::menugroup_delete')) {
			$this->buttonList->remove('delete');
		} else {
			$this->buttonList->remove('delete');
		}

		if ($items->getItemsId() || $id) {
			$this->buttonList->add(
				'deleteitems',
				[
					'label' => __('Delete Items'),
					'class' => 'show-hide delete-items',
					'onclick' => "deleteConfirm('Are you sure you want to do this?', '{$deleteUrl}')"
				],
				-100
			);
			$this->buttonList->add(
				'duplicate',
				[
					'label' => __('Duplicate Items'),
					'class' => 'show-hide duplicate-items',
					'onclick' => "deleteConfirm('Are you sure you want to do this?', '{$duplicateUrl}')"
				],
				-100
			);
			$this->buttonList->add(
				'add_new',
				[
					'label' => __('Add New Items'),
					'class' => 'show-hide add-new-items',
					'onclick' => "setLocation('{$newUrl}')"
				],
				-100
			);
		} else {
			$this->buttonList->remove('deleteitems');
			$this->buttonList->remove('duplicate');
			$this->buttonList->remove('add_new');
		}
	}

	public function getHeaderText()
	{
		if ($this->_coreRegistry->registry('megamenu_menuitems')->getItemsId()) {
			return __("Edit Items '%1'", $this->escapeHtml($this->_coreRegistry->registry('megamenu_menuitems')->getTitle()));
		} else {
			return __('Add New Items');
		}
	}

	protected function _isAllowedAction($resourceId)
	{
		return $this->_authorization->isAllowed($resourceId);
	}

	protected function _prepareLayout()
	{
		/* $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('menuitems_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'menuitems_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'menuitems_content');
                }
            }
        "; */
		return parent::_prepareLayout();
	}
}