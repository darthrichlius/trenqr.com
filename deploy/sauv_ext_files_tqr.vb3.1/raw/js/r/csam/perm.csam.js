/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Permet de gérer les autorisations en ce qui concerne les actions.
 * Mettre à disposition de l'utilisateur actif une fonctionnalité est une action
 * 
 * La classe .
 */
function PERM () {
    
    var _f_GCUPermList = function (o) {
//    this._AquireCUPermList = function (o) {
        //o = Options (Content, MotherContent, ContentOwner, MotherContentOwner)
        /* Permet de récupérer les AccessGrade pour l'utilisateur actif.
         * Cette acquisition est faite en se basant sur les données présentes au niveau du FrontEnd.
         * Le serveur aura quoi qu'il arrive le dernier mot !
         * 
         * Rappel : Pour le glossaire sur les autorisations voir "feat_rules"
         * 
         * GLOSSAIRE :
         *  -> CU = CurrentUser
         * */
        
         //Tableau des règles
        var rl = new Array();
        
        //On vérifie s'il est de type Wm. On vérifie si CurentUserBox est défini
        if (! $("#user-id-card").length ) { 
            rl.push("wm");
            return rl; //[07-12-14]
        } else {
            rl.push("ru");
        }
        
        /* On vérifie si le propriétaire de la page est le CurrentUser */
        //owi = OwNerpageId ; cui = CurrentUserId ;
        var owi = Kxlib_GetOwnerPgPropIfExist().ueid, cui = Kxlib_DataCacheToArray($("#user-id-card").data("cache"))[0][0];
        
        //On vérifie s'il s'agit du propriétaire de la page courante
        if ( owi === cui ) { rl.push("rpo"); }
        //alert("VAL1 => "+o.rco+"; VAL2 => "+o.hasOwnProperty("rco"));
//        Kxlib_DebugVars ([o.rco,cui],true);
        //On vérifie s'il s'agit du propriétaire du content (Si l'option est définie)
        if ( !KgbLib_CheckNullity(o) && o.hasOwnProperty("rco") && o.rco.toString() === cui.toString() ) { rl.push("rco"); }
        
        //On vérifie s'il s'agit du propriétaire du MotherContent (Si l'option est définie)
        if ( !KgbLib_CheckNullity(o) && o.hasOwnProperty("rmco") && o.rmco.toString() === cui.toString() ) { rl.push("rmco"); }
        
        return rl;
        
    };
    
    /******************************* MAIN ******************************/
    
    this.PermByUrqid = function () {
        /* Permet d'autoriser une requete vers le serveur. La requete est identifiable à partir de l'URQID fourni.
         * L'autorisation est prise en prenant en compte certaines données comme :
         *  * l'utilisateur actif
         *  * le propriétaire de la page
         *  * Si le propriétaire du contenu, le cas échéant)
         *  Ensuite, on récupère l'autorisation définie dans "AJAX_RULES" pour décider;
         * * */
        
    };
    
    this.PermForFeatures = function (c,o) {
        //c = FeatCode, o = Options (ContentOwner, MotherContentOwner)
        /* Permet de d'autoriser la mise à disposition d'une fonctionnalité auprès de l'utlisateur actif.
         * L'autorisation de la mise à disposition est prise en prenant en compte certaines données comme :
         *  * l'utilisateur actif
         *  * le propriétaire de la page
         *  * Si le propriétaire du contenu, le cas échéant)
         *   Ensuite, on récupère l'autorisation définie dans "FEAT_RULES" pour décider;
         * * */ 
       
        if ( KgbLib_CheckNullity(c) ) { 
            return; 
        }
        
        //Déterminer la situation de navigation de l'utilisateur actif.
        //curl = CurrentUserRuLes; rl = Rules (Disponible pour)
        var curl, rl, th = this;
        
        try {
            curl = _f_GCUPermList(o);
//            alert(curl.join());
            rl = Kxlib_GetFeatRules(c).enafor.split(',');
//            alert(rl.join());
            
            var r = _.intersection(rl,curl);
//            Kxlib_DebugVars([curl,typeof r, r.length,r,( r.length ) ? true : false],true);

            return ( r.length ) ? true : false;
        } catch (e) {
//            Kxlib_DebugVars([e],true);
            return; //[07-12-14] @author L.C. Je confirme qu'on doit bien renvoyer "undefined" 
        }
    };
    
}