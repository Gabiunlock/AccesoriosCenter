<?php
/*------------------------------------------------------------------------
# SM Categories Menu - Version 1.0.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\CategoriesMenu\Block;

class CategoriesMenu  extends \Magento\Framework\View\Element\Template
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
		$_cfg_xml = $this->_scopeConfig->getValue('categoriesmenu',\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->_storeCode);
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
			$_cfgs = $this->_scopeConfig->getValue('categoriesmenu/'.$group.'',\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->_storeCode);
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
		if (!(int)$this->_getConfig('isenabled', 1)) return ;
        $template_file = $this->getTemplate();
        $template_file = (!empty($template_file)) ? $template_file : "Sm_Categories::default.phtml";
        $this->setTemplate($template_file);
        return parent::_toHtml();
    }

	public function _getList()
	{
		$catids = $this->_getConfig('select_category');
		!is_array($catids) && $catids = preg_split('/[\s|,|;]/', $catids, -1, PREG_SPLIT_NO_EMPTY);
		$list = [];
		if (!empty($catids)) {
			$str = ''; ;
			foreach($catids as $i => $catid){
				$category = $this->_categoryFactory->create()->load($catid);
				if ($category->getIsActive()){
					$child_catids = $category->getAllChildren(false);
					$str .= $child_catids .($i < count($catids) - 1 ? ',' : '');
				}
			}	
			$menus = array(
				'categories' => array(),
				'parent_cats' => array()
			);
			if (!empty($str)){
				$arr_tmp = explode(',', $str);
				$_catids = array_merge($arr_tmp, $catids);
				$_catids = array_unique($_catids);
				$_catactive = [];
				$catinfo = [] ;
				$cattemp = '';		
				foreach($_catids as $_cat) {
					$catinfo = $this->_getCategoryInfo($_cat);
					if (isset($catinfo['is_active']) && $catinfo['is_active'] == 1) {
						$_catactive[] = $_cat;
						$menus['categories'][$_cat] =   $catinfo;
						if (!empty($catinfo) && isset($catinfo['parent_id'])){
							$menus['parent_cats'][$catinfo['parent_id']][] = $catinfo['entity_id'];
						}
					}
				}
				if (isset($menus['parent_cats']) && !empty($menus['parent_cats'])) {
					foreach($menus['parent_cats'] as $key => &$menu){
						if (!in_array($key, $_catids)){
							$cattemp .= implode(',',$menu).',';
							unset($menus['parent_cats'][$key]);
							
						}
					}
					if (!empty($cattemp)){
						$cattemp = rtrim($cattemp,',');
						$cattemp = explode(',',$cattemp);
						$cattemp = array_unique($cattemp);
						$menus['parent_cats'][0] = $cattemp;
					}
					$max_level = (int)$this->_getConfig('max_depth');
					$visible = $this->_getConfig('visible_category');
					$visible = $visible > 0 ? $visible : 9999;
					$_menu_str = $this->_buildMenu(0, $menus , 'menu-wrap',0 , $max_level , $visible);
					return $_menu_str;
				}else{
					return  __('We can\'t find category matching the selection.');
				}
			}else{
				return  __('We can\'t find category matching the selection.');
			}
		}
		return  __('We can\'t find category matching the selection.');
	}
	
	private function _buildMenu($parent, $category , $cls, $level = 0, $max_level = 4, $visible = 0) {
		$html = "";
		if (isset($category['parent_cats'][$parent])) {
			$level++;
			$html .= '<ul class="'.$cls.'">';
			
			if ($level <= $max_level) {
				$i = 0; $_cls_visible = '' ; $_nb_level1 = 0; $j = 0;
				foreach ($category['parent_cats'][$parent] as $cat_id) {
					 $j++;
					if ($level ==  1) {
						$_nb_level1 ++;
						if ( $i < $visible){
							$i++;
						}else {
							$_cls_visible = ' hidden-item ';
						}
					}
					if (!isset($category['parent_cats'][$cat_id])) {
						$html .= '<li class="level-'.$level. $_cls_visible.'"> <a title="'.$category['categories'][$cat_id]['name'].'" href="' . $category['categories'][$cat_id]['url'] . '">' . $category['categories'][$cat_id]['name']. '</a></li>';
					}
					if (isset($category['parent_cats'][$cat_id])) {
						$html .= '<li class="level-'.$level. $_cls_visible.' have-sub">  <a  title="'.$category['categories'][$cat_id]['name'].'" href="' . $category['categories'][$cat_id]['url'] . '">' . $category['categories'][$cat_id]['name']. '</a> ';
						$html .= $this->_buildMenu($cat_id, $category ,'submenu-wrap', $level, $max_level, $visible);
						$html .= '<span class="btn-showsub touch-top"></span></li>';
					}
					
				}
				
				if ($level ==  1 && $visible < 9999 && $_nb_level1 > $visible) {
					$html .= '<li class="all-categories">';
					$html .= '<a class="show_more btn-showmore smcm-btn" title='.__('More Categories').' href="javascript:void(0);" >'.__('More Categories').'<em class="fa fa-plus-square-o" style="float:right; margin-top: 6px;"></em></a>';
					$html .= '<a class="close_more btn-close smcm-btn" style="display:none" title='.__('Close Menu').' href="javascript:void(0);">'.__('Close Menu').'<em class="fa fa-minus-square-o" style="float: right; margin-top: 6px;"></em></a>';
					$html .= '</li>';
				}
			}
			$html .= '</ul>';
		}else{
			$parent ++;
			$html .= $this->_buildMenu($parent, $category , $cls, $level, $max_level, $visible);
		}
		return $html;
	}
	
	public function _tagId()
	{
		$tag_id = $this->getNameInLayout();
		$tag_id = strpos($tag_id, '.') !== false ? str_replace('.', '_', $tag_id) : $tag_id;
		return $tag_id;
	}
	 
	private function _getCategoryInfo($catid){
		$list = [];
		if (!empty($catid)) {
			$category = $this->_categoryFactory->create()->load($catid);
			if ($category->getIsActive()){
				$category->getUrl();	
				$list = $category->__toArray();
			}
		}
		return $list ;
	}
}