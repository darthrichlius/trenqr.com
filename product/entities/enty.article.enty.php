<?php

/*
 * [DEPUIS 19-01-16]
 */
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Format\Video\X264;
use FFMpeg\FFProbe;
/*
 * [DEPUIS 18-08-16]
 */
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\Filters\Video\RotateFilter;

/**
 * Entity ARTICLE.
 * Cette classe est héritée par ARTICLE_ITR et ARTICLE_IML.
 *
 * @author Richard DIEUD <lou.carther@deuslynn-entreprise.com>
 */
class ARTICLE extends PROD_ENTITY {
    
    /**
     * Permet de signifier s'il faut que l'article soit accompagné d'un texte ou un mot-clé.
     * Par défaut, cette valeur est à TRUE. En effet, une image sans texte ne pourra pas $etre indéxée.
     * On ne pourra donc pas la Rechercher ou la Suggérer.
     * @var bool 
     */
    protected $reg_art_desc;
    
    /*---------------------------*/
    
    protected $artid;
    /**
     * L'identifiant 'art_eid' permet d'avoir un identifiant composé unique.
     * (1) Il a l'avantage de ne pas indiquer au monde exterieur le nombre d'Articles au niveau de la plateforme.
     * En effet, artid est un nombre autoincrémenté. Aussi, les utilisateurs peuvent facilement savoir le nombre d'Articles créés depuis le debut.
     * (2) Il a l'avantage d'etre complexe et de rendre la tâcge plus difficile pour sa compréhension. Par exemple : 
     *      (20) Sa complexité fait qu'il sera par exemple difficile pour un utilisateur de tenter de manipuler un article.
     *          ... Si on laissait le chiffre incrémenté, et qu'il savait qu'on est à 1000 articles, il lui suffirait de chercher l'article ayant un artid < 1000  
     * @var mixed
     */
    protected $art_eid;
    protected $art_picid;
    /*
     * Si cette valeur est différente de NULL alors l'Article ne doit pas être utilisé.
     * Un Article est 'to_delete' dans plusieurs cas :
     *      (1) L'utilisateur a décidé de supprimer son compte
     */
    protected $art_removal_date;
    protected $art_pdpic_path;
    protected $art_locip;
    protected $art_creadate;
    protected $art_desc;
    protected $art_client_creadate;
    /**
     * Liste des Mots-clés. Il ne s'agit que des libellés
     * Cette propriété est obligatoire pour le chargement de l'ENTITY. Meme si elle est NULL.
     * @var type 
     */
    protected $art_list_hash; 
    /**
     * Liste des Usertag pour l'Article
     * @var array 
     */
    protected $art_list_usertags; 
    /*
     * Liste des UIC (UrlInContent)
     */
    protected $art_list_uic; 
    protected $art_pdpic_string;
    protected $art_prmlk;
    /*
     * [NOTE 12-0-14]
     * J'ai choisi pour la solution qui permet de récupére toutes les informations sur le propriétaire de l'a Tendan'Article.
     * Cela permet ainsi de créer un jet de données sans plus d'opérations.
     */
    protected $art_oid;
    protected $art_ogid;
    /**
     * L'identifiant externe du propriétaire de l'Article.
     * @var type 
     */
    protected $art_oeid;
    protected $art_ofn;
    protected $art_opsd;
    protected $art_oppicid;
    protected $art_oppic;
    protected $art_ohref;
    /**
     * Liste des Reactions. Il s'agit d'une liste d'objets de type REACTION. 
     * Cette propriété est obligatoire pour le chargement de l'ENTITY. Meme si elle est NULL.
     * @var type 
     */
    protected $art_list_reacts;
    protected $art_rnb;
    protected $art_eval;
    protected $art_visitnb;
    
    protected $art_is_video;
    protected $art_vid_url;
    
    protected $art_is_sod;
    protected $art_is_hstd;
    
    /*------------------- RULES ----------------**/
    
    protected $_MAX_LENGTH_DESC;
    protected $art_hash_or_desc;
    protected $_SRV_IF_FIRST;
    protected $_SRV_LIST;
    protected $_ART_STATE;
    /*
     * MODE :
     *  1 : ToInvestigate   => L'Article reste accessible le temps de l'enquete.
     *  2 : AccountRemoval  => L'Article est en mode suppression effective, après une action de suppression de Compte.
     *  3 : TrendRemoval    => L'Article est en mode suppression effective, après une action de suppression de la Tendance liée.
     *  4 : ToRemove        => L'Article est en mode suppression effective. La raison n'est pas connue.  
     *  5 : Buffering       => L'Article n'est pas accessible le temps de l'enquete.
     *  6 : Available
     */
    public static $_ART_STATE_STATIC = [1,2,3,4,5,6];


    /*
     * Le nombre maximum de caractères autorisés. Ce nombre est amené à augmenter en fonction de la futur capacité de nos serveurs.
     * La base de données est cependant calibrer pour accueillir 1000 caractères.
     * Les caractères ne sont pas décompter.
     * A savoir que Facebook est à peu près à 5000 pour les posts. J'imagine que c'est pareil pour les commentaires.
    */
    protected $_REACT_MAX_TEXT;
    
    protected $_FAV_ACT_ID;
    
    protected $_ART_SPECS;
    
    protected $_PIC_XTRABAR_SPECS;
    
    protected $_FILE_TRANSIT_DIR;
    
    function __construct($__FILE = NULL, $__CLASS = NULL) {
        if ( empty($__FILE) && empty($__CLASS) )
            parent::__construct(__FILE__,__CLASS__);
        else
            parent::__construct($__FILE,$__CLASS." (or ATRICLE)");
        
        $this->prop_keys = ["artid","art_eid","art_prmlk","art_picid","art_pdpic_path","art_locip","art_creadate","art_client_creadate","art_desc","art_is_video","art_vid_url","art_is_sod","art_is_hstd","art_list_hash","art_list_usertags","art_list_uic","art_pdpic_string","art_list_reacts","art_rnb","art_eval","art_oid","art_ogid","art_oeid","art_ofn","art_opsd","art_oppicid","art_oppic","art_ohref"];
        $this->needed_to_loading_prop_keys = ["artid","art_eid","art_prmlk","art_picid","art_pdpic_path","art_locip","art_creadate","art_desc","art_is_video","art_vid_url","art_is_sod","art_is_hstd","art_pdpic_string","art_oid","art_ogid","art_oeid","art_ofn","art_opsd","art_oppicid","art_oppic","art_ohref"];
//        $this->needed_to_create_prop_keys = ["accid","acc_eid","art_desc","art_locip","pdpic_fn","art_pdpic_string"];
        $this->needed_to_create_prop_keys = ["accid","acc_eid","art_desc","art_locip","file.name","file.type", "file.data", "file.options"];
        
        $this->art_hash_or_desc = TRUE;
        /*
         * [DEPUIS 17-11-15] @author BOR
         *      Ajout de 4 => Localhost pour permettre de travailler en LOCAL
         */
        $this->_SRV_LIST = [
            1 => "lisa1", 
            2 => "marge1", 
            3 => "bart1", 
            4 => "localhost"
        ];
        $this->_SRV_IF_FIRST = 2;
        $this->_ART_STATE = [1,2,3,4,5,6];
        
        /*
         * 242 = 242 caractères qui ne sont pas des '#'.
         * Les '#' sont échapés. Aussi, on peut atteindre le double à savoir 44.
         */
        $this->_MAX_LENGTH_DESC = 242; 
        
        $this->_REACT_MAX_TEXT = 1000;
        
        /**************************************/
        
        $this->_FAV_ACT_ID = [
            "ART_XA_FAV_PUB"    => 1,
            "ART_XA_FAV_PRI"    => 2,
            "ART_XA_UNFAV"      => 3
        ];
        
        /**************************************/
        /*
         * [DEPUIS 21-01-16]
         */
        $this->_ART_SPECS = [
            "ART_IMG_MAX_SIZE"  => 1048576*8,
            "ART_VID_MAX_SIZE"  => 1048576*20,
            /*
             *  60+1 Si nous n'ajoutons pas +1, le système va rejetté certaine vidéo dont la durée est de 30.85s hors l'utilisateur ne voit que 30s.
             *  Il risque de ne pas comprendre.
             */
            "ART_VID_MAX_DUR"   => 61,
            "ART_IMG_EDGE_REF"  => 600
        ];
              
        /**************************************/
        /*
         * [DEPUIS 21-01-16]
         */
        $this->_PIC_XTRABAR_SPECS = [
            "TEXT_LEN_MAX"  => 50,
            "COLOR_LIST"    => ["NONE","DEFAULT","STD_TRENQR","STD_FRIEND","STD_POD","STD_TREND","STD_BLACK"],
            "POSITION_TOP_MAX" => 337
        ];
        
        /*
         * [DEPUIS 03-08-16]
         */
        $this->_FILE_TRANSIT_DIR = RACINE."/temp/php_file_transit/";
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, ...,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }

    
    protected function build_volatile($args) { }
    
    public function exists($arg, $_OPTIONS = NULL) {
        //SB TESTED
        /*
         * Vérifie si un Article existe. L'Article est identifiable via son identifiant art_eid fourni dans le tableau.
         * Si l'Article n'existe pas, on revoie FALSE.
         */
        $art_eid = NULL;
        
        //Déclencher une exception personnalisée si on ne recoit pas la valeur ["artid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->art_eid) ) {
                return;
            } else {
                $art_eid = $this->art_eid;
            }
        } else {
            $art_eid = $arg;
        }
                
        //Contacter la base de données et vérifier si l'Article existe.
        
        $QO = new QUERY("qryl4artn3neo0615001");
//        $QO = new QUERY("qryl4artn3");
        $params = array( ':art_eid' => $art_eid );
        $datas = $QO->execute($params);
        /*
        var_dump(__LINE__,$datas[0],!empty($datas[0]["ash_id"]), empty($datas[0]["ash_evedate_tstamp"]), !empty($datas[0]["ash_state"]), intval($datas[0]["ash_state"]) );
//        var_dump(__LINE__,$datas);
        if ( intval($datas[0]["artid"]) === 595 ) {
            exit(); 
        }
        //*/
        if ( $datas && !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("NO_STATE_CHECK", $_OPTIONS) ) {
            return $datas[0];
        } else if ( $datas && key_exists("ash_id", $datas[0]) ) {
            $r = ( !empty($datas[0]["ash_id"]) && empty($datas[0]["ash_evedate_tstamp"]) && !empty($datas[0]["ash_state"]) && !in_array(intval($datas[0]["ash_state"]),[1,6]) ) ?  FALSE : $datas[0]; 
//            $r = ( !empty($datas[0]["ash_id"]) && empty($datas[0]["ash_evedate_tstamp"]) && !empty($datas[0]["ash_state"]) && intval($datas[0]["ash_state"]) !== 6 ) ?  FALSE : $datas[0]; 
        } else {
            $r = FALSE;
        }
//        $r = ( $datas ) ? $datas[0] : FALSE;
        
        return $r;
    }
    
    public function exists_with_id($arg) {
        //SB TESTED
        /*
         * Vérifie si un Article existe. L'Article est identifiable via son identifiant artid fourni dans le tableau.
         * Si l'Article n'existe pas, on revoie FALSE.
         */
        $artid = NULL;
        //Déclencher une exception personnalisée si on ne recoit pas la valeur ["artid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->artid) ) {
                return;
            } else {
                $artid = $this->artid;
            }
        } else { 
            $artid = $arg; 
        } 
                
        //Contacter la base de données et vérifier si l'Article existe.
        
        $QO = new QUERY("qryl4artn4neo0615001");
//        $QO = new QUERY("qryl4artn4");
        $params = array( ':artid' => $artid );
        $datas = $QO->execute($params);
        /*
        var_dump(__LINE__,$datas);
        if (intval($datas[0]["artid"])===595) {
            exit(); 
        }
        */
        if ( $datas && key_exists("ash_id", $datas[0]) ) {
            $r = ( !empty($datas[0]["ash_id"]) && empty($datas[0]["ash_evedate_tstamp"]) && !empty($datas[0]["ash_state"]) && !in_array(intval($datas[0]["ash_state"]),[1,6]) ) ?  FALSE : $datas[0]; 
//            $r = ( !empty($datas[0]["ash_id"]) && empty($datas[0]["ash_evedate_tstamp"]) && !empty($datas[0]["ash_state"]) && intval($datas[0]["ash_state"]) !== 6 ) ?  FALSE : $datas[0]; 
        } else {
            $r = FALSE;
        }
//        $r = ( $datas ) ? $datas[0] : FALSE;
        
        return $r;
    }
    
    public function exists_with_prmlk($arg) {
        //SB TESTED 21-02-15
        /*
         * Vérifie si un Article existe. L'Article est identifiable via son identifiant PerMaLinKid fourni.
         * Si l'Article n'existe pas, on revoie FALSE.
         */
                
        $QO = new QUERY("qryl4artn15neo0615001");
//        $QO = new QUERY("qryl4artn15");
        $params = array( ':prmlk' => $arg );
        $datas = $QO->execute($params);
        /*
        var_dump(__LINE__,$datas);
        if (intval($datas[0]["artid"])===595) {
            exit(); 
        }
        */
        if ( $datas && key_exists("ash_id", $datas[0]) ) {
            $r = ( !empty($datas[0]["ash_id"]) && empty($datas[0]["ash_evedate_tstamp"]) && !empty($datas[0]["ash_state"]) && !in_array(intval($datas[0]["ash_state"]),[1,6]) ) ?  FALSE : $datas[0]; 
//            $r = ( !empty($datas[0]["ash_id"]) && empty($datas[0]["ash_evedate_tstamp"]) && !empty($datas[0]["ash_state"]) && intval($datas[0]["ash_state"]) !== 6 ) ?  FALSE : $datas[0]; 
        } else {
            $r = FALSE;
        }
//        $r = ( $datas ) ? $datas[0] : FALSE;
        
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
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED",$this->needed_to_loading_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT =>",array_keys($datas)],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>",  array_diff($this->needed_to_loading_prop_keys, array_keys($datas))],'v_d');
                $this->signalError ("err_sys_l4comn5", __FUNCTION__, __LINE__,TRUE);
            } 
            /*
            else 
            {
                /*
                 * Ces données doivent être NON NULLES. En effet, elles ne peuvent être NULL
                 *
                if ( in_array($k, $this->needed_to_loading_prop_keys) && empty($datas[$k]) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$datas[$k],'v_d');
                    $this->signalError ("err_sys_l4comn", __FUNCTION__, __LINE__,TRUE);
                }
            }
            */
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
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args, TRUE);
        
        /* On vérifie si on a l'identifiant. Sinon on tente de prendre celui déjà chargé s'il existe */
        $art_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("art_eid", $args) && !empty($args["art_eid"]) ) ) 
        {
            if ( empty($this->art_eid) )
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            else 
                $art_eid = $this->art_eid;
        } else $art_eid = $args["art_eid"];
        
        // On controle si l'occurrence existe et on récupèrre les données (notamment accid)
        $exists = $this->exists($art_eid);
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$exists],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        if ( $exists["art_eid"] === '7fbbjo12k' ) var_dump(__LINE__,$exists);
//        exit();
        if ( ( !$exists ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$exists ) && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $accid = $exists["art_accid"];
        $article = $exists;
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$exists,$article,$datas],'v_d');
        
        //*/
        /*
        //TODO : Intéroger la base de données 
        //*
        $QO = new QUERY("qryl4accn1");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$exists,$article,$datas],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
        if ( ( !$datas || count($datas) > 1 ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_sys_l4comaccn1", __FUNCTION__, __LINE__);
        }
        else if ( ( !$datas || count($datas) > 1 ) && !$std_err_enbaled ) 
        {
            //[NOTE 24-0-2014] Cela peut aussi 
            if (! $datas )
                return "__ERR_VOL_USER_GONE";
            else 
                return 0;
        }
        //*/
        $PA = new PROD_ACC();
        $exists = $PA->exists_with_id($accid,TRUE);
        
        if ( !$exists && $std_err_enbaled ) 
        {
            $this->signalError ("err_sys_l4comaccn1", __FUNCTION__, __LINE__);
        }
        else if ( !$exists && !$std_err_enbaled ) 
        {
            return "__ERR_VOL_USER_GONE";
        }
        
        $owner = $exists;
        
        $loads = [
            //Données simulées sur l'Article
            "artid"             => $article["artid"],
            "art_eid"           => $article["art_eid"],
            //RAPPEL : art_prmlk représente un identifiant et non un lien. Il serait judicieux de le changer mais l'opportunité ne sait pas encore présentée.
            "art_prmlk"         => $article["art_prmlk"],
            "art_picid"         => $article["art_pdpicid"],
            "art_pdpic_path"    => $article["art_pdpic_realpath"], //A FAire : Recuperer l'adresse
            "art_locip"         => $article["art_loc_numip"],
            "art_desc"          => htmlentities($article["art_desc"]),
            //[DEPUIS 24-01-16]
            "art_is_video"      => $article["art_is_video"],
            "art_is_sod"        => $article["art_is_sod"],
            //[DEPUIS 24-01-16]
            "art_is_hstd"       => $article["art_is_hstd"],
            "art_creadate"      => $article["art_cdate_tstamp"],
            "art_pdpic_string"  => $article["art_pdpic_string_b64"],
            //Données simulées sur l'OWNER
            "art_oid"           => $owner["pdaccid"],
            "art_ogid"          => $owner["pdacc_gid"],
            "art_oeid"          => $owner["pdacc_eid"],
            "art_ofn"           => $owner["pdacc_ufn"],
            "art_opsd"          => $owner["pdacc_upsd"],
//            "art_oppic" => $owner["pdacc_uppic"],
            "art_ohref"         => "/".$owner["pdacc_upsd"]
        ];
      
        /*
         * [NOTE 22-09-14] @author L.C.
         * Du fait de changements au niveau de la gestion de PROFILPIC, on est obligé de faire appel à PDACC pour récupérer l'image de profil.
        */
       //RAPPEL : Attention, à vb1, ppicid peut être 1 mais l'image peut ne pas êxister dans la base de données. (Meme très surement)
        $loads["art_oppicid"] = $PA->onread_acquiere_pp_datas($loads["art_oid"])["picid"];
        $loads["art_oppic"] = $PA->onread_acquiere_pp_datas($loads["art_oid"])["pic_rpath"];
        
        $artid = $loads['artid'];
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $loads,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
        if ( !count($loads) ) 
        { 
            if ( $std_err_enbaled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } 
        else 
        {
            $r;
            $extras = ["art_list_hash","art_list_usertags","art_list_uic","art_list_reacts","art_eval","art_vid_url"];
            foreach ( $extras as $v ) {
                $r = $this->load_entity_extras_datas($artid, $loads["art_eid"], $v);

                /* 
                 * Si pour x raisons, le contenu n'est pas disponible, plutot que de déclencher une erreur on déclare les valeurs à NULL. 
                 * Cela permet à l'utilisateur d'avoir au moins une partie de ses données.
                 * 
                 * Si $r === 0, alors on l'identifiant est faux. Dans ce cas et dans le cas n'est pas disponible on affiche le code une erreur.
                 * On ne le fait que si et seulement si on est en mode non DEBUG.
                 */
                if ( !isset($r) || $r === 0 ) {
                    $loads[$v] = NULL;
                    
                    //On signale l'erreur si on est en mode DEBUG
                    $er = $this->get_or_signal_error (1, "err_sys_l4comn7", __FUNCTION__, __LINE__, TRUE);
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$er,$v,$r], 'v_d');
                } else {
                    $loads[$v] = $r;
                }
                
                /*
                //Cas du nombre des commentaires
                if ( $v === "art_list_reacts" ) {
                    if ( !empty($loads[$v]) && is_array($loads[$v]) && count($loads[$v]) ) {
                        $loads["art_rnb"] = count($loads[$v]);
                    }
                    else {
                        $loads["art_rnb"] = 0;
                    }
                }
                //*/
            }
            
            /*
             * ETAPE :
             * On récupère le nombre total de Commentaires pour l'Article
             */
            $loads["art_rnb"] = $this->onload_art_rnb_wid($loads["artid"]);
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $loads,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//            exit();
            
            $this->init_properties($loads);
            $this->is_instance_load = TRUE;
            return $loads;
        }
        
    }

    
    protected function on_alter_entity($args) { }
    

    public function on_create_entity($args) {
        //RAPPEL : pdpic_string est la représentation BASE64 d'une image. Le texte doit avoir comme préfixe data:....
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        /*
         * [DEPUIS 18-01-16]
         *      Ajout des éléments : file.type, file.data, file.options
         */
        //On vérifie la présence des données obligatoires : file.type, file.data, file.options, art_oeid, art_pdpic_string, art_desc, art_locip
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,array_keys($args));
//        exit();
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                /*
                 * [DEPUIS 15-08-15] @author BOR
                 *      Prise en compte de "aistr"
                 */
                if ( !( isset($v) && $v !== "" ) && !in_array($k,["aistr","file.options"]) ) {
                    /*
                     * [DEPUIS 15-08-15] @BOR
                     *  Corrige le bogue lié à un texte de description ne contenant que le caractère '0'.
                     */
//                if ( empty($v) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        //On vérifie si le compte existe toujours
        $PACC = new PROD_ACC();
        $exists_id = $PACC->exists_with_id($args["accid"],TRUE);
//        $exists_eid = $PACC->exists($args["acc_eid"],TRUE); //OBSELETE 06-10-14
        
        /*
         * ETAPE : 
         * On s'assure de manière stricte que l'utilisateur existe
         * Pour cela on vérifie si son eid et son id existent et coeincident.
         */
        if (! ( $exists_id && $exists_id["pdacc_eid"] === $args["acc_eid"] ) ){
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * [DEPUIS 18-01-16]
         *      On vérifie le type de données et redirige vers la bonne méthode de traitement.
         */
        $file = [
            "name"      => $args["file.name"], 
            "type"      => $args["file.type"], 
            "data"      => $args["file.data"], 
            "options"   => $args["file.options"]
        ];
        
//        $pdpic_string = $args["art_pdpic_string"];
        
        /* On effectue les vérifications et traitements de l'image associée */
//        $infos = NULL;
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$file["name"]);
//        exit();
        
        /*
         * [DEPUIS 18-08-16]
         *      Ajout du 2eme param pour on_create_check_pic();
         */
        $infos = ( $args["file.type"] === "image" ) ? $this->on_create_check_pic($file["data"],$file) : $this->video_check($file);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $infos) ) {
            return $infos;
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$infos);
//        exit();
        
        /*
         * [DEPUIS 24-01-15]
         *      
         */
        $this->art_is_video = ( $args["file.type"] === "image" ) ? FALSE : TRUE;
        $this->art_is_sod = ( $args["file.options"] && !empty($args["file.options"]["istory"]) 
            && ( ( is_string($args["file.options"]["istory"]) && strtolower($args["file.options"]["istory"]) === "true" ) || $args["file.options"]["istory"] === 1 ) ) ? TRUE : FALSE;
        $this->art_is_hstd = ( $args["file.options"] && !empty($args["file.options"]["ihosted"]) 
            && ( ( is_string($args["file.options"]["ihosted"]) && strtolower($args["file.options"]["ihosted"]) === "true" ) || $args["file.options"]["ihosted"] === 1 ) ) ? TRUE : FALSE;
        
        /*
         * ETAPE :
         *      On retravaille l'image dans les cas suivants :
         *      1) L'image n'est pas carrée, elle a été envoyée sans post-traitement pour ne pas déteriorer sa qualité.
         *          > Il nous faut la RESIZE (600px) et la CROPPER au niveau du SERVER
         *      2) L'image est un extrait d'une vidéo et elle n'est pas carrée
         *          > Il nous faut créer une image carrée avec la combinaison de : fond noir + IMAGE qui a été RESIZE (600px)
         */
        if ( ( intval($infos["width"])/intval($infos["height"]) ) !== 1 && $this->art_is_video ) {
            $infos = $this->video_create_jacket($infos);
        } else if ( ( intval($infos["width"])/intval($infos["height"]) ) !== 1 && !$this->art_is_video ) {
            echo "We should now RESIZE and CROP this image to enhance its quality";
        } 
        /*
         * [DEPUIS 30-04-16]
         * ETAPE :
         *      On ajoute la barre personnalisée le cas échéant
         */
        else if ( $args["file.options"] && !empty($args["file.options"]["xtrabar"]) ) {
            $txbar_datas = $this->cuztextbar_check_params($args["file.options"]["xtrabar"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $txbar_datas) ) {
                return $txbar_datas;
            }
            $infos = $this->cuztextbar_add_image($infos,$txbar_datas);
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$infos);
//        exit();
        
        /*
        ob_clean();
//        var_dump($infos, $infos["file.data"]);
        var_dump($txbar_datas);
//        echo "<img src='data:image/jpeg;base64,".base64_encode($infos["body_b64"])."' />";
        echo "<img src='".$infos["file.data"]."' />";
        exit();
        //*/
        
