<?php

/**
 * <p>
 * La classe abstraite Prod_Entity permet de définir des droits et devoirs pour les PROD_ENTITY.<br/>
 * Les fonctions abtraites contenues dans la classe permettent de définir des actions à entreprendre pour chaque event.
 * Il ne s'agit pas ici de traiter les events liés aux propriétés mais à l'objet dans sa globalité. 
 * </p>
 * @author RDL
 */
abstract class PROD_ENTITY extends MOTHER {
    protected $is_instance_load;
    protected $all_properties;
    protected $art_oncreate_args;
    /**
     * Etant donné qu'on utilise la déclaration dynamique des variables, ce tableau permet d'avoir sous la main tous les noms de variables.
     * Sinon, on pourrait essayer d'atteindre $article quand il n'existe pas car il n'a pas été déclaré. 
     * Si on ajoute une nouvelle propriété il faudra donc bien penser à rajouter sa clé au niveau de $prop_keys; 
     * 
     * @var array 
     */
    protected $prop_keys;
    /**
     * Ce tableau est un dérivé de $art_oncreate_args à la seule différence que les propriétés listées ci-dessous doivent être obligatoirement présentes
     * pour que l'instance soit chargée.
     * 
     * @var array 
     */
    protected $needed_to_loading_prop_keys;
    protected $needed_to_create_prop_keys;
    protected $needed_to_update_prop_keys;
    
    /*
     * [DEPUIS 19-08-16]
     *      Permet de mettre de côté certaines données.
     *      L'avantage c'est par exemple de ne pas refaire indéfinement les mêmes opérations au risque de ternir les PERF
     */
    protected $entity_cache;
    
    protected abstract function build_volatile ($args);

    protected abstract function load_entity($args);
    protected abstract function init_properties($datas);
    /**
     * A la creation de l'objet. 
     * Exemple : ... au niveau de la base de données.
     */
    protected abstract function on_create_entity($args);
    /**
     * A la modification de l'objet.
     * Exemple : ... au niveau de la base de données.
     */
    protected abstract function on_alter_entity($args);
    /**
     * A la suppression de l'objet.
     * Exemple : ... au niveau de la base de données.
     */
    protected abstract function on_delete_entity($args);
    /**
     * A la lecture de l'objet. Peut se traduire par, lorsque l'entite client souhaite acquerir l'objet. 
     * Exemple : ... par user.
     */
    protected abstract function on_read_entity($args);
    protected abstract function exists($args);
    /**
     * Ecrire l'objet au niveau de la base de données.
     */
    protected abstract function write_new_in_database($args);
    //protected abstract function write_new_in_database($args);

    public function getAll_properties() {
        return $this->all_properties;
    }
    
    /**
     * Vérifie si les propriétes attendues, triées dans le tableau $props, sont présentes dans le tableau fourni $args.
     * Les deux tableaux sont des tableaux contenants les clés nominatives.
     *  
     * @param Array $args Tableaux contenant les arguments à vérifier.
     * @param Array $props Tableaux contenant les arguments pivots servant à la comparaison.
     * @return boolean
     */
    public function CheckAllPropExist ($args,$props) {
        $r = ( count(array_intersect($args,$props)) === count($props) );
        return $r;
    }
    
    public function getIs_instance_load() {
        $r = ( isset($this->is_instance_load) ) ? $this->is_instance_load : FALSE;
        
        return $r;
    }
    
    
    /***************************** EID & OTHERS **********************/
    public function serverName_encode($servername){
        //On va faire un truc très simple, juste pour éviter que les gens ne fassent le rapprochement
        //Base 32. Tous les caractères usuels (a-zA-Z0-9) seront codés sur 2 caractères.
        $output = '';
        for($i = 0; $i < strlen($servername); $i++){
            $ord = ord($servername[$i]);
            $b32 = base_convert(intval($ord), 10, 32);
            $output .= $b32;
        }
        return $output;
    }
    
    public function serverName_decode($codename){
        //On sait que les éléments de la chaîne de base sont codés sur 2 caractères
        //Donc:
        $output = '';
        $strArray = str_split($codename, 2);
        foreach($strArray as $char){
            $b10 = base_convert($char, 32, 10);
            $chr = chr($b10);
            $output .= $chr;
        }
        return $output;
    }
        
    /**
    * HS: Fonction de création de l'IEID, extraite de la fonction de création du nom de l'image
    * @param int $upload_tstamp Timestamp d'upload de l'image (millisecondes)
    * @param int $id ID de l'élément
    */
    public function entity_ieid_encode($upload_tstamp_ms, $id){
        //On doit convertir les ms en s pour avoir des dates 'normales' au décode
        $sec_tstamp = floor(intval($upload_tstamp_ms)/1000);
        $b23tstamp = base_convert(intval($sec_tstamp), 10, 23);
        $b23picid = base_convert(intval($id), 10, 23);
        $ieid = $b23tstamp . 'o' . $b23picid;
        
        return $ieid;
    }

    /**
     * Inverse de create_ieid(). Permet de retrouver les infos utilisées pour la création de l'ieid.
     * @param string $ieid IEID de l'élément
     * @return array Tableau associatif contenant 'uplaod_tstamp_seconds' et 'picid'
     */
    public function entity_ieid_decode($ieid){
        $exp = explode('o', $ieid);
        $b23tstamp = $exp[0];
        $b23picid = $exp[1];

        $rArray = [
            'id'    => base_convert($b23picid, 23, 10),
            'time'  => base_convert($b23tstamp, 23, 10)
        ];

        return $rArray;
    }


}

?>
