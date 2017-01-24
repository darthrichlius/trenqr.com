/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function AsideRight () {
    this.nPost_Mode= "#asR_nav_sug_ppl";
    
    
    this.checkNullity = function(arg) {
        if (arg === null || typeof(arg) === "undefined" || arg === "") {
            return 1;
        } else {
            return 0;
        }
    };
    
    this.openSpecifiedWind = function(arg) {
        //Est ce que la fenetre en focus n'est pas celle de la demande
//        alert("open");
        $_obj = $(arg);
        var $focus = $(".in_sug_focus");
        var itsId = $focus.attr("id");
        
        if ( $_obj.attr("id") === itsId ) {
            //alert('here too, too loud');
            return;
        } else {
            //(Sinon) Fermer la fenetre en focus
            $focus.addClass("this_hide");
            $focus.removeClass("in_sug_focus");
            //Mettre en focus la nouvelle fenetre
            $_obj.removeClass("this_hide");
            $_obj.addClass("in_sug_focus");
        }
    };
    
    this.checkNPMode = function(arg) {
        this.nPost_Mode = ( this.checkNullity(arg) ) ? this.nPost_Mode : arg ;
        var _id;
        //alert("Mode1 : "+arg);
        
        switch (this.nPost_Mode) {
            case "ppl": 
                    _id = "#asR_nav_sug_ppl";
                    this.openSpecifiedWind(_id);
                break;
            case "post": 
                    _id = "#asR_nav_sug_post";
                    this.openSpecifiedWind(_id);
                break;
            case "news": 
                    _id = "#asR_nav_sug_tr";
                    this.openSpecifiedWind(_id);
                break;
            default:
                 return 0;
                break;
        }
        return 1;
        /**
         * Cette instruction sert surtout au mode debug. 
         * Elle permet d'ouvrir la fenete pour afficher les erreurs.
         * Etant donné qu'il n'existe aucun message par defaut, cette instruction ne sert qu'à ouvrir la fenetre.
         * Voir la methode pour gérer les erreurs pour l'implémentation du process 'NPostErrOccur'
         */
        
        //this.closeErrWindow();
    };
    
    this.handleSugMenuSelection = function(arg){
        //alert("here");
        var $arg = $(arg);
        var _t = $arg.data("target");
//        alert( _t);
        if (! this.checkNullity(_t)) {
            var _r = this.checkNPMode(_t);
            
            if (_r) {
                var $o = $(".com_asRm_sel");
//                alert($o.length);
                //*
                if ($o.length === 1) {
//                    alert("bizarre!");
                    //*
                    $o.removeClass("com_asRm_sel");
                    $arg.addClass("com_asRm_sel");
                    //*/
                } else {
                    alert("Remove others classes. Will be longer");
                }
                //*/ 
            }
            //alert("bizarre!");
        }
        
        return;
    };
    
    this.init = function(arg) {
        
        this.checkNPMode(arg, null);
    };
}

(function() {
    var obj = new AsideRight();
    
    obj.init();
    
    $(".com_asRm").click(function(e){
        e.preventDefault();
        obj.handleSugMenuSelection(e.target);
    });
})();