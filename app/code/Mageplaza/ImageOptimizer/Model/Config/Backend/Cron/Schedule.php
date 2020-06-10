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

namespace Mageplaza\ImageOptimizer\Model\Config\Backend\Cron;

use Exception;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\App\Config\ValueFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class Schedule
 * @package Mageplaza\ImageOptimizer\Model\Config\Backend\Cron
 */
class Schedule extends Value
{
    /**
     * Cron optimize path
     */
    const CRON_OPTIMIZE_PATH = 'crontab/default/jobs/mpimageoptimizer_cronjob_optimize/schedule/cron_expr';

    /**
     * Cron scan path
     */
    const CRON_SCAN_PATH = 'crontab/default/jobs/mpimageoptimizer_cronjob_scan/schedule/cron_expr';

    /**
     * @var ValueFactory
     */
    protected $_configValueFactory;

    /**
     * @var string
     */
    protected $_runModelPath = '';

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * Schedule constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param ValueFactory $configValueFactory
     * @param ManagerInterface $messageManager
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param string $runModelPath
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        ValueFactory $configValueFactory,
        ManagerInterface $messageManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        $runModelPath = '',
        array $data = []
    ) {
        $this->_runModelPath       = $runModelPath;
        $this->_configValueFactory = $configValueFactory;
        $this->messageManager      = $messageManager;

        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @return Value
     */
    public function afterSave()
    {
        $enableScan       = $this->getData('groups/cron_job/fields/enabled_scan/value');
        $scanSchedule     = $this->getData('groups/cron_job/fields/scan_schedule/value');
        $enableOptimize   = $this->getData('groups/cron_job/fields/enabled_optimize/value');
        $optimizeSchedule = $this->getData('groups/cron_job/fields/optimize_schedule/value');

        if ($enableScan) {
            try {
                $this->_configValueFactory->create()->load(
                    self::CRON_SCAN_PATH,
                    'path'
                )->setValue(
                    $scanSchedule
                )->setPath(
                    self::CRON_SCAN_PATH
                )->save();
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t save the cron expression. %1', $e->getMessage()));
            }
        }

        if ($enableOptimize) {
            try {
                $this->_configValueFactory->create()->load(
                    self::CRON_OPTIMIZE_PATH,
                    'path'
                )->setValue(
                    $optimizeSchedule
                )->setPath(
                    self::CRON_OPTIMIZE_PATH
                )->save();
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t save the cron expression. %1', $e->getMessage()));
            }
        }

        return parent::afterSave();
    }
}
