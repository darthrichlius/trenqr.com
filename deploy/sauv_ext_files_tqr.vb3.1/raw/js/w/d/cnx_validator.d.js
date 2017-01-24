/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* Regex utilisées */
var regSafe = /^[^:;="']{1,}$/;
var regMail = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;
var regNickname = /^[a-zA-Z0-9-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/;

/* Sidebar positionning */
function sidebarPos(){
    var contentOffset = $('#cnx_form_wrapper').offset().top;
    var sidebarOffset = contentOffset - 113; //Mesure empirique -- 'Bon' alignement
    
    $('#cnx_sidebar').css('margin-top', sidebarOffset + 'px');
}


/* Affichage des messages d'erreur */
function cnxErrorBox(){
    if($('#cnx_form_error').html() === ''){
        //$('#cnx_form_error').css('display', 'none');
        //$('#cnx_sidebar').css('margin-top', '195px');
    } else {
        //$('#cnx_form_error').css('display', 'block');
        //$('#cnx_sidebar').css('margin-top', '230px'); 
    }
}


$(document).ready(function(){
    sidebarPos();
    $('#cnx_form_login_input').focus();
    /*if($('.cnx_msg_normal p').html() === ''){
        $('.cnx_msg_normal').hide();
    }*/
});

function cnxErrorMsg(msg){
    //$('#cnx_form_error').html(msg);
    $('#cnx_msg > p').html(msg);
    $('#cnx_msg').css('border-color', '#ddd');
    $('#cnx_msg').css('background-color', '#eee');
}

/* Gestionnaire d'erreurs */
/* Les détails de la fonction sont sur le fichier ins_form_validator.d.js */
function cnxLockCheck(){
    var lockTable = $('.cnx_input');
    var errorArray = new Array();
    
    $.each(lockTable, function(h, i){
        if($(i).data('su') === "lock"){
            errorArray.push(i);
        }
    });
    
    var errorCount = errorArray.length;
    if(errorCount > 1){
        var msg = Kxlib_getDolphinsValue("p_err_cnx_multifield");
        cnxErrorMsg(msg);
        cnxErrorBox();
    } else if(errorCount === 0){
        msg = Kxlib_getDolphinsValue("p_cnx_default");
        cnxErrorMsg(msg);
        cnxErrorBox();
    } else if(errorCount === 1){
        var arg = errorArray[0];
        var argSelector = $(arg).attr('id');
        
        switch(argSelector){
            case "cnx_form_login_input":
                var msg = Kxlib_getDolphinsValue("p_err_cnx_badlogin");
                cnxErrorMsg(msg);
                break;
            
            case "cnx_form_passwd_input":
                var msg = Kxlib_getDolphinsValue("p_err_cnx_pwbadchars");
                cnxErrorMsg(msg);
                break;
        }
        cnxErrorBox();
    }
}



/* Fonction de vérification du login */
function cnx_login_check(input){
    if(!regMail.test(input)){
        if(!regNickname.test(input)){
            $('#cnx_form_login_input').addClass('error_border');
            $('#cnx_form_login_input').data('su', 'lock');
            cnxLockCheck();
        } else {
            $('#cnx_form_login_input').removeClass('error_border');
            $('#cnx_form_login_input').data('su', 'ulock');
            cnxLockCheck();           
        }
    } else {
        $('#cnx_form_login_input').removeClass('error_border');
        $('#cnx_form_login_input').data('su', 'ulock');
        cnxLockCheck();
    }
}

/* Fonction de vérification des caractères du password */
function cnx_passwd_check(input){
    if(!regSafe.test(input)){
        $('#cnx_form_passwd_input').addClass('error_border');
        $('#cnx_form_passwd_input').data('su', 'lock');
        //$('#cnx_sidebar').css('margin-top', '230px');
        cnxLockCheck();
    } else {
        $('#cnx_form_passwd_input').removeClass('error_border');
        $('#cnx_form_passwd_input').data('su', 'ulock');
        //$('#cnx_sidebar').css('margin-top', '195px');   
        cnxLockCheck();
    }
}

/* Vérification que tous les champs de date sont remplis */
function cnx_birthday_check(){
    if($('#cnx_day').val() === 'init' && $('#cnx_month').val() === 'init' && $('#cnx_year').val() === 'init'){
        //On ne comptabilise pas la date
        $('#cnx_day').removeClass('error_border');
        $('#cnx_month').removeClass('error_border');
        $('#cnx_year').removeClass('error_border');
        $('#cnx_dob_wrapper').data('su', 'ulock');
        cnxLockCheck();
    } else if($('#cnx_day').val() !== 'init' && $('#cnx_month').val() !== 'init' && $('#cnx_year').val() !== 'init'){
        //Pareil
        $('#cnx_day').removeClass('error_border');
        $('#cnx_month').removeClass('error_border');
        $('#cnx_year').removeClass('error_border');
        $('#cnx_dob_wrapper').data('su', 'ulock');
        cnxLockCheck();
    } else {
        //Un ou plusieurs champs en 'init', donc erreur
        $('#cnx_day').addClass('error_border');
        $('#cnx_month').addClass('error_border');
        $('#cnx_year').addClass('error_border');
        $('#cnx_dob_wrapper').data('su', 'lock');
        cnxLockCheck();
    } 
}


/* Triggers */
$('#cnx_form_login_input').blur(function(){
    $('#cnx_form_passwd_input').removeClass('error_border');
    $('#cnx_form_login_input').removeClass('error_border');
    if($('#cnx_form_login_input').val() === ""){
        $('#cnx_form_login_input').data('su', 'ulock');
        $('#cnx_form_passwd_input').data('su', 'ulock');
        cnxLockCheck();
    } else {
        cnx_login_check($('#cnx_form_login_input').val());        
    }
    sidebarPos();
    sid();
});

$('#cnx_form_passwd_input').blur(function(){
    $('#cnx_form_passwd_input').removeClass('error_border');
    $('#cnx_form_login_input').removeClass('error_border');
    if($('#cnx_form_passwd_input').val() === ""){
        $('#cnx_form_login_input').data('su', 'ulock');
        $('#cnx_form_passwd_input').data('su', 'ulock');
        cnxLockCheck();
    } else {
        cnx_passwd_check($('#cnx_form_passwd_input').val());        
    }
    sidebarPos();
    sid();
});

/* Boolean de contrôle avant envoi */
var cnx_validation_ok = true;

/* Recontrôle des deux champs et envoi si OK */
function cnx_login_validation(input){
    if(!regMail.test(input) || input === ''){
        if(!regNickname.test(input) || input === ''){
            cnx_validation_ok = false;
            $('#cnx_form_login_input').addClass('error_border');
        }
    }
}

function cnx_passwd_validation(input){
    if(!regSafe.test(input) || input === ''){
        cnx_validation_ok = false;
        $('#cnx_form_passwd_input').addClass('error_border');
    }
}

function cnx_birthday_validation(){
    if($('#cnx_day').val() === 'init' && $('#cnx_month').val() === 'init' && $('#cnx_year').val() === 'init'){
        //
    } else if($('#cnx_day').val() !== 'init' && $('#cnx_month').val() !== 'init' && $('#cnx_year').val() !== 'init'){
        //
    } else {
        cnx_validation_ok = false;
    }
}

$('#cnx_submit').click(function(e){
    e.preventDefault();
    sidebarPos();
    var cnxFinalLogin = $('#cnx_form_login_input').val();
    var cnxFinalPasswd = $('#cnx_form_passwd_input').val();
    
//    if(sc === true){
//        return;
//    }
    
    cnx_validation_ok = true;
    
    
    cnx_login_validation(cnxFinalLogin);
    cnx_passwd_validation(cnxFinalPasswd);
    cnx_birthday_validation();
        
    sidebarPos();
    var connectpage_ajax_locker = ajaxConnectPage();
    
    if(cnx_validation_ok === true && connectpage_ajax_locker[0] === true){
        cnxErrorMsg(Kxlib_getDolphinsValue("p_cnx_default"));
        cnxErrorBox();
        $('#cnx_locktype').val(connectpage_ajax_locker[1]);
        var date = new Date;
        var s = date.getSeconds();
        var m = date.getMinutes();
        var h = date.getHours();
        var tod = h + ':' + m + ':' + s;
        $('#cnx_tod').val(tod);
        //REACTIVER
        $('#cnx_form').submit();
        //alert('Connexion en cours');
    } else if(cnx_validation_ok === true && connectpage_ajax_locker[0] === 'deletedacc'){
        //Cas du compte en instance de suppression. On attend l'action de l'user
        sidebarPos();
        e.preventDefault();
    } else {
        //alert('Informations de connexion erronées');
        //var msg = Kxlib_getDolphinsValue("p_err_cnx_checkyourinfo");
        //cnxErrorMsg(msg);
        //cnxErrorBox();
        sidebarPos();
        e.preventDefault();
    }
});

/* * * * * * * * * */
/* Gestion de l'affichage des éléments additionnels de la page */

function sid(){
    //var rSid = false;
    var exs = false;
    var cred = false;
    //Reset des items qui ont été append:
    //(C'est pas vraiment joli sur le frontend, mais pour le moment c'est le seul moyen que j'ai trouvé pour garantir qu'il n'y ait pas le bordel dans les append.
    $('.cnx_hidden_grp').remove();
    $('#cnx_login_spinner').show();
    if($('#cnx_form_login_input').val() !== '' && $('#cnx_form_passwd_input').val() !== ''){
        exs = ajaxCnxAccountExists($('#cnx_form_login_input').val());
        cred = ajaxCnxGCChecker();
    } else {
//    //Reset des items qui ont été append:
//    $('.cnx_hidden_grp').remove();
    }
    if(exs === true && cred === true && $('#cnx_form_login_input').val() !== '' && $('#cnx_form_passwd_input').val() !== ''){    
        var sg = ajaxCnxSupergroupChecker();
        var tc = ajaxCnxIsThirdCrit();
        //Reset des éléments
//        $('#cnx_superlogin').slideUp();
//        $('#cnx_birthday_group').slideUp();
//        $('#cnx_supergroup').slideUp();
        switch(sg){
            case -1:
                //Erreur côté serveur
                $('#cnx_msg > p').html(Kxlib_getDolphinsValue("p_cnx_srv_err"));
                $('#cnx_msg').css('border-color', 'red');
                $('#cnx_msg').css('background-color', 'pink');
            case 1:
                //Cas "OK"
                if($('#cnx_birthday_group').css('display') === 'none'){$('#cnx_birthday_group').slideDown();}
                if($('#cnx_supergroup').css('display') === 'none'){$('#cnx_supergroup').slideDown();}
                //rSid = true;
                break;
            case 0:
                //Erreur ne devant supposément jamais arriver. Survient lorsque l'URQ renvoie une donnée inattendue.
            case 2:
                //L'utilisateur sait qu'il est admin et sait comment il doit se connecter. C'est son problème.
                //On ne fait donc rien
                //break;
            case 3:
            case false:
            default:
                //On considère l'utilisateur comme "classique" avec birthday
                if(tc === true){
                    if($('#cnx_birthday_group').css('display') === 'none'){$('#cnx_birthday_group').slideDown();}
                    //rSid = true;
                } else if(tc === -1){
                    $('#cnx_msg > p').text(Kxlib_getDolphinsValue("p_cnx_srv_err"));
                    $('#cnx_msg').css('border-color', 'red');
                    $('#cnx_msg').css('background-color', 'pink');
                }
                break;
        }
    }
    $('#cnx_login_spinner').hide();
    //return rSid;
}

function cnxBirthdayDisplay(){
    //$('#cnx_login_spinner').hide();
    $('#cnx_birthday_group').slideDown();
}

function cnxBirthdayHide(){
    //$('#cnx_login_spinner').hide();
    $('#cnx_birthday_group').slideUp();
}

//Désactivation de l'envoi via 'Enter'
$('#cnx_form').bind("keyup keypress", function(e) {
  var code = e.keyCode || e.which; 
  if (code  === 13) {               
    e.preventDefault();
    return false;
  }
});

//Gestion des évènements si l'utilisateur fait face au 'todelete'
$(document).on('click', '#delcancel_keep', function(){
   location.href = 'http://www.trenqr.com';
});

$(document).on('click', '#delcancel_abort', function(){
    ajaxCnxTodeleteCancel($('#cnx_form_login_input').val());
});