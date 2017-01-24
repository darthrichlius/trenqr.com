<?php

namespace Ltc;

/*
 * NOTE :
 *      Permet d'accéder à tout l'univers FMK du côté du CANAL HTTP
 */
require_once "LtcEnv.inc.php";
use TREND;
use QUERY;
use MYSTERY;

use \Thruway\Peer\Router;
use \Thruway\Peer\ClientInterface;
use \Thruway\Event\ConnectionOpenEvent;
use \Thruway\Event\ConnectionCloseEvent;
use \Thruway\Transport\InternalClientTransportProvider;
use \React\EventLoop\Factory;
use \React\EventLoop\LoopInterface;
use Thruway\ClientSession;
use Thruway\Session;

use Psr\Log\NullLogger;
use Thruway\Logging\Logger;

class LtcRouter extends Router {
    private $_loop;
    private $_internalClients = [];
    private $_strictSessions = [];
    
    public function __construct(LoopInterface $loop = null) {
        Logger::set(new NullLogger());
        $this->_loop = $loop ?: Factory::create();
        parent::__construct($loop);
    }
    
    public function addInternalClient(ClientInterface $client)
    {
        $internalTransport = new InternalClientTransportProvider($client);
        $this->registerModule($internalTransport);
        
        ////
        if ( $client instanceof LtcInternalClient && !key_exists($client->getInternalClientId(), $this->_internalClients) ) {
            $this->_internalClients[$client->getInternalClientId()] = $client;
        }
    }
    
    public function handleConnectionOpen(ConnectionOpenEvent $event)
    {
        \ob_start();
        
        $this->sessions[$event->session->getSessionId()] = $event->session;
        
        /*
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",__FUNCTION__,"\n",get_class($event->session->getTransport()),"\n",$event->session->getTransport()->getTransportDetails()["headers"]
                ,"\n",$event->session->getSessionId()]);
        //*/
        
        ////
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",__FUNCTION__,"\n",$event->session instanceof Session,"\n",$event->session instanceof ClientSession,"\n",$event->session->getSessionId()]);
        $td = $event->session->getTransport()->getTransportDetails();
//        var_dump("LTC DEBUG :",$td);
        if ( $td && !empty($td["type"]) && $td["type"] === "ratchet" && !empty($td["headers"]) && !empty($td["url"]) 
                && $td["headers"]["Host"][0] === "localhost:8081" ) { //VERSION DEV
                // && $td["headers"]["Host"][0] === "37.59.53.98:8888" ) ) { //VERSION PROD
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n","SERVICE_GAGNANT","\n"]);
            
            if (! ( $this->_strictSessions && key_exists($event->session->getSessionId(), $this->_strictSessions) ) ) {
                
                /*
                 * ETAPE :
                 *      On récupère les données QUERY de l'URL.
                 *      Ces données comportent notamment l'TRD_EID.
                 */
                $url_query_datas = $this->extractUrlDatas($td["url"]);
            
                $http_cookies_tmp = explode(";", $td["headers"]["Cookie"][0]);
                
//                $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$http_cookies_tmp,"\n"]);

                $http_cookies = [];
                foreach ($http_cookies_tmp as $k => $v) {
                    $foo = explode("=",$v);
                    $http_cookies[trim($foo[0])] = $foo[1];
                }
                
                if ( !empty($http_cookies["PHPSESSID"]) 
                        && !key_exists( $http_cookies["PHPSESSID"], array_column($this->_strictSessions,"http_ssid") ) 
                        && $url_query_datas && isset($url_query_datas["tid"])
                ) {
                    $this->_strictSessions[$event->session->getSessionId()] = [
                        "http_ssid" => $http_cookies["PHPSESSID"],
                        "session"   => $event->session,
                    ];
//                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$this->_strictSessions,"\n"]);
                    
                    session_id($http_cookies["PHPSESSID"]);
                    session_start();
                    
                    
                    /*
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$http_cookies["PHPSESSID"],"\n"]);
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$_SESSION,"\n"]);
//                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$_SESSION["rsto_infos"],"\n"]);
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$this->http_session_is_connected($_SESSION),"\n"]);
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$this->http_session_get_user_datas($_SESSION),"\n"]);
//                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$this->http_session_get_trend_datas($_SESSION),"\n"]);
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",$this->getTrendDatasFromTrid($url_query_datas["tid"]),"\n"]);
//                    exit();
                    //*/
                      
                    if (! $_SESSION ) {
                        \session_write_close();
                        exit();
                    }
                    
                    $is_con = $this->http_session_is_connected($_SESSION);
                    $udatas = $this->http_session_get_user_datas($_SESSION);
//                    $trdatas = $this->http_session_get_trend_datas($_SESSION);
                    /*
                     * ETAPE :
                     *      Bien que moins "hermétique", la méthode d'acquisition des données via la "METHODE GET" est plus simple, fiable (car elle est directe et ne dépend pas des deonnées de $_SESSION) ... 
                     *      ... et facilement implémentable.
                     *      Seules les utilisateurs (HACKERS) expérimentés pourront facilement y trouver une faille.
                     *      Cependant, le temps qu'elle soit trouvée, j'espère qu'on aura colmaté la "brèche".
                     */
                    $trdatas = $this->getTrendDatasFromTrid($url_query_datas["tid"]);
                    
                    $wmp_ssid = $event->session->getSessionId();
                    $ssid = $http_cookies["PHPSESSID"];
                    
                    \session_write_close();
                    
//                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",[!$is_con,!$udatas,!$trdatas,!$wmp_ssid,!$ssid],"\n"]);
                    
                    /*
                     * ETAPE :
                     *      On enregistre la connexion au niveau de la base de données
                     */
                    //Vérifications de sécurité
                    if ( !$is_con | !$udatas | !$trdatas | !$wmp_ssid | !$ssid ) {
                        exit();
                    }
                    
                    /*
                     * ETAPE :
                     *      On ferme toutes les anciennes connexions actives pour cet utilisateur sur la Tendance correspondante.
                     */
//                    $this->session_log_close_all_for_this_user_trend($udatas["uid"],$trdatas["trid"]);
                    
                    /*
                     * ETAPE :
                     *      On enregistre la nouvelle connexion
                     */
                    $this->session_log_write_new($udatas,$trdatas,$wmp_ssid,$ssid);
                    
                    /*
                     * ETAPE :
                     *       On avertit les CLIENTS INTERNES (MANAGER) qu'un nouveau CLIENT (SESSION) s'est connecté.
                     */
                    $this->dispatchNewConnectionOpen($event->session->getSessionId(),$trdatas["trid"]);
                }
            }
        }
        
        \ob_end_flush();
        
    }
    
