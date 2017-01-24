/****************************/
/* AJAX PARTIE #0 - GENERAL */
/****************************/

/**
 * A besoin d'avoir le passwd en paramètre pour pouvoir utiliser la même
 * fonction pour tous les checks de hidden passwds.
 * @param {string} passwd User's password.
 * @returns {boolean} TRUE if the passwd is correct, FALSE otherwise.
 */
function ajaxHiddenPwCheck(passwd){
    var rVal = new Boolean();
    var jsonData = new Object();
    jsonData.urqid = 'hiddenpw_check';
    jsonData.datas = {
        'hiddenpw'  : passwd
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_gen_chkHidPas";
    
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.authorized === true){
                        rVal = true;
                    } else {
                        rVal = false;
                    }
                } else {
                    rVal = false;
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
            rValue = false;
        }
    });
    return rVal;
}

function ajaxLoadHiddenpw(panel){
    var jsonData = new Object();
    jsonData.urqid = 'load_hiddenpw';
    //jsonData.datas = {};
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_gen_loadHidPas";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    var block = dataset.html;
                    
                    //Gestion des problèmes pouvant survenir
                    if(block === 'APPHIDPAS_ERR'){
                        //TODO: METTRE EN PLACE UN MESSAGE D'ERREUR VISIBLE PAR L'UTILISATEUR
                        console.log("PROBLEME");
                    }
                    
                    switch(panel){
                        case 'profile':
                            $('#pfl_profile_submit').append(block);
                            $('.pfl_hidden_form_submit').prop('id', 'pfl_submit_profile');
                            break;
                        case 'security':
                            $('#pfl_locks_submit').append(block);
                            $('.pfl_hidden_form_submit').prop('id', 'pfl_submit_secu_locks');
                            break;
                        case 'account':
                            $('#pfl_account_submit').append(block);
                            $('.pfl_hidden_form_submit').prop('id', 'pfl_submit_account_classic');
                            break;
                        case 'newmail':
                            $('#pfl_veriflink_resend').after(block);
                            $('.pfl_hidden_form_submit').prop('id', 'pfl_veriflink_passwd_ok');
                            break;
                        default:
                            //TODO: METTRE EN PLACE UN MESSAGE D'ERREUR VISIBLE PAR L'UTILISATEUR
                            console.log("PROBLEME");
                            break;
                    }
                } else {
                    //error
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
            rValue = false;
        }
    });
}

/***************************/
/* AJAX PARTIE #1 - PROFIL */
/***************************/

function ajaxProfileFormChecker(){
    var rValue = new Boolean();
    var jsonData = new Object();
    jsonData.urqid = 'pflProfile';
    jsonData.datas = {
        'fullname':         htmlEntities($('#pfl_input_fullname').val()),
        'birthday':         htmlEntities($('#pfl_month').val() + '-' + $('#pfl_day').val() + '-' + $('#pfl_year').val()),
        'gender':           htmlEntities($('#slider_selector').data('g')),
        //'city':             $('#pfl_input_city').val(),
        'cityId':           htmlEntities($('#pfl_input_city').data('cc'))
    };
    var formURL = '../../__servers/serverAccount.php';
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                dataset = JSON.parse(data);
                if(dataset.okForSending === true){
                    rValue = true;
                } else {
                    rValue = false;
                }
            }catch(ex){
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
            rValue = false;
        }
    });
    return rValue;
}

