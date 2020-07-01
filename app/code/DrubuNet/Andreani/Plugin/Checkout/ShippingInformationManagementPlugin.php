<?php

namespace DrubuNet\Andreani\Plugin\Checkout;

class ShippingInformationManagementPlugin
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * ShippingInformationManagementPlugin constructor.
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getShippingAddress()->getExtensionAttributes();

        $quote = $this->quoteRepository->getActive($cartId);

        $shippingAddress = $quote->getShippingAddress();

        $shippingAddress->setDni($extAttributes->getDni());
        $shippingAddress->setAltura($extAttributes->getAltura());
        $shippingAddress->setPiso($extAttributes->getPiso());
        $shippingAddress->setDepartamento($extAttributes->getDepartamento());
        $shippingAddress->setCelular($extAttributes->getCelular());
        $shippingAddress->setObservaciones($extAttributes->getObservaciones());
    }
}
