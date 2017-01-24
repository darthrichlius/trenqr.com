/*
 * Ce fichier centralise la gestion du redimentionnement des elements dans le DOM.
 */
function Resizing () {
    //this.minCenterW = 842; /* 2px pour à cause d'un border */
//    this.minCenterW = 832; /* 2px pour à cause d'un border */
    //this.maxCenterW = 1072; /* 2px pour à cause d'un border */
    var _mxCtrW = 1062; /* 2px pour à cause d'un border */
    
    var _minCvrW = 760;
    this.minTrCoverW = 758;
    var _maxCvrW = 840;
    
    //Correspond à la hauteur du bloc contenant l'image
    var _cvrStdH = 260;
    
//    this.saveImgH;
//    this.saveImgW;
    
    var _f_RszAccHdr = function () {
//    this.ResizeAccHeader = function () {
        
        //On recupere la taille de center
        //currentCenterWidth
        var ccW = $(".jb-p-l-c-main").width();
        
//        alert("CENTER => "+$(".jb-p-l-c-main").width());
//        alert("CENTER => "+curCenterW);
        //*
        
        //On recupere la taille de Cover
        var ccvrW = $(".jb-acc-hdr").width();
//        curCoverW = parseInt(curCoverW.replace("px",""));
//        alert("COVER => "+curCoverW);
//        alert("CENTER => "+curCenterW+"; COVER => "+curCoverW);
  
        //curCoverLeftMarg
        var ccvlm = $(".jb-acc-hdr").css("margin-right");
        ccvlm = parseInt(ccvlm.replace("px",""));
//        var ccvlm = $(".jb-acc-hdr").css("margin-right");
//        ccvlm = parseInt(ccvlm.replace("px",""));
//        Kxlib_DebugVars([cvlm]);

        //DEV : addClass(Class,Time) est une fonction de JquerUi et non de Jquery
        if ( ( ccW < _mxCtrW ) && ( ccvrW === _maxCvrW ) && ccvlm < 50 ) {
        //Cas 1 : Center est en Min ou < Max & Cover n'est pas en min
            // On anime la Cover pour atteindre son Min
//             alert("Go Thiner");
            //alert("Go Thiner !");
            //*
            var $img = new Image();
            var $th = this;
            $img.src = $(".jb-a-h-t-top-img").attr("src");
            var _img_top = $(".jb-a-h-t-top-img").css("top");

            $img.onload = function(){
                // image  has been loaded
//                $th.saveImgH = this.height;
//                $th.saveImgW = this.width;
            };
            
            setTimeout(function(){
                // On anime la Cover pour atteindre son Max
                /*
                $(".jb-acc-hdr").addClass("resz_gothiner_bloc",700);
                $(".jb-acc-hdr-top").addClass("resz_gothiner_w",700);
                $("#a-h-t-top").addClass("resz_gothiner_w",700);
                $("#a-h-t-top-img-max").addClass("resz_gothiner_w",700);
                $("#a-h-t-top-fade").addClass("resz_gothiner_w",700);
//                $("#a-h-t-down").addClass("resz_gothiner_w",700);
                $(".jb-acc-hdr-down").addClass("resz_gothiner_w",700);
                $(".jb-acc-spec-loc").addClass("loc_go_thi",700);
                //*/
                
                $(".jb-acc-spec-loc").stop(true,true).addClass("loc_go_thi",700);
//                $(".jb-acc-hdr, .jb-acc-hdr-top, .jb-a-h-t-top, .jb-a-h-t-top-img-mx, .jb-a-h-t-top-fade, .jb-acc-hdr-down").stop(true,true).addClass("resz_gothiner_w",700);
                $(".jb-acc-hdr, .jb-acc-hdr-top, .jb-a-h-t-top, .jb-a-h-t-top-img-mx, .jb-a-h-t-top-fade, .jb-acc-hdr-down").stop(true,true).animate({
                    width: _minCvrW
                },700);
                
                //*
                //Si l'image a une hauteur égale à celle du bloc, on ne peut pas la redim au prorata ...
                //... Sinon, l'img resultante aura une hauteur trop petite et ne 'fitera' plus le bloc ... 
                // ... On ne réduit donc que le bloc ce qui a pour conséquence de cacher une partie de l'image
                if ( _cvrStdH !== $img.height ) {
                    var oo = new Cropper();
//                alert("COVER THINNER BEFORE CROP WIDTH => "+$img.width);
                    $img = oo.Cropper_resizeWidthKeepHeightProrata($img, _minCvrW);
//                alert("COVER THINNER AFTER CROP WIDTH => "+$img.width);
//                    $(".jb-a-h-t-top-img").replaceWith($img);
//                    alert("TOP => "+_img_top);
                    $(".jb-a-h-t-top-img").stop(true,true).animate({
                        top: _img_top,
                        width: $img.width,
                        height: $img.height
                    },700);
                }
                //*/
            },2000);
            //*/
//        } else if ( ( curCenterW <= this.maxCenterW ) && ( curCoverW === this.minCoverW ) && ccvlm >= 50 ) {
        } else {
            //Cas 2 : Center est en Max et Cover n'est pas en Max
            //alert("Go Bigger !");
            //*
            var $img = new Image();
            var $th =this;
            $img.src = $(".jb-a-h-t-top-img").attr("src");

            $img.onload = function(){
                // image  has been loaded
//                $th.saveImgH = this.height;
//                $th.saveImgW = this.width;
            };
            
            setTimeout(function(){
                // On anime la Cover pour atteindre son Max
                /*
                $(".jb-acc-hdr").removeClass("resz_gothiner_bloc",700);
                $(".jb-acc-hdr-top").removeClass("resz_gothiner_w",700);
                $("#a-h-t-top").removeClass("resz_gothiner_w",700);
                $("#a-h-t-top-img-max").removeClass("resz_gothiner_w",700);
                $("#a-h-t-top-fade").removeClass("resz_gothiner_w",700);
//                $("#a-h-t-down").removeClass("resz_gothiner_w",700);
                $(".jb-acc-hdr-down").removeClass("resz_gothiner_w",700);
                $(".jb-acc-spec-loc").removeClass("loc_go_thi",700);
                //*/
                
                $(".jb-acc-spec-loc").stop(true,true).removeClass("loc_go_thi",700);
//                $(".jb-acc-hdr, .jb-acc-hdr-top, .jb-a-h-t-top, .jb-a-h-t-top-img-mx, .jb-a-h-t-top-fade, .jb-acc-hdr-down").stop(true,true).removeClass("resz_gothiner_w",700);
                $(".jb-acc-hdr, .jb-acc-hdr-top, .jb-a-h-t-top, .jb-a-h-t-top-img-mx, .jb-a-h-t-top-fade, .jb-acc-hdr-down").stop(true,true).animate({
                    width: _maxCvrW
                },700);
                
                //*
                //var $img = $(".jb-a-h-t-top-img");
                //Si l'image a une largeur égale à celle du bloc en max, on ne peut pas la redim au prorata ...
                //... Car il s'agit surement d'une image non réduite lors du go thinner !
                //... Elle n'a pas été réduite pour ne pas la dénaturé car elle fit le bloc en hauteur.
                //Dans tous les autres cas on redim
                if ( _maxCvrW !== $img.width ) {
                    var oo = new Cropper();
//                alert("COVER THINNER BEFORE CROP WIDTH => "+$img.width);
                    $img = oo.Cropper_resizeWidthKeepHeightProrata($img, _maxCvrW);
//                alert("COVER THINNER AFTER CROP WIDTH => "+$img.width);
//                    $(".jb-a-h-t-top-img").replaceWith($img);
                     $(".jb-a-h-t-top-img").stop(true,true).animate({
                        width: $img.width,
                        height: $img.height
                    },700);
                }
                //*/
            },2000);
            //*/
        } 
        //*/  
    };
    
    this.ResizeTrHeader = function () {
        //On recupere la taille de center
        var curCenterW = $(".jb-p-l-c-main").css("width");
        curCenterW = parseInt(curCenterW.replace("px",""));
       // alert("CENTER => "+curCenterW);
        //*
        //On recupere la taille de Cover
        var curCoverW = $("#tr-header").css("width");
        curCoverW = parseInt(curCoverW.replace("px",""));
//        alert("COVER => "+curCoverW);
//        alert("CENTER => "+curCenterW+"; COVER => "+curCoverW);
    
        //curCoverLeftMarg
        var ccvlm = $("#tr-header").css("margin-right");
        ccvlm = parseInt(ccvlm.replace("px",""));
//        var ccvlm = $(".jb-acc-hdr").css("margin-right");
//        ccvlm = parseInt(ccvlm.replace("px",""));
//        Kxlib_DebugVars([cvlm]);

        //DEV : addClass(Class,Time) est une fonction de JquerUi et non de Jquery
        if ( ( curCenterW < _mxCtrW ) && ( curCoverW === _maxCvrW ) && ccvlm < 50 ) {
        //Cas 1 : Center est en Min ou < Max & Cover n'est pas en min
            // On anime la Cover pour atteindre son Min
//             alert("Go Thiner");
            //alert("Go Thiner !");
            //*
            var $img = new Image();
            var $th =this;
            $img.src = $("#a-h-t-top-tr-img-max img").attr("src");

            $img.onload = function(){
                // image  has been loaded
//                $th.saveImgH = this.height;
//                $th.saveImgW = this.width;
            };
            
            setTimeout(function(){
                // On anime la Cover pour atteindre son Max
                
                $("#tr-header").addClass("resz_gothiner_bloc",700);
                $("#tr-header-top").addClass("resz_gothiner_w",700);
                $("#a-h-t-top-tr").addClass("resz_gothiner_w",700);
                $("#a-h-t-top-img-max").addClass("resz_gothiner_w",700);
                $("#a-h-t-top-tr-title").addClass("tr-title-go-thi",700);
                $("#a-h-t-top-tr-desc").addClass("tr-desc-go-thi",700);
//                $("#a-h-t-top-fade").addClass("resz_gothiner_w",700);
                $(".tr-h-t-d-4").addClass("access_thi",700);
                $("#tr-h-t-down").addClass("resz_gothiner_w",700);
                
                //*
                //Si l'image a une hauteur égale à celle du bloc, on ne peut pas la redim au prorata ...
                //... Sinon, l'img resultante aura une hauteur trop petite et ne 'fitera' plus le bloc ... 
                // ... On ne réduit donc que le bloc ce qui a pour conséquence de cacher une partie de l'image
                if ( _cvrStdH !== $img.height ) {
                    var oo = new Cropper();
//                alert("COVER THINNER BEFORE CROP WIDTH => "+$img.width);
                    var wi = _minCvrW; //- 2;
                    $img = oo.Cropper_resizeWidthKeepHeightProrata($img, wi);
//                alert("COVER THINNER AFTER CROP WIDTH => "+$img.width);
                    var $img = $(img).attr('id',"a-h-t-top-img");
                    
                    $("#a-h-t-top-tr-img-max img").replaceWith($img);
                }
                //*/
            },2000);
            //*/
//        } else if ( ( curCenterW <= this.maxCenterW ) && ( curCoverW === this.minTrCoverW ) && ccvlm >= 50 ) {
        } else {
            //Cas 2 : Center est en Max et Cover n'est pas en Max
//            alert("Go Bigger !");
            //*
            var $img = new Image();
            var $th =this;
            $img.src = $("#a-h-t-top-tr-img-max img").attr("src");

            $img.onload = function(){
                // image  has been loaded
//                $th.saveImgH = this.height;
//                $th.saveImgW = this.width;
            };
            
            setTimeout(function(){
                // On anime la Cover pour atteindre son Max
                $("#tr-header").removeClass("resz_gothiner_bloc",700);
                $("#tr-header-top").removeClass("resz_gothiner_w",700);
                $("#a-h-t-top-tr").removeClass("resz_gothiner_w",700);
                $("#a-h-t-top-img-max").removeClass("resz_gothiner_w",700);
                $("#a-h-t-top-tr-title").removeClass("tr-title-go-thi",700);
                $("#a-h-t-top-tr-desc").removeClass("tr-desc-go-thi",700);
//                $("#a-h-t-top-fade").addClass("resz_gothiner_w",700);
                $(".tr-h-t-d-4").removeClass("access_thi",700);
                $("#tr-h-t-down").removeClass("resz_gothiner_w",700);
                
                //*
                //var $img = $(".jb-a-h-t-top-img");
                //Si l'image a une largeur égale à celle du bloc en max, on ne peut pas la redim au prorata ...
                //... Car il s'agit surement d'une image non réduite lors du go thinner !
                //... Elle n'a pas été réduite pour ne pas la dénaturé car elle fit le bloc en hauteur.
                //Dans tous les autres cas on redim
                if ( _maxCvrW !== $img.width ) {
                    var oo = new Cropper();
//                alert("COVER THINNER BEFORE CROP WIDTH => "+$img.width);
                    var wi = _maxCvrW;// - 2;
//                    alert(wi);
                    $img = oo.Cropper_resizeWidthKeepHeightProrata($img, wi);
//                alert("COVER THINNER AFTER CROP WIDTH => "+$img.width);
                    var $img = $(img).attr('id',"a-h-t-top-tr-img-img");
                    
                    $("#a-h-t-top-tr-img-max img").replaceWith($img);
                }
                //*/
            },2000);
            //*/
        } 
        //*/  
    };
    
    this.ResizeTrHeaderHigher = function () {
        $("#a-h-t-top-tr").stop(true,true).switchClass("resz-tr-hdr-smlr","resz-tr-hdr-hir",800, "easeOutSine");
        /*
        $("#a-h-t-top-tr").stop();
        $("#a-h-t-top-tr").switchClass("resz-tr-hdr-smlr","resz-tr-hdr-hir",800, "easeOutSine");
//        $("#a-h-t-top-tr").removeClass("resz-tr-hdr-smlr",1000, "easeOutSine");
        //*/
    };
    
    this.ResizeTrHeaderSmaller = function () {
        $("#a-h-t-top-tr").stop(true,true).switchClass("resz-tr-hdr-hir","resz-tr-hdr-smlr",800, "easeOutSine");
        /*
        $("#a-h-t-top-tr").stop();
        $("#a-h-t-top-tr").switchClass("resz-tr-hdr-hir","resz-tr-hdr-smlr",800, "easeOutSine");
//        $("#a-h-t-top-tr").addClass("resz-tr-hdr-smlr",1000, "easeOutSine");
        //*/
    };
    
    this.Init = function () {
        
        var $p = $("#rez-pg");
//        Kxlib_DebugVars([$p.length,$p.data("pg")],true);
        if ( !$p.length || KgbLib_CheckNullity($p.data("pg")) ) {
            return;
        }
        
        var p = $p.data("pg");
        
        switch (p.toUpperCase()) {
            case "TMLNR" :
                    _f_RszAccHdr();
                break;
            case "TRPG" :
                    this.ResizeTrHeader();
                break;
            default:
                    return;
                break;
        }
    };
}

