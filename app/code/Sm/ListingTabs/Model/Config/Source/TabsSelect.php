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

use \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class TabsSelect implements \Magento\Framework\Option\ArrayInterface
{
	protected $_categoryCollectionFactory;

	public function __construct(\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $objectManager)
	{
		$this->_categoryCollectionFactory = $objectManager;
	}

	public function toOptionArray()
	{
		$collection = $this->_categoryCollectionFactory->create();
		$collection->addAttributeToSelect('name')
			->addPathFilter('^1/[0-9/]+')
			->addIsActiveFilter()
			->load();

		$options = [];
		$opt_cats = [];
		$cats = [];

		foreach ($collection as $category) {
			$c = new \stdClass();
			$c->label = $category->getName();
			$c->value = $category->getId();
			$c->level = $category->getLevel();
			$c->parentid = $category->getParentId();
			$cats[$c->value] = $c;
		}

		foreach ($cats as $id => $c) {
			if (isset($cats[$c->parentid])) {
				if (!isset($cats[$c->parentid]->child)) {
					$cats[$c->parentid]->child = array();
				}
				$cats[$c->parentid]->child[] =& $cats[$id];
			}
		}
		foreach ($cats as $id => $c) {
			if (!isset($cats[$c->parentid])) {
				$stack = array($cats[$id]);
				while (count($stack) > 0) {
					$opt = array_pop($stack);
					$option = array(
						/*'label' => ($opt->level > 1 ? str_repeat('- - ', $opt->level - 1) : '') . $opt->label,*/
						'label' => '- - ' . $opt->label,
						'value' => $opt->value
					);
					array_push($opt_cats, $option);
					if (isset($opt->child) && count($opt->child)) {
						foreach (array_reverse($opt->child) as $child) {
							array_push($stack, $child);
						}
					}
				}
			}
		}
		unset($cats);
		$group_field =  [
                            'label' => '____________ Field Product ____________',
                            'value' => [
								['value'=>'name', 'label'=>__('- - Name')],
								['value'=>'entity_id', 'label'=>__('- - Id')],
								['value'=>'created_at', 'label'=>__('- - Date Created')],
								['value'=>'price', 'label'=>__('- - Price')],
								['value'=>'lastest_product', 'label'=>__('- - Lastest Product')],
								['value'=>'top_rating', 'label'=>__('- - Top Rating')],
								['value'=>'most_reviewed', 'label'=>__('- - Most Reviews')],
								['value'=>'most_viewed', 'label'=>__('- - Most Viewed')],
								['value'=>'best_sales', 'label'=>__('- - Most Selling')],
								['value'=>'random', 'label'=>__('- - Random')]
							]
                        ];
						
		$group_cats = [
					'label' => '____________ Select Categories ____________',
					'value' => $opt_cats
				];				
		array_push($options, $group_field, $group_cats);
		return $options;
	}
}