<?php
/**
 *
 * @name DrubuNet\Welivery\Controller\Index\Shippingcalc
 *
 * @description Welivery shipping calculation action
 *
 */
namespace DrubuNet\Andreani\Controller\Index;

use \Magento\Framework\App\Action\Context;
use \Magento\Catalog\Helper\Product\View;
use \Magento\Framework\Controller\Result\ForwardFactory;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Pricing\Helper\Data;
use \Magento\Catalog\Api\ProductRepositoryInterface;

use DrubuNet\Andreani\Model\Webservice;
use DrubuNet\Andreani\Helper\Data as AndreaniHelper;
use DrubuNet\Andreani\Model\TarifaFactory;
use DrubuNet\Andreani\Model\SucursalFactory;

class Shippingcalc extends \Magento\Catalog\Controller\Product\View
{
    /**
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory Return JSON data to AJAX call
     *
     */
    protected $_resultJsonFactory;

    /**
     *
     * @var \Magento\Framework\Pricing\Helper\Data Price helper to format rate
     *
     */
    protected $_priceHelper;


    /**
     * @var AndreaniHelper
     */
    protected $_andreaniHelper;


    /**
     * @var TarifaFactory
     */
    protected $_tarifaFactory;


    /**
     * @var SucursalFactory
     */
    protected $_sucursalFactory;


    /**
     * @var registry
     */
    protected $_registry;

    /**
     * @var productRepository
     */
    protected $_productRepository;

    /**
     * @var stockItem
     */
    protected $_stockItem;


    /**
     *
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Helper\Product\View $viewHelper
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     *
     */
    public function __construct(
        Context $context,
        View $viewHelper,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        Data $priceHelper,
        Webservice $webservice,
        AndreaniHelper $andreaniHelper,
        TarifaFactory  $tarifaFactory,
        SucursalFactory $sucursalFactory,
        ProductRepositoryInterface $productRepository
    ) {
        /**
         *
         * Set the json responder
         *
         */
        $this->_resultJsonFactory = $resultJsonFactory;

        /**
         *
         * Set the price helper
         *
         */
        $this->_priceHelper = $priceHelper;

        /**
         *
         * Set the webservice
         *
         */
        $this->_webservice          = $webservice;

        /**
         *
         * Set the andreanihelper
         *
         */
        $this->_andreaniHelper      = $andreaniHelper;

        /**
         *
         * Set the tarifafactory
         *
         */
        $this->_tarifaFactory       = $tarifaFactory;


        /**
         *
         * Set the sucursal factory
         *
         */
        $this->_sucursalFactory     = $sucursalFactory;


        /**
         *
         * Set the productRepository
         *
         */
        $this->_productRepository = $productRepository;



        parent::__construct($context, $viewHelper, $resultForwardFactory, $resultPageFactory);
    }

