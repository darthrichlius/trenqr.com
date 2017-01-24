//Hauteur de la div contenant les langues
var langHeight = $('.langlist').height();

//Hauteur du 'sélecteur'
var selectHeight = $('.lang').height();
    
//Calcul du décalage top nécessaire pour l'animation 'drop-up'
var topOffset = langHeight - selectHeight;  

//Etat du menu | 0 = Menu fermé, 1 = Menu ouvert
var state = 0;


$('.lang').click(function(){
    if(state === 0){
        $('.lang').stop();
        $('.lang').animate({
            height: langHeight
        });
        state = 1; 
        $('.langplus').css('visibility', 'hidden');
    } else if (state === 1){
        $('.lang').stop();
        $('.lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('.langplus').css('visibility', 'visible');
        });
        state = 0;  
    }
});

$(document).click(function(e){
    if($(e.target).is('.lang, .lang *')){
        return;
    } else {
        $('.lang').stop();
        $('.lang').animate({
            height: selectHeight,
            top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
        }, function(){
            $('.langplus').css('visibility', 'visible');
        });
        state = 0;
    }
});


//L'utilisateur ne peut pas cliquer sur sa langue actuelle
$('.lang_current').click(function(e){
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