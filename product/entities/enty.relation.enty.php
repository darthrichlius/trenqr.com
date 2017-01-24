<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enty
 *
 * @author arsphinx
 */
class RELATION extends PROD_ENTITY {
    //Vous devez suivre l'utilisateur visé depuis <b>au moins une semaine</b>.,Vous devez vous suivre mutuellement depuis <b>au moins 24 heures</b>.,Vous devez avoir <b>au moins 2 amis en commun</b>.",
    private $tbrel_id;
    private $tbrel_acc_actor;
    private $tbrel_acc_target;
    private $tbrel_relsts;
    private $tbrel_relsts_code;
    private $tbrel_relsts_fecode;
    private $tbrel_datecrea;
    private $tbrel_datecrea_tstamp;
    private $tbrel_dateend;
    private $tbrel_dateend_tstamp;
    private $tbrel_relevt;
    
    /*** RULES ***/
    private $_REL_COM_FRD;
    private $_REL_EVENT_TYPES;
    private $_FAR_RULE_USED;
    private $_REL_STATUS;
    private $_REL_START_EVENT;
    private $_ERR_VOL_TABLE;
    
    
    function __construct() {
        
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["tbrel_id","tbrel_acc_actor","tbrel_acc_target","tbrel_relsts","tbrel_relsts_code","tbrel_relsts_fecode","tbrel_datecrea","tbrel_datecrea_tstamp","tbrel_dateend","tbrel_dateend_tstamp","tbrel_relevt"];
        $this->needed_to_loading_prop_keys = ["tbrel_id","tbrel_acc_actor","tbrel_acc_target","tbrel_relsts","tbrel_relsts_code","tbrel_relsts_fecode","tbrel_datecrea","tbrel_datecrea_tstamp","tbrel_dateend","tbrel_dateend_tstamp","tbrel_relevt"];
        /*
         * [NOTE 28-08-14] @author Lou Carther <lou.carther@deuslynn-entreprise.com>
         * OLD -> J'ai retiré 'relsts' car ça devenait ambigue. Il doit être par défaut à 's_folw'.
         *      Ce n'est pas logique que de créer une Relation autre que 's_folw'.
         *      L'attribution du type de Relation est faite en interne !!
         */
//        $this->needed_to_create_prop_keys = ["acc_actor","acc_target","relsts"]; 
        $this->needed_to_create_prop_keys = ["acc_actor","acc_target"];
        $this->needed_to_alter_prop_keys = ["actor","target","trigger_eventid","new_relsts","curr_tbrel_id"];
        
        /*
         * Le code du type d'évènement lors de la création d'une (première) nouvelle RELATION.
         * On le met sous forme de "constante" pour pouvoir effectuer des modifications centralisées.
         */
        $this->_REL_START_EVENT = "folw";
        /*
         * On a les codes tels qu'ils apparaissent dans la base de données ainsi que leur id.
         * Avoir les ids à porter de main permet d'inscrire le bon identifiant lors des Ajouts ou Modifications dans les tables.
         * Cela diminue aussi fortement le taux d'erreurs probables.
         */
        $this->_REL_EVENT_TYPES = ["folw" => "1", "ufolw" => "2", "frdask" => "3", "frdask_rjt" => "4", "frdask_acpt" => "5", "ufrd" => "6"];
        $this->_REL_STATUS = ["s_folw" => "1", "d_folw" => "2", "frd" => "3", "void" => "4"];
        /**
         * Code des éléments qui permettent de dire qu'elle a été utilisée par un utilisateur pour être autorisé à faire une demande d'amis.
         * "s_folw_7d" => "L'utilisateur suit la Cible depuis au moins 7 jours complets"
         * "d_folw_1d" => "L'utilisateur et la cible se suivent depuis au moins 24 heures. Cela évite de permettre des demandes par erreur mais permet aussi de valoriser la relation dite "Amis""
         * "comfrd" => "L'utilisateur et la cible ont des amis en commun. Par défaut à la vb1 ce nombre est de 2"
         */
        $this->_FAR_RULE_USED = ["s_folw_7d" => "1", "d_folw_1d" => "2", "comfrd" => "3"];
        $this->_ERR_VOL_TABLE = ["__ERR_VOL_SAME_PROTAS", "__ERR_VOL_ACC_GONE", "__ERR_VOL_ACC_ATR_GONE", "__ERR_VOL_ACC_TGT_GONE", "__ERR_VOL_ATLEAST_ONE_GONE", "__ERR_VOL_NO_REL", "__ERR_VOL_RL_XSTS", "__ERR_VOL_RL_DF_XSTS", "__ERR_VOL_RL_FRD_XSTS", "__ERR_VOL_NO_FRD", "__ERR_VOL_VOID_REL", "__ERR_VOL_ALDY_FRD", "__ERR_VOL_ACT_RQT_PDG", "__ERR_VOL_TGT_RQT_PDG", "__ERR_VOL_FRDRSQT_NOT_FOUND", "__ERR_VOL_FRRUL_MSM"];
        
        //RAPPEL : Pour les règles, voir méthodes qui s'en occupe pour lire l'énnoncé
        /* 
         * Le nombre d'amis en commum necessaire pour autoriser une demande d'amis.
         * Le but étant de valoriser les connexions de type "Amis" pour éviter toute banalisation.
         * Nous voulons que chaque utilisateur sur TQr ait beaucoup d'amis sans pour autant en faire quelque chose de banale.
         * 
         * TODO : Ce nombre pourra être modifié manuellement par l'utilisateur pour atteindre jusqu'à 10.
         */
        $this->_REL_COM_FRD = 3;
        
    }
    
    protected function build_volatile($args) {}

    public function exists($args) {
        //QUESTION : Existe t-il une Relation entre Actor et Target ? Si oui, je veux toutes les informations pertinentes sur cette relation
        
        $actor_id = $target_id = NULL;
        if (! ( is_array($args) && count($args ) )
                && ( ( key_exists("actor", $args) && !empty($args["actor"]) ) && ( key_exists("target", $args) && !empty($args["target"]) ) ) ) 
        {
            if ( !empty($this->tbrel_acc_actor) && !empty($this->tbrel_acc_target) ) {
                $actor_id = $this->tbrel_acc_actor;
                $target_id = $this->tbrel_acc_target;
            } else {
                return;
            }
        } else {
            $actor_id = $args["actor"];
            $target_id = $args["target"];
        }
        
        /*
         * On vérifie qu'il s'agit bien de deux comptes différents
         */
        if ( floatval($actor_id) === floatval($target_id) ) {
             return "__ERR_VOL_SAME_PROTAS";  
        }
        
        /*
         * On vérifie si les Protas existent toujours
         */
        $r = $this->check_if_both_protas_exist($actor_id, $target_id);
        if (! $r ) {
            return "__ERR_VOL_ATLEAST_ONE_GONE";
        }
        
        /*
         * Contacter la base de données et vérifier si la Relation existe.
         */
        $QO = new QUERY("qryl4reln1");
        $params = array( 
            ':actor1'   => $actor_id, 
            ':target1'  => $target_id, 
            ':actor2'   => $actor_id, 
            ':target2'  => $target_id );
        $datas = $QO->execute($params);
        
//        var_dump(__LINE__,__FUNCTION__,$datas);
//        exit();
        if ( $datas ) {
//            if ( count($datas) > 1 ) {
//                return "__ERR_VOL_FAILED";
//            }
            $r = $datas[0];
        } else {
            $r = FALSE;
        }
        
        return $r;
    }

    protected function init_properties($datas) {
        //[NOTE 23-08-14] : La démarche peut être améliorée et raccourcie en utilisant array_diff
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
            
            if ( count($this->needed_to_loading_prop_keys) != count(array_intersect(array_keys($datas), $this->needed_to_loading_prop_keys)) ) {
                
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED => ",$this->needed_to_loading_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ",array_keys($datas)],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>",  array_diff($this->needed_to_loading_prop_keys, array_keys($datas))],'v_d');
                $this->signalError ("err_sys_l4comn5", __FUNCTION__, __LINE__,TRUE);
            } 
            
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

