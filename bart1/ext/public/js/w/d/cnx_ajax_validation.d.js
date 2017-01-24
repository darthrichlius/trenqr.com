/*******************************/
/* Test AJAX pour la connexion */
/*******************************/

/* Fonction de communication */
function ajaxConnectPage(){
    //Récupération de l'heure locale, on en aura besoin
    var date = new Date;
    var s = date.getSeconds();
    var m = date.getMinutes();
    var h = date.getHours();
    
    //e.preventDefault();
    var connectPageLocker; //true = all good | false = locked
    var lt;
    
    var jsonData = {};
    jsonData.urqid = "cp_connectPageSubmit";
    
    //Obligé de faire la distinction à ce niveau là parce qu'en PHP le check se fait sur un isset(); de birthday.
    //Or si on ne faisait pas le distinguo ici on se retrouverai avec isset(); true mais avec des 'init' dedans.
    if($('#cnx_day').length && !$('#cnx_form_superpw').length){
        jsonData.datas = {
            "timeofday" : h + ':' + m + ':' + s,
            "login"     : $('#cnx_form_login_input').val(),
            "passwd"    : $('#cnx_form_passwd_input').val(),
            "staycon"   : $('#cnx_session_checkbox').prop('checked'),
            "birthday"  : $('#cnx_day').val() + '-' + $('#cnx_month').val() + '-' + $('#cnx_year').val(),
            "locktype"  : 'dob'
        };
        lt = 'dob';
    } else if($('#cnx_day').length && $('#cnx_form_superpw').length){
        jsonData.datas = {
            "timeofday" : h + ':' + m + ':' + s,
            "login"     : $('#cnx_form_login_input').val(),
            "passwd"    : $('#cnx_form_passwd_input').val(),
            "staycon"   : $('#cnx_session_checkbox').prop('checked'),
            "birthday"  : $('#cnx_day').val() + '-' + $('#cnx_month').val() + '-' + $('#cnx_year').val(),
            "superpw"   : $('#cnx_form_superpw').val(),
            "locktype"  : 'full'
        };
        lt = 'full';
    } else {
        jsonData.datas = {
            "timeofday" : h + ':' + m + ':' + s,
            "login"     : $('#cnx_form_login_input').val(),
            "passwd"    : $('#cnx_form_passwd_input').val(),
            "staycon"   : $('#cnx_session_checkbox').prop('checked'),
            "locktype"  : 'min'
        };
        
        lt = 'min';
    }
    
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signin&urqid=cp_cnxPrcs";
    
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                var dataset = JSON.parse(data);
                if(dataset.connected === false){
                    $('#cnx_msg > p').html(Kxlib_getDolphinsValue(dataset.err));
                    $('#cnx_form_login_input').addClass('error_border');
                    $('#cnx_form_passwd_input').addClass('error_border');
                    if($('#cnx_form_superpw').length){$('#cnx_form_superpw').addClass('error_border');}
                    if($('#cnx_day').length){
                        $('#cnx_day').addClass('error_border');
                        $('#cnx_month').addClass('error_border');
                        $('#cnx_year').addClass('error_border');
                    }
                    connectPageLocker = false;
                } else if(dataset.connected === true){
                    $('#cnx_msg > p').html(Kxlib_getDolphinsValue("p_cnx_default"));
                    $('#cnx_form_login_input').removeClass('error_border');
                    $('#cnx_form_passwd_input').removeClass('error_border');
                    
                    tdl = ajaxCnxTodeleteCheck();
                    
                    if(tdl === true){
                        connectPageLocker = true;
                    } else if(tdl === -1){
                        $('#cnx_msg > p').html(Kxlib_getDolphinsValue("p_cnx_srv_err"));
                        $('#cnx_msg').css('border-color', 'red');
                        $('#cnx_msg').css('background-color', 'pink');
                    } else {
                        connectPageLocker = 'deletedacc';
                    }
                } else {
                    $('#cnx_msg > p').html(Kxlib_getDolphinsValue("p_err_cnx_unknown"));
                    $('#cnx_msg').css('border-color', 'red');
                    $('#cnx_msg').css('background-color', 'pink');
                    connectPageLocker = false;
                }
            } catch(ex){
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
    
    return [connectPageLocker,lt];
}

