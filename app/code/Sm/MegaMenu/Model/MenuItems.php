<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model;

use \Magento\Framework\Registry;
use Magento\Framework\App\Action\Context as ActionContext;
use Sm\MegaMenu\Api\Data\MenuItemsInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Sm\MegaMenu\Helper\Defaults;
use Magento\Framework\Model\Context as FrameContext;
use Magento\Framework\Model\ResourceModel\Db\Context;

class MenuItems extends AbstractModel implements MenuItemsInterface
{
	/**
	 * @var \Magento\Framework\Message\ManagerInterface
	 */
	protected $messageManager;
	protected $_defaults;
	protected $_dataCollection = [];
	protected $_dataObject = [];
	protected $_resource = null;
	protected $_query;
	protected $_statusChild;
	protected $_tableName;

	public function __construct(
		EntityFactoryInterface $entityFactory,
		ActionContext $actionContext,
		Context $context,
		Registry $registry,
		Collection $collection,
		DataObject $dataObject,
		Defaults $defaults,
		FrameContext $frameContext,
		array $data = []
	)
	{
		parent::__construct($frameContext, $registry);
		$this->_dataCollection = $collection;
		$this->_dataObject = $dataObject;
		$this->_defaults = $defaults->get($data);
		$this->messageManager = $actionContext->getMessageManager();
		$this->_statusChild = \Sm\MegaMenu\Model\Config\Source\Status::STATUS_ENABLED;
		$this->_tableName = $this->_getResource()->getMainTable();
	}

	protected function _construct()
	{
		$this->_init('Sm\MegaMenu\Model\ResourceModel\MenuItems');
	}

	public function getItemsId()
	{
		return $this->getData(self::ITEMS_ID);
	}

	public function getGroupId()
	{
		return $this->getData(self::GROUP_ID);
	}

	public function getTitle()
	{
		return $this->getData(self::TITLE);
	}

	public function getStatus()
	{
		return $this->getData(self::STATUS);
	}

	public function getShowTitle()
	{
		return $this->getData(self::SHOW_TITLE);
	}

	public function getDescription()
	{
		return $this->getData(self::DESCRIPTION);
	}

	public function getAlign()
	{
		return $this->getData(self::ALIGN);
	}

	public function getDepth()
	{
		return $this->getData(self::DEPTH);
	}

	public function getColsNb()
	{
		return $this->getData(self::COLS_NB);
	}

	public function getIconUrl()
	{
		return $this->getData(self::ICON_URL);
	}

	public function getTarget()
	{
		return $this->getData(self::TARGET);
	}

	public function getType()
	{
		return $this->getData(self::TYPE);
	}

	public function getDataType()
	{
		return $this->getData(self::DATA_TYPE);
	}

	public function getCustomClass()
	{
		return $this->getData(self::CUSTOM_CLASS);
	}

	public function getParentId()
	{
		return $this->getData(self::PARENT_ID);
	}

	public function getOrderItem()
	{
		return $this->getData(self::ORDER_ITEM);
	}

	public function getPositionItem()
	{
		return $this->getData(self::POSITION_ITEM);
	}

	public function getPriorities()
	{
		return $this->getData(self::PRIORITIES);
	}

	public function getContent()
	{
		return $this->getData(self::CONTENT);
	}

	public function getShowImageProduct()
	{
		return $this->getData(self::SHOW_IMAGE_PRODUCT);
	}

	public function getShowTitleProduct()
	{
		return $this->getData(self::SHOW_TITLE_PRODUCT);
	}

	public function getShowRatingProduct()
	{
		return $this->getData(self::SHOW_RATING_PRODUCT);
	}

	public function getShowPriceProduct()
	{
		return $this->getData(self::SHOW_PRICE_PRODUCT);
	}

	public function getShowTitleCategory()
	{
		return $this->getData(self::SHOW_TITLE_CATEGORY);
	}

	public function getLimitCategory()
	{
		return $this->getData(self::LIMIT_CATEGORY);
	}

	public function setItemsId($itemsId)
	{
		return $this->setData(self::ITEMS_ID, $itemsId);
	}

	public function setGroupId($groupId)
	{
		return $this->setData(self::GROUP_ID, $groupId);
	}

