<?php

namespace DrubuNet\Andreani\Block\Adminhtml\Tarifa;

/**
 * Class Container
 *
 * @description Bloque contenedor del formulario de administracion de tarifas andreani
 * @author Drubu Team
 * @package DrubuNet\Andreani\Block\Adminhtml\Tarifa
 */
class Container extends \Magento\Backend\Block\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Preparing block layout
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareLayout()
    {

        $this->getToolbar()->addChild(
            'save_button',
            'Magento\Backend\Block\Widget\Button',
            [
                'label' => __('Save'),
                'data_attribute' => [
                    'role' => 'template-save',
                ],
                'class' => 'save primary',
                'onclick' => 'window.location.href = window.location.href',
            ]
        );

        return parent::_prepareLayout();
    }

    /**
     * Return edit flag for block
     *
     * @return boolean
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getEditMode()
    {
        if ($this->getModel()->getId()) {
            return true;
        }
        return false;
    }

    /**
     * Return form block HTML
     *
     * @return string
     */
    public function getForm()
    {
        return $this->getLayout()
            ->createBlock('DrubuNet\Andreani\Block\Adminhtml\Tarifa\Form')
            ->toHtml();
    }

    /**
     * Return action url for form
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save');
    }

    /**
     * Getter for single store mode check
     *
     * @return boolean
     */
    protected function isSingleStoreMode()
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    /**
     * Getter for id of current store (the only one in single-store mode and current in multi-stores mode)
     *
     * @return int
     */
    protected function getStoreId()
    {
        return $this->_storeManager->getStore(true)->getId();
    }

}