;(function( $ ) {
    "use strict";

    $( document ).ready(function() {

        $( '.datepicker' ).each(function(index, el) {
            if ( $( el ).parents( '.exopite-sof-cloneable__muster' ).length ) return;
            var dateFormat = $( el ).data( 'format' );
            $( el ).datepicker( { 'dateFormat': dateFormat } );
        });

    });

}(jQuery));
