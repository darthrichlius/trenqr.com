<?php

class VIDEO extends PROD_ENTITY {
    //Utiliser par les classes filles pour définir leur path respectif
    protected $entity_path;
    
    protected $vid_infos;
    
    protected $vidid;
    protected $vid_eid;
    protected $vid_fn;
    protected $vid_name;
    protected $vid_string;
    protected $vid_height;
    protected $vid_width;
    protected $vid_size;
    protected $vid_type;
    protected $vid_creadate;
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
    protected $vid_path;
    /*
     * Il s'agit l'adresse réel de l'image enregistrée sur le serveur.
     * Exemple : UIMG_DEFAULT_GLOBALPATH/articles/user/image_name.png
     */
    protected $vid_realpath;
        
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
        $this->_ALLOWED_EXT = ( is_array($_ALLOWED_EXT) && count($_ALLOWED_EXT) ) ? $_ALLOWED_EXT : ["mp4"];
        $this->_MAX_SIZE = intval($_MAX_SIZE);
        //On vérifie s'il y a une restriction de forme
        $formats = [WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT];
        $this->_DIMS_FORMAT = ( isset($_DIMS_FORMAT) && is_int($_DIMS_FORMAT) && in_array($_DIMS_FORMAT, $formats) ) ? $_DIMS_FORMAT : WOS_IMG_ALLFORMAT;
        
        //Marge1
        $this->_DEFAULT_SRVID = 2;
        
