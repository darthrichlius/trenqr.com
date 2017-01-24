<?php

class CHATBOX extends PROD_ENTITY {
    
    private $chbxid;
    private $chbx_uid;
    private $chbx_ueid;
    private $chbx_fstart;
    private $chbx_fstart_tstamp;
    private $chbx_state;
    private $chbx_usts;
    private $chbx_shwconv;
    private $chbx_onlyol;
    private $chbx_dsplhrs;
    private $chbx_dsplnotfeph;
    private $chbx_meonleft;
    private $chbx_noblocked;
    private $chbx_enterena;
    private $chbx_lastupd;
    private $chbx_lastupd_tstamp;
    // TENDANCE SCOPE
    private $chbxtr_stayco;
    
    /******** RULES ********/
    private $_SRH_1ST_LIMIT;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["chbxid","chbx_uid","chbx_ueid","chbx_fstart","chbx_fstart_tstamp","chbx_state","chbx_usts","chbx_shwconv","chbx_onlyol","chbx_dsplhrs","chbx_dsplnotfeph","chbx_meonleft","chbx_noblocked","chbx_enterena","chbx_lastupd","chbx_lastupd_tstamp","chbxtr_stayco"];
        $this->needed_to_loading_prop_keys = ["chbxid","chbx_ueid","chbx_fstart","chbx_fstart_tstamp","chbx_state","chbx_usts","chbx_shwconv","chbx_onlyol","chbx_dsplhrs","chbx_dsplnotfeph","chbx_meonleft","chbx_noblocked","chbx_enterena","chbx_lastupd","chbx_lastupd_tstamp","chbxtr_stayco"];
        
        $this->needed_to_create_prop_keys = ["uid"];
        
