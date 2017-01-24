<?php

class POSTMAN extends PROD_ENTITY {
    /*
     * L'auteur de l'action.
     * Cette donnée est quand même inscrite pour permettre d'avoir des données quand on ne peut pas les avoir via l'objet ou les objets de référence.
     */
    private $pm_actor;
    /*
     * L'action liée à la notification.
     * Le type de l'action est essentiel à la compréhension et au traitement de la tâche à effectuer. 
     */
    private $pm_action;
    /*
     * L'objet de référence principal.
     * On stock la table de l'objet de référence. 
     */
    private $pm_reftab;
    /*
     * L'objet de référence secondaire.
     * On stock la table de l'objet de référence.
     */
    private $pm_refs_auxtab;
    /*
     * 
     */
    private $pm_refotype;
    private $pm_notcrea;
    private $pm_notred;
    
    /****** RULES ******/
    /*
     * Le nombre de Notification "pullable" par défaut.
     */
    private $_DFLT_LIMIT;
    
    /*
     * RAPPEL SUR KEYs POUR UNE TAB NOTIFICATION avec WFEO
     *  pmrid        : L'identifiant (externe) de la Notification. 
     *                 L'identifiant étant tout autant unique que celui interne, CALLER pourra s'en servir pour des opérations au niveau de la base de données.
     *  act_uid      : L'identifiant (interne) de l'utilisateur qui a effectué l'action
     *  act_ueid     : L'indetifiant (externe) de l'utilisateur qui a effectué l'action
     *  act_ufn      : Le nom complet de l'utilisateur qui a effectué l'action
     *  act_upsd     : Le pseudo de l'utilisateur qui a effectué l'action
     *  wha          : L'action effectuée par l'utilsateur qui joue le rôle de l'acteur VU PAR UALOG
     *  pmr_wha      : L'action effectuée par l'utilsateur qui joue le rôle de l'acteur VU POSTMAN_REPORT
     *  mst (Master) : L'identifiant (interne) de l'objet principal lié à l'action. 
     *                 Par exemple, si l'utilisateur ajoute un Commentaire à un Article, l'objet Master est le Commentaire.
     *  mst_eid      : L'identifiant (externe) de l'objet principal lié à l'action. 
     *  mst_prmlkid  : L'identifiant de l'objet principal utilisé pour créer le lien "permalien". Certains objets n'admettent pas d'identifiant "prmlkid"
     *  slv (Slave)  : L'identifiant (interne) de l'objet secondaire lié à l'action. 
     *                 Par exemple, si l'utilisateur ajoute un Commentaire à un Article, l'objet Slave est le l'Article. 
     *  slv_eid      : L'identifiant (externe) de l'objet secondaire lié à l'action. 
     *  slv_prmlkid  : L'identifiant de l'objet secondaire utilisé pour créer le lien "permalien". Certains objets n'admettent pas d'identifiant "prmlkid"
     *  tm (TiMe)    : Le temps de l'exécution de l'action. Cette date correspond le plus souvent à la date d'ajout (ou autre action) de l'objet principal 
     *  tm_pull      : La date de traitement de la Notification. En d'autres termes la date selon laquelle la Notification a été traitée et notifiée auprès de son destinataire.
     *  tm_rgr       : La date de lecture de la Notification. En d'autres termes la date selon laquelle le destinataire a pris connaissance de la Notification. 
     *                 Cette date n'est pas sure à 100%. Cela ne veut pas dire que l'utilisateur a l'oeil sur la ligne. On ne peut pas en être sur.
     *                 Il ne s'agit donc que de la date que l'on considère comme authentique.
     *  tm_vstd      : La date de visite de l'Objet lié à la Notification depuis la ligne de Notification. Cela permet à FE d'afficher que l'utilisateur a déjà visité le lien.
     *  pmr_tp       : Le type de Notification (PRT_NOTFY,PRT_INFO,PRT_ALERT_ORG,PRT_ALERT_RED,PRT_NEWS)
     *                 De cette information dépend la présentation de l'information au niveau de FE
     * 
     * NOTE : 
     *  (1) Même si les tables provenant de la base de données pour le cas de WFEO sont destinés à FE, les identifiants des objets liés sont des identifiants interne.
     *      L'interet réside dans le fait qu'il est plus que probable qu'il sera effectué d'autres opérations pour réccupérer des informations complémentaires.
     *      Aussi, autant prendre les identifiants internes. Elles seront utilisés pour effectuer des opérations au niveau de la base de données dont les clés de liaison sont celles internes.
     *      Mais pour des raisons pratiques, on fournit aussi l'identifiant externe.
     *  (2) On peut ajouter autant de slv que l'on veut selon les niveaux. On aura alors : mst -> slv -> slvl1 -> slvl2 -> ... -> slvln.
     *      Ce cas est obligatoire pour atteindre un Entity dont a absolument besoin CALLER ou FE
     */
            
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["chmsgid","chmsg_eid","chmsg_msg","chmsg_convid","chmsg_actid","chmsg_acteid","chmsg_actgid","chmsg_actfn","chmsg_actpsd","chmsg_actgdr","chmsg_actppic","chmsg_acttodl","chmsg_tgtid","chmsg_tgteid","chmsg_tgtgid","chmsg_tgtfn","chmsg_tgtpsd","chmsg_tgtgdr","chmsg_tgtppic","chmsg_tgttodl","chmsg_locip","chmsg_useragt","chmsg_cdate","chmsg_cdate_tstamp","chmsg_fe_cdate","chmsg_fe_cdate_tstamp","chmsg_rdate","chmsg_rdate_tstamp","chmsg_ad_date","chmsg_ad_date_tstamp", "chmsg_ad_rsncaz","chmsg_td_date","chmsg_td_date_tstamp","chmsg_td_rsncaz","chmsg_sd_date","chmsg_sd_date_tstamp","chmsg_sd_rsncaz","chmsg_nxtdldate","chmsg_nxtdldate_tstamp"];
        $this->needed_to_loading_prop_keys = ["chmsgid","chmsg_eid","chmsg_convid","chmsg_msg","chmsg_actid","chmsg_acteid","chmsg_actgid","chmsg_actfn","chmsg_actpsd","chmsg_actgdr","chmsg_actppic","chmsg_acttodl","chmsg_tgtid","chmsg_tgteid","chmsg_tgtgid","chmsg_tgtfn","chmsg_tgtpsd","chmsg_tgtgdr","chmsg_tgtppic","chmsg_tgttodl","chmsg_locip","chmsg_useragt","chmsg_cdate","chmsg_cdate_tstamp","chmsg_fe_cdate","chmsg_fe_cdate_tstamp","chmsg_rdate","chmsg_rdate_tstamp","chmsg_ad_date","chmsg_ad_date_tstamp", "chmsg_ad_rsncaz","chmsg_td_date","chmsg_td_date_tstamp","chmsg_td_rsncaz","chmsg_sd_date","chmsg_sd_date_tstamp","chmsg_sd_rsncaz","chmsg_nxtdldate","chmsg_nxtdldate_tstamp"];
        
        $this->needed_to_create_prop_keys = ["conv_eid","message","act_eid","tgt_eid", "fetime","locip","useragt"];
        
        /********** RULES **********/
        $this->_DFLT_LIMIT = 10;
//        $this->_DFLT_LIMIT = 5; //DEV, DEBUG, TEST
    }
    
    protected function build_volatile($args) { }

    public function exists($args) {
        
    }

    protected function init_properties($datas) {
        
    }

    protected function load_entity($args) {
        
    }

    protected function on_alter_entity($args) {
        
    }

    public function on_create_entity($args) {
        
    }

    public function on_delete_entity($args) {
        
    }

    public function on_read_entity($args) {
        
    }

    protected function write_new_in_database($args) {
        
    }
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** SPECIFICS SCOPE ****************************************************************************/
    /*************************************************************************************************************************************************************************/
    
    /**************************************************************************************************************************************************/
    /**************************************************************** onEXISTS CREATE *****************************************************************/
                                                                                      
    public function onexistscreate_reactions ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les Commentaires.
         * Dans le cas où il en existerait, on lance la création des Notifications.
         * 
         * [NOTE 08-03-1991] @Lou
         * La référence temporelle peut ne pas être transmise ou servir. 
         * En effet, les actions sont à ce point hétérogènes qu'une donnée temporelle pivot serait un non sens.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_reactions($uid,$ref_time);
