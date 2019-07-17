/**
 * Created by houedraogo on 15/07/2019.
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
        while ($collectionHolder.data('index') < 1 )
            addTagForm($collectionHolder, $newLinkLi);

    $addLink.on('click', function(e) {
        e.preventDefault();
        addTagForm($collectionHolder, $newLinkLi);
    });

});

function addTagForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype.replace(/__name__/g, '_'+index+'_');

    var $newFormLi = $('<tr class="collection-tag" ></tr>').append(newForm);

    $newFormLi.append('<td><a href="#" class="remove-collection"> X </a></td>');

    $newLinkLi.before($newFormLi);

    prechargerTx(index);

    $collectionHolder.data('index', index + 1);


    $('.remove-collection').click(function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        totalLigne();
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

function totalLigne(element){
    var total = 0;
    var totalLigne=0
    var i=0;
    //alert('propre'); alert($("#devise_recus_deviseMouvements_"+i+"_nombre").val());
    while ($("#devise_recus_deviseMouvements__"+i+"__nombre").val()!=undefined) {

        totalLigne=Echape($("#devise_recus_deviseMouvements__"+i+"__nombre").val())*Echape($("#devise_recus_deviseMouvements__"+i+"__taux").val());

        $("#devise_recus_deviseMouvements__"+i+"__total").val(formatMillier(totalLigne,0));
        total=total + totalLigne;
        i++;
    };

     document.getElementById('totalGeneral').innerHTML=formatMillier(total,0);


   /* var id=element.id;
    var i=id.split('__')[1];
    $("#devise_recus_deviseMouvements__"+i+"__total").val(formatMillier(Echape($("#devise_recus_deviseMouvements__"+i+"__nombre").val())*Echape($("#devise_recus_deviseMouvements__"+i+"__taux").val()),0));

    //alert(total); totalLigne

    totalInputCol('devise_recus_deviseMouvements__','__total','totalGeneral');*/

    //totalInputClassCol('totalLigne','totalGeneral');

}

function prechargerTx(i){
    var devise=document.getElementById("devise_recus_deviseMouvements__"+i+"__devise").selectedOptions[0].text;

    $("#devise_recus_deviseMouvements__"+i+"__taux").val(getTx(devise));
}

function chargerTx(element){
    //alert('Cool');
    var devise=element.selectedOptions[0].text;
    var id=element.id;
    var i=id.split('__')[1];

    $("#devise_recus_deviseMouvements__"+i+"__taux").val(getTx(devise));

    totalLigne(element);
}

function getTx(devise){
    var tx=0;
    var tabDevise=devise.split('=>');
    //alert(devise);
    var tabTaux=tabDevise[1].split('|');
    var sens=$("#devise_recus_sens").val();
    tx=(sens.toUpperCase()=='A')?tabTaux[0]:tabTaux[1];
    return tx;
}

function changeSensTx(){
    //alert('propre');
    var i=0;
    //alert('propre'); alert($("#devise_recus_deviseMouvements_"+i+"_nombre").val());
    while ($("#devise_recus_deviseMouvements__"+i+"__nombre").val()!=undefined) {
        var element=document.getElementById("devise_recus_deviseMouvements__"+i+"__devise");
        chargerTx(element);
        i++;
    };

    //var tabElements=document.getElementsByClassName('collection-tag');
}