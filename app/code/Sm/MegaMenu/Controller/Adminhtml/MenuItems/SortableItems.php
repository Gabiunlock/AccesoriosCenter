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
use Sm\MegaMenu\Helper\Defaults;
use Magento\Framework\View\Result\PageFactory;

class SortableItems extends \Magento\Backend\App\Action
{
	protected $resultPageFactory;
	protected $_defaults;

	public function __construct(
		Action\Context $context,
		Defaults $defaults,
		PageFactory $resultPageFactory
	)
	{
		$this->_defaults = $defaults->get();
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	public function createMenuItems(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function getIdParent($gid){
		$menuItems= $this->createMenuItems();
		$status_child = $this->_defaults['isenabled'];
		$level_start = $this->_defaults['start_level'];
		$items = $menuItems->getIdParent($gid, $level_start, $status_child);
		$data = $items[0];
		return $data;
	}

	public function getOrderItems($child)
	{
		$arr = [];
		foreach ($child as $chi)
		{
			$arr[] = $chi->items_id;
		}
		if (count($arr))
		{
			return $arr;
		}
	}

	public function execute()
	{
		$gid = $this->getRequest()->getParam('gid');
		$data_items_by_group_id = $this->getIdParent($gid);
		$parent_id = $data_items_by_group_id['parent_id'];
		if (json_decode($this->getRequest()->getParam('element')))
		{
			$data = json_decode($this->getRequest()->getParam('element'));
			$this->getSortableItems($data, 1, $parent_id, 0);
		}
	}

	public function getSortableItems($data, $depth_item, $parent_id, $order)
	{
		$count = 0;
		$menuItems= $this->createMenuItems();
		$count_items = 0;
		$orderitems = [];
		$resultRedirect = $this->resultRedirectFactory->create();
		if ($this->getOrderItems($data))
		{
			$orderitems = $this->getOrderItems($data);
			$count_items = count($orderitems);
		}
		if ($data)
		{
			foreach ($data as $dat)
			{
				$count++;
				$id = $dat->items_id;
				$depth = $depth_item;
				$menuItems->load($id);
				$menuItems->setDepth($depth);
				$menuItems->setParentId((string)$parent_id);
				if (($count_items>0) && (count($orderitems)>0))
				{
					$order_items = 0;
					for($i = 0; $i < $count_items; $i++)
					{
						if($i>0)
							$p = $i-1;
						else
							$p = 0;

						if($orderitems[$i] == $id)
						{
							if ($orderitems[$p])
							{
								$order_items = $orderitems[$p];
							}
							/*else
							{
								$order_items = 0;
							}*/
						}
					}
					$menuItems->setOrderItem((string)$order_items);
					$menuItems->setPositionItem(2);
					$menuItems->setPriorities($count);
					try
					{
						if (isset($dat->children) && count($dat->children)>0)
						{
							$this->getSortableChildrenItems($dat->children, $depth+1, $id, $order_items, $count);
						}
						$menuItems->save();
					} catch (\Exception $e) {
						$this->messageManager->addError($e->getMessage());
						return $resultRedirect->setPath('*/*/edit', [
							'gid' => $menuItems->getGroupId(),
							'id'  => $menuItems->getItemsId(),
							'activeTab' => 'menuitems'
						]);
					}
				}else
					return '';
			}
		}
	}

	public function getSortableChildrenItems($data, $depth_item, $parent_id, $order, $count)
	{
		$menuItems= $this->createMenuItems();
		$count_items = 0;
		$orderitems = [];
		$resultRedirect = $this->resultRedirectFactory->create();
		if ($this->getOrderItems($data))
		{
			$orderitems = $this->getOrderItems($data);
			$count_items = count($orderitems);
		}
		foreach ($data as $dat)
		{
			$count++;
			$id = $dat->items_id;
			$depth = $depth_item;
			$menuItems->load($id);
			$menuItems->setDepth($depth);
			$menuItems->setParentId((string)$parent_id);
			if (($count_items>0) && (count($orderitems)>0))
			{
				$order_items = 0;
				for($i = 0; $i < $count_items; $i++)
				{
					if($i>0)
						$p = $i-1;
					else
						$p = 0;

					if($orderitems[$i] == $id)
					{
						if ($orderitems[$p])
						{
							$order_items = $orderitems[$p];
						}
						else
						{
							$order_items = 0;
						}
					}
				}
				$menuItems->setOrderItem((string)$order_items);
				$menuItems->setPositionItem(2);
				$menuItems->setPriorities($count);
				try
				{
					if (isset($dat->children) && count($dat->children)>0)
					{
						$this->getSortableChildrenItems($dat->children, $depth+1, $id, $order_items, $count);
					}
					$menuItems->save();
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