function ajaxProfileLoader(/*pflid*/){
    var dataset = null;
    var jsonData = new Object();
    jsonData.urqid = 'pfl_profileLoader';
    jsonData.datas = {
        /*'pflid':        parseInt(pflid)*/
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_pfl_load";
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                dataset = JSON.parse(data);
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
    return dataset;
}

function ajaxProfileSave(e){
    //TODO: Chercher à target le bon input
    if(e.type !== 'submit' | e.target.length !== 7){
        //tentative faible de bypass, mais il faut quand même la repérer
        //le e.target.length = 7 est une 'sécurité' supplémentaire mais qui peut
        //changer si la page subit des modifications.
        console.log('lightbypass');
        return;
    }
    var p = $('#hidden_input_pw').val();
    if(ajaxHiddenPwCheck(p) !== true){
        //tentative de passage en force avec bypass du password
        console.log('forcepass');
        return;
    }
    var dataset;
    var jsonData = new Object();
    jsonData.urqid = 'pfl_profileSave';
    jsonData.datas = {
        'fullname'  : $('#pfl_input_fullname').val(),
        'birthday'  : $('#pfl_year').val() + '-' + $('#pfl_month').val() + '-' + $('#pfl_day').val(),
        'gender'    : $('#slider_selector').data('g'),
        'cityId'    : $('#pfl_input_city').data('cc')
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_pfl_save";
    $.ajax({
        async   : false,    //Alors pour le coup, ici c'est nécessaire, mais je comprends pas pourquoi
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.rt !== null && parseInt(dataset.rt) !== 1){
                        //TEMPORAIRE
                        console.log(dataset.rt);
                        switch(dataset.rt){
                            case 'PFLU_NO_BDMOD_REM':
                                $('#pfl_errmsg').html(Kxlib_getDolphinsValue("p_pfl_valpfl_no_bdmod_rem"));
                                $('#hidden_input_pw').addClass('.error_border');
                                $('#pfl_errbox').show();
                                break;
                            case 'PFLU_NO_GMOD_REM':
                                $('#pfl_errmsg').html(Kxlib_getDolphinsValue("p_pfl_valpfl_no_gmod_rem"));
                                $('#hidden_input_pw').addClass('.error_border');
                                $('#pfl_errbox').show();
                                break;
                            default:
                                return;
                                break;
                        }
                    } else {
                        location.reload(true);
                        //console.log('NP')
                    }
                }
            } catch (ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Problem");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
}

/******************************************/
/* AJAX PARTIE #1BIS - AUTOCOMPLETE VILLE */
/******************************************/
function ajaxProfileCitySuggestion(){
    var suggestionArray = new Array();
    var jsonData = new Object();
    jsonData.urqid = 'pfl_citySuggestion';
    jsonData.datas = {
        'input': htmlEntities($('#pfl_input_city').val())
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_pfl_citSug";

    $.ajax({
        //async: false, // Nécessaire pour manualInputChecker()
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);                 //Parsing des données JSON
                $.each(dataset, function(k, v){                 //Je range chaque 'pack' de données dans un tableau
                    suggestionArray.push(v);
                });
                //console.log(suggestionArray[1].name);         //On accède aux données comme dans un tableau normal
                //console.log(suggestionArray[1].country);      //en connaissant la structure de l'objet (name / country / pop
                //console.log(suggestionArray[1].pop);

                pflCityAutocomplete(suggestionArray);           //On envoie à l'autocomplete le tableau d'objets
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function ajaxProfileCityList(){
    var jsonData = new Object();
    var cityArray = new Array();
    jsonData.urqid = 'pfl_cityList';
    jsonData.datas = {
        'inputCity':    htmlEntities($('#pfl_input_city').val())
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_pfl_citLis";
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                $.each(dataset, function(k, v){
                    cityArray.push(v.asciiname);
                });
            } catch(ex) {
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
    $('#pfl_city_spinner').hide();
    return cityArray;
}


/***************************/
/* AJAX PARTIE #2 - COMPTE */
/***************************/

function ajaxAccountParamsChecker(){
    var rValue = new Boolean();
    var jsonData = new Object();
    jsonData.urqid = 'pflAccountParams';
    jsonData.datas = {
        'pseudo':           htmlEntities($('#pfl_input_nickname').val()),
        'email':            htmlEntities($('#pfl_input_email').val()),
        'lang':             htmlEntities($('#pfl_lang option:selected').val()),
        'socialarea':       htmlEntities($('#pfl_input_socialarea').val())
    };
    var formURL = '../../__servers/serverAccount.php';
    
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                var dataset = JSON.parse(data);
                if(dataset.okForSending === true){
                    rValue = true;
                } else {
                    rValue = false;
                }
            } catch(ex) {
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
            rValue = false;
        }
    });
    return rValue;
}

function ajaxPseudoAlreadyTaken(){
    var rValue = new Boolean();
    var jsonData = new Object();
    jsonData.urqid = 'acc_pseudoAvailable';
    //jsonData.urqid = 'pflAccountPseudo';
    jsonData.datas = {
        'pseudo':           $('#pfl_input_nickname').val()
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_acc_psdAvaChk";
    
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined' && dataset.isAvailable === true){
                    rValue = true;
                } else if(typeof dataset !== 'undefined' && dataset.isAvailable === false) {
                    rValue = false;
                } else {
                    //TODO: Afficher un message d'erreur en FE
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
            rValue = false;
        }
    });
    return rValue;
}

function ajaxEmailAlreadyTaken(){
    var rValue = new Boolean();
    var jsonData = new Object();
    jsonData.urqid = 'acc_emailAvailable';
    jsonData.datas = {
        'email':           $('#pfl_input_email').val()
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_acc_emaAvaChk";
    
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                dataset = JSON.parse(data);
                if(dataset.isAvailable === true){
                    rValue = true;
                } else {
                    rValue = false;
                }
            } catch(ex){
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
            rValue = false;
        }
    });
    return rValue;
}

