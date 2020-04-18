<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model;

use Sm\MegaMenu\Api\Data\MenuGroupInterface;
use Magento\Framework\Model\AbstractModel;

class MenuGroup extends AbstractModel implements MenuGroupInterface
{
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 2;

	protected function _construct()
	{
		$this->_init('Sm\MegaMenu\Model\ResourceModel\MenuGroup');
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

	public function getContent()
	{
		return $this->getData(self::CONTENT);
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

	public function getAvailableStatuses()
	{
		return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
	}
}