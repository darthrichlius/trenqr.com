<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of srvc
 *
 * @author arsphinx
 */
class FTP_HANDLER extends MOTHER {
    //FTP SCOPE
    private $ftp_hostname;
    private $ftp_host;
    private $ftp_port;
    private $ftp_username;
    private $ftp_password;
    private $ftp_timeout;
    
    private $ftp_cnxid;
    private $cxstr;
    
    //FILE SCOPE
    private $filename;
    private $filepath;
    
    //CLASS
    /*
     * Défini si la classe de Service est prête à être utilisée.
     * Cette valeur dépend de l'etat de connexion.
     */
    private $is_ready;
   
    function __construct($srvname) {
        //We ensure that until the mother this class is innit.  
        parent::__construct( __FILE__, __CLASS__);
        
        if ( isset($srvname) and is_string($srvname) and $srvname != "" ){
            $this->dbname = $srvname;
        } else {
            $this->signalError ("err_sys_l029", __FUNCTION__, __LINE__);
        }
        
        $this->is_ready = FALSE;
        
        $this->tryConnection($srvname);
        
    }
    
    /************************************************************************************************************************************/
    /********************************************************** FTP SCOPE ***************************************************************/

    private function tryConnection ($srvname) {
        /*
         * Permet d'établir de façon effective une connexion à un serveur FTP.
         * Cette méthode utilise la méthode try_login() et celle qui prépare les identifiants de connexions
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $srvname);
        
        //On préparer les données nécessaire à une connexion. Ces données correspondent au server passé en paramètre
        if (! $this->prepareConxParams($srvname) ) {
            $this->signalError ("err_sys_l030", __FUNCTION__, __LINE__);
        }
        
        //On tente la connexion
        $cnx_id = ftp_connect($this->ftp_host, $this->ftp_port) 
        or die( $this->signalErrorWithoutErrIdButGivenMsg( print_r([debug_print_backtrace(),"<br/><br/><span style='color: red; font-weight: bold;'><-- SEPARATOR --></span><br/><br/>","Impossible de se connecter au serveur FTP : '$this->ftp_host'"]), __FUNCTION__, __LINE__, TRUE)   ); 
        
        //On tente une identification
        $cnx_id = $this->tryLogin($cnx_id, $this->ftp_username, $this->ftp_password);
        
        //On "stocke" l'identifiant de connexion
        $this->ftp_cnxid = $cnx_id;
        
        /*
         * [DEPUIS 17-11-15] @author BOR
         */
        $this->srvname = $srvname;
        
        $this->is_ready = TRUE;
        
    }
    
    private function prepareConxParams ($srvname) {
        //Permet de charger les propriétés nécessaires à la connexion FTP
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $srvname);
        
