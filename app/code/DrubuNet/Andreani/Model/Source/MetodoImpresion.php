<?php

namespace DrubuNet\Andreani\Model\Source;

/**
 * Class MetodoImpresion
 *
 * @description
 * @author
 * @package DrubuNet\Andreani\Model\Source
 */
class MetodoImpresion implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'constancia' => 'Constancia de Entrega',
            'etiqueta'=> 'Etiqueta'
        ];
    }
}
