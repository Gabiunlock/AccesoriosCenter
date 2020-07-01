<?php
/**
 * Author: Drubu Team
 */
namespace DrubuNet\Andreani\Model;

ini_set('max_execution_time',300);

use SoapFault;
use DrubuNet\Andreani\Helper\Data as AndreaniHelper;
use DrubuNet\Andreani\Model\Soap\Webservice;
use Magento\Framework\Model\ResourceModel\Db\TransactionManager;
use Magento\Sales\Model\Convert\Order as ConvertOrder;
use Magento\Shipping\Model\ShipmentNotifier;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\Order\Shipment\Track;
use Magento\Catalog\Model\Product;
use Magento\Shipping\Model\Shipping\LabelGenerator;
use Magento\Shipping\Model\Shipping\LabelsFactory;
use Magento\Shipping\Model\CarrierFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order\Shipment\TrackFactory;
//use Magento\CatalogInventory\Api\StockRegistryInterface as StockRegistryInterface;

/**
 * Class GuiasMasivas
 * @package DrubuNet\Andreani\Model
 */
class GuiasMasivas
{
    /**
     * @var
     */
    protected $_webService;

    /**
     * @var AndreaniHelper
     */
    protected $_andreaniHelper;

    /**
     * @var AndreaniHelper
     */
    protected $_convertOrder;

    /**
     * @var ShipmentNotifier
     */
    protected $_shipmentNotifier;

    /**
     * @var Track
     */
    protected $_track;

    /**
     * @var ShipmentCollectionFactory
     */
    protected $shipment;

    /**
     * @var LabelGenerator
     */
    protected $_labelGenerator;

    /**
     * @var Product
     */
    protected $_product;

    /**
     * @var \Magento\Shipping\Model\Shipping\LabelsFactory
     */
    protected $_labelFactory;

    /**
     * @var CarrierFactory
     */
    protected $_carrierFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var TrackFactory
     */
    protected $_trackFactory;

    //protected $stockRegistry;