        switch ( strtolower($srvname) ) {
            case "lisa1" :
                /* [ DESCIPTION ]
                     * Celle qui sait tout faire et qui a le cerveau pour. C'est la première de la classe.
                     *      
                     * Ce type de serveur gère la couche métier d'un produit.
                     * Cependant, il est aussi capable d'héberger des bases de données tant qu'elle ne sont pas trop lourdes ou que les accès ne sont pas trop fréquenets.
                     * Ou encore, pour alléger la charge des serveurs de type MARGE
                     * 
                     * RAPPEL : CE SERVICE NE SERT QU'A SE CONNECTER ET A INTERAGIR AVEC LES HOTES FTP ET NON LES BASES DE DONNEES
                     */
                     $this->ftp_hostname = "trenqr.com";
                     $this->ftp_host = "ftp.trenqr.com";
                     $this->ftp_username = "trenqrcola";
                     $this->ftp_password = "@72UraNIUM-37!";
//                     $this->ftp_password = "TqrVille.69";
                     $this->ftp_port = 21;
                break;
            case "marge1" :
                    /* [ DESCIPTION ]
                     * Celle qui gère le "warehouse". 
                     * Ce type de serveur sert à stocker des fichiers qui seront utilisés par un autre serveur.
                     * Mais encore, il sert aussi à héberger les bases de données de premier plan.
                     * 
                     * Par exemple :
                     *  -> Les images liées aux Articles
                     *  -> Les images de profil
                     *  -> Les images de courverture
                     *  ->  ...
                     * 
                     * RAPPEL : CE SERVICE NE SERT QU'A SE CONNECTER ET A INTERAGIR AVEC LES HOTES FTP ET NON LES BASES DE DONNEES
                     */
                     $this->ftp_hostname = "trenqr.com";
                     $this->ftp_host = "ftp.trenqr.com";
                     $this->ftp_username = "trenqrcola";
                     $this->ftp_password = "@72UraNIUM-37!";
                     $this->ftp_port = 21;
                /*
                    $this->ftp_hostname = "ycgkit1.com";
                    $this->ftp_host = "ftp.ycgkit1.com";
                    $this->ftp_username = "ycgkitcoai";
                    $this->ftp_password = "Geek.iT-69";
                //*/
                    $this->ftp_port = 21;
                break;
            case "bart1" :
                    /* [ DESCIPTION ]
                     * Celui a qui il vaut mieux demander le moins possible au risque qu'il fasse des bétises
                     * Ce type de serveur sert à gérer des produits ou des services annexex au produits de première catégorie.
                     * Par exemple :
                     *  -> Les fichiers externes
                     *  -> Le site de l'entreprise
                     *  -> Le blog d'un produit
                     *  ->  ...
                     * 
                     * Dans le cas où le service ou le produit devient trop lourd, important ou gourmand il pourra être tranféré sur un serveur de type Lisa.
                     * 
                     * RAPPEL : CE SERVICE NE SERT QU'A SE CONNECTER ET A INTERAGIR AVEC LES HOTES FTP ET NON LES BASES DE DONNEES
                     */
                     $this->ftp_hostname = "trenqr.com";
                     $this->ftp_host = "ftp.trenqr.com";
                     $this->ftp_username = "trenqrcola";
                     $this->ftp_password = "@72UraNIUM-37!";
                     $this->ftp_port = 21;
                     /*
                    $this->ftp_hostname = "ycgkit.com";
                    $this->ftp_host = "ftp.ycgkit.com";
                    $this->ftp_username = "ycgkitcodj";
                    $this->ftp_password = "Geek.It.69";
                    $this->ftp_port = 21;
                    //*/
                break;
            case "localhost" :
                    /*
                     * [DEPUIS 17-11-15] @author BOR
                     *      Utiliser dans le cas où on exécute l'opération en local (DEBUG, DEV, TEST)
                     */
                     $this->ftp_hostname = "127.0.0.1";
                     $this->ftp_host = "localhost";
                     $this->ftp_username = "looka";
                     $this->ftp_password = "toto123";
                     $this->ftp_port = 21;
                break;
            default :
                    return FALSE;
                break;
        }
        
