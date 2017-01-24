/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* Regex utilisée */
var regMail = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;


/* Fonction de gestion du message d'erreur.
 * Le paramètre passé en entrée détermine le message.
 * 0 = Reset du message et disparition du bloc
 * 1 = Mail entré incorrect (echec de la regex)
 * 2 = Champ vide (echec de la regex ( + non vide))
 * 3 = Mail inconnu dans la base de données (ajax) */
function recErrorManager(errtype){
    switch(errtype){
        case 0:
            $('#rec_form_error').html('');
            $('#rec_form_error').css('display', 'none');
            $('#rec_form_email').removeClass('rec_error_border');
            break;
            
        case 1:
            $('#rec_form_error').html(Kxlib_getDolphinsValue("p_rec_err_emailbadchar"));
            $('#rec_form_error').css('display', 'block');
            $('#rec_form_email').addClass('rec_error_border');
            break;
            
        case 2:
            $('#rec_form_error').html(Kxlib_getDolphinsValue("p_rec_err_emailempty"));
            $('#rec_form_error').css('display', 'block');
            $('#rec_form_email').addClass('rec_error_border');
            break;
            
        case 3:
            $('#rec_form_error').html(Kxlib_getDolphinsValue("p_rec_err_noaccount"));
            $('#rec_form_error').css('display', 'block');
            $('#rec_form_email').addClass('rec_error_border');
            break;
            
        case 4:
            $('#rec_form_error').html(Kxlib_getDolphinsValue("p_rec_err_srverror"));
            $('#rec_form_error').css('display', 'block');
            $('#rec_form_email').addClass('rec_error_border');
            break;
    }
}

$('#rec_form_email').blur(function(e){
    if($('#rec_form_email').val() === ''){
        recErrorManager(0);
    } else if(!regMail.test($('#rec_form_email').val())){
        recErrorManager(1);
    } else {
        recErrorManager(0);
        ajaxRecoveryEmailChecker(e);
    }
});

$('#rec_form').submit(function(e){
    if($('#rec_form_email').val() === ''){
        recErrorManager(2);
        e.preventDefault();
    } else if(!regMail.test($('#rec_form_email').val())){
        recErrorManager(1);
        e.preventDefault();
    } else {
        recErrorManager(0);
        var recStatus = ajaxRecoveryEmailChecker(e);
        if(recStatus === false){
            //alert('Echec de l\'envoi');
            e.preventDefault();
        } else {
            ajaxRecoveryEmailSender($('#rec_form_email').val());
        }
    }    
});