/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function EVALBOX () {
    var gt = this;
   
    /***************************************************************************************************************************************************************************************/
    /************************************************************************************ PROCESS SCOPE ************************************************************************************/
    /***************************************************************************************************************************************************************************************/
    
     this.Action = function (x) {
         try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
         
            //a = L'Action a gérer; b = L'Objet (max) représentant l'Article; p = L'identifiant de la page., t = Type de l'Article
            var a = $(x).data("action"),
                    p = $(x).data("xc"),
                        b = _f_GetArtBox($(x), p), t, i; 
            
            if ( b === "tq:skip_psmn" ) {
                if (KgbLib_CheckNullity($(".jb-unq-art-mdl.active").data("psmn")) | typeof $(".jb-unq-art-mdl.active").data("psmn") !== "string") { 
                    return; 
                }
                
                var t__ = $(".jb-unq-art-mdl.active").data("psmn");
                t__ = Kxlib_DataCacheToArray(t__);
                
                i = t__[0][0];
                t = t__[0][1]; 
                
                b = $(".jb-unq-art-mdl.active");
                p = "psmn";
            } else {
                t = $(b).data("atype"); 
                i = $(b).data("item");
            }
            
            if ( KgbLib_CheckNullity(a) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(t) ) {
                //TODO: Send error to server
                return;
            }
            
            /*
             * [NOTE 26-06-14] J'ai préféré ne pas mutualiser les fonctions de traitement serveur pour avoir un code plus souple 
             * * */
            /*
             * [NOTE 08-09-14] @author L.C. 
             * Pour combler les problèmes du au temps de latence, j'ai décidé de changer le visuel en attendant la réponse du serveur.
             * Au retour du serveur on ne va changer que les données
             */
            var s = $("<span/>");
            
            switch (a) {
                case "rh_spcl":
                        _f_Srv_RhnSpcl(x, t, b, p, i, s);
                    break;
                case "rh_cool":
                        _f_Srv_RhnCl(x, t, b, p, i, s);
                    break;
                case "rh_dislk":
                        _f_Srv_RhnDslk(x, t, b, p, i, s);
                    break;
                case "bk_spcl":
                        _f_Srv_BkSpcl(x, t, b, p, i, s);
                    break;
                case "bk_cool":
                        _f_Srv_BkCl(x, t, b, p, i, s);
                    break;
                case "bk_dislk":
                        _f_Srv_BkDslk(x, t, b, p, i, s);
                    break;
                default :
                    return;
            }
            
            var b__;
            if ( $.inArray(p,["unq","nwfd"]) !== -1 ) {
                b__ = $(".jb-unq-art-mdl.active");
            } else if ( $.inArray(p,["arp"]) !== -1 ) {
                b__ = b;
            } else {
                b__ = b;
            }
            
            /*
             * On affiche la barre de progression.
             */
            _f_ShPgrsBr(b__,true);
            
            /*
             * [NOTE 30-04-15] @BOR
             * L'affichage de l'EVAL se fait en maintenant en fonction de la réponse du Server.
             * Le processus devient plus fiable et sécurisé.
             */
//            var me = this.DisplayEval(x);
//            var th = this;
            
            $(s).on("datasready", function(e, x, t, b, i, d) {
                if ( KgbLib_CheckNullity(d) | !d.hasOwnProperty("me") ) {
                    return;
                }
                
                /*
                 * [NOTE 30-04-15] @BOR
                 * On traite le cas de l'affichage de l'EVAL en fonction du retour serveur
                 */
                gt.DplCUzrEvl(b__,d.me);
                
                //TODO : On vérifie si le serveur nous dit que l'utilisateur n'est pas connecté
                
                //Dans ce cas on redirige vers la page de connexion (EVO) Vers l'Overlay
                
//                var p = $(x).data("xc"); //[NOTE 10-04-15] @BOR Pas cohérent
//                var a = $(x).data("action"); //[NOTE 10-04-15] @BOR Pas cohérent
//            Kxlib_DebugVars([b,p,d,me,a],true);
                
                gt.UpdateModelWithEval(b, p, d, d.me, a);
//                gt.UpdateModelWithEval(b, p, d, me, a);
                
                //(Si l'utilisateur est sur son compte) On mets à jour le capital
                /*
                 * Pour le savoir, on se fit au serveur. En effet, lorsque les données vont au niveau du serveur, il vérifie si on est dans une cas RO.
                 * Dans ce cas, il renvoie 'ocap'
                 */
                if ( d.hasOwnProperty("ocap") ) {
                    $(".jb-u-sp-cap-nb").text(d.ocap);
                }
                
                /*
                 * ETAPE :
                 * On ne retire la barre que si tout s'est bien passé.
                 * Sinon, on la laisse pour forcer l'utilisateur à recharger la page plutot que de continuer sur sa lancer ...
                 * ... et causer en plus de bogues !
                 */
                
                if ( $.inArray(p,["unq","nwfd"]) !== -1 ) {
                    b = $(".jb-unq-art-mdl.active");
                }
                _f_ShPgrsBr(b);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_GetArtBox = function (x,a) {
//    this.GetArtBox = function (x,a) {
        
        if ( KgbLib_CheckNullity(a) ) {
            return; 
        }
        try {
            
            var b; 
            a = a.toLowerCase();
            switch (a) {
                case "arp":
                    b = $(x).closest(".jb-tmlnr-mdl-std");
                    break;
                case "trpg":
                    //NON IMPLEMENTE. ON PASSE PAR UNQ
                    break;
                case "nwfd":
                    b = $(x).closest(".jb-unq-bind-art-mdl");
                    break;
                case "unq":
                    var t = $(".jb-unq-art-mdl.active").data("item");
                    i = Kxlib_DataCacheToArray(t)[0][1];
                    if (i === "tq:skip_psmn") {
                        return i;
                    } 
                    b = Kxlib_ValidIdSel(i);
                    break;
                default : 
                    return;
            }
            
            return b;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    this.DplCUzrEvl = function (b,me) {
        /*
         * Cette méthode permet de définir visuellement une évaluation en fonction dee l'évaluation donnée en paramètre.
         * Elle fait appelle a DisplayEval()
         * * */
        //b = bloc; me = MyEval
        try {
            
            if ( KgbLib_CheckNullity(b) ) { 
                return; 
            }
            
            //On commence par reset l'EVAL (Permet d'éviter que lorsqu'on pointe vers la meme cible (Article) que l'Action contraire se produise 
            this.RmvAlEvl(b);
            
            if (!KgbLib_CheckNullity(me)) {
                //Si 'me' n'est pas défini, on tente de la récupérer. Sinon, on donne une valeur par défaut
                //On regarde si la valeur est défini dans le bloc
                
                //Sinon ... On donne la valeur 0 => On return;
            }
            
            var x;
            //Si le Caller a défini l'évaluation, on la défini par rapport au paramètre envoyé
            switch (me) {
                case "p2" :
                        x = $(b).find(".jb-csam-eval-spcool");
                    break;
                case "p1" :
                        x = $(b).find(".jb-csam-eval-cool");
                    break;
                case "m1" :
                        x = $(b).find(".jb-csam-eval-dislk");
                    break;
                default:
                        this.RmvAlEvl(b);
                    return;
            }
            
            this.DisplayEval(x,b);
            
            return b; //Depuis 15-12-14
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    this.RhanaSpcl = function () {
        
    };
    
    this.RhanaCool = function (x,b,t,p) {
        
    };
    
    this.RhanaDislk = function () {
        
    };
    
    /*************/
    
    this.BackSpcl = function () {
        
    };
    
    this.BackCool = function () {
        
    };
    
    this.BackDislk = function () {
        
    };
    
    /***********************************************************************************************************************************************************************/
    /************************************************************************** AJAX - SERVER SCOPE ************************************************************************/
    /***********************************************************************************************************************************************************************/
    
    var _Ax_RhnCl = Kxlib_GetAjaxRules("EVAL_RHCOOL");
    var _f_Srv_RhnCl = function (x,t,b,p,i,s) {
//    this.Ajax_RhanaCool = Kxlib_GetAjaxRules("EVAL_RHCOOL");
//    this._Srv_RhanaCool = function (x,t,b,p,i,s) {
        //On choisit de ne pas écrire le code en dur pour plus de souplesse dans une eventuelle refactorisation et une plus grande tolérance
        
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(p) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                       
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_CU_GONE" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_TARGET_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE" :
                                    return;
                                break;
                            case "__ERR_VOL_DENY" :
                            case "__ERR_VOL_DENY_AKX" :
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var ds = [x,t,b,i,datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        /* 
         * ec => Eval_Code : le code qui sera reçu par le serveur
         * i => id de l'article qui sera identifiable grace à son type ou à la page 
         * t => Le type d'Article 'iml' ou 'itr'
         * p => Le code de la page 
         * a => L'Action rh_cool, rh_spcl, rh_dislk, bk_cool, bk_spcl, bk_dislk ([NOTE 26-06-14] C'est inutile, on est déjà dans la fonction de traitement de l'action connue
         * * */
        //On récupère la page en cours
        var pg = Kxlib_GetPagegProperties();
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_RhnCl.urqid,
            "datas": {
                "ec"    : "_EVAL_CL",
                "t"     : t,
                "i"     : i,
                "mdl"   : p,
                "pg_prop": {
                    "pg"    : pg["pg"],
                    "ver"   : pg["ver"] 
                },
                curl    : cu
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_RhnCl.url, wcrdtl : _Ax_RhnCl.wcrdtl });
    };
    
    /*****************/
    
    var _Ax_RhnDslk = Kxlib_GetAjaxRules("EVAL_RHDSLK");
    var _f_Srv_RhnDslk = function (x,t,b,p,i,s) {
//    this.Ajax_RhanaDslk = Kxlib_GetAjaxRules("EVAL_RHDSLK");
//    this._Srv_RhanaDslk = function (x,t,b,p,i,s) {
        //On choisit de ne pas écrire le code en dur pour plus de souplesse dans une eventuelle refactorisation et une plus grande tolérance
        
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(p) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                       
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_TARGET_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var ds = [x,t,b,i,datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        /* 
         * ec => Eval_Code : le code qui sera reçu par le serveur
         * i => id de l'article qui sera identifiable grace à son type ou à la page 
         * t => Le type d'Article 'iml' ou 'itr'
         * p => Le code de la page 
         * a => L'Action rh_cool, rh_spcl, rh_dislk, bk_cool, bk_spcl, bk_dislk ([NOTE 26-06-14] C'est inutile, on est déjà dans la fonction de traitement de l'action connue
         * * */
        //On récupère la page en cours
        var pg = Kxlib_GetPagegProperties();
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_RhnDslk.urqid,
            "datas": {
                "ec": "_EVAL_DLK",
                "t": t,
                "i": i,
                "mdl": p,
                "pg_prop": {
                    "pg": pg["pg"],
                    "ver": pg["ver"] 
                },
                curl : cu
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_RhnDslk.url, wcrdtl : _Ax_RhnDslk.wcrdtl });
    };
    
    /*****************/
    
    var _Ax_RhnSpcl = Kxlib_GetAjaxRules("EVAL_RHSPCL");
    var _f_Srv_RhnSpcl = function (x,t,b,p,i,s) {
//    this.Ajax_RhanaSpcl = Kxlib_GetAjaxRules("EVAL_RHSPCL");
//    this._Srv_RhanaSpcl = function (x,t,b,p,i,s) {
        //On choisit de ne pas écrire le code en dur pour plus de souplesse dans une eventuelle refactorisation et une plus grande tolérance
        
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(p) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                       
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_TARGET_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var ds = [x,t,b,i,datas.return];
                    $(s).trigger("datasready",ds);
                } else return;
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        /* 
         * ec => Eval_Code : le code qui sera reçu par le serveur
         * i => id de l'article qui sera identifiable grace à son type ou à la page 
         * t => Le type d'Article 'iml' ou 'itr'
         * p => Le code de la page 
         * a => L'Action rh_cool, rh_spcl, rh_dislk, bk_cool, bk_spcl, bk_dislk ([NOTE 26-06-14] C'est inutile, on est déjà dans la fonction de traitement de l'action connue
         * * */
        //On récupère la page en cours
        var pg = Kxlib_GetPagegProperties();
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_RhnSpcl.urqid,
            "datas": {
                "ec": "_EVAL_SPCL",
                "t": t,
                "i": i,
                "mdl": p,
                "pg_prop": {
                    "pg": pg["pg"],
                    "ver": pg["ver"] 
                }, 
                curl : cu
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_RhnSpcl.url, wcrdtl : _Ax_RhnSpcl.wcrdtl });
    };
    
    /*******************/
    /*******************/
    
    var _Ax_BkCl = Kxlib_GetAjaxRules("EVAL_BKCOOL");
    var _f_Srv_BkCl = function (x,t,b,p,i,s) {
//    this.Ajax_BackCool = Kxlib_GetAjaxRules("EVAL_BKCOOL");
//    this._Srv_BackCool = function (x,t,b,p,i,s) {
        //On choisit de ne pas écrire le code en dur pour plus de souplesse dans une eventuelle refactorisation et une plus grande tolérance
        
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(p) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                     
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_TARGET_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var ds = [x,t,b,i,datas.return];
                    $(s).trigger("datasready",ds);
                } else return;
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        /* 
         * ec => Eval_Code : le code qui sera reçu par le serveur
         * i => id de l'article qui sera identifiable grace à son type ou à la page 
         * t => Le type d'Article 'iml' ou 'itr'
         * p => Le code de la page 
         * a => L'Action rh_cool, rh_spcl, rh_dislk, bk_cool, bk_spcl, bk_dislk ([NOTE 26-06-14] C'est inutile, on est déjà dans la fonction de traitement de l'action connue
         * * */
        //On récupère la page en cours
        var pg = Kxlib_GetPagegProperties();
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_BkCl.urqid,
            "datas": {
                "ec": "_EVAL_CL",
                "t": t,
                "i": i,
                "mdl": p,
                "pg_prop": {
                    "pg": pg["pg"],
                    "ver": pg["ver"] 
                },
                curl : cu
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_BkCl.url, wcrdtl : _Ax_BkCl.wcrdtl });
    };
    
    /*****************/
    
    var _Ax_BkDslk = Kxlib_GetAjaxRules("EVAL_BKDSLK");
    var _f_Srv_BkDslk = function (x,t,b,p,i,s) {
//    this.Ajax_BackDslk = Kxlib_GetAjaxRules("EVAL_BKDSLK");
//    this._Srv_BackDslk = function (x,t,b,p,i,s) {
        //On choisit de ne pas écrire le code en dur pour plus de souplesse dans une eventuelle refactorisation et une plus grande tolérance
        
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(p) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                     
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_TARGET_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var ds = [x,t,b,i,datas.return];
                    $(s).trigger("datasready",ds);
                } else return;
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        /* 
         * ec => Eval_Code : le code qui sera reçu par le serveur
         * i => id de l'article qui sera identifiable grace à son type ou à la page 
         * t => Le type d'Article 'iml' ou 'itr'
         * p => Le code de la page 
         * a => L'Action rh_cool, rh_spcl, rh_dislk, bk_cool, bk_spcl, bk_dislk ([NOTE 26-06-14] C'est inutile, on est déjà dans la fonction de traitement de l'action connue
         * * */
        //On récupère la page en cours
        var pg = Kxlib_GetPagegProperties();
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_BkDslk.urqid,
            "datas": {
                "ec": "_EVAL_DLK",
                "t": t,
                "i": i,
                "mdl": p,
                "pg_prop": {
                    "pg": pg["pg"],
                    "ver": pg["ver"] 
                },
                curl : cu
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_BkDslk.url, wcrdtl : _Ax_BkDslk.wcrdtl });
    };
    
    /*****************/
    
    var _Ax_BkSpcl = Kxlib_GetAjaxRules("EVAL_BKSPCL");
    var _f_Srv_BkSpcl = function (x,t,b,p,i,s) {
//    this.Ajax_BackSpcl = Kxlib_GetAjaxRules("EVAL_BKSPCL");
//    this._Srv_BackSpcl = function (x,t,b,p,i,s) {
        //On choisit de ne pas écrire le code en dur pour plus de souplesse dans une eventuelle refactorisation et une plus grande tolérance
        
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(p) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                       
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_TARGET_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var ds = [x,t,b,i,datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        /* 
         * ec => Eval_Code : le code qui sera reçu par le serveur
         * i => id de l'article qui sera identifiable grace à son type ou à la page 
         * t => Le type d'Article 'iml' ou 'itr'
         * p => Le code de la page 
         * a => L'Action rh_cool, rh_spcl, rh_dislk, bk_cool, bk_spcl, bk_dislk ([NOTE 26-06-14] C'est inutile, on est déjà dans la fonction de traitement de l'action connue
         * * */
        //On récupère la page en cours
        var pg = Kxlib_GetPagegProperties();
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_BkSpcl.urqid,
            "datas": {
                "ec": "_EVAL_SPCL",
                "t": t,
                "i": i,
                "mdl": p,
                "pg_prop": {
                    "pg": pg["pg"],
                    "ver": pg["ver"] 
                },
                curl : curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_BkSpcl.url, wcrdtl : _Ax_BkSpcl.wcrdtl });
    };
    
    /******************/
    
    
    /********************************************************************************************************************************************************/
    /********************************************************************** VIEW SCOPE **********************************************************************/
    /********************************************************************************************************************************************************/
    
    //STAY PUBLIC
    this.RmvAlEvl = function (b) {
        if ( KgbLib_CheckNullity(b) ) { 
            return; 
        }
        
        try {
            
            $(b).find(".jb-csam-eval-choices").removeClass("css-c-e-chs-scl_hover css-c-e-chs-cl_hover css-c-e-chs-dsp_hover active");
            var l = $(b).find(".jb-csam-eval-choices");
            $.each(l, function(x, v) {
//                $(v).removeClass("css-c-e-chs-cl_hover active");
//                $(v).removeClass("css-c-e-chs-scl_hover css-c-e-chs-cl_hover css-c-e-chs-dsp_hover active");
                var zr = $(v).data("zr");
                $(v).data("action", zr);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    //STAY PUBLIC
    this.DisplayEval = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
        
            var s = "";
            
            /*
             * RULES :
             * La classe '.active' sert surtout aux processus CSS hover
             * Les données reçues ne contiennent qu'un récapitulatif général de points et du nombre d'eval pour chaque EVAL.
             * Pour la partie MyEval, on le fait en local. Par défaut c'est 0.
             * * */
            var me = "", a = $(x).data("action").toLowerCase();
            
            //Choix de la classe hover
            switch (a) {
                case "rh_spcl":
                        //Traitement visuel
                        s = "css-c-e-chs-scl_hover";
                        //On active les triggers
                        $(x).addClass(s);
                        $(x).addClass("active");
                        //On va aussi activer EVAL_COOL
                        $(x).parent().find(".css-c-e-chs-cl").addClass("css-c-e-chs-cl_hover");
                        $(x).parent().find(".css-c-e-chs-cl").addClass("active");

                        //On change le data-action
                        var rv = $(x).data("rev");
                        $(x).data("action", rv);

                        //[NOTE 27-06-14] Refactorisation possible
                        //On remet tous les autres Triggers à leur état d'origine (état par défaut) 
                        var at = $(x).parent().find(".jb-csam-eval-choices").not($(x));
    //                    alert(at.length)
                        $.each(at, function(x, v) {
                            var d = $(v).data("zr");
    //                        alert(d);
                            $(v).data("action", d);
                        });

                        //On désactive les autres triggers
                        $(x).parent().find(".css-c-e-chs-dsp").removeClass("css-c-e-chs-dsp_hover");

                        //On indique la valeur choisie par l'utilisateur
                        me = "p2";
                    break;
                case "rh_cool":
                        //Traitement visuel
                        s = "css-c-e-chs-cl_hover";
                        //On active le trigger
                        $(x).addClass(s);
                        $(x).addClass("active");

                        //On change le data-action
                        var rv = $(x).data("rev");
                        $(x).data("action", rv);

                        //On remet tous les autres Triggers à leur état d'origine (état par défaut) 
                        var at = $(x).parent().find(".jb-csam-eval-choices").not($(x));
    //                    alert(at.length)
                        $.each(at, function(x, v) {
                            var d = $(v).data("zr");
    //                        alert(d);
                            $(v).data("action", d);
                        });

                        //On désactive les autres triggers
                        $(x).parent().find(".css-c-e-chs-scl").removeClass("css-c-e-chs-scl_hover");
                        $(x).parent().find(".css-c-e-chs-scl").removeClass("active");
                        $(x).parent().find(".css-c-e-chs-dsp").removeClass("css-c-e-chs-dsp_hover");
                        $(x).parent().find(".css-c-e-chs-dsp").removeClass("active");

                        //On indique la valeur choisie par l'utilisateur
                        me = "p1";
                    break;
                case "rh_dislk":    
                        //Traitement visuel
                        s = "css-c-e-chs-dsp_hover";
                        //On active le trigger
                        $(x).addClass(s);
                        $(x).addClass("active");

                        //On change le data-action
                        var rv = $(x).data("rev");
                        $(x).data("action", rv);

                        //On remet tous les autres Triggers à leur état d'origine (état par défaut) 
                        var at = $(x).parent().find(".jb-csam-eval-choices").not($(x));
                        $.each(at, function(x, v) {
                            var d = $(v).data("zr");
                            $(v).data("action", d);
                        });

                        //On désactive les autres triggers
                        $(x).parent().find(".css-c-e-chs-scl").removeClass("css-c-e-chs-scl_hover");
                        $(x).parent().find(".css-c-e-chs-scl").removeClass("active");
                        $(x).parent().find(".css-c-e-chs-cl").removeClass("css-c-e-chs-cl_hover");
                        $(x).parent().find(".css-c-e-chs-cl").removeClass("active");

                        //On indique la valeur choisie par l'utilisateur
                        me = "m1";
                    break;
                case "bk_spcl":
                case "bk_cool":
                case "bk_dislk":
                        //On désactive tous les triggers (métode bourrine :) )
                        $(x).parent().find(".css-c-e-chs-scl").removeClass("css-c-e-chs-scl_hover");
                        $(x).parent().find(".css-c-e-chs-scl").removeClass("active");
                        $(x).parent().find(".css-c-e-chs-cl").removeClass("css-c-e-chs-cl_hover");
                        $(x).parent().find(".css-c-e-chs-cl").removeClass("active");
                        $(x).parent().find(".css-c-e-chs-dsp").removeClass("css-c-e-chs-dsp_hover");
                        $(x).parent().find(".css-c-e-chs-dsp").removeClass("active");

                        //On remet tous les Triggers à leur état d'origine (état par défaut) 
                        var at = $(x).parent().find(".jb-csam-eval-choices");
                        $.each(at, function(x, v) {
                            var d = $(v).data("zr");
                            $(v).data("action", d);
                        });
                    break;
                default:
                    return;
            }
            
            return me;
            
            /*
             //On peut utiliser cette méthode juste pour faire afficher les évalutions sans toucher à quoi que ce soit.
             if (! KgbLib_CheckNullity(d) ) {
             
             var p = $(x).data("xc");
             this.UpdateModelWithEval(b,p,d,me,a);
             }
             //*/
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
            
    };
    
    //STAY PUBLIC
    this.UpdateModelWithEval = function (b,p,d,me,a) {
        /*
        * [17-07-14] Dans le contexte du produit il n'exite que 3 modèles qui permettent d'Evaluer.
        * Il s'agit de : UNQ, ARP, NWFD. 
        * 
        * (1) Cependant, dans le cas de UNQ, b est une référence à l'Article dans la page.
        *     Il faut donc bien faire attention à effectuer les modifications sur le modèle UNQ et page.
        * (2) Dans le cas de ARP, la référence correspond au modèle ARP. 
        *     Dans ce cas, il faut aussi faire attentiond'effectuer les mises à jour au niveau de ARP et page
        * (3) Dans le cas de NWFD, on effectue les modifications qu'au niveau de NWFD. Pour la version page, elle sera mise à jour
        *      lors de son appel sur la version page.
        * (4) TODO : ATTENTION : Le 4eme cas est celui de l'UNQ dans le cas de NWFD. b est une référence vers model NWFD
        *  
        * * */
        try {
            
            if ( KgbLib_CheckNullity(b) && KgbLib_CheckNullity(p) && KgbLib_CheckNullity(d) ) {
                return;
            }
            
            p = p.toLowerCase();
            switch (p) {
                case "arp" : 
                case "psmn" : 
                    /* 
                     * ETAPE :
                     * On met à jour le nombre d'EVAL dans les différentes zones 'output' de l'Article dans page. 
                     * */
                    var c = "[" + d.eval.join(",") + "," + me + "]";
                    $(b).find(".jb-csam-eval-oput").data("cache", c);
                    $(b).find(".jb-evlbx-ch-nb[data-scp='scl']").text(d.eval[0]);
                    $(b).find(".jb-evlbx-ch-nb[data-scp='cl']").text(d.eval[1]);
                    $(b).find(".jb-evlbx-ch-nb[data-scp='dlk']").text(d.eval[2]);
                    
                    //On met à jour le texte visible par l'utilisateur
                    $(b).find(".jb-csam-eval-oput").find("span:first").text(d.eval[3]);
                    break;
                case "trpg" : 
                    //NON IMPLEMENTE. ON PASSE PAR UNQ
                    break;
                case "nwfd" : 
                case "unq" : 
                    /* On met à jour l'entete de l'Article.
                     * On le fait surtout pour la valeur MyEVAL, mais autant en profiter pour mettre à jour les autres données.
                     * L'avantage est qu'on pourra toujours avoir les données mises à jour à chaque fois qu'on effectue une action d'EVAL
                     * * */
                    /* Mises à jour des valeurs EVAL générales (PRIMODIALES POUR PERENNISER L'EVAL AU NIVEAU DU FE */
                    var s = $(b).data("cache");
//                    alert("CB => "+$(b).attr("id"));
//                    Kxlib_DebugVars([me],true);
//                    Kxlib_DebugVars([d.eval[0]],true);
                    s = Kxlib_AlterDataCacheAt(s, d.eval[0], 2, 0);
                    s = Kxlib_AlterDataCacheAt(s, d.eval[1], 2, 1);
                    s = Kxlib_AlterDataCacheAt(s, d.eval[2], 2, 2);
                    s = Kxlib_AlterDataCacheAt(s, d.eval[3], 2, 3);
                    s = Kxlib_AlterDataCacheAt(s, me, 4, 0);
//                    Kxlib_DebugVars([s],true);
                    $(b).data("cache", s);
//                    alert("CB => "+$(b).data("cache"));
                    
//                    Kxlib_DebugVars([$(b).find(".jb-csam-eval-oput span:first").text()],true);
                    
                    /* 
                     * ETAPE :
                     * On met à jour le nombre d'EVAL dans les différentes zones 'output' de l'Article dans page 
                     * */
                    var c = "[" + d.eval.join(",") + "," + me + "]";
                    $(b).find(".jb-csam-eval-oput").data("cache", c);
                    $(b).find(".jb-evlbx-ch-nb[data-scp='scl']").text(d.eval[0]);
                    $(b).find(".jb-evlbx-ch-nb[data-scp='cl']").text(d.eval[1]);
                    $(b).find(".jb-evlbx-ch-nb[data-scp='dlk']").text(d.eval[2]);
                    
                    //On met à jour le texte visible par l'utilisateur
                    $(b).find(".jb-csam-eval-oput span:first").text(d.eval[3]);
                    
                    
                    /* 
                     * ETAPE :
                     * On met à jour les données d'EVAL et le choix au niveau de la barre d'EVAL de l'Article en mode page (Si elle existe)
                     * */
                    if ( $(b).find(".jb-csam-eval-box").length ) {
                        var eb = $(b).find(".jb-csam-eval-box");
                        //Mise à jour de la donnée visuelle
                        $(eb).find(".jb-csam-eval-oput span:first").text(d.eval[3]);
                        //Mise à jour du cache
                        var f = "[" + d.eval.join(',') + "," + me + "]";
                        $(eb).find(".jb-csam-eval-oput").data("cache", f);
                        
                        /* Mise à jour du choix (Seulement si UNQ */
                        if ( p === "unq" ) {
                            var u = $(b).find(".jb-csam-eval-box > div:first").find("a[data-zr='" + a + "']");
                            if ($(u).length) {
                                this.DisplayEval(u, b);
                            }
                        }
                    }
                    
                    /* On met à jour dans le cas d'un modèle NWFD-MOZ */
                    if ( $(b).is(".jb-eval-bind-moz") ) {
                        /* On change l'entete */
                        var j = $(b).find(".nwfd-b-m-mdl-trig").data("cache");
//                        alert("CB1 => "+j);
                        j = Kxlib_AlterDataCacheAt(j, d.eval[0], 3, 0);
                        j = Kxlib_AlterDataCacheAt(j, d.eval[1], 3, 1);
                        j = Kxlib_AlterDataCacheAt(j, d.eval[2], 3, 2);
                        j = Kxlib_AlterDataCacheAt(j, d.eval[3], 3, 3);
                        $(b).find(".nwfd-b-m-mdl-trig").data("cache", j);
//                        alert("CB2 => "+$(b).find(".nwfd-b-m-mdl-trig").data("cache"));
                        
                        /* On change la valeur de l'Eval dans le bottom du modèle */
                        $(b).find(".nwfd-b-moz-art-ss-eval span:first").text(d.eval[3]);
                    }
                    
                    /* 
                     * ETAPE :
                     * On met à jour le nombre d'EVAL dans les différentes zones 'output' de l'Article dans UNQ 
                     * */
                    if ( $(".jb-unq-art-mdl.active").length ) {
                        b = $(".jb-unq-art-mdl.active");
                        c = "[" + d.eval.join(",") + "," + me + "]";
                        $(b).find(".jb-csam-eval-oput").data("cache", c);
                        $(b).find(".jb-evlbx-ch-nb[data-scp='scl']").text(d.eval[0]);
                        $(b).find(".jb-evlbx-ch-nb[data-scp='cl']").text(d.eval[1]);
                        $(b).find(".jb-evlbx-ch-nb[data-scp='dlk']").text(d.eval[2]);
                        
                        //On met à jour le texte visible par l'utilisateur
                        $(b).find(".jb-csam-eval-oput span:first").text(d.eval[3]);
                    }
                    
                    break;
                default:
                    return;
            }
            
            return b; //Depuis 15-12-14 : Surtout dans le cas où l'élément n'existe pas dans le DOM
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShPgrsBr = function (b,shw) {
        if ( KgbLib_CheckNullity(b) | !$(b).length | !$(b).find(".jb-eval-dplw-bar-mx").length | !$(b).find(".jb-eval-wait-bar").length ) {
            return;
        }
        
        if ( shw ) {
            $(b).find(".jb-eval-dplw-bar-mx").addClass("this_hide"); 
            $(b).find(".jb-eval-wait-bar").removeClass("this_hide"); 
        } else {
            $(b).find(".jb-eval-wait-bar").addClass("this_hide"); 
            $(b).find(".jb-eval-dplw-bar-mx").removeClass("this_hide"); 
        }
    };
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** LISTENERS SCOPE ****************************************************************************/
    /*************************************************************************************************************************************************************************/
    
    $(".css-c-e-chs-scl").hover(function(){
//        Kxlib_DebugVars([Leave Heart! => "+$(this).parent().find(".css-c-e-chs-cl").hasClass("active")]);
        if (! $(this).parent().find(".css-c-e-chs-cl").hasClass("active") ) 
            $(this).parent().find(".css-c-e-chs-cl").addClass("css-c-e-chs-cl_hover");
    },function(){
//        Kxlib_DebugVars([Leave Heart! => "+$(this).parent().find(".css-c-e-chs-cl").hasClass("active")]);
        if (! $(this).parent().find(".css-c-e-chs-cl").hasClass("active") ) 
            $(this).parent().find(".css-c-e-chs-cl").removeClass("css-c-e-chs-cl_hover");
    });
    
}

(function(){
    $O = new EVALBOX();
    
    $(".jb-csam-eval-choices").click(function(e){
        Kxlib_PreventDefault(e);
        
        $O.Action(this);
    });
    
})();