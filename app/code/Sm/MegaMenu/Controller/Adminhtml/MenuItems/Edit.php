<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuItems;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Sm\MegaMenu\Helper\Defaults;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;

class Edit extends \Magento\Backend\App\Action
{
	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry = null;

	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $resultPageFactory;

	/*
	 * @var Sm\MegaMenu\Helper\Defaults
	 * */
	protected $_defaults;

	protected $_dataCollection;

	protected $_dataObject;

	protected $_view;

	/**
	 * @param Action\Context $context
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 * @param \Magento\Framework\Registry $registry
	 */
	public function __construct(
		Action\Context $context,
		PageFactory $resultPageFactory,
		Registry $registry,
		Collection $collection,
		DataObject $dataObject,
		Defaults $defaults
	)
	{
		$this->resultPageFactory = $resultPageFactory;
		$this->_coreRegistry = $registry;
		$this->_defaults = $defaults;
		$this->_dataCollection = $collection;
		$this->_dataObject = $dataObject;
		$this->_view = $context->getView();
		parent::__construct($context);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Sm_MegaMenu::save');
	}

	/**
	 * Init actions
	 *
	 * @return \Magento\Backend\Model\View\Result\Page
	 */
	protected function _initAction()
	{
		// load layout, set active menu and breadcrumbs
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Sm_MegaMenu::megamenu_menuitems')
			->addBreadcrumb(__('Manager Menu'), __('Manager Menu'))
			->addBreadcrumb(__('Manager Menu'), __('Manager Menu'));
		return $resultPage;
	}

	public function createMenuGroup(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuGroup');
	}

	public function createMenuItems(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function createMenuItemsCollection(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\ResourceModel\MenuItems\Collection');
	}

	public function getItemsByGroupId($groupId)
	{
		$config = $this->_defaults->get($attributes = []);
		$level_start = $config['start_level'];
		$menuItemsCollection = $this->createMenuItemsCollection();
		$itemsByGroupId = $menuItemsCollection->getItemsByGroupId($groupId, $level_start);
		return $itemsByGroupId;
		/*$collection = $this->_dataCollection;
		$itemObject = $this->_dataObject;
		foreach ($itemsByGroupId as $item) {
			$itemObject->addData($item);
			$collection->addItem($itemObject);
		}
		return $collection->getItems();*/
	}

	/**
	 * Edit Menu Items
	 *
	 * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function execute()
	{
		// 1. Get ID and create model
		$id = $this->getRequest()->getParam('id');
		$groupId = $this->getRequest()->getParam('gid'); // official
		$data_items = $this->getItemsByGroupId($groupId);
		$model = $this->createMenuItems();
		$modelGroup = $this->createMenuGroup();

		// 2. Initial checking
		if ($id) {
			$model->load($id);
			if (!$model->getItemsId()) {
				$this->messageManager->addError(__('This items no longer exists.'));
				/** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
				$resultRedirect = $this->resultRedirectFactory->create();

				return $resultRedirect->setPath('*/*/');
			}
		}

		if($groupId)
		{
			$modelGroup->load($groupId);
			if (!$modelGroup->getGroupId()) {
				$this->messageManager->addError(__('This group no longer exists.'));
				/** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
				$resultRedirect = $this->resultRedirectFactory->create();

				return $resultRedirect->setPath('*/*/');
			}
		}

		if ($model->getItemsId() || $id == 0)
		{
			// 3. Set entered data if was error when we do save
			$data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			// 4. Register model to use later in blocks
			$this->_coreRegistry->register('megamenu_menuitems', $model);
			$this->_coreRegistry->register('megamenu_menugroup', $modelGroup);
			$this->_coreRegistry->register('megamenu_items', $data_items);
			$this->_coreRegistry->register('group_id', $groupId);

			// 5. Build edit form
			/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
			$resultPage = $this->_initAction();
			$resultPage->addBreadcrumb(
				$id ? __('Edit Items') : __('New Items'),
				$id ? __('Edit Items') : __('New Items')
			);
			$this->_addContent(
				$this->_view->getLayout()->createBlock('\Sm\MegaMenu\Block\Adminhtml\MenuItems\Edit')
			);
			$this->_addLeft(
				$this->_view->getLayout()->createBlock('\Sm\MegaMenu\Block\Adminhtml\MenuItems\Edit\Tabs')
			);

			$resultPage->getConfig()->getTitle()->prepend(__('Menu Items'));
			$resultPage->getConfig()->getTitle()
				->prepend($model->getItemsId() ? $model->getTitle() : __('New Items'));
			return $resultPage;
		}
	}
}