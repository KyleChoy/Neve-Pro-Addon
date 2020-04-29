/**
 * Exported function that calls the initialization
 */
export default function repeater() {
	init();
}

/**
 * Initialize the repeaters.
 */
function init() {
	let repeaters = document.querySelectorAll( '.nv-repeater--wrap' );
	_.each( repeaters, function(repeater, id) {
		let reorderButton = repeater.querySelector( '.nv-repeater--reorder' ),
				addButton = repeater.querySelector( '.nv-repeater--add-new' ),
				items = repeater.querySelectorAll(
						'.nv-repeater--items-wrap .nv-repeater--item' ),
				itemsWrap = repeater.querySelector( '.nv-repeater--items-wrap' ),
				itemTemplate = repeater.querySelector(
						'.nv-repeater--hidden-item .nv-repeater--item' );

		// Initialize all repeater items.
		_.each( items, function(item, index) {
			initializeRepeaterItem( item, repeater );
		} );

		// Initialize add button.
		addButton.addEventListener( 'click', function(e) {
			let newItem = itemTemplate.cloneNode( true );
			initializeRepeaterItem( newItem, repeater );
			itemsWrap.appendChild( newItem );
			updateValues( repeater );
		} );

		// Initialize the reordering.
		reorderButton.addEventListener( 'click', function(e) {
			e.preventDefault();
			repeater.classList.toggle( 'reordering' );
			updateValues( repeater );
		} );
	} );
}

function initializeRepeaterItem(item, repeater) {
	let visibilityToggle = item.querySelector( '.nv-repeater--toggle' ),
			expander = item.querySelector( '.nv-repeater--item-title' ),
			orderButtonDown = item.querySelector( '.reorder-btn.down' ),
			orderButtonUp = item.querySelector( '.reorder-btn.up' ),
			removeButton = item.querySelector( '.nv-repeater--remove-item' ),
			titleInput = item.querySelector( 'input[data-key="title"]' ),
			titleTag = item.querySelector( '.nv-repeater--title-text' ),
			colorInputs = item.querySelectorAll( '.color-picker-hex' ),
			valueInputs = item.querySelectorAll( '.has-value' ),
			iconPickers = item.querySelectorAll( '.nv--icon-field-wrap' );

	// Toggle Visibility.
	visibilityToggle.addEventListener( 'click', function() {
		this.parentNode.classList.toggle( 'visibility-hidden' );
		this.setAttribute( 'data-value',
				this.getAttribute( 'data-value' ) === 'yes' ? 'no' : 'yes' );
		updateValues( repeater );
	} );

	// Expand Item.
	expander.addEventListener( 'click', function() {
		this.parentNode.parentNode.classList.toggle( 'expanded' );
	} );

	// Remove Item.
	removeButton.addEventListener( 'click', function() {
		item.parentNode.removeChild( item );
		updateValues( repeater );
	} );

	// Move Item Down.
	orderButtonDown.addEventListener( 'click', function(e) {
		e.stopPropagation();
		let nextItem = item.nextSibling;
		if ( !nextItem ) {
			return false;
		}
		nextItem.parentNode.insertBefore( item, nextItem.nextSibling );
		updateValues( repeater );
	} );

	// Move Item Up.
	orderButtonUp.addEventListener( 'click', function(e) {
		e.stopPropagation();
		let previousItem = item.previousSibling;
		if ( !previousItem ) {
			return false;
		}
		item.parentNode.insertBefore( item, item.previousSibling );
		updateValues( repeater );
	} );

	// Sync Title.
	titleInput.addEventListener( 'input', function(e) {
		titleTag.innerHTML = this.value !== '' ?
				this.value :
				titleTag.dataset.default;
		updateValues( repeater );
	} );

	// Initialize Color Pickers.
	_.each( colorInputs, function(input) {
		jQuery( input ).wpColorPicker( {
			change: function() {
				// Timeout as iris color picker is a bit weird.
				setTimeout( function() {
					updateValues( repeater );
				}, 1 );
			},
			clear: function() {
				updateValues( repeater );
			}
		} );
	} );

	// Initialize Icon Pickers
	_.each( iconPickers, function(iconPicker) {
		let selectButton = iconPicker.querySelector( '.nv--icon-selector' ),
				input = iconPicker.querySelector( 'input' ),
				clear = iconPicker.querySelector( '.nv--remove-icon' ),
				icons = iconPicker.querySelectorAll( '.nv--icons-container > a' ),
				search = iconPicker.querySelector( '.nv--icons-search > input' );

		// Toggle picker
		selectButton.addEventListener( 'click', function(e) {
			e.preventDefault();
			iconPicker.classList.toggle( 'nv--iconpicker-expanded' );
			search.value = '';
			search.dispatchEvent( new Event( 'input' ) );
			search.focus();
		} );

		// Icon clear
		clear.addEventListener( 'click', function(e) {
			e.preventDefault();
			input.value = '';
			selectButton.innerHTML = '<span class="dashicons dashicons-plus"></span>';
			let selected = iconPicker.querySelector( 'a.selected' );
			if ( selected !== null ) {
				selected.classList.remove( 'selected' );
			}
			updateValues( repeater );
		} );

		// Icon selection
		_.each( icons, function(icon) {
			icon.addEventListener( 'click', function(e) {
				e.preventDefault();
				selectButton.innerHTML = icon.innerHTML;
				input.value = icon.dataset.icon;
				let selected = iconPicker.querySelector( 'a.selected' );
				if ( selected !== null ) {
					selected.classList.remove( 'selected' );
				}
				icon.classList.add( 'selected' );
				iconPicker.classList.remove( 'nv--iconpicker-expanded' );
				updateValues( repeater );
			} );
		} );

		// Search functionality
		search.addEventListener( 'input', function(e) {
			let filter = e.target.value.toLowerCase().replace( /\s+/g, '' );
			console.log( filter );
			_.each( icons, function(icon) {
				if ( icon.dataset.icon.toLowerCase().indexOf( filter ) > -1 ) {
					icon.style.display = '';
				} else {
					icon.style.display = 'none';
				}
			} );
		} );
	} );

// Initialize inputs that have a value.
	_.each( valueInputs, function(input) {
		input.addEventListener( 'change', function(event) {
			updateValues( repeater );
		} );
	} );
}

/**
 * Update the repeater values.
 *
 * @param repeater
 */
function updateValues(repeater) {
	let collectorInput = repeater.querySelector( '.nv-repeater--collector' ),
			items = repeater.querySelectorAll(
					'.nv-repeater--items-wrap .nv-repeater--item' ),
			newValue = [];

	_.each( items, function(item, index) {
		let inputs = item.querySelectorAll( '.has-value' ),
				itemValue = {};
		_.each( inputs, function(input, index) {
			let key = input.dataset.key, value;
			if( input.getAttribute( 'type' ) === 'checkbox' ) {
				value = input.checked;
			} else {
				value = input.dataset.value ? input.dataset.value : input.value;
			}
			itemValue[key] = value;
		} );
		newValue.push( itemValue );
	} );

	collectorInput.value = JSON.stringify( newValue );
	jQuery( collectorInput ).trigger( 'change' );
}