function ajaxAccountPasswdChecker(){
    var rValue = new Boolean();
    var jsonData = new Object();
    jsonData.urqid = 'pflAccountPasswd';
    jsonData.datas = {
        'oldpw':            $('#pfl_input_oldpw').val(),
        'newpw':            $('#pfl_input_newpw').val(),
        'newpwconf':        $('#pfl_input_newpwconf').val()
    };
    var formURL = '../../__servers/serverAccount.php';
    
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                var dataset = JSON.parse(data);
                if(dataset.okForSending === true){
                    rValue = true;
                } else {
                    rValue = false;
                }
            } catch(ex) {
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
            rValue = false;
        }
    });
    return rValue;
}

function ajaxAccountLoader(/*id*/){
    var dataset = null;
    var jsonData = new Object();
    jsonData.urqid = 'acc_accountLoader';
    jsonData.datas = {/*'id': parseInt(id)*/};
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&user="+psd+"&page=parametres&urqid=pfl_acc_load";
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                dataset = JSON.parse(data);
            } catch(ex){
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
    return dataset;
}

function ajaxAccountClassicSave(){
    var jsonData = new Object();
    jsonData.urqid = 'acc_accountClassicSave';
    jsonData.datas = {
        'accpseudo'         : $('#pfl_input_nickname').val(),
        'emailraw'          : $('#pfl_input_email').val(),
        'acclang'           : $('#pfl_lang').val(),
        'acc_socialarea'    : $('#pfl_input_socialarea').val(),
        //Utilisation de la variable globale pour le moment, faute de meilleure idée
        'aic'               : aic
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_acc_claSave";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    switch(dataset.status){
                        case true:
                            //tout est OK
                            //console.log('OK - Penser a reactiver le reload')
                            location.reload(true);
                            break;
                        case false:
                            //Il y a eu un problème - avertir l'utilisateur
                            console.log("PROBLEME");
                    }
                }
                //TODO (si j'ai le temps): Coder un signal de retour pour l'utilisateur lui confirmant la sauvegarde
                //Reload de la page une fois la sauvegarde effectuée
                //location.reload(true);
            } catch(ex) {
                console.log(ex);
            }
        },
        error   : function(jqXHR, errorThrown, textStatus){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function ajaxAccountPasswdSave(){
    var jsonData = new Object();
    jsonData.urqid = 'acc_accountPasswdSave';
    jsonData.datas = {
        'oldpw'     : $('#pfl_input_oldpw').val(),
        'newpw'     : $('#pfl_input_newpw').val(),
        'newpwconf' : $('#pfl_input_newpwconf').val()
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_acc_pwdSave";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                //TODO (si j'ai le temps): Coder un signal de retour pour l'utilisateur lui confirmant la sauvegarde
                //Reload de la page une fois la sauvegarde effectuée
                var dataset = JSON.parse(data);
                if(parseInt(dataset.ret) === 1){
                    location.reload(true);
                } else {
                    customAccountErrors(dataset.ret);
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error   : function(jqXHR, errorThrown, textStatus){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function ajaxTryaccountChecker(){
    var jsonData = new Object();
    jsonData.urqid = 'acc_tryaccountChecker';
    var formURL = '../../process_urq/profile_project/urq_sandboxprofile.php';
    $.ajax({
        //async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.isTryaccount === true){
                        $('#pfl_form_account_passwd').prepend(dataset.warning_block);
                    }
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error   : function(jqXHR, errorThrown, textStatus){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function ajaxTryaccountConverter(){
    var jsonData = new Object();
    jsonData.urqid = 'acc_tryaccountConverter';
    var formURL = '../../process_urq/profile_project/urq_sandboxprofile.php';
    $.ajax({
        //async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(){
            location.reload(true);
        },
        error   : function(jqXHR, errorThrown, textStatus){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function ajaxIsEmailConfirmed(){
    var jsonData = new Object();
    jsonData.urqid = 'acc_isEmailConfirmed';
    jsonData.datas = {
        //id
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_gen_confEmaChk";
    $.ajax({
        //async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,        
        success : function(data){
            try{
                dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.conf === false){
                        $(dataset.link).insertAfter('#pfl_input_email');
                        $(dataset.div).insertBefore('#pfl_content');
                    } else if(dataset.conf === true){
                        //Désactivation de l'encart rouge de confirmation lors de la sauvegarde
                        drc();
                    }
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error   : function(jqXHR, errorThrown, textStatus){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function ajaxResendConfEmail(db){    
    var jsonData = new Object();
    
    if(db === 'resend' || db ===''){
        //On renvoie au même email
        var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
        var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_acc_resConfEma";
        jsonData.urqid = 'acc_resendConfEmail';
        jsonData.datas = {
            //id
        };
        $.ajax({
            //async   : false,
            url     : formURL,
            type    : 'POST',
            data    : jsonData,
            success : function(data){
                try{
                    var dataset = JSON.parse(data);
                    if(typeof dataset !== 'undefined'){
                        if(dataset.ctrl === true){
                            //Tout s'est bien passé
                            console.log('ok');
                            //Reload le temps de rediriger
                            location.reload('true');
                        } else {
                            //Erreur
                            console.log('erreur: '+dataset.ctrl);
                        }
                    }
                } catch(ex) {
                    console.log(ex);
                }
            },
            error   : function(jqXHR, errorThrown, textStatus){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    } else if(db === 'newmail'){
        var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
        //On envoie sur le nouvel email
        var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_acc_sendConfToNewEma";
        jsonData.urqid = 'acc_sendConfToNewMail';
        jsonData.datas = {
            'newmail'   : $('#pfl_input_email').val()
        };
        $.ajax({
            async   : false,
            url     : formURL,
            type    : 'POST',
            data    : jsonData,
            success : function(data){
                try{
                    var dataset = JSON.parse(data);
                    if(typeof dataset !== 'undefined'){
                        if(dataset.ctrl === true){
                            //Tout s'est bien passé
                            console.log('ok');
                        } else {
                            //Erreur
                            console.log('erreur newmail: '+dataset.ctrl);
                        }
                    }
                } catch(ex) {
                    console.log(ex);
                }
            },
            error   : function(jqXHR, errorThrown, textStatus){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }
}

function ajaxHasEmailChanged(){
    var email = (typeof $('#pfl_input_email').val() === "undefined") ? null : $('#pfl_input_email').val();
    if(email === null){
        return;
    }
    var rVal;
    var jsonData = new Object();
    jsonData.urqid = 'acc_hasEmailChanged';
    jsonData.datas = {
        'email' : email
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_acc_hasEmaCha";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.changed === true || dataset.changed === false){
                        rVal = dataset.changed;
                    } else {
                        //TODO: Coder un message d'erreur pour l'utilisateur
                        console.log("PROBLEME");
                    }
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error   : function(jqXHR, errorThrown, textStatus){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
    return rVal;
}

/*****************************/
/* AJAX PARTIE #3 - SECURITE */
/*****************************/

function ajaxSecurityFormChecker(){
    var returnValue = new Boolean();
    var jsonData = new Object();
    jsonData.urqid = 'pflSecurity';
    jsonData.datas = {
        'stayconn' :        $('#pfl_input_stayconn').prop('checked'),
        'cowithpseudo' :    $('#pfl_input_cowithpseudo').prop('checked'),
        'thirdcriteria':    $('#pfl_input_thirdcriteria').prop('checked')
    };
    var formURL = '../../__servers/serverAccount.php';
    
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                var dataset = JSON.parse(data);
                if(dataset.okForSending === true){
                    returnValue = true;
                } else {
                    returnValue = false;
                }
            } catch(ex) {
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);   
            returnValue = false;
        }
    });
    return returnValue;
}

function ajaxSecurityFormLockChecker(){
    var returnValue = new Boolean();
    var jsonData = new Object();
    jsonData.urqid = 'pflSecurityLocks';
    jsonData.datas = {
        'hlock_start':      $('#hlock_start_hour').val() + ':' + $('#hlock_start_min').val(), // HH:MM
        'hlock_end':        $('#hlock_end_hour').val() + ':' + $('#hlock_end_min').val(),
        'dlock_start':      $('#dlock_start_month').val() + '-' + $('#dlock_start_day').val() + '-' + $('#dlock_start_year').val(), //MM-DD-YYYY
        'dlock_end':        $('#dlock_end_month').val() + '-' + $('#dlock_end_day').val() + '-' + $('#dlock_end_year').val(),
        'passwd':           $('#hidden_input_lock').val()
    };
    var formURL = '../../__servers/serverAccount.php';
    
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                var dataset = JSON.parse(data);
                if(dataset.okForSending === true){
                    returnValue = true;
                } else {
                    returnValue = false;
                }
            } catch(ex){
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);   
            returnValue = false;
        }
    });
    return returnValue;
}

// Reset HLOCK
function ajaxHlockReset(){
    var jsonData = new Object();
    jsonData.urqid = 'secu_hlock_reset';
    var formURL = '../../process_urq/profile_project/urq_sandboxprofile.php';
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.return === false){
                        console.log('something went wrong');
                    }
                } else {
                    console.log('something went wrong');
                }
                location.reload(true);
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);   
        }
    });
}

// Reset DLOCK
function ajaxDlockReset(){
    var jsonData = new Object();
    jsonData.urqid = 'secu_dlock_reset';
    var formURL = '../../process_urq/profile_project/urq_sandboxprofile.php';
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.return === false){
                        console.log('something went wrong');
                    }
                } else {
                    console.log('something went wrong');
                }
                location.reload(true);
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);   
        }
    });
}

function ajaxSecurityLoader(/*id*/){
    var dataset = null;
    var jsonData = new Object();
    jsonData.urqid = 'secu_securityLoader';
    jsonData.datas = {/*'id': id*/};
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_secu_load";
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                dataset = JSON.parse(data);
            } catch(ex) {
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);   
        }
    });
    return dataset;
}

