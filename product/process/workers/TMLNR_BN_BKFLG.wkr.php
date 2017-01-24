<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_BN_BKFLG extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function Unfollow () {
        //On crée le scope de la cible
        $PA = new PROD_ACC();
        $trg_tab = $PA->exists($this->KDIn["datas"]["i"],TRUE);
        
        if ( !$trg_tab ) {
            $this->Ajax_Return("err","__ERR_VOL_TRG_GONE");
        }
        
        $RL = new RELATION();
        //On lance une tentative de création de Relation
        $r = $RL->onalter_downgrade_relation($this->KDIn["oid"],$trg_tab["pdaccid"]);
        
        if ( !$r || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r)  ) {
            $this->Ajax_Return("err",$r);
        }
        
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

                    if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab) ) 
                    {
                        $this->Ajax_Return("err",$u_tab);
                    } else {
                        //Nombre d'abonnements de l'utilisateur actif
                        $this->KDOut["FE_DATAS"]["flg_nb"] = count($PA->onread_acquiere_my_following($this->KDIn["oid"]));
                       
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
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
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
        $exists = $A->exists_with_id($oid);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        }
        
        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->Unfollow();
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