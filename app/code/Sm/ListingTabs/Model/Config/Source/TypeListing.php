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

class TypeListing implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'all', 'label' => __('All')],
			['value' => 'deals', 'label' => __('Only Deals')],
			// ['value' => 'featured', 'label' => __('Only Featured')],
			['value' => 'under', 'label' => __('Under Price')],
		];
	}
}