<?php

//require_once "LtcEnv.inc.php";
require dirname(__DIR__) . '/../../vendor/autoload.php';


$router = new Ltc\LtcRouter();


/*
 * La dénomination "Wolverine" donne un air tranchant au REALM.
 * On même temps il peut contrer, ou laisser passer.
 */
$realm = "ltc.realm.wolverine";

$loop = React\EventLoop\Factory::create();
/*
 * Ce Client gérera toutes les ACTIONS. Quelque soit le canal d'accès.
 * On laisse la charge au ROUTER d'interdir certaines actions
 */
$internal_client = new Ltc\LtcInternalClient($router, $realm, $loop); 

//// 

$authorizationManager = new \Thruway\Authentication\AuthorizationManager($realm);
$rules = [
    (object)[
        "role"      => "anonymous",
        "action"    => "publish",
        "uri"       => "",
        "allow"     => false
    ],
    (object)[
        "role"      => "anonymous",
        "action"    => "subscribe",
        "uri"       => "",
        "allow"     => true
    ],
    (object)[
        "role"      => "anonymous",
        "action"    => "call",
        "uri"       => "",
        "allow"     => false
    ],
    (object)[
        "role"      => "anonymous",
        "action"    => "register",
        "uri"       => "",
        /*
         * [EN THEORIE]
         *      Permet aux clients de REGISTER.
         *      Sert surtout au CLIENT interne qui a besoin de REGISTER des fonctions pour les clients HTTP
         */
        "allow"     => true 
//        "allow"     => false 
    ]
];

foreach ($rules as $rule) {
    $authorizationManager->addAuthorizationRule([$rule]);
}
$router->registerModule($authorizationManager);

$router->getRealmManager()->setAllowRealmAutocreate(true);

$router->addInternalClient($internal_client);
//$router->addTransportProvider(new Thruway\Transport\RatchetTransportProvider("37.59.53.98", 8888));
$router->addTransportProvider(new Thruway\Transport\RatchetTransportProvider("0.0.0.0", 8081));

///

$trustedTransport = new Thruway\Transport\RatchetTransportProvider("127.0.0.1", 5555);
/*
 * Donnne un ROLE d'admin qui a pour conséquence d'annuler les restrictions établies plus haut.
 */
$trustedTransport->setTrusted(true); 
$router->addTransportProvider($trustedTransport);

$router->start();