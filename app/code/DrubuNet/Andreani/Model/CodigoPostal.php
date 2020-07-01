<?php

namespace DrubuNet\Andreani\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class CodigoPostal
 *
 * @description Modelo representativo de la tabla drubunet_andreani_codigo_postal.
 * @author Drubu Team
 * @package DrubuNet\Andreani\Model
 */
class CodigoPostal extends AbstractModel
{
    protected $_eventPrefix = 'drubunet_andreani_codigo_postal';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'andreani_codigo_postal';

    /**
     * True if data changed
     *
     * @var bool
     */
    protected $_isStatusChanged = false;


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
        $this->_init('DrubuNet\Andreani\Model\ResourceModel\CodigoPostal');
    }

}