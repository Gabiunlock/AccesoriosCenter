<?php

namespace DrubuNet\Andreani\Model\ResourceModel;

/**
 * Class Tarifa
 *
 * @description ResourceModel para la tabla Tarifa
 * @author Drubu Team
 * @package DrubuNet\Andreani\Model\ResourceModel
 */
class Tarifa extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }

    public function _construct()
    {
        $this->_init('drubunet_andreani_tarifa','tarifa_id');
    }

}