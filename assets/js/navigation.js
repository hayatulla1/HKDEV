/**
 * HKDEV Navigation JS
 *
 * Handles keyboard accessibility for the desktop mega/dropdown menus.
 * No jQuery dependency.
 */
( function () {
	'use strict';

	var nav = document.querySelector( '.main-navigation' );

	if ( ! nav ) {
		return;
	}

	// ── Keyboard navigation for dropdown menus ────────────────────────────────

	var allLinks = nav.querySelectorAll( 'a' );

	allLinks.forEach( function ( link ) {
		link.addEventListener( 'focus', function () {
			var li = link.closest( 'li' );
			if ( li ) {
				li.classList.add( 'focus' );
			}
		} );
		link.addEventListener( 'blur', function () {
			var li = link.closest( 'li' );
			if ( li ) {
				// Only remove focus class when focus leaves the entire li
				setTimeout( function () {
					if ( ! li.contains( document.activeElement ) ) {
						li.classList.remove( 'focus' );
					}
				}, 0 );
			}
		} );
	} );

	// ── Accessible dropdown via keyboard ──────────────────────────────────────

	var topLevelItems = nav.querySelectorAll( 'ul > li' );

	topLevelItems.forEach( function ( item ) {
		var subMenu = item.querySelector( '.sub-menu, .mega-menu-wrapper' );
		var link    = item.querySelector( 'a' );

		if ( ! subMenu || ! link ) {
			return;
		}

		// Add aria attributes
		link.setAttribute( 'aria-haspopup', 'true' );
		link.setAttribute( 'aria-expanded', 'false' );

		item.addEventListener( 'mouseenter', function () {
			link.setAttribute( 'aria-expanded', 'true' );
		} );

		item.addEventListener( 'mouseleave', function () {
			link.setAttribute( 'aria-expanded', 'false' );
		} );

		link.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'ArrowDown' || e.key === 'Enter' ) {
				e.preventDefault();
				link.setAttribute( 'aria-expanded', 'true' );
				var firstSubLink = subMenu.querySelector( 'a' );
				if ( firstSubLink ) {
					firstSubLink.focus();
				}
			}
		} );

		subMenu.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) {
				link.setAttribute( 'aria-expanded', 'false' );
				link.focus();
			}
		} );
	} );

} )();
