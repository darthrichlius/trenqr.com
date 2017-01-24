/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Fonctions de display de l'overlay
function overlay_display(){
    $('#home1_overlay').css('display', 'block');
}

function overlay_hide(){
    $('#home1_overlay').css('display', 'none');
}

//Compteur de champs erronés
var nombreChampsFaux = 0;

function compteurErreurs(){
    nombreChampsFaux++;
    if (nombreChampsFaux > 1){
        var msg = Kxlib_getDolphinsValue("p_home_val_checkinputs");
        errorMessage(msg);
    }
}


function lockCheck(){
    var lockTable = $('#home1_preinscription input[type=text]');
    var cn=0;
    var ar = new Array();
    
    $.each(lockTable, function(i, v){
        if ($(v).data('st') === "lock"){
            ar.push(v);
            
        }
    });
    //alert(cn);
    
    cn = ar.length;
    if (cn > 1){
        //alert("toto");
        var msg = Kxlib_getDolphinsValue("p_home_val_checkinputs");
        errorMessage(msg);
    } else if (cn === 0){
        overlay_hide();
    } else if (cn === 1){
        var argument = ar[0];
        var argSelector = $(argument).attr('id');
        //Recuppérer msg pour mettre dans le champs
        switch(argSelector){
            case "fullname":
                var msg = Kxlib_getDolphinsValue("p_home_val_fnbadchars");
                errorMessage(msg);
                break;
            
            case "nickname":
                var msg = Kxlib_getDolphinsValue("p_home_val_pseudobadchars");
                errorMessage(msg);
                break;
            
            case "email":
                var msg = Kxlib_getDolphinsValue("p_home_val_emailbadchars");
                errorMessage(msg);
                break;
        }
    }
    
    
}



function errorMessage(msg){
    $('#form_errors').html(msg);
}

//Fonctions de vérification
//function fullname_check(inputFullName) {
//    $('#form_errors').html('');
//    var reg = /^[a-zA-Z- ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,40}$/;
//    var msg = Kxlib_getDolphinsValue("p_home_val_fnbadchars");
//    if(!reg.test(inputFullName)) {
//        $('#fullname').data("st", "lock");
//        overlay_display();
//        $('#fullname').addClass('form_error_border');
//        errorMessage(msg);
//        lockCheck();        
//    } else {
//        $('#fullname').data("st", "ulock");
//        $('#fullname').removeClass('form_error_border');
//        lockCheck();  
//    }
//}

//function nickname_check(inputNickname) {
//    $('#form_errors').html('');
//    var reg = /^[a-zA-Z0-9-_@ ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/;
//    var msg = Kxlib_getDolphinsValue("p_home_val_pseudobadchars");
//    if(!reg.test(inputNickname)) {
//        $('#nickname').data("st", "lock");
//        overlay_display();
//        $('#nickname').addClass('form_error_border');
//        errorMessage(msg);
//        lockCheck();
//    } else {
//        $('#nickname').data("st", "ulock");
//        $('#nickname').removeClass('form_error_border');
//        lockCheck();  
//    }
//}

//function email_check(inputEmail, e) {
//    $('#form_errors').html('');
//    var reg = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;
//    var msg = Kxlib_getDolphinsValue("p_home_val_emailbadchars");
//    if(!reg.test(inputEmail)) {
//        $('#email').data("st", "lock");
//        overlay_display();
//        $('#email').addClass('form_error_border');
//        errorMessage(msg);
//        lockCheck();
//    } else {
//        $('#email').data("st", "ulock");
//        $('#email').removeClass('form_error_border');
//        lockCheck();  
//        //ajaxPreInsMailStateChecker(e);
//    }
//}

//function passwd_check(inputPasswd){
//    $('#form_errors').html('');
//    var reg = /^[^<=>\\;/]{4,15}$/;
//    var msg = Kxlib_getDolphinsValue("p_home_val_pwbadchars");
//    if(!reg.test(inputPasswd)){
//        overlay_display();
//        $('#passwd').addClass('form_error_border');
//        errorMessage(msg);
//    } else {
//        $('#passwd').removeClass('form_error_border');
//    }
//}


//Call des fonctions générales
//$('#fullname').blur(function(){
//    var inputFullName = $(this).val();
//    if(inputFullName !== ''){
//        fullname_check(inputFullName);
//    } else {
//        $('#form_errors').html('');
//        //overlay_hide();
//        $('#fullname').data("st", "ulock");
//        $('#fullname').removeClass('form_error_border');
//        lockCheck();
//    }
//});

//$('#nickname').blur(function(){
//    var inputNickname = $(this).val();
//    if(inputNickname !== ''){
//        nickname_check(inputNickname);
//        var av = ajaxPseudoAvailability();
//        if(av !== false){
//            lockCheck();
//        } else {
//            $('#form_errors').html(Kxlib_getDolphinsValue("p_home_val_pseudotaken"));
//            overlay_display();
//            $('#nickname').data("st", "lock");
//            $('#nickname').addClass('form_error_border');
//        }
//    } else {
//        $('#form_errors').html('');
//        //overlay_hide();
//        $('#nickname').data("st", "ulock");
//        $('#nickname').removeClass('form_error_border');
//        lockCheck();
//    }
//});

