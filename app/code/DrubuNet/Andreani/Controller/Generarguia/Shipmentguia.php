<?php

namespace DrubuNet\Andreani\Controller\Generarguia;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\TestFramework\Inspection\Exception;

/**
 * Class Shipmentguia
 *
 * @description
 *
 * @author Jhonattan Campo <jcampo@ids.net.ar>
 * @package DrubuNet\Andreani\Controller\Generarguia
 */
class Shipmentguia extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct
    (
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_resultJsonFactory   = $resultJsonFactory;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        return $this->_resultPageFactory->create();

    }
}


