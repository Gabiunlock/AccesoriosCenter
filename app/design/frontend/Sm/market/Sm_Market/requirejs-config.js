var config = {
    map: {
        '*': {
            jquerypopper: "Sm_Market/js/bootstrap/popper",
            jquerybootstrap: "Sm_Market/js/bootstrap/bootstrap.min",
            owlcarousel: "Sm_Market/js/owl.carousel",
            jqueryfancyboxpack: "Sm_Market/js/jquery.fancybox.pack",
            jqueryfancyboxmedia: "Sm_Market/js/jquery.fancybox-media",
            jqueryunveil: "Sm_Market/js/jquery.unveil",
            yttheme: "Sm_Market/js/yttheme"
        }
    },
    shim: {
        'jquerypopper': {
            'deps': ['jquery'],
            'exports': 'Popper'
        },
        'jquerybootstrap': {
            'deps': ['jquery', 'jquerypopper']
        }
    },
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Sm_Market/js/swatch-renderer-mixin': true
            }
        }
    }
};