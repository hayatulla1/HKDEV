/**
 * HKDEV Carousels — Swiper.js initialization
 *
 * Initialises all Swiper carousels used in the theme:
 *   - Banner / Hero carousel (.hkdev-banner-carousel)
 *   - Category carousel     (.hkdev-cat-carousel-swiper)
 *   - Product carousel      (.hkdev-product-carousel-swiper)
 */
( function () {
	'use strict';

	if ( typeof Swiper === 'undefined' ) {
		return;
	}

	// ── Banner Carousel ───────────────────────────────────────────────────────

	var bannerEl = document.querySelector( '.hkdev-banner-carousel .swiper' );
	if ( bannerEl ) {
		new Swiper( bannerEl, {
			loop:              true,
			speed:             700,
			autoplay:          { delay: 5000, disableOnInteraction: false },
			effect:            'fade',
			fadeEffect:        { crossFade: true },
			pagination:        { el: '.swiper-pagination', clickable: true },
			navigation:        { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
			a11y:              { enabled: true },
		} );
	}

	// ── Category Carousel ─────────────────────────────────────────────────────

	var catEls = document.querySelectorAll( '.hkdev-cat-carousel-swiper' );
	catEls.forEach( function ( el ) {
		new Swiper( el, {
			loop:           false,
			spaceBetween:   16,
			navigation:     { nextEl: el.querySelector( '.swiper-button-next' ), prevEl: el.querySelector( '.swiper-button-prev' ) },
			pagination:     { el: el.querySelector( '.swiper-pagination' ), clickable: true },
			a11y:           { enabled: true },
			breakpoints:    {
				0:    { slidesPerView: 2.2 },
				480:  { slidesPerView: 3.2 },
				768:  { slidesPerView: 4.2 },
				1024: { slidesPerView: 6 },
				1280: { slidesPerView: 7 },
			},
		} );
	} );

	// ── Product Carousel ──────────────────────────────────────────────────────

	var productEls = document.querySelectorAll( '.hkdev-product-carousel-swiper' );
	productEls.forEach( function ( el ) {
		new Swiper( el, {
			loop:           false,
			spaceBetween:   20,
			navigation:     { nextEl: el.querySelector( '.swiper-button-next' ), prevEl: el.querySelector( '.swiper-button-prev' ) },
			pagination:     { el: el.querySelector( '.swiper-pagination' ), clickable: true },
			a11y:           { enabled: true },
			breakpoints:    {
				0:    { slidesPerView: 1.5 },
				480:  { slidesPerView: 2.2 },
				768:  { slidesPerView: 3 },
				1024: { slidesPerView: 4 },
				1280: { slidesPerView: 5 },
			},
		} );
	} );

} )();
