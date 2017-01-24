<?php


/**
 * <p>Un WORKER est un objet qui a la charge la gestion d'un evenement urq.<br/>
 * A un urq correspond donc un worker. Dans le modèle MVC c'est la partie process du modèle.
 * </p>
 * <p>Ils sont utilisés au niveau de FACT_PROCESS.</p>
 *
 * @author Lou Carther <lou.carther@deuslynn-entreprise.com>
 * @copyright (c) 2013, DEUSLYNN ENTREPRISE.
 */
abstract class WORKER extends MOTHER  {
    /**
     * @var array Un tableau contenant les informations extraites par prepare_datasin_if_exist(). 
     * @author Richard Dieud <lou.carther@deuslynn-entreprise.com>
     * @see prepare_urlparams() 
     * @since vb1.10.08.14
     */
    protected $KDIn;
    protected $KDOut;
    protected $isAjax;
    protected $perfEna;
    protected $tm_start;
    /*
     * [DEPUIS 12-07-16]
     */
    protected $Base_File;
    protected $Base_Class;
    
    protected $runlang;
    
    function __construct($file, $class, $runlang = NULL) {
        $this->Base_File = $file;
        $this->Base_Class = $class; 
        if ( $runlang ) {
            $this->runlang = $runlang;
        }
    }
    
    /**
     * <p><b><i><u>Contexte :</u></i></b> Il y a toujours deux phases lorsqu'une demande arrive au niveau du serveur.<br/>
     * <ul>
     * <li>Opération in : Il peut s'agir de traitement avant enregistrement ou préparation pour le out.</li>
     * <li>Opération out : Il s'agit dans la grande majorité d'une opération qui a trait à l'affichage. </li>
     * </ul>
     * </p>
     * <p>Cette méthode gère toutes les opérations référant à la phase in.
     * </p>
     * 
     * @author Lou Carther <lou.carther@deuslynn-entreprise.com>
     * @see on_process_out()
     * @see prepare_params_in_if_exist()
     * @copyright (c) 2013, DEUSLYNN ENTREPRISE.
     */
    abstract protected function on_process_in();
    
    /**
     * <p><b><i><u>Contexte :</u></i></b> Il y a toujours deux phases lorsqu'une demande arrive au niveau du serveur.<br/>
     * <ul>
     * <li>Opération in : Il peut s'agir de traitement avant enregistrement ou préparation pour le out.</li>
     * <li>Opération out : Il s'agit dans la grande majorité d'une opération qui a trait à l'affichage. </li>
     * </ul>
     * </p>
     * <p>Cette méthode gère toutes les opérations référant à la phase out. Elle est appelée après FACT_DATA.</p>
     * <ul><span>Parmi ses missions : </span>
     * <li>Effectuer des opérations sur les données brutes pprovenant de la base de données.</li>
     * <li>S'assurer que $_SESSION["ud_carrier"] est conforme. Il est donc chargé de l'instancié.</li>
     * <li>Autres ...</li>
     * </<ul>
     * 
     * @author Lou Carther <lou.carther@deuslynn-entreprise.com>
     * @see on_process_in()
     * @copyright (c) 2013, DEUSLYNN ENTREPRISE.
     */
    abstract protected function on_process_out();
    /**
     * @deprecated since version vb1.10.10.14
     * <p><b><i><u>Contexte :</u></i></b> Les DVT sont souvent composées de données provenant de la bdd.
     * Pour obtenir ces données, les DVT sont liées à des requetes sous forme de QObject.<br/>
     * Ces QObject sont traités en chaine automatiquement par FACT_DATA.<br/>
     * Or, ces dites requetes sont la plupart du temps des requetes préparées avec données en entrée.</p>
     * <p>Cette méthode permet d'insérer dans $_SESSION["params_in"] les valeurs qui seront insérées dans les requetes.<br/>
     * La méthode est utilisée dans @see on_process_in() pour permettre par la suite à FACT_DATA d'exécuter les QObject.</p>
     * <p><i><u>Exemple :</u></i> $_SESSION["$params_in"] =  [ ":id" => $val ] </p>
     * 
     * @author Lou Carther <lou.carther@deuslynn-entreprise.com>
     * @see on_process_in()
     * @copyright (c) 2013, DEUSLYNN ENTREPRISE.
     */
    abstract protected function prepare_params_in_if_exist ();
    
