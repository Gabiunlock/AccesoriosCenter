<?php
namespace DrubuNet\Andreani\Plugin\Magento\Quote\Model\Quote\Address;

class BillingAddressPersister
{

    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function beforeSave(
        \Magento\Quote\Model\Quote\Address\BillingAddressPersister $subject,
        $quote,
        \Magento\Quote\Api\Data\AddressInterface $address,
        $useForShipping = false
    ) {

        $extAttributes = $address->getExtensionAttributes();

        if (!empty($extAttributes)) {
            try {
                $address->setAltura($extAttributes->getAltura());
                $address->setPiso($extAttributes->getPiso());
                $address->setDepartamento($extAttributes->getDepartamento());
                $address->setCelular($extAttributes->getCelular());
                $address->setDni($extAttributes->getDni());
                $address->setObservaciones($extAttributes->getObservaciones());
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
    }
}