<?php

namespace DrubuNet\Andreani\Plugin\Customer;
use Magento\Framework\Exception\InputException;

/**
 * Class AddressRepositoryPlugin
 *
 * @description Plugin para sobre-escribir el comportamiento del guardado de direcciones cuando el usuario es invitado
 * y se va a registrar luego de una compra exitosa, debido a un problema de magento al usar atributos custom en las direcciones.
 *
 * @author Drubu Team
 * @package DrubuNet\Andreani\Plugin\Customer
 */
class AddressRepositoryPlugin
{
    /**
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;

    /**
     * @var \Magento\Customer\Model\AddressRegistry
     */
    protected $addressRegistry;

    /**
     * Directory data
     *
     * @var \Magento\Directory\Helper\Data
     */
    protected $directoryData;

    /**
     * AddressPlugin constructor.
     * @param \Magento\Customer\Model\AddressFactory $addressFactory
     * @param \Magento\Customer\Model\AddressRegistry $addressRegistry
     * @param \Magento\Customer\Model\CustomerRegistry $customerRegistry
     * @param \Magento\Directory\Helper\Data $directoryData
     */
    public function __construct(
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Customer\Model\AddressRegistry $addressRegistry,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        \Magento\Directory\Helper\Data $directoryData
    ) {
        $this->addressFactory = $addressFactory;
        $this->addressRegistry = $addressRegistry;
        $this->customerRegistry = $customerRegistry;
        $this->directoryData = $directoryData;
    }

    /**
     * @param $addressRepository
     * @param \Closure $proceed
     * @param $address
     * @return \Magento\Customer\Api\Data\AddressInterface
     * @throws InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    /*
     * Pasa por aca cada vez que se guarda una direccion. Sea por checkout o customer/address
     */
    public function aroundSave(
        $addressRepository,
        \Closure $proceed,
        $address
    )
    {
        $addressModel = null;
        $customerModel = $this->customerRegistry->retrieve($address->getCustomerId());
        if ($address->getId()) {
            $addressModel = $this->addressRegistry->retrieve($address->getId());
        }

        if ($addressModel === null) {
            /** @var \Magento\Customer\Model\Address $addressModel */
            $addressModel = $this->addressFactory->create();
            $addressModel->updateData($address);
            $addressModel->setCustomer($customerModel);
        } else {
            $addressModel->updateData($address);
        }

        $addressArray = $address->__toArray();

        if(isset($addressArray['altura']))
            $addressModel->setAltura($addressArray['altura']);
        if(isset($addressArray['piso']))
            $addressModel->setPiso($addressArray['piso']);
        if(isset($addressArray['departamento']))
            $addressModel->setDepartamento($addressArray['departamento']);
        if(isset($addressArray['observaciones']))
            $addressModel->setObservaciones($addressArray['observaciones']);
        if(isset($addressArray['dni']))
            $addressModel->setDni($addressArray['dni']);
        if(isset($addressArray['celular']))
            $addressModel->setCelular($addressArray['celular']);

        $inputException = $this->_validate($addressModel);
        if ($inputException->wasErrorAdded()) {
            throw $inputException;
        }
        $addressModel->save();
        $address->setId($addressModel->getId());
        // Clean up the customer registry since the Address save has a
        // side effect on customer : \Magento\Customer\Model\ResourceModel\Address::_afterSave
        $this->customerRegistry->remove($address->getCustomerId());
        $this->addressRegistry->push($addressModel);
        $customerModel->getAddressesCollection()->clear();

        return $addressModel->getDataModel();
    }

    /**
     * Validate Customer Addresses attribute values.
     *
     * @param CustomerAddressModel $customerAddressModel the model to validate
     * @return InputException
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function _validate($customerAddressModel)
    {
        $exception = new InputException();
        if ($customerAddressModel->getShouldIgnoreValidation()) {
            return $exception;
        }

        if (!\Zend_Validate::is($customerAddressModel->getFirstname(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'firstname']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getLastname(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'lastname']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getStreetLine(1), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'street']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getCity(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'city']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getTelephone(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'telephone']));
        }

        $havingOptionalZip = $this->directoryData->getCountriesWithOptionalZip();
        if (!in_array($customerAddressModel->getCountryId(), $havingOptionalZip)
            && !\Zend_Validate::is($customerAddressModel->getPostcode(), 'NotEmpty')
        ) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'postcode']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getCountryId(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'countryId']));
        }

        if ($this->directoryData->isRegionRequired($customerAddressModel->getCountryId())) {
            $regionCollection = $customerAddressModel->getCountryModel()->getRegionCollection();
            if (!$regionCollection->count() && empty($customerAddressModel->getRegion())) {
                $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'region']));
            } elseif (
                $regionCollection->count()
                && !in_array(
                    $customerAddressModel->getRegionId(),
                    array_column($regionCollection->getData(), 'region_id')
                )
            ) {
                $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'regionId']));
            }
        }
        return $exception;
    }
}