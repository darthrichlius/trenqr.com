$(".down-mang").hide();
$("#stream-filter-1").hide();
$("#stream-filter-2").hide();
$(".t-m-s-3").hide();

function h_hover () {
    $(".tr-in-stream").hover(function(){
        var _id = $(this).attr("id");
        var _idtx = "#"+_id;
        
        $(_idtx+" li[class=down-badge]").hide();
        $(_idtx+" li[class=down-spec]").hide();
        $(_idtx+" li[class=down-mang]").show();
    }, function(){
        var _id = $(this).attr("id");
        var _idtx = "#"+_id;
        
        var _t= $(_idtx+" li[class=down-mang]").children("a[class=blocked-btn-deny]").css("display").toString();

    if( _t !== "block") {
            $(_idtx+" li[class=down-badge]").show();
            $(_idtx+" li[class=down-spec]").show();
            $(_idtx+" li[class=down-mang]").hide();
        }
    });
};

function h_sh_filters () {
    $("#s-fls-1").click(function(){
        //alert("toto");
        //*
        var _tx = $("#stream-filter-1").css("display").toString();
        
        if ( _tx === "none") {
            $("#stream-filter-1").fadeIn(400);
            $("#s-fls-1").html("Hide filters");
        } else {
            $("#stream-filter-1").fadeOut(300);
            $("#s-fls-1").html("Show filters");
        }
        //*/
    });
    
    $("#s-fls-2").click(function(){
        //alert("toto");
        //*
        var _tx = $("#stream-filter-2").css("display").toString();
        
        if ( _tx === "none") {
            $("#stream-filter-2").fadeIn(400);
            $("#s-fls-2").html("Hide filters");
        } else {
            $("#stream-filter-2").fadeOut(300);
            $("#s-fls-2").html("Show filters");
        }
        //*/
    });
}

function h_art_mini () {
    $(".t-m-s-1 a").hover(function(){
        var _id = $(this).parent().parent().parent().attr("item-id").toString();
        
        _slr = "#post-id-"+_id+" .t-m-s-3";
        
        $(_slr).show();
    },function(){
        var _id = $(this).parent().parent().parent().attr("item-id").toString();
        
        _slr = "#post-id-"+_id+" .t-m-s-3";
        
        $(_slr).hide();
    });
}

//alert();

//h_hover();
h_sh_filters();
h_art_mini();

//(function(){
    //Correct wrong declarations
    var _v = $(".tr-in-stream");
    $.each(_v, function(k,v){
        if( $(v).data("status") === "b_f_tr" && ( $(v).find(".hell_unblock").html() ) !== "unblock" ) {
            $(v).find(".hell_unblock").html("unblock");
        }
    });
    
function handl_unblock_event (e) {
        e.preventDefault();
        
        //Server-Side Treament Function
        
        //Call-Back
        var _el = $(".tr-in-stream").has(e.target); 
        var _sub = $(_el).find(".down-img");
        var _p = $(_sub).children("p");
        var _nsub = $(_el).find(".hell_left");
        var _b = document.getElementById("nosecret_basket");
        var _toadd = $(_b).children("#hi_b");
        _toadd = _toadd.clone();
        _toadd.on("click", handl_block_event);
        //alert(_sub.html());
        
        $(_p).remove();
        
        $(_nsub).children(".hell_unblock").remove();
        $(_nsub).append(_toadd);
        $(_nsub).children("#hi_b").toggleClass("this_hide");
        $(_nsub).children("#hi_b").removeAttr("id");
}
    
function handl_block_event (e) {
        e.preventDefault();

        //Server-Side Treament Function
        
        //Call-Back
        var _el = $(".tr-in-stream").has(e.target); 
        var _sub = $(_el).find(".down-img");
        var _p = $(_sub).children("p");
        var _nsub = $(_el).find(".hell_left");
        var _b = document.getElementById("nosecret_basket");
        var _toadd = $(_b).children(".blocked_tr_badge");
        _toadd = _toadd.clone();
        var _ntoadd = $(_b).children("#hi_ub");
        _ntoadd = _ntoadd.clone();
        _ntoadd.on("click", handl_unblock_event);
        
//        alert(_toadd.html());
        
        $(_sub).append(_toadd);
        
        $(_nsub).children(".hell_block").remove();
        $(_nsub).append(_ntoadd);
        $(_nsub).children("#hi_ub").toggleClass("this_hide");
        $(_nsub).children("#hi_ub").removeAttr("id");
    }

$(".hell_block").click(function(e){
    
    handl_block_event(e);
});

$(".hell_unblock").click(function(e){
    handl_unblock_event(e);
});
//})();