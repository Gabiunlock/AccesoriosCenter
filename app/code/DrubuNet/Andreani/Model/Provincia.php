<?php


namespace DrubuNet\Andreani\Model;

use Magento\Framework\DataObject;
use DrubuNet\Andreani\Api\Data\ProvinciaInterface;

class Provincia extends DataObject implements ProvinciaInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->_getData('name');
    }

}