    protected function load_entity($arg, $std_err_enbaled = FALSE) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $arg, TRUE);
        
        //Intéroger la base de données pour récupérer les données sur la RELATION
        $QO = new QUERY("qryl4reln5");
        $params = array( ':id' => $arg );
        $datas = $QO->execute($params);
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$exists,$trend,$datas],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
        if ( ( !$datas || count($datas) > 1 ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$datas || count($datas) > 1 ) && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $Rel = $datas[0];
        
        $loads = [
            'tbrel_id'              => $Rel["tbrel_id"],
            'tbrel_acc_actor'       => $Rel["tbrel_acc_actor"],
            'tbrel_acc_target'      => $Rel["tbrel_acc_targ"],
            'tbrel_relsts'          => $Rel["tbrel_relsts"],
            'tbrel_relsts_code'     => $Rel["relsts_code"],
            'tbrel_relsts_fecode'   => $Rel["relsts_fecode"],
            'tbrel_datecrea'        => $Rel["tbrel_datecrea"],
            'tbrel_datecrea_tstamp' => $Rel["tbrel_datecrea_tstamp"],
            'tbrel_dateend'         => $Rel["tbrel_dateend"],
            'tbrel_dateend_tstamp'  => $Rel["tbrel_dateend_tstamp"],
            'tbrel_relevt'          => $Rel["tbrel_relevt"]
        ];
        
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
            $this->init_properties($loads);
            $this->is_instance_load = TRUE;
            return $loads;
        }
        
        //Just in CASE !!
        return $loads;
        
    }

    /**
     * ["actor","target","trigger_eventid","new_relsts"]
     * @param type $args
     * @return type
     */
    protected function on_alter_entity($args) { 
        /*
         * Permet de modifier la relation entre deux protagonistes.
         * La modification se fait en deux étapes :
         *      (1) On annule la Relation active
         *      (2) On crée la nouvelle modification
         * 
         * Enfin, on renvoie l'identifiant de la nouvel Relation. Cela pourra par exemple permettre au CALLER de Load l'Entity ou d'effectuer d'autres tâches connexes.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //(02-12-14) Ajout de 'curr_tbrel_id' pour les besoins de mise à jour de VM
        //On vérifie la présence des données obligatoires :["actor","target","trigger_eventid","new_relsts","curr_tbrel_id"]
        $com  = array_intersect( array_keys($args), $this->needed_to_alter_prop_keys);
        if ( count($com) != count($this->needed_to_alter_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_alter_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        }
        
        $now = round(microtime(TRUE)*1000);
        $isautobuild = ( key_exists("isautobuild", $args) && isset($args["isautobuild"]) && ( $args["isautobuild"] === 1 || $args["isautobuild"] === TRUE ) ) ? 1 : 0;
        
        //(1) On annule la Relation active
        $QO = new QUERY("qryl4reln11");
        $params = array(
            ":actor1"   => $args["actor"], 
            ":target1"  => $args["target"], 
            ":actor2"   => $args["actor"], 
            ":target2"  => $args["target"], 
            ":tstamp"   => $now, 
            ":date"     => date("Y-m-d G:i:s",($now/1000)), 
            ":relevt"   => $args["trigger_eventid"]
        );
        $QO->execute($params);
        
        //On met à jour la ligne dans VM
        //or = OpeResult
        $o_or = $this->onalter_SyncWmRel($args["curr_tbrel_id"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $o_or) ) {
            //TODO : Mettre le compte en maintenance car on a besoin que la ligne soit créée au niveau de VM si non on craint un dysfonctionnement général. PUT_ACC_MAINTENANCE (Repère de recherche)
            $this->signalError ("err_sys_l4reln3", __FUNCTION__, __LINE__, TRUE);
        }
        
        //(2) On insère la nouvelle ligne représentant la Relation
        $QO = new QUERY("qryl4reln4");
        $params = array(":actor" => $args["actor"], ":target" => $args["target"], ":rel_type" => $args["new_relsts"], ":isautobuild" => $isautobuild, ":tstamp" => $now, ":eventid" => $args["trigger_eventid"]);
        $n_relid = $QO->execute($params);
        
        //On crée la ligne dans VM
        //or = OpeResult
        $n_or = $this->oncreate_SyncWmRel($n_relid);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $n_or) ) {
            //TODO : Mettre le compte en maintenance car on a besoin que la ligne soit créée au niveau de VM si non on craint un dysfonctionnement général. PUT_ACC_MAINTENANCE (Repère de recherche)
            $this->signalError ("err_sys_l4reln3", __FUNCTION__, __LINE__, TRUE);
        }
        
        return $n_relid;
    }

    public function on_create_entity( $args, $std_err_enabled = FALSE ) {
        //RAPPEL : Le CALLER doit envoyer les identifiants et non eid des comptes
        /*
         * La méthode ne crée qu'un seul type de relation : la relation de type FOLLOW.
         * Pour les autres relations se reporter à la partie FRIEND SCOPE.
         * 
         * [NOTE 28-08-14] L.C.
         * Le seul évènement accepté ici est folw !!
         * Aussi le CALLER n'envoie rien car par défaut c'est 'folw' Sinon d'autres méthodes existent
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : ["acc_actor","acc_target"]
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_create_prop_keys],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        $actor = $Protas["actor"] = $args["acc_actor"];
        $target = $Protas["target"] = $args["acc_target"];
        
        //On vérifie qu'il s'agit bien de deux comptes différents
         if ( intval($actor) === intval($target) ) {
             return "__ERR_VOL_SAME_PROTAS";
         }
         
        //On vérifie qu'une relation n'existe pas déjà entre les protagonistes
        $exists = $this->exists($Protas);
//        var_dump(__LINE__,$exists);
//        exit();
        if ( isset($exists) && is_array($exists) ) {
            //On vérifie le type de la relation et on agit selon le cas
            $rel = strtolower($exists["relsts_code"]);
            switch ($rel) {
                case "s_folw":
                        // On vérifie si l'acteur de l'action encours est le même que celui de la relation à créer
                        if ( intval($exists["tbrel_acc_actor"]) === intval($actor) ) {
                            return "__ERR_VOL_RL_XSTS"; 
                        } else {
                            //On fait évoluer la relation vers d_folw
                            $r = $this->oncreate_upgrade($actor, $target, $exists["tbrel_id"]);
                            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                                return $r;
                            }
                            
                            //Pur formalisme
                            $id = $r;
                            
                            //On load l'Entity avec le nouvel id
                            return $this->load_entity($id);
                        }
//                        echo "STOP!";
//                        exit();
                    break;
                case "d_folw":
                    return "__ERR_VOL_RL_DF_XSTS"; 
                case "frd":
                    return "__ERR_VOL_RL_FRD_XSTS";
                case "void":
                        //On continue vers la création
                        /*
                         * [NOTE 09-05-15] @BOR
                         * Cette partie du code n'est pas cohérente. 
                         * En effet, on devrait annuler la dernière ligne VOID au risque de se retrouver avec plusieurs occurrences non NULL.
                         * D'ailleurs, ce qu'on peut appeler "faute de conception", provoque des bogues au niveau du module SEARCHBOX.
                         * Je vais donc modifier le code en conséquence tout en étant vigilant pour détecter des "bogues de regression".
                         * En effet, je reste intrigué par mon ancien code. 
                         * L'ajout est effectué après que l'évenement soit créé
                         */
                    break;
                default:
                        //ERROR
                        if ( $std_err_enabled ) {
                            $this->signalError("err_sys_l4reln2", __FUNCTION__, __LINE__,TRUE);
                        } else {
                            return "__ERR_VOL_UXPTD";
                        }
                    break;
            }
            
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $exists) ) {
            return $exists;
        } else {
            if ( $exists === FALSE ) {
                //On continue
            } else if ( $exists === NULL ) {
                //On n'a pas envoyé un tableau contenant "actor" et "target" comme clé.
                
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                $this->signalError ("err_sys_l4reln1", __FUNCTION__, __LINE__,TRUE);
            }
        }

        $event_type = $this->_REL_START_EVENT;
        //On crée la ligne de l'évènement 
        $evid = $this->oncreate_create_event($args["acc_actor"], $args["acc_target"], $event_type);
        /*
         * [DEPUIS 10-05-15] @BOR
         * On annule les anciennes Relations actives de type 'VOID'.
         * L'opération est placée ici car on a besoin de l'identifiant de l'évènement.
         */ 
        if ( !empty($exists) && !empty($rel) && strtolower($rel) === "void" ) {
            $now = round(microtime(TRUE)*1000);
            $QO = new QUERY("qryl4reln11");
            $params = array(
                ":actor1"   => $args["acc_actor"], 
                ":target1"  => $args["acc_target"], 
                ":actor2"   => $args["acc_actor"], 
                ":target2"  => $args["acc_target"], 
                ":tstamp"   => $now, 
                ":date"     => date("Y-m-d G:i:s",($now/1000)), 
                ":relevt"   => $evid
            );
            $QO->execute($params);

            /*
             * On met à jour la ligne dans VM.
             */
            //or = OpeResult
            $o_or = $this->onalter_SyncWmRel($exists["tbrel_id"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $o_or) ) {
                //TODO : Mettre le compte en maintenance car on a besoin que la ligne soit créée au niveau de VM si non on craint un dysfonctionnement général. PUT_ACC_MAINTENANCE (Repère de recherche)
                $this->signalError ("err_sys_l4reln3", __FUNCTION__, __LINE__, TRUE);
            }
        }
        
        $args["eventid"] = $evid;
        //Le code sera revalidé par la méthode de création de la RELATION
        $args["relsts"] = "s_folw";
                
                
        //On crée la ligne dans TableRelations
        $relid = $this->write_new_in_database($args);
        
        //On crée la ligne dans VM
        //or = OpeResult
        $or = $this->oncreate_SyncWmRel($relid);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $or) ) {
            //TODO : Mettre le compte en maintenance car on a besoin que la ligne soit créée au niveau de VM si non on craint un dysfonctionnement général. PUT_ACC_MAINTENANCE (Repère de recherche)
            $this->signalError ("err_sys_l4reln3", __FUNCTION__, __LINE__,TRUE);
        }
        
        //On load la classe
        return $this->load_entity($relid);
    }

    public function on_delete_entity($args) { }

    public function on_read_entity($args) {}

    protected function write_new_in_database($args) {
        //$actor, $target, $type, $event
        /*
         * Permet de créer une Relation entr de type RELATION.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie que le type est bien défini
        if (! in_array($args["relsts"], array_keys($this->_REL_STATUS)) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXCEPTED => ", array_keys($this->_REL_STATUS)],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args["relsts"]],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__,TRUE);
        }
        
        /*
         * On fait la transformation du code vers l'identifiant
         */
        $relsts = $this->_REL_STATUS[$args["relsts"]];
        
        /*
         *  On crée l'évnèment au niveau de la base de données 
         */
        $now = round(microtime(TRUE)*1000);
        $isautobuild = ( key_exists("isautobuild", $args) && isset($args["isautobuild"]) && $args["isautobuild"] === 1 ) ? 1 : 0;

        $QO = new QUERY("qryl4reln4");
        $params = array(":actor" => $args["acc_actor"] , ":target" => $args["acc_target"], ":rel_type" => $relsts, ":isautobuild" => $isautobuild, ":eventid" => $args["eventid"], ":tstamp" => $now);
        $id = $QO->execute($params);
        
        return $id;
    }
    
    /***************************************************************************************************************************************************/
    /***************************************************************************************************************************************************/
    /************************************************************ SPECEFIC SCOPE ***********************************************************************/
    
    private function check_if_both_protas_exist ($actor, $target) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //NOTE : Plutot qu'utiliser deux fois Exists de la classe Prddb_Account, on utiliser une requete qui vérifie l'existence des deux Prota. On fait ainsi une pierre deux coups.
        $QO = new QUERY("qryl4reln2");
        $params = array(":actor" => $actor, ":target" => $target);
        $datas = $QO->execute($params);
        
        if (! ( is_array($datas) && count($datas) === 2 ) ) {
            return FALSE;
        } else {
            return TRUE;
        }
        
    }
    
    /*****************************************************************************************************************************/
    /************************************************** FOLLOW SCOPE (start)  ****************************************************/
        
    public function onread_relation_exists_fecase ($curruser, $target) {
        /* IMPORTANT : ON compare par rapport aux id et non aux eid
         * Permet de savoir si une relation existe entre les Protagonistes. L'un est l'utilisateur actif.
         * A la différence de la méthode exists(), celle-ci renvoie un code qui prend en compte celui qui est ACTOR dans l'histoire.
         * 
         * [NOTE 18-10-14] @author L.C. 
         *  L'utilisation de la conversion en b23 a été abondonné. CALLER ou tout autre entité devra passer par les fonctions encode_relcode()/decode_relcode()
         *  Les codes en eux mêmes restent valables.
         *  Ils vont même être enrichis avec de nouveaux codes dans les versions futures ou dès que le développement l'exigera.
         * 
         * 
         * A partir de cela, il renvoie un des codes ci-dessous :
         *      _REL_o_O (40098) : Ils n'ont jamais été en contact
         *      _REL_HOME (b734l9) : C'est le propriétaire
         *      _REL_CFO (29el5) : CurrentUserFollowsOWner
         *      _REL_OFC (2jbh2) : CurrentUserFollowedbyOWner
         *      _REL_FEO (...) : CurrentAndOWnerFollowing-EachOthers
         *      _REL_FRD (2c4k6) : They are freinds
         *      _REL_CBO (29e3j) : CurrentUserBlocksOWner
         *      _REL_OBC (2jamg) : CurrentUserBlockedbyOWner
         *      _REL_VOID (db3j4e) : No more Realtion
         *      
         *      (!!! POUR LA CONVERSION, on prend _REL_(OWN). On respecte la casse et on convertie en ASCII puis en base23
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( floatval($curruser) === floatval($target) ) {
            return "_REL_HOME";
        }
        
        $args = [
            "actor"     => $curruser,
            "target"    => $target
        ];
        
        $rel = $this->exists($args);
        
        if ( !isset($rel) ) {
            return;
        } else if ( $rel === FALSE ) {
            return "_REL_o_O";
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rel) ) {
            return $rel;
        } else {
            //RAPPEL : $this->_REL_STATUS = ["s_folw" => "1", "d_folw" => "2", "frd" => "3", "void" => "4"];
            
            $temp = array_flip($this->_REL_STATUS);
            
            //On vérifie le type renvoyé
            $cr = $temp[$rel["tbrel_relsts"]];
            $r__;
            switch ($cr) {
                case "s_folw" :
                        $r__ = ( floatval($curruser) === floatval($rel["tbrel_acc_actor"]) ) ?  "_REL_CFO" : "_REL_OFC";
                    break;
                case "d_folw" :
                        $r__ = "_REL_FEO";
                    break;
                case "frd" :
                        $r__ = "_REL_FRD";
                    break;
                case "void" :
                        $r__ = "_REL_VOID";
                    break;
                default:
                    //IMPOSSIBLE !
                    return "__ERR_VOL_UXPTD";
            }
            
            //PARANO : FINALLY
            return $r__;
        }
        
    }
    
    public function encode_relcode ($code) {
        /*
         * Permet de convertir un code REL de la version serveur à la version FE.
         * La version FE est plus difficile a comprendre pour l'oeil humain non inité.
         * Cependant, il reste un outils puissant pour décrire la relation entre deux utilisateurs d'un premier coup d'oeil.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        /*
         * RULES :
         *  X : Lettre pour symboliser la Liaison
         *  R : (Relation) Pour être plus explicite
         *  [NOMBRE 1] Ce lui qui fait ou a fait l'action
         *      Il s'agit de la personne qui a initié la relation 
         *      (OU) La personne qui le premier à initié la relation
         *      REGLES : 
         *          0: Données non disponible ou oscultée
         *          1: CU a initié l'action
         *          2: L'action a été initié par la Cible. La "cible" désigne le plus souvent OW.
         *  [.] En attente
         *  [NOMBRE 2] Le type de la relation.
         *  Le code est assez similaire à celui provenant de la base de données
         */
        /*
         * [NOTE 18-10-14] @author L.C.
         * Cette méthode existe car la précédente était peu fiable.
         *  (1) Il suffisait de convertir pour avoir un texte en clair
         *  (2) Le développeur aurait eu du mal à le comprendre facilement ce qui aurait pu le ralentir
         *  (3) Il fallait mettre en place un système de conversion lourd
         *  (4) Tout changement aurait été plus difficile à gérer que ce type de codage 
         */
         
        $nc = NULL;
        switch ($code) {
            case "_REL_o_O" :
                //Ils n'ont jamais été en contact
                    $nc = "xr00";
                break;
            case "_REL_HOME" :
                // C'est le propriétaire
                    $nc = "xrh";
                break;
            case "_REL_SF" :
                //OneOfProtasFollowsOther
                    $nc = "xr01";
                break;
            case "_REL_CFO" :
                //CurrentUserFollowsOWner
                    $nc = "xr11";
                break;
            case "_REL_OFC" :
                //CurrentUserFollowedbyOWner
                    $nc = "xr21";
                break;
            case "_REL_FEO" :
                //CurrentAndOWnerFollowing-EachOthers
                    $nc = "xr02";
                break;
            case "_REL_FEO_CU" :
                //CurrentAndOWnerFollowing-EachOthers
                //CU began
                    $nc = "xr12";  
                break;
            case "_REL_FEO_TR" :
                //CurrentAndOWnerFollowing-EachOthers
                //Target began
                    $nc = "xr22";
                break;
            case "_REL_FRP" :
                //FP : FriendRequestPending
                //OneOfProtasAskedOther
                    $nc = "xr.3";
                break;
            case "_REL_FRP_CU" :
                //FP : FriendRequestPending
                //OneOfProtasAskedOther
                //by CU
                    $nc = "xr.13";
                break;
            case "_REL_FRP" :
                //FP : FriendRequestPending
                //OneOfProtasAskedOther
                //by TR
                    $nc = "xr.23";
                break;
            case "_REL_FRD" :
                //They are freinds
                    $nc = "xr03";
                break;
            case "_REL_FRD_CU" :
                //They are freinds
                //by CU
                    $nc = "xr13";
                break;
            case "_REL_FRD_TR" :
                //They are freinds
                //by TR
                    $nc = "xr23";
                break;
            /*
            case "_REL_BO" :
                //OneOfProtasBlockedOther
                    $nc = "xr05";
                break;
            case "_REL_CBO" :
                //CurrentUserBlocksOWner
                    $nc = "xr15";
                break;
            case "_REL_OBC" :
                //CurrentUserBlockedbyOWner
                    $nc = "xr25";
                break;
            //*/
            case "_REL_VOID" :
                //No more Realtion
                    $nc = "xr04";
                break;
            case "_REL_VOID_CU" :
                //No more Realtion
                //Because of CU
                    $nc = "xr14";
                break;
            case "_REL_VOID_TR" :
                //No more Realtion
                //Because of TR
                    $nc = "xr24";
                break;
            default :
                    "__ERR_VOL_WRG_DATAS";
                break;
        }
        
        return $nc;
    }
    
    public function decode_relcode ($code) {
        /*
         * Permet de convertir un code REL de la version FE à la version serveur.
         * La version FE est plus difficile a comprendre pour l'oeil humain non inité.
         * Cependant, il reste un outils puissant pour décrire la relation entre deux utilisateurs d'un premier coup d'oeil.
         * 
         * Pour plus d'informations (doc) @see encode_relcode();
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());

        $nc = NULL;
        switch ($code) {
            case "xr00" :
                //Ils n'ont jamais été en contact
                    $nc = "_REL_o_O";
                break;
            case "xrh" :
                // C'est le propriétaire
                    $nc = "_REL_HOME";
                break;
            case "xr01" :
                //OneOfProtasFollowsOther
                    $nc = "_REL_SF";
                break;
            case "xr11" :
                //CurrentUserFollowsOWner
                    $nc = "_REL_CFO";
                break;
            case "xr21" :
                //CurrentUserFollowedbyOWner
                    $nc = "_REL_OFC";
                break;
            case "xr02" :
                //CurrentAndOWnerFollowing-EachOthers
                    $nc = "_REL_FEO";
                break;
            case "xr12" :
                //CurrentAndOWnerFollowing-EachOthers
                //CU began
                    $nc = "_REL_FEO_CU";
                break;
            case "xr22" :
                //CurrentAndOWnerFollowing-EachOthers
                //Target began
                    $nc = "_REL_FEO_TR";
                break;
            case "xr.3" :
                //FP : FriendRequestPending
                //OneOfProtasAskedOther
                    $nc = "_REL_FRP";
                break;
            case "xr.13" :
                //FP : FriendRequestPending
                //OneOfProtasAskedOther
                //by CU
                    $nc = "_REL_FRP_CU";
                break;
            case "xr.23" :
                //FP : FriendRequestPending
                //OneOfProtasAskedOther
                //by TR
                    $nc = "_REL_FRP";
                break;
            case "xr03" :
                //They are freinds
                    $nc = "_REL_FRD";
                break;
            case "xr13" :
                //They are freinds
                //by CU
                    $nc = "_REL_FRD_CU";
                break;
            case "xr23" :
                //They are freinds
                //by TR
                    $nc = "_REL_FRD_TR";
                break;
            /*
            case "xr05" :
                //OneOfProtasBlockedOther
                    $nc = "_REL_BO";
                break;
            case "xr15" :
                //CurrentUserBlocksOWner
                    $nc = "_REL_CBO";
                break;
            case "xr25" :
                //CurrentUserBlockedbyOWner
                    $nc = "_REL_OBC";
                break;
            //*/
            case "xr04" :
                //No more Realtion
                    $nc = "_REL_VOID";
                break;
            case "xr14" :
                //No more Realtion
                //Because of CU
                    $nc = "_REL_VOID_CU";
                break;
            case "xr24" :
                //No more Realtion
                //Because of TR
                    $nc = "_REL_VOID_TR";
                break;
            default :
                    "__ERR_VOL_WRG_DATAS";
                break;
        }
        
        return $nc;
    }
    
    
    public function onread_commons_followers_list ($user1, $user2) {
        /*
         * La méthode permet d'obtenir une liste contenant les followers en communs entre les deux Protas en présence.
         */
    }
        
    public function onread_commons_following_list ($user1, $user2) {
        /*
         * La méthode permet d'obtenir une liste contenant les followers en communs entre les deux Protas en présence.
         */
    }
    
    public function onread_get_urel_if_exists ($actor, $target) {
        /*
         * Renvoie une chaine représentant la relation entre deux utilisateurs passé en paramètre.
         * Cette chaine est compréhensible par les modules au niveau de FE.
         * 
         * Les codes sont :
         *      .-> _REL_o_O : Ils n'ont jamais été en contact
         *      .-> _REL_HOME : C'est le propriétaire
         *      .-> _REL_CUFOW : CurrentUserFollowsOWner
         *      .-> _REL_CUFDOW : CurrentUserFollowedbyOWner
         *      .-> _REL_DF : Both users are following each other.
         *      .-> _REL_FRD : They are freinds
         *      .-> _REL_CUBOW : CurrentUserBlocksOWner (Plus tard)
         *      .-> _REL_CUBDOW : CurrentUserBlockedbyOWner (plus tard)
         *      .-> _REL_VOID : No more Realtion
         * 
         * !!! ATTENTION !!!
         * Les codes seront ensuite codés en ASCII- > b23 pour les envoyés au niveau du FE
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( !( !is_array($actor) && ( is_integer($actor) || is_string($actor) ) ) 
               || !( !is_array($target) && ( is_integer($target) || is_string($target) ) ) ) {
            return;
        }
        
        /*
         * On vériie si on est dans le cas où les deux Protas sont en fait le même compte.
         * RAPPEL : Cela peut être possible si l'utilisateur est sur sa page.
         */
        
        $PA = new PROD_ACC();
        
        $exists = $PA->exists_with_id($actor);
        if (! $exists) {
            return "__ERR_VOL_ACC_ATR_GONE";
        }   
        
        $exists = $PA->exists_with_id($target);
        if (! $exists) {
            return "__ERR_VOL_ACC_TGT_GONE";
        }   
        
        if ( intval($actor) === intval($target) ) {
            //On signifie qu'il s'agit de la même personne
            return "_REL_HOME";
        }
        
        $exist = $this->check_if_both_protas_exist($actor, $target);
        if ( !$exist ) {
            return "__ERR_VOL_ATLEAST_ONE_GONE"; //la réponse n'est pas très précise. CALLER n'a qu'à vérifier avant de nous envoyer les ids.
        }
        
        $args = [
            "actor" => $actor,
            "target" => $target
        ];
        
        $exists = $this->exists($args);
        
//        var_dump($exists);
        
        if ( $exists === NULL ) {
            return "__ERR_VOL_UXPTD";
        } else if ( $exists === FALSE ) {
            return "_REL_o_O";
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $exists) ) {
            return $exists;
        } else if ( isset($exists) && is_array($exists) && count($exists) ) {
            $rel_actor = $exists["tbrel_acc_actor"];
            $rel_target = $exists["tbrel_acc_targ"];
            $relsts = $exists["tbrel_relsts"];
            $invs = array_flip($this->_REL_STATUS);
            
            
            //["s_folw" => "1", "d_folw" => "2", "frd" => "3", "void" => "4"];
            
            if ( $invs[$relsts] === "frd" ) {
                return "_REL_FRD";
            } else { 
                
                switch ($invs[$relsts]) {
                    case "void":
                           return "_REL_VOID";
                        break;
                    case "s_folw":
                            if ( intval($actor) === intval($rel_actor) ) {
                                return "REL_CUFOW";
                            } else {
                                return "_REL_CUFDOW";
                            }
                        break;
                    case "d_folw":
                            return "_REL_DF";
                        break;
                    default:
                            //[NOTE 31-09-14] A cette date je ne sais pas gérer ce cas correctement.
                            return "__ERR_VOL_UXPTD";
                        break;
                }
            }
        }
    }
    
    /**
     * Cette méthode permet de "dégradé" la Relation entre deux Protas. Il s'agit d'effectuer une action de type Unfolow
     * @param type $actor Celui qui veut Unfollow
     * @param type $target Le compte qui va se faire Unfollow
     */
    public function onalter_downgrade_relation ($actor, $target) {
        //RAPPEL : L'action ici sera forcement 'ufolw'
        /*
         * Cette méthode permet d'arreter une relation qu'on a avec un autre utilisateur.
         * La méhtode ne permet pas de changement de la relation mais crée l'évènement qui le permettra. 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie qu'il s'agit bien de deux comptes différents
        if ( intval($actor) === intval($target) ) {
             return "__ERR_VOL_SAME_PROTAS";  
        }
        
        //On vérifie qu'une relation existe avant effectivement lancer la création de l'évènement
        $exists = $this->exists([ "actor" => $actor , "target" => $target ]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $exists) ) {
            return $exists;
        } else if ( $exists === FALSE ) {
            return "__ERR_VOL_NO_REL";
        } else {
            if (! ( is_array($exists) && count($exists) ) ) {
                return "__ERR_VOL_UXPTD";
            }
        }
        
        $ori_event_id = $this->oncreate_create_event($actor, $target, "ufolw");
        //$ori_event_id = "fake_id"; //FOR TEST, DEBUG. ATTENTION : Cela va entrainer un bug car ATER on aura besoin pour créer la nouvelle Relation
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ori_event_id) ) {
            return $ori_event_id;
        } 
        
        $r = $this->onalter_handle_downgrade_relation($actor, $target, $ori_event_id, "ufolw");
        
//        var_dump($r);
//        exit();
        //On vérifie qu'il ne s'agit pas d'une erreur
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            return $r;
        }
        
        //On load la Relation
        return $this->load_entity($r);
    }
    
    private function onalter_handle_downgrade_relation ($actor, $target, $origin_event_id, $action, $std_err_enabled = FALSE) {
        //RAPPEL : La fonciton est en mode privée pour obliger a ce qu'on passe par une fonction qui crée un évènement valide.
        /*
         * Cette méthode permet de Downgrader une relation de manière intelligente.
         * Elle recoit l'action effectuée par l'utilisateur et en fonction du statut actuel de la Relation elle change la Relation de manière appropriée.
         * 
         * 
         * (VBeta1)
         * L'action ne peut être que "ufolw" ou "ufrd. 
         * On traite aussi le cas de FRIEND car les relations ne sont pas mises à jour automatiquement.
         * Si un utilisateur décide de "ufolw" un autre et que l'autre aussi le fait, il faudra passer à VOID. 
         * La méthode break() de Friend ne permet pas de choisir intelligement la futur relation 
         * 
         * 
         * !!! -- NOTE -- !!!
         * On a l'impression qu'on utilise des fois plusieurs le même code pour la simple et bonne raison que le code n'est pas toujours le même.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On s'assure que l'action est connue
        if ( !in_array($action, array_keys($this->_REL_EVENT_TYPES)) || ( $action !== "ufolw" && $action !== "ufrd" ) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXCEPTED => ", array_keys($this->_REL_EVENT_TYPES)],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $action],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__,TRUE);
        }
        
        //* On récupère les informations sur la relation actuelle *//
        $exist_tab = [
            "actor"     => $actor,
            "target"    => $target
        ];
        
        $exists = $this->exists($exist_tab);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $exists) ) {
            return $exists;
        } else if ( $exists === FALSE ) {
            return "__ERR_VOL_NO_REL";
        } else {
            if (! ( is_array($exists) && count($exists) ) ) {
                return "__ERR_VOL_UXPTD";
            }
        }
        
        //On récupère le tableau et on flip. Cela va nous permettre de travailler avec des versions facilement compréhensibles par un humain
        $invs = array_flip($this->_REL_STATUS);
        
        $c_rel = strtolower($invs[$exists["tbrel_relsts"]]);
        $c_rel_actor = $exists["tbrel_acc_actor"];
        $c_rel_target = $exists["tbrel_acc_targ"];
        
