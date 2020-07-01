<?php
/**
 * Author: Jhonattan Campo <jcampo@DrubuNet.net.ar>
 *
 */
namespace DrubuNet\Andreani\Model\Source;

/**
 * Class AlmacenamientoGuias
 * @package DrubuNet\Andreani\Model\Source
 */
class AlmacenamientoGuias implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '30'  => '30 Días',
            '60'  => '60 Días',
            '90'  => '90 Días',
            '0'   => 'Nunca'
        ];
    }
}
