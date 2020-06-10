/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_ImageOptimizer
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal'
], function ($, confirmation, alert) {
    "use strict";
    var btnOptimize      = $('#optimize_image'),
        isStopBtnClicked = false;

    $.widget('mageplaza.imageoptimizer', {
        options: {
            index: 0,
            confirmMessage: $.mage.__('Too many images will take a long time to optimize. Are you sure you want to optimize all images?')
        },

        _create: function () {
            this.initListener();
        },

        initListener: function () {
            var self = this;

            btnOptimize.on('click', function () {
                if (self.options.isEnabled === '0') {
                    alert({
                        title: $.mage.__('Optimize Image'),
                        content: $.mage.__('The module has been disabled.')
                    });

                    return;
                }
                self.openConfirmModal();
            });
        },

        openConfirmModal: function () {
            var collection = this.options.collection.items;

            if (collection.length > 0) {
                this.getConfirmModal();
            } else {
                alert({
                    title: $.mage.__('Optimize Image'),
                    content: $.mage.__('You need to scan all images before starting optimization process.')
                });
            }
        },

        getConfirmModal: function () {
            var self = this;

            confirmation({
                title: $.mage.__('Optimize Image'),
                content: this.options.confirmMessage,
                actions: {
                    confirm: function () {
                        var processModal = $('#mpimageoptimizer-modal');

                        processModal.modal({
                            'type': 'popup',
                            'title': $.mage.__('Optimize Image'),
                            'responsive': true,
                            'modalClass': 'mpimageoptimizer-modal-popup',
                            'buttons': [
                                {
                                    text: $.mage.__('Stop'),
                                    class: 'action-stop-optimize',
                                    click: function () {
                                        isStopBtnClicked = true;
                                        confirmation({
                                            content: $.mage.__('Are you sure you want to stop optimizing images?'),
                                            actions: {
                                                confirm: function () {
                                                    location.reload();
                                                },
                                                cancel: function () {
                                                    isStopBtnClicked = false;
                                                    self.loadAjax();
                                                }
                                            }
                                        });
                                    }
                                },
                                {
                                    text: $.mage.__('Close'),
                                    class: 'action-close-optimize',
                                    click: function () {
                                        location.reload();
                                    }
                                }
                            ]
                        });
                        processModal.modal('openModal');
                        self.optimizeImage();
                    }
                }
            });
        },

        optimizeImage: function () {
            this.options.index = 0;

            this.loadAjax();
        },

        loadAjax: function () {
            if (isStopBtnClicked) {
                return;
            }

            var self              = this,
                collection        = this.options.collection.items,
                contentProcessing = $('.mpimageoptimizer-modal-content-processing'),
                item              = collection[this.options.index],
                collectionLength  = collection.length,
                percent           = 100 * (this.options.index + 1) / collectionLength;

            if (this.options.index >= collectionLength) {
                contentProcessing.text($.mage.__('Image optimization completed'));
                $('button.action-stop-optimize').hide();
                $('button.action-close-optimize').show();

                return;
            }
            contentProcessing.text(
                $.mage.__('Processing (%1/%2)')
                .replace('%1', this.options.index + 1)
                .replace('%2', collectionLength));
            this.options.index++;

            return $.ajax({
                url: this.options.url,
                data: {image_id: item.image_id}
            }).done(function (data) {
                self.getContent(percent, data.path, data.status);
                self.loadAjax();
            }).fail(function (data) {
                self.getContent(percent, data.path, data.status);
                self.loadAjax();
            });
        },

        getContent: function (percent, path, status) {
            var progressBar  = $('#progress-bar-optimize'),
                modalPercent = $('#mpimageoptimizer-modal-percent'),
                modalContent = $('#mpimageoptimizer-modal-content');

            progressBar.width(percent.toFixed(2) + '%');
            modalPercent.text(percent.toFixed(2) + '%');
            modalContent.append('<p>' + path + ': ' + '<strong>' + status + '</strong>' + '</p>');
        }
    });

    return $.mageplaza.imageoptimizer;
});