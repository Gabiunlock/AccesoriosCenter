<?php
/**
 *
 * SM Listing Tabs - Version 2.8.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */

namespace Sm\ListingTabs\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\UrlFactory;
use Magento\Framework\Serialize\Serializer\Json as SerializerJson;

class ListingTabs extends \Magento\Catalog\Block\Product\AbstractProduct
{
    const CACHE_TAGS = 'SM_LISTING_TABS';
	protected $_config = null;
    protected $_resource;
	protected $_storeManager;
    protected $_scopeConfig;
	protected $_storeId;
	protected $_storeCode;
	protected $_catalogProductVisibility;
	protected $_review;
	protected $_objectManager;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $jsonSerializer;
	

    public function __construct(
		\Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\ResourceConnection $resource,
		\Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
		\Magento\Review\Model\Review $review,
		\Magento\Catalog\Block\Product\Context $context,
        SerializerJson $jsonSerializer,
        array $data = [],
		$attr = null
    ) {
		$this->_objectManager = $objectManager;
        $this->_resource = $resource;
		$this->_storeManager = $context->getStoreManager();
        $this->_scopeConfig = $context->getScopeConfig();
		$this->_catalogProductVisibility = $catalogProductVisibility;
		$this->_storeId=(int)$this->_storeManager->getStore()->getId();
		$this->_storeCode=$this->_storeManager->getStore()->getCode();
        $this->jsonSerializer = $jsonSerializer;
		$this->_review = $review;
		if ($context->getRequest() && $context->getRequest()->isAjax()) {
			$this->_config =  $context->getRequest()->getParam('config');
		} else {
			$this->_config = $this->_getCfg($attr, $data);
		}
        parent::__construct($context, $data);
    }

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        parent::_construct();
        $this->addData(
            [
                'cache_lifetime' => 86400,
                'cache_tags' => [self::CACHE_TAGS]]
        );
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCacheKeyInfo()
    {
        $params = $this->getRequest()->getParams();
        return [
            'BLOCK_TPL_SM_LISTING_TABS',
            $this->_storeManager->getStore()->getCode(),
            $this->_storeManager->getStore()->getId(),
            $this->_getNameLayout(),
            $this->getTemplateFile(),
            'base_url' => $this->getBaseUrl(),
            'template' => $this->getTemplate(),
            $this->jsonSerializer->serialize($params)
        ];
    }
	
