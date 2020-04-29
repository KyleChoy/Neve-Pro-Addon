import axios from 'axios';

function formSerialize( formElement ) {
	const values = new URLSearchParams();
	const inputs = formElement.elements;

	for (let i = 0; i < inputs.length; i++) {
		let name  = inputs[i].name;
		let value = inputs[i].value;
		if ( name === 'product_id' ) {
			continue;
		}
		if ( name === 'add-to-cart' ) {
			name  = 'product_id';
			value = formElement.querySelector( 'input[name=variation_id]' ) ? formElement.querySelector( 'input[name=variation_id]' ).value : value;
		}
		if ( name !== '' ) {
			values.append( name, value );
		}
	}
	return values;
}

function addToCart() {
	let body = document.getElementsByTagName( 'body' )[0];
	if ( ! body.classList.contains( 'seamless-add-to-cart' ) ) {
		return false;
	}
	let trigger = document.getElementsByClassName( 'single_add_to_cart_button' );
	for ( let i = 0; i < trigger.length; i++ ) {
		trigger[i].addEventListener(
			'click',
			clickEvent
		);
	}
}

function clickEvent(e) {
	e.preventDefault();
	let triggerButton = this;
	if ( triggerButton.classList.contains( 'disabled' ) ) {
		return false;
	}
	let form           = triggerButton.closest( 'form.cart' );
	let serializedData = formSerialize( form );
	let body           = document.getElementsByTagName( 'body' )[0];
	jQuery( body ).trigger( 'adding_to_cart', [ jQuery( triggerButton ), serializedData ] );

	let requestUrl = woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' );
	let config     = {
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		}
	};

	triggerButton.classList.remove( 'added' );
	triggerButton.classList.add( 'loading' );
	axios.post(
		requestUrl,
		serializedData,
		config
	).then(
		response =>
		{
			if ( response.status === 200 ) {
				let responseData = response.data;
				triggerButton.classList.remove( 'loading' );
				triggerButton.classList.add( 'added' );
				jQuery( body ).trigger( 'added_to_cart', [responseData.fragments, responseData.cart_hash, jQuery( triggerButton ) ] );
			} else {
				if ( response.error && response.product_url ) {
					window.location = response.product_url;
					return false;
				}
			}
		}
	);
}

export {
	addToCart
};
