<?php

namespace Ltc;

/*
 * NOTE :
 *      Permet d'accéder à tout l'univers FMK du côté du CANAL HTTP
 */
require_once "LtcEnv.inc.php";
use QUERY;

use Thruway\Peer\Router;
use Thruway\ClientSession;
use Thruway\Message\ResultMessage;
use Thruway\Peer\Client;
use React\EventLoop\Factory;

use Psr\Log\NullLogger;
use Thruway\Logging\Logger;


class LtcInternalClient extends Client {
    private $objectId;
    private $_sessionId;
    private $_session;
    protected $router; 
    protected $loop; 
    protected $sessions = [];
    protected $subscribedTopics = [];
    
    public function __construct(LtcRouter &$router, $realm, $loop = null) {
        Logger::set(new NullLogger());
        $this->loop = ( $loop ) ?: Factory::create();
        parent::__construct($realm, $this->loop);
        
        $this->objectId = spl_object_hash($this);
        $this->router = $router;
        
        $this->on("routeur.sessionstart", [$this,"onRouterNewSessionStart"]);
        $this->on("routeur.sessionleave", [$this,"onRouterNewSessionLeave"]);
    }
    
    public function onSessionStart($session, $transport) {
        
        $this->_session = $session;
        $this->_sessionId = $this->_session->getSessionId();
        
        $this->_session->subscribe('ltc.session.event.onjoin',  [$this, 'onSessionJoin']);
        $this->_session->subscribe('ltc.session.event.onleave', [$this, 'onSessionLeave']);
        
        $this->_session->register('ltc.session.app.mtune',[$this, 'getOnline_Count_With_WmpSsid']);
        
//        echo "\nConnection started for a CLIENT with SessionID : {$this->_session->getSessionId()}\n";
        
//        var_dump(__FUNCTION__,__LINE__,$this->_session->getTransport()->getTransportDetails());
    }
    
    public function onRouterNewSessionStart ($wmp_ssid,$trid) {
        var_dump("\nLTC : SESSION_START","\n",[$wmp_ssid,$trid]);
        
        $online = $this->getOnline_Count_With_Trid($trid);
        $my_online = $this->getMyOnline_Count($wmp_ssid);
        
        /*
        var_dump("\nLTC : SESSION_START_CHECK","\n",[
            "ssid"      => $args,
            "online"    => $online,
            "my_online" => $my_online,
        ]);
        //*/
        
        if ( $this->_session ) {
            $this->_session->publish('ltc.session.event.onjoin',[],[
                "ssid"      => $wmp_ssid,
                "online"    => $online,
                "my_online" => $my_online,
            ]);
        }
    }
    
    public function onRouterNewSessionLeave ($wmp_ssid,$trid) {
//        var_dump("\nLTC : SESSION_END",[$wmp_ssid,$trid],"\n");
        
        $online = $this->getOnline_Count_With_Trid($trid);
        $my_online = $this->getMyOnline_Count($wmp_ssid);
        
        /*
        var_dump("\nLTC : SESSION_END_CHECK","\n",[
            "ssid"      => $wmp_ssid,
            "online"    => $online,
            "my_online" => $my_online,
        ]);
        //*/
        
        $this->_session->publish('ltc.session.event.onleave',[],[
            "ssid"      => $wmp_ssid,
            "online"    => $online,
            "my_online" => $my_online,
        ]);
    }
    
    /**
     * Obtenir la liste de Sessions connectées à la Tendance liée à la WAMP_SESSION passée en paramètre
     * 
     * @param int $wmp_ssid
     * @return array
     */
    public function getOnline ($wmp_ssid) {
        return  $this->session_log_count_active_sessions_on_trend_with_wmp($wmp_ssid);
    }
    
    /**
     * Obtenir le nombre de Sessions connectées à la Tendance liée à la WAMP_SESSION passée en paramètre
     * 
     * @param int $wmp_ssid
     * @return int
     */
    public function getOnline_Count_With_WmpSsid ($wmp_ssid) {
//        return ( $this->router->getStrictSessionCount() > 0 )? $this->router->getStrictSessionCount() : 0;
        
        return  $this->session_log_count_active_sessions_on_trend_with_wmp($wmp_ssid);
    }
    
    public function getOnline_Count_With_Trid ($trid) {
        return  $this->session_log_count_active_sessions_on_trend($trid);
    }
    
    /**
     * Obtenir le nombre de MES Sessions connectées à la Tendance liée à la WAMP_SESSION passée en paramètre
     * 
     * @param int $wmp_ssid
     * @return int
     */
    public function getMyOnline_Count ($wmp_ssid) {
        return  $this->session_log_count_my_active_sessions_on_trend_with_wmp($wmp_ssid);
    }
    
    public function onSessionJoin ($args, $kwArgs, $options) {
        /*
        $datas = (array) $kwArgs;
        
        $ssid = $datas["ssid"];
        $text = $datas["text"];
        
        if (! key_exists($ssid, $this->sessions) ) {
            echo "\nLTC_DEBUG : Session {$ssid} joinned, saying : {$text}\n";
            $this->sessions[$ssid] = $options;
        }
        //*/
    }
    
    public function onSessionLeave ($args, $kwArgs, $options) {
        /*
        if (!empty($args[0]['session'])) {
            foreach ($this->sessions as $key => $details) {
                if ($args[0]['session'] == $details['session']) {
                    echo "Session {$details['session']} leaved\n";
                    unset($this->sessions[$key]);
                    return;
                }
            }
        }
        //*/
    }
    
    public function getInternalClientId(){
        return $this->objectId;
    }
    
    /************************************** session_log scope **************************************/
    
    private function session_log_count_active_sessions_on_trend_with_wmp ($wmp_ssid) {
        //QUOI : Le nombre de Sessions actioves sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn7_distinct");
        $params = array( 
            ':wmp_ssid' => $wmp_ssid 
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0]["count_ssn"] : 0;
    }
    
    private function session_log_count_my_active_sessions_on_trend_with_wmp ($wmp_ssid) {
        //QUOI : Le nombre de Sessions actioves sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn9");
        $params = array( 
            ':wmp_ssid'     => $wmp_ssid, 
            ':wmp_ssid1'    => $wmp_ssid 
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0]["count_ssn"] : 0;
    }
    
    private function session_log_get_active_sessions_on_trend_with_wmp ($wmp_ssid) {
        //QUOI : Les données sur les Sessions actives sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn8");
        $params = array( 
            ':wmp_ssid' => $wmp_ssid 
        );
        $datas = $QO->execute($params);
        
        return $datas;
    }
    
    private function session_log_count_active_sessions_on_trend ($trid) {
        //QUOI : Le nombre de Sessions actioves sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn5_distinct");
        $params = array( 
            ':trid' => $trid 
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0]["count_ssn"] : 0;
    }
    
}