    /**
     *
     * Shipping calculation action
     *
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\Controller\Result\Redirect. Return the price rate or an error message in case there is not rate for the postal code
     *
     * @note Get POST data: 'postcode' -> Postal code to get the rate - 'ajax' -> If it is an AJAX call
     *
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $params = $this->getRequest()->getParams();

        if($this->getRequest()->isAjax()) {

            $codigo_postal = $params['postcode'];
            $product_id = $params['productid'];
            $product_qty = $params['productqty'];
            $response_html = '';

            if(empty($codigo_postal)){
                $result->setData(['response' => __('Debe ingresar un código postal')]);
                return $result;
            }

            $helper = $this->_andreaniHelper;
            $sucursalId      = 1;
            $pesoTotal       = 0;
            $volumenTotal    = 0;
            $valorProducto  = 0;

            $tipoCarriers = array();

            if($helper->isActiveAndreaniEstandar()){
                $tipoCarriers[] = 'andreaniestandar';
            }

            if($helper->isActiveAndreaniUrgente()){
                $tipoCarriers[] = 'andreaniurgente';
            }

            $producto = $this->_productRepository->getById($product_id);

            if (!$producto->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }

            if($producto->getTypeId() == 'configurable'){
                $_children = $producto->getTypeInstance()->getUsedProducts($producto);
                $volumen_alto = 0;
                $volumen_bajo = 10000000;
                $peso_alto = 0;
                $peso_bajo = 10000000;
                $valor_alto = 0;
                $valor_bajo = 10000000;
                foreach($_children as $_producto){
                    $volumen = (int) $_producto->getResource()
                            ->getAttributeRawValue($_producto->getId(), 'volumen', $_producto->getStoreId()) * $product_qty;
                    if($volumen_alto < $volumen){
                        $volumen_alto = $volumen;
                    }
                    if($volumen_bajo > $volumen){
                        $volumen_bajo = $volumen;
                    }

                    $peso = $product_qty * $_producto->getWeight();
                    if($peso_alto < $peso){
                        $peso_alto = $peso;
                    }
                    if($peso_bajo > $peso){
                        $peso_bajo = $peso;
                    }

                    if($_producto->getCost())
                        $valor = $_producto->getCost() * $product_qty;
                    else {
                        $finalPrice = $_producto->getPriceInfo()->getPrice('final_price')->getValue();;
                        $valor = $finalPrice * $product_qty;
                    }

                    if($valor_alto < $valor){
                        $valor_alto = $valor;
                    }
                    if($valor_bajo > $valor){
                        $valor_bajo = $valor;
                    }
                }

                if($helper->getTipoCotizacion() == $helper::COTIZACION_ONLINE)
                {
                    $ws = $this->_webservice;

                    $peso_bajo_total = $peso_bajo * 1000;
                    $peso_alto_total = $peso_alto * 1000;

                    foreach($tipoCarriers as $tipoCarrier){
                        /**
                         * conversion de los kg a gramos
                         */

                        $costoEnvio_bajo = $ws->cotizarEnvio(
                            [
                                'sucursalRetiro'=> $sucursalId,
                                /*'cpDestino'     => $sucursal->getCodigoPostal(),*/
                                'cpDestino'     => $codigo_postal,
                                'volumen'       => $volumen_bajo,
                                'peso'          => $peso_bajo_total,
                                'valorDeclarado'=> $valor_bajo,
                            ],$tipoCarrier ? $tipoCarrier : \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE);

                        $costoEnvio_alto = $ws->cotizarEnvio(
                            [
                                'sucursalRetiro'=> $sucursalId,
                                /*'cpDestino'     => $sucursal->getCodigoPostal(),*/
                                'cpDestino'     => $codigo_postal,
                                'volumen'       => $volumen_alto,
                                'peso'          => $peso_alto_total,
                                'valorDeclarado'=> $valor_alto,
                            ],$tipoCarrier ? $tipoCarrier : \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE);

