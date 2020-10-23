$(document).ready(function() {
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
        if($(".test").val().length === 0){
            $(".test").after("<span style='color:red;'>Merci de remplir ce champ</span>");
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
                    //window.location.href = data.url;
                    //console.log(data);
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
});