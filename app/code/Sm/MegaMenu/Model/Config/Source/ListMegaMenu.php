<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\Config\Source;

use Sm\MegaMenu\Model\MenuGroup;
use Magento\Framework\Option\ArrayInterface;

class ListMegaMenu implements ArrayInterface
{
	protected $_menuGroup;

	public function __construct(
		MenuGroup $menuGroup
	)
	{
		$this->_menuGroup = $menuGroup;
	}
	
	public function getOptionArray(){
		foreach ($this->_menuGroup->getCollection() as $group)
		{
			$arr[$group ->getTitle()] = $group ->getTitle();
		}
		return $arr;
	}
	public function toOptionArray(){
		$arr[] = array(
			'value'			=>	'',
			'label'     	=>	__('--Please Select--'),
		);
		foreach ($this->_menuGroup->getCollection() as $group)
		{
			$label = '('.$group->getId().') ' . $group->getTitle();
			$arr[] = array(
				'value'		=>	$group->getId(),
				'label'     => 	$label,
			);
		}
		return $arr;
	}
}