import {initializeInfiniteScroll} from "./infinite.js";

function run() {
    initializeInfiniteScroll();
}

window.addEventListener(
    'load',
    function () {
        run();
    }
);
