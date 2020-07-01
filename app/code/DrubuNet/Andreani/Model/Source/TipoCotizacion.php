<?php

namespace DrubuNet\Andreani\Model\Source;

/**
 * Class TipoCotizacion
 *
 * @description
 * @author
 * @package DrubuNet\Andreani\Model\Source
 */
class TipoCotizacion implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'tabla'     => 'Tarifario por tabla',
            'webservice'=> 'Cotización ONLINE'
        ];
    }
}
