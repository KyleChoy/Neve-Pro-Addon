/**
 * Handles cart expansion.
 *
 * @returns {boolean}
 */
export const cartExpansion  = function () {
	if (typeof jQuery === 'undefined') {
		return false;
	}
	jQuery(document.body).on('added_to_cart', expandCart);
};

const expandCart = function () {
	let offCanvasCart = document.querySelector( '.cart-off-canvas' );
	if ( offCanvasCart !== null ) {
		document.querySelector( '.cart-off-canvas' ).classList.add( 'cart-open' );
	}

	let cart = document.querySelector( '.nv-nav-cart:not(.expand-disable)' );
	if ( cart === null ) {
		return;
	}

	cart.style.visibility = 'visible';
	cart.style.opacity = 1;
	setTimeout(
			function () {
				cart.style = null;
			}, 3000
	);
};
