<?php

namespace DrubuNet\Andreani\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Tarifa
 *
 * @description Modelo representativo de la tabla drubunet_andreani_tarifa.
 * @author Drubu Team
 * @package DrubuNet\Andreani\Model
 */
class Tarifa extends AbstractModel
{
    protected $_eventPrefix = 'drubunet_andreani_tarifa';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'andreani_tarifa';

    /**
     * True if data changed
     *
     * @var bool
     */
    protected $_isStatusChanged = false;

    /**
     * Tarifa constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Inicia el resource model
     */
    protected function _construct()
    {
        $this->_init('DrubuNet\Andreani\Model\ResourceModel\Tarifa');
    }


    /**
     * @description Cotizacion de un envio mediante el tarifario configurable desde el admin
     *
     * @param array $params
     * @return float | bool
     */
    public function cotizarEnvio(array $params)
    {
        settype($params['peso'],'float');

        $rango1 = 0;
        $rango2 = 1000;
        $rango3 = 1400;
        $rango4 = 1920;
        $rango5 = 2440;
        $rango6 = 3004;
        $rango7 = 5290;

        $tarifa = $this->getCollection();

        if($params['tipo'] == \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE)
        {
            $tarifa->getSelect()
                ->reset(\Magento\Framework\DB\Select::COLUMNS)
                /*->join(
                    ['c' =>'drubunet_andreani_codigo_postal'],
                    "c.zona_id = main_table.zona_id",
                    []);*/
                ->join(
                    ['c' =>'drubunet_andreani_codigo_postal'],
                    "c.zona_id = main_table.zona_id AND c.codigo_postal = {$params['cpSucursal']}",
                    []);

            /*$tarifa->join(
                ['s' =>'drubunet_andreani_sucursal'],
                "s.codigo_postal = c.codigo_postal AND s.codigo_sucursal = {$params['codigoSucursal']}",
                []
            );*/
        }
        else
        {
            $tarifa->getSelect()
                ->reset(\Magento\Framework\DB\Select::COLUMNS)
                ->join(
                    ['c' =>'drubunet_andreani_codigo_postal'],
                    "c.zona_id = main_table.zona_id AND c.codigo_postal = {$params['cpDestino']}",
                    []);
        }

        $peso = $params['peso'];

        if($peso >= $rango1 && $peso < $rango2)
        {
            $tarifa->addFieldToFilter('rango',['eq'=>'1000']);
        }

        if($peso >= $rango2 && $peso < $rango3)
        {
            $tarifa->addFieldToFilter('rango',['gteq' =>'1000']);
            $tarifa->addFieldToFilter('rango',['lt' =>'1400']);
        }

        if($peso >= $rango3 && $peso < $rango4)
        {
            $tarifa->addFieldToFilter('rango',['gteq' =>'1400']);
            $tarifa->addFieldToFilter('rango',['lt' =>'1920']);
        }

        if($peso >= $rango4 && $peso < $rango5)
        {
            $tarifa->addFieldToFilter('rango',['gteq' =>'1920']);
            $tarifa->addFieldToFilter('rango',['lt' =>'2440']);
        }

        if($peso >= $rango5 && $peso < $rango6)
        {
            $tarifa->addFieldToFilter('rango',['gteq' =>'2440']);
            $tarifa->addFieldToFilter('rango',['lt' =>'3004']);
        }

        if($peso >= $rango6 && $peso < $rango7)
        {
            $tarifa->addFieldToFilter('rango',['gteq' =>'3004']);
            $tarifa->addFieldToFilter('rango',['lt' =>'5290']);
        }

        /**
         * @TODO Respeta la misma logica que paruolo 1 cuando se supera el peso.
         */
        if($peso >= $rango7)
        {
            $tarifa->addFieldToFilter('rango',['gteq' =>'5290']);
        }

        $tarifa->getSelect()->group('c.codigo_postal');

        switch($params['tipo'])
        {
            case \DrubuNet\Andreani\Model\Carrier\AndreaniEstandar::CARRIER_CODE:
                $tarifa->addFieldToSelect(['valor_estandar']);
                $valorCotizacion = $tarifa->getFirstItem()->getValorEstandar();
                break;

            case \DrubuNet\Andreani\Model\Carrier\AndreaniSucursal::CARRIER_CODE:
                $tarifa->addFieldToSelect(['valor_sucursal']);
                $valorCotizacion = $tarifa->getFirstItem()->getValorSucursal();
                break;

            case \DrubuNet\Andreani\Model\Carrier\AndreaniUrgente::CARRIER_CODE:
                $tarifa->addFieldToSelect(['valor_urgente']);
                $valorCotizacion = $tarifa->getFirstItem()->getValorUrgente();
                break;

            default:
                $valorCotizacion = false;
                break;
        }

        return $valorCotizacion;
    }
}