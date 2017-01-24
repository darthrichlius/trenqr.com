/* 
 * Lou Carther (R.D.L) 06/07/14
 * Copyright CABINET INFORMATIQUE DIEUD - DEUSLYNN ENTREPRISE
 * 
 * CONTEXTE :
 * Repertorie toutes les règles relatives à la mise à disposition de certaines fonctionnalités ...
 * ... auprès de l'utilisateur actif. 
 * 
 * RAPPEL SECU : Bien que la partie FrontEnd ait la capacité d'autoriser la "mise à disposition" d'une fonctionnalité ...
 * ... 
 * 
 * GLOSSAIRE :
 *  -> akx = Access
 *  -> any = N'importe qui peut le faire qu'il soit connecté ou pas
 *  -> wm = Un utilisateur non connecté
 *  -> ru = RestUser
 *  -> rpo = RestPageOwner
 *  -> rfl = RestFollower
 *  -> rflg = RestFollowing
 *  -> rfrd = RestFriend
 *  -> rmco = RestMotherContentOwner (Exemple : Dans le cas d'un commentaire lié à un article : Article est Mother, Content est le commentaire ...
 *     ... Cet exemple n'est valide que si l'objet actif est le commentaire. S'il s'agissait de l'Article, RCO serait l'Article.
 *  -> rco = RestContentOwner
 *  -> ...
 */


window.FeatRules = {
    
    /********** UNQ **********/
    //QUI : Le propriétaire de l'Article 
     "UNQ_AKX_DEL_ANYREACT" : {
        "code" : "UNQ_AKX_DEL_ANYREACT",
        "enafor" : "rco" /*any pour les besoins de DEV_MODE, TEST_MODE */
    },
    //QUI : Le propriétaire du Commentaire et/ou le propriétaire de l'Article 
     "UNQ_AKX_DEL_REACT" : {
        "code" : "UNQ_AKX_DEL_REACT",
        "enafor" : "rco,rmco" /*any pour les besoins de DEV_MODE, TEST_MODE */
    },
    //QUI : Le propriétaire du Commentaire et/ou le propriétaire de l'Article 
    "UNQ_DEL_REACT" : {
        "code" : "UNQ_DEL_REACT",
        "enafor" : "rco,rmco"
    },
    //QUI : Le propriétaire de l'Article 
    "UNQ_AKX_DEL_ART" : {
        "code" : "UNQ_AKX_DEL_ART",
        "enafor" : "rco" /*any pour les besoins de DEV_MODE, TEST_MODE */
    },
    //QUI : Le propriétaire de l'Article, 
    "UNQ_DEL_ART" : {
        "code" : "UNQ_DEL_ART",
        "enafor" : "rco"
    }
   
};