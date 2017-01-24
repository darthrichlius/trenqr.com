/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * TESTY est le module qui traite l'ensemble des opérations en rapport avec les témoignages.
 * Le module gère aussi bien les messages reçus que ceux envoyés.
 * Ce qui implique qu'il gère : 
 * - la creation
 * - la suppression
 * - le processus de favorite : Favorite/Unfavorite
 * - Le chargement de nouveaux témoignages (AJAX)
 * 
 * @returns {undefined}
 */
function TESTY () {
    this.triggerObj;
    this.overlayIdSel = "#write_testy_max";
    this.targetBloc = "#brain_list_gbck_r";
    this.yellowActionCl = "action_a_gbck";
    this.actionBtnSl = ".action_a";
    this.testyFavBdg = ".testi_fav";
    this.confPo = ".conf_po";
    this.confPoTimerSl = "#conf_po_timer";
    this.confPoTimerMax = 3;
    
    this.ubfurel;
    this.ubfuid;
    this.testyBlocId;
    this.testyType;
    this.testyIsFav;
    
    
    this.centerVert = function () {
        var h = $("#write_testy_black").height();
        //    var w = $("#write_testy_black").width();
        var form_h = $("#write_testy_form").height();

        var top = (h/2) - form_h;
        top = (top < 0) ? 0 :top ;
        //alert(top);
        var topx = top+"px";
        $("#write_testy_form").css("top",topx);
    };  
    
    
    /**
     * NOTE : Cette methode est importée de FPH. Elle a subit des mdifications pour être utiliser sur le present module.
     * @see FPH pour d'autres precisions.
     * 
     * Permet de s'assurer que la cible 
     * @param {type} th
     * @returns {Number|undefined}
     */
    this.IsTriggerAuthentic = function (th) {
        $tarsel = $(th);
        
        this.testyBlocId = $tarsel.data("target");
        
        if( KgbLib_CheckNullity(this.testyBlocId) ) {
            //L'erreur devra etre encoyé au server dans la version production
            Kxlib_DebugVars(["Error : Can't reach target"]);
            return;
        }
        
        this.targetBloc = $tarsel.data("tarbloc");
        
        if( KgbLib_CheckNullity(this.targetBloc) ) {
            //L'erreur devra etre encoyé au server dans la version production
            Kxlib_DebugVars(["Error : Can't reach targetBloc"]);
            return;
        }
        
        this.uaction = $tarsel.data("action");
        
        if( KgbLib_CheckNullity(this.uaction) ) {
            //L'erreur devra etre encoyé au server dans la version production
            Kxlib_DebugVars(["Error : Can't get access to uaction"]);
            return;
        }
        
        return 1;
    };
    
    /**
     * * NOTE : Cette methode est importée de FPH. Elle a subit des mdifications pour être utiliser sur le present module.
     * @see FPH pour d'autres precisions.
     * 
     * Permet de verifier qu'on peut atteindre le bloc contenant la cible
     * ET que l'on peut atteindre la cible dans ce bloc
     * @param {type} bloc
     * @param {type} id
     * @returns {undefined|Number}
     */
    this.IsTargetReachableNAuthentic = function(bloc, id) {
        /**
         * Si on arrive pas joindre l'element, une erreur est déclenchée et le script va s'arreter.
         * C'est dérangeant car on ne pourra avoir aucun retour. 
         * Le seul moyen est d'avoir un moyen pour l'user de remonter l'information.
         */
        var $o;
        try {
            $o = $("#"+bloc+" #"+id);
            this.bfuElSel = "#"+bloc+" #"+id;
        } catch(e) {
            //TODO : Send error to server
            Kxlib_DebugVars(["Can't reach TESTY element !"]);
            return;
        }
        
        var l = $o.html().length;
        
        if(! KgbLib_CheckNullity(l) ) {
            //On s'assure que les deux attributs existent
            try {
                //toString evite le cas ou on aurait 0 et qu'il le considérerait comme faux
                var _bfurel = $o.data("bfurel").toString();
                var _bfuid = $o.data("author").toString();
                var _testyType = $o.data("type").toString();
                //Pas de toString car on veut traiter le caractère booleen
                var _fav = $o.data("fav");
            } catch(e) {
                //TODO : Send error to server
                Kxlib_DebugVars(["TESTY defective ! Miss attr : bfurel, bfuid or type"]);
                return;
            }
            
            /**
             * Contrairement à la version de FPH, ici tous ces paramètres doivent être présent
             */
            if ( !_bfurel || !_bfuid || !_testyType || _fav === "" ) {
                //TODO : Send error to server
                Kxlib_DebugVars(["Error : Element reached is not authentic. Miss bfurel, bfuid, fav, or type"]);
                return;
            }
            
            this.ubfurel = _bfurel;
            this.ubfuid = _bfuid;
            this.testyType = _testyType;
            this.testyIsFav = _fav;
        } 
        
        return 1;
    };
    
    this.TotalReverseTestyReversableChoice = function(o, action, no_rev_txt) {
        var $o = $(o).find(".bind-testyfav-fav");
        //alert($o.html());
        try {
            if (! no_rev_txt) {
                var _n = $o.data("revs");
                $o.data("revs",$o.html());
                $o.html(_n);
            }
            
            if (! KgbLib_CheckNullity(action) ) $o.data("action",action);
            
            $(o).data("fav",0);
            
            return 1;
        } catch(e) {
            return;
        }
    };
    
    /************************************************************************************************/
    /** MAIN METHODES *******************************************************************************/
    this.HandleNewTesty = function () {
        //0: On s'assure que la cible et le visiteur ont une relation de type bijective. flr, flw 
            // On contacte le serveur pour se renseigner en ce qui concerne la relation entre les deux.
            // Si on se rend compte que l'utilisateur a accès au bouton alors que la relation ne le permettait pas on déclenche : incoherence.
            // NOTE : Client-Side transmettra au server sa version des fait : visiteur et cible.
            // NOTE : Mais dans l'url joint à la créationo de la page, il y a aussi ces informations
        //1: On verifie que le taxte a bien moins MAX_CHAR caractères
        //2: On verifie qu'il n'y a pas de lien URL dans le texte
        
        //On envoie au serveur
        //Si tout se passe bien on affiche le message de confirmation ...
        //(Sinon) le message d'erreur Overlay
    };
    
    this.innerRemoveTesty = function (obj,ans) {
        //NOTE : Cette version ne traite que le mode 'sans-echec'
        if (! KgbLib_CheckNullity(ans)) {
            //*
            //Si on ne met pas parent, il selectionne 'span'
            var _id = $(obj).parent().data("target");
            var $ol = $("#"+_id);
            
            //Stop et reinit
            window.clearInterval($ol.data("timer"));
            this.timerID = null;
            
            //Remove
            $op = $ol.find(this.confPo);
            $op.addClass("this_hide");
            
            $ol.fadeOut();
            //On attend avant de supprimer pour laisser le temps à l'animation de se produire
            //L'opération est plus intuitive comme ça
            window.setTimeout(function(){
                $ol.remove();
            },1000);
            //*/
        } else {
            //CAS : user demande un PO
           
            //1: Faire apparaitre le message de conf
            $op = $("#"+this.testyBlocId).find(this.confPo);
            $op.removeClass("this_hide");
            //2: Lancer le timer
            var _time = this.confPoTimerMax;
//            alert($("#"+this.confPoTimerSl).text());
            var $this = this;
            var timerID = setInterval(function(){
                $("#"+$this.testyBlocId).find($this.confPo+" "+$this.confPoTimerSl+"").html("("+--_time+")");
                //La méthode avec --v et -1 est la seule adéquate
                if (_time === -1 ) {
                    //On arrete le timer
                    window.clearInterval(timerID);
                    
                    //On avorte l'opération
                    $("#"+$this.testyBlocId).find($this.confPo).addClass("this_hide");
                    
                    //Reinit
                    $this.timerID = null;
                    _time = $this.confPoTimerMax;
                    $("#"+$this.testyBlocId).find($this.confPo+" "+$this.confPoTimerSl+"").html("("+_time+")");
                }
            },1000);
            $("#"+this.testyBlocId).data("timer",timerID);
        }
    };
    
    //Supprimer un testy qu'on a écrit pour une autre personne
    this.HandleDeleteWTesty = function (obj,ans) {
        this.innerRemoveTesty(obj,ans);
    };
    
    //Supprimer un testy qu'on a ecrit pour une personne quand ce dernier etait Fav
    this.HandleDeleteWFavTesty = function () {
        
    };
    
    //'Retirer' un testy qui a été écrit par une autre personne
    this.HandlePulloutRTesty = function (obj,ans) {
        this.innerRemoveTesty(obj,ans);
    };
    
    //'Retirer' un testy qui a été écrit par une autre personne, quand ce dernier etait Fav
    this.HandlePulloutRFavTesty = function () {
        
    };
    //'Favorite' un testy reçu 
    this.HandleFavoriteRTesty = function () {
        //On s'assure qu'il s'agit bien d'un testy recu
        if ( this.testyType !== 'r' ) {
            //TODO: Send error to sever
            
            Kxlib_DebugVars(["ERROR, INCOHERENCE: Testy is not defined as 'r' but the process reached this function !"]);
            //TODO: Avertir l'utilisateur de l'erreur et qu'on est entrain de le régler. Qu'il recharge la page ou réessait plus tard
            //Reverse le texte
            if ( KgbLib_ReverseText (this.triggerObj, "revs") !== 1 ) {
                //TODO: Send error to sever
                Kxlib_DebugVars(["ERROR: Le contre reverse n'a pas pu être exécuté !"]);
                //TODO: Avertir l'utilisateur de l'erreur et qu'on est entrain de le régler. Qu'il recharge la page ou réessait plus tard
            };
            
            return;
        }
        
        //On s'assure du fait que le message n'est pas déjà Favorite
        if ( this.testyIsFav ) {
            //TODO: Send error to sever
            Kxlib_DebugVars(["ERROR, INCOHERENCE: Testy is already as defined but user wants to fav again !"]);
            //Pas besoin d'avertir l'user ce n'est pas assez grave de son point de vue
            //Reverse le texte
            if ( KgbLib_ReverseText (this.triggerObj, "revs") !== 1 ) {
                //TODO: Send error to sever
                Kxlib_DebugVars(["ERROR: Le contre reverse n'a pas pu être exécuté !"]);
                //TODO: Avertir l'utilisateur de l'erreur et qu'on est entrain de le régler. Qu'il recharge la page ou réessait plus tard
            };
            return;
        }

        //On annule les autres 'Favorite'
        var $ot;
        try{
            $ot = $("#"+this.targetBloc).find('.brain_gbkmsg_mdl[favorite]');
        } catch(e) {
            
        }
        //1: Le Badge
        //alert($("#"+this.targetBloc).find('.brain_gbkmsg_mdl[favorite]').html());
        $ot.find(this.testyFavBdg).addClass("this_hide");
        //2: Le Bouton
        $ot.find(this.actionBtnSl).removeClass("action_a_gbck");
        //3: On effectue un Reverse total
        this.TotalReverseTestyReversableChoice($ot, "rhana_fav");
        //4: On retire l'attribut 'favorite'
        $ot.removeAttr("favorite");
       
        //*
        //On procède au changement de style
        //1) On ajoute le badge 'Favorite'
        $("#"+this.testyBlocId).find(this.testyFavBdg).removeClass("this_hide");
        //2) On change la couleur du bonton
        $("#"+this.testyBlocId).find(this.actionBtnSl).addClass("action_a_gbck");
        //3) On change l'element en le designant comme le fav
        $("#"+this.testyBlocId).data("fav",1);
        $("#"+this.testyBlocId).attr("favorite","true");
        //alert($("#"+this.testyBlocId).html());
//        alert("id = "+$("#"+this.testyBlocId).attr("id")+"; fav = "+$("#"+this.testyBlocId).data("fav"));
//        alert($("#"+this.targetBloc).find('.brain_gbkmsg_mdl[favorite]').html());
        //*/
        //4: On effectue un Reverse total
        this.TotalReverseTestyReversableChoice($("#"+this.testyBlocId), "back_fav",true);
    };
    
    //'Unfavorite' un testy reçu 
    this.HandleUnfavoriteRTesty = function () {
        //Pour des soucis d'efficacité, la fonction ne traitera que le cas 'sans-echec'.
        //C'est a dire, où il n'y a pas de faute, ni de l'user ni du système
        
        //On annule le mode 'Favorite'
        var $ot = $("#"+this.testyBlocId);
        //1: Le Badge
        //alert($("#"+this.targetBloc).find('.brain_gbkmsg_mdl[favorite]').html());
        $ot.find(this.testyFavBdg).addClass("this_hide");
        //2: Le Bouton
        $ot.find(this.actionBtnSl).removeClass("action_a_gbck");
        //3: On effectue un Reverse total
        this.TotalReverseTestyReversableChoice($ot, "rhana_fav",true);
        //4: On retire l'attribut 'favorite'
        $ot.removeAttr("favorite");
    };
    
    this.ValidForm = function() {
        alert("Ici houston");
    };
    
    this.OpenCloseTWOverlay = function (arg) {
        switch(arg) {
            case 'c' :
                    $(this.overlayIdSel).addClass("this_hide");
                break;
            case 'o' :
                    this.centerVert();
                    $(this.overlayIdSel).removeClass("this_hide");
                break;
        }
    };
    
    this.CheckOperation = function(th) {
        //On considere que th (this) a été verifié au préalable
        //On aurait pu faire cela en une ligne
        
        if (! this.IsTriggerAuthentic(th) && 1 )
            return;
        
        //Maintenant qu'on s'est que tout est ok. On sauvegarde le declencheur.
        //On peut le réutiliser s'il a une fonction de switch
        if ( $(th).hasClass("kgb_el_can_revs") ) this.triggerObj = th;
        
//        alert(this.uaction);
        if (! this.IsTargetReachableNAuthentic(this.targetBloc, this.testyBlocId) && 1 ) return;
        
        switch( this.uaction ) {
            case "back_fav":
                    this.HandleUnfavoriteRTesty();
                break;
            case "rhana_fav":
                    this.HandleFavoriteRTesty();
                break;
            case "del_wtesty" :
                    this.HandleDeleteWTesty();
                break;
            case "po_rtesty" : 
                    this.HandlePulloutRTesty();
                break;
            default :
                    //TODO : Incoherence
                break;
        }
    };
    
    this.Init = function (arg) {
        if (KgbLib_CheckNullity(arg)) {
            this.OpenCloseTWOverlay('c');
        } else {
            this.OpenCloseTWOverlay('o');
        }
    };
}

