<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TRPG_GTPG
 *
 * @author arsphinx
 */
class WORKER_TRPG_GTPG extends WORKER {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
//                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $STOI->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        /**
         * Le but est de rediriger vers le bon URQ. On ne vérifie rien d'autre.
         */
        if (! (isset($_SESSION) && key_exists("sto_infos", $_SESSION) && isset($_SESSION["sto_infos"]) && ( $_SESSION["sto_infos"] instanceof SESSION_TO ) ) ) {
            //C'est NORMALEMENT IMPOSSIBLE. On le met car on est paranoïaque
            $this->signalErrorWithoutErrIdButGivenMsg("ERR_UNKNOW", __FUNCTION__, __LINE__);
            exit();
        }
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION["sto_infos"];
        
        $RDH = new REDIR($STOI->getProd_xmlscope());
        
        $url = NULL;
        
        $ups = $STOI->getCurr_wto()->getUps_required();
        $title = ( key_exists("tle", $ups) && !empty($ups["tle"]) ) ? $ups["tle"] : "";
        $teid = ( key_exists("tei", $ups) && !empty($ups["tei"]) ) ? $ups["tei"] : "";
        
        //Il est pratiquement IMPOSSIBLE que cela arrive mais je préfère ne prendre aucun risque
        if ( empty($teid) | empty($title) ) {
            $this->signalError ("err_user_l5e404_any", __FUNCTION__, __LINE__);
        }
        
        //On récupère les infos sur la Tendance
        $TRD = new TREND();
        $tr_infos = $TRD->on_read_entity(["trd_eid" => $teid]);
        /*
         * On pensera à adapter les erreurs plus tard.
         * 
         * [NOTE 21-03-14] @BlackOwlRobot
         * On ne rejette plus les requêtes quand le titre n'est pas similaire. Seul l'identifiant compte désormais.
         * Cette décision est prise pour corriger le fait que les Articles VM ne sont pas mise à jour régulièrement donc elles peuvent conserver des données fausses.
         * Cette solution est la plus douce et pourra restée perenne même après la mise en place du module CRON.
         * Une des conséquences est n'utilisera plus les données passées par l'utilisateur pour la redirection mais celle de la Tendance.
         */
        if ( !$tr_infos ) 
        {
            $this->signalError ("err_user_l5e404_trend", __FUNCTION__, __LINE__, TRUE);
        } else if ( $tr_infos === "__ERR_VOL_USER_GONE" ) 
        {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$teid, $title, $tr_infos]);
            $this->signalError ("err_user_l5e404_trend", __FUNCTION__, __LINE__, TRUE);
//        } else if ( strtolower($title) !== strtolower($tr_infos["trd_title_href"]) )
//        {
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$teid, $title, $tr_infos]);
//            $this->signalError ("err_user_l5e404_trend", __FUNCTION__, __LINE__, TRUE);
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $tr_infos) )
        {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$teid, $title, $tr_infos]);
            $this->signalError ("err_user_l5e404_trend", __FUNCTION__, __LINE__, TRUE);
        }     
        
        $title = $tr_infos["trd_title_href"];
        
        //On vérifie si l'utilisateur est connecté
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            $RSTOI = new RSTO_INFOS();
            $RSTOI = $_SESSION["rsto_infos"];

            $cu = $RSTOI->getUpseudo();
            $toid = $tr_infos["trd_oid"];
            
            //On vérifie si l'utilisateur connecté existe toujours
            $PA = new PROD_ACC();
            $u_tab = $PA->exists_with_psd($cu,TRUE);
            if (! $u_tab ) {
                //On détruit la connexion 
                $CXH->try_logout();
                //On redirige vers la page de connexion
                $url = $RDH->redir_to_default_page("pdpage_welc_conx_pgid");
            } else {
               
                //La Tendance appartient à l'utilisateur connecté
                if ( intval($u_tab["pdaccid"]) === intval($toid) ) {
                    $args_redir = [
                        "teid" => strtolower($teid),
                        "title" => strtolower($title),
                        "lang" => $RSTOI->getUspklang()
                    ];

                    $url = $RDH->redir_build_scoped_url("TRPG_GTPG_RO",$args_redir);
    //                    $url = $RDH->redir_build_std_url_string("profil", "TRPG_GTPG_RO", $target);
                } else if ( $TRD->trend_abo_exists($u_tab["pdaccid"],$teid) ) {
                    $args_redir = [
                        "teid" => strtolower($teid),
                        "title" => strtolower($title),
                        "lang" => $RSTOI->getUspklang()
                    ];
                    $url = $RDH->redir_build_scoped_url("TRPG_GTPG_RFOL",$args_redir);
    //                    $url = $RDH->redir_build_std_url_string("profil", "TRPG_GTPG_RU", $target);
                } else {
                    $args_redir = [
                        "teid" => strtolower($teid),
                        "title" => strtolower($title),
                        "lang" => $RSTOI->getUspklang()
                    ];
                    $url = $RDH->redir_build_scoped_url("TRPG_GTPG_RU",$args_redir);
    //                    $url = $RDH->redir_build_std_url_string("profil", "TRPG_GTPG_RU", $target);
                }
                
            }

//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $url);
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
            $RDH->start_redir_to_this_url_string($url);
        }
        else
        {
            $args_redir = [
                "teid" => strtolower($teid),
                "title" => strtolower($title)
            ];
            $url = $RDH->redir_build_scoped_url("TRPG_GTPG_WLC",$args_redir);
//                $url = $RDH->redir_build_std_url_string("profil", "TRPG_GTPG_WLC", strtolower($target));
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $url);
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
            $RDH->start_redir_to_this_url_string($url);
        }
            
        exit(); //PARANO
    }
    
    public function on_process_in() {
        
    }

    public function on_process_out() {
        
    }

    public function prepare_params_in_if_exist() {
        
    }

}
