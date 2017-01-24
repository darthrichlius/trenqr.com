<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enty
 *
 * @author arsphinx
 */
class NEWSFEED extends MOTHER {
    
    private $_NWFD_DFT_MENU;
    private $_NWFD_DFT_MODE;
    /*
     * CODE ABA (ArticleBAtch) : Necessaire pour retrouver l'origine d'un Article et permettre une meilleure mise à jour de la liste coté client
     *  _xl_3im     : Articles IML des Amis
     *  _xl_3it     : Articles ITR des Amis
     *  [DEPUIS 13-05-15] @BOR
     *  Les D_FOLW peuvent accéder au
     *  _xl_2im    : Articles IML D_FOLW
     *  _xl_12it    : Articles ITR des S_FOLW, D_FOLW
     *  [DEPUIS 12-05-15] @BOR
     *  On doit éliminer tous les Articles dont les auteurs ont une relation S_FOLW, D_FOLW et FRD avec CU.
     *  Le but état d'éviter que des données soient envoyées quand elles sont déjà affichées au niveau de FE.
     *  Cette modification sera opérée surtout pour NEWER. Je ne vois pas l'interet de le faire pour FIRST ou OLDER.
     *  En effet, la duplicité est presque impossible, de plus, s'il y avait un problème on ne le verrait pas.
     *  _xl_mt      : Articles de mes Tendances
     *  _xl_st      : Articles de mes Abonnements aux Tendances
     */
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->_NWFD_MENUS = ["team","comy","bzfeed"];
        $this->_NWFD_DFT_MENU = "comy";
        $this->_NWFD_DFT_MODE = "list";
        
        /*
         * [DEPUIS 24-11-15] @author BOR
         */
        $this->default_dbname = ( defined("WOS_MAIN_HOST") && !in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) ? "tqr_product_vb1_prod" : "tqr_product_vb1";
        
    }

    
    public function GetFirstArticles ($accid, $mode, $menu = NULL) {
        /*
         * Permet de récupérer les Articles pour NWFD selon l'utilisateur et le menu sélectionné.
         * Si aucun menu n'est sélectionné, on prends le menu par défaut.
         * 
         * A la version vb1, seule le menu "comy" sera disponible.
         */
        $args = [$accid, $mode];
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $menu = ( empty($menu) ) ? $this->_NWFD_DFT_MENU : $menu;
        
        //Sélection du mode de visionnage
        $MD_xPTD = ["list","moz"];
        $md = (! in_array($mode, $MD_xPTD) ) ? $this->_NWFD_DFT_MODE : $mode;
        
        switch (strtolower($menu)) {
            case "comy": 
//                    $fret = $this->GetFistArt_Comy($accid,$md);
                    $fret = $this->GetFistArt_Iml_Frd($accid,$md); //[DEPUIS 07-04-16]
                break;
            //Les menus ne sont pas disponibles à cette version (vb1)
            case "team":
            case "bzfeed": 
                break;
            /*
             * [DEPUIS 07-04-16]
             *      Ajout des nouveeaux MENU
             */
            case "iml_pod":
                    $fret = $this->GetFistArt_Iml_Pod($accid,$md);
                break;
            case "itr": 
                    $fret = $this->GetFistArt_Itr($accid,$md);
                break;
            case "tlkb": 
                break;
            default:
                return "__ERR_VOL_NOT_FOUND";
        }
        
        return $fret;
    }
    
    public function GetNewerArticles ($accid, $mode, $asp, $menu = NULL) {
//    public function GetNewerArticles ($accid, $mode, $art_eid, $time, $menu = NULL) {
        /*
         * Permet de récupérer les Articles pour NWFD ultérieurs à l'Article pivot passé en paramètre ainsi qu'à la date liée. 
         * 
         * A la version vb1, seule le menu "comy" sera disponible.
         */
        $args = [$accid, $mode, $asp];
//        $args = [$accid, $mode, $art_eid, $time];
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $menu = ( empty($menu) ) ? $this->_NWFD_DFT_MENU : $menu;
        
        //Sélection du mode de visionnage
        $MD_xPTD = ["list","moz"];
        $md = (! in_array($mode, $MD_xPTD) ) ? $this->_NWFD_DFT_MODE : $mode;
        
        switch (strtolower($menu)) {
            case "comy": 
//                    $fret = $this->GetNewerArt_Comy($accid,$md,$art_eid,$time);
//                    $fret = $this->GetNewerArt_Comy($accid,$md,$asp); //[DEPUIS 07-04-16]
                    $fret = $this->GetNewerArt_Iml_Frd($accid,$md,$asp);
                break;
            //Les menus ne sont pas disponibles à cette version (vb1)
            case "team":
            case "bzfeed": 
                break;
            /*
             * [DEPUIS 07-04-16]
             *      Ajout des nouveeaux MENU
             */
            case "iml_pod":
                    $fret = $this->GetNewerArt_Iml_Pod($accid,$md,$asp);
                break;
            case "itr": 
                    $fret = $this->GetNewerArt_Itr($accid,$md,$asp);
                break;
            case "tlkb": 
                    $fret = [];
                break;
            default: 
                    return "__ERR_VOL_NOT_FOUND";
                break;
        }
        
        return $fret;
        
    }
    
    public function GetOlderArticles($accid, $mode, $asp, $menu = NULL) {
//    public function GetOlderArticles($accid, $mode, $artid, $time, $menu = NULL) {
        /*
         * Permet de récupérer les Articles pour NWFD antérieurs à l'Article pivot passé en paramètre ainsi qu'à la date liée. 
         * 
         * A la version vb1, seule le menu "comy" sera disponible.
         */
        $args = [$accid, $mode, $asp];
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $menu = ( empty($menu) ) ? $this->_NWFD_DFT_MENU : $menu;
        
        //Sélection du mode de visionnage
        $MD_xPTD = ["list","moz"];
        $md = (! in_array($mode, $MD_xPTD) ) ? $this->_NWFD_DFT_MODE : $mode;
        
        switch (strtolower($menu)) {
            case "comy": 
                    
//                    $fret = $this->GetOlderArt_Comy($accid,$md,$artid,$time);
//                    $fret = $this->GetOlderArt_Comy($accid,$md,$asp); //[DEPUIS 07-04-16]
                    $fret = $this->GetOlderArt_Iml_Frd($accid,$md,$asp);
                break;
            //Les menus ne sont pas disponibles à cette version (vb1)
            case "team":
            case "bzfeed": 
                break;
            /*
             * [DEPUIS 07-04-16]
             *      Ajout des nouveeaux MENU
             */
            case "iml_pod":
                    $fret = $this->GetOlderArt_Iml_Pod($accid,$md,$asp);
                break;
            case "itr": 
                    $fret = $this->GetOlderArt_Itr($accid,$md,$asp);
                break;
            case "tlkb": 
                    $fret = [];
                break;
            default: 
                return "__ERR_VOL_NOT_FOUND";
                
        }
        
        return $fret;
        
    }
    
    /************************* FIRST ARTICLES SCOPE ****************************/
    
    private function GetFistArt_Comy ($accid, $mode) {
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed à la première ouverture de la zone.
         */
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        /*
         * [NOTE 19-03-15] @Lou
         * On crée deux listes : (1) Liste des Amis (2) Listes abonnements S_FOLW & D_FOLW.
         * Cela permet de récupérer les données qui auront les codes :
         *  _xl_3im     : Articles IML des Amis
         *  _xl_3im_pod : Articles IML des Amis (Pic Of Day)
         *  _xl_3it     : Articles ITR des Amis
         *  _xl_2im     : Articles IML des D_FOLW
         *  _xl_12it    : Articles ITR des S_FOLW, D_FOLW
         *  _xl_mt      : Articles de mes Tendances
         *  _xl_st      : Articles de mes Abonnements aux Tendances
         */
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l2 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                }
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        } /*
        } else {
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
            $rel_list = ""; //A retirer
        }
        //*/
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
        $mine_datas = $comy_datas["own"];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
        $abo_datas = $comy_datas["abo"];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
