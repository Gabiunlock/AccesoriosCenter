<?php
/**
 * Created by PhpStorm.
 * User: Pablo Garcia
 * Email: pablolgarcia@gmail.com
 * Date: 29/08/18
 * Time: 22:07
 */

namespace DrubuNet\Andreani\Plugin\Adminhtml\Order;

use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as SalesOrderGridCollection;

class CustomFiltersSalesOrderGridCollection
{
    private $messageManager;
    private $collection;

    public function __construct(
        MessageManager $messageManager,
        SalesOrderGridCollection $collection
    ) {
        $this->messageManager = $messageManager;
        $this->collection = $collection;
    }

    public function aroundGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        \Closure $proceed,
        $requestName
    ) {
        $result = $proceed($requestName);

        if ($requestName == 'andreani_sales_order_grid_data_source') {
            if ($result instanceof $this->collection) {
                $collection = $this->collection;
                $select = $collection->getSelect();

                $select->join(
                    ["so" => $collection->getTable("sales_order")],
                    'main_table.entity_id = so.entity_id AND so.status != "complete" AND so.shipping_method IN ("andreaniestandar_estandar","andreaniurgente_urgente","andreanisucursal_sucursal", "freeshipping_freeshipping")',
                    array('shipping_method')
                );

                $select->joinLeft(
                    ["ss" => $collection->getTable("sales_shipment")],
                    'main_table.entity_id = ss.order_id',
                    array('shipping_label')
                );

                $select->where('ss.order_id IS NULL');

                $collection->addFilterToMap('entity_id','main_table.entity_id');

                return $this->collection;
            }
        }

        return $result;
    }
}