//        $tmp = ( intval($infos["width"])/intval($infos["height"]) );
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$infos,intval($infos["width"]),intval($infos["height"]),$tmp, $tmp !== 1]);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$infos,$this->art_is_video,$this->art_is_sod,$this->art_is_hstd]);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$args,$args["file.options"]["istory"],$args["file.options"]["istory"] === "true", $this->art_is_video,$this->art_is_sod,$this->art_is_hstd]);
//        exit();

        //On effectue les vérifications et traitements sur le texte
        $art_desc = $args["art_desc"];
        $kws = $usertags = NULL;
        $nt = $this->on_create_treat_desc($art_desc, $kws, $usertags);
        if (! ( isset($nt) && $nt != "" ) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $nt,'v_d');
            $this->signalError ("err_user_l4artn1", __FUNCTION__, __LINE__,TRUE);
        } else {
            /*
             * [DEPUIS 17-11-15] @author BOR
             *      $odesc permet de garder le texte d'origine pour le traiter dans d'autres secteurs.
             */
            $odesc = $args["art_desc"];
            $args["art_desc"] = $nt;
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$nt]);
//        exit();
        
        /*
         * [NOTE 16-0-14]
         *      On ne stocke pas 'art_pdpic_string' à la version vbeta1 pour des raisons d'économie.
         *      De plus, cette version numérique est déjà stockée au niveau de la table IMAGE.
         */
        
        //On crée un tableau contenant les données dont 
        /* On crée l'Article à proprement parler */
        $art_infos = $this->write_new_in_database($args);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$art_infos]);
//        exit();
              
        $artid = $art_infos["artid"];
        $art_eid = $art_infos["art_eid"];
        $art_prmlk = $art_infos["art_prmlk"];
        /*
         * ETAPE :
         * On ajoute l'image en FTP ainsi que sa représentation au niveau de la base de données.
         */
        
        /*
         * Vérifier sur quel server a été enregistré le dernier Article de CU
         */
        /*
        $QO = new QUERY("qryl4picn4");
        $params = array(":ap_artid" => $artid);  
        $datas = $QO->execute($params);
        
        $last_srvid = NULL;
        
        if (! $datas ) {
            $last_srvid = $this->_SRV_IF_FIRST;
        } else {
            $csid = $datas[0]["pdpic_srvid"];
            $last_srvid = ( intval($csid) === intval($this->_SRV_IF_FIRST) ) ? 1 : $this->_SRV_IF_FIRST;
        }
        //*/
        
        /*
         * [DEPUIS 17-11-15] @author BOR
         *      Le code ci-dessus était COMPLETEMENT obselète et ne permettait pas la portabilité du code d'un environnement à un autre.
         *      Par défaut le serveur sera toujours 1. Sauf si on est en local où on choisit 4 ! 
         */
        $last_srvid = ( ( defined("WOS_MAIN_HOST") && in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? 4 : 1;
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,"WOS_MAIN_HOST => ",WOS_MAIN_HOST,"; IS_SANDBOX => ",IS_SANDBOX,$last_srvid);
//        exit();
        
        /*
         * (EVOLUTION) TODO : 
         *      Vérfier si le serveur est disponible. Sinon changer de serveur
         */
       /*
        * [DEPUIS 30-04-16]
        */
        if ( $file["type"] === "video" ) {
            $file_datas = $infos["frame"]["file.data"];
        } else if ( $args["file.options"] && !empty($args["file.options"]["xtrabar"]) ) {
            $file_datas = $infos["file.data"];
        } else {
            $file_datas = $file["data"];
        }
        /* On commence l'enregistrement de l'image (BDD + DISK) */
        $imgdatas = [
            "pdpic_fn"      => $file["type"] === "image" ? $args["file.name"] : $infos["frame"]["file.basename"],
//            "pdpic_string"  => $args["art_pdpic_string"], //On renvoie la version complete car image on aura besoin pour valider correctement l'image_string
            
            "pdpic_string"  => $file_datas,
            "srvid"         => $last_srvid,
            "srvname"       => $this->_SRV_LIST[$last_srvid],
            "pdpic_artid"   => $artid,
            "pdpic_art_eid" => $art_eid,
            "pdpic_ueid"    => $args["acc_eid"],
            "oeid"          => $exists_id["pdacc_eid"]
        ];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$imgdatas);
//        exit();
       
        $ARI = new IMAGE_ART();
        $create_img_r = $ARI->on_create($imgdatas);
        
        //On met à jour ARTICLE avec les données de IMAGE
        if ( $create_img_r ) {
            $create_img_r["artid"] = $artid;
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$r],'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//            exit();
            
            $this->on_create_append_image_infos($create_img_r);
        } else {
            /*
             * [NOTE 02-04-15] @BlackOwlRobot
             * Si une erreur survient, le processus en entier s'arretera. Aussi, il parait impossible qu'on atterisse ici.
             * Si cela est possible, on doit procéder à la supression de l'Article.
             * RAPPEL : Un script au niveau de CRON permet (en temps normal) de réaliser la même opération de vérification/suppression.
             */
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$create_img_r);
//        exit();
        
        /*
         * [DEPUIS 21-01-16]
         * ETAPE :
         *      On ajoute la vidéo au niveau de la base de données et en dur, le cas échéant.
         */
        if ( $args["file.type"] === "video" ) {
            if ( $infos && $infos["frame"] ) {
                
                /*
                 * ETAPES POSSIBLES :
                 *  -> Réduire la vidéo si elle est beucoup trop, grande
                 *  -> Sauvegarder la video en dur
                 *  -> Sauvegarder la video dans la base de données
                 *      -> Nommer la video en fonction de l'identifiant de l'article associé
                 */
                if ( false ) {
                    //Reduire la video
                }
                
                /*
                 * ETAPE :
                 *      
                 */
                $vdatas = [
                    "vid_fname"     => $args["file.name"],
                    "vid_data"      => $file["data"],
                    "srvid"         => $last_srvid,
                    "srvname"       => $this->_SRV_LIST[$last_srvid],
                    "vid_artid"     => $artid,
                    "vid_art_eid"   => $art_eid,
                    "vid_ueid"      => $args["acc_eid"]
                ];
                
                $VID = new VIDEO_ART();
                $create_vid_r = $VID->on_create($vdatas);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $create_vid_r) ) {
                    return $create_vid_r;
                }
                
            } else {
                echo "Suppprimer Frame, Supprimer l'Article et lancer une erreur !";
            }
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$create_vid_r);
//        exit();
        
        /*
         * ETAPE : 
         * On insére les hashtags 
         */
        if ( isset($kws) && is_array($kws) && count($kws) ) {
            
            /*
             * [DEPUIS 17-11-15] @author BOR
             *      Permet d'enregistrer les hashtags pour que la page HVIEW puisse fonctionner.
             *      En ce qui concerne les données HASHTAGs sur articles créés avant cette date, il devront être transférées au risque que ces articles ne soit ignorés par HVIEW
             */
            $HVIEW = new HVIEW();
            $type = ( key_exists("aistr", $args) && $args["aistr"] === TRUE ) ? "HCTP_ART_ITR" : "HCTP_ART_IML";
            $args_urlic = [
                "t"     => $odesc,
                "hci"   => $artid,
                "hcei"  => $art_eid,
                "hcp"   => $type,
                "ssid"  => session_id(),
                "locip" => $args["art_locip"],
                "curl"  => NULL,
                "uagnt" => $_SERVER['HTTP_USER_AGENT']
            ];
            $kws_r = $HVIEW->HSH_oncreate($args_urlic["t"], $args_urlic["hci"], $args_urlic["hcei"], $args_urlic["hcp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
            
            /*
            $list_kws = $kws[1];
                    
            $TH = new TEXTHANDLER();
            /*
             * [NOTE 30-11-14] @author L.C.
             *  Permet de corriger le bug qui fait que lorsqu'on ajoute un Article avec deux hashtag identique ça bogue. 
             *  Cela est du au fait que la table d'Archivage n'accèpte qu'un exemplaire.
             *  On va donc stocker les hashtags déjà archiver et ne pas les ajouter.
             *
            $archived = [];
            foreach ( $list_kws as $v ) {
                /*
                 * On crée la version "neutre" (lettre s en minuscule et sans accents).
                 * Cette version est la version archivée.
                 * 
                 * _nm = NeutreFormat
                 *
                $kw_nf = $TH->remove_accents($v);
                $kw_nf = strtolower($kw_nf);
                
                //On vérifie si le mot-clé a déjà été enregistré
                $QO = new QUERY("qryl4kwsn1");
                $params = array(":kwlib" => $kw_nf);  
                $datas = $QO->execute($params);
                
                $time = round(microtime(TRUE)*1000);
                 
                if (! $datas ) {
                    
                    //(Sinon) On l'enregistre dans la table mère Keywords qui a la représentation du mot-clé
                    $QO = new QUERY("qryl4kwsn2");
                    $params = array(":kwlib" => $kw_nf,":kw_lib_ori" => $v,":kw_loc_numip" => $args["art_locip"],":kw_date_tstamp" => $time,":kw_art_eid" => $art_eid,":kw_acc_eid" => $args["acc_eid"]);  
                    $QO->execute($params);
                    
                    if (! in_array($kw_nf, $archived) ) {
                        //On l'enregistre aussi dans les Archives. (Elles permettent de faire des recherches, des statistiques et d'Archiver les mots-clés
                        $QO = new QUERY("qryl4kwsn3");
                        $params = array(":kwarch_lib" => $kw_nf,":kwarch_lib_ori" => $v,":kwarch_art_eid" => $art_eid,":kwarch_loc_numip" => $args["art_locip"],":kwarch_date_tstamp" => $time);  
                        $QO->execute($params);
                        
                        //On lie le mot-clé à l'article 
                        $QO = new QUERY("qryl4kwsn4");
                        $params = array(":kba_kwlib" => $kw_nf,":kba_lib_ori" => $v,":kba_artid" => $artid,":kba_date_tstamp" => $time);  
                        $QO->execute($params);
                        
                        $archived[] = $kw_nf;
                    }
                    
                } else {
                    
//                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$art_eid,in_array($kw_nf, $archived),$archived],'v_d');
                    if (! in_array($kw_nf, $archived) ) {
                        //On lie le mot-clé à l'article 
                        $QO = new QUERY("qryl4kwsn4");
                        $params = array(":kba_kwlib" => $kw_nf,":kba_lib_ori" => $v,":kba_artid" => $artid,":kba_date_tstamp" => $time);  
                        $QO->execute($params);
                        
                        //On l'enregistre aussi dans les Archives. (Elles permettent de faire des recherches, des statistiques et d'Archiver les mots-clés
                        $QO = new QUERY("qryl4kwsn3");
                        $params = array(":kwarch_lib" => $kw_nf,":kwarch_lib_ori" => $v,":kwarch_art_eid" => $art_eid,":kwarch_loc_numip" => $args["art_locip"],":kwarch_date_tstamp" => $time);  
                        $QO->execute($params);
                        
                        $archived[] = $kw_nf;
                    }
                    
                }
                
            }
            //*/
        }
        
        /*
         * ETAPE : 
         * On insére les pseudos dans les tables UserTag
         */
        if ( $usertags && is_array($usertags) && count($usertags) ) {
            $TH = new TEXTHANDLER();
            
            $list_utags = $usertags[1];
            
            //On retire les accents et on met tous les tags en lowercase
            /*
             * [NOTE 02-04-15] @BOR
             * J'ai décidé de ne plus prendre en compte les accents car leur traitement est trop lourd à ce stade.
             * Aussi, si l'utilisateur rentre "pépé" au lieu de "pepe", il n'atteindra pas sa cible.
             * On se réfère à la ménière dont le pseudo apparait dans l'url
             */
            /*
            array_walk($list_utags,function(&$i,$k){
                $TXH = new TEXTHANDLER();
                $i = strtolower($TXH->remove_accents($i));
            });
            //*/
            //On transforme en LOWERCASE();
            array_walk($list_utags,function(&$i,$k){
                $i = strtolower($i);
            });
            
            //On va supprimer les doublons
            $list_utags = array_unique($list_utags);
            
            //Pour chaque pseudo, nous allons vérifier qu'il s'agir belle et bien d'un pseudo valide
            $PA = new PROD_ACC();
            foreach ($list_utags as $psd) {
//                var_dump(__LINE__,$PA->exists_with_psd($psd,TRUE));
                $utag_tab = $PA->exists_with_psd($psd,TRUE,TRUE);
//                $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$psd,$utag_tab], 'v_d');
                if ( $utag_tab ) {
                    /*
                     * ETAPE :
                     * On lance la procédure de création du tag au niveau de la base de données.
                     * On procède dans un premier temps à l'enregistrement puis à la mise à jour.
                     */
//                     $artid $exists_id["pdaccid"]
                    $now = round(microtime(TRUE)*1000);
                    
                    $QO = new QUERY("qryl4ustgn1");
                    $params = array(
                        /*
                         * [DEPUIS 13-08-16]
                         */
                        ":us_eid"   => $now, 
                        ":tgtuid"   => $utag_tab["pdaccid"], 
                        ":datecrea" => date("Y-m-d G:i:s",($now/1000)), 
                        ":tstamp"   => $now
                    );  
                    $id = $QO->execute($params);
                    //On procède à la mise à jour en insérant l'identifiant externe
                    $QO = new QUERY("qryl4ustgn2");
                    $params = array(
                        ":id"   => $id, 
                        ":eid"  => $this->entity_ieid_encode($now, $id)
                    );  
                    $QO->execute($params);
                        
                    //On insère l'occurrence dans la classe fille dédiée à ARTICLE
                    $QO = new QUERY("qryl4ustg_artn1");
                    $params = array(":id" => $id, ":artid" => $artid);  
                    $QO->execute($params);
                }
            }
        }
        
        //On load l'instance
        return $this->load_entity(["art_eid" => $art_eid]);
    }

    public function on_delete_entity($art_eid, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $art_eid);
        
        /*
         * [NOTE  13-0-14] @author RDL
         * On signale les erreurs si on a pas défini 'artid' et 'cuid' car
         * il s'agit d'une erreur de CONCEPTION GRAVE !!! 
         */
        
        //On vérifie qu'on a bien 'artid'
        if (! ( !empty($art_eid) && is_string($art_eid) ) ) 
        {
            if ( empty($this->art_eid) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $art_eid,'v_d');
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            }
            else {
                $art_eid = $this->art_eid;
            }
        } 
        
        /* OBSELETE : C'est au CALLER de s'en assurer. L'Entity fait ce qu'on lui demande si c'est POSSIBLE et pas tirer par les cheveux !
        //On vérifie qu'on a bien $cuid dans $args
        if (! ( key_exists("cueid", $args) && !empty($args["cueid"]) && is_string($args["cueid"]) ) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
        } else {
            $cuid = $args["cueid"];
        }
        //*/
        
        /*
         * ETAPE :
         *      On vérifie que l'occurrence existe
         */
        $art_tab = $this->exists($art_eid,["NO_STATE_CHECK"]);
        
        if ( $art_tab ) {
            
            /*
             * [DEPUIS 04-07-16]
             *      On vérifie que les éléments essentiels à DEL EXISTENT
             *          -> PHOTO
             *          -> VIDEO
             */
            //Vérification pour IMAGE
            $artpicid = $art_tab["art_pdpicid"];
            $apic_is_deletable = $this->art_bfr_ondelete_aimg($artpicid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $apic_is_deletable) ) {
                return $apic_is_deletable;
            }
            //Vérifications pour VIDEO
            if ( intval($art_tab["art_is_video"]) === 1 ) {
                $avid_is_deletable = $this->art_bfr_ondelete_avid_frmarti($art_tab["artid"]);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $avid_is_deletable) ) {
                    return $avid_is_deletable;
                }
            }
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$apic_is_deletable,$avid_is_deletable]);
//            exit();  
            
            
            /* 
             * On supprime les différents composants de l'Article.
             * Si une erreur survient, on déclenche une erreur. Aussi si tout se passe bien on renverra 1. 
             */
            
            /*
            //Verifier que CU a le droit de supprimer l'Article. Sinon, on revoie un tableau avec le numéro du méssage et le message d'erreur
            $continue = $this->art_ondelete_legitimate($cuid, $std_err_enabled);
            if ( isset($continue) && is_array($copy) ) {
                    return $continue;
            }
            //*/      
            
            /*
             * ETAPE :
             * On supprime les hashtags s'ils existent
             */
            $this->art_ondelete_hastags($art_tab["artid"]);
            

            /*
             * ETAPE :
             *      On supprime les visites s'il en existe
             */
//            $this->art_ondelete_visits ($art_tab["artid"]); //NON IMPLEMENTE A vb1

            /*
             * ETAPE :
             *      On supprime les Evaluations s'il en existe (et les évènements liés)
             */
            $this->art_ondelete_evals($art_tab["artid"]);
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$art_tab);
//            exit();
            
            /*
             * ETAPE :
             *      On supprime les commentaires (y compris les Usertags liés)
             */
            $this->art_ondelete_reacts($art_tab["artid"]);
            
            /*
             * ETAPE :
             *      On supprime les Usertags s'il en existe 
             * RAPPEL : 
             *      - On ne supprime pas Actys et Notifs : Ils doivent être recyclés ET pour éviter de destabiliser le système.
             */
            $this->art_ondelete_ustags($art_tab["artid"]);
            
            
            /*
             * [DEPUIS 05-06-16]
             * ETAPE :
             *      On supprime les occurences de ART_FAV
             */
            $this->art_ondelete_artfavs($art_tab["artid"]);
            
            
            /*
             * [DEPUIS 05-06-16]
             * ETAPE :
             *      On supprime les occurences de ART_REPORT
             * NOTES :
             *      - La fonctionnalité de ART_REPORT n'est pas accessible au [05-06-16]. Mais nous sommes prévoyants !
             */
            $this->art_ondelete_artrprt($art_tab["artid"]);
            
            /*
             * ETAPE :
             *      On supprime l'occurrence de l'image dans la base de données
             */
            $this->art_ondelete_artimg($art_tab["art_pdpicid"], $std_err_enabled);
            
            /*
             * ETAPE :
             *      On supprime l'occurrence de la video (Base de données et Fichier), le cas échéant !
             */
            if ( intval($art_tab["art_is_video"]) === 1 ) {
                $ARTV = new VIDEO_ART();
                $vidtab = $ARTV->exists_from_artid($art_tab["artid"]);
                if ( $vidtab ) {
                    $vid_is_del = $this->art_ondelete_vidimg($vidtab["vidid"], $std_err_enabled);
                    if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $vid_is_del) ) {
                        return $r;
                    }
                }
            }
