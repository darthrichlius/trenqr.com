/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* Fonction d'affichage du pseudo depuis la base */
/*function nicknameSuggestion(obj){
    $.each(obj, function(key, value){
        //alert(obj[key]);
    });
}*/



/******************************/
/* Test AJAX sur champ Pseudo */
/******************************/

//Déclenchement de la fonction la première fois (sur .blur());
/*$('#ins_input_nickname').blur(function(e){
    console.log('t0t0')
    var writtenNickname = $('#ins_input_nickname').val();
    suggestionManager(writtenNickname);
    ajaxNicknameChecker(e);
});*/

//Compteur pour savoir quelle partie de fonction appeller dans le validator
var checkerCount = 0;


function ajaxNicknameTakenShort(e){
    var rt;
    var jsonData = new Object();
    jsonData.urqid = 'sup_pseudoAvailabilityCheck';
    jsonData.datas = {
        'pseudo'    : $('#ins_input_nickname').val()
    };
    var formURL = 'http://www.trenqr.com/forrest/index.php?page=signup&urqid=sup_psdAvaChk';
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    rt =  dataset.taken;
                }
            } catch(ex) {
                console.log(ex);
            }
        }
    });
    return rt;
}

function ajaxNicknameChecker(e){    
    var jsonData = {};
    jsonData.urqid = "sup_pseudoAvailabilityCheck";
    jsonData.datas = {
        "pseudo" : ins_suggestion
    }; 
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signup&urqid=sup_psdAvaChk";
    //var formURL = "../../__servers/ServerSignup.php";
    //e.preventDefault();
    
    $.ajax(
    {
        async: false,
        url : formURL,
        type : "POST",
        data : jsonData,
        success:function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.taken === true){
                        checkerCount++;
                        ajaxNicknameCheckerYY(e);
                    } else if(dataset.taken === 'regex_error'){
                        ins_nickname_check(ins_suggestion);
                    } else {
                        ins_nickname_generator(ins_suggestion, checkerCount);
                        checkerCount = 0;
                    }
                } else {
                    console.log('erreur parsing json');
                }
            } catch(ex){
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            console.log("Error ?");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
}

function ajaxNicknameCheckerYY(e){
    var jsonData = {};
    jsonData.urqid = "sup_pseudoAvailabilityCheck";
    jsonData.datas = {
        "pseudo" : ins_suggestion_yy
    }; 
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signup&urqid=sup_psdAvaChk";
    //e.preventDefault();
    
    $.ajax(
            {
                async: false,
                url: formURL,
                type: "POST",
                data: jsonData,
                success: function(data){
                    try{
                        var dataset = JSON.parse(data);
                        if(dataset.taken === false){
                            ins_nickname_generator(ins_suggestion, checkerCount);
                            checkerCount = 0;
                        } else {
                            checkerCount++;
                            ajaxNicknameCheckerYYYY(e);
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
                }
                
            });
}

function ajaxNicknameCheckerYYYY(e){
    var jsonData = {};
    jsonData.urqid = "sup_pseudoAvailabilityCheck";
    jsonData.datas = {
        "pseudo" : ins_suggestion_yyyy
    }; 
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signup&urqid=sup_psdAvaChk";
    //e.preventDefault();
    
    $.ajax(
            {
                async: false,
                url: formURL,
                type: "POST",
                data: jsonData,
                success: function(data){
                    try{
                        var dataset = JSON.parse(data);
                        if(dataset.taken === false){
                            ins_nickname_generator(ins_suggestion, checkerCount);
                            checkerCount = 0;
                        } else {
                            checkerCount++;
                            ins_nickname_generator(ins_suggestion, checkerCount);
                            checkerCount = 0;
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
                }
                
            });
}

/****************************************/
/* Tests AJAX sur le formulaire complet */
/****************************************/

function ajaxFormChecker(e){
    var jsonData = {};
    //Variable de lock. true = tout est bon | false = erreur quelque part
    var ins_locker = false;
    
    jsonData.urqid = "insFormChecker";
    jsonData.datas = {
        "fullname" : $('#ins_input_fullname').val(),
        //Date au format américain: MM-DD-YYYY
        "birthday" : $('#month').val() + '-' + $('#day').val() + '-' + $('#year').val(),
        "gender" : $('input[name=gender]:checked').val(),
        "city" : $('#ins_input_city').val(),
        "nickname" : $('#ins_input_nickname').val(),
        "mail" : $('#ins_input_mail').val(),
        "passwd" : $('#ins_input_passwd').val(),
        "mainComputer" : $('#box_main_computer').prop('checked'),
        "cgu" : $('#ins_cgu').prop('checked')
    };
    var formURL = "../../__servers/ServerSignup.php";
    
    //e.preventDefault();
    
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                //console.log(data);
                var dataset = JSON.parse(data);
                var tab = new Array();
                $.each(dataset, function(k, v){
                    tab.push(v);
                });

                if(dataset.formValidation !== true){
                    ins_locker = false;
                } else {
                    ins_locker = true;
                }
            }catch(ex){
                
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log("Error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);    
        }
    });
    
    return ins_locker;
}

/******************************************************************************/
/* Test AJAX sur la présence de l'email de pré-inscription en base et utilisé */
/******************************************************************************/

function ajaxInsMailStateChecker(e){
    var rt;
    var jsonData = new Object();
    jsonData.urqid = "sup_emailAvailabilityCheck";
    jsonData.datas = {
        "email": $('#ins_input_mail').val()
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=signup&urqid=sup_emaAvaChk";
    
    $.ajax({
        //async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    rt = dataset.available;
                    if(rt === false){
                        //insKnownUserOverlay();
                        insErrorMsg(Kxlib_getDolphinsValue("p_ins_err_emailalreadyused"));
                        insErrorBox();
                        $('#ins_input_mail').data('su', 'lock');
                        $('#ins_input_mail').addClass('ins_error_border');
                    }
                }
            }catch(ex){
                console.log(ex);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Error');
            console.log(errorThrown);
            console.log(textStatus);            
        }
    });
    $('#ins_email_spinner').hide();
    return rt;
}

function ajaxInsPreregMailChecker(){
    var key;
    var jsonData = new Object();
    jsonData.urqid = 'sup_detectCurrentPrereg';
    jsonData.datas = {
        'email' : $('#ins_input_mail').val()
    };
    var formURL = '../../process_urq/welcome_project/urq_sandbox.php';
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    key = dataset.k;
                }
            } catch(ex){
                console.log(ex);
            }
        }
    });
    return key;
}


