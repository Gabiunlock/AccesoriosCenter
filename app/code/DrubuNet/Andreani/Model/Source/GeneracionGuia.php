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
class GeneracionGuia implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '1'  => 'Una guía por pedido.',
            /**
             * @TODO Revisar e implementar correctamente le generacion de guias por items. Esta hecho pero no funciona
             * correctamente.
             **/
            //'2'  => 'Una guía por cada ítem del pedido.',
        ];
    }
}
