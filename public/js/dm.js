/**
 * Created by houedraogo on 02/08/2018.
 */

var $addLink = $('<a href="#" class="add-collection"> + </a>');
var $newLinkLi = $('<div></div>').append($addLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $collectionHolder = $('tbody.deviseMouvements');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    //var index = $collectionHolder.data('index');
    if ($collectionHolder.data('index') == 0) addForm($collectionHolder, $newLinkLi);

    $addLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see code block below)
        addForm($collectionHolder, $newLinkLi);
    });


});

function addForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<tr class="form-inline"></tr>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-collection"> X </a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-collection').click(function(e) {
        e.preventDefault();
        $(this).parent().remove();

        return false;
    });
}

