<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Entity de gestion des images sur WOS.
 * Cette classe n'hérite pas de PROD_ENTITY mais pour des soucis de formalisme on lui empreinte des noms de méthodes.
 * Cela permet d'avoir un code assez similaire sur tous les ENTIY
 *
 * @author Richard DIEUD <lou.carther@deuslynn-entreprise.com>
 */
class IMAGE_COVERACC extends IMAGE {
    
    private $acov_pdpicid;
    private $acov_accid;
    private $acov_width;
    private $acov_height;
    private $acov_top;
    
    private $needeed;
    
    /********* RULES ***********/
    private $_ART_SRV_TABLE;
    
    
    function __construct() {
            
        $_MAX_HEIGHT = 5500;
        $_MIN_HEIGHT = 250;
        $_MAX_WIDTH = 5500;
        $_MIN_WIDTH = 250;
        $_ALLOWED_EXT = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
        $_MAX_SIZE = 1048576 * 2.5; //Pour changer la valeur il suffit de changer le multiplicateur. 1048576 = 1Mo
        $_DIMS_FORMAT = WOS_IMG_ALLFORMAT; //NULL, WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT
                
        parent::__construct(__FILE__, __CLASS__." (OR IMAGE)", $_MAX_HEIGHT, $_MIN_HEIGHT, $_MAX_WIDTH, $_MIN_WIDTH, $_ALLOWED_EXT, $_MAX_SIZE, $_DIMS_FORMAT);
         
        $this->needeed = ["cov_w","cov_h","cov_t"];
        
        $this->entity_path = WOS_SYSDIR_PROD_COVER;
//        $this->_ART_SRV_TABLE = [2 => TQR_DEFAULT_SERVER_NAME];
        $this->_ART_SRV_TABLE = [
            2 => TQR_DEFAULT_SERVER_NAME,
            4 => "localhost"
        ];
        
    }
    
    

    /************************************ ON_CREATE *****************************************/
    
