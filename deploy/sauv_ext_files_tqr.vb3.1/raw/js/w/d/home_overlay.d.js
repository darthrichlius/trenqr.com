/* Contrôle du statut de l'overlay */
var overlay_state = "hidden";

/* Affichage / Disparition de l'overlay */
function manualOverlay(){
    home_backgroundSwitch();
    $('#home_overlay').fadeIn(function(){
        overlay_state = "displayed";
    });
}

function preregLoginOverlay(){
    home_backgroundSwitch();
    $('#home_badLogin').fadeIn(function(){
        overlay_state = "displayed";
    });
}

function moreInfoLoginOverlay(){
    home_backgroundSwitch();
    $('#home_moreInfoRequired').fadeIn(function(){
        overlay_state = "displayed";
    });
}

function todeleteOverlay(){
    home_backgroundSwitch();
    $('#delcancel_container').fadeIn(function(){
        overlay_state = "displayed";
    });
}

$(document).click(function(e){
    if(overlay_state === "displayed"){
        if($(e.target).is('.overlay, .overlay *')){
            return;
        } else if($('#home_moreInfoRequired').css('display') === 'block'){
            //on ne veut pas que cet overlay se barre
            return;
        } else if($('#delcancel_container').css('display') === 'block'){
            //idem
            return;
        } else {
            home_backgroundSwitch();
            $('#home_overlay').fadeOut();
            $('#home_badLogin').fadeOut();
            $('#home_birthdayRequired').fadeOut();
            overlay_state = "hidden";
        }
    }
});

/* Fonction de changement du fond */
function home_backgroundSwitch(){
    $('#home_theater').css('height', $(document).height());
    $('#home_theater').fadeToggle();
}

/* Gestion du scrolling de la sidebar */
$().ready(function() {
        var $scrollingDiv = $("#home_overlay");

        $(window).scroll(function(){			
                $scrollingDiv
                        .stop()
                        .animate({"marginTop": ($(window).scrollTop())}, "slow" );			
        });
});
    
    
/* Gestion du "submit" de la date de naissance dans l'overlay */
function homeOverlayButton(e){
    e.preventDefault();
    //Vérification que la date est correcte
    if($("#home_overlay_year").val() === 'init' | $("#home_overlay_month").val() === 'init' | $("#home_overlay_day").val() === 'init' | $('#home_overlay_superpw').val() === ''){
        //Erreur
        $('#home_overlay_year').addClass('error_border');
        $('#home_overlay_month').addClass('error_border');
        $('#home_overlay_day').addClass('error_border');
        $('#home_overlay_superpw').addClass('error_border');
    } else {
        $('#home_overlay_year').removeClass('error_border');
        $('#home_overlay_month').removeClass('error_border');
        $('#home_overlay_day').removeClass('error_border');
        $('#home_overlay_superpw').removeClass('error_border');
        //'Formatage' de la date en d-m-Y (d-m-Y et pas autre chose à cause du PHP plus loin)
        var fd = $('#home_overlay_day').val() + '-' + $('#home_overlay_month').val() + '-' + $('#home_overlay_year').val();
        //Stockage dans les input hidden
        $('#dd_hb').val(fd);
        $('#dd_hp').val($('#home_overlay_superpw').val());
        //Appel de la fonction de check. Cette fonction gère les vérifications, qu'il y ait un superpw ou pas
            ajaxHomepageDobCheck();
    }
}