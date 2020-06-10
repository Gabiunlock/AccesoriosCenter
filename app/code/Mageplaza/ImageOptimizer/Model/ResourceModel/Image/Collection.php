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
 * @package     Mageplaza_imageOptimizer
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ImageOptimizer\Model\ResourceModel\Image;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mageplaza\ImageOptimizer\Model\Image;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image as ImageResourceModel;

/**
 * Class Collection
 * @package Mageplaza\ImageOptimizer\Model\ResourceModel\Image
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'image_id';

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(Image::class, ImageResourceModel::class);
    }
}
