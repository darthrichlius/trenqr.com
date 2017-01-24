<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_SETCOV extends WORKER {

    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }

    /*     * ************** START SPECFIC METHODES ******************* */

    private function DoesItComply_Datas () {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //Validation préléminaire de l'URL
            if ( ( $k === "cl" ) && !filter_var($v, FILTER_VALIDATE_URL) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
        }
        
    }
    
    
    private function SetAccCover() {
        

        //On vérifie si l'utilisateur est sur son compte
        /*
         * On utilise l'url envoyée par FE
         */
        $url = $this->KDIn["datas"]["cl"];
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $url_tab = $TQR->explode_tqr_url($url);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $url_tab) ) {
            $this->Ajax_Return("err",$url_tab);
        } else {
            
            //On vérifie si on a une URL valide et identifiable selon TQR
            if ( $url_tab && is_array($url_tab) && count($url_tab) ) {
                
                //** On récupère les données supplémentaires s'il y a une correpondance de pseudo **//
            
                if ( isset($url_tab["user"]) && ( strtolower($url_tab["user"]) === strtolower($this->KDIn["opsd"]) ) ) {
                    $PA = new PROD_ACC();
                    $u_tab = $PA->on_read_entity(["accid" => $this->KDIn["oid"]]);

                    if ( $u_tab && is_array($u_tab) && isset($u_tab["pdacc_capital"]) && isset($u_tab["pdacc_stats_posts_nb"])
                            && !$this->return_is_error_volatile(__FUNCTION__, __LINE__, $url_tab) ) 
                    {
                        
                        $this->DoesItComply_Datas();
        
                        $args_new_acov = [
                            "cov_w" => $this->KDIn["datas"]["iw"],
                            "cov_h" => $this->KDIn["datas"]["ih"],
                            "cov_t" => $this->KDIn["datas"]["it"],
                            "pdpic_fn" => $this->KDIn["datas"]["in"],
                            "pdpic_string" => urldecode($this->KDIn["datas"]["img"]),
                            "oeid" => $this->KDIn["oeid"]
                        ];

                        $CVAI = new IMAGE_COVERACC();
                        $cov_tab = $CVAI->on_create($args_new_acov);

                        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cov_tab) ) {
                            $this->Ajax_Return("err",$cov_tab);
                        }
                        
                        //----- COVER DATAS
                        /*
                         * Données sur l'image de couverture.
                         * Il s'agit de données sur l'image en elle même mais aussi sur les dimensions et le positionnement
                         */
                        $this->KDOut["FE_DATAS"]["cov_datas"] = [
                            "acov_width" => $cov_tab["acov_width"],
                            "acov_height" => $cov_tab["acov_height"],
                            "acov_top" => $cov_tab["acov_top"],
                            "acov_rpath" => $cov_tab["pdpic_realpath"],
                        ];
                        
                        //----- EXTRAS DATAS
                        /*
                         * On emprofite pour charger quelques données qui sont susceptibles de changer régulièrement.
                         */
                        
                        //On read à nouveau pour prendre en compte la mise à jour
                        $u_tab = $PA->on_read_entity(["accid" => $this->KDIn["oid"]]);
                        
                        //ProfilBio
                        $this->KDOut["FE_DATAS"]["o_pbio"] = $u_tab["pdacc_profilbio"];
                        //Nouveau capital de l'utilisateur
                        $this->KDOut["FE_DATAS"]["o_cap"] = $u_tab["pdacc_capital"];  
                        
                        //Nombre d'Articles
                        $this->KDOut["FE_DATAS"]["o_pnb"] = $u_tab["pdacc_stats_posts_nb"];
                        //Nombre de Tendances
                        $this->KDOut["FE_DATAS"]["tr_nb"] = $u_tab["pdacc_stats_mytrends_nb"];
                        
                        
                        
                        /*
                         * [NOTE 12-09-14] @author L.C.
                         * !!!! ATTENTION !!!!
                         * Si on doit changer les clés, il faut aussi les modifiers dans les fichiers *.js en FE qui les utilisent.
                         * Il s'agit à cette date de "acc-rich-post.d.js" et de "unique.csam.js"
                         */
                    }
                } else {
                    $this->Ajax_Return("err","__ERR_VOL_MISMATCH_RULES");
                }
                
            } else {
                $this->Ajax_Return("err","__ERR_VOL_MISMATCH_RULES");
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
        session_start();


        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["img","in","ih","iw","it","cl"];

        if (count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD))) {
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

        if (!$exists) {
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
        $this->SetAccCover();
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