//            exit();
            
            //[NOTE 08-10-14] @author L.C. Suppression dans VM
            /*
             * [NOTE 04-12-14] @author L.C.
             *      A ce niveau on peut supposer qu'on peut supprimer VM car la probabilité que la suppression de ART échoue est infime.
             *      Avant cette ligne était située plus bas. Elle échouait car l'Article étant déjà suprimée, l'opération qui suivait était faussée.
             * 
             *      Même si la suppression de l'Article VM échouait, ce n'est pas grave, un script (CRON) se chargera de nettoyer la base.
             */
            $this->ondelete_art_vm($art_tab["art_eid"]);
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$art_tab["art_eid"]],'v_d');
            
            /*
             * [NOTE 08-06-15] @BOR
             *      On supprime les occurrences des états
             */
            $this->art_ondelete_statehisty($art_tab["artid"]);

            /*
             * ETAPE :
             *      On supprime l'occurrence de l'Article dans la base de données
             */
            $this->art_ondelete_article ($art_tab["artid"]);
            
            /*
             * ETAPE :
             *      On met à jour le capital du propriétaire de l'Article
             */
            $PA = new PROD_ACC();
            $PA->on_read_entity(["acc_eid" => $art_tab["ueid"]]);
            
            $r = $PA->update_capital();
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                return $r;
            }
            
            //TODO : Vider toutes les propriétés de l'Article au cas où CALLER tente de réutiliser le même objet quand il devrait le supprimer 

            return TRUE;
            
        } else {
            return FALSE;
        }
        
    }

    public function on_read_entity($args) {
        
        $art_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("art_eid", $args) && !empty($args["art_eid"]) ) ) 
        {
            if ( empty($this->art_eid) ) {
                return;
            } else {
                $art_eid = $this->art_eid;
            }
        } else {
            $art_eid = $args["art_eid"];
        }
        
        //On vérifie que l'occurrence existe
        $exists = $this->exists($art_eid); //AME : Fait perdre du temps pour rien !
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $exists,'v_d');
        if ( $exists ) {
                  
            //On Load la classe
            $loads = $this->load_entity($args);
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args,'v_d');
            return $loads;
            
        } else {
            return "__ERR_VOL_ART_GONE";
        }
        
    }

    protected function write_new_in_database($args, $new_row = NULL) {
        /*
        artid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, -> SKIP
        accid INT UNSIGNED NOT NULL, -> OK 3 (God)
        art_eid CHAR(255) NOT NULL, -> SKIP
        art_picid BIGINT UNSIGNED NOT NULL, -> SKIP
        art_desc TEXT (242) NOT NULL, -> OK
        art_pdpic_string_b64 MEDIUMTEXT -> INSERE ULTERIEUREMENT
        art_loc_numip INT UNSIGNED NOT NULL DEFAULT 0 -> OK
        permalien CHAR(255) -> SKIP
        art_creadate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -> SKIP
        art_date_tstamp BIGINT NOT NULL -> OK
        //*/
        
        /*
        $art_infos = NULL;
        $art_infos["artid"] = "-1";
        $art_infos["art_eid"] = "bidon";
        $art_infos["art_prmlk"] = "bidon";
        
        return $art_infos;
        //*/
       
        $time = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4artn1");
        $params = array(
            ":accid"            => $args["accid"], 
            ":art_desc"         => $args["art_desc"], 
            ":art_loc_numip"    => $args["art_locip"], 
            ":art_cdate_tstamp" => $time,
            ":art_is_video"     => $this->art_is_video,
            ":art_is_sod"       => $this->art_is_sod,
            ":art_is_hstd"      => $this->art_is_hstd
        );  
        $artid = $QO->execute($params);     
//        var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,$artid);
//        exit();
        
        //Créer art_eid
        $art_eid = $this->entity_ieid_encode($time, $artid);
        //On crée l'identifiant Permalink
        $art_prmlk = $this->oncreate_EncodePrmlk($art_eid);
//        var_dump("CHECKPOINT => ",__LINE__);
        
        //Insérer art_eid
        $QO = new QUERY("qryl4artn2");
        $params = array(":artid" => $artid, ":art_eid" => $art_eid, ":art_prmlk" => $art_prmlk);  
        $datas = $QO->execute($params);
//        var_dump("CHECKPOINT => ",__LINE__);
        
        
        /*
         * [DEPUIS 14-11-15] @author
         *      On lance l'opération d'entregistrement des UrlInContent.
         *      S'il n'y a pas d'URL, la méthode renvera FALSE.
         */
        $TXH = new TEXTHANDLER();
        if ( $TXH->ExtractURLs($args["art_desc"]) ) {
            $type = ( key_exists("aistr", $args) && $args["aistr"] === TRUE ) ? "UCTP_ART_ITR" : "UCTP_ART_IML";
            $URLIC = new URLIC();
            
            $args_urlic = [
                "t"     => $args["art_desc"],
                "uci"   => $artid,
                "ucei"  => $art_eid,
                "ucp"   => $type,
                "ssid"  => session_id(),
                "locip" => $args["art_locip"],
                "curl"  => NULL,
                "uagnt" => $_SERVER['HTTP_USER_AGENT']
            ];
            $r = $URLIC->URLIC_oncreate($args_urlic["t"], $args_urlic["uci"], $args_urlic["ucei"], $args_urlic["ucp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
        }
        
        
        //Création ART_INFOS
        $art_infos = NULL;
        $art_infos["artid"] = $artid;
        $art_infos["art_eid"] = $art_eid;
        $art_infos["art_prmlk"] = $art_prmlk;
        
        
        return $art_infos;
    }
    
    /****************************************************************************************************/
    /************************************** ARTICLE CLASS SPECIFIC **************************************/
    
    
    /*********** ON_LOAD SCOPE (START) *************/
    private function load_entity_extras_datas ($artid, $art_eid, $k) {
        /*
         * Permet de load les autres données necessaires pour load l'Entity. 
         * La méthode peut servir lorsqu'on a un tableau de extras_keys et qu'on veut les charger les (extras) acquerir les uns après les autres. 
         */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
            
        switch($k) {
            case "art_list_hash" :
                return $this->onload_art_list_hash($artid,$art_eid);
            case "art_list_reacts" :
                return $this->onload_art_list_reacts($artid);
            case "art_list_usertags" :
                return $this->onload_art_list_usertags($artid);
            case "art_list_uic" :
                return $this->onload_art_list_uic($art_eid);
            case "art_eval" :
                return $this->onload_art_eval ($art_eid);
            case "art_vid_url" :
                return $this->onload_art_vid_url ($art_eid,NULL,TRUE);
            default:
                return;
        }
    }
    
    
    private function onload_art_list_hash ($artid,$art_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $list = NULL;
        
        /*
         * [DEPUIS 17-11-15] @author BOR
         *      On récupère les données depuis le nouveau système de gestion HVIEW.
         *      Cela est possible grace au transfert des données HAHSTAG
         * [NOTE]
         *      On utilise la combinaison "artid" "art_eid" pour plus une plus grande fiabilité des données.
         */
        
        $QO = new QUERY("qryl4hviewn14");
        $qparams_in_values = array(
            ":aid"  => $artid,
            ":aeid" => $art_eid
        );  
        $datas = $QO->execute($qparams_in_values);
        
        if ( $datas ) {
            foreach ($datas as $v) {
                $list[] = $v["hic_gvnhsh"];
            }
        }
        
        return $list;
    }
    
    
    private function onload_art_list_reacts ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $reacts = NULL;
        
        //Récupérer la liste des commentaires
//        $QO = new QUERY("qryl4reactn1"); 
        /*
         * [NOTE 29-11-14] @author L.C. 
         * Je suis passé à 6 car certains WKR ont besoin de plus de données. De plus, 6 est un peu comme 1 mais avec des données en plus. Aussi, le risque de "mauvaises répercusions" est faible.
         */
        $QO = new QUERY("qryl4reactn6");
        $params = array(":react_artid" => $artid);
        $reacts = $QO->execute($params);
//        var_dump(__LINE__,__FUNCTION__,[$artid,$reacts]);
        /*
        //SIMULATION
        $time = round(microtime(true) * 1000);
        for( $i=0; $i<10; $i++ ) {
        $reacts [] = [ 
            "rid" => $time+$i,
            "react_msg" => "Un message pour le commentaire $i",
            "react_time" => $time
            ];
        }
        //*/
        /*
         * [DEPUIS 06-09-15] @author L.C.
         *  On vérifie que le compte auteur n'est pas en instance de suppression ou n'est juste pas indisponible
         */
        if ( $reacts ) {
            foreach ($reacts as $k => &$rtab) {
                if ( intval($rtab["atdl"]) !== 0 ) {
                    unset($reacts[$k]);
                }
            }
        }
        
        return $reacts;
    }
    
    
    private function onload_art_list_usertags ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list = NULL;
        //Récupérer la liste des Usertags
        $QO = new QUERY("qryl4ustg_artn2");
        $qparams_in_values = array(":aid" => $artid);  
        $list = $QO->execute($qparams_in_values);
        
        return $list;
    }
    
    
    private function onload_art_list_uic ($aeid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list = NULL;
        /*
         * Récupérer la liste des URLICS
         * [NOTE 17-11-15]
         *      On utilisera TOUJOURS $eid car il est unique dans la table quand id peut porter à confusion et fausser le résultat.
         */
        $QO = new QUERY("qryl4urlic_artn1");
        $qparams_in_values = array(":aeid" => $aeid);  
        $list = $QO->execute($qparams_in_values);
        
        return $list;
    }
    
    
    public function onload_react_list_usertags ($rid) {
        /*
         * [NOTE 11-04-15] @BOR
         * La méthode est passée de private à public pour permettre à d'autres modules de vérifier quels utilisateurs sont tagués pour le commentaire mentionné.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list = NULL;
        //Récupérer la liste des Usertags pour le Commentaire
        $QO = new QUERY("qryl4ustg_rctn2");
        $qparams_in_values = array(":rid" => $rid);  
        $list = $QO->execute($qparams_in_values);
        
        return $list;
    }
    
    
    public function onload_react_list_hashs ($rid,$reid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $list = NULL;
        
        $QO = new QUERY("qryl4hviewn17_AR");
        $qparams_in_values = array(
            ":id"   => $rid,
            ":eid"  => $reid,
        );  
        $datas = $QO->execute($qparams_in_values);
        if ( $datas ) {
            foreach ($datas as $v) {
                $list[] = $v["hic_gvnhsh"];
            }
        }
        
        return $list;
    }
    
    /**
     * Récupère le nombre de Commentaires pour l'Article passé en paramètre.
     * Cette version ne prend en compte que l'identifiant externe.
     * ATTENTION : La méthode ne vérifie pas si l'Article existe. Si l'Article n'existe pas, la méthode renvoie 0.
     * @version 1504.001
     * @param {string} $art_eid L'identifiant externe de l'Article
     * @return {interger} Le nombre de Commentaires
     */
    public function onload_art_rnb ($art_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $art_eid);
        
        $QO = new QUERY("qryl4reactn11");
        $params = array(":art_eid" => $art_eid);  
        $datas = $QO->execute($params);
        
        $rnb = ( $datas ) ? $datas[0]["rnb"] : 0;
        return $rnb;
    }
    
    /**
     * Récupère le nombre de Commentaires pour l'Article passé en paramètre.
     * Cette version ne prend en compte que l'identifiant interne.
     * ATTENTION : La méthode ne vérifie pas si l'Article existe. Si l'Article n'existe pas, la méthode renvoie 0.
     * @version 1504.001
     * @param {string} $art_eid L'identifiant externe de l'Article
     * @return {interger} Le nombre de Commentaires
     */
    public function onload_art_rnb_wid ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        $QO = new QUERY("qryl4reactn13");
//        $QO = new QUERY("qryl4reactn12"); //[NOTE 06-09-15] @author BOR
        $params = array(":artid" => $artid);  
        $datas = $QO->execute($params);
        
        $rnb = ( $datas ) ? $datas[0]["rnb"] : 0;
        return $rnb;
    }
    
    public function onload_art_eval ($art_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $eval = NULL;
        
        //** Récupérer les évaluations pour l'Article **//
        $EV = new EVALUATION();
        $e_p2 = $e_p1 = $e_m1 = $e_tot = NULL;
        
        //Pour _EVAL_SPCL
        $r = $EV->onread_count_eval_bytype_byart($art_eid, "_EVAL_SPCL");
        if ( $r === FALSE || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            //Si erreur ou ERR_VOL. On ne prete que peu d'importance aux erreurs car la probabilié qu'elles arrivent est faible
            $e_p2 = 0;
        } else {
            $e_p2 = $r;
        }
        
        
        //Pour _EVAL_CL
        $r = $EV->onread_count_eval_bytype_byart($art_eid, "_EVAL_CL");
        if ( $r === FALSE || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            //Si erreur ou ERR_VOL. On ne prete que peu d'importance aux erreurs car la probabilié qu'elles arrivent est faible
            $e_p1 = 0;
        } else {
            $e_p1 = $r;
        }
        
        //Pour _EVAL_DLK
        $r = $EV->onread_count_eval_bytype_byart($art_eid, "_EVAL_DLK");
        if ( $r === FALSE || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            //Si erreur ou ERR_VOL. On ne prete que peu d'importance aux erreurs car la probabilié qu'elles arrivent est faible
            $e_m1 = 0;
        } else {
            $e_m1 = $r;
        }
        
        //Pour la valeur de l'Article
        $r = $EV->onread_eval_article_value($art_eid);
        if ( $r === FALSE || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            //Si erreur ou ERR_VOL. On ne prete que peu d'importance aux erreurs car la probabilié qu'elles arrivent est faible
            $e_tot = 0;
        } else {
            /*
             * [NOTE 0-09-14] @author L.C.
             * Pour la vb1, on affiche les valeurs négatives pour éviter mes problèmes entre utilisateurs.
             * Une mise à jour permettra de montrer la valeur négative qu'au proriétaire de l'Article.
             */
            
            //Remettre à 0 permet d'éviter une erreur soit déclenchée plus bas
            $e_tot = ( $r < 0 ) ? 0 : $r;
        }
        
        $eval = [$e_p2,$e_p1,$e_m1,$e_tot]; /* [+2,+1,-1,total]*/
        
        /*
         * On vérifie les données extraites avant de les renvoyer
         */
        
        if ( empty($eval) ) {
            $eval = [0,0,0,0];
        } else {
            foreach ( $eval as $k => $v ) {
                /*
                 * Déclencher une erreur "err_sys_l4evaln1". C'est une erreur de conception important dans le sens où ça ne devrait pas arriver.
                 * TODO : Si on est en mode PROD, on remplace la valeur défectueuse par 0 et on déclenche une erreu silencieuse.
                 */
                if ( !isset($v) || ( intval($v) < 0 ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$k,$v],'v_d');
                    $this->signalError ("err_sys_l4evaln1", __FUNCTION__, __LINE__);
                }
                   
            } 
        }
        
        return $eval;
    }
    
    public function onload_art_vid_datas ($aied, $aid = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $aied);
        
        if (! $aid ) {
            $aid = $this->on_read_get_artid_from_arteid($aied);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $aid) ) {
                return $aid;
            }
        }
        
        $QO = new QUERY("qryl4tvidn8");
        $params = array(
            ":aid"    => $aid
        );  
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : NULL;
    }
    
    public function onload_art_vid_url ($aied, $aid = NULL, $_with_metas = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $aied);
        
        if (! $aid ) {
            $aid = $this->on_read_get_artid_from_arteid($aied);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $aid) ) {
                return $aid;
            }
        }
        
        $QO = new QUERY("qryl4tvidn8");
        $params = array(
            ":aid"    => $aid
        );  
        $datas = $QO->execute($params);
        
        $url = "";
        if ( $datas ) {
            $height = $datas[0]["vid_height"];
            $width = $datas[0]["vid_width"];
            $duration = $datas[0]["vid_duration"];
            $url = ( $_with_metas ) ? $datas[0]["vid_realpath"]."?fmat=".$width."x".$height."&dur=".$duration : $datas[0]["vid_realpath"];
        } 
        
        return $url;
    }
    
    
    /**
     * Permet de récupérer les Articles dans le voisinage de l'Article de référence.
     * NOTICE : La méthode n'exclut pas l'Article de référence dans son processus.
     * 
     * @author Lou Carther <lou.carther@deuslynn-entreprise.com>
     * @since 2015.07.15.vb.01
     * @param mixed (string|integer) $artid
     * @param string $src
     * @param mixed (string|integer) $sprt_ref
     * @param aray $_OPTIONS
     * @return mixed (string|array)
     */
    public function onload_neighbors_from ($artid, $src, $sprt_ref, $lmt = NULL, $_OPTIONS = NULL) {
        /*
         * artid : L'identifiant de l'Article
         * src   : SOURCE_ACC | SOURCE_ACC_IML | SOURCE_TRD
         * 
         * OPTIONS :
         *  FKSA_SAMPLE : Je veux utiliser une méthode d'acquisition lié à FKSA.
         *  VM_ART      : Je veux une définition suivant la méthode VM.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$artid,$src,$sprt_ref]);
        
        if (! in_array($src, ["SOURCE_ACC","SOURCE_ACC_NOT_IML","SOURCE_TRD"]) ) {
            return "__ERR_VOL_MSM";
        }
        
        /*
         * On vérifie l'existence L'Article
         */
        $aextb = $this->exists_with_id($artid, TRUE);
        if (! $aextb ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        /*
         * [DEPUIS 18-06-16]
         */
        if ( $lmt && is_int($lmt) && $lmt > 0 ) {
            $limit = $lmt;
        } else {
            $limit = ( $_OPTIONS && in_array("FKSA_SAMPLE",$_OPTIONS) ) ? 9 : 2; 
        }
        
        
        /*
         * TODO : Vérifier l'exister de la référence (ACCOUNT ou TREND)
         */
        
        $sql; $limit; $articles_ids;
        
        $cdate = $aextb["art_cdate_tstamp"];
        
//        var_dump(__LINE__,__FILE__,$cdate,$limit);

        //On récupère les Articles IML
        if ( in_array($src,["SOURCE_ACC"]) ) {
            
            $QO = new QUERY("qryl4artn22");
            $params = array(
                ":sprt_ref1"    => $sprt_ref,
                ":ref_date1"    => $cdate,
                ":sprt_ref2"    => $sprt_ref,
                ":ref_date2"    => $cdate,
                ":limit1"       => $limit,
                ":limit2"       => $limit  
            );  
            $articles_ids = $QO->execute($params);
        
        } else if ( in_array($src,["SOURCE_ACC_NOT_IML"]) ) {
            
//            $QO = new QUERY("qryl4artn23");
            $QO = new QUERY("qryl4artn23_neovb30_0416001");
            $params = array(
                ":sprt_ref1"    => $sprt_ref,
                ":ref_date1"    => $cdate,
                ":sprt_ref2"    => $sprt_ref,
                ":ref_date2"    => $cdate,
                ":limit1"       => $limit,
                ":limit2"       => $limit  
            );  
            $articles_ids = $QO->execute($params);
        
        } else if ( in_array($src,["SOURCE_TRD"]) ) {
            $QO = new QUERY("qryl4artn24");
            $params = array(
                ":sprt_ref1"    => $sprt_ref,
                ":ref_date1"    => $cdate,
                ":sprt_ref2"    => $sprt_ref,
                ":ref_date2"    => $cdate,
                ":limit1"       => $limit,
                ":limit2"       => $limit  
            );  
            $articles_ids = $QO->execute($params);
        }
//        var_dump(__LINE__,__FILE__,$sql);
//        var_dump(__LINE__,__FILE__,$articles_ids);
//        exit();
        
        $articles_iml; $articles_itr;
        if ( $articles_ids ) {
            foreach ($articles_ids as $ids) {
                $artid = $ids["artid"];
                $art_eid = $ids["art_eid"];
                if ( $this->onread_is_trend_version($artid) ) {
                    $ART_TR = new ARTICLE_TR();
                    if ( in_array("VM_ART",$_OPTIONS) ) {
                        $r__ = $ART_TR->onread_archive_itr(["art_eid" => $art_eid]);
//                        var_dump(__LINE__,__FILE__,$r__);
                        if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                            continue;
                        }
                        $load_itr = $r__;
//                        $load_itr = $ART_TR->onread_archive_itr(["art_eid" => $art_eid]);
                    } else {
                        $r__ = $ART_TR->on_read(["art_eid" => $art_eid]);
                        if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                            continue;
                        }
                        $load_itr = $r__;
//                        $load_itr = $ART_TR->on_read(["art_eid" => $art_eid]);
                    }

                    $articles_itr[$artid] = $load_itr;
                } else {
                    $ART = new ARTICLE();
                    if ( in_array("VM_ART",$_OPTIONS) ) {
                        $r__ = $ART->onread_archive_iml(["art_eid" => $art_eid]);
//                        var_dump(__LINE__,__FILE__,$r__);
                        if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                            continue;
                        }
                        $load_iml = $r__;
//                        $load_iml = $ART->onread_archive_iml(["art_eid" => $art_eid]);
                    } else {
                        $r__ = $ART->on_read_entity(["art_eid" => $art_eid]);
                        if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                            continue;
                        }
                        $load_iml = $r__;
//                        $load_iml = $ART->on_read_entity(["art_eid" => $art_eid]);
                    }

                    $articles_iml[$artid] = $load_iml;
                }
            }
        }

//        var_dump(__LINE__,__FILE__,[$articles_iml,$articles_itr]);
//        exit();
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$articles_iml,$articles_itr],'v_d');
//        exit();
        $stack = [
            "iml" => $articles_iml,
            "itr" => $articles_itr
        ];
            
        return $stack;
    } 
    
    /*********** ON_READ SCOPE (START) *************/
    
    public function on_read_get_artid_from_arteid ($art_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4artn8");
        $params = array( ':art_eid' => $art_eid);
        $datas = $QO->execute($params);
        
        return (! $datas ) ? "__ERR_VOL_ART_GONE" : $datas[0]['artid'];
    }
    
    public function on_read_get_arteid_from_artid ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4artn9");
        $params = array( ':artid' => $artid);
        $datas = $QO->execute($params);
        
        return (! $datas ) ? "__ERR_VOL_ART_GONE" : $datas[0]['art_eid'];
    }
    
    /*********** ON_CREATE SCOPE (START) *************/
       
    private function on_create_treat_desc ( $art_desc, &$kws = NULL, &$usertags = NULL ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$art_desc]);
        
        $TH = new TEXTHANDLER();
        
        //On vérifie la close $this->art_hash_or_desc
        if ( $this->art_hash_or_desc && $art_desc == "" ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $art_desc,'v_d');
            $this->signalError ("err_user_l4artn1", __FUNCTION__, __LINE__,TRUE);
        }
        
        /*
         * [NOTE 01-04-15] @BlackOwlRobot
         * On vérifie sa longueur en prennant de ne pas compter les '#' de mot-clé et '@' pour les tags d'utilisateurs
         */
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$art_desc,strlen($art_desc)],'v_d');
//        exit();
        $len = $TH->strlen_ship_tagsmarks($art_desc,['#','@']);
        if ( !$len | $len > $this->_MAX_LENGTH_DESC ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$art_desc," LENGHT => ",$len],'v_d');
            $this->signalError ("err_user_l4artn1", __FUNCTION__, __LINE__,TRUE);
        }
        
       /*     
        //Vérifie sa longueur en prennant de ne pas compter les '#' de mot-clé
        if ( $TH->strlen_skip_hashtags($art_desc) > $this->_MAX_LENGTH_DESC ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $art_desc,'v_d');
            $this->signalError ("err_user_l4artn1", __FUNCTION__, __LINE__,TRUE);
        }
        //*/
        
        //On extrait les hashstags si le texte en comporte
        $kws = $TH->extract_prod_keywords($art_desc);
        //On extrait les usertags si le texte en comporte
        $usertags = $TH->extract_tqr_usertags($art_desc);
//        
//        var_dump(preg_match("#\\\\n#", $art_desc));
//        exit();
        //On échappe le texte
        $new_art_desc = $TH->secure_text($art_desc);
        
        
        /*
         * [DEPUIS 05-02-16]
         *      On convertit les éventuels EMOJIS en une correspondance HTML.
         */
        $new_art_desc = $TH->replace_emojis_in($new_art_desc);
        
//        var_dump($art_desc, html_entity_decode($art_desc), $new_art_desc);
//        exit();
        //On parse avec addslaches notamment pour les '\n'
//        $art_desc = addcslashes($art_desc, "\0..\37!@\177..\377 \\"); //Inspiration : php.net
//        $art_desc = addcslashes($art_desc, "\\");
        //*/
        return $new_art_desc;
    }
    
    private function on_create_check_pic ($imstr, &$file) {
        //Traite l'image en entrée
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $imstr);
        
        /*
         * Données dont on a besoin
         * MAX_SIZE     : Le poids maximum autorisé pour l'Image
         * MAX_WIDTH    : La Largeur maximale acceptée pour une Image
         * MAX_HEIGHT   : La Hauteur maximale acceptée pour une Image
         * MIN_WIDTH    : La Largeur minimale acceptée pour une image
         * MIN_HEIGHT   : La Hauteur minimale acceptée pour une Image
         * ALLOW_EXT    : Un tableau des extensions admises pour une Image
         * 
         * Toutes ces données sont stockées dans IMAGE_ART
         */
        $pdpic_infos = NULL;
        
        $pdpic_string = ( $file && $file["data"] ) ? $file["data"] : $imstr;
        
//        var_dump($pdpic_string);
//        exit();
        
        $ART = new IMAGE_ART();
        
        /*
         * [DEPUIS 18-08-16]
         *      Changer l'angle d'orientation de l'IMAGE.
         */
        $rgx = "#^(?:data:(image)\/([a-zA-Z\d]*);base64),([\s\S]+)#";
        if ( preg_match($rgx, $pdpic_string, $m) && $file && $file["options"]["orien"]["ang"] ) {
            $rot_ang = $file["options"]["orien"]["ang"];

//            var_dump($rot_ang);
//            var_dump($m,base64_decode($m[3]));
//            exit();

            $im_src_str = base64_decode($m[3]);
            $im_src = imagecreatefromstring($im_src_str);

            $im_rot = imagerotate($im_src,$rot_ang,0);

            // start buffering
            ob_start();
            imagepng($im_rot);
            $contents =  ob_get_contents();
            ob_end_clean();
//            echo "<img src='data:image/jpeg;base64,".base64_encode($contents)."' />";
            
            $im_rot_str = "data:image/$m[2];base64,".base64_encode($contents);
            
//            var_dump($pdpic_string,$im_rot_str);
            
            $pdpic_string = $im_rot_str;
            
            $file["data"] = $pdpic_string;

            imagedestroy($im_src);
            imagedestroy($im_rot);
        }
//        exit();
        
        $ART->valid($pdpic_string, $pdpic_infos);
        