//        var_dump($comy_datas, empty($rel_list_l12), empty($rel_list_l3), empty($abo_list), empty($mine_list));
//        var_dump($comy_datas, $rel_list_l12, $rel_list_l3, $abo_list, $mine_list);
//        exit();
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 10 : 30;
//            $limit = ( $mode === "list" ) ? 2 : 2; //DEV, TEST, DEBUG
            $now = round(microtime(TRUE)*1000);
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
            
            if ( !empty($rel_list_l12) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 19-03-15] @BlackOwlRobot
                 *  Le code a été profondement refactorisé car il étaait faux.
                 *  Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 *  Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 * 
                 * [NOTE 05-09-15] @BOR
                 *  Prise en compte de la spécifité TODEL
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }
            
            
            if ( !empty($rel_list_l2) ) {
                
                /*
                 * [NOTE 13-05-15] @BOR
                 * Ces Articles doivent être incoporés au fil NewsFeed.
                 * Il s'agit des Articles de type IML accessibles pour les Relations de type D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_IML.* ";
                $qbody .= "FROM VM_Articles_IML ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
            if ( !empty($rel_list_l3) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 19-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $QO = new QUERY();
                $qbody   = "SELECT VM_Articles_IML.* ";
//                $qbody   = "( SELECT *, null as art_trid, null as art_trd_eid, null as art_trd_title, null as art_trd_desc, null as art_trd_title_href, null as art_trd_catgid, null as art_trd_is_public, null as art_trd_grat, null as art_trd_date_tstamp ";
//                $qbody   = "( SELECT *, '', '', '', '', '', '', '', '', '' ";
                $qbody  .= "FROM VM_Articles_IML ";
//                $qbody  .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                $qbody  .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody  .= "AND pdacc_todelete = 0 ";        
                $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                $qbody  .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                $t__1 = $QO->execute($qparams_in);
                
                $qbody  = "SELECT VM_Articles_ITR.* ";
                $qbody  .= "FROM VM_Articles_ITR ";
//                $qbody  .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                $qbody  .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody  .= "AND pdacc_todelete = 0 ";   
                $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                $qbody  .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
//                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                $t__2 = $QO->execute($qparams_in);
                
//                $t__ = $t__1+$t__2;
                $t__ = array_merge($t__1,$t__2);
//                var_dump($t__1,$t__2,$t__);
//                exit();
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( $t__ ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
//            SELECT * FROM VM_Articles_ITR WHERE art_oid IN ('53') AND art_crea_tstamp <= 1426803616000 ORDER BY art_crea_tstamp DESC LIMIT 10;
//            SELECT * FROM VM_Articles_IML T1, VM_Articles_ITR T2 WHERE T1.art_oid IN ('101','104') AND T2.art_oid IN ('101','104') AND T1.art_crea_tstamp <= 1426803616000 AND T2.art_crea_tstamp <= 1426803616000 ORDER BY art_crea_tstamp DESC LIMIT 10;
            
            if ( !empty($mine_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR des Tendances faisant partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                /*
                 * [13-05-15] @BOR
                 * Ces lignes ne semblaient pas correctes. En effet, la partie avec final ne pose pas vraiment problème.
                 * C'est la ligne qui tente de ne pas intégrer les Articles des Relations qui est fausse.
                 * En effet, dans tous les cas ils ne doivent pas être intégrés car ils doivent être logiquement dans les tables "_12it", "_2im" et/ou "_3im".
                 * Il ne faut donc pas attendre qu'il y ait des données dans "final" pour devoir retirer ces données.
                 * La seule précaution qu'on pourrait prendre serait de vérifier si on a effectivement la liste des Relations.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /*
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            if (! empty($abo_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                /*
                $abo_articles = $QO->execute($qparams_in);
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
                //*/
            }
            
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
//            var_dump(count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump(array_column($rel_l3_articles,'artid'),count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump($rel_l12_articles,$rel_l3_articles,$mine_articles,$abo_articles);
//            exit();
            
            if ( isset($final) && count($final) ) {
                
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 * 
                 * [NOTE 04-10-14] @author L.C.
                 * On est passé au mode ASC car au niveau du FE les images sont ajoutées par ordre inversé.
                 * Aussi, on ajoutera d'abord le moins récent puis le plus récent au dessus de ce dernier Article.
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] > $b['art_crea_tstamp']) ? -1 : 1;
//                    $t__ = $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
//                    var_dump($b['art_crea_tstamp'],$a['art_crea_tstamp'],$t__);
//                    $t__ = intval($b['art_crea_tstamp']) - intval($a['art_crea_tstamp']);
//                    var_dump(intval($b['art_crea_tstamp']),intval($a['art_crea_tstamp']),$t__);
//                    return $t__;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if ( in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    
    private function GetFistArt_Iml_Frd ($accid, $mode) {
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed à la première ouverture de la zone.
         * 
         * [DEPUIS 07-04-16]
         *      Cette nouvelle fonction permettra de ne récupérer que les Articles IML de type FRIEND
         */
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
//        var_dump(__FILE__,__FUNCTION__,__FILE__,$comy_datas);
//        return $comy_datas;
//        exit();
        
        //On crée une chaine listant les comptes en relation
        /*
         * [NOTE 19-03-15] @Lou
         * On crée deux listes : (1) Liste des Amis (2) Listes abonnements S_FOLW & D_FOLW.
         * Cela permet de récupérer les données qui auront les codes :
         *  _xl_3im     : Articles IML des Amis
         *  _xl_3it     : Articles ITR des Amis
         *  _xl_2im     : Articles IML des D_FOLW
         *  _xl_12it    : Articles ITR des S_FOLW, D_FOLW
         *  _xl_mt      : Articles de mes Tendances
         *  _xl_st      : Articles de mes Abonnements aux Tendances
         */
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l2 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                }
                /*
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                }
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
                //*/
                
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        } /*
        } else {
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
            $rel_list = ""; //A retirer
        }
        //*/
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
//        $mine_datas = $comy_datas["own"]; //[DEPUIS 07-04-16]
        $mine_datas = [];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
//        $abo_datas = $comy_datas["abo"]; //[DEPUIS 07-04-16]
        $abo_datas = [];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
//        var_dump($comy_datas, empty($rel_list_l12), empty($rel_list_l3), empty($abo_list), empty($mine_list));
//        var_dump($comy_datas, $rel_list_l12, $rel_list_l3, $abo_list, $mine_list);
//        exit();
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 10 : 30;
//            $limit = ( $mode === "list" ) ? 2 : 2; //DEV, TEST, DEBUG
            $now = round(microtime(TRUE)*1000);
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
            
            if ( !empty($rel_list_l12) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 19-03-15] @BlackOwlRobot
                 *      Le code a été profondement refactorisé car il étaait faux.
                 *      Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 *      Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 * 
                 * [NOTE 05-09-15] @BOR
                 *      Prise en compte de la spécifité TODEL
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }
            
            
            if ( !empty($rel_list_l2) ) {
                
                /*
                 * [NOTE 13-05-15] @BOR
                 *      Ces Articles doivent être incoporés au fil NewsFeed.
                 *      Il s'agit des Articles de type IML accessibles pour les Relations de type D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_IML.* ";
                $qbody .= "FROM VM_Articles_IML ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
            if ( !empty($rel_list_l3) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 19-03-15] @BlackOwlRobot
                 *      Le code a été profondement refactorisé car il étaait faux.
                 *      Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 *      Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $QO = new QUERY();
                $qbody   = "SELECT VM_Articles_IML.* ";
//                $qbody   = "( SELECT *, null as art_trid, null as art_trd_eid, null as art_trd_title, null as art_trd_desc, null as art_trd_title_href, null as art_trd_catgid, null as art_trd_is_public, null as art_trd_grat, null as art_trd_date_tstamp ";
//                $qbody   = "( SELECT *, '', '', '', '', '', '', '', '', '' ";
                $qbody  .= "FROM VM_Articles_IML ";
//                $qbody  .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                $qbody  .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody  .= "AND pdacc_todelete = 0 ";        
                $qbody  .= "AND art_is_sod = 0 "; //  [DEPUIS 07-04-16]     
                $qbody  .= "AND art_is_hstd = 0 "; //  [DEPUIS 17-07-16]     
                $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                $qbody  .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                $t__1 = $QO->execute($qparams_in);
                
                /*
                $qbody  = "SELECT VM_Articles_ITR.* ";
                $qbody  .= "FROM VM_Articles_ITR ";
//                $qbody  .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                $qbody  .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody  .= "AND pdacc_todelete = 0 ";   
                $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                $qbody  .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
//                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                $t__2 = $QO->execute($qparams_in);
                //*/
                $t__2 = [];
                        
//                $t__ = $t__1+$t__2;
                $t__ = array_merge($t__1,$t__2);
//                var_dump($t__1,$t__2,$t__);
//                exit();
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( $t__ ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
//            SELECT * FROM VM_Articles_ITR WHERE art_oid IN ('53') AND art_crea_tstamp <= 1426803616000 ORDER BY art_crea_tstamp DESC LIMIT 10;
//            SELECT * FROM VM_Articles_IML T1, VM_Articles_ITR T2 WHERE T1.art_oid IN ('101','104') AND T2.art_oid IN ('101','104') AND T1.art_crea_tstamp <= 1426803616000 AND T2.art_crea_tstamp <= 1426803616000 ORDER BY art_crea_tstamp DESC LIMIT 10;
            
            if ( !empty($mine_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR des Tendances faisant partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                /*
                 * [13-05-15] @BOR
                 * Ces lignes ne semblaient pas correctes. En effet, la partie avec final ne pose pas vraiment problème.
                 * C'est la ligne qui tente de ne pas intégrer les Articles des Relations qui est fausse.
                 * En effet, dans tous les cas ils ne doivent pas être intégrés car ils doivent être logiquement dans les tables "_12it", "_2im" et/ou "_3im".
                 * Il ne faut donc pas attendre qu'il y ait des données dans "final" pour devoir retirer ces données.
                 * La seule précaution qu'on pourrait prendre serait de vérifier si on a effectivement la liste des Relations.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /*
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            if (! empty($abo_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                /*
                $abo_articles = $QO->execute($qparams_in);
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
                //*/
            }
            
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
//            var_dump(count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump(array_column($rel_l3_articles,'artid'),count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump($rel_l12_articles,$rel_l3_articles,$mine_articles,$abo_articles);
//            exit();
            
            if ( isset($final) && count($final) ) {
                
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 * 
                 * [NOTE 04-10-14] @author L.C.
                 * On est passé au mode ASC car au niveau du FE les images sont ajoutées par ordre inversé.
                 * Aussi, on ajoutera d'abord le moins récent puis le plus récent au dessus de ce dernier Article.
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] > $b['art_crea_tstamp']) ? -1 : 1;
//                    $t__ = $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
//                    var_dump($b['art_crea_tstamp'],$a['art_crea_tstamp'],$t__);
//                    $t__ = intval($b['art_crea_tstamp']) - intval($a['art_crea_tstamp']);
//                    var_dump(intval($b['art_crea_tstamp']),intval($a['art_crea_tstamp']),$t__);
//                    return $t__;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if ( in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    
    private function GetFistArt_Iml_Pod ($accid, $mode) {
        /*
         * [DEPUIS 07-04-16]
         *      Cette fonction est une évolution/mutation de l'ancienne fonction @see GetFistArt_Comy
         */
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$comy_datas);
//        exit();
        
        //On crée une chaine listant les comptes en relation
        /*
         * [NOTE 19-03-15] @Lou
         * On crée deux listes : (1) Liste des Amis (2) Listes abonnements S_FOLW & D_FOLW.
         * Cela permet de récupérer les données qui auront les codes :
         *  _xl_3im     : Articles IML des Amis
         *  _xl_3im_pod : Articles IML des Amis (Pic Of Day)
         *  _xl_3it     : Articles ITR des Amis
         *  _xl_2im     : Articles IML des D_FOLW
         *  _xl_12it    : Articles ITR des S_FOLW, D_FOLW
         *  _xl_mt      : Articles de mes Tendances
         *  _xl_st      : Articles de mes Abonnements aux Tendances
         */
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l2 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                }
                /*
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                }
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
                //*/
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        } /*
        } else {
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
            $rel_list = ""; //A retirer
        }
        //*/
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
//        $mine_datas = $comy_datas["own"];
        $mine_datas = [];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
//        $abo_datas = $comy_datas["abo"];
        $abo_datas = [];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
