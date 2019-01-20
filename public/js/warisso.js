/**
 * Created by Mouni on 07/03/2017.
 */

function majTransfert() {

    // les variables des totaux
    var reception = 0;
    var emission = 0;
    var i=0;
    while ($("#transfert_transfertInternationaux_"+i+"_mTransfert")) {

        if ($("#transfert_transfertInternationaux_"+i+"_sens").val() == "1"){
            emission = emission + +Echape($("#transfert_transfertInternationaux_" + i + "_mTransfertTTC").val());
            $("#transfert_mEmissionTrans").val(emission);
            //alert($("#transfert_mEmissionTrans").val());
        }
        else {
            if ($("#transfert_transfertInternationaux_"+i+"_mTransfertTTC").val()==0){
                $("#transfert_transfertInternationaux_"+i+"_mTransfertTTC").val($("#transfert_transfertInternationaux_"+i+"_mTransfert").val());
            }
            reception = reception + +Echape($("#transfert_transfertInternationaux_" + i + "_mTransfertTTC").val())
            $("#transfert_mReceptionTrans").val(reception);
        }
        i++;
    }

};


/*function majTransfert() {

    // les variables des totaux
    var reception = 0;
    var emission = 0;
    var i=0;
    var tva = 0;
    var autresTaxes = 0;
    var mTTC = 0;
    while ($("#emissions_transfertEmis_"+i+"_mTransfert")) {
        //alert(Echape($("#transfert_transfertInternationaux_"+i+"_mFraisHt").val()));
        if ($("#sens").val() == "1"){
            //tva=Echape($("#emissions_transfertEmis_"+i+"_mFraisHt").val())*0.18;
            //tva=Math.round(tva);
            //alert(tva);


            //$("#emissions_transfertEmis_"+i+"_mTva").val(tva);
            //$("#emissions_transfertEmis_"+i+"_mAutresTaxes").val(autresTaxes);
            //alert($("#emissions_transfertEmis_" + i + "_mTransfertTTC").val());
            emission = emission + +Echape($("#emissions_transfertEmis_" + i + "_mTransfertTTC").val());
            $("#emissions_mEmissionTrans").val(emission);
        }
        else {
            //alert('ok');
            reception = reception + +Echape($("#receptions_transfertRecus_" + i + "_mTransfert").val())
            $("#receptions_mReceptionTrans").val(reception);
        }
        i++;
        //alert(emission);receptions_transfertInternationaux_0_mTransfert

    }

};
*/

function Echape(data)
{
    return data.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "");
}

function majOuverture() {
    alert('top');
    $("#dispo").val(valeur("#ouverture_valeurBillet")+valeur("#ouverture_soldeElectOuv"));
    $("#soldeNet").val(valeur("#ouverture_valeurBillet")+valeur("#ouverture_soldeElectOuv")+valeur("#ouverture_mCreditDivers")-valeur("#ouverture_mDetteDivers"));
    $("#ouverture_mCvd").val(valeurDevises());
    $("#ouverture_ecartOuv").val(valeur("#soldeNetPrec")-valeur("#soldeNet"));
}

function  valeurDevises() {
    var valeurDevises=0;
    var nbrDevises=valeur("#nbrDevises");
    for (i=1; i<=nbrDevises; i++) {
        valeurDevises=valeurDevises+valeur("#devise"+i);
    }
    return valeurDevises;
}

/*$("<input>").on('click', function () {
    var x = $(this).attr('id');
    var valeur = this.value;
    alert(valeur);
});*/

/*function majBilletage() {
    var nbrBillet=valeur("#nombreLigne");
    var totalBillet=0;
    for ( b=1;b<=nbrBillet;b++){
        var nbr=valeur("#billetages_billetageLignes_"+b+"_nbBillet");
        $("#billetages_billetageLignes_"+b+"_nbBillet").val(valeur("billetages_billetageLignes_"+b+"_valeurLigne")*nbr);

        totalBillet=totalBillet+valeur("billetages_billetageLignes_"+b+"_valeurLigne");

        $("#billetages_valeurTotal").val(totalBillet);


    }

}*/


function majElectronique() {
    var nbrSystemElect=valeur("#nombreElect");
    var totalElect=0;

    for ( a=0;a<nbrSystemElect;a++){
        solde=$("#system_elect_inventaires_systemElectLigneInventaires_" + a + "_solde").val();
        totalElect=totalElect + +Echape(solde);
        $("#system_elect_inventaires_soldeTotal").val(totalElect);
    }

}

///////////////GESTION DES INTERCAISSES ////////////////////////


