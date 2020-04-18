<?php
/*------------------------------------------------------------------------
# SM Market - Version 1.0.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Market\Model\Config\Source;

class ListResMenuStyles implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			/* ['value' => 'selectbox', 'label' => __('Selectbox')], */
			['value' => 'collapse', 'label' => __('Collapse')],
			['value' => 'sidebar', 'label' => __('Sidebar')],
		];
	}
}