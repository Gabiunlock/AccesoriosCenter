<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Api\Data;

interface MenuItemsInterface
{
	/**#@+
	 * Constants for keys of data array. Identical to the name of the getter in snake case
	 */
	const ITEMS_ID              = 'items_id';
	const GROUP_ID              = 'group_id';
	const TITLE                 = 'title';
	const STATUS                = 'status';
	const SHOW_TITLE            = 'show_title';
	const DESCRIPTION           = 'description';
	const CONTENT               = 'content';
	const ALIGN                 = 'align';
	const DEPTH                 = 'depth';
	const COLS_NB               = 'cols_nb';
	const ICON_URL              = 'icon_url';
	const TARGET                = 'target';
	const TYPE                  = 'type';
	const DATA_TYPE             = 'data_type';
	const CUSTOM_CLASS          = 'custom_class';
	const PARENT_ID             = 'parent_id';
	const ORDER_ITEM            = 'order_item';
	const POSITION_ITEM         = 'position_item';
	const PRIORITIES            = 'priorities';
	const SHOW_IMAGE_PRODUCT    = 'show_image_product';
	const SHOW_TITLE_PRODUCT    = 'show_title_product';
	const SHOW_RATING_PRODUCT   = 'show_rating_product';
	const SHOW_PRICE_PRODUCT    = 'show_price_product';
	const SHOW_TITLE_CATEGORY   = 'show_title_category';
	const LIMIT_CATEGORY        = 'limit_category';

	public function getItemsId();

	public function getGroupId();

	public function getTitle();

	public function getStatus();

	public function getShowTitle();

	public function getDescription();

	public function getAlign();

	public function getDepth();

	public function getColsNb();

	public function getIconUrl();

	public function getTarget();

	public function getType();

	public function getDataType();

	public function getCustomClass();

	public function getParentId();

	public function getOrderItem();

	public function getPositionItem();

	public function getPriorities();

	public function getContent();

	public function getShowImageProduct();

	public function getShowTitleProduct();

	public function getShowRatingProduct();

	public function getShowPriceProduct();

	public function getShowTitleCategory();

	public function getLimitCategory();

	public function setItemsId($itemsId);

	public function setGroupId($groupId);

	public function setTitle($title);

	public function setStatus($status);

	public function setContent($content);

	public function setShowTitle($showTitle);

	public function setDescription($desription);

	public function setAlign($align);

	public function setDepth($depth);

	public function setColsNb($colsNb);

	public function setIconUrl($iconUrl);

	public function setTarget($target);

	public function setType($type);

	public function setDataType($dataType);

	public function setCustomClass($customClass);

	public function setParentId($parentId);

	public function setOrderItem($orderItem);

	public function setPositionItem($positionItem);

	public function setPriorities($priorities);

	public function setShowImageProduct($showImageProduct);

	public function setShowTitleProduct($showTitleProduct);

	public function setShowRatingProduct($showRatingProduct);

	public function setShowPriceProduct($showPriceProduct);

	public function setShowTitleCategory($showTitleCategory);

	public function setLimitCategory($limitCategory);
}