/*define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'ko',
    'mage/translate',
], function ($, wrapper, quote,ko,$t) {*/
define([
        'jquery',
        'mage/utils/wrapper',
        'ko',
        'underscore',
        'Magento_Ui/js/form/form',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-billing-address',
        'Magento_Checkout/js/action/select-billing-address',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/action/set-billing-address',
        'Magento_Ui/js/model/messageList',
        'mage/translate',
        'Magento_Checkout/js/model/billing-address-postcode-validator'
    ],
    function (
        $,
        wrapper,
        ko,
        _,
        Component,
        customer,
        addressList,
        quote,
        createBillingAddress,
        selectBillingAddress,
        checkoutData,
        checkoutDataResolver,
        customerData,
        setBillingAddressAction,
        globalMessageList,
        $t,
        billingAddressPostcodeValidator
    ) {
    'use strict';

    return function (targetModule) {
        return targetModule.extend({
            /*defaults: {
                template: 'Magento_Checkout/billing-address',
                actionsTemplate: 'Magento_Checkout/billing-address/actions',
                formTemplate: 'Magento_Checkout/billing-address/form',*/
            /*detailsTemplate: 'DrubuNet_Andreani/billing-address/details',
            /*lis: {
                isAddressFormVisible: '${$.billingAddressListProvider}:isNewAddressSelected'
            }
        },*/
            getAltura: function ()
            {
                if(!customer.isLoggedIn() && this.isAddressSameAsShipping() && jQuery('#shipping-new-address-form input[name="altura"]').val())
                {
                    return jQuery('#shipping-new-address-form input[name="altura"]').val();
                }

                if(typeof this.currentBillingAddress() != "undefined" && typeof this.currentBillingAddress().customAttributes != "undefined" )
                {
                    if(typeof this.currentBillingAddress().customAttributes.altura != "undefined" &&
                        typeof this.currentBillingAddress().customAttributes.altura.value != "undefined")
                        return this.currentBillingAddress().customAttributes.altura.value;
                    if(typeof this.currentBillingAddress().customAttributes.altura != "undefined")
                        return this.currentBillingAddress().customAttributes.altura;

                    return '';
                }
                else
                    return '';
            },
            getPiso: function ()
            {
                if(!customer.isLoggedIn() && this.isAddressSameAsShipping() && jQuery('#shipping-new-address-form input[name="piso"]').val())
                {
                    return ', Piso: '+ jQuery('#shipping-new-address-form input[name="piso"]').val();
                }

                if(typeof this.currentBillingAddress() != "undefined" && typeof this.currentBillingAddress().customAttributes != "undefined")
                {
                    if(typeof this.currentBillingAddress().customAttributes.piso != "undefined" &&
                        typeof this.currentBillingAddress().customAttributes.piso.value != "undefined")
                        return ', Piso: '+ this.currentBillingAddress().customAttributes.piso.value;
                    if(typeof this.currentBillingAddress().customAttributes.piso != "undefined")
                        return ', Piso: '+ this.currentBillingAddress().customAttributes.piso;

                    return '';
                }
                else
                    return '';
            },
            getDepartamento: function ()
            {
                if(!customer.isLoggedIn() && this.isAddressSameAsShipping() && jQuery('#shipping-new-address-form input[name="departamento"]').val())
                {
                    return ', Departamento: '+ jQuery('#shipping-new-address-form input[name="departamento"]').val();
                }

                if(typeof this.currentBillingAddress() != "undefined" && typeof this.currentBillingAddress().customAttributes != "undefined")
                {
                    if(typeof this.currentBillingAddress().customAttributes.departamento != "undefined" &&
                        typeof this.currentBillingAddress().customAttributes.departamento.value != "undefined")
                        return ', Departamento: '+ this.currentBillingAddress().customAttributes.departamento.value;
                    if(typeof this.currentBillingAddress().customAttributes.departamento != "undefined")
                        return ', Departamento: '+ this.currentBillingAddress().customAttributes.departamento;

                    return '';
                }
                else
                    return '';
            }
        });
    };
});