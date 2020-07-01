<?php

namespace DrubuNet\Andreani\Block\Checkout\Cart;

use Magento\Framework\View\Element\Template;

/**
 * Class CalculadorCuotas
 *
 * @description
 *
 * @author Mauro Maximiliano Martinez <mmartinez@ids.net.ar>
 * @package DrubuNet\Andreani\Block\Checkout\Cart\CostoEnvio
 */
class CostoEnvio extends Template
{
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_cart;

    /**
     * CalculadorCuotas constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Checkout\Model\Cart $cart
     */
    public function __construct
    (
        Template\Context $context,
        array $data,
        \Magento\Framework\Registry $registry,
        \Magento\Checkout\Model\Cart $cart
    )
    {
        $this->_cart = $cart;

        parent::__construct($context, $data);
    }

}