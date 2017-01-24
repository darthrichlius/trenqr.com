function hideMenu() {
    $(".jb-handle-menu").addClass("this_hide");
}

function showMenu() {
    $(".jb-handle-menu").removeClass("this_hide");
}

function main() {
    hideMenu();
    /*
    $("#header-btn-handle").focus(function(){
        $("#header-btn-handle").click(function(){
            hideMenu();
            this.blur();
        });
    });
    //*/
    $("#header-btn-handle").click(function(e){
        Kxlib_PreventDefault(e);
        
      //On coupe levent blur car il se declecnhera auto et faussera notre logique
      $("document").off('blur', "#header-btn-handle", showMenu());
      $("#header-btn-handle").focus(); //Pour Chrome et pouvoir utiliser blur() derriere
    });
    
    $("#header-btn-handle").blur(function() {
        try {
            if ( !$(".jb-handle-menu").is(":hover") ) {
                hideMenu(); 
            }
            else {
                $("#header-btn-handle").focus();
            }
        } catch (e) {
            Kxlib_DebugVars([e],true);
        }

    });
}
main();