function Testy_Receiver (){
    var obj = new TESTY();
    
    obj.Init();
    
    $("#write_testy_black").click(function(e){
//        e.stopPropagation();
//        e.preventDefault();
        //Rentre dans la suite des process pour empecher que le click soit transmit aux enfants;
        //$(this).children().toggle();
        obj.OpenCloseTWOverlay('c');
    });
    
    $("#write_testy_form_max").click(function(e) {
        e.stopPropagation();
   });
   
   $("#asR_nav_start_writn_a").click(function(){
//       e.preventDefault();
       obj.OpenCloseTWOverlay('o');
   });
   
//   $("#write_testy_form").bind("submit",);
   
   $("#w_t_f_post").click(function(e){
       e.preventDefault();
       obj.HandleNewTesty();
   });
   
   $(".bind-testyfav").click(function(e){
       e.preventDefault();
       obj.HandleNewTesty();
   });
   
   $(".conf_po").click(function(e){
       e.preventDefault();
       if( $(e.target).parent().hasClass("conf_del") ) obj.HandleDeleteWTesty(e.target,true);
       else obj.HandlePulloutRTesty(e.target,true);
   });
   
   this.Routeur = function (th){
        if ( KgbLib_CheckNullity(th) ) return; 
        
        obj.CheckOperation(th);
    };
};