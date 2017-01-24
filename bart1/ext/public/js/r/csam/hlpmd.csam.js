

function HLPMD () {
    
    /*****************************************************************************************************************************************************************/
    /************************************************************************* PROCESS SCOPE *************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    var _f_Entry = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("target")) ) {
                return;
            }
            
            var fld = $(x).data("target"), $mx, $sbs;
            switch (fld.toUpperCase()) {
                case "NWTR" : 
                        $mx = $(".jb-tqr-hlprmd-entry-tgr[data-target='"+fld+"']");
                        $sbs = $(".jb-tqr-hlprmd-popps-mx[data-scp='nwtr']");
//                        Kxlib_DebugVars([mx.length,$sbs.length]);
                        if ( !$mx.length | $sbs.length !== 4 ) {
                            return;
                        }
                        
//                        Kxlib_DebugVars([mx.hasClass("activated"),$sbs.filter(":not(.this_hide)").length]);
                        if ( $mx.hasClass("activated") && $sbs.filter(":not(.this_hide)").length ) {
                            //On change l'état
                            $mx.removeClass("activated");
                            
                            //On doit fermer les éléments
                            $sbs.addClass("this_hide");
                        } else {
                            //On change l'état
                            $mx.addClass("activated");
                            
                            //On doit ouvrir les éléments
                            $sbs.removeClass("this_hide");
                        }
                        
                    break;
                case "MYTRS" : 
                case "FLGTRS" : 
                        $mx = $(".jb-tqr-hlprmd-entry-tgr[data-target='"+fld+"']");
                        $sbs = $(".jb-tqr-hlprmd-popps-mx[data-scp='"+fld+"']");
//                        Kxlib_DebugVars([mx.length,$sbs.length]);
                        if ( !$mx.length | $sbs.length !== 1 ) {
                            return;
                        }
                        
//                        Kxlib_DebugVars([mx.hasClass("activated"),$sbs.filter(":not(.this_hide)").length]);
                        if ( $mx.hasClass("activated") && $sbs.filter(":not(.this_hide)").length ) {
                            //On change l'état
                            $mx.removeClass("activated");
                            
                            //On doit fermer les éléments
                            $sbs.addClass("this_hide");
                        } else {
                            //On change l'état
                            $mx.addClass("activated");
                            
                            //On doit ouvrir les éléments
                            $sbs.removeClass("this_hide");
                        }
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ClzSingle = function (x) {
        
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("target")) ) {
                return;
            }
            
            var fld = $(x).data("target"), $mx;
            switch (fld.toUpperCase()) {
                case "NWTR_TITLE" : 
                case "NWTR_DESC" : 
                case "NWTR_CATG" : 
                case "NWTR_PART" : 
                case "MYTRS_ADDITR" : 
                case "FLGTRS_ADDITR" : 
                        $mx = $(".jb-tqr-hlprmd-popps-mx[data-target='"+fld+"']");
                    break;
                default :
                    return;
            }
            
            if (! $mx.length ) {
                return;
            }
            
            $mx.addClass("this_hide");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    
    /*****************************************************************************************************************************************************************/
    /************************************************************************* SERVER SCOPE *************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    /*****************************************************************************************************************************************************************/
    /*************************************************************************** VIEW SCOPE **************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    /*****************************************************************************************************************************************************************/
    /*********************************************************************** LISTERNERS SCOPE ************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    $(".jb-tqr-hlprmd-entry-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Entry(this);
    });
    
    $(".jb-tqr-hlprmd-popps-clz").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_ClzSingle(this);
    });
}


new HLPMD ();