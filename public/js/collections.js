/**
 * Created by houedraogo on 22/01/2019.
 */

var $addLink = $('<a href="#" class="add-collection"> + </a>');
var $newLinkLi = $('<div></div>').append($addLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $collectionHolder = $('.collections-tag');

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    if ($collectionHolder.data('index') == 0)
        while ($collectionHolder.data('index') < 5 )
            addTagForm($collectionHolder, $newLinkLi);

    $addLink.on('click', function(e) {
        e.preventDefault();
        addTagForm($collectionHolder, $newLinkLi);
    });

});

function addTagForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype.replace(/__name__/g, index);

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