        return TRUE;
    }
    
    private function tryLogin ($ftp_stream, $ftp_user, $ftp_pwd) {
        /*
         * Permet de lancer un processus d'identification au niveau d'un serveur FTP dont la connexion a préalablement été établie.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        // Tentative d'identification
        if (! @ftp_login($ftp_stream, $ftp_user, $ftp_pwd) ) {
            $this->signalErrorWithoutErrIdButGivenMsg( print_r([debug_print_backtrace(),"<br/><br/><span style='color: red; font-weight: bold;'><-- SEPARATOR --></span><br/><br/>","Conexion impossible sur FTP en tant que : <span style='color: red; font-weight: bold;'>'$ftp_user@$this->ftp_host'</span>"]), __FUNCTION__, __LINE__, TRUE );
        }
        
        //On construit une chaine de connexion
        $this->cxstr = "ftp://$this->ftp_username:$this->ftp_password@$this->ftp_host";
        
        return $ftp_stream;
        
    }
    
    /************************************************************************************************************************************/
    /********************************************************** FILE SCOPE **************************************************************/
    
    public function ftp_dir_exists ($dirpath) {
        /*
         * Permet de vérifier si un repertoire existe sur un serveur distant dont la connexion a préalablement été établie.
         */
        if (! $this->is_ready ) return;
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie la conformité et en récupère certains caractères
        $str_tab = $this->is_real_path($dirpath);
        if (! $str_tab ) {
            return;
        }
        
        //On vérifie si la chaine commence bien par '/' sinon on AJOUTE
        if ( $str_tab[0] !== "/" ) {
            $dirpath = "/".$dirpath;
        }
        
        //On vérifie que la chaine N'A PAS DE '/' à la fin. Sinon on ENLEVE
        if ( $str_tab[1] === "/" ) {
            $dirpath = substr($dirpath, 0, -1);
        }
        
        //On construit le chemin
        $rp = $this->cxstr.$dirpath;
                
        if ( is_dir($rp) ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function ftp_file_exists ($filepath) {
        /*
         * Permet de vérifier si un repertoire existe sur un serveur distant dont la connexion a préalablement été établie.
         */
        if (! $this->is_ready ) {
//            echo __LINE__;
            return;
        }
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie la conformité et en récupère certains caractères
        $str_tab = $this->is_real_path($filepath);
        if (! $str_tab ) {
//            echo __LINE__;
            return;
        }
        
        //On vérifie si la chaine commence bien par '/' sinon on AJOUTE
        if ( $str_tab[0] !== "/" ) {
            $filepath = "/".$filepath;
        }
        
        //On construit le chemin
        $rp = $this->cxstr.$filepath;
//        $rp = "ftp://trenqrcola:@72UraNIUM-37!@ftp.trenqr.com/www/marge1/tqim/article/12fqj05be98/9k7gl5go2jb_3c393j311h_9k7gl5ho33h.jpg";
        
//        var_dump($filepath,[is_file($rp)]);
//        $filepath = "/www/marge1/tqim/article/12fqj05be98/";
//        var_dump([$this->ftp_cnxid,$filepath,ftp_rawlist($this->ftp_cnxid,$filepath)]);
                
        if ( is_file($rp) ) {
//            echo __LINE__;
            return TRUE;
        } else {
//            var_dump(__FILE__,__LINE__,[$rp]);
            return FALSE;
        }
        
    }
    
    public function ftp_create_file ($filepath, $file, $dir_force_create = FALSE) {
        set_error_handler('exceptions_error_handler');
        try {
            
        
            //$dir_force_create : Permet de dire que si le dossier n'existe pas, il faut le créer
            /*
             * Permet de créer un fichier sur un serveur distant.
             * D'un point de vue purement technique, il s'agit
             */
            $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
            
            if (! $this->is_ready ) return;
            
            $tmp = pathinfo($filepath)['dirname'];
            //On vérifie la conformité et en récupère certains caractères
            $str_tab = $this->is_real_path($tmp);
            if (! $str_tab ) {
                return;
            }
            
            //On vérifie si la chaine commence bien par '/' sinon on AJOUTE
            if ( $str_tab[0] !== "/" ) {
                $tmp = "/".$tmp;
                $filepath = "/".$filepath;
            }
            //On vérifie que la chaine N'A PAS DE '/' à la fin. Sinon on ENLEVE
            if ( $str_tab[1] === "/" ) {
                $tmp = substr($tmp, 0, -1);
            }
            
            //On vérifie si le dossier existe
            if ( !$this->ftp_dir_exists($tmp) && !$dir_force_create ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $tmp, 'v_d');
                $this->signalError ("err_sys_l012", __FUNCTION__, __LINE__, TRUE);
            } else if ( !$this->ftp_dir_exists($tmp) && $dir_force_create ) {
                //On crée dossier
                if (! ftp_mkdir($this->ftp_cnxid, $tmp) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["A échouer après une tentative de création du repertoire ! ", $tmp], 'v_d');
                    $this->signalError ("err_sys_l012", __FUNCTION__, __LINE__, TRUE);
                }
            }
            
             // Activation du mode passif
            ftp_pasv($this->ftp_cnxid, true);
            
            $r = ftp_fput($this->ftp_cnxid, $filepath, $file, FTP_BINARY);

            if (! $r ) {
                return FALSE;
            } else {
                //** On crée l'URL complète du fichier **//
                /*
                 * [DEPUIS 17-11-15] @author BOR
                 *      On prend en compte le cas LOCAL
                 */
                if ( $this->srvname === "localhost" ) {
                    $url = "http://".$this->ftp_hostname.$filepath;
                } else {
                    //On récupère le "sous-domaine"
                    $pieces = explode("/", $filepath);

                    $sd = $pieces[1];
                    $z = array_slice($pieces,2); 

                    /*
                     *  [DEPUIS 03-07-16]
                     *      L'objectif étant de limiter la dépendance à l'égard d'un domaine en particulier.
                     */
//                    $url = "http://".$sd.".".$this->ftp_hostname."/".implode("/", $z);
                    $url = "/".implode("/", $z);
                }
                
                return $url;
            }
        } catch (Exception $exc) {
//            var_dump(__FUNCTION__, __LINE__, $exc->getMessage());
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["A échouer après une tentative de création du repertoire ! ", $tmp], $exc->getMessage(), 'v_d');
            $this->signalError ("err_sys_l012", __FUNCTION__, __LINE__, TRUE);
            exit();
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $exc->getMessage(), 'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $arg, 'v_d');
            //Pour pouvoir l'utiliser dans SandBox. 
            $msg = "DEBUB_BACKTRACE OMITTED BECAUSE THE POSSIBLE BIG LENGTH OF pdpic_string";
            $this->signalError ("err_sys_anyerr", __FUNCTION__, __LINE__, TRUE);
        }
    }
    
    public function ftp_rename_file ($filepath, $newfn, $strict = FALSE) {
        //strict : Permet de définit s'il faut obliger la méthode à renommer le fichier même si le nom est la même.
        /*
         * Permet de vérifier si un repertoire existe sur un serveur distant dont la connexion a préalablement été établie.
         * 
         * [ NOTE ]
         * ATTENTION => IL NE FAUT PAS DONNER LE CHEMIN SOUS FORME ftp://
         * IL FAUT DONNER LE CHEMIN DEPUIS LA RACINE DU SERVEUR SUR LEQUEL LA CONNEXION C'EST FAITE !
         */
        if (! $this->is_ready ) return;
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$filepath,$newfn]);
        
        //On vérifie si le fichier existe
        if (! $this->ftp_file_exists($filepath) ) {
            return;
        }
        
        //On vérifie que la variable pour le nouveau nom est conforme
        if (! ( $newfn && is_string($newfn) && strlen($newfn) ) ) {
            return;
        }
        
        $oldfn = basename($filepath);
        
        //On vérifie si le nouveau nom n'est pas déjà celui du fichier actuellement sur le serveur FTP
        if ( $newfn === $oldfn && !$strict ) {
            //On ne effectue pas l'opération. Cela fait gagner du temps à tout le monde
            return TRUE;
        } //Si le mode strict est activé, on procède à la modification
        
        //On crée un chemin pour le nouveau fichier
        $tmp = pathinfo($filepath)['dirname'];
        
        //On vérifie la conformité et en récupère certains caractères
        $str_tab = $this->is_real_path($tmp);
        if (! $str_tab ) {
            return;
        }
        
        //On vérifie si la chaine commence bien par '/' sinon on AJOUTE
        if ( $str_tab[0] !== "/" ) {
            $tmp = "/".$tmp;
        }
        //On vérifie que la chaine N'A PAS DE '/' à la fin. Sinon on ENLEVE
        if ( $str_tab[1] === "/" ) {
            $tmp = substr($tmp, 0, -1);
        }
        
        $o_rp = "/".$filepath;
        $n_rp = $tmp."/".$newfn;
        
