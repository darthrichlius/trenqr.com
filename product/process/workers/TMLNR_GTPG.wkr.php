<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TMLNR_GTPG
 *
 * @author arsphinx
 */
class WORKER_TMLNR_GTPG extends WORKER {
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
        
        $target = $STOI->getCurr_wto()->getUser();
        
        //On vérifie qu'on a bien USER
        if ( isset($target) && $target != "" ) {
            
            //On s'assure d'avoir une version sans '@'
            $TXH = new TEXTHANDLER();
            $target = $TXH->genuine_pseudo_in_url($target);
            
            //On récupère la la page
            $pg = $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["pg"];
            
//            var_dump(__FILE__,__LINE__,$pg);
//            exit();
            
            //On vérifie si l'utilisateur est connecté
            $CXH = new CONX_HANDLER();
            if ( $CXH->is_connected() ) {
                $RSTOI = new RSTO_INFOS();
                $RSTOI = $_SESSION["rsto_infos"];
                
                $cu = $RSTOI->getUpseudo();
                
                //On vérifie s'il la cible est l'utilisateur connecté
                if ( strtolower($cu) === strtolower($target) ) {
                    $args_redir = [
                        "user"  => strtolower($cu),
                        "pg"    => strtolower($pg),
                        "lang"  => $RSTOI->getUspklang()
                    ];
                    
                    $url = $RDH->redir_build_scoped_url("TMLNR_GTPG_RO",$args_redir);
//                    $url = $RDH->redir_build_std_url_string("profil", "TMLNR_GTPG_RO", $target);
                    
                    /*
                     * [TODO]
                     *      BOUNCE directement vers le bon WORKER
                     * [NOTE 19-08-16]
                     *      Testée ! Il ne reste plus qu'à faire suivre en modifiant d'autres éléments de RODE pour prendre en compte ce BOUNCE.
                     */
//                    return new WORKER_BOUNCE("WORKER_TMLNR_GTPG_RO");
                    
                } else {
                    $args_redir = [
                        "user"  => strtolower($target),
                        "pg"    => strtolower($pg),
                        "lang"  => $RSTOI->getUspklang()
                    ];
                    $url = $RDH->redir_build_scoped_url("TMLNR_GTPG_RU",$args_redir);
//                    $url = $RDH->redir_build_std_url_string("profil", "TMLNR_GTPG_RU", $target);
                }
                
//                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $url);
                $RDH->start_redir_to_this_url_string($url);
            }
            else
            {
                $args_redir = [
                    "user"  => strtolower($target),
                    "pg"    => strtolower($pg)
                ];
                $url = $RDH->redir_build_scoped_url("TMLNR_GTPG_WLC",$args_redir);
//                var_dump($args_redir,__LINE__,$url);
//                exit();
//                $url = $RDH->redir_build_std_url_string("profil", "TMLNR_GTPG_WLC", strtolower($target));
                $RDH->start_redir_to_this_url_string($url);
            }
            
        } else {
            $CXH = new CONX_HANDLER();
            if ( $CXH->is_connected() ) {
                $RSTOI = new RSTO_INFOS();
                $RSTOI = $_SESSION["rsto_infos"];
                
                $args_redir = [
                    "user"  => $RSTOI->getUpseudo(),
                    "pg"    => strtolower($pg),
                    "lang"  => $RSTOI->getUspklang()
                ];

                $url = $RDH->redir_build_scoped_url("TMLNR_GTPG_RO",$args_redir);
                
            } else {
                $url = $RDH->redir_build_scoped_url("ONTRENQR");
            }
            
            $RDH->start_redir_to_this_url_string($url);
        }
        
    }
    
    public function on_process_in() {
        
    }

    public function on_process_out() {
        
    }

    public function prepare_params_in_if_exist() {
        
    }

}
