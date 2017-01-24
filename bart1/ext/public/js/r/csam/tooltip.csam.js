/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var anim_lock = false; 

function ins_tooltip_show ($sel, msg){
    if(anim_lock === false){
        anim_lock = true;
        
        $sel.find("#ins_tooltip").html(msg);
        
        //On place au bon endroit la cible
        var loc = $sel.data("loc").split(",");
        var Mycss = new Object();
        // f = forcer l'apparition de la valeur 0
        var f = false;
        if ( v.toString().match(/do/g) ) {
            f = true;
            v.toString().replace("do",'');
        }
        
        //*
        $.each(loc, function(i,v){
            //alert(i+" : "+v);
            if ( parseInt(v) || f ) {
//                alert("V is defined as => "+v);
                switch (i) {
                    case 0 :
//                            alert(i+" : "+v);
                            Mycss["top"] = ""+v+"px";
                        break;
                    case 1 :
//                            alert(i+" : "+v);
                            Mycss["right"] = ""+v+"px";
                        break;
                    case 2 :
//                            alert(i+" : "+v);
                            Mycss["bottom"] = ""+v+"px";
                        break;
                    case 3 :
//                            alert(i+" : "+v);
                            Mycss["left"] = ""+v+"px";
                        break;
                    case 4 :
//                            alert(i+" : "+v);
                            Mycss["z-index"] = ""+v+"";
                        break;
                }
            }
//            alert("MyCSS => "+i+" : "+Mycss[i]);
        });
        //*/
        //*
        $sel.css(Mycss);
        //*/
        $sel.fadeIn(function(){
            anim_lock = false;
        });
    }
}

function ins_tooltip_hide($sel){
    if(anim_lock === false){
        anim_lock = true;
        $($sel).fadeOut(function(){
            anim_lock = false;
        });
    }
}

$('.tool-tip').hover(function(e){
    //Récupérer le message au niveau des Dolphins
//    var msg = "Vous pouvez remettre votre inscription à plus tard, à conditiond d'avoir rempli au minimum votre nom, votre adresse mail, votre pseudo et votre mot de passe.";
    var msg = Kxlib_getDolphinsValue ("trf_help");
    
    //On crée le selecteur cible
    var id = $(e.target).attr("id");
    var $sel = $(".ins_tooltip_wrapper[data-tpbind='" + id + "']");
//    alert($sel.html());

    ins_tooltip_show($sel, msg);
}, function(e){
    //On crée le selecteur cible
    var id = $(e.target).attr("id");
    var $sel = $(".ins_tooltip_wrapper[data-tpbind='" + id + "']");
    
    ins_tooltip_hide($sel);
});