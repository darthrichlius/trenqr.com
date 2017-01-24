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
class IMAGE_ART extends IMAGE {
    
    private $needeed;
    private $pdpic_artid;
    private $pdpic_art_eid;
    
    /**** RULES ****/
    private $_ART_PATHS_TABLE;
    private $_ART_SRV_TABLE;
    
    function __construct() {
        
        $_MAX_HEIGHT = 5000;
        $_MIN_HEIGHT = 100;
        $_MAX_WIDTH = 5000;
        $_MIN_WIDTH = 100;
        $_ALLOWED_EXT = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];
//        $_MAX_SIZE = 1048576 * 2.5; //Pour changer la valeur il suffit de changer le multiplicateur. 1048576 = 1Mo
        /*
         * [DEPUIS 21-01-16]
         */
        $_MAX_SIZE = 1048576 * 6;
        $_DIMS_FORMAT = NULL; //NULL, WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT
                
        parent::__construct(__FILE__, __CLASS__." (OR IMAGE)", $_MAX_HEIGHT, $_MIN_HEIGHT, $_MAX_WIDTH, $_MIN_WIDTH, $_ALLOWED_EXT, $_MAX_SIZE, $_DIMS_FORMAT);
        
        //NEEDED par IMAGE (Mère) ["pdpic_fn","pdpic_string","pdpic_path (ART_IMG)","server_id (ART)","is_default" (ART_IMG)]
        $this->needeed = ["pdpic_artid","pdpic_art_eid","pdpic_ueid"];
        
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
    
    public function pdpic_exists_with_id($pdpicid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        return parent::exists_with_id($pdpicid);
    }

    /************************************************* ON_CREATE SCOPE ******************************************************/
    
