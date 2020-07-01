<?php

namespace DrubuNet\Andreani\Model\Source;
use Magento\Framework\Option\ArrayInterface;
/**
 * Class Método
 *
 * @description arma el array del método de consulta de sucursales.
 * @author Drubu Team
 * @package DrubuNet\Andreani\Model\Source
 */
class Metodo implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'basico'    => 'Básico',
            'medio'     => 'Medio',
            'completo'  => 'Completo'
        ];
    }
}