	public function setTitle($title)
	{
		return $this->setData(self::TITLE, $title);
	}

	public function setStatus($status)
	{
		return $this->setData(self::STATUS, $status);
	}

	public function setContent($content)
	{
		return $this->setData(self::CONTENT, $content);
	}

	public function setShowTitle($showTitle)
	{
		return $this->setData(self::SHOW_TITLE, $showTitle);
	}

	public function setDescription($desription)
	{
		return $this->setData(self::DESCRIPTION, $desription);
	}

	public function setAlign($align)
	{
		return $this->setData(self::ALIGN, $align);
	}

	public function setDepth($depth)
	{
		return $this->setData(self::DEPTH, $depth);
	}

	public function setColsNb($colsNb)
	{
		return $this->setData(self::COLS_NB, $colsNb);
	}

	public function setIconUrl($iconUrl)
	{
		return $this->setData(self::ICON_URL, $iconUrl);
	}

	public function setTarget($target)
	{
		return $this->setData(self::TARGET, $target);
	}

	public function setType($type)
	{
		return $this->setData(self::TYPE, $type);
	}

	public function setDataType($dataType)
	{
		return $this->setData(self::DATA_TYPE, $dataType);
	}

	public function setCustomClass($customClass)
	{
		return $this->setData(self::CUSTOM_CLASS, $customClass);
	}

	public function setParentId($parentId)
	{
		return $this->setData(self::PARENT_ID, $parentId);
	}

	public function setOrderItem($orderItem)
	{
		return $this->setData(self::ORDER_ITEM, $orderItem);
	}

	public function setPositionItem($positionItem)
	{
		return $this->setData(self::POSITION_ITEM, $positionItem);
	}

	public function setPriorities($priorities)
	{
		return $this->setData(self::PRIORITIES, $priorities);
	}

	public function setShowImageProduct($showImageProduct)
	{
		return $this->setData(self::SHOW_IMAGE_PRODUCT, $showImageProduct);
	}

	public function setShowTitleProduct($showTitleProduct)
	{
		return $this->setData(self::SHOW_TITLE_PRODUCT, $showTitleProduct);
	}

	public function setShowRatingProduct($showRatingProduct)
	{
		return $this->setData(self::SHOW_RATING_PRODUCT, $showRatingProduct);
	}

	public function setShowPriceProduct($showPriceProduct)
	{
		return $this->setData(self::SHOW_PRICE_PRODUCT, $showPriceProduct);
	}

	public function setShowTitleCategory($showTitleCategory)
	{
		return $this->setData(self::SHOW_TITLE_CATEGORY, $showTitleCategory);
	}

	public function setLimitCategory($limitCategory)
	{
		return $this->setData(self::LIMIT_CATEGORY, $limitCategory);
	}

	public function getNodesByGroupId($groupId, $addPrefix = true)
	{
		$prefix = ($addPrefix) ? \Sm\MegaMenu\Model\Config\Source\Prefix::PREFIX : '';
		$itemByGroupId = $this->getCollection()->getNodesByGroupId($prefix, $groupId);
		return $itemByGroupId;
	}

	public function getChildsDirectlyByItem($parent, $mode = 1)
	{
		$tableName = $this->_tableName;
		$depth_child_directly = $parent['depth'] + 1;
		$parent_id = $parent['items_id'];
		$groupId = $parent['group_id'];
		$query = "
			SELECT * FROM {$tableName}
			WHERE (depth = '{$depth_child_directly}') AND parent_id = '{$parent_id}' AND group_id ='{$groupId}'
			ORDER BY priorities ASC
		";
		try {
			$childrenItems = $this->getCollection()->getConnection()->fetchAll($query);
			return $childrenItems;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return false;
		}
		return false;
	}

	public function getIdParent($groupId, $levelStart, $statusChild)
	{
		$tableName = $this->_tableName;
		$filterStatus = "AND status ='{$statusChild}'";
		$query = "
			SELECT * FROM {$tableName}
			WHERE (depth = '{$levelStart}') AND group_id ='{$groupId}' {$filterStatus}
			ORDER BY items_id ASC
		";
		try {
			$childrenItems = $this->getCollection()->getConnection()->fetchAll($query);
			return $childrenItems;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return false;
		}
		return false;
	}

