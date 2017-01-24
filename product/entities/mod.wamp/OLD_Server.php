<?php
/*
    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    use Ltc\Chat;

    require dirname(__DIR__) . '/../../vendor/autoload.php';
    
//    var_dump(class_exists("Ltc\Chat"));
//    exit();

    session_start();
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        8081
    );

    $server->run();
    //*/
    
    // Your shell script
use Ratchet\Session\SessionProvider;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;
use Ratchet\App;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ltc\Chat;

require dirname(__DIR__) . '/../../vendor/autoload.php';

//    var_dump(class_exists("App"),class_exists("Chat"),class_exists("Memcache"),class_exists("SessionProvider"));
//    exit();

    $memcache = new Memcache;
    $memcache->connect('127.0.0.1', 11211) or die ("Could not connect");
    
    $session = new SessionProvider(
        new Chat
      , new Handler\MemcacheSessionHandler($memcache)
    );
    
    //*
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        8081
    );
   
    $server->run();
    //*/
   
    //*
    $server = new App('localhost',8081,"127.0.0.1");
    $server->route('/sessDemo', $session);
    $server->run();
    //*/