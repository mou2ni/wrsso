/**
 * Created by Mouni on 07/03/2017.
 */

function majOuverture() {
    $("#dispo").val(valeur("#ouverture_valeurBillet")+valeur("#ouverture_soldeElectOuv"));
    $("#soldeNet").val(valeur("#ouverture_valeurBillet")+valeur("#ouverture_soldeElectOuv")+valeur("#ouverture_mCreditDivers")-valeur("#ouverture_mDetteDivers"));
    $("#ouverture_mCvd").val(valeur("#euros")+valeur("#dollars"));
}

function majBilletage() {
    var nbrBillet=valeur("#nbrBillet");
    var totalBillet=0;
    for ( b=1;b<=nbrBillet;b++){
        var nbr=valeur("#formBilletage_nbBillet"+b);
        $("#formBilletage_valeurLigne"+b).val(valeur("#formBilletage_valeur"+b)*nbr);

        totalBillet=totalBillet+valeur("#formBilletage_valeurLigne"+b);

        $("#formBilletage_valeurTotal").val(totalBillet);
        //$("#form_mBilletageFerm").val(valeur("#totalBilletage"));
        //majOuverture();
    }

}

function majElectronique() {
    var nbrSystemElect=valeur("#nbrSystemElect");
    var totalElect=0;
    for ( a=1;a<=nbrSystemElect;a++){
        solde=valeur("#formElectronic_solde"+a);
        totalElect=totalElect+solde;
        $("#formElectronic_soldeTotal").val(totalElect);
    }

}

$("#ouverture_idCaisse").change(function () {
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
});


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