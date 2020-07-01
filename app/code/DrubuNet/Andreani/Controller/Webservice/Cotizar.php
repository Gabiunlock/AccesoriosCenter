<?php

namespace DrubuNet\Andreani\Controller\Webservice;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\TestFramework\Inspection\Exception;
use DrubuNet\Andreani\Model\Webservice;
use Magento\Checkout\Model\Session;
use DrubuNet\Andreani\Helper\Data as AndreaniHelper;
use DrubuNet\Andreani\Model\TarifaFactory;
use DrubuNet\Andreani\Model\SucursalFactory;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Checkout\Model\Cart;

/**
 * Class Cotizar
 *
 * @description
 *
 *
 * @author Mauro Maximiliano Martinez <mmartinez@ids.net.ar>
 * @package DrubuNet\Andreani\Controller\Webservice
 */
class Cotizar extends Action
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
     * @var AndreaniHelper
     */
    protected $_andreaniHelper;

    /**
     * @var TarifaFactory
     */
    protected $_tarifaFactory;

    /**
     * @var PriceHelper
     */
    protected $_priceHelper;

    /**
     * @var Cart
     */
    protected $_cart;

    /**
     * @var SucursalFactory
     */
    protected $_sucursalFactory;

    /**
     * Cotizar constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param Webservice $webservice
     * @param Session $checkoutSession
     * @param AndreaniHelper $andreaniHelper
     * @param TarifaFactory $tarifaFactory
     * @param PriceHelper $priceHelper
     * @param Cart $cart
     * @param SucursalFactory $sucursalFactory
     */
    public function __construct
    (
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        Webservice $webservice,
        Session $checkoutSession,
        AndreaniHelper $andreaniHelper,
        TarifaFactory  $tarifaFactory,
        PriceHelper $priceHelper,
        Cart $cart,
        SucursalFactory $sucursalFactory
    )
    {
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_webservice          = $webservice;
        $this->_checkoutSession     = $checkoutSession;
        $this->_andreaniHelper      = $andreaniHelper;
        $this->_tarifaFactory       = $tarifaFactory;
        $this->_priceHelper         = $priceHelper;
        $this->_cart                = $cart;
        $this->_sucursalFactory     = $sucursalFactory;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $request = $this->getRequest();
        $result  = $this->_resultJsonFactory->create();
        $helper = $this->_andreaniHelper;

        if($request->isXmlHttpRequest())
        {
            $checkoutSession = $this->_checkoutSession;
            $tipoCarrier     = $request->getParam('tipoCarrier');
            $sucursalId      = $request->getParam('sucursalId');
            $sucursalTxt      = $request->getParam('sucursalTxt');
            $sucursalTxtArray = explode(',', $sucursalTxt);
            if(isset($sucursalTxtArray[1])){
                $destCP = trim($sucursalTxtArray[1]);
            }
            $pesoTotal       = 0;
            $volumenTotal    = 0;
            $valorProductos  = 0;
            $quote           = $this->_cart->getQuote();

            if($checkoutSession->getFreeShipping())
            {
                $checkoutSession->setCotizacionAndreaniSucursal(0);
                $checkoutSession->setCodigoSucursalAndreani($sucursalId);
                $checkoutSession->setNombreAndreaniSucursal($request->getParam('sucursalTxt'));

                $costoEnvio = $this->_priceHelper->currency(0,true,false);

                return $result->setData(['cotizacion'=> $costoEnvio,'method_title'=>$request->getParam('sucursalTxt')]);
            }
            else
            {
                if($helper->getTipoCotizacion() == $helper::COTIZACION_ONLINE)
                {
                    foreach($quote->getAllItems() as $_item)
                    {
                        if($_item->getProductType() == 'configurable')
                            continue;

                        $_producto = $_item->getProduct();

                        if($_item->getParentItem())
                            $_item = $_item->getParentItem();

                        $volumenTotal += (int) $_producto->getResource()
                                ->getAttributeRawValue($_producto->getId(),'volumen',$_producto->getStoreId()) * $_item->getQty();

                        $pesoTotal += $_item->getQty() * $_item->getWeight();

                        if($_producto->getCost())
                            $valorProductos += $_producto->getCost() * $_item->getQty();
                        else
                            $valorProductos += $_item->getPrice() * $_item->getQty();
                    }

                    $ws = $this->_webservice;

                    $sucursal = $this->_sucursalFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('codigo_sucursal',['eq'=>$sucursalId])
                        ->getFirstItem();

                    /**
                     * conversion de los kg a gramos
                     */
                    $pesoTotal = $pesoTotal * 1000;

                    $costoEnvio = $ws->cotizarEnvio(
                        [
                            'sucursalRetiro'=> $sucursalId,
                            'cpDestino'     => $destCP, //$sucursal->getCodigoPostal(),
                            'volumen'       => $volumenTotal,
                            'peso'          => $pesoTotal,
                            'valorDeclarado'=> $valorProductos,
                        ],$tipoCarrier ? $tipoCarrier : \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE);
                }
                elseif($helper->getTipoCotizacion() == $helper::COTIZACION_TABLA)
                {
                    foreach ($quote->getAllItems() as $_item)
                    {
                        if($_item->getProductType() == 'configurable')
                            continue;

                        $pesoTotal += $_item->getQty() * $_item->getWeight();
                    }

                    /**
                     * conversion de los kg a gramos
                     */
                    $pesoTotal = $pesoTotal * 1000;

                    /** @var $tarifa \DrubuNet\Andreani\Model\Tarifa */
                    $tarifa = $this->_tarifaFactory->create();

                    $costoEnvio = $tarifa->cotizarEnvio(
                        [
                            'codigoSucursal'=> $sucursalId,
                            'peso'          => $pesoTotal,
                            'tipo'          => \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE
                        ]);
                }

                if($costoEnvio)
                {
                    $checkoutSession->setCotizacionAndreaniSucursal($costoEnvio);
                    $checkoutSession->setCodigoSucursalAndreani($sucursalId);

                    /**
                     * Temporal! el nombre de la sucursal no debe venir por parametro. Se debe cargar al traer la sucursal
                     * con el id que viene por parametro...
                     */
                    $checkoutSession->setNombreAndreaniSucursal($request->getParam('sucursalTxt'));

                    /**
                     * Formateo el precio con el seteado en la tienda
                     */
                    $costoEnvio = $this->_priceHelper->currency($costoEnvio,true,false);

                    return $result->setData(['cotizacion'=> $costoEnvio,'method_title'=>$request->getParam('sucursalTxt')]);
                }
            }
        }

        return $result->setData([]);
    }
}