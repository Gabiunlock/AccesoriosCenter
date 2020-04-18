<?php
/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
namespace Sm\CartQuickPro\Block;

class CartQuickPro extends \Magento\Framework\View\Element\Template
{
    /**
     * @var array
     */
    protected $_config = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
	protected $_storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var int
     */
	protected $_storeId;

    /**
     * @var string
     */
	protected $_storeCode;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
	protected $_request ;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * CartQuickPro constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     * @param null $attr
     */
	public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\View\Element\Template\Context $context,
		array $data = [],
		$attr = null
	)
	{
		$this->_storeManager = $context->getStoreManager();
        $this->_scopeConfig = $context->getScopeConfig();
		$this->_storeId=(int)$this->_storeManager->getStore()->getId();
		$this->_storeCode=$this->_storeManager->getStore()->getCode();
		$this->_request = $context->getRequest();
        $this->_objectManager = $objectManager;
		$this->_config = $this->_getCfg($attr, $data);
		parent::__construct($context, $data);
	}

    /**
     * @param null $attr
     * @param null $data
     * @return array|null|void
     */
    public function _getCfg($attr = null , $data = null)
	{
		$defaults = [];
		$_cfg_xml = $this->_scopeConfig->getValue('cartquickpro',\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->_storeCode);
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
			$_cfgs = $this->_scopeConfig->getValue('cartquickpro/'.$group.'',\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->_storeCode);
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

    /**
     * @param null $name
     * @param null $value_def
     * @return array|mixed|null|void
     */
	public function _getConfig($name = null, $value_def = null)
	{
		if (is_null($this->_config)) $this->_getCfg();
		if (!is_null($name)) {
			$value_def = isset($this->_config[$name]) ? $this->_config[$name] : $value_def;
			return $value_def;
		}
		return $this->_config;
	}

    /**
     * @param $name
     * @param null $value
     * @return bool|void
     */
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

    /**
     * @return bool
     */
	public function _isProductView(){
		$_action_name = ['cartquickpro_wishlist_index_configure', 'cartquickpro_catalog_product_view' ,'cartquickpro_catalog_product_options', 'cartquickpro_cart_configure'];
        if (in_array($this->_request->getFullActionName(), $_action_name )) {
			return true;
		}
		return false;
	}

    /**
     * @return bool
     */
	public function _isConfigure(){
		if ($this->_request->getFullActionName() == 'cartquickpro_cart_configure' ) {
			return true;
		}
		return false;
	}
	
	public function _isCompareIndex(){
		if ($this->_request->getFullActionName() == 'catalog_product_compare_index' ) {
			return true;
		}
		return false;
	}

    /**
     * @return bool
     */
	public function _isWishlistIndex(){
		if ($this->_request->getFullActionName() == 'wishlist_index_index' ) {
			return true;
		}
		return false;
	}

    /**
     * @return bool
     */
	public function _isWishlistIndexConfigure(){
		if ($this->_request->getFullActionName() == 'cartquickpro_wishlist_index_configure' ) {
			return true;
		}
		return false;
	}

    /**
     * @return bool
     */
	public function _isPageCheckout(){
		if ($this->_request->getFullActionName() == 'checkout_cart_index' ) {
			return true;
		}
		return false;
	}

    /**
     * @return bool
     */
	public function _isLoggedIn(){
		$customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
		if($customerSession->isLoggedIn()){
		   return true;
		}
		return false;
	}

    /**
     * @return string
     */
	public function _urlLogin(){
		return $this->getUrl('customer/account/login');
	}

    /**
     * @return string
     */
	public function getCurrentUrl() {
		return $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
	}

    /**
     * @return bool
     */
	public function _isAjaxCart () {
		if($this->_getConfig('isenabled', 1) && ($this->_getConfig('select_type', 1) == 'both' || $this->_getConfig('select_type', 1) == 'ajaxcart')){
			return true;
		}
		return false;
	}

    /**
     * @return bool
     */
	public function _isQuickView () {
		if($this->_getConfig('isenabled', 1) && ($this->_getConfig('select_type', 1) == 'both' || $this->_getConfig('select_type', 1) == 'quickview')){
			return true;
		}
		return false;
	}
}