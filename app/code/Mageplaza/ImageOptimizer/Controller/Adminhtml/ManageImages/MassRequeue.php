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
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\ImageOptimizer\Controller\Adminhtml\Image;
use Mageplaza\ImageOptimizer\Model\Config\Source\Status;

/**
 * Class MassRequeue
 * @package Mageplaza\ImageOptimizer\Controller\Adminhtml\ManageImages
 */
class MassRequeue extends Image
{
    /**
     * @return $this|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->helperData->isEnabled()) {
            return $this->isDisable($resultRedirect);
        }

        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return $resultRedirect->setPath('*/*/');
        }

        $updated = 0;
        foreach ($collection as $image) {
            try {
                $data = [
                    'status'  => Status::PENDING,
                    'message' => ''
                ];
                $image->addData($data)->save();
                $updated++;
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while requeue for %1.', $image->getData('path'))
                );
            }
        }

        if ($updated) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $updated));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