function ajaxSecurityClassicSave(){
    var jsonData = new Object();
    jsonData.urqid = 'secu_securitySave';
    jsonData.datas = {
        'stayconn' :        $('#pfl_input_stayconn').prop('checked') ? '1' : '0',
        'cowithpseudo' :    $('#pfl_input_cowithpseudo').prop('checked') ? '1' : '0',
        'thirdcriteria':    $('#pfl_input_thirdcriteria').prop('checked') ? '1' : '0'
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_secu_save";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(parseInt(dataset.ret) === 1){
                        //all good
                        location.reload(true);
                    } else {
                        //error
                        console.log(dataset.ret);
                    }
                } else {
                    //erreur
                    console.log('ERREUR RETOUR JSON');
                }
            } catch(ex){
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);   
        }
    });
}

function ajaxSecurityLockSave(e){
    //TODO: Chercher à target le bon input
    if(e.type !== 'submit' | e.target.length !== 12){
        //tentative faible de bypass, mais il faut quand même la repérer
        //le e.target.length = 12 est une 'sécurité' supplémentaire mais qui peut
        //changer si la page subit des modifications.
        console.log('lightbypass');
        return;
    }
    var p = $('#hidden_input_pw').val();
    if(ajaxHiddenPwCheck(p) !== true){
        //tentative de passage en force avec bypass du password
        console.log('forcepass');
        return;
    }
    var jsonData = new Object();
    jsonData.urqid = 'secu_lockSave';
    jsonData.datas = {
        'secu_lock_h_start' : $('#hlock_start_hour').val() + ':' + $('#hlock_start_min').val(),
        'secu_lock_h_end'   : $('#hlock_end_hour').val() + ':' + $('#hlock_end_min').val(),
        'secu_lock_d_start' : $('#dlock_start_year').val() + '-' + $('#dlock_start_month').val() + '-' + $('#dlock_start_day').val(),
        'secu_lock_d_end'   : $('#dlock_end_year').val() + '-' + $('#dlock_end_month').val() + '-' + $('#dlock_end_day').val()
    };
    var formURL = '../../process_urq/profile_project/urq_sandboxprofile.php';
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.rt === null){
                        //all good
                        location.reload(true);
                    } else {
                        console.log(dataset.rt);
                    }
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);   
        }
    });
}

