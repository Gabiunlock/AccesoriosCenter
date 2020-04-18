<?php
/*------------------------------------------------------------------------
# SM Basic Products - Version 2.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\CartQuickPro\Model\Config\Source;

class ListSource implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'both', 'label' => __('Both')],
			['value' => 'quickview', 'label' => __('Quick View')],
			['value' => 'ajaxcart', 'label' => __('Ajax Cart')]
		];
	}
}