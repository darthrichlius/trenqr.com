/*
 * Permet de gérer les changements de langues de navigation.
 * Le module est surtout utilisé pour les cas de page Welcome.
 * 
 * Il reprend un travail précédent et l'améliore.
 */

function LGSELECT () {
   
//    var inscz = ( $('#home1_langlist').length ) ? true : false;
    var inscz = ( $('.tqr-lgslt-list.inheader').length ) ? true : false;
   
    //Hauteur de la div contenant les langues
//    var $lgSlH = ( inscz ) ? $('#home1_langlist') : $(".langlist") ;
    var $lgSlH = ( inscz ) ? $('.tqr-lgslt-list.inheader') : $(".tqr-lgslt-list.home") ;
    var lgH = $($lgSlH).height();

    //Hauteur du 'sélecteur'
//    var $slctSl = ( inscz ) ? $('#home1_lang') : $(".lang");
    var $slctSl = ( inscz ) ? $('.tqr-lgslt-list-mx.inheader') : $(".tqr-lgslt-list-mx.home");
    var slctH = $slctSl.height();
    
    //Calcul du décalage top nécessaire pour l'animation 'drop-up'
    var topOffset = lgH - slctH;  

    //Etat du menu | 0 = Menu fermé, 1 = Menu ouvert
    var stt = 0;
    
//    Kxlib_DebugVars([lgH,slctH,topOffset],true);

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
    
    var _f_OnLoad = function(){
        try {
            /*
             * ETAPE :
             *      On gère les éléments de HEIGHT ...
             */
            if ($(document).height() >= $(window).height()) {
                $('.theater').css('height', $(document).height());
            } else {
                $('.theater').css('height', $(window).height());
            }
            
            /*
             * ETAPE :
             *      On gère le cas de la LANG
             */
            var loc_lang = ReadCookie("TQR_LCLG");
            if ( loc_lang && $(".jb-tqr-wlc-lg-ch-mx[data-lang='"+loc_lang+"']").length ) {
                $(".jb-tqr-wlc-lg-ch.current").removeClass("current css-lang-current");
                $(".jb-tqr-wlc-lg-ch-mx[data-lang='"+loc_lang+"']").find(".jb-tqr-wlc-lg-ch").addClass("current css-lang-current");
                
                if (! inscz ) {
                    $(".jb-tqr-wlc-lg-ch-mx[data-lang='"+loc_lang+"']").appendTo(".jb-tqr-lgslt-list");
                } else {
                    $(".jb-tqr-wlc-lg-ch-mx[data-lang='"+loc_lang+"']").prependTo(".jb-tqr-lgslt-list");
                }
            }
            
            $('.jb-tqr-wlc-lg-ch').not(".current").click(function(e){
                Kxlib_PreventDefault(e);

                _f_Select(this);
            });
    
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Open = function () {
        try {
            $slctSl.stop().animate({
                top     : ( inscz ) ? "auto" : -topOffset,
                height  : lgH
            });
            stt = 1; 
            $("#home1_langplus, .langplus").css("visibility", "hidden");
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Close = function () {
        try {
            $slctSl.stop().animate({
                top     : 0,              //Réinitialisation de la position top, pour que ça recolle à l'origine
                height  : slctH
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
            if ( KgbLib_CheckNullity(x) | !$(x).data("lang") | $.inArray($(x).data("lang"), _f_Gdf().langs) === -1 | $(x).hasClass(".current") ) {
                return;
            }
            
            var lg = $(x).data("lang");
            switch (lg) {
                case "fr" :
                        _f_SetLang(lg);
                    break;
                case "en" :
//                        $(".jb-tqr-nolg-sprt").removeClass("this_hide");
                        alert("By changing this value you understand that Trenqr is partially in English. The translation will be complete in the coming days. Thanks for your understanding.");
                        _f_SetLang(lg);
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_SetLang = function (lg) {
        try {
            if ( KgbLib_CheckNullity(lg) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On ajoute/remplace cookie
             */
            CreateCookie("TQR_LCLG",lg,365);
            
            /*
             * ETAPE :
             *      On reload la PAGE
             */
            document.location.reload();
            
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
    
    $slctSl.click(function(e){
        if (! stt ) {
            _f_Open();
        } else if ( stt ) {
            _f_Close();
        }
    });

    $(document).click(function(e){
//        if( $(e.target).is('#home1_lang, #home1_lang *, .lang, .lang *') ) {
        if ( $(e.target).is('.tqr-lgslt-list-mx.home, .tqr-lgslt-list-mx.home *, .tqr-lgslt-list-mx.inheader, .tqr-lgslt-list-mx.inheader *') ) {
            return;
        } else {
            _f_Close();
        }
    });


    //L'utilisateur ne peut pas cliquer sur sa langue actuelle
    $('.jb-tqr-wlc-lg-ch.current').click(function(e){
        Kxlib_PreventDefault(e);
    });

    /* //[DEPUIS 11-07-16]
    $('.jb-tqr-wlc-lg-ch').not(".current").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Select(this);
    });
    //*/
    
    /************************************************/
    /************** GESTION DE THEATER **************/
    /************************************************/

    $(document).ready(function(){
        try {
            /*
             * [DEPUIS 11-07-16]
             *      On utilise désormais une fonction pour gérer le cas de OnLOAD
             */
            _f_OnLoad();
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    });
}

new LGSELECT();