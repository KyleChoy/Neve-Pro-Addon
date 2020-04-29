/* global neveWooBooster */
import axios from 'axios';

let wishListTimeout;

function initializeWishList() {
	handleWishlistClicks();
	removeFromWishList();
	handleWishListAsyncUpdate();
}

/**
 * Add or remove current item to wish list on shop page.
 */
function handleWishlistClicks() {
	let trigger = document.getElementsByClassName( 'add-to-wl' );
	for ( let i = 0; i < trigger.length; i++ ) {
		trigger[i].addEventListener(
				'click', function(e) {
					e.preventDefault();

					let pid = parseInt( this.getAttribute( 'data-pid' ) ),
							svg = trigger[i].querySelector( 'svg' ),
							notification = document.querySelector( '.nv-wl-notification' ),
							notificationContent = notification.querySelector(
									'.wl-notification-content' ),
							setTo;

					notification.classList.add( 'in-view' );
					if ( typeof wishListTimeout !== 'undefined' ) {
						clearTimeout( wishListTimeout );
					}
					wishListTimeout = setTimeout( function() {
						notification.classList.remove( 'in-view', 'added', 'removed' );
					}, 2500 );

					if ( trigger[i].classList.contains( 'item-added' ) ) {
						setTo = false;
						notificationContent.innerHTML = neveWooBooster.i18n.wishListNoticeTextRemove;
						notification.classList.remove( 'added' );
						notification.classList.add( 'removed' );
					} else {
						setTo = true;
						notificationContent.innerHTML = neveWooBooster.i18n.wishListNoticeTextAdd;
						notification.classList.add( 'added' );
						notification.classList.remove( 'removed' );
					}

					trigger[i].classList.toggle( 'item-added' );
					svg.classList.toggle( 'heart-pop' );
					animateHeart( trigger[i] );
					updateWishListCookie( pid, setTo );
				}
		);
	}
}

/**
 * Update the wishlist cookie.
 *
 * @param int productId the product id.
 * @param bool value the status in wishlist.
 */
function updateWishListCookie(productId, value) {
	let cookie = getCookie( 'nv-wishlist' );

	if ( cookie ) {
		cookie = JSON.parse( cookie );
	} else {
		cookie = {};
		createCookie( 'nv-wishlist', JSON.stringify( cookie ), 10 );
	}
	if ( Object.keys( cookie ).length >= 50 ) {
		delete cookie[Object.keys( cookie )[0]];
	}
	cookie[productId] = value;
	createCookie( 'nv-wishlist', JSON.stringify( cookie ), 10 );
}

function handleWishListAsyncUpdate() {
	if ( !neveWooBooster.loggedIn ) return false;
	let updating = false;
	let asyncUpdateInterval = setInterval(
			function() {
				// Don't update if update in progress.
				if ( updating ) return false;

				let cookie = getCookie( 'nv-wishlist' );
				// Don't update if there's no cookie.
				if ( cookie === '' ) return false;

				// Flag update and start update request.
				updating = true;
				axios.post( neveWooBooster.wishListUpdateEndpoint,
						JSON.parse( cookie ), {
							headers: {
								'X-WP-Nonce': neveWooBooster.nonce,
								'Content-Type': 'application/json; charset=UTF-8'
							}
						} ).
						then( function(response) {
							// Flag that update is done.
							updating = false;
							if ( response.status === 200 ) {
								// Check if previous value is what we have now and leave cookie if not.
								if ( cookie !== getCookie( 'nv-wishlist' ) ) {
									return false;
								}

								// Remove data from cookie.
								createCookie( 'nv-wishlist', JSON.stringify( {} ), -1 );
							} else {
								let notification = document.querySelector(
										'.nv-wl-notification' ),
										notificationContent = notification.querySelector(
												'.wl-notification-content' );
								notification.classList.add( 'removed' );
								notificationContent.innerHTML = neveWooBooster.i18n.wishlistError;
							}
						} ).
						catch( function(err) {
							console.error( err.message );
						} );
			}, 3000
	);
}

/**
 * Remove item form wish list on My Account page.
 */
function removeFromWishList() {
	let wlItems = document.querySelectorAll( '.nv-wl-product' );
	if ( wlItems === null ) {
		return false;
	}

	for ( let i = 0; i < wlItems.length; i++ ) {
		let removeBtn = wlItems[i].querySelector( '.remove-wl-item' );

		removeBtn.addEventListener( 'click', function() {
			wlItems[i].classList.add( 'is-loading' );
			let pid = this.getAttribute( 'data-pid' ),
					container = document.querySelector( '.nv-wishlist-wrap' ),
					remainingChildren = container.children.length;

			pid = parseInt( pid );
			updateWishListCookie( pid, false );

			if ( remainingChildren === 0 ) {
				container.innerHTML = neveWooBooster.i18n.emptyWishList;
				return false;
			}

			removeFadeOut( wlItems[i], 200 );
		} );
	}
}

/**
 * Animate hearts to spread and disappear.
 * @param heart
 */
function animateHeart(heart) {
	let clones = randomInt( 2, 4 );
	for ( let it = 1; it <= clones; it++ ) {
		let clone = heart.querySelector( 'svg' ).cloneNode( true ),
				size = randomInt( 5, 16 );
		heart.appendChild( clone );
		clone.classList.remove( 'heart-pop' );
		clone.setAttribute( 'width', size );
		clone.setAttribute( 'height', size );
		clone.style.position = 'absolute';
		clone.style.transition = 'transform 0.5s cubic-bezier(0.12, 0.74, 0.58, 0.99) 0.3s, opacity 1s ease-out .5s';
		let animTimeout = setTimeout( function() {
			clone.style.transform = 'translate3d(' +
					( plusOrMinus() * randomInt( 10, 20 ) ) + 'px,' +
					( plusOrMinus() * randomInt( 10, 20 ) ) + 'px,0)';
			clone.style.opacity = 0;
			clearTimeout( animTimeout );
		}, 1 );
		let removeNodeTimeout = setTimeout( function() {
			clone.parentNode.removeChild( clone );
			clearTimeout( removeNodeTimeout );
		}, 2500 );
	}
}

/**
 * Returns +/- 1.
 * @returns {number}
 */
function plusOrMinus() {
	return Math.random() < 0.5 ? -1 : 1;
}

/**
 * Random int between min and max.
 *
 * @param min
 * @param max
 * @returns {number}
 */
function randomInt(min, max) {
	return Math.floor( Math.random() * ( max - min + 1 ) + min );
}

/**
 * Fade out the element and then remove it.
 * @param el
 * @param speed
 */
function removeFadeOut(el, speed) {
	let seconds = speed / 1000;
	el.style.transition = 'opacity ' + seconds + 's ease';
	el.style.opacity = 0;
	setTimeout(
			function() {
				el.parentNode.removeChild( el );
			},
			speed
	);
}

function createCookie(name, value, days) {
	let expires;
	let date = new Date();
	date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
	expires = '; expires=' + date.toGMTString();
	document.cookie = name + '=' + value + expires + '; path=/';
}

function getCookie(cname) {
	let name = cname + '=',
			decodedCookie = decodeURIComponent( document.cookie ),
			ca = decodedCookie.split( ';' );

	for ( let i = 0; i < ca.length; i++ ) {
		let c = ca[i];
		while (c.charAt( 0 ) === ' ') {
			c = c.substring( 1 );
		}
		if ( c.indexOf( name ) === 0 ) {
			return c.substring( name.length, c.length );
		}
	}
	return '';
}

export {
	initializeWishList
};
