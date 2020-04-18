<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
namespace Sm\MegaMenu\Block\Adminhtml\MenuGroup\Edit;

use Magento\Framework\Json\EncoderInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Translate\InlineInterface;
use Magento\Framework\Registry;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
	const BASIC_TAB_GROUP_CODE = 'basic';
	/**
	 * @var InlineInterface
	 */
	protected $_translateInline;

	public function __construct(
		Context $context,
		Session $authSession,
		Registry $registry,
		EncoderInterface $jsonEncoder,
		InlineInterface $translateInline,
		array $data = []
	){
		$this->_coreRegistry = $registry;
		$this->_translateInline = $translateInline;
		parent::__construct($context, $jsonEncoder, $authSession, $data);
	}
	
	protected function _construct()
	{
		parent::_construct();
		$this->setId('menugroup_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle("".__('Mega Menu'));
		if ($tab = $this->getRequest()->getParam('activeTab' ))
			$this->_activeTab = $tab;
		else
			$this->_activeTab = 'menugroup';
	}

	protected function _translateHtml($html)
	{
		$this->_translateInline->processResponseBody($html);
		return $html;
	}

	/**
	 * @return $this
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function _prepareLayout()
	{
		$modelGroup = $this->_coreRegistry->registry('megamenu_menugroup');
		$this->addTab(
			'menugroup',
			[
				'label' => __('Menu Group'),
				'title' => __('Menu Group'),
				'content' => $this->_getTabHtml('\Form'),
				'group_code' => self::BASIC_TAB_GROUP_CODE
			]
		);

		if($modelGroup->getGroupId())
		{
			$this->addTab(
				'menuitems',
				[
					'label' => __('Menu Items'),
					'title' => __('Menu Items'),
					'url' => $this->getUrl('*/menuitems/newaction',
						[
							'gid' => $this->getRequest()->getParam('id')
						]),
					'group_code' => self::BASIC_TAB_GROUP_CODE
				]
			);
		}
		else
		{
			$this->addTab(
				'menuitems',
				[
					'label' => __('Menu Items'),
					'title' => __('Menu Items'),
					'content' => $this->_getTabHtml('\FromItemsNoGroupId'),
					'group_code' => self::BASIC_TAB_GROUP_CODE
				]
			);
		}

		return parent::_prepareLayout();
	}

	private function _getTabHtml($tab)
	{
		return $this->getLayout()->createBlock('\Sm\MegaMenu\Block\Adminhtml\MenuGroup\Edit\Tab' . $tab )->toHtml();
	}
}