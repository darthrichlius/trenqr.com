

function ACCJS () {
    
    /**********************************************************************************************************************************************************/
    /********************************************************************* PROCESS SCOPE **********************************************************************/
    /**********************************************************************************************************************************************************/
     var _f_Gdf = function () {
        var DT = {
            "__ZInMax": 90,
            "__ZOutMax": 45
        };
        
        return DT;
    };
    
    var _f_Init = function(){
        try {
            /*
             * On vérifie s'il y a des Articles. 
             * Dans ce dernier cas, on affiche la barre de load et on masque eventuellement NoOne.
             * RAPPEL : La barre est toujours masquée, on l'affiche via cette méthode init().
             * On affiche si on a la présence des 6 publications et que le nombre de publications affiché est superieur à 6
             */
            if ( typeof window.pageIsLoaded === "undefined" || window.pageIsLoaded === false ) {
                if ( $(".menu-selected").data("target") === "page" ) {
                    if ( 
                        $(".feeded_com_bloc_figs").length && parseInt($(".jb-acc-spec-artnb").data("length")) > $(".feeded_com_bloc_figs").length
                    ) {
                        $(".jb-whub-mx").addClass("this_hide");
                        $(".jb-nwfd-loadm-box.tmlnr").removeClass("this_hide");
                    } else if ( !$(".feeded_com_bloc_figs").length && ( !$("#slave_maximus").length || $("#slave_maximus").hasClass("this_hide") ) ) {
                        $(".jb-nwfd-loadm-box.tmlnr").addClass("this_hide");
                        $(".jb-whub-mx").removeClass("this_hide");
                    } else {
                        $(".jb-whub-mx").addClass("this_hide");
                        $(".jb-nwfd-loadm-box.tmlnr").addClass("this_hide");
                    }
                } else if ( $(".menu-selected").data("target") === "trends" ) {
                    if ( $(".jb-myts-mdl-mx").length && $(".jb-myts-mdl-mx").length < ( parseInt($(".jb-acc-spec-trnb").data("length"))+parseInt($(".jb-acc-spec-abotr-nb").data("length")) ) ) {
//                    if ( $(".jb-myts-mdl-mx").length && $(".jb-myts-mdl-mx").length === 1 && ( parseInt($(".jb-acc-spec-trnb").data("length"))+parseInt($(".jb-acc-spec-abotr-nb").data("length")) ) > 1 ) { //[DEPUIS 30-07-15] @BOR
                        $(".jb-whub-mx").addClass("this_hide");
                        $(".jb-nwfd-loadm-box.tmlnr").removeClass("this_hide");
                    } else {
                        $(".jb-whub-mx").removeClass("this_hide");
                        $(".jb-nwfd-loadm-box.tmlnr").addClass("this_hide");
                    }
                } else {
                    return;
                }
 
                window.pageIsLoaded = true;
            } 

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ZmInPic = function(a,force) {
        try {

            var a = ( KgbLib_CheckNullity(a) ) ? $(".jb-pfl-uppic-notro-img") : a;
            setTimeout(function() {
                if ( !$(".jb-p-h-b-ui-i-fade").is(":hover") && KgbLib_CheckNullity(force) ) {
//                if ( !$(a).is(":hover") && KgbLib_CheckNullity(force) ) { //[DEPUIS 20-09-15] @author BOR
                    return; 
                } 

                $(a).stop(true,true).animate({
                    height: _f_Gdf().__ZInMax,
                    width: _f_Gdf().__ZInMax
                }, function() {
                });
            }, 250);

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };

    var _f_ZmOutPic = function(v) {
        try {

            var a = ( KgbLib_CheckNullity(v) ) ? $(".jb-pfl-uppic-notro-img") : v;
            $(a).stop(true,true).animate({
                height: _f_Gdf().__ZOutMax,
                width: _f_Gdf().__ZOutMax
            }, function() {
            });

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };

    /**********************************************************************************************************************************************************/
    /******************************************************************** LISTENERS SCOPE *********************************************************************/
    /**********************************************************************************************************************************************************/
    
    /*
    $(".mdl-acc-post-img ul").hover(function(){
        $(this).toggleClass("fade");
        $(this).toggleClass("fade-txt");

        $(this).children().children(".put-txt").html("Lorem ipsum dolor sit amet, consectetur adipiscing elit");

        $(this).parent().parent().children(".mdl-acc-post-roof").children(".mdl-a-p-r-cat").children("p").toggleClass("mdl-a-p-r-cat-p-full");
    }, function(){
        $(this).toggleClass("fade");
        $(this).toggleClass("fade-txt");

        $(this).children().children(".put-txt").html("");

        $(this).parent().parent().children(".mdl-acc-post-roof").children(".mdl-a-p-r-cat").children("p").toggleClass("mdl-a-p-r-cat-p-full");
    });
    */
   

    /**
     * Pour 
     * $(".fcb_img_maximus").click();
     * @see acc_rich_post.js    
     */

    $(".brain_elmnt_lib").hover(function(){
        $(this).toggleClass("brain_hover");
    });


    //$(".in_slave_list").perfectScrollbar();


    $(".th-sams_samplUnik_a").click(function(e){
        $o = $($(this).data("target"));

        //With animation
        $o.animatescroll({scrollSpeed:2000});
    });
    
    /*
     * [DEPUIS 26-06-15] @BOR
     * Pour les cas où on se trouve sur une page TMLNR hors RO.
     * Sur ces pages, la gestion est effectuée par un module qui n'est pas présent sur les autres pages.
     */
    /*
    $(".jb-pfl-uppic-notro-img").hover(function(e){
        _f_ZmInPic(this);
    },function(){
        _f_ZmOutPic(this); 
    });
    //*/
    /*
     * [DEPUIS 20-09-15] @BOR
     */ 
    $(".jb-p-h-b-ui-trg > *").off("hover").hover(function(e){
        var _this = $(this).closest(".jb-p-h-b-ui-trg").find(".jb-pfl-uppic-notro-img");
        _f_ZmInPic(_this);
    },function(){
        var _this = $(this).closest(".jb-p-h-b-ui-trg").find(".jb-pfl-uppic-notro-img");
        _f_ZmOutPic(_this); 
    });
    
    try {
        $("#brain_list_mytrs").perfectScrollbar();
        $("#brain_list_follgtrs").perfectScrollbar();
        $("#brain_list_folls").perfectScrollbar();
        $("#brain_list_folgs").perfectScrollbar();
        $("#brain_list_gbck_r").perfectScrollbar();
        $("#brain_list_gbck_s").perfectScrollbar();
        $("#brain_list_notif_all").perfectScrollbar();
        //$("#bn-ntr-catg-chs-bdy").perfectScrollbar();
        /*
         * [DEPUIS 25-05-16]
         */
        $("#brain_list_stgs").perfectScrollbar();
        $("#brain_list_trophz").perfectScrollbar();
        
    } catch (ex) {
        Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
    }
    
    /**********************************************************************************************************************************************************/
    /*********************************************************************** INIT SCOPE ***********************************************************************/
    /**********************************************************************************************************************************************************/
    
    _f_Init();
    
}

/*
 * [DEPUIS 26-06-15] @BOR
 */
new ACCJS();
