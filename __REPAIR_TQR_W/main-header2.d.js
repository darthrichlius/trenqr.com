var f = function () {    
    var _ul = $("#main-header-ul").css("width").toString();
    var _ps_sh= $("#ps_sh span").css("width").toString();
    var _ps_hi = $("#ps_hi span").css("width").toString();
    var _txt = $("#main-header-li-left p span").html().toString(); 
    var _spaceInt = 0;
    
    _ulInt = parseInt(_ul.replace("px", ''));
    var _psInt = parseInt(_ps_sh.replace("px", ''));
    
    
    if ( _txt !== "THIS IS WHERE, IT ALL BEGINS !") {
        _psInt = parseInt(_ps_hi.replace("px", ''));
    }
    
    if ( $("#ps_sh_img").length == 0 ) {
        _spaceInt = (_ulInt-_psInt)/2;
    } else {
        _psInt += 45+10;
        _spaceInt = (_ulInt-_psInt)/2;
    }
        
    var _spaceSt = _spaceInt+"px";

    $("#main-header-li-left p").css("text-indent",_spaceSt);
};

$(document).ready(function () {
    f();
    var _txt = $("#ps_hi").html().toString(); 
    $("#main-header-li-left p span").delay(4000).fadeOut(1000,function(){
        $("#main-header-li-left p span").html(_txt);
        f();
        $("#main-header-li-left p span").delay(500).fadeIn(1000);
    });
});

$( window ).resize(f);