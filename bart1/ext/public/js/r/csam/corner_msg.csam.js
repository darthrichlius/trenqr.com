function CornerMessenger () {
    //Le statut par défaut
    this.defStatus = "close";
    this.currentStatus;
    this.defEnbClose = true;
    
    this.ckeckser_url = "http://127.0.0.1/korgb/ajax_test.php";
    this.ckeckser_uq = "check_and_get_msg";
    
    this.BuildPlatMessage = function(argv) {
        /*
         * RAPPEL SUR LE FORMAT DE DATAS
         * identifiant : {
         *    body: "Le message dans la bonne langue",
         *    ena_close: "y|n",
         *    choices: [ch1,ch2,...]
         *      }
         */
        //alert(argv);
        var datas = argv;
        var cn = Kxlib_ObjectChild_Count (argv);
        //alert(cn);
        if ( cn === 1 ) {
            //cas 1 : Un seul message
            Kxlib_DebugVars(["Debut construction du PlatMsg SOLO"]);
            var first;
            //*
            $.each(datas, function(k,v){
                first = v;
                /*
                 Kxlib_DebugVars([key = "+k+"; value = "+v]);
                Kxlib_DebugVars([BODY = > "+v.body]);
                 
                $.each(v, function(kk,vv){
                    Kxlib_DebugVars([key = "+kk+"; value = "+vv]);
                });
                //*/
            });
            
            
            //for (k in first) alert(firstf); //Raisonnement faux
            Kxlib_DebugVars(["LE MESSAGE = > "+first.body]);
            
            //Insertion du body
            $("#c_n_text").html(first.body);
            
            //Insertion des boutons
            this.EnableChoices(first.choices);
            
            //Gestion du cas de la croix de fermeture
            var en;
            if ( KgbLib_CheckNullity(first.ena_close) ) {
                alert("Close ? "+en);
                en = this.defEnbClose;
            } else {
                en = ( first.ena_close === "y" ) ? true : false;
            }
            
            this.EnableCloseCross (en);
            
            //Affichage du Max
            $("#corner_msg_maximus").removeClass("this_hide");
            
        } else if ( cn > 1 ) {
            //Cas 2 : Plusieurs messages
            Kxlib_DebugVars(["Debut construction du PlatMsg MULTI"]);
        }
    };
    
    this.CheckServer = function() {
        //TODO ... Verifier si un message est disponible pour un utilisateur
        /**
         * Le retour est composé :
         *  - Du message lui meme
         *  - Des choix possible (Si aucun choix n'est disponible, on insère que le message et la croix. Sinon, la croix est enable)  
         */
        
        var th = this;
        var onsuccess = function (datas) {
            if(! KgbLib_CheckNullity(datas.err) ) 
                alert(datas.err);
            //alert("BEFORE JSON PARSE =>"+datas);
            datas = JSON.parse(datas);
//            alert("AFTER JSON PARSE =>"+datas);
            var str = JSON.stringify(datas);
//            alert("AFTER JSON PARSE and STRINGFY => "+JSON.stringify(datas));
            datas = JSON.parse( str );
            //th.Display(datas);
            /*
            var type = typeof datas;
            Kxlib_DebugVars([TYPE of DATAS => "+type]);
            var t = (datas.isArray) ? 'y': 'n';
            Kxlib_DebugVars([DATAS is Array = >"+t]);
            Kxlib_DebugVars([LENGTH = >"+datas.length]);
//            alert(datas);
            //*/
            //*
            if ( KgbLib_CheckNullity(datas.length) ) {
                //Si la "taille" n'est pas définie, c'est qu'il s'agit d'un objet.
                th.BuildPlatMessage(datas);
            } else {
                Kxlib_DebugVars(["Pas de nouveaux messages !"]);
            }
            //*/
        };

        var onerror = function(a,b,c) {
            alert("AJAX ERR : "+th.ckeckser_uq);
        };

        var toSend = {
            "urqid": th.ckeckser_uq
        };

        Kx_XHR_Send(toSend, "post", this.ckeckser_url, onerror, onsuccess);
    };
    
    //Gère les cas des boutons
    /*
     * Ne plus afficher
     * Ok
     * Oui
     * Non
     */
    this.HandleAnswerCase = function (btn) {
       if ( KgbLib_CheckNullity(btn) )
            return;
        
        var val = $(btn).data("val") ;
        
        switch(val) {
            case 'getit' : //I understand (J'ai compris le message)
                    alert("getit");
                break;
            case 'o' : //Ok case
                    alert("Ok");
                break;
            case 'dsma': // Don't Show Me Again case
                    alert("Don't Show Me Again");
                break;
            case 'y': //Yes case
                    alert("Yes");
                break;
            case 'n': //No case
                    alert("No");
                break;
        }

        //On finit par fermer la fenetre
        $("#corner_msg_maximus").addClass("this_hide");
    };
    
    this.EnableCloseCross = function(argv) {
        if (argv ) 
            $("#c_n_header_x").removeClass("this_hide");
        else 
            $("#c_n_header_x").addClass("this_hide");
    };
    
    this.EnableChoices = function(argv) {
        $.each(argv, function(k,v){
//            alert("K => "+k);
//            alert("V => "+v);
            
            switch (v) {
                case "getit": 
                        $("#c_n_bot_getit").removeClass("this_hide");
                    break;
                case "o": 
                        $("#c_n_bot_ok").removeClass("this_hide");
                    break;
                case "dsma":
                        $("#c_n_bot_dsma").removeClass("this_hide");
                    break;
                case "y": 
                        $("#c_n_bot_ya").removeClass("this_hide");
                    break;
                case "n": 
                        $("#c_n_bot_na").removeClass("this_hide");
                    break;
            }
        });
    };
    
    this.disableChoices = function(argv) {
        $.each(argv, function(k,v){
            
        });
    };
    
    //true=open
    this.OpenClose = function (isOpen) {
        
        if ( KgbLib_CheckNullity(isOpen) )
            return;
        
        this.currentStatus = ( $("#corner_msg_maximus").hasClass("this_hide") ) ? 'close': 'open';
        
        if ( isOpen && this.currentStatus === 'close' ) {
            $("#corner_msg_maximus").removeClass("this_hide");
        } else if ( !isOpen && this.currentStatus === 'open' ) {
            $("#corner_msg_maximus").addClass("this_hide");
        } else {
            //Nothing
        }
    };
    
    this.Init = function (isOpen) {
        //Si un evariable est définie pour Init est prend le pas sur celle déjà définie
        var st = this.defStatus;
        
        if ( !KgbLib_CheckNullity(isOpen) ) {
            st = ( isOpen ) ? "open" : "close";
            this.defStatus = st;
        }
        
        //Quel est le statut d'ouverture par défaut ?
        switch(st) {
            case "open":
                    this.OpenClose(true);
                break;
            case "close":
                    this.OpenClose(false);
                break;
            defaukt:
                    //Si rien n'est precisé ou s'il y a une erreur de frappe => close
                    this.OpenClose(false);
                break;
        }
        //Est ce qu'un message est load au niveau du serveur ? ...
        // ... Si oui, lancer mecanisme d'affichage
    };
}

var _obj = new CornerMessenger();
_obj.Init();

$("#c_n_header_x").click(function(e){
   e.preventDefault(); 
   _obj.OpenClose(false);
});

$(".c_n_bot_btn").click(function(e){
   e.preventDefault(); 
   _obj.HandleAnswerCase(e.target);
});

//setInterval( "_obj.CheckServer();", 5000 ); //Provoque des erreurs

setInterval(function(){
        _obj.CheckServer();
    }, 10000);
    