//        var_dump($pdpic_infos);
//        exit();
        
        return $pdpic_infos;
    }
    
    private function on_create_append_image_infos ($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //["picid","pdpic_artid","pdpic_eid","pdpic_creadate","pdpic_realpath","pdpic_string_b64"]
        
        $QO = new QUERY("qryl4artn7");
        $params = array(
            ":artid"        => $args["artid"], 
            ":art_picid"    => $args["picid"], 
            ":art_pdpic_realpath" => $args["pdpic_realpath"] 
        );  
        $QO->execute($params);
        
    }
    
    /*********** ARCHIVE ***********/
    
    public function oncreate_archive_iml ($args) {
        /*
         * Permet de créer une occurrence d'un Article IML dans la table VM.
         * Cela permettra d'améliorer les traitements sur les Articles.
         * 
         * RAPPEL : Cette table ne sert qu'à de la lecture
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //Liste des données attendues, référencées par key
        $XPTD = [
            "artid",
            "art_eid",
            "art_picid",
            "art_pic_rpath",
            "art_desc",
            "art_crea_tstamp",
            "art_locip",
            "art_is_video",
            "art_vid_url",
            "art_is_sod",
            "art_is_hstd",
            "art_hashs",
            "art_rnb",
            "art_evals",
            "art_tot",
            "art_oid",
            "art_ogid",
            "art_oeid",
            "art_opsd",
            "art_ofn",
            "art_oppicid",
            "art_oppic_rpath",
            "art_todel"
        ];
        
        /* On vérifie que les données sont attendues et qu'elles sont non-vides */
        $com = array_intersect($XPTD, array_keys($args));
        
        if ( count($com) !== count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD], 'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args], 'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            $X = ["art_hashs","art_vid_url"];
            foreach ($args as $k => $v) {
                if ( !( isset($v) && $v !== "" ) && !in_array($k, $X) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        $QO = new QUERY("qryl4artwm_imln1");
        $params = array(
            ":artid"            => $args["artid"],
            ":art_eid"          => $args["art_eid"],
            ":art_picid"        => $args["art_picid"],
            ":art_pic_rpath"    => $args["art_pic_rpath"],
            //[NOTE 10-10-14] @author L.C. Permet d'éviter qu'à la sortie on ait un système de triple encode, ce qui corromp les données
            ":art_desc"         => html_entity_decode($args["art_desc"]),
            ":art_crea_tstamp"  => $args["art_crea_tstamp"],
            ":art_locip"        => $args["art_locip"],
            /*
             * [DEPUIS 28-03-16]
             */
            ":art_is_video"     => $args["art_is_video"],
            ":art_vid_url"      => $args["art_vid_url"],
            ":art_is_sod"       => $args["art_is_sod"],
            /*
             * [DEPUIS 17-07-16]
             */
            ":art_is_hstd"      => $args["art_is_hstd"],
            //Mots-clés
            ":art_hashs"        => $args["art_hashs"],
            //Nombre de commentaires
            ":art_rnb"          => $args["art_rnb"],
            //Données sur les Evaluations liées à l'Article
            ":art_evals"        => $args["art_evals"],
            ":art_tot"          => $args["art_tot"],
            //Données sur le propriétaire
            ":art_oid"          => $args["art_oid"],
            ":art_ogid"         => $args["art_ogid"],
            ":art_oeid"         => $args["art_oeid"],
            ":art_opsd"         => $args["art_opsd"],
            ":art_ofn"          => $args["art_ofn"],
            ":art_oppicid"      => $args["art_oppicid"],
            ":art_oppic_rpath"  => $args["art_oppic_rpath"],
            ":art_todel"        => $args["art_todel"]
        );  
//        var_dump("LINE => ",__LINE__,"; DATAS => ",$params);
//        exit();
        $i__ = $QO->execute($params);
//        var_dump("LINE => ",__LINE__,"; DATAS => ",$i__);
        
        return TRUE;
    }
    
    
    public function onread_archive_iml ($args) {
        /*
         * [AJOUTE 28-04-15] @BOR
         * 
         * Permet de récupérer une occurrence d'un Article IML dans la table VM.
         * Cela permettra d'améliorer les traitements sur les Articles.
         * 
         * RAPPEL : Cette table ne sert qu'à de la lecture
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args(),TRUE);
       
        $XPTD = ["artid","art_eid"];
        $com = array_intersect(array_keys($args),$XPTD);
        
        if (! ( $com && is_array($args) && count($args) === 1 && !empty($args[$com[0]]) ) ) {
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
//            $this->signalError ("...", __FUNCTION__, __LINE__);
            return;
        } 
        
        if ( isset($args["art_eid"]) ) {
            $QO = new QUERY("qryl4artwm_imln2");
            $params = array(":art_eid" => $args["art_eid"]);
        } else {
            $QO = new QUERY("qryl4artwm_imln3");
            $params = array(":artid" => $args["artid"]);
        }
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            $a_tab = $datas[0];
                    
            $article = [
                /* DONNEES SUR ARTICLE */
                "artid"             => $a_tab["artid"],
                "art_eid"           => $a_tab["art_eid"],
                "art_picid"         => $a_tab["art_picid"],
                "art_pdpic_path"    => $a_tab["art_pic_rpath"], 
                "art_locip"         => $a_tab["art_locip"],
//                "art_desc"          => $a_tab["art_desc"], 
               /*
                * [DEPUIS 28-03-16]
                */
               "art_is_video"      => $a_tab["art_is_video"],
               "art_vid_url"       => $a_tab["art_vid_url"],
               "art_is_sod"        => $a_tab["art_is_sod"],
               /*
                * [DEPUIS 17-07-16]
                */
               "art_is_hstd"       => $a_tab["art_is_hstd"],
                /*
                 * [TEMP ESSAI 28-04-15]
                 * [CONFIRME 29-04-15]
                 */
                "art_desc"          => htmlentities($a_tab["art_desc"]), 
                "art_creadate"      => $a_tab["art_crea_tstamp"],
                "art_pdpic_string"  => "",
                "art_state"         => $a_tab["art_state"],
                /* DONNEES SUR LE PROPRIETAIRE */
                "art_oid"           => $a_tab["art_oid"],
                "art_oeid"          => $a_tab["art_oeid"],
                "art_ofn"           => $a_tab["art_ofn"],
                "art_opsd"          => $a_tab["art_opsd"],
                "art_ohref"         => "/".$a_tab["art_opsd"],
                "art_oppic"         => $a_tab["art_oppic_rpath"],
                "art_list_hash"     => explode(",", $a_tab["art_hashs"]),
                "art_list_reacts"   => NULL,
                /*
                 * [NOTE 11-09-15] @auhtor BOR
                 *  J'ai voulu mettre en place un code pour que les données soient à jour mais la performance en prend un gros coup.
                 *  J'ai laissé tombé.
                 *  Quand l'utilisateur va lire l'Article via ARP ou UNQ les données seront mises à jour.
                 *  De plus, Reaper va mettre à jour les Articles ce qui va réduire les risques de voir les données être fausses pendant plus d'une journée.
                 */
//                "art_eval"          => $this->onload_art_eval($a_tab["art_eid"]),
                "art_eval"          => explode(",", $a_tab["art_evals"]),
                "art_rnb"           => $a_tab["art_rnb"]
            ];
            
        } else {
            return "__ERR_VOL_ART_GONE";
        }
        
        if ( !empty($a_tab["art_state"]) && is_numeric($a_tab["art_state"]) && !in_array(intval($a_tab["art_state"]),[1,6]) ) {
//        if ( !empty($a_tab["art_state"]) && is_numeric($a_tab["art_state"]) && intval($a_tab["art_state"]) !== 6 ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        return $article;
    }
    
    public function oncreate_archive_itr ($args) {
        /*
         * Permet de créer une occurrence d'un Article IML dans la table VM.
         * Cela permettra d'améliorer les traitements sur les Articles.
         * 
         * RAPPEL : Cette table ne sert qu'à de la lecture
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //Liste des données attendues, référencées par key
        $XPTD = [
            "artid",
            "art_eid",
            "art_picid",
            "art_pic_rpath",
            "art_desc",
            "art_crea_tstamp",
            "art_locip",
            /*
             * [DEPUIS 28-03-16]
             */
            "art_is_video",
            "art_vid_url",
            "art_trid",
            "art_trd_eid",
            "art_trd_title",
            "art_trd_desc",
            "art_trd_title_href",
            "art_trd_catgid",
            "art_trd_is_public",
            "art_trd_grat",
            "art_trd_date_tstamp",
            "art_hashs",
            "art_rnb",
            "art_evals",
            "art_tot",
            "art_oid",
            "art_ogid",
            "art_oeid",
            "art_opsd",
            "art_ofn",
            "art_oppicid",
            "art_oppic_rpath",
            "art_todel"
            ];
        
        /* On vérifie que les données sont attendues et qu'elles sont non-vides */
        $com = array_intersect($XPTD, array_keys($args));
        
        if ( count($com) !== count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD], 'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args], 'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            $X = ["art_hashs","art_vid_url"];
            foreach ($args as $k => $v) {
                if ( !(isset($v) && $v !== "") && !in_array($k, $X) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        $QO = new QUERY("qryl4artwm_itrn1");
        $params = array(
            ":artid"                => $args["artid"],
            ":art_eid"              => $args["art_eid"],
            ":art_picid"            => $args["art_picid"],
            ":art_pic_rpath"        => $args["art_pic_rpath"],
            //[NOTE 10-10-14] @author L.C. Permet d'éviter qu'à la sortie on ait un système de triple encode, ce qui corrompt les données
            ":art_desc"             => html_entity_decode($args["art_desc"]),
            ":art_crea_tstamp"      => $args["art_crea_tstamp"],
            ":art_locip"            => $args["art_locip"],
            /*
             * [DEPUIS 28-03-16]
             */
            ":art_is_video"         => $args["art_is_video"],
            ":art_vid_url"          => $args["art_vid_url"],
            ":art_trid"             => $args["art_trid"],
            ":art_trd_eid"          => $args["art_trd_eid"],
            ":art_trd_title"        => $args["art_trd_title"],
            ":art_trd_desc"         => $args["art_trd_desc"],
            ":art_trd_title_href"   => $args["art_trd_title_href"],
            ":art_trd_catgid"       => $args["art_trd_catgid"],
            ":art_trd_is_public"    => $args["art_trd_is_public"],
            ":art_trd_grat"         => $args["art_trd_grat"],
            ":art_trd_date_tstamp"  => $args["art_trd_date_tstamp"],
            //Mots-clés
            ":art_hashs"            => $args["art_hashs"],
            //Nombre de commentaires
            ":art_rnb"              => $args["art_rnb"],
            //Données sur les Evaluations liées à l'Article
            ":art_evals"            => $args["art_evals"],
            ":art_tot"              => $args["art_tot"],
            //Données sur le propriétaire
            ":art_oid"              => $args["art_oid"],
            ":art_ogid"             => $args["art_ogid"],
            ":art_oeid"             => $args["art_oeid"],
            ":art_opsd"             => $args["art_opsd"],
            ":art_ofn"              => $args["art_ofn"],
            ":art_oppicid"          => $args["art_oppicid"],
            ":art_oppic_rpath"      => $args["art_oppic_rpath"],
            ":art_todel"            => $args["art_todel"]
        );  
        $QO->execute($params);
        
        return TRUE;
    }
    
    public function onread_archive_itr ($args) {
        /*
         * Permet de récupérer une occurrence d'un Article IML dans la table VM.
         * Cela permettra d'améliorer les traitements sur les Articles.
         * 
         * RAPPEL : Cette table ne sert qu'à de la lecture
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args(),TRUE);
        
        $XPTD = ["artid","art_eid"];
        $com = array_intersect(array_keys($args),$XPTD);
        
        if (! ( $com && is_array($args) && count($args) === 1 && !empty($args[$com[0]]) ) ) {
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
//            $this->signalError ("...", __FUNCTION__, __LINE__);
            return;
        } 
        
        if ( isset($args["art_eid"]) ) {
            $QO = new QUERY("qryl4artwm_itrn2");
            $params = array(":art_eid" => $args["art_eid"]);
        } else {
            $QO = new QUERY("qryl4artwm_itrn3");
            $params = array(":artid" => $args["artid"]);
        }
        $datas = $QO->execute($params);
        
        /*
         * [DEPUIS 29-07-15] @BOR
         * On vérifie que les données sont disponibles
         */
        if (! $datas ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        /*
         * [DEPUIS 02-05-15] @BOR
         * On récupère les données à jour depuis la Tendance.
         */
        $TRD = new TREND();
        $tr_infos = $TRD->trend_get_trend_infos($datas[0]["art_trd_eid"]);
        if (! $tr_infos) {
            return "__ERR_VOL_TRD_GONE";
        }
        $title = $tr_infos["trd_title"];
        $tle_hrf = $tr_infos["trd_title_href"];
        
        if ( $datas ) {
            $a_tab = $datas[0];
                    
            $article = [
                /* DONNEES SUR ARTICLE */
                "artid"             => $a_tab["artid"],
                "art_eid"           => $a_tab["art_eid"],
                "art_picid"         => $a_tab["art_picid"],
                "art_pdpic_path"    => $a_tab["art_pic_rpath"], 
                "art_locip"         => $a_tab["art_locip"],
//                "art_desc"          => $a_tab["art_desc"], 
                "art_desc"          => htmlentities($a_tab["art_desc"]), //[TEMP ESSAI 28-04-15]
                "art_creadate"      => $a_tab["art_crea_tstamp"],
                "art_pdpic_string"  => "",
                "art_state"         => $a_tab["art_state"],
               /*
                * [DEPUIS 28-03-16]
                */
                "art_is_video"      => $a_tab["art_is_video"],
                "art_vid_url"       => $a_tab["art_vid_url"],
                /* DONNEES SUR LE PROPRIETAIRE */
                "art_oid"           => $a_tab["art_oid"],
                "art_oeid"          => $a_tab["art_oeid"],
                "art_ofn"           => $a_tab["art_ofn"],
                "art_opsd"          => $a_tab["art_opsd"],
                "art_ohref"         => "/".$a_tab["art_opsd"],
                "art_oppic"         => $a_tab["art_oppic_rpath"],
                "art_list_hash"     => explode(",", $a_tab["art_hashs"]),
                "art_list_reacts"   => NULL,
                /*
                 * [NOTE 11-09-15] @auhtor BOR
                 *  J'ai voulu mettre en place un code pour que les données soient à jour mais la performance en prend un gros coup.
                 *  J'ai laissé tombé.
                 *  Quand l'utilisateur va lire l'Article via ARP ou UNQ les données seront mises à jour.
                 *  De plus, Reaper va mettre à jour les Articles ce qui va réduire les risques de voir les données être fausses pendant plus d'une journée.
                 */
//                "art_eval"          => $this->onload_art_eval ($a_tab["art_eid"]),
                "art_eval"          => explode(",", $a_tab["art_evals"]),
                "art_rnb"           => $a_tab["art_rnb"], 
                /* DONNEES SUR LA TENDANCE */
                "trid"              => $a_tab["art_trid"],
                "trd_eid"           => $a_tab["art_trd_eid"],
                /*
                 * [DEPUIS 02-05-15] @BOR
                 * On récupère les données mise à jour depuis la Tendance originale.
                 * Cela nous permet de garder les données à jour et de ne pas compter uniquement sur CRON (s'il est implémenté)
                 */
                "trd_title"         => $title,
                "trd_title_href"    => $tle_hrf
//                "trd_title"         => $a_tab["art_trd_title"],
//                "trd_title_href"    => $a_tab["art_trd_title_href"]
            ];
            
        } else {
            return "__ERR_VOL_ART_GONE";
        }
        
        if ( !empty($a_tab["art_state"]) && is_numeric($a_tab["art_state"]) && !in_array(intval($a_tab["art_state"]),[1,6]) ) {
//        if ( !empty($a_tab["art_state"]) && is_numeric($a_tab["art_state"]) && intval($a_tab["art_state"]) !== 6 ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        return $article;
        
    }
    
    
    private function oncreate_EncodePrmlk ($aeid) {
        /*
         * Permet de génrer un identifiant alétoire unique à partir de l'identifiant externe de l'Article.
         * Grace à l'unicité de l'identifiant externe, on peut s'assurer que PerMalink restera unique.
         * Pour s'assurer de l'unicité de cet identifiant, on teste s'il n'est pas déjà présent dans la base de données.
         * Dans ce dernier cas, on réessait de créer un autre identifiant.
         * 
         * [21-02-2015] @Loukz
         * La vérification est assurée par CALLER
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $aeid);
        
        if (! is_string($aeid) ) {
            return;
        }
        /*
         * [NOTE 21-02-2015] @Louks
         *      On ne prend que les majuscule pour s'assurer qu'il y en ait dans la chaine.
         *      L'idenfiant externe ne contient que des minuscules en ce qui concerne les lettres.
         */
        $salt = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_";
        $aeid = str_split($aeid);
        $aeid = array_reverse($aeid);
        
        $p = "";
        foreach ($aeid as $k => $ch) {
            $p .= ( $k == 0) ? $ch : str_split($salt)[mt_rand(0,37)].$ch;
        }
        
        return $p;
    }

    /***************************************************************** ON_ALTER **********************************************************************/
    
    public function onalter_selfupdate_vm ($art_eid) {
        /*
         * Permet de mettre à jour l'occurrence dans une table VM lorsqu'on ne connait pas exactement sa nature (ITR ou TML)
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( $this->onread_is_trend_version_eid($art_eid) ) {
            $r = $this->onalter_selfupdate_vm_itr($art_eid);
            return $r;
        } else {
            $r =  $this->onalter_selfupdate_vm_iml($art_eid);
            return $r;
        }
    }
    
    public function onalter_selfupdate_vm_iml ($art_eid) {
        /*
         * [NOTE 08-10-14] @author L.C. 
         * Permet de mettre à jour toutes les colonnes d'une occurrence Article_VM de type IML.
         * On met à jour tous les éléments car c'est la manière la plus fiable et simple mais peut être pas la plus performante.
         * Cependant, c'est un moyen sur et fiable qui permet d'être appelé partout et par tous.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $ART = new ARTICLE();
        $a_tab = $ART->on_read_entity(["art_eid"=>$art_eid]);
        
//        var_dump(__LINE__,$a_tab);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $a_tab) ) {
            return $a_tab;
        }
        
        //On vérifie aussi si l'Article existe réellement dans la table VM
        $QO = new QUERY("qryl4artwm_imln2");
        $params = array(":art_eid" => $art_eid);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
        
            $QO = new QUERY("qryl4artwm_imln4");
            $params = array(
                ":artid1"           => $a_tab["artid"],
                ":artid2"           => $a_tab["artid"],
                ":art_eid"          => $a_tab["art_eid"],
                ":art_picid"        => $a_tab["art_picid"],
                ":art_pic_rpath"    => $a_tab["art_pdpic_path"],
                /*
                 * [NOTE 29-04-15] @BOR
                 * Les données provenant d'un READ, elles sont corrompues par le fait qu'on utilise htmlentities() pour desc.
                 * A chaque manipulaition, les données qui seront mis à jour seront corrompues au niveau de la BDD.
                 * Pour les réparer, il faut modifier manuellement l'Article. Quand la base en mode test cela est possible. 
                 * Cependant, en mode production la tâche devient plus délicate.
                 */
                ":art_desc"             => html_entity_decode($a_tab["art_desc"]),
//                ":art_desc"             => $a_tab["art_desc"],
                ":art_crea_tstamp"  => $a_tab["art_creadate"],
                ":art_locip"        => $a_tab["art_locip"],
                //Mots-clés
                ":art_hashs"        => ( isset($a_tab["art_list_hash"]) && is_array($a_tab["art_list_hash"]) && count($a_tab["art_list_hash"]) ) ? implode(",",$a_tab["art_list_hash"]) : "",
    //            //Nombre de commentaires
                ":art_rnb"          => ( isset($a_tab["art_rnb"]) ) ? $a_tab["art_rnb"] : 0,
    //            //Données sur les Evaluations liées à l'Article
                ":art_evals"        => ( isset($a_tab["art_eval"]) && is_array($a_tab["art_eval"]) && count($a_tab["art_eval"]) ) ? implode(",",$a_tab["art_eval"]) : "0,0,0,0",
                ":art_tot"          => ( isset($a_tab["art_eval"]) && is_array($a_tab["art_eval"]) && count($a_tab["art_eval"]) ) ? $a_tab["art_eval"][3] : 0,
    //            //Données sur le propriétaire
                ":art_oid"          => $a_tab["art_oid"],
                ":art_ogid"         => $a_tab["art_ogid"],
                ":art_oeid"         => $a_tab["art_oeid"],
                ":art_opsd"         => $a_tab["art_opsd"],
                ":art_ofn"          => $a_tab["art_ofn"],
                ":art_oppicid"      => $a_tab["art_oppicid"],
                ":art_oppic_rpath"  => $a_tab["art_oppic"],
                ":art_todel" => 0 //On met 0 car si les données proviennent d'un read de Article, le propriétaire est très surement pas en 1.
            );  
            $QO->execute($params);
        
            return true;
            
        } else {
            //TODO : Créer l'occurrence dans la table VM
            return 0;
        }
        
    }
    
    public function onalter_selfupdate_vm_itr ($art_eid) {
        /*
         * [NOTE 08-10-14] @author L.C. 
         * Permet de mettre à jour toutes les colonnes d'une occurrence Article_VM de type ITR.
         * On met à jour tous les éléments car c'est la manière la plus fiable et simple mais peut être pas la plus performante.
         * Cependant, c'est un moyen sur et fiable qui permet d'être appelé partout et par tous.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $ART = new ARTICLE_TR();
        $a_tab = $ART->on_read(["art_eid"=>$art_eid]);
        
//        var_dump(__LINE__,$a_tab);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $a_tab) ) {
            return $a_tab;
        }
        
        //On vérifie aussi si l'Article existe réellement dans la table VM
        $QO = new QUERY("qryl4artwm_itrn2");
        $params = array(":art_eid" => $art_eid);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
//        if ( true ) {
        
            $QO = new QUERY("qryl4artwm_itrn4");
            $params = array(
                ":artid1"               => $a_tab["artid"],
                ":artid2"               => $a_tab["artid"],
                ":art_eid"              => $a_tab["art_eid"],
                ":art_picid"            => $a_tab["art_picid"],
                ":art_pic_rpath"        => $a_tab["art_pdpic_path"],
                /*
                 * [NOTE 29-04-15] @BOR
                 * Les données provenant d'un READ, elles sont corrompues par le fait qu'on utilise htmlentities() pour desc.
                 * A chaque manipulaition, les données qui seront mis à jour seront corrompues au niveau de la BDD.
                 * Pour les réparer, il faut modifier manuellement l'Article. Quand la base en mode test cela est possible. 
                 * Cependant, en mode production la tâche devient plus délicate.
                 */
                ":art_desc"             => html_entity_decode($a_tab["art_desc"]),
//                ":art_desc"             => $a_tab["art_desc"],
                ":art_crea_tstamp"      => $a_tab["art_creadate"],
                ":art_locip"            => $a_tab["art_locip"],
                ":art_trid"             => $a_tab["trid"],
                ":art_trd_eid"          => $a_tab["trd_eid"],
                /*
                 * [NOTE 29-04-15] @BOR
                 * Les données provenant d'un READ_TR, elles sont corrompues par le fait qu'on utilise htmlentities() pour le titre de la Tendance.
                 * A chaque manipulaition, les données qui seront mis à jour seront corrompues au niveau de la BDD.
                 * Pour les réparer, il faut modifier manuellement l'Article. Quand la base en mode test cela est possible. 
                 * Cependant, en mode production la tâche devient plus délicate.
                 */
                ":art_trd_title"        => html_entity_decode($a_tab["trd_title"]),
                ":art_trd_desc"         => $a_tab["trd_desc"],
                ":art_trd_title_href"   => $a_tab["trd_title_href"],
                ":art_trd_catgid"       => $a_tab["trd_catgid"],
                ":art_trd_is_public"    => $a_tab["trd_is_public"],
                ":art_trd_grat"         => $a_tab["trd_grat"],
                ":art_trd_date_tstamp"  => $a_tab["trd_date_tstamp"],
                //Mots-clés
                ":art_hashs"            => ( isset($a_tab["art_list_hash"]) && is_array($a_tab["art_list_hash"]) && count($a_tab["art_list_hash"]) ) ? implode(",",$a_tab["art_list_hash"]) : "",
                //Nombre de commentaires
                ":art_rnb"              => ( isset($a_tab["art_rnb"]) ) ? $a_tab["art_rnb"] : 0,
                /* Données sur les Evaluations liées à l'Article */
                ":art_evals"            => ( isset($a_tab["art_eval"]) && is_array($a_tab["art_eval"]) && count($a_tab["art_eval"]) ) ? implode(",",$a_tab["art_eval"]) : "0,0,0,0",
                ":art_tot"              => ( isset($a_tab["art_eval"]) && is_array($a_tab["art_eval"]) && count($a_tab["art_eval"]) ) ? $a_tab["art_eval"][3] : 0,
                /* Données sur le propriétaire */
                ":art_oid"              => $a_tab["art_oid"],
                ":art_ogid"             => $a_tab["art_ogid"],
                ":art_oeid"             => $a_tab["art_oeid"],
                ":art_opsd"             => $a_tab["art_opsd"],
                ":art_ofn"              => $a_tab["art_ofn"],
                ":art_oppicid"          => $a_tab["art_oppicid"],
                ":art_oppic_rpath"      => $a_tab["art_oppic"],
                ":art_todel"            => 0 //On met 0 car si les données proviennent d'un read de Article, le propriétaire n'est très surement pas en 1.
            );  
            $QO->execute($params);
        
            return true;
            
        } else {
            //TODO : Créer l'occurrence dans la table VM
            return 0;
        }
    }
    
    /**
     * Permet de changer proprement l'etat d'un Article.
     * 
     * La méthode controle auss
     * @param string $aeid
     * @param int $state
     * @return mixed [string|boolean]
     */
    public function onalter_change_state($aeid, $state, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie que l'etat est attendu
        if (! is_numeric($state) ) {
            return "__ERR_VOL_FAILED";
        }
        if (! in_array(intval($state), $this->_ART_STATE) ) {
            return "__ERR_VOL_MSM";
        }
        $state = intval($state);
        
        /*
         * [NOTE 08-06-15] @BOR
         * Je n'utilise pas la méthode Exists() pour vérifier car elle vérifie l'etat et peut renvoyer FALSE.
         */ 
        $QO = new QUERY("qryl4artn3neo0615001");
        $params = array( ':art_eid' => $aeid );
        $datas = $QO->execute($params);
        if (! $datas ) {
            return "__ERR_VOL_ART_GONE";
        }
        $atb = $datas[0];
        
        /*
         * [NOTE 08-06-15] @BOR
         * On suppose qu'on aura toujours qu'un seul état valide par Article.
         * Il est donc important de toujours utiliser cette méthode pour tout changement d'état au risque de tomber sur des erreurs non gérées.
         */
        
        $now = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($now/1000));
                            
        //On lance la procédure en faisant attention de ne pas lancer la procédure pour rien.
        if ( !isset($atb["ash_state"]) ) {
            
            //On ajoute le nouvel état 
            $QO = new QUERY("qryl4artn18");
            $params = array( ':ash_aid' => $atb["artid"], ':ash_state' => $state, ':time' => $date, ':tstamp' => $now );
            $QO->execute($params);
            
        } else if ( !empty($atb["ash_id"]) && intval($atb["ash_state"]) !== $state ) {
            
            //On termine l'état précédent
            $QO = new QUERY("qryl4artn17");
            $params = array( ':ashid' => $atb["ash_id"], ':time' => $date, ':tstamp' => $now );
            $QO->execute($params);
            
            //On crée une ligne pour le nouvel état
            $QO = new QUERY("qryl4artn18");
            $params = array( ':ash_aid' => $atb["artid"], ':ash_state' => $state, ':time' => $date, ':tstamp' => $now  );
            $QO->execute($params);
            
        } else if ( !empty($atb["ash_id"]) && intval($atb["ash_state"]) === $state ) {
            return TRUE;
        } else {
            return "__ERR_VOL_ART_GONE";
        }
        
        //Dans tous les cas, on change l'information au niveau de la version VM
        $QO = (! $this->onread_is_trend_version_eid($aeid) ) ? new QUERY("qryl4artn19") : new QUERY("qryl4artn20");
        $params = array( ':artid' => $atb["artid"], ':state' => $state );
        $QO->execute($params);
        
        /*
         * [DEPUIS 06-08-15] @BOR
         *  On met à jour le capital du Compte de l'utilisateur sauf si on a une indication contraire.
         */
        if (! ( $_OPTIONS && is_array($_OPTIONS) && in_array("UPD_CAP_NO", $_OPTIONS) ) ) {
            $PA = new PROD_ACC();
            $y__ = $PA->update_capital_for($atb["art_accid"],["AQAP"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $y__) ) {
                return "__ERR_VOL_ALMST_DONE";
            }
        }
        
        return TRUE;
    }
    
    
    /***************************************************************** ON_DELETE **********************************************************************/
    
    public function ondelete_art_vm ($art_eid) {
        /*
         * Permet de supprimer la version VM d'un Article lorsque ca version standard a été supprimée.
         * On ne vérifie pas si l'Article existe car si l'Article pas dans VM ce n'est pas si grave que ça.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie son type, ce qui va conditionner la table dans laquelle on ira pour supprimer l'occurrence.
        
        $q = NULL;
        if ( $this->onread_is_trend_version_eid($art_eid) ) {
            $q = "qryl4artwm_itrn5";
        } else {
            $q = "qryl4artwm_imln5";
        }
        
        $QO = new QUERY($q);
        $params = array(":art_eid" => $art_eid);
        $QO->execute($params); 
        
        return TRUE;
    }
    
    /**************************************************************************************************************************************************/
    /**************************************************************** TEST DATAS SCOPE ****************************************************************/
    
    public function GenerateFakiesIML ($accid, $acc_eid, $count) {
        /*
         * Permet de créer des Articles à la volet pour des besoins de test.
         * Le Caller fournit l'identifiant du compte et le nombre d'Article.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( $count <= 0 )
            return;
        
//        $file = "http://ext.ycgkit.com/public/timg/370.jpg"; 
        $file = "http://ext.ycgkit.com/public/timg/500.png"; 
        $img_string = "data:image/png;base64,".base64_encode(file_get_contents($file));
        
        $args_newart = [
            "accid"             => $accid,
            "acc_eid"           => $acc_eid,
            "art_desc"          => "\r\n \n<span class='red' style='color: red;'>maréééààà@@@çççie en rouge ?</span><script>alert('injection')</script>)",
            "art_locip"         => ip2long($_SERVER["REMOTE_ADDR"]),
            "file.name"         => "rihanna",
            "art_pdpic_string"  => $img_string
        ];
        
        for ($i=0; $i<$count; $i++) {
            $a_tab = $this->on_create_entity($args_newart);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $a_tab) ){
                return $a_tab;
            }
            
            $PA = new PROD_ACC();
            $u_tab = $PA->on_read_entity(["accid"=>$accid]);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab) ){
                return $u_tab;
            }

            $args = [
                "artid"             => $a_tab["artid"],
                "art_eid"           => $a_tab["art_eid"],
                "art_picid"         => $a_tab["art_picid"],
                "art_pic_rpath"     => $a_tab["art_pdpic_path"],
                "art_desc"          => $a_tab["art_desc"],
                "art_crea_tstamp"   => $a_tab["art_creadate"],
                "art_locip"         => ip2long($_SERVER["REMOTE_ADDR"]),
                //Mots-clés
                "art_hashs"         => (! isset($a_tab["art_list_hash"]) ) ? "" : implode(",", $a_tab["art_list_hash"]),
                //Nombre de commentaires
                "art_rnb"           => $a_tab["art_rnb"],
                //Données sur les Evaluations liées à l'Article
    //            "art_me" => "", //Il s'agit d'un nouvel Article
                "art_evals"         => implode(",", $a_tab["art_eval"]),
                "art_tot"           => $a_tab["art_eval"][3],
                //Données sur le propriétaire
                "art_oid"           => $u_tab["pdaccid"],
                "art_ogid"          => $u_tab["pdacc_gid"],
                "art_oeid"          => $u_tab["pdacc_eid"],
                "art_opsd"          => $u_tab["pdacc_upsd"],
                "art_ofn"           => $u_tab["pdacc_ufn"],
                "art_oppicid"       => $u_tab["pdacc_uppicid"],
                "art_oppic_rpath"   => $u_tab["pdacc_uppic"],
                "art_todel"         => $u_tab["pdacc_todelete"]
            ];

            $this->oncreate_archive_iml($args);

        }
        
        return TRUE;
                
    }
    
    public function GenerateFakiesITR ($accid, $acc_eid, $trd_eid, $count) {
        /*
         * Permet de créer des Articles à la volet pour des besoins de test.
         * Le Caller fournit l'identifiant du compte et le nombre d'Article.
         * Il peut aussi décider de créer des Articles de type ITR plutot qu'IML.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( $count <= 0 )
            return;
        
        $file = "http://ext.ycgkit.com/public/timg/370.jpg"; 
//        $file = "http://ext.ycgkit.com/public/timg/500.png"; 
        
        $img_string = "data:image/png;base64,".base64_encode(file_get_contents($file));
        
        $args_newart = [
            "accid"             => $accid,
            "acc_eid"           => $acc_eid,
            "art_desc"          => "\r\n \n<span class='red' style='color: red;'>maréééààà@@@çççie en rouge ?</span><script>alert('injection')</script>)",
            "art_locip"         => ip2long($_SERVER["REMOTE_ADDR"]),
            "file.name"          => "rihanna",
            "art_pdpic_string"  => $img_string,
            "trd_eid"           => $trd_eid
        ];
        
        for ($i=0; $i<$count; $i++) {
            $AT = new ARTICLE_TR();
            $a_tab = $AT->on_create($args_newart, $accid);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $a_tab) ){
                return $a_tab;
            }
            
            $PA = new PROD_ACC();
            $u_tab = $PA->on_read_entity(["accid"=>$accid]);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab) ){
                return $u_tab;
            }
            
            $TR = new TREND();
            $t_tab = $TR->on_read_entity(["trd_eid"=>$trd_eid]);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t_tab) ){
                return $t_tab;
            }
            

            $args = [
                "artid"                 => $a_tab["artid"],
                "art_eid"               => $a_tab["art_eid"],
                "art_picid"             => $a_tab["art_picid"],
                "art_pic_rpath"         => $a_tab["art_pdpic_path"],
                "art_desc"              => $a_tab["art_desc"],
                "art_crea_tstamp"       => $a_tab["art_creadate"],
                "art_locip"             => ip2long($_SERVER["REMOTE_ADDR"]),
                "art_trid"              => $a_tab["trid"],
                "art_trd_eid"           => $a_tab["trd_eid"],
                "art_trd_title"         => $t_tab["trd_title"],
                "art_trd_desc"          => $t_tab["trd_desc"],
                "art_trd_title_href"    => $t_tab["trd_title_href"],
                "art_trd_catgid"        => $t_tab["trd_catgid"],
                "art_trd_is_public"     => $t_tab["trd_is_public"],
                "art_trd_grat"          => $t_tab["trd_grat"],
                "art_trd_date_tstamp"   => $t_tab["trd_creadate_tstamp"],
                //Mots-clés
                "art_hashs"             => (! isset($a_tab["art_list_hash"]) ) ? "" : implode(",", $a_tab["art_list_hash"]),
                //Nombre de commentaires
                "art_rnb"               => $a_tab["art_rnb"],
                //Données sur les Evaluations liées à l'Article
    //            "art_me" => "", //Il s'agit d'un nouvel Article
                "art_evals"             => implode(",", $a_tab["art_eval"]),
                "art_tot"               => $a_tab["art_eval"][3],
                //Données sur le propriétaire
                "art_oid"               => $u_tab["pdaccid"],
                "art_ogid"              => $u_tab["pdacc_gid"],
                "art_oeid"              => $u_tab["pdacc_eid"],
                "art_opsd"              => $u_tab["pdacc_upsd"],
                "art_ofn"               => $u_tab["pdacc_ufn"],
                "art_oppicid"           => $u_tab["pdacc_uppicid"],
                "art_oppic_rpath"       => $u_tab["pdacc_uppic"],
                "art_todel"             => $u_tab["pdacc_todelete"]
            ];

            $this->oncreate_archive_itr($args);
        }
        
        return TRUE;
    }
    
    /****************************************************************************************************************************************************/
    /****************************************************************** ONREAD (START) ******************************************************************/
    
    public function onread_is_sod_version ($artid) {
        /*
         * Permet de vérifier si l'Article est un Article de type Tendance.
         * Cela sert notamment à définir à définir les droits sur les Commentaires ou les Articles.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4artn30");
        $params = array(":artid" => $artid);
        $datas = $QO->execute($params);    

        return  (! $datas ) ? FALSE : TRUE;
    }
    
    public function onread_is_trend_version ($artid) {
        /*
         * Permet de vérifier si l'Article est un Article de type Tendance.
         * Cela sert notamment à définir à définir les droits sur les Commentaires ou les Articles.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4artn10");
        $params = array(":artid" => $artid);
        $datas = $QO->execute($params);    

        return  (! $datas ) ? FALSE : TRUE;
    }
    
    public function onread_is_trend_version_eid ($art_eid) {
        /*
         * [NOTE 08-10-14] @author L.C.
         * Permet de vérifier si l'Article est un Article de type Tendance.
         * Cela sert notamment à définir à définir les droits sur les Commentaires ou les Articles.
         * Ici on utilise l'eid
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $art_eid);
        
        $QO = new QUERY("qryl4trartn7");
        $params = array(":art_eid" => $art_eid);
        $datas = $QO->execute($params);    

        return (! $datas ) ? FALSE : TRUE;
    }
    
    public function onread_PrmlkToHref ($prmlkid, $ivid = FALSE) {
        /*
         * [NOTE 02-03-15]
         * Permet de générer le lien permanent pour un Article à partir de l'identifiant prmlkid fourni.
         * Cette méthode a aussi et surtout l'avantage de centraliser la gestion de l'url pour les permaliens
         * 
         * RAPPEL : 
         *      (1) "art_prmlk" représente un identifiant et non un lien. Il serait judicieux de le changer mais l'opportunité ne sait pas encore présentée.
         *      (2) Nous ne vérifions pas la validité de l'identifiant fourni pour des raisons de performances et fonctionnelles.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $prmlkid);
        
        $url = ( $ivid ) ? "/f/vid/$prmlkid" : "/f/$prmlkid";
        
        return $url;
    }
    
    public function onread_AcquierePrmlk($arteid, $ivid = FALSE, $_ONLYID_OPT = FALSE) {
        /*
         * Permet de récupérer le permalien d'un Article dont l'identifiant est passé en paramètre.
         * Caller peut décider de ne récupérer que l'identifiant. Pour cela il passe l'option correspondante.
         * 
         * NOTE :
         *      -> [21-03-2015] A l'origine, cette méthode a été créée pour permettre aux modèles VM d'accéder au "permalien" sans devoir modifier en profondeur la base de données.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $arteid);
        
        /*
         * On vérifie que l'Article existe.
         * TODO : Vérifier que l'Article n'est pas en mode de suppression
         */
        $atab = $this->exists($arteid);
        if (! $atab ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        return ( $_ONLYID_OPT ) ? $atab["art_prmlk"] : $this->onread_PrmlkToHref($atab["art_prmlk"],$ivid);
    }
    
    public function onread_AcquiereUsertags_Article ($arteid, $_WITH_FE_OPT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $arteid);
        
        /*
         * On vérifie que l'Article existe.
         * TODO : Vérifier que l'Article n'est pas en mode de suppression
         */
        $atab = $this->exists($arteid);
        if (! $atab ) {
            return "__ERR_VOL_ART_GONE";
        } else {
            $ustgs = $this->onload_art_list_usertags ($atab["artid"]);
            if ( $ustgs && $_WITH_FE_OPT ) {
                array_walk($ustgs,function(&$i,$k){
                    $i = [
                        'eid'   => $i['ustg_eid'],
                        'ueid'  => $i['tgtueid'],
                        'ufn'   => $i['tgtufn'],
                        'upsd'  => $i['tgtupsd']
                    ];
                });
            }
        }
        return $ustgs;
    }
    
    public function onread_AcquiereHashs_Article ($arteid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $arteid);
        
        /*
         * On vérifie que l'Article existe.
         * TODO : Vérifier que l'Article n'est pas en mode de suppression
         */
        $atab = $this->exists($arteid);
        if (! $atab ) {
            return "__ERR_VOL_ART_GONE";
        } else {
            $hashs = $this->onload_art_list_hash($atab["artid"],$atab["art_eid"]);
        }
        
        return $hashs;
    }
    
    /**************************************************************************************************************************************************************/
    
    public function onread_AcquiereHashs_Reaction ($reid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $reid);
        
        $hashs = [];
        /*
         * ETAPE :
         *      On vérifie que REACTION existe.
         */
        $rtab = $this->reaction_exists($reid);
        if (! $rtab ) {
            return "__ERR_VOL_RCT_GONE";
        } else {
            $hashs = $this->onload_react_list_hashs($rtab["reactid"],$reid);
        }
        return $hashs;
    }
    
    public function onread_AcquiereUsertags_Reaction ($reid, $_WITH_FE_OPT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $reid);
        
        $ustgs = NULL;
        /*
         * On vérifie que l'Article existe.
         * TODO : Vérifier que l'Article n'est pas en mode de suppression
         */
        $rtab = $this->reaction_exists($reid);
        if (! $rtab ) {
            return "__ERR_VOL_RCT_GONE";
        } else {
            $ustgs = $this->onload_react_list_usertags($rtab["reactid"]);
            if ( $ustgs && $_WITH_FE_OPT ) {
                array_walk($ustgs,function(&$i,$k){
                    $i = [
                        'eid'   => $i['ustg_eid'],
                        'ueid'  => $i['tgtueid'],
                        'ufn'   => $i['tgtufn'],
                        'upsd'  => $i['tgtupsd']
                    ];
                });
            }
        }
        return $ustgs;
    }
    
    public function onread_AcquiereURLICs_Article ($arteid, $_WITH_FE_OPT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $arteid);
        
        /*
         * On vérifie que l'Article existe.
         * TODO : Vérifier que l'Article n'est pas en mode de suppression
         */
        $atab = $this->exists($arteid);
        if (! $atab ) {
            return "__ERR_VOL_ART_GONE";
        } else {
            $urlics = $this->onload_art_list_uic($atab["artid"]);
            if ( $urlics && $_WITH_FE_OPT ) {
                array_walk($urlics,function(&$i,$k){
                    $i = [
                        'uicid'     => $i['uic_eid'],
                        'uic_url'   => $i['uic_gvnurl'],
                        'uic_cid'   => $i['uic_uceid']
                    ];
                });
            }
        }
        
        return $urlics;
    }
    
    
    /**
     * Permet de vérifier si l'utilisateur passé en paramètre a les autorisations pour accéder en lecture au dit Article
     * 
     * Cette méthode peut être appelé dans le cas où on a pas d'utilisateur de référence. Par exemple, s'il s'agit d'un vitisteur.
     * [DEPUIS 07-07-15] 
     * @author Lou Carther
     * @param type $uid
     * @param type $aid
     * @param type $_OPTIONS
     * @return string|boolean
     */
    public function onread_CanRead($uid, $aid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $aid);
        
        /*
         * On vérifie les Options.
         */
        $FW = ( !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("FAST_WAY", $_OPTIONS) ) ? TRUE : FALSE;
        $FEM = ( !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("FE_MODE", $_OPTIONS) ) ? TRUE : FALSE;
        
        /*
         * On vérifie si l'Utilisateur et n'est pas en mode DEL dans le cas de FEM
         */
        $PA = new PROD_ACC();
        $utab;
        if ( !$FW && !empty($uid) ) {
             $utab = $PA->exists_with_id($uid, TRUE);
             if (! $utab ) {
                 return "__ERR_VOL_U_G";
             }
        }
        
        /*
         * On récupère la table de l'Article
         */
        $ART = new ARTICLE_TR();
        $atab = $ART->child_exists_with_id($aid);
        if ( !$atab ) {
            return "__ERR_VOL_ART_GONE";
        } else if ( $atab === -1 ) {
            $atab = $ART->getArt_loads();
        }
        
        /*
         * [NOTE 07-07-15] @BOR
         * Le fait de ne pas regrouper les conditions facilite le DEBUG. 
         * On sait qu'elle condition a permis de autoriser/refuser l'opération
         */
        if ( 
            key_exists("trartid", $atab) && !empty($atab["trartid"]) //C'est un Article de type ITR
        ) {
//            var_dump("FIRST => ",floatval($uid) === floatval($atab["art_accid"]),( key_exists("trartid", $atab) && !empty($atab["trartid"]) ),$aid,$atab["trartid"]);
            return TRUE;
        } else if ( 
            !empty($uid) && floatval($uid) === floatval($atab["art_accid"])  //Je suis le propriétaire)
        ) {
            return TRUE;
        } 
        /*
         * [DEPUIS 02-07-16]
         */
        else if ( intval($atab["art_is_sod"]) === 1 ) { 
            return TRUE;
        } 
        /*
         * [DEPUIS 17-07-16]
         */
        else if ( intval($atab["art_is_hstd"]) === 1 ) { 
            return TRUE;
        } 
        else if ( empty($uid) ) { //L'Article est forcement de type IML. Le compte de référence n'étant pas connu, on REFUSE !
            return FALSE;
        } 
        else {
            //A ce stade, il s'agit d'un Article de type IML. Il nous faut vérifier le type de relation qui lie les deux Acteurs 
            $REL = new RELATION();
            $r = $REL->onread_relation_exists_fecase($uid,$atab["art_accid"]);
            $urel = $REL->encode_relcode($r);
            
//            var_dump("UREL => ".$urel,$aid);
            
            return ( isset($urel) && in_array(strtolower($urel),["xr03","xr13","xr23","xr02","xr12","xr22"]) ) ? TRUE : FALSE;
                    
        } 
    }

    /******************************************************************** ONREAD (END) ********************************************************************/
    /*******************************************************************************************************************************************************/
    
    
    /*********** EVALUATION SCOPE (START) *************/
    
    public function article_get_evals ($art_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $art_eid);
        
        //On va convertir eid en id à partir de la base de données
        $QO = new QUERY("qryl4artn5");
        $params = array(":art_eid" => $art_eid);
        $datas = $QO->execute($params);
       
        if (! $datas ) {
            return "__ERR_VOL_ART_GONE"; 
        } else if ( $datas && count($datas) > 1 ) { 
            return "__ERR_VOL_UXPTD"; 
        } else { 
            $id = $datas[0]["artid"];
        }
        
        //On récupère les avis
        $QO = new QUERY("qryl4evaln11_wtdlo");
