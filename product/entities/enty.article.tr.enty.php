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
class ARTICLE_TR extends ARTICLE {
    
    private $trartid;
    
    //DONNÉES SUR LA TENDANCE
    private $trid;
    private $trd_eid;
    private $trd_title;
    private $trd_desc;
    private $trd_title_href;
    private $trd_href;
    private $art_trd_catgid;
    private $art_trd_is_public;
    private $art_trd_grat;
    
    //Le nombre d'ARTICLES de l'auteur de cet ARTICLE dans la TENDANCE liée.
    private $ocontrb;
    
    //DONNÉES SUR LE PROPRIETAIRE DE LA TENDANCE
    private $art_trd_oid;
    private $art_trd_oeid;
    private $art_trd_ogid;
    private $art_trd_ofn;
    private $art_trd_opsd;
    private $art_trd_otdel;
    
    //Utiliser dans certains cas. Notamment lorsqu'il s'agit d'une tentative de chargement d'un Article mais quand il n'est pas TR. ON sauvegarde quand même les données de l'Article.
    private $art_loads;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /*
    * [NOTE 18-08-14] 
    *   Pour permettre à la classe mère et fille d'utiliser leur exists() respectif, j'ai changé le nom de la méthode en child_exixts().
    * [NOTE 20-04-15] @BOR
    *   On ajoute un paramètre OPTIONS qui permet d'ajouter des Options, notamment de ne récupérer qu'une partie des données :
    *      -> BA_ART (BreakAt_ARTicle) : Arrêter le processus à la vérification de l'existence de l'Article.
    *      -> BA_ART_TO (BreakAt_ARTicle_TrendOption) : Arreter le processus après l'opération de vérification de la Nature TREND de l'Article
    */
    public function child_exists($arg, $_OPTIONS = NULL) {
//    public function child_exists($arg, $urqid = NULL) {
        //On vérifie si on a bien un ARTICLE de type TR qui a artid fourni
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$arg]);
        
        $art_eid = $art_loads = NULL;
        //Déclencher une exception personnalisée si on ne recoit pas la valeur ["artid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->art_eid) ){
                return; 
            } else {
                $art_eid = $this->art_eid;
            }
        } else {
            $art_eid = $arg;
        }
        
        //On va récupérer les données sur l'ARTICLE s'il existe
        $article = (new ARTICLE)->exists($art_eid);
        if (! $article ) {
            return FALSE;
        } else if ( !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("BA_ART", $_OPTIONS) ) {
            return $article;
        } else {
            $art_loads = $article;
        }
        
        $QO = new QUERY("qryl4trartn2");
        $params = array( ':trart_artid' => $article["artid"]);
        $datas = $QO->execute($params);
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$datas);
//        exit();
        
        if (! $datas ) {
            $this->art_loads = $art_loads;
            return -1;
        } else {
            //On transforme artid en art_eid
//            $QO = new QUERY("qryl4artn9");
//            $params = array( ':artid' => $artid);
//            $art_eid = $QO->execute($params)[0]['art_eid'];
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $article,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//            exit();
            
            /*
             * [NOTE 08-10-14] @author L.C.
             * 
             */
            
            $datas = $datas[0];
            //On init les valeurs de ART_TR
            $article["trartid"] = $datas["trartid"];
            $article["trid"] = $datas["trid"];
            $article["trd_eid"] = $datas["trd_eid"];
            $article["trd_title"] = $datas["trd_title"];
            $article["trd_desc"] = $datas["trd_desc"];
            $article["trd_title_href"] = $datas["trd_title_href"];
            /*
             * [NOTE 14-12-14] @author L.C.
             * J'ai rajouté les suffixes "art_" pour qu'il y ait une concordance avec les données renvoyées par "on_read". 
             * De plus, le risque de bogues est faible car la méthode est inutilisée à cette date.
             */
            $article["art_trd_catgid"] = $datas["trd_catgid"];
            $article["art_trd_is_public"] = $datas["trd_is_public"];
            $article["art_trd_grat"] = $datas["trd_grat"];
            $article["art_trd_date_tstamp"] = $datas["trd_date_tstamp"];
            //DONNEES SUR LE PROPRIETAIRE DE LA TENDANCE
            $article["art_trd_oid"] = $datas["pdaccid"];
            $article["art_trd_oeid"] = $datas["pdacc_eid"];
            $article["art_trd_ogid"] = $datas["pdacc_gid"];
            $article["art_trd_ofn"] = $datas["pdacc_ufn"];
            $article["art_trd_opsd"] = $datas["pdacc_upsd"];
            $article["art_trd_otdel"] = $datas["pdacc_todelete"];
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $article,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//            exit();
            if ( !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("BA_ART_TO", $_OPTIONS) ) {
                return $article;
            } 
        
            //On récupère le nombre de contributions de l'utliisateur pour la Tendance liée.
            $QO = new QUERY("qryl4trartn3");
            $params = array(':art_accid' => $article["art_accid"], ':trart_trid' => $article["trid"]);
            $datas = $QO->execute($params)[0];

            $article["ocontrb"] = $datas["ucontb"];
            
            //On ajoute trd_href
            $TR = new TREND();
            $article["trd_href"] = $TR->on_read_build_trdhref($article["trd_eid"], $article["trd_title"], $urqid);
            
            return $article;
        }
        
    }
    
    /*
    * [NOTE 18-08-14] 
    * Pour permettre à la classe mère et fille d'utiliser leur exists() respectif, j'ai changé le nom de la méthode en child_exixts().
    * [NOTE 20-04-15] @BOR
    *   On ajoute un paramètre OPTIONS qui permet d'ajouter des Options, notamment de ne récupérer qu'une partie des données :
    *      -> BA_ART (BreakAt_ARTicle) : Arrêter le processus à la vérification de l'existence de l'Article.
    *      -> BA_ART_TO (BreakAt_ARTicle_TrendOption) : Arreter le processus après l'opération de vérification de la Nature TREND de l'Article
    */
    public function child_exists_with_id($arg, $_OPTIONS = NULL) {
//    public function child_exists_with_id($arg, $urqid = NULL) {
        //On vérifie si on a bien un ARTICLE de type TR qui a artid fourni
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$arg]);
        
        $artid = $art_loads = NULL;
        //Déclencher une exception personnalisée si on ne recoit pas la valeur ["artid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->artid) ){
                return; 
            } else {
                $artid = $this->artid;
            }
        } else {
            $artid = $arg;
        }
        
        //On va récupérer les données sur l'ARTICLE s'il existe
        $article = (new ARTICLE)->exists_with_id($artid);
        if (! $article ) {
            return FALSE;
        } else if ( !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("BA_ART", $_OPTIONS) ) {
            return $article;
        } else {
            $art_loads = $article;
        }
        
        $QO = new QUERY("qryl4trartn2");
        $params = array( ':trart_artid' => $artid);
        $datas = $QO->execute($params);
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
        if (! $datas ) {
            $this->art_loads = $art_loads;
            return -1;
        } else {
            //On transforme artid en art_eid
//            $QO = new QUERY("qryl4artn9");
//            $params = array( ':artid' => $artid);
//            $art_eid = $QO->execute($params)[0]['art_eid'];
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $article,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//            exit();
            
            /*
             * [NOTE 08-10-14] @author L.C.
             * 
             */
            
            $datas = $datas[0];
            //On init les valeurs de ART_TR
            $article["trartid"] = $datas["trartid"];
            $article["trid"] = $datas["trid"];
            $article["trd_eid"] = $datas["trd_eid"];
            $article["trd_title"] = $datas["trd_title"];
            $article["trd_desc"] = $datas["trd_desc"];
            $article["trd_title_href"] = $datas["trd_title_href"];
            /*
             * [NOTE 14-12-14] @author L.C.
             * J'ai rajouté les suffixes "art_" pour qu'il y ait une concordance avec les données renvoyées par "on_read". 
             * De plus, le risque de bogues est faible car la méthode est inutilisée à cette date.
             */
            $article["art_trd_catgid"] = $datas["trd_catgid"];
            $article["art_trd_is_public"] = $datas["trd_is_public"];
            $article["art_trd_grat"] = $datas["trd_grat"];
            $article["art_trd_date_tstamp"] = $datas["trd_date_tstamp"];
            //DONNEES SUR LE PROPRIETAIRE DE LA TENDANCE
            $article["art_trd_oid"] = $datas["pdaccid"];
            $article["art_trd_oeid"] = $datas["pdacc_eid"];
            $article["art_trd_ogid"] = $datas["pdacc_gid"];
            $article["art_trd_ofn"] = $datas["pdacc_ufn"];
            $article["art_trd_opsd"] = $datas["pdacc_upsd"];
            $article["art_trd_otdel"] = $datas["pdacc_todelete"];
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $article,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//            exit();
            
            if ( !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("BA_ART_TO", $_OPTIONS) ) {
                return $article;
            } 
            
            //On récupère le nombre de contributions de l'utliisateur pour la Tendance liée.
            $QO = new QUERY("qryl4trartn3");
            $params = array(':art_accid' => $article["art_accid"], ':trart_trid' => $article["trid"]);
            $datas = $QO->execute($params)[0];

            $article["ocontrb"] = $datas["ucontb"];
            
            //On ajoute trd_href
            $TR = new TREND();
            $article["trd_href"] = $TR->on_read_build_trdhref($article["trd_eid"], $article["trd_title"], $urqid);
            
            return $article;
        }
        
    }

    public function on_create ($args, $cuid, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //NEEDED BY ARTICLE
        //["accid","acc_eid","art_desc","art_locip","pdpic_fn","art_pdpic_string"]
        
        $trd_eid = $trid = NULL;
        if (! ( !empty($args) && is_array($args) && key_exists("trd_eid", $args) && !empty($args["trd_eid"]) ) ) 
        {
            //On essaye avec trid
            if ( key_exists("trid", $args) && !empty($args["trid"]) && !is_array($args["trid"]) ) {
                $trid = $args["trid"];
            } else if ( !empty($this->trid) ) {
                $trid = $this->trid;
            } else {
                if ( empty($this->trd_eid) ) {
                    $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
                } else {
                    $trd_eid = $this->trd_eid;
                }
            }
            
        } else { 
            $trd_eid = $args["trd_eid"]; 
        }
        
        $TR = new TREND();
        if ( empty($trd_eid) ) {
            //On convertit en eid
            $trd_eid = $TR->get_trdeid_from_trid($trid, $std_err_enabled);
            
            //On ne vérifie pas si $std_err_enabled. Sinon on ne serait jamais arriver ici.
            if ( "__ERR_VOL_TREND_GONE" === $trd_eid ) { 
                return $trd_eid;
            }
        }
        
        //Necessaire pour récupérer des données de la Tendance
        $trend_infos = $TR->exists($trd_eid);
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$args,$cuid,$trend_infos],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit(); 
        
        if (! $trend_infos ) {
            if ( $std_err_enabled ) {
                $this->signalError ("err_user_l4trdn3", __FUNCTION__, __LINE__);
            } else {
                return "__ERR_VOL_TREND_GONE";
            }
        }
        
        //* On vérifie que l'Utilisateur a le droit d'ajouter dans la Tendance *//
        $continue = $this->on_create_create_authorized ($trd_eid, $cuid, $std_err_enabled);
        if ( "__ERR_VOL_TREND_GONE" === $continue ) {
            return "__ERR_VOL_TREND_GONE";
        } else if (! $continue ) {
            return "__ERR_VOL_TREND_IS_PRIVATE";
        }
        
        //On initialise les propriétés de la classe
        $this->all_properties["trid"] = $tr_loads["trid"] = $this->trid = $trend_infos["trid"];
        $this->all_properties["trd_eid"] = $tr_loads["trd_eid"] = $this->trd_eid = $trend_infos["trd_eid"];
        $this->all_properties["trd_title"] = $tr_loads["trd_title"] = $this->trd_title = htmlentities($trend_infos["trd_title"]);
        $this->all_properties["trd_title_href"] = $tr_loads["trd_title_href"] = $this->trd_title_href = $trend_infos["trd_title_href"];
        
        /*
         * [DEPUIS 14-11-15] @aurhor BOR
         *      On indique qu'il s'agit d'un Article de type TENDANCE.
         */
        $args["aistr"] = TRUE;
        $art_loads = (new ARTICLE)->on_create_entity($args);
        
        //DEBUG
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$art_loads],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit(); 
        
