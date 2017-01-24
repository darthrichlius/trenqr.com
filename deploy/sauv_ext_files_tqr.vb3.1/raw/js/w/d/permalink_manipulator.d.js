$(document).ready(function(){
    //Ouverture de la question par un trigger de click();
    $('.faq_q').trigger('click');
    
    //Gestion du bon 'current'
    var dataset;
    var jsonData = new Object();
    jsonData.urqid = 'subcat';
    jsonData.datas = {
        'id': parseInt($('.faq_q').prop('id'))
    };
    
    /* Ajax nécessaire ? Essayer un simple JSON.parse */
    $.ajax({
        asyn: false,
        url: 'serverPermalink.php',
        type: 'POST',
        data: jsonData,
        success: function(data){
            dataset = JSON.parse(data);
            var eqLoc = parseInt(dataset.place);
            $('#faq_left_menu a li:eq('+ (eqLoc - 1) +')').addClass('faq_left_item_current');
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
    
});