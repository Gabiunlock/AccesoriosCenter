<?php
/**
 * Author: Drubu Team
 */
namespace DrubuNet\Andreani\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 * @package DrubuNet\Andreani\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if(version_compare($context->getVersion(), '1.0.1', '<')) {
            if($setup->tableExists("ids_andreani_guia_generada")){
                $setup->getConnection()->renameTable($setup->getTable('ids_andreani_guia_generada'), $setup->getTable('drubunet_andreani_guia_generada'));
            }
            else {
                if(!$setup->tableExists("drubunet_andreani_guia_generada")) {
                    $guiaGenerada = $setup->getConnection()
                        ->newTable($setup->getTable('drubunet_andreani_guia_generada'))
                        ->addColumn(
                            'guia_id',
                            Table::TYPE_INTEGER,
                            11,
                            ['identity' => true, 'nullable' => false, 'primary' => true]

                        )
                        ->addColumn(
                            'fecha_generacion',
                            Table::TYPE_TIMESTAMP,
                            null,
                            [
                                'nullable' => false,
                                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                            ]
                        )
                        ->addColumn(
                            'path_pdf',
                            Table::TYPE_TEXT,
                            200,
                            [
                                'nullable' => true,
                                'default' => null
                            ]
                        )
                        ->addColumn(
                            'shipment_increment_id',
                            Table::TYPE_TEXT,
                            2500,
                            [
                                'nullable' => true,
                                'default' => null
                            ]
                        );

                    $setup->getConnection()->createTable($guiaGenerada);
                }
            }

        }


        $setup->endSetup();
    }
}