//        var_dump($comy_datas, empty($rel_list_l12), empty($rel_list_l3), empty($abo_list), empty($mine_list));
//        var_dump($comy_datas, $rel_list_l12, $rel_list_l3, $abo_list, $mine_list);
//        exit();
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 10 : 30;
//            $limit = ( $mode === "list" ) ? 2 : 2; //DEV, TEST, DEBUG
            $now = round(microtime(TRUE)*1000);
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
            
            if ( !empty($rel_list_l12) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 19-03-15] @BlackOwlRobot
                 *  Le code a été profondement refactorisé car il étaait faux.
                 *  Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 *  Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 * 
                 * [NOTE 05-09-15] @BOR
                 *  Prise en compte de la spécifité TODEL
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }
            
            
            if ( !empty($rel_list_l2) ) {
                
                /*
                 * [NOTE 13-05-15] @BOR
                 * Ces Articles doivent être incoporés au fil NewsFeed.
                 * Il s'agit des Articles de type IML accessibles pour les Relations de type D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_IML.* ";
                $qbody .= "FROM VM_Articles_IML ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
            if ( !empty($rel_list_l3) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 19-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $QO = new QUERY();
                $qbody   = "SELECT VM_Articles_IML.* ";
//                $qbody   = "( SELECT *, null as art_trid, null as art_trd_eid, null as art_trd_title, null as art_trd_desc, null as art_trd_title_href, null as art_trd_catgid, null as art_trd_is_public, null as art_trd_grat, null as art_trd_date_tstamp ";
//                $qbody   = "( SELECT *, '', '', '', '', '', '', '', '', '' ";
                $qbody  .= "FROM VM_Articles_IML ";
//                $qbody  .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                $qbody  .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody  .= "AND pdacc_todelete = 0 ";  
                $qbody  .= "AND art_is_sod = 1 "; //  [DEPUIS 07-04-16]   
                $qbody  .= "AND :now <= `art_crea_tstamp`+(24*3600000)"; //  [DEPUIS 20-05-16]   
                $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                $qbody  .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(
                    ":now"      => $now, 
                    ":tstamp"   => $now, 
                    ":limit"    => $limit
                );
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                $t__1 = $QO->execute($qparams_in);
                
                /*
                $qbody  = "SELECT VM_Articles_ITR.* ";
                $qbody  .= "FROM VM_Articles_ITR ";
//                $qbody  .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                $qbody  .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody  .= "AND pdacc_todelete = 0 ";   
                $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                $qbody  .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
//                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                $t__2 = $QO->execute($qparams_in);
                //*/
                $t__2 = [];
                
//                $t__ = $t__1+$t__2;
                $t__ = array_merge($t__1,$t__2);
//                var_dump($t__1,$t__2,$t__);
//                exit();
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( $t__ ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_3im_pod";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
//            SELECT * FROM VM_Articles_ITR WHERE art_oid IN ('53') AND art_crea_tstamp <= 1426803616000 ORDER BY art_crea_tstamp DESC LIMIT 10;
//            SELECT * FROM VM_Articles_IML T1, VM_Articles_ITR T2 WHERE T1.art_oid IN ('101','104') AND T2.art_oid IN ('101','104') AND T1.art_crea_tstamp <= 1426803616000 AND T2.art_crea_tstamp <= 1426803616000 ORDER BY art_crea_tstamp DESC LIMIT 10;
            
            if ( !empty($mine_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR des Tendances faisant partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                /*
                 * [13-05-15] @BOR
                 * Ces lignes ne semblaient pas correctes. En effet, la partie avec final ne pose pas vraiment problème.
                 * C'est la ligne qui tente de ne pas intégrer les Articles des Relations qui est fausse.
                 * En effet, dans tous les cas ils ne doivent pas être intégrés car ils doivent être logiquement dans les tables "_12it", "_2im" et/ou "_3im".
                 * Il ne faut donc pas attendre qu'il y ait des données dans "final" pour devoir retirer ces données.
                 * La seule précaution qu'on pourrait prendre serait de vérifier si on a effectivement la liste des Relations.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /*
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            if (! empty($abo_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                /*
                $abo_articles = $QO->execute($qparams_in);
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
                //*/
            }
            
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
//            var_dump(count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump(array_column($rel_l3_articles,'artid'),count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump($rel_l12_articles,$rel_l3_articles,$mine_articles,$abo_articles);
//            exit();
            
            if ( isset($final) && count($final) ) {
                
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 * 
                 * [NOTE 04-10-14] @author L.C.
                 * On est passé au mode ASC car au niveau du FE les images sont ajoutées par ordre inversé.
                 * Aussi, on ajoutera d'abord le moins récent puis le plus récent au dessus de ce dernier Article.
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] > $b['art_crea_tstamp']) ? -1 : 1;
//                    $t__ = $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
//                    var_dump($b['art_crea_tstamp'],$a['art_crea_tstamp'],$t__);
//                    $t__ = intval($b['art_crea_tstamp']) - intval($a['art_crea_tstamp']);
//                    var_dump(intval($b['art_crea_tstamp']),intval($a['art_crea_tstamp']),$t__);
//                    return $t__;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if ( in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    private function GetFistArt_Itr ($accid, $mode) {
        /*
         * [DEPUIS 07-04-16]
         *      Cette fonction est une évolution/mutation de l'ancienne fonction @see GetFistArt_Comy
         */
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        /*
         * [NOTE 19-03-15] @Lou
         * On crée deux listes : (1) Liste des Amis (2) Listes abonnements S_FOLW & D_FOLW.
         * Cela permet de récupérer les données qui auront les codes :
         *  _xl_3im     : Articles IML des Amis
         *  _xl_3im_pod : Articles IML des Amis (Pic Of Day)
         *  _xl_3it     : Articles ITR des Amis
         *  _xl_2im     : Articles IML des D_FOLW
         *  _xl_12it    : Articles ITR des S_FOLW, D_FOLW
         *  _xl_mt      : Articles de mes Tendances
         *  _xl_st      : Articles de mes Abonnements aux Tendances
         */
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l2 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                }
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        } /*
        } else {
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
            $rel_list = ""; //A retirer
        }
        //*/
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
        $mine_datas = $comy_datas["own"];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
        $abo_datas = $comy_datas["abo"];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
//        var_dump($comy_datas, empty($rel_list_l12), empty($rel_list_l3), empty($abo_list), empty($mine_list));
//        var_dump($comy_datas, $rel_list_l12, $rel_list_l3, $abo_list, $mine_list);
//        exit();
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 10 : 30;
//            $limit = ( $mode === "list" ) ? 2 : 2; //DEV, TEST, DEBUG
            $now = round(microtime(TRUE)*1000);
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
            
            if ( !empty($rel_list_l12) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 19-03-15] @BlackOwlRobot
                 *  Le code a été profondement refactorisé car il étaait faux.
                 *  Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 *  Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 * 
                 * [NOTE 05-09-15] @BOR
                 *  Prise en compte de la spécifité TODEL
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }
            
            
            if ( !empty($rel_list_l2) ) {
                
                /*
                 * [NOTE 13-05-15] @BOR
                 * Ces Articles doivent être incoporés au fil NewsFeed.
                 * Il s'agit des Articles de type IML accessibles pour les Relations de type D_FOLW.
                 */
                /*
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_IML.* ";
                $qbody .= "FROM VM_Articles_IML ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                //*/
            }
            
            
            if ( !empty($rel_list_l3) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 19-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                /*
                $QO = new QUERY();
                $qbody   = "SELECT VM_Articles_IML.* ";
//                $qbody   = "( SELECT *, null as art_trid, null as art_trd_eid, null as art_trd_title, null as art_trd_desc, null as art_trd_title_href, null as art_trd_catgid, null as art_trd_is_public, null as art_trd_grat, null as art_trd_date_tstamp ";
//                $qbody   = "( SELECT *, '', '', '', '', '', '', '', '', '' ";
                $qbody  .= "FROM VM_Articles_IML ";
//                $qbody  .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                $qbody  .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody  .= "AND pdacc_todelete = 0 ";        
                $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                $qbody  .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                $t__1 = $QO->execute($qparams_in);
                //*/
                $t__1 = [];
                
                $qbody  = "SELECT VM_Articles_ITR.* ";
                $qbody  .= "FROM VM_Articles_ITR ";
//                $qbody  .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                $qbody  .= "AND art_crea_tstamp <= :tstamp ";
//                $qbody  .= "AND pdacc_todelete = 0 ";   
                $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                $qbody  .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit);
//                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                $t__2 = $QO->execute($qparams_in);
                
//                $t__ = $t__1+$t__2;
                $t__ = array_merge($t__1,$t__2);
//                var_dump($t__1,$t__2,$t__);
//                exit();
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( $t__ ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
//            SELECT * FROM VM_Articles_ITR WHERE art_oid IN ('53') AND art_crea_tstamp <= 1426803616000 ORDER BY art_crea_tstamp DESC LIMIT 10;
//            SELECT * FROM VM_Articles_IML T1, VM_Articles_ITR T2 WHERE T1.art_oid IN ('101','104') AND T2.art_oid IN ('101','104') AND T1.art_crea_tstamp <= 1426803616000 AND T2.art_crea_tstamp <= 1426803616000 ORDER BY art_crea_tstamp DESC LIMIT 10;
            
            if ( !empty($mine_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR des Tendances faisant partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                /*
                 * [13-05-15] @BOR
                 * Ces lignes ne semblaient pas correctes. En effet, la partie avec final ne pose pas vraiment problème.
                 * C'est la ligne qui tente de ne pas intégrer les Articles des Relations qui est fausse.
                 * En effet, dans tous les cas ils ne doivent pas être intégrés car ils doivent être logiquement dans les tables "_12it", "_2im" et/ou "_3im".
                 * Il ne faut donc pas attendre qu'il y ait des données dans "final" pour devoir retirer ces données.
                 * La seule précaution qu'on pourrait prendre serait de vérifier si on a effectivement la liste des Relations.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /*
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            if (! empty($abo_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                $QO = new QUERY();
                $qbody = "SELECT VM_Articles_ITR.* ";
                $qbody .= "FROM VM_Articles_ITR ";
//                $qbody .= "INNER JOIN Proddb_Accounts ON pdaccid = art_oid ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
//                $qbody .= "AND pdacc_todelete = 0 ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $now, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                /*
                $abo_articles = $QO->execute($qparams_in);
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
                //*/
            }
            
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
//            var_dump(count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump(array_column($rel_l3_articles,'artid'),count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump($rel_l12_articles,$rel_l3_articles,$mine_articles,$abo_articles);
//            exit();
            
            if ( isset($final) && count($final) ) {
                
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 * 
                 * [NOTE 04-10-14] @author L.C.
                 * On est passé au mode ASC car au niveau du FE les images sont ajoutées par ordre inversé.
                 * Aussi, on ajoutera d'abord le moins récent puis le plus récent au dessus de ce dernier Article.
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] > $b['art_crea_tstamp']) ? -1 : 1;
//                    $t__ = $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
//                    var_dump($b['art_crea_tstamp'],$a['art_crea_tstamp'],$t__);
//                    $t__ = intval($b['art_crea_tstamp']) - intval($a['art_crea_tstamp']);
//                    var_dump(intval($b['art_crea_tstamp']),intval($a['art_crea_tstamp']),$t__);
//                    return $t__;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if ( in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    /************************* OLDER ARTICLES SCOPE ****************************/
    
    private function GetOlderArt_Comy ($accid, $mode, $asp) {
//    private function GetOlderArt_Comy ($accid,$mode,$art_eid,$time) {
        //ArticleSPecs
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed.
         * Les Articles sont anterieurs.
         */
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                } 
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        }
        /*
        $rel_datas = $comy_datas["rel"];
        if ( $rel_datas ) {
            foreach ($rel_datas as $v) {
                $temp[] = $v["id"];
            }
            $rel_list = implode("','", $temp);
        } else { 
            $rel_list = ""; 
        }
        //*/
        
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
        $mine_datas = $comy_datas["own"];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
        $abo_datas = $comy_datas["abo"];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 10 : 30;
