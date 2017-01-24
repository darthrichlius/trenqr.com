/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* Liste des RegEx utilisées */
var regFullname = /^[a-zA-Z- ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,40}$/;
var regNickname = /^[a-zA-Z0-9-_ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/;
var regMail = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;
var regCity = /^[a-zA-Z-.`, ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{0,50}$/;
var regPasswd = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6,20}$/;
//Password: 6 à 20 caractères, avec au moins un chiffre, au moins une lettre 
//(les lettres accentuées fonctionnent aussi), au moins un des spéciaux précisés,
//et pas de :;="' pour éviter les injections.

//Adaptations de la regex du password
var regPasswdIncorrect = /^[^"'=]{1,5}$/;
var regPasswdWeak = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6,7}$/;
var regPasswdMed = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6}(((?=(.*[a-zA-Z]))[a-zA-Z]{2})|(([^;:="'])?(?=(.*[0-9!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%]))[^:;="']))$/;
var regPasswdStrong = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6}(([^;:="'])?(?=(.*[0-9!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%]))[^:;="']{3,14})$/;


//Regex magique pour la date
var regMagicDate = /^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;


/* Variable d'état d'erreur du champ nickname
 * 0 = Caractères illégaux
 * 1 = Pseudo déjà pris
 */
var ins_nickname_errtype;

/* Variable d'état d'erreur du champ city
 * 0 = Caractères illégaux
 * 1 = Ville inconnue
 */
var ins_city_errtype;

/* Variable d'état d'erreur pour l'âge */
var ins_bd_errtype;

/* Variable de transfert de l'info pour la suggestion de pseudo */
var ins_nickname_suggestion = null;


/* Gestion de l'affichage de la div d'erreurs */
function insErrorBox(){
    if($('#ins_error_msg').html() === ''){
        $('#ins_right_bottom').css('display', 'none');
    } else {
        $('#ins_right_bottom').css('display', 'block');
    }
}


/* Fonction d'affichage des erreurs */
function insErrorMsg(msg){
    $('#ins_error_msg').html(msg);
}


/* Fonction de vérifications du nombre total d'erreurs et de l'état
 * 'lock' des différents inputs - à l'exception des select et des radio */
insCityErrtype = new String();
function mainLockCheck(){
    var lockTable = $('.ins_error_lock');                       //On récupère tous les champs du formulaire, sauf les select et les radio
    var errorArray = new Array();                               //Tableau de stockage des champs erronés
    
    $.each(lockTable, function(h, i){                           //Pour chaque champ, on regarde s'il est correct | /!\ IMPORTANT: Les deux arguments passés à la fonction sont nécessaires! /!\
        if($(i).data('su') === "lock"){
            errorArray.push(i);                                 //Si non, on le stocke dans le tableau
        }
    });
    
    var errorCount = errorArray.length;                         //Variable qui contient le nombre d'erreurs
    if(errorCount > 1){                                         //Si on a plus d'une erreur
        var msg = Kxlib_getDolphinsValue("p_ins_err_multifield");
         insErrorMsg(msg);
    } else if (errorCount === 0){                               //Si pas d'erreurs, on vide le champ d'erreurs
        insErrorMsg('');                                        //et on le cache
        insErrorBox();
    } else if(errorCount === 1){                                //Si une erreur, on récupère son id grâce au tableau
        var arg = errorArray[0];
        var argSelector = $(arg).attr('id');

        switch(argSelector){
            case "ins_input_fullname":
                var msg = Kxlib_getDolphinsValue("p_ins_err_fnbadchars");
                insErrorMsg(msg);
                break;
                
            case "ins_input_city":
                switch(insCityErrtype){
                    case "empty":
                        var msg = Kxlib_getDolphinsValue("p_ins_err_cityempty");
                        insErrorMsg(msg);
                        break;
                    case "badchars":
                        var msg = Kxlib_getDolphinsValue("p_ins_err_citybadchars");
                        insErrorMsg(msg);
                        break;
                    case "unknown":
                        var msg = Kxlib_getDolphinsValue("p_ins_err_cityunknown");
                        insErrorMsg(msg);
                        break;
                    case "select":
                        var msg = Kxlib_getDolphinsValue("p_ins_err_cityselect");
                        insErrorMsg(msg);
                        break;
                    default:
                        var msg = Kxlib_getDolphinsValue("p_ins_err_citydefault");
                        insErrorMsg(msg);
                        break;
                }
                break;
                
            case "ins_input_nickname":
                if(ins_nickname_errtype === 0){
                    var msg = Kxlib_getDolphinsValue("p_ins_err_psdbadchars");
                    insErrorMsg(msg);
                } else if(ins_nickname_errtype === 1){
                    if(ins_nickname_suggestion === null){
                        var msg = Kxlib_getDolphinsValue("p_ins_err_psdtaken");
                        insErrorMsg(msg);
                    } else {
                        insErrorMsg(ins_nickname_suggestion);
                    }
                }
                break;
                
            case "ins_input_mail":
                var msg = Kxlib_getDolphinsValue("p_ins_err_emailbadchars");
                insErrorMsg(msg);
                break;
                
            case "ins_input_passwd":
                var msg = Kxlib_getDolphinsValue("p_ins_err_pwbadchars");
                insErrorMsg(msg);
                break;
                
            case "ins_input_mail_confirmation":
                var msg = Kxlib_getDolphinsValue("p_ins_err_emailconf");
                insErrorMsg(msg);
                break;
            
            case "ins-fld-pwd-cnf":
                var msg = Kxlib_getDolphinsValue("p_ins_err_pwconf");
                insErrorMsg(msg);
                break;
            
            case "ins_birthday_wrapper":
                switch(ins_bd_errtype){
                    case 'tooyoung':
                        var msg = Kxlib_getDolphinsValue("p_ins_err_tooyoung");
                        insErrorMsg(msg);
                        break;
                    case 'invaliddate':
                        var msg = Kxlib_getDolphinsValue("p_ins_err_bdbaddate");
                        insErrorMsg(msg);
                        break;
                }
                break;
                
            case "ins_gender_wrapper":
                var msg = Kxlib_getDolphinsValue("p_ins_err_genderselect");
                insErrorMsg(msg);
                break;
                
            case "ins_group_cgu":
                var msg = Kxlib_getDolphinsValue("p_ins_err_cgu");
                insErrorMsg(msg);
                break;
        }
        insErrorBox();
    }
}



/* Fonctions de vérification */
function ins_fullname_check(inputFullname){
    $('#ins_error_msg').html('');
    
    if(!regFullname.test(inputFullname)){
        $('#ins_input_fullname').data('su', 'lock');
        $('#ins_input_fullname').addClass('error_border');
        mainLockCheck();
    } else {
        $('#ins_input_fullname').data('su', 'ulock');
        $('#ins_input_fullname').removeClass('error_border');
        mainLockCheck();
    }
}

function ins_city_check(inputCity){
    //Reset du statut pour empêcher les flashs d'erreur
    $('#ins_input_city').removeClass('error_border');
    $('#ins_input_city').data('su', 'ulock');
    $('#ins_error_msg').html('');
    mainLockCheck();
    
    if(inputCity === ''){
        $('#ins_input_city').data('su', 'lock');
        $('#ins_input_city').addClass('error_border');
        insCityErrtype = 'empty';
        ins_validation_ok = false;
        mainLockCheck();
    } else if($('#ins_input_city').data('cc') === '-1' && $('#ins_input_city').data('temp') !== 'allowed' && $('#ins_input_city').data('check') === 'true'){
        //Correspond au moment entre le clic dans la liste déroulante et le moment du clic sur le tableau.
        //Pas une erreur, mais il faut empêcher la submission de l'inscription dans l'état.
        $('#ins_input_city').data('su', 'lock');
        $('#ins_input_city').addClass('error_border');
        ins_validation_ok = false;
        insCityErrtype = 'select';
        mainLockCheck();
    } else {
        var cityNames = ajaxCityList();
        if(!regCity.test(inputCity) && $('#ins_input_city').data('check') === 'true'){
            $('#ins_input_city').data('su', 'lock');
            $('#ins_input_city').addClass('error_border');
            insCityErrtype = 'badchars';
            mainLockCheck();
            ins_validation_ok = false;
        } else if(!insCityLoop(inputCity, cityNames) && $('#ins_input_city').data('check') === 'true'){
            $('#ins_input_city').data('su', 'lock');
            $('#ins_input_city').addClass('error_border');
            ins_validation_ok = false;
            insCityErrtype = 'unknown';
            mainLockCheck();
    //    } else if($('#ins_input_city').data('cc') === 'multi'){
    //        $('#ins_input_city').addClass('error_border');
    //        //Pas d'erreur à proprement parler, mais le tooltip est affiché
    //        ins_validation_ok = false;
    //        mainLockCheck();

        //Correspond au moment entre le clic dans la liste déroulante et le moment du clic sur le tableau.
        //Pas une erreur, mais il faut empêcher la submission de l'inscription dans l'état.
        } else {
            $('#ins_input_city').data('su', 'ulock');
            $('#ins_input_city').removeClass('error_border');
            mainLockCheck();
        }
    }
    $('#ins_city_spinner').hide();
}

function insCityLoop(inputCity, cityNames){
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

function ins_nickname_check(inputNickname){
    $('#ins_error_msg').html('');
    
    if(!regNickname.test(inputNickname)){
        $('#ins_input_nickname').data('su', 'lock');
        $('#ins_input_nickname').addClass('error_border');
        ins_nickname_errtype = 0;
        mainLockCheck();
    } else {
        $('#ins_input_nickname').data('su', 'ulock');
        $('#ins_input_nickname').removeClass('error_border');
        $('#ins_pseudo_spinner').show();
        ins_nickname_generator(inputNickname);
        mainLockCheck();
    }
}

function ins_mail_check(inputMail){
    $('#ins_error_msg').html('');
    
    if(!regMail.test(inputMail)){
        $('#ins_input_mail').data('su', 'lock');
        $('#ins_input_mail').addClass('error_border');
        mainLockCheck();
    } else {
        $('#ins_input_mail').data('su', 'ulock');
        $('#ins_input_mail').removeClass('error_border');
        mainLockCheck();
    }
}

function ins_passwd_check(inputPasswd){
    $('#ins_error_msg').html('');
    ins_tooltip_hide();
    
    if(regPasswdIncorrect.test(inputPasswd)){
        $('#ins_input_passwd').data('su', 'lock');
        $('#ins_input_passwd').addClass('error_border');
        var msg = Kxlib_getDolphinsValue("p_ins_err_pwpolicy");
        ins_tooltip_show(msg);
        mainLockCheck();
    } else if(regPasswdWeak.test(inputPasswd)){
        $('#ins_input_passwd').data('su', 'ulock');
        $('#ins_input_passwd').removeClass('error_border');
        var msg = Kxlib_getDolphinsValue("p_ins_hint_pwweak");
        ins_tooltip_show(msg);
        mainLockCheck();
    } else if(regPasswdMed.test(inputPasswd)){
        $('#ins_input_passwd').data('su', 'ulock');
        $('#ins_input_passwd').removeClass('error_border');
        mainLockCheck();
    } else if(regPasswdStrong.test(inputPasswd)){
        $('#ins_input_passwd').data('su', 'ulock');
        $('#ins_input_passwd').removeClass('error_border');
        mainLockCheck();
    } else {
        $('#ins_input_passwd').data('su', 'lock');
        $('#ins_input_passwd').addClass('error_border');
        var msg = Kxlib_getDolphinsValue("p_ins_err_pwpolicy");
        ins_tooltip_show(msg);
        mainLockCheck();
    }
}

/* Display du tooltip de password */
function ins_tooltip_show(msg){
    $('#ins_passwd_tooltip').html(msg);
    $('#ins_tooltip_wrapper').fadeIn();
}

function ins_tooltip_hide(){
    $('#ins_tooltip_wrapper').fadeOut();
}




/* Vérifications au moment des blur() des champs principaux */
$('#ins_input_fullname').blur(function(){
    var inputFullname = $(this).val();
    if(inputFullname !== ''){
        ins_fullname_check(inputFullname);
    } else {
        $('#ins_input_fullname').data('su', 'ulock');
        $('#ins_input_fullname').removeClass('error_border');
        mainLockCheck();
    }
});

/*$('#ins_input_city').blur(function(){
    var inputCity = $(this).val();
    var suggTab = new Array();
    if(inputCity !== ''){
        ins_city_check(inputCity);
        //Vérification que la ville est bien en base
        if(inputCity === '## Ville inconnue ##'){
            ins_city_errtype = 1;
            $('#ins_input_city').data('su', 'lock');
            $('#ins_input_city').addClass('error_border');
            mainLockCheck();
        } else {
            var knownCity = false;
            $.each(suggTab, function(k, v){
                if(inputCity === v){
                    knownCity = true;
                    $('#ins_input_city').data('su', 'ulock');
                    $('#ins_input_city').removeClass('error_border');
                    ins_citysugg_tooltip_hide();
                    mainLockCheck();
                }
                if(knownCity === false){
                    ins_city_errtype = 1;
                    $('#ins_input_city').data('su', 'lock');
                    $('#ins_input_city').addClass('error_border');
                    var errMsg = 'La ville que vous avez renseigné n\'a pas été trouvée. Vous devriez essayer avec une autre ville proche de chez vous.';
                    ins_citysugg_tooltip_show(errMsg);
                    mainLockCheck();
                }
            });
        }
    } else {
        $('#ins_input_city').data('su', 'ulock');
        $('#ins_input_city').removeClass('error_border');
        ins_citysugg_tooltip_hide();
        mainLockCheck();
    }
});*/
$('#ins_input_city').blur(function(){
    $('#ins_city_spinner').show();
    ins_city_check($(this).val());
});

$('#ins_input_nickname').blur(function(e){
    var inputNickname = $(this).val();
    if(inputNickname !== '' && regNickname.test(inputNickname) === true){
        suggestionManager(inputNickname);
        ajaxNicknameChecker(e);
        //ins_nickname_check(inputNickname);
        //ins_nickname_generator(inputNickname);
    } else if(inputNickname !== '' && regNickname.test(inputNickname) === false) {
        $('#ins_input_nickname').data('su', 'lock');
        $('#ins_input_nickname').addClass('error_border');
        mainLockCheck();
    } else {
        $('#ins_input_nickname').data('su', 'ulock');
        $('#ins_input_nickname').removeClass('error_border');
        mainLockCheck();
    }
});

$('#ins_input_mail').blur(function(e){
    var inputMail = $(this).val();
    if(inputMail !== ''){
        ins_mail_check(inputMail);
        ins_mail_conf_check();
        $('#ins_email_spinner').show();
        ajaxInsMailStateChecker(e);
        var pa = extract_pa_get_data();
        if(pa === '' && regMail.test(pa)){
            var pgs = ajaxInsPreregMailChecker();
            if(pgs !== 'false'){
                askToContinuePrereg(pgs);
            }
        }
    } else {
        $('#ins_input_mail').data('su', 'ulock');
        $('#ins_input_mail').removeClass('error_border');
        mainLockCheck();
    }
});

$('#ins_input_passwd').blur(function(){
    var inputPasswd = $(this).val();
    if(inputPasswd !== ''){
        ins_passwd_check(inputPasswd);
        ins_passwd_conf_check();
    } else {
        $('#ins_input_passwd').data('su', 'ulock');
        $('#ins_input_passwd').removeClass('error_border');
        mainLockCheck();
    }
});

/* Contrôle des champs de vérification */
$('#ins_input_mail_confirmation').blur(function(){
    ins_mail_conf_check();
});

function ins_mail_conf_check(){
    var inputMailConf = $('#ins_input_mail_confirmation').val();
    if(inputMailConf !== ''){
        var inputFirstMail = $('#ins_input_mail').val();
        if(inputMailConf !== inputFirstMail){
            $('#ins_input_mail_confirmation').data('su', 'lock');
            $('#ins_input_mail_confirmation').addClass('error_border');
            mainLockCheck();
        } else {
            $('#ins_input_mail_confirmation').data('su', 'ulock');
            $('#ins_input_mail_confirmation').removeClass('error_border');
            mainLockCheck();
        }
    } else {
        $('#ins_input_mail_confirmation').data('su', 'ulock');
        $('#ins_input_mail_confirmation').removeClass('error_border');
        mainLockCheck();
    }
}

$('#ins-fld-pwd-cnf').blur(function(){
    ins_passwd_conf_check();
});

function ins_passwd_conf_check(){
    var inputPasswdConf = $('#ins-fld-pwd-cnf').val();
    if(inputPasswdConf !== ''){
        var inputFirstPasswd = $('#ins_input_passwd').val();
        if(inputPasswdConf !== inputFirstPasswd){
            $('#ins-fld-pwd-cnf').data('su', 'lock');
            $('#ins-fld-pwd-cnf').addClass('error_border');
            mainLockCheck();
        } else {
            $('#ins-fld-pwd-cnf').data('su', 'ulock');
            $('#ins-fld-pwd-cnf').removeClass('error_border');
            mainLockCheck();
        }
    } else {
        $('#ins-fld-pwd-cnf').data('su', 'ulock');
        $('#ins-fld-pwd-cnf').removeClass('error_border');
        mainLockCheck();
    }
}


/************************************************************/
/* Contrôle final avant envoi -- Inclus le genre et la date */
/************************************************************/

var ins_validation_ok = true;                       //Boolean de contrôle

/* Revalidation du nom -- Non vide */
function ins_fullname_validation(input){
    if(!regFullname.test(input)){
        ins_validation_ok = false;
        $('#ins_input_fullname').addClass('error_border');
        $('#ins_input_fullname').data('su', 'lock');
        mainLockCheck();
    }
}

/* Revalidation du pseudo -- Non vide */
function ins_nickname_validation(input){
    if(!regNickname.test(input)){
        ins_validation_ok = false;
        $('#ins_input_nickname').addClass('error_border');
        $('#ins_input_nickname').data('su', 'lock');
        mainLockCheck();
    } else {
        $('#ins_input_nickname').trigger('blur');
    }
}

/* Revalidation du mail -- Non vide */
function ins_mail_validation(input){
    if(!regMail.test(input)){
        ins_validation_ok = false;
        $('#ins_input_mail').addClass('error_border');
        $('#ins_input_mail').data('su', 'lock');
        mainLockCheck();
    }
}

/* Revalidation de la confirmation de mail -- Non vide */
function ins_mail_confirmation_validation(input){
    var mail = $('#ins_input_mail').val();
    var mailConf = $('#ins_input_mail_confirmation').val();
    
    if(mail !== mailConf){
        ins_validation_ok = false;
        $('#ins_input_mail_confirmation').addClass('error_border');
        $('#ins_input_mail_confirmation').data('su', 'lock');
        mainLockCheck();
    } else{
        $('#ins_input_mail_confirmation').removeClass('error_border');
        $('#ins_input_mail_confirmation').data('su', 'ulock');
        mainLockCheck();
    }
}

/* Revalidation du passwd -- Non vide */
function ins_passwd_validation(input){
    if(!regPasswd.test(input)){
        ins_validation_ok = false;
        $('#ins_input_passwd').addClass('error_border');
        $('#ins_input_passwd').data('su', 'lock');
        mainLockCheck();
    }
}

/* Revalidation de la confirmation de passwd -- Non vide */
function ins_passwd_confirmation_validation(input){
    var passwd = $('#ins_input_passwd').val();
    var passwdConf = $('#ins-fld-pwd-cnf').val();
    
    if(passwd !== passwdConf){
        ins_validation_ok = false;
        $('#ins-fld-pwd-cnf').addClass('error_border');
        $('#ins-fld-pwd-cnf').data('su', 'lock');
        mainLockCheck();
    } else {
        $('#ins-fld-pwd-cnf').removeClass('error_border');
        $('#ins-fld-pwd-cnf').data('su', 'ulock');
        mainLockCheck();
    }
}

/* Validation du genre */
function ins_gender_validation(){
    if($('#ins_radio_f').prop('checked') === true){
        $('#ins_gender_wrapper').removeClass('gender_error_border');
        $('#ins_gender_wrapper').data('su', 'ulock');
        mainLockCheck();
    } else if($('#ins_radio_m').prop('checked') === true){
        $('#ins_gender_wrapper').removeClass('gender_error_border');
        $('#ins_gender_wrapper').data('su', 'ulock');
        mainLockCheck();
    } else {
        $('#ins_gender_wrapper').addClass('gender_error_border');
        $('#ins_gender_wrapper').data('su', 'lock');
        ins_validation_ok = false;
        mainLockCheck();
    }
}



/* Fonction qui permet de retourner l'âge à partir de la date entrée */
function getAge(dateYMD) {
    var today = new Date();
    var birthDate = new Date(dateYMD);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}


/* Validation de la date*/
function ins_birthday_validation(){
    var dob_day = $('#day').val();
    var dob_month = $('#month').val();
    var dob_year = $('#year').val();
    
    //On regarde si la date entrée est suffisemment ancienne (Utilisateur de 13 ans minimum)(format YYYY-MM-DD)
    var userAge = getAge(dob_year + "-" + dob_month + "-" + dob_day);
    
    //On récupère la date au format américain MM-DD-YYYY avant de l'envoyer à la regex
    var formatedDate = dob_month + "-" + dob_day + "-" + dob_year;
    
    if(dob_day === "init" | dob_month === "init" | dob_year === "init"){
        ins_validation_ok = false;
        $('#ins_birthday_wrapper').data('su', 'lock');
        $('#day').addClass('error_border');
        $('#month').addClass('error_border');
        $('#year').addClass('error_border');
        ins_bd_errtype = 'invaliddate';
        mainLockCheck();
    } else if(regMagicDate.test(formatedDate)){
        if(parseInt(userAge) < 13){
            ins_validation_ok = false;
            $('#ins_birthday_wrapper').data('su', 'lock');
            $('#day').addClass('error_border');
            $('#month').addClass('error_border');
            $('#year').addClass('error_border');
            ins_bd_errtype = 'tooyoung';
            mainLockCheck();
        } else {
            $('#ins_birthday_wrapper').data('su', 'ulock');
            $('#day').removeClass('error_border');
            $('#month').removeClass('error_border');
            $('#year').removeClass('error_border');
            mainLockCheck();
        }
    }  else {
        ins_validation_ok = false;
        $('#ins_birthday_wrapper').data('su', 'lock');
        $('#day').addClass('error_border');
        $('#month').addClass('error_border');
        $('#year').addClass('error_border');
        ins_bd_errtype = 'invaliddate';
        mainLockCheck();        
    }
}

/* Gestion des dates impossibles */
function ins_impossible_date(){
    //Reset des dates 'fausses'
    $('#day option').attr('disabled', false);
    switch($('#month').val()){
        case "02":
            var ins_leapYear = new Date($('#year').val(),2,0).getDate();
            if(ins_leapYear === 28){
                if($('#day').val() === "31" | $('#day').val() === "30" | $('#day').val() === "29"){
                    $('#day').val('28');
                }
                $('#day option[value="29"]').attr('disabled', true);
                $('#day option[value="30"]').attr('disabled', true);
                $('#day option[value="31"]').attr('disabled', true);
            } else if(ins_leapYear === 29){
                if($('#day').val() === "31" | $('#day').val() === "30"){
                    $('#day').val('29');
                }
                $('#day option[value="30"]').attr('disabled', true);
                $('#day option[value="31"]').attr('disabled', true);
            }
            break;
        case "04":
            if($('#day').val() === "31"){
                $('#day').val('30');
            }
            $('#day option[value="31"]').attr('disabled', true);
            break;
        case "06":
            if($('#day').val() === "31"){
                $('#day').val('30');
            }
            $('#day option[value="31"]').attr('disabled', true);
            break;
        case "09":
            if($('#day').val() === "31"){
                $('#day').val('30');
            }
            $('#day option[value="31"]').attr('disabled', true);
            break;
        case "11":
            if($('#day').val() === "31"){
                $('#day').val('30');
            }
            $('#day option[value="31"]').attr('disabled', true);
            break;
    }
}

$('#month').change(function(){
    ins_impossible_date();
});

$('#year').change(function(){
    ins_impossible_date();
});



/* Appel de la validation de date au clic hors du wrapper */
$(document).click(function(e){
    if($(e.target).is('#ins_birthday_wrapper, #ins_birthday_wrapper *')){
        return;
    } else if($('#year').val() === "init" && $('#month').val() === "init" && $('#day').val() === 'init'){
        return;
    } else if($(e.target).is('#ins_form_submit')){
        return;
    } else {
        ins_birthday_validation();
    }
});


/* Vérification que la checkbox des CGU est cochée */
function ins_cgu_validation(){
    if($('#ins_cgu').prop('checked') === false){
        $('#ins_group_cgu').data('su', 'lock');
        $('#ins_group_cgu').addClass('gender_error_border');
        mainLockCheck();
        ins_validation_ok = false;
    } else {
        $('#ins_group_cgu').data('su', 'ulock');
        $('#ins_group_cgu').removeClass('gender_error_border');
        mainLockCheck();
    }
}


/**************************************/
/* Fonction "finale" qui appelle tout */
/**************************************/
$('#ins_form_submit').click(function(e){
    e.preventDefault();
    //Récupération des valeurs des champs
    var insFinalFullname = $('#ins_input_fullname').val();
    var insFinalCity = $('#ins_input_city').val();
    var insFinalNickname = $('#ins_input_nickname').val();
    var insFinalMail = $('#ins_input_mail').val();
    var insFinalMailConf = $('#ins_input_mail_confirmation').val();
    var insFinalPasswd = $('#ins_input_passwd').val();
    var insFinalPasswdConf = $('#ins-fld-pwd-cnf').val();
    
    //Reset du boolean
    ins_validation_ok = true;
    
    //Check des informations des champs
    ins_fullname_validation(insFinalFullname);
    ins_city_check(insFinalCity);
    ins_nickname_validation(insFinalNickname);
    ins_mail_validation(insFinalMail);
    ins_mail_confirmation_validation(insFinalMailConf);
    ins_passwd_validation(insFinalPasswd);
    ins_passwd_confirmation_validation(insFinalPasswdConf);
    
    var mailAvailable = ajaxInsMailStateChecker(e);
    var pseudoTaken = ajaxNicknameTakenShort(e);
    
    //Appel des autres vérifications
    ins_gender_validation();
    ins_birthday_validation();
    ins_cgu_validation();
    //Call Ajax
    if(ins_validation_ok === true){
        /*var insAjaxLock = ajaxFormChecker(e);*/
    }
    
    //Si tout ce qui est au-dessus est bon, on peut envoyer le formulaire.
    //Sinon, on intercepte le clic et on demande à l'utilisateur de reprendre ses informations.
    if(ins_validation_ok === true && mailAvailable !== false && pseudoTaken !== true/*&& insAjaxLock === true*/){
        insErrorMsg('');
        insErrorBox();
        //betaOverlayToggle();
        fuseTimer();
        ajaxCreateAccount();
        //DESACTIVE POUR TEST
        $('#ins_form').submit();
//        setTimeout(function(){
//            //5 secondes de timeout pour être sûr que l'user ait bien le temps de lire
//            betaOverlayToggle();
//            
//            $('#ins_form').submit();
//        }, 5200);
        
    } else {
        //alert("Formulaire non valide");
        var msg = Kxlib_getDolphinsValue("p_ins_err_forminvalid");
        insErrorMsg(msg);
        insErrorBox();        
        e.preventDefault();

    }
    
});

/* Fonction d'affichage de l'overlay bêta-test */
//function betaOverlayToggle(){
//    ins_darkenBackground();
//    $('#ins_overlay_beta').fadeToggle();
//}

/* Fonction de vérification de la force du passwd */
function passwdStrCheck(input){
    if(regPasswdIncorrect.test(input.val())){
        $('.passwd_str_fill').animate({
            'width': '5%',
            'background-color': '#f00'
        });
    } else if(regPasswdWeak.test(input.val())){
        $('.passwd_str_fill').animate({
            'width': '33%',
            'background-color': '#e72a2d'
        });
    } else if(regPasswdMed.test(input.val())){
    ins_tooltip_hide();
        $('.passwd_str_fill').animate({
            'width': '66%',
            'background-color': '#c9f011'
        });
    } else if(regPasswdStrong.test(input.val())){
    ins_tooltip_hide();
        $('.passwd_str_fill').animate({
            'width': '100%',
            'background-color': '#28f011'
        });
    } else {
        if(input.val() === ''){
            ins_tooltip_hide();
            $('.passwd_str_fill').animate({
                'width': '0%'
            });
        } else {
            $('.passwd_str_fill').animate({
                'width': '5%',
                'background-color': '#f00'
            });
        }
    }
}


/* Appel de la fonction quand on tape dans l'input */
$('#ins_input_passwd').keypress(function(){
    passwdStrCheck($(this));
});

/************************/
/* Génération de pseudo */
/************************/

/* Variable de test du pseudo
 * 0 = Nom disponible
 * 1 = Nom de base pris
 * 2 = Nom de base + AA pris
 * 3 = Nom de base + AAAA pris */
ins_nickname_taken = 0;

/* Variables de stockage des pseudos
 * pour les vérifications ultérieures
 * faites en AJAX */
var ins_suggestion;
var ins_suggestion_yy;
var ins_suggestion_yyyy;

function suggestionManager(nickname){
    ins_suggestion = nickname;
    ins_suggestion_yy = nickname + $('#year').val().slice(-2);
    ins_suggestion_yyyy = nickname + $('#year').val();
}


function ins_nickname_generator(nickname, ins_nickname_taken){
    suggestionManager(nickname);
    if($('#year').val() !== 'init'){
        switch(ins_nickname_taken){
            case 0:
                $('#ins_input_nickname').data('su', 'ulock');
                $('#ins_input_nickname').removeClass('error_border');
                break;

            case 1:
                ins_nickname_suggestion = Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakenbase"), "%pseudo%", ins_suggestion)+" "+Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakensug"), "%sugg%", ins_suggestion_yy);
                ins_nickname_errtype = 1;
                $('#ins_input_nickname').data('su', 'lock');
                $('#ins_input_nickname').addClass('error_border');
                mainLockCheck();
                break;

            case 2:
                ins_nickname_suggestion = Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakenbase"), "%pseudo%", ins_suggestion)+" "+Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakensug"), "%sugg%", ins_suggestion_yyyy);
                ins_nickname_errtype = 1;
                $('#ins_input_nickname').data('su', 'lock');
                $('#ins_input_nickname').addClass('error_border');
                mainLockCheck();
                break;

            case 3:
                ins_nickname_suggestion = Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakenbase"), "%pseudo%", ins_suggestion)+" "+Kxlib_getDolphinsValue("p_ins_err_psdtakennosug");
                ins_nickname_errtype = 1;
                $('#ins_input_nickname').data('su', 'lock');
                $('#ins_input_nickname').addClass('error_border');
                mainLockCheck();
                break;
        }
    } else {
        //alert(ins_nickname_taken);
        /* Mouais, c'est encore bancal comme solution ça. 
         * 'faut que je trouve pourquoi ma fonction passe dans le else avant tout.
         * Enfin, du coup, que je truove pourquoi je passe DEUX fois dans la fonction,
         * et que la première fois mon alert me donne un truc aussi bizarre.
         */
        if (ins_nickname_taken === 0){
                $('#ins_input_nickname').data('su', 'ulock');
                $('#ins_input_nickname').removeClass('error_border');
                mainLockCheck();
        } else {
                ins_nickname_errtype = 1;
                $('#ins_input_nickname').data('su', 'lock');
                $('#ins_input_nickname').addClass('error_border');
                mainLockCheck();
        }
        
    }
    $('#ins_pseudo_spinner').hide();
}

/****************************************/
/* GESTION DES CONSEILS DANS LA SIDEBAR */
/****************************************/

//Initialisation de toutes les variables
var hintWelcome = Kxlib_getDolphinsValue("p_ins_msg_welcome");

var hintFullname = Kxlib_getDolphinsValue("p_ins_msg_fullname");
var hintBirthday = Kxlib_getDolphinsValue("p_ins_msg_birthday");
var hintGender = Kxlib_getDolphinsValue("p_ins_msg_gender");
var hintCity = Kxlib_getDolphinsValue("p_ins_msg_city");
var hintNickname = Kxlib_getDolphinsValue("p_ins_msg_pseudo");
var hintMail = Kxlib_getDolphinsValue("p_ins_msg_email");
var hintMailConf = Kxlib_getDolphinsValue("p_ins_msg_emailconf");
var hintPasswd = Kxlib_getDolphinsValue("p_ins_msg_pw");
var hintPasswdConf = Kxlib_getDolphinsValue("p_ins_msg_pwconf");
var hintMainComputer = Kxlib_getDolphinsValue("p_ins_msg_maincomp");

//Fonction d'affichage dans la <div> avec des fadeIn/Out pour faire joli
var lastHintDisplayed = null;
function ins_hintDisplay(txt){
    if(lastHintDisplayed !== txt){
        $('#ins_info_msg').fadeOut(500, function(){
            $('#ins_info_msg').html(txt);
            $('#ins_info_msg').fadeIn(500);
        });
        lastHintDisplayed = txt;
    } else {
        stop();
    }
}


function backToBaseMsg(div){
    if(div.val() === ''){
        ins_hintDisplay(hintWelcome);
    }
}

//focusout() pour ré-afficher le message de base
//$('#ins_form').focusout(function(){ins_hintDisplay(hintWelcome)});
$('#ins_input_fullname').focusout(function(){backToBaseMsg($(this));});
//$('#ins_birthday_wrapper, #ins_birthday_wrapper *').focusout(function(){ins_hintDisplay(hintWelcome);});
//$('#ins_gender_wrapper, ins_gender_wrapper *').focusout(function(){ins_hintDisplay(hintWelcome);});
$('#ins_input_city').focusout(function(){backToBaseMsg($(this));});
$('#ins_input_nickname').focusout(function(){backToBaseMsg($(this));});
$('#ins_input_mail').focusout(function(){ins_hintDisplay(hintWelcome);});
$('#ins_input_mail_confirmation').focusout(function(){backToBaseMsg($(this));});
$('#ins_input_passwd').focusout(function(){backToBaseMsg($(this));});
$('#ins-fld-pwd-cnf').focusout(function(){backToBaseMsg($(this));});


//Gestion des focus
$('#ins_input_fullname').focusin(function(){ins_hintDisplay(hintFullname);});
$('#ins_birthday_wrapper, #ins_birthday_wrapper *').focusin(function(){ins_hintDisplay(hintBirthday);});
$('#ins_gender_wrapper, #ins_gender_wrapper *').focusin(function(){ins_hintDisplay(hintGender);});
$('#ins_input_city').focusin(function(){ins_hintDisplay(hintCity);});
$('#ins_input_nickname').focusin(function(){ins_hintDisplay(hintNickname);});
$('#ins_input_mail').focusin(function(){ins_hintDisplay(hintMail);});
$('#ins_input_mail_confirmation').focusin(function(){ins_hintDisplay(hintMailConf);});
$('#ins_input_passwd').focusin(function(){ins_hintDisplay(hintPasswd);});
$('#ins-fld-pwd-cnf').focusin(function(){ins_hintDisplay(hintPasswdConf);});



/* Gestion du tooltip sur le :hover du lien de report */

function ins_delay_tooltip_show(msg){
        $('#ins_delay_tooltip').html(msg);
        $('#ins_delay_tooltip_wrapper').stop(true, true).fadeIn();
}

function ins_delay_tooltip_hide(){
        $('#ins_delay_tooltip_wrapper').stop(true, true).fadeOut();
}

$('#ins_right_middle_link a').hover(function(){
    var msg = Kxlib_getDolphinsValue("p_ins_hint_delay");
    ins_delay_tooltip_show(msg);
}, function(){
    ins_delay_tooltip_hide();
});

/* Désactivation des possibilités de c/c pour les champs de confirmation */
$('#ins_input_mail').bind("contextmenu cut copy paste",function(e){
    e.preventDefault();
});

$('#ins_input_passwd').bind("contextmenu cut copy paste",function(e){
    e.preventDefault();
});

/* Gestion du tooltip sur le :hover de la checkbox 'Ordinateur principal' */

function ins_mc_tooltip_show(msg){
        $('#ins_mc_tooltip').html(msg);
        $('#ins_mc_tooltip_wrapper').stop(true, true).fadeIn();
}

function ins_mc_tooltip_hide(){
        $('#ins_mc_tooltip_wrapper').stop(true, true).fadeOut();
}

/* Gestion du tooltip pour la suggestion de ville sur le champ 'City' */
/*var anim_lock_citysugg = false;
function ins_citysugg_tooltip_show(msg){
    if(anim_lock_citysugg === false){
        anim_lock_citysugg = true;
        $('#ins_citysugg_tooltip').html(msg);
        $('#ins_citysugg_tooltip_wrapper').fadeIn(function(){
            anim_lock_citysugg = false;
        });
    }
}

function ins_citysugg_tooltip_hide(){
    if(anim_lock_citysugg === false){
        anim_lock_citysugg = true;
        $('#ins_citysugg_tooltip_wrapper').fadeOut(function(){
            anim_lock_citysugg = false;
        });
    }
}*/
                                                                                    
//Gestion de la bordure des champs de date
$('#year, #month, #day').focus(function(){
    $('#year').css('border-color', '#bbb');
    $('#month').css('border-color', '#bbb');
    $('#day').css('border-color', '#bbb');
});

$('#year, #month, #day').blur(function(){
    $('#year').css('border-color', '#ddd');
    $('#month').css('border-color', '#ddd');
    $('#day').css('border-color', '#ddd');
});

$('#mc_megabox, #ins_mc_tooltip_wrapper').hover(function(){
    var msg = "Lorem ipsum dolor sit amet, <a href=\"#\">consectetur adipiscing</a> elit. Phasellus quis feugiat magna, id venenatis dui.";
    ins_mc_tooltip_show(msg);
}, function(){
    ins_mc_tooltip_hide();
});

/* Gestion du des events sur ready() */
$().ready(function() {
        //Sidebar
        var scrollingDiv = $("#ins_right");
        $(window).scroll(function(){			
                scrollingDiv
                        .stop()
                        .animate({"marginTop": ($(window).scrollTop())}, "slow" );
        });
});

/* Gestion du report de l'inscription */
$('#ins_report').click(function(e){
    e.preventDefault();
    var fn = $('#ins_input_fullname').val();
    var pd = $('#ins_input_nickname').val();
    var em = $('#ins_input_mail').val();
    var p = $('#ins_input_passwd').val();
    
    var ins_validation_ok = true;
    
    ins_fullname_validation(fn);
    ins_passwd_validation(p);
    ins_nickname_validation(pd);
    ins_mail_validation(em);
    
    if(fn !== '' && p !== '' && em !== '' && p !== '' && ins_validation_ok === true){
        ajaxReportInscription();
        //Apparition d'un overlay de confirmation
        insReportInscriptionOverlay();
        //Puis redirection
//        setTimeout(function(){
//            window.location.href = 'http://www.trenqr.com';
//        }, 5000);
    } else {
        //On 'bloque' le tooltip pour que l'utilisateur puisse voir ce qu'il a mal fait
        $('#ins_delay_tooltip_wrapper').fadeIn();
    }
});

function extract_pa_get_data(){
    var search = window.location.search;
    var pa = search.substr(4);
    return pa;
}

function askToContinuePrereg(k){
    insResumeOverlay();
    $('#ins_resume_link').click(function(){
        //Redirection
        var url = window.location.href;
        window.location.href = url + '?pa=' + k;
    });
}