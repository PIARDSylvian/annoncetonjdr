/*
 * annoncetonjdr V3
 */
var $ = require('jquery');
$(function() {
    $('form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url: "http://annoncetonjdr/forgotten_password",
            data: $("form").serialize(),
        })
        .done(function(ajax_response) {
            if(ajax_response.url) {
                window.location.href = ajax_response.url;
            }
            else {
                $('form button').before(ajax_response.response);
            }
        });
    });
});
