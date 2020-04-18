<?php
/*------------------------------------------------------------------------
# SM Mega Menu - Version 3.2.0
# Copyright (c) 2015 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\MegaMenu\Block\Adminhtml\MenuItems\Edit;

use Magento\Framework\Json\EncoderInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Translate\InlineInterface;

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
        EncoderInterface $jsonEncoder,
        InlineInterface $translateInline,
        array $data = []
    )
    {
        $this->_translateInline = $translateInline;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('menuitems_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle("" . __('Mega Menu'));
        if ($tab = $this->getRequest()->getParam('activeTab'))
            $this->_activeTab = $tab;
        else
            $this->_activeTab = 'menuitems';
    }

    protected function _translateHtml($html)
    {
        $this->_translateInline->processResponseBody($html);
        return $html;
    }

    protected function _prepareLayout()
    {
////		if ($this->getChildBlock('menuitems_form')) {
////			$this->addTab('menuitems_form', 'menuitems_form');
////			$this->getChildBlock('menuitems_form')->setGroupCode(self::BASIC_TAB_GROUP_CODE);
////		}

        $this->addTab(
            'menugroup',
            [
                'label' => __('Menu Group'),
                'title' => __('Menu Group'),
                'url' => $this->getUrl('*/menugroup/newaction',
                    [
                        'id' => $this->getRequest()->getParam('gid')
                    ]),
                'group_code' => self::BASIC_TAB_GROUP_CODE
            ]
        );

        $this->addTab(
            'menuitems',
            [
                'label' => __('Menu Items'),
                'title' => __('Menu Items'),
                'content' => $this->_getTabHtml('\Form'),
                'group_code' => self::BASIC_TAB_GROUP_CODE
            ]
        );

        return parent::_prepareLayout();
    }

    private function _getTabHtml($tab)
    {
        return $this->getLayout()->createBlock('\Sm\MegaMenu\Block\Adminhtml\MenuItems\Edit\Tab' . $tab)->toHtml();
    }
}