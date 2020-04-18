<?php
/**------------------------------------------------------------------------
* SM Search Box - Version 2.1.0
* Copyright (c) 2015 YouTech Company. All Rights Reserved.
* @license - Copyrighted Commercial Software
* Author: YouTech Company
* Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\SearchBox\Controller\Index;

class Ajax extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var \Magento\Framework\App\ViewInterface
	 */
	protected $_view;

	public function __construct(
		\Magento\Framework\App\Action\Context $context
	)
	{
		parent::__construct($context);
		$this->_view = $context->getView();
	}

	/**
	 * @return \Magento\Framework\View\Result\Page
	 */
	public function execute()
	{
		$block = $this->_view->getLayout()->createBlock('Sm\SearchBox\Block\SearchBox');
		header('content-type: text/javascript');
		echo '{"htm":'.json_encode($block->toHtml()).'}';
		die();
//		$this->_view->renderLayout();
	}
}