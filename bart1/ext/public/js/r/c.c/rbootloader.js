
function BootLoader() {
    //NOTE : Les modules JQuery et KXLIB ne sont pas disponibles dans ce module
    var gt = this;
    
    /****************************************************************************************************************************************************************************/
    /**************************************************************************** LOCALE DATAS SCOPE ****************************************************************************/
    /****************************************************************************************************************************************************************************/
    var asdApps = {
        /*
         * xtras : Permet d'insérer d'autres propriétés à la volée
         *       wsts: WorkingStatus,
         *       usts: UserStatus,
         *       ool: Only OnLine,
         *       sbe: SubmitByEnter,
         *       shr: ShowHour, 
         * contents: Le développeur est libre d'ajouter les éléments qu'il veut dans le tableau. 
         *           Il s'agira la plus part du temps de résultats de recherche, conversations ou messages
         */
        searchbox : {
            isactive: false, //DEFAULT : true
            name: "srhbx",
            lib: "SearchBox",
            lupd: null,
            xtras : {
                maininput: null
            },
            mods: {
                srhbox : { 
                    isactive: true,
                    name: "srhbx",
                    lib: "SearchBox",
                    lupd: 0,
                    xtras : {},
                    contents: []
                }
            }
        },
        chatbox : {
            isactive: true, //DEFAULT : false
            name: "chbx",
            lib: "ChatBox",
            xtras : {
                wsts: true,
                usts: true,
                ool: false,
                sbe: true,
                shr: false
            },
            mods: {
                convlist : { 
                    isactive: true, //DEFAULT : true
                    name: "convlist",
                    lib: null,
                    lupd: null, //Si null, alors la valeur n'a jamais été modifiée
                    xtras : {
                        maininput: ""
                    },
                    contents: [
                        /*
                        {
                            id: "", //Peut etre NULL
                            lupd: "",
                            xtras: {
                                cvtype: "", //(conv,parley),
                                uid: "",
                                upsd: "",
                                ufn: "",
                                uppic: "",
                                sample: "" //Peut être null dans le cas de "Parley"
                            }
                        }
                        //*/
                    ]
                },
                convtheater : { 
                        isactive: false, //DEFAULT : false
                        name: "convtheater",
                        lib: null,
                        lupd: null,//Si null, alors la valeur n'a jamais été modifiée
                        xtras : {
                            maininput: "",
                            cvid: null,
                            uid: null,
                            upsd: null,
                            ufn: null,
                            uppic: null,
                            //TOF : TimeOfFirst, la conversation existe depuis ...
                            tof: null
                        },
                        contents: [
                            /*
                            {
                                id: "",
                                lupd: "",
                                xtras: {
                                    message: "",
                                    time: ""
                                }
                            }
                            //*/
                        ]
                }
            }
        }
    };
    /****************************************************************************************************************************************************************************/
    /******************************************************************************* PROCESS SCOPE ******************************************************************************/
    /****************************************************************************************************************************************************************************/
    this.OnLoad = function() {
//        _f_OnLoad();
    };
    
    this.OnBoot = function () {
        /*
         * Gère toutes les opérations liées au chargement de la page.
         * 
         * RAPPEL : Le fichier kxlib.js est appelé après BootLoader.js
         */
        try {
            var f__ = _f_ChkSsStorage();
            if (! f__ ) {
                alert("Certaines fonctionnalités de Trenqr ne sont pas disponibles sur ce navigateur. Nous vous conseillons vivement d'utiliser un navigateur plus récent.");
            }
            
            //: ASIDEAPPPS
            _f_Onload_AsdApps();
            
        } catch (ex) {
//            alert(ex);
        }
    };
    
    var _f_Onload_AsdApps = function () {
        if ( "asdApps" in sessionStorage ) {
            //TODO : Travailler sur les applications ASIDE
//            sessionStorage.clear(); //Pour les besoins de developpement
        } else {
            sessionStorage.setItem("asdApps",JSON.stringify(asdApps));
//            alert(JSON.stringify(sessionStorage));
        }
    };
    
    var _f_ChkSsStorage = function () {
        return typeof sessionStorage !== 'undefined';
    };
    
    
    /****************************************************************************************************************************************************************************/
    /********************************************************************************* SERVER SCOPE *****************************************************************************/
    /****************************************************************************************************************************************************************************/
    
    /****************************************************************************************************************************************************************************/
    /****************************************************************************** LISTENERS SCOPE *****************************************************************************/
    /****************************************************************************************************************************************************************************/
    
    
};

try {
    var _RB = new BootLoader();
    (function(){
        _RB.OnBoot();
    })();
    window.onload = _RB.OnLoad;
} catch(ex) {
    alert(ex);
}