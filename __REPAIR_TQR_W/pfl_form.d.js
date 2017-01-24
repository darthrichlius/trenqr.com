//<editor-fold defaultstate="collapsed" desc="Fonctions diverses">
/**********************/
/* FONCTIONS DIVERSES */
/**********************/

var aic = false;
function drc(){
    aic = true;
}

$().ready(function(){
    //Initialisation du slider 'gender' au bon endroit
    switch($('#slider_selector').data('g')){
        case 'm':
            $('#slider_selector').css('margin-left', '0');
            $('#slider_selector').css('margin-right', '40px');
            $('#pfl_gender_female').css('color', '#ddd');
            break;
        case 'f':
            $('#slider_selector').css('margin-left', '40px');
            $('#slider_selector').css('margin-right', '0');
            $('#pfl_gender_male').css('color', '#ddd');
            break;
    }
});


/* Gestion du slider pour le choix du genre */

//Fonction de changement de couleur pour l'animation du slider
function genderSliderColorChange(){
    $('#slider_selector').css('background-color', '#999');
    $('#slider_selector').animate({
        backgroundColor: '#bbb'
    }, 300);
}

//Changement au clic
//N.B.: Je suis obligé de passer par deux handlers (au lieu de tout faire
//avec une sélection multiple '#slider_base, #slider_selector') pour pouvoir
//empêcher le clic "à tarvers" du selector via stopPropagation();
function sliderChange(){
    var gender = $('#slider_selector').data('g');
    if(gender === "m"){
        $('#slider_selector').stop(true);
        $('#pfl_gender_male').css('color', '#eee');
        $('#pfl_gender_female').css('color', '#333');
        $('#slider_selector').animate({
            marginLeft: '40',
            marginRight: '0'
        });
        $('#slider_selector').data('g', 'f');
    } else if(gender === "f"){
        $('#slider_selector').stop(true);
        $('#pfl_gender_male').css('color', '#333');
        $('#pfl_gender_female').css('color', '#eee');
        $('#slider_selector').animate({
            marginLeft: '0',
            marginRight: '40'
        });
        $('#slider_selector').data('g', 'm');
    }
    genderSliderColorChange();
}

$('#slider_base').click(function(){
    sliderChange();
});

$('#slider_selector').click(function(e){
    sliderChange();
    e.stopPropagation();
});

function sliderUpdate(gender){
    if(gender === 'm'){
        $('#slider_selector').stop(true);
        $('#pfl_gender_male').css('color', '#333');
        $('#pfl_gender_female').css('color', '#eee');
        $('#slider_selector').animate({
            marginLeft: '0',
            marginRight: '40'
        });
    } else if(gender === 'f'){
        $('#slider_selector').stop(true);
        $('#pfl_gender_male').css('color', '#eee');
        $('#pfl_gender_female').css('color', '#333');
        $('#slider_selector').animate({
            marginLeft: '40',
            marginRight: '0'
        });
    }
}

//Gestion du clic sur les signes / lettres
//H
$('#pfl_gender_male').click(function(){
    $('#slider_selector').stop(true);
    $('#pfl_gender_male').css('color', '#333');
    $('#pfl_gender_female').css('color', '#eee');
    $('#slider_selector').animate({
        marginLeft: '0',
        marginRight: '40'
    });
    $('#slider_selector').data('g', 'm');
    genderSliderColorChange();
});

//F
$('#pfl_gender_female').click(function(){
    $('#slider_selector').stop(true);
    $('#pfl_gender_male').css('color', '#eee');
    $('#pfl_gender_female').css('color', '#333');
    $('#slider_selector').animate({
        marginLeft: '40',
        marginRight: '0'
    });
    $('#slider_selector').data('g', 'f');
    genderSliderColorChange();
});

//Gestion de la bordure des champs de date
$('#pfl_year, #pfl_month, #pfl_day').focus(function(){
    $('#pfl_year').css('border-color', '#bbb');
    $('#pfl_month').css('border-color', '#bbb');
    $('#pfl_day').css('border-color', '#bbb');
});

$('#pfl_year, #pfl_month, #pfl_day').blur(function(){
    $('#pfl_year').css('border-color', '#ddd');
    $('#pfl_month').css('border-color', '#ddd');
    $('#pfl_day').css('border-color', '#ddd');
});

//Gestion de l'affichage de l'explication pour secu_lock
$('#pfl_seculock_hint').click(function(){
    $('#pfl_seculock_detail').stop();
    $('#pfl_seculock_detail').slideToggle();
});

//Gestion de l'affichage des explications pour les blocages
$('.pfl_locks_hint').click(function(e){
    if($(e.target).prop('id') === 'pfl_hlock_hint'){
        $('#pfl_hlock_detail').stop();
        $('#pfl_hlock_detail').slideToggle();
    } else if($(e.target).prop('id') === 'pfl_dlock_hint'){
        $('#pfl_dlock_detail').stop();
        $('#pfl_dlock_detail').slideToggle();
    }
});

//HTMLENTITIES custom
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&apos;').replace(/=/g, '&equals;');
}

//</editor-fold>


//<editor-fold defaultstate="collapsed" desc="Contrôle des formulaires">
/****************************/
/* CONTRÔLE DES FORMULAIRES */
/****************************/

