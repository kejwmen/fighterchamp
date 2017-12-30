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
                        $('#form_body').html(jqXHR.responseJSON.form);
                    }

                    $('.form_error').html(jqXHR.responseJSON.message);
                    $('.select2').select2({placeholder: "Wybierz klub albo wpisz nowy", tags: true});

                } else {
                    alert(errorThrown);
                }

            });
    });
}