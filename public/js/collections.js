/**
 * Created by houedraogo on 22/01/2019.
 */

var $addLink = $('<a href="#" class="add-collection"> + </a>');
var $newLinkLi = $('<div></div>').append($addLink);

jQuery(document).ready(function() {
    var $collectionHolder = $('table.collection-tags');

    $collectionHolder.append($newLinkLi);

    $collectionHolder.data('index', $collectionHolder.find('tr.collection-tag').length);
// add a delete link to all of the existing tag form li elements
    $collectionHolder.find('tr.collection-tag').each(function() {
        addTagFormDeleteLink($(this));
    });
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

    var $newFormLi = $('<tr class="collection-tag" ></tr>').append(newForm);

    $newFormLi.append('<td><a href="#" class="remove-collection"> X </a></td>');

    $newLinkLi.before($newFormLi);

    $('.remove-collection').click(function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        return false;
    });
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<td><a href="#" class="remove-collection"> X </a></td>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $tagFormLi.remove();
        //majTransfert();


    });
}

/*<input type="text" id="recette_depense_journees_recetteDepenses_0_mSaisie" name="recette_depense_journees[recetteDepenses][0][mSaisie]" class="form-control" value="25000">

function setCollectionsTotal($racineChamp, $nomChamp) {
    var total = 0;
    var i=0;
    while ($("#"+$racineChamp+"_"+i+"_"+$nomChamp)) {
        total+=$("#"+$racineChamp+"_"+i+"_"+$nomChamp).val();
        i++;
    }

    $("div#total").html("TOTAL= "+total);
}*/
