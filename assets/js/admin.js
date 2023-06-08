/**!
 * Admin Product Edit Screen
 * @version 1.5.0
 * @since 1.0.0
 */
;(function( $, window ){
    $(window).load( function () {
        let $wcProductDataMetaBox = $( '#woocommerce-product-data' );
        // Tested in firefox and chrome.
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

        $( '#commentsdiv .comments-box' ).css( {
            'padding': '0 10px',
            'margin-bottom': '10px',
        } );
        if ( ! window.commentsBox ) {
            // wp-admin/js/post.js
            // noinspection DuplicatedCode
            window.commentsBox = {
                // Comment offset to use when fetching new comments.
                st : 0,

                /**
                 * Fetch comments using Ajax and display them in the box.
                 *
                 * @memberof commentsBox
                 *
                 * @param {number} total Total number of comments for this post.
                 * @param {number} num   Optional. Number of comments to fetch, defaults to 20.
                 * @return {boolean} Always returns false.
                 */
                get : function(total, num) {
                    var st = this.st, data;
                    if ( ! num )
                        num = 20;

                    this.st += num;
                    this.total = total;
                    $( '#commentsdiv .spinner' ).addClass( 'is-active' );

                    data = {
                        'action' : 'get-comments',
                        'mode' : 'single',
                        '_ajax_nonce' : $('#add_comment_nonce').val(),
                        'p' : $('#post_ID').val(),
                        'start' : st,
                        'number' : num
                    };

                    $.post(
                        ajaxurl,
                        data,
                        function(r) {
                            r = wpAjax.parseAjaxResponse(r);
                            $('#commentsdiv .widefat').show();
                            $( '#commentsdiv .spinner' ).removeClass( 'is-active' );

                            if ( 'object' == typeof r && r.responses[0] ) {
                                $('#the-comment-list').append( r.responses[0].data );

                                theList = theExtraList = null;
                                $( 'a[className*=\':\']' ).off();

                                // If the offset is over the total number of comments we cannot fetch any more, so hide the button.
                                if ( commentsBox.st > commentsBox.total )
                                    $('#show-comments').hide();
                                else
                                    $('#show-comments').show().children('a').text( __( 'Show more comments' ) );

                                return;
                            } else if ( 1 == r ) {
                                $('#show-comments').text( __( 'No more comments found.' ) );
                                return;
                            }

                            $('#the-comment-list').append('<tr><td colspan="2">'+wpAjax.broken+'</td></tr>');
                        }
                    );

                    return false;
                },

                /**
                 * Load the next batch of comments.
                 *
                 * @memberof commentsBox
                 *
                 * @param {number} total Total number of comments to load.
                 */
                load: function(total){
                    this.st = jQuery('#the-comment-list tr.comment:visible').length;
                    this.get(total);
                }
            };
        }
    });
})( jQuery, window );
