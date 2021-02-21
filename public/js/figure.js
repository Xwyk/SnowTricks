jQuery(document).ready(function () {
    const $videosCollectionHolder = $('#figureMedias');

    for (const $elt of $videosCollectionHolder.find('iframe')){
        // Convert video URL to embed version in iframe
        srcToEmbed($($elt));
    }


});

function srcToEmbed($elt){
    const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const url = $($elt).attr('src');
    const match = url.match(regExp);
    if (match && match[2].length == 11)
    {
        const id = match[2];
        const embedlink = "http://www.youtube.com/embed/" + id;
        $($elt).attr('src', embedlink);
    }
}