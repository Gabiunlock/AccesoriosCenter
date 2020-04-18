<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\MenuGroup;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Sm\MegaMenu\Model\MenuGroup;
use Sm\MegaMenu\Model\ResourceModel\MenuGroup\CollectionFactory;
use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
	protected $_collectionFactory;

	protected $_menuGroup;

	protected $_pageLayoutBuilder;

	public function __construct(
		Context $context,
		Data $backendHelper,
		MenuGroup $menuGroup,
		CollectionFactory $collectionFactory,
		BuilderInterface $pageLayoutBuilder,
		array $data = []
	) {
		$this->_collectionFactory = $collectionFactory;
		$this->_menuGroup = $menuGroup;
		$this->_pageLayoutBuilder = $pageLayoutBuilder;
		parent::__construct($context, $backendHelper, $data);
	}

	public function _construct()
	{
		parent::_construct();
		$this->setId('menuGroupGrid');
		$this->setDefaultSort('group_id');
		$this->setDefaultDir('ASC');
	}

	protected function _prepareCollection()
	{
		$collection = $this->_collectionFactory->create();
		/* @var $collection \Magento\Cms\Model\ResourceModel\Page\Collection */
		$this->setCollection($collection);

		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn(
			'group_id',
			[
				'header'    => __('Group ID'),
				'index'     => 'group_id',
			]
		);

		$this->addColumn(
			'title',
			[
				'header'    => __('Title'),
				'index'     => 'title'
			]
		);

		$this->addColumn(
			'status',
			[
				'header'    => __('Status'),
				'index'     => 'status',
				'type' => 'options',
				'options' => $this->_menuGroup->getAvailableStatuses()
			]
		);

		$this->addColumn(
			'menugroups_actions',
			[
				'header' => __('Action'),
				'type' => 'action',
				'getter' => 'getId',
				'actions' => [
					[
						'caption' => __('Edit'),
						'url' => [
							'base' => '*/*/edit',
							'params' => ['store' => $this->getRequest()->getParam('store')]
						],
						'field' => 'id'
					]
				],
				'sortable' => false,
				'filter' => false,
				'index' => 'stores',
				'header_css_class' => 'col-action',
				'column_css_class' => 'col-action'
			]
		);

		parent::_prepareColumns();
	}

	/**
	 * @return $this
	 */
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('group_id');
		$this->getMassactionBlock()->setFormFieldName('menugroup_param');

		$this->getMassactionBlock()->addItem(
			'delete',
			[
				'label' => __('Delete'),
				'url' => $this->getUrl('megamenu/*/massDelete'),
				'confirm' => __('Are you sure?')
			]
		);

		$statuses = $this->_menuGroup->getAvailableStatuses();

		array_unshift($statuses, ['label' => '', 'value' => '']);
		$this->getMassactionBlock()->addItem(
			'status',
			[
				'label' => __('Change Status'),
				'url' => $this->getUrl('megamenu/*/massStatus', ['_current' => true]),
				'additional' => [
					'visibility' => [
						'name' => 'status',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => __('Status'),
						'values' => $statuses
					]
				]
			]
		);

		return $this;
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
	}
}