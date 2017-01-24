<?php 
/*
 * [NOTE 03-08-15] @author BOR
 *  Cette application a pour principal objectif de mettre à jour la base de données de manière automatique.
 *  Les applications se concentrent aussi et surtout sur l'archivage voire l'effacement de certaines données. 
 *  L'avantage de cette application réside dans le fait d'agir comme un deamon en arrière plan de manière autonome. 
 * 
 *  L'application necessite qu'on lui indique qu'elle opération doit être lancée.
 *  On aurait pu adopter une approche où on lancerait toutes les opérations sans qu'il soit obliger de spécifier l'opération.
 *  Cependant, cela aurait pu posser des problèmes de sécurité.
 *  Mais encore, lancer toutes les opérations d'un seul bloc peut avoir des conséquences en ce qui concerne la performance de la plateforme.
 *  Dans le pire des cas, cela peut nuire à sa stabilité.
 * 
 */

/*
 * ETAPE : On charge l'environnement.
 */
require_once "../env/env.php";

$_KEY_OPER = $_GET["oper"];
if (! $_KEY_OPER ) {
    exit();
}

function tqsta_upd_todel () {
    $TQACC = new TQR_ACCOUNT();
    $TQACC->ondetele_update_todel_state_all();
}

function main ($_KEY_OPER) {
    
    switch ($_KEY_OPER) {
        case "TQSTA_UPD_ACC_TODEL" :
                /*
                 * [NOTE] @author BOR
                 *  L'objectif est de changer l'état TODELETE des comptes.
                 *  Lorsqu'un compte passe en mode TODEL sont etat est à : 1.
                 *  Cet état permet au propriétaire du compte de poouvoir de nouveau accéder à son compte avant la fin du delai de carence.
                 */
                 tqsta_upd_todel();
            break;
        case "TQSTA_UPD_TRD_TODEL" :
                /*
                 */
            break;
        default:
                /*
                 * TODO : Lancer un processus ERROR_404
                 */
            break;
    }
}


main($_KEY_OPER);


?>