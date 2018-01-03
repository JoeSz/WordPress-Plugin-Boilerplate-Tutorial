;(function( $ ) {
    "use strict";

    $( document ).ready(function() {

        $( '.datepicker' ).each(function(index, el) {
            var dateFormat = $( el ).data( 'format' );
            $( el ).datepicker( { 'dateFormat': dateFormat } );
        });

    });

}(jQuery));
