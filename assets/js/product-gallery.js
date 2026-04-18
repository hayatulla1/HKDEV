/**
 * HKDEV Product Gallery JS
 *
 * Handles thumbnail switching on the single product page
 * (works alongside or as a replacement for WooCommerce's own gallery).
 */
( function ( $ ) {
	'use strict';

	// ── Thumbnail Switcher ────────────────────────────────────────────────────

	$( document ).on( 'click', '.gallery-thumb', function () {
		var $thumb    = $( this );
		var full      = $thumb.data( 'full' ) || $thumb.find( 'img' ).attr( 'src' );
		var $mainImg  = $( '.gallery-main img' );
		var $mainLink = $( '.gallery-main a' );

		$( '.gallery-thumb' ).removeClass( 'is-active' );
		$thumb.addClass( 'is-active' );

		if ( $mainImg.length ) {
			$mainImg
				.addClass( 'is-loading' )
				.one( 'load', function () {
					$( this ).removeClass( 'is-loading' );
				} )
				.attr( 'src', full );
		}

		if ( $mainLink.length ) {
			$mainLink.attr( 'href', full );
		}
	} );

	// ── Quantity Input Sync with WooCommerce ─────────────────────────────────

	$( document ).on( 'click', '.single-product .qty-btn', function () {
		var $btn    = $( this );
		var $custom = $btn.closest( '.qty-selector' ).find( '.qty-input' );
		var $wcQty  = $( 'form.cart .qty' );

		if ( $wcQty.length ) {
			$wcQty.val( $custom.val() ).trigger( 'change' );
		}
	} );

	// ── Dynamic Stock Status ─────────────────────────────────────────────────

	$( document ).on( 'change', 'form.variations_form', function () {
		// WooCommerce handles variation price & stock via its own JS.
		// This hook is a placeholder for custom logic if needed.
	} );

} )( jQuery );
