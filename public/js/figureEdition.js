
jQuery(document).ready(function () {
    // Get videos collection holder element, and assigning index
    const $videosCollectionHolder = $('#videos');
    addAddButton($videosCollectionHolder, "Ajouter une video");
    $videosCollectionHolder.data('index', $videosCollectionHolder.find('fieldset').length);

    // Add remove for each video element
    // /!\ Use fieldset as primary child element
    for (const $elt of $videosCollectionHolder.find('fieldset')){
        addRemoveButton($elt, $videosCollectionHolder);
    }

    // Get pictures collection holder element, and assigning index
    const $picturesCollectionHolder = $('#figure_pictures');
    addAddButton($picturesCollectionHolder, "Ajouter une image");
    $picturesCollectionHolder.data('index', $picturesCollectionHolder.find('fieldset').length);

    // Add remove for each video element
    // /!\ Use fieldset as primary child element
    for (const $elt of $picturesCollectionHolder.find('fieldset')){
        addRemoveButton($elt, $picturesCollectionHolder);
    }

    // Modifying dimensions of stored iframe video
    for(const $elt of $('.videoOnEdit')){
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
});

/**
 * Add an AddButton after an element
 * @param $elt element
 * @param value button text value
 */
function addAddButton($elt, value) {
    const button = document.createElement('button');
    button.className = "btn btn-primary";
    button.innerText = value;
    button.addEventListener('click', (e) => {
        e.preventDefault();
        addFormToCollection($elt);
    });
    $elt.after(button);
}

/**
 * Add an AddButton inside a '.removeBtn' child inside an element
 * Associated remove function delete element and update collection holder index
 * @param $elt element
 * @param $collectionHolder elements collection holder
 */
function addRemoveButton($elt, $collectionHolder){
    const button = document.createElement('button');
    button.className = "btn btn-danger";
    button.innerText = "Delete";
    button.addEventListener('click', (e) => {
        $elt.remove();
        let index = $collectionHolder.data('index');
        $collectionHolder.data('index', index --);
    });
    $($elt).find('.removeBtn').append(button);
}

/**
 * Add new element to collection holder by getting it's prototype
 * @param $collectionHolder collection holder
 */
function addFormToCollection($collectionHolder) {
    // Get prototype, index & create form of prototype
    const prototype = $collectionHolder.data('prototype');
    let index = $collectionHolder.data('index');
    const $newForm = $(prototype);

    // Update collection index, add form and add a remove button to that form
    $collectionHolder.data('index', index++);
    $collectionHolder.append($newForm);
    addRemoveButton($newForm,$collectionHolder);
}