<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\MenuItems\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;
use Magento\Framework\View\Layout;
use Magento\Backend\Helper\Data;
use Magento\Framework\DataObject as ObjectData;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements
	\Magento\Backend\Block\Widget\Tab\TabInterface
{
	protected $_data;
	protected $_systemStore;
	protected $_objectManager;
	protected $_blockFactory;
	protected $_dataObject = [];

	/**
	 * Adminhtml data
	 *
	 * @var \Magento\Backend\Helper\Data
	 */
	protected $_backendData = null;

	public function __construct(
		Context $context,
		Registry $registry,
		ObjectData $dataObject,
		FormFactory $formFactory,
		Store $systemStore,
		Layout $layout,
		Data $backendData,
		ObjectManagerInterface $objectManagerInterface,
		array $data = []
	) {
		$this->_systemStore = $systemStore;
		$this->_blockFactory = $layout;
		$this->_dataObject = $dataObject;
		$this->_backendData = $backendData;
		$this->_objectManager = $objectManagerInterface;
		parent::__construct($context, $registry, $formFactory, $data);
	}

	public function _modelMenuItems()
	{
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function _modelMenuGroup()
	{
		return $this->_objectManager->create('Sm\MegaMenu\Model\ResourceModel\MenuGroup\Collection');
	}

	protected function _prepareForm()
	{
		$modelGroup = $this->_coreRegistry->registry('megamenu_menugroup');

		if($this->_objectManager->get('Magento\Backend\Model\Session')->getMegamenuMenuitems())
		{
			$model = $this->_objectManager->get('Magento\Backend\Model\Session')->getMegamenuMenuitems();
			$this->_objectManager->get('Magento\Backend\Model\Session')->setMegamenuMenuitems(null);
		}elseif($this->_coreRegistry->registry('megamenu_menuitems'))
		{
			$model = $this->_coreRegistry->registry('megamenu_menuitems');
		}


		if($model->getItemsId()){
			$model->setParentId($model->getParentId());
			if ($model->getColsNb())
				$col_max = $model->getColsNb();
			else
				$col_max = 0;
		}
		else{
			$dataObj = $this->_dataObject;
			$model = new $dataObj;
			$model->setData([
				'cols_nb' => '6',
				'group_id' => $modelGroup->getGroupId()
			]);
		}
		/*
         * Checking if user have permissions to save information
         */
		if ($this->_isAllowedAction('Sm_MegaMenu::save')) {
			$isElementDisabled = false;
		} else {
			$isElementDisabled = true;
		}

		/** @var \Magento\Framework\Data\Form $form */
		$form = $this->_formFactory->create();
		$objectManager = $this->_objectManager;

		//$form->setHtmlIdPrefix('menuitems_');
		$fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Items Information')]);

		if ($model->getItemsId()) {
			$fieldset->addField('items_id', 'hidden', ['name' => 'items_id']);
		}

		if ($modelGroup->getGroupId()) {
			$fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
		}

		$fieldset->addField(
			'title',
			'text',
			[
				'name' => 'title',
				'label' => __('Items Title'),
				'title' => __('Items Title'),
				'required' => true,
				'disabled' => $isElementDisabled
			]
		);

		$fieldset->addField(
			'status',
			'select',
			[
				'label' => __('Status'),
				'title' => __('Items Status'),
				'name' => 'status',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\Status')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$fieldset->addField(
			'show_title',
			'select',
			[
				'label' => __('Show Title'),
				'title' => __('Show Title'),
				'name' => 'show_title',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\YesNo')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$fieldset->addField(
			'description',
			'textarea',
			[
				'label' => __('Description'),
				'title' => __('Description'),
				'style' => 'height:14em;',
				'name'  => 'description',
				'disabled' => $isElementDisabled
			]
		);

		$fieldset->addField(
			'align',
			'select',
			[
				'label' => __('Align'),
				'title' => __('Align'),
				'name' => 'align',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\Align')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$item = $fieldset->addField(
			'parent_id',
			'select',
			[
				'label' => __('Parent Items'),
				'title' => __('Parent Items'),
				'name' => 'parent_id',
				'required' => true,
				'values' => ($modelGroup->getGroupId() ? $this->_getItemsByGroupId($modelGroup->getGroupId()) : $this->_getItemsByGroupId()),
				'disabled' => $isElementDisabled
			]
		);

		$item->setOnchange('loadPosItems(this)');

		$order = $fieldset->addField(
			'order_item',
			'select',
			[
				'label' => __('Order Items'),
				'title' => __('Order Items'),
				'name' => 'order_item',
				'required' => true,
				'values' => ($model->getItemsId() ? $this->_getOrderByParentId($model[$item->getId()]) : $this->_getOrderBegin()),
				'disabled' => $isElementDisabled
			]
		);
		$jsAfterItem = '
			<script type="text/javascript">
				function loadPosItems(element)
				{
					require(["jquery","mage/template","prototype"], function(){
						//<![CDATA[
						var objTreeitems=Class.create();
						objTreeitems.prototype=	{
							initialize: function(){
								this.opsTemp=\'<option  value="#{id}">#{title}</option>\';
									this.listItems = [];
									this.allowDisabled = 1;
									this.allowEnabled = 0;
								},
								updateItems: function(url,group_value,callback){	//update cac items = ajax
									new Ajax.Request(url,{encoding:"UTF-8",method:"post",
										parameters:{
											group:group_value //param for request
											,addprefix:true
										},
										onSuccess: function(resp){	//resp chua du lieu tra ve cua request
											resp = resp.responseText.evalJSON();	// loc lay text
											callback(resp);
										},
										onLoading : function(){
											$("loading-mask").show();
										},
										onFailure : function(resp){
											console.log(resp.responseText); //Element.setInnerHTML( display, resp.responseText);
										},
										onComplete: function(){
											$("loading-mask").hide();
										}
									});
								},
								getOptions: function(temp, list_ops){	//getOptions.bindAsEventListener(temp, list_ops), temp is template build <option...>...<> ,list_ops = [ {id:"1", title:"item1"}, {id:"2" , title:"item2"} ]
									//var element = Event.element(event);		//get this
									ops_temp = new Template(temp);	// initialize instanl template
									var ops_html = "";
									for(var i=0; i< list_ops.length; i++){
										ops_html += ops_temp.evaluate(list_ops[i].evalJSON());			//fill data to template
									}
									return ops_html;
								}
							}
						var parentItem = new objTreeitems();
						parent_val = element.value;
						if(!parent_val){
							//disable list order item option
							$("'.$order->getId().'").disabled = parentItem.allowDisabled;
						}
						else {
							$("'.$order->getId().'").disabled = parentItem.allowEnabled;
						}
						if(typeof(parentItem.listItems[parent_val])!="undefined"){
							$("'.$order->getId().'").update(parentItem.listItems[parent_val]);
							return true;
						}
						else{
							//ajax update menu items tree
							parentItem.updateItems("'.$this->_backendData->getUrl('megamenu/menuitems/getchilditems').'",
								parent_val,
								function(json_ops){ 	//{success:"1",items: [ {id:"1", title:"item1"}, {id:"2" , title:"item2"} ]}
									if(!json_ops["items"].length){
										json_ops["items"] = ["{\"id\":\"0\", \"title\":\"' . __('This item is first') . '\"}"];
									}
									str_ops = parentItem.getOptions(parentItem.opsTemp, json_ops["items"]);	//convert data json to html input select

									parentItem.listItems[parent_val] = str_ops;
									//inner string options to select box menu items
									$("'.$order->getId().'").update(str_ops);	//inner input select to $order
									filterCol(json_ops["col_max"]);
								}
							);
						}
						//]]>
					});
                }
			</script>';
		$item->setAfterElementHtml($jsAfterItem);


		$fieldset->addField(
			'position_item',
			'select',
			[
				'label' => __('Insert Items'),
				'title' => __('Insert Items'),
				'name' => 'position_item',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\PositionItem')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$col = $fieldset->addField(
			'cols_nb',
			'select',
			[
				'label' => __('Column Number'),
				'title' => __('Column Number'),
				'name' => 'cols_nb',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\ListNumCol')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$jsAfterOrder = '
				<script type="text/javascript">
						function filterCol(col_max){
							require(["jquery","mage/template","prototype"], function(){
								//<![CDATA[
								jQuery("select#'.$col->getId().' option").each(function(element){
										if(element.value > col_max){
											element.disabled = false;
										}
								});
								//]]>
							});
						}
				</script>
				';
		if($model->getItemsId()){
			$jsAfterOrder .= '
					<script type="text/javascript">
						require(["jquery","mage/template","prototype"], function(){
								//<![CDATA[
							var col_max = '.$col_max.';
							jQuery("select#'.$col->getId().' option").each(function(element){
									if(element.value > col_max){
										element.disabled = false;
									}
							});
							//]]>
						});
					</script>
			';
		}
		$col->setAfterElementHtml($jsAfterOrder);

		$fieldset->addField(
			'custom_class',
			'text',
			[
				'name' => 'custom_class',
				'label' => __('Custom Class'),
				'title' => __('Custom Class'),
				'disabled' => $isElementDisabled
			]
		);

		$icon = $fieldset->addField(
			'icon_url',
			'text',
			[
				'name' => 'icon_url',
				'label' => __('Icon'),
				'title' => __('Items Icon'),
				'note' => 'You can set link in folder media. Ex: wysiwyg/...',
				'disabled' => $isElementDisabled
			]
		);
		$url = $this->_backendData->getUrl('cms/wysiwyg_images/index');
		$storeId = null;
		$visible = true;
		$buttonsInsertImageHtml = $this->_blockFactory
			->createBlock(
				'\Magento\Backend\Block\Widget\Button',
				'',
				[
					'data' => [
						'title'	  => __('Insert Image...'),
						'label'   => __('Insert Image...'),
						'type'		=> 'button',
						'class' 	=> 'action-add-image plugin',
						'style'     => $visible ? '' : 'display:none',
						'onclick' => "MediabrowserUtility.openDialog('" .
							$url .
							"target_element_id/".$icon->getId() . "/" .
							((null !== $storeId)
								? ('store/' . $storeId . '/')
								: '')
							. "')"
					]
				]
			)->toHtml();

		$icon ->setAfterElementHtml($buttonsInsertImageHtml. $this->_getJs($icon));

		$fieldset->addField(
			'target',
			'select',
			[
				'label'     => __('Target Window'),
				'title'     => __('Target Window'),
				'name'      => 'target',
				'values'    => $objectManager->get('Sm\MegaMenu\Model\Config\Source\LinkTargets')->toOptionArray(),
			]
		);

		$type = $fieldset->addField(
			'type',
			'select',
			[
				'label'     => __('Menu Type'),
				'title'     => __('Menu Type'),
				'name'      => 'type',
				'values'    => $objectManager->get('Sm\MegaMenu\Model\Config\Source\Type')->toOptionArray(),
				'onchange'	=>'CheckType(this)',
			]
		);

		$data_type = $fieldset->addField(
			'data_type',
			'text',
			[
				'label'     => __('Data Type'),
				'title'     => __('Data Type'),
				'class'     => 'data_type',
				'name'      => 'data_type',
				'note'      => "With Menu Type: Enternal Link <br /> Http link can is full link \"http://magento.com, ...\" or short link \"magento.com, ...\". <br/>".
					"Note: With link type \"https://www.google.com.vn\", \"https://...\", you only can use short link \"google.com.vn\", ..."
			]
		);

		$addWidget = $this->_blockFactory->createBlock('\Sm\MegaMenu\Block\Adminhtml\Widget\AddField');
		$addWidget->addFieldWidget(
			[
				'data' => [
					'@' => ['type' => 'complex'],
					'id'            => 'product_id',
					'sort_order'    => '10',
					'label'			=> 'Product',
					'required'      => false,
					'helper_block'  => [
						'data' => [
							'button'  => [
								'open' => __('Select Product...')
							]
						],
						'type' => 'Magento\Catalog\Block\Adminhtml\Product\Widget\Chooser'
					]
				]
			], $fieldset
		);

		$showImgProd = $fieldset->addField(
			'show_image_product',
			'select',
			[
				'label' => __('Show Image Products'),
				'title' => __('Show Image Products'),
				'name' => 'show_image_product',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\YesNo')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$showTitleProd = $fieldset->addField(
			'show_title_product',
			'select',
			[
				'label' => __('Show Title Products'),
				'title' => __('Show Title Products'),
				'name' => 'show_title_product',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\YesNo')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$showRatingProd = $fieldset->addField(
			'show_rating_product',
			'select',
			[
				'label' => __('Show Rating Products'),
				'title' => __('Show Rating Products'),
				'name' => 'show_rating_product',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\YesNo')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$showPriceProd = $fieldset->addField(
			'show_price_product',
			'select',
			[
				'label' => __('Show Price Products'),
				'title' => __('Show Price Products'),
				'name' => 'show_price_product',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\YesNo')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$addWidget->addFieldWidget(
			[
				'data' => [
					'@' => ['type' => 'complex'],
					'id'            => 'category_id',
					'sort_order'    => '11',
					'label'			=> 'Category',
					'required'      => false,
					'helper_block'  => [
						'data' => [
							'button'  => [
								'open' => __('Select Category...')
							]
						],
						'type' => 'Magento\Catalog\Block\Adminhtml\Category\Widget\Chooser'
					]
				]
			], $fieldset
		);

		$showTitleCat = $fieldset->addField(
			'show_title_category',
			'select',
			[
				'label' => __('Show Title Category'),
				'title' => __('Show Title Category'),
				'name' => 'show_title_category',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\YesNo')->toOptionArray(),
				'disabled' => $isElementDisabled
			]
		);

		$limitCat = $fieldset->addField(
			'limit_category',
			'text',
			[
				'label' => __('Limit Category'),
				'title' => __('Limit Category'),
				'style' => "text-align:left",
				'class' => 'validate-greater-than-zero validate-number',
				'name'  => 'limit_category',
				'note'  => 'The limit category for category parents',
				'disabled' => $isElementDisabled
			]
		);

		$showSubCat = $fieldset->addField(
			'show_sub_category',
			'select',
			[
				'label' => __('Show Sub Category'),
				'title' => __('Show Sub Category'),
				'name'  => 'show_sub_category',
				'values' => $objectManager->get('Sm\MegaMenu\Model\Config\Source\YesNo')->toOptionArray(),
				'note'  => 'The show sub category for sub category (or than level 1)',
				'disabled' => $isElementDisabled
			]
		);

		$limitCatThanLv2 = $fieldset->addField(
			'limit_sub_category',
			'text',
			[
				'label' => __('Limit Sub Category'),
				'title' => __('Limit Sub Category'),
				'style' => "text-align:left",
				'class' => 'validate-greater-than-zero validate-number',
				'name'  => 'limit_sub_category',
				'note'  => 'The limit sub category for sub category (or than level 1)',
				'disabled' => $isElementDisabled
			]
		);

		$addWidget->addFieldWidget(
			[
				'data' => [
					'@' => ['type' => 'complex'],
					'id'            => 'page_id',
					'sort_order'    => '12',
					'label'			=> 'CMS Page',
					'required'      => false,
					'helper_block'  => [
						'data' => [
							'button' => [
								'open' => __('Select Page...')
							]
						],
						'type' => 'Magento\Cms\Block\Adminhtml\Page\Widget\Chooser'
					]
				]
			], $fieldset
		);

		$addWidget->addFieldWidget(
			[
				'data' => [
					'@' => ['type' => 'complex'],
					'id'            => 'block_id',
					'sort_order'    => '13',
					'label'			=> 'CMS Block',
					'required'      => false,
					'helper_block'  => [
						'data' => [
							'button'  => [
								'open' => __('Select Block...')
							]
						],
						'type' => 'Magento\Cms\Block\Adminhtml\Block\Widget\Chooser'
					]
				]
			], $fieldset
		);

		$textarea = $fieldset->addField(
			'content',
			'textarea',
			[
				'label' => __('Content'),
				'title' => __('Content'),
				'style' => 'height:14em;',
				'name'  => 'content',
				'class' => 'megamenu_content',
				'note'  => 'Content width must match the number of column pixel in the Column Number field',
				'disabled' => $isElementDisabled
			]
		);

		$html = $this->_blockFactory->createBlock(
			'\Magento\Backend\Block\Widget\Button',
			'',
			[
				'data' => [
					'label' => __('WYSIWYG Editor'),
					'type' => 'button',
					'disabled' => $isElementDisabled,
					'class' => 'action-wysiwyg',
					'onclick' => 'catalogWysiwygEditor.open(\'' . $this->_backendData->getUrl(
							'catalog/product/wysiwyg'
						) . '\', \''.$textarea->getId(). '\')',
				]
			]
		)->toHtml();

		//$block_js = $this->_blockFactory->createBlock('\Magento\Backend\Block\Template')
			//->setTemplate('Magento_Catalog::catalog/wysiwyg/js.phtml');
		//$html .= $block_js->toHtml();
		//$textarea->setAfterElementHtml($html);


		$js_type = "";
		if($model->getItemsId()){
			$type_val = $model[$type->getId()];
			$data_type_val = $model[$data_type->getId()];
			$js_type = '
					data_val['.$type_val.'] = "'.$data_type_val.'";
					CheckType($(\''.$type->getId().'\'));
			';
		}

		$type->setAfterElementHtml('
					<script type="text/javascript">
						// check type
						var data_val = new Array();
						require([
							"jquery",
							"Magento_Ui/js/modal/modal",
							"prototype",
							"mage/adminhtml/events"
						], function(jQuery,  modal){
							$$("div[id^=\''.'box_\']").each( function(element){
								element.up().up().up().hide();
							});
							
							/*$$("[id^=\''.'content\']").each(function(element){
								element.up().up().up().hide();
							});*/
							$$(".megamenu_content").each(function(element){
								element.up().up().hide();
							});
							$$(".data_type").each(function(element){
								element.up().up().hide();
								element.removeClassName("required-entry");
							});
							$(\''.$type->getId().'\').observe("focus",function(event){
								var element = Event.element(event);
								data_val[element.value] = $$(".data_type")[0].value;
							});
							'.$js_type.'
							
						});
						function CheckType(element){
							type = element.value;
							if(typeof(data_val[type]) !="undefined"){
								$$(".data_type")[0].value = data_val[type];
							}
							else{
								$$(".data_type")[0].value ="";
							}
							$$("div[id^=\''.'box_\']").each(function(element){
								element.up().up().up().hide();
							});
							$$(".data_type").each(function(element){
								element.up().up().hide();
								element.removeClassName("required-entry");
							});
							/*$$("[id^=\''.'content\']").each(function(element){
								element.up().up().up().hide();
								element.removeClassName("required-entry");
							});*/
							$$(".megamenu_content").each(function(element){
								element.up().up().hide();
								element.removeClassName("required-entry");
							});
							if(type=='.\Sm\MegaMenu\Model\Config\Source\Type::CONTENT.'){
								/*$$("[id^=\''.'content\']").each(function(element){
									element.up().up().up().show();
									element.addClassName("required-entry");
								});*/
								$$(".megamenu_content").each(function(element){
									element.up().up().show();
									element.addClassName("required-entry");
								});
							}
							else if(type!='.\Sm\MegaMenu\Model\Config\Source\Type::NORMAL.'){
								$$(".data_type").each(function(element){
									element.up().up().show();
									element.addClassName("required-entry");
								});
							}
							if(type=='.\Sm\MegaMenu\Model\Config\Source\Type::PRODUCT.'){
								$$("div[id^=\''.'box_product_id\']")[0].up().up().up().show();
							}
							if(type=='.\Sm\MegaMenu\Model\Config\Source\Type::CATEGORY.'){
								$$("div[id^=\''.'box_category_id\']")[0].up().up().up().show();
							}
							if(type=='.\Sm\MegaMenu\Model\Config\Source\Type::CMSPAGE.'){
								$$("div[id^=\''.'box_page_id\']")[0].up().up().up().show();
							}
							if(type=='.\Sm\MegaMenu\Model\Config\Source\Type::CMSBLOCK.'){
								$$("div[id^=\''.'box_block_id\']")[0].up().up().up().show();
							}
						}
					</script>
		');

		/*
		 * Form sortable items
		 * */
		$fieldset2 = $form->addFieldset('form_sortable', array(
			'legend' => ''.__('Sortable Categories Items')
		));

		$sortAble = $fieldset2->addField(
			'sort_able',
			'text',
			[
				'name'  => 'sort_able',
				'disabled' => $isElementDisabled
			]
		);
		// Setting custom renderer for content field to remove label column
		$renderer = $this->getLayout()->createBlock(
			'Sm\MegaMenu\Block\Adminhtml\Widget\Form\Renderer\SortAble'
		);
		$sortAble->setRenderer($renderer);

		$this->_eventManager->dispatch('adminhtml_menuitems_page_edit_tab_form_prepare_form', ['form' => $form]);

		// define field dependencies
		$this->setChild(
			'form_after',
			$this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence')
				->addFieldMap($type->getHtmlId(), $type->getName())
				->addFieldMap($showImgProd->getHtmlId(), $showImgProd->getName())
				->addFieldDependence($showImgProd->getName(), $type->getName(), '3')
				->addFieldMap($showTitleProd->getHtmlId(), $showTitleProd->getName())
				->addFieldDependence($showTitleProd->getName(), $type->getName(), '3')
				->addFieldMap($showRatingProd->getHtmlId(), $showRatingProd->getName())
				->addFieldDependence($showRatingProd->getName(), $type->getName(), '3')
				->addFieldMap($showPriceProd->getHtmlId(), $showPriceProd->getName())
				->addFieldDependence($showPriceProd->getName(), $type->getName(), '3')
				->addFieldMap($showTitleCat->getHtmlId(), $showTitleCat->getName())
				->addFieldDependence($showTitleCat->getName(), $type->getName(), '4')
				->addFieldMap($limitCat->getHtmlId(), $limitCat->getName())
				->addFieldDependence($limitCat->getName(), $type->getName(), '4')
				->addFieldMap($showSubCat->getHtmlId(), $showSubCat->getName())
				->addFieldDependence($showSubCat->getName(), $type->getName(), '4')
				->addFieldMap($limitCatThanLv2->getHtmlId(), $limitCatThanLv2->getName())
				->addFieldDependence($limitCatThanLv2->getName(), $type->getName(), '4')
		);

		if($model->getData())
		{
			$form->setValues($model->getData());
			$this->_data = $model;
		}
		$this->setForm($form);
	}



	public function _getItemsByGroupId($group_id=''){
		$menuItems = $this->_modelMenuItems();
		// get array list group id
		$arr[] = [
			'value'			=>	'',
			'label'     	=>	__('--Please Select--'),
		];
		if($group_id){
			$items = $menuItems->getNodesByGroupId($group_id, true);
			foreach ($items as $item)
			{
				$item_id = $item->getItemsId();
				$title = $item->getTitle();
				$arr[] = [
					'value'			=>	$item_id,
					'label'     	=>	$title,
				];
			}
		}
		return $arr;
	}

	public function _getOrderByParentId($parent_id = ''){
		$arr = [];
		$menuItems = $this->_modelMenuItems();
		// get array list group id
		if($parent_id){
			$menuItemsByGroup = $menuItems->load($parent_id);
			$data = $menuItemsByGroup->getData();
			$childItems = $menuItems->getChildsDirectlyByItem($data, 2);
			foreach ($childItems as $item)
			{
				$item_id = $item['items_id'];
				$title = '('.$item_id.') '.$item['title'];
				$arr[] = [
					'value'			=>	$item_id,
					'label'     	=>	$title,
				];
			}
		}
		return $arr;
	}

	public function _getOrderBegin(){
		$arr[] = [
			'value'			=>	'',
			'label'     	=>	__('--Please Select--'),
		];
		return $arr;
	}

	public function _toHtml()
	{
		// Get the default HTML for this option
		$html = parent::_toHtml();
		if($this->_data){
			$data = $this->_data;
			if($data->getItemsId()){
				$modelMenuitems = true;
			}
		}
		//  tam thoi khoa' objTreeitems khi edit item,
		// if(!$modelMenuitems){
		$html = '
			<script type="text/javascript">
			require(["jquery","mage/template","prototype"], function(){
			//<![CDATA[
				if(typeof objTreeitems=="undefined") {
					var objTreeitems = {};
				}
				var objTreeitems = Class.create();
				objTreeitems.prototype=	{
					initialize: function(){
						this.opsTemp=\'<option  value="#{id}">#{title}</option>\';
						this.listItems = [];
						this.allowDisabled = 1;
						this.allowEnabled = 0;
					},
					updateItems: function(url,group_value,callback){	//update cac items = ajax
						new Ajax.Request(url,{encoding:"UTF-8",method:"post",
							parameters:{
								group:group_value //param for request
								,addprefix:true
							},
							onSuccess: function(resp){	//resp chua du lieu tra ve cua request
								resp = resp.responseText.evalJSON();	// loc lay text
								callback(resp);
							},
							onLoading : function(){
								$("loading-mask").show();
							},
							onFailure : function(resp){
								console.log(resp.responseText); //Element.setInnerHTML( display, resp.responseText);
							},
							onComplete: function(){
								$("loading-mask").hide();
							}
						});
					},
					getOptions: function(temp, list_ops){	//getOptions.bindAsEventListener(temp, list_ops), temp is template build <option...>...<> ,list_ops = [ {id:"1", title:"item1"}, {id:"2" , title:"item2"} ]
						//var element = Event.element(event);		//get this
						ops_temp = new Template(temp);	// initialize instanl template
						var ops_html = "";
						for(var i=0; i< list_ops.length; i++){
							ops_html += ops_temp.evaluate(list_ops[i].evalJSON());			//fill data to template
						}
						return ops_html;
					}
				}
				var groupItem= new objTreeitems();
				var parentItem = new objTreeitems();
				var columnItem = new objTreeitems();
				//]]>
            });
			</script>'
			.$html	;
		// }
		return $html;
	}

	protected function _getJs($element){
		$js = '
            <script type="text/javascript">
            //<![CDATA[
                openEditorPopup = function(url, name, specs, parent) {
                    if ((typeof popups == "undefined") || popups[name] == undefined || popups[name].closed) {
                        if (typeof popups == "undefined") {
                            popups = new Array();
                        }
                        var opener = (parent != undefined ? parent : window);
                        popups[name] = opener.open(url, name, specs);
                    } else {
                        popups[name].focus();
                    }
                    return popups[name];
                }

                closeEditorPopup = function(name) {
                    if ((typeof popups != "undefined") && popups[name] != undefined && !popups[name].closed) {
                        popups[name].close();
                    }
                }
            //]]>
            </script>';
		return $js;
	}

	/**
	 * Prepare label for tab
	 *
	 * @return \Magento\Framework\Phrase
	 */
	public function getTabLabel()
	{
		return __('Menu Items');
	}

	/**
	 * Prepare title for tab
	 *
	 * @return \Magento\Framework\Phrase
	 */
	public function getTabTitle()
	{
		return __('Menu Items');
	}

	/**
	 * {@inheritdoc}
	 */
	public function canShowTab()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isHidden()
	{
		return false;
	}

	/**
	 * Check permission for passed action
	 *
	 * @param string $resourceId
	 * @return bool
	 */
	protected function _isAllowedAction($resourceId)
	{
		return $this->_authorization->isAllowed($resourceId);
	}
}