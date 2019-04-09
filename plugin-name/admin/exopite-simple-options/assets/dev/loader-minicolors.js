
/**
 * Exopite Simple Options Framework Color Picker
 *
 * https://tovic.github.io/color-picker/#section:extend
 * https://bgrins.github.io/spectrum/
 * https://www.jqueryscript.net/other/Color-Picker-Plugin-jQuery-MiniColors.html
 * - https://www.jqueryscript.net/demo/Color-Picker-Plugin-jQuery-MiniColors/
 * https://www.jqueryscript.net/other/Color-Picker-Plugin-jQuery-ChromoSelector.html
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteSOFMinicolors";

    // The actual plugin constructor
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this.$element = $(element);
        this.init();

    }

    Plugin.prototype = {

        init: function () {

            var plugin = this;

            plugin.minicolorOptions = {
                format: 'rgb',
                // opacity: $(this).attr('data-opacity'),
                theme: 'default',
                // control: 'saturation',
                swatches: '#000|#fff|#f00|#dd9933|#eeee22|#81d742|#1e73be|#8224e3|#2196f3|#4caf50|#ffeb3b|#ff9800|#795548|rgba(0, 0, 0, 0)'.split('|'),
                // swatches: '#ef9a9a|#90caf9|#a5d6a7|#fff59d|#ffcc80|#bcaaa4|#eeeeee|#f44336|#2196f3|#4caf50|#ffeb3b|#ff9800|#795548|rgba(0, 0, 0, 0)'.split('|'),
                change: function(value, opacity) {
                    plugin.change(value, opacity, $(this));
                    if( !value ) return;
                    if( opacity ) value += ', ' + opacity;
                    if( typeof console === 'object' ) {
                        console.log(value);
                    }
                },
            };

            plugin.$element.find('.minicolor').each(function (index, el) {

                if ($(el).closest('.exopite-sof-cloneable__item').hasClass('exopite-sof-cloneable__muster')) return;
                if ($(el).hasClass('disabled')) return;

                plugin.minicolorOptions.opacity = $(el).attr('data-opacity') || false;
                plugin.minicolorOptions.control = $(el).attr('data-control') || 'saturation';

                $(el).minicolors(plugin.minicolorOptions);

            });

            plugin.$element.closest('.exopite-sof-wrapper').on('exopite-sof-field-group-item-added-after', function (event, $cloned) {

                $cloned.find('.minicolor').each(function (index, el) {

                    if ($(el).closest('.exopite-sof-cloneable__item').hasClass('exopite-sof-cloneable__muster')) return;
                    if ($(el).hasClass('disabled')) return;

                    $(el).minicolors(plugin.minicolorOptions);

                });

                console.log('color picker clone');

            });

        },

        change: function (value, opacity, $this) {
            // if( opacity ) value += ', ' + opacity;
            var color = value;
            if ($this.hasClass('font-color-js')) {
                console.log('has font-color');
                $this.parents('.exopite-sof-font-field').find('.exopite-sof-font-preview').css({ 'color': color });
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

; (function ($) {
    "use strict";

    $(document).ready(function () {

        $('.exopite-sof-field').exopiteSOFMinicolors();

    });

}(jQuery));
