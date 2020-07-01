<?php
namespace DrubuNet\Andreani\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class SalesEventQuoteSubmitBeforeObserver
 * @package DrubuNet\Andreani\Observer
 */
class SalesOrderPlaceBefore implements ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    private $addressRepository;

    /**
     * SalesOrderPlaceBefore constructor.
     * @param \Magento\Customer\Model\Session $customer
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Customer\Model\Session $customer,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
    )
    {
        $this->_customerSession = $customer;
        $this->_checkoutSession = $checkoutSession;
        $this->addressRepository = $addressRepository;

    }

    /**
     * Graba en la orden el numero de sucursal andreani que tenga el quote, y el dni en el quote address
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $codigoSucursalAndreani = $this->_checkoutSession->getCodigoSucursalAndreani();
        $customer               = $this->_customerSession->getCustomer();
        $order                  = $observer->getEvent()->getOrder();
        $shippingAddress        = $order->getShippingAddress();
        $billingAddress         = $order->getBillingAddress();

        $metodoEnvio = explode('_',$order->getShippingMethod());

        if($metodoEnvio[0] ==  \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE)
        {
            $order->setCodigoSucursalAndreani($codigoSucursalAndreani);
        }

        /**
         * Parche para hacer que se guarde la altura, piso, departamento, dni, celular y observaciones en el billing
         * cuando el usuario es invitado. Esto funciona haciendo que la direccion de envio y facturacion sean las mismas,
         * ya que sino los datos no coinciden.
         */
        $billingAddress
            ->setFirstname($shippingAddress->getFirstname())
            ->setLastname($shippingAddress->getLastname())
            ->setCompany($shippingAddress->getCompany())
            ->setCity($shippingAddress->getCity())
            ->setRegionId($shippingAddress->getRegionId())
            ->setRegion($shippingAddress->getRegion())
            ->setPostcode($shippingAddress->getPostcode())
            ->setCountryId($shippingAddress->getCountryId())
            ->setTelephone($shippingAddress->getTelephone())
            ->setDni(trim($shippingAddress->getDni()))
            ->setAltura(trim($shippingAddress->getAltura()))
            ->setPiso(trim($shippingAddress->getPiso()))
            ->setDepartamento(trim($shippingAddress->getDepartamento()))
            ->setObservaciones(trim($shippingAddress->getObservaciones()))
            ->setCelular(trim($shippingAddress->getCelular()))
            ->save();

        $order->setCustomerDni($shippingAddress->getDni());

        //Guardo los atributos custom en la direccion del usuario
        if(!empty($order->getCustomerId())){
            $customerAddress = $this->addressRepository->getById($shippingAddress->getCustomerAddressId());
            $hasChange = false;
            $customAttributes = $customerAddress->getCustomAttributes();
            if(!array_key_exists('altura',$customAttributes) || array_key_exists('altura',$customAttributes) && $customAttributes['altura']->getValue() != trim($shippingAddress->getAltura())){
                $customerAddress->setCustomAttribute('altura',trim($shippingAddress->getAltura()));
                $hasChange = true;
            }
            if(!array_key_exists('dni',$customAttributes) || array_key_exists('dni',$customAttributes) && $customAttributes['dni']->getValue() != trim($shippingAddress->getDni())){
                $customerAddress->setCustomAttribute('dni',trim($shippingAddress->getDni()));
                $hasChange = true;
            }
            if(!array_key_exists('piso',$customAttributes) ||array_key_exists('piso',$customAttributes) && $customAttributes['piso']->getValue() != trim($shippingAddress->getPiso())){
                $customerAddress->setCustomAttribute('piso',trim($shippingAddress->getPiso()));
                $hasChange = true;
            }
            if(!array_key_exists('departamento',$customAttributes) || array_key_exists('departamento',$customAttributes) && $customAttributes['departamento']->getValue() != trim($shippingAddress->getDepartamento())){
                $customerAddress->setCustomAttribute('departamento',trim($shippingAddress->getDepartamento()));
                $hasChange = true;
            }
            if(!array_key_exists('observaciones',$customAttributes) || array_key_exists('observaciones',$customAttributes) && $customAttributes['observaciones']->getValue() != trim($shippingAddress->getObservaciones())){
                $customerAddress->setCustomAttribute('observaciones',trim($shippingAddress->getObservaciones()));
                $hasChange = true;
            }
            if(!array_key_exists('celular',$customAttributes) || array_key_exists('celular',$customAttributes) && $customAttributes['celular']->getValue() != trim($shippingAddress->getCelular())){
                $customerAddress->setCustomAttribute('celular',trim($shippingAddress->getCelular()));
                $hasChange = true;
            }
            /*$customerAddress->setCustomAttribute('dni',$shippingAddress->getDni());
            $customerAddress->setCustomAttribute('altura',$shippingAddress->getAltura());
            $customerAddress->setCustomAttribute('piso',$shippingAddress->getPiso());
            $customerAddress->setCustomAttribute('departamento',$shippingAddress->getDepartamento());
            $customerAddress->setCustomAttribute('observaciones',$shippingAddress->getObservaciones());
            $customerAddress->setCustomAttribute('celular',$shippingAddress->getCelular());*/
            if($hasChange) {
                $this->addressRepository->save($customerAddress);
            }
        }

        if($metodoEnvio[0] ==  \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE)
        {
            $this->_checkoutSession->unsCodigoSucursalAndreani();
            $this->_checkoutSession->unsNombreAndreaniSucursal();
            $this->_checkoutSession->unsCotizacionAndreaniSucursal();
        }


        return $this;
    }
}
