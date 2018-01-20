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
                location.reload();

                // window.location.href = data.location;
            })
            .success(function(data){
                if(data.location){
                    window.location.replace(data.location);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (typeof jqXHR.responseJSON !== 'undefined') {
                    if (jqXHR.responseJSON.hasOwnProperty('form')) {
                        $('.ajaxForm').html(jqXHR.responseJSON.form);
                    }

                    $('.form_error').html(jqXHR.responseJSON.message);
                } else {
                    alert(errorThrown);
                }

            });
    });
}