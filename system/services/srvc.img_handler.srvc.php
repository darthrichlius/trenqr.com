<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImageHandler
 *
 * @author arsphinx
 */
class SRVC_IMAGE_HANDLER extends MOTHER {
    /**
     * Le chemin vers la zone de stockage des images. Il peut s'agir d'un serveur externe.
     * @var type 
     */
    private $__UIMG_DEFAULT_GLOBALPATH;
    /**
     * Le chemin par défaut utilisé lorsqu'on ne fournit aucun chemin aux différentes fonctions de traitement.
     * Le chemin ne correspond pas à un chemin 
     * @var String 
     */
//    private $__UIMG_DEFAULT_ORPHANS_PATH;
    /*
     * Il s'agit d'un dossier où sont stockées les images avant d'être encoyé vers le serveur de stockage adéquat
     */
    private $__UIMG_TEMP_DIR;
    
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        
        //TODO : Récupérer la donnée depuis un fichier de conf XML
        $this->__UIMG_DEFAULT_GLOBALPATH = WOS_SYSDIR_PROD_GLOBAL_USERIMG;
        //TODO : Récupérer la donnée depuis un fichier de conf XML
//        $this->__UIMG_DEFAULT_ORPHANS_PATH = WOS_SYSDIR_PROD_ORPHANSIMAGE;
        $this->__UIMG_TEMP_DIR = WOS_SYSDIR_PROD_TMP_DIR;
     }
    
    public function GetInfosFromBase64ImgString ($a) {
        if ( !isset($a) || !is_string($a) ) return;
        
        //TODO : Vérifier que la fonction existe avant de l'utiliser
        set_error_handler('exceptions_error_handler');
        try {
            
            $m = [];
            $specs = [];
            
            $p = "#^(?:data:(image)/([a-zA-Z]*);base64),([\s\S]+)#";
            
            if (! preg_match($p, $a, $m) ) {
                return "__ERR_VOL_NOT_IMAGE";
            }
            
            //On extrait le body sinon la suite du traitement se retrouvera érronée
//            $body = base64_decode($m[3]);
            $data = base64_decode($m[3]);
            
            $r = list($width, $height, $type, $attr) = getimagesizefromstring( $data );
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $toto, 'v_d');
            
            $format = ($height === $width) ? WOS_IMG_IS_SQUARE : WOS_IMG_IS_RECT;

            /* On va déterminer le taille (poids) de l'image */
   
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $type );
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, image_type_to_mime_type($type) );
//            
            //SAMPLE D'IMAGE BASE64
            /*
            $body = 'iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABl'
            . 'BMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDr'
            . 'EX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r'
            . '8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==';
            //*/
//            $data = base64_decode($body);
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $data);
            
            /* FAUX et WEIRD
            $tmpPath = "php://memory/";
            
            if ($im === false) 
                return "_VOL_ERR_STREAM_FILE_FAILED";
            
            switch ($type) {
                case IMAGETYPE_GIF : 
                       $tmpPath .= "temp.gif";
                       file_put_contents($tmpPath, base64_decode($a));
                    break;
                case IMAGETYPE_JPEG :
                       $tmpPath .= "temp.jpg";
                       imagejpeg($im, $tmpPath);
                    break;
                case IMAGETYPE_PNG :
                       $im .= "temp.png";
                       imagepng($body, $tmpPath);
                    break;
                default:
                    break;
            }
            //*/
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $data);
            //TODO : Vérifier que le fichier existe avant de lancer la procédure.
            $size = ( function_exists('mb_strlen') ) ? mb_strlen($data, '8bit') : strlen($data);
            
            if ( !isset($size) || $size == 0 || $size < 0 )
                return "__ERR_VOL_SIZE_NOTDEFINED"; 

