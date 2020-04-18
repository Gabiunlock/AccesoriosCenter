<?php
/**------------------------------------------------------------------------
* SM Search Box - Version 2.1.0
* Copyright (c) 2015 YouTech Company. All Rights Reserved.
* @license - Copyrighted Commercial Software
* Author: YouTech Company
* Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\SearchBox\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
	/**
	 * @return \Magento\Framework\View\Result\Page
	 */
	public function execute()
	{
		/** @var \Magento\Framework\View\Result\Page $resultPage */
		$resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
		return $resultPage;
	}
}