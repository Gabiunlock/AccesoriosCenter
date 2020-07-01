<?php

namespace DrubuNet\Andreani\Block\Catalog\Product\View;

use Magento\Framework\View\Element\Template;

/**
 * Class CalculadorCuotas
 *
 * @description
 *
 * @author Mauro Maximiliano Martinez <mmartinez@ids.net.ar>
 * @package DrubuNet\Andreani\Block\Catalog\Product\View\CostoEnvio
 */
class CostoEnvio extends Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * CalculadorCuotas constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct
    (
        Template\Context $context,
        array $data,
        \Magento\Framework\Registry $registry
    )
    {
        $this->_registry = $registry;

        parent::__construct($context, $data);
    }

    /**
     * @return float
     */
    public function getMontoFinanciacion()
    {
        return $this->_registry->registry('current_product')->getFinalPrice();
    }

    public function getProductId()
    {
        return $this->_registry->registry('current_product')->getId();
    }

}