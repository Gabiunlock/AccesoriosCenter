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

namespace Mageplaza\ImageOptimizer\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Action
 * @package Mageplaza\ImageOptimizer\Ui\Component\Listing\Column
 */
class Action extends Column
{
    /** Url path */
    const URL_PATH_OPTIMIZE = 'mpimageoptimizer/manageimages/optimize';
    const URL_PATH_RESTORE  = 'mpimageoptimizer/manageimages/restore';
    const URL_PATH_REQUEUE  = 'mpimageoptimizer/manageimages/requeue';
    const URL_PATH_SKIP     = 'mpimageoptimizer/manageimages/skip';
    const URL_PATH_DELETE   = 'mpimageoptimizer/manageimages/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Action constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['image_id'])) {
                    $item[$name]['optimize'] = [
                        'href'  => $this->urlBuilder->getUrl(
                            self::URL_PATH_OPTIMIZE,
                            ['image_id' => $item['image_id']]
                        ),
                        'label' => __('Optimize')
                    ];
                    $item[$name]['restore']  = [
                        'href'  => $this->urlBuilder->getUrl(
                            self::URL_PATH_RESTORE,
                            ['image_id' => $item['image_id']]
                        ),
                        'label' => __('Restore')
                    ];
                    $item[$name]['requeue']  = [
                        'href'  => $this->urlBuilder->getUrl(
                            self::URL_PATH_REQUEUE,
                            ['image_id' => $item['image_id']]
                        ),
                        'label' => __('Requeue')
                    ];
                    $item[$name]['delete']   = [
                        'href'    => $this->urlBuilder->getUrl(
                            self::URL_PATH_DELETE,
                            ['image_id' => $item['image_id']]
                        ),
                        'label'   => __('Delete'),
                        'confirm' => [
                            'title'         => __('Delete'),
                            'message'       => __('Are you sure you want to delete a record?'),
                            '__disableTmpl' => true,
                        ],
                        'post'    => true,
                    ];
                    $item[$name]['skip']     = [
                        'href'  => $this->urlBuilder->getUrl(
                            self::URL_PATH_SKIP,
                            ['image_id' => $item['image_id']]
                        ),
                        'label' => __('Skip')
                    ];
                }
            }
        }

        return $dataSource;
    }
}
