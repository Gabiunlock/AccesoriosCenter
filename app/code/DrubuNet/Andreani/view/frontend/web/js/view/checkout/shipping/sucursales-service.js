define(
    [
        'jquery',
        'DrubuNet_Andreani/js/view/checkout/shipping/model/resource-url-manager',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/model/customer',
        'mage/storage',
        'mage/url',
        'Magento_Checkout/js/model/shipping-service',
        'DrubuNet_Andreani/js/view/checkout/shipping/model/sucursales-registry',
        'DrubuNet_Andreani/js/view/checkout/shipping/model/provincias-registry',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Catalog/js/price-utils'
    ],
    function ($,resourceUrlManager, quote, customer, storage, urlBuilder, shippingService, sucursalesRegistry,provinciasRegistry, errorProcessor,priceUtils) {
        'use strict';

        return {
            /**
             * Get nearest machine list for specified address
             * @param {Object} address
             */
            getSucursalList: function (address, form) {
                shippingService.isLoading(true);
                var cacheKey = address.getCacheKey() + "_" + quote.shippingAddress().extensionAttributes.andreanisucursal_provincia + "_" + quote.shippingAddress().extensionAttributes.andreanisucursal_localidad,
                    cache = sucursalesRegistry.get(cacheKey),
                    serviceUrl = resourceUrlManager.getUrlForSucursalList(quote);

                if (cache) {
                    form.setSucursalList(cache);
                    shippingService.isLoading(false);
                } else {
                    storage.get(
                        serviceUrl, false
                    ).done(
                        function (result) {
                            sucursalesRegistry.set(cacheKey, result);
                            form.setSucursalList(result);
                        }
                    ).fail(
                        function (response) {
                            errorProcessor.process(response);
                        }
                    ).always(
                        function () {
                            shippingService.isLoading(false);
                        }
                    );
                }
            },
            /**
             * Get nearest machine list for specified address
             * @param {Object} address
             */
            getProvinciaList: function (address, form) {
                shippingService.isLoading(true);
                var cacheKey = address.getCacheKey(),
                    cache = provinciasRegistry.get(cacheKey),
                    serviceUrl = resourceUrlManager.getUrlForProvinciaList(quote);

                if (cache) {
                    form.setProvinciaList(cache);
                    shippingService.isLoading(false);
                } else {
                    storage.get(
                        serviceUrl, false
                    ).done(
                        function (result) {
                            provinciasRegistry.set(cacheKey, result);
                            form.setProvinciaList(result);
                        }
                    ).fail(
                        function (response) {
                            errorProcessor.process(response);
                        }
                    ).always(
                        function () {
                            shippingService.isLoading(false);
                        }
                    );
                }
            },

            getLocalidadList: function (address, form) {
                shippingService.isLoading(true);
                var cacheKey = address.getCacheKey() + "_" + quote.shippingAddress().extensionAttributes.andreanisucursal_provincia,
                    cache = provinciasRegistry.get(cacheKey),
                    serviceUrl = resourceUrlManager.getUrlForLocalidadList(quote);

                if (cache) {
                    form.setLocalidadList(cache);
                    shippingService.isLoading(false);
                } else {
                    storage.get(
                        serviceUrl, false
                    ).done(
                        function (result) {
                            provinciasRegistry.set(cacheKey, result);
                            form.setLocalidadList(result);
                        }
                    ).fail(
                        function (response) {
                            errorProcessor.process(response);
                        }
                    ).always(
                        function () {
                            shippingService.isLoading(false);
                        }
                    );
                }
            },

            getCotizacionSucursal: function (address, form) {
                shippingService.isLoading(true);
                //var cacheKey = address.getCacheKey() + "_cotizacion_" + quote.shippingAddress().extensionAttributes.andreanisucursal_provincia + "_" + quote.shippingAddress().extensionAttributes.andreanisucursal_localidad,
                    //cache = sucursalesRegistry.get(cacheKey),
                var serviceUrl = resourceUrlManager.getUrlForCotizacionSucursal(quote);
/*                storage.post(
                    '%URL for shipping rate estimation%',
                    JSON.stringify({
                        // '%address parameters%'
                    }),
                    false
                )
 */

                var settings2 = {
                    "url": "http://127.0.0.1/rest/default/V1/module/get-cotizacion-sucursal",
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/json"
                    },
                    "data": JSON.stringify({"sucursal":form.getSucursal()}),
                };
                storage.post(
                    serviceUrl,
                    JSON.stringify({"sucursal":form.getSucursal()}),
                    true
                )
                    .done(function (response) {
                        let costoEnvio = priceUtils.formatPrice(response[0].shippingPrice, quote.getPriceFormat());
                        jQuery(jQuery(jQuery("#label_method_sucursal_andreanisucursal").parent()[0].childNodes[3]).children('span')).text(costoEnvio)
                    })
                    .fail(function (response) {
                        alert("Ocurrio un error obteniendo la cotizacion. Intentelo mas tarde")
                    })
                    .always(function () {
                        shippingService.isLoading(false);
                    });
            },

        };
    }
);
/*
            else
            {
                shippingService.isLoading(true);
                storage.post(
                '%URL for shipping rate estimation%',
                    JSON.stringify({
                       // '%address parameters%'
            }),
                false
            ).done(
                    function (result) {
                        rateRegistry.set(address.getKey(), result);
                        shippingService.setShippingRates(result);
                    }
                ).fail(
                    function (response) {
                        shippingService.setShippingRates([]);
                        errorProcessor.process(response);
                    }
                ).always(
                    function () {
                        shippingService.isLoading(false);
                    }
                );
            }
 */