(function(){
    
    var _obj = new Resizing();
    
//    _obj.Init();
    /*
    var id;
    window.onresize = function(){
//        alert("Resize!");
        clearTimeout(id);
        id = setTimeout(function(){
            _obj.Init();
        }, 500);
    };
    /*
    //On relance le controle tous les x secondes pour contrer les erreurs où l'user thin et/ou large trop vite
    setTimeout(function(){
            _obj.Init();
        }, 10000);
    //
    //*/
    
    /** 
     * Resize du Header de TREND
     */
    //Go Higher
    $("#tr-h-an-hi").click(function(e){
        Kxlib_PreventDefault(e);
        
        _obj.ResizeTrHeaderHigher();
        
        if (! $(e.target).hasClass("tr-hdr-an-full") ) {
            $(e.target).stop(true,true).toggleClass("tr-hdr-an-full");
            $("#tr-h-an-sm").stop(true,true).toggleClass("tr-hdr-an-full");
        }
        
    });
    
    //Go Smaller
    $("#tr-h-an-sm").click(function(e){
        Kxlib_PreventDefault(e);
        
        _obj.ResizeTrHeaderSmaller();
       
        if (! $(e.target).hasClass("tr-hdr-an-full") ) {
            $(e.target).stop(true,true).toggleClass("tr-hdr-an-full");
             $("#tr-h-an-hi").stop(true,true).toggleClass("tr-hdr-an-full");
        }
    });
})();