/*********************************************/
/* AJAX PARTIE #3BIS - SUPPRESSION DE COMPTE */
/*********************************************/

function ajaxDeleteAccount(){
    var jsonData = new Object();
    jsonData.urqid = 'del_deletionRequest';
    jsonData.datas = {
        'reason':       htmlEntities($('#pfl_delete_radio_group input[name=pfl_deactivation_reason]:checked').val()),
        /* Traitements de sécurité (mysql_real_escape_string et htmleltities) refaits en PHP */
        'detail':       htmlEntities($('#pfl_delete_reason_textarea').val()),
        'passwd':       htmlEntities($('#pfl_delete_code').val())
    };
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_del_req";
    
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(parseInt(dataset.rt) === 1){
                        //all good
                        location.reload(true);
                    } else {
                        //erreur
                        //TODO: FAIRE UN SWITCH AVEC LES CODES D'ERREURS POSSIBLE
                        console.log("ERREUR DELETE REQUEST");
                    }
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
}

function ajaxCancelDeleteRequest(){
    var jsonData = new Object();
    jsonData.urqid = 'del_cancelRequest';
    var psd = Kxlib_GetOwnerPgPropIfExist().upsd;
    var formURL = "http://www.trenqr.com/forrest/index.php?user="+psd+"&page=parametres&urqid=pfl_del_canDel";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(parseInt(dataset.rt) === 1){
                        //all good
                        location.reload(true);
                    } else {
                        //erreur
                        //TODO: FAIRE UN SWITCH AVEC LES CODES D'ERREURS POSSIBLE
                        console.log("ERREUR DELETE REQUEST");
                    }
                }
            } catch(ex) {
                console.log(ex);
            }
        },
        error   : function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
}

