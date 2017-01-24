<?php

class VIDEO_ART extends VIDEO {
    
    private $needeed;
    private $vid_artid;
    private $vid_art_eid;
    
    /**** RULES ****/
    private $_ART_PATHS_TABLE;
    private $_ART_SRV_TABLE;
    
    function __construct() {
        
        $_MAX_HEIGHT = 5000;
        $_MIN_HEIGHT = 100;
        $_MAX_WIDTH = 5000;
        $_MIN_WIDTH = 100;
        $_ALLOWED_EXT = ["mp4"];
//        $_MAX_SIZE = 1048576 * 2.5; //Pour changer la valeur il suffit de changer le multiplicateur. 1048576 = 1Mo
        /*
         * [DEPUIS 21-01-16]
         */
        $_MAX_SIZE = 1048576 * 20;
        $_DIMS_FORMAT = NULL; //NULL, WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT
                
        parent::__construct(__FILE__, __CLASS__." (OR IMAGE)", $_MAX_HEIGHT, $_MIN_HEIGHT, $_MAX_WIDTH, $_MIN_WIDTH, $_ALLOWED_EXT, $_MAX_SIZE, $_DIMS_FORMAT);
        
        //NEEDED par IMAGE (Mère) ["fname","vid_data","vid_path (ART_IMG)","server_id (ART)","is_default" (ART_IMG)]
        $this->needeed = ["vid_artid","vid_art_eid","vid_ueid"];
        
        /************** RULES *************/
        
        $this->_ART_PATHS_TABLE = [
            1 => WOS_SYSDIR_PROD_ARTIMAGE_SRV1, 
            4 => WOS_SYSDIR_PROD_ARTIMAGE_SRV4
        ];
        
        $this->_ART_SRV_TABLE = [
            1 => TQR_WH2_SERVER_NAME, 
            2 => TQR_DEFAULT_SERVER_NAME, 
            4 => "localhost"
        ];
        
    }
    
    public function onexists ($vid_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $vid_eid);
        
        $QO = new QUERY("qryl4tvidn5");
        $qparams = array(":vid_eid" => $vid_eid);  
        $datas = $QO->execute($qparams);
        