    /**
     * Permet de préparer toutes les données dont a besoin le WORKER provenant soit de l'utilisateur ou de WOS.
     * En fonction des données récupérées ou non, le WORKER peut optimiser son traitement voire rediriger vers une autre page.
     * Cette méthode est appelée avant la méthode on_process_in() afin de préparer les données et de les stocker dans $KDIn.
     * ATTENTION : prepare_datasin() ne gère QUE les cas en ce qui concerne les données. Par exemple, ce n'est pas sa tache que de dire qu'une ressource n'existe pas.
     * C'est la tâche de on_process_out().
     * 
     * Si le WORKER n'attends aucune donnée, il faut laisser cette méthode vide d'instructions.
     * 
     * @see $KDin
     * @author Richard Dieud <lou.carther@deuslynn-entreprise.com>
     * @since vb1.10.08.14
     * @copyright (c) 2014, DEUSLYNN ENTREPRISE.
     */
    abstract protected function prepare_datas_in ();
    
    /*
     * Permet de mesurer le temps d'execution entre deux points chronologiques.
     */
    protected function perfAtPoint($starttime, $line, $WEO = FALSE) {
        if ( empty($starttime) | empty($line) | !$this->perfEna ) {
            return;
        }
        
        //WEO : WithEchoOption
        $t__ = round(microtime(true)*1000) - $starttime;
        
        $str = "<br/>At line $line : ".$t__." ms;";
        if ( $WEO ) {
            echo $str;
        } else {
            return $str;
        }
    }
    
    
    protected function Wkr_LogUsertagActy (POSTMAN $PM, $uid, $iplong, $fe_curl, $srv_curl, $isAx, $refid, $uatid, $ispasv, $reflib = NULL, $remarks = NULL) {
        
        //On ajoute dans la table des Actions
        $args = [
            "uid"           => $uid,
            "ssid"          => session_id(),
            "locip_str"     => $iplong,
            "locip_num"     => sprintf('%u', ip2long($iplong)),
            "useragt"       => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
            "wkr"           => $this->Base_Class,
            "fe_url"        => $fe_curl,
            "srv_url"       => $srv_curl,
            "url"           => $srv_curl,
            "isAx"          => $isAx,
            "refobj"        => $refid,
            "uatid"         => $uatid,
            "uanid"         => 2,
            "ispasv"        => $ispasv,
            "reflib"        => $reflib,
            "remarks"       => $remarks
        ];
        $uai = $PM->UserActyLog_Set_MdPsv($args);
        
        return $uai;
    }
    
}

/*
 * [DEPUIS 19-08-16]
 */
class WORKER_BOUNCE {
    private $worker_name;
    private $worker_id;
    private $file;
    
    function __construct($worker_name) {
        $worker_name = ( substr(strtoupper($worker_name),0,7) === "WORKER_" ) ? $worker_name : "WORKER_".$worker_name;
        $worker_id = substr(strtoupper($worker_name),7);
        $file = WOS_GEN_PATH_TO_PROCESS_WORKERS.$worker_id.WOS_PROCESS_WORKERS_EXT;
//        var_dump($worker_name,$worker_id,$file,file_exists($file));
        if ( file_exists($file) ) {
            $this->worker_name = $worker_name;
            $this->worker_id = $worker_id;
            $this->file = $file;
        }
    }
    
    function getWorker_name() {
        return $this->worker_name;
    }
    
    function getWorker_id() {
        return $this->worker_id;
    }

    function getFile() {
        return $this->file;
    }
}

?>
