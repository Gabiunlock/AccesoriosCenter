<?php

namespace DrubuNet\Andreani\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;

/**
 * Class UpgradeData
 *
 * @description Actualizador de datos para las tablas
 * @author Mauro Maximiliano Martinez <mmartinez@ids.net.ar>
 * @package DrubuNet\Andreani\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->_eavSetupFactory = $eavSetupFactory;

        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '1.0.2', '<'))
        {
            $eavSetup->updateAttribute(\Magento\Catalog\Model\Product::ENTITY,'volumen','apply_to','simple');
        }

        if (version_compare($context->getVersion(), '1.0.3', '<'))
        {
            //$eavSetup->updateAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'dni','validate_rules','{"max_text_length":15,"min_text_length":7}');
            //$eavSetup->updateAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'celular','validate_rules','{"max_text_length":20}');
            //$eavSetup->updateAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'altura','validate_rules','{"min_text_length":1}');
            $this->deleteOldFields($eavSetup,$setup);
            $this->addNewFields($setup);
        }

        $setup->endSetup();
    }

    private function deleteOldFields($eavSetup,$setup){
        $eavSetup ->removeAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'dni');
        $eavSetup ->removeAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'celular');
        $eavSetup ->removeAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'altura');
        $eavSetup ->removeAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'piso');
        $eavSetup ->removeAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'departamento');
        $eavSetup ->removeAttribute(\Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,'observaciones');

        $customerAddressSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerAddressEntity = $customerAddressSetup->getEavConfig()->getEntityType(
            \Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS
        );
        $customerAddressSetup->removeAttribute('customer_address', "dni");
        $customerAddressSetup->removeAttribute('customer_address', "celular");
        $customerAddressSetup->removeAttribute('customer_address', "altura");
        $customerAddressSetup->removeAttribute('customer_address', "piso");
        $customerAddressSetup->removeAttribute('customer_address', "departamento");
        $customerAddressSetup->removeAttribute('customer_address', "observaciones");
    }

    private function addNewFields($setup){
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $attributesAddressInfo = [
            'dni' => [
                'label' => 'DNI',
                'input' => 'text',
                'type' => 'varchar',
                'source' => '',
                'required' => false,
                'position' => 118,
                'visible' => true,
                'system' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'backend' => ''
            ],
            'celular' => [
                'label' => 'Celular',
                'input' => 'text',
                'type' => 'varchar',
                'source' => '',
                'required' => false,
                'position' => 200,
                'visible' => true,
                'system' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'backend' => ''
            ],
            'altura' => [
                'label' => 'Altura',
                'input' => 'text',
                'type' => 'varchar',
                'source' => '',
                'required' => false,
                'position' => 70,
                'visible' => true,
                'system' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'backend' => ''
            ],
            'piso' => [
                'label' => 'Piso',
                'input' => 'text',
                'type' => 'varchar',
                'source' => '',
                'required' => false,
                'position' => 71,
                'visible' => true,
                'system' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'backend' => ''
            ],
            'departamento' => [
                'label' => 'Departamento',
                'input' => 'text',
                'type' => 'varchar',
                'source' => '',
                'required' => false,
                'position' => 72,
                'visible' => true,
                'system' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'backend' => ''
            ],
            'observaciones' => [
                'label' => 'Observaciones',
                'input' => 'text',
                'type' => 'varchar',
                'source' => '',
                'required' => false,
                'position' => 1000,
                'visible' => true,
                'system' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'backend' => ''
            ]
        ];
        foreach ($attributesAddressInfo as $attributeCode => $attributeParams) {
            $customerSetup->addAttribute(
                \Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                $attributeCode,
                $attributeParams
            );
            $attribute = $customerSetup->getEavConfig()
                ->getAttribute(
                    \Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                    $attributeCode
                )
                ->addData([
                    'used_in_forms' => [
                        'customer_address_edit',
                        'customer_register_address'
                    ]
                ]);
            $attribute->save();
        }
    }
}
