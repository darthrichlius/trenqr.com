
function ASDW () {
    
    /*************************************************************************************************************************************************/
    /***************************************************************** PROCESS SCOPE *****************************************************************/
    /**************************************************************************************************************************************************/
    
    
    var _f_SwImHigh = function (x,shw,lk) {
        /*
         * Permet de switcher entre le mode "Floating" et "Asided"
         */
        try {
            
            var st__ = ( $(".jb-asr-trd-ckpt-bx") && $(".jb-asr-trd-ckpt-bx").length ) ? 442 : 447;
            var t__ = $(x).scrollTop() - st__ ;
//            Kxlib_DebugVars("TOPx : "+t__);
//            Kxlib_DebugVars(shw, typeof x !== undefined, $(x).length);
            if ( shw && typeof x !== undefined && $(x).length ) {
                $("#aside").css({ top: t__+"px" });
            } else {
                $("#aside").css({ top: "0" });
            }
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }

    };
    
    /*************************************************************************************************************************************************/
    /****************************************************************** SERVER SCOPE *****************************************************************/
    /**************************************************************************************************************************************************/
    
    
    /*************************************************************************************************************************************************/
    /******************************************************************* VIEW SCOPE ******************************************************************/
    /**************************************************************************************************************************************************/
    
    
    /*************************************************************************************************************************************************/
    /**************************************************************** LISTERNERS SCOPE ***************************************************************/
    /**************************************************************************************************************************************************/
    
    $(window).scroll(function(e){
        $(".jb-wos-csl-opt-steam").text("TOP : "+$(this).scrollTop());
//        Kxlib_DebugVars("TOP : "+$(this).scrollTop());
/*
        var st__ = ( $(".jb-asr-trd-ckpt-bx") && $(".jb-asr-trd-ckpt-bx").length ) ? 442 : 427;
        if ( $(".jb-asdr-w-mx") && $(".jb-asdr-w-mx").length && $(this).scrollTop() >= st__ ) {
            _f_SwImHigh(this,true);
        } else {
            _f_SwImHigh(this);
        }
        */
        
        /*
         * [DEPUIS 04-11-15] @author BOR
         */
        if ( $(this).scrollTop() >= 1905 && ( !$(".jb-asd-apps-pin-btn").length || ( $(".jb-asd-apps-pin-btn").length && $(".jb-asd-apps-pin-btn").attr("data-state") === "lock" ) ) ) {
            var t__ = (-1) * ( $("#aside-rich-banner").outerHeight() + 91/*margin-top*/ );
            t__ += ( $(".asd-rch-sctn[data-section='ad1']").outerHeight() + $(".asd-rch-sctn[data-section='ad2']").outerHeight() + $("#legals").outerHeight() + 40/*Ajustement manuel*/ );
            
//            console.log(t__);
//            return;
//            $("#aside").css({ top: t__+"px" });
            $("#aside-rich-banner").css({ 
                position: "fixed",
                top: t__+"px"
//                top: "-1290px"
//                top: "-1573px"
            });
            $(".asd-rch-sctn[data-section='ad1']").insertAfter(".asd-rch-sctn[data-section='ad2']");
        } else {
//            $("#aside").css({ top: "0" });
            $("#aside-rich-banner").removeAttr("style");
            $(".asd-rch-sctn[data-section='ad1']").insertBefore(".asd-rch-sctn[data-section='profil-sugg']");
//            $(".asd-rch-sctn[data-section='profil-sugg']").
        }
        
    });
    
}

new ASDW();