//            $limit = ( $mode === "list" ) ? 2 : 2; //DEV, TEST, DEBUG
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $abo_articles = NULL;
            
            
            if ( !empty($rel_list_l12) && ( key_exists("_xl_12it", $asp) && $asp["_xl_12it"] ) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il était faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_12it"]["i"], ":tstamp" => $asp["_xl_12it"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }

            if ( !empty($rel_list_l2) && ( key_exists("_xl_2im", $asp) && $asp["_xl_2im"] ) ) {
                /*
                 * [NOTE 13-05-15] @BOR
                 * On ajoute les Articles de type IML pour les Relations de type D_FOLW.
                 * Les règles stipulent qu'on peut accéder à un Article en mode lecture si on se suit mutuellement.
                 * Plus tard, une option permettra à l'utilisateur de refuser cette diffusion.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get"; 
                $qparams_in = array(":art_eid" => $asp["_xl_2im"]["i"], ":tstamp" => $asp["_xl_2im"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            if ( !empty($rel_list_l3) && ( ( key_exists("_xl_3im", $asp) && $asp["_xl_3im"] ) | ( ( key_exists("_xl_3it", $asp) && $asp["_xl_3it"] ) ) ) ) {
                
                /*
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $t__ = $t__1 = $t__2 = [];
                if ( key_exists("_xl_3im", $asp) && count($asp["_xl_3im"]) ) {
                    $QO = new QUERY();
                    $qbody   = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_IML ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp <= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(
                        ":art_eid"  => $asp["_xl_3im"]["i"], 
                        ":tstamp"   => $asp["_xl_3im"]["t"], 
                        ":limit"    => $limit
                    );
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__1 = $QO->execute($qparams_in);
                    
                    $t__ = ( $t__1 ) ? $t__1 : [];
                }
                
                if ( key_exists("_xl_3it", $asp) && count($asp["_xl_3it"]) ) {
                    $QO = new QUERY();
                    $qbody  = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_ITR ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp <= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
    //                var_dump($qbody);
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(
                        ":art_eid"  => $asp["_xl_3it"]["i"], 
                        ":tstamp"   => $asp["_xl_3it"]["t"], 
                        ":limit"    => $limit
                    );
    //                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__2 = $QO->execute($qparams_in);
                    
                   if ( $t__ && $t__2 ) {
                       $t__ = array_merge($t__1,$t__2);
                   } else if ( !$t__ || $t__2 ) {
                       $t__ = $t__2;
                   }
                }
                
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( count($t__) ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            /*
            if ( !empty($rel_list) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $rel_articles = $QO->execute($qparams_in);
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( $rel_articles ) {
                    $final = $rel_articles;
                }
            }
            //*/
            
            if ( !empty($mine_list) && ( key_exists("_xl_mt", $asp) && !empty($asp["_xl_mt"]) ) ) {
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_mt"]["i"], ":tstamp" => $asp["_xl_mt"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date pivot.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $mine_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( count($final) && ( $mine_articles && is_array($mine_articles) && count($mine_articles) ) ) {
                    $final = array_merge($final,$mine_articles);
                } else if ( $mine_articles ) {
                    $final = $mine_articles;
                }
                //*/
            }
            
            if ( !empty($abo_list) && ( key_exists("_xl_st", $asp) && !empty($asp["_xl_st"]) ) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */

                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_st"]["i"], ":tstamp" => $asp["_xl_st"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 *
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":accid" => $accid, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $abo_articles = $QO->execute($qparams_in);
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
            //*/ 
            }
                 
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
//            var_dump(count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump(array_column($rel_l3_articles,'artid'),count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump($rel_l12_articles,$rel_l3_articles,$mine_articles,$abo_articles);
//            exit();
            
            if ( isset($final) && count($final) ) {
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 * 
                 * [NOTE 16-03-15] @author L.C.
                 * On est passé au mode ASC car au niveau du FE les images sont ajoutées par ordre inversé.
                 * Aussi, on ajoutera d'abord le moins récent puis le plus récent au dessus de ce dernier Article.
                 * Ce changement récent suit celui de la méthode First(). Je n'avait pas reporté les changements au niveau de toutes les autres méthodes.
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] > $b['art_crea_tstamp']) ? -1 : 1;
//                    $t__ = $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
//                    var_dump($b['art_crea_tstamp'],$a['art_crea_tstamp'],$t__);
//                    $t__ = intval($b['art_crea_tstamp']) - intval($a['art_crea_tstamp']);
//                    var_dump(intval($b['art_crea_tstamp']),intval($a['art_crea_tstamp']),$t__);
//                    return $t__;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if (in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    
    private function GetOlderArt_Iml_Frd ($accid, $mode, $asp) {
//    private function GetOlderArt_Comy ($accid,$mode,$art_eid,$time) {
        //ArticleSPecs
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed.
         * Les Articles sont anterieurs.
         */
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                }
                /* //[DEPUIS 07-04-16]
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                } 
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
                //*/
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        }
        /*
        $rel_datas = $comy_datas["rel"];
        if ( $rel_datas ) {
            foreach ($rel_datas as $v) {
                $temp[] = $v["id"];
            }
            $rel_list = implode("','", $temp);
        } else { 
            $rel_list = ""; 
        }
        //*/
        
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
//        $mine_datas = $comy_datas["own"]; //[DEPUIS 07-04-16]
        $mine_datas = [];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
//        $abo_datas = $comy_datas["abo"]; //[DEPUIS 07-04-16]
        $abo_datas = [];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 10 : 30;
//            $limit = ( $mode === "list" ) ? 2 : 2; //DEV, TEST, DEBUG
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $abo_articles = NULL;
            
            
            if ( !empty($rel_list_l12) && ( key_exists("_xl_12it", $asp) && $asp["_xl_12it"] ) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il était faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_12it"]["i"], ":tstamp" => $asp["_xl_12it"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }

            if ( !empty($rel_list_l2) && ( key_exists("_xl_2im", $asp) && $asp["_xl_2im"] ) ) {
                /*
                 * [NOTE 13-05-15] @BOR
                 * On ajoute les Articles de type IML pour les Relations de type D_FOLW.
                 * Les règles stipulent qu'on peut accéder à un Article en mode lecture si on se suit mutuellement.
                 * Plus tard, une option permettra à l'utilisateur de refuser cette diffusion.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get"; 
                $qparams_in = array(":art_eid" => $asp["_xl_2im"]["i"], ":tstamp" => $asp["_xl_2im"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            if ( !empty($rel_list_l3) && ( ( key_exists("_xl_3im", $asp) && $asp["_xl_3im"] ) | ( ( key_exists("_xl_3it", $asp) && $asp["_xl_3it"] ) ) ) ) {
                
                /*
                 * [NOTE 20-03-15] @BlackOwlRobot
                 *      Le code a été profondement refactorisé car il étaait faux.
                 *      Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 *      Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $t__ = $t__1 = $t__2 = [];
                if ( key_exists("_xl_3im", $asp) && count($asp["_xl_3im"]) ) {
                    $QO = new QUERY();
                    $qbody   = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_IML ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp <= :tstamp ";
                    $qbody  .= "AND art_is_sod = 0 "; //  [DEPUIS 07-04-16]     
                    $qbody  .= "AND art_is_hstd = 0 "; //  [DEPUIS 17-07-16]     
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(
                        ":art_eid"  => $asp["_xl_3im"]["i"], 
                        ":tstamp"   => $asp["_xl_3im"]["t"], 
                        ":limit"    => $limit
                    );
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__1 = $QO->execute($qparams_in);
                    
                    $t__ = ( $t__1 ) ? $t__1 : [];
                }
                
                /*
                if ( key_exists("_xl_3it", $asp) && count($asp["_xl_3it"]) ) {
                    $QO = new QUERY();
                    $qbody  = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_ITR ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp <= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
    //                var_dump($qbody);
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3it"]["i"], ":tstamp" => $asp["_xl_3it"]["t"], ":limit" => $limit);
    //                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__2 = $QO->execute($qparams_in);
                    
                   if ( $t__ && $t__2 ) {
                       $t__ = array_merge($t__1,$t__2);
                   } else if ( !$t__ || $t__2 ) {
                       $t__ = $t__2;
                   }
                }
                //*/
                
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( count($t__) ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            /*
            if ( !empty($rel_list) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $rel_articles = $QO->execute($qparams_in);
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( $rel_articles ) {
                    $final = $rel_articles;
                }
            }
            //*/
            
            if ( !empty($mine_list) && ( key_exists("_xl_mt", $asp) && !empty($asp["_xl_mt"]) ) ) {
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_mt"]["i"], ":tstamp" => $asp["_xl_mt"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date pivot.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $mine_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( count($final) && ( $mine_articles && is_array($mine_articles) && count($mine_articles) ) ) {
                    $final = array_merge($final,$mine_articles);
                } else if ( $mine_articles ) {
                    $final = $mine_articles;
                }
                //*/
            }
            
            if ( !empty($abo_list) && ( key_exists("_xl_st", $asp) && !empty($asp["_xl_st"]) ) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */

                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_st"]["i"], ":tstamp" => $asp["_xl_st"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 *
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":accid" => $accid, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $abo_articles = $QO->execute($qparams_in);
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
            //*/ 
            }
                 
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
//            var_dump(count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump(array_column($rel_l3_articles,'artid'),count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump($rel_l12_articles,$rel_l3_articles,$mine_articles,$abo_articles);
//            exit();
            
            if ( isset($final) && count($final) ) {
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 * 
                 * [NOTE 16-03-15] @author L.C.
                 * On est passé au mode ASC car au niveau du FE les images sont ajoutées par ordre inversé.
                 * Aussi, on ajoutera d'abord le moins récent puis le plus récent au dessus de ce dernier Article.
                 * Ce changement récent suit celui de la méthode First(). Je n'avait pas reporté les changements au niveau de toutes les autres méthodes.
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] > $b['art_crea_tstamp']) ? -1 : 1;
//                    $t__ = $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
//                    var_dump($b['art_crea_tstamp'],$a['art_crea_tstamp'],$t__);
//                    $t__ = intval($b['art_crea_tstamp']) - intval($a['art_crea_tstamp']);
//                    var_dump(intval($b['art_crea_tstamp']),intval($a['art_crea_tstamp']),$t__);
//                    return $t__;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if (in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    
    private function GetOlderArt_Iml_Pod ($accid, $mode, $asp) {
//    private function GetOlderArt_Comy ($accid,$mode,$art_eid,$time) {
        //ArticleSPecs
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed.
         * Les Articles sont anterieurs.
         */
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                }
                /*
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                } 
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
                //*/
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        }
        /*
        $rel_datas = $comy_datas["rel"];
        if ( $rel_datas ) {
            foreach ($rel_datas as $v) {
                $temp[] = $v["id"];
            }
            $rel_list = implode("','", $temp);
        } else { 
            $rel_list = ""; 
        }
        //*/
        
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
//        $mine_datas = $comy_datas["own"];
        $mine_datas = [];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
