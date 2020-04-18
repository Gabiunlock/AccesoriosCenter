/**
 *
 * SM CartQuickPro - Version 1.4.0
 * Copyright (c) 2017 YouTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: YouTech Company
 * Websites: http://www.magentech.com
 */
 
define(['jquery',  'Magento_Ui/js/modal/modal'],
	function($) {
		"use strict";
		$.widget('cartQuickPro.customModal', $.mage.modal, {
			 options: {
				clsModalPopup: '.smcqp-modal-popup',
				clsInerWrap: '.smcqp-modal-popup .modal-inner-wrap',
				clsLoadMark: '#smcqp-container .loading-mask',
				idContents: '#smcqp-contents',
				idReport: '#smcqp-report'
			 },
			_init: function() {
				this._super();
			},
			openModal: function() {
				var _self = this , _options = _self.options;
				var _el = $('.smcqp-modal-popup');
				$(_options.clsInerWrap).removeClass("smcqp-options report-messages");
				$(_options.clsInerWrap).addClass("smcqp-loading");
				$(_options.clsLoadMark).show();
				$(_options.idContents).html('').hide();
				return this._super();
			},
			closeModal: function () {
				var _self = this , _options = _self.options;
				$(_options.idContents).html('').hide();
				$(_options.idReport).hide();
				if(typeof window.ajaxCart !== "undefined" && typeof window.ajaxCart.options.timeCountDown !== "undefined"){
					  window.clearTimeout(window.ajaxCart.options.timeCountDown);
					  window.ajaxCart.options.timeCountDown = 0;
				}
				return this._super();
			},
		});
    return $.cartQuickPro.customModal;
});
		