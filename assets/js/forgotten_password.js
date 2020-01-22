/*
 * annoncetonjdr V3
 */
var $ = require('jquery');
$(function() {
    $('form').on('submit', function(event){
        event.preventDefault();

        let $submitBtn = $(this).find('button');
        $submitBtn.prop("disabled", true);

        $('input[name=email]').prop("disabled", false);

        $.ajax({
            url: "/forgotten_password",
            data: $("form").serialize(),
        })
        .done(function(ajax_response) {
            $('input[name=email]').prop("disabled", true);

            if(ajax_response.url) {
                window.location.href = ajax_response.url;
            }
            else {
                $('form button').before(ajax_response.response);
                $submitBtn.prop("disabled", false);
            }
        });
    });
});
