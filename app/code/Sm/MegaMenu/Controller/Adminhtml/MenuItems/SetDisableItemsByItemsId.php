<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuItems;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class SetDisableItemsByItemsId extends \Magento\Backend\App\Action
{
	protected $resultPageFactory;

	public function __construct(
		Context $context,
		PageFactory $resultPageFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	public function createMenuItems(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function createMenuItemsCollection(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\ResourceModel\MenuItems\Collection');
	}

	public function setDisableChildItemsByParentId($allItems, $gid, $id)
	{
		$menuItemsCollection = $this->createMenuItemsCollection();
		try
		{
			foreach ($allItems as $allItem)
			{
				$menuItems = $this->createMenuItems();
				$groupId = $allItem['group_id'];
				$parentId = $allItem['items_id'];
				$all_item = $menuItemsCollection->getAllItemsByItemsId($parentId, $groupId);
				$menuItems->setData($allItem);
				$menuItems->setStatus(2);
				$menuItems->save();
				if (count($all_item))
				{
					$this->setDisableChildItemsByParentId($all_item, $gid, $id);
				}
			}
		} catch(\Exception $e)
		{
			$this->messageManager->addError($e->getMessage());
		}
	}

	public function execute()
	{
		$gid = $this->getRequest()->getParam('gid');
		$id = $this->getRequest()->getParam('id');
		if ($id > 0) {
			$resultRedirect = $this->resultRedirectFactory->create();
			$menuItems = $this->createMenuItems();
			$menuItemsCollection = $this->createMenuItemsCollection();
			$items = $menuItems->load($id);
			if ($items->getItemsId()) {
				$groupId = $items->getGroupId();
				$parentId = $items->getItemsId();
				$all_item = $menuItemsCollection->getAllItemsByItemsId($parentId, $groupId);
				$data = $items->getData();
				$menuItems->setData($data);
				$menuItems->setStatus(2);
				try {
					$menuItems->save();
					if (count($all_item)) {
						$this->setDisableChildItemsByParentId($all_item, $gid, $id);
					}
					$this->messageManager->addSuccess(__('You disable items was successfully.'));
					return $resultRedirect->setPath('*/*/edit', [
						'gid' => $menuItems->getGroupId(),
						'id'  => $menuItems->getItemsId(),
						'activeTab' => 'menuitems'
					]);
				} catch (\Exception $e) {
					$this->messageManager->addError($e->getMessage());
					return $resultRedirect->setPath('*/*/edit', [
						'gid' => $menuItems->getGroupId(),
						'id'  => $menuItems->getItemsId(),
						'activeTab' => 'menuitems'
					]);
				}
			}
		}
	}
}