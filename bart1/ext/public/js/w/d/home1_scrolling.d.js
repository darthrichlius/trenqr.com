/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('#home1_locker').click(function(){ 
    //Vérification pour le on/off
    if ($('#home1_locker').data('st') === "undone"){
        
        //Changement de background du bouton et unlock de la page
        $('#home1').css('padding', '0 0 50px 0');
        $('#home1_locker').css('background', 'url("/bart1/timg/files/img/w/c_open.jpg")');
        $('#home1_scrolllock').css('overflow', 'visible');
        
        //Smooth scroll sur une partie de la p2
        $('html, body').stop(true).animate({
            scrollTop: 215
        }, 1000);
        $('#home1_locker').data('st', 'done');    
        
    } else if ($('#home1_locker').data('st') === "done"){
                
        $('html, body').stop(true).animate({
            scrollTop: 0
        }, 1000, function(){$('#home1_scrolllock').css('overflow', 'hidden'); $('#home1').css('padding', '0 0 4px 0')});
        $('#home1_locker').data('st', 'undone');  
        
        //Rechangement du background et relock de la page.
        $('#home1_locker').css('background', 'url("/bart1/timg/files/img/w/c_lock.jpg")');
    }
});