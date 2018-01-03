function updateRangeInput( elem ) {
    $( elem ).next().val( $( elem ).val() );
}

function updateInputRange( elem ) {
    $( elem ).prev().val( $( elem ).val() );
}

if (typeof throttle !== "function") {
    // Source: https://gist.github.com/killersean/6742f98122d1207cf3bd
    function throttle(callback, limit, callingEvent) {
        var wait = false;
        return function() {
            if ( wait && $(window).scrollTop() > 0 ) {
                return;
            }
            callback.call(undefined, callingEvent);
            wait = true;
            setTimeout(function() {
                wait = false;
            }, limit);
        };
    }
}

/**
 * Plugin to handle dependencies
 *
 * @link https://github.com/miohtama/jquery-interdependencies
 *
 * jQuery Plugin Boilderplate
 *
 * @link https://github.com/jquery-boilerplate/jquery-boilerplate/wiki/Extending-jQuery-Boilerplate
 * @link https://john-dugan.com/jquery-plugin-boilerplate-explained/
 *
 * CodeStar Framework
 * The code for process dependencies from data attribute
 *
 * @link https://github.com/Codestar/codestar-framework/
 */
;(function ( $, window, document, undefined ) {

    var pluginName = "exopiteSofManageDependencies";

    // The actual plugin constructor
    function Plugin( element, options ) {

        this.element = element;
        this._name = pluginName;

        this.init();

    }

    Plugin.prototype = {

        init: function() {

            var base     = this;
                base.$el = $(this.element);
                base.el  = this.element;

            base.ruleset = $.deps.createRuleset();

            var config = {

                show: function( el ) {
                    el.removeClass( 'hidden' );
                },
                hide: function( el ) {
                    el.addClass( 'hidden' );
                },
                log: false,
                checkTargets: false

            };

            $.deps.enable( base.$el, base.ruleset, config );

            this.applyRules( base );
        },

        applyRules: function( base ) {

            base.$el.find('[data-controller]').each( function( el, index ) {

                var $this       = $(this),
                    _controller = $this.data('controller').split('|'),
                    _condition  = $this.data('condition').split('|'),
                    _value      = $this.data('value').toString().split('|'),
                    _rules      = base.ruleset;

                $.each(_controller, function(index, element) {

                    var value     = _value[index] || '',
                        condition = _condition[index] || _condition[0];
                    _rules = _rules.createRule('[data-depend-id="'+ element +'"]', condition, value);
                    _rules.include($this);

                });

            });

            base.ruleset.install();

        }
    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );

/*
 * Exopite Save Options with AJAX
 */
;(function ( $, window, document, undefined ) {

    var pluginName = "exopiteSaveOptionsAJAX";

    // The actual plugin constructor
    function Plugin( element, options ) {

        this.element = element;
        this._name = pluginName;
        this.$element = $( element );
        this.init();

    }

    Plugin.prototype = {

        init: function() {

            this.bindEvents();

        },

       // Bind events that trigger methods
        bindEvents: function() {
            var plugin = this;

            plugin.$element.find( '.exopite-sof-form-js' ).on( 'submit'+'.'+plugin._name, function( event ) {
                plugin.submitOptions.call( this, event );
            });

            $(window).on( 'scroll'+'.'+plugin._name, throttle(plugin.checkFixed, 100, ''));

        },

        // Unbind events that trigger methods
        unbindEvents: function() {
            this.$element.off('.'+this._name);
        },

        checkFixed: function() {

            var footerWidth = $( '.exopite-sof-form-js' ).outerWidth();
            var bottom = 0;

            if ( ( $( window ).scrollTop() > ( $( '.exopite-sof-header-js' ).position().top + $( '.exopite-sof-header-js' ).outerHeight(true) ) ) &&
                ($(window).scrollTop() + $(window).height() < $(document).height() - 100) ) {
                bottom = '0';
            } else {
                bottom = '-' + $( '.exopite-sof-footer-js' ).outerHeight() + 'px';
            }


            $( '.exopite-sof-footer-js' ).outerWidth( footerWidth );
            $( '.exopite-sof-footer-js' ).css({
                bottom: bottom,
            });

        },

        submitOptions: function ( event ) {

            event.preventDefault();
            var saveButtonString = $( this ).data( 'save' );
            var savedButtonString = $( this ).data( 'saved' );
            var $submitButtons = $( this ).find( '.exopite-sof-submit-button-js' );
            var currentButtonString = $submitButtons.val();
            var $ajaxMessage = $( this ).find( '.exopite-sof-ajax-message' );
            $submitButtons.val( saveButtonString ).attr( 'disabled', true );

            /*
             * Ajax save submit
             *
             * @link https://www.wpoptimus.com/434/save-plugin-theme-setting-options-ajax-wordpress/
             */
            $(this).ajaxSubmit({
                success: function(){
                    $submitButtons.val( currentButtonString ).attr( 'disabled', false );
                    $ajaxMessage.html( savedButtonString ).addClass('success show');
                    setTimeout(function(){
                        // $ajaxMessage.fadeOut( 400 );
                        $ajaxMessage.removeClass( 'show' );
                    }, 3000);
                },

                error: function( data ) {
                    console.log( 'data: ' + data );
                    $submitButtons.val( currentButtonString ).attr( 'disabled', false );
                    $ajaxMessage.html( 'Error! See console!' ).addClass('error show');
                },
            });
            return false;

        }

    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );

/*
 * Exopite Media Uploader
 */
;(function ( $, window, document, undefined ) {

    var pluginName = "exopiteMediaUploader";

    // The actual plugin constructor
    function Plugin( element, options ) {

        this.element = element;
        this._name = pluginName;
        this.$element = $( element );

        this._defaults = $.fn.exopiteMediaUploader.defaults;
        this.options = $.extend( {}, this._defaults, options );

        this.init();

    }

    Plugin.prototype = {

        init: function() {

            this.bindEvents();

        },

        // Bind events that trigger methods
        bindEvents: function() {
            var plugin = this;

            plugin.$element.find( '.button' ).on( 'click'+'.'+plugin._name, function( event ) {
                // this refer to the "[plugin-selector] .button" element
                plugin.openMediaUploader.call( this, event, plugin );
            });

            if ( plugin.options.remove !== undefined && plugin.options.input !== undefined && plugin.options.preview !== undefined ) {
                plugin.$element.find( plugin.options.remove ).on( 'click'+'.'+plugin._name, function( event ) {
                    // this refer to the "[plugin-selector] .button" element
                    plugin.removePreview.call( this, event, plugin );
                });
            }

        },

        openMediaUploader: function( event, plugin ) {

            event.preventDefault();

            /*
             * Open WordPress Media Uploader
             *
             * @link https://rudrastyh.com/wordpress/customizable-media-uploader.html
             */

            var button = $( this ),
            parent = button.closest( '.exopite-sof-media' ),
            isVideo = parent.hasClass( 'exopite-sof-video' ),
            mediaType = ( isVideo ) ? 'video' : 'image',
            custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    // uncomment the next line if you want to attach image to the current post
                    // uploadedTo : wp.media.view.settings.post.id,
                    type : mediaType
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function() { // it also has "open" and "close" events
                var attachment = custom_uploader.state().get('selection').first().toJSON();

                if ( plugin.options.input !== undefined ) {
                    parent.find( plugin.options.input ).val( attachment.url );
                }
                if ( ! isVideo && plugin.options.preview !== undefined ) {
                    parent.find( plugin.options.preview ).removeClass( 'hidden' );
                    parent.find( 'img' ).attr({ 'src': attachment.url });
                }
                if ( isVideo ) {
                    parent.find( 'video' ).attr({ 'src': attachment.url });
                }
                // $(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
                /* if you sen multiple to true, here is some code for getting the image IDs
                var attachments = frame.state().get('selection'),
                    attachment_ids = new Array(),
                    i = 0;
                attachments.each(function(attachment) {
                    attachment_ids[i] = attachment['id'];
                    console.log( attachment );
                    i++;
                });
                */
            })
            .open();

        },

        removePreview: function( event, plugin ) {

            var parent = plugin.$element;

            var previewWrapper = parent.find( plugin.options.preview );
            var previewImg = parent.find( 'img' );

            if ( previewWrapper.css('display') !== 'none' &&
                 previewImg.css('display') !== 'none'
               ) {
                previewWrapper.addClass( 'hidden' );
                previewImg.attr({ 'src': '' });
            }

            parent.find( plugin.options.input ).val( '' );
        }

    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );

/*
 * Exopite Options Navigation
 */
;(function ( $, window, document, undefined ) {

    /*
     * A jQuery Plugin Boilerplate
     *
     * https://github.com/johndugan/jquery-plugin-boilerplate/blob/master/jquery.plugin-boilerplate.js
     * https://john-dugan.com/jquery-plugin-boilerplate-explained/
     */

    var pluginName = "exopiteOptionsNavigation";

    // The actual plugin constructor
    function Plugin( element, options ) {

        this.element = element;
        this._name = pluginName;
        this.$element = $( element );
        this.init();

    }

    Plugin.prototype = {

        init: function() {

            this.bindEvents();

        },

       // Bind events that trigger methods
        bindEvents: function() {
            var plugin = this;

            plugin.$element.find( '.exopite-sof-nav-list-item' ).on( 'click'+'.'+plugin._name, function() {

                plugin.changeTab.call( plugin, $( this ) );

            });

        },

        // Unbind events that trigger methods
        unbindEvents: function() {
            this.$element.off('.'+this._name);
        },

        changeTab: function( botton ) {

            if ( ! botton.hasClass( 'active' ) ) {

                var section = '.exopite-sof-section-' + botton.data( 'section' );

                this.$element.find( '.exopite-sof-nav-list-item.active' ).removeClass( 'active' );

                botton.addClass( 'active' );

                this.$element.find( '.exopite-sof-section' ).addClass( 'hide' );
                this.$element.find( section ).removeClass( 'hide' );

            }

        }

    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );

;(function( $ ) {
    "use strict";

    $( document ).ready(function() {

        $( '.exopite-sof-wrapper' ).exopiteSofManageDependencies();

        $( '.exopite-sof-wrapper-menu' ).exopiteSaveOptionsAJAX();

        $( '.exopite-sof-media' ).exopiteMediaUploader({
            input: 'input',
            preview: '.exopite-sof-image-preview',
            remove: '.exopite-sof-image-remove'
        });

        $( '.exopite-sof-content-js' ).exopiteOptionsNavigation();

    });

}(jQuery));
