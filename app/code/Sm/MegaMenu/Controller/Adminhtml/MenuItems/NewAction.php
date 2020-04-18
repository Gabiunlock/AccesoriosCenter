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
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\ForwardFactory;

class NewAction extends \Magento\Backend\App\Action
{
	protected $resultForwardFactory;

	public function __construct(
		Context $context,
		ForwardFactory $resultForwardFactory
	) {
		$this->resultForwardFactory = $resultForwardFactory;
		parent::__construct($context);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Sm_MegaMenu::save');
	}

	public function execute()
	{
		/** @var \Magento\Framework\Controller\Result\Forward $resultForward */
		$resultForward = $this->resultForwardFactory->create();
		return $resultForward->forward('edit');
	}
}