var $addLink = $('<a href="#" class="add-collection"> + </a>');
//var $removeLink=$('<a href="#" class="remove-collection"> X </a>');
var $newLinkLi = $('<div></div>').append($addLink);//.append($removeLink);

jQuery(document).ready(function() {
    var $collectionHolder = $('table.collection-tags');

    $collectionHolder.append($newLinkLi);

    $collectionHolder.data('index', $collectionHolder.find('tr.collection-tag').length);

    if ($collectionHolder.data('index') == 0)
        while ($collectionHolder.data('index') < 1 )
            addTagForm($collectionHolder, $newLinkLi);

    $addLink.on('click', function(e) {
        e.preventDefault();
        addTagForm($collectionHolder, $newLinkLi);
    });
    /*$removeLink.onclick('click', function (e) {
        e.preventDefault();
        removeTagForm($collectionHolder, $newLinkLi)
    })*/

});

/*function removeTagForm($collectionHolder, $newLinkLi){
    var index = $collectionHolder.data('index');
    var toRemoveForm=$collectionHolder.find("_"+index+"_");
    $(toRemoveForm).parent().remove();
    $collectionHolder.data('index', index - 1);

}*/

function addTagForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype.replace(/__name__/g, index);

    var $newFormLi = $('<tr class="collection-tag"></tr>').append(newForm);

    $newFormLi.append('<a href="#" class="remove-collection"> X </a>');

    $newLinkLi.before($newFormLi);

    $('.remove-collection').click(function(e) {
        e.preventDefault();
        $(this).parent().remove();
        return false;
    });
    chargerLibelleDetail(index);
    prechargerMontant(index);

    $collectionHolder.data('index', index + 1);
}

function chargerLibelleDetail(index) {
    //var index = $collectionHolder.data('index');
    var indexprec=0;
    if(index==0)
        $("#transactions_transactionComptes_0_libelle").val($("#transactions_libelle").val());
    else {
        indexprec=index-1;
        $("#transactions_transactionComptes_"+index+"_libelle").val($("#transactions_transactionComptes_"+indexprec+"_libelle").val());
    }
}

function prechargerMontant(index) {
    var mCredit=0;
    var mDebit=0;
    var mSolde=0

    for (i=0; i<index; i++){
        if (document.getElementById("transactions_transactionComptes_"+i+"_mCredit")){
            mCredit+=Number($("#transactions_transactionComptes_"+i+"_mCredit").val());
        }
        if (document.getElementById("transactions_transactionComptes_"+i+"_mDebit")){
            mDebit+=Number($("#transactions_transactionComptes_"+i+"_mDebit").val());
        }
        //alert (mCredit + " : "+mDebit);
    }
    mSolde=mDebit-mCredit;

    if (mSolde>=0) {
        $("#transactions_transactionComptes_"+index+"_mCredit").val(mSolde);
        $("#transactions_transactionComptes_"+index+"_mDebit").val(0);
    }
    else {
        $("#transactions_transactionComptes_"+index+"_mDebit").val(-mSolde)
        $("#transactions_transactionComptes_"+index+"_mCredit").val(0)
    }
}