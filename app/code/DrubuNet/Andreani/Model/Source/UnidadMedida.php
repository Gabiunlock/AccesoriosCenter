<?php

namespace DrubuNet\Andreani\Model\Source;

/**
 * Class UnidadMedida
 *
 * @description
 * @author
 * @package DrubuNet\Andreani\Model\Source
 */
class UnidadMedida implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'kilos' => 'kg / m3',
            'gramos'=> 'gramos / cm3'
        ];
    }
}
