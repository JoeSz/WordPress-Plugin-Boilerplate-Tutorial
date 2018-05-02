function updateRangeInput( elem ) {
    jQuery( elem ).next().val( jQuery( elem ).val() );
}

function updateInputRange( elem ) {
    jQuery( elem ).prev().val( jQuery( elem ).val() );
}

if (typeof throttle !== "function") {
    // Source: https://gist.github.com/killersean/6742f98122d1207cf3bd
    function throttle(callback, limit, callingEvent) {
        var wait = false;
        return function() {
            if ( wait && jQuery(window).scrollTop() > 0 ) {
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
 * CodeStar Framework
 * The code for process dependencies from data attribute
 *
 * @link https://github.com/Codestar/codestar-framework/
 */
;(function ( $, window, document, undefined ) {
      'use strict';
    /*
     * Dependency System
     *
     * Codestar Framework
     */
    $.fn.exopiteSofManageDependencies = function ( param ) {
        return this.each(function () {

            var base  = this,
                $this = $(this);

            base.init = function () {

                base.ruleset = $.deps.createRuleset();
                base.param = ( param !== undefined ) ? base.param = param + '-' : '';

                var cfg = {
                    show: function( el ) {
                        el.removeClass('hidden');
                    },
                    hide: function( el ) {
                        el.addClass('hidden');
                    },
                    log: false,
                    checkTargets: false

                };

                base.dep();

                $.deps.enable( $this, base.ruleset, cfg );

            };

            base.dep = function() {

                $this.each( function() {

                    $(this).find( '[data-' + base.param + 'controller]' ).each( function() {

                        var $this       = $( this ),
                            _controller = $this.data( base.param + 'controller' ).split( '|' ),
                            _condition  = $this.data( base.param + 'condition' ).split( '|' ),
                            _value      = $this.data( base.param + 'value' ).toString().split( '|' ),
                            _rules      = base.ruleset;

                        $.each( _controller, function( index, element ) {

                            var value     = _value[index] || '',
                                condition = _condition[index] || _condition[0];

                            _rules = _rules.createRule( '[data-' + base.param + 'depend-id="'+ element +'"]', condition, value );
                            _rules.include( $this );

                        });

                    });

                });

            };

            base.init();

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

/*
 * Exopite SOF Repeater
 */
;(function ( $, window, document, undefined ) {

    /*
     * A jQuery Plugin Boilerplate
     *
     * https://github.com/johndugan/jquery-plugin-boilerplate/blob/master/jquery.plugin-boilerplate.js
     * https://john-dugan.com/jquery-plugin-boilerplate-explained/
     */

    var pluginName = "exopiteSOFRepeater";

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
            this.updateTitle();

        },

       // Bind events that trigger methods
        bindEvents: function() {
            var plugin = this;

            plugin.$element.find( '.exopite-sof-cloneable--add' ).off().on( 'click'+'.'+plugin._name, function(e) {

                e.preventDefault();
                if ( $( this ).is(":disabled") ) return;
                plugin.addNew.call( plugin, $( this ) );

            });

            plugin.$element.on( 'click'+'.'+plugin._name, '.exopite-sof-cloneable--remove:not(.disabled)', function(e) {

                e.preventDefault();
                plugin.remove.call( plugin, $( this ) );

            });

            plugin.$element.find( '.exopite-sof-cloneable__item' ).on('input', '[data-title=title]', function(event) {

                plugin.updateTitle();

            });

        },

        // Unbind events that trigger methods
        unbindEvents: function() {
            this.$element.off('.'+this._name);
        },

        remove: function( $button ) {

            $button.parents( '.exopite-sof-cloneable__item' ).remove();
            this.checkAmount();
            this.updateNameIndex();
            $button.trigger('exopite-sof-field-group-item-removed');
        },

        checkAmount: function() {

            var numItems = this.$element.find( '.exopite-sof-cloneable__wrapper' ).children( '.exopite-sof-cloneable__item' ).length;
            var maxItems = this.$element.data( 'limit' );

            if ( maxItems <= numItems ) {
                this.$element.find( '.exopite-sof-cloneable--add' ).attr("disabled", true);
                return false;
            } else {
                this.$element.find( '.exopite-sof-cloneable--add' ).attr("disabled", false);
                return true;
            }


        },

        updateTitle: function() {

            this.$element.find( '.exopite-sof-cloneable__wrapper' ).find( '.exopite-sof-cloneable__item' ).each(function(index, el) {
                var title = $( el ).find( '[data-title=title]' ).val();
                $( el ).find( '.exopite-sof-cloneable__text' ).text( title );
                $( el ).trigger('exopite-sof-field-group-item-title-updated');
            });

        },

        updateNameIndex: function() {

            var fieldParentName = this.$element.find( '.exopite-sof-cloneable__wrapper' ).data( 'name' );
            var regex = new RegExp(/\[(.*?)\]\[(.*?)\]\[(.*?)\]/, "i");

            this.$element.find( '.exopite-sof-cloneable__wrapper' ).find( '.exopite-sof-cloneable__item' ).each(function(index, el) {

                $( el ).find( '[name^="' + fieldParentName + '"]' ).attr( 'name', function () {

                    return this.name.replace(regex, function ($0, $1, $2, $3) {
                        return '[' + $1 + '][' + index + '][' + $3 + ']';
                    });
                });

            });

        },

        addNew: function() {

            var $group = this.$element.parents( '.exopite-sof-field-group' );

            // var numItems = this.$element.find( '.exopite-sof-cloneable__wrapper' ).children( '.exopite-sof-cloneable__item' ).length;

            if ( $.fn.chosen ) $group.find("select.chosen").chosen("destroy");

            var $muster = this.$element.find( '.exopite-sof-cloneable__muster' );
            var $cloned = $muster.clone( true );

            /**
             * Get hidden "muster" element and clone it. Remove hidden muster classes.
             * Finaly append to group.
             * After that need to initilaize components like:
             * - ACE Editor
             * - Choosen
             * - Date picker
             * - Dependencies
             */
            $cloned.find( '.exopite-sof-cloneable--remove' ).removeClass( 'disabled' );
            $cloned.removeClass( 'exopite-sof-cloneable__muster' );
            $cloned.removeClass( 'exopite-sof-cloneable__muster--hidden' );
            $cloned.removeClass( 'exopite-sof-accordion--hidden' );
            $cloned.find( '[disabled]' ).attr('disabled', false);
            $cloned.trigger('exopite-sof-field-group-item-added-before');

            /**
             * Deal with ACE Editor.
             * ACE Editor need a field ID and ID has to be unique.
             * So we need to update ID to the next one.
             */
            var hasACE = false;
            if( $cloned.find( '.exopite-sof-ace-editor' ).length !== 0 ) {
                hasACE = true;
                console.log( 'has ace editor' );
                var lastACEEditor = $group.find( '.exopite-sof-ace-editor' ).last().attr( 'id' );
                var lastID = lastACEEditor.substr( lastACEEditor.length - 1 );
                var nextACEEditorID = lastACEEditor.substr( 0, lastACEEditor.length - 1 ) + ( ++lastID);
                $cloned.find( '.exopite-sof-ace-editor' ).attr( 'id', nextACEEditorID );
            }

            $group.find( '.exopite-sof-cloneable__wrapper' ).append( $cloned );

            // Initialize ACE Editor
            if ( hasACE ) $cloned.find( '.exopite-sof-field-ace_editor' ).exopiteSofFieldACEEditor();

            this.checkAmount();
            this.updateNameIndex();

            // If has choosen, initilize it.
            if ( $.fn.chosen ) $group.find("select.chosen").chosen({width:"300px"});

            // If has date picker, initilize it.
            $cloned.find( '.datepicker' ).each(function(index, el) {
                var dateFormat = $( el ).data( 'format' );
                $( el ).removeClass('hasDatepicker').datepicker( { 'dateFormat': dateFormat } );
            });

            // $cloned.exopiteSofManageDependencies({modifier: 'sub-'});

            // Handle dependencies.
            $cloned.exopiteSofManageDependencies( 'sub' );

            $cloned.trigger('exopite-sof-field-group-item-added-after');
        },

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
 * Exopite SOF Accordion
 */
;(function ( $, window, document, undefined ) {

    var pluginName = "exopiteSOFAccordion";

    // The actual plugin constructor
    function Plugin( element, options ) {

        this.element = element;
        this._name = pluginName;
        this.$element = $( element );
        this.isSortableCalled = false;
        this.init();

    }

    Plugin.prototype = {

        init: function() {

            this.bindEvents();

            if ( this.$element.data('sortable') ) {

                /**
                 * Make accordion items sortable.
                 *
                 * https://jqueryui.com/sortable/
                 * http://api.jqueryui.com/sortable/
                 */
                this.$element.sortable({
                    axis: "y",
                    cursor: "move",
                });
                this.$element.disableSelection();
            }

        },

       // Bind events that trigger methods
        bindEvents: function() {
            var plugin = this;

            plugin.$element.off().on( 'click' + '.' + plugin._name, '.exopite-sof-accordion__title', function(e) {
                e.preventDefault();
                plugin.toggleAccordion.call( plugin, $( this ) );

            });

            /**
             * Need to "reorder" name elements for metabox,
             * so it is saved in the order of displayed.
             */
            // Call function if sorting is stopped
            plugin.$element.on('sortstop' + '.' + plugin._name, function () {

                // If it is a metabox (not a plugin options)
                if ( plugin.$element.parents('.exopite-sof-wrapper-metabox').length && plugin.$element.data('sortable') ) {
                    // Need to reorder name index, make sure, saved in the wanted order in meta
                    plugin.$element.find('.exopite-sof-accordion__item').each(function (index_item) {
                        $(this).find('[name^="' + plugin.$element.data('name') + '"]').each(function () {
                            var $this_name = $(this).attr('name');
                            // Get name "prefix" from parent
                            var $name_prefix = plugin.$element.data('name');
                            // Escape square brackets
                            $name_prefix = $name_prefix.replace(/\[/g, '\\[').replace(/]/g, '\\]');
                            var regex = new RegExp($name_prefix + '\\[\\d+\\]');
                            // Generate name to replace based on the parent item
                            var $this_name_updated = $this_name.replace(regex, plugin.$element.data('name') + '\[' + index_item + '\]');
                            // Update
                            $(this).attr('name', $this_name_updated);
                        });
                    });
                }

                // Stop next click after reorder
                // @link https://stackoverflow.com/questions/947195/jquery-ui-sortable-how-can-i-cancel-the-click-event-on-an-item-thats-dragged/19858331#19858331
                plugin.isSortableCalled = true;

            });

        },

        // Unbind events that trigger methods
        unbindEvents: function() {
            this.$element.off('.'+this._name);
        },

        toggleAccordion: function( $header ) {

            var $this = $header.parent( '.exopite-sof-accordion__item' );

            // To prevent unwanted click trigger after sort (drag and drop)
            if ( this.isSortableCalled ) {
                this.isSortableCalled = false;
                return;
            }

            if ( $this.hasClass('exopite-sof-accordion--hidden' ) ) {
                $this.find( '.exopite-sof-accordion__content' ).slideDown(350, function(){
                    $this.removeClass( 'exopite-sof-accordion--hidden' );
                });
            } else {
                $this.find( '.exopite-sof-accordion__content' ).slideUp(350, function(){
                    $this.addClass( 'exopite-sof-accordion--hidden' );
                });

            }

        },

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

    var pluginName = "exopiteImportExportAJAX";

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

            plugin.$element.find( '.exopite-sof-import-js' ).off().on( 'click'+'.'+plugin._name, function( event ) {

                event.preventDefault();
                if ( $( this ).hasClass( 'loading' ) ) return;

                swal({

                    // title: "Are you sure?",
                    text: $( this ).data( 'confirm' ),
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,

                }).then(( willImport ) => {

                    if ( willImport ) {

                        $( this ).addClass('loading');
                        $( this ).prop( "disabled", true );
                        this.disabled = true;
                        plugin.importOptions.call( this, event, plugin );

                    }

                });

                // if ( confirm( $( this ).data( 'confirm' ) ) ) {

                //     plugin.importOptions.call( this, event, plugin );

                // }

            });

            plugin.$element.find( '.exopite-sof-reset-js' ).off().on( 'click'+'.'+plugin._name, function( event ) {

                event.preventDefault();

                swal({

                    // title: "Are you sure?",
                    text: $( this ).data( 'confirm' ),
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,

                }).then(( willDelete ) => {

                    if (willDelete) {

                        $( this ).addClass('loading');
                        $( this ).prop( "disabled", true );
                        plugin.resetOptions.call( this, event, plugin );

                    }

                });

            });

        },

        // Unbind events that trigger methods
        unbindEvents: function() {
            this.$element.off('.'+this._name);
        },

        importOptions: function ( event, plugin ) {

            var AJAXData = plugin.$element.find( '.exopite-sof--data' );

            $.ajax({
                url: AJAXData.data( 'admin' ),
                type: 'post',
                data: {
                    action: 'exopite-sof-import-options',
                    unique: AJAXData.data( 'unique' ),
                    value: plugin.$element.find( '.exopite-sof__import' ).val(),
                    wpnonce: AJAXData.data( 'wpnonce' )
                },
                success: function( response ) {

                    if ( response == 'success' ) {

                        plugin.$element.find( '.exopite-sof__import' ).val( '' );
                        swal({
                            icon: "success",
                        });
                        location.reload();

                    }

                },
                error: function( xhr, status, error ) {

                    console.log( 'Status: ' + xhr.status );
                    console.log( 'Error: ' + xhr.responseText );
                    swal( "Error!", "Check console for more info!", "error" );

                }
            });

            return false;

        },

        resetOptions: function ( event, plugin ) {

            var AJAXData = plugin.$element.find( '.exopite-sof--data' );

            $.ajax({
                url: AJAXData.data( 'admin' ),
                type: 'post',
                data: {
                    action: 'exopite-sof-reset-options',
                    unique: AJAXData.data( 'unique' ),
                    wpnonce: AJAXData.data( 'wpnonce' )
                },
                success: function( response ) {

                    if ( response == 'success' ) {

                        swal({
                            icon: "success",
                        });
                        location.reload();

                    }

                },

                error: function( xhr, status, error ) {
                    console.log( 'Status: ' + xhr.status );
                    console.log( 'Error: ' + xhr.responseText );
                    swal( "Error!", "Check console for more info!", "error" );
                }
            });

            return false;

        },

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
        $( '.exopite-sof-sub-dependencies' ).exopiteSofManageDependencies( 'sub' );

        $( '.exopite-sof-wrapper-menu' ).exopiteSaveOptionsAJAX();

        $( '.exopite-sof-media' ).exopiteMediaUploader({
            input: 'input',
            preview: '.exopite-sof-image-preview',
            remove: '.exopite-sof-image-remove'
        });

        $( '.exopite-sof-content-js' ).exopiteOptionsNavigation();

        $( '.exopite-sof-group' ).exopiteSOFRepeater();
        $( '.exopite-sof-accordion__wrapper' ).exopiteSOFAccordion();

        $( '.exopite-sof-field-backup' ).exopiteImportExportAJAX();

    });

}(jQuery));
