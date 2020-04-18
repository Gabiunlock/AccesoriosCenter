<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuItems;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class SetEnableAll extends \Magento\Backend\App\Action
{
	protected $resultPageFactory;

	public function __construct(
		Context $context,
		PageFactory $resultPageFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	public function createMenuItemsResource()
	{
		return $this->_objectManager->create('Sm\MegaMenu\Model\ResourceModel\MenuItems');
	}

	public function createMenuItemsCollection()
	{
		return $this->_objectManager->create('Sm\MegaMenu\Model\ResourceModel\MenuItems\Collection');
	}

	public function execute()
	{
		$groupId = $this->getRequest()->getParam('gid');
		if ($groupId)
		{
			$mainTable = $this->createMenuItemsResource()->getMainTable();
			$resultRedirect = $this->resultRedirectFactory->create();
			try{
				$this->createMenuItemsCollection()->setEnableAll($mainTable, $groupId);
				$this->messageManager->addSuccess(__('You enable all items was successfully.'));
				return $resultRedirect->setPath('*/*/newaction', [
					'gid' => $groupId,
					'activeTab' => 'menuitems'
				]);
			} catch (\Exception $e) {
				$this->messageManager->addError($e->getMessage());
				return $resultRedirect->setPath('*/*/newaction', [
					'gid' => $groupId,
					'activeTab' => 'menuitems'
				]);
			}
		}
	}
}