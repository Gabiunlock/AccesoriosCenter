<?php
/**
 * IDS 2016
 * Author Jhonattan Campo <jcampo@ids.net.ar>
 */
namespace DrubuNet\Andreani\Block\Widget;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Block\Widget\AbstractWidget;
use Magento\Customer\Model\Address\Mapper;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Customer Value Added Tax Widget
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Altura extends AbstractWidget
{
    /**
     * @var Mapper
     */
    protected $addressMapper;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Helper\Address $addressHelper
     * @param CustomerMetadataInterface $customerMetadata
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Address $addressHelper,
        CustomerMetadataInterface $customerMetadata,
        Mapper $addressMapper,
        CustomerRepositoryInterface $customerRepository,
        AddressRepositoryInterface $addressRepository,
        array $data = []
    ) {
        $this->addressMapper = $addressMapper;
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
        $this->_isScopePrivate = true;
    }

    /**
     * Sets the template
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('customer/form/field/altura.phtml');
    }

    /**
     * Get is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Get is required.
     *
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