//        $QO = new QUERY("qryl4evaln11"); //[DEPUIS 11-09-15] @author BOR
        $params = array(":eval_artid" => $id);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return;
        } else {
            foreach ($datas as &$e) {
                /*
                 * [NOTE 25-02-15] @Loukz
                 * Les lignes de TableEvals ou de ProdEvents n'avaient pas été conçues pour enregistrer des identifiants externes.
                 * Or, nous devons à tout prix liée chaque donnée à un élément unique d'identification.
                 * Plutôt que d'opter pour la solution modifications de la base, j'ai préféré prendre comme identifiant unique,
                 * l'identifiant de l'utilisateur à l'origine de l'action.
                 * Cette solution est pertinente dans le sens où, "à un utilisateur ne correspond qu'une Evaluation active"
                 */
                $PA = new PROD_ACC();
                $uppic = $PA->onread_acquiere_pp_datas($e["oid"])["pic_rpath"];
                
                $e["oppic"] = $uppic;
            }
            
            return $datas;
        }       
    }
    
    
    /*********** REACTION SCOPE (START) *************/
    
    public function reaction_add_art_reaction ($rbody, $rlocip, $rwriter, $artid, $std_err_enabled = FALSE) {
        //$rwriter : Permet de vérifier que CU a le droit d'ajouter le Commentaire (Article'owner, selon UREL.
        /*
         * [NOTE 12-0-14]
         * En attendant de construire une classe REACTION on centralise les taches d'ajout et de suppression dans le calse ARTICLE.
         * Dautant plus qu'il n'y a que deux actions add, del.
         * 
         * Si des erreurs surviennent on les signale. Si CALLER ne veut qu'on le fasse il change $std_err_enabled.
         * Dans ce cas on renvoie l'erreur.
         * 
         * [NOTE 16-0-14]
         * On peut utiliser cette méthode sans load l'Article. Les principales raisons sont :
         *  (1) On gagne en performance
         *  (2) Quand on aura un ENTITY REACTION on aura pas à load l'ARTICLE lié sauf si c'est obligatoire
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
         $accid = $rwriter;
        //* Vérifier si l'utilisateur a bel et bien le droit d'ajouter le commentaire *//
            //On vérifie si l'Article est un Article de Tendance
//         ...
        
         /* OBSELETE : CALLER DOIT MAINTENANT ENVOYER ACCID
            //On convertit $writer en un code $accid valide
            $QO = new QUERY("qryl4accn2");
            $params = array(":acc_eid" => $rwriter);
            $datas = $QO->execute($params);    
            
            if ( !$datas )
                return "__ERR_CU_GONE";
            else {
                $accid = $datas[0]["art_oid"];
            }
          
          */
                
         /* OBSELETE, LAISSER CALLER LE FAIRE
            /* (1) Est qu'il est le propriétaire de l'Article sur lequel il vaut ajouter le commentaire 
        
            //On interroge la base de données
            $QO = new QUERY("qryl4artn6");
            $params = array(":art_accid" => $nrbody);
            $id = $QO->execute($params);
            //(2) (Sinon) Est ce que la relation qu'il a avec le propriétaire permet l'ajout
            //(Sinon) Renvoyer "__ERR_DENY"
            
        //*/
         
         if (! is_string($rbody) ) {
             return "__ERR_VOL_FAILED";
         }
         
        //On récupère l'eid et en emprofite pour vérifier si l'Article existe toujours
        $art_eid = $this->on_read_get_arteid_from_artid ($artid);
         
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $art_eid) ) {
            return $art_eid;
        }
        
        //Est qu'on est dans la limite de _MAX ?
        if ( mb_strlen($rbody,"UTF8") > $this->_REACT_MAX_TEXT ){
//        if ( strlen($rbody) > $this->_REACT_MAX_TEXT ){ //[DEPUIS 26-04-15] @BOR
            return "__ERR_VOL_MAX_TEXT";
        }
        
        $TH = new TEXTHANDLER();
        
        //Sécuriser le texte
        $nrbody = $TH->secure_text($rbody);
        
        /*
         * [DEPUIS 18-04-16]
         *      On convertit les éventuels EMOJIS en une correspondance HTML.
         */
        $nrbody = $TH->replace_emojis_in($nrbody);
        
        
        /* Ajouter le commentaire dans la base de données */
        $time = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4reactn3");
        $params = array(":react_body" => $nrbody, ":react_writer" => $accid, ":react_loc_numip" => $rlocip, ":react_artid" => $artid, ":react_date_tstamp" => $time);
        $rid = $QO->execute($params);
        
        //Acquisition de react_eid
        $react_eid = $this->entity_ieid_encode($time, $rid);
        
        //On UPDATE pour ajouter eid
        $QO = new QUERY("qryl4reactn5");
        $params = array(":reactid" => $rid, ":react_eid" => $react_eid);
        $QO->execute($params);
        
        //On load les données du nouveau commentaire pour les renvoyer à CALLER
        $QO = new QUERY("qryl4reactn2");
        $params = array(":reactid" => $rid);
        $datas = $QO->execute($params);
        
        $react_infos = $datas[0];
        
        //On extrait les mots-clés s'ils existent
        $mc = $TH->extract_prod_keywords($rbody);
        if ( $mc ) {
            $react_infos["hashs"] = $mc[1];
        }
        
        /*
         * [NOTE 06-04-15] @BOR
         * ETAPE : 
         * On vérifie s'il y a des USERTAGs pour le commentaire.
         * Dans ce cas, on insère dans la base de données.
         */
        $usertags = $TH->extract_tqr_usertags($rbody);
        if ( $usertags && is_array($usertags) && count($usertags) ) {
            $TH = new TEXTHANDLER();
            
            $list_utags = $usertags[1];
            
            //On retire les accents et on met tous les tags en lowercase
            /*
             * [NOTE 02-04-15] @BOR
             * J'ai décidé de ne plus prendre en compte les accents car leur traitement est trop lourd à ce stade.
             * Aussi, si l'utilisateur rentre "pépé" au lieu de "pepe", il n'atteindra pas sa cible.
             * On se réfère à la ménière dont le pseudo apparait dans l'url
             */
            /*
            array_walk($list_utags,function(&$i,$k){
                $TXH = new TEXTHANDLER();
                $i = strtolower($TXH->remove_accents($i));
            });
            //*/
            //On transforme en LOWERCASE();
            array_walk($list_utags,function(&$i,$k){
                $i = strtolower($i);
            });
            
            //On va supprimer les doublons
            $list_utags = array_unique($list_utags);
            
            //Pour chaque pseudo, nous allons vérifier qu'il s'agir belle et bien d'un pseudo valide
            $PA = new PROD_ACC();
            foreach ($list_utags as $psd) {
//                var_dump(__LINE__,$PA->exists_with_psd($psd,TRUE));
                $utag_tab = $PA->exists_with_psd($psd,TRUE,TRUE);
//                $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$psd,$utag_tab], 'v_d');
                if ( $utag_tab ) {
                    /*
                     * ETAPE :
                     * On lance la procédure de création du tag au niveau de la base de données.
                     * On procède dans un premier temps à l'enregistrement puis à la mise à jour.
                     */
//                     $artid $exists_id["pdaccid"]
                    $now = round(microtime(TRUE)*1000);
                    
                    $QO = new QUERY("qryl4ustgn1");
                    $params = array(
                        /*
                         * [DEPUIS 13-08-16]
                         */
                        ":us_eid"   => $now, 
                        ":tgtuid"   => $utag_tab["pdaccid"], 
                        ":datecrea" => date("Y-m-d G:i:s",($now/1000)), 
                        ":tstamp"   => $now);  
                    $id = $QO->execute($params);
                    
                    //On procède à la mise à jour en insérant l'identifiant externe
                    $QO = new QUERY("qryl4ustgn2");
                    $params = array(":id" => $id, ":eid" => $this->entity_ieid_encode($now, $id));  
                    $QO->execute($params);
                    
                    //On insère l'occurrence dans la classe fille dédiée à REACTION
                    $QO = new QUERY("qryl4ustg_rctn1");
                    $params = array(":id" => $id, ":rid" => $rid);  
                    $QO->execute($params);
                    
                }
                
            }
            
            $react_infos["ustgs"] = $this->onload_react_list_usertags($rid);
        }
        
        /*
         * [DEPUIS 17-11-15]
         *      On traite le cas des mot-clés
         */
        if (! empty($react_infos["hashs"]) ) {
            $HVIEW = new HVIEW();
            $args_urlic = [
                "t"     => $rbody,
                "hci"   => $rid,
                "hcei"  => $react_eid,
                "hcp"   => "HCTP_ART_REACT",
                "ssid"  => session_id(),
                "locip" => $rlocip,
                "curl"  => NULL,
                "uagnt" => ( $_SERVER['HTTP_USER_AGENT'] ) ? : ""
            ];
            $kws_r = $HVIEW->HSH_oncreate($args_urlic["t"], $args_urlic["hci"], $args_urlic["hcei"], $args_urlic["hcp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
            /*
             * NOTE 
             *      On ne controle pas la valeur retour.
             *      L'objectif étant de ne pas arreter le process de création à cause de l'enregistrement des mots-clés.
             */
//            var_dump(__LINE__,__FUNCTION__,$kws_r);
        }
        
        /*
         * [DEPUIS 30-04-15] @BOR
         */
        $react_infos["raeid"] = $art_eid;
        
        //On met à jour la version VM pour le nombre de commentaires
        $this->onalter_selfupdate_vm($art_eid);
        
        return $react_infos;
    }
    
    public function reaction_exists ($react_eid) {
        //art_eid = L'identifiant externe du commentaire.
        /*
         * Permet de vérifier et de charger un commentaire s'il existe.
         * Cette méthode est interessante dans le cas où on veut avoir un maximum d'information sur un commentaire.
         * On peut l'utiliser dans le processus de supression de commentaire.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4reactn7");
        $params = array(":react_eid" => $react_eid);
        $datas = $QO->execute($params);
        
        return ( $datas ) ?  $datas[0] : FALSE;
        
    }
    
    private function reaction_del_art_authorized ($react_tab, $cuid) {
        /*
         * QUESTION : Est ce que l'utilisateur a le droit de supprimer l'article passé en paramètre.
         *            Pour des raisons de "performance" on ne vérifie pas si 'cuid' existe. C'est au CALLER de le faire.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //(1) Est qu'il est le propriétaire 
        if (  intval($react_tab["roid"])  === intval($cuid) ) {
            return TRUE;
        } else if ( intval($react_tab["aoid"]) === intval($cuid) ) { //(2) (Sinon) Est ce que CU est le propriétaire de l'Article lié
            return TRUE;
        }
        
        return FALSE;
    }
    
    public function reaction_del_art_reaction ($react_eid, $cuid, $aeid, $std_err_enabled = FALSE) {
        //cuid : Permet de vérifier que CU a le droit de supprimer le Commentaire. Il faut que le commentaire ou l'Article lié l'appartiennent ou que l'Article
        /*
         * [NOTE 12-0-14]
         * En attendant de construire une classe REACTION on centralise les taches d'ajout et de suppression dans le calse ARTICLE.
         * Dautant plus qu'il n'y a que deux actions : add, del.
         * 
         * Si des erreurs surviennent on les signale. Si CALLER ne veut qu'on le fasse il change $std_err_enabled.
         * Dans ce cas on renvoie l'erreur.
         * 
         * P.S : IL FAUT PRENDRE EN COMPTE :
         *  (1) On accepte eid car il est plus probable que ca soit cette valeur qui soit transmise. 
         *  (2) Si l'utilisateur cuid n'existe pas, on tombera encore e toujours sur un "DENY"
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$react_eid, $cuid, $aeid]);
        
        /*
         * ETAPE :
         *      On vérifie que l'Article existe.
         * RAPPEL : 
         *      On n'aurait pu ne pas vérifier l'existence de l'Article car, au vu de la requete, ...
         *      ... vérifier l'existence du Commentaire revient à vérifier l'existence de l'Article lié.
         *      On le fait dans le seul but d'être suffisamment précis pour FE.
         */
        $atab = $this->exists($aeid);
        if (! $atab ) {
            return "__ERR_VOL_ART_GONE"; 
        } 
        
        /*
         * ETAPE :
         *      On vérifie si le commentaire existe.
         */
        $react_tab = $this->reaction_exists($react_eid);
        if (! $react_tab ) {
            return "__ERR_VOL_REACT_GONE"; 
        } 
        
        /*
         * ETAPE :
         *      On vérifie la concordance entre l'identifiant de l'Article passé en paramètre et le Commentaire.
         */
        if ( $react_tab["aeid"] !== $aeid ) {
            return "__ERR_VOL_FRBDN"; 
        } 
            //** Vérifier si l'utilisateur a bel et bien le droit de supprimer le commentaire (Owner ou Article's Owner) **//
            
        if ( $this->reaction_del_art_authorized($react_tab,$cuid) ) {
//                var_dump(__LINE__,__FUNCTION__,$react_tab["reactid"]);

            /*
             * Suppression effective du commentaire la base de données
             * NOTES :
             *  [17-04-15] @BOR 
             *      * On ne supprime pas l'activité liée à l'ajout du Commentaire, l'occurrence devra être archivée par CRON
             *      * On ne supprime pas l'activité liée à l'ajout de l'Usertag lié au Commentaire, l'occurrence devra être archivée par CRON
             *      * Les Actys peuvent fonctionner sans les Occurrences de references. Il n'y a pas de risque de voir l'opération de suppression de l'Usertag échoué.
             */ 

            /*
             * ETAPE :
             *      On supprime les Usertags attachés.
             */
            $QO = new QUERY("qryl4ustg_rctn3");
            $params = array( ':rid' => $react_tab["reactid"] );
            $QO->execute($params);

            /*
             * ETAPE :
             *      On supprime l'occurrence de Commentaire.
             */
            $QO = new QUERY("qryl4reactn4");
            $params = array( ':reactid' => $react_tab["reactid"] );
            $QO->execute($params);
            
            /*
             * [DEPUIS 30-04-15] @BOR
             *      On met à jour l'e nombre de Commentaire au niveau de 'Article au niveau de VM
             */
            $this->onalter_selfupdate_vm($aeid);

            return TRUE;

        } else {
            return "__ERR_VOL_DENY";
        }
        
        //[NOTE 02-09-14] C'est presque impossible d'arriver ici mais bon, c'est de l'informatique tout ça !!
        return;
             
    }
    
    //L.C. 16-0-14 23:06
    //L.C. Modifications effectuées pour fit aux données attendues en FE par la plupart des modules
    public function article_get_reacts ($art_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On va convertir eid en id à partir de la base de données
        $QO = new QUERY("qryl4artn5");
        $params = array(":art_eid" => $art_eid);
        $datas = $QO->execute($params);
       
        if ( !$datas ) {
            return "__ERR_VOL_ART_GONE"; //[2-0-14 06:5] Ajouté par L.C.
        } else if ( $datas && count($datas) > 1 ) { 
            return "__ERR_VOL_UXPTD"; //[2-0-14 06:5] Ajouté par L.C.
        } else { 
            $id = $datas[0]["artid"];
        }
        
        //On récupère les commentaires
        $QO = new QUERY("qryl4reactn6");
        $params = array(":react_artid" => $id);
        $reacts = $QO->execute($params);
        
//        var_dump(__LINE__,$reacts);
//        exit();
        
        if (! $reacts ) {
            return;
        } else {
            $reactions = NULL;
            
            foreach ($reacts as $r) {
                /*
                 * [DEPUIS 06-09-15] @author L.C.
                 *  On vérifie que le compte n'est pas en instance de suppression ou n'est juste pas indisponible
                 */
                if ( intval($r["atdl"]) !== 0 ) {
                    continue;
                }
                
                /*
                 * [NOTE 22-09-14] @author L.C.
                 *  Du fait de changements au niveau de la gestion de PROFILPIC, on est obligé de faire appel à PDACC pour récupérer l'image de profil.
                 */
                $PA = new PROD_ACC();
                $uppic = $PA->onread_acquiere_pp_datas($r["oid"])["pic_rpath"];
                
                $reactions[] = [
                    "itemid"    => $r["react_eid"],
                    "body"      => $r["react_body"],
                    "time"      => $r["react_date_tstamp"],
                    "artid"     => $id,
                    "raeid"     => $r["raeid"],
                    "utc"       => NULL,
                    "ustgs"     => $this->onread_AcquiereUsertags_Reaction($r["react_eid"],TRUE),
                    "hashs"     => $this->onload_react_list_hashs ($r["reactid"],$r["react_eid"]),
                    //Le propriétaire de l'Article lié au Commentaire
                    "aoid"      => $r["aoid"],
                    //Le propriétaire du commentaire
                    "oid"       => $r["oid"],
                    "oeid"      => $r["oeid"],
                    "ofn"       => $r["ofn"],
                    "opsd"      => $r["opsd"],
                    "oppic"     => $uppic,
                    "ohref"     => "/".$r["opsd"],
                ];
                
            }
            
            return $reactions;
            
        }       
        
        return;
    }
    
    /**
     * Récupère les commentaires depuis le Commentaire dont l'identifiant est passé en paramètre.
     * 
     * @param type $art_eid L'identifiant externe de l'Article
     * @param type $reid L'identifiant externe du Commentaire
     * @param type $dir La direction qui détermine s'il faut récupérer les Commentaires les plus récents ou plus anciens
     * @return array|NULL
     */
    public function article_get_reacts_from ($art_eid, $reid, $rtm, $dir = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$art_eid, $reid, $rtm]);
        
        $dir = ( $dir && in_array($dir,["bot","top"]) ) ? $dir : "bot";
        
        //On va convertir eid en id à partir de la base de données
        $QO = new QUERY("qryl4artn5");
        $params = array(":art_eid" => $art_eid);
        $datas = $QO->execute($params);
       
        if ( !$datas ) {
            return "__ERR_VOL_ART_GONE"; //[2-0-14 06:5] Ajouté par L.C. 
        } else if ( $datas && count($datas) > 1 ) { 
            return "__ERR_VOL_UXPTD"; //[2-0-14 06:5] Ajouté par L.C.
        } else { 
            $aid = $datas[0]["artid"];
        }
        
        /*
         * ETAPE :
         * On vérifie si l'identifiant de référence existe et est lié à l'Article
         */
        $ref_rct_tab = $this->reaction_exists($reid);
        if (! $ref_rct_tab ) {
            return "__ERR_VOL_REF_GONE";
        } else if ( $ref_rct_tab["react_artid"] !== $aid ) {
            return "__ERR_VOL_REF_LOST";
        } else if ( floatval($ref_rct_tab["react_date_tstamp"]) !== floatval($rtm) ) {
            return "__ERR_VOL_REF_LOST";
        }
        
        //On récupère les commentaires en fonction de la direction précisée
        $QO = ( $dir === "bot" ) ? new QUERY("qryl4reactn9") : new QUERY("qryl4reactn10");
        $params = array(":react_artid" => $aid, ":rid" => $ref_rct_tab["reactid"], ":time" => $rtm);
        $reacts = $QO->execute($params);
        
        if (! $reacts ) {
            return;
        } else {
            $reactions = NULL;
            
            foreach ($reacts as $r) {
                /*
                 * [NOTE 22-09-14] @author L.C.
                 * Du fait de changements au niveau de la gestion de PROFILPIC, on est obligé de faire appel à PDACC pour récupérer l'image de profil.
                 */
                $PA = new PROD_ACC();
                $uppic = $PA->onread_acquiere_pp_datas($r["oid"])["pic_rpath"];
                $reactions[] = [
                    "itemid"    => $r["react_eid"],
                    "body"      => $r["react_body"],
                    "time"      => $r["react_date_tstamp"],
                    "artid"     => $aid,
                    "art_eid"   => $art_eid, 
                    "utc"       => NULL,
                    "ustgs"     => $this->onread_AcquiereUsertags_Reaction($r["react_eid"],TRUE),
                    "hashs"     => $this->onload_react_list_hashs ($r["reactid"],$r["react_eid"]),
                    //Le propriétaire de l'Article lié au Commentaire
                    "aoid"      => $r["aoid"],
                    //Le propriétaire du commentaire
                    "oid"       => $r["oid"],
                    "oeid"      => $r["oeid"],
                    "ofn"       => $r["ofn"],
                    "opsd"      => $r["opsd"],
                    "oppic"     => $uppic,
                    "ohref"     => "/@".$r["opsd"],
                ];
                
            }
            
            return $reactions;
        }       
        
        return;
    }
    
    /********************************** FAVORITE SCOPE (START) **********************************/
    
    public function Favorite_hasFavorite ($uid, $aeid, $_WD = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid, $aeid]);
        
        $aid = $this->on_read_get_artid_from_arteid($aeid);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $aid) ) {
            return $aid;
        }
        
        $QO = new QUERY("qryl4artfavn2");
        $qparams = array(
            ":aid" => $aid,
            ":uid" => $uid
        );  
        $datas = $QO->execute($qparams);
        
        $r = FALSE;
        if ( $datas ) {
            $r = ( $_WD === TRUE ) ? $datas[0] : TRUE;
        }
        
        return $r;
    }
    
   /*
    * [DEPUIS 12-08-16]
    *      Optimisation pour des soucis de PERF
    */
    public function Favorite_hasFavorite_waid ($uid, $aid, $_WD = FALSE, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid, $aid]);
        
        if (! ( $_OPTIONS && in_array("AQAP", $_OPTIONS) ) ) {
            $aid = $this->exists_with_id($aid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $aid) ) {
                return $aid;
            }
        }
        
        $QO = new QUERY("qryl4artfavn2");
        $qparams = array(
            ":aid" => $aid,
            ":uid" => $uid
        );  
        $datas = $QO->execute($qparams);
        
        $r = FALSE;
        if ( $datas ) {
            $r = ( $_WD === TRUE ) ? $datas[0] : TRUE;
        }
        
        return $r;
    }
    
    public function Favorite_ConvertTypeID ($ftid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ftid]);
        
        /*
         * ETAPE :
         *      On vérifie que l'action est attendu.
         * TABLE :
         *      > ART_XA_FAV_PUB    : ARTicle_eXtrasAction_FAVOorite_PUBlic
         *      > ART_XA_FAV_PRI    : ARTicle_eXtrasAction_FAVOorite_PRIvate
         *      > ART_XA_UNFAV      : ARTicle_eXtrasAction_UnFAVOorite
         */
        if ( !in_array($ftid,array_values($this->_FAV_ACT_ID))  ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        return array_flip($this->_FAV_ACT_ID)[$ftid];
    }
    
    public function Favorite_Count ($aeid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$aeid]);
        
        $aid = $this->on_read_get_artid_from_arteid($aeid);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $aid) ) {
            return $aid;
        }
                
        $QO = new QUERY("qryl4artfavn1");
        $qparams = array(":aid" => $aid);  
        $datas = $QO->execute($qparams);
        
        return ( $datas ) ? $datas[0]["cn"] : 0;
    }
    
    public function Favorite_Action ($cueid, $aeid, $action, $ssid, $locip, $uagent = NULL, $_OPTIONS = NULL) {
        /*
         *      Il est important que CALLER transmette ACTION car l'utilisateur peut effectuer une action sur un élément non à jour.
         *      Si on devait deviner, effectuer l'ACTION cela pourrait entrainer des problèmes de compréhension.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cueid, $aeid, $action, $ssid, $locip]);
        
        /*
         * ETAPE :
         *      On vérifie que l'action est attendue.
         * TABLE :
         *      > ART_XA_FAV_PUB    : ARTicle_eXtrasAction_FAVOorite_PUBlic
         *      > ART_XA_FAV_PRI    : ARTicle_eXtrasAction_FAVOorite_PRIvate
         *      > ART_XA_UNFAV      : ARTicle_eXtrasAction_UnFAVOorite
         */
        
        if ( !in_array(strtoupper($action), array_keys($this->_FAV_ACT_ID))  ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        /*
         * ETAPE :
         *      On vérifie que l'élément existe et on récupère la table
         */
        $atab = $this->exists($aeid);
        if (! $atab ) {
            return "__ERR_VOL_ART_GONE";
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$atab);
//        exit();
        
        /*
         * ETAPE :
         *      On vérifie l'existence des différents ACTORS et on récupère la table.
         */
        $PA = new PROD_ACC();
        $cutab = $PA->exists($cueid);
        if (! $cutab ) {
            return "__ERR_VOL_CU_GONE";
        }
        if ( intval($cutab["pdaccid"]) === intval($atab["art_accid"]) ) {
            $tgutab = $cutab;
        } else {
            $tgutab = $PA->exists_with_id($atab["art_accid"]);
            if (! $tgutab ) {
                return "__ERR_VOL_TGT_GONE";
            }
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$atab,$cutab,$tgutab);
//        exit();    
        
        /*
         * ETAPE :
         *      On vérifie que l'utilisateur a le droit d'accéder au TESTY
         * 
         * [NOTE]
         *      Ne pas vérifier nous fait gagner 200ms. 
         *      Cependant, on pourrait avoir de gros problèmes de sécurité et de fiabilité sans cette vérification.
         */
        if (! ( ( intval($cutab["pdaccid"]) === intval($atab["art_accid"]) ) || $this->onread_CanRead($cutab["pdaccid"], $atab["artid"], ["FAST_WAY"]) ) ) {
            return "__ERR_VOL_DNY_AKX";
        }
        
        /*
         * ETAPE :
         *      On vérifie si l'utilisateur a déjà "Favorite" l'ARTICLE.
         */
        $me = $this->favorite_hasFavorite($cutab["pdaccid"],$atab["art_eid"],TRUE);
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$me);
//        exit();
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$action,$this->_FAV_ACT_ID[$action],$me);
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$me, is_array($me), $me["arfv_fvtid"], $this->_FAV_ACT_ID[$action], $me["arfv_fvtid"] === $this->_FAV_ACT_ID[$action]);
//        exit();
        
        /*
         * ETAPE :
         *      On vérifie l'Action est différente de la précédente
         */
        if ( $me && is_array($me) && intval($me["arfv_fvtid"]) === intval($this->_FAV_ACT_ID[$action]) ) {
            /*
             * [31-07-16]
             */
            $me["datas_is_previous"] = TRUE;
            return $me;
        } else if ( !$me && strtoupper($action) === "ART_XA_UNFAV" ) {
            return [];
        }
        
        /*
         * ETAPE :
         *      On annule l'ACTION précédente
         */
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4artfavn3");
        $params = array(
            ":id"       => $atab["artid"],
            ":uid"      => $cutab["pdaccid"],
            ":date"     => date("Y-m-d G:i:s",($now/1000)),
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *      On ajoute l'occurence de l'action et renvoie les données le cas échéant
         */
        $now = round(microtime(TRUE)*1000);

        $QO = new QUERY("qryl4artfavn4");
        $params = array(
            ":artid"    => $atab["artid"], 
            ":uid"      => $cutab["pdaccid"],
            ":aftid"    => $this->_FAV_ACT_ID[$action],
            ":ssid"     => $ssid,
            ":locip"    => $locip,
            ":uagnt"    => $uagent,
            ":date"     => date("Y-m-d G:i:s",($now/1000)),
            ":tstamp"   => $now
        );
        $id = $QO->execute($params);
        
        /*
         * [DEPUIS 06-05-16]
         *      Création et ajout d'un ID externe
         */
        $eid = $this->entity_ieid_encode($now,$id);
        
        //Insérer eid
        $QO = new QUERY("qryl4artfavn4_upd");
        $params = array(
            ":id"   => $id, 
            ":eid"  => $eid
        );  
        $datas = $QO->execute($params);

        /*
         * ETAPE :
         *      On récupère les données
         */
        $QO = new QUERY("qryl4artfavn5");
        $params = array( ":id"  => $id);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            if ( $_OPTIONS && $_OPTIONS["RETURN_DATAS_ANYW"] === TRUE ) {
                return $datas[0];
            } else {
                return ( in_array(strtoupper($action),["ART_XA_FAV_PUB","ART_XA_FAV_PRI"]) ) ? $datas[0] : [];
            }
            
        } else {
            return "__ERR_VOL_FAILED";
        }
        
    }
    
    public function Favorite_GetFavArts ($tguid, $cuid = NULL, $dir = "FST", $pvi = NULL, $pvt = NULL, $lmt = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tguid]);
        
        if (! in_array($dir,["FST","TOP","BTM"] ) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        if ( in_array($dir,["TOP","BTM"]) && !( $pvi && $pvt ) ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        $_lmt = (! $lmt ) ? 9 : $lmt;
        
        $are_frd = 0;
        if ( $cuid ) {
            if ( intval($tguid) === intval($cuid) ) { //HACK
                $are_frd = 1;
            } else {
                $REL = new RELATION();
                $are_frd = ( is_array($REL->friend_theyre_friends($tguid,$cuid)) ) ? 1 : 0;
            }
            
            if ( strtoupper($dir) === "FST" ) {
                $QO = ( $_OPTIONS && is_array($_OPTIONS) && in_array("ONLY_PUB", $_OPTIONS) ) ? new QUERY("qryl4artfavn6_olypub_cumd") : new QUERY("qryl4artfavn6_cumd");
                $qparams = array(
                    ":tguid"    => $tguid,
                    ":cuid"     => $cuid,
                    ":cuid1"    => $cuid,
                    ":are_frd"  => $are_frd,
                    ":limit"    => $_lmt
                );  
            } else if ( strtoupper($dir) === "TOP" ) {
                $aid = $this->on_read_get_artid_from_arteid($pvi);
                $QO = ( $_OPTIONS && is_array($_OPTIONS) && in_array("ONLY_PUB", $_OPTIONS) ) ? new QUERY("qryl4artfavn8_olypub_cumd") : new QUERY("qryl4artfavn8_cumd");
                $qparams = array(
                    ":tguid"    => $tguid,
                    ":cuid"     => $cuid,
                    ":cuid1"    => $cuid,
                    ":are_frd"  => $are_frd,
                    ":pvi"      => $aid,
                    ":pvt"      => $pvt,
                    ":limit"    => $_lmt
                ); 
            } else {
                $aid = $this->on_read_get_artid_from_arteid($pvi);
                $QO = ( $_OPTIONS && is_array($_OPTIONS) && in_array("ONLY_PUB", $_OPTIONS) ) ? new QUERY("qryl4artfavn7_olypub_cumd") : new QUERY("qryl4artfavn7_cumd");
                $qparams = array(
                    ":tguid"    => $tguid,
                    ":cuid"     => $cuid,
                    ":cuid1"    => $cuid,
                    ":are_frd"  => $are_frd,
                    ":pvi"      => $aid,
                    ":pvt"      => $pvt,
                    ":limit"    => $_lmt
                );
            }
        } else {
            if ( strtoupper($dir) === "FST" ) {
                $QO = ( $_OPTIONS && is_array($_OPTIONS) && in_array("ONLY_PUB", $_OPTIONS) ) ? new QUERY("qryl4artfavn6_olypub") : new QUERY("qryl4artfavn6");
                $qparams = array(
                    ":tguid"    => $tguid,
                    ":are_frd"  => $are_frd,
                    ":limit"    => $_lmt
                );  
            } else if ( strtoupper($dir) === "TOP" ) {
                $aid = $this->on_read_get_artid_from_arteid($pvi);
                $QO = ( $_OPTIONS && is_array($_OPTIONS) && in_array("ONLY_PUB", $_OPTIONS) ) ? new QUERY("qryl4artfavn8_olypub") : new QUERY("qryl4artfavn8");
                $qparams = array(
                    ":tguid"    => $tguid,
                    ":are_frd"  => $are_frd,
                    ":pvi"      => $aid,
                    ":pvt"      => $pvt,
                    ":limit"    => $_lmt
                ); 
            } else {
                $aid = $this->on_read_get_artid_from_arteid($pvi);
                $QO = ( $_OPTIONS && is_array($_OPTIONS) && in_array("ONLY_PUB", $_OPTIONS) ) ? new QUERY("qryl4artfavn7_olypub") : new QUERY("qryl4artfavn7");
                $qparams = array(
                    ":tguid"    => $tguid,
                    ":are_frd"  => $are_frd,
                    ":pvi"      => $aid,
                    ":pvt"      => $pvt,
                    ":limit"    => $_lmt
                );
            }
        }
        $datas = $QO->execute($qparams);
        if (! $datas) {
            return [];
        }
        $ids = array_column($datas,"arfv_artid");
        
        $fds = [];
        foreach ($ids as $id) {
            if ( $this->onread_is_trend_version($id) ) {
                $atab = $this->onread_archive_itr(["artid"=>$id]);
                $atab["istrd"] = TRUE;
            } else {
                $atab = $this->onread_archive_iml(["artid"=>$id]);
                $atab["istrd"] = FALSE;
            }
            $fds[] = $atab;
        }
        
        return $fds;
    }
    
    
    /********************************* FAVORITE SCOPE (END) *************************************/

    /*********************************** REPORT SCOPE (START) **********************************/
    
    
    
    /************************************ REPORT SCOPE (END) ************************************/
    
    
    /*********** VISIT SCOPE (START) *************/
     public function add_art_visit () {
        /*
         * [NOTE 12-0-14]
         * En attendant de construire une classe VISIT on centralise les tâches ici.
         * Dautant plus qu'il n'y a que deux actions
         */
    }
    
    
    /************ ONDELETE SCOPE (START) **************/
    
    private function art_ondelete_legitimate($cuid, $std_err_enabled) {
        //Vérifie si l'utilisateur CU a le droit de supprimer l'article
        
        //Est-il le propriétaire de l'Article
        if ( $cuid != $this->art_oeid ) {
            if (! $std_err_enabled ) {
                $r = ["err_user_l6comn", $this->get_or_signal_error(1, "err_user_l6comn",  __FUNCTION__, __LINE__)];
                
                return $r;
            } else {
                $this->get_or_signal_error(2, "err_user_l6comn",  __FUNCTION__, __LINE__);
            }
        }
        
        return TRUE;
        
    }
    
    /**
     * Permet de supprimer tous les commentaires liés à un Article.
     * @param {integer} $artid
     * @return boolean
     */
    private function art_ondelete_reacts ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        /*
         * ETAPE :
         * On supprime tous les Usertags liés aux commentaires de l'Article dont l'identifiant est passé en paramètre.
         * Cela permet de s'assurer que les Commentaires sont définitivement libres pour suppression.
         */
         $QO = new QUERY("qryl4artn16");
         $qparams = array(":aid" => $artid);  
         $QO->execute($qparams);
         
        /*
         * ETAPE :
         * Supprimer les commentaires dans la base de données s'ils existent
         */
        $QO = new QUERY("qryl4artn11");
        $qparams = array(":artid" => $artid);  
        $QO->execute($qparams);
        
        return TRUE;
    }
    
    private function art_ondelete_hastags ($artid) {
        /*
         * La méthode permet de supprimer les liaisons entre l'Article et certains hashtags.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        //Supprimer les hashtags dans la base de données s'ils existent
        $QO = new QUERY("qryl4artn12");
        $qparams = array(":artid" => $artid);  
        $QO->execute($qparams);
        
        return TRUE;
        
    }
    
    private function art_ondelete_visits ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //TODO : Supprimer les visites dans la base de données s'elles existent
         /*
            $QO = new QUERY("...");
        
            $qparams_in_values = array(":artid" => $artid);  

            $QO->execute($qparams_in_values);
         */
        
        //TODO : Si une erreur survient, on déclenche une erreur sinon, on renvoie 1
        
        return 1;
    }
    
    private function art_ondelete_evals ($artid) {
        /*
         * La méthode permet de supprimer les Evaluations  de l'Article passé en paramètre.
         * On supprime aussi les "Evènements" et les "Opérations" liées.
         * 
         * Le fait que TE et CO pointe vers EV rend les choses plus difficles. 
         * On va donc commencer par supprimer les occurrences de CO puis TE+EV
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        //Suppression de tous les éléments via une procédure stockée
        $QO = new QUERY("qryl4artn13");
        $qparams = array( 
            ":artid" => intval($artid) 
        );  
        $QO->execute($qparams);
        
        return TRUE;
    }
    
    private function art_bfr_ondelete_aimg ($apicid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
         * ETAPE :
         *      On vérifie que l'ART_IMAGE existe dans la BDD
         */
        $apic_tab = (new IMAGE_ART())->exists_with_id($apicid);
        if (! $apic_tab ) {
            return "__ERR_VOL_AIMG_GONE";
        }
        $pdpicid = $apic_tab["pdpicid"];
        
        /*
         * ETAPE :
         *      On vérifie que l'PDDB_IMAGE existe dans la BDD
         */
        
        $pdpic_tab = (new IMAGE_ART())->pdpic_exists_with_id($pdpicid);
        if (! $pdpic_tab ) {
            return "__ERR_VOL_PDIMG_GONE";
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$pdpic_tab);
//        exit();
		
//        $srvnm = ( ( defined("WOS_MAIN_HOST") && WOS_MAIN_HOST === "localhost" ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $pdpic_tab["server_name"];
        $srvnm = ( ( defined("WOS_MAIN_HOST") && in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $pdpic_tab["server_name"];
        $FTH = new FTP_HANDLER($srvnm);
        if (! $FTH->ftp_file_exists($pdpic_tab["pdpic_full_ftppath"]) ) {
            return "__ERR_VOL_PDIMG_PHY_GONE";
        }
        
        return TRUE;
    }
    
    private function art_bfr_ondelete_avid_frmarti ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $ARTV = new VIDEO_ART();
        $vid_tab = $ARTV->exists_from_artid($artid);
        if (! $vid_tab ) {
            return "__ERR_VOL_AVID_GONE";
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$vid_tab);
//        exit();
        
//        $srvnm = ( ( defined("WOS_MAIN_HOST") && WOS_MAIN_HOST === "localhost" ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $this->_SRV_LIST[$vid_tab["vid_srvid"]];
        $srvnm = ( ( defined("WOS_MAIN_HOST") && in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? "localhost" : $this->_SRV_LIST[$vid_tab["vid_srvid"]];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$this->_SRV_LIST[$vid_tab["vid_srvid"]]);
//        exit();
        
        $FTH = new FTP_HANDLER($srvnm);
        if (! $FTH->ftp_file_exists($vid_tab["vid_full_ftppath"]) ) {
            return "__ERR_VOL_VID_PHY_GONE";
        }
        
        return TRUE;
    }
    
    
    private function art_ondelete_artimg ($artpicid, $std_err_enabled = FALSE) {
        /*
         * La méthode supprime l'Image liée à l'Article passé en paramètre. 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On utilise un Objet ArtImage pour supprimer proprement l'image 
        $ARTI = new IMAGE_ART();
        $r = $ARTI->on_delete($artpicid);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            if (! $std_err_enabled ) {
                return $r;
            } else {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["START CHECKING PROCESS HERE  => ",$artpicid],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $r,'v_d');
                $this->signalError ("err_sys_l4artn3", __FUNCTION__, __LINE__);
            }
        }
        
        return TRUE;
    }
    
    private function art_ondelete_vidimg ($avidid, $std_err_enabled = FALSE) {
        /*
         * La méthode supprime l'Image liée à l'Article passé en paramètre. 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On utilise un Objet ArtImage pour supprimer proprement l'image 
        $ARTV = new VIDEO_ART();
        $r = $ARTV->on_delete($avidid);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            if (! $std_err_enabled ) {
                return $r;
            } else {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["START CHECKING PROCESS HERE  => ",$avidid],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $r,'v_d');
                $this->signalError ("err_sys_l4artn3", __FUNCTION__, __LINE__);
            }
        }
        
        return TRUE;
    }
    
    private function art_ondelete_ustags ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        $QO = new QUERY("qryl4ustg_artn3");
        $qparams = array(":aid" => $artid);  
        $QO->execute($qparams);
        
        return TRUE;
    }
    
    private function art_ondelete_artfavs ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        $QO = new QUERY("qryl4artn31");
        $qparams = array(
            ":aid" => $artid
        );  
        $QO->execute($qparams);
        
        return TRUE;
    }
    
    private function art_ondelete_artrprt ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        $QO = new QUERY("qryl4artn32");
        $qparams = array(
            ":aid" => $artid
        );  
        $QO->execute($qparams);
        
        return TRUE;
    }
    
    private function art_ondelete_statehisty ($artid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        $QO = new QUERY("qryl4artn21");
        $qparams = array(":aid" => $artid);  
        $QO->execute($qparams);
        
        return TRUE;
    }
    
    private function art_ondelete_article ($artid) {
        /*
         * La méthode supprime l'Article a proprement parlé dans la base de données. 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $artid);
        
        //On commence par vérifier si l'Article est de type TR
        
        if ( $this->onread_is_trend_version($artid) ) {
            $QO = new QUERY("qryl4trartn6");
            $qparams = array(":artid" => $artid);  
            $QO->execute($qparams);
        } else {
            $QO = new QUERY("qryl4artn14");
            $qparams = array(":artid" => $artid);  
            $QO->execute($qparams);
        }
       
        return TRUE;
    }
    
    /***** ON_DELETEE SCOPE (END) ******/
    
    
    
    /*******************************************************************************************************************************************************/
    /********************************************************************* VIDEO SCOPE *********************************************************************/
    
    private function video_check (&$file) {
        /*
         * [NOTE 20-01-16]
         * NON ! LAISSER LE GESTIONNAIRE PAR DEFAUT GERER LES EXCEPTIONS
         */
//        set_error_handler('exceptions_error_handler'); 
        try {
            $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $file);
            
            $infos = []; $m = [];
            
            $vd = $file["data"];
            /*
             * ETAPE 1 : 
             *      Vérifie qu'il s'agit bien d'un fichier de type video
             */
            $rgx = "#^(?:data:(video)\/([a-zA-Z\d]*);base64),([\s\S]+)#";
            if (! preg_match($rgx, $vd, $m) ) {
                return "__ERR_VOL_NOT_VIDEO";
            }

            /*
             * ETAPE 2 : 
             *      Vérifie que le TYPE de la video est autorisé pour traitement
             */
            if ( strtolower($m[2]) !== "mp4" ) {
                return "__ERR_VOL_NOT_VID_WRG_TYPE";
            }

            $data = base64_decode($m[3]);
            /*
             * ETAPE 3 : 
             *      Vérifie que la vidéo respectent les conditions de POIDS
             */

            $size = ( function_exists('mb_strlen') ) ? mb_strlen($data, '8bit') : strlen($data);
            if ( $size >  $this->_ART_SPECS["ART_VID_MAX_SIZE"] ) {
                return "__ERR_VOL_VID_WRG_SIZE";
            }

//            var_dump(__FILE__,__FUNCTION__,__LINE__,$size,$this->_ART_SPECS["ART_VID_MAX_SIZE"]);
//            exit();
            /*
             * ETAPE 4 : 
             *      Vérifie que la vidéo respectent les conditions de DURÉE
             */
//            var_dump($data);
//            var_dump(__FUNCTION__,__LINE__,$m[3]);
            
//            $pa = tempnam(sys_get_temp_dir(), 'vid');
            /*
             * [DEPUIS 03-08-16]
             */
            $pa = tempnam($this->_FILE_TRANSIT_DIR, 'vid');
            
            $old_name = $pa;
            $new_name = pathinfo($pa)['dirname'].'\\'.pathinfo($pa)['filename'].".mp4";
            rename($old_name,$new_name);
            $pa = $new_name;
            
//            var_dump(getcwd());
//            var_dump($old_name,$new_name);
//            exit();
            
            $tmpvid = file_put_contents($pa,$data);
//            fseek($tmpvid, 0);
            
//            var_dump($pa,pathinfo($pa),pathinfo($pa)['dirname'].'\\'.pathinfo($pa)['filename'].".mp4");
//            exit();
            
//            var_dump($tmpvid);
//            var_dump($pa,pathinfo($pa));
//            exit();
            
            
            $ffprobe = FFProbe::create();
            $vln = $ffprobe->format($pa)->get('duration');
            $mdatas = $ffprobe->streams($pa)->videos()->first();
            
            $mdatas_width_ori = $mdatas_width = $mdatas->getDimensions()->getWidth();
            $mdatas_height_ori = $mdatas_height = $mdatas->getDimensions()->getHeight();
            
//            var_dump(__FUNCTION__,__LINE__,$mdatas);
//            var_dump(__FUNCTION__,__LINE__,[$size,$mdatas_width,$mdatas_height]);
//            exit();
                    
            if ( $vln > $this->_ART_SPECS["ART_VID_MAX_DUR"] ) {
                return "__ERR_VOL_VID_WRG_DUR";
            }
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$vln,$this->_ART_SPECS["ART_VID_MAX_DUR"]);
//            exit();
            
            /*
             * ETAPE 5 :
             *      On extrait une image de la vidéo qui servira d'image de présentation
             */
            //On enregistre les données sur le fichier
            $ffmpeg = FFMpeg::create();

            $video = $ffmpeg->open($pa);
            
            /*
             * [18-08-16]
             *      Permet de résoudre le problème des vidéos provenant des mobiles qui nous donne des soucis de traitement à cause de la rotation.
             *      En effet, ces vidéos ont des tag qui indique l'orientation selon laquelle la vidéo a été prise. 
             *      Si nous ne prennons pas en compte ces données le traitement risque d'être faussé.
             */
            $videostream = $ffmpeg->getFFProbe()
                ->streams($pa)
                    ->videos()
                        ->first();
            
            
            /*
             * [DEPUIS 18-08-16]
             */
            $tag_rotate;
            if ( $videostream instanceof Stream && $videostream->has('tags') ) {
                $tags = $videostream->get('tags');
                $tag_rotate = ( isset($tags['rotate']) && intval($tags['rotate']) !== 0 ) ? intval($tags['rotate']) : 0;
                
                switch($tag_rotate) {
                    case 90:
                    case 180:
                    case 270:
                        /*
                         * [NOTE 18-08-16]
                         *      On le fait en deux temps car on ne peut pas ROTATE à 360 ou à 0...
                         */
                        $vid_rot_angle = RotateFilter::ROTATE_270;
                        $vid_rot_angle_then = RotateFilter::ROTATE_90;
                        break;
                }
                
                $format = new X264('libmp3lame');
                
                /*
                 * [DEPUIS 18-08-16]
                 *      (1) On procède aux modifications.
                 *      (2) On enregistre le fichier.
                 *      (3) On reOpen
                 */
                $rot1_basename = "rotvd_".mt_rand(1,9)."_".round(microtime(TRUE)*1000).".mp4";
                $rot1_path_to = getcwd()."/".$rot1_basename;
                $video
                    ->filters()->rotate($vid_rot_angle);
                $video
                    ->save($format,$rot1_basename);
                $video = $ffmpeg->open($rot1_path_to);
                
                
                /*
                 * ETAPE :
                 *      (1) On procède aux modifications.
                 *      (2) On enregistre le fichier.
                 *      (3) On reOpen
                 */
                if ( $vid_rot_angle_then ) {
                    $rot2_basename = "rotvd_".mt_rand(1,9)."_".round(microtime(TRUE)*1000).".mp4";
                    $rot2_path_to = getcwd()."/".$rot2_basename;
                    $video
                        ->filters()->rotate($vid_rot_angle_then);
                    $video
                        ->save($format,$rot2_basename);
                    $video = $ffmpeg->open($rot2_path_to);
                }
                
                $rotd_final_path = ( $rot2_path_to ) ? $rot2_path_to : $rot1_path_to;
                
                /*
                 * ETAPE :
                 *      On traite le nouveau fichier pour récupérer les METAS
                 */
                $raw_datas_rotd = "data:video/mp4;base64,".base64_encode(file_get_contents($rotd_final_path));
                $rgx = "#^(?:data:(video)\/([a-zA-Z\d]*);base64),([\s\S]+)#";
                if (! preg_match($rgx, $raw_datas_rotd, $m) ) {
                    return "__ERR_VOL_NOT_VIDEO_CZ2";
                }
                $data = base64_decode($m[3]);
                
                $ffprobe = FFProbe::create();
                $vln = $ffprobe->format($rotd_final_path)->get('duration');
                $mdatas = $ffprobe->streams($rotd_final_path)->videos()->first();
                //TAILE DE LA NEW VID
                $size = ( function_exists('mb_strlen') ) ? mb_strlen($data, '8bit') : strlen($data);
                
                /*
                 * ETAPE :
                 *      On modifie les données brutes fournies par USER sinon, le système traitera la VID originelle.
                 */
                $file["data"] = $raw_datas_rotd;
            }
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$tags,$tag_rotate,$vid_rot_angle]);
//            exit();
            
            /*
             * ETAPE :
             *      On détermine le moment qui sera pris pour créer le PREVIEW.
             *      L'objectif est d'améliorer la compréhension de la vidéo ET donc l'expérience utilisateur.
             */
            $frame_at = 0;
            if ( $vln > 2 && $vln <= 4 ) {
                $frame_at = 1;
            } else if ( $vln > 4 ) {
                $frame_at = 2;
            }
            
            /*
             * [DEPUIS 02-08-16]
             *      On réduit la taille de la vidéo si elle est superieure à 3Mo.
             *      Cette méthode n'est pas très agressive.
             *      Elle préservera au mieux la qualité de la vidéo.
             * 
             * [NOTE IMPORTANTE 02-08-16]
             *      Cette méthode de réduction, est LA SEULE, JE DIS BIEN LA SEULE !!! que j'ai trouvé après des heures (plus de 2 jours) de R&D.
             *      En effet, le FFMPEG-PHP n'est pas assez abouti pour permettre d'utiliser une autre option moins "bourrin"
             */
            $now1 = $now = round(microtime(TRUE)*1000);
           /*
            * [NOTE]
            *       La valeur 5 a été obtenu après R&D empirique.
            *       En dessous il y a un risque que la compression résulte d'un fichier plus lourd que celui de base
            */
            $quality_limit = 5 * 1024*1024;
            if ( $size >= $quality_limit )
            {
                /*
                 * ETAPE :
                 *      On détermine taille qui servira de référence pour la résolution
                 *          Si la taille de la vidéo t, est > 720p : 720p
                 *          Si la taille de la vidéo t, est t > 640 && t <= 720 : 640p
                 *          Si la taille de la vidéo t, est t > 480 && t <= 640 : 480p
                 *          NOTE : 
                 *              Les cas ci-dessous sont peu probables, mais on les considère quand même
                 *          Si la taille de la vidéo t, est t > 360 && t <= 480 : 360p
                 *          Si la taille de la vidéo t, est t > 240 && t <= 360 : 240p
                 *          Si la taille de la vidéo t, est t <= 240 : 144p
                 */
                $side = 720;
                $ref = ( $mdatas_width >= $mdatas_height ) ? $mdatas_width : $mdatas_height;
                if ( $ref > $side ) {
                    $bit_rate = 1000;
                    $side = 720;
                }
                else if ( $ref > 640 && $ref <= 720 ) {
                    $bit_rate = 720;
                    $side = 640;
                }
                else if ( $ref > 480 && $ref <= 640 ) {
                    $bit_rate = 640;
                    $side = 480;
                }
                else if ( $ref > 360 && $ref <= 480 ) {
                    $bit_rate = 480;
                    $side = 360;
                }
                else if ( $ref > 240 && $ref <= 360 ) {
                    $bit_rate = 360;
                    $side = 240;
                } 
                else {
                    $bit_rate = 144;
                    $side = 144;
                }
                
                if ( $ref > $side ) {
                    $coef = $side/$ref;
                    $mdatas_height *= $coef;
                    $mdatas_width *= $coef;
                }
                $mdatas_width = round($mdatas_width/2)*2;
                $mdatas_height = round($mdatas_height/2)*2;
                
                /*
                 * ETAPE :
                 *      On applique le FILTRE de redim
                 */
                $video
                    ->filters()
                    ->resize(new Dimension($mdatas_width,$mdatas_height))
                    ->synchronize();
                
                /*
                 * ETAPE :
                 *      On sauvegarde physiquement la VIDEO redim à la racine du dossier qui exec le script
                 */
                $basename = pathinfo($pa)['basename'];
                $format = new X264('libmp3lame');
                $format
                    /*
                     * [NOTE]
                     *      Obtenu par R&D empirique
                     */
                    ->setKiloBitrate($bit_rate); 
                $video
                    ->save($format,$basename);
                
                /*
                 * ETAPE :
                 *      On récupère le DATA_STRING la vidéo redim. 
                 *      Il nous servira de remplacement pour les données originelles.
                 */
                $path_to_rezd = getcwd()."/".$basename;
                $video = $ffmpeg->open($path_to_rezd);
                
                /*
                 * ETAPE :
                 *      On traite le nouveau fichier pour récupérer les METAS
                 */
                $raw_datas_rezd = "data:video/mp4;base64,".base64_encode(file_get_contents($path_to_rezd));
                $rgx = "#^(?:data:(video)\/([a-zA-Z\d]*);base64),([\s\S]+)#";
                if (! preg_match($rgx, $raw_datas_rezd, $m) ) {
                    return "__ERR_VOL_NOT_VIDEO_CZ2";
                }
                $data = base64_decode($m[3]);
                
                $ffprobe = FFProbe::create();
                $vln = $ffprobe->format($path_to_rezd)->get('duration');
                $mdatas = $ffprobe->streams($path_to_rezd)->videos()->first();
                //TAILE DE LA NEW VID
                $size = ( function_exists('mb_strlen') ) ? mb_strlen($data, '8bit') : strlen($data);
                
                /*
                 * ETAPE :
                 *      On modifie les données brutes fournies par USER sinon, le système traitera la VID originelle.
                 */
                $file["data"] = $raw_datas_rezd;
            }
            $now2 = $now = round(microtime(TRUE)*1000);
            
//            var_dump(__FILE__,__LINE__,[($now2-$now1),$mdatas]);
//            var_dump(__FILE__,__LINE__,[($now2-$now1),$data,$m]);
//            var_dump(__FILE__,__LINE__,[$path_to_rezd]);
//            var_dump(__FILE__,__LINE__,[($now2-$now1),$mdatas_width,$mdatas_height,$size >= $quality_limit]);
//            exit();
            
            //On crée un fichier temporaire
//            $tpa = tempnam(sys_get_temp_dir(), 'frm');
            /*
             * [DEPUIS 03-08-16]
             */
            $tpa = tempnam($this->_FILE_TRANSIT_DIR, 'frm');
            
            $video
                ->frame(TimeCode::fromSeconds($frame_at))
                ->save($tpa);
            
            
            //On récupère les données numériques de l'image que l'on place dans une variable
            $tmpfrm = "data:image/jpeg;base64,".base64_encode(file_get_contents($tpa));
            
//            echo "<img src='data:image/jpeg;base64,".base64_encode(file_get_contents($tpa))."' />";
//            exit();
            
            /*
            $tmpfrm = imagecreatefromstring(file_get_contents($tpa));
            
            $tmpfrm_rez = imagecreatetruecolor($mdatas_width, $mdatas_height);
            imagecopyresampled($tmpfrm_rez, $tmpfrm, 0, 0, 0, 0, $mdatas_width, $mdatas_height, $mdatas_width_ori, $mdatas_height_ori);
            
            // start buffering
            ob_start();
            imagejpeg($tmpfrm_rez);
            $contents =  ob_get_contents();
            ob_end_clean();
            
            $abc = "data:image/jpeg;base64,".base64_encode($contents);
            
            
//            echo "<img src='data:image/jpeg;base64,".base64_encode($contents)."' />";
//            exit();
            
//            var_dump($abc);
//            exit();
            //*/
            
            
            //On efface tous  les fichiers TEMP utilisés
            unlink($pa);
            unlink($old_name);
            unlink($new_name);
            unlink($tpa);
            unlink($tpa_rsz);
            unlink($path_to_rezd);
            /*
             * [DEPUIS 18-08-16]
             */
            unlink($rot1_path_to);
            unlink($rot2_path_to);
            
            $IMGSRVC = new SRVC_IMAGE_HANDLER(); 
            $frm_infos = $IMGSRVC->GetInfosFromBase64ImgString($tmpfrm);
//            $frm_infos = $IMGSRVC->GetInfosFromBase64ImgString($abc);
//            var_dump($frm_infos);
//            exit();
            $frm_infos["file.basename"] = pathinfo($file["name"])["filename"].".jpg";
            $frm_infos["file.filename"] = pathinfo($file["name"])["filename"];
            $frm_infos["file.data"] = "data:image/jpeg;base64,".$frm_infos["body_b64"];
                    
//            var_dump($frm_infos);
//            exit();
            /*
             * ETAPE 6 :
             *      Conglomérer les informations et les renvoyer
             */
            $infos = [
                "body"          => $data,
                "body_b64"      => $m[3],
                "type"          => strtolower($m[2]),
                "size"          => $size,
                "duration"      => $vln,
//                "width"         => $mdatas->get('width'),
//                "height"        => $mdatas->get('height'),
                "width"         => $mdatas_width,
                "height"        => $mdatas_height,
                "frame"         => $frm_infos
            ];
            
            //On restore le gestionnaire par défaut
//            restore_error_handler(); // NON ! VOIR PLUS HAUT

            return $infos;
        } catch (Exception $exc) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getMessage(),'v_d');
            //Pour pouvoir l'utiliser dans SandBox. 
            $msg = "DEBUB_BACKTRACE OMITTED BECAUSE THE POSSIBLE BIG LENGTH OF pdpic_string";
            $this->signalError ("err_sys_anyerr", __FUNCTION__, __LINE__, TRUE);
        }
    }
    
    
    private function video_create_jacket ($infos) {
        try {
            $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $infos);
            
            $width = intval($infos["width"]);
            $height = intval($infos["height"]);
            $ref_edge = ( intval($infos["width"]) >= intval($infos["height"]) ) ? intval($infos["width"]) : intval($infos["height"]);
            
            $ratio = ( $height >= $width ) ? $ref_edge/$this->_ART_SPECS["ART_IMG_EDGE_REF"] : $ref_edge/$this->_ART_SPECS["ART_IMG_EDGE_REF"];
            
            $new_width = intval($width/$ratio);
            $new_height = intval($height/$ratio);
            
            /*
             * ETAPE :
             *      On détermine les coordonnées de l'image par rapport au background
             */
            if ( intval($infos["width"]) >= intval($infos["height"]) ) {
                $offset_x = 0;
                $offset_y = ( $this->_ART_SPECS["ART_IMG_EDGE_REF"] - $new_height ) / 2;
            } else {
                $offset_x = ( $this->_ART_SPECS["ART_IMG_EDGE_REF"] - $new_width ) / 2;
                $offset_y = 0;
            }
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$width,$height,$new_width,$new_height,$offset_x,$offset_y]);
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$width,$height,$ref_edge,$ratio,$new_width,$new_height]);
            
            /*
             * ETAPE :
             *      On redimensionne l'image au côté le plus long
             */
            $data = base64_decode($infos["frame"]["body_b64"]);
            
            $prvw = imagecreatefromstring($data);
            
            $prvw_rzd = imagecreatetruecolor($new_width, $new_height);
            imagecopyresized($prvw_rzd, $prvw, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            
            /*
             * ETAPE :
             *      On crée une image avec un fond noir
             */
            $prvw_bkgd = imagecreatetruecolor($this->_ART_SPECS["ART_IMG_EDGE_REF"], $this->_ART_SPECS["ART_IMG_EDGE_REF"]);
            
            /*
             * ETAPE :
             *      On fusionne les deux images
             */
            imagecopymerge($prvw_bkgd, $prvw_rzd, $offset_x, $offset_y, 0, 0, $this->_ART_SPECS["ART_IMG_EDGE_REF"], $this->_ART_SPECS["ART_IMG_EDGE_REF"], 75);

            /*
             * [DEPUIS 19-06-16]
             * NOTE :
             *      Pour des raisons de compréhension (developpeur), je change le nom de la variable.
             */
            $new_prvw = $prvw_bkgd;
            
            // start buffering
            ob_start();
            imagejpeg($new_prvw);
            $contents =  ob_get_contents();
            ob_end_clean();
            
//            echo "<img src='data:image/png;base64,".base64_encode($contents)."' />";
//            exit();
            
            /*
             * ETAPE :
             *      On récupère les infos de la nouvelle imge PUIS on les compète.
             */
            $img_str = "data:image/jpeg;base64,".base64_encode($contents);
            $jacket_infos = $this->on_create_check_pic($img_str);
            $jacket_infos["file.basename"] = $infos["frame"]["file.basename"];
            $jacket_infos["file.filename"] = $infos["frame"]["file.filename"];
            $jacket_infos["file.data"] = $img_str;
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$infos,$jacket_infos]);
                
            /*
             * ETAPE :
             *      On remplace les informations de au sujet du FRAME par les nouvelles infos
             */
            $infos["frame"] = $jacket_infos;
            
            /*
             * ETAPE :
             *      On détruit les images en mémoire
             */
            imagedestroy($prvw);