$(document).ready(function () {
    $('.boutonintercaisse').click(function (e) {
        var bouton = $(this);
        var x = $(this).attr('id');
        var ligne = x.substr(0,x.length-1);
        if (confirm('Voulez vous continuer ?')){
            var valeur = this.value;
            var DATA = 'intercaisse=' + valeur ;
            //alert(DATA);
            $.ajax({
                type: "POST",
                data: DATA,
                cache: false,
                success: function (data) {
                    console.log(data.intercaisse);
                    if(data.intercaisse)alert("veuillez rafraichir la page et réessayer");
                    else {
                        if ($(this).attr('name')=="Annuler") {
                            //$('<tr id="'+data.intercaisse+"S"+'"></tr>').remove();
                            //$("#32S").remove();
                            remove($('<tr id="'+data.intercaisse+"S"+'"></tr>'));
                            $("#"+data.intercaisse+"E").remove();
                            $("#"+data.intercaisse+"S").remove();
                        }
                        if ($(this).attr('name')=="Valider") document.getElementById('statut'+x).innerHTML="<span id='valider{{intercaisse.id}}V'>Validé</span>";
                        $("#"+x).hide();
                    }
                }
            })
        }
        else return false;
    });
});



function  valider() {
    var jc = $('#jc').val();
    alert(jc);
    var DATA = '_journeeCaisse=' + jc;
    alert(DATA);
    $.ajax({
        type: "POST",
        data: DATA,
        cache: false,
        success: function (data) {
            //document.location.reload(true);
            //document.getElementsByTagName('table').load(true);
        }
    })
}



function  chargerNomCompte() {
    var nom = $("#retrait_numCompte").val();
    if (!nom) nom = $("#depot_numCompte").val();
    var DATA = 'num=' + nom;
    //alert(DATA);
    $.ajax({
        type: "POST",
        data: DATA,
        cache: false,
        success: function (data) {
            //var yourval = jQuery.parseJSON(JSON.stringify(data));
            //var obj = JSON.parse(data);
            //console.log(data.compte[0].client);
            console.log(data.compte[0].client);
            document.getElementById('nom').innerHTML=data.compte[0].client;
            $("#solde").val(data.compte[0].soldeCourant);
        }

    })
}
/*
jQuery(document).ready(function() {
    $("#validerIntercaisses").click(function () {
        alert($('.boutonintercaisse').value);
        if ($('.boutonintercaisse').value) {
            alert('propre');
        }
        else alert("de l'autre cote");
    });
});
*/

var $collectionHolder;

// setup an "add a tag" link
var $addTagButton = $('<td><button type="button" class="add_tag_link"> +5 lignes</button></td>');
var $newLinkLi = $('<tr></tr>').append($addTagButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('table.transfert');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('tr.transfert').each(function() {
        addTagFormDeleteLink($(this));
    });
    // Get the ul that holds the collection of tags
    $collectionHolder = $('table.transfert');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('tr.transfert').length);
    if ($collectionHolder.data('index') ==0)
        while ($collectionHolder.data('index') < 5 )
            addTagForm($collectionHolder, $newLinkLi);
    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        //i=0;
        for (i=0;i<5;i++){
            addTagForm($collectionHolder, $newLinkLi);
        }
        // get the new index
        /*var i = $collectionHolder.data('index');
        alert(i);
        $("#transfert_transfertInternationaux_"+i+"_mTransfert").val(0);
        $("#transfert_transfertInternationaux_"+i+"_mTransfertTTC").val(0);
        $("#transfert_transfertInternationaux_"+i+"_mFraisHt").val(0);
        $("#transfert_transfertInternationaux_"+i+"_mAutresTaxes").val(0);
        $("#transfert_transfertInternationaux_"+i+"_mTva").val(0);*/
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);
    //alert(newForm);
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<tr class="transfert"></tr>').append(newForm);
    $newLinkLi.before($newFormLi);
    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<td><button type="button">Supprimer</button></td>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
        //majTransfert();


    });
}
/*
// add-collection-widget.js
jQuery(document).ready(function () {
    jQuery('#add-another-collection-widget').click(function (e) {
        var list = jQuery(jQuery(this).attr('data-list'));
        // Try to find the counter of the list
        var counter = list.data('widget-counter') | list.children().length;
        // If the counter does not exist, use the length of the list
        if (!counter) { counter = list.children().length; }

        // grab the prototype template
        var newWidget = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data(' widget-counter', counter);

        // create a new list element and add it to the list
        var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });
});

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('table');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('tr.transfert').each(function() {
        addTagFormDeleteLink($(this));
    });

    // ... the rest of the block from above
});

function addTagForm() {
    // ...

    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button">Supprimer</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

*/

/*$("#ouverture_idCaisse").change(function () {
    var caisse = valeur("#ouverture_idCaisse");
    var DATA = 'caisse=' + caisse;
    $.ajax({
        type: "POST",
        url: "{{ path('journee_caisses_ouverture')}}",
        data: DATA,
        cache: false,
        success: function(data){
            //$('#listeMouvement').html(data);
            //mouvementFermeture=$("#totalMouvement").val();
            //$('#fermeture_form_totalMouvement').val(totalMouvement);
            //differenceFermeture = valeur("#fermeture_form_difference");
            //ecartFermeture = +differenceFermeture + +mouvementFermeture;
            //$("#fermeture_form_ecart").val(+difference + +valeur("#fermeture_form_totalMouvement"));
            alert(data);

        }
    });

    alert('correct');
    $("#form_mBilletageFem").val(valeur("#totalBilletage"));
    $("#billetage").hide();
});*/


