<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TRPG_RSTCVR extends WORKER {

    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }

    /*     * ************** START SPECFIC METHODES ******************* */

    private function DoesItComply_Datas () {
        /*
         * [DEPUIS 16-08-15] @BOR
         *  Permet de régler le bogue qui ne permettait pas d'exécuter les opérations liées à ABO/DISABO.
         *  Le problème venait d'un mauvais encodage de l'URL surtout pour IE. Grace au filtre ci-dessous, l'URL est dans un format compréhensible par filter_var().
         *  
         * [NOTE 16-08-15] @BOR
         *  La fonction peut être utilisée ailleurs pour résoudre des problèmes de même type.
         */
        $f = function($str) {
            return htmlentities(stripcslashes(html_entity_decode(preg_replace("#u([0-9a-f]{3,4})#i","&#x\\1;",urldecode($str)),null,'UTF-8')));
        };
        foreach ($this->KDIn["datas"] as $k => $v) {
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //Validation préléminaire de l'URL
            if ( ( $k === "cl" ) && !filter_var($f($v), FILTER_VALIDATE_URL) ) {
//            if ( ( $k === "cl" ) && !filter_var($v, FILTER_VALIDATE_URL) ) { //[DEPUIS 26-08-15]
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
        }
        
    }
    
    
    private function DeleteCover() {
        
        $this->DoesItComply_Datas();

        /*
         * On vérifie si l'utilisateur est sur une la page d'une de ses Tendances au niveau du client
         * On utilise l'url envoyée par FE. 
         */
        $url = $this->KDIn["datas"]["cl"];
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $url_tab = $TQR->explode_tqr_url($url);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $url_tab) ) {
            $this->Ajax_Return("err",$url_tab);
        } else {
            
            //On vérifie si on a une URL valide et identifiable selon TQR
            if ( $url_tab && is_array($url_tab) && count($url_tab) && !empty($url_tab["ups_raw"]) && !empty($url_tab["ups_raw"]["tei"]) && !empty($url_tab["ups_raw"]["tle"]) ) {
                
                $TR = new TREND();
//                var_dump(__LINE__,$url_tab["ups_raw"]["tei"]);
                /*
                 * On vérifie que le code fourni au niveau de l'URL correspond à une Tendance valide
                 */
                $t_tab = $TR->on_read_entity(["trd_eid" => $url_tab["ups_raw"]["tei"]]);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t_tab) ) {
                    $this->Ajax_Return("err",$t_tab);
                } else if (! $t_tab ) {
                    $this->Ajax_Return("err","__ERR_VOL_TRD_GONE");
                }
                
                /*
                 * On vérifie que l'utilisateur connecté est bel et bien le propriétaire de la Tendance
                 */
                if ( floatval($t_tab["trd_oid"]) !== floatval($this->KDIn["oid"]) ) {
                    $this->Ajax_Return("err","__ERR_VOL_MSM_RULES");
                }
                
               //*
                $CVTI = new IMAGE_COVERTR();
                $r__ = $CVTI->on_delete($t_tab["trd_eid"],["FAST_WAY"]);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r__) ) {
                    $this->Ajax_Return("err",$r__);
                }
                //*/

                //----- EXTRAS DATAS
                /*
                 * On emprofite pour charger quelques données qui sont susceptibles de changer régulièrement.
                 */

                //On read à nouveau pour prendre en compte la mise à jour
                $t_tab = $TR->on_read_entity(["trd_eid" => $url_tab["ups_raw"]["tei"]]);
                
                $t_infos = [
                    "t"         => $t_tab["trd_title"],
                    "d"         => $t_tab["trd_desc"],
                    "c"         => $t_tab["catg_decocode"],
                    "p"         => ( $t_tab["trd_is_public"] ) ? ["pub","Public"] : ["pri","Prive"],
                    //Est que la Tendance est en mode suppression
                    "tmtodl"    => ( key_exists("tsh_state_time", $t_tab) && !empty($t_tab["tsh_state_time"]) ) ? $t_tab["tsh_state_time"] : NULL,
                    "pnb"       => $t_tab["trd_stats_posts"],
                    "sbs"       => $t_tab["trd_stats_subs"],
                    "oid"       => $t_tab["trd_oid"],
                    "oeid"      => $t_tab["trd_oeid"],
                    "ofn"       => $t_tab["trd_ofn"],
                    "opsd"      => $t_tab["trd_opsd"],
                    "oppic"     => $t_tab["trd_oppic"],
                    "ohref"     => "/".$t_tab["trd_opsd"]
                ];
                $this->KDOut["FE_DATAS"] = $t_infos;

            } else {
                $this->Ajax_Return("err","__ERR_VOL_MSM_RULES");
            }
            
        }
    }

    /*     * **************** END SPECFIC METHODES ******************* */


    /*     * ************************************************************************************************************************************************** */
    /*     * ************************************************************************************************************************************************** */
    /*     * ************************************************************************************************************************************************** */

    /*     * ********* TMP ************ */

    //Mettre les instructons faites ailleurs pour les intégrer au WORKER


    /*     * ************************** */

    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();


        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["ti","cl"];

        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if (!( isset($v) && $v != "" )) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable

            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }

        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if (!$CXH->is_connected()) {
            $this->Ajax_Return("err", "__ERR_VOL_DNY_AKX");
        }

        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid,TRUE);

        if (! $exists ) {
            $this->Ajax_Return("err", "__ERR_VOL_CU_GONE");
        }

        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));

        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->DeleteCover();
    }

    public function on_process_out() {

        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }

    protected function prepare_params_in_if_exist() {
        
    }

}

?>