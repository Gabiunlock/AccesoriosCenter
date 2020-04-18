<?php
/*------------------------------------------------------------------------
# SM Market - Version 1.0.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Market\Model\Config\Source;

class ListBgImage implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'pattern1', 'label' => __('Pattern 1')],
			['value' => 'pattern2', 'label' => __('Pattern 2')],
			['value' => 'pattern3', 'label' => __('Pattern 3')],
			['value' => 'pattern4', 'label' => __('Pattern 4')],
			['value' => 'pattern5', 'label' => __('Pattern 5')],
			['value' => 'pattern6', 'label' => __('Pattern 6')],
			['value' => 'pattern7', 'label' => __('Pattern 7')],
			['value' => 'pattern8', 'label' => __('Pattern 8')],
		];
	}
}

