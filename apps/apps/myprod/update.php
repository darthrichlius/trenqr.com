<?php 
/*
 * Ce fichier est utilisé pour mettre à jour les données statistiques.
 * Il est appelé par un module à exécution automatique et périodique.
 */

/*
 * ETAPE : On charge l'environnement.
 */
require_once "../env/env.php";

/*
 * On lance le processus de mise à jour des données.
 */

$TQR = new TRENQR();
$TQR->tqsta_update_from_now();

?>