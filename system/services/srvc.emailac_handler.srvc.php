<?php

/**
 * Ce service gère les opérations liées aux émaux.
 * Il s'agit le plus souvent d'envois d'email.
 * @author L.C.
 */
class EMAILAC_HANDLER extends MOTHER {
    
    private $em_args_props;
    private $em_args_needed;
    private $em_args_needed_wmdlcase;
    private $em_catg;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->em_args_props = ["exp","rcpt","rcpt_uid","object","copyto","type","catg"];
        $this->em_args_needed = ["exp","rcpt","object","catg"];
        //Dans ce cas, "object" est facultatif. En effet, les modèles founissent déjà des objets
        $this->em_args_needed_wmdlcase = ["exp","rcpt","catg"];
        $this->em_catg = ["SYS_ERR" => 1, "PRODSYS_ERR" => 2, "USER_ERROR" => 3, "USER_ACTION" => 4, "USER_COMY" => 5, "PRODSYS_SECU_THREAT" => 6, "USER_SECU_THREAT" => 7, "PROD_INFOS" => 8];
    }
    
    public function emac_acquire_emtab ($emid, $lang, $err_used_in_case = NULL) {
        /*
         * Renvoie un tableau de données sur le modèle.
         * Si le modèle n'exuste pas, la fonction renvoie FALSE.
         * Si une erreur survient, elle renvoie une erreur de type __ERR_VOL
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$emid,$lang]);
        
        if (! is_string($emid) )
            return "__ERR_VOL_WRG_DATAS";
        
        $path = WOS_GEN_PATH_TO_EMMDLDEF_REPOS.$lang."/".WOS_EMMDLDEF_FILE;
        
        $xml_tools = new MyXmlTools();
        $err = ( $err_used_in_case ) ? $err_used_in_case : "err_sys_l013";
        $emtab = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path, $emid, $err);
        
        if (! $emtab )
            return "__ERR_VOL_UXPTD";
        else 
            return $emtab;
    } 
    
    public function emac_send_email () {
        /*
         * TODO WHEN NEEDED
         * Permet d'envoyer un email sans passer par un model.
         */
    } 
    
    public function emac_send_email_via_model ($emid, $lang, $em_args, $marks_tbl = NULL, $is_base64 = FALSE) {
        /*
         * Gère les opérations consécutives à un envoi d'email, de la prépartion à l'enregistrement de l'activité au niveau de la base de données.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$emid, $lang, $em_args]);
        
        //On vérifie que les éléments necessaires à la construction de l'email sont présents
        //["exp","rcpt","catg"]
        $com  = array_intersect( array_keys($em_args), $this->em_args_needed_wmdlcase);
        
        /* var_dump($this->em_args_needed_wmdlcase,array_keys($em_args)); */
        if ( count($com) != count($this->em_args_needed_wmdlcase) ) {
            
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->em_args_needed_wmdlcase],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $em_args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            
            foreach ($em_args as $k => $v) {
                
                if (! ( !is_array($v) && !empty($v) ) ) {
                    
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    
                } 
            }
        }

        //On vérifie que la catégorie est correcte
        if (! in_array($em_args["catg"], array_keys($this->em_catg)) ) {
            return "__ERR_VOL_WRG_DATAS";
        }

        //On récupère le modèle
        $em_tab = $this->emac_acquire_emtab($emid, $lang);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $em_tab) ) {
            return $em_tab;
        }

//        var_dump($em_tab);
//        exit();
        
        //On vérifie si le texte contient des marqueurs
        $TXH = new TEXTHANDLER();
        $dmd_tab = $TXH->ExtractAllDmd($em_tab["emodel.body"]);
//        var_dump($dmd_tab, array_keys($marks_tbl));
//        exit();
//         var_dump(__LINE__,$dmd_tab,$marks_tbl);
        
        /*
         * [DEPUIS 30-07-16]
         */
        $dmd_tab_unique = array_unique($dmd_tab);
        $marks_tbl_keys_unique = array_unique(array_keys($marks_tbl));
        
