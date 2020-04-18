<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\ResourceModel\MenuItems;

use \Magento\Framework\Registry;
use Magento\Framework\App\Action\Context as ActionContext;
use Sm\MegaMenu\Api\Data\MenuItemsInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Model\Context as FrameContext;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init('Sm\MegaMenu\Model\MenuItems', 'Sm\MegaMenu\Model\ResourceModel\MenuItems');
	}

	public function getNodesByGroupId($prefix, $groupId)
	{
		$this->getSelect()->columns(
			'CONCAT(REPEAT( "'.$prefix.' ", main_table.depth) , main_table.title) AS title'
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->group(
			'main_table.items_id'
		)->order(
			'main_table.items_id'
		);
		return $this->getItems();
	}

	public function getChildrenItems($depth_child_directly, $parent_id, $groupId){
		$this->getSelect()->where(
			'main_table.depth = ?',
			(int)$depth_child_directly
		)->where(
			'main_table.parent_id = ?',
			(int)$parent_id
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->group(
			'main_table.items_id'
		)->order(
			'main_table.priorities'
		);
		return $this->getData();
	}

	public function getItemsByGroupId($groupId, $level_start)
	{
		$this->getSelect()->where(
			'main_table.depth = ?',
			(int)$level_start
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->group(
			'main_table.items_id'
		)->order(
			'main_table.priorities'
		);
		return $this->getData();
	}

	public function getItemsByLv($groupId, $level_start, $status_child)
	{
		$this->getSelect()->where(
			'main_table.depth = ?',
			(int)$level_start
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->where(
			'main_table.status = ?',
			(int)$status_child
		)->group(
			'main_table.items_id'
		)->order(
			'main_table.priorities'
		);
		return $this->getData();
	}

	public function getAllItemsFirstByGroupId($tableName, $groupId, $level_start, $status_child)
	{
		$this->getSelect()->join(
			['parent' => $tableName], '', []
		)->where(
			'main_table.depth = ?',
			(int)$level_start
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->where(
			'parent.group_id = ?',
			(int)$groupId
		)->where(
			'main_table.status = ?',
			(int)$status_child
		)->group(
			'main_table.items_id'
		)->order(
			'main_table.priorities'
		);
		return $this->getData();
	}

	public function getAllActivedItems($statusChild, $type, $itemId, $groupId){
		$this->getSelect()->where(
			'main_table.type = ?',
			(int)$type
		)->where(
			'main_table.data_type = ?',
			(int)$itemId
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->where(
			'main_table.status = ?',
			(int)$statusChild
		)->group(
			'main_table.items_id'
		)->order(
			'main_table.priorities'
		);
		return $this->getData();
	}

	public function getDeleteItemsByGroup($tableName, $groupId)
	{
		$query = "
			DELETE FROM `{$tableName}` WHERE group_id ='{$groupId}';
		";
		return $this->getConnection()->query($query);
	}

	public function getPrioritiesParent($orderItem, $groupId){
		$this->getSelect()->where(
			'main_table.items_id = ?',
			(int)$orderItem
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		);
		$data = $this->getData();
		if (count($data) > 0)
		{
			foreach ($data as $p)
			{
				return $p['priorities'];
			}
		}
		return false;
	}

	public function getAllItemsByItemsId($parentId, $groupId)
	{
		$this->getSelect()->where(
			'main_table.parent_id = ?',
			(int)$parentId
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->order(
			'main_table.items_id'
		);
		return $this->getData();
	}

	public function getAllItemsByItemsIdEnabled($parentId, $groupId, $status_child)
	{
		$this->getSelect()->where(
			'main_table.parent_id = ?',
			(int)$parentId
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->where(
			'main_table.status = ?',
			(int)$status_child
		)->order(
			'main_table.priorities'
		);
		return $this->getData();
	}

	public function getAllItemsMinusItemsId($id, $parentId, $groupId)
	{
		$this->getSelect()->where(
			'main_table.items_id != ?',
			(int)$id
		)->where(
			'main_table.parent_id = ?',
			(int)$parentId
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->group(
			'main_table.items_id'
		)->order(
			'main_table.priorities'
		);
		return $this->getData();
	}

	public function setPrioritiesByNewItems($tableName, $id, $allItems, $groupId, $prioritiesOrder)
	{
		$query = "
			UPDATE `{$tableName}` SET priorities = '{$prioritiesOrder}' WHERE items_id = '{$id}' AND group_id='{$groupId}';
		";
		$this->getConnection()->query($query);
		if($allItems)
		{
			foreach($allItems as $all)
			{
				if($all['priorities'] >= $prioritiesOrder)
				{
					$priorities = $all['priorities']+1;
					$itemsId = $all['items_id'];
					$queryupdate = "
						UPDATE `{$tableName}` SET priorities = '{$priorities}' WHERE items_id = '{$itemsId}' AND group_id='{$groupId}';
					";
					$this->getConnection()->query($queryupdate);
					return true;
				}

			}
		}
		return true;
	}

	public function getChidrenItemsByItems($id, $groupId)
	{
		$this->getSelect()->where(
			'main_table.parent_id = ?',
			(int)$id
		)->where(
			'main_table.group_id = ?',
			(int)$groupId
		)->group(
			'main_table.items_id'
		)->order(
			'main_table.priorities'
		);
		return $this->getData();
	}

	public function deleteItems($tableName, $id, $groupId)
	{
		$query = "
			DELETE FROM `{$tableName}` WHERE items_id = '{$id}' AND group_id ='{$groupId}';
		";
		return $this->getConnection()->query($query);
	}

	public function setEnableAll($mainTable, $groupId)
	{
		$query = "
			UPDATE `{$mainTable}` SET status = 1 WHERE group_id='{$groupId}';
		";
		return $this->getConnection()->query($query);
	}

	public function setDisableAll($mainTable, $groupId)
	{
		$query = "
			UPDATE `{$mainTable}` SET status = 2 WHERE group_id='{$groupId}';
		";
		return $this->getConnection()->query($query);
	}
}