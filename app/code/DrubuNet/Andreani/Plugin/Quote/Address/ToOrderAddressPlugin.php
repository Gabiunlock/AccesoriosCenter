<?php

namespace DrubuNet\Andreani\Plugin\Quote\Address;

/**
 * Class ToOrderAddressPlugin
 * @package DrubuNet\Andreani\Quote\Model\Quote\Address
 */
class ToOrderAddressPlugin
{
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_cart;

    /**
     * ToOrderAddressPlugin constructor.
     * @param \Magento\Checkout\Model\Cart $cart
     */
    public function __construct(
        \Magento\Checkout\Model\Cart $cart
    )
    {
        $this->_cart = $cart;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\ToOrderAddress $subject
     * @param $interceptedOutput
     */
    public function afterConvert(
        \Magento\Quote\Model\Quote\Address\ToOrderAddress $subject,
        $interceptedOutput
    )
    {
        if($interceptedOutput->getAddressType()=='shipping')
        {
            $shippingAddress = $this->_cart->getQuote()->getShippingAddress();

            $interceptedOutput->setDni($shippingAddress->getDni());
            $interceptedOutput->setPiso($shippingAddress->getPiso());
            $interceptedOutput->setAltura($shippingAddress->getAltura());
            $interceptedOutput->setDepartamento($shippingAddress->getDepartamento());
            $interceptedOutput->setCelular($shippingAddress->getCelular());
            $interceptedOutput->setObservaciones($shippingAddress->getObservaciones());
        }
        else
        {
            $billingAddress = $this->_cart->getQuote()->getBillingAddress();

            $interceptedOutput->setDni($billingAddress->getDni());
            $interceptedOutput->setPiso($billingAddress->getPiso());
            $interceptedOutput->setAltura($billingAddress->getAltura());
            $interceptedOutput->setDepartamento($billingAddress->getDepartamento());
            $interceptedOutput->setCelular($billingAddress->getCelular());
            $interceptedOutput->setObservaciones($billingAddress->getObservaciones());
        }

        return $interceptedOutput;
    }


}