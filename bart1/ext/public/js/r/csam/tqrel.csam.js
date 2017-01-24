

/**********************************************************************************************************************************************************************************************************************************/ 
/**********************************************************************************************************************************************************************************************************************************/ 
/**********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************                                         *********************************************************************************************/
/********************************************************************************************             FILE : FPH.CSAM             *********************************************************************************************/
/********************************************************************************************                                         *********************************************************************************************/
/**********************************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************************/ 


/**
 * RAPPEL : Ce module n'effectue aucun traitement reel.
 * Son unique but est de préparer une action coté serveur en se basant
 * sur les informations présentes coté client-side.
 * 
 * In fine c'est le server qui décide de suivre les recommandations du module ou 
 * de déclencher une erreur.
 * 
 * Dans le cas où le server valide l'opération, le module continu le trvail au niveau 
 * du design.
 * 
 * Cependant, il permet de changer quelques paramètres visuelles
 * permettant de donner des points de repères à l'utilisateur dans la compréhension du processus.
 * Ces indicateurs sont avant tout des un changement de badges.
 */
/**
 * GLOSSAIRE :
 * FPH = FollowProcessHandler
 * bind-fluser = bind-followuser => Permet d'identifier un element qui déclenche un changement dans la relation entre deux contacts.
 * target = Permet d'extraire l'id de l'element à traiter. L'Id à un suffixe 'bfuid' suivi de l'uid-ext de l'utilisateur
 * bfuid = bindFollowUser_id => Un indentifiant pour identifier le bind-foll dans la liste
 * action = Permet d'identifier l'objet de l'action déclenchée par l'utilisateur
 *  - back_* : Retire un etat à un bfu
 *      * : flw (Following), blk (Blocked)
 *  - rhana_* : Attribut un etat 
 *      * flw (Following), sugflw (Following From Suggest), srflw, blk (Blocked)
 *      [sug_flw : permet d'avertir le Server que l'ajout s'est faite à l'issue d'une suggestion]
 *      [srch_flw : permet d'avertir le Server que l'ajout s'est faite à l'issue d'une recherche]
 *  NOTE: On se base toujours sur le visiteur. Cette zone n'etant accessible qu'au owner, 'flr' veut dire :
 *  : la cible est désigné comme un des abonnés au compte de 'owner'.    
 *  Aussi, on admettra aisement qu'on ne peut pas retirer (ni donner) l'owner ne peut pas demander à back' sur un 'flr'.
 *  Il s'agirait d'une incoherence!
 *  Mais cela est possible sur un 'flw' donc celui qu'il suit.
 *  bfurel = BindFollowUserrelation
 *      * : sug (Suggested), flr (Follower), flw (Following), blk (Blocked)
 */
function FPH() {
    
    var gt = this;
    
    //[NOTE 15-09-14] @author L.C. bfuid = BlocFollowUserId ?
    this.bfuid;
    this.targetBloc;
    this.uaction;
    this.ubfurel;
    
    //Selector de la repesentation d'un user dans le dom
    this.bfuElSel;
    //Element d'où vient l'action. Il sagit soit d'un bouton ou un sous-menu la +part du temps
    this.triggerObj;
    
    
//    //URQID => Bloquer un utilisateur depuis SLAVE
//    this.srvRhanaBlk_url = "http://127.0.0.1/korgb/ajax_test.php";
//    this.srvRhanaBlk_uq = "rhana_blk";
    
    //URQID => Débloquer un utilisateur depuis SLAVE
//    this.srvBackBlk_url = "http://127.0.0.1/korgb/ajax_test.php";
//    this.srvBackBlk_uq = "back_blk";
    
    
    
    /*************************************************************************************************************************************/
    /********************************************************* PROCESS SCOPE *************************************************************/
    /*************************************************************************************************************************************/
    //STAY PUBLIC
    this.ChkOper = function(x) {
        try {
            
            //On considere que th (this) a été verifié au préalable
            //On aurait pu faire cela en une ligne
            
            var d = this.IsTriggerAuthentic(x);
            if ( KgbLib_CheckNullity(d) ) {
                /*
                 * TODO :
                 * (1) Annoncer l'erreur auprès de l'utilisateur pour qu'il sache pourquoi rien ne bouge
                 * (2) Envoyer une erreur au serveur
                 */
                return;
            }
            
            
            //Maintenant qu'on s'est que tout est ok. On sauvegarde le declencheur.
            //On peut le réutiliser s'il a une fonction de switch
//        if ( $(th).hasClass("kgb_el_can_revs") ) this.triggerObj = th;
            
//        alert(this.uaction);
//        if (! this.IsTargetReachableNAuthentic(this.targetBloc, this.bfuid) && 1 ) return;
            
            switch (d.a) {
                case "back_flw":
                    _f_BackFlw(x, d);
                    break;
                case "rhana_flw":
                    _f_RhanaFlw(x, d);
                    break;
//            case "back_blk":
//                    this.Process_BackBlk();
//                break;
//            case "rhana_blk":
//                    this.Process_RhanaBlk();
//                break;
//            case "rhana_sugflw": 
//                    this.Process_RhanaSugFlw();
//                break;
//            case "rhana_srflw": 
//                    this.Process_RhanaSrFlw();
//                break;
                default :
                    //TODO : Incoherence
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Gdf = function () {
        var df = {
            "actionList"        : ["back_flw","back_blk","rhana_flw","rhana_sugflw","rhana_srflw","rhana_blk"],
            "bfurel"            : ["flr","flg","blk"],
            "actionChoicesSel"  : "action_foll_choices",
            "actionChoiceFolg"  : ".bind-fluser-folg",
            //Badges Selector
            "badgeFlwSel"       : ".br_foll_folg",
            "badgeFlw"          : ".br_foll_folg",
            "badgeBlkSel"       : ".br_foll_folg_blo",
            "badgeBlk"          : ".br_foll_folg_blo",
        };
        
        return df;
    };
    
    var _f_SwFolBtn = function (x,rbo) {
        //rbo : ReturnButtonObject
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var t = $(x).data("target");
        if ( KgbLib_CheckNullity(t) ) {
            return;
        }
        
        t = Kxlib_ValidIdSel(t);
        $(x).fadeOut().addClass("this_hide");
        $(t).hide().removeClass("this_hide").fadeIn();
        
        return ( rbo === true ) ? $(t) : true;
    };
    
    var _f_UltmtFlwgWhich = function (x) {
//    this.UltmtFlwgWhich = function (x) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var a = $(x).data("action");
        switch (a) {
            case "follow":
                    _f_TmlnrFlwHdr(x);
                break;
            case "unfollow":
                    _f_TmlnrUflwHdr(x);
                break;
            default :
                return;
        }
    };
    
    /*
     * Permet de s'assurer que la déclencheur est authentique.
     * Pour cela on vérifie s'il a les données nécessaires :
     *  (1) Le bloc contenant la cible
     *  (2) La cible (l'élément représentant l'utilisateur dans la liste)
     *  (3) L'action (Follow, Unfollow, ou autres en fonction des nouvelles fonctionnalités)
     * 
     * @param object x
     * @returns {undefined|Boolean}
     */ 
    this.IsTriggerAuthentic = function (x) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        /*
         * trg : trigger
         * b : bloc (le bloc qui contient la cible)
         * a : action
         */
        var $tgr = $(x), _trg, _b, _a;
        
        _trg = $tgr.data("target");
        
        if ( KgbLib_CheckNullity(_trg) ) {
            //L'erreur devra etre encoyé au server dans la version production
//            Kxlib_DebugVars([Error : Can't reach target"]);
            return;
        }
        
        _b = $tgr.data("tarbloc");
        if ( KgbLib_CheckNullity(_b) ) {
            //L'erreur devra etre encoyé au server dans la version production
//            Kxlib_DebugVars([Error : Can't reach targetBloc"]);
            return;
        }
        
        _a = $tgr.data("action");
        if ( KgbLib_CheckNullity(_a) ) {
            //L'erreur devra etre encoyé au server dans la version production
//            Kxlib_DebugVars([Error : Can't get access to uaction"]);
            return;
        }
        
        var o = {
            "trg"   : _trg,
            "b"     : _b,
            "a"     :_a
        };
        
        return o;
    };
    
    /**
     * Informe le caller sur le statut du BFU.
     * 
     * La fonction renvoie undefined si BFU n'a aucune relation avec le visiteur.
     * La fonction renvoie false si la relation ne convient pas pour ce type d'action
     * La fonction renvoie true si la relation entre les deux protagonistes permet l'action
     * 
     * Note : C'est au Caller de gérer l'erreur et non à la fonction
     * 
     */
    var _f_ChkUrlAuthyBfrProcz = function (rl,list) {
//    this.ChkUrlAuthyBfrProcz = function (rl,list) {
        if ( KgbLib_CheckNullity(rl) | KgbLib_CheckNullity(list) ) { 
            return;
        }
        
        if ( list.match(",") ) {
//            alert('found ,'+" "+relcode+" "+list);
            var _urel = list.trim().split(",");
            if ( KgbLib_CheckNullity(_.intersection(_urel, [rl])) ) 
                return false; 
        } else if ( list !== rl ) {
            //alert("not found");
            return false;
        }
        
        return true;
    };
    
    //Traite le cas où on decide de ne plus suivre un contact
    var _f_BackFlw = function(x,i) {
//    this.Process_BackFlw = function(x,i) {
        //On suppose que le switch de texte a déjà eu lieu
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(i) )
            return;
        
//        Kxlib_DebugVars([i.trg, i.b, i.a],true);
        
        var $t = $(Kxlib_ValidIdSel(i.trg)), $b = $(Kxlib_ValidIdSel(i.b)), a = i.a;
        
        var urel = $t.data("bfurel");
        var _r = _f_ChkUrlAuthyBfrProcz("flg",urel);
        
        if ( typeof _r === "undefined" ) {
            //TODO : Send error to server
//            Kxlib_DebugVars([ERR : Incoherence, BFU should have had at least one urel code']);
            return;
        } else if (! _r ) {
            //TODO : Send error to server
//            Kxlib_DebugVars([ERR : Incoherence, BFU should have had code : flw ']);
            return;
        }
       
       //2) On retire le badge 'Following'
        //Pour l'instant on ne traite que le cas des listes Flr/Flg
        if (! $t.find(_f_Gdf().badgeFlwSel).length ) {
            //TODO : Envoyer au serveur. 
//            Kxlib_DebugVars([ERR : Incoherence, Can't find Following badge !"]);

            /*
             * On ne coupe pas car si le badge n'est pas présent visuellement ce n'est pas dramatique
             */
        } else {
            $t.find(_f_Gdf().badgeFlwSel).fadeOut(250);
        }
        
        //3) On change action. On le transforme en rhana_flw.
        //NOTE : Seulement si Trigger est reverse
        if (! KgbLib_CheckNullity(x) ) {
            
            //On change action
            $(x).data("action","rhana_flw");
            
            //On retire 'flg' dans la liste des urel
            var _urel = urel.trim().split(",");
            
            var _nst = _.without(_urel,"flg").join(",");
            $t.data("bfurel",_nst);
        }
        
        
        //4) Avertir le serveur
        var id = $t.data("bfuid"), s = $("<span/>");
        _f_Srv_BackFlg($t,id,s);
        
        $(s).on("operended",function(e,d){
            //On attend : nombre d'abonnements
            if ( KgbLib_CheckNullity(d) || KgbLib_CheckNullity(d.flg_nb) )
                return;
            
            //** On met à jour le nombre de Following **//
            
            //On met à jour dans ProfilBox
            $(".jb-u-sp-flg-nb").text(d.flg_nb);
            
            //On met à jour dans le header
            $b.siblings(".brain_f_title").find("span").text(d.flg_nb);
            
        });

    };
    
    
    //Traite le cas où on decide de suivre 
    //(Ce cas suppose surtout le cas où l'on va sur un compte et on follow)
    var _f_RhanaFlw = function(x,i) {
//    this.Process_RhanaFlw = function(x,i) {
        //On suppose que le switch de texte a déjà eu lieu
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(i) )
            return;
        
//        Kxlib_DebugVars([i.trg, i.b, i.a],true);

        var $t = $(Kxlib_ValidIdSel(i.trg)), $b = $(Kxlib_ValidIdSel(i.b)), a = i.a;
        
//        var _r = _f_ChkUrlAuthyBfrProcz("blk");
//        
//        if ( _r ) {
//            //TODO : Send error to server
//            Kxlib_DebugVars([ERR : Incoherence, User triggered action "rhana_flw" when bfu is signaled as "blocked". Should be impossible']);
//            return;
//        }
        
        var urel = $t.data("bfurel");
        var _r = _f_ChkUrlAuthyBfrProcz("flr",urel);
        
        if ( _r ) {
            //TODO : Send error to server
//            Kxlib_DebugVars([ERR : Incoherence, User triggered action "rhana_flw" when bfu IS ALREADY signaled as "flw". Should be impossible']);
            return;
        }
        
        /*
         * NOTE 16-09-14] @author L.C.
         * On effectue les opération seulemmant après avoir reçu le retour du serveur pour éviter au maximum les erreurs
         * J'ai donc retiré de cette zone les opérations :
         *  (1) Ajouter le badge
         *  (2) Faire le reverse
         *  (3) Ajouter UREL
         *  
         * Les instructions ont été transférées dans la zone exécutée seulement lorsque le serveur répond
         */
        
        
        //4) Avertir le serveur
        var id = $t.data("bfuid"), s = $("<span/>");
        _f_Srv_RhanaFlg($t,id,s);
        
//        var th = this;
        $(s).on("operended",function(e,d){
            //On attend : nombre d'abonnements
            if ( KgbLib_CheckNullity(d) || KgbLib_CheckNullity(d.flg_nb) )
                return;
            
            //2) On intègre le badge 'Following'
            //Pour l'instant on ne traite que le cas des listes Flr/Flg
            if (! $t.find(_f_Gdf().badgeFlwSel).length ) {
                //TODO : Envoyer au serveur. 
//                Kxlib_DebugVars([ERR : Incoherence, Can't find Following badge !"]);

                /*
                 * On ne coupe pas car si le badge n'est pas présent visuellement ce n'est pas dramatique.
                 * Surtout si l'utilisateur l'a retiré. Au rechargement, il apparaitra et tout le monde sera content.
                 */
            } else {
                $t.find(_f_Gdf().badgeFlwSel).fadeIn(250);
            }

            //3) On change action. On le transforme en back_flw.

            //On change action
            $(x).data("action","back_flw");

            //On ajoute 'flg' dans la liste des urel
            var _nst = (urel === "") ? "flg" : urel+= ",flg";
            $t.data("bfurel",_nst);
            
            //** On met à jour le nombre de Following **//
            
            //On met à jour dans ProfilBox
            $(".jb-u-sp-flg-nb").text(d.flg_nb);
            //On met à jour dans le header
            $b.siblings(".brain_f_title").find("span").text(d.flg_nb);
            
        });
    };
    
    var _f_TmlnrFlwHdr = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            } 
        
            if ( $(x).data("lk") === 1 ) {
//            Kxlib_DebugVars([Processing ..."]);
//                alert("BLOQUEROh ! -> Folw");
                return;
            }
            
            //On lock le bouton en attendant une réponse ou une erreur. Cela éviter de bourrer le script
            $(x).data("lk", 1);
            
            //TODO : Si après 3 secondes, l'user n'a pas changer d'avis on continu le process
