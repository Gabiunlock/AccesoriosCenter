<?php
namespace Sm\Market\Block;

class Template extends \Magento\Framework\View\Element\Template {
    public $_coreRegistry;
	
	public function _prepareLayout(){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$helper_config = $objectManager->get('Sm\Market\Helper\Data');

		$headerStyle = $helper_config->getThemeLayout('header_style');
		$homeStyle = $helper_config->getThemeLayout('home_style');
		$footerStyle = $helper_config->getThemeLayout('footer_style');
		$productStyle = $helper_config->getThemeLayout('product_style');
		$layout = $helper_config->getThemeLayout('layout_style');
		$right_to_left = $helper_config->getThemeLayout('direction_rtl');
		
		if($right_to_left){
			$rtl = 'direction-rtl';
		} else {
			$rtl = '';
		}

		$this->pageConfig->addBodyClass($headerStyle . '-style');
		$this->pageConfig->addBodyClass($homeStyle . '-style');
		$this->pageConfig->addBodyClass($footerStyle . '-style');
		$this->pageConfig->addBodyClass($productStyle . '-style');
		$this->pageConfig->addBodyClass('layout-' . $layout);
		$this->pageConfig->addBodyClass($rtl);
		
		return parent::_prepareLayout();
	}
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }    
}
?>