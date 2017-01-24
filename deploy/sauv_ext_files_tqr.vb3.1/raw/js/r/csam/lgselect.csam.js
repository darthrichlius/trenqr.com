/*
 * Permet de gérer les changements de langues de navigation.
 * Le module est surtout utilisé pour les cas de page Welcome.
 * 
 * Il reprend un travail précédent et l'améliore.
 */

function LGSELECT () {
   
    var inscz = ( $('#home1_langlist').length ) ? true : false;
   
    //Hauteur de la div contenant les langues
    var $lgSlH = ( inscz ) ? $('#home1_langlist') : $(".langlist") ;
    var lgH = $($lgSlH).height();

    //Hauteur du 'sélecteur'
    var $slctSl = ( inscz ) ? $('#home1_lang') : $(".lang");
    var slctH = $($slctSl).height();
            
    //Calcul du décalage top nécessaire pour l'animation 'drop-up'
    var topOffset = lgH - slctH;  

    //Etat du menu | 0 = Menu fermé, 1 = Menu ouvert
    var stt = 0;

    /*********************************************************************************************************************************/
    /********************************************************* PROCESS SCOPE *********************************************************/
    /*********************************************************************************************************************************/
    
    var _f_Gdf = function ()  {
        var ds = {
            //AsdAppsAction
            "langs" : ["fr","en"]
        };
        
        return ds;
    };
    
    var _f_Open = function () {
        try {
            
            $slctSl.stop();
            $slctSl.animate({
                height: lgH,
                top: (inscz) ? -topOffset : "auto"
            });
            stt = 1; 
            $("#home1_langplus, .langplus").css("visibility", "hidden");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Close = function () {
        try {
            
            $slctSl.stop();
            $slctSl.animate({
                height: slctH,
                top: 0              //Réinitialisation de la position top, pour que ça recolle à l'origine
            }, function() {
                $('#home1_langplus, .langplus').css('visibility', 'visible');
            });
            stt = 0;  
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Select = function (x) {
        try {
            
            if (KgbLib_CheckNullity(x) | !$(x).data("lang") | $.inArray($(x).data("lang"), _f_Gdf().langs) === -1) {
                return;
            }
            
            var lg = $(x).data("lang");
            switch (lg) {
                case "fr" :
                    return;
                case "en" :
                    $(".jb-tqr-nolg-sprt").removeClass("this_hide");
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /*********************************************************************************************************************************/
    /********************************************************** VIEW SCOPE ***********************************************************/
    /*********************************************************************************************************************************/
    
    /*********************************************************************************************************************************/
    /********************************************************** DATAS SCOPE **********************************************************/
    /*********************************************************************************************************************************/
    
    /*********************************************************************************************************************************/
    /******************************************************** LISTENERS SCOPE ********************************************************/
    /*********************************************************************************************************************************/
    
    $slctSl.click(function(){
        if ( stt === 0 ) {
            _f_Open();
        } else if ( stt === 1 ) {
            _f_Close();
        }
    });

    $(document).click(function(e){
        if($(e.target).is('#home1_lang, #home1_lang *, .lang, .lang *')){
            return;
        } else {
            _f_Close();
        }
    });


    //L'utilisateur ne peut pas cliquer sur sa langue actuelle
    $('.jb-tqr-wlc-lg-ch.current').click(function(e){
        Kxlib_PreventDefault(e);
    });

    $('.jb-tqr-wlc-lg-ch').not(".current").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Select(this);
    });
    
    /* * * * * * * * * * * */
    /* Gestion de .theater */
    /* * * * * * * * * * * */

    $(document).ready(function(){
        try {
            
            if ($(document).height() >= $(window).height()) {
                $('.theater').css('height', $(document).height());
            } else {
                $('.theater').css('height', $(window).height());
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    });
}

new LGSELECT();