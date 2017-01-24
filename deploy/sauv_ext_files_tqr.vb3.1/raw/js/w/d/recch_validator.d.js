/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* Regex utilisées */
var regPasswdIncorrect = /^[^"'=]{0,5}$/;
var regPasswdWeak = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6,7}$/;
var regPasswdMed = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6}(((?=(.*[a-zA-Z]))[a-zA-Z]{2})|(([^;:="'])?(?=(.*[0-9!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%]))[^:;="']))$/;
var regPasswdStrong = /^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%])[^:;="']{6}(([^;:="'])?(?=(.*[0-9!.?+*_~µ£^¨°\(\)/\\\[\]\-@#$%]))[^:;="']{3,14})$/;

/* Fonction de gestion du message d'erreur.
 * Le paramètre passé détermine le message.
 * 0 = Reset du message et disparition du bloc
 * 1 = Password rentré incorrect (echec de la regex)
 * 2 = Password de vérification incorrect (ne correspond pas au premier)
 * 3 = Password vide (echec de la regex (+ non vide))
 * 4 = Confirmation du password vide (submit only)
 */
function recchErrorManager(errtype){
    switch(errtype){
        case 0:
            $('#recch_form_error').html('');
            $('#recch_form_error').css('display', 'none');
            $('#recch_passwd').removeClass('recch_error_border');
            $('#recch_passwd_conf').removeClass('recch_error_border');
            break;
        
        case 1:
            $('#recch_form_error').html(Kxlib_getDolphinsValue("p_recch_err_pwpolicy"));
            $('#recch_form_error').css('display', 'block');
            $('#recch_passwd').addClass('recch_error_border');
            break;
        
        case 2:
            $('#recch_form_error').html(Kxlib_getDolphinsValue("p_recch_err_pwconf"));
            $('#recch_form_error').css('display', 'block');
            $('#recch_passwd_conf').addClass('recch_error_border');
            break;
        
        case 3:
            $('#recch_form_error').html(Kxlib_getDolphinsValue("p_recch_err_pwempty"));
            $('#recch_form_error').css('display', 'block');
            $('#recch_passwd').addClass('recch_error_border');
            break;
        
        case 4:
            $('#recch_form_error').html(Kxlib_getDolphinsValue("p_recch_err_pwconfempty"));
            $('#recch_form_error').css('display', 'block');
            $('#recch_passwd_conf').addClass('recch_error_border');
            break;
        
        case 5:
            $('#recch_form_error').html(Kxlib_getDolphinsValue("p_recch_err_srverror"));
            $('#recch_form_error').css('display', 'block');
            break;
    }
}

function recch_pwcheck(){
    if($('#recch_passwd').val() === ''){
        recchErrorManager(0);
    } else if(regPasswdStrong.test($('#recch_passwd').val())){
        recchErrorManager(0);
    } else if(regPasswdMed.test($('#recch_passwd').val())){
        recchErrorManager(0);
    } else if(regPasswdWeak.test($('#recch_passwd').val())){
        recchErrorManager(0);
    } else {
        recchErrorManager(1);
    }
}

$('#recch_passwd').blur(function(){recch_pwcheck();});

$('#recch_passwd_conf').blur(function(){
    if($('#recch_passwd_conf').val() === ''){
        recchErrorManager(0);
    } else if($('#recch_passwd_conf').val() === $('#recch_passwd').val()){
        recchErrorManager(0);
        recch_pwcheck();
    } else {
        recchErrorManager(2);
    }
});

$('#recch_form').submit(function(e){
    var r;
    if($('#recch_passwd').val() === ''){
        recchErrorManager(3);
        e.preventDefault();
    } else if($('#recch_passwd_conf').val() !== $('#recch_passwd').val()){
        recchErrorManager(4);
        e.preventDefault();
    } else if(!regPasswdStrong.test($('#recch_passwd').val())){
        if(!regPasswdMed.test($('#recch_passwd').val())){
            if(!regPasswdWeak.test($('#recch_passwd').val())){
                recchErrorManager(1);
                e.preventDefault();
            } else { /*alert('okWeak');*/ r = ajaxRecoveryPasswordChanger();}
        } else {/*alert('okMedium');*/ r = ajaxRecoveryPasswordChanger();}
    } else { /*alert('okStrong');*/ r = ajaxRecoveryPasswordChanger();}
    if(r === false){
        //Si on passe ici c'est qu'il y a un problème en amont avec le changement de password.
        //Il faudrait alors rediriger l'utilisateur
        e.preventDefault();
        recchErrorManager(5);
    } else {
        //On laisse le submit se faire en disant que tout est OK
        $('#recch_rd').val('t');
//        e.preventDefault();
    }
});

/* Désactivation du c/c */
$('#recch_passwd').bind("contextmenu cut copy paste",function(e){
    e.preventDefault();
});

$('#recch_passwd_conf').bind("contextmenu cut copy paste",function(e){
    e.preventDefault();
});

/* Gestion de l'annulation */
$('#recch_cancel_link').click(function(){
    ajaxCancelRecovery();
});