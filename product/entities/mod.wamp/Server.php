<?php
require dirname(__DIR__) . '/../../vendor/autoload.php';

$router = new Thruway\Peer\Router();
$realm = "realm1";

$loop = React\EventLoop\Factory::create();
$pusher = new Ltc\REDIS_Pusher;
//    $client = new Client('tcp://127.0.0.1:5555', $loop);
//    var_dump(class_exists("React\ZMQ\Context"));
//    var_dump(class_exists("Ltc\ZMQ_Pusher"));
//    var_dump(class_exists("ZMQContext"));
//    var_dump(class_exists("Predis\Async\Client"));
//    var_dump(class_exists("Thruway\Peer\Router"));
//    exit();

//$loop->addPeriodicTimer(200, array($pusher, 'timedCallback'));
////    $client = new Predis\Async\Client('tcp://127.0.0.1:5555', $loop);
//$client = new Predis\Async\Client('tcp://127.0.0.1:5555', $loop);
//$client->connect(array($pusher, 'init'));
/*
//////// WampPost part
// The WampPost client
// create an HTTP server on port 8181 - notice that we have to
// send in the same loop that the router is running on
//$wp = new WampPost\WampPost('realm1', $router->getLoop(), '127.0.0.1', 5555);
//$wp = new Ltc\Pusher('realm1', $router->getLoop(), '127.0.0.1', 5555);

// add a transport to connect to the WAMP router
$router->addTransportProvider(new Thruway\Transport\InternalClientTransportProvider($wp));
//////////////////////

// The websocket transport provider for the router
$router->addTransportProvider(new Thruway\Transport\RatchetTransportProvider("0.0.0.0", 8081)); // Binding to 0.0.0.0 means remotes can connect
$router->start();
//*/
//*
//$router->addInternalClient(new Ltc\Pusher($realm, $loop));
$router->addTransportProvider(new Thruway\Transport\RatchetTransportProvider("0.0.0.0", 8081));
$router->start();