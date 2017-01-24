function hideMenu() {
    $(".jb-handle-menu").addClass("this_hide");
}

function showMenu() {
    $(".jb-handle-menu").removeClass("this_hide");
}

function main() {
    try {
        hideMenu();
        /*
        $(".jb-hdr-btn-hdle").focus(function(){
//            $(".jb-hdr-btn-hdle").click(function(){
//                hideMenu();
//                this.blur();
//            });
            console.log("EDITER :","FOCUS !");
        });
        //*/
        
        $(".jb-hdr-btn-hdle").blur(function(e) {
//            console.log("EDITER :","BLUR !");
            hideMenu(); 
            
            /*
            if ( !$(".jb-handle-menu").is(":hover") ) {
                hideMenu(); 
            }
            else {
                $(".jb-hdr-btn-hdle").focus();
            }
            //*/
        });
        
        $(".jb-hdr-btn-hdle").click(function(e){
            Kxlib_PreventDefault(e);
            Kxlib_StopPropagation(e);
            
//            console.log("EDITER :","CLICK !");

          //On coupe l'event blur car il se declecnhera auto et faussera notre logique
          $("document").off('blur', ".jb-hdr-btn-hdle", showMenu());
//          $("document").off('blur', ".jb-hdr-btn-hdle");
//          showMenu();
//          $(this).focus(); //Pour Chrome et pouvoir utiliser blur() derriere
        });

    } catch (ex) {
        Kxlib_DebugVars([ex],true);
    }
}
main();