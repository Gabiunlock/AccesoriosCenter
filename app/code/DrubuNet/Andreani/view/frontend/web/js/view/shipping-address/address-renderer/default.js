define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-address/form-popup-state',
    'Magento_Checkout/js/checkout-data',
    'Magento_Customer/js/customer-data',
    'mage/utils/wrapper',
], function ($, ko, Component, selectShippingAddressAction, quote, formPopUpState, checkoutData, customerData,wrapper) {
    'use strict';

    return function (targetModule) {
        return targetModule.extend({
            defaults: {
                template: 'DrubuNet_Andreani/shipping-address/address-renderer/default'
            },
            getAltura: function () {
                if(typeof(this.address()) != "undefined" && typeof(this.address().customAttributes) != "undefined") {
                    if (typeof (this.address().customAttributes.altura) != "undefined") {
                        return this.address().customAttributes.altura.value;
                    }
                    else{
                        let alturaValue = null;
                        $.each(this.address().customAttributes , function( key, value ) {
                            if(!isNaN(key)){
                                if(value.attribute_code == 'altura'){
                                    return alturaValue = value.value.value;
                                }
                            }
                        });
                        return alturaValue;
                    }
                }
                return '';
            },
            getPiso: function () {
                if(typeof(this.address()) != "undefined" && typeof(this.address().customAttributes) != "undefined") {
                    if (typeof (this.address().customAttributes.piso) != "undefined") {
                        return ', Piso: ' + this.address().customAttributes.piso.value;
                    } else {
                        let pisoValue = null;
                        $.each(this.address().customAttributes , function( key, value ) {
                            if(!isNaN(key)){
                                if(value.attribute_code == 'piso'){
                                    return pisoValue = value.value.value;
                                }
                            }
                        });
			if(pisoValue){
                        	return ', Piso: ' + pisoValue;
			}
                    }
                }
                return '';
            },
            getDepartamento: function () {
                if(typeof(this.address()) != "undefined" && typeof(this.address().customAttributes) != "undefined") {
                    if(typeof(this.address().customAttributes.departamento) != "undefined") {
                        return ', Departamento: ' + this.address().customAttributes.departamento.value;
                    }
                    else {
                        let deptoValue = null;
                        $.each(this.address().customAttributes , function( key, value ) {
                            if(!isNaN(key)){
                                if(value.attribute_code == 'departamento'){
                                    return deptoValue = value.value.value;
                                }
                            }
                        });
			if(deptoValue){
	                        return ', Departamento: ' + deptoValue;
			}
                    }
                }
                return '';
            }
        });
    };
});
