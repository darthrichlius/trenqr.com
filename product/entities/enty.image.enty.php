<?php

/**
 * Entity de gestion des images sur WOS.
 * Pour des soucis de contraines de temps et de moyen, la classe gère les opérations pour : ARTIMG, TR_COVER, PFL_PIC, ACC_COVER
 *
 * @author arsphinx
 */
class IMAGE extends PROD_ENTITY {
    //Utiliser par les classes filles pour définir leur path respectif
    protected $entity_path;
    
    protected $pdpic_infos;
    
    protected $is_default;
    protected $picid;
    protected $pdpic_eid;
    protected $pdpic_fn;
    protected $pdpic_prod_fn;
    protected $pdpic_string;
    protected $pdpic_height;
    protected $pdpic_width;
    protected $pdpic_size;
    protected $pdpic_type;
    protected $pdpic_creadate;
    /*
     * Le chemin à partir du dossier "UIMG_DEFAULT_GLOBALPATH" qui mène vers le futur repertoire de stockage du fichier.
     * Par exmple :
     *      Pour la sauvegarde des images d'ARTICLES
     *      => UIMG_DEFAULT_GLOBALPATH/articles/user/
     *      Pour la sauvegarde des images de TR_COVER
     *      => UIMG_DEFAULT_GLOBALPATH/tr_cover/user/
     * 
     * Ce chemin est foruni par la classe fille.
     */
    protected $pdpic_path;
    /*
     * Il s'agit l'adresse réel de l'image enregistrée sur le serveur.
     * Exemple : UIMG_DEFAULT_GLOBALPATH/articles/user/image_name.png
     */
    protected $pdpic_realpath;
    /*
     * TODO : Chemin vers la version non animée de l'image s'il s'agit d'un GIF.
     *       (TECHNIQUE) Obtenu grace à imagegif()
     */
    protected $pdpic_path_to_unanimated;
    protected $pdpic_quality;
    
    protected $server_id;
    /*
     * Le nom du serveur sur lequel est stockée l'image.
     * A vbeta1 ca sera surement : Kang1 et Kodos1
     */
    protected $server_name;
    /*
     * La famille à laquelle le serveur appartient.
     * A vbeta1 ca sera surement : Kang et Kodos
     */
    protected $server_name_family;
    
