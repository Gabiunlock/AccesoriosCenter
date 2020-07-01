<?php
namespace DrubuNet\Andreani\Plugin\Checkout;


class LayoutProcessorPlugin
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $customerAddressFactory;

    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\CheckoutAgreements\Model\ResourceModel\Agreement\CollectionFactory $agreementCollectionFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\AddressFactory $customerAddressFactory
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->checkoutSession = $checkoutSession;
        $this->customerAddressFactory = $customerAddressFactory;
    }
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
        /*
         * ['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['altura']['sortOrder']
         */
        $jsLayout/*['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['before-form']['children']['altura']*/
        ['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['altura']= [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'altura'
            ],
            'dataScope' => 'shippingAddress.altura',
            'label' => 'Altura',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'min_text_length' => 1,
                'required-entry' => true,
                'validate-number' => true
            ],
            'sortOrder' => 71,
            'id' => 'altura'
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['departamento'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'departamento'
            ],
            'dataScope' => 'shippingAddress.departamento',
            'label' => 'Departamento',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
            ],
            'sortOrder' => 73,
            'id' => 'departamento'
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['celular'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'celular'
            ],
            'dataScope' => 'shippingAddress.celular',
            'label' => 'Celular',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'min_text_length' => 7,
                'max_text_length' => 20,
                'validate-number' => true,
                'validate-digits' => true
            ],
            'sortOrder' => 200,
            'id' => 'celular'
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['piso'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'piso'
            ],
            'dataScope' => 'shippingAddress.piso',
            'label' => 'Piso',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
            ],
            'sortOrder' => 72,
            'id' => 'piso'
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['dni'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'dni'
            ],
            'dataScope' => 'shippingAddress.dni',
            'label' => 'DNI',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'validate-digits' => true,
                'max_text_length' => 8,
                'min_text_length' => 6,
                'validate-number' => true,
                'required-entry'  => true
            ],
            'sortOrder' => 52,
            'id' => 'dni'
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['observaciones'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'observaciones'
            ],
            'dataScope' => 'shippingAddress.observaciones',
            'label' => 'Observaciones',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
            ],
            'sortOrder' => 250,
            'id' => 'observaciones'
        ];

        return $jsLayout;
    }


}
