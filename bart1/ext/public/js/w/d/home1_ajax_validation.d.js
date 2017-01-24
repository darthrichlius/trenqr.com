/* Regex */
var regFullname = /^[a-zA-Z- ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,40}$/;
var regNickname = /^[a-zA-Z0-9-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/;
var regMail = /^[a-zA-Z0-9-]{1,15}([.][a-zA-Z0-9-]{1,15})*@[a-zA-Z0-9-]{1,15}[.][a-z]{2,4}([.][a-z]{2})*$/;


/******************************/
/* Test AJAX sur la connexion */
/******************************/

/* Fonction de vérification lors du blur(); pour la date de naissance */
function ajaxThirdCriteriaChecker(){
    var rtc = false;
    var jsonData = new Object();
    jsonData.urqid = "hp_thirdCritCheck";
    jsonData.datas = {
        "login" : $('#dd_login').val()
    };
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    
    $.ajax({
        async:  false,
        url:    formURL,
        type:   'POST',
        data:   jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset === 'undefined'){
                    console.log('Erreur de retour JSON');
                } else {
                    if(dataset.enabled === true){
                        //moreInfoLoginOverlay();
                        var htmlblock = dataset.htmlblock;
                        $('#home1_scrolllock').append(htmlblock);
                        rtc = true;
                    }
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, errorThrown, textStatus){
            console.log(jqXHR);
            console.log(errorThrown);
            console.log(textStatus);
        }
    });
    return rtc;
}

/* Fonction de vérification de l'appartenance à un supergroupe */
function ajaxSupergroupChecker(){
    var sgc = false;
    var jsonData = new Object();
    jsonData.urqid = 'hp_supergroupUserCheck';
    jsonData.datas = {
        'login' : $('#dd_login').val()
    };
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset === 'undefined'){
                    console.log('erreur retour json');
                } else {
                    var htmlblock = dataset.htmlblock;
                    $('#home1_scrolllock').append(htmlblock);
                    switch(dataset.status){
                        case 'superuser_email':
                            sgc = 1;
                            break;
                        case 'superuser_pseudo':
                            sgc = 2;
                            break;
                        case 'classical_user':
                            sgc = 3;
                            break;
                        default:
                            //ne devrait pas arriver
                            sgc = 0;
                            break;
                    }
                }
            } catch(ex){
                console.log(ex);
            }
        }
    });
    return sgc;
}

/* Fonction de vérification de la justesse des infos de connexion avant d'afficher l'overlay */
function ajaxGCChecker(){
    var ret = false;
    var jsonData = new Object();
    jsonData.urqid = 'hp_overlayGCChecker';
    jsonData.datas = {
        'login'     : $('#dd_login').val(),
        'passwd'    : $('#dd_passwd').val()
    };
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    ret = dataset.gcc;
                }
            } catch(ex) {
                console.log(ex);
            }
        }
    });
    return ret;
}

/* Fonction de vérification de la validation */
function ajaxLoginChecker(){
    //Récupération de l'heure locale, on en aura besoin
    var date = new Date;
    var s = date.getSeconds();
    var m = date.getMinutes();
    var h = date.getHours();
    
    //e.preventDefault();
    var homeLoginLocker; //true = all good | false = locked
    var jsonData = {};
    //jsonData.urqid = "loginCheck";
    jsonData.urqid = "hp_overlayConnectSubmit";
    jsonData.datas = {
        "timeofday" : h + ':' + m + ':' + s,
        "login" : $('#dd_login').val(),
        "passwd" : $('#dd_passwd').val(),
        "staycon" : $('#session').prop('checked'),
        "locktype" : 'min'
    };
        var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                if(dataset.connected === false){
                    $('#dd_error').html(Kxlib_getDolphinsValue("p_home_cnxfailed"));
                    $('#dd_login').addClass('form_error_border');
                    $('#dd_passwd').addClass('form_error_border');
                    
                    var error = Kxlib_getDolphinsValue(dataset.err_ref);
                    
                    //Gestion de la redirection
                    var redir_form_main = document.createElement('form');
                    var login = document.createElement('input');
                    var error_ref = document.createElement('input');
                    
                    redir_form_main.method = "POST";
                    redir_form_main.action = "connection.php";
                    login.name = 'login';
                    login.value = $('#dd_login').val();
                    login.type = 'hidden';
                    error_ref.name = 'error_ref';
                    error_ref.value = error;
                    error_ref.type = 'hidden';
                    redir_form_main.appendChild(login);
                    redir_form_main.appendChild(error_ref);
                    document.body.appendChild(redir_form_main);
                    redir_form_main.submit();
                    
                    homeLoginLocker = false;
                } else if(dataset.connected === true){
                    $('#dd_error').html('');
                    $('#dd_login').removeClass('form_error_border');
                    $('#dd_passwd').removeClass('form_error_border');
                    
                    //Check du statut 'todelete' du compte
                    var tdl = ajaxHomepageTodeleteCheck();
                    
                    if(tdl === true){
                        homeLoginLocker = true;
                    } else {
                        homeLoginLocker = false;
                    }
                }
            } catch(ex){
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);
        }
    });
    
    return homeLoginLocker;
}