//        var_dump(__LINE__,"PM Actys => ",$actys);
//        exit();
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XRCT_AD_oMA" :
                        $noft_tabs["UAT_XRCT_AD_oMA"] = $this->NOTF_oncreate_reaction($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
    }
                    
    
    public function onexistscreate_usertags ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les Usertags.
         * Dans le cas où il en existerait, on lance la création des Notifications.
         * 
         * [NOTE 08-03-2015 @BOR
         * La référence temporelle peut ne pas être transmise ou servir. 
         * En effet, les actions sont à ce point hétérogènes qu'une donnée temporelle pivot serait un non sens.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_usertags($uid,$ref_time);
//        var_dump(__LINE__,"PM Actys => ",$actys);
//        exit();
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XUSTG_ART" :
                        $noft_tabs["UAT_XUSTG_ART"] = $this->NOTF_oncreate_usertag($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                case "UAT_XUSTG_RCT" :
                        $noft_tabs["UAT_XUSTG_RCT"] = $this->NOTF_oncreate_usertag($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                case "UAT_XUSTG_MEoTSM" :
                        $noft_tabs["UAT_XUSTG_MEoTSM"] = $this->NOTF_oncreate_usertag($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                case "UAT_XUSTG_MEoTSR" :
                        $noft_tabs["UAT_XUSTG_MEoTSR"] = $this->NOTF_oncreate_usertag($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
    }
            
    
    public function onexistscreate_evals ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les EVALUATIONS.
         * Dans le cas où il en existerait, on lance la création des Notifications.
         * 
         * [NOTE 08-03-2015 @BOR
         * La référence temporelle peut ne pas être transmise ou servir. 
         * En effet, les actions sont à ce point hétérogènes qu'une donnée temporelle pivot serait un non sens.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_evals($uid,$ref_time);
//        var_dump(__LINE__,"PM Actys => ",$actys);
//        exit();
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XEVL_GOEVL" :
                        $noft_tabs["UAT_XEVL_GOEVL"] = $this->NOTF_oncreate_evaluation($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                case "UAT_XEVL_GOEVL_oMA" :
                        $noft_tabs["UAT_XEVL_GOEVL_oMA"] = $this->NOTF_oncreate_evaluation($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
    }
    

    
    /************************** ACCOUNT SCOPE **************************/
    
    public function onexistscreate_relation_followers ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_relation_followers($uid,$ref_time);
//        var_dump(__LINE__,"PM Actys => ",$actys);
//        exit();
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XREL_NWAB" :
                        $noft_tabs["UAT_XREL_NWAB"] = $this->NOTF_oncreate_relation($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
    }
    
    /************************** TREND SCOPE **************************/
    
    public function onexistscreate_trend_article ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
    } 
    
    public function onexistscreate_trend_follower ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_trend_followers($uid,$ref_time);
//        var_dump(__LINE__,"PM Actys => ",$actys);
//        exit();
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XMTRD_NWABO" :
                        $noft_tabs["UAT_XMTRD_NWABO"] = $this->NOTF_oncreate_trend($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
        
    } 
    
    
    /************************** TALKBOARD SCOPE **************************/
    
    public function onexistscreate_testy ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_testy($uid,$ref_time);
//        var_dump(__LINE__,"PM Actys => ",$actys);
//        exit();
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XTSTY_AD_SBoMTBD" :
                        $noft_tabs["UAT_XTSTY_AD_SBoMTBD"] = $this->NOTF_oncreate_testies($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
        
    } 
    
   
    public function onexistscreate_testy_reactions ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_testy_reactions($uid,$ref_time);
//        var_dump(__LINE__,"PM Actys => ",$actys);
//        exit();
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XTSTY_TSR_SBoMTSM" :
                        $noft_tabs["UAT_XTSTY_TSR_SBoMTSM"] = $this->NOTF_oncreate_testies($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
        
    } 
    
    
    public function onexistscreate_testy_like ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_testy_likes($uid,$ref_time);
//        var_dump(__LINE__,"PM Actys => ",$actys);
//        exit();
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XTSTY_TSL_SBoMTSM" :
                        $noft_tabs["UAT_XTSTY_TSL_SBoMTSM"] = $this->NOTF_oncreate_testies($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
        
    } 
    
    
    /************************** TALKBOARD SCOPE **************************/
    
    public function onexistscreate_fav_art ($uid, $ref_time = 1, $_WITH_FE_OPTION = FALSE) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        //On récupère les activités à partir de la base de données
        $actys = $this->UAL_onexists_fav_art($uid,$ref_time);
        
//        var_dump(__FILE__,__LINE__,"PM Actys => ",$actys);
//        exit();
        
        if ( empty($actys) ) {
            return FALSE;
        }
        
        $noft_tabs = [];
        foreach ($actys as $k => $ds) {
            switch ($k) {
                case "UAT_XFAV_ART_FVoMI" :
                        $noft_tabs["UAT_XFAV_ART_FVoMI"] = $this->NOTF_oncreate_fav($k,$uid,$ds,$_WITH_FE_OPTION);
                    break;
                default :
                    return;
            }
        }
        
        return $noft_tabs;
        
    } 
    
    
    /**************************************************************************************************************************************************/
    /********************************************************************* EXISTS *********************************************************************/
    
    public function UAL_onexists_reactions($uid,$ref_time) {
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les Commentaires.
         * Chaque activité est notifiée dans des tableaux.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        
        /*
         * ETAPE :
         *      On vérifie s'il y a de nouveaux Commentaires à notifier sur des Articles appartiennant à l'utilisateur actif.
         */
        $QO = new QUERY("qryl4ualg_rctn1");
        $params = array ( 
            ":ref_uid1"        => $uid, 
            ":ref_uid2"        => $uid, 
            ":ref_time"        => $ref_time 
        );
        $t__ = $QO->execute($params);
        
        if ( $t__ ) {
            foreach ($t__ as $v) {
                switch ($v["ualg_uatid"]) {
                    case "600" :
                            $actys["UAT_XRCT_AD_oMA"][] = $v;
                        break;
                    default :
                        break;
                }
            }
            
        } 
        /*
        if ( $t__ ) {
            $actys["UAT_XRCT_AD_oMA"] = $t__;
        } 
        //*/
        //TODO : On vérifie s'il y a d'autres activités repertoriées
        
        return $actys;
    }
    
    
    /******************************************************* USERTAGS SCOPE *******************************************************/
    
    public function UAL_onexists_usertags($uid,$ref_time) {
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les Usertags.
         * Chaque activité est notifiée dans des tableaux.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        //On vérifie s'il y a de nouveaux Usertags sur des Articles qui lui appartiennent à notifier pour l'utilisateur actif.
        $QO = new QUERY("qryl4ualg_ustgn1");
        $params = array ( 
            ":ref_uid1"     => $uid, 
            ":ref_uid2"     => $uid, 
            ":ref_time"     => $ref_time 
        );
        $t__ = $QO->execute($params);
        
        if ( $t__ ) {
            foreach ($t__ as $v) {
                switch ($v["ualg_uatid"]) { 
                    case "1101" :
                            $actys["UAT_XUSTG_ART"][] = $v;
                        break;
                    case "1102" :
                            $actys["UAT_XUSTG_RCT"][] = $v;
                        break;
                    case "1106" :
                            $actys["UAT_XUSTG_MEoTSM"][] = $v;
                        break;
                    case "1107" :
                            $actys["UAT_XUSTG_MEoTSR"][] = $v;
                        break;
                    default :
                        break;
                }
            }
        } 
        
        return $actys;
    }
    
    
    /******************************************************* EVALUATIONS SCOPE *******************************************************/
    
    public function UAL_onexists_evals($uid,$ref_time) {
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les Evaluations.
         * Chaque activité est notifiée dans des tableaux.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        
        //On vérifie s'il y a de nouveaux Usertags sur des Articles qui lui appartiennent à notifier pour l'utilisateur actif.
        $QO = new QUERY("qryl4ualg_evln1");
        $params = array ( 
            ":ref_uid1"     => $uid, 
            ":ref_uid2"     => $uid, 
            ":ref_time"     => $ref_time 
        );
        $t__ = $QO->execute($params);
//        var_dump(__LINE__,$t__);
//        exit();
        if ( $t__ ) {
            foreach ($t__ as &$v) {
                switch ($v["ualg_uatid"]) { 
                    case "700" :
                            if ( floatval($v["aoid"]) === floatval($uid) ) {
                                $v["ualg_uatid"] = 701;
                                $actys["UAT_XEVL_GOEVL_oMA"][] = $v;
                            } else {
                                $actys["UAT_XEVL_GOEVL"][] = $v;
                            }
                        break;
                    case "701" :
                            $actys["UAT_XEVL_GOEVL_oMA"][] = $v;
                        break;
                    default :
                        break;
                }
            }
        } 
        
        return $actys;
    }
    
    
    /******************************************************* TESTY SCOPE *******************************************************/
    
    public function UAL_onexists_testy($uid,$ref_time){
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les Evaluations.
         * Chaque activité est notifiée dans des tableaux.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        
        //On vérifie s'il y a de nouvelles activités à notifier pour l'utilisateur actif.
        $QO = new QUERY("qryl4ualg_tsmn1");
        $params = array ( 
            ":ref_uid1"     => $uid, 
            ":ref_uid2"     => $uid, 
            ":ref_time"     => $ref_time 
        );
        $t__ = $QO->execute($params);
        
        if ( $t__ ) {
            foreach ($t__ as &$v) {
                switch ($v["ualg_uatid"]) { 
                    case "1202" :
                            $actys["UAT_XTSTY_AD_SBoMTBD"][] = $v;
                        break;
                    default :
                        break;
                }
            }
        } 
        
        return $actys;
    }
    
    
    public function UAL_onexists_testy_reactions($uid,$ref_time) {
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les Evaluations.
         * Chaque activité est notifiée dans des tableaux.
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        
        //On vérifie s'il y a de nouvelles activités à notifier pour l'utilisateur actif.
        $QO = new QUERY("qryl4ualg_tsrn1");
        $params = array ( 
            ":ref_uid1"     => $uid, 
            ":ref_uid2"     => $uid, 
            ":ref_uid3"     => $uid, 
            ":ref_time"     => $ref_time 
        );
        $t__ = $QO->execute($params);
        
        if ( $t__ ) {
            foreach ($t__ as &$v) {
                switch ($v["ualg_uatid"]) { 
                    case "1212" :
                            $actys["UAT_XTSTY_TSR_SBoMTSM"][] = $v;
                        break;
                    default :
                        break;
                }
            }
        } 
        
        return $actys;
    }
    
    
    public function UAL_onexists_testy_likes($uid,$ref_time) {
        /*
         * Permet de vérifier s'il y a des activités qu'il faut notifier en ce qui concerne les Evaluations.
         * Chaque activité est notifiée dans des tableaux.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        
        //On vérifie s'il y a de nouvelles activités à notifier pour l'utilisateur actif.
        $QO = new QUERY("qryl4ualg_tsln1");
        $params = array ( 
            ":ref_uid1"     => $uid, 
            ":ref_uid2"     => $uid, 
            ":ref_uid3"     => $uid, 
            ":ref_time"     => $ref_time 
        );
        $t__ = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$t__);
//        exit();
        
        if ( $t__ ) {
            foreach ($t__ as &$v) {
                switch ($v["ualg_uatid"]) { 
                    case "1221" :
                            $actys["UAT_XTSTY_TSL_SBoMTSM"][] = $v;
                        break;
                    default :
                        break;
                }
            }
        } 
        
        return $actys;
    }
    
    /******************************************************* RELATION SCOPE *******************************************************/
    
    public function UAL_onexists_relation_followers($uid,$ref_time) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        
        //On vérifie s'il y a de nouvelles activités à notifier pour l'utilisateur actif.
        $QO = new QUERY("qryl4ualg_ureln1");
        $params = array ( 
            ":ref_uid1"     => $uid, 
            ":ref_uid2"     => $uid, 
            ":ref_time"     => $ref_time 
        );
        $t__ = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$t__);
//        exit();
        
        if ( $t__ ) {
            foreach ($t__ as &$v) {
                switch ($v["ualg_uatid"]) { 
                    case "300" :
                            $actys["UAT_XREL_NWAB"][] = $v;
                        break;
                    default :
                        break;
                }
            }
        } 
        
        return $actys;
    }
    
    /******************************************************* TREND SCOPE *******************************************************/
    
    
    public function UAL_onexists_trend_followers($uid,$ref_time) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        
        /*
         * ETAPE :
         *      On vérifie s'il y a de nouvelles activités à notifier pour l'utilisateur actif.
         */
        $QO = new QUERY("qryl4ualg_trabon1");
        $params = array ( 
            ":ref_uid1"     => $uid, 
            ":ref_uid2"     => $uid, 
            ":ref_time"     => $ref_time 
        );
        $t__ = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$t__);
//        exit();
        
        if ( $t__ ) {
            foreach ($t__ as &$v) {
                switch ($v["ualg_uatid"]) { 
                    case "901" :
                            $actys["UAT_XMTRD_NWABO"][] = $v;
                        break;
                    default :
                        break;
                }
            }
        } 
        
        return $actys;
    }
    
    
    /******************************************************* ART_FAV SCOPE *******************************************************/
    
    public function UAL_onexists_fav_art($uid,$ref_time) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$uid,$ref_time], TRUE);
        
        $actys = [];
        
        //On vérifie s'il y a de nouvelles activités à notifier pour l'utilisateur actif.
        $QO = new QUERY("qryl4ualg_fvarn1");
        $params = array ( 
            ":ref_uid1"     => $uid, 
            ":ref_uid2"     => $uid, 
            ":ref_time"     => $ref_time 
        );
        $t__ = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$t__);
