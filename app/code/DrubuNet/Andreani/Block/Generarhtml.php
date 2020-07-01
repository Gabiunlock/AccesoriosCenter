<?php
/**
 * Created by PhpStorm.
 * User: ids
 * Date: 17/08/16
 * Time: 14:43
 */
namespace DrubuNet\Andreani\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use DrubuNet\Andreani\Helper\Data as AndreaniHelper;

class Generarhtml extends Template
{
    /**
     * @var Order
     */
    protected $_order;
    
    /**
     * @var AndreaniHelper
     */
    protected $_andreaniHelper;

    /**
     * Generarhtml constructor.
     * @param Context $context
     * @param AndreaniHelper $andreaniHelper
     */
    public function __construct
    (
        Context $context,
        Order $order,
        
        AndreaniHelper $andreaniHelper
    )
    {
        $this->_order           = $order;
        $this->_andreaniHelper  = $andreaniHelper;
        parent::__construct($context);
    }

    /**
     * @description apartir del id de la orden devuelve los datos de la guía.
     * @param $orderId
     * @return string
     */
    public function getAndreaniDataGuia($orderId)
    {
        $order      = $this->_andreaniHelper->getLoadShipmentOrder($orderId) ;

        //Recorre la colección de envíos, y verifica si hay datos en el campo asignado
        //para guardar los datos que generarán la guía en PDF.
        $andreaniDatosGuia  = '';
        $guiasArray         = [];
        $shipmentCollection = $order->getShipmentsCollection();
        foreach($shipmentCollection AS $shipments)
        {
            if($shipments->getAndreaniDatosGuia() !='')
            {
                $andreaniDatosGuia                          = $shipments->getAndreaniDatosGuia();
                $guiasArray[$shipments['increment_id']]     = $andreaniDatosGuia;
            }
        }

        $andreaniDatosGuia  = json_decode(unserialize($andreaniDatosGuia));
        return $guiasArray;
    }

    /**
     * @description retorna el path de la ubicación del código de barras para generar la guía.
     * @param $numeroAndreani
     * @return string
     */
    public function getCodigoBarras($numeroAndreani)
    {
       return $this->_andreaniHelper->getCodigoBarras($numeroAndreani);
    }

    /**
     * @description devuelve el logo que el cliente sube por admin
     * @return string
     */
    public function getLogoEmpresaPath()
    {
        return $this->_andreaniHelper->getlogoEmpresaPath();
    }
    
    public function getClientCredentials($categoria)
    {
        $clientCredentials  = [];
        $categoria          = strtolower($categoria);
        
        switch($categoria)
        {
            case 'estandar': $clientCredentials['contrato'] = $this->_andreaniHelper->getEstandarContrato();
                break;
            case 'urgente' : $clientCredentials['contrato'] = $this->_andreaniHelper->getUrgenteContrato();
                break;
            default        : $clientCredentials['contrato'] = $this->_andreaniHelper->getSucursalContrato();
                break;
        }
        
        $clientCredentials['cliente'] = $this->_andreaniHelper->getNroCliente();
        
        return $clientCredentials;
    }

    /**
     * Devuelve el método de pago de la orden
     * @param $incrementId
     * @return mixed|null
     */
    public function getShippingMethod($incrementId)
    {
        return  $this->_order->loadByIncrementId($incrementId)->getShippingMethod();
        //return $this->_andreaniHelper->loadByIncrementId($incrementId)->getShippingMethod();
    }
}