<?php

/**
 * Description of ImageHandler
 *
 * @author arsphinx
 */
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;
class SRVC_VIDEO_HANDLER extends MOTHER {
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
    
    public function GetInfosFromBase64VidString ($fdata, $fname = NULL, $_with_frame = FALSE) {
        /*
         * [NOTE 20-01-16]
         *      NON ! LAISSER LE GESTIONNAIRE PAR DEFAUT GERER LES EXCEPTIONS.
         *      Ce gestionnaire personnalisé CATCH aussi les exceptions de type WARNING
         */
//        set_error_handler('exceptions_error_handler'); 
        try {
            $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$fdata]);
            
            $infos = []; $m = [];
            $vd = $fdata;
            /*
             * ETAPE 1 : 
             *      Vérifie qu'il s'agit bien d'un fichier de type video
             */
            $rgx = "#^(?:data:(video)\/([a-zA-Z\d]*);base64),([\s\S]+)#";
            if (! preg_match($rgx, $vd, $m) ) {
                return "__ERR_VOL_NOT_VIDEO";
            }

            /*
             * ETAPE : 
             *      Inforamtions : Le TYPE de la vidéo.
             */
            $type = strtolower($m[2]);

            /*
             * ETAPE : 
             *      Inforamtions : La représentation numérique de la video décode de B64.
             */
            $data = base64_decode($m[3]);
            
            /*
             * ETAPE : 
             *      Inforamtions : Le POIDS de la vidéo
             */
            $size = ( function_exists('mb_strlen') ) ? mb_strlen($data, '8bit') : strlen($data);

            /*
             * ETAPE : 
             *      Inforamtions : La DURÉE de la vidéo
             */
            $pa = tempnam(sys_get_temp_dir(), 'vid');
            $tmpvid = file_put_contents($pa,$data);
//            fseek($tmpvid, 0);

            $ffprobe = FFProbe::create();
            $vln = $ffprobe->format($pa)->get('duration');
            
            /*
             * ETAPE : 
             *      Inforamtions : Autres METADATAS sur la vidéo
             */
            $mdatas = $ffprobe->streams($pa)->videos()->first();
            
            /*
             * ETAPE :
             *      On extrait une image de la vidéo qui servira d'image de présentation, LE CAS ECHEANT.
             */
            $frm_infos = [];
            if ( $_with_frame === TRUE && $fname ) {
                
                $ffmpeg = FFMpeg::create();
                $video = $ffmpeg->open($pa);

                //On crée un fichier temporaire
                $tpa = tempnam(sys_get_temp_dir(), 'frm');
                $video
                    ->frame(TimeCode::fromSeconds(1))
                    ->save($tpa);

                //On récupère les données numériques de l'image que l'on place dans une variable
                $tmpfrm = "data:image/jpeg;base64,".base64_encode(file_get_contents($tpa));

                //On efface les fichiers
                unlink($pa);
                unlink($tpa);

                $IMGSRVC = new SRVC_IMAGE_HANDLER(); 
                $frm_infos = $IMGSRVC->GetInfosFromBase64ImgString($tmpfrm);
                $frm_infos["file.basename"] = pathinfo($fname)["filename"].".jpg";
                $frm_infos["file.filename"] = pathinfo($fname)["filename"];
                $frm_infos["file.data"] ="data:image/jpeg;base64,".$frm_infos["body_b64"];
            
            }
            
//            var_dump($frm_infos);
//            exit();
            
            /*
             * ETAPE :
             *      Conglomérer les informations et les renvoyer
             */
            $infos = [
                "body"          => $data,
                "body_b64"      => $m[3],
                "type"          => strtolower($m[2]),
                "size"          => $size,
                "duration"      => $vln,
                "width"         => $mdatas->get('width'),
                "height"        => $mdatas->get('height'),
                "frame"         => $frm_infos ? $frm_infos : null
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
    
    public function fSaveInFolder ($vdata, $type, $fn, $path) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, [$vdata, $type, $fn, $path]);
        
        //TODO : Vérifier que la fonction existe avant de l'utiliser
        set_error_handler('exceptions_error_handler');
        try {
            
            $cn = strlen($path);
            if ( $path[--$cn] != '/' ) {
                $path .= '/';
            }
            
            switch ($type) {
                case "mp4" :
                        //header('Content-Type: image/png');
                        $path = $path.pathinfo($fn)["filename"].".mp4";
                        
                        file_put_contents($path,$vdata);
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
    

    public function WriteImage ($vid_infos, $srvname, $filename, $path) {
        //$img_infos : oblige ainsi le CALLER a appeler GetInfos; low_quality : Permet de dire qu'il faut créer les images selon une qualité basse déjà définie
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On set un handler sinon try ... catch ne fonctionnera pas.
        //TODO : Vérifier que la fonction existe avant de l'utiliser
        
        set_error_handler('exceptions_error_handler');
        try {
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$vid_infos, $srvname, $filename, $path]);
//            exit();
            
            $vdata = $vid_infos["body"];

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
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$img_infos, $srvname, $filename, $path]);
//            exit();

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
            $filePath = $this->fSaveInFolder($vdata, $vid_infos["type"], $filename, $pathToFile); 
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$filePath]);
//            exit();
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
            $vid_infos["realpath"] = $fc;
            
            /*
             * ETAPE :
             *      On restore le gestionnaire par défaut
             */
            restore_error_handler();
            
            return $vid_infos;
            
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