//        setTimeout(function() {
//            if ( $((".jb-tmlnr-ufol-chcs.this_hide")).data("action") !== "follow" ) 
//                return;
            
            
            
            //On récupère l'id du compte visité (On peut soit prendre dans Dolphins ou dans target, ça n'a aucune importance)
            //On récupère aussi les données en ce qui concerne la page pour faire d'autres vérifications au niveau du serveur. 
            //... Sans la page le traitement serait faussé et/ou non sur
            var i = Kxlib_GetOwnerPgPropIfExist().ueid, p = Kxlib_GetPagegProperties();
            var s = $("<span/>");
            
            //On signale au serveur l'intention de suivre l'utilisateur
            _f_Srv_FlwRl(i, p, 'f', x, s);
            
            //Afficher le loader
            _f_ShwLdg("hdr");
            /*
             * [DEPUIS 10-05-15] @BOR
             * On masque aussi la flèche qui fait apparaitre le menu.
             */
            _f_MnTrgr();
                
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //Masquer le loader
                _f_HidLdg("hdr");
                /*
                 * [DEPUIS 10-05-15] @BOR
                 * On masque aussi la flèche qui fait apparaitre le menu.
                 */
                _f_MnTrgr(true);
                
                //On change les icones
                if ($(x).data("action") === "follow") {
                    $(".jb-tmlnr-urel-m-box").removeClass("this_hide");
                    
                    $(".jb-tmlnr-urel-m-mrbox").removeClass("this_hide");
                    $(".jb-tmlnr-urel-m-frdbox").addClass("this_hide");
                } else {
                    $(".jb-tmlnr-urel-m-box").addClass("this_hide");
                    
                    $(".jb-tmlnr-urel-m-mrbox").addClass("this_hide");
                    $(".jb-tmlnr-urel-m-frdbox").addClass("this_hide");
                }
                
                //On change de bouton
                /*
                 * [DEPUIS 10-05-15] @BOR
                 * On récupère maintenant la référence du bouton cible, qui est le nouveau bouton affiché.
                 * Cela permet de le bloquer et d'empecher l'utilisateur d'effectuer une action qui nuierait à la bonne marche de l'opération.
                 */
                var bo = _f_SwFolBtn(x,true);
                if (! bo ) {
                    return;
                } else {
                    $(bo).data("lk",1);
                }
                
                //Notifier l'utilisateur qu'il suit à présent OWNER
                var N = new Notifyzing();
                var o = {
                    "owner": d.upsd
                };
                N.FromUserAction("UA_ULTMT_FOLL", o);
                
                //On change UREL
                Kxlib_ChangeActorsUrel(d.urel.toLowerCase());
                
                /*
                 * [DEPUIS 10-05-15] @BOR
                 * On recharge dans tous les cas après avoir affiché la barre d'attente
                 */
                //Faire apparaitre l'overlay qui informe qu'une redirection encours
                if ( $(".jb-pg-sts").length ) {
                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                    $(".jb-pg-sts").removeClass("this_hide");
                }
                location.reload();
                return;
//                alert("PASSE ~498!");
                //On unlock le bouton 
                $(x).data("lk", 0);
                
                //-- Mettre à jour OW
                //upsd
                $(".jb-tmlnr-hdr-psd").text(d.upsd);
                //uflr
                $(".jb-u-sp-flwr-nb").text(d.uflr);
                //uflw
                $(".jb-u-sp-flg-nb").text(d.uflw);
                //ucap
                $(".jb-u-sp-cap-nb").text(d.ucap);
                //utnb
                //[DEPUIS 10-05-15] @BOR
                $(".jb-acc-spec-trnb").text(d.utnb);
//                $(".jb-acc-spec-artnb").text(d.utnb);
            });
            
//        },3000);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
   var _f_TmlnrUflwHdr = function(x) {
//    this.TmlnrUflwHdr = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
//                Kxlib_DebugVars([Processing ..."]);
//                alert("BLOQUEROh -> Ufolw !");
                return;
            }
        
            //On lock le bouton en attendant une réponse ou une erreur. Cela éviter de bourrer le script
            $(x).data("lk",1);
        
            //On récupère l'id du compte visité (On peut soit prendre dans Doslphins ou dans target, ça n'a aucune importance)
            //On récupère aussi les données en ce qui concerne la page pour faire d'autres vérifications au niveau du serveur. 
            //... Sans la page le traitement serait faussé et/ou non sur
            var i = Kxlib_GetOwnerPgPropIfExist().ueid, p = Kxlib_GetPagegProperties(); 
            //MainSnitcher
            var ms = $("<span/>");
            var wfd = false;
            
            /****************** UNFOLLOW (Start) ********************/
            /*
             * On déclare avant d'appeler le bloc.
             */
            $(ms).on("operended",function(e) {
                /*
                 * On lance la procédure Unfollow.
                 * Elle peut faire suite à une opération UnFriend.
                 */
                var s2 = $("<span/>");
                
                //On demande la résiliation de la relation "Follow"
                _f_Srv_FlwRl(i,p,'u',x,s2);

                //[DEV, TEST, DEBUG] On check si le changement de UREL s'est bien fait après le changemnent au niveau de FRIEND
                //Kxlib_DebugVars([Kxlib_GetCurUserPropIfExist().rel],true);
                
                $(s2).on("datasready",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
                    /*
                     * [NOTE 19-10-14] @author L.C.
                     * Si la relation était de type FRD avant de Unfollow, on reload.
                     * En effet, certaines fonctionnalités ne sont réservées qu'aux relations de type "Amis".
                     * On pouura citer l'accès aux commentaires des Articles IML.
                     * Or, avant que la relation ait été, il existait peut être déjà des Articles chargés avec commentaires.
                     * Pour garantir la fiabilité du code, on préfère reload
                     * [DEPUIS 10-05-15] @BOR
                     * On recharge dans tous les cas
                     */
                    //[DEPUIS 10-05-15] @BOR
                    /*
                    if ( wfd === true ) {
                        location.reload();
                    }
                    */
                    //Masquer le loader
                    _f_HidLdg("hdr");
                    /*
                     * [DEPUIS 10-05-15] @BOR
                     * On masque aussi la flèche qui fait apparaitre le menu.
                     */
                    _f_MnTrgr(true);
                    
                    //On change les icones
                    if ($(x).data("action") === "follow") {
                        $(".jb-tmlnr-urel-m-box").removeClass("this_hide");

                        $(".jb-tmlnr-urel-m-mrbox").removeClass("this_hide");
                        $(".jb-tmlnr-urel-m-frdbox").addClass("this_hide");
                    } else {
                        $(".jb-tmlnr-urel-m-box").addClass("this_hide");

                        $(".jb-tmlnr-urel-m-mrbox").addClass("this_hide");
                        $(".jb-tmlnr-urel-m-frdbox").addClass("this_hide");
                    }
                    
                   /*
                    * [DEPUIS 10-05-15] @BOR
                    * On récupère maintenant la référence du bouton cible, qui est le nouveau bouton affiché.
                    * Cela permet de le bloquer et d'empecher l'utilisateur d'effectuer une action qui nuierait à la bonne marche de l'opération.
                    */
                   var bo = _f_SwFolBtn(x,true);
                   if (! bo ) {
                       return;
                   } else {
                       $(bo).data("lk",1);
                   }
                    
                    //On change UREL
                    Kxlib_ChangeActorsUrel(d.urel.toLowerCase());
                    
                   /*
                    * [DEPUIS 10-05-15] @BOR
                    * On recharge dans tous les cas après avoir affiché la barre d'attente
                    */
                   //Faire apparaitre l'overlay qui informe qu'une redirection encours
                   if ( $(".jb-pg-sts").length ) {
                       $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                       $(".jb-pg-sts").removeClass("this_hide");
                   }
                   location.reload();
                   return;
//                   alert("PASSE ~626!");
                    //On unlock le bouton 
                    $(x).data("lk",0);

                    //-- Mettre à jour OW
                    //upsd
                    $(".jb-tmlnr-hdr-psd").text(d.upsd);
                    //uflr
                    $(".jb-u-sp-flwr-nb").text(d.uflr);
                    //uflw
                    $(".jb-u-sp-flg-nb").text(d.uflw);
                    //ucap
                    $(".jb-u-sp-cap-nb").text(d.ucap);
                    //utnb
                    //[DEPUIS 10-05-15] @BOR
                    $(".jb-acc-spec-trnb").text(d.utnb);
//                    $(".jb-acc-spec-artnb").text(d.utnb);
                });
                
            });
            
            /****************** UNFOLLOW (End) ********************/
            
            
            /* On vérifie si les utilisateurs sont amis. Si oui on commence par briser la relation entre les deux protagonistes */
            //FrienDTable
            var fdt = ["xr03","xr13","xr23"];
            //CurrentReLation
            var crl = Kxlib_GetCurUserPropIfExist().rel.toLowerCase();
