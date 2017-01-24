
(function(){
    ////HANDLE NO ACTICLE
    
    /************ ACCOUNT - TIMELINE *************/
    //S'il n'y a aucun article au niveau EAST aussi bien que WEST on affiche le bloc 'incitatif'
    var east = $("#feeded_e_list_list").find(".jb-tmlnr-mdl-std").length;
    var west = $("#feeded_w_list_list").find(".jb-tmlnr-mdl-std").length;
//    Kxlib_DebugVars([$("#feeded_e_list_list").length, !east, !west, $(".jb-whub-mx").length, $("#brain_maximus").hasClass("this_hide")],true);
//    Kxlib_DebugVars([east,west],true);
    if ( $("#feeded_e_list_list").length && !east && !west && $(".jb-whub-mx").length && $("#brain_maximus").hasClass("this_hide") ) {
        $(".jb-whub-mx").removeClass("this_hide");
    } else {
        $(".jb-whub-mx").addClass("this_hide");
    }
    
    /********* TREND *********/
    if ( $(".jb-trpg-art-nest").length ) {
        if (! $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list").length ) {
            $(".jb-whub-mx").removeClass("this_hide");
        } else {
            $(".jb-whub-mx").addClass("this_hide");
        }
    }
    
    /* [OBSELETE 23-04-15]
    //S'il n'y a aucun article au niveau EAST aussi bien que WEST on affiche le bloc 'incitatif'
    var tr_east = $(".jb-tr-e-list").find(".jb-mdl-tr-post-in-list").length;
    var tr_west = $(".jb-tr-w-list").find(".jb-mdl-tr-post-in-list").length;
    
//    Kxlib_DebugVars([tr_east,tr_west],true);
    
    if ( $(".jb-tr-e-list").length && !tr_east && !tr_west && $(".jb-whub-mx").length ) {
//    if ( $(".jb-tr-e-list").length && !tr_east && !tr_west && $(".jb-whub-mx").length ) {
        $(".jb-whub-mx").removeClass("this_hide");
    } else {
        $(".jb-whub-mx").addClass("this_hide");
    }
    //*/
})();
