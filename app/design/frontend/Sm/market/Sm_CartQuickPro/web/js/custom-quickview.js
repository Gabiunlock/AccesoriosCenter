/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
define(['jquery',  
		'customModal' , 
		'mage/dataPost'],
	function($, customModal){
		;(function($) {
			function QuickView(element, options) {
				this.options = $.extend({}, QuickView.Defaults , options);
				this.$element = $(element);
				this._plugins = {};
				$.each(QuickView.Plugins, $.proxy(function(key, plugin) {
					this._plugins[key[0].toLowerCase() + key.slice(1)]
						= new plugin(this);
				}, this));
				this.initialize();
				if (this.options.isQuickView){
					this.requestQuickView();
					if (this.options.isAjaxCart == false){
						this.processLink();
					}
				}
				window.ajaxQuickView = this;
			}
			
			QuickView.Defaults = {
				isQuickView: false,
				isAjaxCart: false
			};
			
			QuickView.Plugins = {};
			
			QuickView.prototype.initialize = function() {
				$('#smcqp-container').customModal({
					autoOpen: false,
					clickableOverlay: false,
					innerScroll: true,
					modalClass: "smcqp-modal-popup",
					responsive: true,
					type: 'popup',
					closed: function() {},
					opened: function() {}
					
				});
				if (this.options.isQuickView){
					var that = this;
					var _product_container = $(this.options.product_container);
					_product_container.each(function(){
						var _self = $(this),
						   _id_product = _self.find('[data-product-id]').attr('data-product-id');
						  if (typeof _id_product !== 'undefined' &&  _id_product !== null){
							   var  _url = that.options.base_url+'cartquickpro/catalog_product/view/id/'+_id_product,
							   _button_container = _self.find(that.options.button_container).length ? _self.find(that.options.button_container) :  _self,
								_button = "<a class='action quickview-handler sm_quickview_handler' title='"+that.options.label_button+"' href='"+_url+"'><span>"+that.options.label_button+"</span></a>";
							   if ($('.quickview-handler',_button_container).length <= 0){
									_button_container.append(_button);
							   }
						  }
					});
				}
			}
			
			QuickView.prototype.setHeightIframe = function(ifr) {
				var _self = this , 
					_time = 0 , 
					_ifr = document.getElementById('smcqp-iframe');
				if (typeof ifr === 'undefined' || ifr === null || typeof _ifr === 'undefined' || _ifr === null) return;
					var ifr_height = _ifr.getAttribute('height'),
						_content = _ifr.contentWindow;
					if (typeof _content === 'undefined' || _content === null || _content.document.body === null) return;	
					var _content_temp = $(_content.document.body).height();
					if (typeof _content_temp !== 'undefined') {
						$('.gallery-placeholder',$(_content.document.body)).trigger('contentUpdate');
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
			
			QuickView.prototype.requestQuickView = function() {
				var _self = this ;
				var _handler = $('.quickview-handler');
				if (_handler.length){
					$('body').off('click', '.quickview-handler').on('click', '.quickview-handler', function(e){
						e.preventDefault();
						$('#smcqp-container').customModal("openModal");
						var self = $(this), _link = self.attr('href');
						var ifr=$('<iframe/>', {
							id:'smcqp-iframe',
							src:_link+"/randtime/" + new Date().getTime(),
							scrolling: 'no',
							frameborder:0,
							width:'100%',
							height:'300px',
							load:function(){
								$("#smcqp-container .loading-mask").hide();
								$(".smcqp-modal-popup .modal-inner-wrap").removeClass("smcqp-loading");
								$("#smcqp-contents").show();
								_self.setHeightIframe(ifr);
							}
						});
						$('#smcqp-contents').append(ifr);
					});
				}
			}
			
			QuickView.prototype.closeModalHandler = function(_action) {
					$('#smcqp-container').customModal("closeModal");
					window.location.href = _action;
						
			},
			
			QuickView.prototype.processLink = function() {
				$('body').off('click', '.action.mailto.friend').on('click', '.action.mailto.friend', function (e){
					window.parent.location = $(this).attr('href');
				});
				
				$('body').off('click', '.action.tocart').on('click', '.action.tocart', function (e){
					var _that = $(this), _form = _that.parents('form') ;
					if (_form.length){
						_isValid = _form.valid();
						if (_isValid){
							_oldAction = _form.attr('action');
							_params = _form.serialize();
							var _action = _oldAction+_params;
							window.parent.location = _action;
						}
					}
					
				});
				
				$('body').off('click', '.action.tocompare, .action.towishlist').on('click', '.action.tocompare, .action.towishlist', function (e){
					e.preventDefault();
					var _self = this ;
					var _that = $(this), _dataPost = $.parseJSON(_that.attr('data-post'));
					if (_dataPost){
						var _formKey = $("input[name='form_key']").val();
						var _params = 'product=' + _dataPost.data.product + '&form_key=' + _formKey + '&uenc=' + _dataPost.data.uenc;
						var _action =  _dataPost.action+_params;
						 _dataPost.data.form_key = _formKey;
						if (window.self !== window.parent){
							 $.mage.dataPost().postData(_dataPost);
							setTimeout(function(){
								window.parent.ajaxQuickView.closeModalHandler(_action);
							},2000);
						}
					}
					
				});
			}
			
			$.fn.cartQuickView = function(options) {
				return $(this).data('cartQuickView', new QuickView(this, options));
			};
			
		}(jQuery));
	}
);
		