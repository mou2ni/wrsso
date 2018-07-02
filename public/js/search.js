/**
 * Created by Mouni on 10/03/2017.
 */
$(document).ready(function() {

    //Evènement pour lequel on execute une requete Ajax
    $('#search_keywords').keyup(function(key)
    {
        if (this.value.length >=0)
        {
            var formData = $("#formajax").serialize();
            //alert(formData);
            //Affiche le loader .gif
           // $('#loader').show();
            //Lance la requete Ajax sur l'url
            $.ajax({
                type: "POST",
                //url du formulaire:
                url: "/warisso/ouverture/new",
                dataType: "json",
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                data: formData,

                //Fonction executée quand la requete réussie
                success: function(result){
                    alert(formData);
                   // $('#formajax').submit();
                }
            }).done();
           // $('#loader').hide();
        }
    });

    function ParseXML(xml) {

        //Boucle sur le fichier XML
        $(xml).find('data').each(function(i) {

            var name = $(xml).find('name').eq(i).text();
            var desc = $(xml).find('desc').eq(i).text();

          
            //On peut maintenant mettre à jour les éléments que l'on veut de la page avec les nouvelles données
           
        });
    }

});