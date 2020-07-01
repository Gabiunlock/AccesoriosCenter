<?php

namespace DrubuNet\Andreani\Model;

/**
 * Class Webservice
 *
 * @description Clase que interactua con el WS de Andreani.
 * @author Drubu Team
 * @package DrubuNet\Andreani\Model
 */
class Webservice
{
    const MODE_DEV       = 'dev';
    const MODE_PROD      = 'prod';

    protected $helper;
    protected $httpClientFactory;
    private $url = "https://api.andreani.com";
    private $token;

    public function __construct(
        \DrubuNet\Andreani\Helper\Data $helper,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
    )
    {
        $this->helper       = $helper;
        $this->httpClientFactory = $httpClientFactory;
    }

    /**
     * Funciona
     * @description Obtengo el token necesario para comunicarme con los servicios de Andreani
     * @return string
     */
    public function login(){
        $username = ($this->helper->getUsuario());
        $password = ($this->helper->getPass());
        $encoded['code'] = "Authorization";
        $encoded['value'] = 'Basic ' . base64_encode($username . ':' . $password);
        $url = $this->url . '/login';
        $this->token = $this->doRequest($url,\Zend_Http_Client::GET, $encoded)->getHeader("x-authorization-token");
        return $this->token;
    }

    /**
     * Impongo la orden en el servicio
     */
    public function generarEnvio($params){
        $url = $this->url . '/v1/ordenesDeEnvio';
        if(empty($this->token)){
            $this->login();
        }
        $encoded['code'] = "x-authorization-token";
        $encoded['value'] = $this->token;
        return $this->doPost($url,\Zend_Http_Client::POST, $encoded,json_encode($params))->getBody();//json_decode(,true);
    }

    /**
     * @param $nroOrden
     * @return string
     * @description Obtengo el detalle de un envio por número Andreani.
     */
    public function getOrderCreated($nroOrden){
        $url = $this->url . '/v1/ordenesDeEnvio/' . $nroOrden;
        if(empty($this->token)){
            $this->login();
        }
        $encoded['code'] = "x-authorization-token";
        $encoded['value'] = $this->token;
        return $this->doRequest($url,\Zend_Http_Client::GET, $encoded)->getBody();
    }

    /**
     * @param $nroOrden
     * @return string
     * @description Devuelve todos los movimientos de un envío por número Andreani.
     */
    public function getTrazasDeEnvio($nroOrden){
        $url = $this->url . '/v1/envios/' . $nroOrden . '/trazas';
        if(empty($this->token)){
            $this->login();
        }
        $encoded['code'] = "x-authorization-token";
        $encoded['value'] = $this->token;
        return $this->doRequest($url,\Zend_Http_Client::GET, $encoded)->getBody();
    }

    /**
     * @param $nroOrden
     * @return string
     * @description Devuelve la información de un envío por Número Andreani.
     */
    public function getEnvio($nroOrden){
        $url = $this->url . '/v1/envios/' . $nroOrden;
        if(empty($this->token)){
            $this->login();
        }
        $encoded['code'] = "x-authorization-token";
        $encoded['value'] = $this->token;
        return $this->doRequest($url,\Zend_Http_Client::GET, $encoded)->getBody();
    }

    /**
     * Funciona
     * @param $params
     * @return string
     * @description Devuelve la tarifa de un envio a partir de parametros.
     */
    public function cotizarEnvio($params){
        $url = $this->url . '/v1/tarifas';
        $encoded = null;
        return json_decode($this->doRequest($url,\Zend_Http_Client::GET, $encoded,($params))->getBody(),true);
    }

    /**
     * Funciona
     * @description Listado de las provinicas reconocidas según ISO-3166-2:AR
     * @return array
     */
    public function getRegions(){
        $url = $this->url . '/v1/regiones';
        if(empty($this->token)){
            $this->login();
        }
        /*$encoded['code'] = "x-authorization-token";
        $encoded['value'] = $this->token;*/
        $encoded = null;
        return json_decode($this->doRequest($url,\Zend_Http_Client::GET, $encoded)->getBody(),true);
    }

    /**
     * Funciona
     * @description Lista todas (o una) las sucursales de Andreani que son pausibles de admitir o retirar envíos
     * @return array
     */
    public function getSucursales(){
        $url = $this->url . '/v1/sucursales';
        if(empty($this->token)){
            $this->login();
        }
        $encoded['code'] = "x-authorization-token";
        $encoded['value'] = $this->token;
        return json_decode($this->doRequest($url,\Zend_Http_Client::GET, $encoded)->getBody(),true);
    }

    private function doRequest($url, $method, $header = null, $params = null){
        $client = $this->httpClientFactory->create();
        $client->setUri($url);
        $client->setMethod($method);
        $client->setHeaders(\Zend_Http_Client::CONTENT_TYPE, 'application/json');
        $client->setHeaders('Accept','application/json');
        if(!is_null($header)) {
            $client->setHeaders($header['code'], $header['value']);
        }
        if(!is_null($params)) {
            if($method === \Zend_Http_Client::GET) {
                $client->setParameterGet($params); //json
            }
            else if ($method === \Zend_Http_Client::POST){
                $client->setParameterPost($params);
            }
        }

        return $client->request();

        //return $response->getBody();
    }
    private function doPost($url, $method, $header = null, $params = null){
        $client = $this->httpClientFactory->create();
        $client->setUri($url);
        $client->setMethod($method);
        $client->setHeaders(\Zend_Http_Client::CONTENT_TYPE, 'application/json');
        $client->setHeaders('Accept','application/json');
        $client->setHeaders("x-authorization-token",$this->token);
        $client->setParameterPost($params); //json

        return $client->request();

        //return $response->getBody();
    }
}
