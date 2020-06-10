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
 * Class Restore
 * @package Mageplaza\ImageOptimizer\Controller\Adminhtml\ManageImages
 */
class Restore extends Image
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

        if (!$this->helperData->getConfigGeneral('backup_image')) {
            $this->messageManager->addErrorMessage(__('Backup functionality is currently disabled. Please enable for backups'));

            return $resultRedirect->setPath('*/*/');
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
            $result = $this->helperData->restoreImage($model->getData('path'));
            if ($result) {
                $model->addData([
                    'status'        => Status::SKIPPED,
                    'optimize_size' => null,
                    'percent'       => null,
                    'message'       => ''
                ]);
                $this->resourceModel->save($model);
                $this->messageManager->addSuccessMessage(__('The image has been successfully restored'));
            } else {
                $model->addData([
                    'status'  => Status::ERROR,
                    'message' => __('The file %1 is not writable', $model->getData('path'))
                ]);
                $this->resourceModel->save($model);
                $this->messageManager->addErrorMessage(__('The file %1 is not writable', $model->getData('path')));
            }
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while restore for %1.', $model->getData('path'))
            );
            $this->logger->critical($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}
