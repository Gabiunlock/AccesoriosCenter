<?php
/**------------------------------------------------------------------------
* SM Search Box - Version 2.1.0
* Copyright (c) 2015 YouTech Company. All Rights Reserved.
* @license - Copyrighted Commercial Software
* Author: YouTech Company
* Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\SearchBox\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\App\Helper\Context;
use Magento\Search\Model\Query as SearchQuery;
use Magento\Search\Model\QueryFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	/**
	 * Core store config
	 *
	 * @var ScopeConfigInterface
	 */
	protected $_scopeConfig;

	/**
	 * Query factory
	 *
	 * @var QueryFactory
	 */
	protected $_queryFactory;

	/**
	 * @var Escaper
	 */
	protected $_escaper;

	/**
	 * Object manager
	 *
	 * @var \Magento\Framework\ObjectManagerInterface
	 */
	private $_objectManager;

	/**
	 * Construct
	 *
	 * @param Context $context
	 * @param QueryFactory $queryFactory
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 * @param Escaper $escaper
	 */
	public function __construct(
		Context $context,
		QueryFactory $queryFactory,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		Escaper $escaper
	) {
		$this->_scopeConfig = $context->getScopeConfig();
		$this->_objectManager = $objectManager;
		$this->_queryFactory = $queryFactory;
		$this->_escaper = $escaper;
		parent::__construct($context);
	}

	/**
	 * Retrieve HTML escaped search query
	 *
	 * @return string
	 */
	public function getEscapedQueryText()
	{
		return $this->_escaper->escapeHtml($this->_queryFactory->get()->getQueryText());
	}

	/**
	 * Retrieve maximum query length
	 *
	 * @param mixed $store
	 * @return int|string
	 */
	public function getMaxQueryLength($store = null)
	{
		return $this->_scopeConfig->getValue(
			SearchQuery::XML_PATH_MAX_QUERY_LENGTH,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE,
			$store
		);
	}

	/**
	 * Retrieve result page url and set "secure" param to avoid confirm
	 * message when we submit form from secure page to unsecure
	 *
	 * @param   string $query
	 * @return  string
	 */
	public function getResultUrl($query = null)
	{
		return $this->_getUrl(
			'searchbox/result',
			['_query' => [QueryFactory::QUERY_VAR_NAME => $query], '_secure' => $this->_request->isSecure()]
		);
	}

	/**
	 * @return string
	 */
	public function getQueryParamName()
	{
		return QueryFactory::QUERY_VAR_NAME;
	}

	public function getCategoryParamName() {
		$modelLayerFilterCategory = $this->_objectManager->get('Magento\Catalog\Model\Layer\Filter\Category');
//		return $modelLayerFilterCategory->getRequestVar();
		return $modelLayerFilterCategory;
	}
}