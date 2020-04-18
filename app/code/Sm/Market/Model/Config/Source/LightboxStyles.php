<?php
/*------------------------------------------------------------------------
# SM Market - Version 1.0.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Market\Model\Config\Source;

class LightboxStyles implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'simple', 'label' => __('Simple')],
			['value' => 'button', 'label' => __('Light Box With Button')],
			['value' => 'thumbs', 'label' => __('Light Box With Thumbs')],
		];
	}
}

