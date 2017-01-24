/*
 $(window).load(function(){
    $(".s-s-p-unik").mCustomScrollbar({
            theme:"dark-thick",
            scrollButtons: { enable : true}
     });
     $(".s-s-p-unik").mCustomScrollbar("disable");
});


//*/
//*
$(".cmt-header").hover(function(){
    $(this).children(".bash").css("color","orange");
}, function(){
    $(this).children(".bash").css("color","red");
});
//*/
$(".cmt-header").click(function(e){
    e.preventDefault();
    //*
    if ( $(".s-s-p-filters li").css("display").toString() === "none" ) {
         $(".s-s-p-filters li").fadeIn(500);
         //On pourra par la suite changer ici et appeler Ajax pour écupérer les commentaires
         //Cela permettra un chargement plus rapide de la page
         $(".s-s-p-cmnts").removeAttr('style');
         $(".s-s-p-cmnts").toggleClass("elmnt-hide");
         $(".s-s-p-unik").perfectScrollbar();
         
         
         $("#start-react").animatescroll({element:'.s-s-p-unik',padding:20});
         setTimeout(function(){
            $(".s-s-p-unik").perfectScrollbar();
            //$(".s-s-p-unik").scrollTop(600);
            $(".s-s-p-unik").perfectScrollbar('update');
         },1000);
         
    } else {
        //$(document).scrollTop( $("#start-react").offset().top ); 
        $(".s-s-p-filters li").slideUp();
        $("#toptop").animatescroll({element:'.s-s-p-unik',padding:20});
        setTimeout(function(){
                
                $(".s-s-p-cmnts").hide();
                //$(".s-s-p-cmnts").replaceAttr('style');
                
//                $(".s-s-p-cmnts").css("display","");
                $(".s-s-p-cmnts").toggleClass("elmnt-hide");
                $(".s-s-p-unik").perfectScrollbar('destroy');
        }, 1000);
        
        //$(".s-s-p-cmnts").hide();
    }
    //*/
});
/*
$(".goto-prev-area-on-img a").fadeTo(10,1);
$(".goto-next-area-on-img a").fadeTo(10,1);
    */
$(".goto-prev-area-on-img a").mouseenter(function(){
    setTimeout(function(){
        if ( $(".goto-prev-area-on-img a").css("opacity") === "0" && $(".goto-prev-area-on-img a").is(":hover") ) {
                $(".goto-prev-area-on-img a").animate({
                    opacity:0.3
                },100);
        } else {
            $(".goto-prev-area-on-img a").stop();
        }
            
    },50);
}).mouseleave(function(){
    $(".goto-prev-area-on-img a").animate({
            opacity:0
        },150);
});
/*
    setInterval(function(){
        var $sample = $(".goto-next-area-on-img a");
        if($sample.is(":hover")) {
           $(".goto-next-area-on-img a").animate({
                        opacity:0.3
                    });
        }
        else {
           $(".goto-next-area-on-img a").animate({
                opacity:0
            });
        }
    }, 200);
//*/
//*
$(".goto-next-area-on-img a").mouseenter(function(){
    setTimeout(function(){
        if ( $(".goto-next-area-on-img a").css("opacity") === "0" && $(".goto-next-area-on-img a").is(":hover") ) {
                $(".goto-next-area-on-img a").animate({
                    opacity:0.3
                },100);
        } else {
            $(".goto-next-area-on-img a").stop();
        }
            
    },50);
}).mouseleave(function(){
    $(".goto-next-area-on-img a").animate({
            opacity:0
        },150);
});
//*/
/*
$(".s-s-p-unik").hover(function(){
    $(".s-s-p-unik").mCustomScrollbar("update");
}, function(){
    setTimeout(function() {
        $(".s-s-p-unik").mCustomScrollbar("disable");
    },2000);
});
//*/

//$(".s-s-p-unik").perfectScrollbar();
