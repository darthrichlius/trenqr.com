
function TIMEGOD () {
    var gt = this;
    /**********************************************************************************************************************************************/
    /***************************************************************** PROCESS SCOPE **************************************************************/
    /**********************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var ds = {
            "gnlp"  : 10000
        };
        
        return ds;
    };
    
    //STAY PUBLIC
    this.UpdSpies = function () {
        try {
            var $ts = $(".kxlib_tgspy");
            $.each($ts, function(i,e) {
//                Kxlib_DebugVars([56,$(e).data("tgs-crd")]);
                if (! KgbLib_CheckNullity($(e).data("tgs-crd")) ) 
                {
                    _f_UpdThisSpy(e); 
                }
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    /*
     * Permet de mettre à jour individuellement un TimeSpy
     * @param {Object} o L'objet TS
     */
    var _f_UpdThisSpy = function (o) {
        if ( KgbLib_CheckNullity(o) ) {
            return;
        }
        try {
            
            //La date de création de l'objet qui nous servira de référence
            var crtm = $(o).data("tgs-crd");
            if ( KgbLib_CheckNullity(crtm) ) {
                return;
            }
            
            /*
             * ETAPE :
             * On calcule la différence de temps entre la date de création et la date actuelle.
             * Cette différence nous permettra d'optimiser le rendu de la date à afficher
             */
            var tmdf = (new Date()) - crtm;
            
            /*
             * ETAPE :
             * On fonction de la différence, on tente d'obtenir les données suivantes :
             *  > Faut-il afficher "From" ou pas ?
             *  > La valeur temporelle
             *  > L'unité de temps
             * 
             * RAPPEL : 
             *  -> On n'est pas obliger d'afficher "From". Tout dépend du cas.
             *  -> La valeur temporelle peut être un nombre, un ou plusieurs mots réprésentatifs du moment : Maintenant, Aujourd'hui, Hier, 15 Evr 15, ...
             *  -> L'unité peut-être : Secondes, Minutes, Heures, Jours.
             *  -> Si une valeur est nulle, on n'affiche pas la section correspondante.
             */
            var frm, val, unt;
            if ( tmdf >= 0 && tmdf < 20000 ) {
                /*
                 * CAS : La date correspond à une durée d'intervalle de 20 secondes maximum.
                 * On affiche : "Maintenant"
                 */
                val = Kxlib_getDolphinsValue("TG_TIME_NOW");
            } else if ( tmdf >= 20000 && tmdf < 60000 ) {
                /*
                 * CAS : La date correspond à une durée d'intervalle de 60 secondes (1 minute) maximum.
                 * On affiche la valeur, suivie de 's' : Il y a 30s
                 */
                frm = true;
                val = Math.floor(tmdf/1000);
                unt = "s";
            } else if ( tmdf >= 60000 && tmdf < 3600000 ) {
                /* 
                 * CAS : La date correspond à une durée d'intervalle de 60 minutes (1 heure) maximum.
                 * On affiche la valeur, suivie de 'm' : Il y a 30m
                 */
                frm = true;
                val = Math.floor(tmdf/60000);
                unt = "m";
            } else if ( tmdf >= 3600000 && tmdf < 86400000 ) {
                /*
                 * CAS : La date correspond à une durée d'intervalle de 24 heures (1 journée) maximum.
                 * On affiche la valeur, suivie de 'h' : Il y a 2h
                 */
                frm = true;
                val = Math.floor(tmdf/3600000);
                unt = "h";
            } else if ( tmdf >= 86400000 ) {
                /*
                 * CAS : La date correspond à une durée d'intervalle superieure à 24 heures.
                 * On affiche la date complète. Selon les cas, on affiche l'année : 20 Avr, 20 Jun 16
                 */
                
                /*
                 * ETAPE :
                 * On crée une chaine représentatrice de la date.
                 * NOTE :
                 *  -> J'utilise KxDate car dans le précédent module je l'utilisais. Je n'ai pas de raison valable pour ne pas le refaire.
                 */
                var cdt = new KxDate(crtm), ndt = new Date();
                var s__ = cdt.getDate()+" "+Kxlib_getDolphinsValue("TG_MONTH_" +cdt.getMonth());
                if ( cdt.getFullYear() !== ndt.getFullYear() ) {
                    s__ += " "+cdt.getYear();
                }
                
                val = s__;
            }
            
            /*
             * ETAPE :
             * On met à jour visuellement l'élément
             */
            
            //Pour la section "FROM"
            if ( KgbLib_CheckNullity(frm) ) {
                $(o).find(".tgs-frm").addClass("this_hide");
            } else {
                 $(o).find(".tgs-frm").text(Kxlib_getDolphinsValue("TG_FRM")+" ");
                 $(o).find(".tgs-frm").removeClass("this_hide");
            }
            
            //Pour la section "DATE VALUE"
            $(o).find(".tgs-val").text(val);
            $(o).find(".tgs-val").removeClass("this_hide");
            
            //Pour la section "UNITÉ"
            if ( KgbLib_CheckNullity(unt) ) {
                $(o).find(".tgs-uni").addClass("this_hide");
            } else {
                 $(o).find(".tgs-uni").text(unt);
                 $(o).find(".tgs-uni").removeClass("this_hide");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    
    /**********************************************************************************************************************************************/
    /******************************************************************* AUTO SCOPE ***************************************************************/
    /**********************************************************************************************************************************************/
    (function(){
        setInterval(function(){
            gt.UpdSpies();
        },_f_Gdf().gnlp);
    })();
    
    /**********************************************************************************************************************************************/
    /****************************************************************** SERVER SCOPE **************************************************************/
    /**********************************************************************************************************************************************/
   
    this.STUS = function(s){
        /*
         * Cette méthode permet d'obtenir l'heure du serveur de référence à un instant t donné.   
         * Cette heure est hautement fiable car elle est "lissée".
         * En effet, la méthode permet de ne pas prendre en compte le temps de latence entre la demande et la reception.
         */
        if ( KgbLib_CheckNullity(s) ) {
            return;
        }
        //LCT = LoCalTime
        var lct = (new Date()).getTime();
        var _Ax_STUS = Kxlib_GetAjaxRules("STUS");
        var onsuccess = function (d) {
        try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    _xhr_sbms = null;
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) )  {
                    var ltcy = (new Date()).getTime() - lct; 
                    var tm = d.return - ltcy; // Lissage
                    rds = [tm];
                    $(s).trigger("datasready",rds);
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_STUS.urqid,
            "datas": {
                "curl": u 
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_STUS.url, wcrdtl : _Ax_STUS.wcrdtl });
//        Kx_XHR_Send(toSend, "post", _Ax_STUS.url, onerror, onsuccess);
    };
    
    /**********************************************************************************************************************************************/
    /******************************************************************* VIEW SCOPE ***************************************************************/
    /**********************************************************************************************************************************************/
    
    
}

new TIMEGOD();