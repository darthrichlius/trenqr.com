<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_ACC_CNFRM extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function ValidByEmail () {
        
        /*
         * On s'assure que les données sont remis à leur état originel
         */
        $this->KDIn["datas"]["ueml"] = str_replace(',','.',$this->KDIn["datas"]["ueml"]);

        //On vérifie que les données correspondent à une opération de demande de mot de passe
        $TQACC = new TQR_ACCOUNT();
        $utab = $TQACC->exists($this->KDIn["datas"]["ui"]);
        if ( !$utab | ( $this->KDIn["oeid"] && $utab["acc_eid"] !== $this->KDIn["oeid"] ) ) {
            $this->signalError ("err_sys_l7emcnfn2", __FUNCTION__, __LINE__);
        }
        
//        var_dump(__LINE__,__FILE__,$this->KDIn["datas"]);
//        exit();
        
        $r = $TQACC->EC_ValidOper($this->KDIn["datas"]["ui"],$this->KDIn["datas"]["ueml"],$this->KDIn["datas"]["key"],$this->KDIn["datas"]["ssid"],$this->KDIn["datas"]["tm"],$this->KDIn["datas"]["cd"]);
//        var_dump(__LINE__,__FILE__,$this->KDIn["datas"],$r);
//        exit();
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->signalError ("err_user_l5e404_any", __FUNCTION__, __LINE__);
        } else {
//            ... Aller vers une page de gestion de type DROPALL
                    
            $STOI = new SESSION_TO(); //ASTUCE : Permet d'accéder aux methodes grace à auto-imp
            $STOI = $_SESSION["sto_infos"];
            
            $RDH = new REDIR($STOI->getProd_xmlscope());
            
            $CXH = new CONX_HANDLER();
            if (! $CXH->is_connected() ) {
                $url = HTTP_RACINE."/login/ec/case=".$this->KDIn["datas"]["key"].";".$this->KDIn["datas"]["cd"]."";
            } else {
                $utab = $TQACC->exists($this->KDIn["datas"]["ui"]);
                $url = HTTP_RACINE."/".$utab["acc_psd"]."&as=athome/ec/case=".$this->KDIn["datas"]["key"].";".$this->KDIn["datas"]["cd"]."";
            }
//            var_dump($url);
//            exit();
            $RDH->start_redir_to_this_url_string($url);
        }

    }
    
    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            $this->signalError ("err_sys_l7comn3", __FUNCTION__, __LINE__);
        }
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["ui","ueml","key","ssid","tm","cd"];
        
        $STOI = new SESSION_TO(); //ASTUCE : Permet d'accéder aux methodes grace à auto-imp
        $STOI = $_SESSION["sto_infos"];
        
        $in_datas = $STOI->getCurr_wto()->getUps_required();
        
        /*
         * On vérifie que les données sont bien présentes sinon on déclenche une erreur
         * Normallement, c'est impossible. Mais on ne préfère prendre aucun risque.
         */
        if (! isset($in_datas) ) {
            $this->signalError ("err_sys_l7comn1", __FUNCTION__, __LINE__);
        }
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($in_datas), $EXPTD)) ) {
            $this->signalError ("err_sys_l7comn1", __FUNCTION__, __LINE__);
        }
        
        /*
         * L'utilisateur du WORKER peut passer x : Extras datas
         * Cette donnée n'est pas obligée d'être non vide
         */
        $in_datas_keys = array_keys($in_datas);
        foreach ($in_datas_keys as $k => $v) {
            if (! isset($v) && $v !== "" ) {
                $this->signalError ("err_sys_l7comn1", __FUNCTION__, __LINE__);
            }
        }
        
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            $RSTOI = new RSTO_INFOS();
            $RSTOI = $_SESSION["rsto_infos"];
            
            $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        }
        
        
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $STOI->getCtr_code();
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
        
    }

    public function on_process_in() {
        $this->ValidByEmail();
    }

    public function on_process_out() {
        //echo "on_process_out";
    }
    
    protected function prepare_params_in_if_exist() {
        
    }

}

?>
