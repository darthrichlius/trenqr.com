<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enty
 *
 * @author arsphinx
 */
class SEARCH extends MOTHER {
    
    /********* RULES ***********/
    /*
     * Le nombre maximal de résultats à renvoyer pour le premier jet de réponse.
     */
    private $_PFL_LIT_MAX_RESLT;
    private $_PFL_HVY_MAX_RESLT;
    private $_SRH_FILTERS;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        /*********** RULES ************/
        //LIT : LIGHT, AT : AuTomatique
        $this->_PFL_LIT_AT_POP_RSLT = 3;
        $this->_PFL_LIT_AT_KW_RSLT = 5;
        $this->_PFL_LIT_MAX_RESLT = 7;
        $this->_PFL_HVY_MAX_RESLT = 20;
        /**** TREND */
        $this->_TRD_LIT_AT_POP_RSLT = 2;
        $this->_TRD_LIT_AT_KW_RSLT = 4;
        $this->_TRD_LIT_MAX_RESLT = 6;
        $this->_TRD_HVY_MAX_RESLT = 20;
        
        /*
         * at: AuTomatique
         * pop : Populaires
         * kw : Connus
         * wn : (Why) Inconnus, Suggestions
         */
        $this->_SRH_FILTERS = ["at","pop","kw","wn"];
    }
    
    /****************************************************************************************************************************************/
    /************************************************************* PROFILE SCOPE ************************************************************/
    /****************************************************************************************************************************************/
    public function Profile_FirstResults ($sqy, $pvt_ui = NULL) {
        //sqy : SearchQuerY
        /*
         * Renvoie les résultats d'une requete transmise par CALLER.
         * Cette méthode est utilisée pour renvoyer le plus rapidement possible le résultat auprès de l'utilisateur.
         * Aussi, on ne renvoie que des données que l'on pense les plus pertinentes possibles.
         */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $sqy);
         
         /*
          * RULES :
          * La recherche suit la règle suivante :
          *     (1) La chaine de caractères peut avoir au moins 1 caractère
          *     (2) La chaine de caractère ne doit pas dépasser 50 caractères
          *     (3) La recherche se fait toujours en premier lieu dans deux scopes 
          *         1- Le scope des relations (Amis, Abonnements)
          *         2- Le scope des Compte populaires.
          *     Il s'agit de deux requetes distinctes en UNION. Pour chaque d'elle on donne comme limit MAX. 
          *     Ensuite, on récupère les données à renvoyer dans la proportion voulue.
          *     (4) Si la recherche suivant le point (3) n'a pas pu fournir un nombre de résultats > 0 et <= MAX on essaie avec d'autres critères
          *     (5) CRITERE ADDITIONNEL : La recherche se fait dans le scope des personnes inconnus.
          *     
          */
         
         $QO = new QUERY("qryl4srbxn2");
         $params = array( 
             ':sqy1'        => $sqy, 
             ':sqy2'        => $sqy,
             ':pvt_uid1'    => $pvt_ui,
             ':pvt_uid2'    => $pvt_ui, 
             ':pvt_uid3'    => $pvt_ui,
             ':pvt_uid4'    => $pvt_ui,
             ':pvt_uid5'    => $pvt_ui, 
             ':pvt_uid6'    => $pvt_ui,
             ':pvt_uid7'    => $pvt_ui,
             ':limit_kw'    => $this->_PFL_LIT_AT_KW_RSLT,
             ':limit_pop'   => $this->_PFL_LIT_AT_POP_RSLT,
             ':limit_tot'   => $this->_PFL_LIT_MAX_RESLT
         );
         $datas = $QO->execute($params);