    public function on_create ($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On va (aussi) vérifier que les données attendues par IMAGE et ART_IMAGE sont présentes. Cela évite d'attendre que IMAGE gueule parce qu'il manque des choses.
        $needed = array_merge($this->needeed, ["pdpic_fn","pdpic_string","srvid","srvname","oeid"]);
        $com = array_intersect($args, $needed);
                
        if (    
            ( !empty($args) && is_array($args) ) &&
            ( count($needed) != count($com) ) &&
            ( key_exists("pdpic_art_eid", $args) && !is_array($args["pdpic_art_eid"]) && !empty($args["pdpic_art_eid"]) ) &&
            ( key_exists("pdpic_artid", $args) && !is_array($args["pdpic_artid"]) && !empty($args["pdpic_artid"]) ) &&
            ( key_exists("pdpic_ueid", $args) && !is_array($args["pdpic_ueid"]) && !empty($args["pdpic_ueid"]) )
        ) 
        {
            //["pdpic_artid","pdpic_art_id","picid"]
            $this->pdpic_artid = $args["pdpic_artid"];
            $this->pdpic_art_eid = $args["pdpic_art_eid"];
            
            //TODO : Vérifier si CALLER a transmis un chemin pour realpath
                //Sinon, on prend celui par défaut défini dans la classe
            
            //Créer chemin
            
            $this->entity_path = ( intval($args["srvid"]) === 1 ) ? WOS_SYSDIR_PROD_ARTIMAGE_SRV1 : WOS_SYSDIR_PROD_ARTIMAGE_SRV4;
            $args["pdpic_path"] = $this->entity_path.$args["pdpic_ueid"];
            $args["is_default"] = 0; //La valeur is_default est donnée par ARTIMAGE. IMAGE on a besoin. Or, ARTIMG correspond à ARTICLE. La création d'un ARTICLE ne peut pas porter sur l'ajout d'une Image par défaut.
            
//            var_dump(__LINE__,__FILE__,$args,defined("WOS_MAIN_SUBDOMAIN_ROOTPA"),WOS_MAIN_SUBDOMAIN_ROOTPA);
//            exit();
            /*
             * On ne sauvegarde pas "pdpic_ueid" car il ne nous sert à rien à part à créer le chemin.
             */
            
            /*
             * Il est normalement IMPOSSIBLE que $r ne soit pas défini.
             * Il contient le pdpic_eid de l'IMAGE ajoutée.
             */
            $infos =  parent::on_create_entity($args);
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, $ids,'v_d');
            if (! ( is_array($infos) && !empty($infos) ) && ( key_exists("picid", $infos) ) ) {
                return;
            }
            
            /* 
             * On va créer l'occurrence de l'image ARTICLE.
             * Créer un tableau ici est aussi une manière détournée de revérifier si les valeurs existent.
             * Du moins les clés !
             *  */
            
            //Le path qui va servir pour le rename de l'image
            $npath = $this->_ART_PATHS_TABLE[$args["srvid"]].$args["oeid"];
            $nargs = [
                "picid"             => $infos["picid"],
                "pdpic_artid"       => $args["pdpic_artid"],
                "pdpic_eid"         => $infos["pdpic_eid"],
                "pdpic_creadate"    => $infos["pdpic_creadate"],
                "pdpic_realpath"    => $infos["pdpic_realpath"],
                "pdpic_string_b64"  => $args["pdpic_string"],
                "srvid"             => intval($args["srvid"]),
                "srvname"           => $args["srvname"],
                "npath"             => $npath
            ];
            $this->write_new($nargs);
            
            //On met à jour realpath avant d'envoyer. La valeur a été mise à jour via write_new(); 
            
            $nargs["pdpic_realpath"] = $this->pdpic_realpath;
            
            return $nargs;
                
        } else {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXCPECTED => ",$needed],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args,'v_d']);
            $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__,TRUE);
        }
    }
    
    /************************************************* ON_DELETE SCOPE ******************************************************/
    
    public function on_delete ($artpicid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie que l'Image existe
        $pic_tab = $this->exists_with_id($artpicid);
        
        if (! $pic_tab ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,[
                "ART_PIC_ID" => $artpicid,
            ],'v_d');
            return "__ERR_VOL_ARTIMG_IMG_GONE";
        }
        
        //On supprime la représentation dans la table ArtPictures
        $QO = new QUERY("qryl4artpicn2");
        $qparams = array(":id" => $pic_tab["pdpicid"]);  
        $QO->execute($qparams);
        
        return parent::on_delete_entity($pic_tab["pdpicid"]);
    }
    
    
    /************************************************* ON_READ SCOPE ******************************************************/

    public function on_read ($args) {
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("pdpic_eid", $args) && !empty($args["pdpic_eid"]) ) ) 
        {
            if ( empty($this->pdpic_eid) )
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            else 
                $pdpic_eid = $this->pdpic_eid;
            
        } else $pdpic_eid = $args["pdpic_eid"];
        
        if ( !$this->exists($pdpic_eid) )
            return;
        
         return parent::on_read_entity($args);
    }
    
    private function write_new ($args) {
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->pdpic_art_eid, TQR_DEFAULT_SERVER_NAME, $args["pdpic_creadate"], $args["picid"]], 'v_d');
        //On crée le prod_fn
        
        $pdpic_prod_fn = $this->imgname_encode($this->pdpic_art_eid, $this->_ART_SRV_TABLE[$args["srvid"]], $args["pdpic_creadate"], $args["picid"]);
        
        /*
         * On set prod_fn en renvoyant le chemin complet vers l'ancien fichier afin de correctement modifier le nom du fichier
         */
        if (! $this->setpdpic_prod_fn($args["picid"], $pdpic_prod_fn, $args["pdpic_realpath"], $this->_ART_SRV_TABLE[$args["srvid"]], $args["npath"]) ) {
            return;
        }
        
        /*
         * Enregistrer l'image dans la table des IMAGE_ART
         */
        $QO = new QUERY("qryl4artpicn1");
        $params = array(":ap_picid" => $args["picid"], ":ap_artid" => $this->pdpic_artid);
        $datas = $QO->execute($params);
        
        /* On load l'Entity (Enfin) */
        $load = [ "pdpic_eid" => $args["pdpic_eid"] ]; 
        
        parent::load_entity($load);
        
        return TRUE;
    }
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SPECS SCOPE ***************************************************************************/
    
    public function valid ( $pdpic_string, &$pdpic_infos ) {
        
        parent::check_img_compliance($pdpic_string, $pdpic_infos);
        
    }
    
    /* A Faire dans les classes filles
        //TODO : A partir de l'identifiant on crée le prod_name
        $pdpic_prod_fn = $this->imgname_encode($this->art_oncreate_args["pdpic_ueid"], $server_name, $upload_timestamp, $picid);
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$pdpic_prod_fn,'v_d');
        //TODO : On insère dans la base de donnéees l'ieid et prod_name
        //...
        */
    
     
    
    
    /***************************************************************************************************************************************************************************/
    /*************************************************************************** GETTERS and SETTERS ***************************************************************************/
    
    public function getpdpic_artid() {
        return $this->pdpic_artid;
    }

    public function getpdpic_art_eid() {
        return $this->pdpic_art_eid;
    }
    
    public function setpdpic_art_eid($pdpic_art_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $pdpic_art_eid);
        
        $this->pdpic_art_eid = $pdpic_art_eid;
        
        //TODO : On inscrit dans la base de données
        
    }

}
