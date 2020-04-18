<?php
/**
 *
 * SM Listing Tabs - Version 2.8.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace Sm\ListingTabs\Block\Product\Renderer\Listing;

/**
 * Swatch renderer block in Category page
 *
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Configurable extends \Magento\Swatches\Block\Product\Renderer\Listing\Configurable
{
	protected function getCacheLifetime()
    {
		return null;
    }
    
}
