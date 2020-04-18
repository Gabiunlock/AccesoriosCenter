<?php
/**
 *
 * SM Listing Tabs - Version 2.8.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace Sm\ListingTabs\Model\Config\Source;

class LoadMore implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'loadmore', 'label' => __('Loadmore')],
			['value' => 'slider', 'label' => __('Slider')]
		];
	}
}