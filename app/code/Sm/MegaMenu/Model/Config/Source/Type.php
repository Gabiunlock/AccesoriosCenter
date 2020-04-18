<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Model\Config\Source;

class Type implements \Magento\Framework\Option\ArrayInterface
{
	const NORMAL		= 1;
	const EXTERNALLINK	= 2;
	const PRODUCT		= 3;
	const CATEGORY		= 4;
	const CMSPAGE		= 5;
	const CMSBLOCK		= 6;
	const CONTENT		= 7;
	const PAGE_MODULE	= 8;

	public function getOptionArray()
	{
		return [
			self::NORMAL        => __('Default'),
			self::EXTERNALLINK  => __('External Link'),
			self::PRODUCT       => __('Product'),
			self::CATEGORY      => __('Category'),
			self::CMSPAGE       => __('CMS Page'),
			self::CMSBLOCK      => __('CMS Block'),
			self::CONTENT       => __('Content'),
			self::PAGE_MODULE   => __('Page Module')
		];
	}

	public function toOptionArray()
	{
		return [
			[
				'value'     => self::NORMAL,
				'label'     => __('Default'),
			],
			[
				'value'     => self::EXTERNALLINK,
				'label'     => __('External Link'),
			],
			[
				'value'     => self::PRODUCT,
				'label'     => __('Product'),
			],
			[
				'value'     => self::CATEGORY,
				'label'     => __('Category'),
			],
			[
				'value'     => self::CMSPAGE,
				'label'     => __('CMS Page'),
			],
			[
				'value'     => self::CMSBLOCK,
				'label'     => __('CMS Block'),
			],
			[
				'value'     => self::CONTENT,
				'label'     => __('Content'),
			],
			[
                'value'     => self::PAGE_MODULE,
                'label'     => __('Page Module'),
            ]
		];
	}
}