function ajaxHomepageTodeleteCheck(){
    var rVal;
    var jsonData = new Object();
    jsonData.urqid = 'hp_todeleteAccountCheck';
    jsonData.datas = {
        'login' : $('#dd_login').val()
    };
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.todelete === true){
                        //Récupération du CSS
                        var path = '../../public/css/delcancel_style.css';
                        var link = document.createElement('link');
                        link.setAttribute('rel', 'stylesheet');
                        link.setAttribute('type', 'text/css');
                        link.setAttribute('href', path);
                        document.getElementsByTagName('head')[0].appendChild(link);
                        
                        //Récupération du HTML
                        var overlay = dataset.overlay;
                        $('#home1_scrolllock').append(overlay);
                        todeleteOverlay();
                        rVal = false;
                        
                    } else {
                        rVal = true;
                    }
                    //TODO: Faire le if() et append l'overlay ET de son css
                }
            } catch(ex){
                console.log(ex);
            }
        }
    });
    return rVal;
}

function ajaxHomepageTodeleteCancel(login){
    var jsonData = new Object();
    jsonData.urqid = 'hp_todeleteAccountCancel';
    jsonData.datas = {
        'login' : login
    };
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            //Si on arrive ici, c'est qu'à priori tout c'est bien passé et que l'user a cancel la suppression de son compte
            //On peut donc le log. Pour ça, on simule un clic sur le login:
            $('#dropdown_login_submit').trigger('click');
            ajaxLoginChecker();
        },
        error   : function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);
        }
    });
}

function ajaxHomepageDobCheck(){
    //Récupération de l'heure locale, on en aura besoin
    var date = new Date;
    var s = date.getSeconds();
    var m = date.getMinutes();
    var h = date.getHours();
    
    //Vu le nombre de fonctions par lesquelles est passé le mail, on considère qu'il est safe ici
    var jsonData = new Object();
    jsonData.urqid = 'hp_overlayConnectSubmit';
    if($('#home_overlay_superpw').length){
        jsonData.datas = {
            'timeofday' : h + ':' + m + ':' + s,
            'login'     : $('#dd_login').val(),
            'passwd'    : $('#dd_passwd').val(),
            'staycon'   : $('#session').prop('checked'),
            'birthday'  : $('#dd_hb').val(),
            'superpw'   : $('#home_overlay_superpw').val(),
            'locktype'  : 'full'
        };
    } else {
        jsonData.datas = {
            'timeofday' : h + ':' + m + ':' + s,
            'login'     : $('#dd_login').val(),
            'passwd'    : $('#dd_passwd').val(),
            'staycon'   : $('#session').prop('checked'),
            'birthday'  : $('#home_overlay_day').val() + '-' + $('#home_overlay_month').val() + '-' + $('#home_overlay_year').val(),
            'locktype'  : 'dob'
        };
    }
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset === 'undefined'){
                    console.log('Erreur de retour JSON');
                } else {
                    if(dataset.connected === false){
                        //Redirection connexion
                        var error = Kxlib_getDolphinsValue(dataset.err_ref);
//                        window.location.href = './connection.php';
                        
                        //Gestion de la redirection
                        var redir_form = document.createElement('form');
                        var login = document.createElement('input');
                        var error_ref = document.createElement('input');

                        redir_form.method = "POST";
                        redir_form.action = "connection.php";
                        login.name = 'login';
                        login.value = $('#dd_login').val();
                        login.type = 'hidden';
                        error_ref.name = 'error_ref';
                        error_ref.value = error;
                        error_ref.type = 'hidden';
                        redir_form.appendChild(login);
                        redir_form.appendChild(error_ref);
                        document.body.appendChild(redir_form);
                        redir_form.submit();
                        
                        
                    } else {
                        //Redirection landing
                        window.location.href = './landing.php';
                    }
                    //TODO: Dev + finir sur un submit si tout est ok
                }
            } catch (ex) {
                console.log(ex);
            }
        },
        error   : function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);
        }
    });
}

