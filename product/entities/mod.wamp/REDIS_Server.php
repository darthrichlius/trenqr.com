<?php
    require dirname(__DIR__) . '/../../vendor/autoload.php';

    $loop   = React\EventLoop\Factory::create();
    $pusher = new Ltc\REDIS_Pusher;
//    $client = new Client('tcp://127.0.0.1:5555', $loop);
//    var_dump(class_exists("React\ZMQ\Context"));
//    var_dump(class_exists("Ltc\ZMQ_Pusher"));
//    var_dump(class_exists("ZMQContext"));
//    var_dump(class_exists("Predis\Async\Client"));
    var_dump(class_exists("Thruway\Peer\Router"));
    exit();

    $loop->addPeriodicTimer(200, array($pusher, 'timedCallback'));
//    $client = new Predis\Async\Client('tcp://127.0.0.1:5555', $loop);
    $client = new Predis\Async\Client('tcp://127.0.0.1:5555', $loop);
    $client->connect(array($pusher, 'init'));

    // Set up our WebSocket server for clients wanting real-time updates
    $webSock = new React\Socket\Server($loop);
    $webSock->listen(8081, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
    $webServer = new Ratchet\Server\IoServer(
        new Ratchet\Http\HttpServer(
            new Ratchet\WebSocket\WsServer(
                new Ratchet\Wamp\WampServer(
                    $pusher
                )
            )
        ),
        $webSock
    );

    $loop->run();