//        var_dump(__LINE__,$dmd_tab_unique,$marks_tbl_keys_unique,array_intersect($dmd_tab_unique, $marks_tbl_keys_unique));
//        var_dump(__LINE__,$dmd_tab_unique === array_intersect($dmd_tab_unique, $marks_tbl_keys_unique));
//        exit();
        
        if ( $dmd_tab ) {
            if (! $marks_tbl ) {
                return "__ERR_VOL_DMD_MSG";
            } else {
//                var_dump(__LINE__,$dmd_tab,$marks_tbl);
//                var_dump(array_diff($dmd_tab,array_intersect($dmd_tab, array_keys($marks_tbl))));
//                var_dump(array_unique($dmd_tab),array_intersect($dmd_tab, array_keys($marks_tbl)));
//                var_dump(count(array_unique($dmd_tab)), count(array_intersect($dmd_tab, array_keys($marks_tbl))));
//                var_dump(count($dmd_tab),count(array_intersect($dmd_tab, array_keys($marks_tbl))));
//                exit();
                
                //On vérifie que tous les DMD sont présents
//                if ( count($dmd_tab) !== count(array_intersect($dmd_tab, array_keys($marks_tbl))) ) {
                /*
                 * [DEPUIS 30-07-16]
                 */
                if ( $dmd_tab_unique !== array_intersect($dmd_tab_unique, $marks_tbl_keys_unique) ) {
                    return "__ERR_VOL_DMD_MSG";
                }
            } 
            
            //ETAPE : Pour chaque marqueur fourni et qui se trouve dans la source, on procède au remplacement
            $tp_embdy = $em_tab["emodel.body"];
            
            //ETAPE : On retire les doublons car le remplacement se fait sur toute la chaine
            $dmd_tab = array_unique($dmd_tab);
            
//            var_dump(__LINE__,$dmd_tab,  array_keys($marks_tbl));
//            exit();
            
            foreach ( $dmd_tab as $v ) {
//                print_r($tp_embdy);
//                exit();
                $tp_embdy = $TXH->ReplaceDmd($v, $marks_tbl[$v], $tp_embdy);
//                var_dump($v, $marks_tbl, $marks_tbl[$v], $tp_embdy);
                if (! $tp_embdy ) {
                    return "__ERR_VOL_DMD_MSG";
                } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $tp_embdy) ) {
                    return $tp_embdy;
                }
            }
            $em_tab["emodel.body"] = $tp_embdy;
        } 
        
//        var_dump("116",$em_tab["emodel.body"]);
//        exit();
        
        //On procède à une tentative d'envoi d'email
        $tosend_args = [
            "ctype"     => $em_tab["emodel.type"],
            "sender"    => $em_args["exp"],
            "recipient" => $em_args["rcpt"],
            "subject"   => ( key_exists("object", $em_args) && isset($em_args["object"]) && $em_args["object"] !== "" && is_string($em_args["object"]) ) ? $em_args["object"] : $em_tab["emodel.object"],
            "body"      => $em_tab["emodel.body"]
        ];
         
//        if ( $is_base64 ) {
//            echo "b64";
//            $sr = $this->wos_send_email_b64(__FUNCTION__, __LINE__, $tosend_args);
//        } else {
//            $sr = $this->wos_send_email(__FUNCTION__, __LINE__, $tosend_args);
//        }
        $sr = $this->wos_send_email(__FUNCTION__, __LINE__, $tosend_args);
        if ( $sr === FALSE ) {
            return "__ERR_VOL_FAILED_ON_MX";
        } else if (! $sr ) {
            return "__ERR_VOL_FAILED";
        }
        
        //*** On log l'activité
        $now = round(microtime(TRUE)*1000);
        $uid = (  key_exists("rcpt_uid", $em_args) && !empty($em_args["rcpt_uid"]) ) ? $em_args["rcpt_uid"] : NULL;
        
        /*
         * [NOTE 13-12-14] @author L.C.
         * Dans certains cas (voire tous), la classe n'est pas disponible. 
         * Je ne peux pas me permettre de tester tous les cas.
         * Par précaution, on ne rentrera dans la vérification du Compte que si la classe existe.
         * D'autant plus que cette vérification n'est pas obligatoire.
         */
        if ( $uid && class_exists("TQR_ACCOUNT") ) {
            //On vérifie que le compte existe
            $TQACC = new TQR_ACCOUNT();
            
            $u_tab = $TQACC->exists_with_id($uid);
            if (! $u_tab ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $QO = new QUERY("qryl4emlacn1");
        $params = array(":exp" => $em_args["exp"], ":rcpt" => $em_args["rcpt"], ":rcpt_uid" => $uid, ":subj" => $tosend_args["subject"], ":type" => $em_tab["emodel.type"], ":mdlid" => $emid, ":tstamp" => $now, ":catg" => $this->em_catg[$em_args["catg"]]);
        $emlacid = $QO->execute($params);
        
        if ( !$emlacid ) {
            return "__ERR_VOL_UXPTD";
        }
        
        return $emlacid;
        
//        var_dump($em_tab["emodel.body"]);
    } 
    
    /************************************************* PRIVATE SCOPE ******************************************************/
    
    
}
