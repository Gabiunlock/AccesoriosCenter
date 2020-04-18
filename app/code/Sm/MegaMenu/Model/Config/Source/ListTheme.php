<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\Config\Source;
class ListTheme implements \Magento\Framework\Option\ArrayInterface
{
	const HORIZONTAL =	1;
	const VERTICAL	 =	2;

	public function getOptionArray()
	{
		return [
			self::HORIZONTAL 		=> __('Horizontal'),
			self::VERTICAL			=> __('Vertical'),
		];
	}
	public function toOptionArray()
	{
		return [
			[
				'value'     => self::HORIZONTAL,
				'label'     => __('Horizontal'),
			],
			[
				'value'     => self::VERTICAL,
				'label'     => __('Vertical'),
			],
		];
	}
}