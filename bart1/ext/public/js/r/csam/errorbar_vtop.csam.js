/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//On l'a déclare à l'exterieur des Objets pour leur permettre de tous utiliser cette fonction
function _PositionBar (s) {
        var wl = $(s).data("wloc").split(",");
        //mc = MyCss
        var mc = new Object();
        //*
        $.each(wl, function(i,v){
            //alert(i+" : "+v);
            // f = forcer l'apparition de la valeur 0
            var f = false;
            if ( v.toString().match(/do/g) ) {
//                alert("FORCE");
                f = true;
                v = v.toString().replace("do",'');
//                alert("In Force => "+v);
            }
            
            if ( parseInt(v) || f ) {
//                alert("V is defined as => "+v);
                switch (i) {
                    case 0 :
//                            alert(i+" : "+v);
                            mc["top"] = ""+v+"px";
                        break;
                    case 1 :
//                            alert(i+" : "+v);
                            mc["right"] = ""+v+"px";
                        break;
                    case 2 :
//                            alert(i+" : "+v);
                            mc["bottom"] = ""+v+"px";
                        break;
                    case 3 :
//                            alert(i+" : "+v);
                            mc["left"] = ""+v+"px";
                        break;
                    case 4 :
//                            alert(i+" : "+v);
                            mc["z-index"] = ""+v+"";
                        break;
                }
            }
//            alert("MyCSS => "+i+" : "+Mycss[i]);
        });
        //*/
        //*
        $(s).css(mc);
}


