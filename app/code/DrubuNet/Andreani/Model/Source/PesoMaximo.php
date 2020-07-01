<?php

namespace DrubuNet\Andreani\Model\Source;

/**
 * Class PesoMaximo
 *
 * @description
 * @author
 * @package DrubuNet\Andreani\Model\Source
 */
class PesoMaximo implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '30000'  => '30 kg',
            '50000'  => '50 kg',
            '100000' => '100 kg',
            '1000000' => '1000 kg'
        ];
    }
}
