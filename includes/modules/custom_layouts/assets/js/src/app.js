import { initializeCustomEditor } from './custom-editor.js';
import { initializeConditionals } from './conditionals.js'

function run() {
	initializeCustomEditor();
	initializeConditionals();
}


window.addEventListener(
	'DOMContentLoaded',
	function () {
		run();
	}
);
