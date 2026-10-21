import _ from 'lodash';
import jquery from 'jquery';
import select2 from 'select2';
import flatpickr from 'flatpickr';

window._ = _;

// Reuse the jQuery the layout already loaded so plugins attached to it (jQuery UI,
// Owl Carousel, Fancybox) keep working; fall back to the bundled copy.
window.$ = window.jQuery = window.jQuery || jquery;
select2(window, window.jQuery);

window.flatpickr = flatpickr;
