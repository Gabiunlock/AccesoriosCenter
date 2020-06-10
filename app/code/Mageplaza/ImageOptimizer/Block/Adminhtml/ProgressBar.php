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

namespace Mageplaza\ImageOptimizer\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Mageplaza\ImageOptimizer\Helper\Data;
use Mageplaza\ImageOptimizer\Model\Config\Source\Status;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image\Collection;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image\CollectionFactory;

/**
 * Class ProgressBar
 * @package Mageplaza\ImageOptimizer\Block\Adminhtml
 */
class ProgressBar extends Template
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * ProgressBar constructor.
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Data $helperData,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->helperData        = $helperData;

        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->helperData->isEnabled();
    }

    /**
     * @return Collection
     */
    public function getImagePendingCollection()
    {
        return $this->getImageCollection()->addFieldToFilter('status', Status::PENDING);
    }

    /**
     * @return Collection
     */
    public function getImageCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * @param $status
     *
     * @return string
     */
    public function getBarContent($status)
    {
        if ($this->getTotalByStatus($status) === 0) {
            return '';
        }

        return $this->getWidthByStatus($status) . ' '
            . $status . ' (' . $this->getTotalByStatus($status)
            . '/' . $this->getTotalImage() . ')';
    }

    /**
     * @param $status
     *
     * @return int
     */
    public function getTotalByStatus($status)
    {
        $collection = $this->getImageCollection();
        $collection->addFieldToFilter('status', $status);

        return $collection->getSize();
    }

    /**
     * @param $status
     *
     * @return string
     */
    public function getWidthByStatus($status)
    {
        if ($this->getTotalImage() === 0 || $this->getTotalByStatus($status) === 0) {
            return '0%';
        }
        $width = $this->getTotalByStatus($status) / $this->getTotalImage();

        return round($width * 100, 3) . '%';
    }

    /**
     * @return int
     */
    public function getTotalImage()
    {
        return $this->getImageCollection()->getSize();
    }

    /**
     * Get url for optimize image
     *
     * @return string
     */
    public function getOptimizeUrl()
    {
        return $this->getUrl('*/*/optimize');
    }
}