function ajaxHomepageAccountExists(login){
    var rt;
    var jsonData = new Object();
    jsonData.urqid = 'hp_accountExists';
    jsonData.datas = {
        'login' : login
    };
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    var exists = dataset.exists;
                    if(exists === null){
                        rt = false;
                    } else {
                        rt = true;
                    }
                }
            } catch(ex){
                console.log(ex);
            }
        },
        error   : function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);
        }
    });
    return rt;
}

/************************************/
/* Test AJAX sur la pré-inscription */
/************************************/

/* Fonction de vérification */
function ajaxPreInsChecker(e){
    var homePreinsLocker; // true = all good | false = error
    //e.preventDefault();
    var jsonData = {};
    //jsonData.urqid = "preInsCheck";
    jsonData.urqid = "hp_crprg";
    jsonData.datas = {
        "fullname" : $('#fullname').val(),
        "pseudo" : $('#nickname').val(),
        "email" : $('#email').val(),
        "passwd" : $('#passwd').val()
    };
    //var formURL = "../../__servers/serverHomepage.php";
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signup&urqid=hp_crprg";
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                console.log(data);
                var dataset = JSON.parse(data);
                if(dataset.preins_status === false){
                    overlay_display();
                    errorMessage(Kxlib_getDolphinsValue("p_home_taform_fail"));
                    homePreinsLocker = false;
                } else if(dataset.preins_status !== false){
                    overlay_hide();
                    errorMessage('');
                    homePreinsLocker = dataset.preins_status;
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);
        }
    });
    return homePreinsLocker;
}

/********************************************/
/* Test AJAX sur la disponibilité du pseudo */
/********************************************/
function ajaxPseudoAvailability(){
    var regPseudo = /^[a-zA-Z0-9-_@ ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/;
    if(regPseudo.test($('#nickname').val())){
        var rValue;
        var jsonData = new Object();
        jsonData.urqid = 'hp_prgpsdchk';
        jsonData.datas = {
            'pseudo': $('#nickname').val()
        };
        var formURL = "http://www.trenqr.com/forrest/index.php?page=signup&urqid=hp_prgpsdchk";
        $.ajax({
            async:  false,
            url:    formURL,
            type:   'POST',
            data:   jsonData,
            success: function(data){
                try{
                    var dset = JSON.parse(data);
                    if(dset.available !== false){
                        //Pseudo dispo
                        rValue = dset.available;
                    } else {
                        rValue = false;
                    }
                } catch (ex) {
                    console.log(ex);
                }
            }
        });
        return rValue;
    }
}

/******************************************/
/* Test AJAX sur la disponibilité du mail */
/******************************************/
function ajaxMailAvailability(){
    var regMail = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;
    if(regMail.test($('#email').val())){
        var rValue;
        var jsonData = new Object();
        jsonData.urqid = 'hp_prgemachk';
        jsonData.datas = {
            'email': $('#email').val()
        };
        var formURL = 'http://www.trenqr.com/forrest/index.php?page=signup&urqid=hp_prgemachk';
        $.ajax({
            async:  false,
            url:    formURL,
            type:   'POST',
            data:   jsonData,
            success: function(data){
                try{
                    var dset = JSON.parse(data);
                    if(dset.available !== false){
                        //Mail dispo
                        rValue = dset.available;
                    } else {
                        rValue = false;
                    }
                } catch (ex) {
                    console.log(ex);
                }
            }
        });
        return rValue;
    }
}

/***********************************/
/* Test AJAX sur le compte d'essai */
/***********************************/
/* Triggers de la fonction */
/* Sur le blur(); */
/*$('#mode_essai_mail').blur(function(e){
    if(regMail.test($('#mode_essai_mail').val())){
        ajaxTrialMailChecker(e);
    }
});*/

/* Fonction de vérification */
function ajaxTrialMailChecker(e){
    var regMail = /^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;
    var homeTrialLocker; // true = all good | false = errors
    //e.preventDefault();
    var jsonData = {};
    //jsonData.urqid = "trialMailCheck";
    jsonData.urqid = "hp_trialEmailCheck";
    jsonData.datas = {
        "email" : $('#mode_essai_mail').val()
    };
    //var formURL = "../../__servers/serverHomepage.php";
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                if(typeof data !== 'undefined'){
                    var dataset = JSON.parse(data);
                    console.log(dataset);
                    if(dataset.availability === false){
                        /* Préciser si l'email est utilisé pour un compte d'essai ou un compte normal */
                        $('#mode_essai_error').html(Kxlib_getDolphinsValue("p_home_email_aiu"));
                        $('#mode_essai_mail').addClass('form_error_border');
                        homeTrialLocker = false;
                    } else if(regMail.test($('#mode_essai_mail').val())){
                        $('#mode_essai_error').html('');
                        $('#mode_essai_mail').removeClass('form_error_border');
                        homeTrialLocker = true;
                    } else {
                        $('#mode_essai_error').html('Adresse email invalide');
                        $('#mode_essai_mail').addClass('form_error_border');
                        homeTrialLocker = false;
                    }
                } else {
                    $('#mode_essai_error').html('Erreur inconnue');
                    $('#mode_essai_mail').addClass('form_error_border');
                    homeTrialLocker = false;
                    
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);
        }
    });
    
    return homeTrialLocker;
}

