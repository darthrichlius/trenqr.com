<?php

/* 
 * STUS : ServerTimeUniqueSource
 * Fournit le temps du serveur a un moment donné.
 * Ce script s'affranchit de tout controle supplémentaire pour des raisons de performance.
 * Les modules qui utilisent cette fonctionnalité ont besoin d'un réponse le plus rapidement possible car elle leur permette ...
 * ... de fournir d'autres services.
 */
echo json_encode([ "return" => round(microtime(TRUE)*1000) ]);
?>