/* global neveWooBooster, neveInfiniteScroll */
import inView from 'in-view'
import axios from 'axios'

import { initializeModal } from './modal.js'
import { initializeWishList } from './wish-list.js'

/**
 * Trigger request once the sentinel is in view.
 */
function initializeInfiniteScroll() {
  let page = 2
  let lock = false
  let trigger = document.querySelector( '.load-more-products' )

  if ( trigger === null ) {
    return false
  }

  if ( typeof neveInfiniteScroll !== 'object' ) {
    return false
  }

  inView( '.load-more-products' ).on( 'enter', () => {
    if ( lock ) {
      return false
    }
    if( typeof parent.wp.customize !== 'undefined' ) {
      parent.wp.customize.requestChangesetUpdate()
    }
    document.querySelector(
      '.load-more-products .nv-loader' ).style.display = 'block'
    lock = true

    getProducts( page ).then( () => {
      page++
      lock = false
    } )
  } )
}

const getProducts = (page) => {
  return new Promise( (resolve, reject) => {
    let base_url = `${neveInfiniteScroll.settings.base_url}page/${page}/`
    let url_args = JSON.parse( neveInfiniteScroll.settings.url_args )

    if( neveInfiniteScroll.settings.plain_permalinks ) {
      base_url = `${neveInfiniteScroll.settings.base_url}/`
      url_args['paged'] = page;
    }

    let args_length = Object.keys( url_args ).length

    const elem = document.querySelector( '.load-more-products' )
    const shop = document.querySelector( '.nv-shop ul.products' )

    if ( args_length > 0 ) {
      base_url = base_url + '?'
    }

    let index = 1
    Object.keys( url_args ).forEach( function(key) {
      base_url = base_url + key + '=' + url_args[key]
      if ( index !== args_length ) {
        base_url = base_url + '&'
      }
      index++
    } )

    let config = {
      headers: {
        'X-WP-Nonce': neveWooBooster.nonce,
        'Content-Type': 'application/json; charset=UTF-8'
      }
    }
    axios.post( base_url, {}, config ).then( response => {
      let data = response.data
      if ( response.status === 200 && data.type === 'success' ) {
        shop.innerHTML += data.html
        initializeModal()
        initializeWishList()
        if ( data.lastbatch ) {
          elem.parentNode.removeChild( elem )
        }
      }
      resolve()
    } ).catch( (error) => {
      reject( error )
    } )
  } )
}

export {
  initializeInfiniteScroll
}
