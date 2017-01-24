/**************************************************/
/* Test AJAX pour la récupération de mot de passe */
/**************************************************/

/* Fonction de communication */
function ajaxRecoveryEmailChecker(e){
    $('#rec_spinner').show();
    //e.preventDefault();
    var jsonData = {};
    jsonData.urqid = "rp_recoveryMailExists";
    jsonData.datas = {
        "email" : $('#rec_form_email').val()
    };
    var formURL = "http://www.trenqr.com/forrest/index.php?page=recuperation&urqid=rp_recEmaExi";
    var rec_locker = false;
    $.ajax({
        async: false,
        url: formURL,
        type: "POST",
        data: jsonData,
        success: function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    if(dataset.okForRecovery === false){
                        recErrorManager(3);
                        rec_locker = false;
                    } else {
                        rec_locker = true;
                    }
                } else {
                    recErrorManager(4);
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
        }
    });
    
    $('#rec_spinner').hide();
    return rec_locker;
}

function ajaxRecoveryEmailSender(em){
    var jsonData = new Object();
    jsonData.urqid = 'rp_recoverySendMail';
    jsonData.datas = {
        'em'    : em
    };
    var formURL = 'http://www.trenqr.com/forrest/index.php?page=recuperation&urqid=rp_recSenEma';
    $.ajax({
        async   : false,
        url     : formURL,
        type    : 'POST',
        data    : jsonData,
        success : function(data){
            try{
                var dataset = JSON.parse(data);
                if(typeof dataset !== 'undefined'){
                    console.log(dataset);
                } else {
                    console.log('erreur dans l\'json');
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