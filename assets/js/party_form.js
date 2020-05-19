/*
 * annoncetonjdr V3
 */
import '../scss/party_form.scss';

global.moment = require('moment');
require('tempusdominus-bootstrap-4');
import 'select2';

$(function () {

    let dateTime = new Date($.now());
    dateTime.setHours(dateTime.getHours()+2);
    dateTime.setMinutes(dateTime.getMinutes()+1);

    let option= {
        format: 'YYYY-MM-DDTHH:mm',
        extraFormats: [ 'YYYY-MM-DDTHH:mm:ss' ],
        minDate: dateTime,
        inline: true,
        sideBySide: true,
        locale: 'fr',
        icons: {
            time: "fa fa-clock"
        },
    };

    /* Party */
    const party = $('.date_container #party_date');
    if (party) {
        if (party.val()) {
            option.defaultDate = moment(party.val());
        }
        party.datetimepicker(option);
    }

    /* Event */
    const eventStart = $('.date_container #event_dateStart');
    const eventFinish = $('.date_container #event_dateFinish');
    if (eventStart && eventFinish) {
        if (eventStart.val() && eventFinish.val()) {
            option.defaultDate = moment(eventStart.val());
        }
        eventStart.datetimepicker(option);
        if (eventFinish.val()) {
            option.defaultDate = moment(eventFinish.val());
        }
        eventFinish.datetimepicker(option);
    }

    if ($('.date_container #event_dateFinish') && $('.date_container #event_dateFinish').val()) {
        option.defaultDate = moment($('.date_container input').val());
    }

    $('.date_container input').datetimepicker(option);
    
    $('#party_gameName').select2({
        tags: true,
        placeholder: "Select a game",
        allowClear: true,
        theme: 'bootstrap4',
    });

    $('#party_gameName').on('select2:select', function (e) {
        var data = e.params.data;
    });

    if($('[id$="_address_address"]').attr('value')) {
        var newOption = new Option($('[id$="_address_address"]').attr('value'), 1, false, false);
        $('#select2-address').append(newOption).trigger('change');
    }

    $("#select2-address").select2({
        ajax: {
            url: "http://photon.komoot.de/api",
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term, // search term
                    lang: 'fr'
                };
            },
            processResults: function (data) {
                let o_data = [];
                $.each(data.features, function(idx, i_data){
                    let l_str = "";
                    if (i_data.properties.name) { l_str += i_data.properties.name }
                    if (i_data.properties.postcode) { l_str += ", " + i_data.properties.postcode }
                    if (i_data.properties.city) { l_str += ", " + i_data.properties.city }
                    if (i_data.properties.state) { l_str += ", " + i_data.properties.state }
                    if (i_data.properties.name) { l_str += ", " + i_data.properties.country}

                    o_data.push({
                        id: i_data.properties['osm_id'],
                        lat: i_data.geometry.coordinates[1],
                        lng: i_data.geometry.coordinates[0],
                        name: l_str
                    });
                });

                return {
                    results: o_data
                };
            }
        },
        placeholder: 'Search',
        allowClear: true,
        theme: 'bootstrap4',
        minimumInputLength: 3,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

    $('#select2-address').on('select2:clear', function (e) {
        $('[id$="_address_address"]').attr('value', '');
        $('[id$="_lat"]').val(null);
        $('[id$="_lng"]').val(null);
    });

    function formatRepo (i_data) {
        if (i_data.loading) {
            return i_data.text;
        }

        var $container = $(
            "<div class='select2-result-repository clearfix'>" + 
                "<div class='select2-result-repository_data'></div>" +
                "<div class='select2-result select2-result-lat d-none'>" + i_data.lat + "</div>" +
                "<div class='select2-result select2-result-lng d-none'>" + i_data.lng + "</div>" +
            "</div>"
        );

        $container.find(".select2-result-repository_data").text(i_data.name);

        return $container;
    }

    function formatRepoSelection (i_data) {
        updateTextFields(i_data.name, i_data.lat, i_data.lng);
        return i_data.name || i_data.text;
    }

    function updateTextFields(val, lat, lng) {
        if (val) {
            $('[id$="_address_address"]').attr('value', val);
            $('[id$="_lat"]').val(lat);
            $('[id$="_lng"]').val(lng);
        }
    }
});