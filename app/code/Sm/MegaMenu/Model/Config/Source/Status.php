<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\Config\Source;

class Status implements \Magento\Framework\Option\ArrayInterface
{
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 2;

	public function getOptionArray()
	{
		return [
			self::STATUS_ENABLED    => __('Enable'),
			self::STATUS_DISABLED   => __('Disable')
		];
	}

	public function toOptionArray()
	{
		return [
			[
				'value'     => self::STATUS_ENABLED,
				'label'     => __('Enable'),
			],
			[
				'value'     => self::STATUS_DISABLED,
				'label'     => __('Disable'),
			]
		];
	}
}