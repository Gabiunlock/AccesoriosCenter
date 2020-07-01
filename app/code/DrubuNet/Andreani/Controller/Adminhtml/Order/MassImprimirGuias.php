<?php
/**
 * Created by PhpStorm.
 * User: Pablo Garcia
 * Email: pablolgarcia@gmail.com
 * Date: 25/10/18
 * Time: 17:08
 */

namespace DrubuNet\Andreani\Controller\Adminhtml\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use DrubuNet\Andreani\Helper\Data as AndreaniHelper;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;

class MassImprimirGuias extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    /**
     * @var AndreaniHelper
     */
    protected $_andreaniHelper;

    /**
     * @var ShipmentCollectionFactory
     */
    protected $shipmentCollectionFactory;

    /**
     * MassImprimirGuias constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param AndreaniHelper $andreaniHelper
     * @param ShipmentCollectionFactory $shipmentCollectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        AndreaniHelper $andreaniHelper,
        ShipmentCollectionFactory $shipmentCollectionFactory
    )
    {
        parent::__construct($context, $filter);
        $this->collectionFactory = $collectionFactory;
        $this->_andreaniHelper = $andreaniHelper;
        $this->shipmentCollectionFactory = $shipmentCollectionFactory;
    }

    /**
     * @param AbstractCollection $collection
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function massAction(AbstractCollection $collection)
    {
        $ordersShipment = $this->shipmentCollectionFactory->create()
            ->setOrderFilter(['in' => $collection->getAllIds()]);

        if ($ordersShipment->getSize())
        {
            foreach ($ordersShipment as $order)
            {
                $guiaContent = $order->getAndreaniDatosGuia();
                if ($guiaContent) {
                    $guiasContent[$order->getIncrementId()] = json_decode(unserialize($guiaContent));
                }
            }

            try
            {
                /**
                 * si el contenido de las guías no está vacío
                 */
                if (!empty($guiasContent))
                {
                    $this->_generarGuias($guiasContent,$collection->getAllIds());
                } else {
                    $this->messageManager->addErrorMessage(__('No hay guías creadas para las órdenes seleccionadas.'));
                    return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
                }
            }
            catch (\Exception $e)
            {
                $this->messageManager->addErrorMessage(__('No hay guías creadas para las órdenes seleccionadas.'));
                return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
            }
        } else {
            $this->messageManager->addErrorMessage(__('No hay guías creadas para las órdenes seleccionadas.'));
            return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
        }
    }

    /**
     * Genera las guías en pdf para ser descargadas.
     * @param $guiasContent
     * @param $ordersIds
     */
    protected function _generarGuias($guiasContent,$ordersIds)
    {
        try
        {
            $helper             = $this->_andreaniHelper;
            $order_id = '';

            /**
             * Concatena los ID para mandarlos por comas en la URL.
             */
            foreach($ordersIds AS $key => $orderId)
            {
                $order_id.=$orderId.',';
            }

            $order_id = rtrim($order_id, ',');

            /**
             * Accede al objeto para crear el código de barras.
             */
            foreach($guiasContent AS $key => $guiaData)
            {
                $object = $guiaData->datosguia->GenerarEnviosDeEntregaYRetiroConDatosDeImpresionResult;
                $helper->crearCodigoDeBarras($object->NumeroAndreani);
            }

            $pdfName        = 'guia_masiva_'.date_timestamp_get(date_create());

            /**
             * Crea el bloque dinámicamente y le pasa los parámetros por array para
             * que renderice la guía en html.
             */
            $block = $this->_view
                ->getLayout()
                ->createBlock('DrubuNet\Andreani\Block\Generarhtmlmasivo',
                    "guiamasiva",
                    ['data' => [
                        'order_id' => $order_id
                    ]
                    ])
                ->setData('area', 'frontend')
                ->setTemplate($this->_andreaniHelper->getGuiaMasivaTemplate());


            $html = $block->toHtml();

            /**
             * Espera recibir "true" después de mandarle al método del helper
             * que se encarga de generar la guía en HTML. El tercer parámetro
             * fuerza la descarga (D) o fuerza el almacenamiento en el filesystem (F)
             */
            if($helper->generateHtml2Pdf($pdfName,$html,'D'))
            {
                foreach($guiasContent AS $key => $guiaData)
                {
                    $object  = $guiaData->datosguia->GenerarEnviosDeEntregaYRetiroConDatosDeImpresionResult;
                    unlink($helper->getDirectoryPath('media')."/andreani/".$object->NumeroAndreani.'.png');
                }
            }

        } catch (\Exception $e) {
            $this->messageManager->addError(__('Hubo un problema imprimiendo las guías. Inténtelo de nuevo.'));
        }
    }

    /**
     * Chequea permisos.
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrubuNet_Andreani::guias_admin');
    }
}