/*****************************/
/* AJAX - AUTOCOMPLETE VILLE */
/*****************************/
function ajaxCitySuggestion(){
    var suggestionArray = new Array();
    var jsonData = new Object();
    jsonData.urqid = 'sup_citySuggestion';
    jsonData.datas = {
        'input': $('#ins_input_city').val()
    };
    var formURL = 'http://www.trenqr.com/forrest/index.php?page=signup&urqid=sup_citSug';

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

                //setTimeout(function(){
                insCityAutocomplete(suggestionArray);          //On envoie à l'autocomplete le tableau d'objets
            }catch(ex){
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function ajaxCityList(){
    var jsonData = new Object();
    var cityArray = new Array();
    jsonData.urqid = 'sup_cityList';
    jsonData.datas = {
        'inputCity':    $('#ins_input_city').val()
    };
    var formURL = 'http://www.trenqr.com/forrest/index.php?page=signup&urqid=sup_citLis';
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
            }catch(ex){
                //stuff
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
    
    $('#ins_city_spinner').hide();
    return cityArray;
}


function ajaxCreateAccount(){
    var soar;
    if($('#ins_socialarea').val() === ''){
        soar = null;
    } else {
        soar = $('#ins_socialarea').val();
    }
    
    var jsonData = new Object();
    jsonData.urqid = 'sup_createAccount';
    jsonData.datas = {
        "fullname" : $('#ins_input_fullname').val(),
        "birthday" : $('#year').val() + '-' + $('#month').val() + '-' + $('#day').val(),
        "gender" : $('input[name=gender]:checked').val(),
        "cityId" : $('#ins_input_city').data('cc'),
        "pseudo" : $('#ins_input_nickname').val(),
        "email" : $('#ins_input_mail').val(),
        "passwd" : $('#ins_input_passwd').val(),
        "mainComputer" : $('#box_main_computer').prop('checked'),
        "cgu" : $('#ins_cgu').prop('checked'),
        "acclang" : $('.lang_current').data('lang'),
        "acc_socialarea" : soar,
        "acctype" : $('#tryacc_creation').length > 0 ? 'ta' : 'a'
    };
    var formURL = 'http://www.trenqr.com/forrest/index.php?page=signup&urqid=sup_creAcc';
    $.ajax({
        async   : true,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    //TEMPORAIRE
                    if(dataset.status !== '1'){
                        console.log(dataset.status);
                    }
                }
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

function ajaxReportInscription(){
    var jsonData = new Object();
    jsonData.urqid = 'sup_reportInscription';
    jsonData.datas = {
        //Minimum requis
        'fullname'  : $('#ins_input_fullname').val(),
        'pseudo'    : $('#ins_input_nickname').val(),
        'email'     : $('#ins_input_mail').val(),
        'passwd'    : $('#ins_input_passwd').val(),
        //Le reste
        'birthday'  : $('#year').val() + '-' + $('#month').val() + '-' + $('#day').val(),
        'gender'    : $('input[name=gender]:checked').val(),
        'cityId'    : $('#ins_input_city').data('cc')
    };
    var formURL = '../../process_urq/welcome_project/urq_sandbox.php';
    $.ajax({
        url     : formURL,
        type    : 'POST',
        data    : jsonData
    });
}