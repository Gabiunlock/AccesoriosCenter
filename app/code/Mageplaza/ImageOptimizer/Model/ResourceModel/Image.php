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
 * @package     Mageplaza_ImageOptimizer
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ImageOptimizer\Model\ResourceModel;

use Exception;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Image
 * @package Mageplaza\ImageOptimizer\Model\ResourceModel
 */
class Image extends AbstractDb
{
    /**
     * Insert image data
     *
     * @param $data
     */
    public function insertImagesData($data)
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $connection->insertMultiple($this->getMainTable(), $data);
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
        }
    }

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('mageplaza_image_optimizer', 'image_id');
    }
}
