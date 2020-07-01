<?php

namespace DrubuNet\Andreani\Model\Source;
use Magento\Framework\Option\ArrayInterface;
/**
 * Class WebserviceMethod
 *
 * @description arma el array con el tipo de webservice a consumir.
 * @author Drubu Team
 * @package DrubuNet\Andreani\Model\Source
 */
class WebserviceMethod implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'soap'    => 'Soap',
            //'rest'     => 'Rest',
        ];
    }
}