//        var_dump($c_rel, $c_rel_actor, $c_rel_target);
//        exit();
        
        switch ($c_rel) {
            case "s_folw":
                     if ( $action === "ufrd" ) {
                        return "__ERR_VOL_NO_FRD"; 
                    } else if ( $action === "ufolw" ) {
                                
                        if ( intval($c_rel_actor) === intval($actor) && intval($c_rel_target) === intval($target) ) {
                            $args = [
                                "actor" => $actor,
                                "target" => $target,
                                "trigger_eventid" => $origin_event_id,
                                "new_relsts" => $this->_REL_STATUS["void"],
                                "curr_tbrel_id" => $exists["tbrel_id"]
                            ];
                            
                            $id = $this->on_alter_entity($args);
                        
                            /*
                             * [NOTE 19-10-14] @author L.C.
                             * On supprime les demandes d'amis restées muetes envoyées par ACTOR à TARGET
                            */
                            $this->onalter_ondowngrade_cancel_frqt($actor,$target);

                            return $id;
                            
                        } else if ( intval($actor) === intval($c_rel_target) ) {
                            /*
                             * Avant d'aboutir à une erreur de type inconnue, on vérifie s'il ne s'agit pas du cas où c'est le A suit B mais c'est B qui veut ufolw A.
                             * En d'autres termes que ACTOR est en fait le TARGET de la relation actuelle.
                             * 
                             * On évitera toujours au grand maximum de renvoyer des erreurs inconnues car elles laissent le CALLER voire le FE dans un désaroi.
                             * Lorsqu'on est sur un produit grand public il faut savoir apporter des réponses claires et précises. Elles valent mieux que celles qui sont évasives et trompeuses !!!
                             */
                            return "__ERR_VOL_NO_REL";
                        } else {
                            //ERROR
                            if ( $std_err_enabled )
                                $this->signalError ("err_sys_l4reln2", __FUNCTION__, __LINE__,TRUE);
                            else
                                return "__ERR_VOL_UXPTD";
                        }
                        
                    }
                break;
            case "d_folw":
                    if ( $action === "ufrd" ) {
                        return "__ERR_VOL_NO_FRD"; 
                    } else if ( $action === "ufolw" ) {
                        //[30-08-14] Dans ce cas, on crée une Relation de type 'S_FOLW' où l'acteur est la cible. 
                        
                        $args = [
                            "actor" => $target,
                            "target" => $actor,
                            "trigger_eventid" => $origin_event_id,
                            "new_relsts" => $this->_REL_STATUS["s_folw"],
                            "isautobuild" => 1,
                            "curr_tbrel_id" => $exists["tbrel_id"]
                        ];
                        
                        $id = $this->on_alter_entity($args);
                        
                        /*
                         * [NOTE 19-10-14] @author L.C.
                         * On supprime les demandes d'amis restées muetes envoyées par ACTOR à TARGET
                         */
                        $this->onalter_ondowngrade_cancel_frqt($actor,$target);
                        
                        return $id;
                    }
                break;
            case "frd":
                    /*
                     * [NOTE 30-08-14] Les différents cas possibles :
                     *  -> A ne veut plus être amis avec B et avant cela, A et B n'avaient jamais eu aucune relation
                     *  -> A ne veut plus être amis avec B et avant cela, A suivait B 
                     *  -> A ne veut plus être amis avec B et avant cela, B suivait A 
                     *  -> A ne veut plus être amis avec B et avant cela, A et B se suivaient mutuellement
                     *  -> A ne veut plus être amis avec B et avant cela, A et B n'avait plus de relation
                     * On commence par chercher la précedente relation.
                     * Pour cela, on récupèrre les relations terminées entre les deux protagonistes.
                     * On récupèrre la plus proche. Aussi on donne comme LIMIT 1
                     */
                    $QO = new QUERY("qryl4reln12");
                    $params = array(":actor1" => $actor, ":target1" => $target, ":actor2" => $actor, ":target2" => $target, ":limit" => 1);
                    $datas = $QO->execute($params);
                    
                    
                    if (! $datas ) {
                        //Il n'y a jamais eu de Relation entre les deux protagonistes avant celle ci
                        //RAPPEL : Ce cas est possible si la demande a été faite parce grace au fait que les deux protagonistes avait un au moins 2 amis en commun.
                        
                        if ( $action === "ufrd" ) {
                            //On fait passer la relation à 's_folw' où l'acteur est la personne qui décide de ufrd
                            /*
                             * Ceci est semble t-il le choix le plus consensuel.
                             * La cible continue de suivre l'Acteur. Si ACTOR voulait mettre fin définitevement à la relation il n'avait qu'à choisir Unfollow comme Action.
                             */
                            $args = [
                                "actor"             => $actor,
                                "target"            => $target,
                                "trigger_eventid"   => $origin_event_id,
                                "new_relsts"        => $this->_REL_STATUS["d_folw"],
                                "isautobuild"       => 1,
                                "curr_tbrel_id"     => $exists["tbrel_id"]
                            ];

                            return $this->on_alter_entity($args);
                        } else if ( $action === "ufolw" ) {
                            
                            /*
                             * Cela ne doit en aucun cas avoir d'impacte sur la cible. Les relations de type Follow sont unilatérales.
                             * On crée une Relation où l'Actor est TARGET en 's_folw'
                             */
                            
                            $args = [
                                "actor"             => $target,
                                "target"            => $actor,
                                "trigger_eventid"   => $origin_event_id,
                                "new_relsts"        => $this->_REL_STATUS["s_folw"],
                                "isautobuild"       => 1,
                                "curr_tbrel_id"     => $exists["tbrel_id"]
                            ];

                            return $this->on_alter_entity($args);
                        }
                    } else if ( $datas ) {
                        //On récupère la dernière Relation non active.
                        //OBSELETE au [30-08-14]
                        $pre_rel = strtolower($invs[$datas[0]["tbrel_relsts"]]);
                        $pre_rel_actor = $datas[0]["tbrel_acc_actor"];
                        $pre_rel_target = $datas[0]["tbrel_acc_targ"];
                        
                        
                        /*
                         * [NOTE 30-08-14]
                         * MODIFICATIONS MAJEURES :
                         *  Lorsque deus personnes sont amis, leur Relation peut être interprétée de la sorte :
                         *  (1) Ils se suivent mutuellement
                         *  (2) Ils sont amis
                         * 
                         * Cela permet de bien séparer les deux états. On peut arreter d'être Amis avec une personne sans pour autant couper tous les ponts.
                         *      Pour cela, les Protas n'ont qu'à se DEFOLLOW !
                         * 
                         * Les lignes qui suivent suivent donc deux prinicipaux cas de figures : 
                         *  (1) L'Action est 'UFRD' dans ce cas, on ne pose pas de question, on UNFRIEND en créant une relation D_FLOW à l'initiative de l'ACTOR.
                         *  (2) L'action est 'UFOLW' dans ce cas, on considère que ACTOR ne veut plus avoir aucune Relation avec TARGET. Aussi, on va créer une Relation qui le permet. On crée donc une Relation 'S_FOLW'
                         *      à l'initative de TARGET. On ne coupe pas tout de l'autre coté pour laisser l'opportunité à TARGET de décider que faire de son coté !
                         *      Cela va toujours dans le sens de celui qui n'a rien demandé. Par exemple : Si A unfriend B, et que B suivait A avant qu'ils soient Amis, B continu de suivre A.
                         *      Le but étant d'avoir une gestion des relations moins radicales !
                         */
                        
                        if ( $action === "ufrd" ) {
                            /*
                             * Dans ce cas, on crée une nouvelle relation où les deux Protas se suivent (D_FOLW). Cela à l'initiative de l'ACTOR actuel.
                             */
                            
                            $args = [
                                "actor"             => $actor,
                                "target"            => $target,
                                "trigger_eventid"   => $origin_event_id,
                                "new_relsts"        => $this->_REL_STATUS["d_folw"],
                                "isautobuild"       => 1,
                                "curr_tbrel_id"     => $exists["tbrel_id"]
                            ];
                            
                             return $this->on_alter_entity($args);
                            
                        } else {
                            //RAPPEL : Ici on ne peut être que dans le cas de 'ufolw'
                            
                            //Dans ce cas, on crée une nouvelle relation 'S_FOLW' à l'initative de TARGET
                            $args = [
                                "actor"             => $target,
                                "target"            => $actor,
                                "trigger_eventid"   => $origin_event_id,
                                "new_relsts"        => $this->_REL_STATUS["s_folw"],
                                "isautobuild"       => 1,
                                "curr_tbrel_id"     => $exists["tbrel_id"]
                            ];
                            
                            return $this->on_alter_entity($args);
                        }
                        /*
                        if ( $pre_rel === "void" ) {
                            
                            if ( $action === "ufrd" ) {
                                //RAPPEL : Ce cas est possible si la demande a été faite grace au fait que les deux protagonistes avait un au moins 2 amis en commun.
                                $args = [
                                    "actor" => $actor,
                                    "target" => $target,
                                    "trigger_eventid" => $origin_event_id,
                                    "new_relsts" => $this->_REL_STATUS["d_folw"]
                                ];

                                return $this->on_alter_entity($args);
                            } else if ( $action === "ufolw" ) {
                                $args = [
                                    "actor" => $target,
                                    "target" => $actor,
                                    "trigger_eventid" => $origin_event_id,
                                    "new_relsts" => $this->_REL_STATUS["s_folw"],
                                    "isautobuild" => 1
                                ];

                                return $this->on_alter_entity($args);
                            } else {
                                //ERROR
                                if ( $std_err_enabled )
                                    $this->signalError ("err_sys_l4reln2", __FUNCTION__, __LINE__,TRUE);
                                else
                                    return "__ERR_VOL_UXPTD";
                                }
                            
                        } else {
                            /*
                             * Ici nous sommes dans le cas on a une précédente Relation.
                             * Elle est soit du type : 's_folw', 'd_folw'.
                             * Elle ne peut pas être de type 'frd' car nous sommes déjà dans une relation de type 'frd'.
                             * Le downgrading dépendra de l'action en cours.
                             * 
                            $rel = NULL;
                            
                            if ( $pre_rel === "s_folw" && ( $action === "ufrd" || $action === "ufolw" ) ) {
                                /*
                                 * ACTOR veut Unfriend TARGET ou ACTOR veut Unfollow TARGET
                                 * 
                                 * CE CAS DIT : 
                                 * Avant de devenir amis, l'un des deux protagoniste suivait l'autre.
                                 * Si l'ACTOR dans la précédente Relation était le même que maintenant, on crée une relation o: ACTOR suit TARGET
                                 * Sinon on crée une relation de type VOID //[30-08-14] FAUX !!
                                 *      [NOTE 30-08-14] Dans le cas, où target suivait ACTOR, on crée une relation de type 'autobuild' TARGET suit ACTOR
                                //On fait le choix de la futur Relation
                                if ( $pre_rel_actor === $actor ) {
                                    
                                    $rel = $this->_REL_STATUS["s_folw"];
                                    $args = [
                                        "actor" => $actor,
                                        "target" => $target,
                                        "trigger_eventid" => $origin_event_id,
                                        "isautobuild" => 1,
                                        "new_relsts" => $rel //La conversion a été faite plus haut
                                    ];
                                    
                                }
                                else {
                                    
                                    $rel = $this->_REL_STATUS["s_folw"];
                                    $args = [
                                        "actor" => $target,
                                        "target" => $actor,
                                        "trigger_eventid" => $origin_event_id,
                                        "isautobuild" => 1,
                                        "new_relsts" => $rel //La conversion a été faite plus haut
                                    ];
                                    
                                }

                                return $this->on_alter_entity($args);
                            } else if ( $pre_rel === "d_folw" && $action === "ufrd" ) {
                                /*
                                 * ACTOR veut Unfriend TARGET 
                                 * 
                                 * CE CAS DIT : 
                                 * Avant de devenir amis, les deux protagoniste suivaient mutuellement.
                                 * La relation passe à D_FOLW avec comme ACTOR, ACTOR
                                $args = [
                                    "actor" => $actor,
                                    "target" => $target,
                                    "trigger_eventid" => $origin_event_id,
                                    "new_relsts" => $this->_REL_STATUS["d_folw"]
                                ];

                                return $this->on_alter_entity($args);
                            } else if ( $pre_rel === "d_folw" && $action === "ufolw" ) {
                                /*
                                 * ACTOR veut Unfollow TARGET 
                                 * 
                                 * CE CAS DIT : 
                                 * Avant de devenir amis, les deux protagoniste suivaient mutuellement.
                                 * La relation passe à S_FOLW avec comme ACTOR, TARGET
                                
                                $args = [
                                    "actor" => $target,
                                    "target" => $actor,
                                    "trigger_eventid" => $origin_event_id,
                                    "new_relsts" => $this->_REL_STATUS["s_folw"],
                                    "isautobuild" => 1
                                ];

                                return $this->on_alter_entity($args);
                            } else {
                                //ERROR
                                if ( $std_err_enabled )
                                    $this->signalError ("err_sys_l4reln2", __FUNCTION__, __LINE__,TRUE);
                                else
                                    return "__ERR_VOL_UXPTD";
                            }
                            
                        }
                        //*/
                    } 
                break;
            case "void":
                    return "__ERR_VOL_VOID_REL";
                break;
            default:
                    //ERROR
                    if ( $std_err_enabled )
                        $this->signalError ("err_sys_l4reln2", __FUNCTION__, __LINE__,TRUE);
                    else
                        return "__ERR_VOL_UXPTD";
                break;
        }
    }
    
    private function onalter_ondowngrade_cancel_frqt ($actor,$target) {
        /*
         * On annule la demande d'amis d'Actor si elle existe.
         * Il faut noter qu'ona annule pas la demande faite par Target.
         * En effet, Actor n'aura qu'à refuser la demande faite par target ulterieurement.
         * Si la demande a pu être faite à un moment donné alors elle reste valide pour Target.
         * 
         * (AMELIORATION)
         *  Target pourra toujours annuler par lui-même la relation dans les versions à venir
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4reln13");
        $params = array(":actor" => $actor, ":target" => $target);
        $QO->execute($params);
        
        return TRUE;
    }
    
    /*****************************************************************************************************************************/
    /************************************************** FRIEND SCOPE (start)  ****************************************************/
    public function friend_theyre_friends ($actor, $target) {
        //QUESTION : Est ce que les deux protagonistes ont une relation de type AMIS ?
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( intval($actor) === intval($target) ) {
            return "__ERR_VOL_SAME_PROTAS"; 
        }
        
        $exist = $this->check_if_both_protas_exist($actor, $target);
        if ( !$exist ) {
            return "__ERR_VOL_ATLEAST_ONE_GONE"; 
        }
        
        $args = [
            "actor"     => $actor,
            "target"    => $target
        ];
         
        $r = $this->exists($args);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            return $r;
        } else if (! $r ){
            return FALSE;
        } else {
            return ( intval($r["tbrel_relsts"]) === intval($this->_REL_STATUS["frd"]) ) ? $r : FALSE;
        }
    }
    
    //[DEPUIS 21-09-15] @author BOR
    public function friend_theyre_friends_eidvr ($actor, $target) {
        //QUESTION : Est ce que les deux protagonistes ont une relation de type AMIS ?
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( intval($actor) === intval($target) ) {
            return "__ERR_VOL_SAME_PROTAS"; 
        }
        
        /*
         * [DEPUIS 21-09-15] @author BOR
         *      On transforme les identifiants EXTERNEs en identifiants INTERNEs
         */
        $PA = new PROD_ACC();
        $actor = $PA->onread_get_accid_from_acceid($actor);
        $target = $PA->onread_get_accid_from_acceid($target);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $actor) | $this->return_is_error_volatile(__FUNCTION__, __LINE__, $target) ) {
            return "__ERR_VOL_ATLEAST_ONE_GONE"; 
        }
        
        /* //[DEPUIS 21-09-15] @author BOR
        $exist = $this->check_if_both_protas_exist($actor, $target);
        if ( !$exist ) {
            return "__ERR_VOL_ATLEAST_ONE_GONE"; 
        }
        //*/
        
        $args = [
            "actor"     => $actor,
            "target"    => $target
        ];
         
        $r = $this->exists($args);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            return $r;
        } else if (! $r ){
            return FALSE;
        } else {
            return ( intval($r["tbrel_relsts"]) === intval($this->_REL_STATUS["frd"]) ) ? $r : FALSE;
        }
    }
    
    public function friend_commons_friends_list ($user1, $user2) {
        /*
         * La méthode permet d'obtenir une liste contenant les amis en communs entre les deux Protas en présence.
         */
        if ( intval($user1) === intval($user2) ) {
            return "__ERR_VOL_SAME_PROTAS";
        }
        
        //On récupère la liste d'amis de l'ACTOR
        $ACC_A = new PROD_ACC();
        $actor_friends = $ACC_A->onread_acquiere_my_friends($user1, TRUE);

        if ( $actor_friends === "__ERR_VOL_ACC_GONE" ) 
            return "__ERR_VOL_ACC_ATR_GONE";

        //On récupère la liste d'amis de TARGET
        $ACC_T = new PROD_ACC();
        $target_friends = $ACC_T->onread_acquiere_my_friends($user2, TRUE);
        
        if ( $target_friends === "__ERR_VOL_ACC_GONE" )
            return "__ERR_VOL_ACC_TGT_GONE";
        
        if ( ( isset($actor_friends) && is_array($actor_friends) && count($actor_friends) == 2 && !empty($actor_friends) 
                && ( isset($target_friends) && is_array($target_friends) && count($target_friends) == 2 && !empty($target_friends) ) ) ) {
            
            
            $common_friends = array_intersect($actor_friends[0], $target_friends[0]);
            
            if ( $common_friends && is_array($common_friends) && count($common_friends) ) {
                return $common_friends;
            }
                    
        }

        return NULL;
    }
    
    
    public function friend_actor_matches_friend_rules ($actor, $target) {
        //QUESTION : Est-ce que la demande respecte au moins une des trois règles ? [REP = TRUE/FALSE,La règle sélectionnée]
        //RAPPEL : On renvoie un tableau pour facilement faire la différence entre les différentes réponses. De plus, cela pourrait intéressé CALLER de savoir qu'elle règle a été utilisée !!
        /*
         * La relation de type Amis est une relation bijective. Aussi, une seule demande d'amis est autorisée.
         * Mais encore, cette bijectivité entraine une réciprocité dans les fonctionnalités.
         * 
         * La relation d'Amis a les conséquences suivantes : 
         * (VBETA1)
         *  (1) Les deux utilisateurs se suivent mutuellement.
         *  (2) Les amis ont pleinement accès à la lecture des Aricles IML réciproques.
         *  (4) Les amis peuvent voir les commentaires des Articles IML réciprocques.
         *  (3) Les amis peuvent commenter les Articles IML réciproques.
         * 
         * (PLUS TARD)
         *  (1) Les amis recoivent des notifications lorsque une action est effectuée.
         *  (2) Les amis peuvent communiquer via un chat
         *  (3) (AUTRES ...) 
         * 
         * 
         * Pour qu'une demande d'Amis soit acceptée, il faut que l'Acteur remplisse AU MOINS UNE des conditions suivantes :
         *      1- Il doit suivre l'utilisateur cible depuis AU MOINS UNE SEMAINE.
         *      2- (OU) Les Protas doivent se suivrent mutuellement depuis au moins 24h.
         *      3- (OU) L'Acteur et la Cible doivent avoir au moins 2 amis en commun. (Cette donnée est fortement succeptible de changer)
         * 
         * [DEPUIS 20-11-15 OU VB2.0] @author BOR
         *      NOUVELLES REGLES : A peut envoyer une demande à B si au moins une des conditions suivantes est respectée
         *          1- B SUIT A depuis au moins 7 jours consécutifs
         *          2- (OU) A et B suivrent mutuelement depuis au moins 24 heures consécutives
         *          3- (OU) A et B ont au moins 3 amis en commun
         */
        
        //RAPPEL : ["s_folw_7d" => "1", "d_folw_1d" => "2", "comfrd" => "3"];
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());

        //On vérifie qu'il s'agit bien de deux comptes différents
        if ( floatval($actor) === floatval($target) ) {
            return "__ERR_VOL_SAME_PROTAS";
        }

        //On vérifie au préalable si les Protas existent toujours. Ceci parce que cette méthode est public
        $exist = $this->check_if_both_protas_exist($actor, $target);
        if ( !$exist ) {
            return "__ERR_VOL_ATLEAST_ONE_GONE";
        }
         
         
        /*
         * [DEPUIS 20-11-15] @author BOR   
         *      On vérifie qu'il existe pas déjà une demande en cours.
         */
        $rqt = $this->friend_askfriend_request_exists($actor, $target);
        if ( $rqt ) {
            return ( intval($rqt["rlev_acc_actor"]) === intval($actor) ) ?  "__ERR_VOL_ACT_RQT_PDG" : "__ERR_VOL_TGT_RQT_PDG";
        }
         
         //On vérifie si les deux protagonistes ont une relation.
        $Protas = [
            "actor"    => $actor,
            "target"   => $target
        ];
         
        $rel = $this->exists($Protas);   
        if ( !$rel || ( $rel && $rel["tbrel_relsts"] === intval($this->_REL_STATUS['void'] ) ) ) {
            /*
             * Les deux ptotagonistes n'ont aucune relation existante.
             * On va donc vérifier la règle 3 et voir s'ils ont au moins trois amis en commun.
             */
            /*
             * RULE 3 : L'Acteur et la Cible doivent avoir au moins 3 amis en commun.
             * 
             * [NOTE 23-08-14] 
             *     On aurait pu le faire d'un seul trait mais à l'heure actuelle je n'ai pas trouvé la bonne super requete qui m'aurait permis de le faire.
             */

            $common_friends = $this->friend_commons_friends_list($actor, $target);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $common_friends) ) {
                return $common_friends;
            }

