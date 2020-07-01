<?php

namespace DrubuNet\Andreani\Controller\Sucursal;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\TestFramework\Inspection\Exception;
use DrubuNet\Andreani\Model\Webservice;
use DrubuNet\Andreani\Helper\Data as AndreaniHelper;

/**
 * Class Index
 *
 * @description Recibe un codigo postal y devuelve las sucursales que tiene disponible
 *
 * @author Mauro Maximiliano Martinez <mmartinez@ids.net.ar>
 * @package DrubuNet\Andreani\Controller\Sucursal
 */
class Index extends Action
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
     * @var AndreaniHelper
     */
    protected $_andreaniHelper;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param OrderRepositoryInterface $orderRepositoryInterface
     * @param JsonFactory $resultJsonFactory
     * @param Webservice $webservice
     * @param AndreaniHelper $andreaniHelper
     */
    public function __construct
    (
        Context $context,
        PageFactory $resultPageFactory,
        OrderRepositoryInterface $orderRepositoryInterface,
        JsonFactory $resultJsonFactory,
        Webservice $webservice,
        AndreaniHelper $andreaniHelper
    )
    {
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_webservice          = $webservice;
        $this->_andreaniHelper      = $andreaniHelper;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $request    = $this->getRequest();
        $result     = $this->_resultJsonFactory->create();
        $sucursales = [];

        if($request->isXmlHttpRequest())
        {
            $helper = $this->_andreaniHelper;

            $ws = $this->_webservice;

            $sucursales = $ws->consultarSucursales(
                [
                    'cpDestino'=> $request->getParam('codigoPostal') ? $request->getParam('codigoPostal'):null,
                    'provincia'=> $request->getParam('provincia') ? $request->getParam('provincia'):null,
                    'localidad'=> $request->getParam('localidad') ? $request->getParam('localidad'):null,
                ]);
        }

        return $result->setData($sucursales);
    }
}