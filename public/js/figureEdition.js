jQuery(document).ready(function () {
    // Get videos collection holder element, and assigning index
    const $videosCollectionHolder = $('#videosCollectionHolder');
    addAddButton($videosCollectionHolder, "Ajouter une video");
    $videosCollectionHolder.data('index', $videosCollectionHolder.find('fieldset').length);

    // Add remove button for each video element
    // /!\ Use fieldset as child element
    for (const $elt of $videosCollectionHolder.find('#figure_videos fieldset')){
        addRemoveButton($elt, $videosCollectionHolder);

        $($elt).find('.dynamic-entry').change((e) => {
            e.preventDefault();
            // Convert video URL to embed version in iframe
            srcToEmbed($($elt));
        });
    }

    // Get pictures collection holder element, and assigning index
    const $picturesCollectionHolder = $('#picturesCollectionHolder');
    addAddButton($picturesCollectionHolder, "Ajouter une image", false);
    $picturesCollectionHolder.data('index', $picturesCollectionHolder.find('fieldset').length);

    // Add remove for each video element
    // /!\ Use fieldset as primary child element
    for (const $elt of $picturesCollectionHolder.find('#figure_pictures fieldset')){
        addRemoveButton($elt, $picturesCollectionHolder);
        $($elt).find('.dynamic-entry').on("change", (e) => {
            e.preventDefault();
            const reader = new FileReader();
            reader.readAsDataURL(e.target.files[0]);
            reader.onload = function () {
                $($elt).find('.dynamic-display').attr('src', reader.result);
            };
        });
    }
});

/**
 * Add an AddButton after an element
 * @param $elt element
 * @param value button text value
 */
function addAddButton($elt, value, isVideo = true) {
    const button = document.createElement('button');
    const div = document.createElement('div');
    button.className = "btn btn-primary";
    div.className = "row";
    button.innerText = value;
    if (isVideo){
        button.addEventListener('click', (e) => {
            e.preventDefault();
            addFormToCollection($elt);
        });
    } else {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            addFormToCollection($elt, false);
        });
    }
        $elt.after(button);
    $elt.after(div);
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
function addFormToCollection($collectionHolder, isVideo = true) {
    // Get prototype, index & create form of prototype
    const prototype = $collectionHolder.data('prototype');
    let index = $collectionHolder.data('index');
    const $newForm = $(prototype);
    // Update collection index, add form and add a remove button to that form
    $collectionHolder.data('index', index++);
    $collectionHolder.find('.form-group>div').append($newForm);
    console.log(isVideo);
    if (isVideo){
        $($newForm).find('.dynamic-entry').change((e) => {
            e.preventDefault();
            // Convert video URL to embed version in iframe
            srcToEmbed($($newForm));
        });
    } else{
        // Add event listener on change, update embed version of video in iframe
        $($newForm).find('.dynamic-entry').on("change", (e) => {
            e.preventDefault();
            const reader = new FileReader();
            reader.readAsDataURL(e.target.files[0]);
            reader.onload = function () {
                $($newForm).find('.dynamic-display').attr('src', reader.result);
            };
        });
    }

    addRemoveButton($newForm,$collectionHolder);
}