///////////////////////////////////////////////////////////ancienne version/////////////////////////////////////

function getXhr(){
    var xhr = null;
    if(window.XMLHttpRequest) // Firefox et autres
        xhr = new XMLHttpRequest();
    else if(window.ActiveXObject){ // Internet Explorer
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    else { // XMLHttpRequest non supporté par le navigateur
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
        xhr = false;
    }
    return xhr
}


function majFormFerm() {
    $("#fermeture_form_liquiditeFermeture").val(liquiditeFermeture);
    $('#fermeture_form_soldeTransfertFermeture').val(transfertFermeture);
    $("#fermeture_form_totalFermeture").val(fermeture);
    $('#fermeture_form_totalMouvement').val(mouvementFermeture);
    $("#fermeture_form_difference").val(differenceFermeture);
    $("#fermeture_form_ecart").val(ecartFermeture);
    $("#fermeture_form_soldeElectroniqueFermeture").val(electroniqueFermeture);

}

/*les fonctions appelées dans l'ouverture */

function totaljourneeCaisse() {
    var tot;
    tot=+valeur("#ouverture_form_liquiditeOuverture") + +valeur("#ouverture_form_soldeElectroniqueOuverture") ;
    $("#ouverture_form_totalOuverture").val(tot);
}

function totalElectronique() {
    var tot;
    tot=+valeur("#solde_electronique_form_AIRTEL") + +valeur("#solde_electronique_form_CANAL")
        + +valeur("#solde_electronique_form_WARI") + +valeur("#solde_electronique_form_MOBICASH");
    $("#ouverture_form_soldeElectroniqueOuverture").val(tot);

}


function somCoupures(ev)
{

    var liqid;
    liqid = +valeur("#liquidite_form_coupure10000")*10000 + +valeur("#liquidite_form_coupure5000")*5000 + +valeur("#liquidite_form_coupure2000")*2000
        + +valeur("#liquidite_form_coupure1000")*1000 + +valeur("#liquidite_form_coupure500")*500 + +valeur("#liquidite_form_coupure250")*250
        + +valeur("#liquidite_form_coupure200")*200 + +valeur("#liquidite_form_coupure100")*100 + +valeur("#liquidite_form_coupure50")*50
        + +valeur("#liquidite_form_coupure25")*25 + +valeur("#liquidite_form_coupure10")*10 + +valeur("#liquidite_form_coupure5")*5;
    $("#ouverture_form_liquiditeOuverture").val(liqid);
    $("#liquidite_form_total").val(liqid);
}


/* LES FONCTIONS APPELEES DANS LA FERMETURE */
function totalElectroniqueFermeture() {
    var tot;
    tot=+valeur("#solde_electronique_form_AIRTEL") + +valeur("#solde_electronique_form_CANAL")
        + +valeur("#solde_electronique_form_WARI") + +valeur("#solde_electronique_form_MOBICASH");
    $("#fermeture_form_soldeElectroniqueFermeture").val(tot);

}

function somCoupuresFermeture(ev)
{

    liquiditeFermeture = +valeur("#liquidite_form_coupure10000")*10000 + +valeur("#liquidite_form_coupure5000")*5000 + +valeur("#liquidite_form_coupure2000")*2000
        + +valeur("#liquidite_form_coupure1000")*1000 + +valeur("#liquidite_form_coupure500")*500 + +valeur("#liquidite_form_coupure250")*250
        + +valeur("#liquidite_form_coupure200")*200 + +valeur("#liquidite_form_coupure100")*100 + +valeur("#liquidite_form_coupure50")*50
        + +valeur("#liquidite_form_coupure25")*25 + +valeur("#liquidite_form_coupure10")*10 + +valeur("#liquidite_form_coupure5")*5;
    //alert(liqid);
    $("#fermeture_form_liquiditeFermeture").val(liquiditeFermeture);
    $("#liquidite_form_total").val(liquiditeFermeture);
}

function totaljourneeCaisseFermeture() {


    somCoupuresFermeture();
    totalElectroniqueFermeture();

    liquiditeFermeture =valeur("#fermeture_form_liquiditeFermeture");
    transfertFermeture = valeur('#fermeture_form_soldeTransfertFermeture');
    electroniqueFermeture = valeur('#fermeture_form_soldeElectroniqueFermeture');
    ouverture = valeur('#fermeture_form_totalOuverture');
    mouvementFermeture = valeur("#fermeture_form_mouvement");

    fermeture = +liquiditeFermeture + +electroniqueFermeture + +transfertFermeture ;
    differenceFermeture = +fermeture - +ouverture;
    ecartFermeture = differenceFermeture + mouvementFermeture;
    majFormFerm();

}


// fonction prenant le nom d un champ et retournant la valeur de ce champ 

function valeur(champ) {

    if ($(champ).val())
        r=parseInt($(champ).val(),10);
    else r=0;
    return r;
}