<?php
/*------------------------------------------------------------------------
# SM Categories - Version 3.2.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Categories\Block;

class Categories  extends \Magento\Framework\View\Element\Template
{
	protected $_config = null;
	
	protected $_categoryFactory;
    protected $_categoryHelper;
    protected $_categoryRepository;
	protected $_storeManager;
    protected $_scopeConfig;
	protected $_storeId;
	protected $_storeCode;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,        
        array $data = [],
		$attr = null
    )
    {
		$this->_categoryFactory = $categoryFactory; 
		$this->_categoryHelper = $categoryHelper;
		$this->_categoryRepository = $categoryRepository;
		$this->_storeManager = $context->getStoreManager();
        $this->_scopeConfig = $context->getScopeConfig();
		$this->_storeId=(int)$this->_storeManager->getStore()->getId();
		$this->_storeCode=$this->_storeManager->getStore()->getCode();
		$this->_config = $this->_getCfg($attr, $data);		
        parent::__construct($context, $data);
    }

	public function _getCfg($attr = null , $data = null)
	{
		$defaults = [];
		$_cfg_xml = $this->_scopeConfig->getValue('categories',\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->_storeCode);
		if (empty($_cfg_xml)) return;
		$groups = [];
		foreach ($_cfg_xml as $def_key => $def_cfg) {
			$groups[] = $def_key;
			foreach ($def_cfg as $_def_key => $cfg) {
				$defaults[$_def_key] = $cfg;
			}
		}
		
		if (empty($groups)) return;
		$cfgs = [];
		foreach ($groups as $group) {
			$_cfgs = $this->_scopeConfig->getValue('categories/'.$group.'',\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->_storeCode);
			foreach ($_cfgs as $_key => $_cfg) {
				$cfgs[$_key] = $_cfg;
			}
		}

		if (empty($defaults)) return;
		$configs = [];
		foreach ($defaults as $key => $def) {
			if (isset($defaults[$key])) {
				$configs[$key] = $cfgs[$key];
			} else {
				unset($cfgs[$key]);
			}
		}
		$cf = ($attr != null) ? array_merge($configs, $attr) : $configs;
		$this->_config = ($data != null) ? array_merge($cf, $data) : $cf;
		return $this->_config;
	}

	public function _getConfig($name = null, $value_def = null)
	{
		if (is_null($this->_config)) $this->_getCfg();
		if (!is_null($name)) {
			$value_def = isset($this->_config[$name]) ? $this->_config[$name] : $value_def;
			return $value_def;
		}
		return $this->_config;
	}

	public function _setConfig($name, $value = null)
	{

		if (is_null($this->_config)) $this->_getCfg();
		if (is_array($name)) {
			$this->_config = array_merge($this->_config, $name);

			return;
		}
		if (!empty($name) && isset($this->_config[$name])) {
			$this->_config[$name] = $value;
		}
		return true;
	}
	
	protected function _toHtml()
    {
		if (!(int)$this->_getConfig('enable', 1)) return ;
        $template_file = $this->getTemplate();
        $template_file = (!empty($template_file)) ? $template_file : "Sm_Categories::default.phtml";
        $this->setTemplate($template_file);
        return parent::_toHtml();
    }

	public function _getList()
	{
		$catids = $this->_getConfig('select_category');
		return $this->_getCategoryInfo($catids);
	}
	
	public function _getCategoryInfo($catids){
		$limit = $this->_getConfig('limitation' , 5);
		!is_array($catids) && $catids = preg_split('/[\s|,|;]/', $catids, -1, PREG_SPLIT_NO_EMPTY);
		$list = [];
		if (!empty($catids)) {
			foreach($catids as $catid){
				$category = $this->_categoryFactory->create()->load($catid);
					if ($category->getIsActive()){
					$category->getUrl();	
					$list[$catid] = $category->__toArray();
					$list[$catid]['cat_image'] = $category->getImageUrl();
					$list[$catid]['product_count'] = $category->getProductCount();
					$child_catids = $category->getAllChildren(true);
					$child_catids = array_slice($child_catids,1,$limit);
					$list[$catid]['children_catids'] = $child_catids;
					if (!empty($child_catids)){
						$list[$catid]['children_info'] = $this->_getChildrenCategoryInfo($child_catids);
					}
					
				}
			}
		}
		return $list ;
	}
	
	public function _getChildrenCategoryInfo($catids){
		$list = [];
		if (!empty($catids)) {
			foreach($catids as $catid){
				$category = $this->_categoryFactory->create()->load($catid);
				if ($category->getIsActive()){
					$category->getUrl();	
					$list[$catid] = $category->__toArray();
					$list[$catid]['cat_image'] = $category->getImageUrl();
					$list[$catid]['product_count'] = $category->getProductCount();
				}
			}
		}
		return $list ;
	}
}