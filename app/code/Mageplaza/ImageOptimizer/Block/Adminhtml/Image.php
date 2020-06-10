<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Imageoptimizer
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ImageOptimizer\Block\Adminhtml;

use Magento\Backend\Block\Widget\Container;

/**
 * Class Image
 * @package Mageplaza\ImageOptimizer\Block\Adminhtml
 */
class Image extends Container
{
    /**
     * Prepare button and grid
     *
     * @return Container
     */
    protected function _prepareLayout()
    {
        $addButtonOptimize = [
            'id'           => 'optimize_image',
            'label'        => __('Optimize Images'),
            'class'        => 'primary',
            'button_class' => '',
        ];
        $this->buttonList->add('optimize_image', $addButtonOptimize);

        $addButtonScan = [
            'id'           => 'scan_image',
            'label'        => __('Scan Images'),
            'class'        => 'primary',
            'button_class' => '',
            'onclick'      => 'setLocation(\'' . $this->getScanUrl() . '\')',
        ];
        $this->buttonList->add('scan_image', $addButtonScan);

        return parent::_prepareLayout();
    }

    /**
     * Get url for scan image
     *
     * @return string
     */
    public function getScanUrl()
    {
        return $this->getUrl('*/*/scan');
    }
}
