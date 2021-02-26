jQuery(document).ready(function () {
    const $mediasCollectionHolder = $('#figureMedias');

    for (const $elt of $mediasCollectionHolder.find('fieldset.card')){
        // Convert video URL to embed version in iframe
        srcToEmbed($($elt));
    }


});

function srcToEmbed($elt){
    const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const url = $($elt).find('.dynamic-entry').val();
    const match = url.match(regExp);
    if (match && match[2].length == 11)
    {
        const id = match[2];
        const embedlink = "http://www.youtube.com/embed/" + id;
        $($elt).find('.dynamic-display').attr('src', embedlink);
    }
}

function urlToEmbed(url){
    const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    if (match && match[2].length == 11)
    {
        const id = match[2];
        const embed = "http://www.youtube.com/embed/" + id;
        return embed;
    }
    return false;
}