        return ( $datas ) ? $datas[0] : NULL;
    }
    
    public function onexists_with_id ($vidid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $vidid);
        
        $QO = new QUERY("qryl4tvidn6");
        $qparams = array(":vidid" => $vidid);  
        $datas = $QO->execute($qparams);
        
        return ( $datas ) ? $datas[0] : NULL;
    }

    /************************************************* ON_CREATE SCOPE ******************************************************/

    public function on_create ($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On va (aussi) vérifier que les données attendues par IMAGE et ART_IMAGE sont présentes. Cela évite d'attendre que IMAGE gueule parce qu'il manque des choses.
        $needed = array_merge($this->needeed, ["vid_fname","vid_data","srvid","srvname"]);

        $com = array_intersect($args, $needed);
        if (    
            ( !empty($args) && is_array($args) ) &&
            ( count($needed) != count($com) ) &&
            ( key_exists("vid_art_eid", $args) && !is_array($args["vid_art_eid"]) && !empty($args["vid_art_eid"]) ) &&
            ( key_exists("vid_artid", $args) && !is_array($args["vid_artid"]) && !empty($args["vid_artid"]) ) &&
            ( key_exists("vid_ueid", $args) && !is_array($args["vid_ueid"]) && !empty($args["vid_ueid"]) )
        ) 
        {
            //["vid_artid","vid_art_id","vidid"]
            $this->vid_artid = $args["vid_artid"];
            $this->vid_art_eid = $args["vid_art_eid"];

            //TODO : Vérifier si CALLER a transmis un chemin pour realpath
                //Sinon, on prend celui par défaut défini dans la classe

            //Créer chemin

            $this->entity_path = ( intval($args["srvid"]) === 1 ) ? WOS_SYSDIR_PROD_ARTIMAGE_SRV1 : WOS_SYSDIR_PROD_ARTIMAGE_SRV4;
            $args["vid_path"] = $this->entity_path.$args["vid_ueid"]."/videos";

//            var_dump(__LINE__,__FILE__,$args,defined("WOS_MAIN_SUBDOMAIN_ROOTPA"),WOS_MAIN_SUBDOMAIN_ROOTPA);
//            exit();
            /*
             * On ne sauvegarde pas "vid_ueid" car il ne nous sert à rien à part à créer le chemin.
             */

            /*
             * Il est normalement IMPOSSIBLE que $r ne soit pas défini.
             * Il contient le vid_eid de l'IMAGE ajoutée.
             */
            /*
             * RAPPEL DES CLES (ARGS)
             *      "vid_artid"
             *      "vid_art_eid"
             *      "vid_ueid"
             *      "vid_fname"
             *      "vid_data"
             *      "srvid"
             *      "srvname"
             * 
             * RAPPEL DES CLES (CLASS VIDEO)
             *      "vid_fn"
             *      "vid_string"
             *      "vid_path"
             *      "srvid"
             *      "srvname"
             */
            $vid_args = [
                "vid_artid"     => $args["vid_artid"],
                "vid_art_eid"   => $args["vid_art_eid"],
                "vid_ueid"      => $args["vid_ueid"],
                "vid_fn"        => $args["vid_fname"],
                "vid_string"    => $args["vid_data"],
                "vid_path"      => $args["vid_path"],
                "srvid"         => $args["srvid"],
                "srvname"       => $args["srvname"]
            ];

            $infos = parent::on_create_entity($vid_args);

//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, $infos,'v_d');
            if (! ( is_array($infos) && !empty($infos) && key_exists("vidid", $infos) ) ) {
                return;
            }

//            var_dump(__FILE__,__FUNCTION__,__LINE__,$infos,( is_array($infos) && !empty($infos) ) && ( key_exists("picid", $infos) ));
//            exit();

            /* 
             * On va créer l'occurrence de l'image ARTICLE.
             * Créer un tableau ici est aussi une manière détournée de revérifier si les valeurs existent.
             * Du moins les clés !
             *  */

            //Le path qui va servir pour le rename de l'image
            $npath = $this->_ART_PATHS_TABLE[$args["srvid"]].$args["vid_ueid"]."/videos";
            $nargs = [
                "vidid"           => $infos["vidid"],
                "vid_eid"         => $infos["vid_eid"],
                "vid_artid"       => $args["vid_artid"],
                "vid_creadate"    => $infos["vid_creadate"],
                "vid_realpath"    => $infos["vid_realpath"],
                /*
                 * [DEPUIS 22-05-16]
                 *      Provoquait des problèmes au niveau de la mémoire et de temps limite d'execution.
                 *      Cette donnée n'est pas obligatoire. Elle pourra être de nouveau réassignée dans le futur. 
                 */
//                "vid_string_b64"  => $args["vid_data"], 
                "vid_string_b64"  => NULL,
                "srvid"           => intval($args["srvid"]),
                "srvname"         => $args["srvname"],
                "npath"           => $npath
            ];
            $this->write_new($nargs);

            //On met à jour realpath avant d'envoyer. La valeur a été mise à jour via write_new(); 

            $nargs["vid_realpath"] = $this->vid_realpath;

//            var_dump(__FILE__,__FUNCTION__,__LINE__,$nargs);
//            exit();

            return $nargs;

        } else {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXCPECTED => ",$needed],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args,'v_d']);
            $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__,TRUE);
        }
    }
    
    /************************************************* ON_DELETE SCOPE ******************************************************/
    
    public function on_delete ($artvidid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie que l'Image existe
        $vid_tab = $this->exists_with_id($artvidid);
        
        if (! $vid_tab ) {
            return "__ERR_VOL_ARTVID_VID_GONE";
        } 
        
//        $srvnm = ( ( defined("WOS_MAIN_HOST") && WOS_MAIN_HOST === "localhost" ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $vid_tab["server_name"];
        $srvnm = ( ( defined("WOS_MAIN_HOST") && in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $vid_tab["server_name"];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$srvnm,$vid_tab]);
//        exit();
        
        $FTH = new FTP_HANDLER($srvnm);
        if (! $FTH->ftp_file_exists($vid_tab["vid_full_ftppath"]) ) {
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$vid_tab);
            return "__ERR_VOL_DELVID_MSG";
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$srvnm,$vid_tab]);
//        exit();
        
        //On supprime la représentation dans la table ArtVideos
        $QO = new QUERY("qryl4artvidn2");
        $qparams = array(
            ":id" => $vid_tab["vidid"]
        );  
        $QO->execute($qparams);
        
        return parent::on_delete_entity($vid_tab["vidid"]);
    }
    
    
    /************************************************* ON_READ SCOPE ******************************************************/

    public function on_read ($args) {
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("vid_eid", $args) && !empty($args["vid_eid"]) ) ) 
        {
            if ( empty($this->vid_eid) ) {
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else {
                $vid_eid = $this->vid_eid;
            }
        } else {
            $vid_eid = $args["vid_eid"];
        }
        
        if ( !$this->exists($vid_eid) ){
            return;
        }
            
        return parent::on_read_entity($args);
    }
    
    private function write_new ($args) {
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->vid_art_eid, TQR_DEFAULT_SERVER_NAME, $args["vid_creadate"], $args["vidid"]], 'v_d');
        //On crée le prod_fn
        
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$args);
//            exit();
        
        $vid_prod_fn = $this->vidname_encode($this->vid_art_eid, $this->_ART_SRV_TABLE[$args["srvid"]], $args["vid_creadate"], $args["vidid"]);
        
        /*
         * On set prod_fn en renvoyant le chemin complet vers l'ancien fichier afin de correctement modifier le nom du fichier
         */
        if (! $this->setvid_prod_fn($args["vidid"], $vid_prod_fn, $args["vid_realpath"], $this->_ART_SRV_TABLE[$args["srvid"]], $args["npath"]) ) {
            return;
        }
        
        /*
         * Enregistrer l'image dans la table des IMAGE_ART
         */
        $QO = new QUERY("qryl4artvidn1");
        $params = array(
            ":av_vidid" => $args["vidid"], 
            ":av_artid" => $this->vid_artid
        );
        $datas = $QO->execute($params);
        
        /* On load l'Entity (Enfin) */
        $load = [ "vid_eid" => $args["vid_eid"] ]; 
        
        parent::load_entity($load);
        
        return TRUE;
    }
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SPECS SCOPE ***************************************************************************/
    
    public function valid ( $vid_data, &$vid_infos ) {
        
        parent::check_vid_compliance($vid_data, $vid_infos);
        
    }
    
    /* A Faire dans les classes filles
        //TODO : A partir de l'identifiant on crée le prod_name
        $vid_prod_fn = $this->vidname_encode($this->art_oncreate_args["vid_ueid"], $server_name, $upload_timestamp, $picid);
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$vid_prod_fn,'v_d');
        //TODO : On insère dans la base de donnéees l'ieid et prod_name
        //...
    */
    
    public function exists_from_artid ($artid) {
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$artid], 'v_d');
         
        $QO = new QUERY("qryl4artvidn3");
        $params = array ( ":aid" => $artid );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : [];
     }
    
    
    /***************************************************************************************************************************************************************************/
    /*************************************************************************** GETTERS and SETTERS ***************************************************************************/
    
    public function getvid_artid() {
        return $this->vid_artid;
    }

    public function getvid_art_eid() {
        return $this->vid_art_eid;
    }
    
    public function setvid_art_eid($vid_art_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $vid_art_eid);
        
        $this->vid_art_eid = $vid_art_eid;
        
        //TODO : On inscrit dans la base de données
        
    }

}
