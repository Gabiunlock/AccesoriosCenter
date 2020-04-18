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

class Duplicate extends \Magento\Backend\App\Action
{
	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $resultPageFactory;

	/**
	 * @param Action\Context $context
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 */
	public function __construct(
		Action\Context $context,
		PageFactory $resultPageFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;
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

	public function createMenuItems(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
	}

	public function createMenuItemsCollection(){
		return $this->_objectManager->create('Sm\MegaMenu\Model\ResourceModel\MenuItems\Collection');
	}

	public function dulplicateChildItemsAction($allItems, $parent_id, $order_item, $gid, $id)
	{
		try
		{
			foreach ($allItems as $allItem)
			{
				$modelItemsCollection = $this->createMenuItemsCollection();
				$model = $this->createMenuItems();
				$groupId = $allItem['group_id'];
				$parentId = $allItem['items_id'];
				$all_item = $modelItemsCollection->getAllItemsByItemsId($parentId, $groupId);
				if ($allItem['items_id'])
					unset($allItem['items_id']);
				if ($allItem['parent_id'])
					$allItem['parent_id'] = $parent_id;
				if ($allItem['order_item'])
					$allItem['order_item'] = $order_item;
				$model->setData($allItem);
				$model->save();
				if (count($all_item))
				{
					$this->dulplicateChildItemsAction($all_item, $model->getItemsId(), $model->getItemsId(), $gid, $id);
				}
			}
		} catch(\Exception $e)
		{
			$this->messageManager->addError($e->getMessage());
		}
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
		$gId = $this->getRequest()->getParam('gid'); // official
		$resultRedirect = $this->resultRedirectFactory->create();
		$model = $this->createMenuItems();
		$modelItemsCollection = $this->createMenuItemsCollection();
		if ($id > 0) {
			try {
				$items = $model->load($id);
				if ($items->getItemsId()) {
					$groupId = $items->getGroupId();
					$parentId = $items->getItemsId();
					$all_item = $modelItemsCollection->getAllItemsByItemsId($parentId, $groupId);
					$data = $items->getData();
					$itemsId = $data['items_id'];
					if ($data['items_id'])
						unset($data['items_id']);
					if ($data['order_item'] || ($data['order_item']==0))
						$data['order_item'] = $itemsId;
					$model->setData($data);
					try{
						$model->save();
						if (count($all_item))
						{
							$this->dulplicateChildItemsAction($all_item, $model->getItemsId(), $items->getItemsId(), $gId, $id);
						}
						$this->messageManager->addSuccess(__('You duplicate items was successfully.'));
						return $resultRedirect->setPath('*/*/edit', [
							'gid' => $model->getGroupId(),
							'id'  => $model->getItemsId(),
							'activeTab' => 'menuitems'
						]);
					} catch(\Exception $e)
					{
						$this->messageManager->addError($e->getMessage());
						return $resultRedirect->setPath('*/*/edit', [
							'gid'   => $model->getGroupId(),
							'id'    => $model->getItemsId(),
							'activeTab' => 'menuitems'
						]);
					}
				}
			} catch(\Exception $e)
			{
				$this->messageManager->addError($e->getMessage());
				return $resultRedirect->setPath('*/*/edit', [
					'gid'   => $model->getGroupId(),
					'id'    => $model->getItemsId(),
					'activeTab' => 'menuitems'
				]);
			}
		}
	}
}