//            Kxlib_DebugVars([crl,$.inArray(crl,fdt) && !$(".jb-tmlnr-urel-m-frdbox").hasClass("this_hide")],true);
            if ( $.inArray(crl,fdt) && !$(".jb-tmlnr-urel-m-frdbox").hasClass("this_hide") ) {
                var s1 = $("<span/>");
                wfd = true;
                
                //On demande la résiliation de la relation "Amis".
                var FRM = new FRIENDS();
                FRM.HandleSpeUnfollowCase(s1);
                
                //Afficher le loader
                _f_ShwLdg("hdr");
               /*
                * [DEPUIS 10-05-15] @BOR
                * On masque aussi la flèche qui fait apparaitre le menu.
                */
               _f_MnTrgr();
                    
                $(s1).on("success", function(e){
                    /*
                     * Après que la relation amis ait été brisée, on lance Unfollow
                     */
                    $(ms).trigger("operended");
                });
                
                $(s1).on("knwerror", function(e){
                    /*
                     * [NOTE 19-10-14] @author L.C.
                     * Après analyse, la gestion des erreurs dans ce cas laisse à désirer.
                     * En effet, la méthode cible ne déclenche jamais "knwerror".
                     * 
                     * Je vais modifier le code à l'apparition dès l'apparition de bugs bloquant.
                     */
                    alert("test");
                });
                
            } else {
                //Afficher le loader
                _f_ShwLdg("hdr");
                /*
                 * [DEPUIS 10-05-15] @BOR
                 * On masque aussi la flèche qui fait apparaitre le menu.
                 */
                _f_MnTrgr();
               
                //On signale que l'on peut procéder à l'opération UnFollow
                $(ms).trigger("operended");
            }
            
            //TODO : (Else) On vérifie si au moins une des deux conditions est vraie => Erreur technique 
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        }

    };
    
    /**********************************************************************************************************************************************************/
    /********************************************************************** SERVER SCOPE **********************************************************************/
    /**********************************************************************************************************************************************************/
    
    //URQID => Suivre de nouveau suivre un utilisateur depuis SLAVE
    var _Ax_RhanaFlg = Kxlib_GetAjaxRules("TMLNR_BRAIN_RHFLG", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_RhanaFlg = function(t,i,s) {
//    this.Srv_RhanaFlg = function(t,i,s) {
        //t = target, le bloc représentant l'utilisateur
        
        if ( KgbLib_CheckNullity(t) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) )
            return;
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else return;
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TRG_GONE":
                            case "__ERR_VOL_ATLEAST_ONE_GONE":
                                    $(t).remove();
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) ) {
                    var rds = [datas.return];
                    $(s).trigger("operended",rds);
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_RhanaFlg.urqid,
            "datas": {
                "i": i,
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_RhanaFlg.url, wcrdtl : _Ax_RhanaFlg.wcrdtl });
    };
    
    //URQID => Arreter de suivre un utilisateur depuis SLAVE
    var _Ax_BackFlg = Kxlib_GetAjaxRules("TMLNR_BRAIN_BKFLG", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_BackFlg = function(t,i,s) {
//    this.Srv_BackFlg = function(t,i,s) {
        //t = target, le bloc représentant l'utilisateur
        
        if ( KgbLib_CheckNullity(t) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) )
            return;
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else return;
                        
                if(! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TRG_GONE":
                            case "__ERR_VOL_ATLEAST_ONE_GONE":
                                    $(t).remove();
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) ) {
                    var rds = [datas.return];
                    $(s).trigger("operended",rds);
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_BackFlg.urqid,
            "datas": {
                "i": i,
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_BackFlg.url, wcrdtl : _Ax_BackFlg.wcrdtl });
    };
    
    /********* ULTIMATE FOLLOWING *********/
    //URQID => Envoyer l'id au près du serveur pour signifier l'utimate Follow
    
    var _Ax_FlwRl = Kxlib_GetAjaxRules("ULTMT_FOLL");
    var _f_Srv_FlwRl = function (i,p,isf,x,s) {
        //isf : ISFollow (f,u)
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(p) | KgbLib_CheckNullity(isf) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err) ) {
                    //Cacher le loader
                    var scp = $(x).data("obj");
                    if ( !scp || !scp.length ) {
                        return;
                    }
                    
                    _f_HidLdg(scp);
                   /*
                    * [DEPUIS 10-05-15] @BOR
                    * On masque aussi la flèche qui fait apparaitre le menu.
                    */
                   _f_MnTrgr(true);
                
                    //On unlock le bouton 
                    $(x).data("lk",0);
//                    alert(d.err);
//                    return;
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_TGT_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    return;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                return;
                            case "__ERR_VOL_SS_MISG":
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                return;
                            case "__ERR_VOL_WRG_DATAS" :
                            case "__ERR_VOL_DATAS_MISG" :
                            case "__ERR_VOL_SAME_PROTAS" :
                                    /*
                                     * [NOTE 19-10-14] @author L.C.
                                     * On ne fait car l'action vient sans doutes d'une modification au niveau du DOM.
                                     * On indique au serveur qu'on l'ignore completement.
                                     */
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * [NOTE 19-10-14] @author L.C.
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                return;
                            case "__ERR_VOL_NO_REL" :
                            case "__ERR_VOL_REL_EXISTS" :
                            case "__ERR_VOL_REL_XSTS" :
                            case "__ERR_VOL_VOID_REL" :
                                    /*
                                     * [NOTE 19-10-14] @author L.C.
                                     * On recharge la page pour être sur d'avoir toutes les données de nouveau à jour.
                                     * Je ne préfère pas me risquer à changer les choses à la main.
                                     * En effet, il y a la relation à changer mais aussi d'autres indicateurs visuels.
                                     */
                                   location.reload();
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) )  {
                    /*
                     * Données attendues : 
                     *  (1) La nouvelle relation entre les deux protagonistes (urel)
                     *  (2) Certaines données de mise à jour de l'utilisateur cible
                     *      * upsd, uflr, uflw, ucap, utnb
                     */
                    
                    var rds = [d.return];
                    $(s).trigger("datasready",rds);
                    
                } else return;
                
            } catch (e) {
//                alert("AJAX_SRVERR ("+th.ultmtFol_uq+") : "+e.message);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.ultmtFol_uq);
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var cul = document.URL;
        /*
         * Le plus important est l'URL et L'ID. Le reste ne sert qu'à brouiller les pistes.
         * On extrait ensuite de l'URL de le pseudo qui servira dans la détermination du compte.
         * L'identifiant permet de s'assurer de la conformité de la requete et détecter un "bad-profile".
         * 
         */
        var toSend = {
            "urqid": _Ax_FlwRl.urqid,
            "datas": {
                "i": i,
                "p": p.pg,
                "v": p.ver,
                "fm": isf,
                "cl": cul
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_FlwRl.url, wcrdtl : _Ax_FlwRl.wcrdtl });
    };
    
    /*************************************************************************************************************************************************/
    /******************************************************************* VIEW SCOPE ******************************************************************/
    /*************************************************************************************************************************************************/
    

    /*
    //Permet de verifier qu'on peut atteindre le bloc contenant la cible
    //ET que l'on peut atteindre la cible dans ce bloc
    this.IsTargetReachableNAuthentic = function(bloc, id) {
        /**
         * Si on arrive pas joindre l'element, une erreur est déclenchée et le script va s'arreter.
         * C'est dérangeant car on ne pourra avoir aucun retour. 
         * Le seul moyen est d'avoir un moyen pour l'user de remonter l'information.
         
        var $o;
        try {
            $o = $("#"+bloc+" #"+id);
            this.bfuElSel = "#"+bloc+" #"+id;
        } catch(e) {
            //TODO : Send error to server
            Kxlib_DebugVars([Can't reach BFU element !"]);
            return;
        }

        var l = $o.html().length;
        
        if (! KgbLib_CheckNullity(l) ) {
            //On s'assure que les deux attributs existent
            try {
                //toString evite le cas ou on aurait 0 et qu'il le considérerait comme faux
                var _bfurel = $o.data("bfurel").toString();
                var _bfuid = $o.data("bfuid").toString();
            } catch(e) {
                //TODO : Send error to server
                Kxlib_DebugVars([BFU defective ! Miss attr : bfurel or bfuid']);
                return;
            }
            
            /**
             * _bfurel peut être null. 
             * Exemple : On arrive sur un compte et on décide de Follow. 
             * On a aucun lien avec celui ci donc c'est vide.
             *
            if (! _bfuid ) {
                //TODO : Send error to server
                Kxlib_DebugVars([Error : Element reached is not authentic. Miss bfuid"]);
                return;
            }
            
            this.ubfurel = _bfurel;
            this.bfuid = _bfuid;
        } 
        
        return true;
    };
    //*/
    /*
    this.TotalReverseFollReversableChoice = function(o, action) {
        var $o = $(o);

        try {
            var _n = $o.data("revs");
            var old = $o.html();
            
            $o.html(_n);
            $o.data("revs",old);
            
            if (! KgbLib_CheckNullity(action) ) $o.data("action",action);
            
            return 1;
        } catch(e) {
            return;
        }
    };
    //*/
    
    /*
    //Traite le cas où on decide de suivre une personne consecutif à une suggestion
    this.Process_RhanaSugFlw = function() {
        
    };
    //*/
    /*
    //Traite le cas où on décide de suivre une personne consecutif à une recherche
    this.Process_RhanaSrFlw = function() {
        
    };
    //*/
    /*
    this.SignalServer_RhanaBlk = function(argv) {
        var th = this;
        var id = argv;
                
        var onsuccess = function (datas) {
            if(! KgbLib_CheckNullity(datas) &&KgbLib_CheckNullity(datas.err) ) 
                alert(datas.err);
            
            // NOTE : Ici on en parse pas en JSON car la chaine peut etre autre chose que du JSON
            // alert("CHAINE JSON AVANT PARSE"+datas);
            alert("BLOCKED !");
        };

        var onerror = function(a,b,c) {
            alert("Error from onError function");
        };
        
        var toSend = {
            "urqid": th.srvRhanaBlk_uq,
            "datas": {
                "accexid": id
            }
        };

        Kx_XHR_Send(toSend, "post", this.srvRhanaBlk_url, onerror, onsuccess);
    };
    //*/
    
    /*
    //Traite le cas où on decide de bloquer un contact (ou tout autre compte)
    this.Process_RhanaBlk = function() {
        //Ne traite que le cas où on suit l'user et on décide de le bloquer
        //1: Verifier l'incoherence
        var _r = _f_ChkUrlAuthyBfrProcz("blk");
        
        if ( _r ) {
            //TODO : Send error to server
            Kxlib_DebugVars([ERR : Incoherence, User triggered action "rhana_blk" when bfu is signaled as "blocked". Should be impossible']);
            return;
        }
        var  $o = $(this.bfuElSel);
        try {
            //A ce stade on ne traite que les Foll qui sont en liste.
            //De plus, on ne traite que les comptes que l'on suit DONC avec un badge 'Following'
            $o.find(_f_Gdf().badgeFlwSel);
        } catch(e) {
            //TODO : Envoyer au serveur
            Kxlib_DebugVars([ERR : Incoherence, Can't find Following badge !"]);
        }
        
        //2: Jeux des chaises musicales avec les badges
        //On retire le badge 'Following'
       
        $o.find(_f_Gdf().badgeFlwSel).fadeOut(250);
        //On insert le badge 'Blocked'
        $o.find(_f_Gdf().badgeBlkSel).removeClass( "this_hide", 400);
//        $o.find(_f_Gdf().badgeBlkSel).fadeIn(250); //Fonctionne pas

        //3: On retire le sous-menu "Unfollow"
        //On ne remove pas car l'utilisateur peut toujours faire un reverse
        $o.find(_f_Gdf().actionChoiceFolg).hide();
        //On le transforme en la bonne version (rhana_flw)
        if ( $o.find(_f_Gdf().actionChoiceFolg).data("action") !== "rhana_flw" ) {
            var _r = this.TotalReverseFollReversableChoice($o.find(_f_Gdf().actionChoiceFolg), "rhana_flw");
            
            if ( KgbLib_CheckNullity(_r) ) {
                //TODO : Send error to server
                //TODO : Signal an error to user
                Kxlib_DebugVars([ERR : An error occurs when trying to Totalreverse"]);
                
                return;
            }
        }
        
        //4: On change action. On le transforme en back_blk.
        //NOTE : Seulement si Trigger est reverse
        if (! KgbLib_CheckNullity(this.triggerObj) ) {
            $(this.triggerObj).data("action","back_blk");
            //On retire tous les urel, on remplace par blk
            this.ubfurel = "blk";
            $(this.bfuElSel).data("bfurel",this.ubfurel);
//            alert("RhanaBlk = "+$(this.bfuElSel).data("bfurel"));
        }
        
        //5: Avertir la base de données
        var id = Kxlib_GetOwnerPgPropIfExist().ueid;
        this.SignalServer_RhanaBlk(id);
    };
    //*/
    /*
    this.SignalServer_BackBlk = function(argv) {
        var th = this;
        var id = argv;
                
        var onsuccess = function (datas) {
            if(! KgbLib_CheckNullity(datas) &&KgbLib_CheckNullity(datas.err) ) 
                alert(datas.err);
            
            // NOTE : Ici on en parse pas en JSON car la chaine peut etre autre chose que du JSON
            // alert("CHAINE JSON AVANT PARSE"+datas);
            alert("UNBLOCKED !");
        };

        var onerror = function(a,b,c) {
            alert("Error from onError function");
        };
        
        var toSend = {
            "urqid": th.srvBackBlk_uq,
            "datas": {
                "accexid": id
            }
        };

        Kx_XHR_Send(toSend, "post", this.srvBackBlk_url, onerror, onsuccess);
    };
    
    //*/
    /*
    //Traite le cas où on decide de débloquer un contact qui nous follow ou que l'on follow
    //A la version Beta on ne gere pas le blockage de personne avec qui on a aucun lien.
    this.Process_BackBlk = function() {
        //Ne traite que le cas où on suit l'user et on décide de le bloquer
        //1: Verifier l'incoherence
        var _r = _f_ChkUrlAuthyBfrProcz("blk");
        
        if (! _r ) {
            //TODO : Send error to server
            Kxlib_DebugVars([ERR : Incoherence, User triggered action "back_blk" when bfu IS NOT signaled as "blocked". Should be impossible']);
            return;
        }
        var  $o = $(this.bfuElSel);
        try {
            $o.find(_f_Gdf().badgeBlkSel);
        } catch(e) {
            //TODO : Envoyer au serveur
            Kxlib_DebugVars([ERR : Incoherence, Can't find Blocked badge !"]);
            return;
        }
        
        //2: On retire le badge
        $o.find(_f_Gdf().badgeBlkSel).addClass("this_hide",400);
        
        //3: On reintègre le sous-menu "Follow".
        // Normalement il s'agit de la bonne version du bouton
        $o.find(_f_Gdf().actionChoiceFolg).show();
        
        //4: On change action. On le transforme en rhana_blk.
        //NOTE : Seulement si Trigger est reverse
        if (! KgbLib_CheckNullity(this.triggerObj) ) {
            $(this.triggerObj).data("action","rhana_blk");
//            alert("BackBlk action = "+$(this.triggerObj).html());
            //On retire tous les urel
            this.ubfurel = "";
            //NOTE : On retire les 'urel' par mesure de securité. Sécurité car normalement il n'y rien à l'interieur
            $(this.bfuElSel).data("bfurel",this.ubfurel);
//            alert($(this.bfuElSel).data("bfurel"));
        }
        
        //5: Avertir le serveur
        var id = Kxlib_GetOwnerPgPropIfExist().ueid;
        this.SignalServer_BackBlk(id);
    };
    //*/
    
    var _f_ShwLdg = function (scp) {
        /*
         * Permet d'afficher le loader
         */
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }        
        
        var b;
        if ( scp === "hdr" ) {
            b = ".jb-tmlnr-hdr-top";
        } else {
            //Déclarer d'autres blocs ici
            return;
        }
        
        $(b).find(".jb-fph-ldg").removeClass("this_hide");
    };
    
   /*
    * Permet de masquer le loader.
    */
    var _f_HidLdg = function (scp) {
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
        
        var b;
        if ( scp === "hdr" ) {
            b = ".jb-tmlnr-hdr-top";
        } else {
            //Déclarer d'autres blocs ici
            return;
        }
        
        $(b).find(".jb-fph-ldg").addClass("this_hide");
    };
    
    var _f_MnTrgr = function (shw) {
        if ( shw ) {
            $(".jb-tmlnr-urel-m-mrbox").removeClass("this_hide");
        } else {
            $(".jb-tmlnr-urel-m-mrbox").addClass("this_hide");
        }
    };
    
    /**********************************************************************************************/
    /**********************************************************************************************/
    
    
    /******************************************************************************************************************************************************/
    /******************************************************************* LISTENERS SCOPE ******************************************************************/
    /******************************************************************************************************************************************************/
    
//    var _Obj = new FPH();
    //*
    $(".jb-tmlnr-ufol-chcs").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_UltmtFlwgWhich(this);
    });
    
    //Il est OBLIGATOIRE de mettre un listener sur les enfants
    //Sinon le clique aura lieu sur le SPAN et il remontera vers <a> ET IL Y AURA UN BUG
    $(".jb-tmlnr-ufol-chcs > *").click(function(e){
//        e.stopPropagation();
        Kxlib_StopPropagation(e);
        Kxlib_PreventDefault(e);
        
        _f_UltmtFlwgWhich($(this).parent());
    });
    /* [NOTE 29-07-14] OLD
    $("#btn_head_flwing").hover(function(){
        setTimeout(function(){
            //Si le menu est en mode :hover (au moment t), on affiche le menu
            if (! $("#folw_btn_menus").is(':hover'))
                $("#folw_btn_menus").removeClass("this_hide");
            
            //Pour éviter de faire apparaitre le menu si la souris passe trop vite sur le bouton
            if (! $("#btn_head_flwing").is(':hover'))
                $("#folw_btn_menus").removeClass("this_hide");
        },180);
        
    },function(){
        setTimeout(function(){
            if (! $("#folw_btn_menus").is(':hover'))
                $("#folw_btn_menus").addClass("this_hide");
        },300);
    });
    */
    
    /* [NOTE 29-07-14] 2x OLD J'ai opté pour une solution "au clic"
    $("#tmlnr-user-fol-ufol").hover(function(){
        setTimeout(function(){
            //Si le menu est en mode :hover (au moment t), on affiche le menu
            if (! $(".jb-folw-btn-mns").is(':hover'))
                $(".jb-folw-btn-mns").removeClass("this_hide");
            
            //Pour éviter de faire apparaitre le menu si la souris passe trop vite sur le bouton
            if (! $("#btn_head_flwing").is(':hover'))
                $(".jb-folw-btn-mns").removeClass("this_hide");
        },500);
        
    },function(){
        setTimeout(function(){
            if (! $(".jb-folw-btn-mns").is(':hover'))
                $(".jb-folw-btn-mns").addClass("this_hide");
        },300);
    });
    
    */
   
   
   $(".jb-tmlnr-urel-chs").click(function(e){
        Kxlib_PreventDefault(e);
        
        /*
         * [DEPUIS 18-07-15] @BOR
         */
        $(this).toggleClass("activated");
        $(this).focus();
        
        $(".jb-folw-btn-mns").toggleClass("this_hide");
   });
   
    /*
     * [DEPUIS 18-07-15] @BOR
     */
    $(".jb-tmlnr-urel-chs").blur(function(e){
        $(this).removeClass("activated");
        $(".jb-folw-btn-mns").addClass("this_hide");
    });
   
   /* //[DEPUIS 18-07-15] @BOR
    $(".jb-folw-btn-mns").hover(function(){
        $(".jb-folw-btn-mns").removeClass("this_hide");
    },function(){
        $(".jb-folw-btn-mns").addClass("this_hide");
    });
    //*/
    
    $(".flb_menu").click(function(e){
        Kxlib_PreventDefault(e);
        
        var act = $(this).data("action");
        switch(act) {
            case "ultmt_ufolw": 
                    _f_TmlnrUflwHdr();
                break;
            /*
            case "ultmt_blk": 
                    alert("BLOCK");
                break;
            //*/
        }
    });
}

var _FE_ENTY_FPH = new FPH();

/**
 * Permet de faire le pont entre FPH et les autres environnements
 * @returns {FPH_Receiver}
 */
function FPH_Receiver (){
    this.Routeur = function (x){
        if ( KgbLib_CheckNullity(x) ) {
            return;
        } 
        _FE_ENTY_FPH.ChkOper(x);
    };
};