//            imagedestroy($prvw_bkgd);
            imagedestroy($new_prvw);
            imagedestroy($prvw_rzd);
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$infos]);
//            exit();
            
            return $infos;
        } catch (Exception $ex) {

        }
    }
    
    private function cuztextbar_check_params ($params) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$params]);
        
        $xtrabar = $params;
        
        if ( array_diff(array_keys($xtrabar),["tx","cd","top"]) ) {
            return "__ERR_VOL_WRG_HACK_XTRABAR";
        }

        /*
         * ETAPE :
         *      Vérification des données
         */
        if ( empty($xtrabar["tx"]) | empty($xtrabar["cd"]) ) {
            return "__ERR_VOL_WRG_HACK_XTRABAR";
        }

        $xb_tx = $xtrabar["tx"];
        $xb_cd = strtoupper($xtrabar["cd"]);
        /*
         * ETAPE :
         *      Vérification de la taille du texte
         */
        if ( mb_strlen($xb_tx,"UTF8") > $this->_PIC_XTRABAR_SPECS["TEXT_LEN_MAX"] ) {
            return "__ERR_VOL_WRG_HACK_XTRABAR";
        }
        
        /*
         * ETAPE :
         *      Vérification de la taille du texte
         */
        if ( $xtrabar["top"] && ( $xtrabar["top"] < 0 || $xtrabar["top"] > $this->_PIC_XTRABAR_SPECS["POSITION_TOP_MAX"] ) ) {
            return "__ERR_VOL_WRG_HACK_XTRABAR";
        } else if (! isset($xtrabar["top"]) ) {
            $xb_top = $this->_PIC_XTRABAR_SPECS["POSITION_TOP_MAX"];
        } else {
            $xb_top = $xtrabar["top"];
        }

        /*
         * ETAPE :
         *      Vérification du code couleur
         */
        if (! in_array($xb_cd, $this->_PIC_XTRABAR_SPECS["COLOR_LIST"]) ) {
            return "__ERR_VOL_WRG_HACK_XTRABAR";
        }

        switch ($xb_cd) {
            case "NONE" :
                    $color_background = [
                        "red"   => 0,
                        "green" => 0,
                        "blue"  => 0,
                        /*
                         * NOTE :
                         *      Dans un monde idéal on aurait inscrit 100. 
                         *      La problème c'est que pour obtenir une parfaite transparence, il faut inscrire 127.
                         *      En effet, la "table" de transprence de la fonction qui gère ALPHA va de 0 à 127.
                         */
                        "alpha" => 127 
                    ];
                break;
            case "DEFAULT" :    
                    $color_background = [
                        "red"   => 206,
                        "green" => 59,
                        "blue"  => 59,
                        "alpha" => 70
                    ];
                break;
            case "STD_TRENQR" :
                    $color_background = [
                        "red"   => 0,
                        "green" => 69,
                        "blue"  => 137,
                        "alpha" => 60
                    ];
                break;
            case "STD_FRIEND" :
                    $color_background = [
                        "red"   => 78,
                        "green" => 147,
                        "blue"  => 220,
                        "alpha" => 65
                    ];
                break;
            case "STD_POD" :
                    $color_background = [
                        "red"   => 255,
                        "green" => 255,
                        "blue"  => 100,
                        "alpha" => 85
                    ];
                break;
            case "STD_TREND" :
                    $color_background = [
                        "red"   => 105,
                        "green" => 78,
                        "blue"  => 163,
                        "alpha" => 70
                    ];
                break;
            case "STD_BLACK" :
                    $color_background = [
                        "red"   => 0,
                        "green" => 0,
                        "blue"  => 0,
                        "alpha" => 70
                    ];
                break;
            default:
                return "__ERR_VOL_WRG_HACK_XTRABAR";
        }


        /*
         * ETAPE :
         *      On ajoute la barre de texte
         */
        $txbar_datas = [
            "text"      => $xb_tx,
            "top"       => $xb_top,
            "color_background"      => $color_background,
            "transpa_textshadow"    => 50,
        ];

