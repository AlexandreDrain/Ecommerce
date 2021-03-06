$(document).ready(function() {

    $(".response").hide();
    $(".deleteResponse").hide();
    $(".msgInvalidForm").hide();

    $('#add_cart').on('click',function() {
        // ajout au panier
        $.getJSON($(this).data('url'), {quantity : 1})
        .done((data) => {
            if(data.statut == 'ok') {
                $("#cart-header-nb").text(data.nb_product);
            }
        });
    });

    $('#comment').on('click',function() {

        /*Si la longueur de la valeur du champ #prenom est 0 (c'est-à-dire si
        le champ n'a pas été rempli), on affiche un message et on empêche l'envoi*/
        if($(".productReviewContent").val().length === 0){
            $(".productReviewContent").after("<span style='color:red;'>Merci de remplir ce champ</span>");
            event.preventDefault();
        } else {
            //On effectue nos requêtes Ajax, sérialise, etc...
            $.ajax({
                type: $(this).parents('form').attr('method'),
                url: $(this).parents('form').attr('action'), 
                headers: {name: $(this).parents('form').attr('name')},
                data: $(this).parents('form').serialize(),
            })
            .done((data) => {
                if(data.statut == 'ok') {
                    // Pour recharger la partie des messages, mais du coup les boutons etc ne sont plus actif .. donc pas très utile a moins de trouver un moyen de rendre les bouton actif
                    // $.ajax({
                    //     type: $(this).parents('form').attr('method'),
                    //     url: $(this).parents('form').attr('data-url'),
                    //     headers: {name: $(this).parents('form').attr('name')},
                    // })
                    // .done(function() {
                    //     $("#zoneAvis").load(" #zoneAvis");
                    // })
                    if("content" in document.createElement("template")) {
                        let template = document.querySelector('#tpl-avis');
                        let clone = document.importNode(template.content, true);
                        clone.querySelector('.avis-author').textContent = data.author;
                        clone.querySelector('.avis-content').textContent = data.comment;
                        clone.querySelector('.avis-date').textContent = data.date;

                        $("#zoneAvis").prepend(clone);
                    }
                } else {
                    alert("Vous devez être connecté pour écrire un commentaire");
                }
            });
        }
    });

    $(function() {
        $('.custom-file-label::after').css('content', 'test');
        $('input[type="file"]').change(function(e){
            let documentsName = '';
     
            for(let i = 0; i < e.target.files.length; i++) {
                if(documentsName != "") documentsName += ", ";
                documentsName += e.target.files[i].name
            }
            $(this).next('.custom-file-label').text(documentsName);
        });
    });


    $(".responseButton").on("click", function() {
        let id = $(this).attr('id');
        
        $("button[id='" + id + "']").hide();
        $("[id='" + id + "']").show();
        $("span[id='" + id + "']").hide();
    });

    $('.deleteResponse').on('click', function() {
        let id = $(this).attr('id');

        $("form[id='" + id + "']").hide();
        $("button[id='" + id + "']").show();
        $("span[id='" + id + "']").hide();
    });

    $('.response').on('submit',function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        if($(this).serialize() == "textResponse="){

            $("span[id='" + id + "']").show();

        } else {

            $.ajax({
                type: $(this).attr("method"),
                url: $(this).attr("action"),
                headers: {name: $(this).attr('name')},
                data: $(this).serialize(),
            })
            .done((data) => {
                if(data.statut == 'ok') {
                    $(".zoneResponse[id='" + data.productReviewId + "']").load(" .zoneResponse[id='" + data.productReviewId + "']");
                } else if (data.statut == "formNotValid") {
                    alert("Veuillez remplir correctement le formulaire");

                } else {
                    alert("Vous devez être connecté pour écrire un commentaire");

                }
            });

        }

        return false
    });

    // Activation et désactivation des articles
    // $("#validateOrNotArticle").on("click", function() {
    //     $.ajax({
    //         type: "post",
    //         url: $(this).attr("data-url"),
    //     })
    //     .done((data) => {
    //         if(data.statut == 'ok') {
    //             $(this).parent().load(" .zoneAction[id='" + data.id + "']");
    //         } else {
    //             alert("Vous n'êtes pas authorisé à exécuter cette action");
    //         }
    //     });
    // });
});