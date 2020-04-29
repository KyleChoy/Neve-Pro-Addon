function handleTransparent() {
	let stickyRows = document.querySelectorAll( '.header--row.is_sticky' );
	if ( stickyRows.length > 0 ) {
		return false;
	}
	let hfgHeader = document.querySelector( '.hfg_header' );
	document.querySelector(
			'.neve-main' ).style.marginTop = '-' + hfgHeader.offsetHeight + 'px';
}

function initHeader() {
	let
			stickyRows = document.querySelectorAll( '.header--row.is_sticky' ),
			transparent = document.querySelector( '.neve-transparent-header' );

	if ( stickyRows.length > 0 ) {
		addPlaceholderAndStickHeader()
		let rowContainer = document.querySelector( '.hfg_header' ),
			headerTag = document.querySelector( 'header.header' )
		startObserving( rowContainer, headerTag )
	}
	if ( transparent !== null ) {
		handleTransparent()
	}
}

function  startObserving( rowsWrap, wrapTag ) {
	const observer = new IntersectionObserver( (entries) => {
		if ( entries[0].isIntersecting === true ) {
			rowsWrap.classList.remove( 'is-stuck' );
			return false;
		}
		rowsWrap.classList.add( 'is-stuck' );
	}, { rootMargin: '20px 0px 25px 0px' } );
	observer.observe( wrapTag );
}

function initFooter() {
	let stickyRows = document.querySelectorAll('.footer--row.is_sticky');
	if( stickyRows.length > 0 ) {
		addPlaceholderAndStickFooter();
		let rowContainer = document.querySelector( '.hfg_footer' ),
			footerTag = document.querySelector( 'footer' );
		startObserving( rowContainer, footerTag )
	}
}

function addPlaceholderAndStickFooter() {
	let placeholder = document.querySelector(
			'.sticky-footer-placeholder' ),
			hfgFooter = document.querySelector( '.hfg_footer' );
	if ( placeholder === null ) {
		placeholder = document.createElement( 'div' );
		placeholder.classList.add( 'sticky-footer-placeholder' );
		hfgFooter.parentNode.insertBefore(placeholder, hfgFooter.nextSibling);
	}
	hfgFooter.classList.add( 'has-sticky-rows' );
	placeholder.style.height = hfgFooter.offsetHeight + 'px';
}

function addPlaceholderAndStickHeader() {
	let headerPlaceholder = document.querySelector(
			'.sticky-header-placeholder' ),
			hfgHeader = document.querySelector( '.hfg_header' ),
			transparent = document.querySelector( '.neve-transparent-header' );

	if ( headerPlaceholder === null && transparent === null ) {
		headerPlaceholder = document.createElement( 'div' );
		headerPlaceholder.classList.add( 'sticky-header-placeholder' );
		hfgHeader.parentNode.insertBefore(headerPlaceholder, hfgHeader.nextSibling);
	}
	hfgHeader.classList.add( 'has-sticky-rows' );
	if ( headerPlaceholder !== null ) {
		headerPlaceholder.style.height = hfgHeader.offsetHeight + 'px';
	}
}

window.addEventListener(
		'load',
		function() {
			initHeader();
			initFooter();
		}
);
window.addEventListener(
		'selective-refresh-content-rendered',
		function() {
			initHeader();
		}
);

/**
 * Do resize events debounced.
 */
let neveResizeTimeout;
window.addEventListener( 'resize', function() {
	clearTimeout( neveResizeTimeout );
	neveResizeTimeout = setTimeout( initHeader, 500 );
} );
