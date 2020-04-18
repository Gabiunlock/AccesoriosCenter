<?php
/**------------------------------------------------------------------------
* SM Search Box - Version 2.1.0
* Copyright (c) 2015 YouTech Company. All Rights Reserved.
* @license - Copyrighted Commercial Software
* Author: YouTech Company
* Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\SearchBox\Block;

use Magento\Framework\UrlInterface;

class SearchBox extends \Magento\Framework\View\Element\Template
{
	protected $_config = null;

	/**
	 * @var array
	 */
	protected $_terms;

	/**
	 * @var int
	 */
	protected $_minPopularity;

	/**
	 * @var int
	 */
	protected $_maxPopularity;

	/**
	 * Url factory
	 *
	 * @var \Magento\Framework\UrlFactory
	 */
	protected $_urlFactory;

	/**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterface
	 */
	protected $_scopeConfigInterface;

	/**
	 * Object manager
	 *
	 * @var \Magento\Framework\ObjectManagerInterface
	 */
	private $_objectManager;

	/**
	 * Query collection factory
	 *
	 * @var \Magento\Search\Model\ResourceModel\Query\CollectionFactory
	 */
	protected $_queryCollectionFactory;

	/**
	 * Class constructor
	 *
	 * @param \Magento\Framework\UrlFactory $urlFactory
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \Magento\Search\Model\ResourceModel\Query\CollectionFactory $queryCollectionFactory
	 * @param string|null $scope
	 */
	public function __construct(
		\Magento\Framework\UrlFactory $urlFactory,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Search\Model\ResourceModel\Query\CollectionFactory $queryCollectionFactory,
		array $data = [],
		$attr = null
	)
	{
		$this->_objectManager = $objectManager;
		$this->_scopeConfigInterface = $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
		$this->_queryCollectionFactory = $queryCollectionFactory;
		$this->_urlFactory = $urlFactory;
		$this->_config = $this->_getCfg($attr, $data);
		parent::__construct($context, $data);
	}
	public function _getCfg($attr = null , $data = null)
	{
		// get default config.xml
		$defaults = [];
		$collection = $this->_scopeConfigInterface->getValue('searchbox');

		if (empty($collection)) return;
		$groups = [];
		foreach ($collection as $def_key => $def_cfg) {
			$groups[] = $def_key;
			foreach ($def_cfg as $_def_key => $cfg) {
				$defaults[$_def_key] = $cfg;
			}
		}

		// get configs after change
		$_configs = $this->_scopeConfigInterface->getValue('searchbox');
		if (empty($_configs)) return;
		$cfgs = [];

		foreach ($groups as $group) {
			$_cfgs = $this->_scopeConfigInterface->getValue('searchbox/'.$group.'', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			foreach ($_cfgs as $_key => $_cfg) {
				$cfgs[$_key] = $_cfg;
			}
		}

		// get output config
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

	public function _prepareLayout()
	{
		parent::_prepareLayout();
		if (!$this->_getConfig('isenabled', 1)) return;
		$template_file = $this->getTemplate();
		if ($this->_isAjax()) {
			$template_file = (!empty($template_file)) ? $template_file : "Sm_SearchBox::searchbox.form.mini.ajax.phtml";
		} else {
			$template_file = (!empty($template_file)) ? $template_file : "Sm_SearchBox::searchbox.form.mini.phtml";
		}
		$this->setTemplate($template_file);
		return $this;
	}

	public function _isAjax()
	{
		$isAjax = $this->getRequest()->isAjax();
		if($isAjax)
			return true;
		else
			return false;
	}

	/**
	 * @return array
	 */
	public function getTerms()
	{
		$this->_loadTerms();
		return $this->_terms;
	}

	/**
	 * Load terms and try to sort it by names
	 *
	 * @return $this
	 */
	protected function _loadTerms()
	{
		if (empty($this->_terms)) {
			$termKeys = [];
			$this->_terms = [];

			$is_ajax = $this->getRequest()->getParam('is_ajax');
			if($is_ajax){
				$count_term = $this->getRequest()->getParam('count_term');
				$terms = $this->_queryCollectionFactory->create()->setPopularQueryFilter(
					$this->_storeManager->getStore()->getId()
				)->setPageSize(
					$count_term
				)->load()->getItems();
			} else {
				$count_term = $this->_getConfig('limit_popular', 5);
				$terms = $this->_queryCollectionFactory->create()->setPopularQueryFilter(
					$this->_storeManager->getStore()->getId()
				)->setPageSize(
					$count_term
				)->load()->getItems();
			}
			if (count($terms) == 0) {
				return $this;
			}

			$this->_maxPopularity = reset($terms)->getPopularity();
			$this->_minPopularity = end($terms)->getPopularity();
			$range = $this->_maxPopularity - $this->_minPopularity;
			$range = $range == 0 ? 1 : $range;
			foreach ($terms as $term) {
				if (!$term->getPopularity()) {
					continue;
				}
				$term->setRatio(($term->getPopularity() - $this->_minPopularity) / $range);
				$temp[$term->getQueryText()] = $term;
				$termKeys[] = $term->getQueryText();
			}
			natcasesort($termKeys);

			foreach ($termKeys as $termKey) {
				$this->_terms[$termKey] = $temp[$termKey];
			}
		}
		return $this;
	}

	/**
	 * @param \Magento\Framework\DataObject $obj
	 * @return string
	 */
	public function getSearchUrl($obj)
	{
		/** @var $url UrlInterface */
		$url = $this->_urlFactory->create();
		/*
		 * url encoding will be done in Url.php http_build_query
		 * so no need to explicitly called urlencode for the text
		 */
		$url->setQueryParam('q', $obj->getQueryText());
		return $url->getUrl('catalogsearch/result');
	}

	/**
	 * @return int
	 */
	public function getMaxPopularity()
	{
		return $this->_maxPopularity;
	}

	/**
	 * @return int
	 */
	public function getMinPopularity()
	{
		return $this->_minPopularity;
	}

	/**
	 * @return string
	 */
	public function getSearchBoxAjax()
	{
		return $this->getBaseUrl().'searchbox/index/ajax';
	}

	/**
	 * @return string
	 */
	public function getSearchBoxAdvanced()
	{
		return $this->getBaseUrl().'catalogsearch/advanced';
	}

	/**
	 * @return array
	 */
	public function getCategories(){
		$category = $this->_objectManager->create('Sm\SearchBox\Model\Config\Source\ListCategories');
		$root_id =  $this->_getConfig('root_catalog');
		$depth = $this->_getConfig('max_level');
		/*$depth = $depth + 1;*/
		$cat_list = $category->toOptionArray($root_id, $depth);
		return $cat_list;
	}
}