+function ($) {

    $(document).ready(function() {
        $('#add-tome').on('click', function() {
            $('.search-bar').slideToggle();
        });
    });

}(jQuery);

function postForm( $form, callback ){

    /*
     * Get all form values
     */
    var values = {};
    $.each( $form.serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });

    /*
     * Throw the form values to the server!
     */
    $.ajax({
        type        : $form.attr( 'method' ),
        url         : $form.attr( 'action' ),
        data        : values,
        success     : function(data) {
            callback( data );
        }
    });

}

$(document).ready(function(){

    var forms = [
        '[ name="{{ postform.vars.full_name }}"]'
    ];

    $( forms.join(',') ).submit( function( e ){
        e.preventDefault();

        postForm( $(this), function( response ){
        });

        return false;
    });

});



function initAjaxForm()
{
    $('body').on('submit', '.ajaxForm', function (e) {

        e.preventDefault();

        $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize()
            })
            .done(function (data) {
                if (typeof data.message !== 'undefined') {
                    alert(data.message);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (typeof jqXHR.responseJSON !== 'undefined') {
                    if (jqXHR.responseJSON.hasOwnProperty('form')) {
                        $('#form_body').html(jqXHR.responseJSON.form);
                    }

                    $('.form_error').html(jqXHR.responseJSON.message);

                } else {
                    alert(errorThrown);
                }

            });
    });
}

function afficheAlert() {
    $(document).ready(function() {
        $('ouverture_form_liquiditeOuverture').on('click', function() {
            alert("test reussi");
        });
    });
    
}