function trialMailSaveRedirect(e){
    if(e.type !== 'click'){
        //tentative faible de bypass, mais il faut quand même la repérer
        console.log('lightbypass');
        return;
    }
    var jsonData = new Object();
    jsonData.urqid = 'hp_trialMailRedirect';
    jsonData.datas = {
        'email': $('#mode_essai_mail').val()
    };
    var formURL = '../../process_urq/welcome_project/urq_sandbox.php';
    
    $.ajax({
        //async:  false,
        url:    formURL,
        type:   'POST',
        data:   jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                if(dataset.status === 'ok'){
                    //window.location.href = dataset.location + '?email=' + dataset.email;
                    var form = $('<form action="' + dataset.location + '" method="post">' +
                      '<input type="text" name="email" value="' + dataset.email + '" />' +
                      '<input type="text" name="instype" value="tryaccount" />' +
                      '</form>');
                    $(form).submit();
                } else {
                    window.location.href = dataset.location + '?errno=' + dataset.errcode;
                }
            } catch(ex) {
                console.log(ex);
            }
        }
    });
}

/*******************************************************************/
/* Test AJAX sur la présence de l'email de pré-inscription en base */
/*******************************************************************/

//function ajaxPreInsMailStateChecker(e){
//    //e.preventDefault();
//    jsonData = {};
//    jsonData.urqid = "preinsMailStateCheck";
//    jsonData.datas = {
//        "email": $('#email').val()
//    };
//    var formURL = "../../__servers/serverHomepage.php";
//    
//    $.ajax({
//        async: false,
//        url: formURL,
//        type: "POST",
//        data: jsonData,
//        success: function(data){
//            try{
//                //console.log(data);
//                if(data !== ""){
//                    dataset = JSON.parse(data);
//                    manualOverlay();
//                }
//            } catch(ex) {
//                //stuff
//            }
//        },
//        error: function(jqXHR, textStatus, errorThrown){
//            console.log('Error');
//            console.log(errorThrown);
//            console.log(textStatus);            
//        }
//    });
//}

/**********************************************************************************/
/* Test AJAX sur la tentative de connexion via un compte pré-enregistré seulement */
/**********************************************************************************/

function ajaxPreregLoginCheck(e){
    var rVal = false;
    //e.preventDefault();
    jsonData = {};
    //jsonData.urqid = "preregLoginCheck";
    jsonData.urqid = "hp_preinsLoginAttempt";
    jsonData.datas = {
        "login": $('#dd_login').val(),
        //Pas besoin de passer le passwd en fait. On fait juste une vérif du login.
        "passwd": $('#dd_passwd').val()
    };
    //var formURL = "../../__servers/serverHomepage.php";
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                dataset = JSON.parse(data);
                if(dataset.isPreregAccount === true){
                    rVal = true;
                    e.preventDefault();
                    preregLoginOverlay();
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);            
        }
    });
    return rVal;
}