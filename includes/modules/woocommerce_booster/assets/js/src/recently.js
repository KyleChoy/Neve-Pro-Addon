/* global neveWooBooster */


/**
 * Initialize the widget.
 */
let recentlyBox;

export function initializeRecentlyViewed () {
  if (neveWooBooster.recentlyViewedStatus !== 'enabled')
    return false;

  recentlyBox = document.querySelector('.nv-recently-viewed');

  if (recentlyBox === null) {
    return false;
  }

  handleToggling();
  handleSelfHide();
}


function handleToggling () {
  let close = recentlyBox.querySelector('.close');
  close.addEventListener('click', function () {
    recentlyBox.classList.toggle('expanded');
  });
}

function handleSelfHide () {
  setTimeout(
      function () {
        recentlyBox.classList.remove('expanded');
      }, 1000);
}