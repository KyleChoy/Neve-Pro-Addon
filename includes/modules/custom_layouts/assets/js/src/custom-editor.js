import axios from 'axios';

function initializeCustomEditor() {
	let NeveCustomEditor = {
		init: function () {
			this.cache();
			this.bindEvents();
            this.saveContent();
		},
		isGutenberg: function () {
			return document.getElementById( 'editor' ) !== null;
		},
		cache: function () {
			this.cache = {};

			if ( this.isGutenberg() === true ) {
				this.cache.$editor = document.getElementById( 'editor' );
				this.cache.$switchMode = document.getElementById( 'neve-gutenberg-button-switch-mode' ).innerHTML;
				this.cache.$editor.querySelectorAll( '.edit-post-header-toolbar' )[0].insertAdjacentHTML( 'beforeend', this.cache.$switchMode );
			} else {
				this.cache.$editor = document.getElementById( 'post-body-content' );
				this.cache.$switchMode = document.getElementById( 'neve-gutenberg-button-switch-mode' ).innerHTML;
				document.getElementById( 'titlediv' ).outerHTML += this.cache.$switchMode;
			}

			this.isNeveEditorMode = '1' === document.getElementById( 'neve-switch-editor-mode' ).value;
			this.cache.$switchModeButton = document.getElementById( 'neve-switch-mode-button' );


			let body = document.getElementsByTagName( 'body' )[ 0 ];
			if ( body.classList.contains( 'neve-custom-editor-mode' ) ) {
				this.buildPanel();
			}
		},

		buildPanel: function () {
			let self = this;
			let editorContent = document.getElementById( 'neve-gutenberg-panel' ).innerHTML;
			if ( editorContent !== '' ) {
				document.getElementsByTagName( 'body' )[ 0 ].classList.add( 'neve-custom-editor-mode' );
				let editor = self.cache.$editor.querySelectorAll( '.editor-block-list__layout, .block-editor-block-list__layout' )[ 0 ];
				if ( self.isGutenberg() !== true ) {
					editor = document.getElementById( 'postdivrich' );
				}
				let wrapper = document.createElement( 'div' );
				wrapper.innerHTML = editorContent;
				editor.parentNode.insertBefore( wrapper.firstChild, editor.nextSibling );
				self.cache.$codemirror = wp.CodeMirror.fromTextArea(
					document.getElementById( 'neve-advanced-hook-php-code' ), {
						lineNumbers: true,
						mode: 'application/x-httpd-php-open',
						lint: true,
						gutters: [ 'CodeMirror-lint-markers' ],
						styleActiveLine: true,
						matchBrackets: true,
					}
				);
				let documentTitle = wp.data.select('core/editor').getEditedPostAttribute('title');
				if (!documentTitle) {
					wp.data.dispatch('core/editor').editPost({ title: 'Custom layout #' + document.getElementById('post_ID').value });
				}
			}
		},


		bindEvents: function () {
			let self = this;
			self.cache.$switchModeButton.addEventListener(
				'click',
				function () {

					let data = {
						'neve_editor_mode': "0"
					};
					postRequest( data ).then(
						function ( response ) {
							let mode = response.data.neve_editor_mode;
							if ( mode === '0' ) {
								self.cache.$switchModeButton.classList.add( 'button-primary' );
								self.cache.$switchModeButton.querySelectorAll( '.neve-switch-mode-on' )[ 0 ].classList.add( 'hidden' );
								self.cache.$switchModeButton.querySelectorAll( '.neve-switch-mode-off' )[ 0 ].classList.remove( 'hidden' );
								self.removePanel();
								window.scroll( 100, 100 );
							} else {
								self.cache.$switchModeButton.classList.remove( 'button-primary' );
								self.cache.$switchModeButton.querySelectorAll( '.neve-switch-mode-on' )[ 0 ].classList.remove( 'hidden' );
								self.cache.$switchModeButton.querySelectorAll( '.neve-switch-mode-off' )[ 0 ].classList.add( 'hidden' );
								self.buildPanel();
							}
						}
					);

				}
			);
		},

		removePanel: function () {
			let body = document.getElementsByTagName( 'body' )[ 0 ];
			body.classList.remove( 'neve-custom-editor-mode' );
			document.getElementById( 'neve-editor' ).remove();
		},

		saveContent: function () {
			let self = this;
			if ( typeof  self.cache === 'undefined' ){
				return;
			}

			if ( self.isGutenberg() === false ) {
				let saveTrigger = document.getElementById( 'publish' );
				saveTrigger.addEventListener( 'click', function () {
					let editorValue = self.cache.$codemirror.getValue();
					let data = {
						'neve_editor_content': editorValue
					};
					postRequest( data );
				} );
			} else {
                wp.data.subscribe(lodash.debounce(() => {
                    let isSavingPost = wp.data.select('core/editor').isSavingPost();
                    let isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();
                    let isPublishingPost = wp.data.select('core/editor').isPublishingPost();
                    if ( ( isPublishingPost || isSavingPost ) && !isAutosavingPost) {
                        if( typeof  self.cache.$codemirror === 'undefined' ){
                            return;
                        }
                        let editorValue = self.cache.$codemirror.getValue();
                        let data = {
                            'neve_editor_content': editorValue
                        };
                        postRequest( data );
                    }
                }));
			}
		}
	};

	NeveCustomEditor.init();
}


function postRequest( data ) {
	return new Promise(
		function ( resolve, reject ) {
			let requestUrl = neveCustomLayouts.customEditorEndpoint;
			let config = {
				headers: {
					'X-WP-Nonce': neveCustomLayouts.nonce,
					'Content-Type': 'application/json; charset=UTF-8',
				}
			};
			axios.post( requestUrl, JSON.stringify( data ), config ).then(response => {
				resolve( response );
			}).catch( error => {
				let response = error.response.data;
				reject(
					{
						status: this.status,
						statusText: response.statusText
					}
				);
			});
		}
	);
}

/**
 * Convert a string to HTML entities
 */
String.prototype.toHtmlEntities = function () {
	return this.replace( /./gm, function ( s ) {
		return "&#" + s.charCodeAt( 0 ) + ";";
	} );
};

/**
 * Create string from HTML entities
 */
String.fromHtmlEntities = function ( string ) {
	return (string + "").replace( /&#\d+;/gm, function ( s ) {
		return String.fromCharCode( s.match( /\d+/gm )[ 0 ] );
	} );
};

export {
	initializeCustomEditor
};
