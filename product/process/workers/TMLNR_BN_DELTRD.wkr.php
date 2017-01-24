<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_BN_DELTRD extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function DoesItComply ($tr_tab) {
        
        //On vérifie si l'utilisateur actif est le propriétaire de l'Article
        if ( intval($tr_tab["trd_owner"]) === intval($this->KDIn["oid"]) ) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
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
    
    private function DeleteTrend () {
        //* On supprime l'Article *//
        /*
         * RAPPEL : Ce WORKER doit être appelé dans le contexte d'une suppression d'Article lorsque la page de référence est TMLNR !!!
         *          Cela prend en compte le cas où l'utilisateur supprime depuis NWFD lorsqu'il est sur TimeLiner
         */
         
        //On s'assure que les données reçues sont non nulles et conformes
        $this->DoesItComply_Datas();
                
        $TRD = new TREND();
        
        //On vérifie si l'article existe
        $tr_tab = $TRD->exists($this->KDIn["datas"]["i"]);
        if ( !$tr_tab ) {
            $this->Ajax_Return("err","__ERR_VOL_TR_GONE");
        }
        
        //On vérifie si l'utilisateur a le droit de supprimer l'Article
        if (! $this->DoesItComply($tr_tab) ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY");
        }
        //On tente la suppréssion
        /*
         * [DEPUIS 11-06-15] @BOR
         * On modifie l'état  de la Tendance.
         */
        if ( key_exists("tsh_state", $tr_tab) && !empty($tr_tab["tsh_state"]) && intval($tr_tab["tsh_state"]) === 4 ) {
            $this->Ajax_Return("err","__ERR_VOL_ALDY");
        } else {
            $r = $TRD->onalter_change_state($this->KDIn["datas"]["i"],4);
    //        $r = $TRD->on_delete_entity($this->KDIn["datas"]["i"]); //[DEPUIS 11-06-15] @BOR
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                $this->Ajax_Return("err",$r);
            }
        }
        
        
        //On vérifie si l'utilisateur est sur son compte
        /*
         * On utilise l'url envoyée par FE.
         * 
         * [NOTE 15-09-14] @author
         * Normalement à la version vb1, on doit renvoyer les données systématiquement.
         * Cependant, pour préparer de futurs cas, je préfère sauvegarder le script qui controle au préalable que l'utilisateur et sur sa page
         * De plus , cela nous grantie qu'il s'agit pas juste d'une simple requete. 
         * Seules utilisateur expérimentées pourraient outre, ce qui réduit fortement le risque que ce soit un mouvement généralisé.
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
                        //On read à nouveau pour prendre en compte la mise à jour
                        $u_tab = $PA->on_read_entity(["accid" => $this->KDIn["oid"]]);
                        
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
                    /*
                     * [ TODO ]
                     * On vérifie si on est dans le cas où la page référence est TRPG.
                     * Dans ce cas, on renvoie les couples de données suivants
                     *  (1) t_pnb : Nombre d'articles de la Tendance
                     *  (2) o_ctrb : Nombre d'articles du propriétaire de l'Article dans la Tendance
                     */
                    
                    $this->KDOut["FE_DATAS"] = "DONE";     
                }
                
            } else {
                $this->Ajax_Return("err","__ERR_VOL_MISMATCH_RULES");
            }
            
        }
        
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
        session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["i","cl"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) )  {
                $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
            }
        }
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid, TRUE);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }
        
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        
        $this->DeleteTrend();
        
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>