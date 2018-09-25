
/**
 * Exopite Simple Options Framework Trumbowyg
 */
; (function ($, window, document, undefined) {

    var pluginName = "exopiteSOFTrumbowyg";

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

            $.trumbowyg.svgPath = plugin.$element.find('.trumbowyg-js').data('icon-path');

            plugin.trumbowygOptions = new Object();

            plugin.trumbowygOptions.btnsDef = {
                // Customizables dropdowns
                image: {
                    dropdown: ['insertImage', 'base64'],
                    ico: 'insertImage'
                }
            };

            plugin.trumbowygOptions.btns = [
                ['viewHTML'],
                ['undo', 'redo'],
                ['formatting'],
                ['strong', 'em'],
                ['link'],
                ['image'],
                ['unorderedList', 'orderedList'],
                ['foreColor', 'backColor'],
                ['preformatted'],
                ['fullscreen']
            ];

            plugin.$element.find('.trumbowyg-js').not(':disabled').trumbowyg(plugin.trumbowygOptions);

            var $group = plugin.$element.parents('.exopite-sof-field-group');

            plugin.$element.on('exopite-sof-field-group-item-added-after', function (event, $cloned) {

                $cloned.find('.trumbowyg-js').trumbowyg(plugin.trumbowygOptions);

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

; (function ($) {
    "use strict";

    $(document).ready(function () {

        $('.exopite-sof-wrapper').exopiteSOFTrumbowyg();

    });

}(jQuery));
