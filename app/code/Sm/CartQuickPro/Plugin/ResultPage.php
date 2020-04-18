<?php
/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */

namespace Sm\CartQuickPro\Plugin;

class ResultPage
{

    /**
     * @var  \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    /**
     * ResultPage constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\View\Layout $layout
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\View\Layout $layout)
    {
        $this->request = $request;
        $this->layout = $layout;
    }

    /**
     * Adding the default catalog_product_view_type_ handles as well
     * 
     * @param \Magento\Framework\View\Result\Page $subject
     * @param array $parameters
     * @param type $defaultHandle
     * @return type
     */
    public function beforeAddPageLayoutHandles(
        \Magento\Framework\View\Result\Page $subject, 
        array $parameters = [], 
        $defaultHandle = null)
    {
		$_action_name = ['cartquickpro_wishlist_index_configure', 'cartquickpro_catalog_product_view', 'cartquickpro_catalog_product_options' ,'cartquickpro_cart_configure'];
        if (in_array($this->request->getFullActionName(), $_action_name )) {
			if(!isset($parameters['type']) && isset($parameters['id']) && !empty($parameters['id'])){
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
				$_product = $objectManager->get('Magento\Catalog\Model\Product')->load($parameters['id']);
				$parameters['type'] = $_product->getTypeId();
			}
            $arrayKeys = array_keys($parameters);
            if ((count($arrayKeys) == 3) &&
                in_array('id', $arrayKeys) &&
                in_array('sku', $arrayKeys) &&
                in_array('type', $arrayKeys)) {

                return [$parameters, 'catalog_product_view'];
            }
        } else {
            return [$parameters, $defaultHandle];
        }
    }

}
