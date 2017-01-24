/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    var offset = 220;
    var duration = 250;
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('#home1_backToTop').stop(true, true);
            $('#home1_backToTop').fadeIn(duration);
        } else {
            $('#home1_backToTop').stop(true, true);
            $('#home1_backToTop').fadeOut(duration);
        }
    });
    
    $('#home1_backToTop').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, 500);
        return false;
    });
});