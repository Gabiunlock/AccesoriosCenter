define([
    'jquery',
    'mage/utils/wrapper'
], function (
    jQuery,
    wrapper
) {
    'use strict';

    return function (processor) {
        return wrapper.wrap(processor, function (proceed, payload) {
            payload = proceed(payload);

            var shippingAddress =  payload.addressInformation.shipping_address;
            var dni = jQuery('[name="dni"]').val();
            var altura = jQuery('[name="altura"]').val();
            var piso = jQuery('[name="piso"]').val();
            var departamento = jQuery('[name="departamento"]').val();
            var observaciones = jQuery('[name="observaciones"]').val();
            var celular = jQuery('[name="celular"]').val();

            if(dni == "" || dni == null){
                if(shippingAddress.customAttributes == "undefined" || shippingAddress.customAttributes == null){
                    dni = null;
                } else {
                    if(shippingAddress.customAttributes.dni == "undefined" || shippingAddress.customAttributes.dni == null) {
                        dni = null;
                    } else {
                        dni = shippingAddress.customAttributes.dni.value;
                    }
                }
            }

            if(altura == "" || altura == null){
                if(shippingAddress.customAttributes == "undefined" || shippingAddress.customAttributes == null){
                    altura = null;
                } else {
                    if(shippingAddress.customAttributes.altura == "undefined" || shippingAddress.customAttributes.altura == null) {
                        altura = null;
                    } else {
                        altura = shippingAddress.customAttributes.altura.value;
                    }
                }
            }

            if(piso == "" || piso == null){
                if(shippingAddress.customAttributes == "undefined" || shippingAddress.customAttributes == null){
                    piso = null;
                } else {
                    if(shippingAddress.customAttributes.piso == "undefined" || shippingAddress.customAttributes.piso == null) {
                        piso = null;
                    } else {
                        piso = shippingAddress.customAttributes.piso.value;
                    }
                }
            }

            if(departamento == "" || departamento == null){
                if(shippingAddress.customAttributes == "undefined" || shippingAddress.customAttributes == null){
                    departamento = null;
                } else {
                    if(shippingAddress.customAttributes.departamento == "undefined" || shippingAddress.customAttributes.departamento == null) {
                        departamento = null;
                    } else {
                        departamento = shippingAddress.customAttributes.departamento.value;
                    }
                }
            }

            if(observaciones == "" || observaciones == null){
                if(shippingAddress.customAttributes == "undefined" || shippingAddress.customAttributes == null){
                    observaciones = null;
                } else {
                    if(shippingAddress.customAttributes.observaciones == "undefined" || shippingAddress.customAttributes.observaciones == null) {
                        observaciones = null;
                    } else {
                        observaciones = shippingAddress.customAttributes.observaciones.value;
                    }
                }
            }

            if(celular == "" || celular == null){
                if(shippingAddress.customAttributes == "undefined" || shippingAddress.customAttributes == null){
                    celular = null;
                } else {
                    if(shippingAddress.customAttributes.celular == "undefined" || shippingAddress.customAttributes.celular == null) {
                        celular = null;
                    } else {
                        celular = shippingAddress.customAttributes.celular.value;
                    }
                }
            }

            var goneExtentionAttributes = {
                'altura': altura,
                'piso': piso,
                'departamento': departamento,
                'observaciones': observaciones,
                'dni': dni,
                'celular': celular
            };
            payload.addressInformation.extension_attributes = _.extend(
                payload.addressInformation.extension_attributes,
                goneExtentionAttributes
            );

            return payload;
        });
    };
});