/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//Fonction de vérification du mail
function id_checkDropdown(inputLogin) {
    $('#dd_error').html('');
    var emailReg = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;
    var pseudoReg = /^[a-zA-Z0-9-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/;
    if(!emailReg.test(inputLogin)) {
        if (!pseudoReg.test(inputLogin)){
            $('#dd_error').html('Identifiant invalide');
            $('#dd_login').addClass('form_error_border');
        }
    }
}


//Call de la fonction générale
$('#dd_login').blur(function(){
    var inputMail = $(this).val();
    $('#dd_login').removeClass('form_error_border');
    if(inputMail !== ''){
        id_checkDropdown(inputMail);
    } else {
        $('#dd_error').html('');
    }
});

$('#dd_passwd').blur(function(){
    var inputPasswd = $(this).val();
    $('#dd_passwd').removeClass('form_error_border');
    if(inputPasswd === ''){
        $('#dd_error').html('');
    }
});


//Boolean de véfirification
var dd_validation_ok = true;

//Check du login avant envoi
function dd_login_valid(inputLogin){
    var regEmail = /^[a-zA-Z0-9-]{1,15}([.][a-zA-Z0-9-]{1,15})?@[a-zA-Z0-9-]{1,15}[.][a-z]{2,4}([.][a-z]{2})?$/;
    var regNickname = /^[a-zA-Z0-9-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/;
    if(!regEmail.test(inputLogin)){
        if(!regNickname.test(inputLogin)){
            dd_validation_ok = false;
            $('#dd_login').addClass('form_error_border');
        }
    }
};

//Check du password avant envoi
function dd_password_valid(inputPasswd){
    var reg = /^[^<=>\\;/]{4,15}$/;
    if(!reg.test(inputPasswd)){
        dd_validation_ok = false;
        $('#dd_passwd').addClass('form_error_border');
    }
};

//Gestion du call Ajax pour le check de mail
$('#dd_login').blur(function(){
    readyForInfoCheck();
});
$('#dd_passwd').blur(function(){
    readyForInfoCheck();
});

function readyForInfoCheck(){
    var exs = false;
    var cred = false;
    var l = $('#dd_login').val();
    var p = $('#dd_passwd').val();
    if(l !== '' && p !== ''){
        //Reset des append
        $('#home_moreInfoRequired').remove();
        
        exs = ajaxHomepageAccountExists(l);
        cred = ajaxGCChecker();
        if(exs === true && cred === true){
            var sg = ajaxSupergroupChecker();
            var tc = ajaxThirdCriteriaChecker();
            //Si le test de sg est positif, on a un superuser, donc on affiche la date de naissance et le pw supplémentaire
            //Si non, on regarde si le check du TC est bon ou pas.
            switch(sg){
                case 0:
                    //Erreur ne devant supposément jamais arriver. On va la traiter de la même façon qu'une erreur de type 2
                    $('#home_sub_special_error').show();
                    moreInfoLoginOverlay();
                    break;
                case 1:
                    //Cas "OK"
                    $('#home_sub_birthday').show();
                    $('#home_sub_specialgroup').show();
                    moreInfoLoginOverlay();
                    break;
                case 2:
                    //Erreur de type 2
                    $('#home_sub_special_error').show();
                    moreInfoLoginOverlay();
                    break;
                case 3:
                case false:
                default:
                    //On considère l'utilisateur comme "classique"
                    if(tc === true){
                        $('#home_sub_birthday').show();
                        moreInfoLoginOverlay();
                    }
                    break;
            }
        }
        //Gestion de l'action du bouton 'Valider' de l'overlay
        if($('#home_sub_special_error').css('display') === 'block'){
            $('#home_overlay_ok').click(function(){
                window.location.reload();
            });
        } else {
            $('#home_overlay_ok').bind('click', function(e){
                homeOverlayButton(e);
            });
        }
    }
}


$('#dropdown_login').bind("keyup keypress", function(e) {
  var code = e.keyCode || e.which; 
  if (code  === 13) {               
    e.preventDefault();
    return false;
  }
});

//Fonction de contrôle générale avant submit
$('#dropdown_login_submit').click(function(e){
    var exs = false;
    e.preventDefault();
    //Récupération des valeurs des champs
    var finalInputLogin = $('#dd_login').val();
    var finalInputPasswd = $('#dd_passwd').val();
    
    //Reset du boolean
    dd_validation_ok = true;
    
    //Recheck des contenus (et si vide)
    dd_login_valid(finalInputLogin);
    dd_password_valid(finalInputPasswd);
    
    //existance du compte
    exs = ajaxHomepageAccountExists(finalInputLogin);
    
    var pglk = ajaxPreregLoginCheck(e);
    if(pglk !== true){
        var dd_ajax_locker = ajaxLoginChecker();
    }
    
    if(dd_validation_ok === true && dd_ajax_locker === true && exs === true){
        $('#dd_error').html('');
        $('#dropdown_login').submit();
    }
});

//Gestion des évènements si l'utilisateur fait face au 'todelete'
$(document).on('click', '#delcancel_keep', function(){
   location.href = 'http://www.trenqr.com';
});

$(document).on('click', '#delcancel_abort', function(){
    ajaxHomepageTodeleteCancel($('#dd_login').val());
});