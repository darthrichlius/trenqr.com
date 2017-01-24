<?php

/*
 * Permet de mettre à jour les données liées aux Haashtags.
 * Depuis tqr.vb2.0, les données "hashtag" sont gérées par HVIEW.
 * Il faut donc transférer les anciennes données vers la nouvelle table.
 */

/*
 * ETAPE : On charge l'environnement.
 */
require_once "../env/env.php";

$HVIEW = new HVIEW();
$r = $HVIEW->SPE_TRANSFERT_HSH();

var_dump(__LINE__,__FUNCTION__,__FILE__,$r);