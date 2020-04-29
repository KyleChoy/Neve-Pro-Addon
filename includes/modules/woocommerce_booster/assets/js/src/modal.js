/* global neveWooBooster */
/* jshint expr: true */

import axios from 'axios';
import { tns } from 'tiny-slider/src/tiny-slider';

/**
 * Initialize modal.
 */
function initializeModal() {
	openModal();
	handleClose();
}

/**
 * Open quick view modal
 */
function openModal() {
	/* Get trigger element */
	let modalTrigger = document.getElementsByClassName( 'nv-quick-view-product' );

	/* Set click event handler for all trigger elements */
	for ( let i = 0; i < modalTrigger.length; i++ ) {
		modalTrigger[i].addEventListener(
				'click', function(e) {
					e.preventDefault();
					let pid = this.getAttribute( 'data-pid' ),
							modalWindow = document.querySelector( '#quick-view-modal' );

					modalWindow.classList.add( 'open' );

					request_modal_content( pid );
				}
		);
	}
}

/**
 * Handle modal close.
 */
function handleClose() {
	document.addEventListener( 'keyup', function(e) {
		if ( e.key === 'Escape' ) { // escape key maps to keycode `27`
			closeModal();
		}
	} );

	let closeButton = document.querySelectorAll( '.jsModalClose' ),
			closeOverlay = document.querySelectorAll( '.jsOverlay' );

	/* Set click event handler for close buttons */
	for ( let i = 0; i < closeButton.length; i++ ) {
		closeButton[i].addEventListener( 'click', closeModal );
	}
	/* Set click event handler for modal overlay */
	for ( let i = 0; i < closeOverlay.length; i++ ) {
		closeOverlay[i].addEventListener( 'click', closeModal );
	}
}

/**
 * Close quick view modal.
 */
function closeModal() {
	let modal = document.querySelector( '.nv-modal' );
	modal.classList.remove( 'open' );
	setTimeout( function() {
		document.querySelector( '.nv-modal-container' ).
				classList.
				add( 'is-loading' );
	}, 500 );

}

/**
 * Request modal content.
 * @param pid
 * @returns {Promise}
 */
function request_modal_content(pid) {
	let requestUrl = neveWooBooster.modalContentEndpoint + pid + '/';
	let config = {
		headers: {
			'X-WP-Nonce': neveWooBooster.nonce,
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		}
	};
	let modalContainer = document.querySelector( '.nv-modal-inner-content' );

	axios.get( requestUrl, config ).then( response => {
		let data = response.data;
		modalContainer.innerHTML = data.markup;
		let noscriptTags = modalContainer.querySelectorAll( 'noscript' );
		for ( let i = 0; i < noscriptTags.length; i++ ) {
			noscriptTags[i].parentNode.removeChild( noscriptTags[i] );
		}
		tns( {
			container: '.nv-qv-gallery-wrap .nv-slider-gallery',
			slideBy: 1,
			arrowKeys: true,
			loop: true,
			autoplay: false,
			items: 1,
			edgePadding: 0,
			autoplayButtonOutput: false,
			autoplayHoverPause: true,
			speed: 1000,
			autoplayButton: false,
			controls: true,
			navPosition: 'bottom',
			nav: false,
			prevButton: '.nv-slider-controls .prev',
			nextButton: '.nv-slider-controls .next'
		} );
		document.querySelector( '.nv-modal-container' ).
				classList.
				remove( 'is-loading' );
	} ).catch( (error) => {
		let response = error.response.data;
		let responseText = '<p>' + response.message + '</p>';
		if ( response.code === 'error' ) {
			responseText = response.markup;
		}

		console.error( response.message );
		modalContainer.innerHTML = responseText;
		document.querySelector( '.nv-modal-container' ).
				classList.
				remove( 'is-loading' );
	} );
}

export {
	initializeModal
};
