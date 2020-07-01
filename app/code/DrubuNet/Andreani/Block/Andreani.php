<?php

namespace DrubuNet\Andreani\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class Andreani
 *
 * @description Bloque para renderizar los planes de pago en el checkout
 *
 * @author Mauro Maximiliano Martinez <mmartinez@ids.net.ar>
 * @package DrubuNet\Andreani\Block
 */
class Andreani extends Template
{
    /**
     * @var \DrubuNet\Andreani\Model\ProvinciaFactory
     */
    protected $_provinciaFactory;

    /**
     * Andreani constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \DrubuNet\Andreani\Model\ProvinciaFactory $provinciaFactory
     */
    public function __construct(
        Template\Context $context,
        array $data = [],
        \DrubuNet\Andreani\Model\ProvinciaFactory $provinciaFactory
    )
    {
        $this->_provinciaFactory    = $provinciaFactory;
        $this->_context             = $context;

        parent::__construct($context, $data);
    }

    /**
     * @description Retorna el listado de provincias
     *
     * @return array
     */
    public function getProvincias()
    {
        $provinciasDisponibles = $this->_provinciaFactory
            ->create()
            ->getCollection();
        $provinciasDisponibles->getSelect()->order('nombre ASC');

        return $provinciasDisponibles->getData();
    }

}