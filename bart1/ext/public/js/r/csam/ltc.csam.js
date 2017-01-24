/*
 * LTC => LiveTrendChat
 */
define("r/csam/ltc.csam",["autobahn"],function(ab){
    //cfd : ConstructorFeed
    return function LTC(cfd) {
        /*
         * {
         *      trigger     : Le bouton qui est à l'origine l'évènement
         *      action      : Le libellé de l'action  
         *      autoload    : Lancer l'affichage du viewer si les données sont passées et qu'elles sont valides
         * }
         */
        var cargs = cfd;

        /*
         *  iRdy        : IsrReaDY
         *  md_slc      : MoDelSeLeCtion
         *  md_props    : ModelProperties
         */
        var iRdy, md_slc, md_props;
        /*
         * 
         */
        var trgList = [""];
        var cfdKeys = ["trigger","action"];
        
        var $sprt = $(".jb-tqr-ltc-sprt");
        
        /****** XHR REFERENCE ******/
        var _xhr_sdms;
        
        /****** CONSTANTS ******/
        var KEY_RETURN = 13;    
        
        /****** SOCKET ******/
        var ab_connection;
        var ab_session;
        var ab_ssid;
        var ab_isOpen = false;
        /*
         * ab_ntvChannels : NaTiVeCHANNELS
         */
        var ab_ntvChannels = {
            /*
             * ONJOIN : Un UTILISATEUR se connecte au CHAT
             *      Mettre à jour le nombre la donnée sur le nombre de personnes connectées.
             */
            "onjoin"    : "ltc.session.event.onjoin", //Un nouvel UTILISATEUR se connecte au CHAT
            /*
             * ONLEAVE : Un UTILISATEUR quitte le CHAT
             *      Mettre à jour le nombre la donnée sur le nombre de personnes connectées.
             */
            "onleave"   : "ltc.session.event.onleave", 
        };
        /*
         * msChannels : MoReCHANNELS
         */
        var mrChannels = {};
        
        /*******************************************************************************************************************************************************************/
        /**************************************************************************** PROCESS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        
        var _f_Gdf = function () {
            var dt = {
//                ab_url      : "wss://trenqr.com/l/?tid=",
                ab_url      : "ws://127.0.0.1:8081?tid=",
                ab_realm    : "ltc.realm.wolverine",
                ab_mxrt     : 30
            }; 

            return dt;
        };
        
        var _f_Init = function () {
            try {
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Action = function (x,a,e) {
            try {
                if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                    return;
                } 
                
                var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a : $(x).data("action"); 
                switch (_a) {
                    case "send-msg" :
                            _f_AddMsg(x,e);
                        break;
                    case "tag-user" :
                            _f_TagUser(x);
                        break;
                    case "ltc-hide" :
                            _f_HideApp(x);
                        break;
                    case "ltc-show" :
                            _f_ShowApp(x);
                        break;
                    case "hoit-add" :
                            _f_Hoit_Add(x);
                        break;
                    case "hoit-remove" :
                            _f_Hoit_Rmv(x);
                        break;
                    case "hoit-open" :
                            _f_Hoit_Io(true);
                        break;
                    case "hoit-close" :
                            _f_Hoit_Io();
                        break;
                    default :
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        
        var _f_AddMsg = function (x,e) {
            try {
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                } 
                
//                if (! sok_open ) {
//                    alert("Aucune connexion valide n'est disponible");
//                    return;
//                }
                
//                if ( $(x).data("lk") === 1 ) {
//                    return;
//                }
//                $(x).data("lk",0);
                
                var text = $(".jb-tqr-ltc-s-f-txar").val();
                
                $(".jb-tqr-ltc-s-f-txar").val("");
                
                var s = $("<span/>"), hopi = (new Date()).getTime();
                
                var prm = {
                    tri     : ( Kxlib_GetTrendPropIfExist() ) ? Kxlib_GetTrendPropIfExist().trid : null,
                    wcsi    : ab_ssid,
                    tx      : text,
                    pi      : "",
                    pt      : ""
                };
                /*
                Kxlib_DebugVars([JSON.stringify(prm)],true);
                return;
                //*/
                _f_Srv_SndMs(hopi, prm.tri, prm.wcsi, prm.tx, prm.pi, prm.pt, x, s);
                
                $(s).on("datasready",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
                    
                    $(x).data("lk",0);
                    _xhr_sdms = null;
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ApdMsg = function (ms) {
            try {
                if ( KgbLib_CheckNullity(ms) ) {
                    return;
                } 
                
                $(".jb-tqr-ltc-scrn-wlc").stop(true,true).fadeOut().addClass("this_hide");
                $(".jb-tqr-ltc-list-lvms").stop(true,true).hide().removeClass("this_hide").fadeIn();
                
                //ms : MessageScope
                if ( $(".jb-tqr-ltc-msg-art").filter("[data-item='".concat(ms.msg.id,"']")).length ) {
                    return;
                }

                var mm = _f_PprMdl(ms);
                mm = _f_RbdMdl(mm);
                $(".jb-tqr-ltc-list-lvms").append(mm);
                
                _f_ScrollZn();
                
                $(".jb-tqr-ltc-scrn-bdy").perfectScrollbar("update");
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_TagUser = function (x) {
            try {
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                } 
                
//                var $mm = ( $(x).closest(".jb-tqr-ltc-msg-art") ) 
                var $mm = ( $(x).data("scp") === "std" )
                    ? $(x).closest(".jb-tqr-ltc-msg-art") 
                    : $(x).closest(".jb-tqr-ltc-s-ap-hoit-amx");
                
                                      
                var text = $mm.find(".jb-tqr-ltc-m-ubx-p").text();
                
                $(".jb-tqr-ltc-s-f-txar").val(function(a,prev){
                    return prev.concat(text," ");
                }); 
                $(".jb-tqr-ltc-s-f-txar").focus();
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Start = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                } 
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_LtcIsOnpn = function () {
            try {
                var akx = $(".jb-tqr-ltc-scrn-cnxnb").data("access");
                return ( akx && akx === "1"  ) ? true : false;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ShowApp = function (x) {
            try {
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                } 
                
                var trmetas = Kxlib_GetTrendPropIfExist();
                if ( !trmetas && KgbLib_CheckNullity(trmetas.trid) ) {
                    throw "Unable to get acces to Trend's datas. They are mandatory.";
                }
                
                /*
                 * ETAPE : 
                 *      Afficher la zone au niveau de la PAGE
                 */
                var r = parseInt($(".jb-page").css("margin-right").slice(0,-2));
                $(".jb-tqr-ltc-scrn").stop(true,true).animate({
                    right : r
                },function(){
                   /*
                    * ETAPE : 
                    *      Lancer le processus de connexion si ce n'est pas déjà fait
                    */
                   if (! ab_isOpen ) {
                       ab_connection = new ab.Connection({
                           url          : _f_Gdf().ab_url.concat(trmetas.trid),
                           realm        : _f_Gdf().ab_realm,
                           max_retries  : _f_Gdf().ab_mxrt
                       });

                       ab_connection.onopen = onConnect;
                       ab_connection.onclose = onClose;
                       ab_connection.open();
                   }
//                   _f_Hoit_Io(true);
                }).data("access",1);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_HideApp = function (x) {
            try {
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                } 
                
                if ( _f_Hoit_Io() ) {
                    _f_Hoit_Io();
                }
                
                var r = ($(".jb-tqr-ltc-scrn").width()+2)*-1;
                $(".jb-tqr-ltc-scrn").stop(true,true).animate({
                    right: r
                },function(){
                }).data("access",0);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ScrollZn = function () {
            try {

                var $tar = $(".jb-tqr-ltc-scrn-bdy");
                if ( $tar.find(".jb-tqr-ltc-msg-art").length ) {
                    var r__ = $tar.find(".jb-tqr-ltc-msg-art"), h__ = 0;
                    $.each(r__, function(i, rc) {
                        h__ += $(rc).height();
                    });

                    $($tar).animate({scrollTop: h__}, 1500);
                } else {
                    $($tar).animate({scrollTop: $($tar).height()}, 1500);
                }

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /*********************************************************************************************************************************************************************/
        /***************************************************************************** HOIT SCOPE ****************************************************************************/
        
        
        var _f_Hoit_IsOpen = function () {
            try {
                var akx = $(".jb-tqr-ltc-s-ap-hoit").data("access");
                return ( akx && akx === "access" ) ? true : false;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Hoit_Io = function (shw) {
            try {
                
                var pazn_w = 500, ltc_w = 340;
                if ( shw ) {
                    $(".jb-tqr-ltc-scrn").stop(true,true).css({
                        overflow : "visible"
                    },400);
                    $(".jb-tqr-ltc-s-ap-hoit").data("access",1);
                } else {
                    $(".jb-tqr-ltc-scrn").stop(true,true).css({
                        overflow : "hidden"
                    },400);
                    $(".jb-tqr-ltc-s-ap-hoit").data("access",0);
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Hoit_Add = function (x) {
            try {
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                }
                
                var amx = $(x).closest(".jb-tqr-ltc-msg-art");
                if (! $(amx).length ) {
                    return;
                }
                
                /*
                 * ETAPE : 
                 *      On récupère les données qui seront ajoutés
                 */
                var ajca = $(amx).data("ajcache");
                if ( KgbLib_CheckNullity(ajca) ) {
                    return;
                } 
                var ajca_o = $.parseJSON(ajca);
                if (! ( typeof ajca_o === "object" && !KgbLib_CheckNullity(ajca_o) ) ) {
                    return;
                } 
                
//                Kxlib_DebugVars([JSON.stringify(ajca_o)],true);
//                return;

                var ai = ajca_o.msg.id;
                if ( !ai | $(".jb-tqr-ltc-s-ap-hoit-amx[data-item='"+ai+"']").length ) {
                    if (! _f_LtcIsOnpn() ) {
                        _f_Hoit_Io(true);
                    }
                    return;
                }
                
                /*
                 * ETAPE : 
                 *      On PREPARE l'élément à être ajouté
                 */
                var m = _f_Hoit_PprA(ajca_o);
                
                /*
                 * ETAPE : 
                 *      On REBIND l'élément à être ajouté
                 */
                m = _f_Hoit_RbdMdl(m);
                
                /*
                 * ETAPE :
                 *      S'il la zone doit être fermée on l'ouvre
                 */
                if (! _f_Hoit_IsOpen() ) {
                    _f_Hoit_Io(true);
                }
                
                /*
                 * ETAPE : 
                 *      On ajoute l'élément dans la liste
                 */
                $(m).hide().prependTo(".jb-tqr-ltc-s-ap-hoit-bdy").fadeIn();
                
                
                /*
                 * ETAPE : 
                 *      On met à jour le nombre dans la liste
                 */
                var nb = $(".jb-tqr-ltc-s-ap-hoit-bdy").find(".jb-tqr-ltc-s-ap-hoit-amx").length;
                $(".jb-tqr-ltc-s-f-hoit ._figure").text("(".concat(nb,")"));
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Hoit_Rmv = function (x) {
            try {
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                }
                
                var amx = $(x).closest(".jb-tqr-ltc-s-ap-hoit-amx");
                if (! $(amx).length ) {
                    return;
                }
                
                /*
                 * ETAPE : 
                 *      On retire l'élément de la liste
                 */
                $(amx).remove();
                
                /*
                 * ETAPE :
                 *      S'il n'y a plus d'éléments, la zone doit être fermée
                 */
                if (! $(".jb-tqr-ltc-s-ap-hoit-amx").length ) {
                    _f_Hoit_Io();
                }
                
                
                /*
                 * ETAPE : 
                 *      On met à jour le nombre dans la liste
                 */
                var nb = $(".jb-tqr-ltc-s-ap-hoit-bdy").find(".jb-tqr-ltc-s-ap-hoit-amx").length;
                if ( nb ) {
                    $(".jb-tqr-ltc-s-f-hoit ._figure").text("(".concat(nb,")"));
                } else {
                    $(".jb-tqr-ltc-s-f-hoit ._figure").text("");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        /*********************************************************************************************************************************************************************/
        /**************************************************************************** SOCKET SCOPE ***************************************************************************/
//        socket = new WebSocket("ws://localhost:8081");
//        socket = new WebSocket("ws://localhost:11211");

        
        var onConnect = function (session,transport,details) {
            try {
                ab_session = session;
                ab_ssid = session.id;
                
                /*
                 * ETAPE :
                 *       On crée un nom de CANAL relatif à la TENDANCE relative au CHAT sur lequel on veut se connecter.
                 * [NOTE]
                 *   MessageTUNE : 
                 *       -> Reception de message.
                 *       -> Le processus d'envoi est opéré via AJAX.
                 */
                var tuname = "ltc.chat.app.mtune.";
                //On récupère l'identifiant de la Tendance
                var trmetas = Kxlib_GetTrendPropIfExist();
                if ( KgbLib_CheckNullity(trmetas) | !( trmetas.hasOwnProperty("trid") && trmetas.trid ) ) {
                    return;
                }
                tuname += trmetas.trid;
                ab_ntvChannels["mtune"] = tuname;
                
                /*
                 * ETAPE :
                 *      On s'abonne aux chaines natives
                 */
                subToNatives();
                
                ab_isOpen = true;
                
                /*
                 * ETAPE :
                 *      On indique au niveau du BOUTON I/O du module que la CONX est effective.
                 */
                $(".jb-tqr-trpg-ckp-akxn")
                    .data({
                        "cntd_sts" : "connected"
                    })
                    .attr({
                        "data-cntd_sts" : "connected"
                    });
                
                $(".jb-tqr-ltc-scrn-pndg").addClass("this_hide");
                $(".jb-tqr-ltc-scrn-cnxnb").removeClass("this_hide");
                
                Kxlib_DebugVars(["LTC : Connected from JS :"+session.id]);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var subToNatives = function () {
            try {
                $.each(ab_ntvChannels,function(cn,cl){
                    switch (cn) {
                        case "onjoin" :
                                ab_session.subscribe(cl, onJoin);
                            break;
                        case "mtune" :
                                ab_session.subscribe(cl, onMessage);
                            break;
                        case "onleave" :
                                ab_session.subscribe(cl, onLeave);
                            break;
                    }
                });
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var onJoin = function (args,kwargs) {
            try {
                Kxlib_DebugVars(["LTC : onJoin",JSON.stringify(kwargs),kwargs.online]);
                
                $(".jb-tqr-ltc-scrn-cnxnb ._figure").text(kwargs.online);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var onMessage = function (args,kwargs) {
            try {
                Kxlib_DebugVars(["LTC : onMessage",JSON.stringify(kwargs)]);
//                return;
                $(".jb-tqr-ltc-scrn-cnxnb ._figure").text(kwargs.online);
                _f_ApdMsg(kwargs);
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var onLeave = function (args,kwargs) {
            try {
                Kxlib_DebugVars(["LTC : onLeave",JSON.stringify(kwargs),kwargs.online]);
                
                
                $(".jb-tqr-ltc-scrn-cnxnb ._figure").text(kwargs.online);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var onClose = function (reason) {
            try {
                switch (reason) {
                    case "closed" :
                            Kxlib_DebugVars(["LTC : onClose",JSON.stringify(reason)]);
                        break;
                    case "lost" :
                            Kxlib_DebugVars(["LTC : onClose",JSON.stringify(reason)]);
                        break;
                    case "unreachable" :
                            Kxlib_DebugVars(["LTC : onClose",JSON.stringify(reason)]);
                        break;
                    case "unsupported" :
                            Kxlib_DebugVars(["LTC : onClose",JSON.stringify(reason)]);
                        break;
                }
                ab_isOpen = false;
                
                /*
                 * ETAPE :
                 *      On indique au niveau du BOUTON I/O du module que la CONX est effective.
                 */
                $(".jb-tqr-trpg-ckp-akxn")
                    .data({
                        "cntd_sts" : "pending"
                    })
                    .attr({
                        "data-cntd_sts" : "pending"
                    });
                
                $(".jb-tqr-ltc-scrn-cnxnb").addClass("this_hide");
                $(".jb-tqr-ltc-scrn-pndg").removeClass("this_hide");
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var subscribeTo = function (chan) {
            try {
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /*
        var sess;
        var KEY_RETURN = 13;
        var channels = [];
        var defaultChannels = ['channel:jmoz'];
        var debug = false;
        
        ab.connect("ws://localhost:8081",
            // WAMP session was established
            function (session) {
                alert("AB son");
            // things to do once the session has been established
                    console.log("ab: session connected");
                    sess = session;
                    on_connect();
            },
            // WAMP session is gone
            function (code, reason) {
                // things to do once the session fails
                notify(reason, 'error');
                console.log("ab: session gone code " + code + " reason " + reason);
            }
        );
        
        on_connect = function() {
            // initialise default channels
            console.log("ab: subscribing to default channels");
            $.each(defaultChannels, function (i, el) {
                    subscribe_to(el);
                    add_channel(el);
            });
        };
        
        subscribe_to = function (chan) {
            if (!add_channel(chan)) {
                    return false;
            }
            sess.subscribe(chan, function (channel, event) {
                console.log("ab: channel: " + channel + " event: " + event);
                add_response(event);
                notify("Message: " + event, 'info');
            });
            console.log("ab: subscribed to: " + chan);
            notify("Subscribed to channel " + chan, 'success');
            return true;
        };
        
        unsubscribe = function(channel) {
            remove_channel(channel)
            sess.unsubscribe(channel)
            console.log("ab: unsubscribed from: " + channel)
            notify('Unsubscribed from channel ' + channel, 'warning')
        };
        
        publish = function(channel, message) {
            sess.publish(channel, message);
        };
        
        redis_publish = function(message) {
            $.post('', {"pub": message, "channel":get_channel()}, function (data) {
                    console.log("pubsub: ajax response: " + data);
            });
        };

        add_channel = function (channel) {
            if (channels.indexOf(channel) != -1) {
                    return false;
            }
            channels.push(channel);
            $('ul.channels').append('<li>' + channel + '</li>');
            $('select.channels').append('<option>' + channel + '</option>');
            return channels;
        };
        remove_channel = function (channel) {
            i = channels.indexOf(channel)
            if (i == -1) {
                    return false
            }
            channels.splice(i, 1)
            $('ul.channels li').filter(function() { return $.text([this]) === channel; }).remove();
            $('select.channels option').filter(function() { return $.text([this]) === channel; }).remove();
            return channels
        };
        
        get_channel = function () {
            return $('select.channels').val();
        };
        
        notify = function (message, type) {
            n = $('#notify');
            n.stop().text(message).css({opacity: 1}).removeClass().addClass('alert alert-' + type)
            n.delay(1000).fadeTo(2000, 0.3);
        };

        add_response = function (text, target) {
            if (!target) {
                    target = '#response'
            }
            $(target).val(function (i, val) {
                    return text + "\n" + val;
            });
        };
        
        socket.onopen = function() {
            try {
                sok_open = true;
                console.log("SOCKET : COONECTED");
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        socket.onmessage = function(e) {
            try {
                console.log("SOCKET : MESSAGE");
                var data = JSON.parse(e.data);
                Kxlib_DebugVars([data],true);
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        socket.onclose = function() {
            try {
                console.log("SOCKET : CONNECTION CLOSED");
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        socket.onerror = function() {
            try {
                console.log("SOCKET : ERROR");
                $(".jb-tqr-ltc-action[data-action='send-msg']").data("lk",0);
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        //*/
        
        /*********************************************************************************************************************************************************************/
        /**************************************************************************** OPTIONS SCOPE *************************************************************************/
        
        
        
        
        
        /*********************************************************************************************************************************************************************/
        /**************************************************************************** ACCESSORS SCOPE ************************************************************************/
        /*********************************************************************************************************************************************************************/
        
        
        /*******************************************************************************************************************************************************************/
        /***************************************************************************** SERVER SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        var _Ax_SndMs = Kxlib_GetAjaxRules("TQR_LTC_MSG_SEND");
        var _f_Srv_SndMs = function(hopi,tri,wcsi,ms,pi,pt,x,s) {
            if ( KgbLib_CheckNullity(hopi) | KgbLib_CheckNullity(tri) | KgbLib_CheckNullity(wcsi) | KgbLib_CheckNullity(ms) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
            //            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_sdms = null;
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_sdms = null;
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_CU_GONE" :
                                Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_FAILED" :
                                //TODO : Retirer l'image en Buffer
                                //Afficher le message adéquat dans la zone d'erreur
                                Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DENY" :
                            case "__ERR_VOL_DENY_AKX" :
                                break;
                            case "__ERR_VOL_DNY_AKX_AUTH" :
                                if ( $(".jb-tqr-btm-lock").length ) {
                                    $(".jb-tqr-btm-lock").removeClass("this_hide");
                                    $(".jb-tqr-btm-lock-fd").removeClass("this_hide");
                                }
                                break;
                            default :
                                Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    var ds = [datas.return];
                    $(s).trigger("operended",ds);
                    return;
                } 
            } catch (ex) {
                if (! KgbLib_CheckNullity(x) ) {
                    $(x).data("lk",0);
                }
                _xhr_sdms = null;
                
                //TODO : Renvoyer l'erreur au serveur
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

            //TODO : Send error to SERVER
            //            Kxlib_DebugVars(["AJAX ERR : "+nwtrdart_uq],true);
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };

        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_SndMs.urqid,
            "datas": {
                //HttpOPerationId
                "hopi"  : hopi,
                "tri"   : tri,
                "wcsi"  : wcsi,
                "ms"    : ms,
                "lmpi"  : pi,
                "lmpt"  : pt,
                "cu"    : curl
            }
        };

        _xhr_sdms = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SndMs.url, wcrdtl : _Ax_SndMs.wcrdtl });
    };
        
        /*******************************************************************************************************************************************************************/
        /****************************************************************************** VIEW SCOPE *************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        
        var _f_PprMdl = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * 
                 */
                
                var $ml = "<article class=\"tqr-ltc-msg-art jb-tqr-ltc-msg-art\" >";
                $ml += "<div>";
                $ml += "<a class=\"tqr-ltc-m-ubx jb-tqr-ltc-m-ubx\" href=\"\">";
                $ml += "<img class=\"tqr-ltc-m-ubx-i jb-tqr-ltc-m-ubx-i\" height=\"28\" width=\"28\" alt=\"Nom Complet (@Pseudo)\" src=\"http://www.placehold.it/28/28\" />";
                $ml += "<a class=\"tqr-ltc-m-ubx-p jb-tqr-ltc-m-ubx-p jb-tqr-ltc-action cursor-pointer\" data-action=\"tag-user\" data-scp=\"std\" title=\"Nom Complet\">@Pseudo</a>";
                $ml += "</a>";
                $ml += "<span class=\"tqr-ltc-m-cny jb-tqr-ltc-m-cny this_hide\">[FR]</span>";
                $ml += "<span class=\"tqr-ltc-m-msg jb-tqr-ltc-m-msg\"></span>";
                $ml += "</div>";
                // Extras : Signaler, Suivre
                $ml += "<div class=\"tqr-ltc-msg-art-ftr\">";
                $ml += "<a class=\"tqr-ltc-amx-opts jb-tqr-ltc-amx-opts\" data-action=\"hoit-add\"></a>";
                $ml += "</div>";
                $ml += "</article>";
                $ml = $($.parseHTML($ml));
                
                
                $ml
                    .attr("data-item",d.msg.id)
                    .data({
                        "item"      : d.msg.id,
                        "time"      : d.msg.date,
                        "ajcache"   : JSON.stringify(d)
                    })
                    .attr({
                        "item"      : d.msg.id,
                        "time"      : d.msg.date,
                        "ajcache"   : JSON.stringify(d)
                    });
                    
                $ml.find(".jb-tqr-ltc-amx-opts[data-action='hoit-add']").text("Mettre de côté");
                
                $ml.find(".jb-tqr-ltc-m-ubx").prop("href","/".concat(d.user.ps));
                //PP
                $ml.find(".jb-tqr-ltc-m-ubx-i").prop("src",d.user.pp);
                //PSD
                $ml.find(".jb-tqr-ltc-m-ubx-p").text("@".concat(d.user.ps));
                
                var ustgs = ( d.msg.ustgs ) ? d.msg.ustgs : null;
                var hashs = ( d.msg.hashs ) ? d.msg.hashs : null;

//                var txt = Kxlib_Decode_After_Encode(d.msg.ms);
                var txt = d.msg.ms;

                //rtxt = RenderedText
                var rtxt = Kxlib_TextEmpow(txt,ustgs,hashs,null,{
                    "ena_inner_link" : {
//                        "local" : true, //DEV, DEBUG, TEST
                        "all"   : false,
                        "only"  : "fksa"
                    },
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 20,
                        "position_y"    : 3,
                    }
                });
                $ml.find(".jb-tqr-ltc-m-msg").text("").append(rtxt);
                
                /*
                 * ETAPE :
                 *      On vérifie s'il s'agit d'un MESSAGE écrit par une personne qui a le statut d'ADMIN.
                 *      Dans ce cas, 
                 */
                if ( d.hasOwnProperty("xtras") && !KgbLib_CheckNullity(d.xtras) && !KgbLib_CheckNullity(d.xtras.is_admin) && d.xtras.is_admin === true ) {
                    $ml.addClass("admin");
                }
                
                
                return $ml;
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_RbdMdl = function (mb) {
            try {
                if ( KgbLib_CheckNullity(mb) ) {
                    return;
                }
                
                $rm = $(mb);
                
                $rm.find(".jb-tqr-ltc-amx-opts, .jb-tqr-ltc-action").click(function(e){
                    Kxlib_PreventDefault(e);

                    _f_Action(this,null,e);
                });
                
                return $rm;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Hoit_PprA = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                var $ml = "<article class=\"tqr-ltc-msg-art hoit jb-tqr-ltc-s-ap-hoit-amx\" >";
                $ml += "<div>";
                $ml += "<a class=\"tqr-ltc-m-ubx jb-tqr-ltc-m-ubx\" href=\"\">";
                $ml += "<img class=\"tqr-ltc-m-ubx-i jb-tqr-ltc-m-ubx-i\" height=\"28\" width=\"28\" alt=\"Nom Complet (@Pseudo)\" src=\"http://www.placehold.it/28/28\" />";
                $ml += "<a class=\"tqr-ltc-m-ubx-p jb-tqr-ltc-m-ubx-p jb-tqr-ltc-action cursor-pointer\" data-action=\"tag-user\" data-scp=\"hoit\" title=\"Nom Complet\">@Pseudo</a>";
                $ml += "</a>";
                $ml += "<span class=\"tqr-ltc-m-cny jb-tqr-ltc-m-cny this_hide\">[FR]</span>";
                $ml += "<span class=\"tqr-ltc-m-msg jb-tqr-ltc-m-msg\"></span>";
                $ml += "</div>";
                // Extras : Signaler, Suivre
                $ml += "<div class=\"tqr-ltc-s-ap-hoit-amx-ftr\">";
                $ml += "<a class=\"tqr-ltc-s-ap-hoit-amx-opts jb-tqr-ltc-s-ap-hoit-amx-opts\" data-css=\"hoit-remove\" data-action=\"hoit-remove\" title=\"Retirer de la liste\">Retirer</a>";
                $ml += "</div>";
                $ml += "</article>";
                $ml = $($.parseHTML($ml));
                
                $ml
                    .attr("data-item",d.msg.id)
                    .data({
                        "item"      : d.msg.id,
                        "time"      : d.msg.date,
                        "ajcache"   : JSON.stringify(d)
                    })
                    .attr({
                        "item"      : d.msg.id,
                        "time"      : d.msg.date,
                        "ajcache"   : JSON.stringify(d)
                    });
                    
                $ml.find(".jb-tqr-ltc-amx-opts[data-action='hoit-add']").text("Mettre de côté");
                
                $ml.find(".jb-tqr-ltc-m-ubx").prop("href","/".concat(d.user.ps));
                //PP
                $ml.find(".jb-tqr-ltc-m-ubx-i").prop("src",d.user.pp);
                //PSD
                $ml.find(".jb-tqr-ltc-m-ubx-p").text("@".concat(d.user.ps));
                
                var ustgs = ( d.msg.ustgs ) ? d.msg.ustgs : null;
                var hashs = ( d.msg.hashs ) ? d.msg.hashs : null;

//                var txt = Kxlib_Decode_After_Encode(d.msg.ms);
                var txt = d.msg.ms;

                //rtxt = RenderedText
                var rtxt = Kxlib_TextEmpow(txt,ustgs,hashs,null,{
                    "ena_link_fk"   : true,
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 20,
                        "position_y"    : 3
                    }
                });
                $ml.find(".jb-tqr-ltc-m-msg").text("").append(rtxt);
                
                return $ml;
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Hoit_RbdMdl = function (mb) {
            try {
                if ( KgbLib_CheckNullity(mb) ) {
                    return;
                }
                
                $rm = $(mb);
                
                $rm.find(".jb-tqr-ltc-s-ap-hoit-amx-opts, .jb-tqr-ltc-action").click(function(e){
                    Kxlib_PreventDefault(e);

                    _f_Action(this,null,e);
                });
                
                return $rm;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_spnr = function (shw) {
            try {
                
                if (shw) {
                    $(".jb-tlkb-uqv-a-m-s-mx").removeClass("this_hide");
                } else {
                    $(".jb-tlkb-uqv-a-m-s-mx").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_ldmore = function (shw) {
            try {
                
                if (shw) {
                    $(".jb-tlkb-uqv-a-m-l-a").removeClass("this_hide");
                } else {
                    $(".jb-tlkb-uqv-a-m-l-a").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /*******************************************************************************************************************************************************************/
        /************************************************************************** LISTENERS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        $(".jb-tqr-ltc-action, .jb-tqr-ltc-s-ap-hoit-amx-opts, .jb-tqr-ltc-s-ap-hoit-fmr").off("click").click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_Action(this,null,e);
        });
        
        $(".jb-tqr-ltc-s-f-txar").keypress(function(e){
            if ( e.charCode === 13 ) {
                 Kxlib_PreventDefault(e);
                _f_Action($(".jb-tqr-ltc-action[data-action='send-msg']"),null,e);
            }
        });
        
        /*******************************************************************************************************************************************************************/
        /************************************************************************ CONSTRUCTOR SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        /*
        try {
            $(".jb-tqr-ltc-scrn-bdy").perfectScrollbar({
                suppressScrollX : true
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        //*/
        
        _f_Init();
        
    };
});