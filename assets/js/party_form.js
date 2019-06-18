/*
 * annoncetonjdr V3
 */
require('../scss/party_form.scss');
global.moment = require('moment');
require('tempusdominus-bootstrap-4');

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
});