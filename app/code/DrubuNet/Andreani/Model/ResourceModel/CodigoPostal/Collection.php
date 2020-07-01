<?php

namespace DrubuNet\Andreani\Model\ResourceModel\CodigoPostal;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @author Drubu Team
 * @package DrubuNet\Andreani\Model\ResourceModel\CodigoPostal
 */
class Collection extends AbstractCollection
{
    /**
     * Customer Eav data
     *
     * @var   \Magento\Eav\Helper\Data
     */
    protected $_customerHelperData;

    /**
     * @var string Primary Key de la tabla
     */
    protected $_idFieldName = 'codigo_postal_id';

    /**
     * Collection constructor.
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Eav\Helper\Data $customerHelperData
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Helper\Data $customerHelperData,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_customerHelperData = $customerHelperData;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_init('DrubuNet\Andreani\Model\CodigoPostal','DrubuNet\Andreani\Model\ResourceModel\CodigoPostal');
    }
}