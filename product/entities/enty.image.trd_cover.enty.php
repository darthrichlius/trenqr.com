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
class IMAGE_COVERTR extends IMAGE {
    
    private $tcov_pdpicid;
    private $tcov_teid;
    private $tcov_width;
    private $tcov_height;
    private $tcov_top;
    
    private $needeed;
    
    function __construct() {
        
        $_MAX_HEIGHT = 5500;
        $_MIN_HEIGHT = 260;
        $_MAX_WIDTH = 5500;
        $_MIN_WIDTH = 260;
        $_ALLOWED_EXT = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
        $_MAX_SIZE = 1048576 * 2.5; //Pour changer la valeur il suffit de changer le multiplicateur. 1048576 = 1Mo
        $_DIMS_FORMAT = WOS_IMG_ALLFORMAT; //NULL, WOS_IMG_IS_SQUARE, WOS_IMG_IS_RECT, WOS_IMG_ALLFORMAT
                
        parent::__construct(__FILE__, __CLASS__." (OR IMAGE)", $_MAX_HEIGHT, $_MIN_HEIGHT, $_MAX_WIDTH, $_MIN_WIDTH, $_ALLOWED_EXT, $_MAX_SIZE, $_DIMS_FORMAT);
        
        $this->needeed = ["tcov_teid","cov_w","cov_h","cov_t"];
        
        $this->entity_path = WOS_SYSDIR_PROD_COVER_TR;
        $this->_ART_SRV_TABLE = [
            2 => TQR_DEFAULT_SERVER_NAME,
            4 => "localhost"
        ];
        
    }

    /************************************ ON_CREATE *****************************************/
    
    
    public function on_create ($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On va (aussi) vérifier que les données attendues par IMAGE et ART_IMAGE sont présentes. Cela évite d'attendre que IMAGE gueule parce qu'il manque des choses.
        $needed = array_merge($this->needeed, ["pdpic_fn","pdpic_string"]);
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
            
            //Vérifier que la Tendance est toujours valide
            $TRD = new TREND();
            $t_tab = $TRD->exists($args["tcov_teid"]);
            
            if (! $t_tab ) {
                return "__ERR_VOL_TRD_GONE";
            }
            
            $this->tcov_teid= $args["tcov_teid"];
            
            //Créer chemin
            $args["pdpic_path"] = $this->entity_path.$args["tcov_teid"];
            
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
             * Il est normalement IMPOSSIBLE que $r ne soit pas défini.
             * Il contient le pdpic_eid de l'IMAGE ajoutée.
             */
            $infos =  parent::on_create_entity($args);
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, $ids,'v_d');
            
            if (! ( is_array($infos) && !empty($infos) ) && ( key_exists("picid", $infos) ) ) 
                    return;
            
            /* 
             * On va créer l'occurrence de l'image COVER_TR.
             * Créer un tableau ici est aussi une manière détournée de revérifier si les valeurs existent.
             * Du moins les clés !
             *  */
            //Le path qui va servir pour le rename de l'image
            $npath = $args["pdpic_path"];
            $nargs = [
                "picid" => $infos["picid"],
                "tcov_tid" => $t_tab["trid"],
                "tcov_teid" => $args["tcov_teid"],
                "tcov_height" => $args["cov_h"],
                "tcov_width" => $args["cov_w"],
                "tcov_top" => $args["cov_t"],
                "pdpic_eid" => $infos["pdpic_eid"],
                "pdpic_creadate" => $infos["pdpic_creadate"],
                "pdpic_realpath" => $infos["pdpic_realpath"],
                "srvid" => $args["srvid"],
                "npath" => $npath
            ];
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $nargs,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//            exit();
            
            $id = $this->write_new($nargs);
            
            $nargs["tcovid"] = $id;
            $nargs["pdpic_realpath"] = $this->pdpic_realpath;
            