	public function getAllItemsInEqLv($item, $mode = 1, $attributes = '')
	{
		$tableName = $this->_tableName;
		$depth_child_directly = $item['depth'] + 1;
		$parent_id = $item['items_id'];
		$status_child = $this->_statusChild;
		$filter_status = "AND status ='{$status_child}'";
		if ($mode == 2) {
			$filter_status = '';
		}
		$groupId = $item['group_id'];
		if($attributes)
		{
			$query = "
				SELECT {$attributes} FROM {$tableName}
				WHERE (depth = '{$depth_child_directly}') AND parent_id = '{$parent_id}' AND group_id ='{$groupId}' {$filter_status}
				ORDER BY priorities ASC
			";
		}else
		{
			$query = "
				SELECT * FROM {$tableName}
				WHERE (depth = '{$depth_child_directly}') AND parent_id = '{$parent_id}' AND group_id ='{$groupId}' {$filter_status}
				ORDER BY priorities ASC
			";
		}

		try {
			$childrenItems = $this->getCollection()->getConnection()->fetchAll($query);
			return $childrenItems;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return false;
		}
		return false;
	}

	public function getDeleteItemsByGroup($groupId)
	{
		$tableName = $this->_tableName;
		try {
			$this->getCollection()->getDeleteItemsByGroup($tableName, $groupId);
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}

	public function setPrioritiesByNewItems($id, $allItems, $groupId, $prioritiesOrder)
	{
		$tableName = $this->_tableName;
		try {
			$this->getCollection()->setPrioritiesByNewItems($tableName, $id, $allItems, $groupId, $prioritiesOrder);
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}

	public function deleteNode($id, $groupId)
	{
		$tableName = $this->_tableName;
		try {
			$item_child = $this->getCollection()->getChidrenItemsByItems($id, $groupId);
			$this->getCollection()->deleteItems($tableName, $id, $groupId);
			if(count($item_child))
			{
				$this->deleteItemsChildByItemsId($item_child, $groupId);
			}
			return true;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}

	public function deleteItemsChildByItemsId($item_child, $groupId)
	{
		$tableName = $this->_tableName;
		foreach ($item_child as $item)
		{
			try {
				$item_child = $this->getCollection()->getChidrenItemsByItems($item['items_id'], $groupId);
				$this->getCollection()->deleteItems($tableName, $item['items_id'], $groupId);
				if(count($item_child))
				{
					$this->deleteItemsChildByItemsId($item_child, $groupId);
				}
				/*return true;*/
			} catch (\Exception $e) {
				$this->messageManager->addError($e->getMessage());
				return;
			}
		}
	}

	public function getItemsByLv($groupId, $level_start)
	{
		$status_child = $this->_statusChild;
		try
		{
			$items = $this->getCollection()->getItemsByLv($groupId, $level_start, $status_child);
			return $items;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}

	public function getAllItemsByItemsId($parentId, $groupId){
		try
		{
			$items = $this->getCollection()->getAllItemsByItemsId($parentId, $groupId);
			return $items;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}

	public function getAllItemsByItemsIdEnabled($parentId, $groupId){
		$status_child = $this->_statusChild;
		try
		{
			$items = $this->getCollection()->getAllItemsByItemsIdEnabled($parentId, $groupId, $status_child);
			return $items;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}

	public function getAllActivedItems($type, $itemId, $groupId){
		$status_child = $this->_statusChild;
		try
		{
			$items = $this->getCollection()->getAllActivedItems($status_child, $type, $itemId, $groupId);
			return $items;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}

	public function getAllLeafByGroupId($groupId){
		$lv_config = $this->_defaults['start_level']+1;
		$status_child = $this->_statusChild;
		try
		{
			$items = $this->getCollection()->getItemsByLv($groupId, $lv_config, $status_child);
			return $items;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}

	public function getAllItemsFirstByGroupId($groupId)
	{
		$tableName = $this->_tableName;
		$lv_config = $this->_defaults['start_level']+1;
		$status_child = $this->_statusChild;
		try
		{
			$items = $this->getCollection()->getAllItemsFirstByGroupId($tableName, $groupId, $lv_config, $status_child);
			return $items;
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
			return;
		}
	}
}