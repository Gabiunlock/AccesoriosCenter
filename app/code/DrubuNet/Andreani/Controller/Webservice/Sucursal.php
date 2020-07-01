<?php

namespace DrubuNet\Andreani\Controller\Webservice;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\TestFramework\Inspection\Exception;
use DrubuNet\Andreani\Model\Webservice;
use Magento\Checkout\Model\Session;

/**
 * Class Sucursal
 *
 * @description Recibe un codigo postal y devuelve las sucursales que tiene disponible
 *
 *
 * @author Mauro Maximiliano Martinez <mmartinez@ids.net.ar>
 * @package DrubuNet\Andreani\Controller\Webservice
 */
class Sucursal extends Action
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
     * @var Webservice
     */
    protected $_webservice;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * Sucursal constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param Webservice $webservice
     * @param Session $checkoutSession
     */
    public function __construct
    (
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        Webservice $webservice,
        Session $checkoutSession
    )
    {
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_webservice          = $webservice;
        $this->_checkoutSession     = $checkoutSession;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        /**
         * Deberia recibir:
         *                  Codigo postal de destino
         */

        $request = $this->getRequest();
        $result  = $this->_resultJsonFactory->create();

        if($codigoPostal = $request->getParam('codigoPostal') && $request->isXmlHttpRequest())
        {
            $checkoutSession = $this->_checkoutSession;

            //llamar al ws
            $sucursales = [
                ['sucursal_id'=>'1','nombre'=>'San justo - Arieta 1930'],
                ['sucursal_id'=>'2','nombre'=>'Ramos Mejia - Avenida Rivadavia 1245']
            ];

            if(count($sucursales))
            {
                return $result->setData(['sucursales'=> $sucursales]);
            }
        }

        return $result->setData([]);
    }
}