        $this->prop_keys = ["vidid","vid_eid","vid_fn","vid_prod_fn","vid_string","vid_height","vid_width","vid_size","vid_type","vid_creadate","vid_path","vid_realpath","vid_path_to_unanimated","server_id","server_name","server_name_family"];
        $this->needed_to_loading_prop_keys = ["vidid","vid_eid","vid_fn","vid_prod_fn","vid_string","vid_height","vid_width","vid_size","vid_type","vid_creadate","vid_realpath","server_id","server_name","server_name_family"];
        $this->needed_to_create_prop_keys = ["vid_fn","vid_string","vid_path","srvid","srvname"];
    }

    protected function build_volatile($args) { }

    
    public function exists($arg) {
        $vid_eid = NULL;
        
        //TODO : Déclencher une exception personnalisée si on ne recoit pas la valeur ["vid_eid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->vid_eid) ) {
                return;
            } else{ 
                $vid_eid = $this->vid_eid;
            }
        } else {
            $vid_eid = $arg;
        }
        
        //Contacter la base de données et vérifier si l'Article existe.
        $QO = new QUERY("qryl4tvidn5");
        $params = array( ':vid_eid' => $vid_eid );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }
    
    public function exists_with_id($arg) {
        $vidid = NULL;
        
        //TODO : Déclencher une exception personnalisée si on ne recoit pas la valeur ["vid_eid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->vidid) ) {
                return;
            } else{ 
                $vidid = $this->vidid;
            }
        } else {
            $vidid = $arg;
        }
        
        //Contacter la base de données et vérifier si l'Article existe.
        $QO = new QUERY("qryl4tvidn6");
        $params = array( ':vidid' => $vidid );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
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
        $vid_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("vid_eid", $args) && !empty($args["vid_eid"]) ) ) 
        {
            if ( empty($this->vid_eid) )
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            else 
                $vid_eid = $this->vid_eid;
        } else $vid_eid = $args["vid_eid"];
        
        
        /* On controle si l'occurence existe */
        $exists = $this->exists($vid_eid);
        if ( !$exists && $std_err_enbaled ) 
        {
            $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
        }
        else if ( !$exists && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        //Intéroger la base de données 
        $video = $this->exists($args["vid_eid"]);
        
        if ( ( !$video ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$video ) && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        //On formatte le tableau à sortir
        $datas = [
            "vidid"               => $video["vidid"],
            "vid_eid"             => $video["vid_eid"],
            "vid_fn"              => $video["vid_fn"],
            'vid_prod_fn'         => $this->getvid_prod_fn(),
           /*
            * [DEPUIS 22-05-16]
            *      Provoquait des problèmes au niveau de la mémoire et de temps limite d'execution.
            *      Cette donnée n'est pas obligatoire. Elle pourra être de nouveau réassignée dans le futur. 
            */
//            "vid_string"          => $video["vid_string_b64"],  
            "vid_string"          => NULL,
            "vid_height"          => $video["vid_height"],
            "vid_width"           => $video["vid_width"],
            "vid_size"            => $video["vid_size"],
            "vid_type"            => $video["vid_type"], //Check MIME : image/gif, image/jpg, image/png
            "vid_creadate"        => $video["vid_creadate"],
            "vid_realpath"        => $video["vid_realpath"],
            'server_id'           => $video["server_id"],
            'server_name'         => $video["server_name"],
            'server_name_family'  => $video["server_family"]
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

    
    protected function on_alter_entity($args) { }
    
    
    /***************************************** ON_CREATE *****************************************/
    
    protected function on_create_entity($args) {
        //["vid_fn","vid_string","vid_path","srvid","srvname"]
        //["vid_fn","vid_string","vid_height","vid_width","vid_size","vid_type","vid_creadate","vid_path","srvname"]
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie que les données obligatoires sont présentes et définies
        if ( isset($args) && is_array($args) && count($args) ) {
                   
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
         
        $vid_infos = NULL;
        //On vérifie que les règles sur les proriétés des images sont respectées
        $this->check_vid_compliance($args["vid_string"], $vid_infos);
         
        //RAPPEL : On ne transforme pas les données. S'il faut crypter des données on c'est aux classes filles de le faire
        
        //On enregistre physiquement l'image
        $VIDSRVC = new SRVC_VIDEO_HANDLER(); 
         
        $r = $VIDSRVC->WriteImage($vid_infos, $args["srvname"], $args["vid_fn"], $args["vid_path"]);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$r);
//        exit();
        
        if ( !isset($r) ) {
            $this->signalError ("err_user_l4ain3", __FUNCTION__, __LINE__,TRUE);
        } else if ( isset($r) && $r == "_ERR_VOL_EXT_NOCOMPLY" ) {
            $this->signalError ("err_user_l4ain2", __FUNCTION__, __LINE__,TRUE);
        }

        $r["vid_fn"] = $args["vid_fn"];
        $r["srvid"] = $args["srvid"];
         
        //On enregistre les données sur l'image dans la base de données et on load
        $infos = $this->write_new_in_database($r);
         
        //On ajoute le chemin vers le fichier. Cela permet de changer le nom du fichier après avoir déterminé le "vid_prod_fn"
        $infos[ "vid_realpath"] = $r["realpath"];
        
        return $infos;
    }
    
    public function check_vid_compliance ($vid_string, &$vid_infos) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$vid_string]);
        
        $VIDSRVC = new SRVC_VIDEO_HANDLER(); 
        $infos = $VIDSRVC->GetInfosFromBase64VidString($vid_string);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $infos) ) {
            return $infos;
        }

        $vid_infos = $infos;
        /*
         * DICTIONNAIRE DES CLES
         * 'body'
         * 'body_b64'
         * 'type'
         * 'size'
         * 'width'
         * 'height'
         * IMAGE FRAME  
         *      'nature'
         *      'body' 
         *      'body_b64'
         *      'type'
         *      'PHP_IMAGETYPE'
         *      'size'
         *      'width'
         *      'height'
         *      'attr'
         *      'format' => int 99
         *      'file.basename'
         *      'file.filename'
         *      'file.data'
        */
        
        
        //On vérifie que l'image est du bon type 
        if (! in_array($infos["type"], $this->_ALLOWED_EXT) ) {
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
        
        $this->vid_infos = $infos;
        
        return TRUE;
        
    }
    
    
    /***************************************** ON_DELETE *****************************************/
    

    public function on_delete_entity($vidid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie si l'IMAGE existe
        $vid_tab = $this->exists_with_id($vidid);
        if (! $vid_tab ) {
            return "__ERR_VOL_IMG_GONE";
        }
        
        // *** SUPPRESSION PHYSIQUE DU PATH *** //
        //On vérifie si l'image existe physiquement
        /*
         * [NOTE 09-09-14] @author 
         *      La probabilité qu'une image n'existe pas physiquement est presque NULLE dans le cas d'une base de données PROD.
         *      En effet, de mauvaises données peuvent être insérées dans la base de données lors des phases de test.
         *      Mais la probabilité que cela arrive sur les serveurs de PROD n'est pas vraiment posssible. 
         *      (EVOLUTION)
         *      Si cela devait arriver, un script se chargera de les retrouver.
         */
//        $srvnm = $vid_tab["server_name"];
        /*
         * [DEPUIS 24-01-16]
         */
//        $srvnm = ( ( defined("WOS_MAIN_HOST") && WOS_MAIN_HOST === "localhost" ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $vid_tab["server_name"];
        $srvnm = ( ( defined("WOS_MAIN_HOST") && in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $vid_tab["server_name"];
        
        $FTH = new FTP_HANDLER($srvnm);
        if (! $FTH->ftp_file_exists($vid_tab["vid_full_ftppath"]) ) {
            return "__ERR_VOL_DELIMG_MSG";
        } else if (! $FTH->ftp_delete_file($vid_tab["vid_full_ftppath"]) ) {
            return "__ERR_VOL_DELIMG_FAILED";
        }
        
        /*
         * ETAPE :
         *      On supprime dans la base de données
         */
        $QO = new QUERY("qryl4tvidn7");
        $qparams = array(":id" => $vid_tab["vidid"]);  
        $QO->execute($qparams);
        
        return TRUE;
        
    }
    
    /***************************************** ON_READ *****************************************/

    public function on_read_entity($args) {
        $vid_eid = NULL;
        
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("vid_eid", $args) && !empty($args["vid_eid"]) ) ) 
        {
            if ( empty($this->vid_eid) )
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            else 
                $vid_eid = $this->vid_eid;
        } else $vid_eid = $args["vid_eid"];
        
        //On vérifie si l'IMAGE existe
        $r = $this->exists($vid_eid);
        
        if (! $r )
            return $r;
        
        //On load l'ENTITY
        $this->load_entity($args);
        
        return TRUE;
    }

    protected function write_new_in_database($args) {
        $vidid = $time = $vid_eid = $server_id =  NULL;
         
        /*
         * On vérifie si l'identifiant de server_id est correste. Sinon on enregistre sur un serveur par défaut plutot que déclencher une erreur.
         * On change donc la valeur "srvid" dans $args
         */
        $QO = new QUERY("qryl4srvn1");
        $params = array(":srvid" => $args["srvid"]);
        $datas = $QO->execute($params);
 
        $server_id = ( ! $datas ) ? $this->_DEFAULT_SRVID : $args["srvid"];
        
        //On crée la variable time (en microsecondes)
        $time = round(microtime(TRUE)*1000);
       
        /* On enregristre l'image dans la base de données et on récupère l'identifiant */
        $QO = new QUERY("qryl4tvidn1");
        $params = array(
            /*
             * [DEPUIS 12-06-16]
             *      Quand la version DEV a l'air plus complaisante, la version PROD ne veut pas laisser passer le fait que EID soit NULL, le temps d'un instant.
             *      Nous avons DEUX SOLUTIONS :
             *          (1) Laisser une valeur par défaut au niveau de la base de données. Cette solution est risquée dans le cas d'une forte activité.
             *              En effet, l'algo peut émettre une erreur pour problème de DOUBLON.
             *          (2) Mettre un EID temporaire UNIQUE le temps de la modification. Le plus simple c'est un TIMESTAMP.
             */
            ":vid_eid"            => $time, 
            ":vid_fn"             => $args["vid_fn"], 
//            ":vid_string_b64"     => "TEXTE_TEST", 
           /*
            * [DEPUIS 22-05-16]
            *      Provoquait des problèmes au niveau de la mémoire et de temps limite d'execution.
            *      Cette donnée n'est pas obligatoire. Elle pourra être de nouveau réassignée dans le futur. 
            */
//            ":vid_string_b64"     => $args["body_b64"], 
            ":vid_string_b64"     => NULL, 
            ":vid_height"         => $args["height"],
            ":vid_width"          => $args["width"],
            ":vid_size"           => $args["size"],
            ":vid_type"           => $args["type"],
            ":vid_duration"       => $args["duration"],
            ":vid_realpath"       => $args["realpath"],
            ":vid_srvid"          => $server_id,
            ":vid_date_tstamp"    => (string)$time
        );  
        $datas = $QO->execute($params);
     
        $vidid = $datas;
        
        //A partir de l'identifiant on crée l'ieid
        $vid_eid = $this->entity_ieid_encode($time,$vidid);
     
        //On update et on insère vid_eid
        $QO = new QUERY("qryl4tvidn2");
        $params = array(":vidid" => $vidid, ":vid_eid" => $vid_eid);
        $QO->execute($params);
     
        //ATTENTION : ON NE LOAD PAS L'ENTITY PARCE QUE CA VA ENTRAINER UNE ERREUR
        /*
         * Pour load l'IMAGE il nous faut "vid_prod_fn". Hors à la création de l'IMAGE elle n'existe pas.
         * Cette propriété dépend de la classe fille qui a sa propre fonction de codage.
         * Aussi, c'est la responsabilité du Developpeur que de load IMAGE APRES AVOIR SET "vid_prod_fn".
         */
//        $this->load_entity($args);
        
        $rt = [
            "vidid"           => $vidid,
            "vid_eid"         => $vid_eid,
            "vid_creadate"    => $time,
           /*
            * [DEPUIS 22-05-16]
            *      Provoquait des problèmes au niveau de la mémoire et de temps limite d'execution.
            *      Cette donnée n'est pas obligatoire. Elle pourra être de nouveau réassignée dans le futur. 
            */
//            "vid_string_b64"  => $args["body_b64"]
            "vid_string_b64"  => NULL
        ];
        
        //On retourne l'ieid à qui veut bien le prendre
        return $rt;
    }
    
    /*********************************************************************************************************/
    /************************************************* SPECS *************************************************/
        
    /**
     * Creates a name for the image, containing all the following information.
     * Output pattern: <ueid> . <machine_name> . <ieid> [. <width> . <height> .<quality>]
     * @param string $ueid User External ID
     * @param string $machine_name Name of the server
     * @param int $upload_timestamp Timestamp of the upload date of the image
     * @param int $vidid Video ID
     * @return string Image fullname
     */
     public function vidname_encode($ueid, $machine_name, $upload_timestamp, $vidid, $width = NULL, $height = NULL, $quality = NULL){
            //Génération d'un 'IEID' (Image External ID) basé sur la date d'upload (timestamp), qu'il faut repasser en secondes (et pas millisecondes comme en base)
            //et l'ID de l'image (auto increment)
            //Pour la conversion du timestamp, on va simplement utiliser la base 23.
            //Ensuite, on concatène l'ID réel avec la même stratégie de séparation avec le caractère 'n' dans le cryptage de UEID
            //Mais cette fois-ci avec un autre caractère (pour ne pas confondre): 'o'.
            //On transforme aussi le vidid en base 23.
            //[Pierre | 14/08/14] La création de l'ieid suis toujours cette logique, mais a été extrait d'ici pour en faire une fonction à part.
            $ieid = $this->entity_ieid_encode($upload_timestamp, $vidid);
            
            //On va aussi coder le nom du serveur
            $secret_machine = $this->serverName_encode($machine_name);
            
            //Et on crée notre nom d'image
            //Si $message est set, on l'ajoute à la fin
            if($width && $height && $quality){
                $vidname = $ueid . '_' . $secret_machine . '_' . $ieid . '_' . $width . 'x' . $height . '_' . $quality;
                return $vidname;
            } else if($width && $height){
                $vidname = $ueid . '_' . $secret_machine . '_' . $ieid . '_' . $width . 'x' . $height;
                return $vidname;
            } else {
                $vidname = $ueid . '_' . $secret_machine . '_' . $ieid;
                return $vidname;
            }
    }
    
    /**
    * Reverse function of vidname_encode. Will output an associative array of the 4 parts
    * of the image name (ueid, machine, uploadtstamp and vidid)
    * @param string $vidname Fullname of the image (minus extension)
    * @return array
    */
   public function vidname_decode($vidname){
       //On va décoder les informations contenues dans le nom de l'image à partir de celui-ci.
       //On commence par explode le string pour en récupérer les 3 parties
       $explodedName = explode('_', $vidname);

       $ueid = $explodedName[0];
       $raw_machine_name = $explodedName[1];
       $ieid = $explodedName[2];

       //On va traiter $ieid pour récupérer le timestamp d'uplaod et l'ID de l'image
       //On traite ensuite le tstamp pour le remettre en base 10
       //[Pierre | 14/08/14] La lecture de l'ieid se fait toujours selon cette logique, mais a été extrait pour en faire une fonction à part.
       $decIeid = $this->entity_ieid_decode($ieid);
       $b10vidid = $decIeid['id'];
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
               'vidid'          => $b10vidid,
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
               'vidid'          => $b10vidid,
               'resize'         => $resize
           ];
       } else {
           //Pas de message
           $ra = [
               'ueid'           => $ueid,
               'machine'        => $machine_name,
               'uploadtstamp'   => $b10tstamp,
               'vidid'          => $b10vidid
           ];
       }
       return $ra;
   }
    
    
    /*********************************************************************************************************************************************************************/
    /************************************************************************* GETTERS & SETTERS *************************************************************************/
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getvid_infos() {
        return $this->vid_infos;
    }
    
    public function getVidid() {
        return $this->vidid;
    }

    public function getvid_eid() {
        return $this->vid_eid;
    }

    public function getvid_fn() {
        return $this->vid_fn;
    }

    public function getvid_prod_fn() {
        return $this->vid_prod_fn;
    }
    
    public function setvid_prod_fn( $vidid,  $vid_prod_fn, $vid_fn_realpath, $srvname, $n_ftp_path ) {
        //$vid_fn permet d'aller chercher l'ancien fichier et vérifier s'il existe afin de changer de nom
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $this->vid_prod_fn = $vid_prod_fn; //Au cas où l'Entity est déjà loaded
        
        /* Modifier le nom du fichier sur le disque s'il existe */
        /*
         * [NOTE 09-09-14] @author L.C.
         * Les fichiers sont stockés sur des serveurs distants.
         * Aussi, on a des chemins sous forme d'URL.
         * Cela entraine l'utilisation de la toute fraiche fonction url_exists() de MOTHER
         */
//        if ( file_exists($vid_fn_realpath) ) {
        if ( $this->wos_url_exists($vid_fn_realpath) ) {
            list( $dirname, $basename, $extension, $filename ) = array_values( pathinfo($vid_fn_realpath) );
            $ofn = $filename.'.'.$extension;
            $nfn = $vid_prod_fn.'.'.$extension;
            
            $new_file_realpath = $dirname."/".$nfn;
            
            //Changement au niveau du serveur FTP
            $FPH = new FTP_HANDLER($srvname);
            $o_ftp_path = $n_ftp_path."/".$ofn;
////            var_dump($o_ftp_path, $nfn);
//            exit();
            $r = $FPH->ftp_rename_file($o_ftp_path, $nfn);
            
//            $r = rename($vid_fn_realpath, $new_file_realpath); //OBSELETE
            
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
//            if ( file_exists($vid_prod_fn) )                
//                return TRUE;
//            else {
                //Retourner NULL pour que le CALLER soit averti
                return;
//            }
            
        }
        
        //Enregistrer la valeur dans la base de données
        $n_ftp_fpath = $n_ftp_path."/".$nfn;
        $QO = new QUERY("qryl4tvidn3");
        $params = array(
            ":vidid"          => $vidid, 
            ":vid_prod_fn"    => $nfn, 
            ":vid_realpath"   => $new_file_realpath, 
            ":n_ftp_fpath"    => $n_ftp_fpath
        );
        $QO->execute($params);
        
        /*
         * [DEPUIS 25-06-15] @BOR
         *      On renvoie des données qui pourront servir à d'autres processus.
         */
        $r = [
            "pctid"         => $vidid,
            "pctprod_fn"    => $nfn,
            "pct_realpath"  => $new_file_realpath,
            "pct_ftp_fpath" => $n_ftp_fpath,
        ];
        
        return $r;
//        return TRUE;
    }
    
    public function getvid_string() {
        return $this->vid_string;
    }

    public function getvid_height() {
        return $this->vid_height;
    }

    public function getvid_width() {
        return $this->vid_width;
    }

    public function getvid_size() {
        return $this->vid_size;
    }

    public function getvid_type() {
        return $this->vid_type;
    }

    public function getvid_creadate() {
        return $this->vid_creadate;
    }

    public function getvid_path() {
        return $this->vid_path;
    }

    public function getvid_realpath() {
        return $this->vid_realpath;
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