//        if ( !$art_loads || ( is_array($art_loads) && ( !key_exists("artid", $art_loads) || empty($art_loads["artid"]) ) ) ) {
        if (! ( isset($art_loads) && is_array($art_loads) && count($art_loads) && key_exists("artid", $art_loads) ) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$art_loads/*, $args*/],'v_d');
            $this->signalError ("err_user_l4trartn1", __FUNCTION__, __LINE__, TRUE);
        }
        
        $artid = $art_loads["artid"];
        
        $final_loads = array_merge($art_loads,$tr_loads);
        
        $saving = [
            "artid" => $artid,
            "trid"  => $this->trid,
        ];
        
        //On va créer une occurence dans la base de données
        $this->all_properties["trartid"] = $this->trartid = $this->write_new($saving);
        
        return $final_loads;
        
    }
    
    public function on_delete ($args) {
        //TODO
    }

    /**
     * Renvoie NULL si art_eid n'est pas fourni
     * Renvoie 0 si aucun ARTICLE n'existe avec cet art_eid
     * Renvoie -1 si un ARTICLE existe mais qu'il n'est pas de type ARTICLE_TR
     * 
     * @param type $args
     * @return type
     */
    public function on_read ($args, $urqid = FALSE) {
//        $time_start = round(microtime(TRUE)*1000);
                
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
         * [NOTE 18-08-14] ici on utilise parent pour pouvoir instancier ARTICLE.
         * Cependant, cela entrainera que la lméthode exists qui est sélectionnée est celle de ART_TR.
         * Aussi, j'ai changé son nom en child_exists().
         * 
         */
        $article = parent::on_read_entity($args);
        
        /*
         * La valeur peut être soit NULL si art_eid est absent ou 0 Si l'Article n'existe pas.
         */
        if ( !$article || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $article)  ) {
            return $article;
        }
        $artid = $article["artid"];
        
        /*
         * On vérifie s'il s'agit bien d'un Article de type ART_TR. 
         * Pour cela, on récupérant les données relatives à ART_TR et à la Tendance liée.
         */
        $QO = new QUERY("qryl4trartn2");
        $params = array( ':trart_artid' => $artid);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            //Il ne s'agit pas d'un ARTICLE de type TREND
            /*
             * [NOTE 14-12-14] @author L.C.
             * J'ai commenté les deux lignes ci-dessous
             * On laisse le choix à l'utilisateur de faire ce qu'il veut. 
             * Même si l'Article n'est pas de type TR, on a déjà les données. Autant en profiter et gagner en performance le cas échéant!
             * 
             * Enfin, je mets le résultat de "read_entity" dans le tableau all properties
             */
