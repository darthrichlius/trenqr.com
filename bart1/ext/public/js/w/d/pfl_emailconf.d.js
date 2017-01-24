$(document).ready(function(){
    var g = getKey();
    ajaxConfirmEmail(g);
});

/***************************/

function getKey() {
    var obj = {};
    var params = location.search.split('&ups=');
    var keyVal = params[1].split('=');
    obj[decodeURIComponent(keyVal[0])] = decodeURIComponent(keyVal[1]);
    return obj;
}

function ajaxConfirmEmail(g){
    var jsonData = new Object();
    jsonData.urqid = 'emaconf';
    jsonData.datas = {
        //'email' : g.email,
        'key'   : g.k
    };
    var formURL = 'http://www.trenqr.com/forrest/index.php?page=confirm&urqid=pfl_email_confEma';
    
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    displaySelector(dataset.r);
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

function displaySelector(input){
    switch(input){
        case 'EMACONF_DNE_OR_EXPIRED':
            $('#pfl_emailconf_txt').html(Kxlib_getDolphinsValue("p_emaconf_dne_or_exp"));
            break;
        case 'EMACONF_ALREADYCONF':
            $('#pfl_emailconf_txt').html(Kxlib_getDolphinsValue("p_emaconf_alreadyconf"));
            break;
        case false:
            $('#pfl_emailconf_txt').html(Kxlib_getDolphinsValue("p_emaconf_default_error"));
            break;
        case true:
            $('#pfl_emailconf_txt').html(Kxlib_getDolphinsValue("p_emaconf_ok"));
            break;
    }
}