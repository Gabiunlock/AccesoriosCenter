<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;

class MenuGroup extends AbstractDb
{
	public function __construct(
		Context $context,
		StoreManagerInterface $storeManager,
		$connectionName = null
	)
	{
		parent::__construct($context, $connectionName);
		$this->_storeManager = $storeManager;
	}

	public function _construct()
	{
		$this->_init('sm_megamenu_groups', 'group_id');
	}
}