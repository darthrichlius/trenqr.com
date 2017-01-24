<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TRPG_SET_TCOV extends WORKER {

    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }

    /*     * ************** START SPECFIC METHODES ******************* */
    
    private function DoesItComply($t_tab) {
        /*
         * On va vérifier que l'utilisateur a effectivement le droit d'effectuer cette Action.
         */
        
        //On vérifie si la Tendance existe
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t_tab) ) {
            $this->Ajax_Return("err",$t_tab);
        }
        
        //On vérifie que l'utilisateur actif est le propriétaire de la Tendance
        if ( intval($t_tab["trd_owner"]) !== intval($this->KDIn["oid"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY");
        }
        
    }

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
    
    
    private function SetTrdCover() {
        
        $TRD = new TREND();
        $te_tab = $TRD->exists($this->KDIn["datas"]["ti"]);
        
        
        $this->DoesItComply($te_tab);

        //On vérifie si l'utilisateur est sur son compte
        /*
         * TODO : Utiliser l'URL pour s'assurer que la requete a bien été exécutée depuis la page TRPG spécifiée.
         * On utilise l'url envoyée par FE
         */
        $url = $this->KDIn["datas"]["cl"];
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $url_tab = $TQR->explode_tqr_url($url);
        
        
        $this->DoesItComply_Datas();

        $args_new_tcov = [
            "cov_w"         => $this->KDIn["datas"]["iw"],
            "cov_h"         => $this->KDIn["datas"]["ih"],
            "cov_t"         => $this->KDIn["datas"]["it"],
            "pdpic_fn"      => $this->KDIn["datas"]["in"],
            "pdpic_string"  => urldecode($this->KDIn["datas"]["img"]),
            "tcov_teid"     => $this->KDIn["datas"]["ti"]
        ];

        $CVTI = new IMAGE_COVERTR();
        $cov_tab = $CVTI->on_create($args_new_tcov);

        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cov_tab) ) {
            $this->Ajax_Return("err",$cov_tab);
        }
        
        //On read TREND pour récupérer des données de mise à jour
        $t_tab = $TRD->on_read_entity(["trd_eid" => $this->KDIn["datas"]["ti"]]);
        
        //----- COVER DATAS
        /*
         * Données sur l'image de couverture.
         * Il s'agit de données sur l'image en elle même mais aussi sur les dimensions et le positionnement
         */
        $cov_datas = NULL;
        if ( $t_tab["trd_cover"] ) {
            $cov_datas = [
                "cov_w"     => $t_tab["trd_cover"]["trcov_width"],
                "cov_h"     => $t_tab["trd_cover"]["trcov_height"],
                "cov_t"     => $t_tab["trd_cover"]["trcov_top"],
                "cov_rp"    => $t_tab["trd_cover"]["pdpic_realpath"],
            ];
        } else {
            //C'est presque impossible que cela arrive. Mais on met un garde fou pour améliorer la fiabilité du produit
            $this->Ajax_Return("err","__ERR_VOL_UXPTD_ERR");
        }
        
        //----- EXTRAS DATAS
        /*
         * On emprofite pour charger quelques données qui sont susceptibles de changer régulièrement.
         */

        $t_infos = [
            "t"     => $t_tab["trd_title"],
            "d"     => $t_tab["trd_desc"],
            "c"     => $t_tab["catg_decocode"],
            //pub, pri
//            "p" => ( $t_tab["trd_is_public"] ) ? ["pub",$cat_lib] : ["pri",$cat_lib],
            "p"     => ( $t_tab["trd_is_public"] ) ? ["pub","Public"] : ["pri","Prive"],
//            "g" => "0", //0, 1, 2, 3, 5, 10
            "trcov" => $cov_datas,
            "pnb"   => $t_tab["trd_stats_posts"],
            "sbs"   => $t_tab["trd_stats_subs"],
            "oid"   => $t_tab["trd_oid"],
            "oeid"  => $t_tab["trd_oeid"],
            "ofn"   => $t_tab["trd_ofn"],
            "opsd"  => $t_tab["trd_opsd"],
            "oppic" => $t_tab["trd_oppic"],
            "ohref" => "/".$t_tab["trd_opsd"]
        ];

        $this->KDOut["FE_DATAS"] = $t_infos;

        /*
         * [NOTE 12-09-14] @author L.C.
         * !!!! ATTENTION !!!!
         * Si on doit changer les clés, il faut aussi les modifiers dans les fichiers *.js en FE qui les utilisent.
         * Il s'agit à cette date de "acc-rich-post.d.js" et de "unique.csam.js"
         */
      
                
        
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
        $EXPTD = ["ti","img","in","ih","iw","it","cl"];

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
        $exists = $A->exists_with_id($oid);

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
        $this->SetTrdCover();
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