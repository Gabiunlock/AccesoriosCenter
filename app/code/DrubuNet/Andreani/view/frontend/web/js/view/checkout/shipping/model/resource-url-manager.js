define(
    [
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'mageUtils'
    ],
    function(customer, quote, urlBuilder, utils) {
        "use strict";
        return {
            getUrlForSucursalList: function(quote, limit) {
                var params = {region: quote.shippingAddress().extensionAttributes.andreanisucursal_provincia, location: quote.shippingAddress().extensionAttributes.andreanisucursal_localidad};
                var urls = {
                    'default': '/module/get-sucursales-list/:region/:location'
                };
                return this.getUrl(urls, params);
            },
            getUrlForProvinciaList: function(quote, limit) {
                var params = {};
                var urls = {
                    'default': '/module/get-provincias-list/'
                };
                return this.getUrl(urls, params);
            },
            getUrlForLocalidadList: function(quote, limit) {
                var params = {region: quote.shippingAddress().extensionAttributes.andreanisucursal_provincia};
                var urls = {
                    'default': '/module/get-localidades-list/:region'
                };
                return this.getUrl(urls, params);
            },

            getUrlForCotizacionSucursal: function(quote, limit) {
                var params = {
                    /*region: quote.shippingAddress().extensionAttributes.andreanisucursal_provincia,
                    location: quote.shippingAddress().extensionAttributes.andreanisucursal_localidad,
                    sucursal: quote.shippingAddress().extensionAttributes.andreanisucursal_sucursal,*/
                };
                var urls = {
                    'default': '/module/get-cotizacion-sucursal/'
                };
                return this.getUrl(urls, params);
            },

            /** Get url for service */
            getUrl: function(urls, urlParams) {
                var url;

                if (utils.isEmpty(urls)) {
                    return 'Provided service call does not exist.';
                }

                if (!utils.isEmpty(urls['default'])) {
                    url = urls['default'];
                } else {
                    url = urls[this.getCheckoutMethod()];
                }
                return urlBuilder.createUrl(url, urlParams);
            },

            getCheckoutMethod: function() {
                return customer.isLoggedIn() ? 'customer' : 'guest';
            }
        };
    }
);