//            var_dump(__FILE__,__FUNCTION__,__LINE__,$txbar_datas);
//            exit();

        return $txbar_datas;
    }
    
    
    private function cuztextbar_add_image ($infos,$txbar_datas) {
        try {
            /*
             * NOTE 
             *      Cette fonction est le résultat d'une séance de R&D.
             *      Cella m'a permit d'obtenir des constantes de référence comme FONT POLICE par comparaison.
             */
            $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$infos,$txbar_datas]);
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$txbar_datas);
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$infos);
//            exit();
            
            $width = intval($infos["width"]);
            $height = intval($infos["height"]);
            
            $txim_w = $width;
            $txim_h = ( 34 * $txim_w ) / 370;
            
            $txim = imagecreatetruecolor($txim_w, $txim_h);
        
            /*
             * [ETAPE]
             *      Création des couleurs
             */
            //BACKGROUND
            $tra1 = ( $txbar_datas["color_background"]["alpha"] ) + 10;
            $alpha1 = ( $txbar_datas["color_background"]["alpha"] === 127 ) ? 127 : intval( ( ( 127 - $tra1 ) / 127 ) * 100 );
//            var_dump($txbar_datas["color_background"]["red"], $txbar_datas["color_background"]["green"], $txbar_datas["color_background"]["blue"], $alpha1);
            imagealphablending($txim, false);
            $color_background = imagecolorallocatealpha($txim, $txbar_datas["color_background"]["red"], $txbar_datas["color_background"]["green"], $txbar_datas["color_background"]["blue"], $alpha1);
            
            //TEXT-COLOR
            $color_textcolor = imagecolorallocate($txim, 255, 255, 255);
            $tra2 = ($txbar_datas["transpa_textshadow"]) + 10;
            $alpha2 = intval( ( ( 127 - $tra2 ) / 127 ) * 100 );
            //TEXT-SHADOW
            $color_textshadow = imagecolorallocatealpha($txim, 0, 0, 0, $alpha2);
        
            /*
             * ETAPE
             *      On dessine le rectangle qui servira de background
             */
            imagefilledrectangle($txim, 0, 0, $txim_w, $txim_h, $color_background);
            imagesavealpha($txim, true);
        
            $TH = new TEXTHANDLER();
            /*
             * ETAPE :
             *      On retire les caractères UNICODE car je ne sais pas comment les traiter dans ce cas (pour l'instant)
             */
            $text = $TH->remove_emojis_in($txbar_datas["text"]);
            $font = RACINE.'/common/fonts/liberation_sans/LiberationSans-Bold.ttf';
        
            /*
             * ETAPE :
             *      On détermine approximativement la police que devrait avoir le texte 
             * NOTE :
             *      La constante 11 a été obtenu par R&D parune technique de comparaison sur une image de référence
             */
            $font_size = ( 11 * $txim_w ) / 370;
            
            $bbox = imagettfbbox($font_size,0,$font,$text);
            $bbox_h = $bbox[7] - $bbox[1];
            $bbox_w = $bbox[2] - $bbox[0];
            
            /*
             * ETAPE :
             *      On calcule les coordonnées probables de la ZONE DE TEXTE principale
             */
            $tx_x = ( ( imagesx($txim) - $bbox_w ) / 2 );
            $tx_y = ( ( imagesy($txim) - $bbox_h ) / 2 );
            
            /*
             * ETAPE :
             *      On calcule les coordonnées probables de la ZONE DE TEXTE pour le SHADOW
             */
            $txt_shad_x = $tx_x+1;
            $txt_shad_y = $tx_y+1;
        
            /*
             * ETAPE :  
             *      On ajoute le TEXTE qui fera office de SHADOW
             * NOTE - ASTUCE
             *      Activer "imagealphablending" permet d'avoir une finition "propre" au niveau du texte. 
             */
            imagealphablending($txim, true);
            imagettftext($txim, $font_size, 0, $txt_shad_x, $txt_shad_y, $color_textshadow, $font, $text);
            /*
             * ETAPE :  
             *      On ajoute le TEXTE principale
             */
            imagettftext($txim, $font_size, 0, $tx_x, $tx_y, $color_textcolor, $font, $text);
            
            /*
             * ETAPE :
             *      On crée l'image SUPPORT
             */
            $data = base64_decode($infos["body_b64"]);
            $support = imagecreatefromstring($data);
            
            /*
            // start buffering
            ob_start();
            imagepng($txim);
            $contents =  ob_get_contents();
            ob_end_clean();
            
            echo "<img src='data:image/png;base64,".base64_encode($contents)."' />";
            exit();
            //*/
            
            $top = ($txbar_datas["top"]*$height)/370;
            /*
             * ETAPE :
             *      On MERGE avec imagecopy car il gère mieux le cas de FULL TRANSPARENT
             */