//        exit();
        
        if ( $t__ ) {
            foreach ($t__ as &$v) {
                switch ($v["ualg_uatid"]) { 
                    case "803" :
                            $actys["UAT_XFAV_ART_FVoMI"][] = $v;
                        break;
                    default :
                        break;
                }
            }
        } 
        
        return $actys;
    }
    
    
    /****************************************************************************************************************************************************/
    /********************************************************************* ONCREATE *********************************************************************/
    
    public function UserActyLog_Set($args, $CHECK_USER = TRUE, $reflib = NULL, $remarks = NULL) {
        /*
         * Permet d'enregistrer l'activité de l'utilisateur.
         * Ce "log" est une fonctionnalité essentielle car elle permet de suivre l'activité de l'utilsateur de manière extremement précise.
         * En termes d'application, on pourra noter l'opération qui permet de déterminer si l'utilisateur est connecté. 
         * 
         * [DEPUIS 12-07-16]
         *      AJout du paramètre "IS_PASSIVE_AX" qui permet d'indiquer l'ACTION à enregistrer est une ACTION dite "passive"
         *      Ce genre d'action ne sont pas NOTIF. Elles permettent d'avoir un LOG de l'ACTY de USER.
         *      Ce LOG est interessant par exemple pour vérifier si un USER est connecté à un instant précis.
         */
//        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, $args, TRUE); //[DEPUIS 13-07-16]
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $XPTD = ["uid","ssid","locip_str","locip_num","useragt","wkr","fe_url","srv_url","url","isAx","refobj","uatid","uanid"];
        $com  = array_intersect( array_keys($args), $XPTD);
        
        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE => ", array_diff(array_keys($args), $XPTD)],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    if ( ( $k === "useragt" ) | ( $k === "isAx" && $v === 0 ) ) {
                        continue;
                    } else {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    }
                } 
            }
        }
        
        /*
         * ETAPE :
         *      On vérifie que l'utilisateur existe
         */
        $PA = new PROD_ACC();
        if ( $CHECK_USER ) {
            $utab = $PA->exists_with_id($args["uid"],TRUE);
            if (! $utab ) {
                return "__ERR_VOL_U_GONE";
            }
        }
        
        //On enregistre le log
        $now = round(microtime(true)*1000);
        $QO = new QUERY("qryl4ualgn1");
        $params = array ( 
            ":uid"          => $args["uid"], 
            ":ssid"         => $args["ssid"], 
            ":locip_str"    => $args["locip_str"], 
            ":locip_num"    => $args["locip_num"], 
            ":useragt"      => $args["useragt"], 
            ":wkr"          => $args["wkr"], 
            ":fe_url"       => $args["fe_url"], 
            ":srv_url"      => $args["srv_url"], 
            ":isax"         => $args["isAx"], 
            ":adate"        => date("Y-m-d G:i:s",($now/1000)), 
            ":adate_tstamp" => $now,
            ":refobj"       => $args["refobj"],
            ":uatid"        => $args["uatid"],
            ":uanid"        => $args["uanid"],
            /*
             * [DEPUIS 13-07-16]
             *      La requête a changé il faut désormais inscrire s'il s'agit d'une action PASSIVE.
             *      Nous avons une méthode chargée de gérer les cas passifs. Alors on me 0 par défaut.
             */
            ":ispasv"       => 0,
            /*
             * [DEPUIS 31-07-16]
             */
            ":reflib"       => ( $reflib ) ? $reflib : NULL,
            ":remarks"      => ( $remarks ) ? $remarks : NULL,
        );
        $id = $QO->execute($params);
        
        
        /*
         * ETAPE :
         *      On envoie un EMAIL de NOTIFICATION au destinataire le cas échéant
         */
        $this->NotifEmail_CheckNAct($id,$args["uatid"]);
        
        return $id;
    }
    
    
    public function UserActyLog_Set_MdPsv($args, $CHECK_USER = TRUE) {
        /*
         * Permet d'enregistrer l'activité de l'utilisateur.
         * Ce "log" est une fonctionnalité essentielle car elle permet de suivre l'activité de l'utilsateur de manière extremement précise.
         * En termes d'application, on pourra noter l'opération qui permet de déterminer si l'utilisateur est connecté. 
         * 
         * [DEPUIS 12-07-16]
         *      AJout du paramètre "IS_PASSIVE_AX" qui permet d'indiquer l'ACTION à enregistrer est une ACTION dite "passive"
         *      Ce genre d'action ne sont pas NOTIF. Elles permettent d'avoir un LOG de l'ACTY de USER.
         *      Ce LOG est interessant par exemple pour vérifier si un USER est connecté à un instant précis.
         */
//        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args, TRUE); //[DEPUIS 13-07-16]
        /*
         * [DEPUIS 13-07-16]
         *      
         */
//        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $XPTD = ["uid","ssid","locip_str","locip_num","useragt","wkr","fe_url","srv_url","url","isAx","refobj","uatid","uanid","ispasv","reflib","remarks"];
        $com  = array_intersect( array_keys($args), $XPTD);
        
        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE => ", array_diff(array_keys($args), $XPTD)],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && isset($v) && $v !== "" ) ) {
//                    if ( ( $k === "useragt" ) | ( $k === "isAx" && $v === 0 ) ) {
                    if ( in_array($k, ["useragt","isAx","reflib","remarks"]) ) {
                        continue;
                    } else {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    }
                } 
            }
        }
        
        //On vérifie que l'utilisateur existe
        $PA = new PROD_ACC();
        if ( $CHECK_USER ) {
            $utab = $PA->exists_with_id($args["uid"],TRUE);
            if (! $utab ) {
                return "__ERR_VOL_U_GONE";
            }
        }
        
        //On enregistre le log
        $now = round(microtime(true)*1000);
        $QO = new QUERY("qryl4ualgn3");
        $params = array ( 
            ":uid"          => $args["uid"], 
            ":ssid"         => $args["ssid"], 
            ":locip_str"    => $args["locip_str"], 
            ":locip_num"    => $args["locip_num"], 
            ":useragt"      => $args["useragt"], 
            ":wkr"          => $args["wkr"], 
            ":fe_url"       => $args["fe_url"], 
            ":srv_url"      => $args["srv_url"], 
            ":isax"         => $args["isAx"], 
            ":adate"        => date("Y-m-d G:i:s",($now/1000)), 
            ":adate_tstamp" => $now,
            ":refobj"       => $args["refobj"],
            ":uatid"        => $args["uatid"],
            ":uanid"        => $args["uanid"],
            ":ispasv"       => ( key_exists("ispasv", $args) && $args["ispasv"] && $args["ispasv"] === TRUE ) ? 1 : 0,
            ":reflib"       => $args["reflib"],
            ":remarks"      => $args["remarks"],
        );
        $id = $QO->execute($params);
        
        return $id;
    }
    
    
    public function UserActyLog_FeedTestDatas ($uid, $rfid, $uatid, $setnb = 10) {
        /*
         * Permet de créer un jet de données test pour un Compte donné.
         * Cette méthode est particulièrement utile pour des opérations de DEV, DEBUG et TEST.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( is_int($setnb) && $setnb > 0 ) ) {
            return;
        }
        
        $ids = [];
        for($i=0;$i<$setnb;$i++) {
            $DATAS = [
                "uid"       => $uid,
                "ssid"      => $this->guidv4(),
                "locip_str" => "127.0.0.1",
                "locip_num" => sprintf('%u', ip2long("127.0.0.1")),
                "useragt"   => "USER_AGENT",
//                "wkr"       => "WORKER_UNQ_ADDRCT",
                "wkr"       => "WORKER_TQR_TSTY_ADD",
//                "wkr" => "FAKE_WORKER_ID_".rand(1, 100),
                "fe_url"    => "http://www.blabla.com/something",
                "srv_url"   => $_SERVER['REQUEST_URI'],
                "url"       => "http://127.0.0.1",
                "isAx"      => rand(0,1),
//                "refobj"    => rand(97,99),
                "refobj" => $rfid,
//                "uatid"     => 600,
                "uatid"     => $uatid,
//                "atid" => rand(2,10),
                "uanid"     => 2
            ];
            
            $ids[] = $this->UserActyLog_Set($DATAS,FALSE);
            //Permet d'avoir des données avec une date différente
            sleep(1);
        }
        
        return $ids;
    }
    
    
    public function UserActyLog_Within($uid, $mins, $limit = 10) {
        /*
         * Permet de récupérer l'ensemble des activités de l'utilisateur passé en paramètre dans l'interval de temps mentionné.
         * La valeur est renseignée en minutes. 
         * On prend considère l'ensemble des logs entre "now" et les x minutes qui ont suivi.
         * La méthode admet aussi une limite qui sert de garde-fou.
         * 
         * On ne vérifie pas si l'utilisateur existe car s'il n'existe pas il n'aura aucune activité et puis c'est tout.
         * On le fait aussi pour des soucis de performances quand on sait que dans ce cas savoir si l'utilisateur existe est trivial.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( (is_int($mins) && $mins > 0) && (is_int($limit) && ($limit === -1 || $limit > 0)) ) ) {
            return "__ERR_VOL_RULES";
        }
        
        $now = round(microtime(true)*1000);
        $gap = $now - $mins*60000;
        
        $QO = new QUERY("qryl4ualgn2");
        $params = array( ':uid' => $uid, ':now' => $now, ':minus' => $gap, ':limit' => $limit );
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return;
        } else {
            return $datas;
        }
    }
    
    /***********************************************************************************************************************************/
    /****************************************************** NOTIFICATIONS (EMAIL) ******************************************************/
    
    
    public function NotifEmail_CheckNAct($uaid,$uatid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
               
        /*
         * ETAPE :
         *      On récupère les compléments de données : ACTOR, TARGET, ...
         */
        $self = FALSE;
        switch ($uatid) {
            case 300 : //UAT_XREL_NWAB
                    $QO = new QUERY("qryl4ualg_ureln1_noteml");
                    $params = array( ':uaid' => $uaid );
                break;
            case 600 : //UAT_XRCT_AD_oMA
                    $QO = new QUERY("qryl4ualg_rctn1_notfeml");
                    $params = array( ':uaid' => $uaid );
                break;
            case 700 : //UAT_XEVL_GOEVL
            case 701 : //UAT_XEVL_GOEVL_oMA
                    $QO = new QUERY("qryl4ualg_evln1_notfeml");
                    $params = array( ':uaid' => $uaid );
                break;
            case 803 : //UAT_XFAV_ART_FVoMI
                    $QO = new QUERY("qryl4ualg_fvarn1_notfeml");
                    $params = array( ':uaid' => $uaid );
                break;
            case 901 : //UAT_XMTRD_NWABO
                    $QO = new QUERY("qryl4ualg_trabon1_notfeml");
                    $params = array( ':uaid' => $uaid );
                break;
            case 1101 : //UAT_XUSTG_ART
                    $QO = new QUERY("qryl4ualg_ustgn1_notfeml_1101");
                    $params = array( ':uaid' => $uaid );
                break;
            case 1102 : //UAT_XUSTG_RCT
                    $QO = new QUERY("qryl4ualg_ustgn1_notfeml_1102");
                    $params = array( ':uaid' => $uaid );
                break;            
            case 1106 : //UAT_XUSTG_MEoTSM
                    $QO = new QUERY("qryl4ualg_ustgn1_notfeml_1106");
                    $params = array( ':uaid' => $uaid );
                break;
            case 1107 : //UAT_XUSTG_MEoTSR
                    $QO = new QUERY("qryl4ualg_ustgn1_notfeml_1107");
                    $params = array( ':uaid' => $uaid );
                break;
            case 1202 : //UAT_XTSTY_AD_SBoMTBD
                    $QO = new QUERY("qryl4ualg_tsmn1_notfeml");
                    $params = array( ':uaid' => $uaid );
                break;
            case 1212 : //UAT_XTSTY_TSR_SBoMTSM
                    $self = TRUE;
                
                    $QO = new QUERY("qryl4ualg_tsrn1_notfeml_p001");
                    $params = array( ':uaid' => $uaid );
                    $datas = $QO->execute($params);
                    
                    /*
                     * [NOTE 29-07-16]
                     *      Un TESTY peut avoir un OWNER et un TARGET différents. 
                     *      Les deux sont comme des co-propriétaires du TESTY.
                     *      Aussi, ils doivent recevoir au même titre la NOTIFICATION sauf cas particulier. 
                     */
                    $QO = new QUERY("qryl4ualg_tsrn1_notfeml_p002");
                    $params = array( ':uaid' => $uaid );
                    $datas_p001 = $QO->execute($params);
                break;
            case 1221 : //UAT_XTSTY_TSL_SBoMTSM
                    $self = TRUE;
                
                    $QO = new QUERY("qryl4ualg_tsln1_notfeml_p001");
                    $params = array( ':uaid' => $uaid );
                    $datas = $QO->execute($params);
                    
                    /*
                     * [NOTE 29-07-16]
                     *      Un TESTY peut avoir un OWNER et un TARGET différents. 
                     *      Les deux sont comme des co-propriétaires du TESTY.
                     *      Aussi, ils doivent recevoir au même titre la NOTIFICATION sauf cas particulier. 
                     */
                    $QO = new QUERY("qryl4ualg_tsln1_notfeml_p002");
                    $params = array( ':uaid' => $uaid );
                    $datas_p001 = $QO->execute($params);
                    
                break;
            default :
                return;
        }
        if (! $self ) {
            $datas = $QO->execute($params);
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas,$datas_p001);
//        exit();
        
        /*
         * ETAPE :
         *      On vérifie si on a au moins un ensemble de données et qu'il n'y a pas d'erreurs
         */
        if ( !$datas && !$datas_p001 ) {
            return FALSE;
        } 
        else if ( $datas && count($datas) > 1 ) {
            return "__ERR_VOL_MSM_DATAS_RULES";
        }
        else if ( $datas_p001 && count($datas_p001) > 1 ) {
            return "__ERR_VOL_MSM_DATAS_RULES_P001";
        }
        
        
        $DATA_SET = [];
        if ( $datas ) {
            $DATA_SET[] = $datas[0];
        }
        else if ( $datas_p001 ) {
            $DATA_SET[] = $datas_p001[0];
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$DATA_SET);
//        exit();
        
        foreach ($DATA_SET as $SET) {
            $RCPUID = $SET["tag_uid"];
            if ( $RCPUID ) {
                
                
                /*
                 * ETAPE :
                 *      On vérifie que TARGET a activé (autorisé) l'envoi des EMAILS
                 */
                $TQR = new TRENQR();
                $prefdcs = $TQR->getPreferences($RCPUID,"_PFOP_PSMN_EMLWHN_NW");
                $prefdcs = ( $prefdcs ) ? $prefdcs[0] : $prefdcs;

                $_PFOP_PSMN_EMLWHN_NW = ( 
                    !$prefdcs 
                    || ( $prefdcs && is_array($prefdcs) && $prefdcs["prfodtp_lib"] === "_DEC_ENA" ) 
                ) ? TRUE : FALSE;

                if (! $_PFOP_PSMN_EMLWHN_NW ) {
                    return FALSE;
                }
            
                /*
                 * ETAPE :
                 *      On détermine le STATUT DE CONNEXION de CU. Autrement dit est ce que TGUSER est considéré comme étant ACTIF ou INACTIF :
                 *          NON : On envoie l'EMAIL
                 *          OUI : On n'envoie pas l'EMAIL
                 */      
                $CNX = new TQR_CONX();
//                if ( FALSE ) { //DEB, TEST, DEBUG
                if ( $CNX->IsConnectedLate($RCPUID) ) { //PROD
                
                    return FALSE;
                }
                
                $PA = new PROD_ACC();
                
                /*
                 * ETAPE :
                 *      On formatte les données necessaire au remplacement des données
                 */
                $formatted_datas = [
                    //ACTORS
                    "act_ufn"   => $SET["act_ufn"],
                    "act_upsd"  => $SET["act_upsd"],
                    "act_uppic" => $PA->onread_acquiere_pp_datas($SET["act_uid"])["pic_rpath"],
                    "tag_ufn"   => $SET["tag_ufn"],
                    "tag_upsd"  => $SET["tag_upsd"],
                    "tag_uppic" => $PA->onread_acquiere_pp_datas($SET["tag_uid"])["pic_rpath"],
                ];
                /*
                 * ETAPE :
                 *      On récupère les données données supplémentaires.
                 *          - TIME
                 *          - ACTION SENTECNCE
                 *              - PERMALIEN
                 *          - PREVIEW
                 *              -> USTGS
                 *              -> HASH
                 */
                $TXH = new TEXTHANDLER();
                switch ($uatid) {
                    case 300 : //UAT_XREL_NWAB
                            $permalink = $WOS_MAIN_HTTPS_HOST."/".$formatted_datas["act_upsd"];
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XREL_NWAB");
                                  
                            $is_no_preview = TRUE;
                            $preview = $TXH->get_deco_text("fr","_PM_UAT_NO_PRVW");
                        
                            $formatted_datas["time"] = $SET["tbrel_datecrea_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 600 : //UAT_XRCT_AD_oMA
                            $ART = new ARTICLE();
                        
                            $ivid = ( $SET["art_vid_url"] ) ? TRUE : FALSE;
                            $permalink = $ART->onread_AcquierePrmlk($SET["art_eid"],$ivid);
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XRCT_AD_oMA");
                            
                            $ustgs = $ART->onread_AcquiereUsertags_Reaction($SET["react_eid"],TRUE);
                            $hashs = $ART->onread_AcquiereHashs_Reaction($SET["react_eid"]);
                            $preview = $SET["react_body"];
                        
                            $formatted_datas["time"] = $SET["react_date_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 700 : //UAT_XEVL_GOEVL
                    case 701 : //UAT_XEVL_GOEVL_oMA
                            $ART = new ARTICLE();
                        
                            $ivid = ( $SET["art_vid_url"] ) ? TRUE : FALSE;
                            $permalink = $ART->onread_AcquierePrmlk($SET["art_eid"],$ivid);
                            
                            if ( intval($SET["tbevl_evltpid"]) === 1 ) {
                                $code = "_PM_UAT_XEVL_GOEVL_oMA_SP";
                            } 
                            else if ( intval($SET["tbevl_evltpid"]) === 2 ) {
                                $code = "_PM_UAT_XEVL_GOEVL_oMA_CL";
                            }
                            else {
                                $code = "_PM_UAT_XEVL_GOEVL_oMA_DL";
                            }
                            $action_sentence = $TXH->get_deco_text("fr",$code);
                            
                            $ustgs = $ART->onread_AcquiereUsertags_Article($SET["art_eid"],TRUE);
                            $hashs = $ART->onread_AcquiereHashs_Article($SET["art_eid"]);
                            $preview = $SET["art_desc"];
                        
                            $formatted_datas["time"] = $SET["art_cdate_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 803 : //UAT_XFAV_ART_FVoMI
                            $ART = new ARTICLE();
                        
                            $ivid = ( $SET["art_vid_url"] ) ? TRUE : FALSE;
                            $permalink = $ART->onread_AcquierePrmlk($SET["art_eid"],$ivid);
                            
                            if ( intval($SET["arfv_fvtid"]) === 1 ) {
                                $code = "_PM_UAT_XFAV_ART_FVoMI_PU";
                            } 
                            else {
                                $code = "_PM_UAT_XFAV_ART_FVoMI_PRI";
                            }
                            $action_sentence = $TXH->get_deco_text("fr",$code);
                            
                            $ustgs = $ART->onread_AcquiereUsertags_Article($SET["art_eid"],TRUE);
                            $hashs = $ART->onread_AcquiereHashs_Article($SET["art_eid"]);
                            $preview = $SET["art_desc"];
                        
                            $formatted_datas["time"] = $SET["art_cdate_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 901 : //UAT_XMTRD_NWABO
                            $TRD = new TREND();
                            $permalink = $TRD->on_read_build_trdhref($SET["trd_eid"], $SET["trd_title_href"]);
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XMTRD_NWABO");
                                    
                            $is_no_preview = TRUE;
                            $preview = $TXH->get_deco_text("fr","_PM_UAT_NO_PRVW");
                        
                            $formatted_datas["time"] = $SET["trabo_datecrea_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 1101 : //UAT_XUSTG_ART
                            $ART = new ARTICLE();
                        
                            $ivid = ( $SET["art_vid_url"] ) ? TRUE : FALSE;
                            $permalink = $ART->onread_AcquierePrmlk($SET["art_eid"],$ivid);
                            
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XUSTG_ART");
                            
                            $ustgs = $ART->onread_AcquiereUsertags_Article($SET["art_eid"],TRUE);
                            $hashs = $ART->onread_AcquiereHashs_Article($SET["art_eid"]);
                            $preview = $SET["art_desc"];
                        
                            $formatted_datas["time"] = $SET["art_cdate_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 1102 : //UAT_XUSTG_RCT
                            $ART = new ARTICLE();
                        
                            $ivid = ( $SET["art_vid_url"] ) ? TRUE : FALSE;
                            $permalink = $ART->onread_AcquierePrmlk($SET["art_eid"],$ivid);
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XUSTG_RCT");
                            
                            $ustgs = $ART->onread_AcquiereUsertags_Reaction($SET["react_eid"],TRUE);
                            $hashs = $ART->onread_AcquiereHashs_Reaction($SET["react_eid"]);
                            $preview = $SET["react_body"];
                        
                            $formatted_datas["time"] = $SET["react_date_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;            
                    case 1106 : //UAT_XUSTG_MEoTSM
                            $TST = new TESTY();
                        
                            $permalink = "/f/sts/".$SET["tst_prmlk"];
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XUSTG_MEoTSM");
                                    
                            $ustgs = $TST->onread_AcquiereUsertags_Testy($SET["tst_eid"],TRUE);
                            $hashs = $TST->onread_AcquiereHashs_Testy($SET["tst_eid"]);
                            $preview = $SET["tst_msg"];
                        
                            $formatted_datas["time"] = $SET["tst_adddate_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 1107 : //UAT_XUSTG_MEoTSR
                            $TST = new TESTY();
                            $TQR = new TRENQR();
                        
                            $permalink = "/f/sts/".$SET["tst_prmlk"];
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XUSTG_MEoTSR");
                            
                            $ustgs = $TQR->pdreact_getUsertags($SET["pdrct_eid"],TRUE);
                            $hashs = $TQR->pdreact_getHashs($SET["pdrct_eid"]);
                            $preview = $SET["pdrct_text"];
                        
                            $formatted_datas["time"] = $SET["pdrct_dcrea_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 1202 : //UAT_XTSTY_AD_SBoMTBD
                            $TST = new TESTY();
                        
                            $permalink = "/f/sts/".$SET["tst_prmlk"];
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XTSTY_AD_SBoMTBD");
                                    
                            $ustgs = $TST->onread_AcquiereUsertags_Testy($SET["tst_eid"],TRUE);
                            $hashs = $TST->onread_AcquiereHashs_Testy($SET["tst_eid"]);
                            $preview = $SET["tst_msg"];
                        
                            $formatted_datas["time"] = $SET["tst_adddate_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 1212 : //UAT_XTSTY_TSR_SBoMTSM
                            $TST = new TESTY();
                            $TQR = new TRENQR();
                        
                            $permalink = "/f/sts/".$SET["tst_prmlk"];
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XTSTY_TSR_SBoMTSM");
                            
                            $ustgs = $TQR->pdreact_getUsertags($SET["pdrct_eid"],TRUE);
                            $hashs = $TQR->pdreact_getHashs($SET["pdrct_eid"]);
                            $preview = $SET["pdrct_text"];
                        
                            $formatted_datas["time"] = $SET["pdrct_dcrea_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    case 1221 : //UAT_XTSTY_TSL_SBoMTSM
                            $TST = new TESTY();
                        
                            $permalink = "/f/sts/".$SET["tst_prmlk"];
                            $action_sentence = $TXH->get_deco_text("fr","PM_UAT_XTSTY_TSL_SBoMTSM");
                                    
                            $ustgs = $TST->onread_AcquiereUsertags_Testy($SET["tst_eid"],TRUE);
                            $hashs = $TST->onread_AcquiereHashs_Testy($SET["tst_eid"]);
                            $preview = $SET["tst_msg"];
                        
                            $formatted_datas["time"] = $SET["tst_adddate_tstamp"];
                            $formatted_datas["preview"] = $preview;
                            $formatted_datas["action_sentence"] = $action_sentence;
                            $formatted_datas["perma"] = $permalink;
                        break;
                    default :
                        return;
                }
        
//                var_dump(__FILE__,__FUNCTION__,__LINE__,$formatted_datas);
//                exit();
                
                
                $TQAC = new TQR_ACCOUNT();
                $exp = $TQAC->onread_getSenderMail("ACTY_EML_NOTIFY");
                if (! $exp ) {
                    return;
                }
                
                $rcpt_eml = $TQAC->PullEmail($SET["tag_uid"]);
                $EMH = new EMAILAC_HANDLER();
                $args_eml = [
                   "exp"        => "Trenqr <notify-noreply@trenqr.com>", //DEV, TEST, DEBUG
//                   "exp"       => htmlspecialchars_decode($exp),  //PROD
//                   "rcpt"       => "dieudrichard@gmail.com", //DEV, TEST, DEBUG
                   "rcpt"       => $rcpt_eml, //PROD
                   "rcpt_uid"   => $SET["tag_uid"],
                    "object"    => $TXH->get_deco_text("fr","_PM_PREM_OBJ_PRE_001")." ".$formatted_datas["act_ufn"],
                   "catg"       => "USER_ACTION"
                ];

//               var_dump(__FILE__,__FUNCTION__,__LINE__,$args_eml);
//               var_dump(__FILE__,__FUNCTION__,__LINE__,$formatted_datas);
       //        var_dump(__FILE__,__FUNCTION__,__LINE__$args_eml,$rec_link_ccl);
//               exit();
                
                /* //DEV, TEST, DEBUG
                $WOS_MAIN_HTTPS_HOST = "http://127.0.0.1/"; 
                $WOS_SYSDIR_PRODIMAGE = "http://127.0.0.1/bart1/timg/files/img"; 
                //*/
                //*
                $WOS_MAIN_HTTPS_HOST = WOS_MAIN_HTTPS_HOST;
                $WOS_SYSDIR_PRODIMAGE = WOS_SYSDIR_PRODIMAGE;
                //*/
                
                $args_eml_marks = [
                    //ACTOR
                    "act_ufn"                   => $formatted_datas["act_ufn"],
                    "act_upsd"                  => $formatted_datas["act_upsd"],
                    "act_uppic"                 => $WOS_MAIN_HTTPS_HOST.$formatted_datas["act_uppic"],
                    "act_uhrf"                  => $WOS_MAIN_HTTPS_HOST."/".$formatted_datas["act_upsd"],
                    //TARGET
                    "tag_ufn"                   => $formatted_datas["tag_ufn"],
                    "tag_upsd"                  => $formatted_datas["tag_upsd"],
                    "tag_uppic"                 => $WOS_MAIN_HTTPS_HOST.$formatted_datas["tag_uppic"],
                    "tag_uhrf"                  => $WOS_MAIN_HTTPS_HOST."/".$formatted_datas["tag_upsd"],
                    "tag_ueml"                  => $rcpt_eml,
                    //OBJECT
                    "action_sentence"           => $formatted_datas["action_sentence"], 
                    "preview"                   => $formatted_datas["preview"], 
                    "perma"                     => $WOS_MAIN_HTTPS_HOST.$formatted_datas["perma"], 
                    //COMONS
                    "trenqr_http_root"          => $WOS_MAIN_HTTPS_HOST,
                    "trenqr_login_link"         => $WOS_MAIN_HTTPS_HOST."/login",
                    "trenqr_start_rcvy_link"    => $WOS_MAIN_HTTPS_HOST."/recovery/password",
                    "trenqr_prod_img_root"      => $WOS_SYSDIR_PRODIMAGE,
                ];
//               var_dump("LINE => ",__LINE__,"; DATAS => ",$args_eml_marks);
//               exit();
                
                /*
                 * ETAPE :
                 *      On construit l'EMAIL et on l'envoie l'EMAIL à TARGET
                 */
                $eml_code = ( $is_no_preview ) ? "emdl_acty_notify_noprw" : "emdl_acty_notify";
                $r_ = $EMH->emac_send_email_via_model($eml_code, "fr", $args_eml, $args_eml_marks);
//                var_dump("LINE => ",__LINE__,"; DATAS => ",$r_);
//                exit();
                if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) { 
                    return "__ERR_VOL_FAILED";
                }

                /*
                 * ETAPE :
                 *      On signale que l'EMAIL a été envoyé
                 */
                $now = round(microtime(true)*1000);
                $QO_I = new QUERY("qryl4pmremn1");
                $params_insert = array ( 
                    ":rcptuid"      => $RCPUID,
                    ":type"         => 1, 
                    ":uat"          => $uatid, 
                    ":ualgid"       => $uaid, 
                    ":datecrea"     => date("Y-m-d G:i:s",($now/1000)),
                    ":tstamp"       => $now
                );
                $id = $QO_I->execute($params_insert);
                //On met à jour pour insérer l'eid
                $eid = $this->entity_ieid_encode($now, $id);
                $QO_U = new QUERY("qryl4pmremn2");
                $params_update = array ( 
                    ":id"      => $id,
                    ":eid"     => $eid
                );
                $QO_U->execute($params_update);
                
                return TRUE;
            } 
            else {
                return "__ERR_VOL_UNXPTD";
            }
        } 
    
    }
    
    
    
    /***********************************************************************************************************************************/
    /********************************************************** NOTIFICATIONS **********************************************************/
    
    public function onread_NtfyNewest ($uid, $_MODE = 1, $_WFEO = FALSE) {
        //WFEO : WithFEOption
        /*
         * Permet de récupérer les Notifications les plus récentes.
         * Cette liste peut être très utile lorsqu'il s'agit de transmettre les données au niveau de FE.
         * La méthode fonctionne selon différents modes.
         * 
         * MODE :
         *  1 : Récupérer les ids des x Notifications les plus récentes
         *  2 : Récupérer les tables des x Notifications les plus récentes
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        if ( $_MODE !== 1 && $_MODE !== 2 ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
//        echo "AVANT ;";
//        exit();
        
        $datas = NULL;
        if ( $_MODE === 1 ) {
            $QO = new QUERY("qryl4pmrn4_wfeo_wrlolyo");
//            $QO = new QUERY("qryl4pmrn4_wfeo"); //[DEPUIS 09-07-15] @BOR
            $params = array ( 
                ":ref_uid"     => $uid,
                ":limit"       => $this->_DFLT_LIMIT
            );
            $datas = $QO->execute($params);
        } else {
            //On récupère les données afin de les traiter par la suite
            $QO = new QUERY("qryl4pmrn6_wrlolyo");
//            $QO = new QUERY("qryl4pmrn6"); //[DEPUIS 09-07-15] @BOR
            $params = array ( 
                ":ref_uid"     => $uid,
                ":limit"        => $this->_DFLT_LIMIT
            );
            $t__ = $QO->execute($params);
            
//            var_dump(__LINE__,__FILE__,$t__);
//            exit();
            
            if ( $t__ ) {
                foreach ($t__ as $v) {
                    switch ($v["uat_eid"]) {
                        case "UAT_XRCT_AD" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_rctn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XUSTG_ART" : 
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ustgn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XUSTG_RCT" : 
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ustgn2_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XEVL_GOEVL" : 
                        case "UAT_XEVL_GOEVL_oMA" : 
                                /*
                                 * [NOTE 10-04-15] @BOR
                                 *      A cette date, lorsqu'un utilisateur autre que le propriétaire EVAL un Artilce, on assigne le code 700 sinon 701.
                                 *      Dans cette version, on ne prend en compte (on tente) que le cas 701. Autrement, "Quelqu'un a EVAL votre publication".
                                 * ...
                                 * Grosso modo, on assimile 700 à 701.
                                 */
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_evln1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        /* TQR.VB3.0 */
                        case "UAT_XUSTG_MEoTSM" : 
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ustgn3_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XUSTG_MEoTSR" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ustgn4_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XTSTY_AD_SBoMTBD" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_tsmn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XTSTY_TSR_SBoMTSM" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_tsrn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XTSTY_TSL_SBoMTSM" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_tsln1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XREL_NWAB" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ureln1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XMTRD_NWABO" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_trabon1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XFAV_ART_FVoMI" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_fvarn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        default:
                            break;
                    }
                }
                
//                var_dump(__LINE__,__FILE__,$datas);
                
            }
            
        }
        
        return $datas;
    }
    
    public function onread_AllUnRgrGrpCount ($uid,$_OPTIONS=NULL) {
        /*
         * Permet de récupérer le nombre de Notifications non prises en compte groupées par "nature".
         * Cela permet par exemple de savoir le nombre de Notifications : "Non lue" pour les Notifications, Alert, News, etc ...
         * Ces données sont interessante pour FE en ce qu'elles lui permettent d'informer de manière précise l'utilisateur.
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        $datas = [];
        if ( $_OPTIONS && !empty($_OPTIONS["test_force_null_datas"]) && $_OPTIONS["test_force_null_datas"] === TRUE ) {
            $datas = [];
        } else {
            $QO = new QUERY("qryl4pmrn5_wrlolyo");
    //        $QO = new QUERY("qryl4pmrn5"); //[DEPUIS 09-07-15] @BOR
            $params = array ( 
                ":ref_uid"  => $uid
            );
            $datas = $QO->execute($params);
        }
        
        
        /*
         * [NOTE 16-04-15] @BOR
         *      On spécifie par nos propres moyens '0' sinon la méthode renverra NULL.
         *      Or NULL ne veut pas dire 0 pour FE.
         *      De plus, si on ne précisait pas les intitulés, FE ne comprendrait pas
         * [NOTE 09-07-15] @BOR
         *      J'ai ajouté la prise en compte du cas où l'objet de référence est introuvable.
         *      Sans cette spécificité, le système affichera des données erronées qui risqueraient d'agacer l'utilisateur
         */
        if (! $datas ) {
            $datas = [
                [ 
                    "typ"   => "PRT_ALERT_ORG", 
                    "cn"    => 0
                ], [ 
                    "typ"   => "PRT_ALERT_RED", 
                    "cn"    => 0
                ], [ 
                    "typ"   => "PRT_INFO", 
                    "cn"    => 0
                ], [ 
                    "typ"   => "PRT_NEWS", 
                    "cn"    => 0
                ], [ 
                    "typ"   => "PRT_NOTFY", 
                    "cn"    => 0
                ]
            ];
        } else {
            /*
             * [DEPUIS 09-07-15] @BOR
             *      On vérifie qu'on a tous les couples. 
             *      Si un couple manque, on crée sa représentation NULL
             */
            $typs = array_column($datas,"typ");
            if (! in_array("PRT_ALERT_ORG", $typs) ) {
                $datas[] = [
                    "typ"   => "PRT_ALERT_ORG", 
                    "cn"    => 0
                ];
            }
            if (! in_array("PRT_ALERT_RED", $typs) ) {
                $datas[] = [
                    "typ"   => "PRT_ALERT_RED", 
                    "cn"    => 0
                ];
            }
            if (! in_array("PRT_INFO", $typs) ) {
                $datas[] = [
                    "typ"   => "PRT_INFO", 
                    "cn"    => 0
                ];
            }
            if (! in_array("PRT_NEWS", $typs) ) {
                $datas[] = [
                    "typ"   => "PRT_NEWS", 
                    "cn"    => 0
                ];
            }
            if (! in_array("PRT_NOTFY", $typs) ) {
                $datas[] = [
                    "typ"   => "PRT_NOTFY", 
                    "cn"    => 0
                ];
            }
        }
        
        return $datas;
    }
    
    public function onupdate_ntfyPulleds($uid, $plds) {
        /*
         * Permet de mettre à jour une ou plusieurs occurences de Notification en indiquant que :
         *  -> Les Notifications ont été envoyées au niveau de FE.
         *  -> Les Notifications ont été reportées (coin gauche, nombre rouge) auprès de l'utilisateur.
         * 
         * NOTE : Les données proviennent de FE. Aussi, on doit garder à l'esprit qu'elles peuvent être compromises.
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$uid,$plds]);
        
        //brks = Les Notifications qui sont non conformes
        $brks = [];
        foreach ( $plds as $tab ) {
            if ( !( key_exists("i",$tab) && $tab["i"] ) | !( key_exists("t",$tab) && $tab["t"] ) ) {
                $brks[] = $tab["i"];
            } else  {
                $t__ = $this->onupdate_ntfyPulled($uid,$tab["i"],$tab["t"]);
                if (! $t__ ) {
                    $brks[] = $tab["i"];
                }
            }
        }
        
        return ( $brks ) ? $brks : TRUE;
    }
    
    private function onupdate_ntfyPulled($uid, $i, $t) {
        /*
         * Permet de mettre à jour une occurence de Notification en indiquant qu'elle a été notifiée ou traitée au niveau de FE sans pour autant que l'utilisateur l'ait lu.
         * NOTE : 
         *  - Les données proviennent de FE. Aussi, on doit garder à l'esprit qu'elles peuvent être compromises.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$i,$t]);
        
        /*
         * ETAPE :
         * On vérifie que la valeur TIMESTAMP passée en paramètre est valide.
         * Pour cela on vérifie qu'on arrive à la transformer en date valide.
         */
        if ( floatval($t) > 1388534400000 && floatval($t) < 1893456000000 ) {
            $td = date("Y-m-d G:i:s",(floatval($t)/1000));
        } else {
//            echo __LINE__;
            return FALSE;
        }
        
        /*
         * ETAPE : 
         * On vérifie qu'il y a une concordance entre la Notification et l'utilisateur passé en paramètre.
         */
        $QO = new QUERY("qryl4pmrn11");
        $params = array ( 
            ":ref_uid"  => $uid,
            ":ref_pid"  => $i
        );
        $d__ = $QO->execute($params);
        if ( !$d__ || count($d__) > 1 ) {
//            echo __LINE__;
            return FALSE;
        } else if ( floatval($t) < $d__[0]["pmrpt_datecrea_tstamp"] ) {
//            echo __LINE__;
            return FALSE;
        } else if ( isset($d__[0]["pmrpt_datepull_tstamp"]) ) {
            /*
             * On signale que l'élément a déjà fait l'objet d'un traitement.
             * Il ne s'agit pas pour autant d'un mauvais cas. On est entre les deux.
             */
            
            return -1;
        }
        
//        var_dump(__LINE__,$d__,$uid,$i);
//        exit();
        /*
         * ETAPE : 
         * On met à jour l'occurence en lui assignant la date de "Pull".
         */
        $QO = new QUERY("qryl4pmrn12");
        $params = array ( 
            ":ref_pid"  => $d__[0]["pmrpt_id"],
            ":time"     => $td,
            ":tmstp"    => floatval($t)
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    public function onupdate_ntfyRogers($uid, $rgrs) { 
        /*
         * Permet de mettre à jour plusieurs occurences de Notifications en indiquant qu'elles ont été lues par l'utilisateur.
         * NOTE : Les données proviennent de FE. Aussi, on doit garder à l'esprit qu'elles peuvent être compromises.
         * 
         * ARCHITECTURE DU TABLEAU : 
         */
         $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$uid,$rgrs]);
         
         //brks = Les Notifications qui sont non conformes
         $brks = [];
         foreach ( $rgrs as $tab ) {
            if ( !( key_exists("i",$tab) && $tab["i"] ) | !( key_exists("t",$tab) && $tab["t"] ) ) {
                $brks[] = $tab["i"];
            } else  {
                $t__ = $this->onupdate_ntfyRoger($uid,$tab["i"],$tab["t"]);
                if (! $t__ ) {
                    $brks[] = $tab["i"];
                }
//                var_dump(__LINE__,$t__);
            }
         }
        
         return ( $brks ) ? $brks : TRUE;
    }
    
    private function onupdate_ntfyRoger($uid,$i,$t) {
        /*
         * Permet de mettre à jour une occurence de Notification en indiquant qu'elle a été lue par l'utilisateur.
         * NOTE : 
         *  - Les données proviennent de FE. Aussi, on doit garder à l'esprit qu'elles peuvent être compromises.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$i,$t]);
        
        /*
         * ETAPE :
         * On vérifie que la valeur TIMESTAMP passée en paramètre est valide.
         * Pour cela on vérifie qu'on arrive à la transformer en date valide.
         */
        if ( floatval($t) > 1388534400000 && floatval($t) < 1893456000000 ) {
            $td = date("Y-m-d G:i:s",(floatval($t)/1000));
        } else {
//            echo __LINE__;
            return FALSE;
        }
        
        /*
         * ETAPE : 
         * On vérifie qu'il y a une concordance entre la Notification et l'utilisateur passé en paramètre.
         */
        $QO = new QUERY("qryl4pmrn11");
        $params = array ( 
            ":ref_uid"  => $uid,
            ":ref_pid"  => $i
        );
        $d__ = $QO->execute($params);
//        var_dump(__LINE__,floatval($t),floatval($d__[0]["pmrpt_datecrea_tstamp"]));
        if ( !$d__ || count($d__) > 1 ) {
//            echo __LINE__;
            return FALSE;
        } else if ( floatval($t) < floatval($d__[0]["pmrpt_datecrea_tstamp"]) ) {
//            echo __LINE__;
            return FALSE;
        } else if ( isset($d__[0]["pmrpt_datergr_tstamp"]) ) {
            /*
             * On signale que l'élément a déjà fait l'objet d'un traitement.
             * Il ne s'agit pas pour autant d'un mauvais cas. On est entre les deux.
             */
            return -1;
        }
        
//        var_dump(__LINE__,$d__,isset($d__[0]["pmrpt_datergr_tstamp"]),$uid,$i);
//        exit();
        /*
         * ETAPE : 
         * On met à jour l'occurence en lui assignant la date de "Pull".
         */
        $QO = new QUERY("qryl4pmrn13");
        $params = array ( 
            ":ref_pid"  => $d__[0]["pmrpt_id"],
            ":time"     => $td,
            ":tmstp"    => floatval($t)
        );
        $QO->execute($params);
        
        /*
         * ETAPE : 
         * On vérifie si la Notification a une date pull.
         * Dans le cas contraire, on l'insère pour plus de cohérence.
         */
        if (! isset($d__[0]["pmrpt_datepull_tstamp"]) ) {
            $QO = new QUERY("qryl4pmrn12");
            $params = array ( 
                ":ref_pid"  => $d__[0]["pmrpt_id"],
                ":time"     => $td,
                ":tmstp"    => floatval($t)
            );
            $QO->execute($params);
        }
        
        return TRUE;
        
    }
    
    public function onread_NtfyFrom ($uid, $fri, $frt, $_DIR = "btm", $_MODE = 1, $_WFEO = FALSE) {
        //WFEO : WithFEOption; RLOLY : ReLevantOnLY
        /*
         * Permet de récupérer les Notifications en se basant sur une Notification de référence désignée par son identifiant et la date d'enregistrement lié.
         * 
         * MODE :
         *  1 : Récupérer les ids des x Notifications les plus récentes
         *  2 : Récupérer les tables des x Notifications les plus récentes
         * 
         * [NOTE 08-07-15] @BOR
         *  Les modifications apportées (inclusion de l'option RLOLY) permettent de ne prendre en compte que les NOTIFICATIONs non-obselète afin de ne pas "casser" la chaine.
         *  En effet, dans le cas de BTM par exemple, si toutes les NTFs récupérées après la référence sont obselète, le système se bloque et on ne peut plus continuer à aller en profondeur.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( $_MODE !== 1 && $_MODE !== 2 ) {
            return "__ERR_VOL_WRG_DATAS";
        }
//        var_dump(__LINE__,func_get_args());
//        $datas = NULL;
        if ( $_MODE === 1 ) {
//            $QO = ( strtolower($_DIR) === "btm" ) ? new QUERY("qryl4pmrn7_wfeo") : new QUERY("qryl4pmrn8_wfeo"); //[DEPUIS 08-07-15] @BOR
            $QO = ( strtolower($_DIR) === "btm" ) ? new QUERY("qryl4pmrn7_wfeo_wrlolyo") : new QUERY("qryl4pmrn8_wfeo_wrlolyo");
            $params = array ( 
                ":ref_uid"  => $uid,
                ":fri"      => $fri,
                ":frt"      => $frt,
                ":limit"    => $this->_DFLT_LIMIT
            );
            $datas = $QO->execute($params);
        } else {
            //On récupère les données afin de les traiter par la suite
            $QO = ( strtolower($_DIR) === "btm" ) ? new QUERY("qryl4pmrn9_wrlolyo") : new QUERY("qryl4pmrn10_wrlolyo");
//            $QO = ( strtolower($_DIR) === "btm" ) ? new QUERY("qryl4pmrn9") : new QUERY("qryl4pmrn10");  //[DEPUIS 08-07-15] @BOR
            $params = array ( 
                ":ref_uid"  => $uid,
                ":fri"      => $fri,
                ":frt"      => $frt,
                ":limit"    => $this->_DFLT_LIMIT
            );
            $t__ = $QO->execute($params);
            
//            var_dump(__LINE__,__FILE__,$t__);
//            exit();
            
            if ( $t__ ) {
                foreach ($t__ as $v) {
                    switch ($v["uat_eid"]) {
                        case "UAT_XRCT_AD" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_rctn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XUSTG_ART" : 
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ustgn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XUSTG_RCT" : 
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ustgn2_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XEVL_GOEVL" : 
                        case "UAT_XEVL_GOEVL_oMA" : 
                                /*
                                 * [NOTE 10-04-15] @BOR
                                 * A cette date, lorsqu'un utilisateur autre que le propriétaire EVAL un Artilce, on assigne le code 700 sinon 701.
                                 * Dans cette version, on ne prend en compte (on tente) que le cas 701. Autrement, "Quelqu'un a EVAL votre publication".
                                 * ...
                                 * Grosso modo, on assimile 700 à 701.
                                 */
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_evln1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        /* TQR.VB3.0 */
                        case "UAT_XUSTG_MEoTSM" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ustgn3_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XUSTG_MEoTSR" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ustgn4_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XTSTY_AD_SBoMTBD" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_tsmn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XTSTY_TSR_SBoMTSM" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_tsrn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XTSTY_TSL_SBoMTSM" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_tsln1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XREL_NWAB" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_ureln1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XMTRD_NWABO" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_trabon1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        case "UAT_XFAV_ART_FVoMI" :
                                $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY("qryl4pmr_fvarn1_wfeo");
                                $params_select = array ( 
                                    ":id"   => $v["pmrpt_id"]
                                );
                                $datas[] = $QO_S->execute($params_select)[0];
                            break;
                        default:
                            break;
                    }
                }
                
//                var_dump(__LINE__,__FILE__,$datas);
//                exit(); 
                
            }
            
        }
        
        return $datas;
    }
    
    /********************************************************/
    /*** NOTIFICATIONS - PRODUCT SCOPE **********************/
    /********************************************************/
    
    private function oncreate_product () {
        
    }
    
    /********************************************************/
    /*** NOTIFICATIONS - LEGALS SCOPE ***********************/
    /********************************************************/
    
    private function oncreate_legals () {
        
    }
    
    /********************************************************/
    /*** NOTIFICATIONS - ACCOUNT SCOPE **********************/
    /********************************************************/
    
    private function oncreate_account () {
        
    }
    
    /********************************************************/
    /*** NOTIFICATIONS - RELATION SCOPE **********************/
    /********************************************************/
    
    private function NOTF_oncreate_relation ($k, $rcptuid, $d, $_WFEO = FALSE) {
        //k = La clé représentant l'action. d = data les données sur l'activité qui serviront à créer la Notification. WFEM (WithFrontEndMode) : 
        /*
         * Permet de créer des occurrences de Notification pour le scope REACTION. 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$k,$rcptuid,$d]);
        
        $ids = $tabs = [];
        
        if (! in_array($k, ["UAT_XREL_NWAB"]) ) {
            return;
        }
        
        foreach ($d as $v) {
//            var_dump(__LINE__,floatval($rcptuid),floatval($v["ualg_actuid"]));
//            exit();
            /*
             * ETAPE :
             *      On s'assure qu'on ne notifie pas quand l'ACTOR est TARGET
             */
            if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                continue;
            } 

            /*
             * ETAPE :  
             *      On crée la Notification au niveau de la base de données
             */
            $now = round(microtime(true)*1000);
            $QO_I = new QUERY("qryl4pmrn1");
            $params_insert = array ( 
                ":rcptuid"      => $rcptuid,
                ":type"         => 1, 
                ":uat"          => intval($v["ualg_uatid"]), 
                ":isAuto"       => 0, 
                ":ualgid"       => $v["ualgid"], 
                ":datecrea"     => date("Y-m-d G:i:s",($now/1000)),
                ":tstamp"       => $now
            );
            /*
             * [NOTE 20-02-2015] @Louky
             *      Pour l'heure, ids ne sert à rien.
             *      Il ne faut donc pas lui préter attention
             */
            $ids[] = $id = $QO_I->execute($params_insert);

            //On met à jour pour insérer l'eid
            $eid = $this->entity_ieid_encode($now, $id);
            $QO_U = new QUERY("qryl4pmrn2");
            $params_update = array ( 
                ":id"      => $id,
                ":eid"     => $eid
            );
            $QO_U->execute($params_update);
            
//            var_dump(__FILE__,$id);
            
           /*
            * ETAPE :
            *      On récupère la table en fonction de la demande de CALLER
            */
           $WFEO_QRY = "";
           switch ($k) {
               case "UAT_XREL_NWAB" : //REL_NEWABO
                       $WFEO_QRY = "qryl4pmr_ureln1_wfeo";
                   break;
               default:
                   return;
           }

           $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY($WFEO_QRY);
           $params_select = array ( ":id" => $id );
           $datas = $QO_S->execute($params_select);
           $tabs[] = ( $datas ) ? $datas[0] : [];
        }
        
        return $tabs;
    }
    
    /********************************************************/
    /*** NOTIFICATIONS - TRENDS SCOPE ***********************/
    /********************************************************/
    
    private function NOTF_oncreate_trend ($k, $rcptuid, $d, $_WFEO = FALSE) {
        //k = La clé représentant l'action. d = data les données sur l'activité qui serviront à créer la Notification. WFEM (WithFrontEndMode) : 
        /*
         * Permet de créer des occurrences de Notification pour le scope REACTION. 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$k,$rcptuid,$d]);
        
        $ids = $tabs = [];
        
        if (! in_array($k, ["UAT_XMTRD_NWABO"]) ) {
            return;
        }
        
        foreach ($d as $v) {
//            var_dump(__LINE__,floatval($rcptuid),floatval($v["ualg_actuid"]));
//            exit();
            /*
             * ETAPE :
             *      On s'assure qu'on ne notifie pas quand l'ACTOR est TARGET
             */
            if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                continue;
            } 

            /*
             * ETAPE :  
             *      On crée la Notification au niveau de la base de données
             */
            $now = round(microtime(true)*1000);
            $QO_I = new QUERY("qryl4pmrn1");
            $params_insert = array ( 
                ":rcptuid"      => $rcptuid,
                ":type"         => 1, 
                ":uat"          => intval($v["ualg_uatid"]), 
                ":isAuto"       => 0, 
                ":ualgid"       => $v["ualgid"], 
                ":datecrea"     => date("Y-m-d G:i:s",($now/1000)),
                ":tstamp"       => $now
            );
            /*
             * [NOTE 20-02-2015] @Louky
             * Pour l'heure, ids ne sert à rien.
             * Il ne faut donc pas lui préter attention
             */
            $ids[] = $id = $QO_I->execute($params_insert);

            //On met à jour pour insérer l'eid
            $eid = $this->entity_ieid_encode($now, $id);
            $QO_U = new QUERY("qryl4pmrn2");
            $params_update = array ( 
                ":id"      => $id,
                ":eid"     => $eid
            );
            $QO_U->execute($params_update);
            
//            var_dump(__FILE__,$id);
            
           /*
            * ETAPE :
            *      On récupère la table en fonction de la demande de CALLER
            */
           $WFEO_QRY = "";
           switch ($k) {
               case "UAT_XMTRD_NWABO" : //REL_NEWABO
                       $WFEO_QRY = "qryl4pmr_trabon1_wfeo";
                   break;
               default:
                   return;
           }

           $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY($WFEO_QRY);
           $params_select = array ( ":id" => $id );
           $datas = $QO_S->execute($params_select);
           $tabs[] = ( $datas ) ? $datas[0] : [];
        }
        
        return $tabs;
    }
    
    
    
    /********************************************************/
    /*** NOTIFICATIONS - ARTCLE SCOPE ***********************/
    /********************************************************/
    
    private function oncreate_article () {
        
    }
    
    /********************************************************/
    /*** NOTIFICATIONS - REACTION SCOPE *********************/
    /********************************************************/
    
    private function NOTF_oncreate_reaction ($k, $rcptuid, $d, $_WFEO = FALSE) {
        
        //k = La clé représentant l'action. d = data les données sur l'activité qui serviront à créer la Notification. WFEM (WithFrontEndMode) : 
        /*
         * Permet de créer des occurrences de Notification pour le scope REACTION. 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$k,$rcptuid,$d]);
        
        $ids = $tabs = [];
        switch ($k) {
            case "UAT_XRCT_AD_oMA" :
                    foreach ($d as $v) {
//                        var_dump(__LINE__,floatval($rcptuid),floatval($v["ualg_actuid"]));
                        /*
                         * [NOTE 08-04-15] @BOR
                         *      On ne crée pas de notification quand le propriétaire du commentaire est le propiétaire de l'Article
                         */
                        if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                            continue;
                        } else {
                            /*
                             * [NOTE 11-04-15] @BOR
                             * ETAPE :
                             *      On vérifie si l'utilisateur qui a écrit le Commentaire a tagué le propriétaire de l'Article.
                             *      Dans ce cas, on ne signale pas l'ajout du commentaire, cela se fera via le module USERTAG.
                             *      Cette décision est purement d'ordre fonctionnelle 
                             */
                            $ART = new ARTICLE();
                            $u_tab = $ART->onload_react_list_usertags($v["ualg_refobj"]);
                            if ( !empty($u_tab) && in_array($rcptuid, array_column($u_tab,"tgtuid")) ) {
                                continue;
                            }
                        }
                
                        /*
                         * ETAPE :
                         *      On crée la Notification au niveau de la base de données
                         */
                        $now = round(microtime(true)*1000);
                        $QO_I = new QUERY("qryl4pmrn1");
                        $params_insert = array ( 
                            ":rcptuid"      => $rcptuid,
                            ":type"         => 1, 
                            ":uat"          => 601, 
                            ":isAuto"       => 0, 
                            ":ualgid"       => $v["ualgid"], 
                            ":datecrea"     => date("Y-m-d G:i:s",($now/1000)),
                            ":tstamp"       => $now
                        );
                        /*
                         * [NOTE 20-02-2015] @Louky
                         *      Pour l'heure, ids ne sert à rien.
                         *      Il ne faut donc pas lui préter attention
                         */
                        $ids[] = $id = $QO_I->execute($params_insert);
                        
                        /*
                         * ETAPE : 
                         *      On met à jour pour insérer l'eid
                         */
                        $eid = $this->entity_ieid_encode($now, $id);
                        $QO_U = new QUERY("qryl4pmrn2");
                        $params_update = array ( 
                            ":id"      => $id,
                            ":eid"     => $eid
                        );
                        $QO_U->execute($params_update);
                        
                        /*
                         * ETAPE : 
                         *      On récupère la table en fonction de la demande de CALLER
                         * [NOTE 03-03-15] @Lou
                         *      Il serait certainement préférable de réaliser la collecte des données en une seule fois
                         *      Cela permettrait des gains de performance.
                         *      Pour cela, il suffira d'exécuter la requête de manière volatile pour ajouter une liste d'identifiants ce qu'on ne peut pas faire avec la version en mode "stockée".
                         */
                        if (! $_WFEO ) {
                            $QO_S = new QUERY("qryl4pmrn3");
                            $params_select = array ( 
                                ":id"      => $id
                            );
                            $tabs[] = $QO_S->execute($params_select)[0];
                        } else {
                            $QO_S = new QUERY("qryl4pmr_rctn1_wfeo");
                            $params_select = array ( 
                                ":id"      => $id
                            );
                            $tabs[] = $QO_S->execute($params_select)[0];
                        }
                    }
                break;
            default:
                return;
        }
        
        return $tabs;
        
    }
    
    /********************************************************/
    /*** NOTIFICATIONS - EVALUATION SCOPE *******************/
    /********************************************************/
    
    private function NOTF_oncreate_evaluation ($k, $rcptuid, $d, $_WFEO = FALSE) {
        //k = La clé représentant l'action. d = data les données sur l'activité qui serviront à créer la Notification. WFEM (WithFrontEndMode) : 
        /*
         * Permet de créer des occurrences de Notification pour le scope REACTION. 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$k,$rcptuid,$d]);
        
        $ids = $tabs = [];
        switch ($k) {
            case "UAT_XEVL_GOEVL" :
            case "UAT_XEVL_GOEVL_oMA" :
                    foreach ($d as $v) {
    //                var_dump(__LINE__,floatval($rcptuid),floatval($v["ualg_actuid"]));
                        /*
                         * [NOTE 08-04-15] @BOR
                         * On ne crée pas de notification quand le propriétaire du commentaire est le propiétaire de l'Article
                         */
                        if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                            continue;
                        } 

                        //On crée la Notification au niveau de la base de données
                        $now = round(microtime(true)*1000);
                        $QO_I = new QUERY("qryl4pmrn1");
                        $params_insert = array ( 
                            ":rcptuid"      => $rcptuid,
                            ":type"         => 1, 
                            ":uat"          => intval($v["ualg_uatid"]), 
                            ":isAuto"       => 0, 
                            ":ualgid"       => $v["ualgid"], 
                            ":datecrea"     => date("Y-m-d G:i:s",($now/1000)),
                            ":tstamp"       => $now
                        );
                        /*
                         * [NOTE 20-02-2015] @Louky
                         * Pour l'heure, ids ne sert à rien.
                         * Il ne faut donc pas lui préter attention
                         */
                        $ids[] = $id = $QO_I->execute($params_insert);

                        //On met à jour pour insérer l'eid
                        $eid = $this->entity_ieid_encode($now, $id);
                        $QO_U = new QUERY("qryl4pmrn2");
                        $params_update = array ( 
                            ":id"      => $id,
                            ":eid"     => $eid
                        );
                        $QO_U->execute($params_update);

                        //On récupère la table en fonction de la demande de CALLER
                        /*
                         * [NOTE 03-03-15] @Lou
                         * Il serait certainement préférable de réaliser la collecte des données en une seule fois
                         * Cela permettrait des gains de performance.
                         * Pour cela, il suffira d'exécuter la requête de manière volatile pour ajouter une liste d'identifiants ce qu'on ne peut pas faire avec la version en mode "stockée".
                         */
                        if (! $_WFEO ) {
                            $QO_S = new QUERY("qryl4pmrn3");
                            $params_select = array ( 
                                ":id"      => $id
                            );
                            $tabs[] = $QO_S->execute($params_select)[0];
                        } else {
                            $QO_S = new QUERY("qryl4pmr_evln1_wfeo");
                            $params_select = array ( 
                                ":id"      => $id
                            );
                            $tabs[] = $QO_S->execute($params_select)[0];
                        }
                    }
                break;
            default:
                return;
        }
        
        return $tabs;
    }
     
    
    /********************************************************/
    /*** NOTIFICATIONS - USERTAG SCOPE **********************/
    /********************************************************/
    
    private function NOTF_oncreate_usertag ($k, $rcptuid, $d, $_WFEO = FALSE) {
        //k = La clé représentant l'action. d = data les données sur l'activité qui serviront à créer la Notification. WFEM (WithFrontEndMode) : 
        /*
         * Permet de créer des occurrences de Notification pour le scope REACTION. 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$k,$rcptuid,$d]);
        
        
        if (! in_array($k,["UAT_XUSTG_ART","UAT_XUSTG_RCT","UAT_XUSTG_MEoTSM","UAT_XUSTG_MEoTSR"]) ) {
            return;
        }
        
        $ids = $tabs = [];
        $query;
        foreach ($d as $v) {
//                var_dump(__LINE__,floatval($rcptuid),floatval($v["ualg_actuid"]));
                        
            if ( strtoupper($k) === "UAT_XUSTG_ART" ) {
               /*
                * [NOTE 08-04-15] @BOR
                * On ne crée pas de Notification quand celui qui est tagué est celui qui tague
                */
                if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                    continue;
                }
            } 
            else if ( strtoupper($k) === "UAT_XUSTG_RCT" ) {
               /*
                * [NOTE 08-04-15] @BOR
                * On ne crée pas de Notification quand celui qui est tagué est celui qui tague.
                */
                if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                    continue;
                } 
                /*
                else {
                    $ART = new ARTICLE();
                    $a_tab = $ART->exists_with_id($v["ualg_refobj"]);
                    if ( floatval($a_tab["art_accid"]) === floatval($v["ualg_actuid"]) ) {
                       /*
                        * [NOTE 11-04-15] @BOR
                        * CAS : Il ne faut pas créer de Notification si le propriétaire de l'Article est celui cité dans le commentaire.
                        *       En effet, il recevra forcement une Notification lui indiquant qu'un nouveau Commentaire est disponible.
                        continue;
                    }
                }
                //*/
            } 
            else if ( in_array(strtoupper($k), ["UAT_XUSTG_MEoTSM","UAT_XUSTG_MEoTSR"]) ) {
                if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                    continue;
                } 
            }

            //On crée la Notification au niveau de la base de données
            $now = round(microtime(true)*1000);
            $QO_I = new QUERY("qryl4pmrn1");
            $params_insert = array ( 
                ":rcptuid"      => $rcptuid,
                ":type"         => 1, 
                ":uat"          => intval($v["ualg_uatid"]), 
                ":isAuto"       => 0, 
                ":ualgid"       => $v["ualgid"], 
                ":datecrea"     => date("Y-m-d G:i:s",($now/1000)),
                ":tstamp"       => $now
            );
            /*
             * [NOTE 20-02-2015] @Louky
             * Pour l'heure, ids ne sert à rien.
             * Il ne faut donc pas lui préter attention
             */
            $ids[] = $id = $QO_I->execute($params_insert);
                        
            //On met à jour pour insérer l'eid
            $eid = $this->entity_ieid_encode($now, $id);
            $QO_U = new QUERY("qryl4pmrn2");
            $params_update = array ( 
                ":id"      => $id,
                ":eid"     => $eid
            );
            $QO_U->execute($params_update);

            //On récupère la table en fonction de la demande de CALLER
            /*
             * [NOTE 03-03-15] @Lou
             *      Il serait certainement préférable de réaliser la collecte des données en une seule fois
             *      Cela permettrait des gains de performance.
             *      Pour cela, il suffira d'exécuter la requête de manière volatile pour ajouter une liste d'identifiants ce qu'on ne peut pas faire avec la version en mode "stockée".
             */
            switch ($k) {
            case "UAT_XUSTG_ART" :
            case "UAT_XUSTG_RCT" :
                        /*
                         * [NOTE 12-04-16]
                         *      Je n'ai fait que reproduire l'ancien algorithme. Aussi l'utilisation (qui me semble louche) de la requete "qryl4pmr_rctn1_wfeo" n'est pas de mon ressort
                         */
                        $query = (! $_WFEO ) ? "qryl4pmrn3" : "qryl4pmr_rctn1_wfeo";
                    break;
                case "UAT_XUSTG_MEoTSM" :
                        $query = (! $_WFEO ) ? "qryl4pmrn3" : "qryl4pmr_ustgn3_wfeo";
                    break;
                case "UAT_XUSTG_MEoTSR" :
                        $query = (! $_WFEO ) ? "qryl4pmrn3" : "qryl4pmr_ustgn4_wfeo";
                    break;
                default:
                    return;
            }
            
            $QO_S = new QUERY($query);
            $params_select = array ( 
                ":id" => $id
            );
            $datas = $QO_S->execute($params_select);

            $tabs[] = ( $datas ) ? $datas[0] : [];
        }
        
        return $tabs;
        
    }
    
    
    /********************************************************/
    /*** NOTIFICATIONS - TESTY SCOPE ************************/
    /********************************************************/
    
    private function NOTF_oncreate_testies ($k, $rcptuid, $d, $_WFEO = FALSE) {
        //k = La clé représentant l'action. d = data les données sur l'activité qui serviront à créer la Notification. WFEM (WithFrontEndMode) : 
        /*
         * Permet de créer des occurrences de Notification pour le scope REACTION. 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$k,$rcptuid,$d]);
        
        $ids = $tabs = [];
        
        if (! in_array($k, ["UAT_XTSTY_AD_SBoMTBD","UAT_XTSTY_TSR_SBoMTSM","UAT_XTSTY_TSL_SBoMTSM"]) ) {
            return;
        }
        
        foreach ($d as $v) {
//            var_dump(__LINE__,floatval($rcptuid),floatval($v["ualg_actuid"]));
//            exit();
            /*
             * ETAPE :
             *      On s'assure qu'on ne notifie pas quand l'ACTOR est TARGET
             */
            if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                continue;
            } 

            /*
             * ETAPE :  
             *      On crée la Notification au niveau de la base de données
             */
            $now = round(microtime(true)*1000);
            $QO_I = new QUERY("qryl4pmrn1");
            $params_insert = array ( 
                ":rcptuid"      => $rcptuid,
                ":type"         => 1, 
                ":uat"          => intval($v["ualg_uatid"]), 
                ":isAuto"       => 0, 
                ":ualgid"       => $v["ualgid"], 
                ":datecrea"     => date("Y-m-d G:i:s",($now/1000)),
                ":tstamp"       => $now
            );
            /*
             * [NOTE 20-02-2015] @Louky
             * Pour l'heure, ids ne sert à rien.
             * Il ne faut donc pas lui préter attention
             */
            $ids[] = $id = $QO_I->execute($params_insert);

            //On met à jour pour insérer l'eid
            $eid = $this->entity_ieid_encode($now, $id);
            $QO_U = new QUERY("qryl4pmrn2");
            $params_update = array ( 
                ":id"      => $id,
                ":eid"     => $eid
            );
            $QO_U->execute($params_update);
            
