/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function CoolPro () {
    this.clbNbSl = ".cl_nbr";
    
    this.Trigger;
    this.Target;
    
    this.HandlePlusOne = function (clbNb) {
        if ( clbNb >= 0 && clbNb <= 999 ) {
            clbNbTx = clbNb;
        } else if ( clbNb/1000 === 1 || clbNb/10000 === 1 || clbNb/100000 === 1 || clbNb/1000000 === 1 || clbNb/10000000 === 1 || clbNb/100000000 === 0 || clbNb/1000000000 === 1 ) {
            //unique k, unique m, unique M
//            alert("unique symbole");
            switch(clbNb) {
                case 1000:
                        clbNbTx = "1k";
                    break;
                case 10000:
                        clbNbTx = "10k";
                    break;
                case 100000:
                        clbNbTx = "100k";
                    break;
                case 1000000:
                        clbNbTx = "1m";
                    break;
                case 10000000:
                        clbNbTx = "10m";
                    break;
                case 100000000:
                        clbNbTx = "100m";
                    break;
                case 1000000000:
                        clbNbTx = "1M";
                    break;
                default : 
                        //TODO : Send error to server and remove console.log
                        Kxlib_DebugVars(["Can't be !"]);
                    break;
            }
        } else if ( clbNb > 1000 && clbNb < 10000 ) {
//            alert(parseFloat(clbNb/1000));
            var str = parseFloat(clbNb/1000).toString().replace("."," ");
//            alert(str.length);
            if ( str.length === 1 ) {
                str += " 000";
                clbNbTx = str;
            } else if ( str.length === 3 ) {
                str += "00";
                clbNbTx = str;
            } else if ( str.length === 4 ) {
                str += "0";
                clbNbTx = str;
            } else {
                clbNbTx = str;
            }
            
        } else if ( clbNb > 10000 && clbNb < 1000000 ) {
            var rest = (clbNb%1000).toString();
            //*
            if ( (rest >= 0 && rest < 100) || !rest ) clbNbTx = parseInt(clbNb/1000).toString()+"k";
            else clbNbTx = parseInt(clbNb/1000).toString()+","+parseInt(rest/100)+"k";
            //*/
        } else if ( clbNb > 1000000 ) {
          var rest = (clbNb%1000000).toString();
            //*
            if ( (rest >= 0 && rest < 100000) || !rest ) clbNbTx = parseInt(clbNb/1000000).toString()+"m";
            else clbNbTx = parseInt(clbNb/1000000).toString()+","+parseInt(rest/100000)+"m";
            //*/
        }
        
        return clbNbTx;
    };
    
    this.CooliClbInAcc = function (o) {
        var $ol = $(o);
        var $tar = $ol.find(".cl_nbr");
        var clbNb = parseInt($tar.attr('title'));
        var clbNbTx = "";
        //On verifie la forme du nombre
        /**
         * MODELE STANDARD => ^(?:(?:[\d]{1,3}(?:k|m)?)|(?:[\d]{1}[\s][\d]{3})|(?:[\d]{1,3}[,]{1}[\d]{1}(?:k|m){1}))$
         * 
         * Lors d'un refactoring, on pourra valider le template grace au regex.
         * Attention cependant à la modifier un petit peu car javascript peut "boguer".
         * Utiliser plutot "'...',g" au lieu des '^...$'
         */
       
        //Incrémenter le nombre de cool et on insère dans title
        $tar.attr('title',++clbNb);
        
        //On calcul l'ajout d'un point puis on transforme le texte selon le modèle standard
        clbNbTx = this.HandlePlusOne(clbNb);
        
        //On affiche le résultat
        $tar.text(clbNbTx);
        
        //On signale que l'élément a été traité pour éviter toute duplication
        $ol.data("cl_status","1");
        
        //On ajoute le badge qui permet de signaler que l'utilisateur vient de procédér au traitement
        
    };
    
    this.HandleCoolProcess = function(obj, target) {
        if ( KgbLib_CheckNullity([obj,target]) )
            return;
        this.Trigger = obj;
        
        var $o, _type;  
        
        try {
            this.Target = $("#"+target);
            $o = $("#"+target);
        } catch (e) {
            //TODO: Send error to server
            Kxlib_DebugVars(["ERROR : Can't reach target !"]);
        }
        
        if ( KgbLib_CheckNullity($o.data("cl_status")) || $o.data("cl_status").toString() === "1" )
            return;
        
        _type = $o.data("el_type");

        switch (_type) {
            case "cl_in_acc" :
                    this.CooliClbInAcc($o);
                break;
        }
    };
}

(function(){
    var obj = new CoolPro();
    
    $(".go-cool").click(function(e){
        e.preventDefault();
        
        try {
            obj.HandleCoolProcess(this,$(this).data("target"));
        } catch (ex) {
            //TODO: Send error to server
            Kxlib_DebugVars([ex]);
        }
        
    });
})();