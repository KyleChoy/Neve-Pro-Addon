/* global neveLifterBooster */
import inView from "in-view";
import axios from 'axios';

let page = 2;

/**
 * Trigger request once the sentinel is in view.
 */
function initializeInfiniteScroll() {
    inView('.lifter-load-more-posts').on('enter', get_more_courses);
}

/**
 * Request for more posts.
 */
function get_more_courses() {
    document.querySelector('.nv-loader').style.display = "block";
    if (typeof parent.wp.customize === 'undefined') {
        request_more_courses();
    } else {
        parent.wp.customize.requestChangesetUpdate().then(
            request_more_courses
        );
    }
}

/**
 * Request for more products.
 */
function request_more_courses() {

    let requestUrl = neveLifterBooster.infiniteCoursesEndpoint + page + '/';
    let elem = document.querySelector('.load-more-courses');
    if (elem === null) {
        elem = document.querySelector('.load-more-memberships');
        if (elem === null) {
            return;
        }
        requestUrl = neveLifterBooster.infiniteMembershipsEndpoint + page + '/';
    }
    if (typeof wp.customize !== 'undefined') {
        requestUrl += '?customize_changeset_uuid=' + wp.customize.settings.changeset.uuid + '&customize_autosaved=on';
    }
    if (typeof _wpCustomizeSettings !== 'undefined') {
        requestUrl += '&customize_preview_nonce=' + _wpCustomizeSettings.nonce.preview;
    }

    let config = {
        headers: {
            'X-WP-Nonce': neveLifterBooster.nonce,
            'Content-Type': 'application/json; charset=UTF-8',
        }
    };
    let data = JSON.stringify( { query: neveLifterBooster.infiniteScrollQuery } );
    let listing = document.querySelector('.llms-course-list');
    if (listing === null) {
        listing = document.querySelector('.llms-membership-list');
        if (listing === null) {
            return;
        }
    }

    axios.post( requestUrl, data, config ).then( response => {
        let data = response.data;

        if( response.status === 204 ){
            elem.parentNode.removeChild( elem );
        }
        if( response.status === 200 ){
            listing.innerHTML += data.markup;
            page++;
            if (inView.is(elem)) {
                request_more_courses();
            }
        }
    }).catch( ( error ) => {
        let response = error.response.data;
        console.error( response.message );
        elem.parentNode.removeChild( elem );
    });
}

export {
    initializeInfiniteScroll
};
