var config = {
    map: {
        '*': {

            customModal: 'Sm_CartQuickPro/js/custom-modal',
            quickView: 'Sm_CartQuickPro/js/custom-quickview',
            ajaxCart: 'Sm_CartQuickPro/js/custom-addtocart',
            addToCart: 'Sm_CartQuickPro/js/custom-msrp',
            sidebar: 'Sm_CartQuickPro/js/custom-sidebar',
            compareItems: 'Sm_CartQuickPro/js/custom-compare',
            wishlist: 'Sm_CartQuickPro/js/custom-wishlist'
        }
    },
    deps: [
        'Magento_Catalog/js/catalog-add-to-cart',
        'Magento_Msrp/js/msrp'
    ]
};