//            imagecopymerge($support,$txim, 0, $txbar_datas["top"], 0, 0, $txim_w, $txim_h, 100);
            imagecopy($support,$txim, 0, $top, 0, 0, $txim_w, $txim_h);
            
            // start buffering
            ob_start();
//            imagejpeg($support,NULL,100);  //DEV, TEST : Attention à l'argument 100 qui va doubler le poids de l'image
            imagejpeg($support);
            $contents = ob_get_contents();
            ob_end_clean();
            
//            echo "<img src='data:image/jpeg;base64,".base64_encode($contents)."' />";
//            exit();
            
            /*
             * ETAPE :
             *      On récupère les infos de la nouvelle imge PUIS on les compète.
             */
            $img_str = "data:image/jpeg;base64,".base64_encode($contents);
            $newpic_infos = $this->on_create_check_pic($img_str);
            $newpic_infos["file.data"] = $img_str;
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$infos,$newpic_infos]);
//            exit();
            
            /*
             * ETAPE :
             *      On détruit les images en mémoire
             */
            imagedestroy($txim);
            imagedestroy($support);
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$infos]);
//            exit();
            
            return $newpic_infos;
        } catch (Exception $ex) {

        }
    }
    
    
    private function image_add_textbar_with_resize ($infos) {
        try {
            $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $infos);
            
            
            ob_clean();
        header('Content-Type: image/png');
        $txim = imagecreatetruecolor(740, 68);
        
        
//        $red = imagecolorallocate($im, 206, 59, 59);
        $k = 70+10;
        $alpha = (((127-$k)/127)*100);
        $red = imagecolorallocatealpha($im, 206, 59, 59, $alpha);
        $black = imagecolorallocate($im, 0, 0, 0);
        $white = imagecolorallocate($im, 255, 255, 255);
        
        imagefilledrectangle($im, 0, 0, 740, 68, $red);
        
        $black2 = imagecolorallocate($bimg, 0, 0, 0);
        imagefilledrectangle($bimg, 0, 0, 1000, 1000, $black2);
        
//        imagecolortransparent($im, $red);
//        
        $text = 'Un texte décoratif qui doit matcher';
        $font = 'LiberationSans-Bold.ttf';
//        $font = 'LiberationSans-Regular.ttf';
        
        $bbox = imagettfbbox(23,0,$font,$text);
        
        $bbox_h = $bbox[7] - $bbox[1];
        $bbox_w = $bbox[2] - $bbox[0];
        
//        $tx_x = ( ( imagesx($im) - $bbox_w ) / 2 ) - 10;
//        $tx_y = ( ( imagesy($im) - $bbox_h ) / 2 ) - 3;
        $tx_x = ( ( imagesx($im) - $bbox_w ) / 2 ) - 0;
        $tx_y = ( ( imagesy($im) - $bbox_h ) / 2 ) - 4;
        
        $txt_shad_x = $tx_x+1;
        $txt_shad_y = $tx_y+1;
//        
        //TEXT-SHADOW
//        imagettftext($im, 11, 0, $txt_shad_x, $txt_shad_y, $black, $font, $text);
        imagettftext($im, 23, 0, $txt_shad_x, $txt_shad_y, $black, $font, $text);
        //TEXT
//        imagettftext($im, 11, 0, $tx_x, $tx_y, $white, $font, $text);
        imagettftext($im, 23, 0, $tx_x, $tx_y, $white, $font, $text);
        
        $thumb = imagecreatetruecolor(370, 34);
        $ratio = 370 / 740;
        $w = 740 * $ratio;
        $h = 68 * $ratio;
        imagecopyresized($thumb, $im, 0, 0, 0, 0, $w, $h, 740, 68);
        
        imagecopymerge($bimg, $im, 0, 0, 0, 0, 1000, 1000, 100);
        
        imagepng($bimg);
        
        imagedestroy($im);
        imagedestroy($bimg);
        imagedestroy($thumb);
        
        
        
            
            $width = intval($infos["width"]);
            $height = intval($infos["height"]);
            $ref_edge = ( intval($infos["width"]) >= intval($infos["height"]) ) ? intval($infos["width"]) : intval($infos["height"]);
            
            $ratio = ( $height >= $width ) ? $ref_edge/$this->_ART_SPECS["ART_IMG_EDGE_REF"] : $ref_edge/$this->_ART_SPECS["ART_IMG_EDGE_REF"];
            
            $new_width = intval($width/$ratio);
            $new_height = intval($height/$ratio);
            
            /*
             * ETAPE :
             *      On détermine les coordonnées de l'image par rapport au background
             */
            if ( intval($infos["width"]) >= intval($infos["height"]) ) {
                $offset_x = 0;
                $offset_y = ( $this->_ART_SPECS["ART_IMG_EDGE_REF"] - $new_height ) / 2;
            } else {
                $offset_x = ( $this->_ART_SPECS["ART_IMG_EDGE_REF"] - $new_width ) / 2;
                $offset_y = 0;
            }
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$width,$height,$new_width,$new_height,$offset_x,$offset_y]);
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$width,$height,$ref_edge,$ratio,$new_width,$new_height]);
            
            /*
             * ETAPE :
             *      On redimensionne l'image au côté le plus long
             */
            $data = base64_decode($infos["frame"]["body_b64"]);
            
            $prvw = imagecreatefromstring($data);
            
            $prvw_rzd = imagecreatetruecolor($new_width, $new_height);
            imagecopyresized($prvw_rzd, $prvw, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            
            /*
             * ETAPE :
             *      On crée une image avec un fond noir
             */
            $prvw_bkgd = imagecreatetruecolor($this->_ART_SPECS["ART_IMG_EDGE_REF"], $this->_ART_SPECS["ART_IMG_EDGE_REF"]);
            
            /*
             * ETAPE :
             *      On fusionne les deux images
             */
            imagecopymerge($prvw_bkgd, $prvw_rzd, $offset_x, $offset_y, 0, 0, $this->_ART_SPECS["ART_IMG_EDGE_REF"], $this->_ART_SPECS["ART_IMG_EDGE_REF"], 75);

            
            // start buffering
            ob_start();
            imagejpeg($prvw_bkgd);
            $contents =  ob_get_contents();
            ob_end_clean();
            
//            echo "<img src='data:image/png;base64,".base64_encode($contents)."' />";
            
            /*
             * ETAPE :
             *      On récupère les infos de la nouvelle imge PUIS on les compète.
             */
            $img_str = "data:image/jpeg;base64,".base64_encode($contents);
            $jacket_infos = $this->on_create_check_pic($img_str);
            $jacket_infos["file.basename"] = $infos["frame"]["file.basename"];
            $jacket_infos["file.filename"] = $infos["frame"]["file.filename"];
            $jacket_infos["file.data"] = $img_str;
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$infos,$jacket_infos]);
                
            /*
             * ETAPE :
             *      On remplace les informations de au sujet du FRAME par les nouvelles infos
             */
            $infos["frame"] = $jacket_infos;
            
            /*
             * ETAPE :
             *      On détruit les images en mémoire
             */
            imagedestroy($prvw);
            imagedestroy($prvw_bkgd);
            imagedestroy($prvw_rzd);
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$infos]);
//            exit();
            
            return $infos;
        } catch (Exception $ex) {

        }
    }
    
    /*******************************************************************************************************************************************************/
    /****************************************************************** GETTERS & SETTERS ******************************************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS & SETTERS">

    public function getArt_oncreate_args() {
        return $this->art_oncreate_args;
    }

    public function getArtid() {
        return $this->artid;
    }


    public function getArt_eid() {
        return $this->art_eid;
    }

    public function getArt_picid() {
        return $this->art_picid;
    }

    public function getArt_pdpic_path() {
        return $this->art_pdpic_path;
    }

    public function getArt_locip() {
        return $this->art_locip;
    }

    public function getArt_creadate() {
        return $this->art_creadate;
    }


    public function setArt_client_creadate($art_client_creadate) {
        $this->art_client_creadate = $art_client_creadate;
    }

    public function getArt_client_creadate() {
        return $this->art_client_creadate;
    }

    public function getArt_desc() {
        return $this->art_desc;
    }

    public function getArt_hash_or_desc() {
        return $this->art_hash_or_desc;
    }


    public function setArt_hash_or_desc($art_hash_or_desc) {
        $this->art_hash_or_desc = $art_hash_or_desc;
    }

    public function getReg_art_desc() {
        return $this->reg_art_desc;
    }

    public function getArt_list_hash() {
        return $this->art_list_hash;
    }
    
    public function getart_list_usertags() {
        return $this->art_list_usertags;
    }
    
    public function getArt_list_uic() {
        return $this->art_list_uic;
    }

    public function getArt_pdpic_string() {
        return $this->art_pdpic_string;
    }

    public function getArt_prmlk() {
        return $this->art_prmlk;
    }


    public function setArt_prmlk($art_prmlk) {
        $this->art_prmlk = $art_prmlk;
    }

    public function getArt_list_reacts() {
        return $this->art_list_reacts;
    }

    public function getArt_rnb() {
        $rnb = ( isset($this->art_rnb) && intval($this->art_rnb) >= 0 ) ? $this->art_rnb : 0;


        return $rnb;
    }

    public function getArt_eval() {
        $eval = ( isset($this->art_eval) && is_array($this->art_eval) && count($this->art_eval) == 4 ) ? $this->art_eval : [0, 0, 0, 0];


        return $eval;
    }


    public function getArt_visitnb() {
        return $this->art_visitnb;
    }


    /* ---------------------- OWNER ------------------------ */

    public function getArt_oid() {
        return $this->art_oid;
    }


    public function getArt_ogid() {
        return $this->art_ogid;
    }

    public function getArt_oeid() {
        return $this->art_oeid;
    }

    public function getArt_ofn() {
        return $this->art_ofn;
    }

    public function getArt_opsd() {
        return $this->art_opsd;
    }


    public function getArt_oppicid() {
        return $this->art_oppicid;
    }

    public function getArt_oppic() {
        return $this->art_oppic;
    }

    public function getArt_ohref() {
        return $this->art_ohref;
    }

// </editor-fold>

}