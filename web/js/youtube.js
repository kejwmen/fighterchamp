function embedYouTube(youtube_id) {


    document.getElementById("yt-video").innerHTML = "<div class='embed-responsive embed-responsive-16by9'><iframe class='embed-responsive-item' src=http://www.youtube.com/embed/" +
        youtube_id + "></iframe></div>";

    $('#youtubeId').val(youtube_id);
}

$('#youtubeId').bind('input paste', show);

function show() {

    var input = document.getElementById("youtubeId").value;

    var check = "?v=";
    var check_mobile = "youtu.be/";
    var youtube_id = 0;

    if ((input.indexOf(check) > -1)) {

         youtube_id = input.substring(input.indexOf("?v=") + 3);

    } else if ((input.indexOf(check_mobile) > -1)) {

         youtube_id = input.substring(input.indexOf("youtu.be/") + 9);

    } else if (input.length === 11) {

        youtube_id = input;

    }

    if (youtube_id.length === 11) {


        embedYouTube(youtube_id);
        getTitle(youtube_id);

    } else {
        $('#yt-video').empty();
        $('#musicArtistAndTitle').val('');
    }
}

function getTitle(youtube_id)
{
    $.get('https://www.googleapis.com/youtube/v3/videos?part=snippet&id=' + youtube_id +
        '&key=AIzaSyAnV6IEZBiHNuhidtkEI20WZcQIymSh06Q', function (json) {
        var title = json.items[0].snippet.title;

        $('#musicArtistAndTitle').val(title);



    });
}
