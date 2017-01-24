<?php
require dirname(__DIR__) . '/../../vendor/autoload.php';

//var_dump(class_exists("WampPost\WampPost"),class_exists("Thruway\Transport\PawlTransportProvider"));
//exit();
     /*   
$redis = new Predis\Async\Client('tcp://127.0.0.1:5555');
$redis->publish("channel:toto", "Un message" );
//*/

// create an HTTP server on port 8181
//$wp = new Ltc\LtcHttpClient('ltc.realm.wolverine', null, '127.0.0.1', 8181);
$wp = new Ltc\LtcHttpClient('ltc.realm.wolverine', null);

// add a transport to connect to the WAMP router
$wp->addTransportProvider(new Thruway\Transport\PawlTransportProvider('ws://127.0.0.1:5555/'));

////

/*
$wp->on("open",function($session,$transport) use ($wp){
    $session->subscribe("ltc.chat.app.mtune", [$wp,"_onMessage"]);
    $wp->test_sendMessage("Is it my last noght with you ?");
});
//*/

$message = "TEST TEST TEST TEST";
$chan = "ltc.chat.app.mtune";
$wcsi = "000000000";
$wp->on("open",function($session) use ($wp,$chan,$wcsi,$message){
            
    /*
     * TODO :
     *      -> Vérifier que la CHAINE existe. Elle déclarée par au moins un des CLIENTS à la connexion.
     *      -> Vérifier que le COMPTE est bien connecté à la liste des SUBS de cette CHAINE.
     */
    $session->subscribe($chan, [$wp,"_onMessage"]);
    $wp->relayMessage($chan, $message);
});

////

// start the WampPost client
$wp->start();