    public function on_create ($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On va (aussi) vérifier que les données attendues par IMAGE et ART_IMAGE sont présentes. Cela évite d'attendre que IMAGE gueule parce qu'il manque des choses.
        $needed = array_merge($this->needeed, ["pdpic_fn","pdpic_string","oeid"]);
        $com = array_intersect(array_keys($args), $needed);
        
        if (    
                ( !empty($args) && is_array($args) ) &&
                ( count($needed) === count($com) )
           ) 
        {
            
            //On vérifie que toutes les données sont présentes
            foreach ($args as $k => $v) {
                if ( ( empty($v) && $k !== "cov_t" ) && $std_err_enabled ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } else if ( ( empty($v) && $k !== "cov_t" ) && !$std_err_enabled ) {
//                    var_dump($k,$v);
                    return "__ERR_VOL_L4_DATAS_MSG";
                }
            }
            
            //Intéroger le serveur pour savoir si l'utilisateur existe toujours à l'heure de l'ajout
            $PA = new PROD_ACC();
            $u_tab = $PA->on_read_entity(["acc_eid"=>$args["oeid"]]);
            
            if (! $u_tab ) {
                return "__ERR_VOL_USER_GONE";
            }
            
            //Créer chemin
            $args["pdpic_path"] = $this->entity_path.$args["oeid"];
            
            
            /*
             * [DEPUIS 19-11-15] @author BOR
             *      Le code ci-dessus était COMPLETEMENT obselète et ne permettait pas la portabilité du code d'un environnement à un autre.
             *      Par défaut le serveur sera toujours 2. Sauf si on est en local où on choisit 4 ! 
             */
//            $srvid = ( ( defined("WOS_MAIN_HOST") && WOS_MAIN_HOST === "localhost" ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? 4 : 2;
            $srvid = ( ( defined("WOS_MAIN_HOST") && in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? 4 : 2;
            
            //On insère les données sur le serveur
            $args["srvid"] = $srvid;
            $args["srvname"] = $this->_ART_SRV_TABLE[$srvid];
            
            /*
            //On insère les données sur le serveur
            $args["srvid"] = 2;
            $args["srvname"] = $this->_ART_SRV_TABLE[2];
            //*/
            
            /*
             * Il est normalement IMPOSSIBLE que $r ne soit pas défini.
             * Il contient le pdpic_eid de l'IMAGE ajoutée.
             */
            $infos =  parent::on_create_entity($args);
            
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, $infos,'v_d');
            
            if (! ( is_array($infos) && !empty($infos) ) && ( key_exists("picid", $infos) ) ) 
                    return;
            
            /* 
             * On va créer l'occurrence de l'image COVER_ACC.
             * Créer un tableau ici est aussi une manière détournée de revérifier si les valeurs existent.
             * Du moins les clés !
             * * */
            //Le path qui va servir pour le rename de l'image
            $npath = $args["pdpic_path"];
            $nargs = [
                "picid"             => $infos["picid"],
                "acov_accid"        => $u_tab["pdaccid"],
                "acov_height"       => $args["cov_h"],
                "acov_width"        => $args["cov_w"],
                "acov_top"          => $args["cov_t"],
                "pdpic_eid"         => $infos["pdpic_eid"],
                "pdpic_creadate"    => $infos["pdpic_creadate"],
                "pdpic_realpath"    => $infos["pdpic_realpath"],
                "srvid"             => $args["srvid"],
                "npath"             => $npath,
                "oid"               => $u_tab["pdaccid"],
                "oeid"              => $u_tab["pdacc_eid"],
                "ofn"               => $u_tab["pdacc_ufn"],
                "opsd"              => $u_tab["pdacc_upsd"],
                "oppic"             => $u_tab["pdacc_uppic"]
            ];
            
            $id = $this->write_new($nargs);
            
            $nargs["acovid"] = $id;
            $nargs["pdpic_realpath"] = $this->pdpic_realpath;
            
            return $nargs;
                
        } else {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$needed],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",  array_keys($args)],'v_d');
            $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__,TRUE);
        }
    }
    
    /*****************************************************************************************************************/
    /************************************************ ON_DELETE SCOPE ************************************************/
    
    public function on_delete ($args, $_OPTIONS = NULL) {
        /*
         * Permet de supprimer l'image de couverture active.
         */
        $PA = new PROD_ACC();
        
        /*
         * ETAPE : 
         * On intéroge le serveur pour savoir si l'utilisateur existe toujours à l'heure de l'ajout.
         */
        if ( $_OPTIONS && in_array("FAST_WAY", $_OPTIONS) ) {
            $u_tab = $PA->on_read_entity(["accid" => $args]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab) ) {
                $this->Ajax_Return("err",$u_tab);
            } else if (! $u_tab ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        
        /*
         * ETAPE : 
         * On vérifie que l'utilisateur a bien une image de profil.
         */
        $cvdts = $PA->onread_acquiere_cover_datas($args);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cvdts) ) {
            $this->Ajax_Return("err",$cvdts);
        } else if (! $cvdts ) {
            $this->Ajax_Return("err","__ERR_VOL_NOTGT");
        }
        $acv_apci = $cvdts["acovid"];
        $acv_pci = $cvdts["acov_pdpicid"];
        
            
        /*
         * ETAPE :
         * On supprime dans la table ACOV.
         */
        $QO = new QUERY("qryl4acovn4");
        $params = array(":accid" => $args);
        $QO->execute($params);

        /*
         * ETAPE :
         * On supprime l'image dans la base de données et de manière physique
         */
        return parent::on_delete_entity($acv_pci);
            
    }
    
    
    /************************************ ON_READ *****************************************/

    public function on_read ($args) {
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("pdpic_eid", $args) && !empty($args["pdpic_eid"]) ) ) {
            if ( empty($this->pdpic_eid) ) {
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else { 
                $pdpic_eid = $this->pdpic_eid;
            }
        } else {
            $pdpic_eid = $args["pdpic_eid"];
        }
        
        if ( !$this->exists($pdpic_eid) ) {
            return;
        }
        
        return parent::on_read_entity($args);
    }
    
    private function write_new ($args) {
        
        //On crée le prod_fn
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->pdpic_art_eid, TQR_DEFAULT_SERVER_NAME, $args["pdpic_creadate"], $args["picid"]], 'v_d');
        $pdpic_prod_fn = $this->imgname_encode($args["oeid"], TQR_DEFAULT_SERVER_NAME, $args["pdpic_creadate"], $args["picid"]);
        
        // *** Enregistrer l'image dans la table des IMAGE_COVERACC *** //
        
        //On vérifie si un Cover n'existe pas déjà. On récupère les données pour pouvoir les supprimer physiquement après la suppression au niveau de la base de données 
        $QO = new QUERY("qryl4acovn1");
        $params = array(":accid" => $args["acov_accid"]);
        $old_files = $QO->execute($params);
        
        /*
         * On supprime toutes les occurrences de l'utilisateur.
         * En effet, ce n'est pas normal qu'il y ait plusieurs lignes pour un même utilisateur.
         * Il s'agirait surement d'une erreur qu'il faut corriger. On s'autorise ainsi une certaine marge d'erreur.
         */
        $QO = new QUERY("qryl4acovn3");
        $params = array(":accid" => $args["acov_accid"]);
        $QO->execute($params);
        
        //Si des occurrences existaient on supprime physiquement leurs représentations physiques
        if ( $old_files && is_array($old_files) && count($old_files) ) {
            
            foreach ($old_files as $pic_tab) {
                
                $srvnm = $pic_tab["srv_name"];
                $FTH = new FTP_HANDLER($srvnm);
                if ( $FTH->ftp_file_exists($pic_tab["pdpic_full_ftppath"]) ) {

                    if (! $FTH->ftp_delete_file($pic_tab["pdpic_full_ftppath"]) ) {
                        return "__ERR_VOL_DELIMG_FAILED";
                    }
                }
            }
        }
       
        
        /*
         * On crée une nouvelle ligne dans la base de données.
         */
        $QO = new QUERY("qryl4acovn2");
        $params = array(":picid" => $args["picid"], ":accid" => $args["acov_accid"], ":height" => $args["acov_height"], ":width" => $args["acov_width"], ":top" => $args["acov_top"]);
        $id = $QO->execute($params);
        
        //On set prod_fn en renvoyant le chemin complet vers l'ancien fichier afin de correctement modifier le nom du fichier
        if (! $this->setpdpic_prod_fn($args["picid"], $pdpic_prod_fn, $args["pdpic_realpath"], $this->_ART_SRV_TABLE[$args["srvid"]], $args["npath"]) )
            return;
        
        //On load
        $load = [ "pdpic_eid" => $args["pdpic_eid"] ]; 
        parent::load_entity($load);
        
        return $id;
    }
    
    /***********************************************************************************************/
    /*************************************** SPECS *************************************************/
    
    public function valid ( $pdpic_string, &$pdpic_infos ) {
        
        parent::check_img_compliance ($pdpic_string, $pdpic_infos);
        
    }
    
    
    /************************************************************************************************/
    /*********************************** GETTERS and SETTERS ****************************************/
    // <editor-fold defaultstate="collapsed" desc="GEETERS and SETTERS">
    public function getAcov_pdpicid() {
        return $this->acov_pdpicid;
    }

    public function getAcov_accid() {
        return $this->acov_accid;
    }

    public function getAcov_width() {
        return $this->acov_width;
    }

    public function getAcov_height() {
        return $this->acov_height;
    }

    public function getAcov_top() {
        return $this->acov_top;
    }

    public function getNeedeed() {
        return $this->needeed;
    }

// </editor-fold>




}
