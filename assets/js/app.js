/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import '../scss/app.scss';
import 'bootstrap';
import L from 'leaflet';

/*
 * annoncetonjdr V3
 */
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


$(function() {
    function initmap() {
        var map = L.map('map').setView([46.866667, 2.333333], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        $.each(party, function(i, item) {
            let popup = item.address + '<br>';
            if(item.parties && item.parties.length > 0) {
                popup += '<br>parties :'
                $.each( item.parties, function(idx, part){
                        popup +='<br><a href="' + part.url + '">' + part.partyName + '</a>';
                });
            };
            if(item.events && item.events.length > 0) {
                popup += '<br>events :'
                $.each( item.events, function(idx, event){
                        popup += '<br><a href="' + event.url + '">' + event.name + '</a>';
                });
            };
            if(item.association) {
                popup += '<br>association :'
                popup += '<br><a href="' + item.association.url + '">' + item.association.name + '</a>';
            };
            L.marker([item.lat, item.lng]).addTo(map)
            .bindPopup(popup);
        });
    }

    initmap();
});
