/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Hauteur de la div contenant les langues
var langHeight = $('#home1_langlist').height();

//Hauteur du 'sélecteur'
var selectHeight = $('#home1_lang').height();
    
//Calcul du décalage top nécessaire pour l'animation 'drop-up'
var topOffset = langHeight - selectHeight;  

//Etat du menu | 0 = Menu fermé, 1 = Menu ouvert
var state = 0;


$('#home1_lang').click(function(){
    if(state === 0){
        $('#home1_lang').stop();
        $('#home1_lang').animate({
            height: langHeight,
            top: -topOffset
        });
        state = 1; 
        $('#home1_langplus').css('visibility', 'hidden');
    } else if (state === 1){
        $('#home1_lang').stop();
        $('#home1_lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('#home1_langplus').css('visibility', 'visible');
        });
        state = 0;  
    }
});

$(document).click(function(e){
    if($(e.target).is('#home1_lang, #home1_lang *')){
        return;
    } else {
        $('#home1_lang').stop();
        $('#home1_lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('#home1_langplus').css('visibility', 'visible');
        });
        state = 0;
    }
});


//L'utilisateur ne peut pas cliquer sur sa langue actuelle
$('.home1_lang_current').click(function(e){
    e.preventDefault();
});

/* * * * * * * * * * * */
/* Gestion de .theater */
/* * * * * * * * * * * */

$(document).ready(function(){
    if($(document).height() >= $(window).height()){
        $('.theater').css('height', $(document).height());
    } else {
        $('.theater').css('height', $(window).height());
    }
});