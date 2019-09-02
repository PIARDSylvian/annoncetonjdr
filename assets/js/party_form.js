/*
 * annoncetonjdr V3
 */
require('../scss/party_form.scss');
global.moment = require('moment');
require('tempusdominus-bootstrap-4');
require('select2');

$(function () {

    $('#party_date').datetimepicker({
        format: 'DD-MM-YYYY HH:mm',
        extraFormats: [ 'DD-MM-YYYY HH:mm' ],
        inline: true,
        sideBySide: true,
        locale: 'fr',
        minDate: new Date($.now()),
        icons: {
                    time: "fa fa-clock"
                }
    });
    
    $('#party_gameName').select2({
        tags: true,
        placeholder: "Select a game",
        allowClear: true,
        theme: 'bootstrap4',
        
        // insertTag: function (data, tag) {
        // Insert the tag at the end of the results
        //     console.log(tag);
        //     data.push(tag);}
    });

    $('#party_gameName').on('select2:select', function (e) {
        var data = e.params.data;
        console.log(data);
    });

    var input = document.getElementById('party_address');
    var options = {
        componentRestrictions: {country: 'fr'}
    };  
    autocomplete = new google.maps.places.Autocomplete(input, options);

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            input.value = '';

            updateTextFields('','');
            return;
        }

        updateTextFields(place.geometry.location.lat(),place.geometry.location.lng());
    });

    function updateTextFields(lat, lng) {
        document.getElementById('party_lat').value = lat;
        document.getElementById('party_lng').value = lng;
    }

    // Trigger search on blur
    google.maps.event.addDomListener(document.getElementById("party_address"), 'blur', function() {
        if (jQuery('.pac-item:hover').length === 0 ) {
            google.maps.event.trigger(this, 'focus', {});
            google.maps.event.trigger(this, 'keydown', {
                keyCode: 13
            });
        } 
    });
});