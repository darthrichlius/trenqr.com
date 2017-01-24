/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function WhiteBoardDialog (){
    /* Affiche un message avec comme fond du blanc transparent */
    
    this.DialogIsOpen = function () {
        //RAPPEL : S'il a la classe 'this_hide' elle est fermée
        if ( $("#stop_playin_wbackgr").length )
            return !$("#stop_playin_wbackgr").hasClass("this_hide");
        else return; 
    };
    
    this.CloseDialog = function () {
        $("#stop_playin").addClass("this_hide");
    };
    
    this._DisplayBackground = function () {
        $("#stop_playin").removeClass("this_hide");
    };
    
    this.Dialog = function (v) {
        //v = [title:"title",message:"message",(Quitter la page et aller ailleurs)fly:"fly", redir:"redir"]
//        alert(v.message);
        if ( !KgbLib_CheckNullity(v) && Kxlib_ObjectChild_Count(v) && !KgbLib_CheckNullity(v.message) && !KgbLib_CheckNullity(v.redir) ) {
            var $ws = $("#stop_playin").find("#stop_playin_wbackgr");
            var $bs = $("#stop_playin").find("#stop_playin_blbackgr");
            
            //On vérifie si WhiteBoard est définie
            if ( $ws.length ) {
                //On renseigne le titre s'il existe
                if ( !KgbLib_CheckNullity(v.title) )
                    $ws.find("#s_p_d_title").removeClass("this_hide").html(v.title);
                else
                    $ws.find("#s_p_d_title").addClass("this_hide");
                    
                //On renseigne le message
                $ws.find("#s_p_d_msg").html(v.message);
                
                //On renseigne le lien fly s'il existe
                if ( !KgbLib_CheckNullity(v.fly) ) {
//                    alert("[data-pg='"+v.fly+"']");
                    switch (v.fly) {
                        case "uhome" :
                                var $mhs = $ws.find(".s_p_d_ch_redir[data-pg='"+v.fly+"']"), lk = "http://www.trenqr.com";
                                $mhs.attr("href",lk);
                                $mhs.removeClass("this_hide");
                            break;
                        case "phome" :
                                var $phs = $ws.find(".s_p_d_ch_redir[data-pg='"+v.fly+"']"), lk = "http://www.trenqr.com";
                                $phs.attr("href",lk);
                                $phs.removeClass("this_hide");
                            break;
                        default :
                                $ws.find(".s_p_d_ch_redir").addClass("this_hide");
                            break;
                    }
                    
                }
                else
                    $ws.find(".s_p_d_ch_redir").addClass("this_hide");
                
                //On renseigne le lien de redirection
                switch (v.redir) {
                    case "reload" :
                            $("#s_p_d_ch_valid").attr("href",document.URL);
                        break;
                    case "phome" :
//                            alert("URL => "+document.URL);
                            var lk = "http://www.trenqr.com";
                            $("#s_p_d_ch_valid").attr("href",lk);
                        break;
                    default :
                            $("#s_p_d_ch_valid").attr("href",v.redir);
                        break;
                }
                
                //On affiche Le Board
                $ws.removeClass("this_hide");
//                alert($ws.parent().parent().find("#stop_playin").hasClass("this_hide"));
                //Reaffiche le background au cas où on l'aurait caché sinon la BdD ne peut pas s'afficher
                this._DisplayBackground();
            } else if ( !$ws.length && $bs.length ) {
                //TODO
                //Sinon si WhiteBoard n'est pas définie mais BlackBoard l'est ...
                // ... On switch entre les deux
                
                $bs.attr("id","stop_playin_wbackgr");
                $ws = $("#stop_playin").find("#stop_playin_wbackgr");
                
                //On renseigne le titre s'il existe
                if ( !KgbLib_CheckNullity(v.title) )
                    $ws.find("#s_p_d_title").removeClass("this_hide").html(v.title);
                else
                    $ws.find("#s_p_d_title").addClass("this_hide");
                    
                //On renseigne le message
                $ws.find("#s_p_d_msg").html(v.message);
                
                //On renseigne le lien fly s'il existe
                if ( !KgbLib_CheckNullity(v.fly) ) {
//                    alert("[data-pg='"+v.fly+"']");
                    switch (v.fly) {
                        case "uhome" :
                                var $mhs = $ws.find(".s_p_d_ch_redir[data-pg='"+v.fly+"']"), lk = "http://www.trenqr.com";
                                $mhs.attr("href",lk);
                                $mhs.removeClass("this_hide");
                            break;
                        case "phome" :
                                var $phs = $ws.find(".s_p_d_ch_redir[data-pg='"+v.fly+"']"), lk = "http://www.trenqr.com";
                                $phs.attr("href",lk);
                                $phs.removeClass("this_hide");
                            break;
                        default :
                                $ws.find(".s_p_d_ch_redir").addClass("this_hide");
                            break;
                    }
                }
                else
                    $ws.find(".s_p_d_ch_redir").addClass("this_hide");
                
                //On renseigne le lien de redirection
                switch (v.redir) {
                    case "reload" :
                            $("#s_p_d_ch_valid").attr("href",document.URL);
                        break;
                    case "phome" :
//                            alert("URL => "+document.URL);
                            var lk = "http://www.trenqr.com";
                            $("#s_p_d_ch_valid").attr("href",lk);
                        break;
                    default :
                            $("#s_p_d_ch_valid").attr("href",v.redir);
                        break;
                }
                
                //On affiche Le Board
                $ws.removeClass("this_hide");
                
                //Reaffiche le background au cas où on l'aurait caché sinon la BdD ne peut pas s'afficher
                this._DisplayBackground();
            } else return;
        }
        
        return;
    };
}