            return $nargs;
                
        } else {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$needed],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",  array_keys($args)],'v_d');
            $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__,TRUE);
        }
    }
    
    /************************************ ON_DELETE *****************************************/
    
    public function on_delete ($args, $_OPTIONS) {
        /*
         * Permet de supprimer l'image de couverture active.
         */
        $TRD = new TREND();
        
        /*
         * ETAPE : 
         * On intéroge le serveur pour savoir si la Tendance existe toujours au moment de l'opération.
         */
        if (! ( $_OPTIONS && in_array("FAST_WAY", $_OPTIONS) ) ) {
            $t_tab = $TRD->on_read_entity(["trd_eid" => $args]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t_tab) ) {
                $this->Ajax_Return("err",$t_tab);
            } else if (! $t_tab ) {
                return "__ERR_VOL_TRD_GONE";
            }
        } else {
            $t_tab = $TRD->exists($args);
        }
        
        /*
         * ETAPE : 
         * On vérifie que la Tendance a bien une image de Couverture.
         */
        $cvds = $TRD->onload_trend_get_trend_cover($t_tab["trid"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cvds) ) {
            $this->Ajax_Return("err",$cvds);
        } else if (! $cvds ) {
            $this->Ajax_Return("err","__ERR_VOL_NOTGT");
        }
        $tcv_pci = $cvds["pdpicid"];
            
        /*
         * ETAPE :
         * On supprime dans la table TRCOV et TRCOV_HISTY. 
         */
        $QO = new QUERY("qryl4trcovn6");
        $params = array(":trid" => $t_tab["trid"]);
        $QO->execute($params);

        /*
         * ETAPE :
         * On supprime l'image dans la base de données et de manière physique
         */
        return parent::on_delete_entity($tcv_pci);
    }
    
    
    /************************************ ON_READ *****************************************/

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
        /*
         * TODO : Si des requetes échouent, cela peut entrainer que certaines données seront orphelines.
         * Pour l'heure, le système de nettoyage n'est pas opérationnel. Aussi, on ne pourra pas y faire grand chose.
         * Cependant, on pourra modifier cette section au besoin, en fonction de l'expérience en utilisation réelle.
         */
        
        //On crée le prod_fn
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->pdpic_art_eid, TQR_DEFAULT_SERVER_NAME, $args["pdpic_creadate"], $args["picid"]], 'v_d');
        $pdpic_prod_fn = $this->imgname_encode($this->tcov_teid, TQR_DEFAULT_SERVER_NAME, $args["pdpic_creadate"], $args["picid"]);
        
        //On vérifie si au moins une occurrence existe dans la table History
        $QO = new QUERY("qryl4trcovn1");
        $params = array(":trid" => $args["tcov_tid"]);
        $old_files = $QO->execute($params);
        
        $tcovid = NULL;
        if ( $old_files ) {
            /*
             * On supprime physiquement l'ancienne image. En effet, c'est un procédé qui rend l'opération plus fiable.
             * Il sera toujours plus facile de traiter le cas où l'image physique manque que de chercher dans plusieurs fichiers
             * toutes les images qui n'ont pas d'attache dans la base de données.
             */
            if ( $old_files && is_array($old_files) && count($old_files) ) {
            
                foreach ($old_files as $pic_tab) {
                
                    $srvnm = $pic_tab["srv_name"];
                    $FTH = new FTP_HANDLER($srvnm);
                    if ( $FTH->ftp_file_exists($pic_tab["pdpic_full_ftppath"]) ) {

                        if (! $FTH->ftp_delete_file($pic_tab["pdpic_full_ftppath"]) ) {
                            //TODO : Mettre dans une table regroupant toutes les erreurs de suppression d'images qui devront être supprimées plus tard.
//                            return "__ERR_VOL_DELIMG_FAILED";
                            //ON NE FAIT POUR L'INSTANT, l'occurrence dans la base de données sera quand même supprimée.
                        }
                    }
                }
            
            }
            
            //On supprime toutes les anciennes occurrences (si elles existent) car on ne sauvegarde pas les anciennes images à la version vb1.10.14
            /*
             * TODO : Changer la requete, en effet, il faudra soit supprimer UNE occurrence (et non toutes) ...
             * ... Soit mettre à jour l'occurrence.
             */
            $QO = new QUERY("qryl4trcovn2");
            $params = array(":trid" => $args["tcov_tid"]);
            $QO->execute($params);
            
        }
        
        //Enregistrer l'image dans la table des IMAGE_COVERTR
        $QO = new QUERY("qryl4trcovn4");
        $params = array(
            ":picid" => $args["picid"],
            ":cov_w" => $args["tcov_width"],
            ":cov_h" => $args["tcov_height"],
            ":cov_t" => $args["tcov_top"]
        );
        $tcovid = $QO->execute($params);
        
        if ( $tcovid ) {
            $time = round(microtime(TRUE)*1000);
            //On crée une occurrence dans la base de données dans la table History
            $QO = new QUERY("qryl4trcovn5");
            $params = array(
                ":tcovid" => $tcovid,
                ":trid" => $args["tcov_tid"],
                ":date" => date("Y-m-d G:i:s",($time/1000)), 
                ":time" => $time
            );
            $QO->execute($params);
            
            //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->imgname_decode("995f99_2b313e371h_7fbbjo1"));
            //On set prod_fn en renvoyant le chemin complet vers l'ancien fichier afin de correctement modifier le nom du fichier
            if (! $this->setpdpic_prod_fn($args["picid"], $pdpic_prod_fn, $args["pdpic_realpath"], $this->_ART_SRV_TABLE[$args["srvid"]], $args["npath"]) ) {
                return;
            }

            //TODO : On load
            $load = [ "pdpic_eid" => $args["pdpic_eid"] ]; 
            parent::load_entity($load);

            return $tcovid;
            
        } else {
            return "__ERR_VOL_FAILED";
        }
        
    }
    /***********************************************************************************************/
    /*************************************** SPECS *************************************************/
    public function valid ( $pdpic_string, &$pdpic_infos ) {
        
        parent::check_img_compliance ($pdpic_string, $pdpic_infos);
        
    }
    
    
    /************************************************************************************************/
    /*********************************** GETTERS and SETTERS ****************************************/
    // <editor-fold defaultstate="collapsed" desc="GEETERS and SETTERS">
    public function getTcov_pdpicid() {
        return $this->tcov_pdpicid;
    }

    public function getTcov_teid() {
        return $this->tcov_teid;
    }

    public function getTcov_width() {
        return $this->tcov_width;
    }

    public function getTcov_height() {
        return $this->tcov_height;
    }

    public function getTcov_top() {
        return $this->tcov_top;
    }

// </editor-fold>




}
