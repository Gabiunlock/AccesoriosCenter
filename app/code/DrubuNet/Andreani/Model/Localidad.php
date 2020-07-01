<?php


namespace DrubuNet\Andreani\Model;

use DrubuNet\Andreani\Api\Data\LocalidadInterface;
use Magento\Framework\DataObject;

class Localidad extends DataObject implements LocalidadInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->_getData('name');
    }

}