<?php

namespace DrubuNet\Andreani\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @description Instalador de tablas. Equivalente a los installer de magento 1.
 * @author Drubu Team
 * @package DrubuNet\Andreani\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @description Instalador de las tablas:
     *                                      - drubunet_andreani_provincia
     *                                      - drubunet_andreani_zona
     *                                      - drubunet_andreani_tarifa
     *                                      - drubunet_andreani_codigo_postal
     *                                      - drubunet_andreani_sucursal
     *
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**Creacion de la tabla drubunet_andreani_provincia*/
        if($installer->tableExists("ids_andreani_provincia")){
            $installer->getConnection()->dropTable($installer->getTable('ids_andreani_provincia'));
        }
        $idsAndreaniProvincia = $installer->getConnection()
            ->newTable($installer->getTable('drubunet_andreani_provincia'))
            ->addColumn(
                'provincia_id',
                Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn('nombre', Table::TYPE_TEXT, 100, ['nullable' => true, 'default' => null]);

        $installer->getConnection()->createTable($idsAndreaniProvincia);

        /**Creacion de la tabla drubunet_andreani_zona*/
        if($installer->tableExists("ids_andreani_zona")){
            $installer->getConnection()->dropTable($installer->getTable('ids_andreani_zona'));
        }
        $idsAndreaniZona = $installer->getConnection()
            ->newTable($installer->getTable('drubunet_andreani_zona'))
            ->addColumn(
                'zona_id',
                Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn('nombre', Table::TYPE_TEXT, 40, ['nullable' => false]);

        $installer->getConnection()->createTable($idsAndreaniZona);

        /**Creacion de la tabla drubunet_andreani_tarifa*/
        if($installer->tableExists("ids_andreani_tarifa")){
            $installer->getConnection()->dropTable($installer->getTable('ids_andreani_tarifa'));
        }
        $idsAndreaniTarifa = $installer->getConnection()
            ->newTable($installer->getTable('drubunet_andreani_tarifa'))
            ->addColumn(
                'tarifa_id',
                Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn('rango', Table::TYPE_DECIMAL, '10,2', ['nullable' => false])
            ->addColumn('valor_estandar', Table::TYPE_DECIMAL, '10,2', ['nullable' => false])
            ->addColumn('valor_sucursal', Table::TYPE_DECIMAL, '10,2', ['nullable' => true, 'default' => null])
            ->addColumn('valor_urgente', Table::TYPE_DECIMAL, '10,2', ['nullable' => true, 'default' => null])
            ->addColumn('zona_id', Table::TYPE_SMALLINT, 6, ['nullable' => false])
            ->addIndex(
                $installer->getIdxName('drubunet_andreani_tarifa', ['zona_id']),
                ['zona_id']
            )
            ->addForeignKey(
                $installer->getFkName('drubunet_andreani_tarifa', 'zona_id', 'drubunet_andreani_zona', 'zona_id'),
                'zona_id',
                'drubunet_andreani_zona',
                'zona_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

        $installer->getConnection()->createTable($idsAndreaniTarifa);

        /**Creacion de la tabla drubunet_andreani_codigo_postal*/
        if($installer->tableExists("ids_andreani_codigo_postal")){
            $installer->getConnection()->dropTable($installer->getTable('ids_andreani_codigo_postal'));
        }
        $idsAndreaniCodigoPostal = $installer->getConnection()
            ->newTable($installer->getTable('drubunet_andreani_codigo_postal'))
            ->addColumn(
                'codigo_postal_id',
                Table::TYPE_INTEGER,
                6,
                ['identity' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn('sucursal', Table::TYPE_TEXT, 40, ['nullable' => false])
            ->addColumn('localidad', Table::TYPE_TEXT, 40, ['nullable' => false])
            ->addColumn('codigo_postal', Table::TYPE_INTEGER, 6, ['nullable' => false])
            ->addColumn('provincia_id', Table::TYPE_SMALLINT, 6, ['nullable' => false])
            ->addColumn('zona_id', Table::TYPE_SMALLINT, 6, ['nullable' => false])
            ->addIndex(
                $installer->getIdxName('drubunet_andreani_codigo_postal', ['provincia_id']),
                ['provincia_id']
            )
            ->addIndex(
                $installer->getIdxName('drubunet_andreani_codigo_postal', ['zona_id']),
                ['zona_id']
            )
            ->addForeignKey(
                $installer->getFkName('drubunet_andreani_codigo_postal', 'provincia_id', 'drubunet_andreani_provincia', 'provincia_id'),
                'provincia_id',
                'drubunet_andreani_provincia',
                'provincia_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('drubunet_andreani_codigo_postal', 'zona_id', 'drubunet_andreani_zona', 'zona_id'),
                'zona_id',
                'drubunet_andreani_zona',
                'zona_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

        $installer->getConnection()->createTable($idsAndreaniCodigoPostal);

        /**Creacion de la tabla drubunet_andreani_sucursal*/
        if($installer->tableExists("ids_andreani_sucursal")){
            $installer->getConnection()->dropTable($installer->getTable('ids_andreani_sucursal'));
        }
        $idsAndreaniSucursal = $installer->getConnection()
            ->newTable($installer->getTable('drubunet_andreani_sucursal'))
            ->addColumn(
                'sucursal_id',
                Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn('nombre', Table::TYPE_TEXT, 40, ['nullable' => false])
            ->addColumn('direccion', Table::TYPE_TEXT, 60, ['nullable' => false])
            ->addColumn('telefono', Table::TYPE_TEXT, 60, ['nullable' => true, 'default' => null])
            ->addColumn('codigo_postal', Table::TYPE_INTEGER, 6, ['nullable' => false])
            ->addColumn('provincia_id', Table::TYPE_SMALLINT, 6, ['nullable' => false])
            ->addColumn('codigo_sucursal', Table::TYPE_SMALLINT, 6, ['nullable' => true, 'default' => null])
            ->addIndex(
                $installer->getIdxName('drubunet_andreani_sucursal', ['provincia_id']),
                ['provincia_id']
            )
            ->addForeignKey(
                $installer->getFkName('drubunet_andreani_sucursal', 'provincia_id', 'drubunet_andreani_provincia', 'provincia_id'),
                'provincia_id',
                'drubunet_andreani_provincia',
                'provincia_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

        $installer->getConnection()->createTable($idsAndreaniSucursal);

        /**Creacion de la columna andreani_datos_guia en sales_shipment si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_shipment'),'andreani_datos_guia')) {
            $column = [
                'type' => Table::TYPE_BLOB,
                'length' => '',
                'nullable' => true,
                'comment' => 'Data del WS para generar la guía PDF.',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_shipment'), 'andreani_datos_guia', $column);
        }

        /**Creacion de la columna codigo_sucursal_andreani en quote si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('quote'),'codigo_sucursal_andreani')) {
            $column = [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Codigo de sucursal andreani',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('quote'), 'codigo_sucursal_andreani', $column);
        }

        /**Creacion de la columna codigo_sucursal_andreani en sales_order si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order'),'codigo_sucursal_andreani')) {
            $column = [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Codigo de sucursal andreani',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_order'), 'codigo_sucursal_andreani', $column);
        }

        /**Creacion de la columna customer_dni en sales_order si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order'),'customer_dni')) {
            $column = [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Numero de documento del cliente',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_order'), 'customer_dni', $column);
        }

        /**Creacion de la columna dni en sales_order_address si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order_address'),'dni')) {
            $column = [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Numero de documento del cliente',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_order_address'), 'dni', $column);
        }

        /**Creacion de la columna dni en quote_address si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('quote_address'),'dni')) {
            $column = [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Numero de documento del cliente',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('quote_address'), 'dni', $column);
        }

        /**Creacion de la columna celular en quote_address y sales_order_address si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order_address'),'celular') &&
            !$installer->getConnection()->tableColumnExists($installer->getTable('quote_address'),'celular')) {
            $column = [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Celular del cliente',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_order_address'), 'celular', $column);
            $installer->getConnection()->addColumn($installer->getTable('quote_address'), 'celular', $column);
        }

        /**Creacion de la columna altura en quote_address y sales_order_address si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order_address'),'altura') &&
            !$installer->getConnection()->tableColumnExists($installer->getTable('quote_address'),'altura')) {
            $column = [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Altura de la calle de la dirección del cliente',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_order_address'), 'altura', $column);
            $installer->getConnection()->addColumn($installer->getTable('quote_address'), 'altura', $column);
        }

        /**Creacion de la columna piso en quote_address y sales_order_address si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order_address'),'piso') &&
            !$installer->getConnection()->tableColumnExists($installer->getTable('quote_address'),'piso')) {
            $column = [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Número del piso la dirección del cliente',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_order_address'), 'piso', $column);
            $installer->getConnection()->addColumn($installer->getTable('quote_address'), 'piso', $column);
        }

        /**Creacion de la columna departamento en quote_address y sales_order_address si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order_address'),'departamento') &&
            !$installer->getConnection()->tableColumnExists($installer->getTable('quote_address'),'departamento')) {
            $column = [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Departamento del piso la dirección del cliente',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_order_address'), 'departamento', $column);
            $installer->getConnection()->addColumn($installer->getTable('quote_address'), 'departamento', $column);
        }

        /**Creacion de la columna observaciones en quote_address y sales_order_address si no existe*/
        if(!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order_address'),'observaciones') &&
            !$installer->getConnection()->tableColumnExists($installer->getTable('quote_address'),'observaciones')) {
            $column = [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Observaciones del cliente',
                'default' => null
            ];
            $installer->getConnection()->addColumn($installer->getTable('sales_order_address'), 'observaciones', $column);
            $installer->getConnection()->addColumn($installer->getTable('quote_address'), 'observaciones', $column);
        }

        $installer->endSetup();
    }

}
