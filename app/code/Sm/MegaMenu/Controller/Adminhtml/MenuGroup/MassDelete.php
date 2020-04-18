<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Controller\Adminhtml\MenuGroup;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

class MassDelete extends \Magento\Backend\App\Action
{
	protected $_coreRegistry;

	protected $query;

	public function __construct(
		Context $context,
		Registry $registry
	)
	{
		$this->_coreRegistry = $registry;
		parent::__construct($context);
	}

	public function execute(){
		$groupIds = $this->getRequest()->getParam('menugroup_param');
		$menuGroup = $this->_objectManager->create('Sm\MegaMenu\Model\MenuGroup');
		$menuItems = $this->_objectManager->create('Sm\MegaMenu\Model\MenuItems');
		$countMenuGroup = 0;

		if(!is_array($groupIds)) {
			$this->messageManager->addError(__('Please select item(s)'));
		}
		else{
			try {
				foreach ($groupIds as $group) {
					$collection = $menuGroup->load($group);
					$collection->delete();
					$menuItems->getDeleteItemsByGroup($group);
					$countMenuGroup++;
				}

				$this->messageManager->addSuccess(
					__('A total of %1 record(s) have been deleted.', $countMenuGroup)
				);
				$redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
				return $redirect->setPath('megamenu/*/index');
			} catch (\Magento\Framework\Exception\LocalizedException $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\Exception $e) {
				$this->_getSession()->addException($e, __('Something went wrong while updating the product(s) status.'));
			}
		}
	}
}