<?php

namespace Ltc;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class REDIS_Pusher implements WampServerInterface {
    protected $clients;
    protected $redis;
    protected $subscribedTopics = array();

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }
    
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        echo "Subscribing";
        $this->subscribedTopics[$topic->getId()] = $topic;
        var_dump("Subscribing", $conn, $topic);
    }
    
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        echo __FUNCTION__;
        exit();
        var_dump("UnSubscribing", $conn, $topic);
    }
    
    public function onOpen(ConnectionInterface $conn) {
        echo "New connection! ({$conn->WAMP->sessionId})";
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

//        echo "Connection opened ! \n Connected number : ({$this->clients->count})\n";
        echo "New connection! ({$conn->resourceId})\n";
    }
    
    public function onClose(ConnectionInterface $conn) {
        echo __FUNCTION__;
        exit();
        echo "Connection closed";
    }
    
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        echo __FUNCTION__;
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to use this application')->close();
    }
    
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        echo __FUNCTION__;
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
    
    /********************************************************************************************************************************************************************/
    /*************************************************************************** CUSTUM CSOPE ***************************************************************************/
    
    public function onToSubmitEntry($entry) {
        $entryData = json_decode($entry, true);

        // If the lookup topic object isn't set there is no one to publish to
        if (! array_key_exists($entryData['category'], $this->subscribedTopics) ) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['category']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }
    
    public function timedCallback() {
        if (array_key_exists('debug', $this->subscribedTopics)) {
            $topic = $this->subscribedTopics['debug'];
            $topic->broadcast('Unix timestamp is ' . time());
        }
    }
    public function init($client) {
        $this->redis = $client;
        $this->log("Connected to Redis, now listening for incoming messages...");
    }
    
    /**
     * echo the message and also broadcast to channel 'debug'
     */
    public function log($value)
    {
        $message = sprintf("Pusher: %s", $value);
        echo "$message\n";
        if (array_key_exists('debug', $this->subscribedTopics)) {
            $topic = $this->subscribedTopics['debug'];
            $topic->broadcast($message);
        }
    }
}