	public function _getCfg($attr = null , $data = null)
	{
		$defaults = [];
		$_cfg_xml = $this->_scopeConfig->getValue('listingtabs',\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->_storeCode);
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
			$_cfgs = $this->_scopeConfig->getValue('listingtabs/'.$group.'',\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->_storeCode);
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
	
	public function getProductDetailsHtml(\Magento\Catalog\Model\Product $product)
    {
        $renderer = $this->getDetailsRenderer($product->getTypeId());
        if ($renderer) {
            $renderer->setProduct($product);
            return $renderer->toHtml();
        }
        return '';
    }

    public function getDetailsRenderer($type = null)
    {
        if ($type === null || $type !== 'configurable') {
            $type = 'default';
			return null;
        }
        $rendererList = $this->getDetailsRendererList();
        if ($rendererList) {
            return $rendererList->getRenderer($type, 'default');
        }
        return null;
    }
	
	private function isHomepage(){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$request = $objectManager->get('Magento\Framework\App\Action\Context')->getRequest();
		if ($request->getFullActionName() == 'cms_index_index') {
			return true;
		}
		return true;
	}
	
	protected function getDetailsRendererList()
    {	
		$name_layout = $this->getNameInLayout();
		if ($this->_isAjax()) {
			$name_layout =  $this->getRequest()->getPost('moduleid');
		}
		return $this->getDetailsRendererListName() ? $this->getLayout()->getBlock(
			$this->getDetailsRendererListName()
		) : $this->getChildBlock(
			$name_layout.'.details.renderers'
		);
	}
	
	private function _getNameLayout() {
		$name_layout = $this->getNameInLayout();
		if ($this->_isAjax()) {
			$name_layout =  $this->getRequest()->getPost('moduleid');
		}
		return $name_layout;
	}
	
	public function _tagId()
	{
		$tag_id = $this->_getNameLayout();
		$tag_id = strpos($tag_id, '.') !== false ? str_replace('.', '_', $tag_id) : $tag_id;
		return $tag_id;
	}
	
	protected function _prepareLayout()
	{
		$name_layout = $this->_getNameLayout();
		$this->getLayout()->addBlock(
			'Magento\Framework\View\Element\RendererList',
			$name_layout.'.renderlist',
			$this->getNameInLayout(),
			$name_layout.'.details.renderers'
		);
		$this->getLayout()->addBlock(
			'Sm\ListingTabs\Block\Product\Renderer\Listing\Configurable',
			$name_layout.'.colorswatches',
			$name_layout.'.renderlist',
			'configurable'
			)->setTemplate('Sm_ListingTabs::product/listing/renderer.phtml')->setData(['tagid' =>  $this->_tagId()]);
	}
	
	public function _isAjax()
	{
		$isAjax = $this->getRequest()->isAjax();
		$is_ajax_listing_tabs = $this->getRequest()->getPost('is_ajax_listing_tabs');
		if ($isAjax && $is_ajax_listing_tabs == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function _getFormKey(){
		$key_form = $this->_objectManager->get('Magento\Framework\Data\Form\FormKey');
		return $key_form->getFormKey();  
	}
	
	/* protected function _toHtml()
    {
		if (!(int)$this->_getConfig('isactive', 1)) return ;
		if ($this->_isAjax()) {
			$template_file = "Sm_ListingTabs::default_items.phtml";
		}else{
			$template_file = $this->getTemplate();
			$template_file = (!empty($template_file)) ? $template_file : "Sm_ListingTabs::default.phtml";
		}
        $this->setTemplate($template_file);
        return parent::_toHtml();
    } */
	
	protected function _toHtml()
    {
		if (!(int)$this->_getConfig('isactive', 1)) return ;
		if ($this->_isAjax()) {
			
			$datacustom_content = $this->getRequest()->getPost('datacustomcontent');
			//var_dump($datacustom_content);
			
			if($datacustom_content == 'data-custom-content'){
				$template_file = "default_items_v3.phtml";
			} else if($datacustom_content == 'data-custom-left'){
				$template_file = "default_items_v4.phtml";
			} else if($datacustom_content == 'data-custom-center'){
				$template_file = "default_items_v6.phtml";
			} else {
				$template_file = "default_items.phtml";
			}
		}else{
			$template_file = $this->getTemplate();
			$template_file = (!empty($template_file)) ? $template_file : "Sm_ListingTabs::default.phtml";
		}
		//var_dump($template_file);
        $this->setTemplate($template_file);
        return parent::_toHtml();
    }
	
	public function _getList (){
		$type_show = $this->_getConfig('type_show');
		$type_listing = $this->_getConfig('type_listing');
		$under_price = $this->_getConfig('under_price');
		$tabs_select = $this->_getConfig('tabs_select');
		$category_select = $this->_getConfig('category_select');
		$order_by = $this->_getConfig('order_by');
		$order_dir = $this->_getConfig('order_dir');
		$limitation = $this->_getConfig('limitation');
		$type_filter = $this->_getConfig('type_filter');
		$category_id = $this->_getConfig('category_tabs');
		$field_tabs = $this->_getConfig('field_tabs');
		$list = [];
		$cat_filter = [];
		switch($type_filter){
			case 'categories':
				if (!empty($category_id)){
					$catids = explode(',',$category_id);
					$all_childrens = $this->_getAllChildren($catids);
					if (!empty($all_childrens)){
						$flag = true;
						foreach($all_childrens as $key => $children){
							$cat_children = implode(',',  $children);
							$object_manager = $this->_objectManager->create('Magento\Catalog\Model\Category')->load($key);
							$list[$key]['name_tab'] =  $object_manager->getName();
							$list[$key]['id_tab'] = $key;
							$list[$key]['cat_children'] = $cat_children;
							if ($flag){
								$list[$key]['sel'] = 'active';
								$list[$key]['products_list'] = $this->_getProductsBasic($children);
								$flag = false;
							}
						}
					}
				}
			break;
			case 'fieldproducts':
				if (!empty($category_select)){
					$catids = explode(',',$category_select);
					$all_childrens = $this->_getAllChildren($catids, true);
					if (!empty($field_tabs)){
						$tabs = explode(',',$field_tabs);
						$flag = true;
						foreach($tabs as $key => $tab){
							$list[$tab]['name_tab'] =  $this->getLabel($tab);
							$list[$tab]['id_tab'] = $tab;
							$list[$tab]['cat_children'] = implode(',',$all_childrens);
							if ($flag){
								$list[$tab]['sel'] = 'active';
								$list[$tab]['products_list'] = $this->_getProductsBasic($all_childrens,  $tab);
								$flag = false;
							}
						}
					}
				}
			break;
		}
		
		return $list;
	}	
	
	public function _ajaxLoad(){
		$catids = $this->getRequest()->getPost('catids');
		$tab_id = $this->getRequest()->getPost('tab_id');
		$type_filter = $this->_getConfig('type_filter');
		if ($type_filter == 'fieldproducts'){
			return  $this->_getProductsBasic($catids,  $tab_id);
		}else{
			return $this->_getProductsBasic($catids);
		}
		
	}
	
	public function getLabel($filter)
	{
		switch ($filter) {
			case 'name':
				return __('Name');
			case 'entity_id':
				return __('Id');
			case 'price':
				return __('Price');
			case 'lastest_products':
				return __('New Products');
			case 'num_rating_summary':
				return __('Top Rating');
			case 'num_reviews_count':
				return __('Most Reviews');
			case 'num_view_counts':
				return __('Most Viewed');
			case 'ordered_qty':
				return __('Most Selling');
		}
	}
	
	private function _getAllChildren($catids, $group = false) {
		$list = [];
		$cat_tmps = '';
		!is_array($catids) && $catids = preg_split('/[\s|,|;]/', $catids, -1, PREG_SPLIT_NO_EMPTY);
		if (!empty($catids) && is_array($catids)){
			foreach($catids as $i => $catid ) {
				$object_manager = $this->_objectManager->create('Magento\Catalog\Model\Category')->load($catid);
				if ($group){
					$cat_tmps .= $object_manager->getAllChildren().($i < count($catids) - 1 ? ',' : '');
				}else{
					$list[$catid] = $object_manager->getAllChildren(true);
				}
				
			}
			if ($group){
				if (!empty($cat_tmps)){
					$list = explode(',',$cat_tmps);
					return array_unique($list);
				}
			}
		}
		return $list;
	}
	
	public function _getOrderFields(& $collection , $tab = false) {
		$order_by = $tab ? $tab : $this->_getConfig('order_by');
		$order_dir = $this->_getConfig('order_dir');
		switch ($order_by) {
			default:
			case 'entity_id':
			case 'name':
                $collection->addAttributeToSort($order_by, $order_dir);
				break;
			case 'lastest_products':		
			case 'created_at':
				$tab ? $collection->getSelect()->order('created_at  DESC') : $collection->getSelect()->order('created_at ' . $order_dir . '');
				break;
			case 'price':
				$collection->getSelect()->order('final_price ' . $order_dir . '');
				break;
			case 'num_rating_summary':
				$tab ? $collection->getSelect()->order('num_rating_summary DESC') : $collection->getSelect()->order('num_rating_summary ' . $order_dir . '');
				break;
			case 'num_reviews_count':
				$tab ? $collection->getSelect()->order('num_reviews_count DESC') : $collection->getSelect()->order('num_reviews_count ' . $order_dir . '');
				break;
			case 'num_view_counts':
				$tab ? $collection->getSelect()->order('num_view_counts DESC') : $collection->getSelect()->order('num_view_counts ' . $order_dir . '');
				break;
			case 'ordered_qty':
				$tab ?  $collection->getSelect()->order('ordered_qty DESC') :  $collection->getSelect()->order('ordered_qty ' . $order_dir . '');
				break;
			
		}
		
		return $collection;
	}
	
	public function _getProductsBasic($catids = null,  $tab = false)
	{
		$type_filter = $this->_getConfig('type_filter');
		$limit = $this->_getConfig('limitation');    
		$type_listing = $this->_getConfig('type_listing');   
		$under_price = $this->_getConfig('under_price', '4.99'); 	
        $catids =  $catids == null ? $this->_getConfig('category_tabs') : $catids;
		!is_array($catids) && $catids = preg_split('/[\s|,|;]/', $catids, -1, PREG_SPLIT_NO_EMPTY);
		$collection = $this->_objectManager->create(\Magento\Catalog\Model\ResourceModel\Product\Collection::class);
		$connection  = $this->_resource->getConnection();
		if ($type_listing == 'under'){
			$collection->addPriceDataFieldFilter('%s < %s', ['min_price', $under_price]);
		}
		$collection->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
			->addAttributeToSelect('special_from_date')
			->addAttributeToSelect('special_to_date')
			->addUrlRewrite()
			->setStoreId($this->_storeId)
			->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
	    if ($type_listing == 'deals'){
			$now = date('Y-m-d H:i:s');
			$collection->addAttributeToFilter('special_price', ['neq' => ''])
					   ->addAttributeToFilter('special_from_date', ['lteq' => date('Y-m-d  H:i:s', strtotime($now))])
					   ->addAttributeToFilter('special_to_date', ['gteq' => date('Y-m-d  H:i:s', strtotime($now))]);
		}
		$collection->addAttributeToFilter('is_saleable', ['eq' => 1], 'left');
        if (!empty($catids) && $catids){
            $collection->joinField(
                'category_id',
                $connection->getTableName($this->_resource->getTableName('catalog_category_product')),
                'category_id',
                'product_id=entity_id',
                null,
                'left'
            )->addAttributeToFilter([['attribute' => 'category_id', 'in' => [$catids]]]);
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
		$this->_getViewedCount($collection);
		$this->_getOrderedQty($collection);
		$this->_getReviewsCount($collection);
		$tab ? $this->_getOrderFields($collection , $tab) : $this->_getOrderFields($collection);
		$collection->clear();
		$collection->getSelect()->distinct(true)->group('e.entity_id');
		$start = (int)$this->getRequest()->getPost('ajax_listingtabs_start');
		if (!$start) $start = 0;
		$_limit = $limit;
		$_limit = $_limit <= 0 ? 0 : $_limit;
		$collection->getSelect()->limit($_limit, $start);
        //echo $collection->getSelect()->__toString(); die('haiza');
		return  $collection;
	}
	
	private function _getOrderedQty(& $collection) {
		$connection  = $this->_resource->getConnection();
		$select = $connection
            ->select()
            ->from($connection->getTableName($this->_resource->getTableName('sales_bestsellers_aggregated_monthly')), array('product_id', 'ordered_qty' => 'SUM(`qty_ordered`)'))
            ->where("store_id=".$this->_storeId."")
			->group('product_id');

        $collection->getSelect()
            ->joinLeft(array('bs' => $select),
                'bs.product_id = e.entity_id');			
        return $collection;
	}
	
	private function _getViewedCount(& $collection) {
		$connection  = $this->_resource->getConnection();
		$select = $connection
			->select()
			->from($connection->getTableName($this->_resource->getTableName('report_event')), ['*', 'num_view_counts' => 'COUNT(`event_id`)'])
			->where("event_type_id = 1 AND store_id=".$this->_storeId."")
			->group('object_id');
		$collection->getSelect()
			->joinLeft(['mv' => $select],
				'mv.object_id = e.entity_id');
		return $collection;			
	}
	
	private function _getReviewsCount(& $collection)
	{	$connection  = $this->_resource->getConnection();
		$collection->getSelect()
			->joinLeft(
				["ra" => $connection->getTableName($this->_resource->getTableName('review_entity_summary'))],
				"e.entity_id = ra.entity_pk_value AND ra.store_id=" . $this->_storeId,
				[
					'num_reviews_count' => "ra.reviews_count",
					'num_rating_summary' => "ra.rating_summary"
				]
			);
		return $collection;
	}
	
	public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {	
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->_objectManager->get('\Magento\Framework\Url\Helper\Data')->getEncodedUrl($url),
            ]
        ];
    }
	
	public function getAjaxUrl(){
		return $this->_storeManager->getStore()->getBaseUrl().'listingtabs/index/index';
	}
	
	public function _setSerialize($str)
    {
        $serializer = $this->_objectManager->get('\Magento\Framework\Serialize\Serializer\Json');
		if (!empty($str)) {
			$items = $serializer->serialize($str);
			return $items;
		}
        return true;
    }
}
