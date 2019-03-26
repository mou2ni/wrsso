/**
 * Created by houedraogo on 25/03/2019.
 */



function changeEnvoi(i) {
    var envoi=$("#compense_collections_compenseLignes_"+i+"_mEnvoiCompense").val()
    $("#compense_collections_compenseLignes_"+i+"_mEnvoiCompense").val(Echape(envoi))
    updateNet(i);
    totalEnvoi();
    totalNet();
    totalEcart();
};

function changeReception(i) {
    var reception=$("#compense_collections_compenseLignes_"+i+"_mReceptionCompense").val()
    $("#compense_collections_compenseLignes_"+i+"_mReceptionCompense").val(Echape(reception))
    updateNet(i);
    totalReception();
    totalNet();
    totalEcart();

};

function updateNet(i) {
    //compense_collections_compenseLignes_0_mReceptionCompense
    document.getElementById('compenseNet_'+i).innerHTML=formatMillier(Echape($("#compense_collections_compenseLignes_"+i+"_mEnvoiCompense").val())*1
    - Echape($("#compense_collections_compenseLignes_"+i+"_mReceptionCompense").val())*1,0);

    document.getElementById('compenseEcart_'+i).innerHTML= formatMillier(Echape(document.getElementById('AttenduNet_'+i).innerHTML)*1
        - Echape(document.getElementById('compenseNet_'+i).innerHTML)*1,0);

};

function totalEnvoi() {
    /*var i=0;
    var total=0;
    while ($("#compense_collections_compenseLignes_"+i+"_mEnvoiCompense").val()!=undefined) {
        total=total+$("#compense_collections_compenseLignes_"+i+"_mEnvoiCompense").val()*1;
        //console.log("compense_collections_compenseLignes_"+i+"_mEnvoiCompense="+$("#compense_collections_compenseLignes_"+i+"_mEnvoiCompense").val());
        i=i+1;
    }
    document.getElementById('compenseTotalEnvoi').innerHTML=total;*/

    totalInputCol("compense_collections_compenseLignes_", "_mEnvoiCompense", "compenseTotalEnvoi")
};

function totalReception() {
    /*var i=0;
    var total=0;
    while ($("#compense_collections_compenseLignes_"+i+"_mReceptionCompense").val()!=undefined) {
        total=total+$("#compense_collections_compenseLignes_"+i+"_mReceptionCompense").val()*1;
        //console.log("compense_collections_compenseLignes_"+i+"_mReceptionCompense="+$("#compense_collections_compenseLignes_"+i+"_mReceptionCompense").val());
        i=i+1;
    }
    document.getElementById('compenseTotalReception').innerHTML=total;*/

    totalInputCol("compense_collections_compenseLignes_", "_mReceptionCompense", "compenseTotalReception")
};

function totalNet() {
    /*var i=0;
    var total=0;
    while (document.getElementById('compenseNet_'+i)) {
        total=total+Echape(document.getElementById('compenseNet_'+i).innerHTML)*1;
        console.log("compenseNet_"+i+" = "+ document.getElementById('compenseNet_'+i).innerHTML) ;
        i++;
    }
    document.getElementById('compenseTotalNet').innerHTML=total;*/
    totalInnerHTMLCol("compenseNet_","","compenseTotalNet");
};

function totalEcart() {
    /*var i=0;
    var total=0;
    while (document.getElementById('compenseEcart_'+i)) {
        document.getElementById('compenseTotalEcart').innerHTML=Echape(document.getElementById('compenseTotalEcart').innerHTML)*1
            +Echape(document.getElementById('compenseEcart_'+i).innerHTML)*1;
        i++;
    }*/

    totalInnerHTMLCol("compenseEcart_","","compenseTotalEcart");
}
