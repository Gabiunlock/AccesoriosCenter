<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuItems;

use Magento\Backend\App\Action;

class Save extends Action
{
	public function createMenuItems(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function createMenuItemsCollection(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\ResourceModel\MenuItems\Collection');
	}

	public function getFilterData($data, $typeFilter){
		$new = '';
		if($typeFilter == 'text'){
			$new = strip_tags(trim($data));
		}
		return $new;
	}

	public function setDepth($model){
		try {
			if($model->getOrderItem()){
				$itemId = $model->getOrderItem();
				$menuItems = $this->createMenuItems();
				$modelMenuitems = $menuItems->load($itemId);
				$data = $modelMenuitems->getData();
				if($model->getPositionItem() == \Sm\MegaMenu\Model\Config\Source\PositionItem::AFTER){		//after item:
					$depth =  intval($data['depth']);
				}
				elseif($model->getPositionItem() == \Sm\MegaMenu\Model\Config\Source\PositionItem::BEFORE){		//before item:
					$depth =  intval($data['depth']);
				}
			}
			else{
				$itemId = $model->getParentId();
				$menuItems = $this->createMenuItems();
				$modelMenuitems = $menuItems->load($itemId);
				$data = $modelMenuitems->getData();
				$depth =  intval($data['depth'])+1;
			}
			$model->setData('depth', $depth);
			return true;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return false;
		}
	}

	public function setPrioritiesItems($model){
		$menuItems = $this->createMenuItems();
		$menuItemsOrderOld = $this->createMenuItems();
		$menuItemsCollection = $this->createMenuItemsCollection();

		$id = $model->getItemsId();
		$parentId = $model->getParentId();
		$orderItem = $model->getOrderItem();
		$positionItem = $model->getPositionItem();
		$groupId = $model->getGroupId();

		$itemsOld = $menuItemsOrderOld->load($orderItem);
		$parentIdOld = $itemsOld->getParentId();

		$prioritiesOrder = $menuItemsCollection->getPrioritiesParent($orderItem, $groupId);
		$allItems = $menuItemsCollection->getAllItemsMinusItemsId($id, $parentId, $groupId);
		$allItemsOrderOld = $menuItemsCollection->getAllItemsMinusItemsId($orderItem, $parentIdOld, $groupId);
		if ($positionItem == 1)
		{
			$menuItems->setPrioritiesByNewItems($id , $allItems, $groupId, $prioritiesOrder);
			$menuItems->setPrioritiesByNewItems($orderItem , $allItemsOrderOld, $groupId, $prioritiesOrder+1);
		}
		else
		{
			$menuItems->setPrioritiesByNewItems($id , $allItems, $groupId, $prioritiesOrder+1);
		}
	}
	
	/**
	 * Save action
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		$id = $this->getRequest()->getParam('items_id');
		$data = $this->getRequest()->getPostValue();
		if ($data['form_key'])
			unset($data['form_key']);

		$data['title'] = $this->getFilterData($data['title'], 'text');
		$data['description'] = $this->getFilterData($data['description'], 'text');
		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultRedirectFactory->create();

		if ($data) {
			$menuItems = $this->createMenuItems();
			$model = $menuItems->load($id);
			if (!$model->getId() && $id) {
				$this->messageManager->addError(__('This items no longer exists.'));
				return $resultRedirect->setPath('*/*/');
			}

			// init model and set data
			$model->setData($data);
			$this->setDepth($model);
			// try to save it
			try {
				// save the data
				$model->save();
				if($model->getItemsId())
				{
					$this->setPrioritiesItems($model);
				}
				// display success message
				$this->messageManager->addSuccess(__('You saved the items.'));
				// clear previously saved data from session
				$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

				// check if 'Save and Continue'
				if ($this->getRequest()->getParam('back')) {
					return $resultRedirect->setPath('*/*/edit', [
						'gid' => $model->getGroupId(),
						'id'  => $model->getItemsId()
					]);
				}
				// go to edit menu group
				return $resultRedirect->setPath('*/menugroup/edit',[
					'id'  => $model->getGroupId(),
					'activeTab' => 'menugroup'
				]);
			}
			catch (\Exception $e)
			{
				// display error message
				$this->messageManager->addError($e->getMessage());
				// save data in session
				$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
				// redirect to edit form
				return $resultRedirect->setPath('*/*/edit', ['gid' => $model->getGroupId()]);
			}
		}
		return $resultRedirect->setPath('*/*/');
	}
}