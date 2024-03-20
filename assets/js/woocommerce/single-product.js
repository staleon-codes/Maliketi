(function ($) {
    'use strict';

    var motta = motta || {};
    motta.init = function () {
        motta.$body = $(document.body),
            motta.$window = $(window),
            motta.$header = $('#site-header');

        this.fractionsProductSlider();

        // Product Layout
        this.singleProductV1();
        this.singleProductV2();
        this.singleProductV3();
        this.singleProductV4();
        this.singleProductV5();
        this.singleProductV6();

        this.productImageZoom();

        this.productVariation();
        this.productTabs();
        this.countDownHandler();

        this.relatedProductCarousel();
        this.upsellsProductCarousel();

        this.productDescriptionMore();

        this.productDegree();
        this.productVideoPopup();
        this.productFullScreen();

        this.updateFreeShippingBar();

        this.stickyHeaderCompact();
        this.fixedProductGallery();

        this.productExtraContent();

        this.reviewProduct();
    };

    /**
     * Product Thumbnails
     */
    motta.productThumbnails = function ( vertical ) {
        var $gallery = $('.woocommerce-product-gallery');

        $gallery.imagesLoaded(function () {
            var columns = $gallery.data('columns'),
            $thumbnail = $gallery.find('.flex-control-thumbs');

            $thumbnail.wrap('<div class="woocommerce-product-gallery__thumbs-carousel"></div>');
            $thumbnail.before('<span class="motta-svg-icon motta-thumbs-button-prev motta-swiper-button"><svg viewBox="0 0 19 32"><path d="M13.552 0.72l2.656 1.76-9.008 13.52 9.008 13.52-2.656 1.76-10.192-15.28z"></path></svg></span>');
            $thumbnail.after('<span class="motta-svg-icon motta-thumbs-button-next motta-swiper-button"><svg viewBox="0 0 19 32"><path d="M5.648 31.28l-2.656-1.76 9.008-13.52-9.008-13.52 2.656-1.76 10.192 15.28z"></path></svg></span>');
            $thumbnail.wrap('<div class="swiper-container" style="opacity:0"></div>');
            $thumbnail.addClass('swiper-wrapper');
            $thumbnail.find('li').addClass('swiper-slide');

            var options = {
                loop: false,
                autoplay: false,
                speed: 800,
                spaceBetween: 15,
                slidesPerView: columns,
                slidesPerGroup: 1,
                watchOverflow: true,
                navigation: {
                    nextEl: '.motta-thumbs-button-next',
                    prevEl: '.motta-thumbs-button-prev',
                },
                on: {
                    init: function () {
                        setTimeout(function () {
                            $thumbnail.parent().css('opacity', 1);
                            $thumbnail.css('opacity', 1);
                        }, 100);
                    }
                },
                breakpoints: {
                    300: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                        allowTouchMove: false,
                    },
                    768: {
                        slidesPerView: 4,
                    },
                    992: {
                        slidesPerView: columns,
                        spaceBetween: 15,
                    },
                }
            };

            if (vertical) {
                options.direction = 'vertical';
            } else {
                options.direction = 'horizontal';
            }

            new Swiper($thumbnail.parent().get(0), options);

            // Add an <span> to thumbnails for responsive bullets.
            $('li', $thumbnail).append('<span/>');
        });

    };

     /**
     * Product Image Zoom
     */
     motta.productImageZoom = function ( vertical ) {
        var $gallery = $('.woocommerce-product-gallery');

        if( mottaData.product_image_zoom == '1' ) {
            $gallery.find('.woocommerce-product-gallery__image').each(function () {
                motta.zoomSingleProductImage(this);
            });
        }
    };

    /**
     * Zoom an image.
     * Copy from WooCommerce single-product.js file.
     */
    motta.zoomSingleProductImage = function (zoomTarget) {
        if ( typeof wc_single_product_params == 'undefined' || ! $.fn.zoom ) {
            return;
        }

        var $target = $(zoomTarget),
            width = $target.width(),
            zoomEnabled = false;

        $target.each(function (index, target) {
            var $image = $(target).find('img');
            if ($image.data('large_image_width') > width) {
                zoomEnabled = true;
                return false;
            }
        });

        // Only zoom if the img is larger than its container.
        if (zoomEnabled) {
            var zoom_options = $.extend({
                touch: false
            }, wc_single_product_params.zoom_options);

            if ('ontouchstart' in document.documentElement) {
                zoom_options.on = 'click';
            }

            $target.trigger('zoom.destroy');
            $target.zoom(zoom_options);
        }
    }

    /**
     * Fractions Product Slider
     */
    motta.fractionsProductSlider = function () {
        var $gallery = $('.woocommerce-product-gallery__wrapper').children(),
            $total   = $gallery.length,
            $current = 0;

        $gallery.each( function ( key ) {
            $current = key + 1;
            $(this).find('a').append( '<span class="motta-product-slider__fractions">' + $current + '/' + $total + '</span>' );
        });
     }

    /**
     * Single Product V1
     */
     motta.singleProductV1 = function () {
        var $product = $('div.product').hasClass('layout-1');
        if ( ! $product ) {
            return;
        }
        $('.woocommerce-product-gallery').on('product_thumbnails_slider_horizontal wc-product-gallery-after-init', function(){
            motta.productThumbnails(false);
        });
    }

    /**
     * Single Product V2
     */
     motta.singleProductV2 = function () {
        var $product = $('div.product').hasClass('layout-2');
        if ( ! $product ) {
            return;
        }

        $('.woocommerce-product-gallery').on('product_thumbnails_slider_vertical wc-product-gallery-after-init', function(){
            motta.productThumbnails(true);
        });

    }

    /**
     * Single Product V3
     */
     motta.singleProductV3 = function () {
        var $product = $('div.product').hasClass('layout-3');
        if ( ! $product ) {
            return;
        }

        $('.woocommerce-product-gallery').on('product_thumbnails_slider_vertical wc-product-gallery-after-init', function(){
            motta.productThumbnails(true);
        });

        var $quantity = $( 'div.product .quantity input[name="quantity"]' );

        $quantity.closest( 'div.product' ).find( '.quantity__label-number' ).text( $quantity.val() );

        $quantity.on( 'change', function() {
            $(this).closest( 'div.product' ).find( '.quantity__label-number' ).text( $(this).val() );
        });

        $('.woocommerce-tabs:not(.wc-tabs-first--opened').find('.motta-dropdown__title').removeClass( 'active' );
    }

    /**
     * Single Product V4
     */
     motta.singleProductV4 = function () {
        var $product = $('div.product').hasClass('layout-4');
        if ( ! $product ) {
            return;
        }

        $('.woocommerce-product-gallery').on('product_thumbnails_slider_horizontal  wc-product-gallery-after-init', function(){
            motta.productThumbnails(false);
        });
        if( motta.$window.width() > 767 ) {
            $('.woocommerce-product-gallery').append($('.motta-product-gallery').find('.motta-product-images-buttons'));
        }
    }

    /**
     * Single Product V5
     */
     motta.singleProductV5 = function () {
        var $product = $('div.product').hasClass('layout-5');
        if ( ! $product ) {
            return;
        }

        $('.woocommerce-product-gallery').on('product_thumbnails_slider_horizontal wc-product-gallery-after-init', function(){
            motta.productThumbnails(false);
        });

        $('.woocommerce-tabs:not(.wc-tabs-first--opened').find('.motta-dropdown__title').removeClass( 'active' );
    }

    /**
     * Single Product V6
     */
     motta.singleProductV6 = function () {
        var $product = $('div.product').hasClass('layout-6');
        if ( ! $product ) {
            return;
        }

        $('.woocommerce-product-gallery').on('product_thumbnails_slider_vertical wc-product-gallery-after-init', function(){
            motta.productThumbnails(true);
        });
    }

    /**
     * Product Tabs
     */
    motta.productTabs = function () {
        var $product      = $('.single-product div.product'),
            $tabs         = $product.find('.woocommerce-tabs'),
            $idTab        = $tabs.find( '.motta-tabs-heading .active a' ).attr( 'href' );

        $( '.wc-tabs-wrapper ' + $idTab ).removeAttr('style');

        motta.$window.on( 'resize', function () {
            if ( mottaData.product_layout !== '3' && mottaData.product_layout !== '5' && mottaData.product_layout !== '6' ) {
                if( motta.$window.width() < 767 ) {
                    $product.addClass('product-wc-tabs-dropdown');
                } else {
                    $product.removeClass('product-wc-tabs-dropdown');
                    $product.find('.motta-dropdown__content').removeAttr('style');
                    $('.motta-dropdown__title').removeClass( 'active' );
                }
            }
        }).trigger('resize');

        $tabs.on('click', '.motta-dropdown__title', function (e) {
            e.preventDefault();

            if( ! $product.hasClass('product-wc-tabs-dropdown') ) {
                return;
            }

            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $(this).siblings('.motta-dropdown__content').stop().slideUp("slow");
            } else {
                $tabs.find('.motta-dropdown__title').removeClass('active');
                $tabs.find('.motta-dropdown__content').slideUp();
                $(this).addClass('active');
                $(this).siblings('.motta-dropdown__content').stop().slideDown("slow");
            }

        });

        $('.button-write-review').on( 'click', function (e) {
            e.preventDefault();

            if( ! $('.motta-dropdown__title.tab-title-reviews').hasClass( 'active' ) ) {
                $('.motta-dropdown__title.tab-title-reviews').trigger('click');
            }

            $('html, body').stop(true, true).animate({
                scrollTop: $( $(this).find('a').attr('href') ).offset().top - 150
            }, 300);
        });
    }

    /**
     * Product Variation
     */
    motta.productVariation = function () {
        var $price = $( '.single-product div.product .product-gallery-summary .variations-attribute-change .price' ).html(),
            $stock = $( '.single-product div.product .product-gallery-summary .variations-attribute-change .stock' ).clone(),
            $date_onsale_to = $( '.single-product div.product .product-gallery-summary .variations-attribute-change .woocommerce-badge--text' ).html();

        $('.single-product div.product .product-gallery-summary .variations_form').on( 'show_variation', function () {
            var $container          = $(this).closest( '.product-gallery-summary' ).find( '.variations-attribute-change' ),
                $price_new          = $(this).find( '.woocommerce-variation-price .price' ).html(),
                $stock_new          = $(this).find( '.woocommerce-variation-availability .stock' ).clone(),
                $variation_id       = $(this).find( '.variation_id' ).val(),
                $date_onsale_to_new = $(this).find( '.variation-id-' + $variation_id ).html();

            $container.find( '.price' ).html( $price_new );
            $container.find( '.stock' ).replaceWith( $stock_new );

            if( $date_onsale_to && $variation_id !== '0' ) {
                $container.find( '.woocommerce-badge--text' ).html( $date_onsale_to_new );
            }
        });

        $('.single-product div.product .product-gallery-summary .variations_form').on( 'hide_variation', function () {
            var $container = $(this).closest( '.product-gallery-summary' ).find( '.variations-attribute-change' );

            $container.find( '.price' ).html( $price );
            $container.find( '.stock' ).replaceWith( $stock );

            if( $date_onsale_to ) {
                $container.find( '.woocommerce-badge--text' ).html( $date_onsale_to );
            }
        });
    }

    /**
     * Related Product Carousel.
     */
     motta.relatedProductCarousel = function () {
        var $related = $('.products.related');

        if ( !$related.length ) {
            return;
        }

        var $products = $related.find('ul.products');

        $products.wrap('<div class="related-product__carousel"></div>');
        $products.after('<span class="motta-svg-icon swiper-button motta-swiper-button-prev motta-swiper-button"><svg viewBox="0 0 32 32"><path d="M20.58 2.58l2.84 2.84-10.6 10.58 10.6 10.58-2.84 2.84-13.4-13.42z"></path></svg></span>');
        $products.after('<span class="motta-svg-icon swiper-button motta-swiper-button-next motta-swiper-button"><svg viewBox="0 0 32 32"><path d="M11.42 29.42l-2.84-2.84 10.6-10.58-10.6-10.58 2.84-2.84 13.4 13.42z"></path></svg></span>');
        $products.after('<div class="swiper-pagination"></div>');
        $products.wrap('<div class="swiper-container linked-products-carousel" style="opacity: 0;"></div>');
        $products.addClass('swiper-wrapper');
        $products.find('li.product').addClass('swiper-slide');

        var $spaceBetween = $products.hasClass('product-card-layout-3') || $products.hasClass('product-card-layout-5') ? 24 : 0;

        var options = {
            loop: false,
            autoplay: false,
            speed: 800,
            watchSlidesVisibility: true,
            watchOverflow: true,
            slidesPerView: 5,
            navigation: {
                nextEl: $related.find('.motta-swiper-button-next'),
                prevEl: $related.find('.motta-swiper-button-prev'),
            },
            pagination: {
                el: $related.find('.swiper-pagination'),
                type: 'bullets',
                clickable: true,
            },
            on: {
                init: function () {
                    this.$el.css('opacity', 1);
                }
            },
            spaceBetween: 0,
            breakpoints: {
                300: {
                    slidesPerView: mottaData.mobile_product_columns == '' ? 2 : mottaData.mobile_product_columns,
                    slidesPerGroup: mottaData.mobile_product_columns == '' ? 2 : mottaData.mobile_product_columns,
                    spaceBetween: 16,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: $spaceBetween,
                },
                1200: {
                    slidesPerView: 5,
                    spaceBetween: $spaceBetween,
                }
            }
        };

        new Swiper( $related.find('.linked-products-carousel'), options );
    }

    /**
     * Upsells Product Carousel.
     */
     motta.upsellsProductCarousel = function () {
        var $upsells = $('.products.upsells');

        if ( !$upsells.length ) {
            return;
        }

        var $products = $upsells.find('ul.products');

        $products.wrap('<div class="upsells-product__carousel"></div>');
        $products.after('<span class="motta-svg-icon swiper-button motta-swiper-button-prev motta-swiper-button"><svg viewBox="0 0 32 32"><path d="M20.58 2.58l2.84 2.84-10.6 10.58 10.6 10.58-2.84 2.84-13.4-13.42z"></path></svg></span>');
        $products.after('<span class="motta-svg-icon swiper-button motta-swiper-button-next motta-swiper-button"><svg viewBox="0 0 32 32"><path d="M11.42 29.42l-2.84-2.84 10.6-10.58-10.6-10.58 2.84-2.84 13.4 13.42z"></path></svg></span>');
        $products.after('<div class="swiper-pagination"></div>');
        $products.wrap('<div class="swiper-container linked-products-carousel" style="opacity: 0;"></div>');
        $products.addClass('swiper-wrapper');
        $products.find('li.product').addClass('swiper-slide');

        var $spaceBetween = $products.hasClass('product-card-layout-3') || $products.hasClass('product-card-layout-5') ? 24 : 0;

        var options = {
            loop: false,
            autoplay: false,
            speed: 800,
            watchSlidesVisibility: true,
            watchOverflow: true,
            navigation: {
                nextEl: $upsells.find('.motta-swiper-button-next'),
                prevEl: $upsells.find('.motta-swiper-button-prev'),
            },
            pagination: {
                el: $upsells.find('.swiper-pagination'),
                type: 'bullets',
                clickable: true,
            },
            on: {
                init: function () {
                    this.$el.css('opacity', 1);
                }
            },
            spaceBetween: $spaceBetween,
            breakpoints: {
                300: {
                    slidesPerView: mottaData.mobile_product_columns == '' ? 2 : mottaData.mobile_product_columns,
                    slidesPerGroup: mottaData.mobile_product_columns == '' ? 2 : mottaData.mobile_product_columns,
                    spaceBetween: 16,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: $spaceBetween,
                },
                1200: {
                    slidesPerView: 5,
                    spaceBetween: $spaceBetween,
                }
            }
        };

        new Swiper($upsells.find('.linked-products-carousel'), options);
    }

    /**
	 * CountDown
	 */
	motta.countDownHandler = function () {
		$('.motta-single-product-sale').find('.motta-countdown').motta_countdown();
	};

    motta.productDescriptionMore = function() {
        var $content = $('div.product').find('.short-description__content');

        if( ! $content.length ) {
            return;
        }

        var    scrollHeight = $content[0].scrollHeight,
            clientheight = $content[0].clientHeight;
        if( scrollHeight > clientheight ) {
            $('div.product').find('.short-description__more').show();
        }
        $('div.product').on('click', '.short-description__more', function(e) {
			e.preventDefault();

			var $settings = $(this).data( 'settings' ),
				$more     = $settings.more,
				$less     = $settings.less,
                $description = $(this).closest( '.short-description' );

			if(  $description.hasClass( 'activate' ) ) {
				$description.removeClass( 'activate' );
				$(this).text( $more )
			} else {
				$description.addClass( 'activate' );
				$(this).text( $less )
			}
		});
    }

    /**
     * Show product 360 degree
     */
    motta.productDegree = function () {
        var $product_degrees = $('.motta-product-gallery .motta-button--degree');

        if ( $product_degrees.length < 1 ) {
            return;
        }

        if ( mottaData.product_degree.length < 1 ) {
            return;
        }
        var degree = '',
            $pswp = $('#product-degree-pswp');

        $product_degrees.on('click', function (e) {
            e.preventDefault();

            if ($pswp.hasClass('init')) {
                return;
            }

            $pswp.addClass('init');

            var imgArray = mottaData.product_degree.split(','),
                images = [];

            for (var i = 0; i < imgArray.length; i++) {
                images.push(imgArray[i]);
            }

            degree = $pswp.find('.motta-product-gallery-degree').ThreeSixty({
                totalFrames: images.length, // Total no. of image you have for 360 slider
                endFrame: images.length, // end frame for the auto spin animation
                currentFrame: 1, // This the start frame for auto spin
                imgList: $pswp.find('.product-degree__images'), // selector for image list
                progress: '.motta-gallery-degree__spinner', // selector to show the loading progress
                imgArray: images, // path of the image assets
                height: 500,
                width: 830,
                navigation: false
            });

            $pswp.on('click', '.nav-bar__run', function () {
                $(this).addClass('active');
                degree.play();
            });

            $pswp.on('click', '.nav-bar__run.active', function () {
                $(this).removeClass('active');
                degree.stop();
            });

            $pswp.on('click', '.nav-bar__next', function () {
                degree.stop();
                $('.nav-bar__run').removeClass('active');
                degree.next();
            });

            $pswp.on('click', '.nav-bar__prev', function () {
                degree.stop();
                $('.nav-bar__run').removeClass('active');
                degree.previous();
            });

            $pswp.on('click', '.modal__button-close, .modal__backdrop', function () {
                degree.stop();
                $('.nav-bar__run').removeClass('active');
            });
        });
    };

    /**
     * Init product video
     */
    motta.productVideoPopup = function () {
        var $video_icon = $('.motta-product-gallery').find('.motta-button--video');
        if ($video_icon.length < 1) {
            return;
        }

        var options = {
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 300,
            preloader: false,
            fixedContentPos: false,
            iframe: {
                markup: '<div class="mfp-iframe-scaler">' +
                        '<div class="mfp-close"></div>' +
                        '<iframe class="mfp-iframe" frameborder="0" allow="autoplay"></iframe>' +
                        '</div>',
                patterns: {
                    youtube: {
                        index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

                        id: 'v=', // String that splits URL in a two parts, second part should be %id%
                        src: 'https://www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe.
                    },
                    youtu: {
                        index: 'youtu.be/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

                        id: '/', // String that splits URL in a two parts, second part should be %id%
                        src: 'https://www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe.
                    },
                    vimeo: {
                        index: 'vimeo.com/',
                        id: '/',
                        src: '//player.vimeo.com/video/%id%?autoplay=1'
                    }
                },

                srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
            }
        };

        $video_icon.magnificPopup(options);
    };

    /**
     * Full Screen
     */
    motta.productFullScreen = function () {
        $( '.woocommerce-product-gallery__image a' ).on( 'click', function (e) {
            e.preventDefault();
        });

        $( '.motta-button--product-lightbox' ).on( 'click', function() {
            if( $( '.woocommerce-product-gallery__trigger' ).length > 0 ) {
                $( '.woocommerce-product-gallery__trigger' ).trigger( 'click' );
            } else {
                $('.flex-viewport .woocommerce-product-gallery__image.flex-active-slide a').trigger( 'click' );
            }
        });
    }

    motta.updateFreeShippingBar = function() {
        $( document.body ).on( 'removed_from_cart', function( e, response ) {
            if( $('.single-product div.product').find('.motta-free-shipping-bar').length && $(response['div.widget_shopping_cart_content']).length ) {
               if( $(response['div.widget_shopping_cart_content']).find('.motta-free-shipping-bar').length  ) {
                    $('.single-product div.product').find('.motta-free-shipping-bar').replaceWith($(response['div.widget_shopping_cart_content']).find('.motta-free-shipping-bar'));
               } else {
                    $('.single-product div.product').find('.motta-free-shipping-bar').hide();
               }
            }
        } );

        $( document.body ).on( 'added_to_cart', function( e, response ) {
            if( $('.single-product div.product').find('.motta-free-shipping-bar').length && $(response['div.widget_shopping_cart_content']).length && $(response['div.widget_shopping_cart_content']).find('.motta-free-shipping-bar').length ) {
                $('.single-product div.product').find('.motta-free-shipping-bar').replaceWith($(response['div.widget_shopping_cart_content']).find('.motta-free-shipping-bar'));
            }

        } );
    }

    motta.stickyHeaderCompact = function() {
        if (!motta.$body.hasClass('mobile-header-compact')) {
            return;
        }

        if( motta.$window.width() > 767 ) {
            return;
        }

        var $headerCompact = motta.$body.find('.product-header-compact');
		if ( ! $headerCompact.length) {
			return;
		}

        motta.$window.on('scroll', function () {
            var scroll 		= motta.$window.scrollTop(),
                scrollTop 		= $headerCompact.outerHeight(true);
            if (scroll > scrollTop) {
				$headerCompact.find('.product-sticky-header').addClass('minimized');
            } else {
				$headerCompact.find('.product-sticky-header').removeClass('minimized');
            }
        } );
    }

    motta.fixedProductGallery = function() {
        if (!motta.$body.hasClass('mobile-fixed-product-gallery')) {
            return;
        }
        var $productGallery = $('div.product .woocommerce-product-gallery');
        motta.$window.on('resize', function () {
			if( motta.$window.width() > 767 ) {
				$productGallery.removeClass('has-scroll');
				$productGallery.removeAttr('style');
			} else {
				$productGallery.addClass('has-scroll');
			}
		}).trigger('resize');

        var noticeHeight = $('.single-product').find('.woocommerce-notices-wrapper').outerHeight(true);
        if( noticeHeight > 0 ) {
            if( motta.$body.hasClass('admin-bar') ) {
                noticeHeight += 46;
            }

            $('.mobile-fixed-product-gallery.admin-bar div.product .woocommerce-product-gallery').css({top: noticeHeight + 10})
        }

        setTimeout(function () {
            var imageHeight = $('.woocommerce-product-gallery .woocommerce-product-gallery__image > a').height(),
                imageWidth = $('.woocommerce-product-gallery .woocommerce-product-gallery__image > a').width(),
                ratio = imageHeight && imageWidth ? imageHeight/imageWidth : 0;
            if( ratio && ratio !== 1 ) {
                $('.product-fixed-gallery-spacing').css( '--motta-product-fixed-gallery-spacing', (ratio * 100).toFixed(2) + 'vw' );
            }
        }, 200);

        motta.$window.on('scroll', function () {
            if( ! $productGallery.hasClass('has-scroll')) {
                return;
            }
            var wScrollTop = motta.$window.scrollTop(),
                galleryHeight = $productGallery.outerHeight(true),
                opacityValue = 0;
            if (wScrollTop > 0) {
                if( galleryHeight < wScrollTop ) {
                    opacityValue = 0;
                } else {
                    opacityValue = (galleryHeight - wScrollTop) / galleryHeight;
                }
            } else {
                opacityValue = 1;
            }

            $('div.product .woocommerce-product-gallery').css({opacity: opacityValue});

        }).trigger('scroll');
    }

    motta.productExtraContent = function() {
        var $productExtra = motta.$body.find('.single-product-extra-content');
		if ( ! $productExtra.length) {
			return;
		}

        $productExtra.on('click', '.motta-icon-box-widget__button', function(e){
            var href = $(this).attr('href');
            if( $('.woocommerce-tabs').find('a[href=' + href + ']').length && $('.woocommerce-tabs').find( href ).length ) {
                e.preventDefault();
                var $tab = $(href),
                    offTop = 20;
                if( motta.$body.hasClass('admin-bar') ) {
                    offTop += 32;
                }

                if( $('#motta-sticky-add-to-cart').length ) {
                    offTop += $('#motta-sticky-add-to-cart').outerHeight(true);
                } else if( motta.$body.hasClass('.motta-header-sticky') ) {
                    offTop += motta.$body.find('.header-sticky').outerHeight(true);
                }
                setTimeout( function () {
                    $( 'html,body' ).stop().animate({
                        scrollTop: $tab.offset().top - offTop
                    },
                    'slow' );
                }, 400 );

                $('.woocommerce-tabs').find('a[href=' + href + ']').trigger('click');
            }
        })
    }

	    /**
	 * Handle product reviews
	 */
	motta.reviewProduct = function () {
		setTimeout(function () {
			var $hash = window.location.hash;
			$('#respond p.stars a').append('<span class="motta-svg-icon"><svg width="24" height="24" aria-hidden="true" role="img" focusable="false" viewBox="0 0 32 32"><path d="M16 1.333l3.467 11.2h11.2l-9.067 6.933 3.467 11.2-9.067-6.933-9.067 6.933 3.467-11.2-9.067-6.933h11.2z"></path></svg></span>');

			if ($hash.toLowerCase().indexOf("comment-") >= 0 || $hash === "#reviews" || $hash === "#tab-reviews") {
				$('.motta-dropdown__title.tab-title-reviews').trigger('click');
                if( $( $hash ).length ) {
                    $('html, body').animate({
                        scrollTop: $( $hash ).offset().top - 150
                    }, 300);
                }

			}
		}, 100);
	};

    /**
     * Document ready
     */
    motta.init();

})(jQuery);