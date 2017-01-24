/****************************************************/
/* Test AJAX pour la saisie du nouveau mot de passe */
/****************************************************/

//Fonction de récupération de la clé
function getKey() {
    var obj = {};
    var params = location.search.split('&ups=');
    var keyVal = params[1].split('=');
    obj[decodeURIComponent(keyVal[0])] = decodeURIComponent(keyVal[1]);
    return obj;
}

/* Fonction de communication */
function ajaxRecoveryPasswordChanger(){
    
    var k = getKey().k;
    
    var rtn = true;
    var jsonData = {};
    jsonData.urqid = "rpc_recoveryChangePasswd";
    jsonData.datas = {
        "newPasswd" : $('#recch_passwd').val(),
        "key" : k
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=recovery_change&urqid=rpc_recChaPas";
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.confirmation === false){
                        rtn = false;
                    }
                } else {
                    console.log('erreur json');
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
    return rtn;
}

function ajaxCancelRecovery(){
    var jsonData = new Object();
    jsonData.urqid = 'rpc_cancelRecovery';
    jsonData.datas = {
        'key'   : $('#recch_key').val()
    };
    var formURL = "../../process_urq/welcome_project/urq_sandbox.php";
    $.ajax({
        url : formURL,
        type: 'POST',
        data: jsonData,
    });
}