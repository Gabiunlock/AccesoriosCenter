define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'ko',
    'mage/translate',
], function ($, wrapper, quote,ko,$t) {
    'use strict';

    return function (targetModule) {
        return targetModule.extend({
            validateShippingInformation: function () {
                let result = this._super();
                if(result && quote.shippingMethod()) {
                    let method = quote.shippingMethod().method_code;
                    let code = quote.shippingMethod().carrier_code;

                    if (code == 'andreanisucursal' && method == 'sucursal') {
                        let optionProvincia = $("#andreanisucursal-provincia").children("option:selected").val();
                        let indexProvincia = $("#andreanisucursal-provincia").prop('selectedIndex');
                        let optionLocalidad = $("#andreanisucursal-localidad").children("option:selected").val();
                        let indexLocalidad = $("#andreanisucursal-localidad").prop('selectedIndex');
                        let optionSucursal = $("#andreanisucursal-sucursal").children("option:selected").val();
                        let indexSucursal = $("#andreanisucursal-sucursal").prop('selectedIndex');

                        if (optionProvincia == "" || indexProvincia == 0) {
                            this.errorValidationMessage(
                                $t('Seleccione una provincia para continuar')
                            );
                            result = false;
                        }
                        else if (optionLocalidad == "" || indexLocalidad == 0) {
                            this.errorValidationMessage(
                                $t('Seleccione una localidad para continuar')
                            );
                            result = false;
                        }
                        else if (optionSucursal == "" || indexSucursal == 0) {
                            this.errorValidationMessage(
                                $t('Seleccione una sucursal para continuar')
                            );
                            result = false;
                        }
                    }
                }
                return result;
            }
        });
    };
});