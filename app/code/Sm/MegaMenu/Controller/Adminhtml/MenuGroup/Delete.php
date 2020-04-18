<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuGroup;

class Delete extends \Sm\MegaMenu\Controller\Adminhtml\MenuGroup
{
	/**
	 * Delete action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute()
	{
		// check if we know what should be deleted
		$id = $this->getRequest()->getParam('group_id');
		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultRedirectFactory->create();
		if ($id) {
			try {
				// init model and delete
				$model = $this->_objectManager->create('Sm\MegaMenu\Model\MenuGroup');
				$menuItems = $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
				$model->load($id);
				$model->delete();
				$menuItems->getDeleteItemsByGroup($id);
				// display success message
				$this->messageManager->addSuccess(__('You deleted the group.'));
				// go to grid
				return $resultRedirect->setPath('*/*/');
			}
			catch (\Exception $e)
			{
				// display error message
				$this->messageManager->addError($e->getMessage());
				// go back to edit form
				return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
			}
		}
		// display error message
		$this->messageManager->addError(__('We can\'t find a group to delete.'));
		// go to grid
		return $resultRedirect->setPath('*/*/');
	}
}