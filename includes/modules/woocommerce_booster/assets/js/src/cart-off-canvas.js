/**
 * Handles cart expansion.
 *
 * @returns {boolean}
 *
 * @package HFG
 */

export const cartOffCanvas = function () {
	let openCart = document.querySelector( '.builder-item--header_cart_icon .responsive-nav-cart.off-canvas:not(.cart-is-empty)' );
	if ( openCart !== null && typeof openCart !== 'undefined' ) {
		openCart.addEventListener(
			'click',
			openEvent
		);
	}


	let closeCart = document.querySelector( '.builder-item--header_cart_icon .nv-close-cart-sidebar' );
	if ( closeCart !== null && typeof  closeCart !== 'undefined' ) {
		closeCart.addEventListener(
			'click',
			closeEvent
		);
	}
};

function openEvent(e) {
	e.preventDefault();
	let openCart = document.querySelector( '.builder-item--header_cart_icon .responsive-nav-cart.off-canvas' );
	let cart     = openCart.querySelectorAll( '.cart-off-canvas' )[0];
	if ( typeof cart !== 'undefined' ) {
		cart.classList.add( 'cart-open' );
	}
}

function closeEvent(e) {
	e.stopPropagation();
	let openCart = document.querySelector( '.builder-item--header_cart_icon .responsive-nav-cart.off-canvas' );
	let cart     = openCart.querySelectorAll( '.cart-off-canvas' )[0];
	if ( typeof cart !== 'undefined' ) {
		cart.classList.remove( 'cart-open' );
	}
}