    /**
     * GuiasMasivas constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param AndreaniHelper $andreaniHelper
     * @param \DrubuNet\Andreani\Model\Soap\Webservice $webservice
     * @param ConvertOrder $convertOrder
     * @param ShipmentNotifier $shipmentNotifier
     * @param Shipment $shipment
     * @param Product $product
     * @param Track $track
     * @param LabelGenerator $labelGenerator
     * @param LabelsFactory $labelFactory
     * @param CarrierFactory $carrierFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param TrackFactory $trackFactory
     * @param array $data
     * @internal param ShipmentTrackCreationInterface $shipmentTrackCreationInterface
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        AndreaniHelper $andreaniHelper,
        Webservice $webservice,
        ConvertOrder $convertOrder,
        ShipmentNotifier $shipmentNotifier,
        Shipment $shipment,
        Product $product,
        Track $track,
        LabelGenerator $labelGenerator,
        LabelsFactory $labelFactory,
        CarrierFactory $carrierFactory,
        ScopeConfigInterface $scopeConfig,
        TrackFactory $trackFactory,
        array $data = []
        //StockRegistryInterface $stockRegistry

    )
    {
        $this->_andreaniHelper      = $andreaniHelper;
        $this->_webService          = $webservice;
        $this->_convertOrder        = $convertOrder;
        $this->_shipmentNotifier    = $shipmentNotifier;
        $this->shipment             = $shipment;
        $this->_track               = $track;
        $this->_product             = $product;
        $this->_labelGenerator      = $labelGenerator;
        $this->_labelFactory        = $labelFactory;
        $this->_carrierFactory      = $carrierFactory;
        $this->_scopeConfig         = $scopeConfig;
        $this->_trackFactory        = $trackFactory;
        //$this->stockRegistry        = $stockRegistry;
    }

    /**
     * @description Obtiene los datos de la orden y consulta el WS, y,  a partir de eso datos
     * inserta el Json serializado en la orden del envío. Posteriormente genera una petición
     * de generar un envío siempre y cuando la orden que llega por parámetros no haya sido enviada
     * previamente.
     * @param $order
     * @return \Magento\Framework\DataObject|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function doShipmentRequest($order)
    {
        try {
            $helper = $this->_andreaniHelper;
            $data = $this->_requestByOrder($order);
            return $data;
        }
        catch (\Magento\Framework\Exception\LocalizedException $e){
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage())
            );
        }
    }

    /**
     * @description Hace la petición al WS de Andreani para obtener el objeto con los datos
     * para armar la guía. Este método está activo cuando se ha seleccionado que se confeccione
     * una guía por orden.
     * @param $order
     * @return \Magento\Framework\DataObject|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _requestByOrder($order)
    {
        $result = new \Magento\Framework\DataObject();
        $helper = $this->_andreaniHelper;

        $volumen = 0;
        $productName = '';
        $packageWeight = 0;

        //Fix para la generacion de nro tracking cuando no hay stock de prods para despachar
        //$this->checkProductsInStock($order);

        foreach ($order->getAllItems() as $_item)
        {
            if($_item->getProductType() == 'configurable')
                continue;

            $webservice = $this->_webService;

            $_producto = $helper->getLoadProduct($_item['product_id']);

            $packageWeight += $_item->getWeight();
            $producto = $this->_product;
            $volumen += (int)$_producto->getResource()->getAttributeRawValue($_producto->getId(), 'volumen', $_producto->getStoreId()) * $_item['qty_ordered'];
            $productName .= $_item['name'] . ', ';
        }

        $productName    = rtrim(trim($productName), ",");
        $pesoTotal      = $packageWeight * 1000;

        $carrierParams = [];
        $carrierParams['provincia']                     = $order->getShippingAddress()->getRegion();
        $carrierParams['localidad']                     = $order->getShippingAddress()->getCity();
        $carrierParams['codigopostal']                  = $order->getShippingAddress()->getPostCode();
        $carrierParams['calle']                         = $order->getShippingAddress()->getStreetLine(1) . ' ' . $order->getShippingAddress()->getStreetLine(2);
        $carrierParams['numero']                        = $order->getShippingAddress()->getAltura() ? $order->getShippingAddress()->getAltura() : '';
        $carrierParams['piso']                          = $order->getShippingAddress()->getPiso() ? $order->getShippingAddress()->getPiso() : '';
        $carrierParams['departamento']                  = $order->getShippingAddress()->getDepartamento() ? $order->getShippingAddress()->getDepartamento() : '';
        $carrierParams['nombre']                        = $order->getShippingAddress()->getFirstname();
        $carrierParams['apellido']                      = $order->getShippingAddress()->getLastname();
        $carrierParams['nombrealternativo']             = '';
        $carrierParams['apellidoalternativo']           = '';
        $carrierParams['tipodedocumento']               = 'DNI';
        $carrierParams['numerodedocumento']             = $order->getShippingAddress()->getDni() ? $order->getShippingAddress()->getDni() : '';
        $carrierParams['email']                         = $order->getCustomerEmail();
        $carrierParams['telefonofijo']                  = $order->getShippingAddress()->getTelephone();
        $carrierParams['telefonocelular']               = $order->getShippingAddress()->getCelular() ? $order->getShippingAddress()->getCelular() : '';
        $carrierParams['categoriapeso']                 = 1;//TODO próximos versiones implementación de acuerdo a la lógica de  negocio
        $carrierParams['peso']                          = $pesoTotal;
        $carrierParams['detalledeproductosaentregar']   = $productName;
        $carrierParams['detalledeproductosaretirar']    = $productName;
        $carrierParams['volumen']                       = $volumen;
        $carrierParams['valordeclaradoconiva']          = $order->getTotalDue();
        $carrierParams['idcliente']                     = '';
        $carrierParams['sucursalderetiro']              = $order->getCodigoSucursalAndreani() ? $order->getCodigoSucursalAndreani() : '';
        $carrierParams['sucursaldelcliente']            = '';
        $carrierParams['increment_id']                  = $order->getIncrementId();
        $carrierCode                                    = explode('_', $order->getShippingMethod()); //andreanisucursal_sucursal

        $dataGuia = null;
        $shipments = $order->getShipmentsCollection();
        foreach ($shipments as $shipment){
            $dataGuiaAux = json_decode(unserialize($shipment->getData('andreani_datos_guia')));
            $dataGuia["datosguia"] = $dataGuiaAux->datosguia;
            $dataGuia["lastrequest"] = $dataGuiaAux->lastrequest;
        }
        if (!$dataGuia) {
            try {
                $dataGuia = $webservice->GenerarEnviosDeEntregaYRetiroConDatosDeImpresion($carrierParams, $carrierCode[0]);
                return $this->_doShipmentByOrder($order, $dataGuia);
            }catch (\Magento\Framework\Exception\LocalizedException $e){
                $helper->log('GuiasMasivas #' . $order->getIncrementId() ." - 'Hubo un error al generar el envío en el WS de Andreani","andreani_pasos_generacion_guia.log" );
                $result->setErrors('Hubo un error al generar el envío por parte de andreani - ' . $e->getMessage());
                return $result;
            }
        }
    }

    /**
     * @description Efectiviza la generación del envío siempre y cuando dicha orden
     * esté habilitada para ser enviada.
     * @param $order
     * @param $dataGuia
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _doShipmentByOrder($order,$dataGuia)
    {
        $response = [];
        if (!$order->canShip())
        {
            return false;
        }

        $helper       = $this->_andreaniHelper;
        $convertOrder = $this->_convertOrder;
        $shipment     = $convertOrder->toShipment($order);

        $valorTotal = $pesoTotal = 0;
        $itemsArray = [];

        foreach ($order->getAllItems() AS $orderItem)
        {
            if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                continue;
            }

            $qtyShipped = $orderItem->getQtyToShip();
            $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);

            $valorTotal += $qtyShipped * $orderItem->getPrice();
            $pesoTotal  += $qtyShipped * $orderItem->getWeight();

            $itemsArray[$orderItem->getId()] = [
                'qty' => $qtyShipped,
                'customs_value' => $orderItem->getPrice(),
                'price' => $orderItem->getPrice(),
                'name' => $orderItem->getName(),
                'weight'=> $orderItem->getWeight(),
                'product_id' => $orderItem->getProductId(),
                'order_item_id' => $orderItem->getId()
            ];

            $shipment->addItem($shipmentItem);
        }

        $shipment->register();
        $shipment->getOrder()->setIsInProcess(true);

        try {
            $shippingLabelContent               = $dataGuia['lastrequest'];
            $trackingNumber                     = $dataGuia['datosguia']->GenerarEnviosDeEntregaYRetiroConDatosDeImpresionResult->NumeroAndreani;
            $response['tracking_number']        = $trackingNumber;
            $response['shipping_label_content'] = $shippingLabelContent;
            $serialJson                         = serialize(json_encode($dataGuia));
            $trackingUrl                        = $helper->getTrackingUrl($trackingNumber);

            $mensajeEstado = "Seguimiento envío <a href='{$trackingUrl}' target='_blank'>{$trackingNumber}</a>";
            $history = $order->addStatusHistoryComment($mensajeEstado);
            $history->setIsVisibleOnFront(true);
            $history->setIsCustomerNotified(true);
            $history->save();

            $order = $shipment->getOrder();
            $carrier = $this->_carrierFactory->create($order->getShippingMethod(true)->getCarrierCode());

            $shipment->setPackages(
                [
                    1=> [
                        'items' => $itemsArray,
                        'params'=> [
                            'weight' => $pesoTotal,
                            'container'=> 1,
                            'customs_value'=> $valorTotal
                    ]
            ]]);
	   //aca esta el problema
           /* $shipment->setData('andreani_datos_guia', $serialJson);
            $shipment->save();
            $shipment->getOrder()->save();

            $response = $this->_labelFactory->create()->requestToShipment($shipment);*/

