<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuItems;

class Delete extends \Magento\Backend\App\Action
{
	public function createMenuItems(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function deleteItems($model)
	{
		$groupId = $model->getGroupId();
		$id = $model->getItemsId();
		$menuItems = $this->createMenuItems();
		$menuItems->deleteNode($id, $groupId);
	}

	/**
	 * Delete action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute()
	{
		// check if we know what should be deleted
		$id = $this->getRequest()->getParam('id');
		$gid = $this->getRequest()->getParam('gid');
		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultRedirectFactory->create();
		if ($id) {
			try {
				// init model and delete
				$model = $this->createMenuItems();
				$model->load($id);
				if($model->getItemsId())
				{
					$this->deleteItems($model);
				}
				// display success message
				$this->messageManager->addSuccess(__('You deleted the items.'));
				// go to grid
				return $resultRedirect->setPath('*/*/newaction', [
					'gid' => $gid,
					'activeTab' => 'menuitems'
				]);
			}
			catch (\Exception $e)
			{
				// display error message
				$this->messageManager->addError($e->getMessage());
				// go back to edit form
				return $resultRedirect->setPath('*/*/newaction', [
					'gid' => $this->getRequest()->getParam('gid'),
					'activeTab' => 'menuitems'
				]);
			}
		}
		// display error message
		$this->messageManager->addError(__('We can\'t find a items to delete.'));
		// go to grid
		return $resultRedirect->setPath('*/*/');
	}
}