define(["jquery", "owlcarousel", "jqueryfancyboxpack", "jqueryfancyboxmedia"], function ($) {
    $(document).ready(function () {
        // Megamenu
        $('.sm_megamenu_menu > li > div').parent().addClass('parent-item');

        // Box full width
        var full_width = $('body').innerWidth();
        $('.full-content').css({'width': full_width});

        $(window).resize(function () {
            var full_width = $('body').innerWidth();
            $('.full-content').css({'width': full_width});
        });

        // Fix hover on IOS
        $('body').bind('touchstart', function () {
        });

        // Go to top
        $('#yt-totop').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

        $("#yt-totop-fix").hide();
        $(function () {
            var wh = $(window).height();
            var whtml = $(document).height();
            $(window).scroll(function () {
                if ($(this).scrollTop() > whtml / 10) {
                    $('#yt-totop-fix').fadeIn();
                } else {
                    $('#yt-totop-fix').fadeOut();
                }
            });
            $('#yt-totop-fix').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });
        });

        // FIX LOI IE INDEX 10

        function fixWidth() {
            if ($('.header-style-10').length && $('.home-page-10').length && full_width > 768) {
                var header_width = $('.header-wrapper > .container').width();
                $('#maincontent').css({'width': header_width + 60, 'display': 'block'});
                $('.header-wrapper').css({'width': header_width + 60, 'display': 'block'});
            }
        }

        fixWidth();

        $(window).resize(function () {
            fixWidth();
        });

        $('.play-video').fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            helpers: {
                media: {}
            }
        });

        // ABOUT US
        $('.aboutus-page .slider-ourmember').owlCarousel({
            pagination: false,
            center: false,
            nav: true,
            loop: false,
            margin: 30,
            navText: ['prev', 'next'],
            slideBy: 1,
            autoplay: false,
            autoplayTimeout: 2500,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            startPosition: 0,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        });


        $('.aboutus-page .slider-happy-client').owlCarousel({
            pagination: false,
            center: false,
            nav: true,
            loop: false,
            margin: 30,
            navText: ['prev', 'next'],
            slideBy: 1,
            autoplay: false,
            autoplayTimeout: 2500,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            startPosition: 0,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            }
        });
        // END ABOUT US

        // HOME PAGE 1
        $(".home-page-1 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-1 .home-collection .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 5
                },
                1200: {
                    items: 6
                }
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 0,
        });
        // END HOME PAGE 1

        // HOME PAGE 2

        $(".home-page-2 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-2 .clients-say-slider").owlCarousel({
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            },

            autoplay: false,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,
        });

        $(".home-page-2 .home-collection .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 0,
        });

        // END HOME PAGE 2

        // HOME PAGE 3

        $(".home-page-3 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-3 .clients-say-slider").owlCarousel({

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            },

            autoplay: false,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,

        });

        $(".home-page-3 .home-collection .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 0,
        });
        // END HOME PAGE 3

        // HOME PAGE 4
        $(".home-page-4 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-4 .home-collection .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 5
                },
                1200: {
                    items: 6
                }
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 0,
        });
        // END HOME PAGE 4

        // HOME PAGE 5
        $(".slidershow-header .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-5 .clients-say-slider").owlCarousel({

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            },

            autoplay: false,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,

        });

        $(".home-page-5 .brand-slider .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 5
                },
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplayHoverPause: true,
            margin: 18
        });

        $(".home-page-5 .slider-post").owlCarousel({
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 3
                },
            },
            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplayHoverPause: true,
            margin: 30
        });

        $(".home-page-5 .slider-deal-type2 .block-content-products").owlCarousel({
            items: 1,
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,
        });

        $(".home-page-5 .list-slider-1 .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                },
                1200: {
                    items: 1
                },
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplayHoverPause: true,
            margin: 30
        });

        $(".home-page-5 .list-slider-2 .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                },
                1200: {
                    items: 1
                },
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplayHoverPause: true,
            margin: 30
        });

        $(".home-page-5 .brand-slider .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 5
                },
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplayHoverPause: true,
            margin: 18
        });
        // END HOME PAGE 5

        // HOME PAGE 6
        $(".home-page-6 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-6 .slider-deal-type2 .block-content-products").owlCarousel({
            items: 1,
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,
        });

        $(".home-page-6 .slider-brand").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                991: {
                    items: 5
                },
                1200: {
                    items: 6
                }
            }
        });
        // END HOME PAGE 6

        // HOME PAGE 7
        $(".home-page-7 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-7 .slider-deal-type2 .block-content-products").owlCarousel({
            items: 1,
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,
        });

        $(".home-page-7 .home-collection .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 5
                },
                1200: {
                    items: 6
                }
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 0,
        });
        // END HOME PAGE 7

        // HOME PAGE 8
        $(".home-page-8 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-8 .list-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 10,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 2
                },
                991: {
                    items: 3
                },
                1200: {
                    items: 3
                }
            }
        });

        $(".home-page-8 .home-collection .owl-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 5
                },
                1200: {
                    items: 6
                }
            },

            autoplay: false,
            loop: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 0,
        });

        $(".home-page-8 .slider-deal-type2 .block-content-products").owlCarousel({
            items: 1,
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,
        });

        $(".home-page-8 .clients-say-slider").owlCarousel({
            nav: false,
            dots: true,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                991: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            }
        });

        $(".home-page-8 .slider-categories").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 2
                },
                991: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        });

        $(".home-page-8 .slider-brand").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                991: {
                    items: 5
                },
                1200: {
                    items: 6
                }
            }
        });
        // END HOME PAGE 8

        // HOME PAGE 9
        $(".home-9-style .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-9 .slider-brands").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                991: {
                    items: 5
                },
                1200: {
                    items: 6
                }
            }
        });

        $(".home-page-9 .collections").owlCarousel({
            nav: true,
            dots: false,
            margin: 10,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 6
                }
            }
        });
        // END HOME PAGE 9

        // HOME PAGE 10
        $(".home-page-10 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $('.home-page-10 .slider-brand').owlCarousel({
            margin: 35,
            nav: true,
            dots: false,
            loop: false,
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            }
        });

        $(".home-page-10 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 4
                }
            }
        });

        // END HOME PAGE 10

        // HOME PAGE 11
        $(".header-11-style.home-11-style .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-11 .products-slider .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            }
        });

        $(".home-page-11 .slider-brands").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                991: {
                    items: 5
                },
                1200: {
                    items: 6
                }
            }
        });
        // END HOME PAGE 11

        // HOME PAGE 12
        $(".home-12-style .sm-imageslider-content").owlCarousel({
            center: false,
            startPosition: 0,
            margin: 0,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 1
                }
            },

            nav: true,
            loop: true,
            dots: false,
            dotsSpeed: 1000,
            slideBy: 1,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            navSpeed: 1000,
            mouseDrag: true,
            responsiveRefreshRate: 100,
        });

        $(".home-page-12 .slider-1 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            }
        });

        setTimeout(function () {
            $(".home-page-12 .banner-slider").owlCarousel({
                nav: false,
                dots: true,
                margin: 0,

                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    991: {
                        items: 3
                    },
                    1200: {
                        items: 3
                    }
                }
            });
        }, 1000);

        $(".home-page-12 .slider-2 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 2
                },
                1200: {
                    items: 3
                }
            }
        });

        $(".home-page-12 .slider-3 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 2
                },
                1200: {
                    items: 3
                }
            }
        });

        $('.home-page-12 .slider-brand').owlCarousel({
            margin: 35,
            nav: true,
            dots: false,
            loop: true,
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            }
        });
        // END HOME PAGE 12

        // HOME PAGE 13
        $(".home-page-13 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-13 .slider-deal .block-content-slider").owlCarousel({
            nav: false,
            dots: true,
            margin: 5,
            stagePadding: 5,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                991: {
                    items: 2
                },
                1200: {
                    items: 2
                }
            }
        });

        $(".home-page-13 .slider-1 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 5,
            stagePadding: 5,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 2
                },
                1200: {
                    items: 3
                }
            }
        });

        $(".home-page-13 .slider-2 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 5,
            stagePadding: 5,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 2
                },
                1200: {
                    items: 3
                }
            }
        });

        $(".home-page-13 .slider-3 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 5,
            stagePadding: 5,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 4
                }
            }
        });
        // END HOME PAGE 13

        // HOME PAGE 14
        $(".home-page-14 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        setTimeout(function () {
            $(".home-page-14 .deal-slider-id14 .block-content-products").owlCarousel({
                autoplay: false,
                nav: true,
                dots: false,
                autoplaySpeed: 500,
                navSpeed: 500,
                dotsSpeed: 500,
                autoplayHoverPause: true,
                margin: 30,

                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 1
                    },
                    768: {
                        items: 1
                    },
                    991: {
                        items: 1
                    },

                    1366: {
                        items: 2
                    },

                    1920: {
                        items: 1
                    }
                },
            });
        }, 500);

        setTimeout(function () {
            $(".home-page-14 .slider-1 .block-content-slider").owlCarousel({
                nav: true,
                dots: false,
                stagePadding: 5,
                margin: 16,

                responsive: {
                    0: {
                        items: 1
                    },

                    480: {
                        items: 2
                    },

                    768: {
                        items: 3
                    },

                    991: {
                        items: 4
                    },

                    1200: {
                        items: 5
                    },

                    1500: {
                        items: 6
                    },

                    1800: {
                        items: 4
                    }
                }
            });
        }, 500);

        setTimeout(function () {
            $(".home-page-14 .slider-top-collection").owlCarousel({
                nav: true,
                dots: false,
                margin: 20,
                stagePadding: 2,

                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    991: {
                        items: 4
                    },
                    1200: {
                        items: 5
                    }
                }
            });
        }, 2000);

        setTimeout(function () {
            $(".home-page-14 .slider-2 .block-content-slider").owlCarousel({
                nav: true,
                dots: false,
                stagePadding: 5,
                margin: 16,

                responsive: {
                    0: {
                        items: 1
                    },

                    480: {
                        items: 2
                    },

                    768: {
                        items: 3
                    },

                    991: {
                        items: 4
                    },

                    1200: {
                        items: 4
                    },

                    1500: {
                        items: 5
                    },

                    1701: {
                        items: 6
                    }

                }
            });
        }, 500);

        setTimeout(function () {
            $(".home-page-14 .slider-3 .block-content-slider").owlCarousel({
                nav: true,
                dots: false,
                stagePadding: 5,
                margin: 16,

                responsive: {
                    0: {
                        items: 1
                    },

                    480: {
                        items: 2
                    },

                    768: {
                        items: 3
                    },

                    991: {
                        items: 4
                    },

                    1200: {
                        items: 4
                    },

                    1500: {
                        items: 5
                    },

                    1701: {
                        items: 6
                    }
                }
            });
        }, 500);

        setTimeout(function () {
            $(".home-page-14 .slider-brands-id14").owlCarousel({
                nav: true,
                dots: false,
                margin: 18,

                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    991: {
                        items: 6
                    },
                    1200: {
                        items: 7,
                        margin: 20,
                    },
                    1440: {
                        items: 8
                    }
                }
            });
        }, 1000);
        // END HOME PAGE 14

        // HOME PAGE 15
        $(".home-15-style .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-15 .slider-id15 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,
            stagePadding: 10,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 4
                }
            }
        });
        // END HOME PAGE 15

        // HOME PAGE 16
        $(".hot-cat-slider").owlCarousel({
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 8
                }
            },

            loop: false,
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 0,
        });

        $(".home-16-style .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        setTimeout(function () {
            $(".home-16-style .slider-categories").owlCarousel({
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 2
                    },
                    768: {
                        items: 4
                    },
                    992: {
                        items: 5
                    },
                    1200: {
                        items: 5
                    }
                },

                autoplay: true,
                loop: false,
                nav: false,
                dots: false,
                autoplaySpeed: 500,
                navSpeed: 500,
                dotsSpeed: 500,
                autoplayHoverPause: true,
                margin: 10,
            });
        }, 1000);

        $(".home-page-16 .deal-slider-id16 .block-content-products").owlCarousel({
            autoplay: false,
            nav: false,
            dots: true,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                991: {
                    items: 2
                },

                1366: {
                    items: 2
                },

                1920: {
                    items: 2
                }
            },
        });

        $(".home-page-16 .slider-1 .block-content-products").owlCarousel({
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 4
                },

                1200: {
                    items: 5
                },
            },
        });

        $(".home-page-16 .slider-2 .block-content-products").owlCarousel({
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                991: {
                    items: 1
                },

                1200: {
                    items: 1
                },
            },
        });

        $(".home-page-16 .slider-3 .block-content-products").owlCarousel({
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                991: {
                    items: 1
                },

                1200: {
                    items: 1
                },
            },
        });

        $(".home-page-16 .slider-4 .block-content-products").owlCarousel({
            autoplay: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                768: {
                    items: 1
                },
                991: {
                    items: 1
                },

                1200: {
                    items: 1
                },
            },
        });
        // END HOME PAGE 16

        // HOME PAGE 17
        $(".home-17-style .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-17-style .slider-deal .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 10,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        });
        // END HOME PAGE 17

        // HOME PAGE 18
        $(".home-page-18 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-18 .slider-1 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },

                480: {
                    items: 2
                },

                768: {
                    items: 3
                },

                991: {
                    items: 4
                },

                1200: {
                    items: 5
                },
            }
        });

        $(".home-page-18 .client-slider-fashion").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: false,
            dots: true,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 20,
        });

        $(".home-page-18 .slider-2 .block-content-slider").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },

                480: {
                    items: 2
                },

                768: {
                    items: 3
                },

                991: {
                    items: 4
                },

                1200: {
                    items: 5
                },
            }
        });
        // END HOME PAGE 18

        // HOME PAGE 19
        $(".home-page-19 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: true,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-19 .slider-categories").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,
            loop: true,

            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                991: {
                    items: 4
                },
                1200: {
                    items: 4
                }
            }
        });
        // END HOME PAGE 19

        // HOME PAGE 20
        $(".home-page-20 .sm-imageslider-content").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: true,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });
        // END HOME PAGE 20

        // HOME PAGE 21

        $(".header-style-21 .search-cart #search").focus(function () {
            $(".header-style-21 .search-cart .search-wrapper").addClass('active');
        }).blur(function () {
            $(".header-style-21 .search-cart .search-wrapper").removeClass('active');
        });

        $(".home-page-21 .slidershow .owl-carousel").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-21 .featured-products .owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },

                480: {
                    items: 2
                },

                768: {
                    items: 3
                },

                991: {
                    items: 4
                },

                1200: {
                    items: 5
                },
            }
        });

        $(".home-page-21 .client-wrapper .owl-carousel").owlCarousel({
            items: 1,
            autoplay: true,
            loop: false,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 30,
        });

        $(".home-page-21 .img-gallery").fancybox({
            openEffect: 'fade',
            closeEffect: 'fade'
        });

        // END HOME PAGE 21

        // HOME PAGE 22
        $(".home-page-22 .slidershow .owl-carousel").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-22 .slider-1 .owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },

                480: {
                    items: 2
                },

                768: {
                    items: 3
                },

                991: {
                    items: 4
                },

                1200: {
                    items: 4
                },
            }
        });

        $(".home-page-22 .slider-2 .owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 1
                },

                480: {
                    items: 2
                },

                768: {
                    items: 3
                },

                991: {
                    items: 4
                },

                1200: {
                    items: 4
                },
            }
        });
        // END HOME PAGE 22

        // HOME PAGE 23
        $(".home-page-23 .slidershow .owl-carousel").owlCarousel({
            items: 1,
            autoplay: true,
            loop: true,
            nav: true,
            dots: false,
            autoplaySpeed: 500,
            navSpeed: 500,
            dotsSpeed: 500,
            autoplayHoverPause: true,
            margin: 1,
        });

        $(".home-page-23 .cat-slider .owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            margin: 1,

            responsive: {
                0: {
                    items: 1
                },

                480: {
                    items: 2
                },

                768: {
                    items: 3
                },

                991: {
                    items: 4
                },

                1200: {
                    items: 5
                },
            }
        });

        $(".home-page-23 .brand-slider .owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            margin: 30,

            responsive: {
                0: {
                    items: 2
                },

                480: {
                    items: 3
                },

                768: {
                    items: 3
                },

                991: {
                    items: 4
                },

                1200: {
                    items: 7
                },
            }
        });

        $(".home-page-23 .gallery-slider .owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            margin: 2,

            responsive: {
                0: {
                    items: 1
                },

                480: {
                    items: 2
                },

                768: {
                    items: 3
                },

                991: {
                    items: 4
                },

                1200: {
                    items: 5
                },

                1600: {
                    items: 6
                },
            }
        });

        var owl_deal = $('.home-page-23 .slider-deal .owl-carousel');
        owl_deal.owlCarousel({
            nav: true,
            dots: false,
            margin: 1,

            responsive: {
                0: {
                    items: 1
                },

                480: {
                    items: 2
                },

                768: {
                    items: 3
                },

                991: {
                    items: 2
                },

                1200: {
                    items: 3
                },

                1600: {
                    items: 5
                },
            }
        });

        $('.home-page-23 .slider-deal .owl-next-trigger').click(function () {
            owl_deal.trigger('next.owl.carousel');
        });

        $('.home-page-23 .slider-deal .owl-prev-trigger').click(function () {
            owl_deal.trigger('prev.owl.carousel');
        });
        // END HOME PAGE 23

    });

});

