/* On load les données présentes en base pour les insérer dans nos champs */

function preregLoader(id){
    var jsonData = new Object();
    jsonData.urqid = 'insLoader';
    jsonData.datas = {'id' : id};
    $.ajax({
        async: false,
        url: '../../__servers/serverHomepage.php',
        type: 'POST',
        data: jsonData,
        success: function(data){
            console.log(data);
            var dataset = JSON.parse(data);
            if(dataset.fullname !== ''){
                $('#ins_input_fullname').val(dataset.fullname);
            }
            if(dataset.nickname !== ''){
                $('#ins_input_nickname').val(dataset.nickname);
            }
            if(dataset.email !== ''){
                $('#ins_input_mail').val(dataset.email);
            }
            if(dataset.password !== ''){
                $('#ins_input_passwd').val(dataset.password);
                passwdStrCheck($('#ins_input_passwd'));
                ins_passwd_check($('#ins_input_passwd').val());
            }
            if(dataset.birthday !== ''){
                var bd = dataset.birthday.split('-');
                $('#day').val(bd[1]);
                $('#month').val(bd[0]);
                $('#year').val(bd[2]);
            }
            if(dataset.gender !== ''){
                if(dataset.gender === 'm'){
                    $('#ins_radio_m').prop('checked', 'true');
                } else if(dataset.gender === 'f'){
                    $('#ins_radio_f').prop('checked', 'false');
                }
            }
            if(dataset.city !== ''){
                $('#ins_input_city').val(dataset.city);
            }
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
            console.log(textStatus);
            console.log(jqXHR);
        }
    });
}


$(document).ready(function(){
        preregLoader(1);    //ID en dur pour le moment, ofc.
    });