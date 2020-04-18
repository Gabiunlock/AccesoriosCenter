<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$installer = $setup;

		$installer->startSetup();

		/*
		 * Create Table 'sm_megamenu_groups'
		 * */
		$table = $installer->getConnection()->newTable(
			$installer->getTable('sm_megamenu_groups')
		)->addColumn(
			'group_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			[
				'identity' => true,
				'unsigned' => true,
				'nullable' => false,
				'primary' => true,

			],
			'Groups ID'
		)->addColumn(
			'title',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => false],
			'Groups Title'
		)->addColumn(
			'status',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '1'],
			'Status'
		)->addColumn(
			'content',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			'2M',
			[],
			'Content'
		)->addIndex(
			$installer->getIdxName(
				$installer->getTable('sm_megamenu_groups'),
				['title', 'content'],
				AdapterInterface::INDEX_TYPE_FULLTEXT
			),
			['title', 'content'],
			['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
		)->setComment(
			'SM Mega Menu Groups'
		);
		$installer->getConnection()->createTable($table);

		/*
		 * Create table 'sm_megamenu_items'
		 * */
		$table = $installer->getConnection()->newTable(
			$installer->getTable('sm_megamenu_items')
		)->addColumn(
			'items_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			[
				'identity' => true,
				'unsigned' => true,
				'nullable' => false,
				'primary' => true,

			],
			'Items ID'
		)->addColumn(
			'title',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => false],
			'Items Title'
		)->addColumn(
			'show_title',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '1'],
			'Show Title'
		)->addColumn(
			'description',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			'2M',
			[],
			'Description'
		)->addColumn(
			'status',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '1'],
			'Status'
		)->addColumn(
			'align',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '1'],
			'Align'
		)->addColumn(
			'depth',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Depth'
		)->addColumn(
			'group_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			['nullable' => false, 'default' => '0'],
			'Group ID'
		)->addColumn(
			'cols_nb',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Cols Number'
		)->addColumn(
			'icon_url',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			'64k',
			['nullable' => false, 'default' => ''],
			'Icon Url'
		)->addColumn(
			'target',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '1'],
			'Target'
		)->addColumn(
			'type',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Type'
		)->addColumn(
			'data_type',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => false],
			'Data Type'
		)->addColumn(
			'content',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			'2M',
			[],
			'Content'
		)->addColumn(
			'custom_class',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => false],
			'Custom Class'
		)->addColumn(
			'parent_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			['nullable' => false, 'default' => '0'],
			'Parent ID'
		)->addColumn(
			'order_item',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			['nullable' => false, 'default' => '0'],
			'Order Items'
		)->addColumn(
			'position_item',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '2'],
			'Position Items'
		)->addColumn(
			'priorities',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			['nullable' => false, 'default' => '0'],
			'Priorities'
		)->addColumn(
			'show_image_product',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Show Image Products'
		)->addColumn(
			'show_title_product',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Show Title Products'
		)->addColumn(
			'show_rating_product',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Show Rating Products'
		)->addColumn(
			'show_price_product',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Show Price Products'
		)->addColumn(
			'show_title_category',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Show Title Category'
		)->addColumn(
			'limit_category',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => false],
			'Limit Category'
		)->addColumn(
			'show_sub_category',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '1'],
			'Show Sub Category'
		)->addColumn(
			'limit_sub_category',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => false],
			'Limit Sub Category'
		)->addIndex(
			$installer->getIdxName(
				$installer->getTable('sm_megamenu_items'),
				['title', 'description', 'icon_url', 'content', 'custom_class'],
				AdapterInterface::INDEX_TYPE_FULLTEXT
			),
			['title', 'description', 'icon_url', 'content', 'custom_class'],
			['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
		)->setComment(
			'SM Mega Menu Items'
		);
		$installer->getConnection()->createTable($table);

		$installer->endSetup();
	}
}