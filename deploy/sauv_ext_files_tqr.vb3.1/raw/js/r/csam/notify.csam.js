/*
 * Permet de :
 * - Checker s'il y a de nouvelles notifications pour cet utilisateur (1)
 * - Afficher le nombre de notifications au niveau de OpenBrain (2)
 * - Afficher le nombre de notifications au niveau de SLAVE (3)
 * - Afficher les nouvelles notifications au niveau de SLAVE (4)
 * - Afficher les notifications en rapport direct avec l'actiond de l'utilisateur (5)
 */

function Notifyzing () {
    /*
    //URQID => Check for notifications to Server
    this.checkserv_urq = "check_new_notf";
    this.checkserv_url = "http://127.0.0.1/korgb/ajax_test.php";
    //Permet de constaté que des notifs non lues sont présentées à l'utilisateur.
    
    
    //(1)
    this.CheckNotifications = function () {
        var th = this;
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    //TODO : Send error to SERVER
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    th.DisplayNotifAlerts(datas);
                } else {
                    th.UpdateNoNotifications();
                }
                
            } catch (e) {
                //TODO : ?
            }
        };

        var onerror = function(a,b,c) {
//            alert("AJAX ERR : "+th.checkserv_urq);
        };

        var toSend = {
            "urqid": th.checkserv_urq
        };

        Kx_XHR_Send(toSend, "post", this.checkserv_url, onerror, onsuccess);
    };
    
    
    //(3)
    this.DisplayNotifInSlave = function(argv) {
        var nb = argv;
        var $obj = $("#brain_menu_notifs").find(".master_signal_new");
        
        $obj.html(nb);
        $obj.removeClass("this_hide");
    };
    
    //(2)
    this.DisplayNotifInOpenBrain = function(argv) {
        var nb = argv;
        var $obj = $("#opb_signal_new");
        
        $obj.html(nb);
        $obj.removeClass("this_hide");
    };
    
    //(2) et (3)
    this.DisplayNotifAlerts = function(datas) {
        var nb = Kxlib_ObjectChild_Count(datas), brain_isOpen = $("#brain_maximus").data("isopen");
        brain_isOpen = ( brain_isOpen === "1" ) ? true : false;
        
        //Cela dépend du statut de BRAIN
        if ( brain_isOpen ) {
            //Affichage du nombre de notifications dans SLAVE
            this.DisplayNotifInSlave(nb);
        } else {
            //Affichage du nombre de notfications sur le Bouton "OPEN BRAIN" 
            this.DisplayNotifInOpenBrain(nb);
        }
        
        //Permet de dire : De nouvelles notifications non lues sont 
        $("#brain_menu_notifs").data("unrNotf",nb) ;
    };
        
    //Permet de retirer les marques de notifications     
    this.UpdateNoNotifications = function (){
        //Permet de dire : Aucune nouvelle notification non lue n'est disponible
        $("#brain_menu_notifs").data("unrNotf","0") ;
        
        //On retire le signalement au niveau de MASTER
        $("#brain_menu_notifs").find(".master_signal_new").html(0);
        $("#brain_menu_notifs").find(".master_signal_new").addClass("this_hide");
        
        //On retire le signalement au niveau de OPBrain
        $("#opb_signal_new").html(0);
        $("#opb_signal_new").addClass("this_hide");
    };
    
    this.FormatUANotifText = function (argv ) {
        var code = argv;
        
        switch (code) {
            case "std": 
                //Correspond au texte normal présent dans le bloc de notification
                //<span class="net_onepsd_max">@<a class="net_onepsd_link"href="">Rihanna</a></span> just folled you. Message here !
                break;
            case "psd": 
                //Correspond à la repré sentaion d'un pseudo
                break;
        }
    };
    
    //*/
    
    //Anime l'affichage de la notif faisant suite à l'action de l'utilisateur
    this.DisplayUserActionNotf = function (m,ise) {
        //On affiche le bloc dans tous les cas
        $(".jb-noty-evt-pan").removeClass("this_hide");
//        var code = argv;
                
        if ( KgbLib_CheckNullity(m) || m === false ) {
            /*
             * On retire la classe qui indique qu'il s'agit d'une erreur.
             * Je veux faire la distinction entre une erreur et ce cas.
             */
            $(".jb-noty-evt-pan").removeClass("ise");
            
            //On affiche le bloc contenant le message d'erreur
            $(".jb-noty-evt-err").removeClass("this_hide");
            
            //On détruit le bloc affichant le texte normal s'il n'a pas été détruit quelque part
            $("#notify_event_text").remove();
            
            return false;
        } else {
            //On crée le bloc d'affichage du message
//            var elmnt = $('<p id="notify_event_text"></p>');
            var e = $("<p/>").attr({
                "id": "notify_event_text"
            });
            
            //S'il s'agit d'afficher une erreur, on change le style de la zone
            if ( ise ) {
                $(".jb-noty-evt-pan").addClass("ise");
            } else {
                $(".jb-noty-evt-pan").removeClass("ise");
            }
            
            //On insère le message
            //[NOTE 10-10-14] @author Attention, c'est bien html() et non text()
            e.html(m);
            
            $(".jb-noty-evt-pan").append(e);
            
            return true;
        }
    }; 
    
    this.HideUserActionNotf = function () {
        $(".jb-noty-evt-pan").toggleClass("ua_notf_gotop", 250);
        //alert("UAnotf : animation");
        
        //On attends que l'animation soit terminée, puis on continue
        setTimeout(function(){
            //alert("UAnotf : reset");
            //On masque le bloc contenant le message d'erreur
            $(".jb-noty-evt-err").addClass("this_hide");
            
            //On détruit le bloc affichant le texte normal s'il n'a pas été détruit quelque part
            $("#notify_event_text").remove();
            
            //On hide le MAX
            $(".jb-noty-evt-pan").addClass("this_hide");
            
            //On retire la classe qui sert d'animation
            $(".jb-noty-evt-pan").removeClass("ua_notf_gotop");
        },300);
    };
    
    /*
    //Permet de choisir le message à afficher puis d'afficher une notification faisant suite à l'action d'un utilisateur
    this.FromUserAction = function (c) {
        
        if ( KgbLib_CheckNullity(c) ) return;
        
        var time, ret, m, th = this;
        
        
        //UA = User Action
        //Le switch permet de traiter les cas particuliers. Par exemple, s'il faut modifier le message à afficher avant de l'afficher.
        switch(c) {
            //Afficher une notification suite à l'ajout d'un article Std InMylife
            case "ua_new_artiml" :
            //Afficher une notification suite à l'ajout d'un article Std InTREND
            case "ua_new_artintr" :
            //Afficher une notification suite à l'ajout d'une nouvelle Tendance par le Owner
                
            case "ua_new_mytr" : 
            //Afficher une notification suite à la modification des paramètres d'une Tendance
            case "ua_trpg_new_setgs" : 
                //Afficher une notification suite à l'ajout d'un article ???
                    //Récupérer le message
                    m = Kxlib_getDolphinsValue(c);
                break;
            case "UA_ULTMT_FOLL" :
                    m = Kxlib_getDolphinsValue(c);
                    var up = Kxlib_GetOwnerPgPropIfExist().upsd;
                    m = m.replace("%owner%",up);
                break;
            default:
                //TODO : On envoit un message au serveur lui indiquant qu'un code inconnu a été utilisé.
                break;
        }
        

        ret = this.DisplayUserActionNotf(m);
        
        /*
         * Si on a pas trouvé le code, on laisse un delai beaucoup plus grand pour laisser l'opportunité ...
         * ... à l'utilisateur de lire et comprendre
         
        time = (! ret) ? 10000 : 5000;
        setTimeout(th.HideUserActionNotf,time);
    };

//*/
    this.SignalForNewReaction = function (c) {
        /*
         * Permet d'affihcer une notification à chaque fois qu'un utilisateur ajoute un nouvel Article.
         * Les messages sont choisis selon un code venant du serveur. Le secret est gardé sur la manière dont ce code est choisi.
         * Les messages sont fait pour être punchy et joyeux !
         * Un message par défaut plus sobre existe.
         */
        c = ( KgbLib_CheckNullity(c) ) ? -1 : c;
        
        //UserActionCode
        var uac;
        switch (c) {
            case 1 :
                    uac = "ua_add_nr_std";
                break;
            case 2 :
                    uac = "ua_add_nr_mr";
                break;
            case 3 :
                    uac = "ua_add_nr_ws";
                break;
            case 4 :
                    uac = "ua_add_nr_wd";
                break;
            case 5 :
                    uac = "ua_add_nr_ydi";
                break;
            case 6 :
                    uac = "ua_add_nr_hwg";
                break;
            default:
                    uac = "ua_add_nr_std";
                break;
        }
        
        this.FromUserAction(uac);
        
    };
            
            
    this.FromUserAction = function (c,o,ise) {
        /*
         *  c = Code à charger dans les Dolphins, 
         *  o = Options, tableau contenant les données qui remplaceront les tags dans la chaine.
         *  ise = ISError, permet de dire qu'on veut afficher une erreur plutot qu'un simple message d'information
         */
        if ( KgbLib_CheckNullity(c) ) { 
            return;
        }
        
        var t, r, m;
        
        //On récupère le texte auprès de Dolphins
        m = Kxlib_getDolphinsValue(c);
        
        if ( typeof m !== "undefined" && m !== false ) {
            var tgs;
            //On vérifie s'il y a des marqueurs.
            tgs = Kxlib_Extract_All_DMD(m);
            
            if ( tgs && ( typeof o === "object" && Kxlib_ObjectChild_Count(o) ) ) {
                /*
                 * On parcours le tableau contenant les DMD.
                 * Pour chaque élément, on vérifie s'il se trouve dans l'objet des Options. 
                 * S'il ne s'y trouv 
                 */
                $.each(o,function(k,v){
                    if ( $.inArray(k,tgs) ) {
                        m = Kxlib_DolphinsReplaceDmd(m,k,o[k]);
                    } else {
                        //On arrete tout et envoi un 'undefined'. Le but étant que les DMD ne soient jamais affichés
                        m = undefined;
                        return false;
                    }
                });
            } else if ( tgs && !(typeof o === "object" && Kxlib_ObjectChild_Count(o) ) ) {
                /*
                 * [NOTE 01-09-14] @author L.C.
                 * Plusieurs choix se présentent. 
                 *  (1) Indiquer l'erreur à l'utilsateur en lui indiquant clairement que cela n'a aucune incidence sur son action mais que le problème vient du module de NOTIF
                 *  (2) Ne rien afficher.
                 * 
                 * ARGUMENTAIRE :
                 *  (1) Cela permet à l'utilisateur d'avoir un retour, c'est rassurant. 
                 *      Le problème ne vient pas de son action mais de quelque chose qui n'est pas grave mais qui ne l'empeche pas de continuer ses actions.
                 *      Mais encore, cela montre que c'est un produit sérieux et qu'il a été étudié pour fonctionner coute que coute. Que rien n'ets laisser au hasard !
                 *  (2) L'utilisateur ne sait pas qu'il y a une erreur. Cependant, il n'a aucun retour. Cela peut être embettant. En effet, s'il a l'habitude voir
                 *      un message et qu'il n'apparait plus j'ai peur qu'il tire des conclusions comme : "Le produit est instable et on dirait qu'ils n'y peuvent rien. Ca me fait peur"
                 */
                //On défini le message à 'undefined' de telle sorte qu'une erreur soit déclencher
                m = undefined;
            }
        } //Sinon, 'm' ira au niveau de l'afficheur et il affichera une erreur.
        
        if ( $(".jb-noty-evt-pan").hasClass("this_hide") ) {
            r = this.DisplayUserActionNotf(m,ise);
            
            if ( !r ) 
                t = 15000;
            else if (ise) {
                t = 10000;
            } else {
                t = 5000;
            }
//            t = ( !r | ise) ? 10000 : 5000;
            setTimeout(this.HideUserActionNotf,t);
        } else {
            //TODO : On verra !
        }
        
    };
    
}
    
(function() {
    
    /*
    var obj = new Notifyzing();
    
    
    $("#opref").change(function(){
        var nb = $("#brain_menu_notifs").data("unrNotf");
        //alert(parseInt(nb));
        //S'il existe des notifs non lues
        if ( parseInt(nb) > 0 ) { 
            //alert($("#brain_maximus").data("isopen"));
            var op = ( $("#brain_maximus").data("isopen") === "1" ) ? true: false;
            
            if ( op ) {
                $("#brain_menu_notifs").find(".master_signal_new").html(nb);
                $("#brain_menu_notifs").find(".master_signal_new").removeClass("this_hide");
            } 
            else {
                $("#opb_signal_new").html(nb);
                $("#opb_signal_new").removeClass("this_hide");
            }
        }
    });
    
    
    setInterval( function(){
        obj.CheckNotifications();
    }, 1000 );
    //*/
})();