<?php
namespace Ltc;

require_once "LtcEnv.inc.php";
use QUERY;

use React\EventLoop\Factory;

use Psr\Log\NullLogger;
use Thruway\Logging\Logger;

class LtcHttpClient extends \Thruway\Peer\Client {
    protected $loop; 
    protected $session;
    
    public function __construct($realm, $loop = null) {
        Logger::set(new NullLogger());
        $this->loop = ( $loop ) ?: Factory::create();
        parent::__construct($realm, $loop);
        
        $reconnectOptions = [
          "max_retries"         => 0,
          "initial_retry_delay" => 1.5,
          "max_retry_delay"     => 300,
          "retry_delay_growth"  => 1.5,
          "retry_delay_jitter"  => 0.1 //not implemented
        ];
        $this->setReconnectOptions($reconnectOptions);
    }
    
    public function onSessionStart($session, $transport)
    {
        $this->session = $session;
//        $this->session->getTransport()->close();
        /*
        $session->publish('ltc.session.on_join',[],[
            "ssid" => $session->getSessionId()
        ]);
        $session->call('ltc.session.app.mtune', [])->then(
            function ($res) {
                var_dump("\nLTC_DEBUG :",$res->getArguments(),"\n");
            },
            function ($error) {
                echo "Call Error: {$error}\n";
            }
        );
        //*/
    }
    
    public function snitchOnJoin ($args, $kwArgs, $options) {
        var_dump("snitchOnJoin : \n", $args, "\n", $kwArgs);
    }
    
    /***************************************************************************************************************************************************************/
    
    public function warmNewSessionAsync ($sessionId) {
        
        if ( $this->session ) {
            $this->session->subscribe("ltc.session.event.onjoin", function($args, $kwArgs, $options){});
            
            $this->session->call('ltc.session.apps.getonline', [])->then(
                function ($res) use ($sessionId) {
        
                    $this->session->publish('ltc.session.event.onjoin',[],[
                        "ssid"      => $sessionId,
                        "online"    => $res->getArguments(),
                    ])->then(function(){
                        /*
                         * Cette méthode ne sert qu'à mettre à jour les données auprès de tous les SUBSCRIBERS, 
                         * ... la SESSION est automatiquement après l'execution de l'opération.
                         */
                        $this->session->close();
                    });
                },
                function ($error) {
                    echo "Error: {$error}\n";
                }
            );
        }
    }
    
    
    public function relayMessage ($chan, $uid, $wcsi, $trid, $user, $message, $OPTIONS = NULL, $FinWithClose = TRUE) {
//        var_dump("\n",__LINE__,$chan, $message, $FinWithClose,"\n");
//        var_dump("\n","RELAY_MESSAGE",__LINE__,[$OPTIONS],"\n");
        
        /*
         * ETAPE :
         *      On récupère les données sur les connexions actives.
         *      L'objectif étant de n'envoyer les données qu'aux Comptes connectés à la Tendane passé en paramètre.
         */
//        $active_sessions = $this->session_log_get_active_sessions_on_trend($trid);
//        if (! $active_sessions ) {
//            return "__ERR_VOL_NO_ACTIVE_SESSION";
//        }
        
        /*
         * ETAPE :
         *      Le nombre de connexions nous servura d'indicatif pour déterminer la fin de l'opération.
         */
//        $count_as = count($active_sessions);
        
        $online = $this->session_log_count_active_sessions_on_trend($trid);
        $my_online = $this->session_log_count_my_active_sessions_on_trend($uid,$trid);
        
        /*
        var_dump(__FILE__,__FUNCTION__,__LINE__,[
            "uid"       => $uid,
            "trid"      => $trid,
            "online"    => $online,
            "my_online" => $my_online,
        ]);
        //*/
        
        $this->session->publish($chan, [], [
            "online"    => $online,
            "my_online" => $my_online,
            "user"      => $user,
            "msg"       => $message,
            "xtras"     => $OPTIONS
        ],["acknowledge" => true])->then(
            /*
             * ETAPE :
             *      On attend que toutes les Messages aient été envoyées avant de cloturer la Session courante
             */
            function () use ($FinWithClose) {
                $this->session->close();
            },
            function ($error) {
                echo "Publish Error {$error}\n";
            }
        );
        
    }
    
    public function _onMessage ($args, $kwArgs, $options) {
        var_dump(__FUNCTION__,__LINE__,"onMessage",$kwArgs);
    }
    
    public function test_sendMessage ($message) {
        $this->session->publish("ltc.chat.app.mtune", [], [
            "ms" => ( $message ) ?: "Hello from the other side :)"
        ],["acknowledge" => true])->then(
            function () {
               $this->session->close();
            },
            function ($error) {
                echo "Publish Error {$error}\n";
            }
        );
        
    }
    
    
    /************************************** session_log scope **************************************/
    
    
    private function session_log_get_active_sessions_on_trend ($trid) {
        //QUOI : Les données sur les Sessions actives sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn6");
        $params = array( 
            ':trid' => $trid 
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas : [];
    }
    
    private function session_log_count_active_sessions_on_trend ($trid) {
        //QUOI : Le nombre de Sessions actives sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn5_distinct");
        $params = array( 
            ':trid' => $trid 
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0]["count_ssn"] : 0;
    }
    
    private function session_log_count_my_active_sessions_on_trend ($uid,$trid) {
        //QUOI : Le nombre de Sessions actives sur la Tendance passée en paramètre
        
        $QO = new QUERY("qryl4ltc_sslogn10");
        $params = array( 
            ':uid'  => $uid, 
            ':trid' => $trid, 
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0]["count_ssn"] : 0;
    }
    
}