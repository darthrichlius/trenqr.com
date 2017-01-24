/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Hauteur de la div contenant les langues
var langHeight = $('#recch_langlist').height();

//Hauteur du 'sélecteur'
var selectHeight = $('#recch_lang').height();
    
//Calcul du décalage top nécessaire pour l'animation 'drop-up'
var topOffset = langHeight - selectHeight;  

//Etat du menu | 0 = Menu fermé, 1 = Menu ouvert
var state = 0;


$('#recch_lang').click(function(){
    if(state === 0){
        $('#recch_lang').stop();
        $('#recch_lang').animate({
            height: langHeight
        });
        state = 1; 
        $('#recch_langplus').css('visibility', 'hidden');
    } else if (state === 1){
        $('#recch_lang').stop();
        $('#recch_lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('#recch_langplus').css('visibility', 'visible');
        });
        state = 0;  
    }
});

$(document).click(function(e){
    if($(e.target).is('#recch_lang, #recch_lang *')){
        return;
    } else {
        $('#recch_lang').stop();
        $('#recch_lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('#recch_langplus').css('visibility', 'visible');
        });
        state = 0;
    }
});


//L'utilisateur ne peut pas cliquer sur sa langue actuelle
$('.recch_lang_current').click(function(e){
    e.preventDefault();
});