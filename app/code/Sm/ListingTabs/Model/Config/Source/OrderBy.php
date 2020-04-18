<?php
/**
 *
 * SM Listing Tabs - Version 2.7.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace Sm\ListingTabs\Model\Config\Source;

class OrderBy implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value'=>'name', 'label'=>__('Name')],
			['value'=>'entity_id', 'label'=>__('Id')],
			['value'=>'created_at', 'label'=>__('Date Created')],
			['value'=>'price', 'label'=>__('Price')],
			['value'=>'num_rating_summary', 'label'=>__('Number Rating')],
			['value'=>'num_reviews_count', 'label'=>__('Number Reviews')],
			['value'=>'num_view_counts', 'label'=>__('Number Views')],
			['value'=>'ordered_qty', 'label'=>__('Number Ordered')],
		];
	}
}