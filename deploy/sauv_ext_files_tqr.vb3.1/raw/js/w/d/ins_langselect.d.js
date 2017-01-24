/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Hauteur de la div contenant les langues
var langHeight = $('#ins_langlist').height();

//Hauteur du 'sélecteur'
var selectHeight = $('#ins_lang').height();
    
//Calcul du décalage top nécessaire pour l'animation 'drop-up'
var topOffset = langHeight - selectHeight;  

//Etat du menu | 0 = Menu fermé, 1 = Menu ouvert
var state = 0;


$('#ins_lang').click(function(){
    if(state === 0){
        $('#ins_lang').stop();
        $('#ins_lang').animate({
            height: langHeight
        });
        state = 1; 
        $('#ins_langplus').css('visibility', 'hidden');
    } else if (state === 1){
        $('#ins_lang').stop();
        $('#ins_lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('#ins_langplus').css('visibility', 'visible');
        });
        state = 0;  
    }
});

$(document).click(function(e){
    if($(e.target).is('#ins_lang, #ins_lang *')){
        return;
    } else {
        $('#ins_lang').stop();
        $('#ins_lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('#ins_langplus').css('visibility', 'visible');
        });
        state = 0;
    }
});


//L'utilisateur ne peut pas cliquer sur sa langue actuelle
$('.ins_lang_current').click(function(e){
    e.preventDefault();
});