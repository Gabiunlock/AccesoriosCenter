/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
define([
    'jquery',
    'Magento_Customer/js/model/authentication-popup',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/confirm',
    'underscore',
    'jquery-ui-modules/widget',
    'mage/decorate',
    'mage/collapsible',
    'mage/cookies',
    'jquery-ui-modules/effect-fade',
	'ajaxCart'
], function ($, authenticationPopup, customerData, alert, confirm) {

    $.widget('mage.sidebar', {
        options: {
            isRecursive: true,
            minicart: {
                maxItemsVisible: 3
            },
			minicartSelector: '[data-block="minicart"]',
			actionEdit : '.action.edit',
			urlRemoveItem: window.checkout.baseUrl+'cartquickpro/sidebar/removeItem/',
			urlUpdateItemQty: window.checkout.baseUrl+'cartquickpro/sidebar/updateItemQty/'
        },
        scrollHeight: 0,

        /**
         * Create sidebar.
         * @private
         */
        _create: function () {
            this._initContent();
        },

        /**
         * Update sidebar block.
         */
        update: function () {
            $(this.options.targetElement).trigger('contentUpdated');
            this._calcHeight();
            this._isOverflowed();
        },

        _initContent: function () {
            var self = this,
                events = {};

            this.element.decorate('list', this.options.isRecursive);

            events['click ' + this.options.button.close] = function (event) {
                event.stopPropagation();
                $(self.options.targetElement).dropdownDialog('close');
            };
            events['click ' + this.options.button.checkout] = $.proxy(function () {
                var cart = customerData.get('cart'),
                    customer = customerData.get('customer');

                if (!customer().firstname && cart().isGuestCheckoutAllowed === false) {
                    // set URL for redirect on successful login/registration. It's postprocessed on backend.
                    $.cookie('login_redirect', this.options.url.checkout);
                    if (this.options.url.isRedirectRequired) {
                        location.href = this.options.url.loginUrl;
                    } else {
                        authenticationPopup.showModal();
                    }

                    return false;
                }
                location.href = this.options.url.checkout;
            }, this);
            events['click ' + this.options.button.remove] =  function (event) {
                event.stopPropagation();
                confirm({
					modalClass: typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart ?  'smcqp-confirm' : '',
                    content: self.options.confirmMessage,
                    actions: {
                        confirm: function () {
                            self._removeItem($(event.currentTarget));
                        },
                        always: function (event) {
                            event.stopImmediatePropagation();
                        }
                    }
                });
            };
            events['keyup ' + this.options.item.qty] = function (event) {
                self._showItemButton($(event.target));
            };
            events['click ' + this.options.item.button] = function (event) {
                event.stopPropagation();
                self._updateItemQty($(event.currentTarget));
            };
            events['focusout ' + this.options.item.qty] = function (event) {
                self._validateQty($(event.currentTarget));
            };
			if (typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart){
				events['click ' + this.options.actionEdit] = function (event) {
					event.stopPropagation();
					event.preventDefault();
					var _url =  $(event.currentTarget).attr('href');
					 $(self.options.targetElement).dropdownDialog('close');
					window.ajaxCart._requestQuickview(_url);
					return false;
				};
			}
            this._on(this.element, events);
            this._calcHeight();
            this._isOverflowed();
        },

        /**
         * Add 'overflowed' class to minicart items wrapper element
         *
         * @private
         */
        _isOverflowed: function () {
            var list = $(this.options.minicart.list),
                cssOverflowClass = 'overflowed';

            if (this.scrollHeight > list.innerHeight()) {
                list.parent().addClass(cssOverflowClass);
            } else {
                list.parent().removeClass(cssOverflowClass);
            }
        },

        _showItemButton: function (elem) {
            var itemId = elem.data('cart-item'),
                itemQty = elem.data('item-qty');

            if (this._isValidQty(itemQty, elem.val())) {
                $('#update-cart-item-' + itemId).show('fade', 300);
            } else if (elem.val() === 0) {
                this._hideItemButton(elem);
            } else {
                this._hideItemButton(elem);
            }
        },

        /**
         * @param origin - origin qty. 'data-item-qty' attribute.
         * @param changed - new qty.
         * @returns {boolean}
         * @private
         */
         _isValidQty: function (origin, changed) {
            return origin != changed && //eslint-disable-line eqeqeq
                changed.length > 0 &&
                changed - 0 == changed && //eslint-disable-line eqeqeq
                changed - 0 > 0;
        },

        /**
         * @param {Object} elem
         * @private
         */
        _validateQty: function (elem) {
            var itemQty = elem.data('item-qty');

            if (!this._isValidQty(itemQty, elem.val())) {
                elem.val(itemQty);
            }
        },

        _hideItemButton: function (elem) {
            var itemId = elem.data('cart-item');
            $('#update-cart-item-' + itemId).hide('fade', 300);
        },

        _updateItemQty: function (elem) {
            var itemId = elem.data('cart-item');
			if (typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart){
				this._ajax(this.options.urlUpdateItemQty, {
					item_id: itemId,
					item_qty: $('#cart-item-' + itemId + '-qty').val()
				}, elem, this._updateItemQtyAfter);
			}else{
				this._ajax(this.options.url.update, {
					item_id: itemId,
					item_qty: $('#cart-item-' + itemId + '-qty').val()
				}, elem, this._updateItemQtyAfter);
			}
        },

        /**
         * Update content after update qty
         *
         * @param elem
         * @param response
         */
        _updateItemQtyAfter: function (elem, response) {
			this._hideItemButton(elem);
			if (typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart){
				window.ajaxCart._afterAjax(response);
			}	
            
        },

        _removeItem: function (elem) {
            var itemId = elem.data('cart-item');
			if (typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart){
				this._ajax(this.options.urlRemoveItem, {
					item_id: itemId
				}, elem, this._removeItemAfter);
			}else{
				this._ajax(this.options.url.remove, {
					item_id: itemId
				}, elem, this._removeItemAfter);
			}
        },

        /**
         * Update content after item remove
         *
         * @param elem
         * @param response
         * @private
         */
        _removeItemAfter: function (elem, response) {
			if (typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart){
				window.ajaxCart._afterAjax(response);
			}
        },
		
        /**
         * @param {String} url - ajax url
         * @param {Object} data - post data for ajax call
         * @param {Object} elem - element that initiated the event
         * @param {Function} callback - callback method to execute after AJAX success
         */
        _ajax: function (url, data, elem, callback) {
			if (typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart){
				var _self = this , _options = _self.options;
				$.extend(data, {
					'form_key': $.mage.cookies.get('form_key')
				});
				if (window.ajaxCart._isCheckoutPage()){
					$.extend(data, {
						'isCheckoutPage': 1
					});
				}else if (window.ajaxCart._isComparePage()){
					$.extend(data, {
						'isComparePage': 1
					});
				}else if (window.ajaxCart._isWishlistPage()){
					$.extend(data, {
						'isWishlistPage': 1
					});
				}
			}
            $.ajax({
                url: url,
                data: data,
                type: 'post',
                dataType: 'json',
                context: this,
                beforeSend: function () {
					if (typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart){
						$(_options.targetElement).dropdownDialog('close');
						$(_options.minicartSelector).trigger('contentLoading');
						window.ajaxCart._showLoader();
					}
                    elem.attr('disabled', 'disabled');
                },
                complete: function () {
                    elem.attr('disabled', null);
                }
            })
                .done(function (response) {
                    if (response.success) {
						if (typeof ajaxCart !== 'undefined' && ajaxCart.options.isAjaxCart){
							$(_options.minicartSelector).trigger('contentUpdated');
						}
                        callback.call(this, elem, response);
                    } else {
						callback.call(this, elem, response);
                        var msg = response.error_message;

                        if (msg) {
                            alert({
                                content: $.mage.__(msg)
                            });
                        }
                    }
                })
                .fail(function (error) {
                    console.log(JSON.stringify(error));
                });
        },

        /**
         * Calculate height of minicart list
         *
         * @private
         */
        _calcHeight: function () {
            var self = this,
                height = 0,
                counter = this.options.minicart.maxItemsVisible,
                target = $(this.options.minicart.list),
                outerHeight;

            self.scrollHeight = 0;
            target.children().each(function () {

                if ($(this).find('.options').length > 0) {
                    $(this).collapsible();
                }
                outerHeight = $(this).outerHeight();

                if (counter-- > 0) {
                    height += outerHeight;
                }
                self.scrollHeight += outerHeight;
            });
            target.parent().height(height);
        }
    });

    return $.mage.sidebar;
});