function BlackBoardDialog (){
    /* Affiche un message avec comme fond du blanc transparent */
    
    this._DisplayBackground = function () {
        $("#stop_playin").removeClass("this_hide");
    };
    
    this.DialogIsOpen = function () {
        //RAPPEL : S'il a la classe 'this_hide' elle est fermée
        if ( $("#stop_playin_blbackgr").length )
            return !$("#stop_playin_blbackgr").hasClass("this_hide");
        else return;
    };
    
    this.CloseDialog = function () {
        $("#stop_playin").addClass("this_hide");
    };
    
    this.Dialog = function (v) {
        
        //v = [title:"title",message:"message",(Quitter la page et aller ailleurs)fly:"fly", redir:"redir"]
//        alert(v.message);
        if ( !KgbLib_CheckNullity(v) && Kxlib_ObjectChild_Count(v) && !KgbLib_CheckNullity(v.message) && !KgbLib_CheckNullity(v.redir) ) {
//            alert('GO BLACK');
            var $ws = $("#stop_playin").find("#stop_playin_wbackgr");
            var $bs = $("#stop_playin").find("#stop_playin_blbackgr");
            
            //On vérifie si BlackBoard est définie
            if ( $bs.length ) {
//                alert('GO BLACK');
                //On renseigne le titre s'il existe
                if ( !KgbLib_CheckNullity(v.title) )
                    $bs.find("#s_p_d_title").removeClass("this_hide").html(v.title);
                else
                    $bs.find("#s_p_d_title").addClass("this_hide");
                    
                //On renseigne le message
                $bs.find("#s_p_d_msg").html(v.message);
                
                //On renseigne le lien fly s'il existe
                if ( !KgbLib_CheckNullity(v.fly) ) {
//                    alert("[data-pg='"+v.fly+"']");
                    switch (v.fly) {
                        case "uhome" :
                                var $mhs = $bs.find(".s_p_d_ch_redir[data-pg='"+v.fly+"']"), lk = "http://www.trenqr.com";
                                $mhs.attr("href",lk);
                                $mhs.removeClass("this_hide");
                            break;
                        case "phome" :
                                var $phs = $bs.find(".s_p_d_ch_redir[data-pg='"+v.fly+"']"), lk = "http://www.trenqr.com";
                                $phs.attr("href",lk);
                                $phs.removeClass("this_hide");
                            break;
                        default :
                                $bs.find(".s_p_d_ch_redir").addClass("this_hide");
                            break;
                    }
                    
                }
                else
                    $bs.find(".s_p_d_ch_redir").addClass("this_hide");
                
                //On renseigne le lien de redirection
                switch (v.redir) {
                    case "reload" :
                            $("#s_p_d_ch_valid").attr("href",document.URL);
                        break;
                    case "phome" :
//                            alert("URL => "+document.URL);
                            var lk = "http://www.trenqr.com";
                            $("#s_p_d_ch_valid").attr("href",lk);
                        break;
                    default :
                            $("#s_p_d_ch_valid").attr("href",v.redir);
                        break;
                }
                
                //On affiche Le Board
                $bs.removeClass("this_hide");
                
                //Reaffiche le background au cas où on l'aurait caché sinon la BdD ne peut pas s'afficher
                this._DisplayBackground();
            } else if ( !$bs.length && $ws.length ) {
                //TODO
                //Sinon si BlackBoard n'est pas définie mais WhiteBoard l'est ...
                // ... On switch entre les deux
//                alert('GO BLACK 2');
                $ws.attr("id","stop_playin_blbackgr");
                $bs = $("#stop_playin").find("#stop_playin_blbackgr");
//                alert("stop_playin => "+v.title);

                //On renseigne le titre s'il existe
                if ( !KgbLib_CheckNullity(v.title) )
                    $bs.find("#s_p_d_title").removeClass("this_hide").html(v.title);
                else
                    $bs.find("#s_p_d_title").addClass("this_hide");
                    
                //On renseigne le message
                $bs.find("#s_p_d_msg").html(v.message);
                
                //On renseigne le lien fly s'il existe
                if ( !KgbLib_CheckNullity(v.fly) ) {
//                    alert("[data-pg='"+v.fly+"']");
                    switch (v.fly) {
                        case "uhome" :
                                var $mhs = $bs.find(".s_p_d_ch_redir[data-pg='"+v.fly+"']"), lk = "http://www.trenqr.com";
                                $mhs.attr("href",lk);
                                $mhs.removeClass("this_hide");
                            break;
                        case "phome" :
                                var $phs = $bs.find(".s_p_d_ch_redir[data-pg='"+v.fly+"']"), lk = "http://www.trenqr.com";
                                $phs.attr("href",lk);
                                $phs.removeClass("this_hide");
                            break;
                        default :
                                $bs.find(".s_p_d_ch_redir").addClass("this_hide");
                            break;
                    }
                }
                else
                    $bs.find(".s_p_d_ch_redir").addClass("this_hide");
                
                //On renseigne le lien de redirection
                switch (v.redir) {
                    case "reload" :
                            $("#s_p_d_ch_valid").attr("href",document.URL);
                        break;
                    case "phome" :
//                            alert("URL => "+document.URL);
                            var lk = "http://www.trenqr.com";
                            $("#s_p_d_ch_valid").attr("href",lk);
                        break;
                    default :
                            $("#s_p_d_ch_valid").attr("href",v.redir);
                        break;
                }
//                alert("stop_playin => "+$bs.parent().html());
                //On affiche Le Board
                $bs.removeClass("this_hide");
                
                //Reaffiche le background au cas où on l'aurait caché sinon la BdD ne peut pas s'afficher
                this._DisplayBackground();
                
            } else return;
        }
        
        return;
    };
    
}

(function(){
    /*
        //ZONE DE TEST
        var O = new BlackBoardDialog();
        
        var op = {
                title: null,
                message: "Un message fake",
                fly: {
                    isd: false,
                    link: "/faq"
                }, 
                redir:"reload_fly"
            };        
        O.Dialog(op);
    //*/
})()