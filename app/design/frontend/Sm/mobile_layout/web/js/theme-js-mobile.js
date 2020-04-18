define([
        "jquery",
        "domReady!"
    ],
    function ($) {
        /**
         * Header index
         */
        if ($(".home-mobile").length) {
            $(".cms-index-index").addClass('mobile-header-top')
        }

        /**
         * Search mobile
         */
        $(".mb-search a").click(function () {
            $('body').addClass('search-active');
            $('.mb-header-device .block-search .input-text').focus();
        });

        $(".close-search").click(function () {
            $('body').removeClass('search-active');
        });

        $('.mb-header-device .block-search .input-text').blur(function () {
            setTimeout(function () {
                $('body').removeClass('search-active');
            }, 200);
        });

        /**
         * Remove header style fixed cms index
         */
        if ($(".home-mobile").length) {
            var wd = $(window);
            var menu_offset_top = 300;

            function processScroll() {
                var scrollTop = wd.scrollTop();
                if (scrollTop >= menu_offset_top) {
                    $('.cms-index-index').removeClass('mobile-header-top');
                } else if (scrollTop <= menu_offset_top) {
                    $('.cms-index-index').addClass('mobile-header-top');
                }
            }

            processScroll();
            wd.scroll(function () {
                processScroll();
            });
        }


        /**
         * Bottom sticky
         */

        $(".sticky-bottom .button-sticky-bottom").click(function () {
            $(".sticky-bottom .block-bottom").removeClass("active");
            $('html').removeClass("minicart-active")

            if ($(this).hasClass("active")) {
                $("#" + $(this).attr("data-drop")).removeClass("active");
                $(this).removeClass("active");
                $("html").removeClass("overflow-hidden");
                return;
            } else {
                $(".sticky-bottom .button-sticky-bottom").removeClass("active");
                $("#" + $(this).attr("data-drop")).toggleClass("active");
                $(this).addClass("active");
                if ($("#" + $(this).attr("data-drop")).hasClass("active")) {
                    $("html").addClass("overflow-hidden");
                } else {
                    $("html").removeClass("overflow-hidden");
                }
            }
        });

        $(".sticky-bottom .close-sticky-bottom").click(function () {
            var el = $(this).attr("data-drop");
            $("#" + el).removeClass("active");
            $(".sticky-bottom .button-sticky-bottom").removeClass("active");
            $("html").removeClass("overflow-hidden");
        });

        /**
         * Megamenu
         */

        $('.btn-submobile').click(function () {
            $(this).prev().slideToggle(200);
            $(this).toggleClass('btnsub-active');
            $(this).parent().toggleClass('parent-active');
        });

        /**
         * Minicart header
         */


        $(".mb-header-device .minicart-wrapper .action.showcart").click(function () {
            $(".sticky-bottom .button-sticky-bottom").removeClass("active");
            $(".block-bottom").removeClass("active");
            $("html").removeClass("overflow-hidden");

            $('html').toggleClass("minicart-active");
        });

        const $menu = $('.minicart-wrapper');

        $(document).on('click touchend', function (e) {
            if (!$menu.is(e.target) // if the target of the click isn't the container...
                && $menu.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $('html').removeClass("minicart-active");
            }
        });

        /**
         * Block footer
         */

        $(".footer-block .ft-mobile-block .block-title").click(function () {
            $(this).parent().toggleClass("active")
        });

    });