//            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$size);
           
            $specs = [
                "nature"        => $m[1],
                "body"          => $data,
                "body_b64"      => $m[3],
                "type"          => image_type_to_mime_type($type),
                "PHP_IMAGETYPE" => $type, //IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, ...
                "size"          => $size,
                "width"         => $width,
                "height"        => $height,
                "attr"          => $attr,
                "format"        => $format //WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT;
            ];
            
            //On restore le gestionnaire par défaut
            restore_error_handler();
            
            return $specs;

        } catch (Exception $exc) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getMessage(),'v_d');
            //Pour pouvoir l'utiliser dans SandBox. 
            $msg = "DEBUB_BACKTRACE OMITTED BECAUSE THE POSSIBLE BIG LENGTH OF pdpic_string";
            $this->signalError ("err_sys_anyerr", __FUNCTION__, __LINE__, TRUE);
        }
        
    }
    
    public function Transform64stringToImage ($arg) {
        /*
         * ATTENTION : le texte représentant l'image doit au préalable avoir été décode de base64.
         * Pour cela, on peut utiliser GetInfosFromBase64ImgString()["body"];
         * 
         * La méthode n'utilise donc pas base64_decode()
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //TODO : Vérifier que la fonction existe avant de l'utiliser
        set_error_handler('exceptions_error_handler');
        try {
            //$a est généralement la partie 'body' d'un imgb64
            $img = imagecreatefromstring($arg);

            if ($img === false)
                return;
            else
                return $img;
        } catch (Exception $exc) {
            
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $exc->getMessage(), 'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $arg, 'v_d');
            //Pour pouvoir l'utiliser dans SandBox. 
            $msg = "DEBUB_BACKTRACE OMITTED BECAUSE THE POSSIBLE BIG LENGTH OF pdpic_string";
            $this->signalError ("err_sys_anyerr", __FUNCTION__, __LINE__, TRUE);
        }
        
    }
    
    public function CreateImageb64InFolder ($imgb64, $type, $fn, $path, $low_quality = NULL) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$imgb64, $type, $fn, $path]);
        
        //TODO : Vérifier que la fonction existe avant de l'utiliser
        set_error_handler('exceptions_error_handler');
        try {
            
            $cn = strlen($path);
            if ( $path[--$cn] != '/' ) {
                $path .= '/';
            }
            
            switch ($type) {
                case IMAGETYPE_PNG :
                        //header('Content-Type: image/png');
                        $path = $path . $fn . ".png";
                        if ( $low_quality ) {
                            $quality = 50;
                            if (! imagepng($imgb64, $path, $quality) ) { return; }
                        } else if (! imagepng($imgb64, $path) ) {
                            return;
                        }
                    break;
                case IMAGETYPE_JPEG :
                        //header('Content-Type: image/jpeg');
                        $path = $path . $fn . ".jpg";
                        if ( $low_quality ) {
                            $quality = 0.7;
                            if (! imagejpeg($imgb64, $path, $quality) ) { return; }
                        } else if (! imagejpeg($imgb64, $path) ) { return; }
                    break;
                case IMAGETYPE_GIF :
//                    header('Content-Type: image/gif');
                        $path = $path . $fn . ".gif";
    //                    if (! imagegif($imgb64, $path) )  //A utiliser pour la version non animée du GIF
    //                    return;
                        if (! file_put_contents($path, $imgb64) ) {
                            return;
                        }
                    break;
                default:
                    return "_ERR_VOL_EXT_NOCOMPLY";
            }
            
            
            //On restore le gestionnaire par défaut
            restore_error_handler();

            //On retourne le chemin complet vers le fichier
            return $path;
        } catch (Exception $exc) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getMessage(),'v_d');
            //Pour pouvoir l'utiliser dans SandBox. 
            $msg = "DEBUB_BACKTRACE OMITTED BECAUSE THE POSSIBLE BIG LENGTH OF pdpic_string";
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $msg,'v_d');
            $this->signalError ("err_sys_l4ain6", __FUNCTION__, __LINE__, TRUE);
        }
    }
    

    public function WriteImage ($img_infos, $srvname, $filename, $path, $low_quality = NULL) {
        //$img_infos : oblige ainsi le CALLER a appeler GetInfos; low_quality : Permet de dire qu'il faut créer les images selon une qualité basse déjà définie
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On set un handler sinon try ... catch ne fonctionnera pas.
        //TODO : Vérifier que la fonction existe avant de l'utiliser
        
        set_error_handler('exceptions_error_handler');
        try {
            
//            if ( empty($path)  ) {
                
//                $path = (! empty($path) ) ? $path : $this->__UIMG_DEFAULT_ORPHANS_PATH;
                
//                if (! file_exists($path) ) {
//                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $path, 'v_d');
//                    $this->signalError ("err_sys_l4ain7", __FUNCTION__, __LINE__, TRUE);
//                }
//            } 
            
            $img =  ( $img_infos["PHP_IMAGETYPE"] !== IMAGETYPE_GIF ) ?
                //Les fichiers GIF seront codés autrement voir lignes suivantes
                $this->Transform64stringToImage($img_infos["body"]) : $img_infos["body"];
                
            
            if ( !isset($img) ) { return; }

            //var_dump($img_infos["type"]);
               //    $pathToFile = "http://127.0.0.1/korgb/images/"; //NO ! Ne pas utiliser un chemin absolu
            
            
            /*
             * ETAPE :
             *      On vérifie si $path a déjà un séparateur ? Si oui on le retire
             */
            if ( $path[0] == '/' ) { $path = substr($path, 1); }
            
            $cn = strlen($path);
            if ( $path[--$cn] == '/' ) { $path = substr($path, 0, -1); }
            
            
            /*
             * [NOTE 14-08-14]
             * Avant on construisait le chemin.
             * Après refactorisation, on n'utilise que le chemin donné par CALLER.
             * IMGHANDLER n'est qu'un service. Ce ne n'est pas à lui de chercher à résoudre les problèmes de chemin.
             * 
             * [NOTE 09-09-14] On donne le chemin temporaire. Ce n'est que le temps de créer le fichier puis de le transférer
             */
            $pathToFile = $this->__UIMG_TEMP_DIR;

            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$path,$pathToFile], 'v_d');
            
            if (! file_exists($pathToFile) ) {
                //On tente de créer le dossier 
                if ( !mkdir($pathToFile) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $pathToFile, 'v_d');
                    $this->signalError ("err_sys_l4ain5", __FUNCTION__, __LINE__, TRUE);
                }
            }
            
            //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $img_infos["type"], 'v_d');
            /* 
             * ETAPE :
             *      Sauvegarde physique 
             */
            $filePath = $this->CreateImageb64InFolder($img, $img_infos["PHP_IMAGETYPE"], $filename, $pathToFile, $low_quality);
            if ( $filePath === "_ERR_VOL_EXT_NOCOMPLY" ) {
                return "_ERR_VOL_EXT_NOCOMPLY";
            }
                
            // *************************************************************** //
            // ************ On va transférer au niveau du serveur ************ //
            
            /*
             * ETAPE :
             *      On récupère le srvname pour lancer la procédure de connexion FTP
             */
            $FTPH = new FTP_HANDLER($srvname);
            /*
             * ETAPE :
             *      On crée une référence de fichier
             */
            $handle = fopen($filePath, "r");
            
            /*
             * ETAPE :
             *      On prépare le BON chemin avec le nom du fichier
             */
            $path = $path."/".basename($filePath);
            
            //fc : FileCreated
            $fc = $FTPH->ftp_create_file($path, $handle, TRUE);
            
            /*
             * ETAPE :
             *      On ferme le fichier pour pouvoir continuer à le manipuler
             */
            fclose($handle);
            
            //DANS TOUS LES CAS, On supprime le fichier temporaire. (CAR) Si une erreur survient on aura plus la main dessus et il deviendra fantome et prendra de la place sur le disque
            /*
             * [NOTE 09-09-14] @author L.C.
             *      POUR CONTOURNER LE PB D'ACCESS (possible ou non) AU REP POSE PAR UNLINK (Permission denied) on change momentannement de rep.
             */
            $old = getcwd(); 
            chdir(pathinfo($filePath)["dirname"]."/");
            if (! unlink($filePath) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $filePath, 'v_d');
                $this->signalError ("err_sys_l4ain9", __FUNCTION__, __LINE__, TRUE);
            }
            chdir($old); // On revient dans l'ancien repertoire 
            
            /*
             * ETAPE :
             *      On vérifie que le transfère s'est bien fait !
             */
            if (! $fc ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $path, 'v_d');
                $this->signalError ("err_sys_l4ain10", __FUNCTION__, __LINE__, TRUE);
            }
            
            /*
             * ETAPE :
             *      On ajoute le chemin physique dans le tableau des infos sur l'image
             */
            $img_infos["realpath"] = $fc;
            
            /*
             * ETAPE :
             *      On restore le gestionnaire par défaut
             */
            restore_error_handler();
            
            return $img_infos;
            
        } catch (Exception $exc) {
            /*
            var_dump(__FUNCTION__, __LINE__, $pathToFile, 'v_d');
            var_dump(__FUNCTION__, __LINE__, $exc->getMessage(),'v_d');
            exit();
            //*/
            /* *On affiche toutes les variables qui pourraient poser problème */
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$pathToFile,$exc->getMessage()], 'v_d');
            
            // On affiche le code d'erreur de résultant de l'Exception
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getMessage(),'v_d');
            //Pour pouvoir l'utiliser dans SandBox. 
            $msg = "DEBUB_BACKTRACE OMITTED BECAUSE THE POSSIBLE BIG LENGTH OF pdpic_string";
            $this->signalError ("err_sys_anyerr", __FUNCTION__, __LINE__, TRUE);
        }
    }
    
    
    /***********************************************************************************************************/
    /**************************************** GETTERS and SETTERS **********************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">

    public function get__UIMG_DEFAULT_GLOBALPATH() {
        return $this->__UIMG_DEFAULT_GLOBALPATH;
    }

    public function get__UIMG_DEFAULT_PATH() {
        return $this->__UIMG_DEFAULT_ORPHANS_PATH;
    }

    // </editor-fold>


}