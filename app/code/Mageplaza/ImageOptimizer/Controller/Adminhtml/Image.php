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

namespace Mageplaza\ImageOptimizer\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ImageOptimizer\Helper\Data;
use Mageplaza\ImageOptimizer\Model\ImageFactory;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image as ResourceImage;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image\CollectionFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Image
 * @package Mageplaza\ImageOptimizer\Controller\Adminhtml
 */
abstract class Image extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Mageplaza_ImageOptimizer::grid';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var ImageFactory
     */
    protected $imageFactory;

    /**
     * @var ResourceImage
     */
    protected $resourceModel;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Mass Action Filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Image constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ImageFactory $imageFactory
     * @param ResourceImage $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param Data $helperData
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ImageFactory $imageFactory,
        ResourceImage $resourceModel,
        CollectionFactory $collectionFactory,
        Filter $filter,
        Data $helperData,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->imageFactory      = $imageFactory;
        $this->resourceModel     = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->filter            = $filter;
        $this->helperData        = $helperData;
        $this->logger            = $logger;

        parent::__construct($context);
    }

    /**
     * @return Page
     */
    protected function initPage()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mageplaza_ImageOptimizer::grid')
            ->addBreadcrumb(__('Image Optimizer'), __('Image Optimizer'))
            ->addBreadcrumb(__('Manage Images'), __('Manage Images'));

        return $resultPage;
    }

    /**
     * @param Redirect $resultRedirect
     *
     * @return Redirect
     */
    protected function isDisable($resultRedirect)
    {
        $this->messageManager->addErrorMessage(__('The module has been disabled.'));

        return $resultRedirect->setPath('*/*/');
    }
}