//            var_dump(__FILE__,$id);
            
           /*
            * ETAPE :
            *      On récupère la table en fonction de la demande de CALLER
            */
           $WFEO_QRY = "";
           switch ($k) {
               case "UAT_XTSTY_AD_SBoMTBD" : //ADD_TESTY
                       $WFEO_QRY = "qryl4pmr_tsmn1_wfeo";
                   break;
               case "UAT_XTSTY_TSR_SBoMTSM" : //ADD_TESTY_REACTIONS
                       $WFEO_QRY = "qryl4pmr_tsrn1_wfeo";
                   break;
               case "UAT_XTSTY_TSL_SBoMTSM" : //ADD_TESTY_LIKE
                       $WFEO_QRY = "qryl4pmr_tsln1_wfeo";
                   break;
               default:
                   return;
           }

           $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY($WFEO_QRY);
           $params_select = array ( ":id" => $id );
           $datas = $QO_S->execute($params_select);
           $tabs[] = ( $datas ) ? $datas[0] : [];
        }
        
        return $tabs;
    }
    
    
    /********************************************************/
    /*** NOTIFICATIONS - ART_FAV SCOPE **********************/
    /********************************************************/
    
    private function NOTF_oncreate_fav ($k, $rcptuid, $d, $_WFEO = FALSE) {
        //k = La clé représentant l'action. d = data les données sur l'activité qui serviront à créer la Notification. WFEM (WithFrontEndMode) : 
        /*
         * Permet de créer des occurrences de Notification pour le scope REACTION. 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$k,$rcptuid,$d]);
        
        $ids = $tabs = [];
        
        if (! in_array($k, ["UAT_XFAV_ART_FVoMI"]) ) {
            return;
        }
        
        foreach ($d as $v) {
//            var_dump(__LINE__,floatval($rcptuid),floatval($v["ualg_actuid"]));
//            exit();
            /*
             * ETAPE :
             *      On s'assure qu'on ne notifie pas quand l'ACTOR est TARGET
             */
            if ( floatval($rcptuid) === floatval($v["ualg_actuid"]) ) {
                continue;
            } 

            /*
             * ETAPE :  
             *      On crée la Notification au niveau de la base de données
             */
            $now = round(microtime(true)*1000);
            $QO_I = new QUERY("qryl4pmrn1");
            $params_insert = array ( 
                ":rcptuid"      => $rcptuid,
                ":type"         => 1, 
                ":uat"          => intval($v["ualg_uatid"]), 
                ":isAuto"       => 0, 
                ":ualgid"       => $v["ualgid"], 
                ":datecrea"     => date("Y-m-d G:i:s",($now/1000)),
                ":tstamp"       => $now
            );
            /*
             * [NOTE 20-02-2015] @Louky
             * Pour l'heure, ids ne sert à rien.
             * Il ne faut donc pas lui préter attention
             */
            $ids[] = $id = $QO_I->execute($params_insert);

            //On met à jour pour insérer l'eid
            $eid = $this->entity_ieid_encode($now, $id);
            $QO_U = new QUERY("qryl4pmrn2");
            $params_update = array ( 
                ":id"      => $id,
                ":eid"     => $eid
            );
            $QO_U->execute($params_update);
            
//            var_dump(__FILE__,$id);
            
           /*
            * ETAPE :
            *      On récupère la table en fonction de la demande de CALLER
            */
           $WFEO_QRY = "";
           switch ($k) {
               case "UAT_XFAV_ART_FVoMI" : //FAV_ART
                       $WFEO_QRY = "qryl4pmr_fvarn1_wfeo";
                   break;
               default:
                   return;
           }

           $QO_S = (! $_WFEO ) ? new QUERY("qryl4pmrn3") : new QUERY($WFEO_QRY);
           $params_select = array ( ":id" => $id );
           $datas = $QO_S->execute($params_select);
           $tabs[] = ( $datas ) ? $datas[0] : [];
        }
        
        return $tabs;
    }
    
    /******************************************************************************************************************************************************/
    /********************************************************************* ONREAD *************************************************************************/

    
    /****************************************************************************************************************************************************/
    /********************************************************************* ONDELETE *********************************************************************/
                                                                                    

    /***************************************************************************************************************************************************************************/
    /**************************************************************************** GETTERS & SETTERS ****************************************************************************/
    /***************************************************************************************************************************************************************************/

}