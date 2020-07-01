define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'DrubuNet_Andreani/js/view/shipping-address/address-renderer/default'
], function ($, wrapper, quote,addressData) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();

            const setValueToAttr = function (attribute) {
                if(shippingAddress['customAttributes'] !== undefined && shippingAddress['customAttributes'][attribute] !== undefined && shippingAddress['customAttributes'][attribute]['value'] !== undefined){
                    shippingAddress['extension_attributes'][attribute] = shippingAddress['customAttributes'][attribute]['value'];
                }
                else{
                    shippingAddress['extension_attributes'][attribute] = jQuery('[name=' + attribute + ']').val();
                    shippingAddress['customAttributes'][attribute] = jQuery('[name=' + attribute + ']').val();
                    shippingAddress['custom_attributes'][attribute] = jQuery('[name=' + attribute + ']').val();
                }
            };

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }
            if (shippingAddress['customAttributes'] === undefined) {
                shippingAddress['customAttributes'] = {};
            }
            if (shippingAddress['custom_attributes'] === undefined) {
                shippingAddress['custom_attributes'] = {};
            }
            /*console.log(addressData().getAltura());
            if(shippingAddress['customAttributes'] !== undefined){
                shippingAddress['extension_attributes']['dni'] = shippingAddress['customAttributes']['dni']['value'];
                shippingAddress['extension_attributes']['altura'] = shippingAddress['customAttributes']['altura']['value'];
                shippingAddress['extension_attributes']['piso'] = shippingAddress['customAttributes']['piso']['value'];
                shippingAddress['extension_attributes']['departamento'] = shippingAddress['customAttributes']['departamento']['value'];
                shippingAddress['extension_attributes']['celular'] = shippingAddress['customAttributes']['celular']['value'];
                shippingAddress['extension_attributes']['observaciones'] = shippingAddress['customAttributes']['observaciones']['value'];
            }
            else {
                shippingAddress['extension_attributes']['dni'] = jQuery('[name="dni"]').val();
                shippingAddress['extension_attributes']['altura'] = jQuery('[name="altura"]').val();
                shippingAddress['extension_attributes']['piso'] = jQuery('[name="piso"]').val();
                shippingAddress['extension_attributes']['departamento'] = jQuery('[name="departamento"]').val();
                shippingAddress['extension_attributes']['celular'] = jQuery('[name="celular"]').val();
                shippingAddress['extension_attributes']['observaciones'] = jQuery('[name="observaciones"]').val();
            }*/

            setValueToAttr('dni');
            setValueToAttr('altura');
            setValueToAttr('piso');
            setValueToAttr('departamento');
            setValueToAttr('celular');
            setValueToAttr('observaciones');

            /*shippingAddress['extension_attributes']['dni'] = '4444444'
            shippingAddress['extension_attributes']['altura'] = "333"
            shippingAddress['extension_attributes']['piso'] = "1"
            shippingAddress['extension_attributes']['departamento'] = "1A"
            shippingAddress['extension_attributes']['celular'] = '1123323123'
            shippingAddress['extension_attributes']['observaciones'] = 'PASA'*/

            return originalAction();
        });
    };
});