//         var_dump(__LINE__,__FUNCTION__,[$sqy,$pvt_ui],$datas);
//         var_dump(__LINE__,__FUNCTION__,$datas);
//         exit();
         if ( ! $datas ) {
             $datas = [];
         }
         
         return $datas;
         
    }
    
    public function Profile_MrReslt ($sqy, $fil_cat, $rows = NULL, $pvt_ui = NULL) {
        /*
         * Renvoie les résultats d'une requête en fonction de la chaine de caractère fournie ainsi que des FILTREs.
         * CALLER doit fournir en priorité le filtre : "catégorie"
         * 
         * De plus, CALLER doit fournir l'identifiant pivot dans le cas du filtre "Connus".
         * Dans les autres cas, l'utilisation d'un filtre est optionnel. 
         * Cependant, la présence du pivot détermine la requête qui sera utilisée pour récupérer les données.
         * 
         * Enfin, CALLER peut fournir le nombre de lignes qu'il désire. 
         * Cette fonctionnalité est intéressante pour le FETCH_MORE.
         * Mais encore, il est à noté qu'on envoie toujours à partir de l'Offset 0.
         * Ainsi, on est sur que les données revoyées sont toujours à jour au niveau de FE.
         * Si CALLER ne fournie pas le nombre de lignes, en récupère le nombre par défaut.
         * 
         */
        /*
         * AMELIORATIONS :
         *  (1) Prendre en compte le caractère Géographique
         *  (2) Prendre en compte le caractère Relation de deuxième cercle
         * Les critères dépendent aussi des filtres utilisés
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$sqy,$fil_cat]);
        
        //On vérifie le nombre de lignes
        if (! isset($rows) ) {
            $rows = $this->_PFL_HVY_MAX_RESLT;
        } else if ( isset($rows) && intval($rows) < 1 ) {
            return "__ERR_VOL_WRG_DATAS";
        } 
        
        $rows *= $this->_PFL_HVY_MAX_RESLT;
        
        $datas = NULL;
        switch ($fil_cat) {
            case "kw":
                     if ( empty($pvt_ui) ) {
                        return "__ERR_VOL_UPVT_MSG";
                    }
                    
                    $QO = new QUERY("qryl4srbxn4");
                    $params = array( 
                        ':sqy1'         => $sqy, 
                        ':pvt_uid1'     => $pvt_ui,
                        ':pvt_uid2'     => $pvt_ui, 
                        ':pvt_uid3'     => $pvt_ui,
                        ':pvt_uid4'     => $pvt_ui,
                        ':lmt_kw_ofs'   => 0,
                        ':lmt_kw_lmt'   => $rows
                    );
                    $datas = $QO->execute($params);
                break;
            case "pop":
                     if ( !empty($pvt_ui) ) {
                        $QO = new QUERY("qryl4srbxn5");
                        $params = array( 
                            ':sqy1'         => $sqy, 
                            ':pvt_uid1'     => $pvt_ui,
                            ':pvt_uid2'     => $pvt_ui, 
                            ':pvt_uid3'     => $pvt_ui,
                            ':lmt_pop_ofs'  => 0,
                            ':lmt_pop_lmt'  => $rows
                        );
                     } else {
                         $QO = new QUERY("qryl4srbxn7");
                         $params = array( 
                            ':sqy1'         => $sqy, 
                            ':lmt_pop_ofs'  => 0,
                            ':lmt_pop_lmt'  => $rows
                        );
                     }
                    
                    $datas = $QO->execute($params);
                break;
            case "wn":
                    if ( !empty($pvt_ui) ) {
                        $QO = new QUERY("qryl4srbxn6");
                        $params = array( 
                            ':sqy1'         => $sqy, 
                            ':pvt_uid1'     => $pvt_ui,
                            ':pvt_uid2'     => $pvt_ui, 
                            ':pvt_uid3'     => $pvt_ui,
                            ':lmt_uk_ofs'   => 0,
                            ':lmt_uk_lmt'   => $rows,
                        );
                    } else {
                        $QO = new QUERY("qryl4srbxn8");
                        $params = array( 
                            ':sqy1'         => $sqy, 
                            ':lmt_uk_ofs'   => 0,
                            ':lmt_uk_lmt'   => $rows,
                        );
                    }
                    
                    $datas = $QO->execute($params);
                break;
            default :
                break;
        }
         
        if ( ! $datas ) {
             $datas = [];
        }
         
        return $datas;
        
    }
    
    
    /****************************************************************************************************************************************/
    /************************************************************* TREND SCOPE **************************************************************/
    /****************************************************************************************************************************************/
    
    public function Trend_FirstResults ($sqy, $pvt_ui = NULL) {
        //sqy : SearchQuerY
        /*
         * Renvoie les résultats d'une requete transmise par CALLER.
         * Cette méthode est utilisée pour renvoyer le plus rapidement possible le résultat auprès de l'utilisateur.
         * Aussi, on ne renvoie que des données que l'on pense les plus pertinentes possibles.
         */
        
        /**
         * RULES : 
         *  (1) On cherche dans les Tendances qui nous appartiennent
         *  (2) On cherche dans les Tendances que l'on suit
         *  (3) On cherche dans les Tendances populaires
         */
        
        $QO = new QUERY("qryl4srbxn10neo0815001");
//        $QO = new QUERY("qryl4srbxn10");
         $params = array( 
             ':sqy1'        => $sqy, 
             ':sqy2'        => $sqy,
             ':pvt_uid1'    => $pvt_ui,
             ':pvt_uid2'    => $pvt_ui, 
             ':pvt_uid3'    => $pvt_ui,
             ':pvt_uid4'    => $pvt_ui, 
             ':pvt_uid5'    => $pvt_ui,
             ':pvt_uid6'    => $pvt_ui, 
             ':pvt_uid7'    => $pvt_ui,
             ':lmt_kw_me'   => $this->_PFL_LIT_AT_KW_RSLT,
             ':lmt_pop'     => $this->_PFL_LIT_AT_POP_RSLT,
             ':lmt_tot'     => $this->_PFL_LIT_MAX_RESLT
         );
         $datas = $QO->execute($params);
//         var_dump(__FUNCTION__,__LINE__,$sqy,$pvt_ui,$this->_PFL_LIT_AT_KW_RSLT,$this->_PFL_LIT_AT_POP_RSLT,$this->_PFL_LIT_MAX_RESLT);
//         var_dump(__FUNCTION__,__LINE__,$datas);
         if ( ! $datas ) {
             $datas = [];
         }
         
         return $datas;
    }
    
    public function Trend_MrReslt ($sqy, $fil_cat, $rows = NULL, $pvt_ui = NULL) {
        /*
         * Renvoie les résultats d'une requête en fonction de la chaine de caractère fournie ainsi que des FILTREs.
         * CALLER doit fournir en priorité le filtre : "catégorie"
         * 
         * De plus, CALLER doit fournir l'identifiant pivot dans le cas du filtre "Connus".
         * Dans les autres cas, l'utilisation d'un filtre est optionnel. 
         * Cependant, la présence du pivot détermine la requête qui sera utilisée pour récupérer les données.
         * 
         * Enfin, CALLER peut fournir le nombre de lignes qu'il désire. 
         * Cette fonctionnalité est intéressante pour le FETCH_MORE.
         * Mais encore, il est à noté qu'on envoie toujours à partir de l'Offset 0.
         * Ainsi, on est sur que les données revoyées sont toujours à jour au niveau de FE.
         * Si CALLER ne fournie pas le nombre de lignes, en récupère le nombre par défaut.
         * 
         */
        /*
         * AMELIORATIONS :
         *  (1) Prendre en compte le caractère Géographique
         *  (2) Prendre en compte le caractère Relation de deuxième cercle
         * Les critères dépendent aussi des filtres utilisés
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$sqy,$fil_cat]);
        
        //On vérifie le nombre de lignes
        if (! isset($rows) ) {
            $rows = $this->_TRD_HVY_MAX_RESLT;
        } else if ( isset($rows) && intval($rows) < 1 ) {
            return "__ERR_VOL_WRG_DATAS";
        } 
        
        $rows *= $this->_TRD_HVY_MAX_RESLT;
        
        $datas = NULL;
        switch ($fil_cat) {
            case "kw":
                     if ( empty($pvt_ui) ) {
                        return "__ERR_VOL_UPVT_MSG";
                    }
                    
                    $QO = new QUERY("qryl4srbxn11");
                    $params = array( 
                        ':sqy1'             => $sqy, 
                        ':pvt_uid1'         => $pvt_ui,
                        ':pvt_uid2'         => $pvt_ui, 
                        ':pvt_uid3'         => $pvt_ui,
                        ':pvt_uid4'         => $pvt_ui, 
                        ':lmt_kw_me_ofs'    => 0,
                        ':lmt_kw_me_lmt'    => $rows
                    );
                    $datas = $QO->execute($params);
                break;
            case "pop":
                     if ( !empty($pvt_ui) ) {
                        $QO = new QUERY("qryl4srbxn12");
                        $params = array( 
                            ':sqy1'         => $sqy, 
                            ':pvt_uid1'     => $pvt_ui,
                            ':pvt_uid2'     => $pvt_ui,
                            ':pvt_uid3'     => $pvt_ui,
                            ':lmt_pop_ofs'  => 0,
                            ':lmt_pop_lmt'  => $rows
                        );
                     } else {
                         $QO = new QUERY("qryl4srbxn14");
                         $params = array( 
                            ':sqy1'         => $sqy, 
                            ':lmt_pop_ofs'  => 0,
                            ':lmt_pop_lmt'  => $rows
                        );
                     }
                    
                    $datas = $QO->execute($params);
                break;
            case "wn":
                    if ( !empty($pvt_ui) ) {
                        $QO = new QUERY("qryl4srbxn13");
                        $params = array( 
                            ':sqy1'         => $sqy, 
                            ':pvt_uid1'     => $pvt_ui,
                            ':pvt_uid2'     => $pvt_ui, 
                            ':lmt_uk_ofs'   => 0,
                            ':lmt_uk_lmt'   => $rows,
                        );
                    } else {
                        $QO = new QUERY("qryl4srbxn15");
                        $params = array( 
                            ':sqy1'         => $sqy, 
                            ':lmt_uk_ofs'   => 0,
                            ':lmt_uk_lmt'   => $rows,
                        );
                    }
                    
                    $datas = $QO->execute($params);
                break;
            default :
                break;
        }
         
        if ( ! $datas ) {
             $datas = [];
        }
         
        return $datas;
        
    }
    
    /****************************************************************************************************************************************/
    /************************************************************** LOGS SCOPE **************************************************************/
    /****************************************************************************************************************************************/
}
