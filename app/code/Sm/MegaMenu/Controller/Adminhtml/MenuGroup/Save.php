<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuGroup;

use Magento\Backend\App\Action;

class Save extends Action
{
	/**
	 * Save action
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */

	public function createMenuGroup()
	{
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuGroup');
	}

	public function createMenuItems()
	{
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function execute()
	{
		$data = $this->getRequest()->getPostValue();
		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultRedirectFactory->create();
		if ($data) {
			$id = $this->getRequest()->getParam('group_id');
			$menuGroup = $this->createMenuGroup();
			$menuItems = $this->createMenuItems();
			$model = $menuGroup->load($id);
			if (!$model->getId() && $id) {
				$this->messageManager->addError(__('This group no longer exists.'));
				return $resultRedirect->setPath('*/*/');
			}

			// init model and set data
			$model->setData($data);

			// try to save it
			try {
				// save the data
				$model->save();
				if(!$id)
				{
					$data = [
						'title' => __('Root['.$model->getTitle().']'),
						'group_id' => $model->getGroupId(),
						'status' => 1,
						'description' => '',
						'depth' => 0,
						'parent_id' => 0,
						'order_item' => 0,
						'data_type' => '',
						'content' => '',
						'custom_class' => '',
						'limit_category' => ''
					];
					// $this->_eventManager->dispatch('megamenu_menugroup_save_after', ['menugroup' => $model]);
					$menuItems->setData($data);
					try{
						$menuItems->save();
					}
					catch (\Exception $e)
					{
						$this->messageManager->addError($e->getMessage());
						return;
					}

				}
				// display success message
				$this->messageManager->addSuccess(__('You saved the group.'));
				// clear previously saved data from session
				$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

				// check if 'Save and Continue'
				if ($this->getRequest()->getParam('back')) {
					return $resultRedirect->setPath('*/*/edit', [
						'id' => $model->getGroupId(),
						'activeTab' => $this->getRequest()->getParam('activeTab')
					]);
				}
				// go to grid
				return $resultRedirect->setPath('*/*/');
			}
			catch (\Exception $e)
			{
				// display error message
				$this->messageManager->addError($e->getMessage());
				// save data in session
				$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
				// redirect to edit form
				return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('gid')]);
			}
		}
		return $resultRedirect->setPath('*/*/');
	}
}