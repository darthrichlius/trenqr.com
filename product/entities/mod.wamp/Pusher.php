<?php
namespace Ltc;

class Pusher extends \WampPost\WampPost {
    
    protected $loop; 
    protected $_sessions = [];
    protected $subscribedTopics = array();
    
    public function __construct($realm, $loop = null, $bindAddress = '127.0.0.1', $port = 8181) {
        parent::__construct($realm, $loop, $bindAddress, $port);
        $this->loop = $loop;
        
//        $this->clients = new \SplObjectStorage;
    }
    
    public function onSessionStart($session, $transport)
    {
//        $this->on("close", [$this, 'onSessionLeave']);
        echo "Started";
        $session->subscribe('wamp.metaevent.session.on_join',  [$this, 'onSessionJoin']);
        /*
        $context = new React\ZMQ\Context($this->getLoop());
        $pull    = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555');
        $pull->on('message', [$this, 'onAjaxEntry']);
        //*/
        /*
        $client = new \Predis\Async\Client('tcp://127.0.0.1:5555', $this->loop);
        $r = $client->connect(array($this, 'init'));
        
        var_dump(__FILE__,__FUNCTION__,__LINE__, $this->loop, $r);
        //*/
        /*
        $session->subscribe('wamp.metaevent.session.on_join',  [$this, 'onSessionJoin']);
        $session->subscribe('wamp.metaevent.session.on_leave', [$this, 'onSessionLeave']);
        //*/
    }
    
    /***************************************************************************************************************************************************/
    
    public function onSessionJoin ($args, $kwArgs, $options) {
        $this->_sessions[] = $args[0];
        
        /*
         * TODO :
         *      Renvoyer le nombre de personnes qui sont connectés
         */
    }
    
    public function getOnline() {
        //Renvoie les informations sur les utilisateurs connectés
        return [$this->_sessions];
    }
    
    public function onSessionLeave () {
        
    }
    
    public function onAjaxEntry () {
        
    }
    
    public function init () {
        echo "Redis ?";
    }
    
    /***************************************************************************************************************************************************/

}

