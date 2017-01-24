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
class IMAGE_PFLPIC extends IMAGE {
    
    private $uppic_width;
    private $uppic_height;
    
    function __construct() {
        
        $_MAX_HEIGHT = 1000;
        $_MIN_HEIGHT = 70;
        $_MAX_WIDTH = 1000;
        $_MIN_WIDTH = 70;
        $_ALLOWED_EXT = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];
        $_MAX_SIZE = 1048576 * 2.5; //Pour changer la valeur il suffit de changer le multiplicateur. 1048576 = 1Mo
        $_DIMS_FORMAT = WOS_IMG_IS_SQUARE; //NULL, WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT
                
        parent::__construct(__FILE__, __CLASS__." (OR IMAGE)", $_MAX_HEIGHT, $_MIN_HEIGHT, $_MAX_WIDTH, $_MIN_WIDTH, $_ALLOWED_EXT, $_MAX_SIZE, $_DIMS_FORMAT);
        
        
        $this->entity_path = WOS_SYSDIR_PROD_PFLPIC;
        $this->_ART_SRV_TABLE = [
            2 => TQR_DEFAULT_SERVER_NAME,
            4 => "localhost"
        ];
    }

    /************************************ ON_CREATE *****************************************/
    
    public function on_create ($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On va (aussi) vérifier que les données attendues par IMAGE et ART_IMAGE sont présentes. Cela évite d'attendre que IMAGE gueule parce qu'il manque des choses.
        $needed = array_merge(["pdpic_fn","pdpic_string","oeid"]);
        $com = array_intersect($args, $needed);
        
        if ( ( !empty($args) && is_array($args) ) && ( count($needed) != count($com) ) ) 
        {
            
            //Intéroger le serveur pour savoir si l'utilisateur existe toujours à l'heure de l'ajout
            $PA = new PROD_ACC();
            $u_tab = $PA->exists($args["oeid"], TRUE);
            
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
            
            $infos =  parent::on_create_entity($args);
            
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, $infos,'v_d');
            
            if (! ( is_array($infos) && !empty($infos) ) && ( key_exists("picid", $infos) ) ) {
                return;
            }
                    
            
            $npath = $args["pdpic_path"];
            $nargs = [
                "picid"             => $infos["picid"],
                "pdpp_accid"        => $u_tab["pdaccid"],
                "pdpic_eid"         => $infos["pdpic_eid"],
                "pdpic_creadate"    => $infos["pdpic_creadate"],
                "pdpic_realpath"    => $infos["pdpic_realpath"],
                "srvid"             => $args["srvid"],
                "npath"             => $npath,
                "oid"               => $u_tab["pdaccid"],
                "oeid"              => $u_tab["pdacc_eid"],
                "ofn"               => $u_tab["pdacc_ufn"],
                "opsd"              => $u_tab["pdacc_upsd"],
            ];
            
            $id = $this->write_new($nargs);
            
            $nargs["pdppid"] = $id;
            $nargs["pdpic_realpath"] = $this->pdpic_realpath;
            
            return $nargs;
                
        } else {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["pdpic_ueid"],'v_d');
            $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__,TRUE);
        }
    }
    
    public function oncreate_defaultpic ($accid, $no_load = FALSE) {
        /*
         * no_load = Perrmet de dire qu'on ne veut pas vraiment de retour. Que l'action se fasse sans pour autant aller recharger PA.
         * Permet aussi de gagner du temps dans la procédure en la raccourcissant.
         */
        /*
         * Permet de lier l'image par défaut à un Compte.
         */
        
        //Intéroger le serveur pour savoir si l'utilisateur existe toujours à l'heure de l'ajout
        $PA = new PROD_ACC();
        $u_tab = $PA->on_read_entity(["accid" => $accid]);
        
        $path = NULL;
        if (! $u_tab ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        //On vérifie si une image de profil existe.
        /*
         * La plage 1 à 100 est reservée pour les images par défaut.
         * Si l'identifiant est dans cette plage, alors on "devine" qu'il s'agit d'une image par défaut.
         * A vrai dire, je ne sais pas comment être sur que l'image correspond à l'image par défaut.
         * Je choisi donc une méthode alternative.
         * 
         * L'identifiant 1 correspond à l'image par défaut de sexe masculin.
         * Si l'identifiant est au delà de 100, alors c'est forcement une image qui n'est pas par défaut.
         */
        
        if ( intval($u_tab["pdacc_uppicid"]) !== 1 && intval($u_tab["pdacc_uppicid"]) > 100 ) {
            
            //On supprime dans la table PFLPIC et PICTURE
            $QO = new QUERY("qryl4pdppn4");
            $params = array(":accid" => $accid);
            $QO->execute($params);
            
            //On supprime l'image dans la base de données et de manière physique
            $del = parent::on_delete_entity($u_tab["pdacc_uppicid"]);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $del) ) {
                return $del;
            }
            
            if (! $no_load ) {
                $u_tab = $PA->on_read_entity(["accid" => $accid]);
                $path = $u_tab["pdacc_uppic"];
                return $path;
            } else {
                return TRUE;
            }
            
        } else {
            
            if (! $no_load ) {
                $path = $u_tab["pdacc_uppic"];
                return $path;
            } else {
                return TRUE;
            }
            
        }
        
    }
    
    
    /************************************ ON_DELETE *****************************************/
    
    public function on_delete ($args) {
        return parent::on_delete_entity($args);
    }
    
    
    /************************************ ON_READ *****************************************/

    public function on_read ($args) {
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("pdpic_eid", $args) && !empty($args["pdpic_eid"]) ) ) 
        {
            if ( empty($this->pdpic_eid) ){
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else {
                $pdpic_eid = $this->pdpic_eid;
            }
            
        } else $pdpic_eid = $args["pdpic_eid"];
        
        if ( !$this->exists($pdpic_eid) )
            return;
        
         return parent::on_read_entity($args);
    }
    
    private function write_new ($args) {
        
        //On crée le prod_fn
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->pdpic_art_eid, TQR_DEFAULT_SERVER_NAME, $args["pdpic_creadate"], $args["picid"]], 'v_d');
        $pdpic_prod_fn = $this->imgname_encode($args["oeid"], TQR_DEFAULT_SERVER_NAME, $args["pdpic_creadate"], $args["picid"]);
        
        // *** Enregistrer l'image dans la table des IMAGE_COVERACC *** //
        
        //On vérifie si un Cover n'existe pas déjà. On récupère les données pour pouvoir les supprimer physiquement après la suppression au niveau de la base de données 
        $QO = new QUERY("qryl4pdppn1");
        $params = array(":accid" => $args["pdpp_accid"]);
        $old_files = $QO->execute($params);
        
        /*
         * On supprime toutes les occurrences de l'utilisateur.
         * En effet, ce n'est pas normal qu'il y ait plusieurs lignes pour un même utilisateur.
         * Il s'agirait surement d'une erreur qu'il faut corriger. On s'autorise ainsi une certaine marge d'erreur.
         */
        $QO = new QUERY("qryl4pdppn3");
        $params = array(":accid" => $args["pdpp_accid"]);
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
        $QO = new QUERY("qryl4pdppn2");
        $params = array(":picid" => $args["picid"], ":accid" => $args["pdpp_accid"]);
        $id = $QO->execute($params);
        
        //On set prod_fn en renvoyant le chemin complet vers l'ancien fichier afin de correctement modifier le nom du fichier
        $r__ = $this->setpdpic_prod_fn($args["picid"], $pdpic_prod_fn, $args["pdpic_realpath"], $this->_ART_SRV_TABLE[$args["srvid"]], $args["npath"]); 
        if (! $r__ ) {
            return;
        }
        
        /*
         * [DEPUIS 25-06-15] @BOR
         * On met à jour la table SRH_PFL
         */
        $QO = new QUERY("qryl4pdaccn30");
        $params = array(":prpath" => $r__["pct_realpath"], ":uid" => $args["pdpp_accid"]);
        $QO->execute($params);        
                
        //On load
        $load = [ "pdpic_eid" => $args["pdpic_eid"] ]; 
        parent::load_entity($load);
        
        return $id;
    }
    /***********************************************************************************************/
    /*************************************** SPECS *************************************************/
    
    
    
    /************************************************************************************************/
    /*********************************** GETTERS and SETTERS ****************************************/
    
}
