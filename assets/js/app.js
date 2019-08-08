/*
 * annoncetonjdr V3
 */
require('../scss/app.scss');
var $ = require('jquery');
require('bootstrap');

// document.addEventListener('DOMContentLoaded', function() {

    window.initMap = function () {
        console.log('ccc');
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 8
          });
    }
// });

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
