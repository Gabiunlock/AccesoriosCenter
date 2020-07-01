define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-service',
    'DrubuNet_Andreani/js/view/checkout/shipping/sucursales-service',
    'mage/translate',
], function ($, ko, Component, quote, shippingService, sucursalService, t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'DrubuNet_Andreani/checkout/shipping/form'
        },

        initialize: function (config) {
            this.sucursales = ko.observableArray();
            this.selectedSucursal = ko.observable();
            this.provincias = ko.observableArray();
            this.selectedProvincia = ko.observable();
            this.localidades = ko.observableArray();
            this.selectedLocalidad = ko.observable();
            this._super();
        },

        initObservable: function () {
            this._super();

            this.showProvinciaSelection = ko.computed(function() {
                return this.provincias().length != 0
            }, this);
            this.showLocalidadSelection = ko.computed(function() {
                return this.localidades().length != 0
            }, this);
            this.showSucursalSelection = ko.computed(function() {
                return this.sucursales().length != 0
            }, this);

            this.selectedMethod = ko.computed(function() {
                var method = quote.shippingMethod();
                var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;
                return selectedMethod;
            }, this);

            quote.shippingMethod.subscribe(function(method) {
                var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;
                if (selectedMethod == 'andreanisucursal_sucursal') {
                    this.reloadProvincias();
                    //this.reloadLocalidades();
                    //this.reloadSucursales();
                }
                else{
                    this.provincias([]);
                    this.localidades([]);
                    this.sucursales([]);
                }
            }, this);
            this.selectedProvincia.subscribe(function(provincia) {
                if (quote.shippingAddress().extensionAttributes == undefined) {
                    quote.shippingAddress().extensionAttributes = {};
                }
                quote.shippingAddress().extensionAttributes.andreanisucursal_provincia = provincia;
            });

            this.selectedLocalidad.subscribe(function(localidad) {
                if (quote.shippingAddress().extensionAttributes == undefined) {
                    quote.shippingAddress().extensionAttributes = {};
                }
                quote.shippingAddress().extensionAttributes.andreanisucursal_localidad = localidad;
            });

            this.selectedSucursal.subscribe(function(sucursal) {
                if (quote.shippingAddress().extensionAttributes == undefined) {
                    quote.shippingAddress().extensionAttributes = {};
                }
                quote.shippingAddress().extensionAttributes.andreanisucursal_sucursal = sucursal;
            });


            return this;
        },

        setSucursalList: function(list) {
            this.sucursales(list);
        },

        setProvinciaList: function(list) {
            this.provincias(list);
        },

        setLocalidadList: function(list) {
            this.localidades(list);
        },

        reloadSucursales: function() {
            if(quote.shippingAddress().extensionAttributes.andreanisucursal_provincia != undefined && quote.shippingAddress().extensionAttributes.andreanisucursal_localidad != undefined) {
                sucursalService.getSucursalList(quote.shippingAddress(), this);
                var defaultSucursal = this.sucursales()[0];
                if (defaultSucursal) {
                    this.selectedSucursal(defaultSucursal);
                }
            }
            else {
                this.sucursales([]);
                this.selectedSucursal(null);
            }
        },

        reloadProvincias: function() {
            sucursalService.getProvinciaList(quote.shippingAddress(), this);
            var defaultProvincia = this.provincias()[0];
            if (defaultProvincia) {
                this.selectedProvincia(defaultProvincia);
            }
        },

        reloadLocalidades: function() {
            if(quote.shippingAddress().extensionAttributes.andreanisucursal_provincia != undefined) {
                sucursalService.getLocalidadList(quote.shippingAddress(), this);
                var defaultLocalidad = this.localidades()[0];
                if (defaultLocalidad) {
                    this.selectedLocalidad(defaultLocalidad);
                }
            }
            else {
                this.sucursales([]);
                this.selectedSucursal(null);
                this.localidades([]);
                this.selectedLocalidad(null);
            }
        },

        getSucursal: function() {
            var sucursal;
            if (this.selectedSucursal()) {
                for (var i in this.sucursales()) {
                    var m = this.sucursales()[i];
                    if (m.name == this.selectedSucursal()) {
                        sucursal = m;
                    }
                }
            }
            else {
                sucursal = this.sucursales()[0];
            }

            return sucursal;
        },

        getProvincia: function() {
            var provincia;
            if (this.selectedProvincia()) {
                for (var i in this.provincias()) {
                    var m = this.provincias()[i];
                    if (m.name == this.selectedProvincia()) {
                        provincia = m;
                    }
                }
            }
            else {
                provincia = this.provincias()[0];
            }

            return provincia;
        },

        getLocalidad: function() {
            var localidad;
            if (this.selectedLocalidad()) {
                for (var i in this.localidades()) {
                    var m = this.localidades()[i];
                    if (m.name == this.selectedLocalidad()) {
                        localidad = m;
                    }
                }
            }
            else {
                localidad = this.localidades()[0];
            }

            return localidad;
        },

        getCotizacionSucursal:function(){
            sucursalService.getCotizacionSucursal(quote.shippingAddress(), this);
        },

        provinciaChange: function(obj, event){
            this.localidades([]);
            this.sucursales([]);
            this.selectedLocalidad(null);
            this.selectedSucursal(null);
            quote.shippingAddress().extensionAttributes.andreanisucursal_localidad = undefined;
            quote.shippingAddress().extensionAttributes.andreanisucursal_sucursal = undefined;
            this.reloadLocalidades();
        },
        localidadChange: function(obj, event){
            this.sucursales([]); //borro las sucursales
            this.selectedSucursal(null);
            quote.shippingAddress().extensionAttributes.andreanisucursal_sucursal = undefined
            this.reloadSucursales();
            //boton siguiente desactivado
        },
        sucursalChange: async function (obj, event) {
            if (this.selectedSucursal()) {
                this.getCotizacionSucursal();
            }
        },

        initSelector: function() {
            var startProvincia = this.getProvincia();
            var startLocalidad = this.getLocalidad();
            var startSucursal = this.getSucursal();
        }
    });

});
