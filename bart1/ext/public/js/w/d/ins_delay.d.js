/* Gestion de l'autorisation de lancer un report de l'inscription */

//Variable de lock -- true = verouillé | false = dévérouillé
var insDelayLocked = false;

/* Vérification du contenu des champs
 * Regex + Non vide sur Fullname / Nickname / Mail / Passwd
 * Regex sur le reste
 */
function insDelayCheckFullname(input){
    if(!regFullname.test(input)){
        insDelayLocked = true;
    }
}

function insDelayCheckBirthday(input){
    if(!/^[^:;="']$/.test(input)){
        insDelayLocked = true;
    }
}

function insDelayCheckGender(input){
    if(!/^[mf]{1}$/.test(input)){
        insDelayLocked = true;
    }
}

function insDelayCheckCity(input){
    if(input !== '' && !regCity.test(input)){
        insDelayLocked = true;
    }
}

function insDelayCheckNickname(input){
    if(!regNickname.test(input)){
        insDelayLocked = true;
    }
}

function insDelayCheckEmail(input){
    if(!regMail.test(input)){
        insDelayLocked = true;
    }
}

function insDelayCheckPasswd(input){
    if(!regPasswdWeak.test(input)){
        if(!regPasswdMed.test(input)){
            if(!regPasswdStrong.test(input)){
                insDelayLocked = true;
            }
        }
    }
}


//Handler
$('#ins_right_middle_link').click(function(e){
    //Stockage des données
    var delayFullname   = $('#ins_input_fullname').val();
    var delayBirthday   = $('#month').val() + '-' + $('#day').val(); + '-' + $('#year').val();
    var delayGender     = $('input[name=gender]:checked').val();
    var delayCity       = $('#ins_input_city').val();
    var delayNickname   = $('#ins_input_nickname').val();
    var delayEmail      = $('#ins_input_mail').val();
    var delayPasswd     = $('#ins_input_passwd').val();
    
    insDelayCheckFullname(delayFullname);
    //insDelayCheckBirthday(delayBirthday);
    //insDelayCheckGender(delayGender);
    insDelayCheckCity(delayCity);
    insDelayCheckNickname(delayNickname);
    insDelayCheckEmail(delayEmail);
    insDelayCheckPasswd(delayPasswd);
    
    if(insDelayLocked === false){
        //alert('Report OK');
    } else {
        e.preventDefault();
        //alert('Erreur: Report impossible');
    }
    
    
});