//            $this->is_instance_load = FALSE;
//            $this->all_properties = NULL;
            $this->art_loads = $this->all_properties["art_loads"] = $article;
            return -1;
        }
        
        $datas = $datas[0];
        /*
         * [NOTE 14-12-14] @author L.C.
         * Si toutes les données ne respectent pas la meme logique, c'est parce que j'ai ajouté des nouvelles lignes sans modifier les précédentes pour limiter au maximum l'apparition de bogues.
         */
        $this->trartid = $this->all_properties["trartid"] = $datas["trartid"];
        $this->trid = $this->all_properties["trid"] = $datas["trid"];
        $this->trd_eid = $this->all_properties["trd_eid"] = $datas["trd_eid"];
        $this->trd_title = $this->all_properties["trd_title"] = $datas["trd_title"];
        $this->trd_desc = $this->all_properties["trd_desc"] = $datas["trd_desc"];
        $this->trd_title_href = $this->all_properties["trd_title_href"] = $datas["trd_title_href"];
        $this->art_trd_catgid = $this->all_properties["trd_catgid"] = $datas["trd_catgid"];
        $this->art_trd_is_public = $this->all_properties["trd_is_public"] = $datas["trd_is_public"];
        $this->art_trd_grat = $this->all_properties["trd_grat"] = $datas["trd_grat"];
        $this->art_trd_date_tstamp = $this->all_properties["trd_date_tstamp"] = $datas["trd_date_tstamp"];
        //DONNEES SUR LE PROPRIETAIRE DE LA TENDANCE
        $this->art_trd_oid = $this->all_properties["art_trd_oid"] = $datas["pdaccid"];
        $this->art_trd_oeid = $this->all_properties["art_trd_oeid"] = $datas["pdacc_eid"];
        $this->art_trd_ogid = $this->all_properties["art_trd_ogid"] = $datas["pdacc_gid"];
        $this->art_trd_ofn = $this->all_properties["art_trd_ofn"] = $datas["pdacc_ufn"];
        $this->art_trd_opsd = $this->all_properties["art_trd_opsd"] = $datas["pdacc_upsd"];
        $this->art_trd_otdel = $this->all_properties["art_trd_otdel"] = $datas["pdacc_todelete"];
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->all_properties,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
        //On ajoute trd_href
        $TR = new TREND();
        $this->trd_href = $this->all_properties["trd_href"] = $TR->on_read_build_trdhref($this->trd_eid, $this->trd_title_href, $urqid);
        
        
        //On récupère le nombre de contributions de l'utliisateur pour la Tendance liée.
        $QO = new QUERY("qryl4trartn3");
        $params = array(':art_accid' => $this->art_oid, ':trart_trid' => $this->trid);
        $datas = $QO->execute($params)[0];
        
        $this->ocontrb = $this->all_properties["ocontrb"] = $datas["ucontb"];
        $time_end = round(microtime(TRUE)*1000);
        
