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

namespace Mageplaza\ImageOptimizer\Controller\Adminhtml\ManageImages;

use Exception;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Mageplaza\ImageOptimizer\Controller\Adminhtml\Image;
use Mageplaza\ImageOptimizer\Model\Config\Source\Status;

/**
 * Class Skip
 * @package Mageplaza\ImageOptimizer\Controller\Adminhtml\ManageImages
 */
class Skip extends Image
{
    /**
     * @return Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->helperData->isEnabled()) {
            return $this->isDisable($resultRedirect);
        }

        /** @var \Mageplaza\ImageOptimizer\Model\Image $model */
        $model   = $this->imageFactory->create();
        $imageId = $this->getRequest()->getParam('image_id');
        try {
            if ($imageId) {
                $this->resourceModel->load($model, $imageId);
                if ($imageId !== $model->getId()) {
                    $this->messageManager->addErrorMessage(__('The wrong image is specified.'));

                    return $resultRedirect->setPath('*/*/');
                }
            }
            $model->setData('status', Status::SKIPPED);
            $this->resourceModel->save($model);
            $this->messageManager->addSuccessMessage(__('The image has been successfully updated.'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->logger->critical($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}
