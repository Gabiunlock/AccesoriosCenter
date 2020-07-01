<?php


namespace DrubuNet\Andreani\Model;

use DrubuNet\Andreani\Api\SucursalManagementInterface;

class SucursalManagement implements SucursalManagementInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    protected $sucursalFactory;
    protected $provinciaFactory;
    protected $localidadFactory;
    protected $webservice;
    protected $andreaniHelper;
    protected $cart;
    private $_tarifaFactory;
    private $soapService;

    /**
     * SucursalManagement constructor.
     * @param \DrubuNet\Andreani\Api\Data\SucursalInterfaceFactory $sucursalInterfaceFactory
     */
    public function __construct(
        \DrubuNet\Andreani\Api\Data\SucursalInterfaceFactory $sucursalInterfaceFactory,
        \DrubuNet\Andreani\Api\Data\ProvinciaInterfaceFactory $provinciaInterfaceFactory,
        \DrubuNet\Andreani\Api\Data\LocalidadInterfaceFactory $localidadInterfaceFactory,
        \DrubuNet\Andreani\Model\Webservice $webservice,
        \DrubuNet\Andreani\Helper\Data $andreaniHelper,
        \Magento\Checkout\Model\Session $_checkoutSession,
        \Magento\Checkout\Model\Cart $cart,
        \DrubuNet\Andreani\Model\Soap\Webservice $webserviceSoap,
        \DrubuNet\Andreani\Model\TarifaFactory $_tarifaFactory
    )
    {
        $this->sucursalFactory = $sucursalInterfaceFactory;
        $this->provinciaFactory = $provinciaInterfaceFactory;
        $this->localidadFactory = $localidadInterfaceFactory;
        $this->webservice = $webservice;
        $this->andreaniHelper = $andreaniHelper;
        $this->_checkoutSession = $_checkoutSession;
        $this->cart = $cart;
        $this->_tarifaFactory = $_tarifaFactory;
        $this->soapService = $webserviceSoap;

    }

    /**
     * @inheritDoc
     */
    public function fetchSucursales($region, $location){
        $result = [];
        if($this->andreaniHelper->getWebserviceMethod() == 'soap'){
            $sucursales = $this->soapService->consultarSucursales();
            $needFilter = (!is_null($region) || !is_null($location));
            try {
                foreach ($sucursales as $item_sucursal) {
                    try {
                        $dirreccion_full = explode(' , ', $item_sucursal->Direccion);

                        if ($needFilter) {
                            if ((!is_null($region) && $region != trim($dirreccion_full[3])) || (!is_null($location) && $location != trim($dirreccion_full[2])))
                                continue;
                        }
                        $sucursal = $this->sucursalFactory->create();
                        $sucursal->setName(trim($item_sucursal->Direccion));
                        $sucursal->setCode(trim($item_sucursal->Numero));
                        $sucursal->setId(trim($item_sucursal->Sucursal));

                        $sucursal->setStreet(trim($dirreccion_full[0]));
                        $sucursal->setPostcode(trim($dirreccion_full [1]));
                        $sucursal->setLocation(trim($dirreccion_full[2]));
                        $sucursal->setRegion(trim($dirreccion_full[3]));

                        $result[] = $sucursal;
                    } catch (\Exception $e) {
                        throw new \Exception("Message : " . $e->getMessage());
                    }
                }
            }catch (\Exception $e){
                \DrubuNet\Andreani\Helper\Data::log($e->getMessage() . " - Sucursales obtenidas: " . print_r($sucursales,true),'error_andreani_fetchSucursales_' . date('Y_m') . '.log');
                throw new \Exception("Error : " . $e->getMessage());
            }
        }
        else {
            $sucursales = $this->webservice->getSucursales();
            $needFilter = (!is_null($region) || !is_null($location));

            foreach ($sucursales as $item_sucursal) {
                try {
                    if ($needFilter) {
                        if ((!is_null($region) && strtoupper($region) != strtoupper($item_sucursal["direccion"]['region'])) || (!is_null($location) && strtoupper($location) != strtoupper($item_sucursal["direccion"]['localidad'])))
                            continue;
                    }
                    $sucursal = $this->sucursalFactory->create();
                    $sucursal->setId($item_sucursal["id"]);
                    $sucursal->setCode($item_sucursal["nomenclatura"]);
                    $sucursal->setStreet(
                        $this->getDireccion($item_sucursal["direccion"]["componentesDeDireccion"])
                    );
                    $sucursal->setPostcode($item_sucursal["direccion"]['codigoPostal']);
                    $sucursal->setLocation($item_sucursal["direccion"]['localidad']);
                    $sucursal->setRegion($item_sucursal["direccion"]['region']);
                    $sucursal->setName("{$sucursal->getStreet()} , {$sucursal->getPostcode()} , {$sucursal->getLocation()} , {$sucursal->getRegion()}");
                    //calle altura , CP , Localidad , Region
                    $result[] = $sucursal;
                } catch (\Exception $e) {
                    throw new \Exception("Message : " . $e->getMessage() . ' - data : ' . print_r($item_sucursal, true));
                }
            }
        }
        usort($result, [$this, "_fieldCompare"]);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function fetchProvincias()
    {
        // TODO: Implement fetchProvincias() method.
        $result = [];
        if($this->andreaniHelper->getWebserviceMethod() == 'soap'){
            $sucursales = $this->fetchSucursales(null,null);
            foreach ($sucursales as $sucursal) {
                if(!array_key_exists($sucursal->getRegion(),$result)) {
                    $provincia = $this->provinciaFactory->create();
                    $provincia->setName($sucursal->getRegion());
                    $result[$sucursal->getRegion()] = $provincia;
                }
            }
        }
        else {
            $regions = $this->webservice->getRegions();
            foreach ($regions as $item) {
                $provincia = $this->provinciaFactory->create();
                $provincia->setName($item['contenido']);
                $result[] = $provincia;
            }
        }
        usort($result, [$this, "_fieldCompare"]);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function fetchLocalidades($region)
    {
        $result = [];
        $sucursales = $this->fetchSucursales($region,null);
        foreach ($sucursales as $sucursal){
            if(!array_key_exists($sucursal->getLocation(),$result)){
                $localidad = $this->localidadFactory->create();
                $localidad->setName($sucursal->getLocation());
                $result[$sucursal->getLocation()] = $localidad;
            }
        }
        usort($result, [$this, "_fieldCompare"]);

        return $result;
    }

    public function getCotizacionSucursal($sucursal){
        $checkoutSession = $this->_checkoutSession;
        $helper = $this->andreaniHelper;
        $sucursal_data = $sucursal;
        if(!is_null($sucursal_data)){
            $costoEnvio = 0;
            if($checkoutSession->getFreeShipping()){
                $costoEnvio = 0;
            }
            else {
                $bultosData = $this->getQuoteData(); //pesoTotal, valorDeclarado y volumen
                if($helper->getTipoCotizacion() == $helper::COTIZACION_ONLINE) {
                    $params = array(
                        "cpDestino" => $sucursal_data->getPostcode(),//CP de la sucursal, viene en la info
                        "contrato" => $this->andreaniHelper->getSucursalContrato(),//nro contrato, config,
                        "cliente" => $this->andreaniHelper->getNroCliente(),//Codigo cliente, config,
                        "sucursalOrigen" => $sucursal_data->getCode(),//Codigo sucursal
                        "bultos" => [$bultosData]
                    );

                    if($this->andreaniHelper->getWebserviceMethod() == 'soap'){
                        $costoEnvio = $this->soapService->cotizarEnvio($params,\DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE);
                    }
                    else {
                        $costoEnvio = $this->webservice->cotizarEnvio($params)["tarifaConIva"]["total"];
                    }
                }
                elseif($helper->getTipoCotizacion() == $helper::COTIZACION_TABLA)
                {

                    /** @var $tarifa \DrubuNet\Andreani\Model\Tarifa */
                    $tarifa = $this->_tarifaFactory->create();

                    $costoEnvio = $tarifa->cotizarEnvio(
                        [
                            'cpSucursal'=> $sucursal_data->getPostcode(),
                            'peso'          => $bultosData['kilos'],
                            'tipo'          => \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE
                        ]
                    );
                }
            }

            $this->_checkoutSession->setCodigoSucursalAndreani($sucursal_data->getId());
            $this->_checkoutSession->setNombreAndreaniSucursal($sucursal_data->getName());
            $this->_checkoutSession->setCotizacionAndreaniSucursal($costoEnvio);

            return array(array('shippingPrice' => $costoEnvio));
        }
        return array(array('shippingPrice' => -1));
    }

    public static function _fieldCompare($a, $b)
    {
        return strcmp($a->getName(), $b->getName());
    }

    private function getQuoteData(){
        $pesoTotal       = 0;
        $volumenTotal    = 0;
        $valorProductos  = 0;
        $quote           = $this->cart->getQuote();

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
        //$pesoTotal = $pesoTotal * 1000; parece que viaja en kgs

        return array(
            "valorDeclarado" => $valorProductos,//total de la compra
            "volumen" => $volumenTotal,//volumen
            "kilos" => $pesoTotal//peso
        );
    }

    private function getDireccion($direccion_sucursal){
        $result = [];
        foreach ($direccion_sucursal as $item) {
            $result[] = $item['contenido'];
        }
        return trim(implode(' ',$result));
    }
}