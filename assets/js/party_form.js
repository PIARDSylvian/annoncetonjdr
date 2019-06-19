/*
 * annoncetonjdr V3
 */
require('../scss/party_form.scss');
global.moment = require('moment');
require('tempusdominus-bootstrap-4');
require('select2');

$(function () {
    $('#datetimepicker').datetimepicker({
        format: 'DD-MM-YYYY HH:mm',
        extraFormats: [ 'DD-MM-YYYY HH:mm' ],
        defaultDate: new Date($.now()),
        locale: 'fr',
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
        //     // Insert the tag at the end of the results
        //     console.log(tag);
        //     data.push(tag);}
    });
});