<?php

/**
 * DATA_CLEANER est un module qui permet de verifier et/ou de nettoyer les données provenant de l'exterieur.
 * Dans la grande majorité, ces données sont présentes dans les variables $_GET et $_POST.
 * Il est conseillé d'utilisé ce module avant tout process de traitement de la requete.
 */
Class DATAS_CLEANER extends MOTHER
{
    private $datas_and_infos_in_array;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        //htmlentities
        $this->treat_new_urq ();
    }
    
    
    /**
     * Permet de nettoyer toutes les données dans un tableau. 
     * L'argument 'option' permet de spécifier le comportement à adopter en cas de detection de données sales. 
     * Faut-il juste nettoyer ou déclencher une erreur.
     * DCNR_PARAMS_THROW_ERROR 
     * DCNR_PARAMS_JUST_CLEAN 
     * @param type $entry_array
     * @param EXC (params) $options
     */
    private function treat_new_urq ($entry_array, $options = EXC_ABORT) {
        //1) On commnce par se renseigner si urq attend des données POST
        //2) Si Oui, on regarde la concordance avec ce qu'on a dans la liste URQ
            //21) S'il y a pas de problème, on continue. Sinon on déclenche une erreur avec mentionné, TENTATIVE D'INTRUISION
            //22) Sinon 
                //220) On corriger les données (maj => minus)  
                //221) On envoie la variable POST pour detection de type
                //222) Pour chaque variable, 
                    //on verifie s'il ne s'agit pas d'un type specifique (EMAIL, PASSWORD, LOGIN, TEL, DATE, ...) necessitant des controle particulier
                    //223) Sinon on effectue un CONTROLE/VERIFICATION standard
    }
    
    
    private function correct_datas ($entry_POST) {
        foreach ($array as $key => $value) {
            $new_key = strtolower($key);
            $new_value = strtolower($value);
            $_POST[$new_key] = $new_value;
        }
    }
    
    
    //Permet de verifier le type de données pour voir s'il faut un traitement specifique
    //La detection se fait d'abord en fonction de la key de la variable
    //On prend plusieurs instances de noms pour une seule. Ex : password => pwd, passwd, mdp, mot_de_passe...
    private function detect_incoming_data_type ($entry_POST) {
        //On ne controle pas $entry on estime que le controle a déjà été réalisé
        /*
         * Liste des types au [27-09-2013] :
         * - std
         * - email
         * - tel
         * - login
         * - date
         * - password  
         */
        $this->datas_and_infos_in_array = array(array());
        foreach ($array as $key => $value) {
            switch ($key) {
                case "password" :
                case "pwd" :
                case "passwd" :
                case "mdp" :
                case "mot_de_passe" :
                case "pass" :
                    $this->datas_and_infos_in_array[] = [
                        "type" => WOS_DATA_TYPE_PASS,
                        "key" => $key,
                        "value" => $value
                    ];
                    break;
                case "login" :
                case "pseudo" :
                    $this->datas_and_infos_in_array[] = [
                        "type" => WOS_DATA_TYPE_LOGIN,
                        "key" => $key,
                        "value" => $value
                    ];
                    break;
                case "phone" :
                case "tel" :
                case "phone_number" :
                    $this->datas_and_infos_in_array[] = [
                        "type" => WOS_DATA_TYPE_TEL,
                        "key" => $key,
                        "value" => $value
                    ];
                    break;
                case "date" :
                case "date_naiss" :
                case "date_naiss" :
                case "day" :
                case "jour" :
                case "naiss" :
                case "d_naiss" :
                case "naissance" :
                case "borndate" :
                case "birthdate" :
                    $this->datas_and_infos_in_array[] = [
                        "type" => WOS_DATA_TYPE_DATE,
                        "key" => $key,
                        "value" => $value
                    ];
                    break;
                case "mail" :
                case "email" :
                case "courriel" :
                    $this->datas_and_infos_in_array[] = [
                        "type" => WOS_DATA_TYPE_EMAIL,
                        "key" => $key,
                        "value" => $value
                    ];
                    break;
                case "hashtag" :
                    $this->datas_and_infos_in_array[] = [
                        "type" => WOS_DATA_TYPE_HASHTAG,
                        "key" => $key,
                        "value" => $value
                    ];
                    break;
                default :
                    //On suppose donc que c'est autre chose, et qu'il n'y a pas de controle spécifique à faire.
                    $this->datas_and_infos_in_array[] = [
                        "type" => WOS_DATA_TYPE_STD,
                        "key" => $key,
                        "value" => $value
                    ];
                    break;;
            }
        }
        return $this->datas_and_infos_in_array;
    }

}
?>
