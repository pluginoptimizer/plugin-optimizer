function check_speed() {
    sessionStorage.now = Date.now();
    setTimeout(check_speed, 25);
}

let speedTest;

(function ($) {
    'use strict';

    $(document).ready(function () {
        //change plugins
        speedTest = () => {
            window.onload = function() {
                const now = Date.now();
                if ( sessionStorage.now ) {
                    const loaded_in = now - parseInt(sessionStorage.now);
                    $('.sos-speed').text(` | ${Number.parseFloat(loaded_in  *  0.001).toFixed(2)} s | ${Number.parseFloat($('html').html().length / 1024).toFixed(2)} kB` );
                }
                check_speed();
            };
        }
    });
})(jQuery);

export {speedTest};