<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\Config\Source;

class LinkTargets implements \Magento\Framework\Option\ArrayInterface
{
	const _BLANK		= 1;
	const _POPUP		= 2;
	const _SELF			= 3;

	public function getOptionArray()
	{
		return [
			self::_BLANK    => __('New Window'),
			self::_POPUP    => __('Popup Window'),
			self::_SELF     => __('Same Window')
		];
	}

	public function toOptionArray()
	{
		return [
			[
				'value'     => self::_BLANK,
				'label'     => __('New Window'),
			],
			[
				'value'     => self::_POPUP,
				'label'     => __('Popup Window'),
			],
			[
				'value'     => self::_SELF,
				'label'     => __('Same Window'),
			]
		];
	}
}