/* global wc_single_product_params */

let vidWrap;

export function initVideoAdaptation () {
  vidWrap = document.querySelector('.nv-video-wrap');
  if (vidWrap === null)
    return false;

  handlePhotoSwipeAdaptation();
  hideZoom();

  /**
   * Dispatch a resize event for video size issues
   */
  window.addEventListener('load', function (event) {
    window.dispatchEvent(new Event('resize'));
  });
}

function handlePhotoSwipeAdaptation () {
  wc_single_product_params.photoswipe_options.index = -1;
  let observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.attributeName.toLowerCase() !== 'style')
        return false;

      let activeSlide = document.getElementsByClassName('flex-active-slide')[0];

      let i = 0;

      while ((activeSlide = activeSlide.previousSibling) != null) {
        i++;
      }

      wc_single_product_params.photoswipe_options.index = i - 2;
    });
  });

  let galleryWrap = document.getElementsByClassName('woocommerce-product-gallery__wrapper');
  observer.observe(galleryWrap[0], {
    attributes: true
  });
}

function hideZoom () {
  let thumbs = document.querySelector('.flex-control-thumbs'),
      zoomTrigger = document.querySelector('.woocommerce-product-gallery__trigger'),
      gallery = document.querySelector('.woocommerce-product-gallery__wrapper');

  zoomTrigger.style.display = 'none';
  thumbs.addEventListener('click', function (e) {
    if (e.target.tagName !== 'IMG')
      return false;

    if (gallery.style.transform !== 'translate3d(0px, 0px, 0px)') {
      zoomTrigger.style.display = 'block';
      let iframe = vidWrap.querySelector('iframe');
      if (iframe !== null) {
        iframe.contentWindow.postMessage(JSON.stringify({
          event: 'command',
          func: 'pauseVideo',
          args: []
        }), '*');
      }
      return false;
    }
    zoomTrigger.style.display = 'none';
  });
}