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
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Mageplaza\ImageOptimizer\Controller\Adminhtml\Image;
use Mageplaza\ImageOptimizer\Helper\Data;
use Mageplaza\ImageOptimizer\Model\Config\Source\Status;

/**
 * Class Optimize
 * @package Mageplaza\ImageOptimizer\Controller\Adminhtml\ManageImages
 */
class Optimize extends Image
{
    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $isAjax = $this->getRequest()->getParam('isAjax');
        if (!$this->helperData->isEnabled() && !$isAjax) {
            return $this->isDisable($resultRedirect);
        }

        $imageId = $this->getRequest()->getParam('image_id');
        try {
            /** @var \Mageplaza\ImageOptimizer\Model\Image $model */
            $model = $this->imageFactory->create();
            if ($imageId) {
                $this->resourceModel->load($model, $imageId);
                if ($imageId !== $model->getId()) {
                    return $this->checkStatus($model, $resultRedirect, $isAjax);
                }
                $status = $model->getData('status');
                if ($status === Status::SUCCESS) {
                    return $this->checkStatus($model, $resultRedirect, $isAjax);
                }

                if ($status === Status::SKIPPED) {
                    return $this->checkStatus($model, $resultRedirect, $isAjax);
                }
            }
            $result = $this->helperData->optimizeImage($model->getData('path'));
            $this->saveImage($model, $result);
            if ($isAjax) {
                if (isset($result['error'])) {
                    return $this->getResponse()->representJson(Data::jsonEncode([
                        'status' => __('Error'),
                        'path'   => $model->getData('path')
                    ]));
                }

                return $this->getResponse()->representJson(Data::jsonEncode([
                    'status' => __('Success'),
                    'path'   => $model->getData('path')
                ]));
            }
            $this->getMessageContent($result);
        } catch (Exception $e) {
            if ($isAjax) {
                return $this->getResponse()->representJson(Data::jsonEncode([
                    'status' => __('Error'),
                    'path'   => $model->getData('path')
                ]));
            }
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->logger->critical($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param \Mageplaza\ImageOptimizer\Model\Image $model
     * @param array $result
     *
     * @return \Mageplaza\ImageOptimizer\Model\Image
     * @throws AlreadyExistsException
     */
    protected function saveImage($model, $result)
    {
        $data = [
            'optimize_size' => isset($result['error']) ? $model->getData('optimize_size') : $result['dest_size'],
            'percent'       => isset($result['error']) ? null : $result['percent'],
            'status'        => isset($result['error']) ? Status::ERROR : Status::SUCCESS,
            'message'       => isset($result['error_long']) ? $result['error_long'] : ''
        ];
        $model->addData($data);
        $this->resourceModel->save($model);

        return $model;
    }

    /**
     * @param array $result
     */
    protected function getMessageContent($result)
    {
        if (isset($result['error'])) {
            $this->messageManager->addErrorMessage(__($result['error_long']));
        } else {
            $this->messageManager->addSuccessMessage(__('Image(s) have been optimized successfully.'));
        }
    }

    /**
     * @param $model
     * @param $resultRedirect
     * @param bool $isAjax
     *
     * @return mixed
     */
    protected function checkStatus($model, $resultRedirect, $isAjax = false)
    {
        $status = $model->getData('status');
        if ($status === Status::SUCCESS) {
            if ($isAjax) {
                return $this->getResponse()->representJson(Data::jsonEncode([
                    'status' => __('Already optimized, please requeue to optimize again'),
                    'path'   => $model->getData('path')
                ]));
            }
            $this->messageManager->addErrorMessage(__('The image(s) had already been optimized previously'));

            return $resultRedirect->setPath('*/*/');
        }

        if ($status === Status::SKIPPED) {
            if ($isAjax) {
                return $this->getResponse()->representJson(Data::jsonEncode([
                    'status' => __('Skipped'),
                    'path'   => $model->getData('path')
                ]));
            }
            $this->messageManager->addErrorMessage(__('The image(s) are skipped.'));

            return $resultRedirect->setPath('*/*/');
        }

        if ($isAjax) {
            return $this->getResponse()->representJson(Data::jsonEncode([
                'status' => __('Error'),
                'path'   => $model->getData('path')
            ]));
        }
        $this->messageManager->addErrorMessage(__('The wrong image is specified.'));

        return $resultRedirect->setPath('*/*/');
    }
}
