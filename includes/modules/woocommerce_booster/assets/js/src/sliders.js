/* global neveWooBooster */
import { tns } from 'tiny-slider/src/tiny-slider';

function initializeSliders() {
	gallerySlider();
	relatedSlider();
}

let svgs = {
	vertical: {
		prev: '<svg width="30px" height="25px" viewBox="0 0 50 80" xml:space="preserve" style="transform: rotate(90deg)"><polyline fill="none" stroke="#333" stroke-width="7" points="25,76 10,38 25,0"/></svg>',
		next: '<svg width="30px" height="25px" viewBox="0 0 50 80" xml:space="preserve" style="transform: rotate(90deg)"><polyline fill="none" stroke="#333" stroke-width="7" points="25,0 40,38 25,75"/></svg>'
	},
	horizontal: {
		prev: '<svg width="25px" height="30px" viewBox="0 0 50 80" xml:space="preserve"><polyline fill="none" stroke="#333" stroke-width="7" points="25,76 10,38 25,0"/></svg>',
		next: '<svg width="25px" height="30px" viewBox="0 0 50 80" xml:space="preserve"><polyline fill="none" stroke="#333" stroke-width="7" points="25,0 40,38 25,75"/></svg>'
	}
};

/**
 * Add prev and next
 *
 * @param targetNode
 * @param slider
 */
function addNextPrev(targetNode, slider, vertical = false) {
	let next = document.createElement( 'span' );
	let prev = document.createElement( 'span' );

	next.classList.add( 'neve-slider-control', 'next' );
	prev.classList.add( 'neve-slider-control', 'prev' );

	prev.innerHTML = vertical ? svgs.vertical.prev : svgs.horizontal.prev;
	next.innerHTML = vertical ? svgs.vertical.next : svgs.horizontal.next;

	prev.addEventListener( 'click', function() {
		slider.goTo( 'prev' );
	} );

	next.addEventListener( 'click', function() {
		slider.goTo( 'next' );
	} );

	targetNode.parentNode.insertBefore( prev, targetNode );
	targetNode.parentNode.appendChild( next );
}

function relatedSlider() {
	if ( neveWooBooster.relatedSliderStatus !== 'enabled' ) {
		return false;
	}

	if ( document.querySelector( '.related.products > .products' ) ===
			null ) return false;

	let products = document.querySelectorAll(
			'.related.products > .products > .product' );
	if ( products === null || products.length <= 4 ) return false;
	const perCol =  neveWooBooster.relatedSliderPerCol || 4;
	let relatedSlider = tns(
			{
				container: '.related.products > .products',
				slideBy: 'page',
				nav: false,
				arrowKeys: true,
				mouseDrag: true,
				rewind: true,
				loop: false,
				controls: false,
				items: 4,
				responsive: {
					'0': { 'items': 2 },
					'576': { 'items': 3 },
					'960': { 'items': perCol }
				},
				gutter: 10
			}
	);

	addNextPrev( document.querySelector( '.related.products .tns-inner' ),
			relatedSlider );
}

function gallerySlider() {
	if ( neveWooBooster.gallerySliderStatus !== 'enabled' ) {
		return false;
	}

	if ( document.querySelector( '.flex-control-thumbs' ) === null ) return false;

	let vertical = false;

	if ( neveWooBooster.galleryLayout !== 'normal' ) {
		vertical = true;
	}

	let
			items = vertical ? 5 : 4,
			axis = vertical ? 'vertical' : 'horizontal',
			responsive = vertical ? null : {
				'0': { 'items': 3 },
				'576': { 'items': 4 }
			};

	let productGallery = tns(
			{
				container: '.flex-control-thumbs',
				slideBy: 'page',
				nav: false,
				arrowKeys: true,
				mouseDrag: true,
				rewind: true,
				loop: false,
				controls: false,
				items,
				axis,
				responsive,
				gutter: 10
			}
	);

	addNextPrev(
			document.querySelector( '.woocommerce-product-gallery .tns-inner' ),
			productGallery, vertical );

}

export {
	initializeSliders,
	gallerySlider
};