function ajaxCnxIsThirdCrit(){
    var rtc = false;
    var jsonData = new Object();
    jsonData.urqid = 'cp_thirdCritCheck';
    jsonData.datas = {
        'login' : $('#cnx_form_login_input').val()
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signin&urqid=cp_thiCrChk";
    
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
                    if(dataset.enabled === true && dataset.htmlblock !== "THICRCHK_ERR"){
                        //cnxBirthdayDisplay();
                        var htmlblock = dataset.htmlblock;
                        if($('#cnx_birthday_group').length === 0){
                            $('#cnx_submit_wrapper').prepend(htmlblock);
                        }
                        rtc = true;
                    } else if(dataset.enabled === true && dataset.htmlblock === "THICRCHK_ERR"){
                        rtc = -1;
                    } else {
                        rtc = false;
                        //cnxBirthdayHide();
                    }
                }
                $('#cnx_login_spinner').hide();
            } catch(ex) {
                console.log(ex);
            }
        }
    });
    return rtc;
}

/* Fonction de vérification de l'appartenance à un supergroupe */
function ajaxCnxSupergroupChecker(){
    var sgc = false;
    var jsonData = new Object();
    jsonData.urqid = 'cp_supergroupUserCheck';
    jsonData.datas = {
        'login' : $('#cnx_form_login_input').val()
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signin&urqid=cp_spgUsChk";
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
                    if(htmlblock === 'SPGUSCHK_ERR'){
                        sgc = -1;
                    } else {
                        if($('#cnx_supergroup').length === 0){
                            $('#cnx_submit_wrapper').prepend(htmlblock);
                        }
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
                }
            } catch(ex){
                console.log(ex);
            }
        }
    });
    return sgc;
}

function ajaxCnxTodeleteCheck(){
    var rVal;
    var jsonData = new Object();
    jsonData.urqid = 'cp_todeleteAccountCheck';
    jsonData.datas = {
        'login' : $('#cnx_form_login_input').val()
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signin&urqid=cp_toDeChk";
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
                        var path = 'http://ext.ycgkit.com/public/css/w/d/delcancel_overlay.d.css';
                        var link = document.createElement('link');
                        link.setAttribute('rel', 'stylesheet');
                        link.setAttribute('type', 'text/css');
                        link.setAttribute('href', path);
                        document.getElementsByTagName('head')[0].appendChild(link);
                        
                        //Récupération du HTML
                        var overlay = dataset.overlay;
                        if(overlay !== "DELACCOVERLAY_ERR"){
                            $('#cnx_screensize').append(overlay);
                            //Show l'overlay
                            if($(document).height() >= $(window).height()){
                                $('.theater').css('height', $(document).height());
                            } else {
                                $('.theater').css('height', $(window).height());
                            }
                            $('.theater').fadeIn();
                            $('#delcancel_container').fadeIn();
                            rVal = false;
                        } else if(overlay === "DELACCOVERLAY_ERR") {
                            rVal = -1;
                        }
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

function ajaxCnxTodeleteCancel(login){
    var gc = ajaxCnxGCChecker();
    if(gc !== true){
        return;
    }
    var jsonData = new Object();
    jsonData.urqid = 'cp_todeleteAccountCancel';
    jsonData.datas = {
        'login' : login
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signin&urqid=cp_toDeAcCan";
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.er !== 'ras'){
                        //Erreur côté serveur
                        $('#delcancel_title').html();
                        $('#delcancel_linkzone').empty();
                        $('#delcancel_txt_zone').html(Kxlib_getDolphinsValue("p_cnx_srv_err"));
                        $('#delcancel_txt_zone').css('color', 'red');
                        $('#delcancel_txt_zone').css('font-weight', 'bold');
                    } else {
                        //Si on arrive ici, c'est qu'à priori tout c'est bien passé et que l'user a cancel la suppression de son compte
                        //On peut donc le log. Pour ça, on simule un clic sur le login:
                        $('#cnx_submit').trigger('click');
                        ajaxConnectPage();
                    }
                }
            } catch(ex) {
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

function ajaxCnxAccountExists(login){
    var rt;
    var jsonData = new Object();
    jsonData.urqid = 'cp_accountExists';
    jsonData.datas = {
        'login' : login
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signin&urqid=cp_accExs";
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

function ajaxCnxGCChecker(){
    var ret = false;
    var jsonData = new Object();
    jsonData.urqid = 'cp_cnxGCChecker';
    jsonData.datas = {
        'login'     : $('#cnx_form_login_input').val(),
        'passwd'    : $('#cnx_form_passwd_input').val()
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signin&urqid=cp_cnxGcChk";
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