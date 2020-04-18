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
	'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/confirm',
	'customModal',
	'domReady!'
], function($, alert, confirm, customModal) {
		"use strict";
		$.widget('cartQuickPro.ajaxCart',{
			options: {
				isEnabled: false,
				isAjaxCart: false,
				isCheckoutPage: false,
				urlLogin:'',
				isLoggedIn: false,
				addUrl: '',
				urlCurrent: '',
				minicartSelector: '[data-block="minicart"]',
				isUpdateConfigure: false,
				isCompareIndex: false,
				isProductView: false,
				isWishlistPage: false,
				addToWishlist: '.action.towishlist, #wishlist-sidebar .btn-remove.action.delete , .wishlist .btn-remove.action.delete, .wishlist .action.edit, .action.towishlist.updated , #wishlist-view-form .action.update, .use-ajax.action.action-towishlist ',
				formWishlist : '#wishlist-view-form',
				errorMessage: $.mage.__('Error occurred. Try to refresh page.'),
				wishlistMessage: $.mage.__('You must login first!'),
				addToCartSelector: '.action.tocart ,  .action.action-edit , .action.action-delete ',
				cartContainerSelector: '.cart-container',
				btnToCompareSelector: '.action.tocompare, #product-comparison a.action.delete, #compare-items a.action.delete , #compare-clear-all',
				btnActionPrintCompare: '.action.print',
				tableCompare: '.catalog-product_compare-index .table-wrapper.comparison',
				colMainCompare: '.catalog-product_compare-index .column.main',
				deleteCompareSelector: '#product-comparison .action.delete, #compare-items .action.delete',
				removeConfirmMessageCompare : $.mage.__( "Are you sure you want to remove this item from your Compare Products list?"),
				clearAllConfirmMessageCompare: $.mage.__("Are you sure you want to remove all items from your Compare Products list?"),
				countDownNumber: 20,
				timeSetHeight: 0,
				timeCountDown: 0,
				emailSelector: '.action.mailto.friend',
				clsModalPopup: '.smcqp-modal-popup',
				clsInerWrap: '.smcqp-modal-popup .modal-inner-wrap',
				idContainer: '#smcqp-container',
				clsLoading: 'smcqp-loading',
				clsLoadMark: '#smcqp-container .loading-mask',
				idIframe: 'smcqp-iframe',
				idReport: '#smcqp-report',
				clsMessages: '#smcqp-report .smcqp-messages',
				idContents: '#smcqp-contents',
				btnContinue: '.smcqp-continue',
				btnLink: '.smcqp-btn'
				
			},
			 _create: function () {
				if (this.options.isAjaxCart){
				    this._initAjaxCart();
					this._initAjaxCompare();
					this._initAjaxWishlist();
					this._isEmailOnQuickView();
				}
				window.ajaxCart = this;
			},
			_isWindowParent : function (){
				if (window.self !== window.parent){
					return true;
				}
				return false;	
			},
			_isCheckoutPage : function () {
				var _self = this , _options = _self.options;
				if (_self._isWindowParent())
					return window.parent.ajaxCart.options.isCheckoutPage;
				else
					return _options.isCheckoutPage;
			},
			_isComparePage : function () {
				var _self = this , _options = _self.options;
				if (_self._isWindowParent())
					return window.parent.ajaxCart.options.isCompareIndex;
				else
					return _options.isCompareIndex;
			},
			_isWishlistPage : function () {
				var _self = this , _options = _self.options;
				if (_self._isWindowParent())
					return window.parent.ajaxCart.options.isWishlistPage;
				else
					return _options.isWishlistPage;
			},
			
			_initAjaxWishlist: function () {
				var _self = this , _options = _self.options;
				$('body').off('click', _options.addToWishlist).on('click', _options.addToWishlist, function (e){
					e.preventDefault();
					var _that = $(this), _params = '', _oldAction = '', _isaddtocart = false,
						_dataPost = (typeof _that.attr('data-post') !== 'undefined') ? $.parseJSON(_that.attr('data-post')) : null;
						_dataPost = (typeof _that.attr('data-post-remove') !== 'undefined') ? $.parseJSON(_that.attr('data-post-remove')) : _dataPost;
						_dataPost = (_dataPost == null) ? _that.attr('href'): _dataPost;
					if (_that.is('.use-ajax.action.action-towishlist')){
						_isaddtocart = true;
					}	
					if (!_options.isLoggedIn){
						if (_self._isWindowParent()){
							window.parent.ajaxCart._closeModalParent(e);
						}else{
							_self._comfirmForWishlist(e);
						}
					}else{
						if (_that.is('#wishlist-view-form .action.update')){
							var _form = $(this).parents('form') ;
							_oldAction = _form.attr('action');
							_params = _form.serialize();
							if (_params.search('&form_key') == -1){
								var _formKey = $("input[name='form_key']").val();
								_params += '&form_key=' + _formKey;
							}
							_self._sendAjax(_oldAction, _params , _isaddtocart);
						}else{
							if (_dataPost && typeof _dataPost == 'object') {
								var _formKey = $("input[name='form_key']").val();
								_params += (typeof _dataPost.data.product !== 'undefined' ? '&product=' + _dataPost.data.product : '');
								_params += (typeof _dataPost.data.item !== 'undefined' ? '&item=' + _dataPost.data.item : '');
								_params += (typeof _dataPost.data.id !== 'undefined' ? '&id=' + _dataPost.data.id : '');
								_params += '&form_key=' + _formKey + '&uenc=' + _dataPost.data.uenc;
								_oldAction = _dataPost.action;
								if (_options.isProductView) {
									window.parent.ajaxCart._sendAjax(_oldAction, _params , _isaddtocart);
								}else{
									_self._sendAjax(_oldAction, _params , _isaddtocart);
								}
							}else{
								_self._requestQuickview(_dataPost);
							}	
						}	
					}
					return false;
				});
				
			},
			_initAjaxCompare: function(){
				var _self = this ,
					_options = _self.options;
					/** For Button Print **/
					 $('body.catalog-product_compare-index').off('click', _options.btnActionPrintCompare).on('click', _options.btnActionPrintCompare, function (e){
						e.preventDefault();
						e.stopPropagation();
						window.print();
					}); 
					
					/** For Button Add, Clear, Remove  **/
					$('body').off('click', _options.btnToCompareSelector).on('click', _options.btnToCompareSelector, function (e){
						e.preventDefault();
						var _that = $(this), _params = '', _oldAction = '',
							_dataPost = $.parseJSON(_that.attr('data-post'));
						if (_dataPost) {
							var _formKey = $("input[name='form_key']").val();
							_params += 'product=' + _dataPost.data.product + '&form_key=' + _formKey + '&uenc=' + _dataPost.data.uenc;
							_oldAction = _dataPost.action;
							if (_that.is('.action.delete') || _that.is('#compare-clear-all')){
									confirm({
										modalClass: 'smcqp-confirm',
										content: _that.is('#compare-clear-all') ? _options.clearAllConfirmMessageCompare : _options.removeConfirmMessageCompare,
										actions: {
											confirm: function () {
												_self._sendAjax(_oldAction, _params);
											},
											always: function (event) {
												event.stopImmediatePropagation();
											}
										}
									});
							}else{
								if (_options.isProductView) {
									window.parent.ajaxCart._sendAjax(_oldAction, _params);
								}else{
									_self._sendAjax(_oldAction, _params);
								}
							}
						} 
						return false;
					});
			},
			_isEmailOnQuickView : function () {
				var _self = this , _options = _self.options;
				if (_self._isWindowParent()){
					$('body').off('click', _options.emailSelector).on('click', _options.emailSelector, function (e){
						window.parent.location = $(this).attr('href');
						window.parent.ajaxCart._closeModalSelf();
					});
				}
			},
			_initAjaxCart: function () {
				var _self = this , _options = _self.options;
				$('body').off('click sumit', _options.addToCartSelector).on('click', _options.addToCartSelector , function (e) {	
					e.preventDefault();
					var _that = $(this), _form = $(this).parents('form') , _isCheck = _form.length ? true : false, _isValid = true , _params = '', _oldAction = '';
					
					if (_isCheck && !_form.is(_options.formWishlist) && !_that.is('.action.action-delete') && !_that.is('.action.action-edit')){
						if (_form.is('.form.shared.wishlist'))
							return ;
						if (_form.attr('data-role') == 'tocart-form'){
						}else{
							_isValid = _form.valid();
						}
						if (_isValid){
							_oldAction = _form.attr('action');
							_params = _form.serialize();
							if (_params.search('&form_key') == -1){
								var _formKey = $("input[name='form_key']").val();
								_params += '&form_key=' + _formKey;
							}
							
							if (_options.isProductView) {
								if (_form.has('input[type="file"]').length && _form.find('input[type="file"]').val() !== '') {
									return _form.submit();
									 setTimeout(function(){  window.parent.ajaxCart._showLoader();}, 500);
									return  window.parent.location.href = window.parent.checkout.shoppingCartUrl;
								}
								window.parent.ajaxCart._sendAjax(_oldAction, _params, true);
							}else{
								if (_form.has('input[type="file"]').length && _form.find('input[type="file"]').val() !== '') {
									 return _form.submit();
									 return setTimeout(function(){ _self._showLoader(); window.location.href = window.checkout.shoppingCartUrl;}, 500);
								}
								if (_that.is('.action.action-edit')){
									var _url =  _that.attr('href');
									_self._requestQuickview(_url);
								}else{
									_self._sendAjax(_oldAction, _params , true);
								}
								
							}
							return false;
						}
					}else{
						 var _dataPost = $.parseJSON($(this).attr('data-post'));
						 if (_dataPost) {
							var _formKey = $("input[name='form_key']").val();
							_params += (typeof _dataPost.data.product !== 'undefined' ? '&product=' + _dataPost.data.product : '');
							_params += (typeof _dataPost.data.item !== 'undefined' ? '&item=' + _dataPost.data.item : '');
							_params += (typeof _dataPost.data.id !== 'undefined' ? '&id=' + _dataPost.data.id : '');
							_params +=  '&form_key=' + _formKey + '&uenc=' + _dataPost.data.uenc;
							var _dataPost2 =  _that.data('post');
							if (_dataPost2){
								_params += (typeof _dataPost2.data.qty !== 'undefined' ? '&qty=' + _dataPost2.data.qty : '');
							}
							_oldAction = _dataPost.action;
							_self._sendAjax(_oldAction, _params, true );
							return false;
						} else {
							if (_that.is('.action.action-edit')){
								var _url =  _that.attr('href');
								_self._requestQuickview(_url);
							}
							
						} 
					}
					return false;
				});	
			},
			_closeModalParent : function (e){
				var _self = this , _options = _self.options;
				$(_options.idContainer).customModal("closeModal");
				_self._comfirmForWishlist(e);
			},
			_comfirmForWishlist : function (event) {
				var _self = this , _options = _self.options;
				return confirm({
					modalClass: 'smcqp-confirm',
					content: _options.wishlistMessage ,
					actions: {
						confirm: function () {
							window.location.href = _options.urlLogin;
						},
						always: function (event) {
							event.stopImmediatePropagation();
						}
					}
				});
			},
			_showLoader : function () {
				var _self = this , _options = _self.options;
				$(_options.idContainer).customModal("openModal");
			},
			
			_hideLoader : function () {
				var _self = this , _options = _self.options;
				$(_options.clsLoadMark).hide();
                $(_options.clsInerWrap).removeClass(_options.clsLoading).removeClass('report-messages');
				$(_options.clsMessages).html('').removeClass('msg-success msg-error');
			},
			_checkBtnLink : function (json) {
				var _self = this , _options = _self.options;
				$(_options.btnLink).removeClass('smcqp-open');
				if (typeof json.isAddToCartBtn !== 'undefined' && json.isAddToCartBtn){
					$(_options.btnLink+'.smcqp-view-cart').addClass('smcqp-open');
				}else if (typeof json.isCompareBtn !== 'undefined' && json.isCompareBtn){
					$(_options.btnLink+'.smcqp-view-compare').addClass('smcqp-open');
				}else if (typeof json.isWishlistBtn !== 'undefined' && json.isWishlistBtn){
					$(_options.btnLink+'.smcqp-view-wishlist').addClass('smcqp-open');
				}
			},
			_afterAjax: function (json){
				var _self = this , _options = _self.options, _msg = json.messages;
				$(_options.idReport).show();
				_self._hideLoader();
				_self._checkBtnLink(json);
				$(_options.clsInerWrap).addClass('report-messages');
				$(_options.clsMessages).html(_msg);
				if (json.success){
					$(_options.clsMessages).addClass('msg-success');
				}else{
					$(_options.clsMessages).addClass('msg-error');
				}
				
				if (typeof json.isPageCheckoutContent  !== 'undefined' && json.isPageCheckoutContent){
					var _content = $(json.content).html();
					if ($('form.form.form-cart', $(json.content)).length){
						$('form.form.form-cart',$(_options.cartContainerSelector )).replaceWith($(json.content).html());
						$('form.form.form-cart',$(_options.cartContainerSelector )).prepend('<input name="form_key" type="hidden" value="'+$.mage.cookies.get('form_key')+'">');
						var deferred = $.Deferred();
						 require(['Magento_Checkout/js/action/get-totals'], function (getTotalsAction) {
							 getTotalsAction([], deferred); 
						 });
					}else{
						$(_options.cartContainerSelector ).replaceWith($(json.content));
					}
				}
				
				if (typeof json.isComparePageContent  !== 'undefined' && json.isComparePageContent){
					$(_options.btnActionPrintCompare ).remove();	
					$(_options.tableCompare ).replaceWith($(json.content));
					/*$(json.content).appendTo($(_options.colMainCompare));*/
				}
				
				if (typeof json.isWishlistPageContent !== 'undefined' &&  json.isWishlistPageContent){
					$('.toolbar.wishlist-toolbar').remove();
					$('script#form-tmpl').remove()
					$(_options.formWishlist).replaceWith($(json.content));
				}
				if(_options.countDownNumber){
					_self._closeBoxComfim(_options.countDownNumber);
				}
				_self._clickContinue();
			},
			_clickContinue : function () {
				var _self = this , _options = _self.options;
				$('body').off('click', _options.btnContinue).on('click', _options.btnContinue, function (e){
					e.preventDefault();
					_self._closeModalSelf();
					return false;
				})	
			},
			_prepareUrl: function (url){
				if (typeof url !=='undefined' && url !== null){
					if (url.search('checkout/cart') !== -1){
						return url = url.replace('checkout/cart', 'cartquickpro/cart');
					}else if (url.search('wishlist/index') !== -1){
						return url = url.replace('wishlist/index', 'cartquickpro/wishlist_index')
					}else if (url.search('catalog/product_compare')!== -1){
						return url = url.replace('catalog/product_compare', 'cartquickpro/product_compare')
					}
					return url;
				}
			},
			_sendAjax: function (oldAction, data, isaddtocart) {
				isaddtocart = isaddtocart || false;
				var _self = this , _options = _self.options , _url_basic = oldAction , _url_ajax = oldAction;
				if (oldAction.search('options=cart') !== -1){
					_url_ajax = _self.options.addUrl;
				}
				if (_self._isCheckoutPage()){
					data += '&isCheckoutPage=1';
				}else if (_self._isComparePage()){
					data += '&isComparePage=1';
				}else if (_self._isWishlistPage()){
					data += '&isWishlistPage=1';
				}
				$.ajax({
					type: 'post',
					url: _self._prepareUrl(_url_ajax),
					data: data,
					dataType: 'json',
					beforeSend: function (){
						if (isaddtocart){
							$(_options.minicartSelector).trigger('contentLoading');
						} 
						_self._showLoader();
					},
					success: function (data) {},
					complete: function () {},
					error: function () {
					}
				}).done(function (json){
					if (typeof json.url !== 'undefined' && json.url !== null){
						_self._requestQuickview(json.url);
					}else{
						_self._afterAjax(json);
						if (isaddtocart){
							$(_options.minicartSelector).trigger('contentUpdated');
						} 
						$(document).trigger("afterAjaxProductsLoaded");
						$(document).trigger("afterAjaxLazyLoad");
					}
				}).fail(function (error) {
					console.log(JSON.stringify(error));
					$(_options.idContainer).customModal("closeModal");
					alert({
						modalClass: 'smcqp-confirm',
						content: _options.errorMessage ,
						title: '',
						actions: {
							always: function (event) {
								var e = typeof event !== 'undefined' ? event :jQuery.Event( "click" );
								e.stopImmediatePropagation();
								window.location.href = _options.urlCurrent;
							}
						}
					});
                });
				return false;
			},
			_closeBoxComfim : function (_time) {
				var _self = this , _options = _self.options;
				if (_time){
					_time = _time - 1;
					$(_options.btnContinue).attr('data-count',_time);
					_options.timeCountDown = setTimeout(function() {
						if(typeof _options.timeCountDown !== "undefined"){
							  window.clearTimeout(_options.timeCountDown);
							  _options.timeCountDown = 0;
						}
						_self._closeBoxComfim(_time);
					}, 1000);
					
				}else{
					_self._closeModalSelf();
				}
				
			},
			_closeModalSelf : function (){
				var _self = this , _options = _self.options;
				if(typeof _options.timeCountDown !== "undefined"){
					  window.clearTimeout(_options.timeCountDown);
					  _options.timeCountDown = 0;
				}
				$(_options.idContainer).customModal("closeModal");
			},
			
			_requestQuickview : function (_link){
				var _self = this , _options = _self.options;
				var _url = _self._prepareUrl(_link);
					_url =  _url.substr(_url.length - 1) == '/' ?  _url.substring(0,_url.length - 1) : _url;
					$(_options.idContainer).customModal("openModal");
					$(_options.clsInerWrap).addClass('smcqp-options');
				if ($('#'+_options.idIframe).length){
					$('#'+_options.idIframe).remove();
				}	
				var ifr=$('<iframe/>', {
					id:_options.idIframe,
					src:_url+"/randtime/" + new Date().getTime(),
					scrolling: 'no',
					frameborder:0,
					width:'100%',
					height: '301px',
					load:function(){
						_self._hideLoader();
						$(_options.idContents).show();
						_self.setHeightIframe(ifr);
					}
				});
				$(_options.idContents).append(ifr);
				$(_options.idContents).trigger('contentUpdated');
				
			},
			setHeightIframe: function(ifr) {
				var _self = this , _options = _self.options, 
					_time = _self.options.timeSetHeight , 
					_ifr = document.getElementById(_options.idIframe);
				if (typeof ifr === 'undefined' || ifr === null || typeof _ifr === 'undefined' || _ifr === null) return;
					var ifr_height = _ifr.getAttribute('height'),
						_content = _ifr.contentWindow;
					if (typeof _content === 'undefined' || _content === null || _content.document.body === null) return;	
					var _content_temp = _content.document.body.scrollHeight;
					var _diff = window.innerHeight - _content_temp ;
					var _content_height = _diff >0 ? _content_temp  : window.innerHeight - 100;
					if (typeof _content_temp !== 'undefined') {
						if (ifr.height() !== _content_temp){
							ifr.height(_content_temp);
						}
					}
				if (_time == 'undefined') _time = 0;
				clearTimeout(_time);
				_time = setTimeout(function() {
					_self.setHeightIframe(ifr);
				}, 500);
			},
			
		});
    return $.cartQuickPro.ajaxCart;
});	