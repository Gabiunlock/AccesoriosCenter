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

namespace Mageplaza\ImageOptimizer\Console\Command;

use Exception;
use Magento\Framework\Console\Cli;
use Mageplaza\ImageOptimizer\Helper\Data;
use Mageplaza\ImageOptimizer\Model\Config\Source\Status;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image as ResourceImage;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image\Collection as ImageOptimizerCollection;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image\CollectionFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Optimize
 * @package Mageplaza\ImageOptimizer\Console\Command
 */
class Optimize extends Command
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
     * Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Optimize constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param ResourceImage $resourceModel
     * @param Data $helperData
     * @param LoggerInterface $logger
     * @param string|null $name
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ResourceImage $resourceModel,
        Data $helperData,
        LoggerInterface $logger,
        string $name = null
    ) {
        parent::__construct($name);

        $this->collectionFactory = $collectionFactory;
        $this->resourceModel     = $resourceModel;
        $this->helperData        = $helperData;
        $this->logger            = $logger;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->helperData->isEnabled()) {
            $output->writeln('<error>Command cannot run because the module is disabled.</error>');

            return Cli::RETURN_FAILURE;
        }

        $count = 0;
        /** @var ImageOptimizerCollection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', Status::PENDING);
        $limit = $this->helperData->getCronJobConfig('limit_number');
        $size  = $collection->getSize();
        if ($limit < $size) {
            $collection->setPageSize($limit);
        } else {
            $limit = $size;
        }

        foreach ($collection as $image) {
            try {
                $result = $this->helperData->optimizeImage($image->getData('path'));
                $data   = [
                    'optimize_size' => isset($result['error']) ? null : $result['dest_size'],
                    'percent'       => isset($result['error']) ? null : $result['percent'],
                    'status'        => isset($result['error']) ? Status::ERROR : Status::SUCCESS,
                    'message'       => isset($result['error_long']) ? $result['error_long'] : ''
                ];
                $image->addData($data);
                $this->resourceModel->save($image);
                $count++;
                $percent = round(($count / $limit) * 100, 2) . '%';
                if (isset($result['error'])) {
                    $output->writeln('<error>The problem occurred during image optimization '.$image->getData('path').'.</error>');
                    $this->logger->critical($result['error_long']);
                } else {
                    $output->writeln('<info>Image '.$image->getData('path').' have been optimized successfully. ('.$count.'/'.$limit.' '.$percent.')</info>');
                }
            } catch (Exception $e) {
                $output->writeln('<error>The problem occurred during image optimization '.$image->getData('path').'.</error>');
                $this->logger->critical($e->getMessage());
            }
        }

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('mpimageoptimizer:optimize');
        $this->setDescription(__('Image Optimizer optimize images.'));

        parent::configure();
    }
}
