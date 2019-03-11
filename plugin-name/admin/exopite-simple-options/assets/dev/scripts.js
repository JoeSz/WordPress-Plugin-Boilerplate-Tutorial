function updateRangeInput(elem) {
    jQuery(elem).next().val(jQuery(elem).val());
}

function updateInputRange(elem) {
    jQuery(elem).prev().val(jQuery(elem).val());
}

if (typeof throttle !== "function") {
    // Source: https://gist.github.com/killersean/6742f98122d1207cf3bd
    function throttle(callback, limit, callingEvent) {
        var wait = false;
        return function () {
            if (wait && jQuery(window).scrollTop() > 0) {
                return;
            }
            callback.call(undefined, callingEvent);
            wait = true;
            setTimeout(function () {
                wait = false;
            }, limit);
        };
    }
}

/**
 * Get url parameter in jQuery
 * @link https://stackoverflow.com/questions/19491336/get-url-parameter-jquery-or-how-to-get-query-string-values-in-js/25359264#25359264
 */
; (function ($, window, document, undefined) {
    $.urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        }
        else {
            return decodeURI(results[1]) || 0;
        }
    };
})(jQuery, window, document);

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
; (function ($, window, document, undefined) {
    'use strict';
    /*
     * Dependency System
     *
     * Codestar Framework
     */
    $.fn.exopiteSofManageDependencies = function (param) {
        return this.each(function () {

            var base = this,
                $this = $(this);

            base.init = function () {

                base.ruleset = $.deps.createRuleset();
                base.param = (param !== undefined) ? base.param = param + '-' : '';

                var cfg = {
                    show: function (el) {
                        el.removeClass('hidden');
                    },
                    hide: function (el) {
                        el.addClass('hidden');
                    },
                    log: false,
                    checkTargets: false

                };

                base.dep();

                $.deps.enable($this, base.ruleset, cfg);

            };

            base.dep = function () {

                $this.each(function () {

                    $(this).find('[data-' + base.param + 'controller]').each(function () {

                        var $this = $(this),
                            _controller = $this.data(base.param + 'controller').split('|'),
                            _condition = $this.data(base.param + 'condition').split('|'),
                            _value = $this.data(base.param + 'value').toString().split('|'),
                            _rules = base.ruleset;

                        $.each(_controller, function (index, element) {

                            var value = _value[index] || '',
                                condition = _condition[index] || _condition[0];

                            _rules = _rules.createRule('[data-' + base.param + 'depend-id="' + element + '"]', condition, value);
                            _rules.include($this);

                        });

                    });

                });

            };

            base.init();

        });

    };

})(jQuery, window, document);

/*
 * Exopite Save Options with AJAX
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteSaveOptionsAJAX";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.init();

    }

    Plugin.prototype = {

        init: function () {

            this.bindEvents();

        },

        // Bind events that trigger methods
        bindEvents: function () {
            var plugin = this;

            plugin.$element.find('.exopite-sof-form-js').on('submit' + '.' + plugin._name, function (event) {
                plugin.submitOptions.call(this, event);
            });

            /**
             * Save on CRTL+S
             * @link https://stackoverflow.com/questions/93695/best-cross-browser-method-to-capture-ctrls-with-jquery/14180949#14180949
             */
            $(window).on('keydown' + '.' + plugin._name, function (event) {

                if (plugin.$element.find('.exopite-sof-form-js').length) {
                    if (event.ctrlKey || event.metaKey) {
                        switch (String.fromCharCode(event.which).toLowerCase()) {
                            case 's':
                                event.preventDefault();
                                var $form = plugin.$element.find('.exopite-sof-form-js');
                                plugin.submitOptions.call($form, event);
                                break;
                        }
                    }
                }
            });

            $(window).on('scroll' + '.' + plugin._name, throttle(plugin.checkFixed, 100, ''));

        },

        // Unbind events that trigger methods
        unbindEvents: function () {
            this.$element.off('.' + this._name);
        },

        checkFixed: function () {

            var footerWidth = $('.exopite-sof-form-js').outerWidth();
            var bottom = 0;

            if (($(window).scrollTop() > ($('.exopite-sof-header-js').position().top + $('.exopite-sof-header-js').outerHeight(true))) &&
                ($(window).scrollTop() + $(window).height() < $(document).height() - 100)) {
                bottom = '0';
            } else {
                bottom = '-' + $('.exopite-sof-footer-js').outerHeight() + 'px';
            }


            $('.exopite-sof-footer-js').outerWidth(footerWidth);
            $('.exopite-sof-footer-js').css({
                bottom: bottom,
            });

        },

        submitOptions: function (event) {

            event.preventDefault();
            var saveButtonString = $(this).data('save');
            var savedButtonString = $(this).data('saved');
            var $submitButtons = $(this).find('.exopite-sof-submit-button-js');
            var currentButtonString = $submitButtons.val();
            var $ajaxMessage = $(this).find('.exopite-sof-ajax-message');
            $submitButtons.val(saveButtonString).attr('disabled', true);

            if (typeof tinyMCE != 'undefined') {
                tinyMCE.triggerSave();
            }

            /**
             * Ajax save submit
             *
             * @link https://www.wpoptimus.com/434/save-plugin-theme-setting-options-ajax-wordpress/
             */
            $(this).ajaxSubmit({
                success: function () {
                    $submitButtons.val(currentButtonString).attr('disabled', false);
                    $ajaxMessage.html(savedButtonString).addClass('success show');
                    $submitButtons.blur();
                    setTimeout(function () {
                        // $ajaxMessage.fadeOut( 400 );
                        $ajaxMessage.removeClass('show');
                    }, 3000);
                },

                error: function (data) {
                    $submitButtons.val(currentButtonString).attr('disabled', false);
                    $ajaxMessage.html('Error! See console!').addClass('error show');
                },
            });
            return false;

        }

    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