//        var_dump($o_rp,$n_rp);
//        exit();
        
        if ( ftp_rename($this->ftp_cnxid, $o_rp, $n_rp) ) {
            return TRUE;
        }
        
        return FALSE;
        
    }
    
    public function ftp_delete_file ($filepath) {
        /*
         * Permet de supprimer un fichier sur un serveur distant dont la connexion a préalablement été établie.
         */
        if (! $this->is_ready ) return;
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie si le fichier existe
        if (! $this->ftp_file_exists($filepath) ) {
            return;
        }
        
        if ( ftp_delete($this->ftp_cnxid, $filepath) ) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    private function is_real_path ($str) {
        if (! ( isset($str) && is_string($str) && $str != "" ) ) {
            return;
        }
        
        $ln = strlen($str);
        
        //On récupère le premier et le dernier caractère de la chaine
        $str_tab[0] = $str[0];
        $str_tab[1] = $str[--$ln];
        
        return $str_tab;
    }
    
    /*********************************************************************************************************************************************/
    /*********************************************************************************************************************************************/
    /********************************************************** GETTERS and SETTERS **************************************************************/
    
    public function getFtp_hostname() {
        return $this->ftp_hostname;
    }

    public function getFtp_host() {
        return $this->ftp_host;
    }

    public function getFtp_port() {
        return $this->ftp_port;
    }

    public function getFtp_username() {
        return $this->ftp_username;
    }

    public function getFtp_password() {
        return $this->ftp_password;
    }

    public function getFtp_timeout() {
        return $this->ftp_timeout;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getFilepath() {
        return $this->filepath;
    }

    public function getIs_ready() {
        return $this->is_ready;
    }

    public function getCxstr() {
        return $this->cxstr;
    }

    
}
