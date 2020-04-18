<?php
/*------------------------------------------------------------------------
# SM Market - Version 1.0.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Market\Model\Config\Source;

class ListColumns implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => '1', 'label' => __('1 Column')],
			['value' => '2', 'label' => __('2 Columns')],
			['value' => '3', 'label' => __('3 Columns')],
			['value' => '4', 'label' => __('4 Columns')],
			['value' => '5', 'label' => __('5 Columns')],
			['value' => '6', 'label' => __('6 Columns')],
		];
	}
}