    /*** RULES ***/
    private $_DEFAULT_SRVID;
    protected $_MAX_HEIGHT;
    protected $_MIN_HEIGHT;
    protected $_MAX_WIDTH;
    protected $_MIN_WIDTH;
    protected $_ALLOWED_EXT;
    protected $_MAX_SIZE;
    protected $_DIMS_FORMAT; //WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT
    
    
    function __construct($_CHILD_FILE, $_CHILD_CLASS, $_MAX_HEIGHT, $_MIN_HEIGHT, $_MAX_WIDTH, $_MIN_WIDTH, $_ALLOWED_EXT, $_MAX_SIZE, $_DIMS_FORMAT = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$_CHILD_FILE, $_CHILD_CLASS, $_MAX_HEIGHT, $_MIN_HEIGHT, $_MAX_WIDTH, $_MIN_WIDTH, $_ALLOWED_EXT, $_MAX_SIZE]);
        
        parent::__construct($_CHILD_FILE, $_CHILD_CLASS);
        
        $this->_MAX_HEIGHT = intval($_MAX_HEIGHT);
        $this->_MIN_HEIGHT = intval($_MIN_HEIGHT);
        $this->_MAX_WIDTH = intval($_MAX_WIDTH);
        $this->_MIN_WIDTH = intval($_MIN_WIDTH);
        //Si on n'a pas un tableau complet, on en crée un. Cependant, cela veut dire qu'on autorise tous les types d'images.
        $this->_ALLOWED_EXT = ( is_array($_ALLOWED_EXT) && count($_ALLOWED_EXT) ) ? $_ALLOWED_EXT : [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];
        $this->_MAX_SIZE = intval($_MAX_SIZE);
        //On vérifie s'il y a une restriction de forme
        $formats = [WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT];
        $this->_DIMS_FORMAT = ( isset($_DIMS_FORMAT) && is_int($_DIMS_FORMAT) && in_array($_DIMS_FORMAT, $formats) ) ? $_DIMS_FORMAT : WOS_IMG_ALLFORMAT;
        
        $this->is_default = FALSE;
        
        //Marge1
        $this->_DEFAULT_SRVID = 2;
        
        $this->prop_keys = ["picid","pdpic_eid","pdpic_fn","pdpic_prod_fn","pdpic_string","pdpic_height","pdpic_width","pdpic_size","pdpic_type","pdpic_creadate","pdpic_path","pdpic_realpath","pdpic_path_to_unanimated","pdpic_quality","server_id","server_name","server_name_family","is_default"];
        $this->needed_to_loading_prop_keys = ["picid","pdpic_eid","pdpic_fn","pdpic_prod_fn","pdpic_string","pdpic_height","pdpic_width","pdpic_size","pdpic_type","pdpic_creadate","pdpic_realpath","pdpic_quality","server_id","server_name","server_name_family","is_default"];
        $this->needed_to_create_prop_keys = ["pdpic_fn","pdpic_string","pdpic_path","srvid","srvname","is_default"];
    }

    
    protected function build_volatile($args) { }

    
    public function exists($arg) {
        $pdpic_eid = NULL;
        
        //TODO : Déclencher une exception personnalisée si on ne recoit pas la valeur ["pdpic_eid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->pdpic_eid) ) {
                return;
            } else{ 
                $pdpic_eid = $this->pdpic_eid;
            }
        } else {
            $pdpic_eid = $arg;
        }
        
        //Contacter la base de données et vérifier si l'Article existe.
        $QO = new QUERY("qryl4picn5");
        $params = array( ":pdpic_eid" => $pdpic_eid );
        $datas = $QO->execute($params);
        
        $r = ( $datas ) ? $datas[0] : FALSE;
        
        return $r;
    }
    
    public function exists_with_id($arg) {
        $pdpicid = NULL;
        
        //TODO : Déclencher une exception personnalisée si on ne recoit pas la valeur ["pdpic_eid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->pdpicid) ) {
                return;
            } else{ 
                $pdpicid = $this->pdpicid;
            }
        } else {
            $pdpicid = $arg;
        }
        
        //Contacter la base de données et vérifier si l'Article existe.
        $QO = new QUERY("qryl4picn6");
        $params = array( ":pdpicid" => $pdpicid );
        $datas = $QO->execute($params);
        
        $r = ( $datas ) ? $datas[0] : FALSE;
        return $r;
    }
    

    protected function init_properties($datas) {
        /*
         * [NOTE 25-11-14] @author L.C.
         * J'ai arreté avec check_isset_and_not_empty_entry_vars() car le bit n'est pas ici de 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $datas, TRUE);

        foreach($datas as $k => $v) {
            
            $$k = $v;
            if (! (!empty($this->prop_keys) && is_array($this->prop_keys) && count($this->prop_keys) ) ) {
                $this->signalError ("err_sys_l4comn4", __FUNCTION__, __LINE__);
            }
            /*
             * On vérifie que toutes les données obligatoires pour l'initialisation des propriétés de la classe sont déclarées.
             * NOTE : On ne vérifie que les clés.
             */
            
            if ( count($this->needed_to_loading_prop_keys) != count(array_intersect(array_keys($datas), $this->needed_to_loading_prop_keys)) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED => ", $this->needed_to_loading_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $datas],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE => ", array_diff($this->needed_to_loading_prop_keys,array_keys($datas))],'v_d');
                $this->signalError ("err_sys_l4comn5", __FUNCTION__, __LINE__,TRUE);
            } 
            /*  [18-08-14] On ne vérifie que les clés. Les valeurs peuvent etre NULLES ou égales à 0
            else 
            {
                /*
                 * Ces données doivent être NON NULLES. En effet, elles ne peuvent être NULL
                 *
                if ( in_array($k, $this->needed_to_loading_prop_keys) && empty($datas[$k]) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$datas[$k]],'v_d');
                    $this->signalError ("err_sys_l4comn8", __FUNCTION__, __LINE__,TRUE);
                }
            }
            //*/
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
    

    protected function load_entity($args, $std_err_enbaled = FALSE) {
//        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        /* On vérifie si on a l'identifiant. Sinon on tente de prendre celui déjà chargé s'il existe */
        $pdpic_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("pdpic_eid", $args) && !empty($args["pdpic_eid"]) ) ) 
        {
            if ( empty($this->pdpic_eid) )
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            else 
                $pdpic_eid = $this->pdpic_eid;
        } else $pdpic_eid = $args["pdpic_eid"];
        
        
        /* On controle si l'occurence existe */
        $exists = $this->exists($pdpic_eid);
        if ( !$exists && $std_err_enbaled ) 
        {
            $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
        }
        else if ( !$exists && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        //Intéroger la base de données 
        $image = $this->exists($args["pdpic_eid"]);
        
        if ( ( !$image ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$image ) && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        //On formatte le tableau à sortir
        $datas = [
            "is_default"            => $image["pdpic_is_default"],
            "picid"                 => $image["pdpicid"],
            "pdpic_eid"             => $image["pdpic_eid"],
            "pdpic_fn"              => $image["pdpic_fn"],
            'pdpic_prod_fn'         => $this->getpdpic_prod_fn(),
            "pdpic_string"          => $image["pdpic_string_b64"],
            "pdpic_height"          => $image["pdpic_height"],
            "pdpic_width"           => $image["pdpic_width"],
            "pdpic_size"            => $image["pdpic_size"],
            "pdpic_type"            => $image["pdpic_type"], //Check MIME : image/gif, image/jpg, image/png
            "pdpic_creadate"        => $image["pdpic_creadate"],
            "pdpic_realpath"        => $image["pdpic_realpath"],
            "pdpic_quality"         => $image["pdpic_quality"],
            'server_id'             => $image["server_id"],
            'server_name'           => $image["server_name"],
            'server_name_family'    => $image["server_family"]
        ];
        
        
        if ( !count($datas) ) 
        { 
            if ( $std_err_enbaled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } 
        else 
        {
            $this->init_properties($datas);
            $this->is_instance_load = TRUE;
            return 1;
        }
    }

    protected function on_alter_entity($args) {
        
    }
    
    /************************************ ON_CREATE *****************************************/
    
    protected function on_create_entity($args) {
        //["pdpic_fn","pdpic_string","pdpic_path","srvid","srvname","is_default"]
        //["pdpic_fn","pdpic_string","pdpic_height","pdpic_width","pdpic_size","pdpic_type","pdpic_creadate","pdpic_path","srvname"]
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie que les données obligatoires sont présentes et définies
        if ( isset($args) && is_array($args) && count($args) ) {
            /*
             * On s'occupe de la valeur de is_default.
             * On vérifie si elle est définie.
             * 
             * Dans tous les cas on converti dans une valeur valide
             */
            $args["is_default"] = ( isset($args["is_default"]) && filter_var($args["is_default"], FILTER_VALIDATE_BOOLEAN)  ) ? 1 : 0;
                   
            $com = array_intersect(array_keys($args), $this->needed_to_create_prop_keys);
            if ( count($com) != count($this->needed_to_create_prop_keys) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
                $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__,TRUE);
            }
            //On vérifie si les valeurs sont définies et non vides
            foreach ( $args as $k => $v ) {
                if (! ( isset($v) && !is_array($v) ) ) {
                    if ( ( is_bool($v) && ( $v === TRUE || $v === FALSE ) ) || ( is_int($v) && $v === 0 ) )
                        continue;
                    else {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$args[$k]],'v_d');
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__,TRUE);
                    }
                }
            }
        }
         
        //On sauvegarde les données fournies en entrées
        $this->art_oncreate_args = $args;
         
        $pdpic_infos = NULL;
        //On vérifie que les règles sur les proriétés des images sont respectées
        $this->check_img_compliance ($args["pdpic_string"], $pdpic_infos);
         
        //RAPPEL : On ne transforme pas les données. S'il faut crypter des données on c'est aux classes filles de le faire
        
        //On enregistre physiquement l'image
        $IMGSRVC = new SRVC_IMAGE_HANDLER(); 
        $r = $IMGSRVC->WriteImage($pdpic_infos, $args["srvname"], $args["pdpic_fn"], $args["pdpic_path"]);
