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
use Mageplaza\ImageOptimizer\Controller\Adminhtml\Image;

/**
 * Class Scan
 * @package Mageplaza\ImageOptimizer\Controller\Adminhtml\ManageImages
 */
class Scan extends Image
{
    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->helperData->isEnabled()) {
            return $this->isDisable($resultRedirect);
        }

        try {
            $data = $this->helperData->scanFiles();
            if (empty($data)) {
                $this->messageManager->addErrorMessage(__('Sorry, no images are found after scan.'));

                return $resultRedirect->setPath('*/*/');
            }
            $this->resourceModel->insertImagesData($data);
            $this->messageManager->addSuccessMessage(__('Successful data scanning'));
        } catch (Exception  $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while scan image. Please review the error log.')
            );
            $this->logger->critical($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}
