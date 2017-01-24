<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_REC_TRYREC_F extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
            $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
            $rbody = $this->KDIn["datas"][$k];

            preg_match_all("/(\n)/", $rbody, $m_c1);
            preg_match_all("/(\r)/", $rbody, $m_c2);
            preg_match_all("/(\r\n)/", $rbody, $m_c3);
            preg_match_all("/(\t)/", $rbody, $m_c4);
            preg_match_all("/(\s)/", $rbody, $m_c5);

            //Parano : Je sais que j'aurais pu ne mettre que \s
            if ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
          
            if ( $k === "curl" ) {
                //On récuèpère les éléments clés de l'url s'ils existent
                $matches = [];
                if (! preg_match("#/s/rpassword\?case=([\w]{2,25})&uid=(.+)&em=(.+)&is=([\w-]+)=([\w-]+)=([\w-]+)&lg=([a-z]{2})#", $this->KDIn["datas"]["curl"], $matches) ) {
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                }
                //On "prépare" les données
                foreach ($matches as $k => $v) {
                    $x = "";
                    switch ($k) {
                        case 1 :
                                $x = "up";
                            break;
                        case 2 :
                                $x = "ui";
                            break;
                        case 3 :
                                $x = "ue";
                            break;
                        case 4 :
                                $x = "oei";
                            break;
                        case 5 :
                                $x = "k";
                            break;
                        case 6 :
                                $x = "ssid";
                            break;
                        case 7 :
                                $x = "lg";
                            break;
                    }
                    
                    $this->KDIn["datas"]["rec_datas"][$x] = $v;
                }
                
            }
        }
        
    }


    private function TryRecoveryFinal() {
        
        
        $this->DoesItComply_Datas();
        
        $TQACC = new TQR_ACCOUNT();
        $oper_args = [
            "up" => $this->KDIn["datas"]["rec_datas"]["up"],
            "ui" => $this->KDIn["datas"]["rec_datas"]["ui"],
            "ue" => str_replace('%2C','.',$this->KDIn["datas"]["rec_datas"]["ue"]),
            "oei" => $this->KDIn["datas"]["rec_datas"]["oei"],
            "k" => $this->KDIn["datas"]["rec_datas"]["k"],
            "ssid" => $this->KDIn["datas"]["rec_datas"]["ssid"]
        ];
        
        $r = $TQACC->onalter_ValidPwdRecovery($this->KDIn["datas"]["p"], $this->KDIn["datas"]["c"], $oper_args);
        
        $sp_errvol = ["__ERR_VOL_UG","__ERR_VOL_UKNW","__ERR_VOL_EXP","__ERR_VOL_CCL","__ERR_VOL_INVLD","__ERR_VOL_OBSLT"];
        
        $FE = NULL;
        if ( $r === TRUE ) {
            $FE = "DONE";
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) && !in_array($r, $sp_errvol) ) {
            /*
             * RAPPEL : Pour les ERR_VOL dans sp_errvol, FE les traite differemment que de simples ERR_VOL.
             * Aussi, on les renvoie par le canal "normal"
             */
            $this->Ajax_Return("err",$r);
        } else {
            $FE = $r;
        }
        
//        $this->KDOut["FE_DATAS"] = "__ERR_VOL_INVLD";//DEV,TEST,DEBUG
        $this->KDOut["FE_DATAS"] = $FE; 
        
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
        @session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["p","c","curl"];

        if (count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD))) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MISG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        /*
         * L'utilisateur du WORKER peut passer x : Extras datas
         * Cette donnée n'est pas obligée d'être non vide
         */
        foreach ($in_datas_keys as $k => $v) {
            if (! ( isset($v) && ( $v != "" || $k === "x") ) ) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MISG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable

            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }

        $this->KDIn["datas"] = $in_datas;
//        $this->KDIn["datas"]["eml"] = "fake.email@ondeuslynn.com";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $_SESSION["sto_infos"]->getCtr_code();
    }

    public function on_process_in() {
        $this->TryRecoveryFinal();
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