        /********* RULES **********/
        $this->_SRH_1ST_LIMIT = 5;
        
    }

    protected function build_volatile($args) {}

    protected function exists($args) {
        
    }

    protected function exists_with_id($args) {
        
    }

    protected function init_properties($datas) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $datas, TRUE);
        
        foreach($datas as $k => $v) {
            $$k = $v;
            
            if (! (!empty($this->prop_keys) && is_array($this->prop_keys) && count($this->prop_keys) ) ) {
                $this->signalError ("err_sys_l4comn4", __FUNCTION__, __LINE__);
            }
            
            if ( count($this->needed_to_loading_prop_keys) != count(array_intersect(array_keys($datas), $this->needed_to_loading_prop_keys)) ) {
                
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED => ",$this->needed_to_loading_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ",array_keys($datas)],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>",  array_diff($this->needed_to_loading_prop_keys, array_keys($datas))],'v_d');
                $this->signalError ("err_sys_l4comn5", __FUNCTION__, __LINE__,TRUE);
            } 
            
            /*
             * On vérifie que les données entrantes sont attendues.
             * NOTE : On ne vérifie que les clés.
             */
            if (! in_array($k, $this->prop_keys) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,"KEY => ".$k,'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->prop_keys,'v_d');
                $this->signalError ("err_sys_l4comn3", __FUNCTION__, __LINE__,TRUE);
            } 
            
            $this->all_properties[$k] = $this->$k = $datas[$k];
        }
    }

    protected function load_entity($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //Intéroger la base de données pour récupérer les données sur la RELATION
        $datas = $this->exists($id);
        
        if ( !$datas && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( !$datas && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $loads = [
            "chbxid"                => $datas["chbxid"],
            "chbx_uid"              => $datas["chbx_uid"],
            "chbx_ueid"             => $datas["chbx_ueid"],
            "chbx_fstart"           => $datas["chbx_fstart"],
            "chbx_fstart_tstamp"    => $datas["chbx_fstart_tstamp"],
            "chbx_state"            => $datas["chbx_state"],
            "chbx_usts"             => $datas["chbx_usts"],
            "chbx_shwconv"          => $datas["chbx_shwconv"],
            "chbx_onlyol"           => $datas["chbx_onlyol"],
            "chbx_dsplhrs"          => $datas["chbx_dsplhrs"],
            "chbx_dsplnotfeph"      => $datas["chbx_dsplnotfeph"],
            "chbx_meonleft"         => $datas["chbx_meonleft"],
            "chbx_noblocked"        => $datas["chbx_noblocked"],
            "chbx_enterena"         => $datas["chbx_enterena"],
            "chbx_lastupd"          => $datas["chbx_lastupd"],
            "chbx_lastupd_tstamp"   => $datas["chbx_lastupd_tstamp"],
            // TENDANCE SCOPE
            "chbxtr_stayco"         => $datas["chbxtr_stayco"]
        ];
        
        /************************* EXTRAS DATAS **************************/

        $this->init_properties($loads);
        $this->is_instance_load = TRUE;
        return $loads;
    }

    public function on_alter_entity($args) {
        
    }

    public function on_create_entity($args) {
        
    }

    public function on_delete_entity($args) {
        
    }

    public function on_read_entity($args) {
        
    }

    protected function write_new_in_database($args) {
        
    }
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** SPECIFICS SCOPE ****************************************************************************/
    /*************************************************************************************************************************************************************************/
    
    public function Search ($ref_uid, $qstr, $RNG_LMT = NULL, $WITH_DEL_OPT = FALSE) {
        /*
         * Permet de chercher la liste des Conversations et/ou des Utilisateurs liés à la chaine de recherche passée en paramètre.
         * Techniquement la méthode procède la manière suivante :
         *      (1) Rechercher dans les conversations de l'utilisateur de référence
         *      (2) Rechercher dans la liste des amis
         * 
         * La méthode admet aussi une donnée dite "Range". Elle guide la requete SQL dans sa recherche. 
         * La valeur par défaut "0," indique que l'on veut tous les données dans la limite définie par défaut.
         * Par ailleurs, l'offset sera toujours 0 pour que les données soient toujours à jour.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ref_uid, $qstr]);
        
        if ( isset($RNG_LMT) && !is_int($RNG_LMT) ) {
            return "__ERR_VOL_RG_RULES";
        }
        
        $offset = 0;
        $limit = $merge = $convrs = $parleys = NULL;
        
        //On détermine la Limite
        $limit = (! $RNG_LMT ) ? $this->_SRH_1ST_LIMIT : $RNG_LMT;
            
        //On recherche dans les Conversations
        if (! $WITH_DEL_OPT ) {
            $QO = new QUERY("qryl4chbxn3");
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid2'     => $ref_uid, 
                ':pvt_uid3'     => $ref_uid, 
                ':pvt_uid4'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid6'     => $ref_uid, 
                ':pvt_uid7'     => $ref_uid, 
                ':pvt_uid8'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
        } else {
            //rdm = Relation Doesn't Matter
            $QO = new QUERY("qryl4chbxn6_rdm");
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':pvt_uid11'    => $ref_uid, 
                ':pvt_uid12'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
            /*//[21-09-15] @author BOR
            $QO = new QUERY("qryl4chbxn6"); 
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid2'     => $ref_uid, 
                ':pvt_uid3'     => $ref_uid, 
                ':pvt_uid4'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid6'     => $ref_uid, 
                ':pvt_uid7'     => $ref_uid, 
                ':pvt_uid8'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':pvt_uid11'    => $ref_uid, 
                ':pvt_uid12'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
            //*/
        }
        
        $convrs = $QO->execute($params);
        
        $PA = new PROD_ACC();
        if ( $convrs ) {
            foreach ($convrs as &$conv) {
//                if ( true ) {
                if ( empty($conv["tgt_uppic"]) ) {
                    $conv["tgt_uppic"] = $PA->onread_acquiere_pp_datas($conv["tgt_uid"],$conv["tgt_ugdr"])["pic_rpath"];
                }
            }
        }  
//        var_dump(__LINE__,__FUNCTION__,$convrs);
//        exit();
        /*********************/
        //On recherche dans les Conversations vides
        if (! $WITH_DEL_OPT ) {
            $QO = new QUERY("qryl4chbxn5");
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid2'     => $ref_uid, 
                ':pvt_uid3'     => $ref_uid, 
                ':pvt_uid4'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid6'     => $ref_uid, 
                ':pvt_uid7'     => $ref_uid, 
                ':pvt_uid8'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
        } else {
            $QO = new QUERY("qryl4chbxn8");
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid2'     => $ref_uid, 
                ':pvt_uid3'     => $ref_uid, 
                ':pvt_uid4'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid6'     => $ref_uid, 
                ':pvt_uid7'     => $ref_uid, 
                ':pvt_uid8'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
        }
        $convoid = $QO->execute($params);
        
        if ( $convoid ) {
            foreach ($convoid as &$conv) {
                if ( empty($conv["tgt_uppic"]) ) {
                    $conv["tgt_uppic"] = $PA->onread_acquiere_pp_datas($conv["tgt_uid"],$conv["tgt_ugdr"])["pic_rpath"];
                }
            }
        } 
        
        /*********************/
        //On recherche dans la liste des Amis
        if (! $WITH_DEL_OPT ) {
            $QO = new QUERY("qryl4chbxn4");
            $params = array( 
                ':sqy1'     => $qstr, 
                ':pvt_uid1' => $ref_uid, 
                ':pvt_uid2' => $ref_uid, 
                ':pvt_uid3' => $ref_uid, 
                ':pvt_uid4' => $ref_uid, 
                ':pvt_uid5' => $ref_uid, 
                ':pvt_uid6' => $ref_uid, 
                ':offset'   => $offset, 
                ':limit'    => $limit, 
            );
        } else {
            $QO = new QUERY("qryl4chbxn7");
            $params = array( 
                ':sqy1'     => $qstr, 
                ':pvt_uid1' => $ref_uid, 
                ':pvt_uid2' => $ref_uid, 
                ':pvt_uid3' => $ref_uid, 
                ':pvt_uid4' => $ref_uid, 
                ':pvt_uid5' => $ref_uid, 
                ':pvt_uid6' => $ref_uid, 
                ':pvt_uid7' => $ref_uid, 
                ':pvt_uid8' => $ref_uid, 
                ':offset'   => $offset, 
                ':limit'    => $limit, 
            );
        }
        
        $parleys = $QO->execute($params);
        if ( $parleys ) {
            foreach ($parleys as &$user) {
                if ( empty($user["tgt_uppic"]) ) {
                    $user["tgt_uppic"] = $PA->onread_acquiere_pp_datas($user["tgt_uid"],$user["tgt_ugdr"])["pic_rpath"];
                }
            }
        }
        
        $merge = [
            //Liste des Conversations qui correspondent à la chaine recherché mais qui n'ont pas de Messages donc ne peuvent apparaitrent 
            "convoid" => $convoid,
            "convers" => $convrs,
            "parleys" => $parleys 
        ];
        
        return $merge;
        
    }
    
    public function SearchFrom ($ref_uid, $qstr, $cveid, $RNG_LMT = NULL, $WITH_DEL_OPT = FALSE) {
        /*
         * Permet de chercher la liste des Conversations et/ou des Utilisateurs liés à la chaine de recherche passée en paramètre.
         * Cette recherche 
         * Techniquement la méthode procède la manière suivante :
         *      (1) Rechercher dans les conversations de l'utilisateur de référence
         *      (2) Rechercher dans la liste des amis
         * 
         * La méthode admet aussi une donnée dite "Range". Elle guide la requete SQL dans sa recherche. 
         * La valeur par défaut "0," indique que l'on veut tous les données dans la limite définie par défaut.
         * Par ailleurs, l'offset sera toujours 0 pour que les données soient toujours à jour.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ref_uid, $qstr]);
        
        if ( isset($RNG_LMT) && !is_int($RNG_LMT) ) {
            return "__ERR_VOL_RG_RULES";
        }
        
        $offset = 0;
        $limit = $merge = $convrs = $parleys = NULL;
        
        //On détermine la Limite
        $limit = (! $RNG_LMT ) ? $this->_SRH_1ST_LIMIT : $RNG_LMT;
            
        //On recherche dans les Conversations
        if (! $WITH_DEL_OPT ) {
            $QO = new QUERY("qryl4chbxn3");
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid2'     => $ref_uid, 
                ':pvt_uid3'     => $ref_uid, 
                ':pvt_uid4'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid6'     => $ref_uid, 
                ':pvt_uid7'     => $ref_uid, 
                ':pvt_uid8'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
        } else {
            $QO = new QUERY("qryl4chbxn6");
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid2'     => $ref_uid, 
                ':pvt_uid3'     => $ref_uid, 
                ':pvt_uid4'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid6'     => $ref_uid, 
                ':pvt_uid7'     => $ref_uid, 
                ':pvt_uid8'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':pvt_uid11'    => $ref_uid, 
                ':pvt_uid12'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
        }
        
        $convrs = $QO->execute($params);
        
        $PA = new PROD_ACC();
        if ( $convrs ) {
            foreach ($convrs as &$conv) {
                if ( empty($conv["tgt_uppic"]) ) {
                    $conv["tgt_uppic"] = $PA->onread_acquiere_pp_datas($conv["tgt_uid"],$conv["tgt_ugdr"])["pic_rpath"];
                }
            }
        } 
        
        /*********************/
        //On recherche dans les Conversations vides
        if (! $WITH_DEL_OPT ) {
            $QO = new QUERY("qryl4chbxn5");
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid2'     => $ref_uid, 
                ':pvt_uid3'     => $ref_uid, 
                ':pvt_uid4'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid6'     => $ref_uid, 
                ':pvt_uid7'     => $ref_uid, 
                ':pvt_uid8'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
        } else {
            $QO = new QUERY("qryl4chbxn8");
            $params = array( 
                ':sqy1'         => $qstr, 
                ':sqy2'         => $qstr, 
                ':pvt_uid1'     => $ref_uid, 
                ':pvt_uid2'     => $ref_uid, 
                ':pvt_uid3'     => $ref_uid, 
                ':pvt_uid4'     => $ref_uid, 
                ':pvt_uid5'     => $ref_uid, 
                ':pvt_uid6'     => $ref_uid, 
                ':pvt_uid7'     => $ref_uid, 
                ':pvt_uid8'     => $ref_uid, 
                ':pvt_uid9'     => $ref_uid, 
                ':pvt_uid10'    => $ref_uid, 
                ':offset'       => $offset, 
                ':limit'        => $limit, 
            );
        }
        $convoid = $QO->execute($params);
        
        if ( $convoid ) {
            foreach ($convoid as &$conv) {
                if ( empty($conv["tgt_uppic"]) ) {
                    $conv["tgt_uppic"] = $PA->onread_acquiere_pp_datas($conv["tgt_uid"],$conv["tgt_ugdr"])["pic_rpath"];
                }
            }
        } 
        
        /*********************/
        //On recherche dans la liste des Amis
        if (! $WITH_DEL_OPT ) {
            $QO = new QUERY("qryl4chbxn4");
            $params = array( 
                ':sqy1'     => $qstr, 
                ':pvt_uid1' => $ref_uid, 
                ':pvt_uid2' => $ref_uid, 
                ':pvt_uid3' => $ref_uid, 
                ':pvt_uid4' => $ref_uid, 
                ':pvt_uid5' => $ref_uid, 
                ':pvt_uid6' => $ref_uid, 
                ':offset'   => $offset, 
                ':limit'    => $limit, 
            );
        } else {
            $QO = new QUERY("qryl4chbxn7");
            $params = array( 
                ':sqy1'     => $qstr, 
                ':pvt_uid1' => $ref_uid, 
                ':pvt_uid2' => $ref_uid, 
                ':pvt_uid3' => $ref_uid, 
                ':pvt_uid4' => $ref_uid, 
                ':pvt_uid5' => $ref_uid, 
                ':pvt_uid6' => $ref_uid, 
                ':pvt_uid7' => $ref_uid, 
                ':pvt_uid8' => $ref_uid, 
                ':offset'   => $offset, 
                ':limit'    => $limit, 
            );
        }
        
        $parleys = $QO->execute($params);
        if ( $parleys ) {
            foreach ($parleys as &$user) {
                if ( empty($user["tgt_uppic"]) ) {
                    $user["tgt_uppic"] = $PA->onread_acquiere_pp_datas($user["tgt_uid"],$user["tgt_ugdr"])["pic_rpath"];
                }
            }
        }
        
        $merge = [
            //Liste des Conversations qui correspondent à la chaine recherché mais qui n'ont pas de Messages donc ne peuvent apparaitrent 
            "convoid" => $convoid,
            "convers" => $convrs,
            "parleys" => $parleys 
        ];
        
        return $merge;
        
    }
    
    public function FirstConversation ($uid) {
        /*
         * Récupère les Conversations d'un utilisateur en mode FIRST.
         * Le mode FIRST correspond à celui où on récupère les x Conversations les plus récentes.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($uid);
        if (!$exists) {
            return "__ERR_VOL_U_G";
        }
        
        $CBCONV = new CHBX_CONVRS();
        
    }
    
    public function PullConversFrom () {
        /*
         * Permet de récupérer les Conversations lié à un Compte à partir d'une Conversation pivot
         */
    }
    
    /*************************************************************************************************************************************************************************/
    /*********************************************************************** GETTERS and SETTERS SCOPE ***********************************************************************/
    /*************************************************************************************************************************************************************************/

}