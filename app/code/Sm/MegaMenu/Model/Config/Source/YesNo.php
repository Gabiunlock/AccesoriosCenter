<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\Config\Source;

class YesNo implements \Magento\Framework\Option\ArrayInterface
{
	const YES	= 1;
	const NO	= 2;

	public function getOptionArray()
	{
		return [
			self::YES    => __('Yes'),
			self::NO   => __('No')
		];
	}

	public function toOptionArray()
	{
		return [
			[
				'value'     => self::YES,
				'label'     => __('Yes'),
			],
			[
				'value'     => self::NO,
				'label'     => __('No'),
			]
		];
	}
}