//        $elp = $time_end - $time_start;
//        var_dump($elp);
//        exit();
        
        return $this->all_properties;
    }

    protected function write_new($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4trartn1");
        $params = array( ':trart_artid' => $args["artid"], ':trart_trid' => $args["trid"]);
        $trartid = $QO->execute($params);
        
        //Créer l'instance dans ART_TR
        if (! $trartid ) { $this->signalError ("err_user_l4trartn2", __FUNCTION__, __LINE__, TRUE); }
        
        return $trartid;
    }
    
    /****************************************************************************************************/
    /****************************************** SPECIFICS SCOPE *****************************************/
    public function on_create_create_authorized ( $trd_eid, $cuid, $std_err_enabled = FALSE ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $TR = new TREND();
        
        //(1) On vérifie s'il s'agit du propriétaire de la TENDANCE
        $trend_infos = $TR->exists($trd_eid);
        
        if ( $trend_infos && $std_err_enabled  ) {
            if ( $std_err_enabled ) {
                $this->signalError ("err_user_l4trdn3", __FUNCTION__, __LINE__);
            } else {
                return "__ERR_VOL_TREND_GONE";
            }
        }
        
        if ( intval($cuid) === intval($trend_infos["trd_owner"]) ) { return TRUE; }
        
        //(2) Sinon, on vérifie la participation
        if ( 1 === intval($trend_infos["trd_is_public"]) ) {
            
            //(3) On vérifie maintenant si l'utilisateur est abonné à la Tendance
            $ae = $TR->trend_abo_exists($cuid, $trd_eid);
            
            if ( isset($ae) && is_array($ae) && count($ae) ) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if ( $std_err_enabled ) {
                $this->signalError ("err_user_l4trdn5", __FUNCTION__, __LINE__);
            } else {
                return FALSE;
            }
        }
    }
    
    /****************************************************************************************************/
    /*************************************** GETTERS and SETTERS*****************************************/
    public function getTrartid() {
        return $this->trartid;
    }

    public function getTrid() {
        return $this->trid;
    }

    public function getTrd_eid() {
        return $this->trd_eid;
    }

    public function getTrd_title() {
        return $this->trd_title;
    }

    public function getTrd_desc() {
        return $this->trd_desc;
    }

    public function getTrd_title_href() {
        return $this->trd_title_href;
    }

    public function getTrd_href() {
        return $this->trd_href;
    }

    public function getArt_trd_catgid() {
        return $this->art_trd_catgid;
    }

    public function getArt_trd_is_public() {
        return $this->art_trd_is_public;
    }

    public function getArt_trd_grat() {
        return $this->art_trd_grat;
    }

    public function getOcontrb() {
        return $this->ocontrb;
    }

    public function getArt_trd_oid() {
        return $this->art_trd_oid;
    }

    public function getArt_trd_oeid() {
        return $this->art_trd_oeid;
    }

    public function getArt_trd_ogid() {
        return $this->art_trd_ogid;
    }

    public function getArt_trd_ofn() {
        return $this->art_trd_ofn;
    }

    public function getArt_trd_opsd() {
        return $this->art_trd_opsd;
    }

    public function getArt_trd_otdel() {
        return $this->art_trd_otdel;
    }

    public function getArt_loads() {
        return $this->art_loads;
    }

}