//          var_dump(__LINE__,__FUNCTION__,$r);
//            exit();
         
        if ( !isset($r) ) {
            $this->signalError ("err_user_l4ain3", __FUNCTION__, __LINE__,TRUE);
        } else if ( isset($r) && $r == "_ERR_VOL_EXT_NOCOMPLY"  ) {
            $this->signalError ("err_user_l4ain2", __FUNCTION__, __LINE__,TRUE);
        }
         
        $r["pdpic_fn"] = $args["pdpic_fn"];
        $r["srvid"] = $args["srvid"];
        $r["is_default"] = $args["is_default"];
         
        //On enregistre les données sur l'image dans la base de données et on load
        $infos = $this->write_new_in_database($r);
         
        //On ajoute le chemin vers le fichier. Cela permet de changer le nom du fichier après avoir déterminé le "pdpic_prod_fn"
        $infos[ "pdpic_realpath"] = $r["realpath"];
        
        return $infos;
    }
    
    public function check_img_compliance ($pdpic_string, &$pdpic_infos) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$pdpic_string]);
        
        $IMGSRVC = new SRVC_IMAGE_HANDLER(); 
        $infos = $IMGSRVC->GetInfosFromBase64ImgString($pdpic_string);
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $infos,'v_d');
        
        switch ($infos) {
            case "__ERR_VOL_NOT_IMAGE":
                    $this->signalError ("err_user_l4ain1", __FUNCTION__, __LINE__, TRUE);
                break;
            case "__ERR_VOL_SIZE_NOTDEFINED":
                    $this->signalError ("err_sys_l4ain4", __FUNCTION__, __LINE__, TRUE);
                break;
            default:
                break;
        }

        //On vérifie qu'il s'agit bien d'une image
        if (! ( $infos && is_array($infos) && count($infos) && key_exists("nature", $infos) && !empty($infos["nature"]) && $infos["nature"] == "image") ) {
            $this->signalError ("err_user_l4ain1", __FUNCTION__, __LINE__, TRUE);
        }
        
        $pdpic_infos = $infos;
        /*
            "nature" => $m[1],
            "body" => $m[3],
            "type" => $m[3],
            "PHP_GD_TYPE" => $type, //IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG
            "size" => $size,
            "width" => $width,
            "height" => $height,
            "attr" => $attr
        //*/
        
        /* On vérifie que l'image respecte les conditions d'insertion */
        
        //On vérifie que l'image respecte les conditions de format d'image si elle a été définie
        //WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT
        if ( $this->_DIMS_FORMAT != WOS_IMG_ALLFORMAT ) {
            $w = intval($infos["width"]);
            $h = intval($infos["height"]);
            
            /*
             * [NOTE 14-08-14 22:22]
             * Je sais bien qu'il n'y que deux formes possibles. Merci ! :)
             */
            switch ( $this->_DIMS_FORMAT ) {
                case WOS_IMG_IS_SQUARE :
                        if ( $h !== $w ) {
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["SHOULD BE => WOS_IMG_IS_SQUARE"],'v_d');
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ",$w,$h],'v_d');
                            $this->signalError ("err_user_l4ain2", __FUNCTION__, __LINE__,TRUE);
                        }
                    break;
                case WOS_IMG_IS_RECT :
                        if ( $h === $w ) {
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["SHOULD BE => WOS_IMG_IS_RECT"],'v_d');
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ",$w,$h],'v_d');
                            $this->signalError ("err_user_l4ain2", __FUNCTION__, __LINE__,TRUE);
                        }
                    break;
                default:
                    //Tant pis !
                    break;
            }
        }
        
        //On vérifie que l'image est du bon type 
        if (! in_array($infos["PHP_IMAGETYPE"], $this->_ALLOWED_EXT) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->_ALLOWED_EXT,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$infos,'v_d');
            $this->signalError ("err_user_l4ain2", __FUNCTION__, __LINE__,TRUE);
        }
        
        //On vérifie le poids
        if ( intval($infos["size"]) > $this->_MAX_SIZE ) {
            $this->signalError ("err_user_l4ain2", __FUNCTION__, __LINE__,TRUE);
        }
        
        //On vérifie les dimensions
        if (! 
            ( ( intval($infos["width"]) >= $this->_MIN_WIDTH && intval($infos["width"]) <= $this->_MAX_WIDTH )
            && ( intval($infos["height"]) >= $this->_MIN_HEIGHT && intval($infos["height"]) <= $this->_MAX_HEIGHT ) )
            ) 
        {
            $this->signalError ("err_user_l4ain2", __FUNCTION__, __LINE__,TRUE);
        }
        
        $this->pdpic_infos = $infos;
        
        return TRUE;
        
    }
    
    
    /************************************ ON_DELETE *****************************************/
    

    public function on_delete_entity($pdpicid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie si l'IMAGE existe
        $pic_tab = $this->exists_with_id($pdpicid);
        if (! $pic_tab ) {
            return "__ERR_VOL_IMG_GONE";
        }
        
        // *** SUPPRESSION PHYSIQUE DU PATH *** //
        //On vérifie si l'image existe physiquement
        /*
         * [NOTE 09-09-14] @author 
         * La probabilité qu'une image n'existe pas physiquement est presque NULLE dans le cas d'une base de données PROD.
         * En effet, de mauvaises données peuvent être insérées dans la base de données lors des phases de test.
         * Mais la probabilité que cela arrive sur les serveurs de PROD n'est pas vraiment posssible. 
         * (EVOLUTION)
         * Si cela devait arriver, un script se chargera de les retrouver.
         * 
         */
//        $srvnm = $pic_tab["server_name"];
        
        /*
         * [DEPUIS 04-07-16]
         */
//        $srvnm = ( ( defined("WOS_MAIN_HOST") && WOS_MAIN_HOST === "localhost" ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $pic_tab["server_name"];
        $srvnm = ( ( defined("WOS_MAIN_HOST") && in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $pic_tab["server_name"];
        
        $FTH = new FTP_HANDLER($srvnm);
        if ( $FTH->ftp_file_exists($pic_tab["pdpic_full_ftppath"]) ) {
         
            if (! $FTH->ftp_delete_file($pic_tab["pdpic_full_ftppath"]) ) {
                return "__ERR_VOL_DELIMG_FAILED";
            }
            
        } //Sinon ... on passe à la suppression de l'Image dans la base de données
        
        //On supprime dans la base de données
        $QO = new QUERY("qryl4picn7");
        $qparams = array(":id" => $pic_tab["pdpicid"]);  
        $QO->execute($qparams);
        
        return TRUE;
        
    }
    
    /************************************ ON_READ *****************************************/

    public function on_read_entity($args) {
        $pdpic_eid = NULL;
        
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("pdpic_eid", $args) && !empty($args["pdpic_eid"]) ) ) 
        {
            if ( empty($this->pdpic_eid) )
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            else 
                $pdpic_eid = $this->pdpic_eid;
        } else $pdpic_eid = $args["pdpic_eid"];
        
        //On vérifie si l'IMAGE existe
        $r = $this->exists($pdpic_eid);
        
        if (! $r )
            return $r;
        
        //On load l'ENTITY
        $this->load_entity($args);
        
        return TRUE;
    }

    protected function write_new_in_database($args) {
        $picid = $time = $pdpic_eid = $server_id =  NULL;
         
        /*
         * On vérifie si l'identifiant de server_id est correste. Sinon on enregistre sur un serveur par défaut plutot que déclencher une erreur.
         * On change donc la valeur "srvid" dans $args
         */
        $QO = new QUERY("qryl4srvn1");
        $params = array(":srvid" => $args["srvid"]);
        $datas = $QO->execute($params);
 
        $server_id = ( ! $datas ) ?  $this->_DEFAULT_SRVID : $args["srvid"];
        
        //On crée la variable time (en microsecondes)
        $time = round(microtime(TRUE)*1000);
       
        /* On enregristre l'image dans la base de données et on récupère l'identifiant */
        $QO = new QUERY("qryl4picn1");
        $params = array(
            ":pdpic_is_default"     => (string)$args["is_default"], 
            ":pdpic_fn"             => $args["pdpic_fn"], 
//            ":pdpic_string_b64"     => "TEXTE_TEST", 
            ":pdpic_string_b64"     => $args["body_b64"], 
            ":pdpic_height"         => $args["height"],
            ":pdpic_width"          => $args["width"],
            ":pdpic_size"           => $args["size"],
            ":pdpic_type"           => $args["type"],
            ":pdpic_realpath"       => $args["realpath"],
            ":pdpic_srvid"          => $server_id,
            ":pdpic_date_tstamp"    => (string)$time
        );  
        $datas = $QO->execute($params);
     
        $picid = $datas;
        
        //A partir de l'identifiant on crée l'ieid
        $pdpic_eid = $this->entity_ieid_encode($time,$picid);
     
        //On update et on insère pdpic_eid
        $QO = new QUERY("qryl4picn2");
        $params = array(":picid" => $picid, ":pdpic_eid" => $pdpic_eid);
        $QO->execute($params);
     
        //ATTENTION : ON NE LOAD PAS L'ENTITY PARCE QUE CA VA ENTRAINER UNE ERREUR
        /*
         * Pour load l'IMAGE il nous faut "pdpic_prod_fn". Hors à la création de l'IMAGE elle n'existe pas.
         * Cette propriété dépend de la classe fille qui a sa propre fonction de codage.
         * Aussi, c'est la responsabilité du Developpeur que de load IMAGE APRES AVOIR SET "pdpic_prod_fn".
         */
//        $this->load_entity($args);
        
        $rt = [
            "picid"             => $picid,
            "pdpic_eid"         => $pdpic_eid,
            "pdpic_creadate"    => $time,
            "pdpic_string_b64"  => $args["body_b64"]
        ];
        
        //On retourne l'ieid à qui veut bien le prendre
        return $rt;
    }
    
    /***********************************************************************************************/
    /*************************************** SPECS *************************************************/
        
    /**
     * Creates a name for the image, containing all the following information.
     * Output pattern: <ueid> . <machine_name> . <ieid> [. <width> . <height> .<quality>]
     * @author Pierre LALLEMENT
     * @param string $ueid User External ID
     * @param string $machine_name Name of the server
     * @param int $upload_timestamp Timestamp of the upload date of the image
     * @param int $picid Picture ID
     * @return string Image fullname
     */
     public function imgname_encode($ueid, $machine_name, $upload_timestamp, $picid, $width = NULL, $height = NULL, $quality = NULL){
            //Génération d'un 'IEID' (Image External ID) basé sur la date d'upload (timestamp), qu'il faut repasser en secondes (et pas millisecondes comme en base)
            //et l'ID de l'image (auto increment)
            //Pour la conversion du timestamp, on va simplement utiliser la base 23.
            //Ensuite, on concatène l'ID réel avec la même stratégie de séparation avec le caractère 'n' dans le cryptage de UEID
            //Mais cette fois-ci avec un autre caractère (pour ne pas confondre): 'o'.
            //On transforme aussi le picid en base 23.
            //[Pierre | 14/08/14] La création de l'ieid suis toujours cette logique, mais a été extrait d'ici pour en faire une fonction à part.
            $ieid = $this->entity_ieid_encode($upload_timestamp, $picid);
            
            //On va aussi coder le nom du serveur
            $secret_machine = $this->serverName_encode($machine_name);
            
            //Et on crée notre nom d'image
            //Si $message est set, on l'ajoute à la fin
            if($width && $height && $quality){
                $imgname = $ueid . '_' . $secret_machine . '_' . $ieid . '_' . $width . 'x' . $height . '_' . $quality;
                return $imgname;
            } else if($width && $height){
                $imgname = $ueid . '_' . $secret_machine . '_' . $ieid . '_' . $width . 'x' . $height;
                return $imgname;
            } else {
                $imgname = $ueid . '_' . $secret_machine . '_' . $ieid;
                return $imgname;
            }
    }
    
    /**
    * Reverse function of imgname_encode. Will output an associative array of the 4 parts
    * of the image name (ueid, machine, uploadtstamp and picid)
    * @param string $imgname Fullname of the image (minus extension)
    * @return array
    */
   public function imgname_decode($imgname){
       //On va décoder les informations contenues dans le nom de l'image à partir de celui-ci.
       //On commence par explode le string pour en récupérer les 3 parties
       $explodedName = explode('_', $imgname);

       $ueid = $explodedName[0];
       $raw_machine_name = $explodedName[1];
       $ieid = $explodedName[2];

       //On va traiter $ieid pour récupérer le timestamp d'uplaod et l'ID de l'image
       //On traite ensuite le tstamp pour le remettre en base 10
       //[Pierre | 14/08/14] La lecture de l'ieid se fait toujours selon cette logique, mais a été extrait pour en faire une fonction à part.
       $decIeid = $this->entity_ieid_decode($ieid);
       $b10picid = $decIeid['id'];
       $b10tstamp = $decIeid['time'];
       

       //On va décoder le nom de la machine
       $machine_name = $this->serverName_decode($raw_machine_name);

       //Si on avait un $message set lors de l'encodage, il faut aussi le retrouver ici
       if(isset($explodedName[3]) && isset($explodedName[4])){
           //Ça veut dire qu'on a un message de set, avec 2 paramètres dedans.
           //On sait que dans le cas de 2 paramètres dans le message, ce sera toujours dans le même ordre
           $resize = $explodedName[3];
           $quality = $explodedName[4];

           $ra = [
               'ueid'           => $ueid,
               'machine'        => $machine_name,
               'uploadtstamp'   => $b10tstamp,
               'picid'          => $b10picid,
               'resize'         => $resize,
               'quality'        => $quality
           ];
       } else if(isset($explodedName[3]) && !isset($explodedName[4])){
           //Ici on a un message avec un seul paramètre, qui sera forcément resize
           $resize = $explodedName[3];
           $ra = [
               'ueid'           => $ueid,
               'machine'        => $machine_name,
               'uploadtstamp'   => $b10tstamp,
               'picid'          => $b10picid,
               'resize'         => $resize
           ];
       } else {
           //Pas de message
           $ra = [
               'ueid'           => $ueid,
               'machine'        => $machine_name,
               'uploadtstamp'   => $b10tstamp,
               'picid'          => $b10picid
           ];
       }
       return $ra;
   }
    
    
    /************************************************************************************************/
    /*********************************** GETTERS and SETTERS ****************************************/
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getpdpic_infos() {
        return $this->pdpic_infos;
    }
    
    public function getIs_default() {
        return $this->is_default;
    }

    public function setIs_default($is_default) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $this->is_default = $is_default;
        
        //TODO : Enregistrer la valeur dans la base de données
        /*
         * ...
         */
        
        return TRUE;
    }

    public function getPicid() {
        return $this->picid;
    }

    public function getpdpic_eid() {
        return $this->pdpic_eid;
    }

    public function getpdpic_fn() {
        return $this->pdpic_fn;
    }

    public function getpdpic_prod_fn() {
        return $this->pdpic_prod_fn;
    }
    
    public function setpdpic_prod_fn( $picid,  $pdpic_prod_fn, $pdpic_fn_realpath, $srvname, $n_ftp_path ) {
        //$pdpic_fn permet d'aller chercher l'ancien fichier et vérifier s'il existe afin de changer de nom
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $this->pdpic_prod_fn = $pdpic_prod_fn; //Au cas où l'Entity est déjà loaded
        
        /* Modifier le nom du fichier sur le disque s'il existe */
        /*
         * [NOTE 09-09-14] @author L.C.
         * Les fichiers sont stockés sur des serveurs distants.
         * Aussi, on a des chemins sous forme d'URL.
         * Cela entraine l'utilisation de la toute fraiche fonction url_exists() de MOTHER
         */
//        if ( file_exists($pdpic_fn_realpath) ) {
        if ( $this->wos_url_exists($pdpic_fn_realpath) ) {
            list( $dirname, $basename, $extension, $filename ) = array_values( pathinfo($pdpic_fn_realpath) );
            $ofn = $filename.'.'.$extension;
            $nfn = $pdpic_prod_fn.'.'.$extension;
            
            $new_file_realpath = $dirname."/".$nfn;
            
            //Changement au niveau du serveur FTP
            $FPH = new FTP_HANDLER($srvname);
            $o_ftp_path = $n_ftp_path."/".$ofn;
////            var_dump($o_ftp_path, $nfn);
//            exit();
            $r = $FPH->ftp_rename_file($o_ftp_path, $nfn);
            
//            $r = rename($pdpic_fn_realpath, $new_file_realpath); //OBSELETE
            
            if ( !$r ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $new_file_realpath,'v_d');
                $this->signalError ("err_sys_l4ain8", __FUNCTION__, __LINE__,TRUE);
            }
        } else {
            /*
             * Cela veut :
             *      (1) Le fichier n'a jamais existé
             *      (2) Le nom du fichier a déjà été changé
             */
            //On vérifie si le fichier n'a pas déjà été renommé
//            if ( file_exists($pdpic_prod_fn) )                
//                return TRUE;
//            else {
                //Retourner NULL pour que le CALLER soit averti
                return;
//            }
            
        }
        
        //Enregistrer la valeur dans la base de données
        $n_ftp_fpath = $n_ftp_path."/".$nfn;
        $QO = new QUERY("qryl4picn3");
        $params = array(
            ":picid"            => $picid, 
            ":pdpic_prod_fn"    => $nfn, 
            ":pdpic_realpath"   => $new_file_realpath, 
            ":n_ftp_fpath"      => $n_ftp_fpath
        );
        $QO->execute($params);
        
        /*
         * [DEPUIS 25-06-15] @BOR
         * On renvoie des données qui pourront servir à d'autres processus.
         */
        $r = [
            "pctid"         => $picid,
            "pctprod_fn"    => $nfn,
            "pct_realpath"  => $new_file_realpath,
            "pct_ftp_fpath" => $n_ftp_fpath,
        ];
        
        return $r;
