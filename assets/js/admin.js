/**
 * JS For Admin Product Edit Screen
 * @version 1.0.0
 * @author KD <webmaster@pixelhive.pro>
 * @copyright 2019 PixelHive.PRO
 * @requires jQuery
 */
;(function( $, window ){
    $(window).on( 'load', function () {
        let $wcProductDataMetaBox = $( '#woocommerce-product-data' );
        // tested in firefox and chrome..
        $wcProductDataMetaBox.off( 'click', '.hndle' );
        // $wcProductDataMetaBox.find( '.hndle' ).off( 'click' );
        $wcProductDataMetaBox.find( '.hndle' ).unbind( 'click.postboxes' );
        // $wcProductDataMetaBox.find( '.hndle' ).bind( 'click.postboxes', window.postboxes.handle_click );
        $wcProductDataMetaBox.on( 'click', '.hndle', function( event ) {
            // If the user clicks on some form input inside the h3 the box should not be toggled.
            // needed for firefox, chrome doesn't have problem with it (tested on chrome 78 & firefox 67)
            if ( $( event.target ).filter( 'input, option, label, select' ).length ) {
                return;
            }
            // if( $wcProductDataMetaBox.hasClass('closed') ) $wcProductDataMetaBox.removeClass('closed');
            // if( ! $wcProductDataMetaBox.hasClass('closed') ) $wcProductDataMetaBox.addCLass('closed');
            // console.log( $( '#woocommerce-product-data' ).get(0), this, $( '#woocommerce-product-data' ).hasClass('closed'));
            // firefox has problem with $.addClass()
            $wcProductDataMetaBox.toggleClass( 'closed' );
            // save postbox state..
            postboxes.save_state( 'product' );
        });
    });
})( jQuery, window );
