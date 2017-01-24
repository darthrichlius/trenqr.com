//Contrôle du statut de l'overlay
var overlay_state = "hidden";

//Affichage / Disparition de l'overlay pour le cas célébrité
$('#ins_famous').click(function(){
    ins_darkenBackground();
    $('#ins_overlay_famous').fadeIn(function(){
        overlay_state = "displayed";
    });
});

//Fonction à call pour le cas email
function insKnownUserOverlay(){
    ins_darkenBackground();
    $('#ins_overlay_knownuser').fadeIn(function(){
        overlay_state = "displayed";
    });
}

//Fonction à call pour le cas report
function insReportInscriptionOverlay(){
    ins_darkenBackground();
    $('#ins_overlay_report').fadeIn(function(){
        overlay_state = "displayed";
    });
}

//Fonction à call pour la reprise d'inscription
function insResumeOverlay(){
    ins_darkenBackground();
    $('#ins_overlay_resume').fadeIn(function(){
        overlay_state = "displayed";
    });
}

$(document).click(function(e){
    if(overlay_state === "displayed"){
        if($(e.target).is('#ins_overlay_resume')){
            return;
        } else {
            ins_darkenBackground();
            $('#ins_overlay_famous').fadeOut();
            $('#ins_overlay_knownuser').fadeOut();
            overlay_state = "hidden";
        }
    }
});

//Fonction de changement du fond
function ins_darkenBackground(){
    $('#ins_theater').css('height', $(document).height());
    $('#ins_theater').fadeToggle();
}

//Position dynamique des ovelays
$().ready(function(){
    var scrollingKnownUserOffset = $('#ins_overlay_knownuser');
    var scrollingFamous = $('#ins_overlay_famous');
    var scrollingBeta = $('#ins_overlay_beta');
    
    $(window).scroll(function(){
        var scroll = $(window).scrollTop() + 105;
        scrollingKnownUserOffset
                .stop()
                .animate({"marginTop": scroll}, 'slow');
        scrollingFamous
                .stop()
                .animate({"marginTop": scroll}, 'slow');
        scrollingBeta
                .stop()
                .animate({"marginTop": scroll}, 'slow');
    });
});

//Loop pour la mèche de beta-test
function fuseTimer(){
    var interval = 50;  //50ms d'intervalle
    var fill = 100;     //On commence à 100% de la barre
    var fuse = $('.ins_fuse_fill');
    
    var tid = setInterval(function(){
        fuse
                .stop(true, true)
                .animate({"width": fill+"%"}, "fast");
        fill--;
    }, interval);
    
    if(fill === 0){
        console.log('fuse stop');
        clearInterval(tid);
    }
}