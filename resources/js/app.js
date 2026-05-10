/**
 * Nails by Mona — app.js
 * jQuery 4 imported here and exposed globally so Blade templates
 * can use $ without additional module imports.
 */

import $ from 'jquery';

// Expose globally for inline Blade scripts
window.$ = $;
window.jQuery = $;
