<?php

namespace DrubuNet\Andreani\Model\ResourceModel;

/**
 * Class Provincia
 *
 * @description ResourceModel para la tabla Provincia
 * @author Drubu Team
 * @package DrubuNet\Andreani\Model\ResourceModel
 */
class Provincia extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('drubunet_andreani_provincia','provincia_id');
    }

}