//On crée l'objet ici pour garantir au mieux qu'il ne sera pas utiliser depuis l'exterieur
 function StackHandler () {
        /* La pile est stockée dans le tableau window.esk. Ce tableau est instancié dans *-index.ix.js */
         
         //Permet de savoir l'interval de temps necessaire pour controller la pile
        this.checkStk = 10000;
         
        // l = lock. Par defaut 0 (non locké)
        //La précaution peut sembler absurde MAIS je le fais quand même
        this.l = 0;
        
        //Fonction de Display
        this.fd;
        
        this.Start = function () {
            setInterval(function(){
                this.LaunchErr();
            },EB_T);
        };
        
        this.PushNewErr = function (o,wlo) {
            
            Kxlib_Log("Push new ERR_OBJECT");
            /* *
             * RULES : o : l'objet erreur; wlo : WITH_LAUNCH_OPTION, ce qui veut dit qu'il lancer la procédure d'affichage [...]
             *  [...] tout de suite et ne pas attendre la procédure différée.
             *  Par défaut, la valeur est TRUE
             * 
             * How To Build => L'id doit être unique aussi on choisit d'utiliser un système simple, celui di timestamp.
             * Un tableau est envoyé à window.esk : [timestamp,Error_Object]
             */
            //alert(typeof wlo);
            wlo = ( typeof wlo === "boolean" && wlo ) ? wlo : false;
            
            if ( !this.l ) {
                Kxlib_Log("New ERR_OBJECT is ALLOWED to be pushed");
                this.l = 1;
                var t = new Date().getTime().toString();
                window.esk.push([t.toString(),o,false]);
                this.l = 0;
                Kxlib_Log("New ERR_OBJECT is NOT IN STACK");
//                alert(window.esk.length);
                if ( wlo && window.esk.length === 1 ) {
//                    alert("BFB");
                    this.LaunchErr(t);
                    Kxlib_Log("Due to wlo OPTION, Display is launch");
                }
                
                //Mise à jour du nombre d'erreurs
                this.UpdateErrPendingNb();
                
                //DEBUG : Lister les elements dans stacks
                this.Debug_ListStackElements();    
                    
                //Libre au CALLER de faire ce qu'il veut avec.
                return t;
            } else {
                Kxlib_Log("New ERR_OBJECT is NOT ALLOWED. Retry !");
                this.PushNewErr(o);
                
                //DEBUG : Lister les elements dans stacks
                this.Debug_ListStackElements();
            }
        };
        
        this.Debug_ListStackElements = function ()  {
            if ( !window.esk.length ) {
                $("#list-id-esk div span").remove();
                return;
            }
                
            $("#list-id-esk div span").remove();
            
            $.each(window.esk, function(ix,v){
                var $o = $('<span/>').html(ix+": "+v[0]+","+v[2]);
                
                $("#list-id-esk div").prepend($o);
            });
        };
        
        this.UpdateErrPendingNb = function (s) {
            var cn = 0;
            
            //On calcule le nombre d'ERR_OBJECT en attente
            $.each(window.esk,function(ix,v){
                if ( v[2] === false )
                    cn++;
            });
//            alert("ici");
            //TODO : Soit envoyer le selcteur soit choisir celui par défaut
            if ( cn > 1 ) {
                //On affiche
                $(".kxlib-dflt-error-bar").first().find(".e-b-v-l-e-nb").html(cn);
                $(".kxlib-dflt-error-bar").first().find(".e-b-vtop-list-err").removeClass("this_hide");
            } else {
                $(".kxlib-dflt-error-bar").first().find(".e-b-vtop-list-err").addClass("this_hide");
            }
            
            //TODO : à X_ERR_MAX déclencher un événement qui va (1) empecher l'ajout de nouvelle erreur pour bloquer le compteur (2) Rédémarre la page 
        };
        
        this.LaunchErr = function (i) {
            Kxlib_Log("StackHandler will now Treat an Err_Obect");
            /**
             * RULES : i est l'identiant 'timestamp'. Grace à cet identifaint LaunchErr peut executer une objet erreur même [...]
             * [...] même s'il n'est pas en debut de liste.
             * 
             * Dans notre contexte, il s'agit d'un cas exceptionnel.
             * 
             * RAPPEL : La composition de chaque élément est [timestamp(String),Err_Object,Est-ce que l'objet a été exécuté?]
             */
//            alert(i);
            //On s'assure qu'il n'y a pas déjà un élément dans la pile
            if ( !KgbLib_CheckNullity(i) && !window.esk.length ) {
                Kxlib_Log("Spefific ERR_OBJECT TREATMENT started !");
                alert("LAUNCH just 1");
                var i = i.toString();
                var th = this;
                $.each(window.esk, function (ix,v) {
                    //S'il s'agit du bon objet et qu'il n'a pas déjà été traité
                    Kxlib_DebugVars(["Index => "+ix+"; VALUE => "+v]);
//                    alert(typeof i);
//                    alert("TIMESTAMP (v[0]) => "+v[0]+"; ARG => "+i);
//                    alert("BREAK_HERE");
                    if ( v[0] === i && !v[2] ) {
                        Kxlib_Log("Spefific ERR_OBJECT FOUND and valid!");
//                        alert("BREAK_HERE");
                        //La référence fd est inscrit dans le prototype à la création de l'objet
                        th.fd(ix, v[1]);
//                        alert("BREAK here !");
                        
                        if ( !KgbLib_CheckNullity(window.esk[ix][2].t,true) && window.esk[ix][2].t === "a" ) {
                            //On signale que l'erreur a été traitée en la retirant
                            window.esk[ix][2] = true;
                            this.RemoveFromStack(ix);
                        } 
                        
                        //Sinon on ajoute on indique qu'il y une erreur de plus à traiter. (incrémentation)
                        th.UpdateErrPendingNb();
                    }
                });
            } else {
                //On récupère le dernier élément dans la liste et on l'exécute
                var cn = window.esk.length;
                
                if ( cn === 0 )
                    return;
                
                cn = cn - 1;
                
                //S'il n'a pas déjà été traité
                if ( !KgbLib_CheckNullity(window.esk[cn]) && !window.esk[cn][2] ) {
                    
                    var lo = window.esk[cn][1], er = window.esk[cn][0];
                    //La référence fd est inscrit dans le prototype à la création de l'objet
                    this.fd(cn,lo);
                    //*
                    if ( window.esk[cn][1].t === "a" ) {
                        //On signale que l'erreur a été traitée en la retirant
                        window.esk[cn][2] = true; //Ne sert sans doutes à rien de mettre TRUE mais bon, à voir avec les évolutions
                        this.RemoveFromStack(ix);
                    }
                    //*/
                    //On renvoit l'identifaint au CALLER. A lui de faire ce qu'il veut avec.
                    return er;
                    
                    //Ici on ne gere donc pas l'affichage des erreurs restant dans la pile
                }
            }
            
        };
        
        this.LaunchNextErr = function () {
            Kxlib_Log("StackHandler will now Treat Next ERR_OBJECT");
            
            //On récupère le dernier élément dans la liste et on l'exécute
            var cn = window.esk.length;

            if ( cn === 0 )
                return;

            cn = cn - 1;

            //S'il n'a pas déjà été traité
            if ( !KgbLib_CheckNullity(window.esk[cn]) && !window.esk[cn][2] ) {

                var lo = window.esk[cn][1], er = window.esk[cn][0];
                //La référence fd est inscrit dans le prototype à la création de l'objet
                this.fd(cn,lo);
                /*
                if ( window.esk[cn][1].t === "a" ) {
                    //On signale que l'erreur a été traitée en la retirant
                    window.esk[cn][2] = true; //Ne sert sans doutes à rien de mettre TRUE mais bon, à voir avec les évolutions
                    this.RemoveFromStack(window.esk[cn][0]);
                }
                //*/
                //On renvoit l'identifaint au CALLER. A lui de faire ce qu'il veut avec.
                return er;

                //Ici on ne gere donc pas l'affichage des erreurs restant dans la pile
            }
        };
        
        this.RemoveFromStack = function (i) {
            //TODO: Retirer une erreur de la pile. 
            var th = this;
            $.each(window.esk, function(ix,v){
//                alert(typeof i);
                if ( !KgbLib_CheckNullity(v) && ix.toString() === i.toString() ) {
                            
                    window.esk.splice(ix,1);
                    Kxlib_Log("L'erreur avec la référence : "+i+", a été retirée de la pile !");
                    
                    //DEBUG : Lister les elements dans stacks
                    th.Debug_ListStackElements();
                }
            });
        };
        
        this.DestroyStack = function () {
            window.esk = new Array();
        };
}

