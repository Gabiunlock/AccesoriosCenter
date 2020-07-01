<?php

namespace DrubuNet\Andreani\Model\Source;

/**
 * Class Modo
 *
 * @description Opciones customizadas para seleccionar el modo habilitado del metodo de pago.
 * @author
 * @package DrubuNet\Andreani\Model\Source
 */
class Modo
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            \DrubuNet\Andreani\Model\Webservice::MODE_DEV  =>'Desarrollo',
            \DrubuNet\Andreani\Model\Webservice::MODE_PROD =>'Producción'
        ];
    }
}
