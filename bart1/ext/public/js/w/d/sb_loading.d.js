$(document).ready(function(){
    /* Call Ajax qui va empêcher la page de se charger si l'utilisateur est déjà passé par ici */
    if(!sbAjaxCheck()){
        /* Redirection sur l'accueil pour le moment */
        window.location.href = 'index.php';
        return;
    }
    /* Gestion des points de suspension du titre */
    if($('#sb_loadinggroup').css('display') !== 'none'){
        setInterval(function(){
            switch($('#sb_suspension_mark').html()){
                case '...':
                    $('#sb_suspension_mark').html('.&nbsp;&nbsp;');
                    break;
                case '.&nbsp;&nbsp;':
                    $('#sb_suspension_mark').html('..&nbsp;');
                    break;
                case '..&nbsp;':
                    $('#sb_suspension_mark').html('...');
                    break;
            }
        }, 1000);
    }
    //LOGIQUE LOADINGBAR
    loadingFill(2000, 0);
    
    //LOGIQUE HINTS
    $('#sb_loadinghints').html(hintSelector());
    hintSlider();
    
    //LOGIQUE STATUS
    //todo
});

/* Fonction de random Integer */
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

/* Fonction à faire loop */
function loadingFill(i, iter){
    var lMax = $('#sb_loadingbar').width();
    var cLen = $('#sb_loadingfill').width();
    if(cLen < lMax && iter <= 7){
        statusSelector(iter);
        iter++;
        loadingBar(i, iter);
    } else {
        $('#sb_loadingfill').stop(true, true).width('520px');
        statusSelector(7);
        pageLoaded();
    }
}

/* Gestion de la barre de loading */
function loadingBar(to, it){
    if(it >= 7){
        //On remplit au dernier passage
        var df = getRandomInt(2000, 4000);
        $('#sb_loadingfill').animate({
        width: '520px',
        backgroundColor: 'rgb(' + getRandomInt(0, 255) + ', ' + getRandomInt(0, 255) + ', ' + getRandomInt(0, 255) + ')'
        }, to, function(){loadingFill(df, it);});
    } else {
        //d = delay
        var d = getRandomInt(2000, 4000);
        //s = salt
        /* C'est 'dangereux' d'avoir un random qui va au dessus de 75 (75 étant la moyenne
         * des tailles), puisque si mon random fait sur les 7 boucles plus de 75 de moyenne
         * je vais finir la barre plus rapidement. Mais bon, statistiquement, c'est improbable
         * puisque (10+100)/2 = 55, et 55 < 75. Le problème ne devrait pas se poser souvent.
         */
        var s = getRandomInt(10, 100);
        //l = length
        var l = $('#sb_loadingfill').width();
        $('#sb_loadingfill').animate({
            width: (l+s)+'px',
            backgroundColor: 'rgb(' + getRandomInt(0, 255) + ', ' + getRandomInt(0, 255) + ', ' + getRandomInt(0, 255) + ')'
        }, to, function(){
            loadingFill(d, it);
        });
    }
}

/* S'exécute quand la barre est remplie */
function pageLoaded(){
    $('#sb_loadigtitle').html('Compte cr&eacute;e avec succ&egrave;s !').css('background', 'transparent');
    $('#sb_btn_wrapper, #sb_btn_wrapper *').fadeIn();
    $('#sb_loadingfill').animate({
        backgroundColor: '#4EBF4E'
        //backgroundColor: '#00205F'
    });
}


/* Sélectionne et retourne un conseil */
function hintSelector(){
    var m = new String();
    var r = getRandomInt(0, 9);
    switch(r){
        case 0:
            m = Kxlib_getDolphinsValue("p_sb_hint0");
            break;
        case 1:
            m = Kxlib_getDolphinsValue("p_sb_hint1");
            break;
        case 2:
            m = Kxlib_getDolphinsValue("p_sb_hint2");
            break;
        case 3:
            m = Kxlib_getDolphinsValue("p_sb_hint3");
            break;
        case 4:
            m = Kxlib_getDolphinsValue("p_sb_hint4");
            break;
        case 5:
            m = Kxlib_getDolphinsValue("p_sb_hint5");
            break;
        case 6:
            m = Kxlib_getDolphinsValue("p_sb_hint6");
            break;
        case 7:
            m = Kxlib_getDolphinsValue("p_sb_hint7");
            break;
        case 8:
            m = Kxlib_getDolphinsValue("p_sb_hint8");
            break;
        case 9:
            m = Kxlib_getDolphinsValue("p_sb_hint9");
            break;
        default:
            m = Kxlib_getDolphinsValue("p_sb_hintdef");
            break;
    }
    return m;
}

/* Choix de faire en sorte que les status aient un ordre logique et non random */
function statusSelector(step){
    var statuses = new Array();
    var $this = $('#sb_loadingstatus');
    //Remplissage du tableau
    statuses[0] = 'Montage des échafaudages pour la création de votre page...';
    statuses[1] = 'Décalage des autres utilisateurs pour vous faire une place...';
    statuses[2] = 'Mise en place des paramètres de sécurité par défaut...';
    statuses[3] = 'Mise en place des paramètres de profil par défaut...';
    statuses[4] = 'Mise en place de l\'apparence par défaut...';
    statuses[5] = 'Envoi des invitations pour l\'innauguration de votre page...';
    statuses[6] = 'Préparation du comité d\'accueil et des cocktails de bienvenue...';
    statuses[7] = 'Tout est prêt :)';
    $this.fadeOut(function(){
        $this.html(statuses[step]);
        $this.fadeIn(); 
    });
}

/* Slider astuces */
function hintSlider(){
    setInterval(function(){
        $('#sb_loadinghints').fadeOut(function(){
            $('#sb_loadinghints').html(hintSelector());
            $('#sb_loadinghints').fadeIn();
        });
    }, 5000);
}

/* Slider statuts */
function statusSlider(){
    setInterval(function(){
        $('#sb_loadingstatus').fadeOut(function(){
            $('#sb_loadingstatus').html(statusSelector());
            $('#sb_loadingstatus').fadeIn();
        });
    }, 3000);
}

/* Fonction du call Ajax */
function sbAjaxCheck(){
    var dataset;
    var jsonData = new Object();
    jsonData.urqid = 'standbyStatusChecker';
    $.ajax({
        async: false,
        url: '../../__servers/serverStandby.php',
        type: 'POST',
        data: jsonData,
        success: function(data){
            dataset = JSON.parse(data);
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
    return dataset.allowed;
}