//$('#email').blur(function(e){
//    var inputEmail = $(this).val();
//    if(inputEmail !== ''){
//        email_check(inputEmail);
//        var av = ajaxMailAvailability();
//        Kxlib_DebugVars([v]);
//        if(av !== false){
//            lockCheck();
//        } else {
//            $('#form_errors').html(Kxlib_getDolphinsValue("p_home_val_emailtaken"));
//            overlay_display();
//            $('#email').data('st', 'lock');
//            $('#email').addClass('form_error_border');
//        }
//    } else {
//        $('#form_errors').html('');
//        //overlay_hide();
//        $('#email').data("st", "ulock");
//        $('#email').removeClass('form_error_border');
//        lockCheck();
//    }
//});

//$('#passwd').blur(function(){
//    var inputPasswd = $(this).val();
//    if(inputPasswd !== ''){
//        passwd_check(inputPasswd);
//    } else {
//        //$('#form_errors').html('');
//        $('#passwd').data("st", "ulock")
//        $('#passwd').removeClass('form_error_border');
//    }
//});



//var validation_ok = true;  //Bool de contrôle
//
//function fullname_valid(inputFullname){
//    var reg = /^[a-zA-Z- ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,40}$/;
//    if(!reg.test(inputFullname)){
//        validation_ok = false;
//        $('#fullname').addClass('form_error_border');
//        //errorMessage("Formulaire non valide");
//    }
//}
//
//function nickname_valid(inputNickname){
//    var reg = /^[a-zA-Z0-9-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/;
//    if(!reg.test(inputNickname)){
//        validation_ok = false;
//        $('#nickname').addClass('form_error_border');
//        //errorMessage("Formulaire non valide");
//    }
//}
//
//function email_valid(inputEmail){
//    var reg = /^[a-zA-Z0-9-]{1,15}([.][a-zA-Z0-9-]{1,15})*@[a-zA-Z0-9-]{1,15}[.][a-z]{2,4}([.][a-z]{2})*$/;
//    if(!reg.test(inputEmail)){
//        validation_ok = false;
//        $('#email').addClass('form_error_border');
//        //errorMessage("Formulaire non valide");
//    }
//}
//
//function passwd_valid(inputPasswd){
//    var reg = /^[^<=>\\;/]{4,15}$/; //TEMPORAIRE
//    if(!reg.test(inputPasswd)){
//        validation_ok = false;
//        $('#passwd').addClass('form_error_border');
//        //errorMessage("Formulaire non valide");
//    }
//}


//
//$('#home1_preinscription').bind("keyup keypress", function(e) {
//  var code = e.keyCode || e.which; 
//  if (code  === 13) {               
//    e.preventDefault();
//    return false;
//  }
//});


//Fonction de contrôle générale avant submit
//Variable de surveillance pour éviter la boucle infinie de triggers 'click'
//var preinsWatcher = 0;
//$('#home1_form_submit_labels').click(function(e){
$('#home1_preinscription').submit(function(e){
//    //Annulation du clic pour laisser le temps au traitement AJAX.
////    if(preinsClickWatcher === 0){
////        alert('prevent');
////        e.preventDefault();
////    }
//    
//    //Récupération des valeurs des champs
//    var finalInputFullname = $('#fullname').val();
//    var finalInputNickname = $('#nickname').val();
//    var finalInputEmail = $('#email').val();
//    var finalInputPasswd = $('#passwd').val();
//    
//    //Reset du boolean
//    validation_ok = true;
//    var home1_form_ajax_locker = ajaxPreInsChecker(e);
//    
//    //Recheck des contenus (et si vide)
//    fullname_valid(finalInputFullname);
//    nickname_valid(finalInputNickname);
//    email_valid(finalInputEmail);
//    passwd_valid(finalInputPasswd);
//    
////    if(preinsClickWatcher === 0){
////        preinsClickWatcher++;
////        $(this).trigger('click');
////        return;
////    }

    var final_val_prf = true;
    
    $('.preg_ins_com_check').removeClass('error_border');
    
    $.each($(".preg_ins_com_check"),function(x,v){
        if ( $(v).val() === "" ) {
            $(v).addClass("error_border");
            final_val_prf = false;
            return;
        } 
    });
    
    if( final_val_prf === true ){
         //e.preventDefault();
        //$('#home1_preinscription').submit();
//        var pgKey = home1_form_ajax_locker;
//        var action = "pa="+pgKey;
//        $("#ups").attr("value", action);
//        //alert($("#ups").attr("value"));
        
    } else {
//        overlay_display();
//        errorMessage("Formulaire non valide");
        e.preventDefault();
    }
    
});


$(".preg_ins_com_check").blur(function(){
    if ( $(this).length === 0 || $(this).val() === "" ) {
        $(this).addClass("error_border");
    } else {
        $(this).removeClass("error_border");
    }
});