                        if($costoEnvio_bajo != $costoEnvio_alto){
                            if($tipoCarrier == 'andreaniestandar'){
                                $response_html .= '<div>Andreani Estandar: desde '.$this->_priceHelper->currency($costoEnvio_bajo, true, false).' hasta '.$this->_priceHelper->currency($costoEnvio_alto, true, false).'</div>';
                            }elseif($tipoCarrier == 'andreaniurgente'){
                                $response_html .= '<div>Andreani Urgente: desde '.$this->_priceHelper->currency($costoEnvio_bajo, true, false).' hasta '.$this->_priceHelper->currency($costoEnvio_alto, true, false).'</div>';
                            }elseif($tipoCarrier == 'andreanisucursal'){
                                $response_html .= '<div>Andreani Sucursal: desde '.$this->_priceHelper->currency($costoEnvio_bajo, true, false).' hasta '.$this->_priceHelper->currency($costoEnvio_alto, true, false).'</div>';
                            }
                        }else{
                            $costoEnvio = $costoEnvio_bajo;
                            if($costoEnvio){
                                if($tipoCarrier == 'andreaniestandar'){
                                    $response_html .= '<div>Andreani Estandar: '.$this->_priceHelper->currency($costoEnvio, true, false).'</div>';
                                }elseif($tipoCarrier == 'andreaniurgente'){
                                    $response_html .= '<div>Andreani Urgente: '.$this->_priceHelper->currency($costoEnvio, true, false).'</div>';
                                }elseif($tipoCarrier == 'andreanisucursal'){
                                    $response_html .= '<div>Andreani Sucursal: '.$this->_priceHelper->currency($costoEnvio, true, false).'</div>';
                                }
                            }
                        }
                    }

                }
                elseif($helper->getTipoCotizacion() == $helper::COTIZACION_TABLA)
                {
                    $pesoTotal = $product_qty * $producto->getWeight();

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

                    if($costoEnvio){
                        $response_html .= '<div>Cotizacion por tabla: '.$this->_priceHelper->currency($costoEnvio, true, false).'</div>';
                    }
                }
            }else{
                if($helper->getTipoCotizacion() == $helper::COTIZACION_ONLINE)
                {

                    if($producto->getParentItem()){
                        $producto_padre = $producto->getParentItem();
                        $producto_hijo = $producto;
                    }else{
                        $producto_padre = $producto;
                        $producto_hijo = $producto;
                    }

                    $volumenTotal += (int) $producto_padre->getResource()
                            ->getAttributeRawValue($producto_padre->getId(), 'volumen', $producto_padre->getStoreId()) * $product_qty;

                    $pesoTotal = $product_qty * $producto_hijo->getWeight();


                    if($producto_padre->getCost())
                        $valorProducto = $producto_padre->getCost() * $product_qty;
                    else {
                        $finalPrice = $producto_hijo->getPriceInfo()->getPrice('final_price')->getValue();
                        $valorProducto = $finalPrice * $product_qty;
                    }

                    $ws = $this->_webservice;

                    $pesoTotal = $pesoTotal * 1000;

                    foreach($tipoCarriers as $tipoCarrier){
                        /**
                         * conversion de los kg a gramos
                         */

                        $costoEnvio = $ws->cotizarEnvio(
                            [
                                'sucursalRetiro'=> $sucursalId,
                                /*'cpDestino'     => $sucursal->getCodigoPostal(),*/
                                'cpDestino'     => $codigo_postal,
                                'volumen'       => $volumenTotal,
                                'peso'          => $pesoTotal,
                                'valorDeclarado'=> $valorProducto,
                            ],$tipoCarrier ? $tipoCarrier : \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE);

                        if($costoEnvio){
                            if($tipoCarrier == 'andreaniestandar'){
                                $response_html .= '<div>Andreani Estandar: '.$this->_priceHelper->currency($costoEnvio, true, false).'</div>';
                            }elseif($tipoCarrier == 'andreaniurgente'){
                                $response_html .= '<div>Andreani Urgente: '.$this->_priceHelper->currency($costoEnvio, true, false).'</div>';
                            }elseif($tipoCarrier == 'andreanisucursal'){
                                $response_html .= '<div>Andreani Sucursal: '.$this->_priceHelper->currency($costoEnvio, true, false).'</div>';
                            }
                        }
                    }

                }
                elseif($helper->getTipoCotizacion() == $helper::COTIZACION_TABLA)
                {
                    $pesoTotal = $product_qty * $producto->getWeight();

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

                    if($costoEnvio){
                        $response_html .= '<div>Cotizacion por tabla: '.$this->_priceHelper->currency($costoEnvio, true, false).'</div>';
                    }
                }
            }

            /**
             *
             * Validate if rate is not equal to 0
             *
             */
            if(!empty($response_html)) {
                $result->setData(['response' => $response_html]);
            }
            else {
                $result->setData(['response' => __('No hay envíos para ese código postal')]);
            }
        }

        return $result;
    }
}