            /*if ($response->hasErrors()) {
                throw new \Magento\Framework\Exception\LocalizedException(__($response->getErrors()));
            }
            if (!$response->hasInfo()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Response info is not exist.'));
            }

            //$labelsContent = [];
            $trackingNumbers = [];
            $info = $response->getInfo();

            foreach ($info as $inf)
            {
                if (!empty($trackingNumber) && !empty($inf['label_content'])) {
                    //$labelsContent[] = $inf['label_content'];
                    $trackingNumbers[] = $trackingNumber;
                }
            }*/
            $trackingNumbers[] = $trackingNumber;
            $carrierCode = $carrier->getCarrierCode();
            $carrierTitle = $this->_scopeConfig->getValue(
                'carriers/' . $carrierCode . '/title',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $shipment->getStoreId()
            );
            if (!empty($trackingNumbers)) {
                $this->addTrackingNumbersToShipment($shipment, $trackingNumbers, $carrierCode, $carrierTitle);
            }


            $shipment->setData('andreani_datos_guia', $serialJson);
            $shipment->save();
            $shipment->getOrder()->save();
            $this->_shipmentNotifier->notify($shipment);
            return true;

        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage())
            );
        }
    }

    /**
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @param array $trackingNumbers
     * @param string $carrierCode
     * @param string $carrierTitle
     *
     * @return void
     */
    private function addTrackingNumbersToShipment(
        \Magento\Sales\Model\Order\Shipment $shipment,
        $trackingNumbers,
        $carrierCode,
        $carrierTitle
    ) {
        foreach ($trackingNumbers as $number)
        {
            if (is_array($number))
            {
                $this->addTrackingNumbersToShipment($shipment, $number, $carrierCode, $carrierTitle);
            }
            else
            {
                $shipment->addTrack(
                    $this->_trackFactory->create()
                        ->setNumber($number)
                        ->setCarrierCode($carrierCode)
                        ->setTitle($carrierTitle)
                );
            }
        }
    }


}
