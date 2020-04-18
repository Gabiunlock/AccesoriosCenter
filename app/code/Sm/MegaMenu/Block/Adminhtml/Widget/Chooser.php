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
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Registry;

class Chooser extends \Magento\Widget\Block\Adminhtml\Widget\Chooser
{
	/**
	 * @var \Magento\Framework\Data\Form\Element\Factory
	 */
	protected $_elementFactory;

	/**
	 * @var \Magento\Framework\Json\EncoderInterface
	 */
	protected $_jsonEncoder;

	protected $_coreRegistry;

	/**
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
	 * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
	 * @param array $data
	 */
	public function __construct(
		Context $context,
		Registry $registry,
		EncoderInterface $jsonEncoder,
		Factory $elementFactory,
		array $data = []
	) {
		$this->_jsonEncoder = $jsonEncoder;
		$this->_elementFactory = $elementFactory;
		$this->_coreRegistry = $registry;
		parent::__construct($context, $jsonEncoder, $elementFactory);
	}

	/**
	 * Chooser source URL getter
	 *
	 * @return string
	 */
	public function getSourceUrl()
	{
		return $this->_getData('source_url');
	}

	/**
	 * Chooser form element getter
	 *
	 * @return \Magento\Framework\Data\Form\Element\AbstractElement
	 */
	public function getElement()
	{
		return $this->_getData('element');
	}

	/**
	 * Convert Array config to Object
	 *
	 * @return \Magento\Framework\DataObject
	 */
	public function getConfig()
	{
		if ($this->_getData('config') instanceof \Magento\Framework\DataObject) {
			return $this->_getData('config');
		}

		$configArray = $this->_getData('config');
		$config = new \Magento\Framework\DataObject();
		$this->setConfig($config);
		if (!is_array($configArray)) {
			return $this->_getData('config');
		}

		// define chooser label
		if (isset($configArray['label'])) {
			$config->setData('label', __($configArray['label']));
		}

		// chooser control buttons
		$buttons = ['open' => __('Choose...'), 'close' => __('Close')];
		if (isset($configArray['button']) && is_array($configArray['button'])) {
			foreach ($configArray['button'] as $id => $label) {
				$buttons[$id] = __($label);
			}
		}
		$config->setButtons($buttons);

		return $this->_getData('config');
	}

	/**
	 * Unique identifier for block that uses Chooser
	 *
	 * @return string
	 */
	public function getUniqId()
	{
		return $this->_getData('uniq_id');
	}

	/**
	 * Form element fieldset id getter for working with form in chooser
	 *
	 * @return string
	 */
	public function getFieldsetId()
	{
		return $this->_getData('fieldset_id');
	}

	/**
	 * Flag to indicate include hidden field before chooser or not
	 *
	 * @return bool
	 * @SuppressWarnings(PHPMD.BooleanGetMethodName)
	 */
	public function getHiddenEnabled()
	{
		return $this->hasData('hidden_enabled') ? (bool)$this->_getData('hidden_enabled') : true;
	}

	/**
	 * Return chooser HTML and init scripts
	 *
	 * @return string
	 */
	protected function _toHtml()
	{
		if(is_null($this->_coreRegistry->registry('menuitems_widget_chooser'))){
			return parent::_toHtml();
		}
		$this->_coreRegistry->unregister('menuitems_widget_chooser');
		$element = $this->getElement();
		//$htmlIdPrefix = $element->getForm()->getHtmlIdPrefix();
		/* @var $fieldset \Magento\Framework\Data\Form\Element\Fieldset */
		// $fieldset = $element->getForm()->getElement($this->getFieldsetId());
		$chooserId = $this->getUniqId();
		$config = $this->getConfig();

		$hiddenHtml = '';
		if ($this->getHiddenEnabled()) {
			$hidden = $this->_elementFactory->create('hidden', ['data' => $element->getData()]);
			$hidden->setId("{$chooserId}value")->setForm($element->getForm());
			if ($element->getRequired()) {
				$hidden->addClass('required-entry');
			}
			$hiddenHtml = $hidden->getElementHtml();
			$element->setValue('');
		}

		$buttons = $config->getButtons();
		$chooseButton = $this->getLayout()->createBlock(
			'Magento\Backend\Block\Widget\Button'
		)->setType(
			'button'
		)->setId(
			$chooserId . 'control'
		)->setClass(
			'btn-chooser'
		)->setLabel(
			$buttons['open']
		)->setOnclick(
			$chooserId . '.choose();$$(\'.data_type\')[0].id=\''.$chooserId.'value\';'
		)->setDisabled(
			$element->getReadonly()
		);

		// render label and chooser scripts
		$configJson = $this->_jsonEncoder->encode($config->getData());

		$js= '
            <script type="text/javascript">
            require(["prototype", "mage/adminhtml/wysiwyg/widget"], function(){
            //<![CDATA[
                (function() {
                    var instantiateChooser = function() {
                        window.'.$chooserId.' = new WysiwygWidget.chooser("'.$chooserId.'",
                            "'.$this->getSourceUrl().'",
                            '.$configJson.'
                        );
                        if ($("'.$chooserId.'value")) {
                            $("'.$chooserId.'value").advaiceContainer = "'.$chooserId.'advice-container";
                        }
                    }
                     jQuery(instantiateChooser);
                })();
            //]]>
            });
            </script>
        ';
		return '<div id="'.'box_'.$chooserId.'">
            <label class="widget-option-label renderer-input" id="'.$chooserId . 'label">'.($this->getLabel() ? $this->getLabel() : __('Not Selected')).'</label>
            <div id="'.$chooserId.'advice-container" class="hidden"></div>
        '.$hiddenHtml . $chooseButton->toHtml().$js.
		'</div>';
	}
}