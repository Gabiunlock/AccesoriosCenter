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

namespace Mageplaza\ImageOptimizer\Cron;

use Exception;
use Mageplaza\ImageOptimizer\Helper\Data;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image as ResourceImage;
use Psr\Log\LoggerInterface;

/**
 * Class Scan
 * @package Mageplaza\ImageOptimizer\Cron
 */
class Scan
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var ResourceImage
     */
    protected $resourceModel;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Scan constructor.
     *
     * @param Data $helperData
     * @param ResourceImage $resourceModel
     * @param LoggerInterface $logger
     */
    public function __construct(
        Data $helperData,
        ResourceImage $resourceModel,
        LoggerInterface $logger
    ) {
        $this->helperData    = $helperData;
        $this->resourceModel = $resourceModel;
        $this->logger        = $logger;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        if (!$this->helperData->isEnabled() || !$this->helperData->getCronJobConfig('enabled_scan')) {
            return $this;
        }

        try {
            $data = $this->helperData->scanFiles();
            if (empty($data)) {
                return $this;
            }
            $this->resourceModel->insertImagesData($data);
        } catch (Exception  $e) {
            $this->logger->critical($e->getMessage());
        }

        return $this;
    }
}
