/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Hauteur de la div contenant les langues
var langHeight = $('#rec_langlist').height();

//Hauteur du 'sélecteur'
var selectHeight = $('#rec_lang').height();
    
//Calcul du décalage top nécessaire pour l'animation 'drop-up'
var topOffset = langHeight - selectHeight;  

//Etat du menu | 0 = Menu fermé, 1 = Menu ouvert
var state = 0;


$('#rec_lang').click(function(){
    if(state === 0){
        $('#rec_lang').stop();
        $('#rec_lang').animate({
            height: langHeight
        });
        state = 1; 
        $('#rec_langplus').css('visibility', 'hidden');
    } else if (state === 1){
        $('#rec_lang').stop();
        $('#rec_lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('#rec_langplus').css('visibility', 'visible');
        });
        state = 0;  
    }
});

$(document).click(function(e){
    if($(e.target).is('#rec_lang, #rec_lang *')){
        return;
    } else {
        $('#rec_lang').stop();
        $('#rec_lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('#rec_langplus').css('visibility', 'visible');
        });
        state = 0;
    }
});


//L'utilisateur ne peut pas cliquer sur sa langue actuelle
$('.rec_lang_current').click(function(e){
    e.preventDefault();
});