/************************************/
/* AJAX PARTIE #4 - COMPTES BLOQUÉS */
/************************************/

/* Récupération de la liste des comptes bloqués */
function ajaxBlockedAccounts(ll, hl){
    var dataset;
    var jsonData = new Object();
    jsonData.urqid = 'pflBlockedAccounts';
    jsonData.datas = {
        'lowLimit' : ll,
        'highLimit' : hl
    };
    var formURL = '../../__servers/serverAccount.php';
    
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        beforeSend: function(){
            $('#bloacc_listzone').append('<div id="bloacc_scroll_load">'+ Kxlib_getDolphinsValue("p_pfl_misc_loading") +'</div>');
        },
        success: function(data){
            try{
                //console.log(data);
                dataset = JSON.parse(data);
                //Simulation de loading:
                setTimeout(function(){
                    $('#bloacc_scroll_load').remove();
                }, 500);
            } catch(ex) {
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
    return dataset;
}

/* Récupération des infos du compte à débloquer */
function ajaxBloAccInfoFetch(accId){
    var dataset;
    var jsonData = new Object();
    jsonData.urqid = 'pflBloAccInfoFetch';
    jsonData.datas = {
        'accId':        parseInt(accId)
    };
    var formURL = '../../__servers/serverAccount.php';
    
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                dataset = JSON.parse(data);
            } catch(ex) {
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
    return dataset;
}

/* Suppression du statut 'bloqué' du compte */
/* /!\: Dans le .php qui sert de serveur, la notion de compte bloqué est un boolean
 * que j'ai en donnée brute dans mon tableau. Ce sera géré autrement en prod. Penser
 * à faire les modifications quand elles seront nécessaires. */
function ajaxUnblock(accid){
    var jsonData = new Object();
    jsonData.urqid = 'pflUnblock';
    jsonData.datas = {
        'accid':        parseInt(accid)
    };
    var formURL = '../../__servers/serverAccount.php';
    
    $.ajax({
        async: false,
        url: formURL,
        type: 'POST',
        data: jsonData,
        success: function(){/*console.log('Delete complete');*/},
        error: function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
}

/* Mise à jour de la raison de blocage d'un compte */
function updateReason(r, i){
     var jsonData = new Object();
     jsonData.urqid = 'blockedReasonUpdate';
     jsonData.datas = {
         'id':      parseInt(i),
         'reason':  r
     };
     
     $.ajax({
         async: false,
         url: '../../__servers/serverAccount.php',
         type: 'POST',
         data: jsonData,
         success: function(){
             /*console.log('Raison du blocage du compte '+ parseInt(i) +':');
             console.log(r);*/
         },
         error: function(jqXHR, textStatus, errorThrown){
             console.log(errorThrown);
             console.log(textStatus);
             console.log(jqXHR);
         }
     });
}

function ajaxBlockedAccSorting(t, o){
    var dataset;
    var jsonData = new Object();
    jsonData.urqid = 'blockedSortingType';
    jsonData.datas = {
        'type':     t,
        'orderby':  o
    };
    
    $.ajax({
        async: false,
        url: '../../__servers/serverAccount.php',
        type: 'POST',
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                //Le type de retour est un tableau de tableaux JSON.
                dataset = JSON.parse(data);
            } catch(ex) {
                //stuff
            }
        }
    });
    return dataset;
}
