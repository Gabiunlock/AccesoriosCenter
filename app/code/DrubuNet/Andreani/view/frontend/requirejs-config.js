/**
 * @type {{map: {*: {}}}}
 */
var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'DrubuNet_Andreani/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/view/shipping': {
                'DrubuNet_Andreani/js/view/shipping': true
            },
            /*'Magento_Checkout/js/view/billing-address': {
                'DrubuNet_Andreani/js/view/billing-address': true,
            },*/
            'Magento_Checkout/js/action/create-shipping-address': {
                'DrubuNet_Andreani/js/action/create-shipping-address': true
            },
            /*'Magento_Checkout/js/action/create-billing-address': {
                'DrubuNet_Andreani/js/action/create-billing-address': true
            },*/
            'Magento_Checkout/js/view/shipping-address/address-renderer/default': {
                'DrubuNet_Andreani/js/view/shipping-address/address-renderer/default': true
            },
            'Magento_Checkout/js/view/shipping-information/address-renderer/default': {
                'DrubuNet_Andreani/js/view/shipping-information/address-renderer/default':true
            },
            /*'Magento_Checkout/js/model/shipping-save-processor/default': {
                'DrubuNet_Andreani/js/model/shipping-save-processor/default': true
            }*/
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'DrubuNet_Andreani/js/model/shipping-save-processor/payload-extender': true
            }
        }
    }
};