//        $abo_datas = $comy_datas["abo"];
        $abo_datas = [];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 10 : 30;
//            $limit = ( $mode === "list" ) ? 2 : 2; //DEV, TEST, DEBUG
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $abo_articles = NULL;
            
            
            if ( !empty($rel_list_l12) && ( key_exists("_xl_12it", $asp) && $asp["_xl_12it"] ) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il était faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_12it"]["i"], ":tstamp" => $asp["_xl_12it"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }

            if ( !empty($rel_list_l2) && ( key_exists("_xl_2im", $asp) && $asp["_xl_2im"] ) ) {
                /*
                 * [NOTE 13-05-15] @BOR
                 * On ajoute les Articles de type IML pour les Relations de type D_FOLW.
                 * Les règles stipulent qu'on peut accéder à un Article en mode lecture si on se suit mutuellement.
                 * Plus tard, une option permettra à l'utilisateur de refuser cette diffusion.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get"; 
                $qparams_in = array(":art_eid" => $asp["_xl_2im"]["i"], ":tstamp" => $asp["_xl_2im"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            if ( !empty($rel_list_l3) && ( ( key_exists("_xl_3im", $asp) && $asp["_xl_3im"] ) | ( ( key_exists("_xl_3it", $asp) && $asp["_xl_3it"] ) ) ) ) {
                
                /*
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $t__ = $t__1 = $t__2 = [];
                if ( key_exists("_xl_3im", $asp) && count($asp["_xl_3im"]) ) {
                    $QO = new QUERY();
                    $qbody   = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_IML ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp <= :tstamp ";
                    $qbody  .= "AND art_is_sod = 1 "; //  [DEPUIS 07-04-16] 
                    $qbody  .= "AND :now <= `art_crea_tstamp`+(24*3600000)"; //  [DEPUIS 20-05-16]   
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(
                        ":art_eid"  => $asp["_xl_3im"]["i"], 
                        ":tstamp"   => $asp["_xl_3im"]["t"], 
                        ":now"      => round(microtime(TRUE)*1000),
                        ":limit"    => $limit
                    );
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__1 = $QO->execute($qparams_in);
                    
                    $t__ = ( $t__1 ) ? $t__1 : [];
                }
                /*
                if ( key_exists("_xl_3it", $asp) && count($asp["_xl_3it"]) ) {
                    $QO = new QUERY();
                    $qbody  = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_ITR ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp <= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
    //                var_dump($qbody);
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3it"]["i"], ":tstamp" => $asp["_xl_3it"]["t"], ":limit" => $limit);
    //                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__2 = $QO->execute($qparams_in);
                    
                   if ( $t__ && $t__2 ) {
                       $t__ = array_merge($t__1,$t__2);
                   } else if ( !$t__ || $t__2 ) {
                       $t__ = $t__2;
                   }
                }
                //*/
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( count($t__) ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            /*
            if ( !empty($rel_list) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $rel_articles = $QO->execute($qparams_in);
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( $rel_articles ) {
                    $final = $rel_articles;
                }
            }
            //*/
            
            if ( !empty($mine_list) && ( key_exists("_xl_mt", $asp) && !empty($asp["_xl_mt"]) ) ) {
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_mt"]["i"], ":tstamp" => $asp["_xl_mt"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date pivot.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $mine_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( count($final) && ( $mine_articles && is_array($mine_articles) && count($mine_articles) ) ) {
                    $final = array_merge($final,$mine_articles);
                } else if ( $mine_articles ) {
                    $final = $mine_articles;
                }
                //*/
            }
            
            if ( !empty($abo_list) && ( key_exists("_xl_st", $asp) && !empty($asp["_xl_st"]) ) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */

                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_st"]["i"], ":tstamp" => $asp["_xl_st"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 *
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":accid" => $accid, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $abo_articles = $QO->execute($qparams_in);
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
            //*/ 
            }
                 
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
//            var_dump(count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump(array_column($rel_l3_articles,'artid'),count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump($rel_l12_articles,$rel_l3_articles,$mine_articles,$abo_articles);
//            exit();
            
            if ( isset($final) && count($final) ) {
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 * 
                 * [NOTE 16-03-15] @author L.C.
                 * On est passé au mode ASC car au niveau du FE les images sont ajoutées par ordre inversé.
                 * Aussi, on ajoutera d'abord le moins récent puis le plus récent au dessus de ce dernier Article.
                 * Ce changement récent suit celui de la méthode First(). Je n'avait pas reporté les changements au niveau de toutes les autres méthodes.
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] > $b['art_crea_tstamp']) ? -1 : 1;
//                    $t__ = $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
//                    var_dump($b['art_crea_tstamp'],$a['art_crea_tstamp'],$t__);
//                    $t__ = intval($b['art_crea_tstamp']) - intval($a['art_crea_tstamp']);
//                    var_dump(intval($b['art_crea_tstamp']),intval($a['art_crea_tstamp']),$t__);
//                    return $t__;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if (in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    
    private function GetOlderArt_Itr ($accid, $mode, $asp) {
//    private function GetOlderArt_Comy ($accid,$mode,$art_eid,$time) {
        //ArticleSPecs
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed.
         * Les Articles sont anterieurs.
         */
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                } 
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        }
        /*
        $rel_datas = $comy_datas["rel"];
        if ( $rel_datas ) {
            foreach ($rel_datas as $v) {
                $temp[] = $v["id"];
            }
            $rel_list = implode("','", $temp);
        } else { 
            $rel_list = ""; 
        }
        //*/
        
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
        $mine_datas = $comy_datas["own"];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
        $abo_datas = $comy_datas["abo"];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 10 : 30;
