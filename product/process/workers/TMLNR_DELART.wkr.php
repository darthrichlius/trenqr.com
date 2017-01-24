<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_DELART extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function DoesItComply ($art_tab) {
        
        //On vérifie si l'utilisateur actif est le propriétaire de l'Article
        if ( intval($art_tab["art_accid"]) === intval($this->KDIn["oid"]) ) {
            return TRUE;
        } else if ( key_exists("art_trd_oid",$art_tab) && intval($art_tab["art_trd_oid"]) === intval($this->KDIn["oid"]) ) {//On vérifie si on est dans le cas d'une Tendance et s'il s'agit du propriétaire de la Tendance
            return TRUE;
        }
        return FALSE;
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
    
    private function DeleteArticle () {
        //* On supprime l'Article *//
        /*
         * RAPPEL : Ce WORKER doit être appelé dans le contexte d'une suppression d'Article lorsque la page de référence est TMLNR !!!
         *          Cela prend en compte le cas où l'utilisateur supprime depuis NWFD lorsqu'il est sur TimeLiner
         */
         
        //On s'assure que les données reçues sont non nulles et conformes
        $this->DoesItComply_Datas();
        
        $ART = new ARTICLE_TR();
            
        /*
         * ETAPE :
         *      On vérifie au préalable que l'article existe
         */
        $art_tab = $ART->child_exists($this->KDIn["datas"]["i"]); //Depuis 14-12-14
//        $art_tab = $ART->exists($this->KDIn["datas"]["i"]);
        if (! $art_tab ) {
            $this->Ajax_Return("err","__ERR_VOL_ART_GONE");
        } else if ( $art_tab === -1 ) {//Depuis 14-12-14
            $art_tab = $ART->getArt_loads();
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);  
        
        
        /*
         * ETAPE :
         *      On vérifie si l'utilisateur a le droit de supprimer l'Article
         */
        if (! $this->DoesItComply($art_tab) ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY");
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);  
        
        
        /*
         * ETAPE :
         *      On tente la suppréssion effective de l'ARTICLE
         */
        $ART = new ARTICLE();
        $r = $ART->on_delete_entity($this->KDIn["datas"]["i"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);  
        
        
        /*
         * ETAPE :
         *      On vérifie si l'utilisateur est sur son compte
         *      On utilise l'url envoyée par FE
         */
        $url = $this->KDIn["datas"]["cl"];
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $url_tab = $TQR->explode_tqr_url($url);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);  
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $url_tab) ) {
//            var_dump(__LINE__,__FILE__,$url,$url_tab);
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
                        //On read à nouveau pour prendre en compte la mise à jour
                        $u_tab = $PA->on_read_entity(["accid" => $this->KDIn["oid"]]);
                        
                        //Nouveau capital de l'utilisateur
                        $this->KDOut["FE_DATAS"]["o_cap"] = $u_tab["pdacc_capital"];     
                        //Nombre d'Articles
                        $this->KDOut["FE_DATAS"]["o_pnb"] = $u_tab["pdacc_stats_posts_nb"];    
                        
                        /*
                         * [NOTE 12-09-14] @author L.C.
                         * !!!! ATTENTION !!!!
                         * Si on doit changer les clés, il faut aussi les modifiers dans les fichiers *.js en FE qui les utilisent.
                         * Il s'agit à cette date de "acc-rich-post.d.js" et de "unique.csam.js"
                         */
                    }
                } else if ( isset($url_tab["urqid"]) && in_array(strtoupper($url_tab["urqid"]),["TRPG_GTPG_RO","TRPG_GTPG_RFOL","TRPG_GTPG_RU","TRPG_GTPG_WLC"]) ) {
                    /*
                     * [ TODO ]
                     * On vérifie si on est dans le cas où la page référence est TRPG.
                     * Dans ce cas, on renvoie les couples de données suivants
                     *      (1) t_pnb : Nombre d'articles de la Tendance
                     *      (2) o_ctrb : Nombre d'articles du propriétaire de l'Article dans la Tendance
                     * [DEPUIS 29-04-15] @BOR
                     *      (1) t_pnb : Nombre d'articles de la Tendance
                     *      (2) t_snb : Nombre d'abonnement de la Tendance
                     *      (3) o_ctrb : Nombre d'articles du propriétaire de l'Article dans la Tendance
                     */
                    $TRD = new TREND();
                    
                    /*
                     * ETAPE :
                     * On récupère le nombre d'Articles de la Tendance après suppression.
                     */
                    $this->KDOut["FE_DATAS"]["t_pnb"] = $TRD->onload_trend_get_trend_stats_posts($art_tab["trid"]);  
                    
                    /*
                     * ETAPE :
                     * On récupère le nombre d'Abonnements de la Tendance mis à jour.
                     */
                    $this->KDOut["FE_DATAS"]["t_snb"] = $TRD->trend_get_trabo_number($art_tab["trid"]);
                    
                    /*
                     * ETAPE :
                     * On récupère le nombre d'Articles du propriétaire de l'Article qui vient d'être supprimé.
                     * Cette donnée doit être manipulée avec précaution. Pour cela, il faut sélectionner tous les Articles et les mettre à jour.
                     */
                    $this->KDOut["FE_DATAS"]["o_ctrb"] = $TRD->onread_usercontrib($art_tab["art_trd_oid"],$art_tab["trid"]);
                    
                } else {
                    $this->KDOut["FE_DATAS"] = "DONE";    
                }
                
            } else {
                $this->Ajax_Return("err","__ERR_VOL_MISMATCH_RULES");
            }
            
        }
        
       /*
        * [NOTE 26-08-15] @author BOR
        *       Necessaire pour l'opération de mise à jour de la Tendance.
        */
       $this->KDOut["spe_trid"] = ( key_exists("trid", $art_tab) && $art_tab["trid"] ) ? $art_tab["trid"] : NULL;
        
    }
    
    
    /****************** END SPECFIC METHODES ********************/
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION
        @session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["i","cl"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) )  {
                $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
            }
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid, TRUE);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->DeleteArticle();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],FALSE);
        
        /*
         * [NOTE 25-08-15] @BOR
         * Mise à jour des données de la Tendance au niveau de SRH
         */
        if ( key_exists("spe_trid", $this->KDOut) && $this->KDOut["spe_trid"] ) {
            $TRD = new TREND();
            $TRD->onalter_update_archv_trend(["trid" => $this->KDOut["spe_trid"]]);
        }
        
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>