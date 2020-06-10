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

namespace Mageplaza\ImageOptimizer\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package Mageplaza\ImageOptimizer\Model\Config\Source
 */
class Status implements OptionSourceInterface
{
    const PENDING = 'pending';
    const ERROR   = 'error';
    const SUCCESS = 'success';
    const SKIPPED = 'skipped';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PENDING, 'label' => __('Pending')],
            ['value' => self::ERROR, 'label' => __('Error')],
            ['value' => self::SUCCESS, 'label' => __('Success')],
            ['value' => self::SKIPPED, 'label' => __('Skipped')]
        ];
    }
}