/**********************************************************************************************************************************************************************************************************************************/ 
/**********************************************************************************************************************************************************************************************************************************/ 
/**********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************                                         *********************************************************************************************/
/********************************************************************************************           FILE : FRIENDS.CSAM           *********************************************************************************************/
/********************************************************************************************                                         *********************************************************************************************/
/**********************************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************************/ 


function FRIENDS () {
    var gt = this;
    var __SRV_SCAN_FRDS = true;
//    this.__SRV_SCAN_FRDS = true;
    
    
    /***************************************************************************************************************************************/
    /************************************************************ PROCESS SCOPE ************************************************************/
    /***************************************************************************************************************************************/
    
    //STAY PUBLIC
    this.ChkOper = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
                    
            var a = $(x).data("action");
            switch (a) {
                case "friend":
                case "unfriend":
                        _f_FrdAct(x);
                    break;
                case "frdmts-sprt-opn" :
                        _f_Action(this,"frdmts-sprt-opn");
                    break;
                default :
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Gdf = function () {
        var ds = {
            /*
             * Utilisée pour déterminer selon quel interval on vérifie s'il y a de nouvelles demandes d'amis.
             * RAPPEL : La valeur est imparfaite pour limiter au maximum les risques de collisions avec les autres requetes.
             */
            "chkfrdrq"  : 10550,
            "hdrchs"    : ["friend","frdrqts"],
            "rgx_psd"   : /^(?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i,
        };
        return ds;
    };
    
    var _f_Init = function (o) {
//    this.Init = function (o) {
        /* Permet de lancer le processus de vérification automatique auprès du serveur.
         * Cela dépend de la valeur de __SRV_SCAN_FRDS.
         * Si on veut modifier cette valeur, il faut que le serveur renvoie une version de ce fichier modifiée
         * * */
        
        try { 
            if (o) {
                _f_OnOpen();
            }
            else{
                _f_OnClose();
            }
        } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_OnOpen = function () {
//    this.OnOpen = function () {
        //TODO : Vérifier si CU a bien le droit d'accéder à cette zone
        
        try {
            
            var s = $("<span/>");
        
            /* Envoyer une requete auprès du serveur pour récupérer les données sur les demandes et les afficher si elles existent. */
            _f_Load(s,"RQT_LIST");

            _f_OpenFrd();
        
            $(s).on("datasready",function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }

                //TODO : Afficher (ajouter aux existants) la liste des nouvelles demandes
                _f_ShwListOfRqts(d);

                _f_NooneRqt();
                
            });
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_HdrClk = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("target")) | $.inArray($(x).data("target"),_f_Gdf().hdrchs) === -1 ) {
                return;
            }
            
            var tg = $(x).data("target").toLowerCase();
            switch (tg) {
                case "friend" :
                        _f_OnOpen();
                        $(".jb-rqm-frd-gc[data-action='myfriends']").click();
                    break;
                case "frdrqts" :
                        _f_OnOpen();
                        $(".jb-rqm-frd-gc[data-action='frdrqts']").click();
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_OnClose = function () {
//    this.OnClose = function () {
        //... Autres opérations
        
        _f_CloseFrd();
        
        /* 
         * On supprime les anciennes lignes pour des raisons de sécurité.
         * Si une personne arrive d'une manière ou d'une autre à faire afficher la fenetre, il n'aura accès à aucune ligne.
         * De toutes les façons, c'est le serveur qui envoie les données.
         * 
         * [NOTE 30-07-14] (ABONDONNE) 
         *  * Cela permet de revenir à l'ancien menu après qu'on ait fermer FRC.
         *  * A cette date, changer de Menu permet de Reload entièrement la liste. Aussi, plus besoin de tout effacé !!!
         * 
         * * */
//        _f_RmvAllLn();
    };
    
    var _f_GetTTR = function () {
//    this._GetTTR = function () {
        /*
         * [NOTE 17-09-14] @author L.C. 
         * Permet de récupérer le temps avant de relancer un processus de mise à jour.
         * Cette méthode existe pour la rendre en lecture seul.
         * Avant, elle était modifiable en instanciant l'Objet FRIENDS.
         */
        
        //TimeToRenew (ou TimeToReload)
        var _TTR = 15000;
        
        return _TTR;
    };
    
    var _f_Action = function (x,a) {
//    this.Action = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
        
            var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a : $(x).data("action"); 
            switch(_a) {
                case "accept" :
                        _f_At(x);
                    break;
                case "decline" :
                        _f_Dn(x);
                    break;
                /* ******** FRIEND_MEETS ******** */
                case "frdmts-sprt-opn" :
                        $(".jb-tqr-frdmts-sprt").removeClass("this_hide");
                    break;
                case "frdmts-sprt-clz" :
                        $(".jb-tqr-frdmts-sprt").addClass("this_hide");
                    break;
                case "frdmts-add-gst" :
                        _f_AddGsts(x);
                    break;
                case "frdmts-rm-gst" :
                        var el = $(x).closest(".jb-tqr-frdmts-gsts-li");
                        $(el).remove();
                    break;
                case "frdmts-submit" :
                        _f_SubMeet(x);
                    break;
                default:
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SwMn = function (x) {
//    this.SwMn = function (x) {
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) { 
            return; 
        }
        
        try {
            var a = $(x).data("action").toLowerCase(), r, f = $("<span/>");
            
            //On retire le bloc noone s'il existe
            $(".jb-rqm-noone").addClass("this_hide");
            
//            _f_RmvAllLn();
            switch(a) {
                case "myfriends" :
                        //** On procède au changement de Menu visuellement
//                        $(".jb-conffrd-mdl-list").removeClass("this_hide");
//                        $(".jb-rqm-bd-user-mdl-list").addClass("this_hide");
                        
                        //** On procède au changement de Menu visuellement
                        $(".jb-frdctr-dspl-sec").addClass("this_hide");
                        $(".jb-frdctr-dspl-sec[data-sec='myfriends']").removeClass("this_hide");

                        $(".jb-rqm-frd-gc[data-action='frdrqts']").removeClass("active");
                        $(x).addClass("active");
                        
                        //** On vérifie s'il y a du contenu sinon on affiche qu'il y'en a pas
                        _f_NooneFrd();
                        
                        //** On vérifie que la donnée "LastPull" nous le permet de load les données
                        
                        var lp = $(".jb-rqm-bd-conffrd-list").data("lp");
                        lp = ( KgbLib_CheckNullity(lp) ) ? 0 : parseInt(lp);
                        var n = (new Date()).getTime();
                        
                        //eld  = elapsed (passé)
                        var eld = n - lp;
                        if ( lp === 0 || eld >= _f_GetTTR() )  {
                            _f_Load(f,"FRD_LIST");
                        }
                        
                    break;
                case "frdrqts" :
                        //** On procède au changement de Menu visuellement
//                        $(".jb-conffrd-mdl-list").addClass("this_hide");
//                        $(".jb-rqm-bd-user-mdl-list").removeClass("this_hide");
                        
                        //** On procède au changement de Menu visuellement
                        $(".jb-frdctr-dspl-sec").addClass("this_hide");
                        $(".jb-frdctr-dspl-sec[data-sec='frdrqts']").removeClass("this_hide");

                        $(".jb-rqm-frd-gc[data-action='myfriends']").removeClass("active");
                        $(x).addClass("active");
                        
                        //** On vérifie s'il y a du contenu sinon on affiche qu'il y'en a pas
                        _f_NooneRqt();
                        
                        //** On vérifie que la donnée "LastPull" nous le permet de load les données
                        var lp = $(".jb-rqm-bd-user-mdl-list").data("lp");
                        lp = ( KgbLib_CheckNullity(lp) ) ? 0 : parseInt(lp);
                        var n = (new Date()).getTime();
                        
                        //eld  = elapsed (passé)
                        var eld = n - lp;
                        if ( lp === 0 || eld >= _f_GetTTR() )  {
                            _f_Load(f,"RQT_LIST");
                        }
                        
                    break;
                case "meets_list" :
                        //** On procède au changement de Menu visuellement
                        $(".jb-frdctr-dspl-sec").addClass("this_hide");
                        $(".jb-frdctr-dspl-sec[data-sec='meets_list']").removeClass("this_hide");
                        
                        
                    break;
                case "meets_rqts" :
                        //** On procède au changement de Menu visuellement
                        $(".jb-frdctr-dspl-sec").addClass("this_hide");
                        $(".jb-frdctr-dspl-sec[data-sec='meets_rqts']").removeClass("this_hide");
                        
                        
                    break;
                default:
                    break;
            }
            
            $(f).on("datasready",function(e,d) {
                
                if ( KgbLib_CheckNullity(d) ) return;
//                alert("ici");
                //On retire le bloc noone s'il existe
               // _f_HdNoLine();
                
                //On vide la liste
                
                /* Afficher (ajouter aux existants) la liste avec les nouvelles données */
                if ( a === "frdrqts" ) {
                    _f_ShwListOfRqts(d);
                    
                    _f_NooneRqt();
                } else{ 
                    _f_ShwListOfFrds(d);
                    
                    _f_NooneFrd();
                }
            });
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_At = function (x) {
//    this.At = function (x) {
        /* Permet de traiter le cas d'acceptation d'une demanede d'amis reçue par un autre protagoniste */
        
        /* Envoyer l'instruction auprès du serveur */
        try {
            var i = $(x).closest(".jb-rqm-bd-user-mdl").data("item"), c = $(x).closest(".jb-rqm-bd-user-mdl"), s = $("<span/>");
            if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
                return;
            }
            
            _f_Srv_Acpt(i,c,s);
            
            //On hide l'élément en attendant la réponse du serveur
            $(c).addClass("this_hide");
                    
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /* Quand on a reçu l'instruction que tout c'est bien passé ... */
                
                //Mettre une notification puis faire disparaitre totalement la ligne
                var cd = "ua_frd_apcted";
                var o = {
                    "targ_psd" : $(c).find(".rqm-userbx-upsd").text(),
                    "targ_href" : $(c).find(".rqm-userbx-href").attr("href")
                };
                
                var Nty = new Notifyzing ();
                Nty.FromUserAction(cd,o);
                
                //... On Fait disparaitre la ligne
                $(c).remove();
                
                //On vérifie s'il y a encore au moins une ligne
                _f_NooneRqt();
                
                //Dans tous les cas on mets à jour le nombre de lignes
                _f_UpdRqtRow();
                
                //On change la relation qu'il y a entre les deux acteurs (CU et OW)
                Kxlib_ChangeActorsUrel(d.urel.toLowerCase());
                
                /*
                 * On vérifie si on est sur le Compte de l'utilisateur tiers.
                 * Dans ce cas, on reload pour permettre à CU d'accéder aux Articles.
                 */
                if ( d.hasOwnProperty("rld") && !KgbLib_CheckNullity(d.rld) && d.rld === true ) {
                    //Faire apparaitre l'overlay qui informe qu'une redirection encours
                    if ( $(".jb-pg-sts").length ) {
                        $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                        $(".jb-pg-sts").removeClass("this_hide");
                    }
                    location.reload();
                    return;
                }
                
            });
        } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    
    var _f_Dn = function (x) {
//    this.Dn = function (x) {
                
        //(Beta2) Faie apparaitre un message demandant de confirmer (On peut désactiver cette partie dans APPAREANCE
        try {
            /* Envoyer l'instruction auprès du serveur */
            var i = $(x).closest(".jb-rqm-bd-user-mdl").data("item"), c = $(x).closest(".jb-rqm-bd-user-mdl");
            
            if ( KgbLib_CheckNullity(i) ) {
                return;
            }
            
            _f_Srv_Dcln(i,c);
            
            //On n'attend pas l'instruction disant que tout c'est bien passé ...
            
            //... On Fait disparaitre la ligne
            $(c).remove();
            
            //On vérifie s'il y a encore au moins une ligne
            _f_NooneRqt();
            
            //Dans tous les cas on met à jour le nombre de lignes
            _f_UpdRqtRow();
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_Load = function (s,c) {
//    this.Load = function (s,c) {
        /* Lance le processus de récupération des données auprès du serveur.
         * Cette méthode est normalement appelée par des CALLER qui utilise la transmission de données via des EVENTS. 
         * De plus, la méthode elle même utilise ce format car elle utilise des méthodes AJAX qui fonctionnent de la sorte.
         * Toutes ces raisons font qu'il n'y a aucune posibilité de retourner quelque chose. Ce serait un non-sens.
         * * */
        //f : Il s'agit de l'élément sur lequel sera déclencé l'évènement 'datasready'; c : Le code permettant de savoir qu'elle liste chargée !
        
        if ( KgbLib_CheckNullity(s) | KgbLib_CheckNullity(c) ) { 
            return; 
        }
        
        var i = Kxlib_GetCurUserPropIfExist().ueid, r; 
        switch (c) {
            case "RQT_LIST" :
                    _f_Srv_GetAllRqts(i,s);
                break;
            case "FRD_LIST" :
                    _f_Srv_GetAllFrds(i,s);
                break;
            default :
                return;
        }
    };
    
    var _f_PlRqtNb = function() {
        try {
            
            var s = $("<span/>");
            
            _f_Load(s,"RQT_LIST");
            
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
//                alert(JSON.stringify(d));
                $(".jb-sig-ev-cn[data-scp='frd']").text(d.length);
                $(".jb-sig-ev-cn[data-scp='frd']").removeClass("this_hide");
                $(".jb-tqr-f-nav-hdr").data("target","frdrqts");
            });
            
            $(s).on("operended", function() {
                $(".jb-sig-ev-cn[data-scp='frd']").addClass("this_hide");
                $(".jb-sig-ev-cn[data-scp='frd']").text("");
                $(".jb-tqr-f-nav-hdr").data("target","friend");
            });
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_NooneRqt = function() {
//    this.NooneRqt = function() {
//    var _f_NooneRqt = function() {
        /* Vérifie s'il y a des lignes dans la zone. Dans le cas contraire on affiche un texte en remplacement */
        
        try {
            var b = $(".jb-rqm-bd-user-mdl-list");
            
            if (! $(b).find(".jb-frdctr-com-mdl").length ) {
                var m = Kxlib_getDolphinsValue("FRD_NOONE_RQM");
                
//            Kxlib_DebugVars([m,(!m),typeof m],true);     
//            Kxlib_DebugVars([(nm !== m),typeof nm],true); 
//                  
                if ( !KgbLib_CheckNullity(m) && typeof m === "string" ) {
                    $(b).find(".jb-rqm-noone-txt").text(m);
                    _f_SgnNoLine(b);
                } else {
                    $(b).find(".jb-rqm-noone-txt").text("");
                    _f_SgnNoLine(b);
                }
                
            } else {
                _f_HdNoLine(b);
            }
        } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_NooneFrd = function() {
//    this.NooneFrd = function() {
        /* Vérifie s'il y a des lignes dans la zone. Dans le cas contraire on affiche un texte en remplacement */
        try {
            var b = $(".jb-rqm-bd-conffrd-list");
            if (! $(b).find(".jb-frdctr-com-mdl").length ) {
                var m = Kxlib_getDolphinsValue("FRD_NOONE_FRD");
                
//            Kxlib_DebugVars([m,(!m),typeof m],true);     
//            Kxlib_DebugVars([(nm !== m),typeof nm],true); 
//                  
                if ( !KgbLib_CheckNullity(m) && typeof m === "string" ) {
                    $(b).find(".jb-rqm-noone-txt").html(m);
                    _f_SgnNoLine(b);
                } else {
                    $(b).find(".jb-rqm-noone-txt").text("");
                    _f_SgnNoLine(b);
                }
                
            } else {
                _f_HdNoLine(b);
            }
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_ChkEltInLn = function (b,i) {
//    this.CheckEltInLine = function (b,i) {
        /* Renvoie un booleen qui permet de savoir si l'élement dont l'itemid est founi en paramètre est dans la liste */
        if ( KgbLib_CheckNullity(b) | KgbLib_CheckNullity(i) ) { return; }
             
        try {
            var e = $(b).find(".jb-frdctr-com-mdl[data-item='" + i + "']");
            if ( $(e).length ) {
                //S'il existe, on vérifie s'il ne s'agit pas du cas où on a précédement brisé la Relation et que l'élément est resté ...
                
                if ( !$(e).find(".jb-conffrd-icobox").length && !$(e).hasClass("jb-rqm-bd-user-mdl") ) {
                    //... Dans ce cas on remove
                    $(e).remove();
                    //.. Puis on renvoie
                    return false;
                } else {
                    return true;
                }
                
            } else {
                return false;
            }
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_UpdRqtRow = function () {
//    this.UpdatRqtRow = function () {
        /* Met à jour le nombre de requtes à la demande */
        var c = $(".jb-rqm-bd-user-mdl-list").find(".jb-rqm-bd-user-mdl").length;
        
        if ( !c ) { $(".jb-rqm-h-m-ln").text(""); }
        else { $(".jb-rqm-h-m-ln").text("("+c+")"); }
    };
    
    var _f_UpdFrdRow = function () {
//    this.UpdatFrdRow = function () {
        /* Met à jour le nombre d'amis à la demande */
        var c = $(".jb-rqm-bd-conffrd-list").find(".jb-rqm-bd-conffrd-mdl:has(.jb-conffrd-choices[data-action=frc_unfriend])").length;
        
        if ( !c ) { $(".jb-frd-h-m-ln").text(""); }
        else { $(".jb-frd-h-m-ln").text("("+c+")"); }
    };
    
    var _f_RmvAllLn = function () {
//    this.RemoveAllLines = function () {
        $(".jb-rqm-bd-user-mdl-list").find(".jb-rqm-bd-user-mdl").remove();
        $(".jb-rqm-bd-conffrd-list").find(".jb-rqm-bd-conffrd-mdl").remove();
    };
    
    /********* FRIENDS CENTER ***********/
//    this.OnOpenFriendRules = function () { //DEV, TEST, DEBUG Permet d'accéder à la fenetre depuis l'exterieur
    var _f_OnOpenFrdRls = function () {
        try {
            /* Récupération des textes */
            //iro : LA phrase d'introduction; rls : Rules, les règles à respecter; fly : Finally, la phrase de conclusion
            var iro = Kxlib_getDolphinsValue("FRD_RULES_IRO"), rls = Kxlib_getDolphinsValue("FRD_RULES_RLS").split(','), fly = Kxlib_getDolphinsValue("FRD_RULES_FLY");
            
            /* Transformation des textes et insertion des textes */
            iro = Kxlib_DolphinsReplaceDmd($(".jb-fdrl-bdy-iro").text(), 'intro', iro);
            $(".jb-fdrl-bdy-iro").html(iro);
            
            $.each($(".jb-fdrl-bdy-rls").find(".jb-fdrl-bdy-rl"), function(x, v) {
                $(v).html(Kxlib_DolphinsReplaceDmd($(v).text(), 'rule', rls[x]));
            });
            
            fly = Kxlib_DolphinsReplaceDmd($(".jb-fdrl-bdy-fly").text(), 'finally', fly);
            $(".jb-fdrl-bdy-fly").html(fly);
            
            var lm = Kxlib_getDolphinsValue("FRD_RULES_WHYRLS");
            lm = Kxlib_DolphinsReplaceDmd($(".jb-fdrl-lmr").text(), 'learn_more', lm);
            $(".jb-fdrl-lmr").text(lm);
           
            var td = Kxlib_getDolphinsValue("COMLG_TOOD");
            td = Kxlib_DolphinsReplaceDmd($(".jb-fdrl-td").text(), 'tood', td);
            $(".jb-fdrl-td").text(td);
            
            //Afficher la fenetre (Overlay)
            $(".jb-frdrules-sprt").removeClass("this_hide");
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_OnClzFrdRls = function () {
//    this.OnClzFrdRls = function () {
        try {
            /*
             * ETAPE :
             *      Femer l'overlay qui affiche les règles 
             */
            $(".jb-frdrules-sprt").addClass("this_hide");

            /*
             * ETAPE :
             *      On réinitialise les zones sinon à la prochaine exécution, on aura des erreurs
             */
            $(".jb-fdrl-bdy-iro").text("%intro%");
            $(".jb-fdrl-bdy-rl").text("%rule%");
            $(".jb-fdrl-bdy-fly").text("%finally%");
            $(".jb-fdrl-lmr").text("%learn_more%");
            $(".jb-fdrl-lmr").addClass("this_hide");
            $(".jb-fdrl-td").text("%tood%");
            
            /*
             * [DEPUIS 21-11-15] @author BOR
             * ETAPE :
             *      Masquer le spinner
             */
            $(".jb-tmlnr-urel-m-frdbox").addClass("this_hide"); //Précaution
            $(".jb-fph-ldg").addClass("this_hide");
            $(".jb-tmlnr-urel-m-mrbox").removeClass("this_hide");
            //On delock le déclencheur
            $(".jb-flb-mn[data-action='friend']").data("lk",0);
            //On delock les déclencheurs de type FOLLOW
            $(".jb-tmlnr-ufol-chcs[data-action='follow'], .jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk",0);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_LrnMrFrdRules = function () {
        //TODO : Permet de lancer une action avant d'autoriser l'utilisateur à aller vers la page du blog.
    };
    
    var _f_RevFrdAct = function (x) {
        /* Permet de changer symétiquement l'action à entreprendre dans le cas de Friend.
         * On ne revérifie pas action, en fait confiance au CALLER.
         * * */
        
        if ( KgbLib_CheckNullity(x) ) { return; }
        
        try {
            switch ($(x).data("action").toLowerCase()) {
                case "friend" :
                case "unfriend" :
                        var na = $(x).data("rev");
                        $(x).data("action",na);
                        return 1;
                    break;
                default :
                        return;
                    break;
            }
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RevFrdTxt = function (x,isr) {
        //isr : ISRest
        /*
         * Permet de reverse le texte pour le bouton situé au niveau du Header (Liste) permettant de Friend/UnFriend.
         * La méthode ne permet pas de reverse "action". @see _f_RevFrdAct()
         * 
         */
        if ( KgbLib_CheckNullity(x) ) 
            return;
        
        try {
            
            var nt = ( isr ) ? $(x).data("zrrevs") : $(x).data("revs") ;
            var ot = $(x).text();
            
            $(x).text(nt);
            $(x).data("revs",ot);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    var _f_FrdAct = function (x) {
//    this.FrdAct = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
        
            var a = $(x).data("action").toLowerCase();
            switch (a) {
                case "friend" :
                        _f_GoFrd(x);
                    break;
                case "unfriend" :
                        _f_Unfrd(x);
                    break;
                case "confirm_abort" :
                case "confirm_unfriend" :
                case "frc_unfriend" :
                        _f_FrcUnfrd(x,a);
                    break;
                default: 
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_GoFrd = function (x) {
        /* Permet d'éxécuter l'action de Friend si elle est autorisée */
        //x : L'objet qui é déclenché l'action
        
        try {
            
            if ( $(x).data("lk") === 1 | $(".jb-tmlnr-ufol-chcs[data-action='follow']").data("lk") === 1 | $(".jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk") === 1 ) {
                return; 
            }
            
            //On lock le déclencheur FRD
            $(x).data("lk",1);
            //On lock les déclencheurs de type FOLLOW
            $(".jb-tmlnr-ufol-chcs[data-action='follow'], .jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk",1);
            
            var i = Kxlib_GetOwnerPgPropIfExist().ueid, rl = Kxlib_GetCurUserPropIfExist().rel, s = $("<span/>");
            _f_Srv_GoFrd(i,rl,x,s);
            
            //On affiche le loader
            _f_ShwLdg("hdr");
            //On masque le logo pour montrer le menu
            _f_MnTrgr();
            
            $(s).on("datasready", function(e,d) {
                //Ici d correspond au nouveau code UREL à mettre en place
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //Mettre à jour la relation entre les deux Acteurs
                Kxlib_ChangeActorsUrel(d.urel);
                
                /*
                //Changer l'icone
                _f_ShwFrdIco();
                
                //Changer l'action
                _f_RevFrdAct(x);
                //*/
                
                //-- Mettre à jour OW
                //upsd
                $(".jb-tmlnr-hdr-psd").text(d.upsd);
                //uflr
                $(".jb-u-sp-flwr-nb").text(d.uflr);
                //uflw
                $(".jb-u-sp-flg-nb").text(d.uflw);
                //ucap
                $(".jb-u-sp-cap-nb").text(d.ucap);
                //utnb
                //[DEPUIS 10-05-15] @BOR
                $(".jb-acc-spec-trnb").text(d.utnb);
//                $(".jb-acc-spec-artnb").text(d.utnb);
               
                //On masque le loader
                _f_HidLdg("hdr");
                //On affiche le trigger de menu
                _f_MnTrgr(true);
                
                //On notifie l'utilisateur
                var o = {
                    "ufn": d.ufn,
                    "upsd": d.upsd,
                    "uhref": "/@"+d.upsd
                    
                };
                var Ny = new Notifyzing();
                Ny.FromUserAction("FRD_FRDRQT_SENT",o);
                
                //On delock le déclencheur
                $(x).data("lk",0);
                //On delock les déclencheurs de type FOLLOW
                $(".jb-tmlnr-ufol-chcs[data-action='follow'], .jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk",0);
                
            });
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        }

        
    };
    
    var _f_Unfrd = function (x) {
//    this.Unfriend = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | !$(x).length ) {
                return;
            }
            
            /*
             * [DEPUIS 12-05-15] @BOR
             * On vérifie le lock des Boutons.
             * RAPPEL : On aurait aussi vérifier 'follow' mais ce n'est pas normalement possible. Si 'follow' est disponible, alors c'est USER quia trafiqué.
             * SERVER va renvoyer une erreur mais ce n'est pas notre problme. On s'occupe du cas le plus standard en s'assurant que tout ce que nous faisons est sécurisé.
             */
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            if ( $(".jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk") === 1 ) {
                return;
            }
            /*
             * On lock les boutons.
             */
            $(x).data("lk",1);
            $(".jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk",1);
            
            //On masque le logo friend + le menu trigger
            _f_HidFrdIco();
            _f_MnTrgr();
            //On affiche le loader
            _f_ShwLdg("hdr");
            
            var i = Kxlib_GetOwnerPgPropIfExist().ueid, rl = Kxlib_GetCurUserPropIfExist().rel, s = $("<span/>");
            
            //Si tout s'est bien passé, on s'assure que le menu qui permet de lancer une requete d'amis a été reinitialisé
            $(s).on("datasready", function(e,d) {
                /*
                 * [NOTE 12-05-15] @BOR
                 * On ne unlock pas les boutons pour éviter toute action lors du rechargement de la page.
                 */
                //On change l'UREL
                //Kxlib_DebugVars([Kxlib_GetCurUserPropIfExist().rel], true); //CHECK BEFORE
                Kxlib_ChangeActorsUrel(d);
                //Kxlib_DebugVars([Kxlib_GetCurUserPropIfExist().rel], true); //CHECK AFTER
                
                //On retire l'icone amis
//                _f_HidFrdIco();
                /*
                 * [DEPUIS 12-05-15] @BOR
                 * On masque le loader
                 */
                _f_HidLdg();
                
                //On reinitialise le data-action
                //Kxlib_DebugVars([$(".jb-frd-action").data("action")], true); //CHECK BEFORE
                _f_RstFrdAct();
                //Kxlib_DebugVars([$(".jb-frd-action").data("action")], true); //CHECK AFTER
                
                /*
                 * [DEPUIS 11-05-15] @BOR
                 * On met un message de Notification à deux tonalités
                 */
                var cd = "BYE_BYE_MYFRD";
                var Nty = new Notifyzing();
                Nty.FromUserAction(cd);
                
                /*
                 * [DEPUIS 10-05-15] @BOR
                 * On recharge dans tous les cas après avoir affiché la barre d'attente
                 */
                //Faire apparaitre l'overlay qui informe qu'une redirection encours
                if ( $(".jb-pg-sts").length ) {
                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                    $(".jb-pg-sts").removeClass("this_hide");
                }
                location.reload();
                return;
            });
            
            $(s).on("knwerror", function(e,d) {
                /*
                 * [DEPUIS 12-05-15] @BOR
                 */
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                switch (d) {
                    case "__ERR_VOL_NO_FRIEND":
                    case "__ERR_VOL_NO_FRD":
                        
                            /*
                             * [DEPUIS 12-05-15] @BOR
                             * On recharge dans tous les cas après avoir affiché la barre d'attente.
                             * On veut éviter de faire apparaitre une erreur pour rien. Ce n'est pas une erreur, il se peut que la SESSION ne soit juste pas à jour.
                             */
                            //Faire apparaitre l'overlay qui informe qu'une redirection encours
                            if ( $(".jb-pg-sts").length ) {
                                $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                                $(".jb-pg-sts").removeClass("this_hide");
                            }
                            location.reload();
                        break;
                    default:
                        break;
                }
                return;
            });  
            
            /*
             * [NOTE 12-05-15] @BOR
             * Pour être sur que le bind se passe bien avant le retour.
             */
            //On appelle la fonction qui demande au serveur la résiliation du lien
            _f_Srv_Ufrd(i,rl,s);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_FrcUnfrd = function (x,a) {
//    this.FrcUnfriend = function (x) {
        //RAPPEL : FRC = FRiendCenter
        
        try {
            
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) { 
                return; 
            }
            
            var $set = $(x).closest(".jb-frdctr-com-mdl");
            switch (a) {
                case "confirm_abort" :
                        $set.find(".jb-rqm-bd-u-m-fnly-cnfrm-mx").addClass("this_hide");
                        $set.find(".jb-conffrd-choices[data-action='frc_unfriend']").removeClass("this_hide");
                    return true;
                case "confirm_unfriend" :
                        //CONTINUE
                    break;
                case "frc_unfriend" :
                        $(x).addClass("this_hide");
                        $set.find(".jb-rqm-bd-u-m-fnly-cnfrm-mx").removeClass("this_hide");
                    return true;
                default:
                    return;
            }
            
//            var i = Kxlib_GetOwnerPgPropIfExist().ueid, rl = Kxlib_GetCurUserPropIfExist().rel, f = $("<span/>");

            var dc = $set.data("cache"), dct = Kxlib_DataCacheToArray(dc)[0];
            var i = dct[0][0], rl = dct[0][5], s = $("<span/>");
//            Kxlib_DebugVars([i,typeof rl, rl.length,rl],true);
            
            /* [NOTE 30-07-14] Lorsque le champ est vide, la valeur retournée est ''. Il faut faire avec. 
             * C'est au serveur de faire attention à ce cas.
             * Cependant, ce cas n'est pas logique ici. En effet, UREL devrait etre de type de la famille FOL ou FRD.
             * Cela peut venir d'une erreur dans la transmission de UREL au cours du processus d'affichage des lignes FRC - FRD.
             * Pour l'heure on commente et on laisse le serveur se débrouiller d'autant plus que cette information est facultative.
             * */
            //if (  rl === "''" ) alert("check");
            
            //On appelle la fonction qui demande au serveur la résiliation du lien
            _f_Srv_Ufrd(i,rl,s);
            
            //On hide le trigger en attendant la réponse
//            $(x).addClass("this_hide"); //[DEPUIS 19-06-15] @BOR
            //On fait apparaitre le spinner
            $set.find(".jb-rqm-bd-u-m-fnly-cnfrm-mx").addClass("this_hide");
            $set.find(".jb-rqm-bd-u-m-fnly-cnfrm-spnr").removeClass("this_hide");
                    
            
            //On retire l'icone statuant de l'état d'une relation de type "Amis" dans FRC
//            _f_HdFrcFrdIco(x); //OBSELETE
            //(AMELIORATION) Plutot que de supprimer, on pourrait hide en attendant la réponse au cas où il faudrait faire machine arrière
            
            //Si tout s'est bien passé, on s'assure que le menu qui permet de lancer une requete d'amis a été reinitialisé
            $(s).on("datasready", function(e,d) {
                
                //On supprime l'élément
                $set.remove();
                
                //Mettre à jour le nombre de lignes
                _f_UpdFrdRow();
                 
               /*
                * [DEPUIS 11-05-15] @BOR
                * On met un message de Notification à "deux tonalités"
                */
                var cd = "BYE_BYE_MYFRD";
                var Nty = new Notifyzing();
                Nty.FromUserAction(cd);
                
                /*
                 * Si l'utilisateur visé est le compte que je viste actuellement ...
                 * Je mets fin "visuellement" à la relation en changeant les icones et textes permettant de statuer de la relation.
                 * 
                 * On effectue ses opérations individuellement car, on ne peut pas utiliser Unfriend().
                 * En effet, on a déjà brisé la relation entre les deux protagonistes.
                 */
                if ( i === Kxlib_GetOwnerPgPropIfExist().ueid  ) {
                    //On change l'UREL
//                    Kxlib_DebugVars([Kxlib_GetCurUserPropIfExist().rel], true); //CHECK BEFORE
                    Kxlib_ChangeActorsUrel(d);
//                    Kxlib_DebugVars([Kxlib_GetCurUserPropIfExist().rel], true); //CHECK AFTER
                    
                    //On retire l'icone statuant de l'état d'une relation de type "Amis" dans HEADER
                    _f_HidFrdIco();
                
                    //On reinit le bouton action
                    _f_RstFrdAct();
                    
                    //Reinit le texte du bouton d'action
                    _f_RstFrdActTxt();
                    
                    /*
                     * [DEPUIS 10-05-15] @BOR
                     * On recharge dans tous les cas après avoir affiché la barre d'attente
                     */
                    //Faire apparaitre l'overlay qui informe qu'une redirection encours
                    if ( $(".jb-pg-sts").length ) {
                        $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                        $(".jb-pg-sts").removeClass("this_hide");
                    }
                    location.reload();
                    return;
                }
            });
            
            
            $(s).on("knwerror", function(e,d) {
                /*
                 * [DEPUIS 12-05-15] @BOR
                 */
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                switch (d) {
                    case "__ERR_VOL_NO_FRIEND":
                    case "__ERR_VOL_NO_FRD":
                            //On supprime l'élément
                            $set.remove();
//                            $(x).closest(".jb-frdctr-com-mdl").remove();

                            //Mettre à jour le nombre de lignes
                            _f_UpdFrdRow();

                           /*
                            * [DEPUIS 11-05-15] @BOR
                            * On met un message de Notification à "deux tonalités"
                            */
                            var cd = "BYE_BYE_MYFRD";
                            var Nty = new Notifyzing();
                            Nty.FromUserAction(cd);

                            /*
                             * Si l'utilisateur visé est le compte que je viste actuellement ...
                             * Je mets fin "visuellement" à la relation en changeant les icones et textes permettant de statuer de la relation.
                             * 
                             * On effectue ses opérations individuellement car, on ne peut pas utiliser Unfriend().
                             * En effet, on a déjà brisé la relation entre les deux protagonistes.
                             */
                            if ( i === Kxlib_GetOwnerPgPropIfExist().ueid  ) {

                                //On retire l'icone statuant de l'état d'une relation de type "Amis" dans HEADER
                                _f_HidFrdIco();

                                //On reinit le bouton action
                                _f_RstFrdAct();

                                //Reinit le texte du bouton d'action
                                _f_RstFrdActTxt();

                                /*
                                 * [DEPUIS 10-05-15] @BOR
                                 * On recharge dans tous les cas après avoir affiché la barre d'attente
                                 */
                                //Faire apparaitre l'overlay qui informe qu'une redirection encours
                                if ( $(".jb-pg-sts").length ) {
                                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                                    $(".jb-pg-sts").removeClass("this_hide");
                                }
                                location.reload();
                                return;
                            }
                            
                        break;
                    default:
                        break;
                }
                return;
            });  
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
 
    
    var _f_ShwFrdIco = function () {
        $(".jb-tmlnr-urel-m-box").removeClass("center");
        
        $(".jb-tmlnr-urel-m-frdbox").removeClass("this_hide");
        $(".jb-tmlnr-urel-m-mrbox").addClass("this_hide");
        
    };
    
    
    var _f_HidFrdIco = function () {
        $(".jb-tmlnr-urel-m-box").addClass("center");
        
        $(".jb-tmlnr-urel-m-frdbox").addClass("this_hide");
        $(".jb-tmlnr-urel-m-mrbox").removeClass("this_hide");
    };
    
    
    var _f_HdFrcFrdIco = function (x) {
//    this.HideFrcFrdIco = function (x) {
        //RAPPEL : FRC = FRiendCenter
        
        try { 
            //On retire l'icone
            $(x).closest(".jb-rqm-bd-u-m-finally").find(".jb-conffrd-icobox").remove();
            //On retire le mot
            $(x).closest(".jb-rqm-bd-u-m-finally").find(".jb-conffrd-txt").remove();
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    
    var _f_RstFrdAct = function () {
        /* Réinitialise le bouton d'action Friend */
        var z = $(".jb-frd-action").data("zr");
        $(".jb-frd-action").data("action",z);
    };
    
    
    var _f_RstFrdActTxt = function () {
        /* Réinitialise le texte du bouton d'action Friend.
         * Utilie pour les opérations où on veut juste changer le visuel de la rupture de lien "amis". 
         * */
        var ct = $(".jb-frd-action").text(), z = $(".jb-frd-action").data("zrrevs");
        
        if ( ct !== z ) {
            /* Si le texte "actif" est différent du texte de base, on switch */
            
            var f = $(".jb-frd-action").data("revs");
            
            $(".jb-frd-action").text(f);
            $(".jb-frd-action").data("revs",ct);
        }
    };
    
    
    //STAY PUBLIC
    this.HandleSpeUnfollowCase = function (s) {
        //RAPPEL : Appeler sdepuis fph.js
        /* L'utilisateur peut décider de 'Unfollow' un utilisateur quand bien il n'a pas arreter la relation amicale. 
         * Si l'utilisateur décide d'agir de la sorte, le module se charge au préalable de mettre fin à la relation amicale.
         * */
        
        try {
            
            if ( KgbLib_CheckNullity(s) ) { 
                return; 
            }
            
            var i = Kxlib_GetOwnerPgPropIfExist().ueid, rl = Kxlib_GetCurUserPropIfExist().rel, f = $("<span/>");
            
            //On appelle la fonction qui demande au serveur la résiliation du lien
            _f_Srv_Ufrd(i,rl,f);
            
            //Si tout s'est bien passé, on s'assure que le menu qui permet de lancer une requete d'amis a été reinitialisé
            $(f).on("datasready", function(e,d) {
                //On change l'UREL
                Kxlib_ChangeActorsUrel(d);
                
                //On retire l'icone amis
                _f_HidFrdIco();
                
                //On reinitialise le data-action
//                Kxlib_DebugVars([$(".jb-frd-action").data("action")],true); //CHECK BEFORE
                _f_RstFrdAct();
//                Kxlib_DebugVars([$(".jb-frd-action").data("action")],true); //CHECK AFTER
                
                /* On change EXCEPTIONNELLEMNT les textes (Normalement un autre module s'en charge via la propriété "kgb_el_can_revs" */
                //nt = NewText ; ot = OldText
                var nt = $(".jb-frd-action").data("revs"), ot = $(".jb-frd-action").html();
                $(".jb-frd-action").html(nt).data("revs",ot);
                
                /* On indique au Caller que tout s'est bien passé. 
                 * On envoie quand même le nouveau UREL même si ça ne changera rien.
                 * (1) La valeur n'est qu'indicative pour le serveur
                 * (2) C'est celle découlant de l'opération à suivre (Unfollow) qui compte.
                 */
                $(s).trigger("success",d);
            });
            
            //On envoie le signale à CALLER (très probablement FPH) avec le code d'erreur
            $(f).on("knwerror", function(e,d) {
                $(s).trigger("knwerror",d);
            });   
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
     
    };
    
    /********************************************************************* FRIEND_MEEETS *********************************************************************/
    
    var _f_AddGsts = function (x) { 
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            var guests = $(".jb-tqr-frdmts-f-ipt[data-fld='frdmt-guests']").val().split(",");
            $.each(guests,function(i,g){
                g = g.trim().toLowerCase();
                var atg = Kxlib_ValidUser(g), g = atg.substring(1);
                if ( $(".jb-tqr-frdmts-gsts-li-a[data-user='"+g+"']").length ) {
                    return true;
                }
                if (! g.match(_f_Gdf().rgx_psd) ) {
                    return true;
                }
                
                gm = _f_PprGst(atg);

                $(".jb-tqr-frdmts-gsts-lst").append(gm);
            });
            
            $(".jb-tqr-frdmts-f-ipt[data-fld='frdmt-guests']").val("");
            
            $(x).data("lk",0);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SubMeet = function (x) { 
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            alert("Controler puis Soumettre !");
            
            var s = $("<span/>");
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                
                $(x).data("lk",0);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /**********************************************************************************************************************************************/
    /*************************************************************** SERVER SCOPE *****************************************************************/
    /**********************************************************************************************************************************************/
    
    /*
    this.Ajax_CheckDbFrdRqt = Kxlib_GetAjaxRules("FRDS_CHECK_DBFRDRQT");
    this.Srv_CheckDbFrdRqt = function(i,f) {
        // [NOTE 30-07-14] Abondonné car redondant et non necessaire 
    };
    //*/
    
    var _Ax_GetAllFrds = Kxlib_GetAjaxRules("FRDS_GET_ALL_FRDS");
    var _f_Srv_GetAllFrds = function(i,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) { 
            return; 
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                    
//                    $.each(datas.return,function(x,v){
//                        Kxlib_DebugVars([v.ueid],true);
//                    });
                    
                    if(! KgbLib_CheckNullity(datas.err) ) {
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            
                            switch (datas.err) {
                                case "__ERR_VOL_USER_GONE":
                                case "__ERR_VOL_ACC_GONE":
                                case "__ERR_VOL_CU_GONE":
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_DENY":
                                case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    return;
                                    break;
                                default:
                                        return;
                                    break;
                            }
                        }
                        return;
                    } else if (! KgbLib_CheckNullity(datas.return) ) {
                        var rds = [datas.return];
                        $(s).trigger("datasready",rds);
                    }
                } else return;
                
            } catch (ex) {
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.Ajax_GetAllFrds.urqid);
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        /* i => useid représentant le compte cible 
         * * */
        var toSend = {
            "urqid": _Ax_GetAllFrds.urqid,
            "datas": {
                "i": i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GetAllFrds.url, wcrdtl : _Ax_GetAllFrds.wcrdtl });
    };
    
    var _Ax_GetAllRqts = Kxlib_GetAjaxRules("FRDS_GET_NEW_RQTS");
    var _f_Srv_GetAllRqts = function(i,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) { 
            return; 
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else{
                    return;
                }

                if(! KgbLib_CheckNullity(datas.err) ) {

                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {

                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_DNY_AKX":
//                                        Kxlib_AJAX_HandleDeny();
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var rds = [datas.return];
                    $(s).trigger("datasready",rds);
                } else {
                    $(s).trigger("operended");
                }
            } catch (ex) {
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function (a,b,c) {
//            alert("AJAX ERR : "+th.GetAllRqts.urqid);
            /*
             * TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
             */
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            return;
        };
        
        /* i => useid représentant le compte cible 
         * * */
        var toSend = {
            "urqid": _Ax_GetAllRqts.urqid,
            "datas": {
                "i": i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GetAllRqts.url, wcrdtl : _Ax_GetAllRqts.wcrdtl });
    };
    
    var _Ax_Acpt = Kxlib_GetAjaxRules("FRDS_ACCEPT");
    var _f_Srv_Acpt = function(i,c,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(c) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            //[DEPUIS 11-05-15] @BOR
                            case "__ERR_VOL_FRDRSQT_NOT_FOUND" :
                                    Kxlib_AJAX_HandleFailed("FRD_ERR_FRDRSQT_404");
                                    $(c).remove();
                                    //On vérifie s'il y a encore au moins une ligne
                                    _f_NooneRqt();
                                    //Dans tous les cas on met à jour le nombre de lignes
                                    _f_UpdRqtRow();
                                break;
                            //Dans tous les autres cas ...
                            default:
                                    //... On fait disparaitre définitivement l'élément
                                    $(c).remove();
                                    return;
                                    //[NOTE 04-09-14] @author L.C. Je n'ai noté aucun autre cas où on devait NE PAS REMOVE
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    $(s).trigger("datasready",datas.return);
//                    $(s).trigger("operended",datas.return);
                }
                
            } catch (ex) {
                //TODO : To server
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.Ajax_Accept.url);
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        /* i => useid représentant le compte cible 
         * * */
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_Acpt.urqid,
            "datas": {
                "i": i,
                "cl" : u
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Acpt.url, wcrdtl : _Ax_Acpt.wcrdtl });
    };
    
    var _Ax_Dcln = Kxlib_GetAjaxRules("FRDS_DECLINE");
    var _f_Srv_Dcln = function(i,c) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(c) ) { 
            return; 
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    
                    Kxlib_DebugVars([JSON.stringify(d.err)],true);
                    if(! KgbLib_CheckNullity(d.err) ) {
//                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY" :
                            case "__ERR_VOL_DENY_AKX" :
                                        Kxlib_AJAX_HandleDeny();
                                break;
                            case "__ERR_VOL_FRDRSQT_NOT_FOUND" :
                                    $(c).remove();
                                break;
                            default:
                                break;
                        }
//                        }
                    } 
                    return;
                } else{
                    return;
                }
            } catch (ex) {
                //TODO : ?
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.Ajax_Decline.urqid);
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        /* i => useid représentant le compte cible 
         * * */
        var toSend = {
            "urqid": _Ax_Dcln.urqid,
            "datas": {
                "i": i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Dcln.url, wcrdtl : _Ax_Dcln.wcrdtl });
    };
    
    
    /***************** FRIEND CENTER ******************/
    var _Ax_GoFrd = Kxlib_GetAjaxRules("FRDS_TRY_FRIEND");
    var _f_Srv_GoFrd = function (i,rl,x,s) {
        /*
         * Lancer une demande d'amis
         */        
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(rl) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
//        var th = this;
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    d = JSON.parse(d);
                } else {
                    return;
                }
                    
                if(! KgbLib_CheckNullity(d.err) ) {
                    /* Dans le cas où le serveur renvoie une erreur. 
                    * L'erreur à laquelle on peut facilement s'attendre est "FEC_AKX_FRD_DENY" ou "FEC_OPER_DENY" 
                    * 
                    * A la date du 27-07-14 les erreurs que l'on peut recevoir sont : 
                    *  * FEC_AKX_FRD_DENY : L'utilisateur ne respecte pas au moins une des conditions necessaire.
                    *  * FEC_OPER_DENY : L'opération est refusée pour x raison.
                    *  
                    * FEC = FrontendErrorCode
                    * 
                    * [NOTE 19-10-14] @author
                    * J'ai retiré la gestion des erreurs au gestionnaire exterieur pour la conformiser.
                    * La gestion se fai maintenant au niveau d'Ajax
                    * * */
                   
                    /*
                     * [NOTE 11-05-15] @BOR
                     * On ne fait plus rien.
                     * En effet, on laisse le loader pour signaler l'erreur de manière douce quand on affiche plus certains messages d'erreur.
                     */
                    /*
                    //On masque le loader
                    _f_HidLdg("hdr");
                    //On delock le déclencheur
                    $(x).data("lk",0);
                    //On delock les déclencheurs de type FOLLOW
                    $(".jb-tmlnr-ufol-chcs[data-action='follow'], .jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk",0);
                    */
                   switch (d.err) {
                       case "__ERR_VOL_TGT_GONE":
                       case "__ERR_VOL_ACC_GONE":
                       case "__ERR_VOL_USER_GONE":
                       case "__ERR_VOL_CU_GONE":
                       case "__ERR_VOL_OWNER_GONE":
                       case "__ERR_VOL_ATLEAST_ONE_GONE":
                                Kxlib_HandleCurrUserGone();
                            break;
                       case "__ERR_VOL_DENY":
                       case "__ERR_VOL_DENY_AKX":
                                Kxlib_AJAX_HandleDeny();
                            break;
                       case "__ERR_VOL_FAILED" :
                                Kxlib_AJAX_HandleFailed();
                            break;
                       case "__ERR_VOL_SS_MISG":
                                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                            break;
                        case "__ERR_VOL_WRG_DATAS" :
                        case "__ERR_VOL_DATAS_MISG" :
                        case "__ERR_VOL_SAME_PROTAS" :
                                /*
                                 * [NOTE 19-10-14] @author L.C.
                                 * On ne fait car l'action vient sans doutes d'une modification au niveau du DOM.
                                 * On indique au serveur qu'on l'ignore completement.
                                 */
                            break;
                        case "__ERR_VOL_WRG_HACK" :
                                /*
                                 * [NOTE 19-10-14] @author L.C.
                                 * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                 * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                 * La plupart du temps, il s'agit de données dites 'système'.
                                 * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                 * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                 */
                            break;
                        case "__ERR_VOL_NO_REL" :
                        case "__ERR_VOL_REL_EXISTS" :
                        case "__ERR_VOL_REL_XSTS" :
                        case "__ERR_VOL_VOID_REL" :
                                /*
                                 * [NOTE 19-10-14] @author L.C.
                                 * On recharge la page pour être sur d'avoir toutes les données de nouveau à jour.
                                 * Je ne préfère pas me risquer à changer les choses à la main.
                                 * En effet, il y a la relation à changer mais aussi d'autres indicateurs visuels.
                                 */
                                location.reload();
                                break;
                       case "FEC_OPER_DENY" :
                       case "__ERR_VOL_FRRUL_MSM" :
                                //Hide le menu. Sinon il reste focus
                                $(".jb-folw-btn-mns").addClass("this_hide");

                                //Informer CU qu'il ne respecte pas les conditions necessaires
                                _f_OnOpenFrdRls();
                                
                                //AVANT
                                /*
                                Kxlib_DebugVars([
                                    "Action ->",$(".jb-frd-action").data("action"),
                                    "Text ->",$(".jb-frd-action").text(),
                                    "Revs(text) ->",$(".jb-frd-action").data("revs")
                                ], true);
                                //*/
                                /* Reinitialiser le bouton d'action + le texte */
                                //On reset le texte
//                                var ot = $(".jb-frd-action").html(), nt = $(".jb-frd-action").data("revs");
//                                $(".jb-frd-action").html(nt).data("revs",ot);

                                //On reset data-action
                                //Kxlib_DebugVars([$(".jb-frd-action").data("action")], true); //CHECK BEFORE
                                _f_RstFrdAct();
                                //Kxlib_DebugVars([$(".jb-frd-action").data("action")], true); //CHECK AFTER
                                
                                //APRES
                                /*
                                Kxlib_DebugVars([
                                    "Action ->",$(".jb-frd-action").data("action"),
                                    "Text ->",$(".jb-frd-action").text(),
                                    "Revs(text) ->",$(".jb-frd-action").data("revs")
                                ], true);
                                //*/
                            break;
                        case "__ERR_VOL_TGT_RQT_PDG" :
                        case "__ERR_VOL_ACT_RQT_PDG":
                                //Récupération du message
                                /*
                                 * [NOTE 19-10-14] @author L.C.
                                 * LEXIQUE :
                                 *  ARQPG : ACTOR REQUEST PENDING
                                 *  TRQPG : TARGET REQUEST PENDING
                                 */
                                var m = ( d.err === "__ERR_VOL_ACT_RQT_PDG" ) ? Kxlib_getDolphinsValue("FRD_ERR_DNY_ARQPG") : Kxlib_getDolphinsValue("FRD_ERR_DNY_TRQPG"); 
                                
                                //Récupération des données
                                var up = Kxlib_GetOwnerPgPropIfExist().upsd;
                                var uf = Kxlib_GetOwnerPgPropIfExist().ufn;
                                var uh = "/@"+up;
                                
                                up = Kxlib_ValidUser(up);
    //                            Kxlib_DebugVars([m,p],true);
                                m = Kxlib_DolphinsReplaceDmd(m,"upsd",up);
                                m = Kxlib_DolphinsReplaceDmd(m,"ufn",uf);
                                m = Kxlib_DolphinsReplaceDmd(m,"uhref",uh);
                                
                                //On insère le texte
                                $(".jb-frd-h-ebx-msg").html(m);

                                //On bind uhref
                                $(".jb-frd-h-ebx-msg").find(".kxlib_user_mtrpg").attr("href",Kxlib_GetOwnerPgPropIfExist().uhref);

                                //On fait disparaitre le menu
                                $(".jb-folw-btn-mns").addClass("this_hide");

                                //On affiche la bande d'erreur
                                $("#frd-hdr-errbox").removeClass("this_hide");

                                /* Reinitialiser le bouton d'action + le texte */
                                /*
                                 * [DEPUIS 12-05-15] @BOR
                                 * Le texte est déjà switch automatiquement.
                                 */
                                /*
                                //On reset le texte
                                var ot = $(".jb-frd-action").html(), nt = $(".jb-frd-action").data("revs");
                                $(".jb-frd-action").html(nt).data("revs",ot);
                                //*/
                                                            
                                //On reset data-action
                                //Kxlib_DebugVars([$(".jb-frd-action").data("action")], true); //CHECK BEFORE
                                _f_RstFrdAct();
                                //Kxlib_DebugVars([$(".jb-frd-action").data("action")], true); //CHECK AFTER
                                
                                /*
                                 * [DEPUIS 11-05-15] @BOR
                                 */
                                //On masque le loader
                                _f_HidLdg("hdr");
                                _f_MnTrgr(true);
                                //On delock le déclencheur
                                $(x).data("lk",0);
                                //On delock les déclencheurs de type FOLLOW
                                $(".jb-tmlnr-ufol-chcs[data-action='follow'], .jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk",0);
                            break;
                        /*
                         * [DEPUIS 11-05-15] @BOR
                         */
                        case "__ERR_VOL_ALDY_FRD" :
                                //Faire apparaitre l'overlay qui informe qu'une redirection encours
                                if ( $(".jb-pg-sts").length ) {
                                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                                    $(".jb-pg-sts").removeClass("this_hide");
                                }
                                location.reload();
                            break;
                        default: 
                            break;
                    }
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    $(s).trigger("datasready",d.return);
                }
            } catch (ex) {
              //TODO : Send error to server
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.Ajax_Friend.urqid);
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
           /*
            * [NOTE 11-05-15] @BOR
            * On ne fait plus rien.
            * En effet, on laisse le loader pour signaler l'erreur de manière douce quand on affiche plus certains messages d'erreur.
            */
           /*
            //On masque le loader
            _f_HidLdg("hdr");
            //On delock le déclencheur
            $(x).data("lk",0);
            //On delock les déclencheurs de type FOLLOW
            $(".jb-tmlnr-ufol-chcs[data-action='follow'], .jb-tmlnr-ufol-chcs[data-action='unfollow']").data("lk",0);
            //*/
            return;
        };
        
        /* i => useid représentant le compte cible 
         * * */
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_GoFrd.urqid,
            "datas": {
                "i": i,
                "rl": rl, //Cette information est envoyée à titre informatif.
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GoFrd.url, wcrdtl : _Ax_GoFrd.wcrdtl });
    };
    
    var _Ax_Unfrd = Kxlib_GetAjaxRules("FRDS_TRY_UNFRIEND");
    var _f_Srv_Ufrd = function (i,rl,s) {
                
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(rl) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                    
                    if(! KgbLib_CheckNullity(datas.err) ) {
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            switch (datas.err) {
                                case "__ERR_VOL_ACC_GONE":
                                case "__ERR_VOL_USER_GONE":
                                case "__ERR_VOL_CU_GONE":
                                case "__ERR_VOL_TGT_GONE":
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_WRG_DATAS":
                                case "__ERR_VOL_DENY":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                                case "__ERR_VOL_NO_FRIEND":
                                case "__ERR_VOL_NO_FRD":
                                        /*
                                         * [DEPUIS 12-05-15] @BOR
                                         * On laisse CALLER gérer ce cas car la méthode peut être appelée pour plusieurs raisons les plus différentes les unes des autres. 
                                         */
                                        $(s).trigger("knwerror",datas.err);
                                    break;
                                case "__ERR_VOL_UXPTD":
                                        Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                    break;
                                default:
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                            }
                        }
                        return;
                    } else if (! KgbLib_CheckNullity(datas.return) ) {
                        $(s).trigger("datasready",datas.return);
                    }
                } else return;
            } catch (ex) {
                //TODO : ?
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.Ajax_Unfriend.urqid);
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        /* i => useid représentant le compte cible 
         * * */
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_Unfrd.urqid,
            "datas": {
                "i": i,
                "rl": rl, //Cette information est envoyée à titre informative.
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Unfrd.url, wcrdtl : _Ax_Unfrd.wcrdtl });
    };
    
    
    /**************************************************************************************************************************************************/
    /***************************************************************** AUTO SCOPE *********************************************************************/
    /**************************************************************************************************************************************************/
    
    setInterval(function(){
        _f_PlRqtNb();
    },_f_Gdf().chkfrdrq);
    
    /**************************************************************************************************************************************************/
    /***************************************************************** VIEW SCOPE *********************************************************************/
    /**************************************************************************************************************************************************/
    
    var _f_CloseFrd = function () {
//    this.View_CloseFrd = function () {
        $(".jb-rqm-sprt").addClass("this_hide");
    };
    
    var _f_OpenFrd = function () {
//    this.View_OpenFrd = function () {
        $(".jb-rqm-sprt").removeClass("this_hide");
    };
    
    var _f_SgnNoLine = function (b) {
//    this.View_SignalNoLine = function (b) {
//        $(b).addClass("this_hide");
        $(b).find(".jb-rqm-noone").removeClass("this_hide");
    };
    
    var _f_HdNoLine = function (b) {
//    this.View_HideNoLine = function (b) {
//        $(b).removeClass("this_hide");
        $(b).find(".jb-rqm-noone").addClass("this_hide");
    };
    
    var _f_ShwListOfRqts = function (d) {
//    this.DisplayListOfRqts = function (d) {
        
        if ( KgbLib_CheckNullity(d) ) { 
            return;
        }
        
        try {
            $.each(d,function(x,v) {
                
                //On vérifie que l'élément n'existe pas déjà
                if ( _f_ChkEltInLn($(".jb-rqm-bd-user-mdl-list"),v.ueid) ){ return;}
                
                //On prépare la vue
                var e = _f_PprRqtView(v);
                
                //On rebind la vue
                e = _f_RbdRqtView(e);
                
                $(e).addClass("this_hide");
//                Kxlib_DebugVars([e],true);

                /* On ajoute la vue */
                $("#rqm-bd-user-mdl-list").prepend(e);
                $(e).hide().removeClass("this_hide").fadeIn();
                
            });
            
            //On met à jour LP
            $(".jb-rqm-bd-user-mdl-list").data("lp",(new Date()).getTime());
            
            _f_UpdRqtRow();
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    
    var _f_ShwListOfFrds = function (d) {
//    this.DisplayListOfFrds = function (d) {
        
        if ( KgbLib_CheckNullity(d) ){ return;}
        
        try {
            $.each(d,function(x,v) {
                
                //On vérifie que l'élément n'existe pas déjà
                if ( _f_ChkEltInLn($(".jb-rqm-bd-conffrd-list"),v.ueid) ){ return; }
                
                //On prépare la vue
                var e = _f_PprFrdView(v);
                
                //On rebind la vue
                e = _f_RbdFrdView(e);
                
                $(e).addClass("this_hide");
//                Kxlib_DebugVars([e],true);
                /* On ajoute la vue */
                $(".jb-rqm-bd-conffrd-list").append(e);
                $(e).hide().removeClass("this_hide").fadeIn();
                
            });
            
            //On met à jour LP
            $(".jb-rqm-bd-conffrd-list").data("lp",(new Date()).getTime());
            
             _f_UpdFrdRow();
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_PprRqtView = function (d) {
//    this.PrepareRqtView = function (d) {
        if ( KgbLib_CheckNullity(d) ){ return;}
        
        try {
            
            d = Kxlib_ReplaceIfUndefined(d);
            
            //at = AcceptText; dt = DeclineText
            var at = Kxlib_getDolphinsValue("FRD_ACCEPT_LANG"), dt = Kxlib_getDolphinsValue("FRD_DECLINE_LANG");
            
            var e = "<div id=\"\" class=\"rqm-bd-user-mdl jb-rqm-bd-user-mdl jb-frdctr-com-mdl\" data-item=\""+d.ueid+"\" data-cache=\"[\'"+d.ueid+"\',\'"+d.ufn+"\',\'"+d.upsd+"\',\'"+d.uppic+"\',\'"+d.uhref+"\',\'"+d.urel.toLowerCase()+"\'],[\'"+d.time+"\',\'"+Kxlib_ReplaceIfUndefined(d.utc)+"\']\">";
            e += "<div class=\"rqm-bd-u-m-userwrap\">";
            e += "<a class=\"rqm-userbx-href\" href=\""+d.uhref+"\" alt=\"\" title=\""+d.ufn+"\">";
            e += "<span class=\"rqm-userbx-pic-fade\"></span>";
            e += "<img class=\"rqm-userbx-pic\" height=\"50\" width=\"50\" src=\""+d.uppic+"\"/>";
            e += "<span class=\"rqm-userbx-upsd\">@"+d.upsd+"</span>";
            e += "</a>";
            e += "<div class=\"rqm-userbx-anx\">";
            e += "<div>";
            e += "<span class=\"rqm-userbx-ufn\">"+d.ufn+"</span>";
            e += "</div>";
            e += "<div class=\"rqm-time-box\">";
            e += "<span id=\"\" class=\'kxlib_tgspy css-rqm-tgspy\' data-tgs-crd=\'"+d.time+"\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
            e += "<span class=\'tgs-frm\'></span>&nbsp;";
            e += "<span class=\'tgs-val\'></span>";
            e += "<span class=\'tgs-uni\'></span>";
            e += "</span>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"rqm-bd-u-m-finally\">";
            e += "<div>";
            e += "<a class=\"css-rqm-choices jb-rqm-choices\" data-action=\"accept\" href=\"javascript:;\" title=\"\" alt=\"\" role=\"button\">"+at+"</a>";
            e += "<a class=\"css-rqm-choices jb-rqm-choices\" data-action=\"decline\" href=\"javascript:;\" title=\"\" alt=\"\" role=\"button\">"+dt+"</a>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            
            return $.parseHTML(e);
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_RbdRqtView = function (e) {
//    this.RebindRqtView = function (e) {
        if ( KgbLib_CheckNullity(e) ) { 
            return;
        }
        
        try {
            $(e).find(".jb-rqm-choices").click(function(e){
                Kxlib_PreventDefault(e);
                 _f_Action(this);
            });
            return e;
        } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_PprFrdView = function (d) {
//    this.PrepareFrdView = function (d) {
        if ( KgbLib_CheckNullity(d) ) return;
        
        try {
            
            d = Kxlib_ReplaceIfUndefined(d);
            
            //fr = FriendText; bft = BreakFriendText
            var ft = Kxlib_getDolphinsValue("FRD_FRIEND_LANG"), bft = Kxlib_getDolphinsValue("FRD_BFRD_LANG");
            
            var e = "<div id=\"\" class=\"rqm-bd-user-mdl css-rqm-bd-conffrd-mdl jb-rqm-bd-conffrd-mdl jb-frdctr-com-mdl\" data-item=\""+d.ueid+"\" data-cache=\"[\'"+d.ueid+"\',\'"+d.ufn+"\',\'"+d.upsd+"\',\'"+d.uppic+"\',\'"+d.uhref+"\',\'"+d.urel.toLowerCase()+"\'],[\'"+d.time+"\',\'"+Kxlib_ReplaceIfUndefined(d.utc)+"\']\">";
            e += "<div class=\"rqm-bd-u-m-userwrap\">";
            e += "<a class=\"rqm-userbx-href\" href=\""+d.uhref+"\" alt=\"\" title=\""+d.ufn+"\">";
            e += "<span class=\"rqm-userbx-pic-fade\"></span>";
            e += "<img class=\"rqm-userbx-pic\" height=\"50\" width=\"50\" src=\""+d.uppic+"\"/>";
            e += "<span class=\"rqm-userbx-upsd\">@"+d.upsd+"</span>";
            e += "</a>";
            e += "<div class=\"rqm-userbx-anx\">";
            e += "<div>";
            e += "<span class=\"rqm-userbx-ufn\">"+d.ufn+"</span>";
            e += "</div>";
            e += "<div class=\"rqm-time-box\">";
            e += "<span class=\"rqm-time-box-lbl\">Vous êtes ami depuis : </span>";
            e += "<span id=\"\" class=\'kxlib_tgspy css-rqm-tgspy\' data-tgs-crd=\'"+d.time+"\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\' title=\'Vous êtes amis depuis cette date\'>";
            e += "<span class=\'tgs-frm\'></span>&nbsp;";
            e += "<span class=\'tgs-val\'></span>";
            e += "<span class=\'tgs-uni\'></span>";
            e += "</span>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"rqm-bd-u-m-finally css-conffrd-finally jb-rqm-bd-u-m-finally\">";
            e += "<div style=\"position: relative;\">";
            e += "<span class=\"css-conffrd-icobox jb-conffrd-icobox\">";
//            e += "<img class=\"css-conffrd-ico\" src=\"http://timg.ycgkit.com/files/img/r/ufol.png\" />";
            e += "<img class=\"css-conffrd-ico\" src=\""+ Kxlib_GetExtFileURL("sys_url_img", "r/ufol.png") +"\" />";
            e += "</span>";
//            e += "<span class=\"css-conffrd-txt jb-conffrd-txt\">"+ft+"</span>";
            e += "<span class=\"css-conffrd-txt jb-conffrd-txt\" title='Vous et @X vous êtes déjà rencontré'>Meet</span>";
            e += "<a class=\"css-conffrd-choices jb-conffrd-choices\" data-action=\"frc_unfriend\" href=\"javascript:;\" alt=\"\" title=\"Bye bye l'ami\">"+bft+"</a>";
            e += "<div class=\"rqm-bd-u-m-fnly-cnfrm-mx jb-rqm-bd-u-m-fnly-cnfrm-mx this_hide\">";
            e += "<div class=\"rqm-bd-u-m-fnly-cnfrm-hdr\">Êtes-vous sûr ?</div>";
            e += "<div class=\"rqm-bd-u-m-fnly-cnfrm-chcs-mx\">";
            e += "<a class=\"rqm-bd-u-m-fnly-cnfrm-chcs jb-rqm-bd-u-m-fnly-cnfrm-chcs\" data-action=\"confirm_unfriend\" href=\"javascript:;\" role=\"button\">Oui</a>";
            e += "<a class=\"rqm-bd-u-m-fnly-cnfrm-chcs jb-rqm-bd-u-m-fnly-cnfrm-chcs\" data-action=\"confirm_abort\" href=\"javascript:;\" role=\"button\">Nooon<i>!</i></a>";
            e += "</div>";
            e += "</div>";
            e += "<i class=\"fa fa-cog fa-spin rqm-bd-u-m-fnly-cnfrm-spnr jb-rqm-bd-u-m-fnly-cnfrm-spnr this_hide\"></i>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            
            return $.parseHTML(e);
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_RbdFrdView = function (e) {
//    this.RebindFrdView = function (e) {
        if ( KgbLib_CheckNullity(e) ) {
            return;
        }
        
        try {
            $(e).find(".jb-conffrd-choices[data-action=frc_unfriend], .jb-rqm-bd-u-m-fnly-cnfrm-chcs").click(function(e){
                Kxlib_PreventDefault(e);
                _f_FrdAct(this);
            });
            return e;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShwLdg = function (scp) {
        /*
         * Permet d'afficher le loader
         */
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
            
        var b;
        if ( scp === "hdr" ) {
            b = ".jb-tmlnr-hdr-top";
        } else {
            //Déclarer d'autres blocs ici
            return;
        }
        
        $(b).find(".jb-fph-ldg").removeClass("this_hide");
    };
    
    var _f_HidLdg = function (scp) {
        /*
         * Permet de masquer le loader.
         */
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
    
        var b;
        if ( scp === "hdr" ) {
            b = ".jb-tmlnr-hdr-top";
        } else {
            //Déclarer d'autres blocs ici
            return;
        }
        
        $(b).find(".jb-fph-ldg").addClass("this_hide");
    };
    
    var _f_MnTrgr = function (shw) {
        if ( shw ) {
            $(".jb-tmlnr-urel-m-mrbox").removeClass("this_hide");
        } else {
            $(".jb-tmlnr-urel-m-mrbox").addClass("this_hide");
        }
    };
    
    var _f_PprGst = function (atg) { 
        try {
            if ( KgbLib_CheckNullity(atg) ) {
                return;
            }
            
            var g = atg.substring(1);
            
            var gm = "<li class=\"tqr-frdmts-gsts-li jb-tqr-frdmts-gsts-li\">";
            gm += "<a class=\"tqr-frdmts-gsts-li-a jb-tqr-frdmts-gsts-li-a\" data-user=\"\" href=\"/\"></a>";
            gm += "<a class=\"tqr-frdmts-gsts-li-rm jb-tqr-frdmts-f-akx\" data-action=\"frdmts-rm-gst\" href=\"javascript:;\">&times;</a>";
            gm += "</li>";
            gm = $.parseHTML(gm);

            $(gm).find(".jb-tqr-frdmts-gsts-li-a")
                .attr({
                    "href"      : "/"+g,
                    "data-user" : g,
                })
                .text(atg);

            $(gm).find(".jb-tqr-frdmts-f-akx").on("click",function(e){
                Kxlib_PreventDefault(e);
                _f_Action(this);
            });
            
            return gm;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /***************************************************************************************************************************************************/
    /***************************************************************** LISTENERS SCOPE *****************************************************************/
    /***************************************************************************************************************************************************/
    
    /************************* FRIEND RQT *********************/
    
    $(".jb-rqm-sprt").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_OnClose();
    });
    
    $(".jb-rqm-sprt *").click(function (e) {
        Kxlib_StopPropagation(e);
    });
    
    /*
     * [DEPUIS 12-06-16]
     */
//    $(".jb-ubx-menu-choices[data-action=open_frdreq]").click(function(e){
    $(".jb-ubx-menu-choices[data-action=open_frdreq]").on("tqr_cuev_click",function(e){
        try {
            Kxlib_PreventDefault(e);
            Kxlib_StopPropagation(e);
            
            //On masque (correctement) le menu pour des raisons esthétiques
            $(".jb-hdr-btn-hdle").blur();
            
            //Permet d'avoir un message si à l'ouverture il n'y a rien dans la liste
            _f_NooneRqt();
            
            _f_OnOpen();
        } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    });
    
    $(".jb-rqm-choices").click(function(e){
        Kxlib_PreventDefault(e);
        
         _f_Action(this);
    });
    
    $(".jb-rqm-frd-gc").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_SwMn(this);
    });
    
    $(".jb-rqm-frd-gc > *").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_SwMn($(this).parent());
    });
    
    /*
    $(".jb-conffrd-choices[data-action=frc_unfriend]").click(function(e){
        Kxlib_PreventDefault(e);
       
        _f_FrdAct(this);
    });
    //*/
                                                                                                                                                                                                                                                    
    /************************* HEADER *********************/
    
    $(".jb-conffrd-choices[data-action=frc_unfriend], .jb-rqm-bd-u-m-fnly-cnfrm-chcs").click(function(e){ //[DEPUIS 18-07-15]
//    $(".jb-flb-mn[data-action=friend], .jb-flb-mn[data-action=unfriend], .jb-conffrd-choices[data-action=frc_unfriend], .jb-rqm-bd-u-m-fnly-cnfrm-chcs").click(function(e){ //[DEPUIS 19-06-15]
//    $(".jb-flb-mn[data-action=friend], .jb-flb-mn[data-action=unfriend]").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_FrdAct(this);
    });     
    
    
    $(".jb-fdrl-td").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_OnClzFrdRls();
    });   
    
    /*
     * [NOTE 13-05-15] @BOR
     * .is(":hover") ne fonctionnant pas partout, j'utilise la voix standard.
     */
    $(".jb-tqr-f-nav-hdr").hover(function(){
        $(this).find(".jb-tqr-f-n-h-i").not(".hover").addClass("this_hide");
        $(this).find(".jb-tqr-f-n-h-i.hover").removeClass("this_hide");
        /*
        if ( $(this).is(":hover") ) {
            $(this).find(".jb-tqr-f-n-h-i").not(".hover").addClass("this_hide");
            $(this).find(".jb-tqr-f-n-h-i.hover").removeClass("this_hide");
        } else {
            $(this).find(".jb-tqr-f-n-h-i").not(".hover").removeClass("this_hide");
            $(this).find(".jb-tqr-f-n-h-i.hover").addClass("this_hide");
        }
        //*/
    }, function(){
        $(this).find(".jb-tqr-f-n-h-i").not(".hover").removeClass("this_hide");
        $(this).find(".jb-tqr-f-n-h-i.hover").addClass("this_hide");
    });
    
    $(".jb-tqr-f-nav-hdr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_HdrClk(this);
    });
    
    $(".jb-tqr-frdmts-clzbx, .jb-tqr-frdmts-f-akx, .jb-flb-mn[data-action='meetup']").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    
    $(".jb-tqr-frdmts-f-ipt[data-fld='when-date']").datepicker($.datepicker.regional["fr"]);
    
    
    /***************************************************************************************************************************************************/
    /***************************************************************** LISTENERS SCOPE *****************************************************************/
    /***************************************************************************************************************************************************/
    _f_Init();
//    Ob.Init();
//    _f_OnOpenFrdRls(); // FOR DEV, TEST, DEBUG

}

var _FE_ENTY_FRD = new FRIENDS();

/**
 * Permet de faire le pont entre FPH et les autres environnements
 * @returns {FPH_Receiver}
 */
function _FE_ENTY_FRD_RCVR (){
    this.Routeur = function (x){
        if ( KgbLib_CheckNullity(x) ) {
            return;
        } 
        _FE_ENTY_FRD.ChkOper(x);
    };
};