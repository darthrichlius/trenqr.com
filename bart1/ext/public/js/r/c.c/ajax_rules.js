/*
 * [02-09-14] @author L.C.
 * 
 * J'ai changé de manière de procéder pour les AJAXRULES. L'ancienne méthode n'était pas assez sécurisée.
 * En effet, n'importe qui pouvait utiliser la console pour modifier ces informations qui sont très sensibles.
 * Aussi, j'ai décidé de faire qu'on ne puisse les accéder qu'en LECTURE. C'est le maximum que je puisse faire.
 */
function AJAXRULES (k,u) {
    //k : La clé à rechercher
    //u : user à rajouter le cas échéant
    
    if ( KgbLib_CheckNullity(k) ) {
        return;
    }
    
    //DEV, TEST, DEBUG
//    var host = "http://127.0.0.1/WOSTQR_Beta1/";
    var host = "/dev.trenqr.com/";
    //PROD
//    var host = "http://www.trenqr.com/forrest/";
//    var host = "/";
    
    /*
     * [DEPUIS 11-07-15] @BOR
     */
    var edges = {
        /*
        "ars"       : "http://edge-ax-zarts.trenqr.com/",
        "chbx-lp"   : "http://edge-ax-chbx-lp.trenqr.com/",
        "chbx-0"    : "http://edge-ax-chbx-0.trenqr.com/",
        "eval"      : "http://edge-ax-evl.trenqr.com/",
        "nwfd"      : "http://edge-ax-nwfd.trenqr.com/",
        "psmn-lp"   : "http://edge-ax-psmn-lp.trenqr.com/",
        "rct"       : "http://edge-ax-rct.trenqr.com/",
        "rel"       : "http://edge-ax-rel.trenqr.com/",
        "srhbx"     : "http://edge-ax-q.trenqr.com/"
        //*/
        /*
        "ars"       : "/",
        "chbx-lp"   : "/",
        "chbx-0"    : "/",
        "eval"      : "/",
        "nwfd"      : "/",
        "psmn-lp"   : "/",
        "rct"       : "/",
        "rel"       : "/",
        "srhbx"     : "/"
        //*/
        //*
        "ars"       : "/dev.trenqr.com/",
        "chbx-lp"   : "/dev.trenqr.com/",
        "chbx-0"    : "/dev.trenqr.com/",
        "eval"      : "/dev.trenqr.com/",
        "nwfd"      : "/dev.trenqr.com/",
        "psmn-lp"   : "/dev.trenqr.com/",
        "rct"       : "/dev.trenqr.com/",
        "rel"       : "/dev.trenqr.com/",
        "srhbx"     : "/dev.trenqr.com/"
        //*/
    };
    
    var user = (! KgbLib_CheckNullity(u) ) ? u : "";
    //MODELE : /ajax/[user/]URQID@PAGE/timestamp (leurre)
    var t = (new Date()).getTime();
    var AR_TAB = {
        "STUS" : {
            //COMMENT : Récupérer l'heure à l'instant t au niveau du server
            "urqid"     : "STUS",
            "url"       : host+"stus.php",
            "wcrdtl"    : false
        },
        "TQR_ART_PULL" : {
            //COMMENT : Récupérer les données pour les Articles dont les identifiants sont passés en paramètre. 
            "urqid"     :"TQR_ART_PULL",
            "url"       : edges["ars"]+"ajax/TPLA@profil/"+t, 
            "wcrdtl"    : true
        },
        "TQR_PULL_TR_FM_WAIO" : {
            //WAIO = WithArticleIdentifiantOption
            //COMMENT : Récupérer les données pour les Tendances dont les identifiants sont passés en paramètre accompagné des identifiants pour les Articles FIRST dont le nombre requis est passé. 
            "urqid"     :"TQR_PULL_TR_FM_WAIO",
            "url"       : host+"ajax/TPTF_WAIO@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_SEARCH" : {
            //COMMENT : Lancer une recherche. 
            "urqid"     :"TQR_SEARCH",
            "url"       : edges["srhbx"]+"ajax/Q@profil/"+t,
            "wcrdtl"    : true
        },
        "PM_SBPL_RPS" : {
            //COMMENT : Récupérer les Evaluations d'un Article en mode lecture Unique. 
            "urqid"     :"PM_SBPL_RPS",
            "url"       : edges["psmn-lp"]+"ajax/PM_SBPL_RPS@profil/"+t,
            "wcrdtl"    : true
        },
        "FKSA_EVAL_AX" : {
            //Comment   : On évalue l'AARTICLE selon les données passées par FE
            "urqid"     : "FKSA_EVAL_AX",
            "url"       : host+"ajax/EVAL_ACT@profil/"+t,
            "wcrdtl"    : true
        },
        "FKSA_PLES" : {
            //COMMENT : Récupérer les Evaluations d'un Article en mode lecture Unique.
            "urqid"     :"FKSA_PL_ES",
            "url"       : host+"ajax/FKSA_PL_ES@focus/"+t,
            "wcrdtl"    : false
        },
        "FKSA_PLRS" : {
            //COMMENT : Récupérer les Commentaires d'un Article en mode lecture Unique. 
            "urqid"     :"FKSA_PL_MS",
            "url"       : host+"ajax/FKSA_PL_MS@focus/"+t,
            "wcrdtl"    : false
        },
        "FKSA_PLSMPL" : {
            //COMMENT : Récupérer les Commentaires d'un Article en mode lecture Unique. 
            "urqid"     :"FKSA_PL_SMPL",
            "url"       : host+"ajax/FKSA_PL_SMPL@focus/"+t,
            "wcrdtl"    : false
        },
        "CHBX_SEARCH" : {
            //COMMENT : Lancer une recherche sur les Conversations et Utilisateurs en fonction de la chaine de recherche pour ChatBox
            "urqid"     :"CHBX_SEARCH",
            "url"       : edges["chbx-0"]+"ajax/"+user+"/CHBX_SRH@profil/"+t,
            "wcrdtl"    : true
        },
        "CHBX_SUB_M" : {
            //COMMENT : Ajouter un message à une Conversation. Qu'il s'agisse d'une nouvelle Conversation ou pas.
            "urqid"     :"CHBX_SUB_M",
            "url"       : edges["chbx-0"]+"ajax/"+user+"/CHBX_SUB_M@profil/"+t,
            "wcrdtl"    : true
        },
        "CHBX_PL_MS" : {
            //COMMENT : Récupérer les messages d'une Conversation à sa sélection dans la liste (Recherche ou FirstConversation).
            "urqid"     :"CHBX_PL_MS",
            "url"       : edges["chbx-lp"]+"ajax/"+user+"/CHBX_PL_MS@profil/"+t,
            "wcrdtl"    : true
        },
        "CHBX_DL_MS" : {
            //COMMENT : Supprimer le ou les messages sélectioné(s) d'une Conversation 
            "urqid"     :"CHBX_DL_MS",
            "url"       : edges["chbx-0"]+"ajax/"+user+"/CHBX_DL_MS@profil/"+t,
            "wcrdtl"    : true
        },
        "CHBX_DL_CS" : {
            //COMMENT : Supprimer le ou les Conversations sélectionée(s)
            "urqid"     :"CHBX_DL_CS",
            "url"       : edges["chbx-0"]+"ajax/"+user+"/CHBX_DL_CS@profil/"+t,
            "wcrdtl"    : true
        },
        "CHBX_PL_CNV" : {
            //COMMENT : Récupérer les Conversations de CU selon les informations complémentaires fournies (FirstConversation, Newer, Older, etc ...).
            "urqid"     :"CHBX_PL_CNV",
            "url"       : edges["chbx-lp"]+"ajax/"+user+"/CHBX_PL_CNV@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_BRAIN_BKFLG" : {
            //COMMENT : Arreter de suivre un utilisateur en lancant l'action depuis BRAIN
            "urqid"     :"TMLNR_BRAIN_BKFLG",
            "url"       : edges["rel"]+"ajax/"+user+"/TMLNR_BN_BKFLG@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_BRAIN_RHFLG" : {
            //COMMENT : Suivre (de nouveau) un utilisateur en lancant l'action depuis BRAIN
            "urqid"     :"TMLNR_BRAIN_RHFLG",
            "url"       : edges["rel"]+"ajax/"+user+"/TMLNR_BN_RHFLG@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_BRAIN_PLCATG" : {
            //COMMENT : Récupérer la list des catégories de Tendances disponibles
            "urqid"     :"TMLNR_BRAIN_PLCATG",
            "url"       : host+"ajax/"+user+"/TMLNR_BN_PLCATG@profil/"+t,
            "wcrdtl"    : true
        },
        "ULTMT_FOLL" : {
            //COMMENT : Suivre un utilisateur en lancant l'action depuis Header
            "urqid"     :"ULTMT_FOLL",
            "url"       : edges["rel"]+"ajax/TMLNR_GOFLWREL@profil/"+t,
            "wcrdtl"    : true
        },
        "ULTMT_UFOLL" : {
            //COMMENT : Arreter de suivre un utilisateur en lancant l'action depuis Header
            "urqid"     :"ULTMT_FOLL",
            "url"       : edges["rel"]+"ajax/TMLNR_GOFLWREL@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_ADD_IMLART" : {
            //Comment : Ajout d'un Article IML à partir de TMLNR.
            "urqid"     :"TMLNR_ADD_IMLART",
            "url"       : host+"ajax/"+user+"/TMLNR_ADD_IMLART@profil/"+t,
            "wcrdtl"    : false
        },
        "TMLNR_ADD_TRART" : {
            //COMMENT : Ajouter un Article Tr dans une Tendance
            "urqid"     :"TMLNR_ADD_TRART",
            "url"       : host+"ajax/"+user+"/TMLNR_ADD_ITRART@profil/"+t,
            "wcrdtl"    : false
        },
        "TMLNR_CHECK_GET_ART_INTR" : {
            //COMMENT : Vérifier et récupérer les Articles InTR ulterieurs
            "urqid"     :"TMLNR_CHECK_GET_ART_INTR",
            "url"       : edges["ars"]+"ajax/TMLNR_PITRS_NWR@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_CHECK_GET_ART_IML" : {
            //COMMENT : Vérifier et récupérer (Pull) les Articles IML ulterieurs
            "urqid"     :"TMLNR_CHECK_GET_ART_IML",
            "url"       : edges["ars"]+"ajax/TMLNR_PIMLS_NWR@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_BRAIN_GETMYTRS" : {
            //COMMENT : Répcupérer la liste des Tendances appartenant à CU 
            "urqid"     :"TMLNR_BRAIN_GETMYTRS",
            "url"       : host+"ajax/"+user+"/TMLNR_BN_GETMYTRS@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_BRAIN_GETFOLGTRS" : {
            //COMMENT : Répcupérer la liste des Tendances appartenant à CU 
            "urqid"     :"TMLNR_BRAIN_GETFOLGTRS",
            "url"       : host+"ajax/"+user+"/TMLNR_BN_GETFLGTRS@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_SIG_DSMATRCCT" : {
            //Comment : On signale au serveur que l'utilisateur demande de ne plus lui montrer le message sur le concept des TREND dans le menu "New Post in TREND"
            "urqid"     :"TMLNR_SIG_DSMATRCCT",
            "url"       : host+"ajax/"+user+"/TMLNR_BN_DSMATRCCT@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_BRAIN_GETMYFOLW" : {
            //Comment : Récupérer la lise des Utilisateurs qui suivent l'utilisateur actif"
            "urqid"     :"TMLNR_BRAIN_GETMYFOLW",
            "url"       : edges["rel"]+"ajax/"+user+"/TMLNR_BN_MYFOLW@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_BRAIN_GETMYFOLG" : {
            //Comment : Récupérer la lise des Utilisateurs que suis l'utilisateur actif"
            "urqid"     :"TMLNR_BRAIN_GETMYFOLG",
            "url"       : edges["rel"]+"ajax/"+user+"/TMLNR_BN_MYFOLG@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_BRAIN_NEWTREND" : {
            //Comment : Créer une nouvelle Tendance depuis BRAIN"
            "urqid"     :"TMLNR_BRAIN_NEWTREND",
            "url"       : host+"ajax/"+user+"/TMLNR_BN_NWTRD@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_DEL_MyTR" : {
            //Comment : Supprimer la Tendance spécifiée depuis BRAIN"
            "urqid"     :"TMLNR_DEL_MyTR",
            "url"       : host+"ajax/"+user+"/TMLNR_BN_DELTRD@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_SET_COVER" : {
            //COMMENT : Sauvegarder la nouvelle image de bannière
            "urqid"     :"TMLNR_SET_COVER",
            "url"       : host+"ajax/"+user+"/TMLNR_SETCOV@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_RST_COVER" : {
            //COMMENT : Sauvegarder la nouvelle image de bannière
            "urqid"     :"TMLNR_RST_COVER",
            "url"       : host+"ajax/"+user+"/TMLNR_RSTCVR@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_SET_PFLPIC" : {
            //COMMENT : Sauvegarder la nouvelle image de profil 
            "urqid"     :"TMLNR_SET_PFLPIC",
            "url"       : host+"ajax/"+user+"/TMLNR_SETPPIC@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_SET_DFTPIC" : {
            //COMMENT : Définit l'image par défaut comme image de profil 
            "urqid"     :"TMLNR_SET_DFTPIC",
            "url"       : host+"ajax/"+user+"/TMLNR_SET_DPPIC@profil/"+t,
            "wcrdtl"    : true
        },
        "GET_ARP_VIA_POSTID" : {
            //COMMENT : Récupérer la version ARP d'un Article à partir de l'id fourni
            "urqid"     :"GET_ARP_VIA_POSTID",
            "url"       : host+"ajax/TMLNR_GTARP@profil/"+t,
            "wcrdtl"    : true
        },
        "ARP_PULL_REACTS" : {
            //COMMENT : Récupérer les commentaires pour un Article en ARP
            "urqid"     :"ARP_PULL_REACTS",
            "url"       : edges["rct"]+"ajax/TMLNR_ARP_PLRCTS@profil/"+t,
            "wcrdtl"    : true
        },
        "ARP_DEL_ART" : {
            //COMMENT : Supprimer un Article lorsque la page de référence est TMLNR. La page de référence change le comportement que doit adopter le serveur
            "urqid"     :"ARP_DEL_ART",
            "url"       : edges["ars"]+"ajax/TMLNR_DELART@profil/"+t,
            "wcrdtl"    : true
        },
        "ADD_R_IN_ARP" : {
            //COMMENT : Ajouter un commentaire pour un Article en ARP
            "urqid"     :"ADD_R_IN_ARP",
            "url"       : edges["rct"]+"ajax/TMLNR_ARP_ADDRCT@profil/"+t,
            "wcrdtl"    : true
        },
        "DEL_R_IN_ARP" : {
            //COMMENT : Supprimer un commentaire pour un Article en ARP
            "urqid"     :"DEL_R_IN_ARP",
            "url"       : edges["rct"]+"ajax/TMLNR_ARP_DELRCT@profil/"+t,
            "wcrdtl"    : true
        },
        "TMLNR_CHECK_GET_PDART" : {
            //COMMENT : Récupérer les données sur les Articles antérieurs
            "urqid"     :"TMLNR_CHECK_GET_PDART",
            "url"       : edges["ars"]+"ajax/TMLNR_GTPDARTS@profil/"+t,
            "wcrdtl"    : true
        },
        /*
        "SIG_COMPLIANT_TIME" : {
            "urqid"     :"SIG_compliant_time",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php",
            "datas" : "ERR_TIME_COMPLIANT"
        },
        "SIG_CRACKED_TIME" : {
            "urqid"     :"SIG_compliant_time",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php",
            "datas" : "ERR_TIME_CRACKED"
        },
        
        "GET_LOCAL_TMZ" : {
            "urqid"     :"GET_LOCAL_TZ",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Il faut joindre l'id du compte actif OU laisser PHP s'en chargé
        },
        "DID_USER_CH_LOC" : {
            "urqid"     :"DID_USER_CH_LOC",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet ensuite de travailler sur TIMEZONE_CONFLICT ou autres
        },
        
        "UPD_NWFD_PARAMS" : {
            "urqid"     :"UPD_NWFD_PARAMS",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet d'améliorer considérablement l'expérience utilisateur lorsqu'il décide de reload la page
        },
        "GET_NWFD_LASTPARAMS" : {
            "urqid"     :"GET_NWFD_LASTPARAMS",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de connaitre la dernière configuration de NewsFeed
        },
        //*/
        "NWFD_GET_ARTS" : {
            //Comment : Permet de vérifier s'il y a de nouveaux Articles 
            "urqid"     :"NWFD_GET_ARTS",
            "url"       : edges["nwfd"]+"ajax/NWFD_GARTS@profil/"+t,
            "wcrdtl"    : true
        },
        "NWFD_GET_ARTS_FROM" : {
            //Comment : Permet de récupérer les anciens Articles s'il existent 
            "urqid"     :"NWFD_GET_ARTS_FROM",
            "url"       : edges["nwfd"]+"ajax/NWFD_GARTS_FM@profil/"+t,
            "wcrdtl"    : true
        },
        /*
        "NWFD_GET_LAST_TEAM_LIST" : {
            "urqid"     :"NWFD_GET_LAST_TEAM_LIST",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de vérifier s'il y a de nouveaux Articles (Team) en mode (List) pour les afficher dans NewsFeed
        },
        "NWFD_GET_LAST_TEAM_MOZ" : {
            "urqid"     :"NWFD_GET_LAST_TEAM_MOZ",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de vérifier s'il y a de nouveaux Articles (Team) en mode (Moz) pour les afficher dans NewsFeed
        },
        "NWFD_GET_LAST_COMY_LIST" : {
            "urqid"     :"NWFD_GET_LAST_COMY_LIST",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de vérifier s'il y a de nouveaux Articles (Community) en mode (List) pour les afficher dans NewsFeed
        },
        "NWFD_GET_LAST_COMY_MOZ" : {
            "urqid"     :"NWFD_GET_LAST_COMY_MOZ",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de vérifier s'il y a de nouveaux Articles (Community) en mode (Moz) pour les afficher dans NewsFeed
        },
        "NWFD_GET_LAST_BZFD_LIST" : {
            "urqid"     :"NWFD_GET_LAST_BZFD_LIST",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de vérifier s'il y a de nouveaux Articles (BuzzFeed) en mode (List) pour les afficher dans NewsFeed
        },
        "NWFD_GET_LAST_BZFD_MOZ" : {
            "urqid"     :"NWFD_GET_LAST_BZFD_MOZ",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de vérifier s'il y a de nouveaux Articles (BuzzFeed) en mode (Moz) pour les afficher dans NewsFeed
        },
        "NWFD_GET_PREDATE_TEAM_LIST" : {
            "urqid"     :"NWFD_GET_PREDATE_TEAM_LIST",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de charger les articles précédents (Team) en mode (List) pour les afficher dans NewsFeed
        },
        "NWFD_GET_PREDATE_TEAM_MOZ" : {
            "urqid"     :"NWFD_GET_PREDATE_TEAM_MOZ",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de charger les articles précédents (Team) en mode (Moz) pour les afficher dans NewsFeed
        },
        "NWFD_GET_PREDATE_COMY_LIST" : {
            "urqid"     :"NWFD_GET_PREDATE_COMY_LIST",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de charger les articles précédents (Community) en mode (List) pour les afficher dans NewsFeed
        },
        "NWFD_GET_PREDATE_COMY_MOZ" : {
            "urqid"     :"NWFD_GET_PREDATE_COMY_MOZ",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de charger les articles précédents (Community) en mode (Moz) pour les afficher dans NewsFeed
        },
        "NWFD_GET_PREDATE_BZFD_LIST" : {
            "urqid"     :"NWFD_GET_PREDATE_BZFD_LIST",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de charger les articles précédents (BuzzFeed) en mode (List) pour les afficher dans NewsFeed
        },
        "NWFD_GET_PREDATE_BZFD_MOZ" : {
            "urqid"     :"NWFD_GET_PREDATE_BZFD_MOZ",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Permet de charger les articles précédents (BuzzFeed) en mode (Moz) pour les afficher dans NewsFeed
        },
        
        /* TREND PAGE ARTICLES SCOPE (START) /
        "TRPG_CHECK_NWPOSTS" : {
            "urqid"     :"TRPG_CHECK_NWPOSTS",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : Vérifier et récupérer des nouveaux Articles d'une page Tendance
        },
        //*/
        "CR_NW_TRDART" : {
            //Comment   : Envoie les données concernant le nouvel Article a créé
            "urqid"     : "CR_NW_TRDART",
            "url"       : host+"ajax/TRPG_NW_ART@trend/"+t,
            "wcrdtl"    : false
        },
        "SAVE_USER_BIO" : {
            //Comment   : Sauvegarde la bio de l'utilisateur
            "urqid"     : "SAVE_USER_BIO",
            "url"       : host+"ajax/"+user+"/TMLNR_SAVE_PFLBIO@profil/"+t,
            "wcrdtl"    : true
        },
        "TRPG_CHECK_PDPOSTS" : {
            //Comment   : On vérifie et récupère les Articles antérieurs. on envoie la date du dernier article affiché
            "urqid"     : "TRPG_CHECK_PDPOSTS",
            "url"       : edges["ars"]+"ajax/TRPG_GARTS_FM@trend/"+t,
            "wcrdtl"    : true
        },
        "TRPG_GET_STGS" : {
            //Comment   : On vérifie et récupère les Articles antérieurs. on envoit la date du dernier article affiché
            "urqid"     : "TRPG_GET_STGS",
            "url"       : host+"ajax/TRPG_GT_STGS@trend/"+t,
            "wcrdtl"    : true
        },
        "TRPG_SV_NW_STS" : {
            //Comment   : Permet de sauvegarder les données de base d'une Tendance modifiées par l'utilisateur.
            "urqid"     : "TRPG_SV_NW_STS",
            "url"       : host+"ajax/TRPG_ST_STGS@trend/"+t,
            "wcrdtl"    : true
        },
        "TRPG_SET_TCOV" : {
            //Comment   : Changer l'image de couverture d'une Tendance
            "urqid"     : "TRPG_SET_TCOV",
            "url"       : host+"ajax/TRPG_SET_TCOV@trend/"+t,
            "wcrdtl"    : true
        },
        "TRPG_DEL_TCOV" : {
            //Comment   : Supprimer l'image de couverture d'une Tendance pour revenir à celle par défaut
            "urqid"     : "TRPG_DEL_TCOV",
            "url"       : host+"ajax/TRPG_RSTCVR@trend/"+t,
            "wcrdtl"    : true
        },
        /* TREND PAGE ARTICLES SCOPE (END) */
        
        "EVAL_RHCOOL" : {
            //Comment   : On évalue l'Article avec un eval+1
            "urqid"     : "EVAL_RHCOOL",
            "url"       : edges["eval"]+"ajax/EVAL_ACT@profil/"+t,
            "wcrdtl"    : true
        },
        "EVAL_RHDSLK" : {
            //Comment   : On évalue l'Article avec un eval-1
            "urqid"     : "EVAL_RHDSLK",
            "url"       : edges["eval"]+"ajax/EVAL_ACT@profil/"+t,
            "wcrdtl"    : true
        },
        "EVAL_RHSPCL" : {
            //Comment   : On évalue l'Article avec un eval+2
            "urqid"     : "EVAL_RHSPCL",
            "url"       : edges["eval"]+"ajax/EVAL_ACT@profil/"+t,
            "wcrdtl"    : true
        },
        "EVAL_BKCOOL" : {
            //Comment : On évalue l'Article en retirant l'eval+1
            "urqid"     :"EVAL_BKCOOL",
            "url"       : edges["eval"]+"ajax/EVAL_ACT@profil/"+t,
            "wcrdtl"    : true
        },
        "EVAL_BKDSLK" : {
            //Comment   : On évalue l'Article en retirant l'eval-1
            "urqid"     : "EVAL_BKDSLK",
            "url"       : edges["eval"]+"ajax/EVAL_ACT@profil/"+t,
            "wcrdtl"    : true
        },
        "EVAL_BKSPCL" : {
            //Comment   : On évalue l'Article en retirant l'eval+2
            "urqid"     : "EVAL_BKSPCL",
            "url"       : edges["eval"]+"ajax/EVAL_ACT@profil/"+t,
            "wcrdtl"    : true
        },
        "UNQ_DEL_REACT" : {
            //Comment   : On supprime l'article présent dans Unique
            "urqid"     : "UNQ_DEL_REACT",
            "url"       : edges["rct"]+"ajax/UNQ_DELRCT@profil/"+t,
            "wcrdtl"    : true
        },
        "UNQ_ADD_REACT" : {
            //Comment   : Ajout un nouveau commentaire
            "urqid"     : "UNQ_ADD_REACT",
            "url"       : edges["rct"]+"ajax/UNQ_ADDRCT@profil/"+t,
            "wcrdtl"    : true
        },
        "UNQ_GET_ALL_REACT_LIMIT" : {
            //Comment   : Récupérer les commentaires liés à un Article qui sera ajouté dans le modèle UNQ
            "urqid"     : "UNQ_GET_ALL_REACT_LIMIT",
            "url"       : edges["rct"]+"ajax/UNQ_GETALL_RCTS@profil/"+t,
            "wcrdtl"    : true
        },
        "UNQ_PULL_VIPs_PLUS" : {
            //Comment   : Récupérer les données sur les VIPs (_PLUS le tableau d'EVAL) d'un Article à afficher dans UNQ. Il s'agit d'une MAJ.
            "urqid"     : "UNQ_PULL_VIPs_PLUS",
            "url"       : edges["eval"]+"ajax/UNQ_PLVIPS_PLUS@profil/"+t,
            "wcrdtl"    : true
        },
        "UNQ_PULL_EVALs" : {
            //Comment   : Récupérer les données sur les EVALs d'un Article à afficher dans UNQ. Il s'agit d'une MAJ.
            "urqid"     : "UNQ_PULL_EVALs",
            "url"       : host+"ajax/UNQ_PLEVALS@profil/"+t,
            "wcrdtl"    : false
        },
        "UNQ_DEL_TMLNR_ART" : {
            //Comment   : Supprimer l'Article actif depuis UNQ
            "urqid"     : "UNQ_DEL_TMLNR_ART",
            "url"       : host+"ajax/TMLNR_DELART@profil/"+t,
            "wcrdtl"    : true
        },
        "FRDS_ACCEPT" : {
            //Comment   : Accepter la demande d'amis proposée par un protagoniste B
            "urqid"     : "FRDS_ACCEPT",
            "url"       : edges["rel"]+"ajax/FRDCTR_ACPT@profil/"+t,
            "wcrdtl"    : true
        },
        "FRDS_DECLINE" : {
            //Comment   : Décliner la demande d'amis proposée par un protagoniste B
            "urqid"     : "FRDS_DECLINE",
            "url"       : edges["rel"]+"ajax/FRDCTR_DCLN@profil/"+t,
            "wcrdtl"    : true
        },
        "FRDS_GET_NEW_RQTS" : {
            //Comment   : On récupèrre au niveau de serveur les requetes auxquelles l'utilisateur actif n'a toujours pas répondues
            "urqid"     : "FRDS_GET_NEW_RQTS",
            "url"       : edges["rel"]+"ajax/FRDCTR_GNRQTS@profil/"+t,
            "wcrdtl"    : true
        },
        "FRDS_GET_ALL_FRDS" : {
            //Comment   : On récupèrre au niveau de serveur la liste complete des amis de l'utilisateur
            "urqid"     : "FRDS_GET_ALL_FRDS",
            "url"       : edges["rel"]+"ajax/FRDCTR_GMFRD@profil/"+t,
            "wcrdtl"    : true
        },
        "FRDS_TRY_FRIEND" : {
            //Comment   : On tente de créer une relation de type "amis" entre CU et OW
            "urqid"     : "FRDS_TRY_FRIEND",
            "url"       : edges["rel"]+"ajax/FRD_GOFRD@profil/"+t,
            "wcrdtl"    : true
        },
        "FRDS_TRY_UNFRIEND" : {
            //Comment   : On tente de mettre fin à une relation de type "amis". L'opération peut être refusée. Par exemple si les deux utilisateurs ne sont pas amis.
            "urqid"     : "FRDS_TRY_UNFRIEND",
            "url"       : edges["rel"]+"ajax/FRDCTR_UFRD@profil/"+t,
            "wcrdtl"    : true
        },
        /*
        "FRDS_CHECK_DBFRDRQT" : {
            "urqid"     :"FRDS_CHECK_DBFRDRQT",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : On demande au serveur est ce que OW n'a pas déjà fait une demande auprès de CU. Cette URQ peut être répétitif face à FRDS_TRY_FRIEND qui répondra par une erreur FEC_DENY_DBASK.
        },
        //*/
        /*
        "TRPG_TRY_GET_CONNECTED" : {
            "urqid"     :"TRPG_TRY_GET_CONNECTED",
            "url"       : "http://127.0.0.1/korgb/ajax_test.php"
            //Comment : On tente de créer une connexion entre m'utilisateur et la Tendance. Le serveur peut refuser, il envera un code errer TRPG_CNTD_ALRY_CNTD. Si à la date de la demande, la Tendance n'existe plus le serveur renvoie TRPG_GONE.
        },
        //*/
        "TRPG_TRY_ABO_OPER" : {
            //Comment   : Se déconnecter d'une Tendance depuis TRPG. C'est la même cible que pour TMLNR. La spécification se fait dans le POST
            "urqid"     : "TRPG_TRY_ABO_OPER",
            "url"       : host+"ajax/TRPG_GN_ABO@trend/"+t,
            "wcrdtl"    : true
        },
        "INS_PULLDATAS" : {
            //Comment   : Rechercher des données de la part de FE necessaires à pour l'activité du formulaire d'inscription 
            "urqid"     : "INS_PULLDATAS",
            "url"       : host+"ajax/INS_SRH@inscription/"+t,
            "wcrdtl"    : false
        },
        "INS_FNL" : {
            //Comment   : Gère les opérations relatives à la finalisation de l'inscription
            "urqid"     : "INS_FNL",
            "url"       : host+"ajax/INS_FNL_OPE@inscription/"+t,
            "wcrdtl"    : false
        },
        "CNX_TRYCNX" : {
            //Comment   : Lance une tentative de connexion
            "urqid"     : "CNX_TRYCNX",
            "url"       : host+"ajax/CNX_TRYCNX@connexion/"+t,
            "wcrdtl"    : true
        },
        "CNX_TDLACT" : {
            //Comment   : Lance une tentative de connexion      
            "urqid"     : "CNX_TDLACT",
            "url"       : host+"ajax/CNX_TDLACT@connexion/"+t,
            "wcrdtl"    : true
        },
        "TQR_REC_L" : {
            //Comment   : Lancer une tentative de demande de changement de mot de passe  
            "urqid"     : "TQR_REC_L",
            "url"       : host+"ajax/REC_TRYREC_L@recovery/"+t,
            "wcrdtl"    : false
        },
        "TQR_REC_F" : {
            //Comment   : Lancer une validation de réinitialisation de mot de passe  
            "urqid"     : "TQR_REC_F",
            "url"       : host+"ajax/REC_TRYREC_F@recovery/"+t,
            "wcrdtl"    : true
        },
        "STGS_SUBT_PFL" : {
            //Comment   : Lancer la sauvegarde d'une mise à jour des données de Profil
            "urqid"     : "STGS_SUBT_PFL",
            "url"       : host+"ajax/"+user+"/STGS_SUBT_PFL@settings/"+t,
            "wcrdtl"    : true
        },
        "STGS_SUBT_ACC" : {
            //Comment: Lancer la sauvegarde d'une mise à jour des données de Compte
            "urqid"     : "STGS_SUBT_ACC",
            "url"       : host+"ajax/"+user+"/STGS_SUBT_ACC@settings/"+t,
            "wcrdtl"    : true
        },
        "STGS_SUBT_PWD" : {
            //Comment   : Lancer la sauvegarde d'une mise à jour du mot de passe
            "urqid"     : "STGS_SUBT_PWD",
            "url"       : host+"ajax/"+user+"/STGS_SUBT_PWD@settings/"+t,
            "wcrdtl"    : true
        },
        "STGS_SUBT_SEC_CO" : {
            //Comment   : Lancer la sauvegarde d'une mise à jour des données de sécurité en ce qui concerne la connexion au Compte
            "urqid"     : "STGS_SUBT_SECCO",
            "url"       : host+"ajax/"+user+"/STGS_SUBT_SECCO@settings/"+t,
            "wcrdtl"    : true
        },
        "STGS_SUBT_DELACC" : {
            //Comment   : Lancer la sauvegarde d'une demande de suppression de Compte
            "urqid"     : "STGS_SUBT_DELACC",
            "url"       : host+"ajax/"+user+"/STGS_SUBT_DELACC@settings/"+t,
            "wcrdtl"    : true
        },
        "BGZY_SUB" : {
            //Comment   : Lancer la sauvegarde d'un signalement d'erreur
            "urqid"     : "BGZY_SUB",
            "url"       : host+"ajax/"+user+"/BGZY_SUB@profil/"+t,
            "wcrdtl"    : true
        },
        "TQREX_STPRFRCS" : {
            //Comment   : Lancer la sauvegarde d'une préférence
            "urqid"     : "TQREX_STPRFRCS",
            "url"       : host+"ajax/TQR_SVEXPREF@profil/"+t,
            "wcrdtl"    : true
        },
        "FVLK_ADD_FAV" : {
            //Comment   : Ajouter un nouveau lien favori
            "urqid"     : "FVLK_ADD_FAV",
            "url"       : host+"ajax/TAPP_FVLK_ADD_FAV@profil/"+t,
            "wcrdtl"    : false
        },
        "FVLK_VST_FAV" : {
            //Comment   : Ajouter un nouveau lien favori
            "urqid"     : "FVLK_VST_FAV",
            "url"       : host+"ajax/TAPP_FVLK_VST_FAV@profil/"+t,
            "wcrdtl"    : false
        },
        "FVLK_DEL_FAV" : {
            //Comment   : Ajouter un nouveau lien favori
            "urqid"     : "FVLK_DEL_FAV",
            "url"       : host+"ajax/TAPP_FVLK_DEL_FAV@profil/"+t,
            "wcrdtl"    : false
        },
        "FVLK_GET_FST_FAV" : {
            //Comment   : Récupérer des liens favoris en mode FIRST
            "urqid"     : "FVLK_GET_FST_FAV",
            "url"       : host+"ajax/TAPP_FVLK_FST_FAV@profil/"+t,
            "wcrdtl"    : false
        },
        "FVLK_GET_FRM_FAV" : {
            //Comment   : Récupérer des liens favoris en prennant un pivot et en se fiant à une direction
            "urqid"     : "FVLK_GET_FRM_FAV",
            "url"       : host+"ajax/TAPP_FVLK_FRM_FAV@profil/"+t,
            "wcrdtl"    : false
        },
        "RCMD_SUBMIT" : {
            //Comment   : Soumettre la demande d'invitation au serveur afin qu'elle soit validée et compléteé par l'envoi d'un email
            "urqid"     : "RCMD_SUBMIT",
            "url"       : host+"ajax/TQR_RCMDTO_SUBMIT@recoma/"+t,
            "wcrdtl"    : false
        },
        "TQR_EC_REDO" : {
            //Comment   : Relancer une opération d'envoi d'email pour la validation de compte
            "urqid"     : "TQR_EC_REDO",
            "url"       : host+"ajax/TQR_EC_REDO@profil/"+t,
            "wcrdtl"    : false
        },
        /************************ SUGGESTION ************************/
        "TQR_SUGG_GETALL" : {
            //Comment   : Relancer une opération d'envoi d'email pour la validation de compte
            "urqid"     : "TQR_SUGG_GETALL",
            "url"       : host+"ajax/TQR_SUGG_GA@profil/"+t,
            "wcrdtl"    : false
        },
        /************************ TEMOIGNAGE ************************/
        "TQR_TSTY_ADD" : {
            //Comment   : Ajouter un nouveau témoignage
            "urqid"     : "TQR_TSTY_ADD",
            "url"       : host+"ajax/TQR_TSTY_ADD@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TSTY_DEL" : {
            //Comment   : Supprimer un témoignage
            "urqid"     : "TQR_TSTY_DEL",
            "url"       : host+"ajax/TQR_TSTY_DL@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TSTY_PIN" : {
            //Comment   : Epingler/Désépingler un témoignage
            "urqid"     : "TQR_TSTY_PIN",
            "url"       : host+"ajax/TQR_TSTY_PIN@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TSTY_GET" : {
            //Comment   : Récupérer les témoignages en fonction des paramètres passés par l'utilisateur
            "urqid"     : "TQR_TSTY_GET",
            "url"       : host+"ajax/TQR_TSTY_GT@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TSTY_GET_CONF" : {
            //Comment   : Récupérer les données sur une configuration
            "urqid"     : "TQR_TSTY_GET_CONF",
            "url"       : host+"ajax/"+user+"/TQR_TSTY_GET_CF@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TSTY_SET_CONF" : {
            //Comment   : Appliquer une configuration
            "urqid"     : "TQR_TSTY_SET_CONF",
            "url"       : host+"ajax/"+user+"/TQR_TSTY_SET_CF@profil/"+t,
            "wcrdtl"    : false
        },
        /************************ LASTA ACTIVITY ************************/
        "TQR_LASTA_GACTV" : {
            //Comment   : Récupérer la liste des Activités
            "urqid"     : "TQR_LASTA_GACTV",
            "url"       : host+"ajax/TQR_LASTA_GA@profil/"+t,
            "wcrdtl"    : false
        },
        "CHBX_NTY_GN" : {
            //Comment   : Récupérer les données sur les messages non lus
            "urqid"     : "CHBX_NTY_GN",
            "url"       : edges["chbx-lp"]+"ajax/"+user+"/CHBX_NTY_GN@profil/"+t,
            "wcrdtl"    : false
        },
        "CHBX_NTY_SN" : {
            //Comment   : Envoyer la liste des messages considérés comme lus
            "urqid"     : "CHBX_NTY_SN",
            "url"       : edges["chbx-lp"]+"ajax/"+user+"/CHBX_NTY_SN@profil/"+t,
            "wcrdtl"    : false
        },
        /************************ HVIEW SCOPE ************************/
        "TQR_HVIEW_GDS" : {
            //Comment   : Récupérer les données relatives à une recherche via le système HVIEW
            "urqid"     : "TQR_HVIEW_GDS",
            "url"       : host+"ajax/TQR_HVIEW_GDS@thehashview/"+t,
            "wcrdtl"    : false
        },
        /************************************************************/
        /************************* TQR.VB3.0 ************************/
        "FKSA_ADD_RCT" : {
            //Comment   : Ajouter un COMMENT depuis n'importe quel MODULE
            "urqid"     : "FKSA_ADD_RCT",
            "url"       : host+"ajax/TQR_GENRC_ADD_ARC@profil/"+t,
            "wcrdtl"    : true
        },
        "FKSA_DEL_RCT" : {
            //Comment   : Supprimer un COMMENT depuis n'importe quel MODULE
            "urqid"     : "FKSA_DEL_RCT",
            "url"       : host+"ajax/TQR_GENRC_DEL_ARC@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_TSTY_XTAC" : {
            //Comment   : Récupérer les données relatives à une recherche via le système HVIEW
            "urqid"     : "TQR_TSTY_XTAC",
            "url"       : host+"ajax/TQR_TSTY_XA@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TBUV_PL_TSR" : {
            //Comment   : Récupérer des commentaires d'un TESTY.
            "urqid"     : "TQR_TBUV_PL_TSR",
            "url"       : host+"ajax/TQR_TBUV_TSR_PL@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TBUV_ADD_TSR" : {
            //Comment   : Ajouter un commentaire pour un TESTY.
            "urqid"     : "TQR_TBUV_ADD_TSR",
            "url"       : host+"ajax/TQR_TBUV_TSR_AD@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TBUV_DEL_TSR" : {
            //Comment   : Supprimer un commentaire pour un TESTY.
            "urqid"     : "TQR_TBUV_DEL_TSR",
            "url"       : host+"ajax/TQR_TBUV_TSR_DL@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_TBUV_PL_TSL" : {
            //Comment   : Récupérer des les données au sujet des LIKES d'un TESTY.
            "urqid"     : "TQR_TBUV_PL_TSL",
            "url"       : host+"ajax/TQR_TBUV_TSL_PL@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_ART_FAV" : {
            //Comment   : Lancer une ACTION de type FAV/UNFAV
            "urqid"     : "TQR_ART_FAV",
            "url"       : host+"ajax/TQR_ARFAV_FAV@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_ART_LDMR" : {
            //Comment   : Récupérer plus d'ARTICLES FAVORITE
            "urqid"     : "TQR_ART_LDMR",
            "url"       : host+"ajax/TQR_ARFAV_LDMR@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_LTC_SSN_NEW" : {
            //Comment   : Signaler que l'utilsateur s'est connecté au chat.
            "urqid"     : "TQR_LTC_SSN_NEW",
            "url"       : host+"ajax/TQR_LTC_SSN_NEW@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_LTC_MSG_SEND" : {
            //Comment   : Envoyer un MESSAGE au niveau du SERVER
            "urqid"     : "TQR_LTC_MSG_SEND",
            "url"       : host+"ajax/TQR_LTC_MSG_SND@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_TIA_EXPLR_PL" : {
            //Comment   : Récupérer les ARTICLES au niveau du server
            "urqid"     : "TQR_TIA_EXPLR_PL",
            "url"       : host+"ajax/TQR_TIA_EXPLR_PL@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_TIA_PCQR_PL" : {
            //Comment   : Récupérer les Articles au niveau du server
            "urqid"     : "TQR_TIA_PCQR_PL",
            "url"       : host+"ajax/TQR_TIA_PCQR_PL@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_TIA_MYSRY_ADD" : {
            //Comment   : Ajouter un MESSAGE de type BlindM
            "urqid"     : "TQR_TIA_MYSRY_ADD",
            "url"       : host+"ajax/TQR_TIA_MYSRY_ADD@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_TIA_MYSRY_DLMM" : {
            //Comment   : Supprimer un MESSAGE de type BlindM
            "urqid"     : "TQR_TIA_MYSRY_ADD",
            "url"       : host+"ajax/TQR_TIA_MYSRY_DEL@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_TIA_MYSRY_PLMM" : {
            //Comment   : Récupérer les MESSAGES de type BlindM au niveau du server
            "urqid"     : "TQR_TIA_MYSRY_PLMM",
            "url"       : host+"ajax/TQR_TIA_MYSRY_PLMM@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_TIA_MYSRY_EVLMM" : {
            //Comment   : Évaluer un MESSAGE
            "urqid"     : "TQR_TIA_MYSRY_EVLMM",
            "url"       : host+"ajax/TQR_TIA_MYSRY_VOTE@profil/"+t,
            "wcrdtl"    : true
        },
        "TQR_SUGG_GT_DCORA" : {
            //Comment   : Récupérer une ou plisuers images avec un algorithme moins sophistiquer que celui de base 
            "urqid"     : "TQR_SUGG_GETALL",
            "url"       : host+"ajax/TQR_SUGG_GA@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_RLC_PLAC" : {
            //Comment   : Récupérer les données sur les RELATIONS au choix (FOLLOWER, FOLLOWING, DFOLLOW) 
            "urqid"     : "TQR_RLC_PLAC",
            "url"       : host+"ajax/TQR_RLC_PLAC@profil/"+t,
            "wcrdtl"    : false
        },
        "TQR_EVT_PGTKFKS" : {
            //Comment   : Signaler au SERVEUR que USER est sur une PAGE. Un moyen de dire qu'il est ACTIF 
            "urqid"     : "TQR_EVT_PGTKFKS",
            "url"       : host+"ajax/TQR_EVT_PGTKFKS@profil/"+t,
            "wcrdtl"    : false
        },
        /************************************************************/
        /************************* TQR.V1.0 **************************/
        "TMLNR_ABME_GDS" : {
            //Comment   : Récupérer les données des sous-sections ABM
            "urqid"     : "TMLNR_ABME_GDS",
            "url"       : host+"ajax/TMLNR_ABME_GDS@profil/"+t,
            "wcrdtl"    : false
        },
        "TMLNR_ABME_SDS" : {
            //Comment   : Ajouter ou Modifier les données des sous-sections ABME
            "urqid"     : "TMLNR_ABME_SDS",
            "url"       : host+"ajax/TMLNR_ABME_SDS@profil/"+t,
            "wcrdtl"    : false
        },
    };
    
    if (! AR_TAB.hasOwnProperty(k) ) {
        return false;
    } else {
        return AR_TAB[k];
    }
    
}
