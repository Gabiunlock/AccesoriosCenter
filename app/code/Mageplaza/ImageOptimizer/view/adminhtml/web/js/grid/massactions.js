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
    'Magento_Ui/js/grid/massactions',
    'Magento_Ui/js/modal/confirm',
    'Mageplaza_ImageOptimizer/js/grid/button/optimize'
], function ($, Massactions, confirmation, imageOptimizer) {
    'use strict';

    return Massactions.extend({
        optimizeImage: function (action) {
            var selectedImages = this.getSelections().selected,
                collection     = {items: []},
                total          = this.getSelections().total,
                confirmMessage = $.mage.__('Too many images will take a long time to optimize. Are you sure you want to optimize the selected image(s)? (%1 record(s))').replace('%1', total);

            $.each(selectedImages, function (index, value) {
                collection.items[index] = {image_id: value};
            });

            imageOptimizer({
                url: action.url,
                collection: collection,
                confirmMessage: confirmMessage
            }).openConfirmModal();
        }
    });
});