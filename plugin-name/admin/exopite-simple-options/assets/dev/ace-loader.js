;(function ( $, window, document, undefined ) {

    $.fn.exopiteSofFieldACEEditor = function() {
        return this.each(function() {

            if( typeof ace !== 'undefined' ) {

                var $this     = $(this),
                    $textarea = $this.find('.exopite-sof-ace-editor-textarea'),
                    options   = JSON.parse( $this.find( '.exopite-sof-ace-editor-options' ).val() ),
                    editor    = ace.edit($this.find('.exopite-sof-ace-editor').attr('id'));

                // global settings of ace editor
                editor.getSession().setValue($textarea.val());

                editor.setOptions( options );

                editor.on( 'change', function( e ) {
                    $textarea.val( editor.getSession().getValue() ).trigger('change');
                });

            }
        });
    };

    $( document ).ready(function() {

        console.log( 'ace-loader' );
        $( '.exopite-sof-field-ace_editor' ).exopiteSofFieldACEEditor();

    });

})( jQuery, window, document );