function ErrorBarVTop () {
    this.wait = 5000;
    //Maximum d'erreurs tolérées avant de demander à l'utilisateur de reload
    this.MAX_TOLERATED = 10;
    
    /**
     * Si l'utilisateur n'a pas fourni de selecteur, on récupère celui par défaut.  
     * En temps normal, la très grande majorité des pages ont une barre identiable via ce selecteur.
     * 
     * Si plusieurs elements sont liés à ce selecteur, l'opération est annulée.
     * @type String
     */
    this._dfltBarSl = ".kxlib-dflt-error-bar";
    
    this.CloseBar = function(s){
        var sh1 = new StackHandler();
        
        //Cas 1 : Il n'y a aucune autre erreur dans la pile
        if ( window.esk.length === 1 ) {
            Kxlib_Log("Fermeture de la barre d'erreur suivant le cas : 1 seule erreur dans la pile.");
            
            //On cache la barre d'erreur
            $(s).addClass("this_hide");
            
            //On retire le texte
            $(s).find(".e-b-vtop-msg").html("");
        
            //On déclare l'erreur comme traitée en la supprimant !
            var er = $(s).data("eref");
//            alert(typeof er+"ligne 283");
//            alert(s);
            sh1.RemoveFromStack(er);
            
            //On enleve la EREF (la reférence de l'erreur qui n'est rien d'autre que son index dans la pile)
            $(s).data("eref","");
            
            //On met à jour le nombre d'erreurs
            sh1.UpdateErrPendingNb();
        } else {
            Kxlib_Log("Fermeture de la barre d'erreur suivant le cas : plusieurs erreurs dans la pile.");
            //Cas 2 : Il y a d'autres erreurs dans le pile
            
            var er = $(s).data("eref");
//            alert(typeof er+"ligne 296");
            //On retire l'erreur de la pile
            sh1.RemoveFromStack(er);
                
            
            //On affiche l'erreur suivante
            sh1.LaunchNextErr();
            
            //On met à jour le nombre d'erreurs
            sh1.UpdateErrPendingNb();
        }
            
        
        /*
//        alert("CLOSA");
       
        
        //On retire l'erreur de la pile
        $.each(window.esk,function(ix,v){
            var er =  $(s).data("eref");
            
            if ( er === v[0] ) {
                alert("BREAK_VILLAGE");
                Kxlib_Log("ERR_OBJECT '"+er+"' closed!");
                window.esk[ix][2] = true;
            }
                
        });
        //*/
    };
    
    
    this.EB_DeclareErr = function (i,m,t) {
        
        //On verifie qu'on a pas atteint le maximum d'erreurs tolérables
        if ( !KgbLib_CheckNullity(window.esk) && window.esk.length === this.MAX_TOLERATED ) {
            //Afficher BlackBoard
            //v = [title:"title",message:"message",(Quitter la page et aller ailleurs)fly:"fly", redir:"redir"]
            var bs = new BlackBoardDialog(), m = Kxlib_getDolphinsValue("err_com_toomuch_msg"), t = Kxlib_getDolphinsValue("err_com_toomuch_title");
            
            var v = {
                "title":t,
                "message":m,
                "fly":"",
                "redir":"reload"
            };
            bs.Dialog(v);
            
            return;
        }
            
        
        /**
         * i  = id ; m = message ; c = code erreur ; t = type de fermeture 
         * Le code erreur permet de mieux gérer les erreurs au niveau du module de Gestion des Erreurs
         *  Exemple :   -> Si l'erreur est déjà signalée, on ne l'a signale plus
         *              -> Si un Caller a fait faire afficher une Erreur qu'un autre ne le fasse pas enlever
         *              -> Si une erreur existe déjà et qu'une nouvelle est déclée on les additionne plutot qu"craser l'ancienne 
         * 
         * On pourrait aussi ajouter le paramètre 'h' pour HOW.
         * Il dirait comment on affiche la barre. Selon quelle animation (fade, slide, etc ...)
         * 
         * [NOTE au 25-05-14] Le paramètre 'c' a été abondonné aussi vite qu'il a été pensé.
         *                    On va plutot utiliser le système de gestion par pile. Ce qui est plus optimisé !
         * 
         */
        var o = {
            "i" : i,
            "m" : m,
            "t" : t
        };
        
        //Permet donne au StackHandler la possibilité d'afficher directement l'erreur
        StackHandler.prototype.fd = this._OpenBar;
        
        var sh = new StackHandler();
        
        sh.PushNewErr(o,true) ;
    };
    
    this._OpenBar = function(er,o) {
         /**
         * er = ERR_REFERENCE (TimeStamp) ; i  = id ; m = message ; c = code erreur ; t = type de fermeture 
         * Le code erreur permet de mieux gérer les erreurs au niveau du module de Gestion des Erreurs
         *  Exemple :   -> Si l'erreur est déjà signalée, on ne l'a signale plus
         *              -> Si un Caller a fait faire afficher une Erreur qu'un autre ne le fasse pas enlever
         *              -> Si une erreur existe déjà et qu'une nouvelle est déclée on les additionne plutot qu"craser l'ancienne 
         * 
         * On pourrait aussi ajouter le paramètre 'h' pour HOW.
         * Il dirait comment on affiche la barre. Selon quelle animation (fade, slide, etc ...)
         * 
         * [NOTE au 25-05-14] Le paramètre 'c' a été abondonné aussi vite qu'il a été pensé.
         *                    On va plutot utiliser le système de gestion par pile. Ce qui est plus optimisé !
         * 
         */
         var i = o.i, m = o.m;
         
         if (! KgbLib_CheckNullity(o.t) )
             var t = o.t;
         
         //Pour test : #err_bar_in_trpg

         //On crée le selecteur
         var s = (i.toString().indexOf("#") !== -1) ? i : "#"+i;

         //On commence par ajouter le texte ...
         $(s).find(".e-b-vtop-msg").html(m);
         
         //On ajoute la référence à l'erreur
         $(s).data("eref",er);
         
         /*
         //On récupère la hauteur
         h = $(s).css("height");
         //*/
         
         //On position la barre
         _PositionBar(s);
         
         //On affiche la barre
         $(s).removeClass("this_hide");
         
         //[NOTE 21-05-14] : FAUX ! Le type doit être indiqué par le CALLER
         //On récupère le type pour gérer la fermeture
         //var t = $(s).data("type");
         
         //m = Fermeture manuelle; a = Fermeture automatique
         switch (t) {
             case "a" :
                    var th = this;
                    setTimeout(function(){
                        th.CloseBar(s);
                    },this.wait);
                 break;
             case "m":
                    //On affiche la zone contenant le lien de fermeture
                    $(s).find(".e-b-vtop-err-max").removeClass("this_hide");
                 break;
             default :
                    //Si le developpeur c'est trompé dans la définition du type, on ferme automatiquement quand même
                    var th = this;
                    setTimeout(function(){
                        th.CloseBar(s);
                    },this.wait);
                 break;
         }
            
    };
    
    //Ici le Caller n'envoit pas le 
    //[NOTE : 20-05-14] Non testé !
    this.OpenBar_vDol = function(i,dl) {
        var m = Kxlib_getDolphinsValue(dl);
        
        if ( typeof m === "undefined" )
            m = "[ The message following the error is unknow ]";
        
        this.OpenBar(i,m);
    };
}

(function(){
    var o = new ErrorBarVTop();
    
    
    
    $(".jsbind-close-trig").click(function(e){
        e.preventDefault();
        var s = "#"+$(this).data("tar");
        
        o.CloseBar(s);
    });
})();