/* Liste des RegEx utilisées */
var regMini = /^([^'":;=])+$/;
var regFullname = /^[a-zA-Z- ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,40}$/;
var regNickname = /^[a-zA-Z0-9-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ@_]{2,20}$/;
//var regCity_OLD = /^[a-zA-Z-, ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñâôöø]{1,50}$/;
var regCity = /^[a-zA-Z0-9-,.` ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñâôöø]{1,50}$/;
var regMail = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;
var regPasswdIncorrect = /^[^"':;=]{0,5}$/;
var regPasswdWeak = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6,7}$/;
var regPasswdMed = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6}(((?=(.*[a-zA-Z]))[a-zA-Z]{2})|(([^;:="'])?(?=(.*[a-zA-Z0-9!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%]))[^:;="']))$/;
var regPasswdStrong = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6}(([^;:="'])?(?=(.*[a-zA-Z0-9!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%]))[^:;="']{3,14})$/;
var regMagicDate = /^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
var regHour = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;

/* Gestion de la <div> d'erreurs */
function pflErrorBox(){
    if($('#pfl_errmsg').html() === ''){
        $('#pfl_errbox').css('display', 'none');
    } else {
        $('#pfl_errbox').css('display', 'block');
    }
}

function pflErrorMsg(msg){
    $('#pfl_errmsg').html(msg);
}


//<editor-fold defaultstate="collapsed" desc="Formulaire: Profil">
/**********/
/* Profil */
/**********/

/* Fonction qui va load les données dans le form */
function profileLoader(/*pflid*/){
    // On charge dans lp (Loaded Profile) un objet contenant les données du form
    var lp = ajaxProfileLoader(/*pflid*/);
    if(lp !== null){
        $('#pfl_input_fullname').val(lp.fullname);
        var dbd = lp.birthday.split(' ');
        var bd = dbd[0].split('-');
        $('#pfl_day').val(bd[2]);
        $('#pfl_month').val(bd[1]);
        $('#pfl_year').val(bd[0]);
        $('#slider_selector').data('g', lp.gender);
        sliderUpdate(lp.gender);
        $('#pfl_input_city').data('cc', lp.cityId);
        $('#pfl_input_city').val(lp.cityName);
        $('#pfl_modrem_gender').html(lp.gender_mod_rem);
        $('#pfl_modrem_birthday').html(lp.birthday_mod_rem);
    }
}

/* Gestion du tooltip indicatif sur les countdowns */
$('#pfl_modrem_birthday').mouseenter(function(){
    $('#pfl_modrem_birthdaytooltip').stop(true);
    $('#pfl_modrem_birthdaytooltip').fadeIn();
});
$('#pfl_modrem_birthday').mouseleave(function(){
    $('#pfl_modrem_birthdaytooltip').stop(true);
    $('#pfl_modrem_birthdaytooltip').fadeOut();
});

$('#pfl_modrem_gender').mouseenter(function(){
    $('#pfl_modrem_gendertooltip').stop(true);
    $('#pfl_modrem_gendertooltip').fadeIn();
});
$('#pfl_modrem_gender').mouseleave(function(){
    $('#pfl_modrem_gendertooltip').stop(true);
    $('#pfl_modrem_gendertooltip').fadeOut();
});


/* Variables ErrType */
var pflFullnameErrtype = new String();
var pflCityErrtype = new String();
var pflBirthdayErrtype = new String();
/* Variable de lock générale | true = ok, false = problème */
var mutexProfile = new Boolean();

/* Profile Error Checker */
function profileErrorChecker(){
    var lockTable = $('.profile_error_checker');
    var errorArray = new Array();
    
    $.each(lockTable, function(k, v){
        if($(v).data('pfl') === 'lock'){
            errorArray.push(v);
        }
    });
    
    var errorCount = errorArray.length;
    if(errorCount > 1){
        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_multi");
        pflErrorMsg(msg);
    } else if(errorCount === 0){
        pflErrorMsg('');
        pflErrorBox();
    } else if(errorCount === 1){
        var arg = errorArray[0];
        var argSelector = $(arg).attr('id');
        
        switch(argSelector){
            case "pfl_input_fullname":
                switch(pflFullnameErrtype){
                    case "empty":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_fnempty");
                        pflErrorMsg(msg);
                        break;
                    case "badchars":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_fnbadchars");
                        pflErrorMsg(msg);
                        break;
                }
                break;
            case "pfl_birthday_date_group":
                switch(pflBirthdayErrtype){
                    case "invalid":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_bdinvalid");
                        pflErrorMsg(msg);
                        break;
                    case "tooyoung":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_bdyoung");
                        pflErrorMsg(msg);
                        break;
                    case "dne":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_bddne");
                        pflErrorMsg(msg);
                        break;
                }
                break;
            case "pfl_input_city":
                switch(pflCityErrtype){
                    case "empty":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_cityempty");
                        pflErrorMsg(msg);
                        break;
                    case "badchars":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_citybadchars");
                        pflErrorMsg(msg);
                        break;
                    case "unknown":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_cityunknown");
                        pflErrorMsg(msg);
                        break;
                    case "select":
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_cityselect");
                        pflErrorMsg(msg);
                        break;
                    default:
                        var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_cityunknerr");
                        pflErrorMsg(msg);
                        break;
                }
                break;
            case 'hidden_input_pw':
                var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_hiddenpw");
                pflErrorMsg(msg);
                break;
        }
        pflErrorBox();
    }
}

/* Datacheck functions */
function pflFullnameCheck(inputFullname){
    $('#pfl_errmsg').html('');
    if(inputFullname === ''){
        $('#pfl_input_fullname').data('pfl', 'lock');
        $('#pfl_input_fullname').addClass('error_border');
        pflFullnameErrtype = 'empty';
        mutexProfile = false;
        profileErrorChecker();
    }else if(!regFullname.test(inputFullname)){
        $('#pfl_input_fullname').data('pfl', 'lock');
        $('#pfl_input_fullname').addClass('error_border');
        pflFullnameErrtype = 'badchars';
        mutexProfile = false;
        profileErrorChecker();
    } else {
        $('#pfl_input_fullname').data('pfl', 'ulock');
        $('#pfl_input_fullname').removeClass('error_border');
        profileErrorChecker();
    }
}


function pflCityCheck(inputCity){
    //Reset du statut pour empêcher les flashs d'erreur
    $('#pfl_input_city').removeClass('error_border');
    $('#pfl_input_city').data('pfl', 'ulock');
    $('#pfl_errmsg').html('');
    profileErrorChecker();
    
    if(inputCity === ''){
        $('#pfl_input_city').data('pfl', 'lock');
        $('#pfl_input_city').addClass('error_border');
        pflCityErrtype = 'empty';
        mutexProfile = false;
        profileErrorChecker();
    } else if($('#pfl_input_city').data('cc') === '-1' && $('#pfl_input_city').data('temp') !== 'allowed' && $('#pfl_input_city').data('check') === 'true'){
        //Correspond au moment entre le clic dans la liste déroulante et le moment du clic sur le tableau.
        //Pas une erreur, mais il faut empêcher la submission de l'inscription dans l'état.
        $('#pfl_input_city').data('pfl', 'lock');
        $('#pfl_input_city').addClass('error_border');
        mutexProfile = false;
        pflCityErrtype = 'select';
        profileErrorChecker();
    }else {
        var cityNames = ajaxProfileCityList();
        if(!regCity.test(inputCity) && $('#pfl_input_city').data('check') === 'true'){
            $('#pfl_input_city').data('pfl', 'lock');
            $('#pfl_input_city').addClass('error_border');
            pflCityErrtype = 'badchars';
            profileErrorChecker();
            mutexProfile = false;
        } else if(!pflCityLoop(inputCity, cityNames) && $('#pfl_input_city').data('check') === 'true'){
            $('#pfl_input_city').data('pfl', 'lock');
            $('#pfl_input_city').addClass('error_border');
            mutexProfile = false;
            pflCityErrtype = 'unknown';
            profileErrorChecker();
        } else if($('#pfl_input_city').data('cc') === 'multi'){
            $('#pfl_input_city').addClass('error_border');
            //Pas d'erreur à proprement parler, mais le tooltip est affiché
            mutexProfile = false;
            profileErrorChecker();
//        } else if($('#pfl_input_city').data('cc') === '-1' && $('#pfl_input_city').data('check') === 'true'){
//            $('#pfl_input_city').data('pfl', 'lock');
//            $('#pfl_input_city').addClass('error_border');
//            mutexProfile = false;
//            pflCityErrtype = 'modified';
//            profileErrorChecker();
        } else {
            $('#pfl_input_city').data('pfl', 'ulock');
            $('#pfl_input_city').removeClass('error_border');
            profileErrorChecker();
        }
    }
}

function pflCityLoop(inputCity, cityNames){
    var isMatching = false;
    var lowerCaseInput = inputCity.toLowerCase();
    for(i = 0; i < cityNames.length; i++){
        var temp = cityNames[i].toLowerCase();        
        if(lowerCaseInput === temp){
            isMatching = true;
        }
    }
    return isMatching;
}

/* Birthday date validator */
function pflBirthdayValidator(){
    //var dob_ok = new Boolean();
    var dob_day = $('#pfl_day').val();
    var dob_month = $('#pfl_month').val();
    var dob_year = $('#pfl_year').val();
    var formatedDob = dob_month + "-" + dob_day + "-" + dob_year;
    
    if(dob_day === "init" | dob_month === "init" | dob_year === "init"){
        //dob_ok = false;
        $('#pfl_birthday_date_group').data('pfl', 'lock');
        $('#pfl_day').addClass('error_border');
        $('#pfl_month').addClass('error_border');
        $('#pfl_year').addClass('error_border');
        mutexProfile = false;
        pflBirthdayErrtype = 'invalid';
        profileErrorChecker();
    } else if(regMagicDate.test(formatedDob)){
        var d = new Date();
        var y = parseInt(dob_year, 10);
        if(y + 10 > d.getFullYear()){
            $('#pfl_birthday_date_group').data('pfl', 'lock');
            $('#pfl_day').addClass('error_border');
            $('#pfl_month').addClass('error_border');
            $('#pfl_year').addClass('error_border');
            mutexProfile = false;
            pflBirthdayErrtype = 'tooyoung';
            profileErrorChecker();
        } else {
            //dob_ok = true;
            $('#pfl_birthday_date_group').data('pfl', 'ulock');
            $('#pfl_day').removeClass('error_border');
            $('#pfl_month').removeClass('error_border');
            $('#pfl_year').removeClass('error_border');
            profileErrorChecker();
        }
    }  else {
        //dob_ok = false;
        $('#pfl_birthday_date_group').data('pfl', 'lock');
        $('#pfl_day').addClass('error_border');
        $('#pfl_month').addClass('error_border');
        $('#pfl_year').addClass('error_border');
        mutexProfile = false;
        pflBirthdayErrtype = 'dne';
        profileErrorChecker();        
    }
    //return dob_ok;
}

/**********************************************/
/* Gestion de l'autocompletion du champ Ville */
/**********************************************/
//Fonction de repérage des doublons
function duplicateOverseer(inputArray){
    var compteur = 1;
    var len = inputArray.length;
    if(len === 1){
        compteur = 1;
    } else {
        for(var i = 0; i < len; i++){
            if($.inArray(inputArray[i], inputArray, i+1) !== -1){
                compteur++;
            }
        }
    }
    return compteur;
}

//Fonction de "SELECT DISTINCT"
function selectDistinct(inputArray){
    var resultArray = new Array();
    var formatArray = new Array();
    var dupliArray = new Array();
    //On boucle sur le tableau donné en entrée
    for(var i = 0; i < inputArray.length; i++){
        //On met tous les éléments uniques dans un tableau
        if($.inArray(inputArray[i], resultArray) === -1){
            resultArray.push(inputArray[i]);
        //Et les doublons dans un autre
        } else {
            dupliArray.push(inputArray[i]);
        }
    }
    
    //On compte le nombre de doublons
    dupliArray.sort();
    var out = new Array();
    var currentElem = null;
    var cnt = 0;
    for (var j = 0; j < dupliArray.length; j++){
        if(dupliArray[j] !== currentElem){
            if(cnt > 0){
                out.push([currentElem, cnt+1]);
            }
            currentElem = dupliArray[j];
            cnt = 1;
        } else {
            cnt++;
        }
    }
    if(cnt > 0){
        out.push([currentElem, cnt+1]);
    }
    
    
    for(var k = 0; k < resultArray.length; k++){
        //lock pour empêcher d'afficher trouzemille fois les mêmes résultats
        var lock = false;
        for(var l = 0; l < out.length; l++){
            if(resultArray[k] === out[l][0]){
                formatArray.push('<div data-fortable="multi" class="pfl_customlist_choices"><span class="pfl_nom_ville">' + resultArray[k] + '</span> <span class="pfl_nb_villes">' + out[l][1] + ' '+Kxlib_getDolphinsValue("p_cities")+'</span></div>');
                lock = true;
            }
        }
        if(lock === false){
            formatArray.push('<div data-fortable="mono" class="pfl_customlist_choices">' + resultArray[k] + '</div>');
        }
    }
    
    return formatArray;
}

// ---------------------------

var globalArray = new Array();
var globalLight = new Array();
var multiArray = new Array();

function pflCityAutocomplete(tab){
    var cityDuplicates = 0;
    var srcArray = new Array();
    var lightArray = new Array();
    var light2 = new Array();
    
    //Reset des tableaux globaux
    globalArray.length = 0;
    globalLight.length = 0;
    multiArray.length = 0;
    
    //Virtuellement inutile, je suis obligé de faire ça pour que
    //mon tableau d'objets soit effectivement traité en tant
    //que tableau, et que je puisse travailler dessus.
    $.each(tab, function(index, value){
        srcArray.push(value);
        lightArray.push(value.asciiname);
    });

    for(var i = 0; i < srcArray.length; i++){
        /* On stocke toutes les données dans un tableau à 2 dimensions si on en a besoin
         * (plusieurs fois la même ville dans un même pays par exemple) */
        light2[i] = [srcArray[i].city_id, srcArray[i].asciiname];
        globalLight[i] = [srcArray[i].city_id, srcArray[i].asciiname];
        multiArray[i] = [srcArray[i].city_id, srcArray[i].asciiname, srcArray[i].ctr_name, srcArray[i].city_pop];
        globalArray[srcArray[i].city_id] = [srcArray[i].asciiname, srcArray[i].ctr_name, srcArray[i].city_pop];
    }
    /* On regarde si notre liste a des doublons.
     * Si oui, ça veut dire qu'on a plusieurs villes
     * avec le même nom dans un même pays. */
    cityDuplicates = duplicateOverseer(lightArray);
    //La partie suivante a été commentée suite à un bricolage visant à faire fonctionner
    //la liste déroulante des villes de manière asynchrone, et sans bug de transition entre
    //villes multiples et uniques
    // ---------
    /*if(cityDuplicates === 1 && tab.length !== 0){
        $('#customlistmulti').hide();
        $('#pfl_city_table_wrapper').slideUp();
        //Reset du check de l'input
        $('#pfl_input_city').data('check', 'true');
        $('#customlist').empty();
        //J'insère les données
        $.each(light2, function(i,v){
            var $o = $("<a/>");
            $o.html(v[1]);
            $o.data("city",v[0]);
            $o.addClass("jsbind-city-lis-elm");
            $o.addClass("city-lis-elm");
            //J'ajoute à mon conteneur
            $("#customlist").append($o);
        });
        //J'affiche ma liste
        $("#customlist").show();
    } else ------------------- */
    if(cityDuplicates >= 1 && tab.length !== 0){
        $('#customlist').hide();
        //On affiche la liste 'DISTINCT'
        var light3 = selectDistinct(lightArray);
        $('#customlistmulti').empty();
        $.each(light3, function(k, v){
            var $p = $('<a/>');
            $p.html(v);
            $p.addClass('jsbind-city-lis-elm');
            $p.addClass('city-lis-elm');
            if($('#customlistmulti > .jsbind-city-lis-elm').length < 10){
                $($p).on('mousedown', function(e){
                    e.stopPropagation();
                    //Cette ligne sert à ne pas soulever d'erreur entre le moment où l'utilisateur clique
                    //sur la liste et le moment où il choisit sa ville dans le tableau
                    $('#pfl_input_city').data('temp', 'allowed');
                    var target = $(e.target).closest('.pfl_customlist_choices');
                    HandleSelectMulti(target);
                    //On vire le tooltip
                    $('#city_tooltip_wrapper').fadeOut();
                });
                $("#customlistmulti").append($p);
            }
        });
        $('#customlistmulti').show();
        $('#pfl_city_spinner').hide();
    } else {
        $('#customlist').hide();
        $('#customlistmulti').hide();
        $('#pfl_city_spinner').hide();
    }
}

/*function HandleSelectAC (t) {
    $("#pfl_input_city").data("cc",t.data('city'));
    $("#pfl_input_city").val(t.html());
    $("#customlist").hide();
    pflCityCheck($('#pfl_input_city').val());
}*/

function HandleSelectMulti(t){
    if(t.data('fortable') === 'mono'){
        $('#pfl_input_city').val(t.html());
        $('#customlistmulti').hide();
        $('#pfl_city_table_wrapper').slideUp();
        for(var i = 0; i < multiArray.length; i++){
            if(multiArray[i][1] === $('#pfl_input_city').val()){
                $('#pfl_input_city').data('cc', multiArray[i][0]);
            }
        }
        pflCityCheck($('#pfl_input_city').val());
    } else if(t.data('fortable') === 'multi'){
        var cName = t.find('.pfl_nom_ville').html();
        $('#pfl_input_city').val(cName);
        $('#customlistmulti').hide();
        //On reset le tableau
        $('#pfl_city_table').html('<tr><th>'+Kxlib_getDolphinsValue("p_city")+'</th><th>'+Kxlib_getDolphinsValue("p_country")+'</th><th>'+Kxlib_getDolphinsValue("p_population")+'</th></tr>');
        //On le remplit
        for(var j = 0; j < multiArray.length; j++){
            if(multiArray[j][1] === cName){
                $('#pfl_city_table').append('<tr data-city=\''+ multiArray[j][0] +'\' class=\'citylist\'><td>'+ multiArray[j][1] +'</td><td>'+ multiArray[j][2] +'</td><td>'+ multiArray[j][3]+'</td></tr>');
            }
        }
        //On le montre
        $('#pfl_city_table_wrapper').slideDown(400, function(){
            $('#pfl_input_city').focus();
        });
        //On vire le tooltip
        $('#city_tooltip_wrapper').fadeOut();
    } else {
        console.log('Error: HandleSelectMulti(t)');        
    }
}

function manualInputChecker(lightTypeArray, manualInput){
    $.each(lightTypeArray, function(k, v){
        //v[1] correspond à la colonne 'name'
        if(manualInput.toLowerCase() === v[1].toLowerCase() && lightTypeArray.length === 1){
            $('#pfl_input_city').data('cc', v[0].toString());
            //console.log($('#pfl_input_city').data());
        } else if(manualInput.toLowerCase() === v[1].toLowerCase() && lightTypeArray.length !== 1){
            //On set 'manuellement' à 'multi' pour traiter le cas sur la validation
            $('#pfl_input_city').data('cc', 'multi');
            $('#pfl_city_tooltip_wrapper').fadeIn();
            //console.log($('#pfl_input_city').data());
        }
    });
}


//Delegate liste
/*$('#customlist').delegate('.city-lis-elm', 'mousedown', function(e){
    e.preventDefault();
    HandleSelectAC($(e.target));
});*/
/*$('#customlist').delegate('.city-lis-elm', 'mouseover', function(e){
    e.preventDefault();
});*/

//Delegate listemulti
/*$('#customlistmulti').on('mousedown', '.city-lis-elm', function(e){
    e.preventDefault();
    var target = $(e.target).closest('.pfl_customlist_choices');
    HandleSelectMulti(target);
});*/
/*$('#customlistmulti').delegate('.city-lis-elm', 'mouseover', function(e){
    e.preventDefault();
});*/

//Trigger de clic sur le tableau
$('#pfl_city_table, #pfl_city_table *').on('mousedown', '.citylist', function(){
    //On signale aussi qu'il n'y a pas besoin de valider l'intérieur de l'input
    $('#pfl_input_city').data('check', 'false');
});
$('#pfl_city_table').on('click', '.citylist', function(){
    //On remplit l'input
    var thisId = $(this).data('city');
    $('#pfl_input_city').val(globalArray[thisId][0] + ', ' + globalArray[thisId][1]);
    //Et on bind l'ID de la ville sélectionnée à notre input pour l'envoyer en AJAX
    $('#pfl_input_city').data('cc', thisId);
    $('#pfl_city_table_wrapper').slideUp();
    //On cache le tooltip d'info
    $('#pfl_city_tooltip_wrapper').fadeOut();
    //On recheck le contenu de la ville
    pflCityCheck($('#pfl_input_city').val());
});





/* Triggers */
$('#pfl_input_fullname').blur(function(){
    pflFullnameCheck($(this).val());
});

$(document).click(function(e){
    if($(e.target).is('#pfl_birthday_date_group, #pfl_birthday_date_group *')){
        //do nothing
    } else if($(e.target).is('#pfl_submit_profile')){
        //do nothing
    } else if($(e.target).is('#pfl_input_city')){
        //do nothing
    } else if($(e.target).is('#pfl_city_tooltip_wrapper, #pfl_city_tooltip')){
        HandleSelectMulti($('.pfl_customlist_choices'));
        $('#pfl_city_tooltip_wrapper').fadeOut();
    } else if($(e.target).is('#pfl_form_profile_div, #pfl_form_profile_div *')){
        pflBirthdayValidator();
        $('#customlist').hide();
        $('#customlistmulti').hide();
    } else {
        //do nothing
    }
    
    //Traitement du cas de ville non précisée
    if($(e.target).not("#pfl_input_city, #pfl_input_city *")){
        $('#pfl_input_city').data('temp', '0');
    }
});


$('#pfl_input_city').blur(function(){
    pflCityCheck($(this).val());
});

$('#pfl_input_city').focus(function(){
    if($(this).data('cc') === 'multi'){
        $('#customlistmulti').show();
    }
});

/* Désactivation de l'envoi du formulaire via 'ENTER' */
$('#pfl_form_profile').bind('keyup keypress', function(e){
    var key = e.which;
    if(key === 13){
        e.preventDefault();
        e.stopPropagation();
    } else if(key === 27){
        e.preventDefault();
        e.stopPropagation();
    }
});

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

var index = 0;
$('#pfl_input_city').keyup(function(e){
    delay(function(){
        //On cache le tableau de villes
        $('#city_table_wrapper').slideUp();
        //reset de cc
        $('#pfl_input_city').data('cc', '-1');
        //reset de check
        $('#pfl_input_city').data('check', 'true');
        if($('#pfl_input_city').val() === ''){
            $('#customlist').hide();
            $('#customlistmulti').hide();
        } else {
            //On affiche le spinner
            $('#pfl_city_spinner').show();
            ajaxProfileCitySuggestion();
            //manualInputChecker(globalLight, $('#pfl_input_city').val());
        }
        /*
        // ------ ARROW KEY NAV ------ //
        if($('#customlistmulti').css('display') === 'block'){
            switch(e.which){
                case 38: //Up
                    index--;
                    if(index < 1){index = 1;}
                    $('.city-lis-elm:nth-child('+ index +')').addClass('city-lis-elm-hover-emulation');
                    break;
                case 40: //Down
                    index++;
                    if(index > $('#customlistmulti .city-lis-elm').length){index = $('#customlistmulti .city-lis-elm').length;}
                    $('.city-lis-elm:nth-child('+ index +')').addClass('city-lis-elm-hover-emulation');
                    break;
                /*case 27: //Esc
                    console.log('titi');
                    $('#customlistmulti').hide();
                    e.preventDefault();
                    break;
                case 13: //Enter
                    $('.city-lis-elm:nth-child('+ index +') .pfl_customlist_choices').trigger('mousedown');
                    $('#customlist').hide();
                    break;
            }
        }
        //*/
    }, 250);
});

$('.pfl_submit_ph_wrapper #pfl_ph_profile').click(function(e){
    ajaxLoadHiddenpw('profile');
    $('#pfl_ph_profile_wrapper').hide();
    $('.pfl_form_group #hidden_div_profile').show();
    e.preventDefault();
});

$('.pfl_submit_ph_wrapper #pfl_ph_account').click(function(e){
    ajaxLoadHiddenpw('account');
    $('#pfl_ph_account_wrapper').hide();
    $('.pfl_form_group #hidden_div_account').show();
    e.preventDefault();
});

/* Fonction de vérification du passwd */
function pflProfileHiddenPw(e, inputPw){
    if(!regMini.test(inputPw)){
        $(e.target).find('.pfl_hidden_form_input').data('pfl', 'lock');
        $(e.target).find('.pfl_hidden_form_input').addClass('error_border');
        mutexProfile = false;
        profileErrorChecker();
    } else {
        var pwChecker = ajaxHiddenPwCheck(inputPw);
        if(pwChecker === true){        
            $(e.target).find('.pfl_hidden_form_input').data('pfl', 'ulock');
            $(e.target).find('.pfl_hidden_form_input').removeClass('error_border');
            profileErrorChecker();
        } else {
            $(e.target).find('.pfl_hidden_form_input').data('pfl', 'lock');
            $(e.target).find('.pfl_hidden_form_input').addClass('error_border');
            mutexProfile = false;
            profileErrorChecker();
        }
    }
}
function pflAccountHiddenPw(e, inputPw){
    if(!regMini.test(inputPw)){
        $(e.target).find('.pfl_hidden_form_input').data('acc', 'lock');
        $(e.target).find('.pfl_hidden_form_input').addClass('error_border');
        mutexAccountParam = false;
        AccountErrorChecker();
        //N'est utile que dans un seul cas
        return false;
    } else {
        var pwChecker = ajaxHiddenPwCheck(inputPw);
        if(pwChecker === true){        
            $(e.target).find('.pfl_hidden_form_input').data('pfl', 'ulock');
            $(e.target).find('.pfl_hidden_form_input').removeClass('error_border');
            AccountErrorChecker();
            //N'est utile que dans un seul cas
            return true;
        } else {
            $(e.target).find('.pfl_hidden_form_input').data('pfl', 'lock');
            $(e.target).find('.pfl_hidden_form_input').addClass('error_border');
            mutexAccountParam = false;
            AccountErrorChecker();
            //N'est utile que dans un seul cas
            return false;
        }
    }
}

//Test
$('#pfl_form_profile').submit(function(e){
    e.preventDefault();
    hidden_submit_profile(e);
});


/* Fonction de vérification générale */
function pflProfileGeneralCheck(){
    //Reset du mutex
    mutexProfile = true;
    
    pflFullnameCheck(htmlEntities($('#pfl_input_fullname').val()));
    pflBirthdayValidator();
    pflCityCheck(htmlEntities($('#pfl_input_city').val()));
}

/* GESTION DU SUBMIT */
function hidden_submit_profile(e){
    e.preventDefault();
    pflProfileGeneralCheck();
    pflProfileHiddenPw(e, $(e.target).find('.pfl_hidden_form_input').val());
    //pflProfileHiddenPw($('#hidden_input_profile').val());
    //var ajaxReturn = ajaxProfileFormChecker();
    if(mutexProfile === false /*| ajaxReturn === false*/){
        //console.log('Erreur - Formulaire non envoyé');
    } else if(mutexProfile === true/* && ajaxReturn === true*/){
        //console.log('Envoi du formulaire');
        ajaxProfileSave(e);
    } else {
        //console.log('Erreur inconnue');
    }
}


//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Formulaire: Compte">
/* Variables Errtype */
var nicknameErrtype = new String();
var emailErrtype = new String();
var newPasswdErrtype = new String();
/* Mutex */
var mutexAccountParam = new Boolean();
var mutexAccountPw = new Boolean();
var mutexSwitch = new Boolean();

function accountLoader(/*id*/){
    // On charge dans lp (Loaded Account) un objet contenant les données du form
    var la = ajaxAccountLoader(/*id*/);
    if(la !== null){
        $('#pfl_input_nickname').val(la.pseudo);
        $('#pfl_input_email').val(la.email);
        $('#pfl_lang').val(la.lang);
        $('#pfl_input_socialarea').val(la.soar);
    }
}


/* Account Error Checker */
function AccountErrorChecker(){
    var accLockTable = $('.account_error_checker');
    var accErrorArray = new Array();
    
    $.each(accLockTable, function (k, v){
        if($(v).data('acc') === 'lock'){
            accErrorArray.push(v);
        }
    });
    
    var accErrorCount = accErrorArray.length;
    if(accErrorCount > 1){
        var msg = Kxlib_getDolphinsValue("p_pfl_valacc_multi");
        pflErrorMsg(msg);
    } else if(accErrorCount === 0){
        pflErrorMsg('');
        pflErrorBox();
    } else if(accErrorCount === 1){
        var accArg = accErrorArray[0];
        var accArgSelector = $(accArg).attr('id');
        switch(accArgSelector){
            case 'pfl_input_nickname':
                switch(nicknameErrtype){
                    case 'empty':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valacc_psdempty");
                        pflErrorMsg(msg);
                        break;
                    case 'badchars':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valacc_psdbadchars");
                        pflErrorMsg(msg);
                        break;
                    case 'unavailable':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valacc_psdtaken");
                        pflErrorMsg(msg);
                        break;
                }
                break;
            case 'pfl_input_email':
                switch(emailErrtype){
                    case 'empty':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valacc_emailempty");
                        pflErrorMsg(msg);
                        break;
                    case 'badchars':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valacc_emailbadchars");
                        pflErrorMsg(msg);
                        break;
                    case 'unavailable':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valacc_emailtaken");
                        pflErrorMsg(msg);
                        break;
                }
                break;
            case 'pfl_input_socialarea':
                var msg = Kxlib_getDolphinsValue("p_pfl_valacc_badsocal");
                pflErrorMsg(msg);
                break;
            case 'pfl_input_oldpw':
                var msg = Kxlib_getDolphinsValue("p_pfl_valacc_badoldpw");
                pflErrorMsg(msg);
                break;
            case 'pfl_input_newpw':
                var msg = Kxlib_getDolphinsValue("p_pfl_valacc_badnewpw");
                pflErrorMsg(msg);
                break;
            case 'pfl_input_newpwconf':
                var msg = Kxlib_getDolphinsValue("p_pfl_valacc_badnewpwconf");
                pflErrorMsg(msg);
                break;
            case 'pfl_form_input_trialswitch':
                var msg = Kxlib_getDolphinsValue("p_pfl_valacc_trialswitch");
                pflErrorMsg(msg);
                break;
            case 'hidden_input_pw':
                var msg = Kxlib_getDolphinsValue("p_pfl_valpfl_hiddenpw");
                pflErrorMsg(msg);
                break;
        }
        pflErrorBox();
    }
}

/**
 * Cette fonction sert à display des messages d'erreur 'custom' destinés à gérer les erreurs qui ne sont
 * pas directement relatives aux champs (ex: erreur lors d'un changement de mot de passe).
 * @param {int} input
 */
function customAccountErrors(input){
    switch(input){
        case 'badpw':
            var msg = Kxlib_getDolphinsValue("p_pfl_valacc_pwu_badoldpw");
            pflErrorMsg(msg);
            pflErrorBox();
            $('#pfl_input_oldpw').addClass('error_border');
            break;
        case 'badconfirm':
            var msg = Kxlib_getDolphinsValue("p_pfl_valacc_pwu_badconf");
            pflErrorMsg(msg);
            pflErrorBox();
            $('#pfl_input_newpw').addClass('error_border');
            $('#pfl_input_newpwconf').addClass('error_border');
            break;
        case 'notconform':
            var msg = Kxlib_getDolphinsValue("p_pfl_valacc_pwu_badnewpw");
            pflErrorMsg(msg);
            pflErrorBox();
            $('#pfl_input_newpw').addClass('error_border');
            $('#pfl_input_newpwconf').addClass('error_border');
            break;
    }
}

/* Fonctions de check */
function pflNicknameCheck(inputNickname, available){
    $('#pfl_errmsg').html('');
    if(inputNickname === ''){
        $('#pfl_input_nickname').data('acc', 'lock');
        $('#pfl_input_nickname').addClass('error_border');
        nicknameErrtype = 'empty';
        mutexAccountParam = false;
        AccountErrorChecker();
    } else if(!regNickname.test(inputNickname)){
        $('#pfl_input_nickname').data('acc', 'lock');
        $('#pfl_input_nickname').addClass('error_border');
        nicknameErrtype = 'badchars';
        mutexAccountParam = false;
        AccountErrorChecker();
    } else if(available === false){
        $('#pfl_input_nickname').data('acc', 'lock');
        $('#pfl_input_nickname').addClass('error_border');
        nicknameErrtype = 'unavailable';
        mutexAccountParam = false;
        AccountErrorChecker();
    } else {
        $('#pfl_input_nickname').data('acc', 'ulock');
        $('#pfl_input_nickname').removeClass('error_border');
        AccountErrorChecker();
    }
}

function pflNicknameAvailable(){
    var available = ajaxPseudoAlreadyTaken();
    if(available === false){
        $('#pfl_nickname_taken_wrapper').finish();
        $('#pfl_nickname_taken_wrapper').fadeIn();
        $('#pfl_input_nickname').addClass('error_border');
        $('#pfl_input_nickname').data('acc', 'lock');
        mutexAccountParam = false;
    } else if(available === true){
        $('#pfl_nickname_taken_wrapper').finish();
        $('#pfl_nickname_taken_wrapper').fadeOut();
        $('#pfl_input_nickname').removeClass('error_border');
        $('#pfl_input_nickname').data('acc', 'ulock');
    }
}

function pflEmailCheck(inputEmail, available){
    $('#pfl_errmsg').html('');
    if(inputEmail === ''){
        $('#pfl_input_email').data('acc', 'lock');
        $('#pfl_input_email').addClass('error_border');
        emailErrtype = 'empty';
        mutexAccountParam = false;
        AccountErrorChecker();
    } else if(!regMail.test(inputEmail)){
        $('#pfl_input_email').data('acc', 'lock');
        $('#pfl_input_email').addClass('error_border');
        emailErrtype = 'badchars';
        mutexAccountParam = false;
        AccountErrorChecker();
    } else if(available === false){
        $('#pfl_input_email').data('acc', 'lock');
        $('#pfl_input_email').addClass('error_border');
        emailErrtype = 'unavailable';
        mutexAccountParam = false;
        AccountErrorChecker();
    } else {
        $('#pfl_input_email').data('acc', 'ulock');
        $('#pfl_input_email').removeClass('error_border');
        AccountErrorChecker();
    }
}

function pflEmailAvailable(){
    var available = ajaxEmailAlreadyTaken();
    if(available === false){
        $('#pfl_email_taken_wrapper').finish();
        $('#pfl_email_taken_wrapper').fadeIn();
        $('#pfl_input_email').addClass('error_border');
        $('#pfl_input_email').data('acc', 'lock');
        mutexAccountParam = false;
    } else if(available === true){
        $('#pfl_email_taken_wrapper').finish();
        $('#pfl_email_taken_wrapper').fadeOut();
        $('#pfl_input_email').removeClass('error_border');
        $('#pfl_input_email').data('acc', 'ulock');
    }
}

function pflEmailHasChanged(){
    var c = ajaxHasEmailChanged();
    if(c === true){
        if($('#pfl_veriflink_resend').length > 0){
            $('#pfl_veriflink_resend').text(Kxlib_getDolphinsValue("p_pfl_valacc_confresend_ch"));
            $('#pfl_veriflink_resend').data('action', 'newmail');
        }
        $('#pfl_email_basestatus').data('status', 'change');
        //ajouter message d'information à la sauvegarde
    } else if(c === false){
        if($('#pfl_veriflink_resend').length > 0){
            $('#pfl_veriflink_resend').text(Kxlib_getDolphinsValue("p_pfl_valacc_confresend"));
            $('#pfl_veriflink_resend').data('action', 'resend');
        }
        $('#pfl_email_basestatus').data('status', 'nochange');
    }
}


function pflSocialAreaCheck(inputSoAr){
    $('#pfl_errmsg').html('');
    //SoAr non obligatoire? Si ça l'est, changer ici
    if(inputSoAr !== 'init'){
        $('#pfl_input_socialarea').data('acc', 'ulock');
        $('#pfl_input_socialarea').removeClass('error_border');
        AccountErrorChecker();
    } else {
        $('#pfl_input_socialarea').data('acc', 'lock');
        $('#pfl_input_socialarea').addClass('error_border');
        mutexAccountParam = false;
        AccountErrorChecker();
    }
}

/* ---------- */
//Fonction de base de vérification. On s'assure juste qu'aucun des caractères interdits ne passe
function pflSwitchPasswdCheck(inputSwitchPasswd){
    $('#pfl_errmsg').html('');
    if(!regMini.test(inputSwitchPasswd)){
        $('#pfl_form_input_trialswitch').data('acc', 'lock');
        $('#pfl_form_input_trialswitch').addClass('error_border');
        mutexSwitch = false;
        AccountErrorChecker();
    } else {
        $('#pfl_form_input_trialswitch').data('acc', 'ulock');
        $('#pfl_form_input_trialswitch').removeClass('error_border');
        AccountErrorChecker();
    }
}
/* ---------- */

function pflOldPasswdCheck(inputOldPasswd){
    $('#pfl_errmsg').html('');
    if(inputOldPasswd === ''){
        $('#pfl_input_oldpw').data('acc', 'ulock');
        $('#pfl_input_oldpw').removeClass('error_border');
        AccountErrorChecker();
    } else if(!regPasswdStrong.test(inputOldPasswd)){
        if(!regPasswdMed.test(inputOldPasswd)){
            if(!regPasswdWeak.test(inputOldPasswd)){
                $('#pfl_input_oldpw').data('acc', 'lock');
                $('#pfl_input_oldpw').addClass('error_border');
                AccountErrorChecker();
            }
            AccountErrorChecker();
        }
        AccountErrorChecker();
    } else {
        $('#pfl_input_oldpw').data('acc', 'ulock');
        $('#pfl_input_oldpw').removeClass('error_border');
        AccountErrorChecker();
    }
}

function pflNewPasswdCheck(inputNewPasswd){
    $('#pfl_errmsg').html('');
    if(inputNewPasswd === ''){
        $('#pfl_input_newpw').data('acc', 'ulock');
        $('#pfl_input_newpw').removeClass('error_border');
        AccountErrorChecker();
    } else if(!regPasswdStrong.test(inputNewPasswd)){
        if(!regPasswdMed.test(inputNewPasswd)){
            if(!regPasswdWeak.test(inputNewPasswd)){
                $('#pfl_input_newpw').data('acc', 'lock');
                $('#pfl_input_newpw').addClass('error_border');
                AccountErrorChecker();
            }
            AccountErrorChecker();
        }
        AccountErrorChecker();
    } else {
        $('#pfl_input_newpw').data('acc', 'ulock');
        $('#pfl_input_newpw').removeClass('error_border');
        AccountErrorChecker();
    }
}

function PflNewPasswdConfCheck(inputNewPasswdCheck){
    if(inputNewPasswdCheck === ''){
        $('#pfl_input_newpwconf').data('acc', 'ulock');
        $('#pfl_input_newpwconf').removeClass('error_border');
        AccountErrorChecker();
    } else if(inputNewPasswdCheck === $('#pfl_input_newpw').val()){
        $('#pfl_input_newpwconf').data('acc', 'ulock');
        $('#pfl_input_newpwconf').removeClass('error_border');
        AccountErrorChecker();
    } else {
        $('#pfl_input_newpwconf').data('acc', 'lock');
        $('#pfl_input_newpwconf').addClass('error_border');
        AccountErrorChecker();        
    }
}

/* Gestion des triggers */
$('#pfl_input_nickname').blur(function(){
    //Ici on ne vérifie pas la disponibilité dans le check général pour ne pas
    //générer d'erreurs si il y en a. On traite à part.
    pflNicknameCheck($(this).val(), true);
    if(regNickname.test($(this).val())){
        pflNicknameAvailable();
    }
});

$('#pfl_input_email').blur(function(){
    //Mêmes explications qu'au dessus
    pflEmailCheck($(this).val(), true);
    if(regMail.test($(this).val())){
        pflEmailAvailable();
        pflEmailHasChanged();
    }
});

$('#pfl_input_socialarea').blur(function(){
    pflSocialAreaCheck($(this).val());
});

$('#pfl_form_input_trialswitch').blur(function(){
    pflSwitchPasswdCheck($(this).val());
});

$('#pfl_input_oldpw').blur(function(){
    pflOldPasswdCheck($(this).val());
});

$('#pfl_input_newpw').blur(function(){
    pflNewPasswdCheck($(this).val());
    if($('#pfl_input_newpwconf').val() !== ('')){
        PflNewPasswdConfCheck($('#pfl_input_newpwconf').val());
    }
});

$('#pfl_input_newpwconf').blur(function(){
    PflNewPasswdConfCheck($(this).val());
});


/* Fonction de vérification générale */
function pflAccountGeneralCheck(){
    //Ici également, on se fiche de la dispo
    pflNicknameCheck(htmlEntities($('#pfl_input_nickname').val()), true);
    //Same
    pflEmailCheck(htmlEntities($('#pfl_input_email').val()), true);
    pflSocialAreaCheck($('#pfl_input_socialarea').val());
    pflOldPasswdCheck($('#pfl_input_oldpw').val());
    pflNewPasswdCheck($('#pfl_input_newpw').val());
    PflNewPasswdConfCheck($('#pfl_input_newpwconf').val());
}

/* Fonction de vérification du password pour le remplissage de l'indicateur */
function pflPasswdBar(input){
    if(regPasswdIncorrect.test(input.val())){
        $('.pfl_passwd_fill').animate({
            'width': '0%'
        });
    } else if(regPasswdWeak.test(input.val())){
        $('.pfl_passwd_fill').animate({
            'width': '33%',
            'background-color': '#e72a2d'
        });
    } else if(regPasswdMed.test(input.val())){
        $('.pfl_passwd_fill').animate({
            'width': '66%',
            'background-color': '#c9f011'
        });
    } else if(regPasswdStrong.test(input.val())){
        $('.pfl_passwd_fill').animate({
            'width': '100%',
            'background-color': '#28f011'
        });
    } 
}


////Test
//$('#pfl_form_profile').submit(function(e){
//    e.preventDefault();
//    hidden_submit_profile(e);
//});
//
//
///* Fonction de vérification générale */
//function pflProfileGeneralCheck(){
//    //Reset du mutex
//    mutexProfile = true;
//    
//    pflFullnameCheck(htmlEntities($('#pfl_input_fullname').val()));
//    pflBirthdayValidator();
//    pflCityCheck(htmlEntities($('#pfl_input_city').val()));
//}
//
///* GESTION DU SUBMIT */
//function hidden_submit_profile(e){
//    e.preventDefault();
//    pflProfileGeneralCheck();
//    pflProfileHiddenPw(e, $(e.target).find('.pfl_hidden_form_input').val());
//    //pflProfileHiddenPw($('#hidden_input_profile').val());
//    //var ajaxReturn = ajaxProfileFormChecker();
//    if(mutexProfile === false /*| ajaxReturn === false*/){
//        //console.log('Erreur - Formulaire non envoyé');
//    } else if(mutexProfile === true/* && ajaxReturn === true*/){
//        //console.log('Envoi du formulaire');
//        ajaxProfileSave(e);
//    } else {
//        //console.log('Erreur inconnue');
//    }
//}


/* Gestion du submit des paramètres généraux */
$('#pfl_form_account_classic').submit(function(e){
//$('#pfl_submit_account_classic').click(function(e){
    e.preventDefault();
    
    //reset du mutex
    mutexAccountParam = true;
    
    //Verif hiddenpw
    pflAccountHiddenPw(e, $(e.target).find('.pfl_hidden_form_input').val());
    
    //var ajaxReturn = ajaxAccountParamsChecker();
    var availablePseudo = (regNickname.test($('#pfl_input_nickname').val())) ? ajaxPseudoAlreadyTaken() : false;
    var availableEmail = (regMail.test($('#pfl_input_email').val())) ? ajaxEmailAlreadyTaken() : false;
   
    pflNicknameCheck($('#pfl_input_nickname').val(), availablePseudo);
    pflEmailCheck($('#pfl_input_email').val(), availableEmail);
    pflSocialAreaCheck($('#pfl_input_socialarea').val());
    
    if(mutexAccountParam === false/* | ajaxReturn === false*/){
        e.preventDefault();
    } else if(mutexAccountParam === true/* && ajaxReturn === true*/){
        e.preventDefault();
        var bs = $('#pfl_email_basestatus').data('status');
        if(bs === 'change' && aic === true){
            ajaxAccountClassicSave();
        } else if(bs === 'change' && aic === false){
            $('#pfl_email_changewarning').slideDown();
            $(document).on('click', '#pfl_email_reconfirm', function(){
                $('#pfl_email_basestatus').data('status', 'reconfirm');
                $('#pfl_form_account_classic').submit();
                //$('#pfl_submit_account_classic').trigger('click');
            });
            $(document).on('click', '#pfl_email_cancel', function(){
                //en attendant de trouver mieux
                //location.reload(true);
            });
        } else if(bs === 'reconfirm' && aic === false){
            ajaxResendConfEmail($('#pfl_veriflink_resend').data('action'));
            ajaxAccountClassicSave();
        } else {
            ajaxAccountClassicSave();
        }
    } else {
        e.preventDefault();
    }
});

/* Gestion du submit du switch */
$('.pfl_trialswitch_submit').click(function(e){
    mutexSwitch = true;
    
    pflSwitchPasswdCheck($('#pfl_form_input_trialswitch').val());
    
    if(mutexSwitch === false){
        console.log('Erreur - Switch non effectué');
        e.preventDefault();
    } else if(mutexSwitch === true){
        console.log('Switch OK');
    } else {
        console.log('pflSwitchPasswdCheck - Erreur Inconnue');
    }
});

/* Gestion du submit du password */
$('#pfl_submit_account_passwd').click(function(e){
    //On ne veut pas que le formulaire s'exécute
    e.preventDefault();
    //reset du mutex
    mutexAccountPw = true;
    //ajaxReturnPw = ajaxAccountPasswdChecker();
    
    pflOldPasswdCheck($('#pfl_input_oldpw').val());
    pflNewPasswdCheck($('#pfl_input_newpw').val());
    PflNewPasswdConfCheck($('#pfl_input_newpwconf').val());
    
    //On enlève le cas où l'utilisateur valide avec rien dans les champs
    if($('#pfl_input_oldpw').val() === '' | $('#pfl_input_newpw').val() === '' | $('#pfl_input_newpwconf').val() === ''){mutexAccountPw = false;}
    
    if(mutexAccountPw === false/* | ajaxReturnPw === false*/){
        console.log('Erreur - Formulaire non envoyé');
        $('#pfl_input_oldpw').addClass('error_border');
        $('#pfl_input_newpw').addClass('error_border');
        $('#pfl_input_newpwconf').addClass('error_border');
        e.preventDefault();
    } else if(mutexAccountPw === true/* && ajaxReturnPw === true*/){
        ajaxAccountPasswdSave();
    } else {
        //console.log('Erreur inconnue');
    }
    
});

/* Gestion de la conversion en compte normal */
$(document).on('click', '#pfl_trialswitch_btn', function(){
    //L'id sera récupéré depuis $_SESSION
    ajaxTryaccountConverter();
});

/* Gestion du renvoi de mail de confirmation depuis le lien de l'onglet account */
$(document).on('click', '#pfl_veriflink_resend', function(e){
    e.preventDefault();
    //On commence par vérifier que le mail est effectivement dispo
    if(regMail.test($("#pfl_input_email").val())){
        pflEmailAvailable();
        if($('#pfl_input_email').data('acc') !== 'ulock'){
            //On ne fait rien
            return;
        } else {
            //On devra récupérer l'ID depuis $_SESSION
            //On passe le databind 'action' en paramètre pour savoir si on doit faire un 'resend' classique (même email)
            //ou un nouveau sur un nouvel email (en faisant le cancel sur l'ancien)
            var db = $('#pfl_veriflink_resend').data('action');
            //On doit commencer par vérifier si l'email est un "nouvel" email.
            if(db === 'newmail'){
                ajaxLoadHiddenpw('newmail');
            } else {
                //Sinon, on peut faire le renvoi normalement.
                ajaxResendConfEmail(db);
            }
            
        }
    } else {
        //On ne fait rien
        return;
    }
});
$(document).on('click', '#pfl_veriflink_passwd_ok', function(e){
    e.preventDefault();
    //Verif hiddenpw
    var ctrl = pflAccountHiddenPw(e, $(e.target).siblings('.pfl_hidden_form_input').val());
    
    var db = $('#pfl_veriflink_resend').data('action');    
    if(db === 'newmail' &&  ctrl === true){
        console.log('good');
        ajaxResendConfEmail(db);
    } else {
        //N'est pas censé arriver.
        $(e.target).siblings('.pfl_hidden_form_input').addClass('error_border');
        $(e.target).siblings('.pfl_hidden_form_input').data('acc', 'lock');
        mutexAccountParam = false;
        AccountErrorChecker();
        console.log('erreur data-action');
    }
});

/* Gestion du renvoi de mail de confirmation depuis le lien de l'onglet account */
$(document).on('click', '#pfl_append_unconf_email', function(){
    //Mêmes explications qu'au dessus.
    //Seule différence: on va cacher le bandeau orange tant que la page n'est pas rechargée, pour éviter d'agresser les yeux.
    var db = $('#pfl_veriflink_resend').data('action');
    ajaxResendConfEmail(db);
    $('#pfl_verifmail_reminder_container').hide();
});

//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Formulaire: Sécurité">
/************/
/* Sécurité */
/************/

/* Variable d'Errtype */
var lockdateDayEndErrtype = new String();
var lockdateDayStartErrtype = new String();
/* Variable de lock générale | true = ok, false = problème */
var mutexSecurity = new Boolean();

/* Data loader */
function securityLoader(/*id*/){
    // On charge dans lp (Loaded Account) un objet contenant les données du form
    var ls = ajaxSecurityLoader(/*id*/);
    if(ls !== null){
        if (parseInt(ls.stayconn) === 0){ls.stayconn = false;} else if(parseInt(ls.stayconn) === 1){ls.stayconn = true;}
        if (parseInt(ls.cowithpseudo) === 0){ls.cowithpseudo = false;} else if(parseInt(ls.cowithpseudo) === 1){ls.cowithpseudo = true;}
        if (parseInt(ls.thirdcriteria) === 0){ls.thirdcriteria = false;} else if(parseInt(ls.thirdcriteria) === 1){ls.thirdcriteria = true;}
        /*********/
        $('#pfl_input_stayconn').prop('checked', ls.stayconn);
        $('#pfl_input_cowithpseudo').prop('checked', ls.cowithpseudo);
        $('#pfl_input_thirdcriteria').prop('checked', ls.thirdcriteria);
        /*********/
        //Gestion des cas 'NULL' (00:00:00)
        var hls = ls.hlock_start.split(':');
        var hle = ls.hlock_end.split(':');
        if(hle[0] === '00' && hle[1] === '00' && hls[0] === '00' && hls[1] === '00'){
            hle[0] = 'init';
            hle[1] = 'init';
            hls[0] = 'init';
            hls[1] = 'init';
        }
        $('#hlock_start_hour').val(hls[0]);
        $('#hlock_start_min').val(hls[1]);
        $('#hlock_end_hour').val(hle[0]);
        $('#hlock_end_min').val(hle[1]);
        /*********/
        var dlso = ls.dlock_start.split(' ');
        var dls = dlso[0].split('-');
        //Gestion des cas 'NULL' (0000-00-00)
        if(dls[0] === '0000' && dls[1] === '00' && dls[2] === '00'){
            dls[0] = 'init';
            dls[1] = 'init';
            dls[2] = 'init';
        }
        $('#dlock_start_day').val(dls[2]);
        $('#dlock_start_month').val(dls[1]);
        $('#dlock_start_year').val(dls[0]);
        
        var dleo = ls.dlock_end.split(' ');
        var dle = dleo[0].split('-');
        //Gestion des cas 'NULL' (0000-00-00)
        if(dle[0] === '0000' && dle[1] === '00' && dle[2] === '00'){
            dle[0] = 'init';
            dle[1] = 'init';
            dle[2] = 'init';
        }
        $('#dlock_end_day').val(dle[2]);
        $('#dlock_end_month').val(dle[1]);
        $('#dlock_end_year').val(dle[0]);
    }
}


/* Même s'il n'y a que peu de valeurs à surveiller, on va quand même faire un ErrorManager.
 * Pour la clarté et l'évolutivité. */
function securityErrorChecker(){
    var secuDayLockTable = $('.lock_error_checker');
    var secuDayErrorArray = new Array();
    
    $.each(secuDayLockTable, function(k, v){
        if($(v).data('secu') === 'lock'){
            secuDayErrorArray.push(v);
        }
    });
    
    var secuDayErrorCount = secuDayErrorArray.length;
    if(secuDayErrorCount > 1){
        var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_multi");
        pflErrorMsg(msg);
    } else if(secuDayErrorCount === 0){
        pflErrorMsg('');
        pflErrorBox();
    } else if(secuDayErrorCount === 1){
        var secuDayArg = secuDayErrorArray[0];
        var secuDayArgSelector = $(secuDayArg).attr('id');
        switch(secuDayArgSelector){
            case 'pfl_dlock_start_group':
                switch(lockdateDayStartErrtype){
                    case 'pastdate':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_dls_pastdate");
                        pflErrorMsg(msg);
                        break;
                    default:
                        var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_dls_default");
                        pflErrorMsg(msg);
                        break;    
                }
                break;
            case 'pfl_dlock_end_group':
                switch(lockdateDayEndErrtype){
                    case 'baddate':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_dle_baddate");
                        pflErrorMsg(msg);
                        break;
                    case 'timeparadox':
                        var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_dle_paradox");
                        pflErrorMsg(msg);
                        break;
                    default:
                        var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_dle_default");
                        pflErrorMsg(msg);
                        break;
                }
                break;
            case 'pfl_hlock_start_group':
                var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_hls_default");
                pflErrorMsg(msg);
                break;
            case 'pfl_hlock_end_group':
                var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_hle_default");
                pflErrorMsg(msg);
                break;
            case 'hidden_input_pw':
                var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_lockhiddenpw");
                pflErrorMsg(msg);
                break;
            case 'pfl_delete_code':
                var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_baddeletepw");
                pflErrorMsg(msg);
                break;
            case 'pfl_delete_radio_group':
                var msg = Kxlib_getDolphinsValue("p_pfl_valsecu_deleteradio");
                pflErrorMsg(msg);
                break;
        }
        pflErrorBox();
    }
}

function pflLockHourStartValidation(){
    var lhs_hour = $('#hlock_start_hour').val();
    var lhs_min = $('#hlock_start_min').val();
    
    if(lhs_hour === 'init' && lhs_min === 'init'){
        $('#pfl_hlock_start_group').data('secu', 'ulock');
        $('#hlock_start_hour, #hlock_start_min').removeClass('error_border');
        securityErrorChecker();
    } else if(regHour.test(lhs_hour + ':' + lhs_min)){
        $('#pfl_hlock_start_group').data('secu', 'ulock');
        $('#hlock_start_hour, #hlock_start_min').removeClass('error_border');
        securityErrorChecker();
    } else {
        $('#pfl_hlock_start_group').data('secu', 'lock');
        $('#hlock_start_hour, #hlock_start_min').addClass('error_border');
        mutexSecurity = false;
        securityErrorChecker();
    }
}

function pflLockHourStartSubmission(){
    var lhs_hour = $('#hlock_start_hour').val();
    
    if(lhs_hour === 'init' && $('#hlock_end_hour').val() !== 'init'){
        $('#pfl_hlock_start_group').data('secu', 'lock');
        $('#hlock_start_hour, #hlock_start_min').addClass('error_border');
        mutexSecurity = false;
        securityErrorChecker();
    }
}

function pflLockHourEndValidation(){
    var lhe_hour = $('#hlock_end_hour').val();
    var lhe_min = $('#hlock_end_min').val();
    
    if(lhe_hour === 'init' && lhe_min === 'init'){
        $('#pfl_hlock_end_group').data('secu', 'ulock');
        $('#hlock_end_hour, #hlock_end_min').removeClass('error_border');
        securityErrorChecker();
    } else if(regHour.test(lhe_hour + ':' + lhe_min)){
        $('#pfl_hlock_end_group').data('secu', 'ulock');
        $('#hlock_end_hour, #hlock_end_min').removeClass('error_border');
        securityErrorChecker();
    } else {
        $('#pfl_hlock_end_group').data('secu', 'lock');
        $('#hlock_end_hour, #hlock_end_min').addClass('error_border');
        mutexSecurity = false;
        securityErrorChecker();
    }
}

function pflLockHourEndSubmission(){
    var lhe_hour = $('#hlock_end_hour').val();
    
    if(lhe_hour === 'init' && $('#hlock_start_hour').val() !== 'init'){
        $('#pfl_hlock_end_group').data('secu', 'lock');
        $('#hlock_end_hour, #hlock_end_min').addClass('error_border');
        mutexSecurity = false;
        securityErrorChecker();
    }
}

/* Fonctions de validation des dates */
function pflLockDateStartValidation(){
    //var lds_ok = new Boolean(); // lds <=> LockDateStart
    var lds_day = $('#dlock_start_day').val();
    var lds_month = $('#dlock_start_month').val();
    var lds_year = $('#dlock_start_year').val();
    var formated_lds = lds_month + '-' + lds_day + '-' + lds_year;
    
    if(lds_day === 'init' && lds_month === 'init' && lds_year === 'init'){
        //On autorise les valeurs de base dans le cas où on ne veut rien programmer
        //lds_ok = false;
        $('#pfl_dlock_start_group').data('secu', 'ulock');
        $('#dlock_start_day, #dlock_start_month, #dlock_start_year').removeClass('error_border');
        securityErrorChecker();
    } else if(regMagicDate.test(formated_lds)){
        if(lds_day !== 'init' && lds_month !== 'init' && lds_year !== 'init'){
            var today = new Date();
            var todayTs = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0).getTime();
            var input = new Date(lds_year, parseInt(lds_month) - 1, lds_day).getTime();
            if(todayTs <= input){
                $('#pfl_dlock_start_group').data('secu', 'ulock');
                $('#dlock_start_day, #dlock_start_month, #dlock_start_year').removeClass('error_border');
            } else {
                $('#pfl_dlock_start_group').data('secu', 'lock');
                $('#dlock_start_day, #dlock_start_month, #dlock_start_year').addClass('error_border');
                lockdateDayStartErrtype = 'pastdate';
                mutexSecurity = false;
            }
        }
        securityErrorChecker();
    } else {
        $('#pfl_dlock_start_group').data('secu', 'lock');
        $('#dlock_start_day, #dlock_start_month, #dlock_start_year').addClass('error_border');
        mutexSecurity = false;
        securityErrorChecker();
    }
}

function pflLockDateStartSubmission(){
    var lds_day = $('#dlock_start_day').val();
    var lds_month = $('#dlock_start_month').val();
    var lds_year = $('#dlock_start_year').val();
    var formated_lds = lds_month + '-' + lds_day + '-' + lds_year;
    var lde_day = $('#dlock_end_day').val();
    var lde_month = $('#dlock_end_month').val();
    var lde_year = $('#dlock_end_year').val();
    var formated_lde = lde_month + '-' + lde_day + '-' + lde_year;
    
    if(formated_lds === 'init-init-init' && formated_lde !== 'init-init-init'){
        $('#pfl_dlock_start_group').data('secu', 'lock');
        $('#dlock_start_day, #dlock_start_month, #dlock_start_year').addClass('error_border');
        mutexSecurity = false;
        securityErrorChecker();
    }
}

function pflLockDateEndValidation(){
    //var lde_ok = new Boolean(); // lde <=> LockDateEnd
    var lde_day = $('#dlock_end_day').val();
    var lde_month = $('#dlock_end_month').val();
    var lde_year = $('#dlock_end_year').val();
    var formated_lde = lde_month + '-' + lde_day + '-' + lde_year;
    var isDiffOk = lockdateDiffChecker();   //Boolean de contrôle qui vérifie que dateStart < dateEnd. Renvoie NaN si problème de parsing.
    
    if(lde_day === 'init' && lde_month === 'init' && lde_year === 'init'){
        //On autorise les valeurs de base dans le cas où on ne veut rien programmer
        //lde_ok = false;
        $('#pfl_dlock_end_group').data('secu', 'ulock');
        $('#dlock_end_day, #dlock_end_month, #dlock_end_year').removeClass('error_border');
        securityErrorChecker();
    } else if(regMagicDate.test(formated_lde)){
        if(isDiffOk === true){
            $('#pfl_dlock_end_group').data('secu', 'ulock');
            $('#dlock_end_day, #dlock_end_month, #dlock_end_year').removeClass('error_border');
        } else {
            $('#pfl_dlock_end_group').data('secu', 'lock');
            $('#dlock_end_day, #dlock_end_month, #dlock_end_year').addClass('error_border');
            lockdateDayEndErrtype = 'timeparadox';
            mutexSecurity = false;
        }
        securityErrorChecker();
    } else {
        $('#pfl_dlock_end_group').data('secu', 'lock');
        $('#dlock_end_day, #dlock_end_month, #dlock_end_year').addClass('error_border');
        lockdateDayEndErrtype = 'baddate';
        mutexSecurity = false;
        securityErrorChecker();
    }
}

function pflLockDateEndSubmission(){
    var lds_day = $('#dlock_start_day').val();
    var lds_month = $('#dlock_start_month').val();
    var lds_year = $('#dlock_start_year').val();
    var formated_lds = lds_month + '-' + lds_day + '-' + lds_year;
    var lde_day = $('#dlock_end_day').val();
    var lde_month = $('#dlock_end_month').val();
    var lde_year = $('#dlock_end_year').val();
    var formated_lde = lde_month + '-' + lde_day + '-' + lde_year;
    
    if(formated_lde === 'init-init-init' && formated_lds !== 'init-init-init'){
        $('#pfl_dlock_end_group').data('secu', 'lock');
        $('#dlock_end_day, #dlock_end_month, #dlock_end_year').addClass('error_border');
        lockdateDayEndErrtype = 'baddate';
        mutexSecurity = false;
        securityErrorChecker();
    }
}

function pflLockHourZeroMinLock(){
    var hlock_start = $('#hlock_start_hour').val()+':'+$('#hlock_start_min').val();
    var hlock_end = $('#hlock_end_hour').val()+':'+$('#hlock_end_min').val();
    if(hlock_start !== 'init:init' && hlock_end !== 'init:init'){
        if(hlock_start === hlock_end){
            $('#pfl_hlock_start_group').data('secu', 'lock');
            $('#hlock_start_hour, #hlock_start_min').addClass('error_border');
            $('#pfl_hlock_end_group').data('secu', 'lock');
            $('#hlock_end_hour, #hlock_end_min').addClass('error_border');
            mutexSecurity = false;
            securityErrorChecker();
        } else {
            $('#pfl_hlock_start_group').data('secu', 'ulock');
            $('#hlock_start_hour, #hlock_start_min').removeClass('error_border');
            $('#pfl_hlock_end_group').data('secu', 'ulock');
            $('#hlock_end_hour, #hlock_end_min').removeClass('error_border');
            securityErrorChecker();
        }
    }
}

function pflLockHiddenPw(e, inputPw){
    if(!regMini.test(inputPw)){
        $(e.target).find('.pfl_hidden_form_input').data('secu', 'lock');
        $(e.target).find('.pfl_hidden_form_input').addClass('error_border');
        mutexSecurity = false;
        securityErrorChecker();
    } else {
        var pwChecker = ajaxHiddenPwCheck(inputPw);
        if(pwChecker === true){        
            $(e.target).find('.pfl_hidden_form_input').data('secu', 'ulock');
            $(e.target).find('.pfl_hidden_form_input').removeClass('error_border');
            securityErrorChecker();
        } else {
            $(e.target).find('.pfl_hidden_form_input').data('secu', 'lock');
            $(e.target).find('.pfl_hidden_form_input').addClass('error_border');
            mutexSecurity = false;
            securityErrorChecker();
        }
    }
}

/* Gestion des dates impossibles */
function pflImpossibleStartDates(){
    //Reset des dates 'fausses'
    $('#dlock_start_day option').attr('disabled', false);
    
    switch($('#dlock_start_month').val()){
        case "02":
            var leapYear = new Date($('#dlock_start_year').val(),2,0).getDate();
            if(leapYear === 28){
                if($('#dlock_start_day').val() === "31" | $('#dlock_start_day').val() === "30" | $('#dlock_start_day').val() === "29"){
                    $('#dlock_start_day').val('28');
                }
                $('#dlock_start_day option[value="29"]').attr('disabled', true);
                $('#dlock_start_day option[value="30"]').attr('disabled', true);
                $('#dlock_start_day option[value="31"]').attr('disabled', true);
            } else if(leapYear === 29){
                if($('#dlock_start_day').val() === "31" | $('#dlock_start_day').val() === "30"){
                    $('#dlock_start_day').val('29');
                }
                $('#dlock_start_day option[value="30"]').attr('disabled', true);
                $('#dlock_start_day option[value="31"]').attr('disabled', true);
            }
            break;
        case "04":
            if($('#dlock_start_day').val() === "31"){
                $('#dlock_start_day').val('30');
            }
            $('#dlock_start_day option[value="31"]').attr('disabled', true);
            break;
        case "06":
            if($('#dlock_start_day').val() === "31"){
                $('#dlock_start_day').val('30');
            }
            $('#dlock_start_day option[value="31"]').attr('disabled', true);
            break;
        case "09":
            if($('#dlock_start_day').val() === "31"){
                $('#dlock_start_day').val('30');
            }
            $('#dlock_start_day option[value="31"]').attr('disabled', true);
            break;
        case "11":
            if($('#dlock_start_day').val() === "31"){
                $('#dlock_start_day').val('30');
            }
            $('#dlock_start_day option[value="31"]').attr('disabled', true);
            break;
    }
}

$('#dlock_start_month').change(function(){
    pflImpossibleStartDates();
});

$('#dlock_start_year').change(function(){
    pflImpossibleStartDates();
});


function pflImpossibleEndDates(){
    //Reset des dates 'fausses'
    $('#dlock_end_day option').attr('disabled', false);
    
    switch($('#dlock_end_month').val()){
        case "02":
            var leapYear = new Date($('#dlock_end_year').val(),2,0).getDate();
            if(leapYear === 28){
                if($('#dlock_end_day').val() === "31" | $('#dlock_end_day').val() === "30" | $('#dlock_end_day').val() === "29"){
                    $('#dlock_end_day').val('28');
                }
                $('#dlock_end_day option[value="29"]').attr('disabled', true);
                $('#dlock_end_day option[value="30"]').attr('disabled', true);
                $('#dlock_end_day option[value="31"]').attr('disabled', true);
            } else if(leapYear === 29){
                if($('#dlock_end_day').val() === "31" | $('#dlock_end_day').val() === "30"){
                    $('#dlock_end_day').val('29');
                }
                $('#dlock_end_day option[value="30"]').attr('disabled', true);
                $('#dlock_end_day option[value="31"]').attr('disabled', true);
            }
            break;
        case "04":
            if($('#dlock_end_day').val() === "31"){
                $('#dlock_end_day').val('30');
            }
            $('#dlock_end_day option[value="31"]').attr('disabled', true);
            break;
        case "06":
            if($('#dlock_end_day').val() === "31"){
                $('#dlock_end_day').val('30');
            }
            $('#dlock_end_day option[value="31"]').attr('disabled', true);
            break;
        case "09":
            if($('#dlock_end_day').val() === "31"){
                $('#dlock_end_day').val('30');
            }
            $('#dlock_end_day option[value="31"]').attr('disabled', true);
            break;
        case "11":
            if($('#dlock_end_day').val() === "31"){
                $('#dlock_end_day').val('30');
            }
            $('#dlock_end_day option[value="31"]').attr('disabled', true);
            break;
    }
}

$('#dlock_end_month').change(function(){
    pflImpossibleEndDates();
});

$('#dlock_end_year').change(function(){
    pflImpossibleEndDates();
});


/* ********** */

function pflImpossibleBirthday(){
    //Reset des dates 'fausses'
    $('#pfl_day option').attr('disabled', false);
    
    switch($('#pfl_month').val()){
        case "02":
            var leapYear = new Date($('#pfl_year').val(),2,0).getDate();
            if(leapYear === 28){
                if($('#pfl_day').val() === "31" | $('#pfl_day').val() === "30" | $('#pfl_day').val() === "29"){
                    $('#pfl_day').val('28');
                }
                $('#pfl_day option[value="29"]').attr('disabled', true);
                $('#pfl_day option[value="30"]').attr('disabled', true);
                $('#pfl_day option[value="31"]').attr('disabled', true);
            } else if(leapYear === 29){
                if($('#pfl_day').val() === "31" | $('#pfl_day').val() === "30"){
                    $('#pfl_day').val('29');
                }
                $('#pfl_day option[value="30"]').attr('disabled', true);
                $('#pfl_day option[value="31"]').attr('disabled', true);
            }
            break;
        case "04":
            if($('#pfl_day').val() === "31"){
                $('#pfl_day').val('30');
            }
            $('#pfl_day option[value="31"]').attr('disabled', true);
            break;
        case "06":
            if($('#pfl_day').val() === "31"){
                $('#pfl_day').val('30');
            }
            $('#pfl_day option[value="31"]').attr('disabled', true);
            break;
        case "09":
            if($('#pfl_day').val() === "31"){
                $('#pfl_day').val('30');
            }
            $('#pfl_day option[value="31"]').attr('disabled', true);
            break;
        case "11":
            if($('#pfl_day').val() === "31"){
                $('#pfl_day').val('30');
            }
            $('#pfl_day option[value="31"]').attr('disabled', true);
            break;
    }
}

$('#pfl_day, #pfl_month, #pfl_year').change(function(){
    pflImpossibleBirthday();
});


/* Calcul de la différence de temps entre la date de début et la date de fin.
 * Si cette différence est inf. à zéro, y'a un lézard. */
function lockdateDiffChecker(){
    var startTimestamp = new Date($('#dlock_start_month').val() + '/' + $('#dlock_start_day').val() + '/' + $('#dlock_start_year').val()).getTime();
    var endTimestamp = new Date($('#dlock_end_month').val() + '/' + $('#dlock_end_day').val() + '/' + $('#dlock_end_year').val()).getTime();
    var diff = endTimestamp - startTimestamp;
    
    if(diff <= 0){
        return false;
    } else {
        return true;
    }
}

/* Remplissage de dlock_start par la date du jour */
function todayFill(){
    var today = new Date();
    if(today.getDate() <= 9){
        $('#dlock_start_day').val('0' + today.getDate());
    } else if(today.getDate() > 9){
        $('#dlock_start_day').val(today.getDate());
    }
    //----//
    if(today.getMonth() <= 8){
        var mValue = parseInt(today.getMonth()) + 1;
        $('#dlock_start_month').val('0' + mValue);
    } else if (today.getMonth > 8){
        var mValue = parseInt(today.getMonth()) + 1;
        $('#dlock_start_month').val(mValue);
    }
    //----//
    $('#dlock_start_year').val(today.getFullYear());
}

/* Check du PW */
function pflLockPasswdCheck(inputPw){
    if(!regMini.test(inputPw)){
        $('#hidden_input_lock').data('secu', 'lock');
        $('#hidden_input_lock').addClass('error_border');
        mutexSecurity = false;
        securityErrorChecker();
    } else {
        $('#hidden_input_lock').data('secu', 'ulock');
        $('#hidden_input_lock').removeClass('error_border');
        securityErrorChecker();
    }
}


/* Gestion des triggers */
$(document).click(function(e){
    if($(e.target).is('#pfl_dlock_start_group, #pfl_dlock_start_group *')){
        hlockMinFiller();
        pflLockDateEndValidation();
        pflLockHourStartValidation();
        pflLockHourEndValidation();
    } else if($(e.target).is('#pfl_dlock_end_group, #pfl_dlock_end_group *')){
        hlockMinFiller();
        pflLockDateStartValidation();
        pflLockHourStartValidation();
        pflLockHourEndValidation();
    } else if($(e.target).is('#pfl_hlock_start_group, #pfl_hlock_start_group *')){
        hlockMinFiller();
        pflLockDateStartValidation();
        pflLockDateEndValidation();
        pflLockHourEndValidation();
    } else if($(e.target).is('#pfl_hlock_end_group, #pfl_hlock_end_group *')){
        hlockMinFiller();
        pflLockDateStartValidation();
        pflLockDateEndValidation();
        pflLockHourStartValidation();
    } else if ($(e.target).is('#pfl_submit_secu_locks')){
        //do nothing
    } else if ($(e.target).is('#pfl_form_security_div, #pfl_form_security_div *')) {
        hlockMinFiller();
        pflLockDateStartValidation();
        pflLockDateEndValidation();
        pflLockHourStartValidation();
        pflLockHourEndValidation();
    }
});

$('.dlock_now, .dlock_now *').click(function(e){
    todayFill();
    e.preventDefault();
});

$('.pfl_submit_ph_wrapper #pfl_ph_lock').click(function(e){
    ajaxLoadHiddenpw('security');
    $('#pfl_ph_lock_wrapper').hide();
    $('.pfl_form_group #hidden_div_lock').show();
    e.preventDefault();
});

//Fonction de vérification générale
function pflSecurityGeneralCheck(){
    //Reset du mutex
    mutexSecurity = true;
    pflLockHourStartValidation();
    pflLockHourEndValidation();
    pflLockDateStartValidation();
    pflLockDateEndValidation();
    // --- //
    pflLockHourStartSubmission();
    pflLockHourEndSubmission();
    pflLockDateStartSubmission();
    pflLockDateEndSubmission();
    // --- //
    pflLockHourZeroMinLock();
}

/* Fonction de 'remplissage' des minutes des hlock */
function hlockMinFiller(){
    if($('#hlock_start_hour').val() !== 'init' && $('#hlock_start_min').val() === 'init'){
        $('#hlock_start_min').val('00');
    }
    
    if($('#hlock_end_hour').val() !== 'init' && $('#hlock_end_min').val() === 'init'){
        $('#hlock_end_min').val('00');
    }
    
}

/* GESTION DES SUBMIT */
$('#pfl_submit_secu').click(function(e){
    e.preventDefault();
    ajaxSecurityClassicSave();
    
    /*var ajaxReturn = ajaxSecurityFormChecker();
    if(ajaxReturn === false){
        //console.log('Erreur - Formulaire non envoyé');
        e.preventDefault();
    } else if(ajaxReturn === true){
        //console.log('Formulaire envoyé');
    } else {
        //console.log('Erreur inconnue');
        e.preventDefault();
    }*/
});

$('#pfl_form_security_locks').submit(function(e){
    e.preventDefault();
    hidden_submit_locks(e);
});

function hidden_submit_locks(e){
    e.preventDefault();
    hlockMinFiller();
    pflSecurityGeneralCheck();
    //pflLockPasswdCheck($('#hidden_input_lock').val());
    pflLockHiddenPw(e, $(e.target).find('.pfl_hidden_form_input').val());
    //var ajaxReturn = ajaxSecurityFormLockChecker();
    if(mutexSecurity === false /*| ajaxReturn === false*/){
        //console.log('Erreur - Formulaire de lock non envoyé');
    } else if(mutexSecurity === true /*&& ajaxReturn === true*/){
        //console.log('Formulaire de lock envoyé');
        ajaxSecurityLockSave(e);
    } else {
        console.log('Erreur inconnue');
    }
}

/* GESTION DES RESETS HLOCK / DLOCK */
$('.pfl_lock_cancel #hlock_cancel_prompt').click(function(e){
    $('#hlock_cancel_conf').css('display', 'block');
    $('#hlock_cancel_hint').css('display', 'none');
    e.preventDefault();
});

$('.pfl_lock_cancel #dlock_cancel_prompt').click(function(e){
    $('#dlock_cancel_conf').css('display', 'block');
    $('#dlock_cancel_hint').css('display', 'none');
    e.preventDefault();
});

$('.pfl_lock_cancel #hlock_cancel_return').click(function(e){
    $('#hlock_cancel_conf').css('display', 'none');
    $('#hlock_cancel_hint').css('display', 'block');
    e.preventDefault();
});

$('.pfl_lock_cancel #dlock_cancel_return').click(function(e){
    $('#dlock_cancel_conf').css('display', 'none');
    $('#dlock_cancel_hint').css('display', 'block');
    e.preventDefault();
});
// ---- //
$('.pfl_lock_cancel #hlock_cancel').click(function(e){
    e.preventDefault();
    ajaxHlockReset();
    $('#hlock_start_hour option:eq(0)').prop('selected', true);
    $('#hlock_start_min option:eq(0)').prop('selected', true);
    $('#hlock_end_hour option:eq(0)').prop('selected', true);
    $('#hlock_end_min option:eq(0)').prop('selected', true);
});

$('.pfl_lock_cancel #dlock_cancel').click(function(e){
    e.preventDefault();
    ajaxDlockReset();
    $('#dlock_start_day option:eq(0)').prop('selected', true);
    $('#dlock_start_month option:eq(0)').prop('selected', true);
    $('#dlock_start_year option:eq(0)').prop('selected', true);
    $('#dlock_end_day option:eq(0)').prop('selected', true);
    $('#dlock_end_month option:eq(0)').prop('selected', true);
    $('#dlock_end_year option:eq(0)').prop('selected', true);
});


/************************/
/* SUPRESSION DE COMPTE */
/************************/
var mutexDeletion = new Boolean();
//Gestion de lu textarea sur la 5e raison | A modifier en fonction de l'ID de la réponse avec explications
$('input[name=pfl_deactivation_reason]').change(function(){
    deleteDetails();
});
        
function deleteDetails(){
    if($('#pfl_delete_reason_five').prop('checked') === true){
        $('#pfl_delete_reason_textarea').finish();
        $('#pfl_delete_reason_textarea').slideDown();
    } else {
        $('#pfl_delete_reason_textarea').finish();
        $('#pfl_delete_reason_textarea').slideUp();
    }
}


function deletePasswdBlur(inputpw){
    if(inputpw === ''){
        $('#pfl_delete_code').data('secu', 'ulock');
        $('#pfl_delete_code').removeClass('error_border');
        securityErrorChecker();
    } else if(!regMini.test(inputpw)){
        $('#pfl_delete_code').data('secu', 'lock');
        $('#pfl_delete_code').addClass('error_border');
        securityErrorChecker();
    } else {
        $('#pfl_delete_code').data('secu', 'ulock');
        $('#pfl_delete_code').removeClass('error_border');
        securityErrorChecker();
    }
}

function deletePasswdValid(inputpw){
    if(inputpw === ''){
        $('#pfl_delete_code').data('secu', 'lock');
        $('#pfl_delete_code').addClass('error_border');
        securityErrorChecker();
        mutexDeletion = false;
    } else if(!regMini.test(inputpw)){
        $('#pfl_delete_code').data('secu', 'lock');
        $('#pfl_delete_code').addClass('error_border');
        securityErrorChecker();
        mutexDeletion = false;
    } else {
        $('#pfl_delete_code').data('secu', 'ulock');
        $('#pfl_delete_code').removeClass('error_border');
        securityErrorChecker();
        mutexDeletion = true;
    }
}

function deleteReasonValid(){
    //On va raisonner à l'envers: on pars du principe qu'aucun radio n'est coché
    //(donc on bloque le mutex), et si on en trouve un, on le débloque
    mutexDeletion = false;
    $('.pfl_radio_wrapper input[type=radio]').each(function(){
        if($(this).prop('checked') === true){
            mutexDeletion = true;
        }
    });
    if(mutexDeletion === false){
        $('#pfl_delete_radio_group').data('secu', 'lock');
        securityErrorChecker();
    } else {
        $('#pfl_delete_radio_group').data('secu', 'ulock');
        securityErrorChecker();
    }
}

//Blur
$('#pfl_delete_code').blur(function(){
    deletePasswdBlur($(this).val());
});

//Submit
$('#pfl_delete_account_submit').click(function(e){
    e.preventDefault();
    //Reset du mutex
    mutexDeletion = true;
    
    deletePasswdValid($('#pfl_delete_code').val());
    deleteReasonValid();
    
    if(mutexDeletion === true){
        ajaxDeleteAccount();
    }
});

//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Comptes bloqués">
/*******************/
/* COMPTES BLOQUÉS */
/*******************/
/*** WORK IN PROGRESS ***/
function blockedAccountsFiller(ll, hl){
    var loadingDiv = '<div id="bloacc_scroll_load">'+Kxlib_getDolphinsValue("p_pfl_bloacc_loading")+'</div>';
    
    if(ll >= hl){
        //Empêche les mauvais comportements
        return;
    }
    
    //0 et 20 sont les bornes de base. On ne charge que les 20 premiers résultats dans un premier temps.
    var blockedAccounts = ajaxBlockedAccounts(ll, hl);
    //Si on arrive à la fin de la liste on quitte la fonction prématurément
    if(blockedAccounts[0].populated === 'end'){
        $('#bloacc_listdiv').unbind('scroll');
        return;
    }
    $('#bloacc_empty_lz').hide();
    //$('#bloacc_listzone').empty();
    if(blockedAccounts[0].populated === true && blockedAccounts[0].isBlocked === true){
        for(var i = 0; i < blockedAccounts.length; i++){
            //Note: les éléments variables qui sont append sont issus de la base. Donc l'ID est celui de l'user.
//            $('#bloacc_listzone').append('<div class="bloacc_listitem" id="'+ blockedAccounts[i].id +'"><div class="bloacc_itempic"><img src="'+ blockedAccounts[i].picPath +'"/></div><div class="bloacc_itemcontent"><div class="bloacc_itemcontent_pseudo bold">@'+  blockedAccounts[i].pseudo +'</div><div class="bloacc_itemcontent_fullname">'+ blockedAccounts[i].fullname +'</div><div class=bloacc_itemcontent_since>Depuis le : '+ blockedAccounts[i].since +'</div></div><div class="bloacc_itemcontent"><div class="bloacc_edit_reason"><a href="#" class="bloacc_edit_reason_button">&Eacute;diter</a></div><div class="bloacc_unblock_zone"><a class="bloacc_unblock_btn" href="#">Débloquer</a></div></div></div>');

            var g = $("<img/>").load(function(){
                //OK
                //TODO : Ajouter lien sur image (si src par défaut)
            }).error(function(){
                this.src = "http://placehold.it/80x80";
            }).attr("src",blockedAccounts[i].picPath);
            
            var d = $('<div class="bloacc_listitem" id="'+ blockedAccounts[i].id +'"><div class="bloacc_itempic"></div><div class="bloacc_itemcontent"><div class="bloacc_itemcontent_pseudo bold">@'+  blockedAccounts[i].pseudo +'</div><div class="bloacc_itemcontent_fullname">'+ blockedAccounts[i].fullname +'</div><div class=bloacc_itemcontent_since>Depuis le : '+ blockedAccounts[i].since +'</div></div><div class="bloacc_itemcontent"><div class="bloacc_edit_reason"><a href="#" class="bloacc_edit_reason_button">'+Kxlib_getDolphinsValue("p_pfl_bloacc_edit")+'</a></div><div class="bloacc_unblock_zone"><a class="bloacc_unblock_btn" href="#">'+Kxlib_getDolphinsValue("p_pfl_bloacc_unblock")+'</a></div></div></div>');
            
            $(d).find(".bloacc_itempic").append(g);
            
            $('#bloacc_listzone').append(d);
            //$('#bloacc_scroll_load').insertBefore('<div class="bloacc_listitem" id="'+ blockedAccounts[i].id +'"><div class="bloacc_itempic"><img src="'+ blockedAccounts[i].picPath +'"/></div><div class="bloacc_itemcontent"><div class="bloacc_itemcontent_pseudo bold">@'+  blockedAccounts[i].pseudo +'</div><div class="bloacc_itemcontent_fullname">'+ blockedAccounts[i].fullname +'</div><div class=bloacc_itemcontent_since>Depuis le : '+ blockedAccounts[i].since +'</div></div><div class="bloacc_itemcontent"><div class="bloacc_edit_reason"><a href="#" class="bloacc_edit_reason_button">&Eacute;diter</a></div><div class="bloacc_unblock_zone"><a class="bloacc_unblock_btn" href="#">Débloquer</a></div></div></div>');
            
            
        }
    } else if(blockedAccounts[0].populated === false && $('#bloacc_listzone *').length === 1){
        $('#bloacc_empty_lz').show();
    }
    //Après le remplissage de la liste on ré-affiche la div 'Chargement'
    $('#bloacc_listzone').append(loadingDiv);
    autoLoad(ll, hl);
}

function sortedBlockedAccountsFiller(input){
    $('#bloacc_empty_lz').hide();
    $('#bloacc_listzone').empty();
    if(input[0].populated === true && input[0].isBlocked === true){
        for(var i = 0; i < input.length; i++){
            //Note: les éléments variables qui sont append sont issus de la base. Donc l'ID est celui de l'user.
            $('#bloacc_listzone').append('<div class="bloacc_listitem" id="'+ input[i].id +'"><div class="bloacc_itempic"><img src="'+ input[i].picPath +'"/></div><div class="bloacc_itemcontent"><div class="bloacc_itemcontent_pseudo bold">@'+  input[i].pseudo +'</div><div class="bloacc_itemcontent_fullname">'+ input[i].fullname +'</div><div class=bloacc_itemcontent_since>'+Kxlib_getDolphinsValue("p_pfl_bloacc_since")+''+ input[i].since +'</div></div><div class="bloacc_itemcontent"><div class="bloacc_edit_reason"><a href="#" class="bloacc_edit_reason_button">'+Kxlib_getDolphinsValue("p_pfl_bloacc_edit")+'</a></div><div class="bloacc_unblock_zone"><a class="bloacc_unblock_btn" href="#">'+Kxlib_getDolphinsValue("p_pfl_bloacc_unblock")+'</a></div></div></div>');
        }
    } else {
        $('#bloacc_empty_lz').show();
    }
}

//Trigger lors d'un clic sur un des boutons 'débloquer'
$('#bloacc_listzone').delegate('.bloacc_unblock_btn', 'click', function(e){
    //On récupère l'ID de la div mère (donc du compte associé)
    var id = $(this).closest('.bloacc_listitem').prop('id');
    //On l'envoie au serveur pour récupérer le reste des infos dans un objet JS (ad = AccountData)
    var ad = ajaxBloAccInfoFetch(id);
    //On remonte jusqu'au contenant (var c), on le vide, et on le re-remplit avec la confirmation
    var c = $(this).closest($('.bloacc_listitem'));
    c.empty();
    c.append('<div class="bloacc_confirmation">'+Kxlib_getDolphinsValue("p_pfl_bloacc_unblockconf")+'<span class="bold">@'+ ad.pseudo +'</span> ('+ ad.fullname +') ?</div>');
    if(ad.reason !== ''){
        c.append('<div class="bloacc_reason">'+ ad.reason +'</div>');
    }
    c.append('<div class="bloacc_confirmation"><a class="bloacc_unblock_confirm" id="'+ ad.accId +'">'+Kxlib_getDolphinsValue("p_pfl_bloacc_yes")+'</a> <a class="bloacc_unblock_cancel">'+Kxlib_getDolphinsValue("p_pfl_bloacc_cancel")+'</a></div>');
    e.preventDefault();
});

//Triggers des boutons de confirmation
$('#bloacc_listzone').delegate('.bloacc_unblock_cancel', 'click', function(e){
    //On revient à la page des comptes bloqués
    clickBlockedAccs(e);
    e.preventDefault();
});

$('#bloacc_listzone').delegate('.bloacc_unblock_confirm', 'click', function(e){
    //Call AJAX de suppression du blocage
    ajaxUnblock($(this).closest('.bloacc_listitem').prop('id'));
    //Et on revient à la page
    clickBlockedAccs(e);
    e.preventDefault();
});


/* Gestion de l'édition du commentaire d'un blocage de compte */
//Delegate de l'event click();
$('#bloacc_listzone').delegate('.bloacc_edit_reason_button', 'click', function(e){
    e.preventDefault;
    var id = $(this).closest('.bloacc_listitem').prop('id');
    reasonOverlay(id);
});

//Fonction de gestion du clic sur 'Valider'
$('.bloacc_reason_overlay .reason_submit').click(function(e){
    e.preventDefault();
    var cr = htmlEntities($('.reason_textarea').val());
    updateReason(cr, $('.bloacc_reason_overlay').data('id'));
});


//Fonction de création de l'overlay pour le commentaire
function reasonOverlay(id){
    var ad = ajaxBloAccInfoFetch(id);
    if(ad.reason !== ''){
        $('.reason_textarea').val(ad.reason);
    } else {
        
        $('.reason_textarea').val('');
    }
    
    //Remplissage de l'overlay
    $('.reason_itempic').html('<img src="' + ad.picPath + '"/>');
    $('.reason_pseudo').text('@' + ad.pseudo);
    $('.reason_fullname').text('('+ ad.fullname +')');
    $('.reason_since').text('Bloqué depuis le ' + ad.since);
    $('.bloacc_reason_overlay').data('id', id);
    $('.reason_block_title').text(Kxlib_getDolphinsValue("p_pfl_bloacc_reason") + '@'+ ad.pseudo +' :');
    $('.reason_submit').show();
    $('.reason_confirm_quit').hide();
    $('.theater').fadeIn();
    $('.bloacc_reason_overlay').fadeIn('slow', function(){
        clickOverseer();
    });
    
}

//Fonction de surveillance des clics pour la fermeture de l'overlay
function clickOverseer(){
    //Boolean de contrôle de la textarea
    var c = false;
    
    //Surveillance du champ de raison
    $('.reason_textarea').change(function(){
        c = true;
    });
    
    //Gestion des clics
    $(document).click(function(e){
        if($(e.target).is('.reason_submit')){
            $('.theater').fadeOut();
            $('.bloacc_reason_overlay').fadeOut();
            e.stopPropagation();
            return;
        } else if($(e.target).is('.bloacc_reason_overlay') || $(e.target).is('.bloacc_reason_overlay *') || $(e.target).is('.bloacc_edit_reason_button')){
            e.stopPropagation();
            return;
        } else {
            if(c === true){
                $('.reason_submit').hide();
                $('.reason_confirm_quit').show();
                $('#reason_quit_yes').click(function(e){
                    e.preventDefault();
                    $('.theater').fadeOut();
                    $('.bloacc_reason_overlay').fadeOut();
                });
                $('#reason_quit_no').click(function(e){
                    e.preventDefault();
                    $('.reason_submit').show();
                    $('.reason_confirm_quit').hide();
                });
                e.stopPropagation();
                return;
            } else {
                $('.reason_submit').show();
                $('.reason_confirm_quit').hide();
                $('.theater').fadeOut();
                $('.bloacc_reason_overlay').fadeOut();
                e.stopPropagation();
                return;
            }
        }
    });
}



/* * * * * * * * */
//Options de tri
$('.pfl_middle_content #bloacc_sorting_title').click(function(e){
    e.preventDefault();
    $('#bloacc_sorting').stop(true).slideToggle();
    $('#bloacc_sorting_title').toggleClass('bloacc_switch_plus bloacc_switch_minus');
});

//Gestion des tris avec mise en place de ASC/DESC
$('#bloacc_sorting #sort_alph').click(function(e){
    e.preventDefault();
    var r = ajaxBlockedAccSorting('alpha', $(this).prop('class'));
    $(this).toggleClass('sort_asc sort_desc');
    sortedBlockedAccountsFiller(r);
});

$('#bloacc_sorting #sort_date').click(function(e){
    e.preventDefault();
    var r = ajaxBlockedAccSorting('date', $(this).prop('class'));
    $(this).toggleClass('sort_asc sort_desc');
    sortedBlockedAccountsFiller(r);
});



/* GESTION DU CHARGEMENT AU SCROLL */
function autoLoad(min, max){
    $('#bloacc_listdiv').scroll(function(){
        var $this = $(this);
        if($this.scrollTop() + $this.height() === $('#bloacc_listzone').height()){
            $('#bloacc_scroll_load').remove();
            //TODO - POURQUOI CA MARCHE PAS
            console.log(min + ' - ' + max);
            min = min + 20;
            max = max + 20;
            blockedAccountsFiller(min, max);
        }
    });
}

//</editor-fold>

//</editor-fold>