/**
 * HKDEV Theme — Main JavaScript
 *
 * Handles: Ajax mini cart open/close, search panel, mobile nav,
 * Ajax add-to-cart, live search, and general UI enhancements.
 */
( function ( $, HKDEV ) {
	'use strict';

	// ── Cache DOM references ─────────────────────────────────────────────────
	var $body              = $( 'body' );
	var $miniCartOverlay   = $( '#mini-cart-overlay' );
	var $cartToggle        = $( '.header-cart-toggle' );
	var $miniCartClose     = $( '.mini-cart-close' );
	var $mobileNavOverlay  = $( '#mobile-nav-overlay' );
	var $menuToggle        = $( '.menu-toggle' );
	var $mobileNavClose    = $( '.mobile-nav-close' );
	var $searchToggle      = $( '.header-search-toggle' );
	var $searchPanel       = $( '#header-search-panel' );
	var $searchInput       = $( '#header-search-input' );
	var $cartCountBadge    = $( '.hkdev-cart-count' );
	var $cartSubtotal      = $( '.hkdev-cart-subtotal' );

	// ── Mini Cart ────────────────────────────────────────────────────────────

	function openMiniCart() {
		$miniCartOverlay.addClass( 'is-open' );
		$body.addClass( 'mini-cart-open' );
		$cartToggle.attr( 'aria-expanded', 'true' );
		$miniCartOverlay.find( '.mini-cart-close' ).focus();
	}

	function closeMiniCart() {
		$miniCartOverlay.removeClass( 'is-open' );
		$body.removeClass( 'mini-cart-open' );
		$cartToggle.attr( 'aria-expanded', 'false' );
	}

	$cartToggle.on( 'click', function ( e ) {
		e.preventDefault();
		if ( $miniCartOverlay.hasClass( 'is-open' ) ) {
			closeMiniCart();
		} else {
			openMiniCart();
		}
	} );

	$miniCartClose.on( 'click', closeMiniCart );

	// Close when clicking the overlay background
	$miniCartOverlay.on( 'click', function ( e ) {
		if ( $( e.target ).is( $miniCartOverlay ) ) {
			closeMiniCart();
		}
	} );

	// Keyboard: Escape to close
	$( document ).on( 'keydown', function ( e ) {
		if ( e.key === 'Escape' ) {
			closeMiniCart();
			closeMobileNav();
			closeSearch();
		}
	} );

	// ── Mobile Navigation ────────────────────────────────────────────────────

	function openMobileNav() {
		$mobileNavOverlay.addClass( 'is-open' );
		$body.addClass( 'mobile-nav-open' );
		$menuToggle.attr( 'aria-expanded', 'true' );
	}

	function closeMobileNav() {
		$mobileNavOverlay.removeClass( 'is-open' );
		$body.removeClass( 'mobile-nav-open' );
		$menuToggle.attr( 'aria-expanded', 'false' );
	}

	$menuToggle.on( 'click', openMobileNav );
	$mobileNavClose.on( 'click', closeMobileNav );

	$mobileNavOverlay.on( 'click', function ( e ) {
		if ( $( e.target ).is( $mobileNavOverlay ) ) {
			closeMobileNav();
		}
	} );

	// Mobile sub-menu accordion
	$( '.mobile-nav-menu' ).on( 'click', '.mobile-sub-toggle', function () {
		var $btn     = $( this );
		var $subMenu = $btn.closest( 'li' ).find( '> .sub-menu' ).first();
		$btn.toggleClass( 'is-open' );
		$subMenu.toggleClass( 'is-open' );
		$btn.attr( 'aria-expanded', $btn.hasClass( 'is-open' ) ? 'true' : 'false' );
	} );

	// Add toggle arrows to mobile menu items that have sub-menus
	$( '.mobile-nav-menu li:has(> .sub-menu) > a' ).after(
		'<button class="mobile-sub-toggle" aria-expanded="false" aria-label="' + ( HKDEV.i18n ? HKDEV.i18n.expand_menu || 'Expand' : 'Expand' ) + '">' +
		'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>' +
		'</button>'
	);

	// ── Header Search Panel ──────────────────────────────────────────────────

	function openSearch() {
		$searchPanel.addClass( 'is-open' ).attr( 'aria-hidden', 'false' );
		$searchToggle.attr( 'aria-expanded', 'true' );
		setTimeout( function () { $searchInput.focus(); }, 100 );
	}

	function closeSearch() {
		$searchPanel.removeClass( 'is-open' ).attr( 'aria-hidden', 'true' );
		$searchToggle.attr( 'aria-expanded', 'false' );
	}

	$searchToggle.on( 'click', function ( e ) {
		e.preventDefault();
		$searchPanel.hasClass( 'is-open' ) ? closeSearch() : openSearch();
	} );

	// Close search when clicking outside
	$( document ).on( 'click', function ( e ) {
		if ( $searchPanel.hasClass( 'is-open' ) &&
			! $searchPanel.is( e.target ) &&
			! $searchPanel.has( e.target ).length &&
			! $searchToggle.is( e.target ) &&
			! $searchToggle.has( e.target ).length
		) {
			closeSearch();
		}
	} );

	// ── Ajax Add to Cart (archive / loop) ────────────────────────────────────

	$body.on( 'click', '.ajax_add_to_cart', function ( e ) {
		e.preventDefault();

		var $btn       = $( this );
		var productId  = $btn.data( 'product_id' ) || $btn.attr( 'data-product_id' );
		var quantity   = $btn.data( 'quantity' ) || 1;

		if ( $btn.hasClass( 'loading' ) || ! productId ) {
			return;
		}

		$btn.addClass( 'loading' );

		$.ajax( {
			url:    HKDEV.ajax_url,
			type:   'POST',
			data:   {
				action:     'hkdev_add_to_cart',
				product_id: productId,
				quantity:   quantity,
				nonce:      HKDEV.nonce,
			},
			success: function ( response ) {
				if ( response.success ) {
					updateCartUI( response.data.count, response.data.subtotal );
					// Trigger WC fragment refresh
					$( document.body ).trigger( 'wc_fragment_refresh' );
					$( document.body ).trigger( 'added_to_cart', [ response.data.fragments, response.data.cart_hash, $btn ] );
					// Brief visual feedback
					$btn.addClass( 'added' );
					setTimeout( function () { $btn.removeClass( 'added loading' ); }, 1600 );
				} else {
					$btn.removeClass( 'loading' );
					alert( response.data ? response.data.message : 'Error adding to cart.' );
				}
			},
			error: function () {
				$btn.removeClass( 'loading' );
			},
		} );
	} );

	// ── Update cart count & subtotal in header ────────────────────────────────

	function updateCartUI( count, subtotal ) {
		$cartCountBadge.text( count );
		if ( subtotal ) {
			$cartSubtotal.html( subtotal );
		}
	}

	// ── WooCommerce fragment update listener ─────────────────────────────────

	$( document.body ).on( 'wc_fragments_refreshed', function () {
		if ( HKDEV.ajax_url ) {
			$.ajax( {
				url:  HKDEV.ajax_url,
				type: 'POST',
				data: {
					action: 'hkdev_get_cart_fragment',
					nonce:  HKDEV.nonce,
				},
				success: function ( response ) {
					if ( response.success ) {
						updateCartUI( response.data.count, response.data.subtotal );
					}
				},
			} );
		}
	} );

	// ── Shop: Grid / List View Toggle ────────────────────────────────────────

	$( document ).on( 'click', '.shop-view-toggle button', function () {
		var $btn  = $( this );
		var view  = $btn.data( 'view' );
		var $grid = $( '.products-grid, .products.columns-4, ul.products' );

		$( '.shop-view-toggle button' ).removeClass( 'is-active' );
		$btn.addClass( 'is-active' );

		if ( view === 'list' ) {
			$grid.addClass( 'view-list' ).removeClass( 'view-grid' );
		} else {
			$grid.removeClass( 'view-list' ).addClass( 'view-grid' );
		}

		localStorage.setItem( 'hkdev_shop_view', view );
	} );

	// Restore saved view preference
	var savedView = localStorage.getItem( 'hkdev_shop_view' );
	if ( savedView === 'list' ) {
		$( '.products-grid, .products.columns-4, ul.products' ).addClass( 'view-list' );
		$( '.shop-view-toggle [data-view="list"]' ).addClass( 'is-active' );
		$( '.shop-view-toggle [data-view="grid"]' ).removeClass( 'is-active' );
	}

	// ── Product Tabs ─────────────────────────────────────────────────────────

	$( document ).on( 'click', '.tab-btn', function () {
		var $btn    = $( this );
		var target  = $btn.data( 'tab' );
		var $panel  = $( '#' + target );

		$( '.tab-btn' ).removeClass( 'is-active' ).attr( 'aria-selected', 'false' );
		$( '.tab-panel' ).removeClass( 'is-active' ).attr( 'hidden', true );

		$btn.addClass( 'is-active' ).attr( 'aria-selected', 'true' );
		$panel.addClass( 'is-active' ).removeAttr( 'hidden' );
	} );

	// ── Quantity Selectors ───────────────────────────────────────────────────

	$( document ).on( 'click', '.qty-btn', function () {
		var $btn   = $( this );
		var $input = $btn.closest( '.qty-selector' ).find( '.qty-input' );
		var val    = parseInt( $input.val(), 10 ) || 1;
		var min    = parseInt( $input.attr( 'min' ), 10 ) || 1;
		var max    = parseInt( $input.attr( 'max' ), 10 ) || 9999;

		if ( $btn.hasClass( 'qty-minus' ) ) {
			val = Math.max( min, val - 1 );
		} else {
			val = Math.min( max, val + 1 );
		}

		$input.val( val ).trigger( 'change' );
	} );

	// ── Product Gallery (custom) ─────────────────────────────────────────────

	$( document ).on( 'click', '.gallery-thumb', function () {
		var $thumb   = $( this );
		var imgSrc   = $thumb.find( 'img' ).attr( 'src' );
		var $mainImg = $( '.gallery-main img' );

		$( '.gallery-thumb' ).removeClass( 'is-active' );
		$thumb.addClass( 'is-active' );

		if ( $mainImg.length ) {
			$mainImg.attr( 'src', imgSrc );
		}
	} );

	// ── Live Search Suggestions ──────────────────────────────────────────────

	var searchTimer;
	var $liveResults = null;

	$searchInput.on( 'input', function () {
		clearTimeout( searchTimer );
		var q = $( this ).val().trim();

		if ( q.length < 2 ) {
			if ( $liveResults ) { $liveResults.remove(); $liveResults = null; }
			return;
		}

		searchTimer = setTimeout( function () {
			$.ajax( {
				url:  HKDEV.ajax_url,
				type: 'GET',
				data: {
					action: 'hkdev_search',
					q:      q,
					nonce:  HKDEV.nonce,
				},
				success: function ( response ) {
					if ( response.success && response.data.results.length ) {
						renderLiveSearch( response.data.results );
					} else {
						if ( $liveResults ) { $liveResults.remove(); $liveResults = null; }
					}
				},
			} );
		}, 350 );
	} );

	function renderLiveSearch( results ) {
		if ( ! $liveResults ) {
			$liveResults = $( '<ul class="hkdev-live-search-results"></ul>' );
			$searchPanel.find( '.header-search-form' ).after( $liveResults );
		}
		$liveResults.empty();
		$.each( results, function ( i, item ) {
			var $li = $( '<li class="hkdev-search-result-item"></li>' );
			$li.html(
				'<a href="' + item.permalink + '">' +
				( item.thumbnail ? '<img src="' + item.thumbnail + '" alt="" width="44" height="44">' : '' ) +
				'<span class="result-title">' + item.title + '</span>' +
				( item.price ? '<span class="result-price">' + item.price + '</span>' : '' ) +
				'</a>'
			);
			$liveResults.append( $li );
		} );
	}

	// Close live results when clicking outside
	$( document ).on( 'click', function ( e ) {
		if ( $liveResults && ! $searchPanel.has( e.target ).length ) {
			$liveResults.remove();
			$liveResults = null;
		}
	} );

	// ── Price Range Slider ───────────────────────────────────────────────────

	function initPriceSlider() {
		var $minSlider = $( '#price-range-min' );
		var $maxSlider = $( '#price-range-max' );
		var $minInput  = $( '#price-min-input' );
		var $maxInput  = $( '#price-max-input' );
		var $fill      = $( '.price-range-fill' );

		if ( ! $minSlider.length ) {
			return;
		}

		function updateFill() {
			var min   = parseInt( $minSlider.val(), 10 );
			var max   = parseInt( $maxSlider.val(), 10 );
			var range = parseInt( $maxSlider.attr( 'max' ), 10 ) - parseInt( $minSlider.attr( 'min' ), 10 );
			var left  = ( ( min - parseInt( $minSlider.attr( 'min' ), 10 ) ) / range ) * 100;
			var right = 100 - ( ( max - parseInt( $minSlider.attr( 'min' ), 10 ) ) / range ) * 100;

			$fill.css( { left: left + '%', right: right + '%' } );
			$minInput.val( min );
			$maxInput.val( max );
		}

		$minSlider.add( $maxSlider ).on( 'input', function () {
			var min = parseInt( $minSlider.val(), 10 );
			var max = parseInt( $maxSlider.val(), 10 );
			if ( min > max ) {
				if ( $( this ).is( $minSlider ) ) {
					$minSlider.val( max );
				} else {
					$maxSlider.val( min );
				}
			}
			updateFill();
		} );

		updateFill();
	}

	initPriceSlider();

} )( jQuery, window.HKDEV || {} );