/*
 * Exopite Media Uploader
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteMediaUploader";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);

        this._defaults = $.fn.exopiteMediaUploader.defaults;
        this.options = $.extend({}, this._defaults, options);

        this.init();

    }

    Plugin.prototype = {

        init: function () {

            this.bindEvents();

        },

        // Bind events that trigger methods
        bindEvents: function () {
            var plugin = this;

            plugin.$element.find('.button').on('click' + '.' + plugin._name, function (event) {
                // this refer to the "[plugin-selector] .button" element
                plugin.openMediaUploader.call(this, event, plugin);
            });

            if (plugin.options.remove !== undefined && plugin.options.input !== undefined && plugin.options.preview !== undefined) {
                plugin.$element.find(plugin.options.remove).on('click' + '.' + plugin._name, function (event) {
                    // this refer to the "[plugin-selector] .button" element
                    plugin.removePreview.call(this, event, plugin);
                });
            }

        },

        openMediaUploader: function (event, plugin) {

            event.preventDefault();

            /*
             * Open WordPress Media Uploader
             *
             * @link https://rudrastyh.com/wordpress/customizable-media-uploader.html
             */

            var button = $(this),
                parent = button.closest('.exopite-sof-media'),
                isVideo = parent.hasClass('exopite-sof-video'),
                mediaType = (isVideo) ? 'video' : 'image',
                custom_uploader = wp.media({
                    title: 'Insert image',
                    library: {
                        // uncomment the next line if you want to attach image to the current post
                        // uploadedTo : wp.media.view.settings.post.id,
                        type: mediaType
                    },
                    button: {
                        text: 'Use this image' // button label text
                    },
                    multiple: false // for multiple image selection set to true
                }).on('select', function () { // it also has "open" and "close" events
                    var attachment = custom_uploader.state().get('selection').first().toJSON();

                    if (plugin.options.input !== undefined) {
                        parent.find(plugin.options.input).val(attachment.url);
                    }
                    if (!isVideo && plugin.options.preview !== undefined) {
                        parent.find(plugin.options.preview).removeClass('hidden');
                        parent.find('img').attr({ 'src': attachment.url });
                    }
                    if (isVideo) {
                        parent.find('video').attr({ 'src': attachment.url });
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

        removePreview: function (event, plugin) {

            var parent = plugin.$element;

            var previewWrapper = parent.find(plugin.options.preview);
            var previewImg = parent.find('img');

            if (previewWrapper.css('display') !== 'none' &&
                previewImg.css('display') !== 'none'
            ) {
                previewWrapper.addClass('hidden');
                previewImg.attr({ 'src': '' });
            }

            parent.find(plugin.options.input).val('');
        }

    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

/*
 * Exopite Options Navigation
 */
; (function ($, window, document, undefined) {

    /*
     * A jQuery Plugin Boilerplate
     *
     * https://github.com/johndugan/jquery-plugin-boilerplate/blob/master/jquery.plugin-boilerplate.js
     * https://john-dugan.com/jquery-plugin-boilerplate-explained/
     */

    var pluginName = "exopiteOptionsNavigation";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.init();

    }

    Plugin.prototype = {

        init: function () {

            this.bindEvents();

        },

        // Bind events that trigger methods
        bindEvents: function () {
            var plugin = this;

            plugin.onLoad.call(plugin);

            plugin.$element.find('.exopite-sof-nav-list-item').on('click' + '.' + plugin._name, function () {

                plugin.changeTab.call(plugin, $(this));

            });

            plugin.$element.find('.exopite-sof-nav-list-parent-item > .exopite-sof-nav-list-item-title').on('click' + '.' + plugin._name, function () {

                plugin.toggleSubMenu.call(plugin, $(this));

            });

        },

        // Unbind events that trigger methods
        unbindEvents: function () {
            this.$element.off('.' + this._name);
        },

        toggleSubMenu: function (button) {
            // var $parent = button;
            var $parent = button.parents('.exopite-sof-nav-list-parent-item');
            $parent.toggleClass('active').find('ul').slideToggle(200);
        },
        changeTab: function (button) {

            if (!button.hasClass('active')) {

                var section = '.exopite-sof-section-' + button.data('section');

                this.$element.find('.exopite-sof-nav-list-item.active').removeClass('active');

                button.addClass('active');

                this.$element.find('.exopite-sof-section').addClass('hide');
                this.$element.find(section).removeClass('hide');

            }

        },

        onLoad: function () {
            var plugin = this;

            /**
             * "Sanitize" URL
             * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/encodeURIComponent
             */
            var URLSection = encodeURIComponent($.urlParam('section'));

            // If section not exist, then return
            if (!plugin.$element.find('.exopite-sof-section-' + URLSection).length) return false;

            var navList = plugin.$element.find('.exopite-sof-nav-list-item');
            plugin.$element.find('.exopite-sof-section').addClass('hide');
            plugin.$element.find('.exopite-sof-section-' + URLSection).removeClass('hide');
            navList.removeClass('active');
            navList.each(function (index, el) {
                var section = $(el).data('section');
                if (section == URLSection) {
                    $(el).addClass('active');
                }
            });
        },

    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

/**
 * Exopite SOF Repeater
 */
; (function ($, window, document, undefined) {

    /**
     * A jQuery Plugin Boilerplate
     *
     * https://github.com/johndugan/jquery-plugin-boilerplate/blob/master/jquery.plugin-boilerplate.js
     * https://john-dugan.com/jquery-plugin-boilerplate-explained/
     */

    var pluginName = "exopiteSOFRepeater";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.init();

    }

    Plugin.prototype = {

        init: function () {

            this.bindEvents();
            this.updateTitle();

        },

        // Bind events that trigger methods
        bindEvents: function () {
            var plugin = this;

            plugin.$element.find('.exopite-sof-cloneable--add').off().on('click' + '.' + plugin._name, function (e) {

                e.preventDefault();
                if ($(this).is(":disabled")) return;
                plugin.addNew.call(plugin, $(this));

            });

            plugin.$element.on('click' + '.' + plugin._name, '.exopite-sof-cloneable--remove:not(.disabled)', function (e) {

                e.preventDefault();
                plugin.remove.call(plugin, $(this));

            });

            plugin.$element.on('click' + '.' + plugin._name, '.exopite-sof-cloneable--clone:not(.disabled)', function (e) {

                e.preventDefault();
                plugin.addNew.call(plugin, $(this));

            });

            plugin.$element.find('.exopite-sof-cloneable__item').on('input change blur', '[data-title=title]', function (event) {

                plugin.updateTitle();

            });

        },

        // Unbind events that trigger methods
        unbindEvents: function () {
            this.$element.off('.' + this._name);
        },

        remove: function ($button) {

            $button.parents('.exopite-sof-cloneable__item').remove();
            this.checkAmount();
            this.updateNameIndex();
            $button.trigger('exopite-sof-field-group-item-removed');
        },

        checkAmount: function () {

            var numItems = this.$element.find('.exopite-sof-cloneable__wrapper').children('.exopite-sof-cloneable__item').length;
            var maxItems = this.$element.data('limit');

            if (maxItems <= numItems) {
                this.$element.find('.exopite-sof-cloneable--add').attr("disabled", true);
                return false;
            } else {
                this.$element.find('.exopite-sof-cloneable--add').attr("disabled", false);
                return true;
            }


        },

        updateTitle: function () {

            this.$element.find('.exopite-sof-cloneable__wrapper').find('.exopite-sof-cloneable__item').each(function (index, el) {
                var title = $(el).find('[data-title=title]').val();
                $(el).find('.exopite-sof-cloneable__text').text(title);
                $(el).trigger('exopite-sof-field-group-item-title-updated');
            });

        },
        updateNameIndex: function () {

            var fieldParentName = this.$element.find('.exopite-sof-cloneable__wrapper').data('name').replace("[REPLACEME]", "");
            // test if multilang (and option stored in array)
            var regex_multilang_select = new RegExp(/\[(.*?)\]\[(.*?)\]\[(.*?)\]\[(.*?)\[(.*?)\]/, "i");
            var regex_multilang = new RegExp(/\[(.*?)\]\[(.*?)\]\[(.*?)\]\[(.*?)\]/, "i");
            // test if not multilang (and option stored in array)
            var regex_array = new RegExp(/\[(.*?)\]\[(.*?)\]\[(.*?)\]/, "i");
            // test if metabox and option stored in separate meta values
            var regex_simple = new RegExp(/\[(.*?)\]\[(.*?)\]/, "i");

            this.$element.find('.exopite-sof-cloneable__wrapper').find('.exopite-sof-cloneable__item').each(function (index, el) {

                $(el).find('[name^="' + fieldParentName + '"]').attr('name', function () {

                    if (regex_multilang_select.test(this.name)) {
                        return this.name.replace(regex_multilang, function ($0, $1, $2, $3, $4, $5) {
                            // options[en][group][0][field][]
                            /*
                            var index_item_second = $2;
                            var index_item_third = $3;
                            if ($2 == 'REPLACEME') {
                                index_item_second = index;
                            }
                            if ($3 == 'REPLACEME') {
                                index_item_third = index;
                            }
                            var index_item = ($3 == 'REPLACEME') ? index : $3;
                            */
                            return '[' + $1 + '][' + $2 + '][' + index + '][' + $4 + '][' + $5 + ']';
                        });
                    }

                    if (regex_multilang.test(this.name)) {
                        return this.name.replace(regex_multilang, function ($0, $1, $2, $3, $4) {
                            // options[en][group][0][field]
                            // options[group][0][field][] (options array no multilang and select)
                            var index_item_second = $2;
                            var index_item_third = $3;
                            /*
                            if ($2 == 'REPLACEME') {
                                index_item_second = index;
                            }
                            if ($3 == 'REPLACEME') {
                                index_item_third = index;
                            }
                            // var index_item = ($3 == 'REPLACEME') ? index : $3;
                            */
                            if (!$4 || 0 === $4.length) {
                                index_item_second = index;
                            } else {
                                index_item_third = index;
                            }
                            return '[' + $1 + '][' + index_item_second + '][' + index_item_third + '][' + $4 + ']';
                        });
                    }
                    if (regex_array.test(this.name)) {
                        return this.name.replace(regex_array, function ($0, $1, $2, $3) {
                            // options[group][0][field]
                            // group[0][emails_group_callback][] (simple and select)
                            var index_item_first = $1;
                            var index_item_second = $2;
                            if (!$3 || 0 === $3.length) {
                                index_item_first = index;
                            } else {
                                index_item_second = index;
                            }
                            // if ($1 == 'REPLACEME') {
                            //     index_item_first = index;
                            // }
                            // if ($2 == 'REPLACEME') {
                            //     index_item_second = index;
                            // }
                            // var index_item = ($2 == 'REPLACEME') ? index : $2;
                            return '[' + index_item_first + '][' + index_item_second + '][' + $3 + ']';
                        });
                    }
                    if (regex_simple.test(this.name)) {
                        return this.name.replace(regex_simple, function ($0, $1, $2) {
                            //options[0][field]
                            // var index_item = ($1 == 'REPLACEME') ? index : $1;
                            return '[' + index + '][' + $2 + ']';
                        });
                    }


                });

            });

        },

        addNew: function ($element) {

            var $group = this.$element.parents('.exopite-sof-field-group');

            if ($.fn.chosen) $group.find("select.chosen").chosen("destroy");

            var is_cloned = false;
            var $cloned = null;
            if ($element.hasClass('exopite-sof-cloneable--clone')) {
                $cloned = $element.parents('.exopite-sof-cloneable__item').clone(true);
                is_cloned = true;
            } else {
                var $muster = this.$element.find('.exopite-sof-cloneable__muster');
                $cloned = $muster.clone(true);
            }

            /**
             * Get hidden "muster" element and clone it. Remove hidden muster classes.
             * Add trigger before and after (for various programs, like TinyMCE, Trumbowyg, etc...)
             * Finaly append to group.
             */
            $cloned.find('.exopite-sof-cloneable--remove').removeClass('disabled');
            $cloned.find('.exopite-sof-cloneable--clone').removeClass('disabled');
            $cloned.removeClass('exopite-sof-cloneable__muster');
            $cloned.removeClass('exopite-sof-cloneable__muster--hidden');
            $cloned.removeClass('exopite-sof-accordion--hidden');
            $cloned.find('[disabled]').attr('disabled', false);

            this.$element.trigger('exopite-sof-field-group-item-added-before', [$cloned, $group]);

            if (is_cloned) {
                $cloned.insertAfter($element.parents('.exopite-sof-cloneable__item'));
            } else {
                $group.find('.exopite-sof-cloneable__wrapper').append($cloned);
            }

            $cloned.insertAfter($element.parents('.exopite-sof-cloneable__item') );

            this.checkAmount();
            this.updateNameIndex();

            // If has choosen, initilize it.
            if ($.fn.chosen) $group.find("select.chosen").chosen({ width: "375px" });

            // If has date picker, initilize it.
            $cloned.find('.datepicker').each(function (index, el) {
                var dateFormat = $(el).data('format');
                $(el).removeClass('hasDatepicker').datepicker({ 'dateFormat': dateFormat });
            });

            // Handle dependencies.
            $cloned.exopiteSofManageDependencies('sub');
            $cloned.find('.exopite-sof-cloneable__content').removeAttr("style").show();

            this.$element.trigger('exopite-sof-field-group-item-added-after', [$cloned, $group]);
        },

    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

/*
 * Exopite Save Options with AJAX
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteSOFTinyMCE";

    // The actual plugin constructor
    function Plugin(element, options) {

        if (typeof tinyMCE == 'undefined') return;

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.init();

    }

    Plugin.prototype = {

        init: function () {

            var plugin = this;

            tinyMCE.init({
                theme: 'modern',
                plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
                quicktags: true,
                tinymce: true,
                branding: false,
                media_buttons: true,
            });

            plugin.initTinyMCE();

            plugin.$element.on('exopite-sof-accordion-sortstart', function (event, $sortable) {
                $sortable.find('.tinymce-js').not(':disabled').each(function () {
                    tinyMCE.execCommand('mceRemoveEditor', false, $(this).attr('id'));
                });
            });

            plugin.$element.on('exopite-sof-accordion-sortstop', function (event, $sortable) {
                $sortable.find('.tinymce-js').not(':disabled').each(function () {
                    tinyMCE.execCommand('mceAddEditor', true, $(this).attr('id'));
                });
            });

            var $group = plugin.$element.parents('.exopite-sof-field-group');

            plugin.$element.on('exopite-sof-field-group-item-added-after', function (event, $cloned) {

                $cloned.find('.tinymce-js').each(function (index, el) {
                    var nextEditorID = plugin.musterID + (parseInt($group.find('.tinymce-js').not(':disabled').length) - 1);
                    $(el).attr('id', nextEditorID);
                    tinyMCE.execCommand('mceAddEditor', true, nextEditorID);
                });

            });

        },

        initTinyMCE: function () {
            var plugin = this;
            plugin.musterID = plugin.$element.find('.exopite-sof-cloneable__muster .tinymce-js').first().attr('id') + '-';

            plugin.$element.find('.tinymce-js').not(':disabled').each(function (index, el) {
                $(this).attr('id', plugin.musterID + index);
                var fullId = $(this).attr('id');

                tinyMCE.execCommand('mceAddEditor', true, fullId);

            });

        },


    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

/**
 * Exopite SOF Accordion
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteSOFAccordion";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.$container = $(element).find('.exopite-sof-accordion__wrapper').first();
        this.isSortableCalled = false;
        this.init();

    }

    Plugin.prototype = {

        init: function () {

            this.bindEvents();

            if (this.$container.data('sortable')) {

                /**
                 * Make accordion items sortable.
                 *
                 * https://jqueryui.com/sortable/
                 * http://api.jqueryui.com/sortable/
                 */
                this.$container.sortable({
                    axis: "y",
                    cursor: "move",
                    handle: '.exopite-sof-accordion__title',
                    tolerance: "pointer",
                    distance: 5,
                    opacity: 0.5,
                });
                // this.$element.disableSelection();
            }

        },

        // Bind events that trigger methods
        bindEvents: function () {
            var plugin = this;

            plugin.$container.off().on('click' + '.' + plugin._name, '.exopite-sof-accordion__title', function (e) {
                e.preventDefault();
                if (!$(e.target).hasClass('exopite-sof-cloneable--clone')) {
                    plugin.toggleAccordion.call(plugin, $(this));
                }
            });

            /**
             * Need to "reorder" name elements for metabox,
             * so it is saved in the order of displayed.
             */
            // Call function if sorting is stopped
            plugin.$container.on('sortstart' + '.' + plugin._name, function () {

                plugin.$element.trigger('exopite-sof-accordion-sortstart', [plugin.$container]);

            });

            plugin.$container.on('sortstop' + '.' + plugin._name, function () {

                // Need to reorder name index, make sure, saved in the wanted order in meta
                plugin.$container.find('.exopite-sof-accordion__item').each(function (index_item) {
                    var $name_prefix = plugin.$container.data('name');
                    var $name_prefix = $name_prefix.replace('[REPLACEME]', '');
                    $(this).find('[name^="' + $name_prefix + '"]').each(function () {
                        var $this_name = $(this).attr('name');
                        // Escape square brackets
                        $name_prefix_item = $name_prefix.replace(/\[/g, '\\[').replace(/]/g, '\\]');
                        var regex = new RegExp($name_prefix_item + '\\[\\d+\\]');
                        // Generate name to replace based on the parent item
                        var $this_name_updated = $this_name.replace(regex, $name_prefix + '\[' + index_item + '\]');
                        // Update
                        $(this).attr('name', $this_name_updated);
                    });
                });

                plugin.$element.trigger('exopite-sof-accordion-sortstop', [plugin.$container]);

                // Stop next click after reorder
                // @link https://stackoverflow.com/questions/947195/jquery-ui-sortable-how-can-i-cancel-the-click-event-on-an-item-thats-dragged/19858331#19858331
                //
                plugin.isSortableCalled = true;

            });

        },

        // Unbind events that trigger methods
        unbindEvents: function () {
            this.$container.off('.' + this._name);
        },

        toggleAccordion: function ($header) {

            var $this = $header.parent('.exopite-sof-accordion__item');

            // To prevent unwanted click trigger after sort (drag and drop)
            if (this.isSortableCalled) {
                this.isSortableCalled = false;
                return;
            }

            if ($this.hasClass('exopite-sof-accordion--hidden')) {
                $this.find('.exopite-sof-accordion__content').slideDown(350, function () {
                    $this.removeClass('exopite-sof-accordion--hidden');
                });
            } else {
                $this.find('.exopite-sof-accordion__content').slideUp(350, function () {
                    $this.addClass('exopite-sof-accordion--hidden');
                });

            }

        },

    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);


/**
 * Exopite SOF Search
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteSofSearch";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.$nav = this.$element.find('.exopite-sof-nav');
        // this.$wrapper = $searchField.parents('.exopite-sof-wrapper');
        // this.$container = $(element).find('.exopite-sof-accordion__wrapper').first();
        this.isSortableCalled = false;
        this.init();

    }

    Plugin.prototype = {

        init: function () {

            $.expr[':'].containsIgnoreCase = function (n, i, m) {
                return jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
            };

            this.bindEvents();

        },

        // Bind events that trigger methods
        bindEvents: function () {
            var plugin = this;

            plugin.$element.on('keyup' + '.' + plugin._name, '.exopite-sof-search', function (e) {
                e.preventDefault();
                plugin.doSearch.call(plugin, $(this));
            });

            plugin.$element.on('click' + '.' + plugin._name, '.exopite-sof-section-header', function (e) {
                e.preventDefault();
                plugin.selectSection.call(plugin, $(this));
            });

            plugin.$element.on('click' + '.' + plugin._name, '.exopite-sof-nav.search', function (e) {
                e.preventDefault();
                plugin.clearSearch.call(plugin, $(this));
            });

        },

        // Unbind events that trigger methods
        unbindEvents: function () {
            this.$element.off('.' + this._name);
        },
        clearSearch: function ($clickedElement) {
            var plugin = this;
            plugin.$element.find('.exopite-sof-search').val('').blur();
            plugin.$element.find('.exopite-sof-nav').removeClass('search');
            plugin.$element.find('.exopite-sof-section-header').hide();
            plugin.$element.find('.exopite-sof-field h4').closest('.exopite-sof-field').not('.hidden').removeAttr('style');
            plugin.$element.find('.exopite-sof-field-card').removeAttr('style');
            var activeElement = plugin.$nav.find("ul li.active").data('section');
            plugin.$element.find('.exopite-sof-sections .exopite-sof-section-' + activeElement).removeClass('hide');
        },
        activateSection: function (activeElement) {
            var plugin = this;
            if (plugin.$nav.length > 0) {
                plugin.$element.find('.exopite-sof-section-header').hide();
                plugin.$element.find('.exopite-sof-nav li[data-section="' + activeElement + '"]').addClass('active');
                plugin.$element.find('.exopite-sof-nav').removeClass('search');
            }
            plugin.$element.find('.exopite-sof-sections .exopite-sof-section').addClass('hide');
            plugin.$element.find('.exopite-sof-sections .exopite-sof-section-' + activeElement).removeClass('hide');
            plugin.$element.find('.exopite-sof-field h4').closest('.exopite-sof-field').not('.hidden').removeAttr('style');
            plugin.$element.find('.exopite-sof-field-card').removeAttr('style');
        },
        selectSection: function ($sectionHeader) {
            var plugin = this;
            plugin.$element.find('.exopite-sof-search').val('').blur();
            var activeElement = $sectionHeader.data('section');
            plugin.activateSection(activeElement);
        },
        doSearch: function ($searchField) {
            var plugin = this;
            var searchValue = $searchField.val();
            var activeElement = this.$nav.find("ul li.active").data('section');
            if (typeof this.$element.data('section') === 'undefined') {
                this.$element.data('section', activeElement);
            }

            if (searchValue) {
                if (this.$nav.length > 0) {
                    this.$element.find('.exopite-sof-nav-list-item').removeClass('active');
                    this.$element.find('.exopite-sof-nav').addClass('search');
                }
                this.$element.find('.exopite-sof-section-header').show();
                this.$element.find('.exopite-sof-section').removeClass('hide');
                this.$element.find('.exopite-sof-field h4').closest('.exopite-sof-field').not('.hidden').hide();
                this.$element.find('.exopite-sof-field-card').hide();
                this.$element.find('.exopite-sof-field h4:containsIgnoreCase(' + searchValue + ')').closest('.exopite-sof-field').not('.hidden').show();
            } else {
                activeElement = this.$element.data('section');
                this.$element.removeData('section');
                plugin.activateSection(activeElement);
            }

        },

    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

/**
 * Exopite SOF Search
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteFontPreview";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.$nav = this.$element.find('.exopite-sof-nav');
        // this.$wrapper = $searchField.parents('.exopite-sof-wrapper');
        // this.$container = $(element).find('.exopite-sof-accordion__wrapper').first();
        this.isSortableCalled = false;
        this.init();

    }

    Plugin.prototype = {

        init: function () {
            var plugin = this;
            // var parentName = jQuery( this ).attr( 'data-id' );
            plugin.preview = this.$element.find('.exopite-sof-font-preview');
            plugin.fontColor = this.$element.find( '.font-color-js' );
            plugin.fontSize = this.$element.find( '.font-size-js' );
            plugin.lineHeight = this.$element.find( '.line-height-js' );
            plugin.fontFamily = this.$element.find( '.exopite-sof-typo-family' );
            plugin.fontWeight = this.$element.find( '.exopite-sof-typo-variant' );

            // Set current values to preview
            this.updatePreview();
            this.loadGoogleFont();

            this.bindEvents();

        },

        // Bind events that trigger methods
        bindEvents: function () {
            var plugin = this;

            plugin.$element.on('change' + '.' + plugin._name, '.font-size-js, .line-height-js, .font-color-js, .exopite-sof-typo-variant', function (e) {
                e.preventDefault();
                plugin.updatePreview();
            });

            plugin.$element.on('change' + '.' + plugin._name, '.exopite-sof-typo-family', function (e) {
                e.preventDefault();
                plugin.loadGoogleFont();
            });


        },

        // Unbind events that trigger methods
        unbindEvents: function () {
            this.$element.off('.' + this._name);
        },
        updatePreview: function () {
            var plugin = this;
            var fontWeightStyle = plugin.calculateFontWeight(plugin.fontWeight.find(':selected').text());
            // Update preiew
            plugin.preview.css({
                'font-size': plugin.fontSize.val() + 'px',
                'line-height': plugin.lineHeight.val() + 'px',
                'font-weight': fontWeightStyle.fontWeightValue,
                'font-style': fontWeightStyle.fontStyleValue
            });
        },
        updateVariants: function (variants) {
            var plugin = this;
            var variantsArray = variants.split('|');
            plugin.fontWeight.empty();
            $.each(variantsArray, function (key, value) {
                plugin.fontWeight.append($("<option></option>").attr("value", value).text(value));
            });
            plugin.fontWeight.val('regular');
            plugin.fontWeight.trigger("chosen:updated");
        },
        loadGoogleFont: function () {
            var plugin = this;
            var variants = plugin.fontFamily.find(":selected").data('variants');

            plugin.updateVariants(variants);

            var font = plugin.fontFamily.val();
            if (!font) return;
            var href = '//fonts.googleapis.com/css?family=' + font + ':' + variants.replace(/\|/g, ',');
            var parentName = plugin.$element.find('.exopite-sof-font-field-js').data('id');
            var html = '<link href="' + href + '" class="cs-font-preview-' + parentName + '" rel="stylesheet" type="text/css" />';

            if ( $( '.cs-font-preview-' + parentName ).length > 0 ) {
                $( '.cs-font-preview-' + parentName ).attr( 'href', href ).load();
            } else {
                $('head').append( html ).load();
            }

            // Update preiew
            plugin.preview.css('font-family', font).css('font-weight', '400');

        },
        calculateFontWeight: function ( fontWeight ) {
            var fontWeightValue = '400';
            var fontStyleValue = 'normal';

            switch( fontWeight ) {
                case '100':
                    fontWeightValue = '100';
                    break;
                case '100italic':
                    fontWeightValue = '100';
                    fontStyleValue = 'italic';
                    break;
                case '300':
                    fontWeightValue = '300';
                    break;
                case '300italic':
                    fontWeightValue = '300';
                    fontStyleValue = 'italic';
                    break;
                case '500':
                    fontWeightValue = '500';
                    break;
                case '500italic':
                    fontWeightValue = '500';
                    fontStyleValue = 'italic';
                    break;
                case '700':
                    fontWeightValue = '700';
                    break;
                case '700italic':
                    fontWeightValue = '700';
                    fontStyleValue = 'italic';
                    break;
                case '900':
                    fontWeightValue = '900';
                    break;
                case '900italic':
                    fontWeightValue = '900';
                    fontStyleValue = 'italic';
                    break;
                case 'italic':
                    fontStyleValue = 'italic';
                    break;
            }

            return { fontWeightValue, fontStyleValue };
        },


    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

/**
 * Exopite Save Options with AJAX
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteImportExportAJAX";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.init();

    }

    Plugin.prototype = {

        init: function () {

            this.bindEvents();

        },

        // Bind events that trigger methods
        bindEvents: function () {
            var plugin = this;

            plugin.$element.find('.exopite-sof-import-js').off().on('click' + '.' + plugin._name, function (event) {

                event.preventDefault();
                if ($(this).hasClass('loading')) return;

                swal({

                    // title: "Are you sure?",
                    text: $(this).data('confirm'),
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,

                }).then((willImport) => {

                    if (willImport) {

                        $(this).addClass('loading');
                        $(this).prop("disabled", true);
                        this.disabled = true;
                        plugin.importOptions.call(this, event, plugin);

                    }

                });

                // if ( confirm( $( this ).data( 'confirm' ) ) ) {

                //     plugin.importOptions.call( this, event, plugin );

                // }

            });

            plugin.$element.find('.exopite-sof-reset-js').off().on('click' + '.' + plugin._name, function (event) {

                event.preventDefault();

                swal({

                    // title: "Are you sure?",
                    text: $(this).data('confirm'),
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,

                }).then((willDelete) => {

                    if (willDelete) {

                        $(this).addClass('loading');
                        $(this).prop("disabled", true);
                        plugin.resetOptions.call(this, event, plugin);

                    }

                });

            });

        },

        // Unbind events that trigger methods
        unbindEvents: function () {
            this.$element.off('.' + this._name);
        },

        importOptions: function (event, plugin) {

            var AJAXData = plugin.$element.find('.exopite-sof--data');

            $.ajax({
                url: AJAXData.data('admin'),
                type: 'post',
                data: {
                    action: 'exopite-sof-import-options',
                    unique: AJAXData.data('unique'),
                    value: plugin.$element.find('.exopite-sof__import').val(),
                    wpnonce: AJAXData.data('wpnonce')
                },
                success: function (response) {

                    if (response == 'success') {

                        plugin.$element.find('.exopite-sof__import').val('');
                        swal({
                            icon: "success",
                        });
                        location.reload();

                    }

                },
                error: function (xhr, status, error) {

                    console.log('Status: ' + xhr.status);
                    console.log('Error: ' + xhr.responseText);
                    swal("Error!", "Check console for more info!", "error");

                }
            });

            return false;

        },

        resetOptions: function (event, plugin) {

            var AJAXData = plugin.$element.find('.exopite-sof--data');

            $.ajax({
                url: AJAXData.data('admin'),
                type: 'post',
                data: {
                    action: 'exopite-sof-reset-options',
                    unique: AJAXData.data('unique'),
                    wpnonce: AJAXData.data('wpnonce')
                },
                success: function (response) {

                    if (response == 'success') {

                        swal({
                            icon: "success",
                        });
                        location.reload();

                    }

                },

                error: function (xhr, status, error) {
                    console.log('Status: ' + xhr.status);
                    console.log('Error: ' + xhr.responseText);
                    swal("Error!", "Check console for more info!", "error");
                }
            });

            return false;

        },

    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);

; (function ($) {
    "use strict";

    $(document).ready(function () {

        $('.exopite-sof-wrapper').exopiteSofManageDependencies();
        $('.exopite-sof-wrapper').exopiteSofSearch();
        $('.exopite-sof-sub-dependencies').exopiteSofManageDependencies('sub');

        $('.exopite-sof-wrapper-menu').exopiteSaveOptionsAJAX();

        $('.exopite-sof-media').exopiteMediaUploader({
            input: 'input',
            preview: '.exopite-sof-image-preview',
            remove: '.exopite-sof-image-remove'
        });

        $('.exopite-sof-content-js').exopiteOptionsNavigation();
        $('.exopite-sof-font-field').exopiteFontPreview();
        $('.exopite-sof-group').exopiteSOFTinyMCE();
        $('.exopite-sof-accordion').exopiteSOFAccordion();
        $('.exopite-sof-group').exopiteSOFRepeater();
        $('.exopite-sof-field-backup').exopiteImportExportAJAX();

    });

}(jQuery));
