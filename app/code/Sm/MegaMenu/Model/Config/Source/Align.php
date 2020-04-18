<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\Config\Source;

class Align implements \Magento\Framework\Option\ArrayInterface
{
	const LEFT	= 1;
	const RIGHT	= 2;

	public function getOptionArray()
	{
		return [
			self::LEFT    => __('Left'),
			self::RIGHT   => __('Right')
		];
	}

	public function toOptionArray()
	{
		return [
			[
				'value'     => self::LEFT,
				'label'     => __('Left'),
			],
			[
				'value'     => self::RIGHT,
				'label'     => __('Right'),
			]
		];
	}
}