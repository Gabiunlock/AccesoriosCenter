<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Defaults extends AbstractHelper
{
	CONST INENABLE = 1;
	CONST GROUP_ID = 1;
	CONST THEME = 1;
	CONST EFFECT = 1;
	CONST EFFECT_DURATION = 800;
	CONST START_LEVEL = 1;
	CONST END_LEVEL = 5;
	CONST INCLUDE_JQUERY = 1;

	protected $_defaults;
	protected $_scopeConfigInterface;

	/**
	 * Object manager
	 *
	 * @var \Magento\Framework\ObjectManagerInterface
	 */
	protected $_objectManager;

	public function __construct(
		Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager
	){
		$this->_objectManager = $objectManager;
		$this->_scopeConfigInterface = $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
		$this->_defaults = [
			/* General options */
			'isenabled'		    => self::INENABLE,
			'group_id'			=> self::GROUP_ID,
			'theme' 			=> self::THEME,			//default = Horizontal
			'effect'			=> self::EFFECT,		//default = css
			'effect_duration'   => self::EFFECT_DURATION,
			'start_level'		=> self::START_LEVEL,
			'end_level'			=> self::END_LEVEL,

			/* advanced options*/
			'include_jquery'	=> self::INCLUDE_JQUERY,
		];
		parent::__construct($context);
	}

	public function get($attributes = [])
	{
		$data       = $this->_defaults;
		$general    = $this->_scopeConfigInterface->getValue('megamenu/general', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$advanced   = $this->_scopeConfigInterface->getValue('megamenu/advanced', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if (!is_array($attributes))
			$attributes = [$attributes];

		if (is_array($general))
			$data = array_merge($data, $general);

		if (is_array($advanced))
			$data = array_merge($data, $advanced);

		return array_merge($data, $attributes);
	}
}