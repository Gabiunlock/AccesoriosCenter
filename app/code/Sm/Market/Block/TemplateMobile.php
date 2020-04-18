<?php

namespace Sm\Market\Block;

class TemplateMobile extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    public $_coreRegistry;

    /**
     * @var \Magento\Theme\Block\Html\Header\Logo
     */
    protected $_logo;

    /**
     * TemplateMobile constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Theme\Block\Html\Header\Logo $logo
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_logo         = $logo;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\View\Element\Template
     */

    public function _prepareLayout()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $page          = $objectManager->get('Magento\Framework\View\Page\Config');
        $helper_config = $objectManager->get('Sm\Market\Helper\Data');
        $rtlLayout     = $helper_config->getThemeLayout('direction_rtl');

        if ($rtlLayout) {
            $extRtl = "_rtl";
        } else {
            $extRtl = "";
        }

        $headerCss = 'css/header-mobile' . $extRtl . '.css';
        $footerCss = 'css/footer-mobile' . $extRtl . '.css';

        $page->addPageAsset($headerCss);
        $page->addPageAsset($footerCss);

        return parent::_prepareLayout();
    }
}