//            $limit = ( $mode === "list" ) ? 2 : 2; //DEV, TEST, DEBUG
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $abo_articles = NULL;
            
            
            if ( !empty($rel_list_l12) && ( key_exists("_xl_12it", $asp) && $asp["_xl_12it"] ) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il était faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_12it"]["i"], ":tstamp" => $asp["_xl_12it"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }

            if ( !empty($rel_list_l2) && ( key_exists("_xl_2im", $asp) && $asp["_xl_2im"] ) ) {
                /*
                 * [NOTE 13-05-15] @BOR
                 * On ajoute les Articles de type IML pour les Relations de type D_FOLW.
                 * Les règles stipulent qu'on peut accéder à un Article en mode lecture si on se suit mutuellement.
                 * Plus tard, une option permettra à l'utilisateur de refuser cette diffusion.
                 */
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get"; 
                $qparams_in = array(":art_eid" => $asp["_xl_2im"]["i"], ":tstamp" => $asp["_xl_2im"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                //*/
            }
            
            if ( !empty($rel_list_l3) && ( ( key_exists("_xl_3im", $asp) && $asp["_xl_3im"] ) | ( ( key_exists("_xl_3it", $asp) && $asp["_xl_3it"] ) ) ) ) {
                
                /*
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $t__ = $t__1 = $t__2 = [];
                /*
                if ( key_exists("_xl_3im", $asp) && count($asp["_xl_3im"]) ) {
                    $QO = new QUERY();
                    $qbody   = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_IML ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp <= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3im"]["i"], ":tstamp" => $asp["_xl_3im"]["t"], ":limit" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__1 = $QO->execute($qparams_in);
                    
                    $t__ = ( $t__1 ) ? $t__1 : [];
                }
                //*/
                
                if ( key_exists("_xl_3it", $asp) && count($asp["_xl_3it"]) ) {
                    $QO = new QUERY();
                    $qbody  = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_ITR ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp <= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
    //                var_dump($qbody);
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3it"]["i"], ":tstamp" => $asp["_xl_3it"]["t"], ":limit" => $limit);
    //                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__2 = $QO->execute($qparams_in);
                    
                   if ( $t__ && $t__2 ) {
                       $t__ = array_merge($t__1,$t__2);
                   } else if ( !$t__ || $t__2 ) {
                       $t__ = $t__2;
                   }
                }
                
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( count($t__) ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            /*
            if ( !empty($rel_list) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $rel_articles = $QO->execute($qparams_in);
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( $rel_articles ) {
                    $final = $rel_articles;
                }
            }
            //*/
            
            if ( !empty($mine_list) && ( key_exists("_xl_mt", $asp) && !empty($asp["_xl_mt"]) ) ) {
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_mt"]["i"], ":tstamp" => $asp["_xl_mt"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date pivot.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $mine_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( count($final) && ( $mine_articles && is_array($mine_articles) && count($mine_articles) ) ) {
                    $final = array_merge($final,$mine_articles);
                } else if ( $mine_articles ) {
                    $final = $mine_articles;
                }
                //*/
            }
            
            if ( !empty($abo_list) && ( key_exists("_xl_st", $asp) && !empty($asp["_xl_st"]) ) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */

                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                /*
                 * [DEPUIS 13-05-15] @BOR
                 * @see $mine_list pour plus d'explications.
                 */
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                /* @see $mine_list pour plus d'explications.
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                //*/
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_st"]["i"], ":tstamp" => $asp["_xl_st"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 *
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp <= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":accid" => $accid, ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $abo_articles = $QO->execute($qparams_in);
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
            //*/ 
            }
                 
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
//            var_dump(count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump(array_column($rel_l3_articles,'artid'),count($rel_l12_articles),count($rel_l3_articles),count($mine_articles),count($abo_articles));
//            var_dump($rel_l12_articles,$rel_l3_articles,$mine_articles,$abo_articles);
//            exit();
            
            if ( isset($final) && count($final) ) {
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 * 
                 * [NOTE 16-03-15] @author L.C.
                 * On est passé au mode ASC car au niveau du FE les images sont ajoutées par ordre inversé.
                 * Aussi, on ajoutera d'abord le moins récent puis le plus récent au dessus de ce dernier Article.
                 * Ce changement récent suit celui de la méthode First(). Je n'avait pas reporté les changements au niveau de toutes les autres méthodes.
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] > $b['art_crea_tstamp']) ? -1 : 1;
//                    $t__ = $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
//                    var_dump($b['art_crea_tstamp'],$a['art_crea_tstamp'],$t__);
//                    $t__ = intval($b['art_crea_tstamp']) - intval($a['art_crea_tstamp']);
//                    var_dump(intval($b['art_crea_tstamp']),intval($a['art_crea_tstamp']),$t__);
//                    return $t__;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if (in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    /************************* NEWER ARTICLES SCOPE ****************************/
    
    private function GetNewerArt_Comy ($accid, $mode, $asp) {
//    private function GetNewerArt_Comy ($accid,$mode,$art_eid,$time) {
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed.
         * Les Articles sont anterieurs.
         */
        
        //TODO : Vérifier que les données en entrées ne sont pas 'undefined'
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                } 
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        }
        /*
        $rel_datas = $comy_datas["rel"];
        
        if ( $rel_datas ) {
            foreach ($rel_datas as $v) {
                $temp[] = $v["id"];
            }
            $rel_list = implode("','", $temp);
        } else {
            $rel_list = "";
        }
        //*/
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
        $mine_datas = $comy_datas["own"];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
        $abo_datas = $comy_datas["abo"];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 2 : 30; //DEV, TEST, DEBUG
//            $limit = ( $mode === "list" ) ? 10 : 30;
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $abo_articles = NULL;
            
            if ( !empty($rel_list_l12) && ( key_exists("_xl_12it", $asp) && $asp["_xl_12it"] ) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il était faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_12it"]["i"], ":tstamp" => $asp["_xl_12it"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }
            
            
            if ( !empty($rel_list_l2) && ( key_exists("_xl_2im", $asp) && $asp["_xl_2im"] ) ) {
                /*
                 * [NOTE 13-05-15] @BOR
                 * On ajoute les Articles de type IML pour les Relations de type D_FOLW.
                 * Les règles stipulent qu'on peut accéder à un Article en mode lecture si on se suit mutuellement.
                 * Plus tard, une option permettra à l'utilisateur de refuser cette diffusion.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get"; 
                $qparams_in = array(":art_eid" => $asp["_xl_2im"]["i"], ":tstamp" => $asp["_xl_2im"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
            if ( !empty($rel_list_l3) && ( ( key_exists("_xl_3im", $asp) && $asp["_xl_3im"] ) | ( ( key_exists("_xl_3it", $asp) && $asp["_xl_3it"] ) ) ) ) {
                
                /*
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $t__ = $t__1 = $t__2 = [];
                if ( key_exists("_xl_3im", $asp) && count($asp["_xl_3im"]) ) {
                    $QO = new QUERY();
                    $qbody   = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_IML ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp >= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array (
                        ":art_eid"  => $asp["_xl_3im"]["i"], 
                        ":tstamp"   => $asp["_xl_3im"]["t"], 
                        ":limit"    => $limit
                    );
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__1 = $QO->execute($qparams_in);
                    
                    $t__ = ( $t__1 ) ? $t__1 : [];
                }
                
                if ( key_exists("_xl_3it", $asp) && count($asp["_xl_3it"]) ) {
                    $QO = new QUERY();
                    $qbody  = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_ITR ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
//                    $qbody  .= "WHERE art_oid IN ('".$rel_list."') "; [DEPUIS 03-06-15] @BOR
                    /*
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_oid NOT IN ('".$rel_list_l12."') ";
                    //*/
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp >= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
    //                var_dump($qbody);
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3it"]["i"], ":tstamp" => $asp["_xl_3it"]["t"], ":limit" => $limit);
    //                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__2 = $QO->execute($qparams_in);
                    
                   if ( $t__ && $t__2 ) {
                       $t__ = array_merge($t__1,$t__2);
                   } else if ( !$t__ || $t__2 ) {
                       $t__ = $t__2;
                   }
                }
                
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( count($t__) ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            /*
            if ( !empty($rel_list) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $rel_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( $rel_articles ) {
                    $final = $rel_articles;
                }
            }
            //*/
                  
            
            if ( !empty($mine_list) && ( key_exists("_xl_mt", $asp) && !empty($asp["_xl_mt"]) ) ) {
//            if ( !empty($mine_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 * [13-05-15] @BOR
                 * On ne récupère pas les données dont l'auteur est en relation avec CU. En effet, il se peut que meme quand '_2it' et '_3it' ne donne rien, ...
                 * ... que les données prennent celles concernent des Relations. Cependant, il se peut que ces données aient déjà été affichées lors de FISRT.
                 * Pour éviter ce genre de bogue, on a opter pour une solution où les données "_mt" et "_st" ne prennent plus en compte les Relations.
                 * A ce stade, je considère qu'on aura pas de perte car les données en question se trouveront forcement au niveau de '_2it' et '_3it'.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_mt"]["i"], ":tstamp" => $asp["_xl_mt"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $time, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $mine_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$mine_articles);
//                exit();
                
                if ( count($final) && ( $mine_articles && is_array($mine_articles) && count($mine_articles) ) ) {
                    $final = array_merge($final,$mine_articles);
                } else if ( $mine_articles ) {
                    $final = $mine_articles;
                }
                //*/
            }
            
            if ( !empty($abo_list) && ( key_exists("_xl_st", $asp) && !empty($asp["_xl_st"]) ) ) {
//            if ( !empty($abo_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_st"]["i"], ":tstamp" => $asp["_xl_st"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $abo_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$abo_articles);
//                exit();
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
                //*/
            }
            
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
            if ( isset($final) && count($final) ) {
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] < $b['art_crea_tstamp']) ? -1 : 1;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if ( in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    private function GetNewerArt_Iml_Frd ($accid, $mode, $asp) {
//    private function GetNewerArt_Comy ($accid,$mode,$art_eid,$time) {
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed.
         * Les Articles sont anterieurs.
         */
        
        //TODO : Vérifier que les données en entrées ne sont pas 'undefined'
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                }
                /* //[DEPUIS 07-04-16]
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                } 
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
                //*/
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        }
        /*
        $rel_datas = $comy_datas["rel"];
        
        if ( $rel_datas ) {
            foreach ($rel_datas as $v) {
                $temp[] = $v["id"];
            }
            $rel_list = implode("','", $temp);
        } else {
            $rel_list = "";
        }
        //*/
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
//        $mine_datas = $comy_datas["own"]; //[DEPUIS 07-04-16]
        $mine_datas = [];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
//        $abo_datas = $comy_datas["abo"]; //[DEPUIS 07-04-16]
        $abo_datas = [];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 2 : 30; //DEV, TEST, DEBUG
//            $limit = ( $mode === "list" ) ? 10 : 30;
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $abo_articles = NULL;
            
            if ( !empty($rel_list_l12) && ( key_exists("_xl_12it", $asp) && $asp["_xl_12it"] ) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il était faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_12it"]["i"], ":tstamp" => $asp["_xl_12it"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }
            
            
            if ( !empty($rel_list_l2) && ( key_exists("_xl_2im", $asp) && $asp["_xl_2im"] ) ) {
                /*
                 * [NOTE 13-05-15] @BOR
                 *      On ajoute les Articles de type IML pour les Relations de type D_FOLW.
                 *      Les règles stipulent qu'on peut accéder à un Article en mode lecture si on se suit mutuellement.
                 *      Plus tard, une option permettra à l'utilisateur de refuser cette diffusion.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get"; 
                $qparams_in = array(":art_eid" => $asp["_xl_2im"]["i"], ":tstamp" => $asp["_xl_2im"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
            if ( !empty($rel_list_l3) && ( ( key_exists("_xl_3im", $asp) && $asp["_xl_3im"] ) | ( ( key_exists("_xl_3it", $asp) && $asp["_xl_3it"] ) ) ) ) {
                
                /*
                 * [NOTE 20-03-15] @BlackOwlRobot
                 *      Le code a été profondement refactorisé car il étaait faux.
                 *      Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 *      Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $t__ = $t__1 = $t__2 = [];
                if ( key_exists("_xl_3im", $asp) && count($asp["_xl_3im"]) ) {
                    $QO = new QUERY();
                    $qbody   = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_IML ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp >= :tstamp ";
                    $qbody  .= "AND art_is_sod = 0 "; //  [DEPUIS 07-04-16]     
                    $qbody  .= "AND art_is_hstd = 0 "; //  [DEPUIS 17-07-16]     
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(
                        ":art_eid"  => $asp["_xl_3im"]["i"], 
                        ":tstamp"   => $asp["_xl_3im"]["t"], 
                        ":limit"    => $limit
                    );
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__1 = $QO->execute($qparams_in);
                    
                    $t__ = ( $t__1 ) ? $t__1 : [];
                }
                
                /*
                if ( key_exists("_xl_3it", $asp) && count($asp["_xl_3it"]) ) {
                    $QO = new QUERY();
                    $qbody  = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_ITR ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
//                    $qbody  .= "WHERE art_oid IN ('".$rel_list."') "; [DEPUIS 03-06-15] @BOR
                    /*
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_oid NOT IN ('".$rel_list_l12."') ";
                    //*
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp >= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
    //                var_dump($qbody);
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3it"]["i"], ":tstamp" => $asp["_xl_3it"]["t"], ":limit" => $limit);
    //                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__2 = $QO->execute($qparams_in);
                    
                   if ( $t__ && $t__2 ) {
                       $t__ = array_merge($t__1,$t__2);
                   } else if ( !$t__ || $t__2 ) {
                       $t__ = $t__2;
                   }
                }
                //*/
                
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( count($t__) ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            /*
            if ( !empty($rel_list) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $rel_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( $rel_articles ) {
                    $final = $rel_articles;
                }
            }
            //*/
                  
            
            if ( !empty($mine_list) && ( key_exists("_xl_mt", $asp) && !empty($asp["_xl_mt"]) ) ) {
//            if ( !empty($mine_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 * [13-05-15] @BOR
                 * On ne récupère pas les données dont l'auteur est en relation avec CU. En effet, il se peut que meme quand '_2it' et '_3it' ne donne rien, ...
                 * ... que les données prennent celles concernent des Relations. Cependant, il se peut que ces données aient déjà été affichées lors de FISRT.
                 * Pour éviter ce genre de bogue, on a opter pour une solution où les données "_mt" et "_st" ne prennent plus en compte les Relations.
                 * A ce stade, je considère qu'on aura pas de perte car les données en question se trouveront forcement au niveau de '_2it' et '_3it'.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_mt"]["i"], ":tstamp" => $asp["_xl_mt"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $time, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $mine_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$mine_articles);
//                exit();
                
                if ( count($final) && ( $mine_articles && is_array($mine_articles) && count($mine_articles) ) ) {
                    $final = array_merge($final,$mine_articles);
                } else if ( $mine_articles ) {
                    $final = $mine_articles;
                }
                //*/
            }
            
            if ( !empty($abo_list) && ( key_exists("_xl_st", $asp) && !empty($asp["_xl_st"]) ) ) {
//            if ( !empty($abo_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_st"]["i"], ":tstamp" => $asp["_xl_st"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $abo_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$abo_articles);
//                exit();
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
                //*/
            }
            
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
            if ( isset($final) && count($final) ) {
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] < $b['art_crea_tstamp']) ? -1 : 1;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if ( in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    private function GetNewerArt_Iml_Pod ($accid, $mode, $asp) {
//    private function GetNewerArt_Comy ($accid,$mode,$art_eid,$time) {
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed.
         * Les Articles sont anterieurs.
         */
        
        //TODO : Vérifier que les données en entrées ne sont pas 'undefined'
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                }
                /*
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                } 
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
                //*/
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        }
        /*
        $rel_datas = $comy_datas["rel"];
        
        if ( $rel_datas ) {
            foreach ($rel_datas as $v) {
                $temp[] = $v["id"];
            }
            $rel_list = implode("','", $temp);
        } else {
            $rel_list = "";
        }
        //*/
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
//        $mine_datas = $comy_datas["own"];
        $mine_datas = [];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
//        $abo_datas = $comy_datas["abo"];
        $abo_datas = [];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 2 : 30; //DEV, TEST, DEBUG
//            $limit = ( $mode === "list" ) ? 10 : 30;
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $abo_articles = NULL;
            
            if ( !empty($rel_list_l12) && ( key_exists("_xl_12it", $asp) && $asp["_xl_12it"] ) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il était faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_12it"]["i"], ":tstamp" => $asp["_xl_12it"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }
            
            
            if ( !empty($rel_list_l2) && ( key_exists("_xl_2im", $asp) && $asp["_xl_2im"] ) ) {
                /*
                 * [NOTE 13-05-15] @BOR
                 * On ajoute les Articles de type IML pour les Relations de type D_FOLW.
                 * Les règles stipulent qu'on peut accéder à un Article en mode lecture si on se suit mutuellement.
                 * Plus tard, une option permettra à l'utilisateur de refuser cette diffusion.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get"; 
                $qparams_in = array(":art_eid" => $asp["_xl_2im"]["i"], ":tstamp" => $asp["_xl_2im"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            
            if ( !empty($rel_list_l3) && ( key_exists("_xl_3im_pod", $asp) && $asp["_xl_3im_pod"] ) ) {
                
                /*
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $t__ = $t__1 = $t__2 = [];
                if ( key_exists("_xl_3im_pod", $asp) && count($asp["_xl_3im_pod"]) ) {
                    $QO = new QUERY();
                    $qbody   = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_IML ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp >= :tstamp ";
                    $qbody  .= "AND art_is_sod = 1 "; //  [DEPUIS 07-04-16] 
                    $qbody  .= "AND :now <= `art_crea_tstamp`+(24*3600000)"; //  [DEPUIS 20-05-16]   
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(
                        ":art_eid"  => $asp["_xl_3im_pod"]["i"], 
                        ":tstamp"   => $asp["_xl_3im_pod"]["t"], 
                        ":now"      => round(microtime(TRUE)*1000),
                        ":limit"    => $limit
                    );
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__1 = $QO->execute($qparams_in);
                    
                    $t__ = ( $t__1 ) ? $t__1 : [];
                }
                
                /*
                if ( key_exists("_xl_3it", $asp) && count($asp["_xl_3it"]) ) {
                    $QO = new QUERY();
                    $qbody  = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_ITR ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
//                    $qbody  .= "WHERE art_oid IN ('".$rel_list."') "; [DEPUIS 03-06-15] @BOR
                    /*
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_oid NOT IN ('".$rel_list_l12."') ";
                    //*
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp >= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
    //                var_dump($qbody);
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3it"]["i"], ":tstamp" => $asp["_xl_3it"]["t"], ":limit" => $limit);
    //                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__2 = $QO->execute($qparams_in);
                    
                   if ( $t__ && $t__2 ) {
                       $t__ = array_merge($t__1,$t__2);
                   } else if ( !$t__ || $t__2 ) {
                       $t__ = $t__2;
                   }
                }
                //*/
                
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( count($t__) ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_3im_pod";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            /*
            if ( !empty($rel_list) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $rel_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( $rel_articles ) {
                    $final = $rel_articles;
                }
            }
            //*/
                  
            
            if ( !empty($mine_list) && ( key_exists("_xl_mt", $asp) && !empty($asp["_xl_mt"]) ) ) {
//            if ( !empty($mine_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 * [13-05-15] @BOR
                 * On ne récupère pas les données dont l'auteur est en relation avec CU. En effet, il se peut que meme quand '_2it' et '_3it' ne donne rien, ...
                 * ... que les données prennent celles concernent des Relations. Cependant, il se peut que ces données aient déjà été affichées lors de FISRT.
                 * Pour éviter ce genre de bogue, on a opter pour une solution où les données "_mt" et "_st" ne prennent plus en compte les Relations.
                 * A ce stade, je considère qu'on aura pas de perte car les données en question se trouveront forcement au niveau de '_2it' et '_3it'.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_mt"]["i"], ":tstamp" => $asp["_xl_mt"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $time, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $mine_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$mine_articles);
//                exit();
                
                if ( count($final) && ( $mine_articles && is_array($mine_articles) && count($mine_articles) ) ) {
                    $final = array_merge($final,$mine_articles);
                } else if ( $mine_articles ) {
                    $final = $mine_articles;
                }
                //*/
            }
            
            if ( !empty($abo_list) && ( key_exists("_xl_st", $asp) && !empty($asp["_xl_st"]) ) ) {
//            if ( !empty($abo_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_st"]["i"], ":tstamp" => $asp["_xl_st"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $abo_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$abo_articles);
//                exit();
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
                //*/
            }
            
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
            if ( isset($final) && count($final) ) {
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] < $b['art_crea_tstamp']) ? -1 : 1;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if ( in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
    
    private function GetNewerArt_Itr ($accid, $mode, $asp) {
//    private function GetNewerArt_Comy ($accid,$mode,$art_eid,$time) {
        /*
         * Permet de récupérer un panel d'Articles pour les afficher dans NewsFeed.
         * Les Articles sont anterieurs.
         */
        
        //TODO : Vérifier que les données en entrées ne sont pas 'undefined'
        
        $PA = new PROD_ACC();
        $u_tab = $PA->exists_with_id($accid, TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab ) ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * On récupère tous les Articles qui ont été ajoutés après la date NOW.
         * Cela dépend aussi du mode de vue : liste ou mosaïque.
         * Selon le mode, on récupère un nombre donné d'Articles.
         */
        
        //On récupère la liste des amis et des comptes qui ont une relation de type "D_FOLM" et "S_FOLW" où l'utilisateur actuel est acteur.
        $comy_datas = $PA->onread_acquiere_my_community($accid);
        
        //On crée une chaine listant les comptes en relation
        $rel_datas = $comy_datas["rel"];
        $t__l = $t__l12 = $t__l2 = $t__l3 = $rel_list_l12 = $rel_list_l3 = [];
        if ( $rel_datas ) {
//            var_dump($rel_datas);
//            exit();
            foreach ($rel_datas as $v) {
                $t__l[] = $v["id"];
                if ( intval($v["rel_sts"]) === 3 ) {
                    $t__l3[] = $v["id"];
                } else if ( intval($v["rel_sts"]) === 1 || intval($v["rel_sts"]) === 2 ) {
                    $t__l12[] = $v["id"];
                } 
                if ( intval($v["rel_sts"]) === 2 ) {
                    $t__l2[] = $v["id"];
                }
            }
            
            $rel_list = implode("','", $t__l);
            $rel_list_l12 = ( count($t__l12) ) ? implode("','", $t__l12) : NULL;
            $rel_list_l2 = ( count($t__l2) ) ? implode("','", $t__l2) : NULL;
            $rel_list_l3 = ( count($t__l3) ) ? implode("','", $t__l3) : NULL;
//            $rel_list = implode("','", $temp); //A retirer
        }
        /*
        $rel_datas = $comy_datas["rel"];
        
        if ( $rel_datas ) {
            foreach ($rel_datas as $v) {
                $temp[] = $v["id"];
            }
            $rel_list = implode("','", $temp);
        } else {
            $rel_list = "";
        }
        //*/
        //On crée une chaine listant les Tendances appartenant à l'utisateur passé en paramètre
        $mine_datas = $comy_datas["own"];
        $mine_list = ( $mine_datas ) ? implode("','", $mine_datas) : "";
        
        //On crée une chaine listant les Tendances suives
        $abo_datas = $comy_datas["abo"];
        $abo_list = ( $abo_datas ) ? implode("','", $abo_datas) : "";
        
        //LITTERALEMENT : Si l'utilisateur a une communauté
        if ( !empty($rel_list_l12) | !empty($rel_list_l2) | !empty($rel_list_l3) | !empty($abo_list) | !empty($mine_list) ) {
//        if ( !empty($rel_list) | !empty($abo_list) | !empty($mine_list) ) {
            
            //A partir du type de visionnage, 
            $limit = ( $mode === "list" ) ? 2 : 30; //DEV, TEST, DEBUG
//            $limit = ( $mode === "list" ) ? 10 : 30;
            $final = $rel_l12_articles = $rel_l2_articles = $rel_l3_articles = $mine_articles = $abo_articles = NULL;
//            $final = $rel_articles = $abo_articles = NULL;
            
            if ( !empty($rel_list_l12) && ( key_exists("_xl_12it", $asp) && $asp["_xl_12it"] ) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 * 
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il était faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles ITR des Relations S_FOLW & D_FOLW.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l12."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_12it"]["i"], ":tstamp" => $asp["_xl_12it"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_12it";
                    }
                    $final = $rel_l12_articles = $t__;
                }
            }
            
            
            if ( !empty($rel_list_l2) && ( key_exists("_xl_2im", $asp) && $asp["_xl_2im"] ) ) {
                /*
                 * [NOTE 13-05-15] @BOR
                 * On ajoute les Articles de type IML pour les Relations de type D_FOLW.
                 * Les règles stipulent qu'on peut accéder à un Article en mode lecture si on se suit mutuellement.
                 * Plus tard, une option permettra à l'utilisateur de refuser cette diffusion.
                 */
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list_l2."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get"; 
                $qparams_in = array(":art_eid" => $asp["_xl_2im"]["i"], ":tstamp" => $asp["_xl_2im"]["t"], ":limit" => $limit);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        
                $t__ = $QO->execute($qparams_in);
                if ( $t__ ) {
                    //On ajoute le type 
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_2im";
                    }
                    $rel_l2_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                //*/
            }
            
            
            if ( !empty($rel_list_l3) && ( ( key_exists("_xl_3im", $asp) && $asp["_xl_3im"] ) | ( ( key_exists("_xl_3it", $asp) && $asp["_xl_3it"] ) ) ) ) {
                
                /*
                 * [NOTE 20-03-15] @BlackOwlRobot
                 * Le code a été profondement refactorisé car il étaait faux.
                 * Il a été décomposé en 2 entités : (1) Relations de type L12 (1) Relations de type L3
                 * Dans cette partie, nous récupérons les Articles IML & ITR des Relations FRIEND.
                 */
                $t__ = $t__1 = $t__2 = [];
                /*
                if ( key_exists("_xl_3im", $asp) && count($asp["_xl_3im"]) ) {
                    $QO = new QUERY();
                    $qbody   = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_IML ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp >= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3im"]["i"], ":tstamp" => $asp["_xl_3im"]["t"], ":limit" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__1 = $QO->execute($qparams_in);
                    
                    $t__ = ( $t__1 ) ? $t__1 : [];
                }
                //*/
                
                if ( key_exists("_xl_3it", $asp) && count($asp["_xl_3it"]) ) {
                    $QO = new QUERY();
                    $qbody  = "SELECT * ";
                    $qbody  .= "FROM VM_Articles_ITR ";
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
//                    $qbody  .= "WHERE art_oid IN ('".$rel_list."') "; [DEPUIS 03-06-15] @BOR
                    /*
                    $qbody  .= "WHERE art_oid IN ('".$rel_list_l3."') ";
                    $qbody  .= "AND art_oid NOT IN ('".$rel_list_l12."') ";
                    //*/
                    $qbody  .= "AND art_eid != :art_eid ";
                    $qbody  .= "AND art_crea_tstamp >= :tstamp ";
                    $qbody  .= "ORDER BY art_crea_tstamp DESC ";
                    $qbody  .= "LIMIT :limit; ";
    //                var_dump($qbody);
                    $qdbname = $this->default_dbname;
                    $qtype = "get";
                    $qparams_in = array(":art_eid" => $asp["_xl_3it"]["i"], ":tstamp" => $asp["_xl_3it"]["t"], ":limit" => $limit);
    //                $qparams_in = array(":tstamp1" => $now, ":tstamp2" => $now, ":limit1" => $limit, ":limit2" => $limit);
                    $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                    $t__2 = $QO->execute($qparams_in);
                    
                   if ( $t__ && $t__2 ) {
                       $t__ = array_merge($t__1,$t__2);
                   } else if ( !$t__ || $t__2 ) {
                       $t__ = $t__2;
                   }
                }
                
//                var_dump("TOTO => ",__LINE__,$t__);
//                exit();
                
                if ( count($t__) ) {
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = ( key_exists("art_trd_eid", $r) && $r["art_trd_eid"] ) ? "_xl_3it" : "_xl_3im";
                    }
                    $rel_l3_articles = $t__;
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
            }
            
            /*
            if ( !empty($rel_list) ) {
                
                /*
                 * On récupère tous les Articles dont les propriétaires font partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * J'ai décidé d'utiliser une construction volatile à cause de la contrainte posée par la necessité de la liste.
                 * En effet, les moyens actuels ne permettent pas d'utiliser une liste avec des éléments indivuelles.
                 * Dans ce cas, on concatène les éléments dans la requete. Aussi, le moteur ne considérera pas qu'il s'agit d'une chaine 
                 * ce qui rendrait la requete fausse.
                 *
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_IML ";
                $qbody .= "WHERE art_oid IN ('".$rel_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "ORDER BY art_crea_tstamp DESC; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $rel_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$art_eid,$rel_articles);
//                exit();
                
                if ( $rel_articles ) {
                    $final = $rel_articles;
                }
            }
            //*/
                  
            
            if ( !empty($mine_list) && ( key_exists("_xl_mt", $asp) && !empty($asp["_xl_mt"]) ) ) {
//            if ( !empty($mine_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * On ne récupère que les données où l'utilisateur n'est pas l'auteur
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 * [13-05-15] @BOR
                 * On ne récupère pas les données dont l'auteur est en relation avec CU. En effet, il se peut que meme quand '_2it' et '_3it' ne donne rien, ...
                 * ... que les données prennent celles concernent des Relations. Cependant, il se peut que ces données aient déjà été affichées lors de FISRT.
                 * Pour éviter ce genre de bogue, on a opter pour une solution où les données "_mt" et "_st" ne prennent plus en compte les Relations.
                 * A ce stade, je considère qu'on aura pas de perte car les données en question se trouveront forcement au niveau de '_2it' et '_3it'.
                 */
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_mt"]["i"], ":tstamp" => $asp["_xl_mt"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $mine_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $mine_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_mt";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$mine_list."') ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":tstamp" => $time, ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $mine_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$mine_articles);
//                exit();
                
                if ( count($final) && ( $mine_articles && is_array($mine_articles) && count($mine_articles) ) ) {
                    $final = array_merge($final,$mine_articles);
                } else if ( $mine_articles ) {
                    $final = $mine_articles;
                }
                //*/
            }
            
            if ( !empty($abo_list) && ( key_exists("_xl_st", $asp) && !empty($asp["_xl_st"]) ) ) {
//            if ( !empty($abo_list) ) {
                
                /*
                 * On récupère tous les Articles de type ITR dont la Tendance liée fait partie de la liste fournie.
                 * Les données sont récupérées dans la table VM d'Articles suivant la date actuelle.
                 * 
                 * (Pour les explications au sujet de l'utilisation de la méthode volatile, voir requete ci-dessus)
                 */
                
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                if ( $final && count($final) ) {
                    //On retire les Articles déjà présents dans la liste des Articles 
                    $t__ =  implode("','", array_column($final,'artid'));
                    $qbody .= "AND artid NOT IN ('".$t__."') ";
                }
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                if ( $rel_list && strlen($rel_list) ) {
                    $qbody .= "AND art_oid NOT IN ('".$rel_list."') ";
                }
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC ";
                $qbody .= "LIMIT :limit; ";
//                var_dump($qbody);
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $asp["_xl_st"]["i"], ":tstamp" => $asp["_xl_st"]["t"], ":limit" => $limit, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $t__ = $QO->execute($qparams_in);
//                $abo_articles = $QO->execute($qparams_in);
                
                if ( $t__ && is_array($t__) && count($t__) ) {
                    $abo_articles = $t__;
                    //On ajoute le type en fonction du type d'Article
                    foreach ($t__ as &$r) {
                        $r["aba"] = "_xl_st";
                    }
                    $final = ( count($final) ) ? array_merge($final,$t__) : $t__;
                }
                
                /*
                $QO = new QUERY();
                $qbody = "SELECT * ";
                $qbody .= "FROM VM_Articles_ITR ";
                $qbody .= "WHERE art_trid IN ('".$abo_list."') ";
                $qbody .= "AND art_eid != :art_eid ";
                $qbody .= "AND art_crea_tstamp >= :tstamp ";
                $qbody .= "AND art_oid != :accid ";
                $qbody .= "ORDER BY art_crea_tstamp DESC; ";
                $qdbname = $this->default_dbname;
                $qtype = "get";
                $qparams_in = array(":art_eid" => $art_eid, ":tstamp" => $time, ":accid" => $accid);
                $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                
                $abo_articles = $QO->execute($qparams_in);
                
//                var_dump(__LINE__,$abo_articles);
//                exit();
                
                if ( count($final) && ( $abo_articles && is_array($abo_articles) && count($abo_articles) ) ) {
                    $final = array_merge($final,$abo_articles);
                } else if ( $abo_articles ) {
                    $final = $abo_articles;
                }
                //*/
            }
            
            /*
            if ( ( isset($rel_articles) && count($rel_articles) ) && ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = array_merge($rel_articles,$abo_articles);
            } else if ( ( isset($rel_articles) && count($rel_articles) ) || ( isset($abo_articles) && count($abo_articles) ) ) {
                $final = ( $rel_articles ) ? $rel_articles : $abo_articles ;
            } 
            //*/
            
            if ( isset($final) && count($final) ) {
                /*
                 * Il s'agit maintenant de :
                 *  -> Fournir un tableau avec toutes les données triées par ordre DESC via la date
                 */
//                var_dump("AVANT => ",$final);
                usort($final, function($a,$b) {
                    if ($a['art_crea_tstamp'] === $b['art_crea_tstamp']) {
                        return 0;
                    }
                    return ($a['art_crea_tstamp'] < $b['art_crea_tstamp']) ? -1 : 1;
                });  
//                var_dump("APRES => ",$final);
//                exit();
                
                /*
                 * [DEPUIS 09-05-16]
                 *      On ne conserve que la part des données dans la LIMIT 
                 */
                $final = array_slice($final,0,$limit);
                
                $n_final = $n_rel_datas = NULL;
                //On fait passer les données au control qualité
                foreach ($final as $a) {
                    //On vérifie si l'utilisateur n'est pas todel
                    if ( intval($a["art_todel"]) === 1 | ( is_array($a) && key_exists("art_state", $a) && !empty($a["art_state"]) && intval($a["art_state"]) !== 6 ) ) {
                        continue;
//                        unset($a); //[DEPUIS 08-06-15] @BOR
                    } else {
                        
                        //On s'assure que seuls les Comptes qui ont au moins un Article dans la liste sont sélectionnés
//                        var_dump(array_keys($rel_datas));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)));
//                        var_dump(!isset($n_rel_datas));
//                        var_dump(!key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)));
//                        var_dump(key_exists(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) | !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas))));
//                        exit();
//                        var_dump($a["art_oid"],array_keys($rel_datas),array_keys($n_rel_datas));
//                        var_dump($rel_datas);
                        
                        if ( in_array(intval($a["art_oid"]), array_keys($rel_datas)) && ( !isset($n_rel_datas) || !key_exists(intval($a["art_oid"]), array_keys($n_rel_datas)) ) ) {
                            
                            $n_rel_datas[$a["art_oeid"]] = [
                                "i" => $a["art_oeid"],
                                "r" => $rel_datas[$a["art_oid"]]["rel_sts"],
                            ];
                        }
                        
                        //On ajoute le lien vers le compte 
                        $a["art_ohref"] = "/@".$a["art_opsd"];
                        
                        unset($a["artid"]);
                        unset($a["art_picid"]);
                        unset($a["art_locip"]);
                        unset($a["art_oid"]);
                        unset($a["art_ogid"]);
                        unset($a["art_oppicid"]);
                        unset($a["art_todel"]);
                        
                        //Dans le cas des Articles de type TREND
                        if ( key_exists("art_trid", $a) ) {
                            
                            //On va constuire l'adresse de la Tendance
                            $TRD = new TREND();
                            $a["art_trd_href"] = $TRD->on_read_build_trdhref($a["art_trd_eid"], $a["art_trd_title_href"]);
                            
                            //On détruit les données inutiles
                            unset($a["art_trid"]);
                            unset($a["art_trd_grat"]);
                            unset($a["art_trd_title_href"]);
                        }
                        $a["art_time"] = $a["art_crea_tstamp"];
                        unset($a["art_crea_tstamp"]);
                    }
                    $n_final[] = $a;
                    
                }
                
                //On renvoie la liste des Articles et la liste des relations
//                var_dump($n_rel_datas);
                //as => ArticleS
                //rs => RelationS
                $r = [
                    "as" => $n_final, 
                    "rs" => $n_rel_datas
                ];
                
                return $r;
            }
            
        }
        
    }
}
