<?php
namespace DrubuNet\Andreani\Plugin\Widget;

use Magento\Backend\Block\Widget\Context AS Subject;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\Url;

/**
 * Class Context
 * @description Plugin que agrega el botón que genera la guía Andreani en PDF.
 * @author Drubu team
 * @package DrubuNet\Andreani\Plugin\Widget
 */
class Context
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManagerInterface;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var Url
     */
    protected $_frontendUrl;

    /**
     * Context constructor.
     * @param StoreManagerInterface $storeManagerInterface
     * @param Order $order
     * @param Url $frontendUrl
     */
    public function __construct(
        StoreManagerInterface $storeManagerInterface,
        Order $order,
        Url $frontendUrl
    )
    {
        $this->_storeManagerInterface  = $storeManagerInterface;
        $this->_order                  = $order;
        $this->_frontendUrl            = $frontendUrl;
    }

    /**
     * @param Subject $subject
     * @param $buttonList
     * @return mixed
     */
    public function afterGetButtonList(
        Subject $subject,
        $buttonList
    )
    {
        //Con el Id de la orden se carga el objeto para obtener el envío.
        $orderId    = $subject->getRequest()->getParam('order_id');
        $order      = $this->_order->load($orderId) ;

        //Recorre la colección de envíos, y verifica si hay datos en el campo asignado
        //para guardar los datos que generarán la guía en PDF.
        $andreaniDatosGuia  = false;

        if($order->getShipmentsCollection())
        {
            $shipmentCollection = $order->getShipmentsCollection();
            foreach($shipmentCollection AS $shipments)
            {
                if($shipments->getAndreaniDatosGuia() !='')
                {
                    $andreaniDatosGuia = true;
                }
            }
        }

        $baseUrl = $this->_frontendUrl->getUrl('andreani/generarguia/index',['order_id' =>$orderId,'rk'=>uniqid()]);

        if($subject->getRequest()->getFullActionName() == 'sales_order_view' && $andreaniDatosGuia){
            $buttonList->add(
                'custom_button',
                [
                    'label'     => __('Imprimir guía Andreani'),
                    'onclick'   => "location.href='{$baseUrl}'",
                    'class'     => 'ship'
                ]
            );
        }

        return $buttonList;
    }
}