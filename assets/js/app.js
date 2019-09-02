/*
 * annoncetonjdr V3
 */
require('../scss/app.scss');
var $ = require('jquery');
require('bootstrap');

global.markerclusterer = require('@google/markerclusterer');

/**
 * initMap For Google map api
 */
window.initMap = function () {
    var option = {disableDefaultUI: true};
    var iw = new google.maps.InfoWindow({maxWidth: 350});
    if(party) {
        if($.isArray(party)) {
            option.center = party[0]
        }
        else {
            option.center = {lat:party.lat, lng:party.lng}
        }
        option.zoom = 9
    }
    else {
        option.center = {lat: 48.866667, lng: 2.333333}, // Paris
        option.zoom = 6
    };
    map = new google.maps.Map(document.getElementById('map'), option);

    if(party) {
        if($.isArray(party)) {
            var markers = party.map(function(party, i) {
                var option = {position: party};
                if (party.length) {
                    option.label = party.length;
                }
                marker =  new google.maps.Marker(option);
                var content = party.desc;
                infoWindow(iw, marker, map, content)

                return marker;

            });

            var options = {
            imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
            }   ;
            var markerCluster = new markerclusterer(map, markers, options);
        }
        else {
            var marker = new google.maps.Marker({
                position: {lat:party.lat, lng:party.lng},
                map: map,
                title: party.name
            });
        }
    }
}

function infoWindow(iw, marker, map, content) {
    google.maps.event.addListener(marker, 'click', function() {
        iw.setContent(content);
        iw.open(map, marker);
    });
};

$(function () {
    console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
});