//             var_dump($common_friends);
//             exit();


            //On vérifie les ids en commun et on compte leur nombre
            if ( is_array($common_friends) && count($common_friends) >= $this->_REL_COM_FRD ) {
                return [TRUE,"comfrd"];
            }

        } else {
             
            /*
             * ETAPE :
             *     On va vérifier les 2 autres RULES restantes : 
             *         1- (ANNULE) B SUIT A depuis au moins 7 jours consécutifs
             *         2- (OU) A et B suivrent mutuelement depuis au moins 24 heures consécutives
             */
             
             
            /*
             * ETAPE :
             *      On vérifie que la relation est d'un type différent de 'FRD'
             */
            if ( intval($rel["tbrel_relsts"]) === intval($this->_REL_STATUS['frd']) ) {
                return "__ERR_VOL_ALDY_FRD"; 
            }

            /*
             * ETAPE :
             *      On vérifie qu'il n'y a pas déjà une demande d'amis en cours faite par ACTOR ou par TARGET 
             * 
             * [DEPUIS 21-11-15] @uthor BOR
             *      J'ai beaucoup de mal à comprendre pour cela n'a pas été vérifié plus en amont.
             *      Je n'ai pas trouvé de raison indiquant pourquoi la vérification ne se fait que pour les deux derniers cas.
             *      J'ai donc décidé de transférer cette fonctionnalité au niveau plus haut. Je resterai attentif quant aux bogues de regression qui pourraient en découler.
             */
            /*
            $rqt = $this->friend_askfriend_request_exists($actor, $target);
            if ( $rqt ) {
                return ( intval($rqt["rlev_acc_actor"]) === intval($actor) ) ?  "__ERR_VOL_ACT_RQT_PDG" : "__ERR_VOL_TGT_RQT_PDG";
            }
            //*/

            $now = round(microtime(TRUE)*1000);
             
            /* VERIFICATION DES REGLES (R1 et R2) */
            //RAPPEL : Si au moins une des règles est satisfaite on répond TRUE (Il a le droit) sinon FALSE (Il n'a pas le droit)
             
            /*
             * RULE 1 : ACTOR doit suivre CIBLE depuis au moins une semaine
             * 
             * [DEPUIS 21-11-15] @author BOR
             *      C'est CIBLE qui doit suivre ACTOR depuis au moins 7 jours consécutifs.
             * 
             * [DEPUIS 21-11-15] @author BOR
             *      ANNULE : 
             *          (1) Cela aurait impliqué d'effectuer des changements importants au nieau de FE pour faire apparaitre le bouton "Add Friend" quand le bouton "Following" n'est pas activé
             *          (2) De plus cela a l'avantage de simplifier le processus
             */ 
            /*
            if ( 
                intval($rel["tbrel_relsts"]) === intval($this->_REL_STATUS['s_folw']) 
//                    && ( intval($rel["tbrel_acc_actor"]) === intval($actor) && intval($rel["tbrel_acc_targ"]) === intval($target) ) 
                    && ( intval($rel["tbrel_acc_actor"]) === intval($target) && intval($rel["tbrel_acc_targ"]) === intval($actor) ) 
            ) {
                
                //Pour les besoins des tests, le développeur peut modifier tout ou partie de la valeur à prendre comme référence.
                $a_week = 86400000 * 7;
//                $a_week = 100; //TESTS & DEBUG :  
                
                //On calcule la différence
                $diff_time = $now - $rel["tbrel_datecrea_tstamp"];
                
//                var_dump("S_FOLW",$diff_time);
                
                //On compare
                if ( $diff_time >= $a_week ) {
                    return [TRUE,"s_folw_7d"];
                }
                 
            } else 
            //*/
            if ( intval($rel["tbrel_relsts"]) === intval($this->_REL_STATUS['d_folw']) ) {
                
                /*
                 * RULE 2 : Les Protas doivent se suivrent mutuellement depuis au moins 24h (consécutives).
                 * [DEPUIS 04-08-16]
                 *      On passe à 7 jours pour plus d'audace et d'authenticité
                 */
                
                $a_day = 7* 86400000;
                $a_day = 100; //UNCOMMENT for DEV, TESTS & DEBUG ! 
                
                //On calcule la différence
                $diff_time = $now - $rel["tbrel_datecrea_tstamp"];
                
//                var_dump("D_FOLW",$diff_time);
                
                //On compare
                if ( $diff_time >= $a_day ) {
                    return [TRUE,"d_folw_1d"];
                }
            }
            
        }
         
        return "__ERR_VOL_FRRUL_MSM";
    }
    
    /********** FRIEND REQUEST */
    
    public function friend_askfriend_request_exists ($actor, $target) {
        /*
         * La méthode permet de vérifier si une requete de demande d'Amis est en cours. Peu importe qui est l'Actor ou le Target
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( intval($actor) === intval($target) ) {
            return "__ERR_VOL_SAME_PROTAS"; 
        }
        
        $QO = new QUERY("qryl4reln7");
        $params = array(
            ":actor1"   => $actor, 
            ":target1"  => $target, 
            ":actor2"   => $actor, 
            ":target2"  => $target
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
        
    }
    
    public function friend_ask_as_a_friend ($actor, $target) {
        /*
         * Renvoie un STRING de type ERROR_VOL si une erreur survient. Cette erreur a de grandes chances de venir de la méthode qui vérifie les autorisations.
         * Renvoie un bool (FALSE) au cas où l'utilisateur n'est pas autorisé. Cela vient surement de la méthode qui vérifie les autorisations.
         * Renvoie un INT l'utilisateur si la demande a été faite. Cet INTEGER représente l'identifiant de l'évènement correspondant à la demande créée. 
         *      ATTENTION (RAPPEL) : Il s'agit de l'identifiant de la table Frdqt_Events et non celui de la table RelEvents.
         * 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( floatval($actor) === floatval($target) ) {
            return "__ERR_VOL_SAME_PROTAS"; 
        }
        
        /*
         * On vérifie si l'utilisateur respecte les règles pour une demande d'Amis et on vérifie si une erreur volatile est renvoyée.
         * Si l'utilisateur a l'autorisation de faire sa demande, la méthode va renvoyer un tableau où à l'index '1', on aura la règle qui a été utilisée.
         */
        $r = $this->friend_actor_matches_friend_rules($actor, $target);
        
//        var_dump($r);
//        exit();
        //NOTE : Suivant le code de friend_actor_matches_friend_rules(); Le retour ne peut être qu'un BOOL ou une chaine de type ERROR_VOLATILE
        
        //POUR LES TESTS : On autorise toutes les demandes
        //*
        //On vérifie quand même le fonctionnement de matches_rules()
//        var_dump($r);
//        exit();
        //On vérifie le retour
        $is_ev = $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r);
        //*/
        //$is_ev = FALSE; //Pour les tests
        
        if ( $is_ev ) {
            return $r;
        } else if ( is_array($r) && count($r) ) {
            //On crée l'évènement et on renvoie son identifiant
            $id = NULL;
            $id = $this->oncreate_create_event($actor, $target, "frdask");
            
            $is_ev = $this->return_is_error_volatile(__FUNCTION__, __LINE__, $id);
            if ( $is_ev ) {
                return $r;
            } else {
                $this->_FAR_RULE_USED[$r[1]];
                $QO = new QUERY("qryl4reln8");
                $params = array(":eventid" => $id, ":rule_used" => $this->_FAR_RULE_USED[$r[1]]);
                $id = $QO->execute($params);
                
                return $id;
            }
        } else {
            return NULL;
        }
    }
    
    /**
     * Permet de réaliser l'action d'acceptation d'une demande de type AMIS.
     * 
     * @param string|integer $asked_user L'utilisateur à qui la demande a été adressée.
     * @param string|integer $asking_user L'utilisateur qui a fait la demande.
     * @param string|integer $frdrqt_eventid L'identifiant de l'évenement de demande. Il s'agit de l'identifiant de la table "Frdqt_Events".
     * @return string|array Renvoie le load de la nouvelle Relation entre les protagonistes. En cas d'erreurs, renvoie une chaine de type ERROR_VOLATILE.
     */
    public function friend_accept_request ($asked_user, $asking_user, $frdrqt_eventid) {
        /*
         * Permet d'accepter une personne en amis.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
         * On vérifie si une requete existe avec l'identifiant donné en paramètre ansi que les protagonistes.
         * La requete doit être non terminée.
         * 
         * [NOTE 30-08-14 09:55] La requete a été modifiée de telle sorte qu'on recherche une demande où 'asked' est en target.
         */
        $QO = new QUERY("qryl4reln9");
        $params = array(":frdrqt_eventid" => $frdrqt_eventid, ":actor" => $asking_user, ":target" => $asked_user);
        $datas = $QO->execute($params);
        
//        var_dump($datas);
//        exit();
        
        if ( $datas ) {
            $frdrqtid = $datas[0]["frdrqtid"];
            $ask_id = $datas[0]["frdrqt_ask_rlevid"];
            
//            var_dump($frdrqtid,$ask_id);
//            exit();
                    
            $now = round(microtime(TRUE)*1000);
            
            $answ_id = $this->oncreate_create_event($asked_user, $asking_user, "frdask_acpt");
            
//            var_dump($answ_id);
//            exit();
            
            //On mets fin à l'ancienne demande demande
            $QO = new QUERY("qryl4reln10");
            $params = array(":frdrqtid" => $frdrqtid, ":old_evtid" => $ask_id, ":new_evtid" =>$answ_id, ":tstamp" => $now);
            $QO->execute($params);
            
            /* On mets à jour la relation */
            //RAPPEL : ["actor","target","trigger_eventid","new_relsts"]
            
            /*
             * [NOTE 30-08-14] @author L.C.
             * Pour les besoins de VM, il faut récupérer l'identifiant de la relation active.
             * On ne vérifie pas le retour car à ce niveau, la probabilité qu'il n'existe aucune relation est pratique NULLE
             */
            $rel_tab = $this->exists(["actor"=>$asking_user, "target"=>$asked_user]);
            
            /*
             * [NOTE 30-08-14 09:55] J'ai modifie le code pour que l'acteur soit bien la personne qui a fait la demande et non celui qui accepte.
             *  En effet, même si c'est celui qui accepte qui a le dernier mot, c'est bel et bien celui qui a fait la demande qui est à l'origine de la nouvelle relation crée.
             */
            $alters = [
                "actor"         => $asking_user,
                "target"        => $asked_user,
                "trigger_eventid" => $answ_id,
                "new_relsts"    => $this->_REL_STATUS["frd"],
                "curr_tbrel_id" => $rel_tab["tbrel_id"]
            ];
                    
            $id = $this->on_alter_entity($alters);
            
            $rel = $this->load_entity($id);
            
            return $rel;
            
        } else {
            return "__ERR_VOL_FRDRSQT_NOT_FOUND";
        }
                     
    }
    
    /**
     * Met fin à l'évènement "Demande d'ajout".
     * La méthode renvoie l'identifiant de l'évènement qui a permis d'inscrire que l'utilisateur a décidé de rejettée la demande
     * 
     * @param string|integer $asked_user L'utilisateur à qui a été adressée.
     * @param string|integer $asking_user L'utilisateur qui a fait la demande.
     * @param string|integer $frdrqt_eventid L'identifiant de l'évenement de demande. Il s'agit de l'identifiant de la table "Frdqt_Events".
     * @return string|array Renvoie le load de la nouvelle Relation entre les protagonistes. En cas d'erreurs, renvoie une chaine de type ERROR_VOLATILE.
     */
    public function friend_reject_request ($asked_user, $asking_user, $frdrqt_eventid) {
        /*
         * Permet de refuser une personne en AMIS.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
         * On vérifie si une requete existe avec l'identifiant donné en paramètre ansi que les protagonistes.
         * La requete doit être non terminée
         * 
         * [NOTE 30-08-14 09:55] La requete a été modifiée de telle sorte qu'on recherche une demande où 'asked' est en target.
         */
        $QO = new QUERY("qryl4reln9");
        $params = array(":frdrqt_eventid" => $frdrqt_eventid, ":actor" => $asking_user, ":target" => $asked_user);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            $frdrqtid = $datas[0]["frdrqtid"];
            $ask_id = $datas[0]["frdrqt_ask_rlevid"];
            
//            var_dump($frdrqtid,$ask_id);
//            exit();
                    
            $now = round(microtime(TRUE)*1000);
            
            $answ_id = $this->oncreate_create_event ($asked_user, $asking_user, "frdask_rjt");
            
            //On mets fin à l'ancienne demande demande
            $QO = new QUERY("qryl4reln10");
            $params = array(":frdrqtid" => $frdrqtid, ":old_evtid" => $ask_id, ":new_evtid" =>$answ_id, ":tstamp" => $now);
            $QO->execute($params);
            
            return TRUE;
            
        } else {
            return "__ERR_VOL_FRDRSQT_NOT_FOUND";
        }
    }
    
    /** ******** HANDLE FRIEND RELATION **/
    
    public function friend_break_friend_relation ($actor, $target) {
        /*
         * Permet de briser la relation de type AMIS.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( intval($actor) === intval($target) ) {
            return "__ERR_VOL_SAME_PROTAS"; 
        }
        
        $r = $this->friend_theyre_friends($actor, $target);
        
        //On vérifie le retour
        $is_ev = $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r);
        
        if ( $is_ev ) {
            return $r;
        } else if ( $r === FALSE || $r === NULL )  { //RAPPEL : Le NULL vient du fait qu'il ait arriver quelque chose au niveau de there_friend. Ce cas est extrement peu probable.
            return "__ERR_VOL_NO_FRD";
        } else {
            
            /*
             * On brise la relation.
             * Cela signifie qu'on la Dégrade. Aussi, les utilisateurs ne seront plus amis mais auront une relation de tuype D_FOLW.
             */
            //On crée l'évènement 
            $origin_event_id = $this->oncreate_create_event($actor, $target, "ufrd");
//            $origin_event_id = "any_for_test";
            
            $id_new_rel = $this->onalter_handle_downgrade_relation($actor, $target, $origin_event_id, "ufrd");
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $id_new_rel) ) {
                return $r;
            }
            
            return $this->load_entity($id_new_rel);
        }
    }
    
    /*
     * [NOTE 30-08-14] OBSELTE, on utilise celui de PDACC d'autant plus qu'il fonctionne parfaitement quand celui ci ne le faisait pas ! 
    public function friend_get_myfriends_list ($uid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $ACC = new PROD_ACC();
        $exists = $ACC->exists_with_id(intval($uid));
        
        if (! $exists ) {
            return "__ERR_VOL_ACC_GONE";
        }
        
        $frd_code = $this->_REL_STATUS["frd"];
             
        //On récupère les RELATIONS de type AMIS de l'utilisateur passé en paramètre
        $QO = new QUERY("qryl4reln6");
        $params = array( ":actor" => intval($uid), ":frd_code" => $frd_code );
        $actor_friends = $QO->execute($params);
        
        if (! $actor_friends ) {
            return NULL;
        }
        
        //* On trie pour ne récupérer que les accid des AMIS de l'ACTOR en présence 
        $ids = $accounts = [];
        foreach ($actor_friends as $k => $v) {
            
            //On vérifie pour chaque ligne le compte contraire au compte passé en paramètre
                // (AVANT) Pour chaque id on read le compte afin d'obtenir le maximum d'infrmations dessus (S'il existe)
            
            $ACC = new PROD_ACC();
            if ( $v["tbrel_acc_actor"] !== intval($uid) ) {
                
                $r = $ACC->exists_with_id($v["tbrel_acc_actor"]);
                if ( $r && !$this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ){
                    $ids[] = $v["tbrel_acc_actor"];
                    $accounts[] = $r;
                }
                    
            } else {
                
                $r = $ACC->exists_with_id($v["tbrel_acc_targ"]);
                if ( $r && !$this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ){
                    $ids[] = $v["tbrel_acc_targ"];
                    $accounts[] = $r;
                }
                
            } //Il ne peut pas y avoir d'autres possibilités si en amont les données se sont enregistrées correctement
            
        }
        
        //On renvoie la liste des ids et des informations sur les comptes
        return [$ids,$accounts];
    }
    //*/
    /************************************************* FRIEND SCOPE (end) *******************************************************/
    /****************************************************************************************************************************/
    
    
    /**************************************************************************************************************************/
    /************************************************** ON CREATE (start)  ****************************************************/
    
    private function oncreate_create_event ($actor, $target, $type) {
        /*
         * Permet de créer un évènement de type RELATION
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //["folw","ufolw","blo","ublo","frdask","frdask_rjt","frdask_acpt","frdask_acpt","ufrd"];
        
        //On vérifie que le type est bien défini
        if ( !in_array($type, array_keys($this->_REL_EVENT_TYPES)) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXCEPTED => ", array_keys($this->_REL_EVENT_TYPES)],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $type],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__,TRUE);
        }
        
        //On vérifie que les Protas existent toujours.
        $exist = $this->check_if_both_protas_exist($actor, $target);

        if (! $exist ) {
            return "__ERR_VOL_ATLEAST_ONE_GONE";
        }
        
        //* On crée l'évnèment au niveau de la base de données *//
        $now = round(microtime(TRUE)*1000);
        $event_type_id = $this->_REL_EVENT_TYPES[$type];
        
        $QO = new QUERY("qryl4reln3");
        $params = array(":actor" => $actor, ":target" => $target, ":rel_type" => $event_type_id, ":tstamp" => $now);
        $id = $QO->execute($params);
        
        return $id;
        
    }
    
    private function oncreate_upgrade ($actor, $target, $curr_relid) {
        /*
         * Permet de faire évoluer une relation entre deux protagonistes.
         * Lorsqu'on parle de faire évoluer, il s'agit en faite de faire passer S_FOLW en D_FOLW.
         * En effet, lorsqu'aucune relation n'existe ou que la relation active est de type VOID, c'est on_create qui s'en occupe.
         * Cette methode ne fait que faire passer S_FOLW en D_FOLW.
         * Pour ce faire, elle crée un évènement puis demande une modification de la relation actuelle.
         * 
         * ATTENTION : On ne vérifie pas si une relation existe déjà. C'est donc au CALLER d'être vigilent.
         * C'est pour cela que la méthode est appelée en Private et (presque) seulement par On_create()
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On crée l'evenement
        $r = $this->oncreate_create_event($actor, $target, "folw");
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            return $r;
        } else {
            //Par pur formalisme
            $id = $r;
            
            //* On change la relation entre les protagonistes *//
            $args = [
                "actor"             => $actor,
                "target"            => $target,
                "trigger_eventid"   => $id,
                "new_relsts"        => $this->_REL_STATUS["d_folw"],
                "curr_tbrel_id"     => $curr_relid
            ];
            
            $newrelid = $this->on_alter_entity($args);
            
            return $newrelid;
        }
        
    }
    
    public function oncreate_SyncWmRel ($relid) {
        /*
         * Permet de synchroniser les données dans la table relation avec celle de VM.
         * Cette méthode effectue un INSERT. La table VM est très utile pour améliorer les performances donc l'exp utilisateur.
         * 
         * Données attendues :
         *  -> tbrel_id 
         *  -> tbrel_acc_actor
         *  -> tbrel_acc_target
         *  -> tbrel_relsts
         *  -> tbrel_cdate_tstamp
         *  -> tbrel_edate_tstamp
         * 
         * [NOTE 02-12-14] @author L.C.
         * L'identifiant suffit.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $relid);
        /*
        $XPTD = ["tbrel_id","tbrel_acc_actor","tbrel_acc_target","tbrel_relsts","tbrel_cdate_tstamp","tbrel_edate_tstamp"];

        //On vérifie la présence des données obligatoires
        $com = array_intersect( array_keys($args), $XPTD);

        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && ( !empty($v) | $k === "tbrel_edate_tstamp" ) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        //*/
        //On vérifie que l'identifiant d'origine existe
        $QO = new QUERY("qryl4reln5");
        $params = array( ':id' => $relid );
        $exists = $QO->execute($params);
        if (! $exists ) {
            return "__ERR_VOL_REF_GONE";
        }
        
        //On vérifie que l'identifiant n'est pas déjà enregistré dans la table VM
        $QO = new QUERY("qryl4reln14");
        $params = array( ':id' => $relid );
        $exists = $QO->execute($params);
        if ( $exists ) {
            return "__ERR_VOL_ALDY_XSTS";
        }
        
        //On ajoute l'occurrence
        $QO = new QUERY("qryl4reln15");
        $params = array( ":id" => $relid );
        $QO->execute($params);
        
        return TRUE;
        
    }
    
    /*********************************************** ON CREATE (end) **********************************************************/
    /**************************************************************************************************************************/
    
    /**************************************************************************************************************************/
    /************************************************** ON UPDATE (start)  ****************************************************/
    public function onalter_SyncWmRel ($relid) {
        /*
         * Permet de synchroniser les données dans la table relation avec celle de VM.
         * Cette méthode effectue un UPDATE. Aussi, elle n'a besoin que l'identifiant de l'occurrence qui servira de référence.
         * Enfin, il est clair que seules les colonnes sur les dates de fin seront modifiées. Les autres étant des données en lecture seule.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $relid);
        
        //On vérifie que l'identifiant existe
        $QO = new QUERY("qryl4reln14");
        $params = array( ':id' => $relid );
        $exists = $QO->execute($params);
        if (! $exists ) {
            return "__ERR_VOL_GONE";
        }
        
        //On met à jour l'occurrence
        $QO = new QUERY("qryl4reln16");
        $params = array( ":id1" => $relid, ":id2" => $relid );
        $QO->execute($params);
         
         return TRUE;
    }
    
    /*********************************************** ON UPDATE (end) **********************************************************/
    /**************************************************************************************************************************/
    
    
    /**************************************************************************************************************************/
    /************************************************** ON DELETE (start)  ****************************************************/
    public function ondelete_SyncWmRel ($relid) {
        /*
         * Permet de synchroniser les données dans la table relation avec celle de VM.
         * Cette méthode effectue un DELETE. Aussi, elle n'a besoin que l'identifiant de l'occurrence qui servira de référence.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $relid);
        
        //On vérifie que l'identifiant existe
        $QO = new QUERY("qryl4reln14");
        $params = array( ':id' => $relid );
        $exists = $QO->execute($params);
        if (! $exists ) {
            return "__ERR_VOL_GONE";
        }
        
        //On met à jour l'occurrence
        $QO = new QUERY("qryl4reln17");
        $params = array( ":id" => $relid );
        $QO->execute($params);
         
         return TRUE;
    }
    
    /*********************************************** ON DELETE (end) **********************************************************/
    /**************************************************************************************************************************/
    
    /***************************************************************************************************************************************************/
    /***************************************************************************************************************************************************/
    /************************************************************ GETTERS and SETTERS ******************************************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS">
    public function getTbrel_id() {
        return $this->tbrel_id;
    }

    public function getTbrel_acc_actor() {
        return $this->tbrel_acc_actor;
    }

    public function getTbrel_acc_target() {
        return $this->tbrel_acc_target;
    }

    public function getTbrel_relsts() {
        return $this->tbrel_relsts;
    }
    
    public function getTbrel_relsts_code() {
        return $this->tbrel_relsts_code;
    }

    public function getTbrel_relsts_fecode() {
        return $this->tbrel_relsts_fecode;
    }

    public function getTbrel_datecrea() {
        return $this->tbrel_datecrea;
    }

    public function getTbrel_datecrea_tstamp() {
        return $this->tbrel_datecrea_tstamp;
    }

    public function getTbrel_dateend() {
        return $this->tbrel_dateend;
    }

    public function getTbrel_dateend_tstamp() {
        return $this->tbrel_dateend_tstamp;
    }

    public function getTbrel_relevt() {
        return $this->tbrel_relevt;
    }

    public function get_REL_STATUS() {
        return $this->_REL_STATUS;
    }

    public function get_REL_EVENT_TYPES() {
        return $this->_REL_EVENT_TYPES;
    }

    public function get_ERR_VOL_TABLE() {
        return $this->_ERR_VOL_TABLE;
    }

    public function get_REL_START_EVENT() {
        return $this->_REL_START_EVENT;
    }


// </editor-fold>



}
