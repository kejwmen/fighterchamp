$('select[data-source]').each(function() {
    var $select = $(this);

    $.ajax({
        url: $select.attr('data-source'),
    }).then(function(options) {
        options.data.map(function(option) {
            var $option = $('<option>');

            $option
                .val(option[$select.attr('data-valueKey')])
                .text(option[$select.attr('data-displayKey')]);

            $select.append($option);

            let id = window.location.href.match(/(\d+)(?!.*\d)/)[0];

            $('select option[value=' + id +']').attr("selected",true);
        });
    });
});

$(function(){
    $('#js-admin-tournament').on('change', function () {
        var id = $(this).val();

        url = window.location.href;

        let er = url.replace(/(\d+)(?!.*\d)/, id);

        window.location = er;

        return false;
    });
});