//        return TRUE;
    }
    
    public function getpdpic_string() {
        return $this->pdpic_string;
    }

    public function getpdpic_height() {
        return $this->pdpic_height;
    }

    public function getpdpic_width() {
        return $this->pdpic_width;
    }

    public function getpdpic_size() {
        return $this->pdpic_size;
    }

    public function getpdpic_type() {
        return $this->pdpic_type;
    }

    public function getpdpic_creadate() {
        return $this->pdpic_creadate;
    }

    public function getpdpic_path() {
        return $this->pdpic_path;
    }

    public function getpdpic_realpath() {
        return $this->pdpic_realpath;
    }

    public function getpdpic_quality() {
        return $this->pdpic_quality;
    }

    public function get_MAX_HEIGHT() {
        return $this->_MAX_HEIGHT;
    }

    public function get_MIN_HEIGHT() {
        return $this->_MIN_HEIGHT;
    }

    public function get_MAX_WIDTH() {
        return $this->_MAX_WIDTH;
    }

    public function get_MIN_WIDTH() {
        return $this->_MIN_WIDTH;
    }

    public function get_ALLOWED_EXT() {
        return $this->_ALLOWED_EXT;
    }

    public function get_MAX_SIZE() {
        return $this->_MAX_SIZE;
    }

    public function get_DIMS_FORMAT() {
        return $this->_DIMS_FORMAT;
    }
    
    public function get_DEFAULT_SRVID() {
        return $this->_DEFAULT_SRVID;
    }
    
    /* --------------- SERVERS -------------------- */

    public function getServer_id() {
        return $this->server_id;
    }

    public function getServer_name() {
        return $this->server_name;
    }

    public function getServer_name_family() {
        return $this->server_name_family;
    }
    
    /*--------------- CHILD ENTITY ------------------*/
    public  function getEntity_path() {
        return $this->entity_path;
    }

    public  function setEntity_path($entity_path) {
        $this->entity_path = $entity_path;
    }

// </editor-fold>




}