    private function dispatchNewConnectionOpen ($sessionId,$trid) {
        if ( $this->_internalClients ) {
            foreach ($this->_internalClients as $client) {
                $client->emit("routeur.sessionstart",[$sessionId,$trid]);
            }
        }
    }
    
    /***************************/
    
    public function handleConnectionClose(ConnectionCloseEvent $event)
    {
        unset($this->sessions[$event->session->getSessionId()]);
        // TODO: should this be a message dispatched from the Transport?
        $event->session->onClose();
        
        
        /*
         * Dans tous les cas, on retire la référence au CLIENT.
         */
        unset($this->_strictSessions[$event->session->getSessionId()]);
        
        $td = $event->session->getTransport()->getTransportDetails();
        
        ////
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["\n",[$td],"\n"]);
        
        if ( $td && !empty($td["type"]) && $td["type"] === "ratchet" && !empty($td["headers"]) && !empty($td["url"]) 
                && $td["headers"]["Host"][0] === "localhost:8081" ) { //VERSION DEV
                // && $td["headers"]["Host"][0] === "37.59.53.98:8888" ) ) { //VERSION PROD
            
            $http_cookies_tmp = explode(";", $td["headers"]["Cookie"][0]);
            $http_cookies = [];
            foreach ($http_cookies_tmp as $k => $v) {
                $foo = explode("=",$v);
                $http_cookies[trim($foo[0])] = $foo[1];
            }
                
            /*
             * ETAPE :
             *      On récupère les données QUERY de l'URL.
             *      Ces données comportent notamment l'TRD_EID.
             */
            $url_query_datas = $this->extractUrlDatas($td["url"]);
                
            if ( !empty($http_cookies["PHPSESSID"]) && !key_exists( $http_cookies["PHPSESSID"], array_column($this->_strictSessions,"http_ssid") ) 
                && $url_query_datas && isset($url_query_datas["tid"])
            ) {
                $this->_strictSessions[$event->session->getSessionId()] = [
                    "http_ssid" => $http_cookies["PHPSESSID"],
                    "session"   => $event->session,
                ];

                session_id($http_cookies["PHPSESSID"]);
                @session_start();

                if (! $_SESSION ) {
                    \session_write_close();
                    exit();
                }

                $is_con = $this->http_session_is_connected($_SESSION);
                $udatas = $this->http_session_get_user_datas($_SESSION);
                /*
                 * ETAPE :
                 *      Bien que moins "hermétique", la méthode d'acquisition des données via la "METHODE GET" est plus simple, fiable (car elle est directe et ne dépend pas des deonnées de $_SESSION) ... 
                 *      ... et facilement implémentable.
                 *      Seules les utilisateurs (HACKERS) expérimentés pourront facilement y trouver une faille.
                 *      Cependant, le temps qu'elle soit trouvée, j'espère qu'on aura colmaté la "brèche".
                 */
                $trdatas = $this->getTrendDatasFromTrid($url_query_datas["tid"]);
                $wmp_ssid = $event->session->getSessionId();
                
                \session_write_close();

                /*
                 * ETAPE :
                 *      On enregistre la connexion au niveau de la base de données
                 */
                if (! $is_con ) {
                    exit();
                }
                
                /*
                 * NOTE :
                 *      On utilise cette méthode plutôt que de fermer toutes les Sessions liées à la Tendance car les données de PHP_SESSION peuvent être erronées.
                 *      On effet, l'utilisateur peut ouvrir une nouvelle fenêtre et cela aura pour conséquence de changer ses données de SESSION si SESSION_ID est identique.
                 *      La solution est donc d'utiliser l'identifiant de Session de Wamp.
                 */
                $this->session_log_close_all_for_this_wmpssn($udatas["uid"],$wmp_ssid);
                
                /*
                 * On avertit les CLIENTS INTERNES internes qu'un CLIENT (SESSION) s'est déconnecté.
                 * Le CLIENT INTERNE devra se fier à la variable $_strictSessions.
                 */
                $this->dispatchConnectionLeave($event->session->getSessionId(),$trdatas["trid"]);
        
            }
        }
        
    }
    
    private function dispatchConnectionLeave ($sessionId, $trid) {
        if ( $this->_internalClients ) {
            foreach ( $this->_internalClients as $client ) {
                $client->emit("routeur.sessionleave",[$sessionId,$trid]);
            }
        }
    }
    
    /************************************************************************************************************/
    
    private function extractUrlDatas ($url) {
        //QUOI : Récupérer les données passé par le client via L'URL (Methode GET)é
        
        $query_params = [];
        $url_datas = parse_url($url);
        if ( $url_datas && $url_datas["query"] ) {
            parse_str($url_datas["query"],$query_params);
        }
        
        return $query_params;
    }
    
    /************************************************************************************************************/
    
    public function getStrictSessions () {
        return $this->_strictSessions;
    }
    
    public function getStrictSessionCount () {
        return count($this->_strictSessions);
    }
    
    /************************************** http_session scope **************************************/
    
    private function http_session_is_connected ($_SS) {
        //QUESTION : Est ce qu'une session existe ainsi que les données liées ?
        
        return ( isset($_SS) && key_exists("rsto_infos", $_SS) && isset($_SS['rsto_infos']) ) ? TRUE : FALSE;
    }
    
    private function http_session_get_user_datas ($_SS) {
        //QUESTION : Est ce que l'utilisateur actif est connecté ?
        $udatas = [];
//        if ( $_SS && $_SS["sto_infos"] && $_SS["rsto_infos"] ) {
        if ( $_SS && $_SS["apps"] && $_SS["apps"]["ltc"] ) {
            $udatas = [
                "uid"       => $_SS["apps"]["ltc"]["cuid"],
                "ueid"      => $_SS["apps"]["ltc"]["cueid"],
                "locip"     => $_SS["apps"]["ltc"]["locip"],
                "loc_cn"    => $_SS["apps"]["ltc"]["loc_cn"],
                "uagent"    => $_SS["apps"]["ltc"]["uagent"]
            ];
        }
        
        return $udatas;
    }
    
    private function http_session_get_trend_datas ($_SS) {
        //QUOI : Récupérer les données de la Tendance supposée sur laquelle l'utilisateur est connecté
        
        $trdatas = [];
        if ( $_SS && $_SS["apps"] && $_SS["apps"]["ltc"] ) {
            $trdatas = [
                "trid"      => $_SS["apps"]["ltc"]["trid"],
                "treid"     => $_SS["apps"]["ltc"]["treid"]
            ];
        }
        
        return $trdatas;
    }
    
    
    /************************************** _ scope **************************************/
    
    
    private function getTrendDatasFromTrid ($url_treid) {
        //QUOI : Récupérer les données de la Tendance supposée sur laquelle l'utilisateur est connecté
        
        $TR = new TREND();
        $trtab = $TR->exists($url_treid);
        if (! $trtab ) {
            return;
        }

        $trdatas = [
            "trid"  => $trtab["trid"],
            "treid" => $trtab["trd_eid"]
        ];
        
        return $trdatas;
    }
    
    /************************************** session_log scope **************************************/
    
    private function session_log_write_new ($udatas,$trdatas,$wmp_ssid,$ssid) {
        //QUOI : Enregistrer la connexion au niveau de la base de données ?
        
        $now = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4ltc_sslogn1");
        $params = array( 
            ":temp_eid"     => $now,
            ":refu"         => $udatas["uid"],
            ":trid"         => $trdatas["trid"], 
            ":wmp_ssid"     => $wmp_ssid,
            ":ssid"         => $ssid,
            ":locip"        => $udatas["locip"],
            ":loc_cn"       => $udatas["loc_cn"],
            ":uagent"       => $udatas["uagent"],
            ":curl"         => NULL,
            ":start_date"   => date("Y-m-d G:i:s",($now/1000)),
            ":start_tstamp" => $now,
        );
        $id = $QO->execute($params);
        
        
        /*
         * ETAPE :
         *      On ajoute l'identifiant externe
         * NOTE 
         *      On choisit n'importe lequel ENTITY qui hérite de PROD_ENTY pour accéder aux fonction d'encodage.
         */
        $MYS = new MYSTERY();
        $eid = $MYS->entity_ieid_encode($now,$id);
        
        $QO = new QUERY("qryl4ltc_sslogn2");
        $params = array( 
            ':id'   => $id,
            ':eid'  => $eid 
        );
        $QO->execute($params);
        
        $fnl_datas = [
            "id"    => $id,
            "eid"   => $eid,
        ];
        
        return $fnl_datas;
    }
    
    /**************** CLOSE */
    
    private function session_log_close_all_for_this_user_trend ($uid,$trid) {
        //QUOI : Fermer toutes les connexions actives de cet utilisateur (via http_uid) pour la Tendance spécifiée
        
        $now = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4ltc_sslogn3");
        $params = array( 
            ":uid"          => $uid,
            ":trid"         => $trid,
            ":end_date"     => date("Y-m-d G:i:s",($now/1000)),
            ":end_tstamp"   => $now,
        );
        $QO->execute($params);
    }
    
    private function session_log_close_all_for_this_user_trend_with_wmp ($wmp_ssid,$trid) {
        //QUOI : Fermer toutes les connexions actives de cet utilisateur (via wmp_ssid) pour la Tendance spécifiée
        
        $now = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4ltc_sslogn3_wmp");
        $params = array( 
            ":wmp_ssid"     => $wmp_ssid,
            ":trid"         => $trid,
            ":end_date"     => date("Y-m-d G:i:s",($now/1000)),
            ":end_tstamp"   => $now,
        );
        $QO->execute($params);
    }
    
    private function session_log_close_all_for_this_wmpssn ($uid,$wmp_ssid) {
        //QUOI : Fermer toutes la ou les connexions actives de cet utilisateur (via http_uid, wmp_ssid)
        
        $now = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4ltc_sslogn4");
        $params = array( 
            ":uid"          => $uid,
            ":wmp_ssid"     => $wmp_ssid,
            ":end_date"     => date("Y-m-d G:i:s",($now/1000)),
            ":end_tstamp"   => $now,
        );
        $QO->execute($params);
    }
    
    /**************** ACQUIERE */
    
    private function session_log_count_active_sessions_on_trend ($trid) {
        //QUOI : Le nombre de Sessions actioves sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn5");
        $params = array( 
            ':trid' => $trid 
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0]["count_ssn"] : 0;
    }
    
    private function session_log_get_active_sessions_on_trend ($trid) {
        //QUOI : Les données sur les Sessions actives sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn6");
        $params = array( 
            ':trid' => $trid 
        );
        $datas = $QO->execute($params);
        
        return $datas;
    }
    
}