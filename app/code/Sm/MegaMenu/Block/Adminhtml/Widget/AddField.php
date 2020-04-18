<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\Widget;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Option\ArrayPool;
use Magento\Widget\Model\Widget;
use Magento\Framework\DataObject;

class AddField extends \Magento\Backend\Block\Template
{
	protected	$_p = [];
	protected	$_b = [];

	/**
	 * Element type used by default if configuration is omitted
	 * @var string
	 */
	protected $_defaultElementType = 'text';

	/**
	 * @var \Magento\Widget\Model\Widget
	 */
	protected $_widget;

	/**
	 * @var \Magento\Framework\Option\ArrayPool
	 */
	protected $_sourceModelPool;

	protected $_coreRegistry;

	/**
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Framework\Data\FormFactory $formFactory
	 * @param \Magento\Framework\Option\ArrayPool $sourceModelPool
	 * @param \Magento\Widget\Model\Widget $widget
	 * @param array $data
	 */
	public function __construct(
		DataObject $dataObject,
		Context $context,
		Registry $registry,
		FormFactory $formFactory,
		ArrayPool $sourceModelPool,
		array $data = []
	) {
		$this->_sourceModelPool = $sourceModelPool;
		$this->_coreRegistry = $registry;
		$this->_p = new $dataObject;
		$this->_b = new $dataObject;
		parent::__construct($context, $data);
	}

	public function addFieldWidget($data, $fieldset){
		$param = $this->_p;
		$button = $this->_b;
		$arr = $data['data'];
		$butt = $arr['helper_block']['data'];
		$param->setKey(($arr['id'])?$arr['id']:'empty_id');
		$param->setVisible(1);
		$param->setRequired(($arr['required'])?$arr['required']:false);
		$param->setType('label');
		$param->setSortOrder(($arr['sort_order'])?$arr['sort_order']:1);
		$param->setValues([]);
		$param->setLabel(($arr['label'])?$arr['label']:'Empty');

		$button->setButton(($butt['button'])?$butt['button']:['open'=>'Select...']);
		$button->setType(($arr['helper_block']['type'])?$arr['helper_block']['type']:'');
		$param->setHelperBlock($button);
		return $this->_addField($param, $fieldset);
	}

	/**
	 * Fieldset getter/instantiation
	 *
	 * @return \Magento\Framework\Data\Form\Element\Fieldset
	 */
	public function getMainFieldset($fieldset)
	{
		if ($this->_getData('main_fieldset') instanceof \Magento\Framework\Data\Form\Element\Fieldset) {
			return $this->_getData('main_fieldset');
		}
		$this->setData('main_fieldset', $fieldset);

		// add dependence javascript block
//		$block = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence');
//		$this->setChild('form_after', $block);

		return $fieldset;
	}

	/**
	 * Add field to Options form based on parameter configuration
	 *
	 * @param \Magento\Framework\DataObject $parameter
	 * @return \Magento\Framework\Data\Form\Element\AbstractElement
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function _addField($parameter, $fieldset)
	{
		$form = $this->getForm();
		$fieldset = $this->getMainFieldset($fieldset);
		//$form->getElement('options_fieldset');

		// prepare element data with values (either from request of from default values)
		$fieldName = $parameter->getKey();
		$data = [
			'name' => $fieldName,
			'label' => __($parameter->getLabel()),
			'required' => $parameter->getRequired(),
			'class' => 'widget-option',
			'note' => __($parameter->getDescription()),
		];

		if ($values = $this->getWidgetValues()) {
			$data['value'] = isset($values[$fieldName]) ? $values[$fieldName] : '';
		} else {
			$data['value'] = $parameter->getValue();
			//prepare unique id value
			if ($fieldName == 'unique_id' && $data['value'] == '') {
				$data['value'] = microtime(1);
			}
		}

		// prepare element dropdown values
		if ($values = $parameter->getValues()) {
			// dropdown options are specified in configuration
			$data['values'] = [];
			foreach ($values as $option) {
				$data['values'][] = [
					'label' => __($option['label']),
					'value' => $option['value']
				];
			}
			// otherwise, a source model is specified
		} elseif ($sourceModel = $parameter->getSourceModel()) {
			$data['values'] = $this->_sourceModelPool->get($sourceModel)->toOptionArray();
		}

		// prepare field type or renderer
		$fieldRenderer = null;
		$fieldType = $parameter->getType();
		// hidden element
		if (!$parameter->getVisible()) {
			$fieldType = 'hidden';
			// just an element renderer
		} elseif ($fieldType && $this->_isClassName($fieldType)) {
			$fieldRenderer = $this->getLayout()->createBlock($fieldType);
			$fieldType = $this->_defaultElementType;
		}

		// instantiate field and render html
		$field = $fieldset->addField($fieldName, $fieldType, $data);
		if ($fieldRenderer) {
			$field->setRenderer($fieldRenderer);
		}

		// extra html preparations
		if ($helper = $parameter->getHelperBlock()) {
			if($this->_coreRegistry->registry('menuitems_widget_chooser'))
				$this->_coreRegistry->unregister('menuitems_widget_chooser');
			$this->_coreRegistry->register('menuitems_widget_chooser', 1); // cho phep block Sm\MegaMenu\Block\Adminhtml\Widget\Chooser check widget available for megamenu
			$helperBlock = $this->getLayout()->createBlock(
				$helper->getType(),
				'',
				['data' => $helper->getData()]
			);
			if ($helperBlock instanceof \Magento\Framework\DataObject) {
				$helperBlock->setConfig(
					$helper->getData()
				)->setFieldsetId(
					$fieldset->getId()
				)->prepareElementHtml(
					$field
				);
			}
		}

		return $field;
	}

	/**
	 * Checks whether $fieldType is a class name of custom renderer, and not just a type of input element
	 *
	 * @param string $fieldType
	 * @return bool
	 */
	protected function _isClassName($fieldType)
	{
		return preg_match('/[A-Z]/', $fieldType) > 0;
	}

	/**
	 * Return custom button HTML
	 *
	 * @param array $data Button params
	 * @return string
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function _getButtonHtml($data)
	{
		$html = '<button type="button"';
		$html .= ' class="scalable ' . (isset($data['class']) ? $data['class'] : '') . '"';
		$html .= isset($data['onclick']) ? ' onclick="' . $data['onclick'] . '"' : '';
		$html .= isset($data['style']) ? ' style="' . $data['style'] . '"' : '';
		$html .= isset($data['id']) ? ' id="' . $data['id'] . '"' : '';
		$html .= '>';
		$html .= isset($data['title']) ? '<span><span><span>' . $data['title'] . '</span></span></span>' : '';
		$html .= '</button>';

		return $html;
	}
}