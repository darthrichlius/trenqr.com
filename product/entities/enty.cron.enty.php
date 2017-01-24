<?php

/**
 * CRON
 *
 * @author Lou Carther
 */
class CRON extends MOTHER {
    
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        
    }
    
    /**************************************************************************************************************************************/
    /********************************************************** UPDATE SCOPE **************************************************************/
    
    public function update_SESSION () {
        /*
         * Permet de mettre à jour les données de SESSION. Il s'agit surtout de mettre à jour les données de profil (RSTO)
         * Cependant, certaines données dans STO doivent aussi être update. D'où la mise à jour de toutes les données.
         * La mise à jour à lieu toutes les 5 minutes.
         * 
         * L'amélioration des performances permettra de descendre à un cycle plus court.
         */
    }
    
    public function uptable_VMIML () {
        /*
         * Permet de mettre à jour la table qui sauvegarde les Articles de type IML dans la table VM (View M?).
         * Cette mise à jour est impérative dans le sens où, il s'agit des Articles qui sont directement présentés à l'utilisateur.
         * 
         * Mettre à jour signifier aussi bien modifier les données que supprimer les Articles qui ne sont plus dans la table de référence.
         * Pour limiter les désagréments, la table est lock le temps du traitement.
         * La mise à jour à lieu toutes les 5 minutes.
         */
        
        
    }
    
    public function uptable_VMITR () {
        /*
         * Permet de mettre à jour la table qui sauvegarde les Articles de type ITR dans la table VM (View M?).
         * Cette mise à jour est impérative dans le sens où, il s'agit des Articles qui sont directement présentés à l'utilisateur.
         * 
         * Mettre à jour signifier aussi bien modifier les données que supprimer les Articles qui ne sont plus dans la table de référence.
         * Pour limiter les désagréments, la table est lock le temps du traitement.
         * La mise à jour à lieu toutes les 5 minutes.
         */
    }
    
    public function uptable_VMREL () {
        /*
         * Permet de mettre à jour la table qui sauvegarde les relation dans la table VM (View M?).
         * Cette mise à jour est impérative dans le sens où, il s'agit des Articles qui sont directement présentés à l'utilisateur.
         * 
         * La mise à jour à lieu toutes les 5 minutes.
         */
    }
    
    public function uptable_SHRPFL () {
        /*
         * Mise à jour de la table qui permet d'effectuer des recherches sur les Profils.
         * 
         * La mise à jour à lieu toutes les 5 minutes.
         */
    }
    
    public function uptable_SHRTRD () {
        /*
         * Mise à jour de la table qui permet d'effectuer des recherches sur les Tendances.
         * 
         * La mise à jour à lieu toutes les 5 minutes.
         */
    }
    
    /**************************************************************************************************************************************/
    /********************************************************** STATS UPDATING ************************************************************/
    
    public function stats_Population () {
        /*
         * Recense les comptes ajoutés, en cours de suppression, supprimés.
         * 
         * L'opération a lieu toutes les 5 minutes.
         */
    }
    
    public function stats_Articles () {
        /*
         * Recense les Articles : totaux, ajoutés
         * La méthode traite aussi bien les Articles IML que les Articles ITR.
         * 
         * L'opération a lieu toutes les 5 minutes.
         */
    }
    
    public function stats_Trends () {
        /*
         * Recense les Tendances : totaux, ajoutés
         * 
         * L'opération a lieu toutes les 5 minutes.
         */
    }
            
            
            
}
