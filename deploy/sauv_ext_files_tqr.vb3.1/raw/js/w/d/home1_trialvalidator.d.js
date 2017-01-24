/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//Fonction de vérification du mail
function mail_checkTrial(inputLogin) {
    $('#mode_essai_error').html('');
    var emailReg = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;
    if(!emailReg.test(inputLogin)) {
        $('#mode_essai_error').html(Kxlib_getDolphinsValue("p_home_val_emailbadchars"));
        $('#mode_essai_mail').addClass('form_error_border');
    }
}


//Call de la fonction générale
$('#mode_essai_mail').blur(function(){
    var inputMail = $(this).val();
    $('#mode_essai_mail').removeClass('form_error_border');
    if(inputMail !== ''){
        mail_checkTrial(inputMail);
    } else {
        $('#mode_essai_error').html('');
    }
});

//Boolean de vérification
var trial_validation_ok = true;

//Check du mail avant envoi
function trial_mail_valid(inputMail){
    var reg = /^[a-zA-Z0-9-]{1,15}([.][a-zA-Z0-9-]{1,15})?@[a-zA-Z0-9-]{1,15}[.][a-z]{2,4}([.][a-z]{2})?$/;
    if(!reg.test(inputMail)){
        trial_validation_ok = false;
        $('#mode_essai_error').html(Kxlib_getDolphinsValue("p_home_val_emailbadchars"));
        $('#mode_essai_mail').addClass('form_error_border');
        
    }
}

//Contrôle général avant le submit
$('#mode_essai_submit').click(function(e){
    //Récupération de la valeur du champ
    var finalInputMail = $('#mode_essai_mail').val();
    
    //Reset du boolean
    trial_validation_ok = true;
    
    //Recheck du contenu
    trial_mail_valid(finalInputMail);
    
    if(trial_validation_ok === true){
        var home1_trial_ajax_locker = ajaxTrialMailChecker(e);
    }
    
    
    if(trial_validation_ok === true && home1_trial_ajax_locker === true){
        $('#mode_essai_error').html('');
        e.preventDefault();
        //alert('Création du compte d\'essai');
        //On sauve le mail en base (archives) et on redirige via cette fonction
        trialMailSaveRedirect(e);
    } else if (finalInputMail === ''){
        $('#mode_essai_error').html(Kxlib_getDolphinsValue("p_home_val_emptyemail"));
        e.preventDefault();
        //alert('Echec de l\'envoi');
    } else {
        e.preventDefault();
        //alert('Echec de l\'envoi');
    }
});