
(function(){
 
    /*
     * ci   : CamanInstance
     * CpDs : CropperDatas
     * ctx  : ConTeXt
     * cvs  : CanVaS
     */
    var _cvs, _ctx, _img, _file, _CpDs = {}, _ci, tqse = $("<span/>");
    
    var DEBUG = {
        
    };   
    
    var EVENT = {
        names : {
            "tqr.downloadingStart"  : "tqr.downloadingStart", 
            "tqr.downloadingEnd"    : "tqr.downloadingEnd", 
            "tqs.loadingStart"      : "tqs.loadingStart", 
            "tqs.loadingEnd"        : "tqs.loadingEnd",
            "tqr.renderingStart"    : "tqr.renderingStart", 
            "tqr.renderingEnd"      : "tqr.renderingEnd", 
            "tqs.applyingStart"     : "tqs.applyingStart", 
            "tqs.applyingEnd"       : "tqs.applyingEnd", 
            "tqs.abortingStart"     : "tqs.abortingStart", 
            "tqs.abortingEnd"       : "tqs.abortingEnd", 
//            "tqs.savingStart"       : "tqs.savingStart", 
//            "tqs.savingEnd"         : "tqs.savingEnd", 
            "tqs.resetingStart"     : "tqs.resetingStart", 
            "tqs.resetingEnd"       : "tqs.resetingEnd"
        },
        createNew : function (nm,opts) {
             try {
                if ( KgbLib_CheckNullity(nm) ) {
                    return;
                }
                switch (nm) {
                    case "tqr.downloadingStart" : 
                    case "tqr.downloadingEnd" : 
                    case "tqs.loadingStart" : 
                    case "tqs.loadingEnd" : 
                    case "tqs.MenuSwitchingStart" : 
                    case "tqs.MenuSwitchingEnd" : 
                    case "tqs.applyingStart" : 
                    case "tqs.applyingEnd" : 
                    case "tqs.renderingStart" : 
                    case "tqs.renderingEnd" : 
                    case "tqs.abortingStart" : 
                    case "tqs.abortingEnd" : 
//                    case "tqs.savingStart" : 
//                    case "tqs.savingEnd" : 
                    case "tqs.resetingStart" : 
                    case "tqs.resetingEnd" :
                            return new CustomEvent(nm, opts);
                        break;
                    default : 
                        return;
                }
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        },
        declareEvent : function (e,d,o) {
            //e : EVENT. o : Options
            try {
                if ( KgbLib_CheckNullity(e) ) {
                    return;
                }
                
                var ev = (! e instanceof CustomEvent) ? this.createNew(e,o) : e;
                if (! ev ) return false;
                
                var ds = (! d ) ? [] : [d];
                
                $(tqse).trigger(ev,ds);
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        }
    };   
/******************************************************************************************************************************************************************************************************/
/***********************************************************************************                                ***********************************************************************************/
/***********************************************************************************    CUSTOM : COLOR FILTER       ***********************************************************************************/
/***********************************************************************************                                ***********************************************************************************/
/******************************************************************************************************************************************************************************************************/
    var CCF = {
        //CCF : CustomColorFunction
        onChangeGetValue : function (x,a,gx) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(gx) ) {
                    return;
                }

               /*
                * ETAPE  :
                *  On va déterminer la nouvelle valeur lié au filtre à appliquer
                */
                var vl, min, max;
                vl = ( $(x).is(".jb-tqs-cstmclr-rng") ) ? parseFloat($(x).val() ) : parseFloat($(gx).find(".jb-tqs-cstmclr-rslt").text());
                min = $(gx).find(".jb-tqs-cstmclr-rng").prop("min");
                max = $(gx).find(".jb-tqs-cstmclr-rng").prop("max");

                /*
                 * ETAPE
                 *  Selon l'origine et l'action, on cha,ge la valeur
                 */
                if ( a !== "range" ) vl = ( a === "minus" ) ? --vl : ++vl;

               /*
                * ETAPE :  
                *  On s'assure que les minimas sont respectés
                */
               if ( vl < min ) vl = min;
               else if ( vl > max ) vl = max;

               return vl;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        },
        onChangeSetValue : function (x,e,v) {
            //e : Effect, v : Value
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(e) | KgbLib_CheckNullity(v) ) {
                    return;
                }

                var mb = $(".jb-tqs-grpact[data-gpaction='"+e+"']"), vl;
                vl = parseFloat(v);
                $(mb).find(".jb-tqs-cstmclr-rng").val(vl);
                $(mb).find(".jb-tqs-cstmclr-rslt").text(vl);

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        },
        effectGetValue : function (e) {
            //e : Effect
            try {
                if ( KgbLib_CheckNullity(e) ) {
                    return;
                }
                var vl = $(".jb-tqs-grpact[data-gpaction='"+e+"']").find(".jb-tqs-cstmclr-rslt").text();
                vl = ( e === "gamma" ) ? parseInt(vl) : parseFloat(vl);
                return vl;

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        }
    };
    
            
       
/******************************************************************************************************************************************************************************************************/
/***********************************************************************************                                ***********************************************************************************/
/***********************************************************************************    CUSTOM : PRESET FILTER      ***********************************************************************************/
/***********************************************************************************                                ***********************************************************************************/
/******************************************************************************************************************************************************************************************************/
    
    var CPF = {
        tqsToCamanName : function (nm) {
            try {
                if ( KgbLib_CheckNullity(nm) ) {
                    return;
                }
                
                nm = nm.toLowerCase();
                if ( $.inArray(nm,Object.keys(_f_Gdf().filter_preset.caman)) !== -1 ) {
                    return _f_Gdf().filter_preset.caman[nm];
                } else if ( $.inArray(nm,Object.keys(_f_Gdf().filter_preset.customs)) !== -1 ) {
                    return _f_Gdf().filter_preset.customs[nm];
                } else return;

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        }
    };
       
/******************************************************************************************************************************************************************************************************/
/***********************************************************************************                                ***********************************************************************************/
/***********************************************************************************        CUSTOM : CROPPING       ***********************************************************************************/
/***********************************************************************************                                ***********************************************************************************/
/******************************************************************************************************************************************************************************************************/
       
/******************************************************************************************************************************************************************************************************/
/***********************************************************************************                                ***********************************************************************************/
/***********************************************************************************          TRENQR STUDIO         ***********************************************************************************/
/***********************************************************************************                                ***********************************************************************************/
/******************************************************************************************************************************************************************************************************/
    

    /**********************************************************************************************************************************************************************/
    /**************************************************************************** PROCESS SCOPE ***************************************************************************/
    /**********************************************************************************************************************************************************************/
    
    var _f_Gdf = function() {
        
        var dt = { 
            /*
             * "tqr.downloadingStart"   : Debut de l'opération du téléchargement de l'image,
             * "tqr.downloadingEnd"     : Fin (présumée?) de l'opération du téléchargement de l'image, 
             * "tqs.loadingStart"       : Debut du téléchargement de l'image,
             * "tqs.loadingEnd"         : Fin du téléchargement de l'image. L'image est chargée et prête à l'emploi,
             * "tqs.applyingStart"      : Debut de l'application des modifications à l'image,
             * "tqs.applyingEnd"        : Fin de l'application des modifications à l'image,
             * "tqs.abortingStart"      : Début de l'opération de l'annulation des modifications non enregistrées sur l'image,
             * "tqs.abortingEnd"        : Fin de l'opération de l'annulation des modifications non enregistrées sur l'image,
             * "tqs.resetingStart"      : Début de l'opération qui permet de revenir défintivement à l'image,
             * "tqs.resetingEnd"        : Fin de l'opération qui permet de revenir défintivement à l'image,
             * "tqs.savingStart"        : (A venir), 
             * "tqs.savingEnd"          : (A venir), 
             */
            EVENT   : ["tqs.loadingStart","tqs.loadingEnd","tqs.applyingStart","tqs.applyingEnd","tqs.abortingStart","tqs.abortingEnd","tqr.downloadingStart","tqr.downloadingEnd","tqs.savingStart","tqs.savingEnd","tqs.resetingStart","tqs.resetingEnd"],
            menus   : ["custom_filter","custom_color","custom_cropping","custom_add_text"],
            grpactn : {
                all                 : ["dragmove","rotate","zoom","reverse","brightness","contrast","saturation","vibrance","hue","gamma","clip","stackBlur","exposure","sepia","noise","sharpen"],
                cropping            : ["dragmove","rotate","zoom","reverse"],
                filter_color        : ["brightness","contrast","saturation","vibrance","hue","gamma","clip","stackBlur","exposure","sepia","noise","sharpen"]
            },
            filter_preset   : {
               /*
                * * * * * * * * * * * * *
                *  CAMAN PRESET          *
                * * * * * * * * * * * * *
                *  
                *      Clarity         -> Baïkal
                *      Concentrate     -> Kanga Moussa
                *      Cross Process   -> Londres
                *      Emboss          -> Koala
                *      Glowing Sun     -> Le Caire
                *      greyscale       -> Vespa
                *      Grungy          -> Gotham
                *      Hazy Days       -> Moscou
                *      HDR Effect      -> HDR Effect
                *      Hemingway       -> Hemingway
                *      Her Majesty     -> Hillier
                *      Jarques         -> Arashiyama
                *      Lomo            -> Lalibela
                *      Love            -> Mars
                *      Nostalgia       -> Brazzaville
                *      Old Boot        -> Paris
                *      Orange Peel     -> Gorges de Diosso
                *      Pleasant        -> Notre Dame
                *      Pinhole         -> Minas Tirith
                *      RadialBlur      -> Radial
                *      SinCity         -> Tarantino
                *      Sunrise         -> Ouarzazate
                *      Vintage         -> Edisson
                *      
                * * * * * * * * * * * *
                *  TQR CUSTOM          *
                * * * * * * * * * * * *
                */
                caman : {
                    "arashiyama"    : "jarques", 
                    "baikal"        : "clarity", 
                    "brazzaville"   : "nostalgia", 
                    "edisson"       : "vintage", 
                    "londres"       : "crossProcess", 
                    "koala"         : "emboss", 
                    "lalibela"      : "lomo", 
                    "le_caire"      : "glowingSun", 
                    "gorges_diosso" : "orangePeel", 
                    "gotham"        : "grungy", 
                    "hemingway"     : "hemingway", 
                    "hillier"       : "herMajesty", 
                    "moscou"        : "hazyDays", 
                    "mars"          : "love", 
                    "minas_tirith"  : "pinhole", 
                    "ouarzazate"    : "sunrise", 
                    "paris"         : "oldBoot", 
                    "radial"        : "radialBlur", 
                    "tarantino"     : "sinCity", 
                    "kanga_moussa"  : "concentrate", 
                    "yingyang"      : "greyscale" 
                },
                customs : {
                    "hdr_effect"    : "hdr_effect",
                    "old_paper"     : "old_paper",
                    "notre_dame"    : "notre_dame"
                }
            }
        };
        
        return dt;
    };
    
    
    var _f_Action = function(x,a,e) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && KgbLib_CheckNullity(a) ) | ( KgbLib_CheckNullity($(x).closest(".jb-tqs-grpact").data("gpaction")) && $(x).data("gpaction") ) | !_cvs ) {
                return;
            }
            
            var ac = ( a ) ? a : $(x).data("action");
            
            /*
             * ETAPE :
             *  On récupère le "groupaction"
             */
            var gax = $(x).closest(".jb-tqs-grpact"), ga = $(gax).data("gpaction");
            /*
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            //*/
            /*
             * ETAPE : 
             *  On vérifie si on est dans le cas du menu "Custom Color"
             */
            if ( $.inArray(ga,_f_Gdf().grpactn.filter_color) !== -1 ) {
                //CCF_PV : CustomColorFilter_PropertyValue
                var CCF_PV = CCF.onChangeGetValue(x,ac,gax);
//                console.log("Value : "+CCF_PV);
            }
            if (! $(x).is(".jb-tqs-cstmadtx-data-chg") ) {
                _f_Dply_WP(true);
            }
            
            
            //sac : SwitchACtion
            var sac;
            if ( $(x).is(".jb-tqs-cstmclr-rng") || $(x).is(".jb-tqs-cstmclr-actn-btn") ) {
                EVENT.declareEvent("tqs.applyingStart",null,{mod : "custom_color"});
                sac = ga;
            } 
            else if ( $(x).is(".jb-tqs-cstmfltr-actn-btn") ) {
                EVENT.declareEvent("tqs.applyingStart",null,{mod : "custom_filter"});
                sac = CPF.tqsToCamanName($(x).data("option"));
            } 
            else if ( $(x).is(".jb-tqs-cstmadtx-data-chg") ) {
                //On CONTINUE
                sac = $(x).data("action");
            }
            else {
                EVENT.declareEvent("tqs.applyingStart",null,{mod : "custom_cropping"});
                sac = ac;
            }
                
            switch (sac) {
                /* TOP ACTION */
                case "back_raw" :
                        _f_RstCvs(x,ga);
                    break;
                case "erase" :
                        _f_Erz(x);
                    break;
                case "download" :
                        _f_Dwnld(x);
                    break;
                /* MENUS */
                case "menu_selector" :
                        _f_MnSlctr();
                    break;
                /* CONTROLS : CROPPER */
                case "dragmode" :
                        var vl = $(x).data("option");
                        $(".jb-tqs-canvas").one('crop.cropper', function () {
                            EVENT.declareEvent("tqs.renderingEnd",null,{mod : "custom_cropping"});
                        }).cropper("setDragMode",vl);
//                        _f_CRP_OnChg_StVl(sac,vl);
                case "ratio" :
                        var vl = parseFloat($(x).data("option"));
                        $(".jb-tqs-canvas").one('crop.cropper', function () {
                            EVENT.declareEvent("tqs.renderingEnd",null,{mod : "custom_cropping"});
                        }).cropper("setAspectRatio",vl);
                    break;
                case "rotate" :
                case "zoom" :
                        var vl = $(x).data("option");
                        $(".jb-tqs-canvas").one('crop.cropper', function () {
                            EVENT.declareEvent("tqs.renderingEnd",null,{mod : "custom_cropping"});
                        }).cropper(sac,vl);
                    break;
                case "reverse" :
                        var sx, sy, op = $(x).data("option"); 
                        if ( op === "x" ) {
                            sx = ( _CpDs["scaleX"] === -1 ) ? 1 : -1;
                            sy = _CpDs["scaleY"];
                        } else {
                            sx = _CpDs["scaleX"];
                            sy = ( _CpDs["scaleY"] === -1 ) ? 1 : -1;
                        }
                        
                        $(".jb-tqs-canvas").one('crop.cropper', function () {
                            EVENT.declareEvent("tqs.renderingEnd",null,{mod : "custom_cropping"});
                        }).cropper("scale",sx,sy);
                    break;
                /* CONTROLS : CUSTOM FILTER */
                case "jarques" : 
                case "clarity" : 
                case "nostalgia" : 
                case "vintage" : 
                case "crossProcess" : 
                case "emboss" : 
                case "lomo" : 
                case "glowingSun" : 
                case "orangePeel" : 
                case "grungy" : 
                case "hemingway" : 
                case "herMajesty" : 
                case "hazyDays" : 
                case "love" : 
                case "pinhole" : 
                case "sunrise" : 
                case "oldBoot" : 
                case "radialBlur" : 
                case "sinCity" : 
                case "concentrate" : 
                case "greyscale" :
                case "old_paper" :
                        _ci = Caman(".jb-tqs-canvas", _img, function () {
                            this.revert(false);
                            this[sac]();
                            this.render();
                        });
//                        CCF.onChangeSetValue(x,sac,CCF_PV);
                    break;
                case "notre_dame" :
                        _ci = Caman(".jb-tqs-canvas", _img, function () {
                            this.revert(false);
                            this.colorize(60, 105, 218, 10);
                            this.contrast(10);
                            this.sunrise();
                            this.hazyDays();
                            this.render();
                        });
                    break;
                case "hdr_effect" :
                        _ci = Caman(".jb-tqs-canvas", _img, function () {
                            this.revert(false);
                            this.contrast(10);
                            this.contrast(10);
                            this.jarques();
                            this.render();
                        });
                    break;
                /* CONTROLS : CUSTOM COLOR */
                case "brightness" : 
                case "clip" : 
                case "contrast" : 
                case "exposure" : 
                case "gamma" : 
                case "hue" : 
                case "noise" : 
                case "saturation" : 
                case "sepia" : 
                case "sharpen" : 
                case "stackBlur" : 
                case "vibrance" : 
                        var br = ( sac === "brightness" )   ? CCF_PV : CCF.effectGetValue("brightness");
                        var cl = ( sac === "clip" )         ? CCF_PV : CCF.effectGetValue("clip");
                        var ct = ( sac === "contrast" )     ? CCF_PV : CCF.effectGetValue("contrast");
                        var xp = ( sac === "exposure" )     ? CCF_PV : CCF.effectGetValue("exposure");
                        var gm = ( sac === "gamma" )        ? CCF_PV : CCF.effectGetValue("gamma");
                        var he = ( sac === "hue" )          ? CCF_PV : CCF.effectGetValue("hue");
                        var nz = ( sac === "noise" )        ? CCF_PV : CCF.effectGetValue("noise");
                        var st = ( sac === "saturation" )   ? CCF_PV : CCF.effectGetValue("saturation");
                        var sp = ( sac === "sepia" )        ? CCF_PV : CCF.effectGetValue("sepia");
                        var sn = ( sac === "sharpen" )      ? CCF_PV : CCF.effectGetValue("sharpen");
                        var sb = ( sac === "stackBlur" )    ? CCF_PV : CCF.effectGetValue("stackBlur");
                        var vb = ( sac === "vibrance" )     ? CCF_PV : CCF.effectGetValue("vibrance");
                        gm = ( gm === 0 ) ? 1 : gm; 
//                        console.log(br,cl,ct,xp,gm,he,nz,st,sp,sn,sb,vb);
                        _ci = Caman(".jb-tqs-canvas", _img, function () {
                            this.revert(false);
                            if ( sac === "sharpen" ) {
                                /*
                                 * [NOTE 15-10-15] @BOR
                                 *  Le filtre SHARPEN demande trop d'energie en termes de performance.
                                 */
                                this.brightness(br).clip(cl).contrast(ct).exposure(xp).gamma(gm).hue(he).noise(nz).saturation(st).sepia(sp).sharpen(sn).stackBlur(sb).vibrance(vb);
                            } else if ( sac === "hue" )  {
                                /*
                                 * [NOTE 15-10-15] @BOR
                                 *  Le filtre SHARPEN demande trop d'energie en termes de performance.
                                 */
                                
                                this.brightness(br).clip(cl).contrast(ct).exposure(xp).gamma(gm).hue(he).noise(nz).saturation(st).sepia(sp).stackBlur(sb).vibrance(vb);
                            } else {
                                this.brightness(br).clip(cl).contrast(ct).exposure(xp).gamma(gm).noise(nz).saturation(st).sepia(sp).stackBlur(sb).vibrance(vb);
                            }
                            this.render();
                        });
                        CCF.onChangeSetValue(x,sac,CCF_PV);
                    break;
                /* CONTROLS : ADD_TEXT */
                case "chg_font_size" :
                        /*
                         * ETAPE :
                         *      On rend les éléments visibles
                         */
                        $(".jb-tqs-adtxt-prvw-mx").css({
                            height  : _cvs.height,
                            width   : _cvs.width
                        }).removeClass("this_hide");
                        $(".jb-tqs-adtxt-prvw-bx").css({
                            "max-width"   : _cvs.width
                        }).removeClass("this_hide");
                        
                        /*
                         * ETAPE :
                         *      On change la FONT-SIZE
                         */
                        var txid = $(x).data("fld");
                        $(".jb-tqs-adtxt-prvw-bx[data-fld='"+txid+"']").css({
                            "font-size" : $(x).val().toString().concat("px")
                        });
                    break;
                case "chg_pic_text" :
                        /*
                         * ETAPE :
                         *      On rend les éléments visibles
                         */
                        $(".jb-tqs-adtxt-prvw-mx").css({
                            height  : _cvs.height,
                            width   : _cvs.width
                        }).removeClass("this_hide");
                        $(".jb-tqs-adtxt-prvw-bx").css({
                            "max-width"   : _cvs.width
                        }).removeClass("this_hide");
                        
                        /*
                         * ETAPE :
                         *      On change le TEXT
                         */
                        var txid = $(x).data("fld");
                        var adtx_txt = $(x).val();
                        
                        $(".jb-tqs-adtxt-prvw-bx[data-fld='"+txid+"']").removeClass("this_hide");
                        $(".jb-tqs-adtxt-prvw-bx[data-fld='"+txid+"']").text(adtx_txt);
                    break;
                /* FINAL ACTION */
                case "apply" :
                        _f_Apply();
                    break;
                case "abort" :
                        _f_Prvs();
                    break;
                default :
                    return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Apply = function () {
        try {
            
            /*
             * On récupère les données sur le menu actif afin de personnaliser le processus.
             */
            var mn = _f_GtNwMn(true).val();
            switch (mn) {
                case "custom_cropping" :
                        EVENT.declareEvent("tqs.applyingStart",null,{mod : mn});
                        var daul = $(".jb-tqs-canvas").cropper('getCroppedCanvas').toDataURL();
                        _f_UpdCvs(daul);
                    break;
                case "custom_color" :
                case "custom_filter" :
                        EVENT.declareEvent("tqs.applyingStart",null,{mod : mn});
                        var daul = _cvs.toDataURL();
                        _f_UpdCvs(daul);
                    break;
                case "custom_add_text" :
                        EVENT.declareEvent("tqs.applyingStart",null,{mod : mn});
                        if ( $(".jb-tqs-adtxt-prvw-bx[data-fld='tx1']").length && !KgbLib_CheckNullity($(".jb-tqs-adtxt-prvw-bx[data-fld='tx1']").text()) ) {
                            /*
                             * [NOTE 16-07-16]
                             *      txorf : TeXtObjectReF
                             */
                            var txorf = $(".jb-tqs-adtxt-prvw-bx[data-fld='tx1']");
                            var adtx_tx1 = $(txorf).text();
                            /*
                             * [NOTE 16-07-16]
                             *      5 : Pour la correction manuelle de PADDING LEFT
                             */
                            var adtx_x_tx1 = $(txorf).position().left + 5;
                            /*
                             * [NOTE 16-07-16]
                             *      5 : Pour la correction manuelle de PADDING TOP
                             */
                            var adtx_y_tx1 = $(txorf).position().top + 5;
                            /*
                             * [NOTE 16-07-16]
                             *      10 : Pour les PADDING (LEFT, RIGHT)
                             *      10 : A cause de "lineWidth=5"
                             */
                            var adtx_w_tx1 = parseInt($(txorf).css("width").slice(0,-2))+10+10;
//                            var adtx_ftsz_tx1 = parseInt($(".jb-tqs-cstmadtx-data-chg[data-fld='tx1']").val())*.75;
                            var adtx_ftsz_tx1 = parseInt($(".jb-tqs-cstmadtx-data-chg[data-fld='tx1']").val());
                            adtx_ftsz_tx1 = parseInt(adtx_ftsz_tx1);
                            
                            /*
                             * [NOTE]
                             *      On corrige COOR_Y grace à une formule obtenue de manière empirique
                             */
                            adtx_y_tx1 += ((12*adtx_ftsz_tx1)/60);
                            
                            
                            _ctx.textBaseline = "hanging";
//                            _ctx.font = "bold ".concat(adtx_ftsz_tx1,"pt Arial");
                            _ctx.font = "bold ".concat(adtx_ftsz_tx1,"px Arial");
                            _ctx.shadowColor = "#000";
                            _ctx.shadowBlur = 0;
//                            _ctx.shadowOffsetX = 1;
//                            _ctx.shadowOffsetY = 0;
                            _ctx.lineWidth = 5;
                            _ctx.strokeText(adtx_tx1, adtx_x_tx1, adtx_y_tx1, adtx_w_tx1);
                            _ctx.shadowBlur = 0;
                            _ctx.fillStyle = "#FFF";
                            _ctx.fillText(adtx_tx1, adtx_x_tx1, adtx_y_tx1, adtx_w_tx1);
                        }
                        if ( $(".jb-tqs-adtxt-prvw-bx[data-fld='tx2']").length && !KgbLib_CheckNullity($(".jb-tqs-adtxt-prvw-bx[data-fld='tx2']").text()) ) {
                            /*
                             * [NOTE 16-07-16]
                             *      txorf : TeXtObjectReF
                             */
                            var txorf = $(".jb-tqs-adtxt-prvw-bx[data-fld='tx2']");
                            var adtx_tx2 = $(txorf).text();
                            /*
                             * [NOTE 16-07-16]
                             *      5 : Pour la correction manuelle de PADDING LEFT
                             */
                            var adtx_x_tx2 = $(txorf).position().left + 5;
                            /*
                             * [NOTE 16-07-16]
                             *      5 : Pour la correction manuelle de PADDING TOP
                             */
                            var adtx_y_tx2 = $(txorf).position().top + 5;
                            /*
                             * [NOTE 16-07-16]
                             *      10 : Pour les PADDING (LEFT, RIGHT)
                             *      10 : A cause de "lineWidth=5"
                             */
                            var adtx_w_tx2 = parseInt($(txorf).css("width").slice(0,-2))+10+10;
                            var adtx_w_tx2 = $(txorf).css("width").slice(0,-2);
//                            var adtx_ftsz_tx2 = parseInt($(".jb-tqs-cstmadtx-data-chg[data-fld='tx2']").val())*.75;
                            var adtx_ftsz_tx2 = parseInt($(".jb-tqs-cstmadtx-data-chg[data-fld='tx2']").val());
                            adtx_ftsz_tx2 = parseInt(adtx_ftsz_tx2);
                            
                            /*
                             * [NOTE]
                             *      On corrige COOR_Y grace à une formule obtenue de manière empirique
                             */
                            adtx_y_tx2 += ((12*adtx_ftsz_tx2)/60);
                            
                            
                            _ctx.textBaseline = "hanging";
//                            _ctx.font = "bold ".concat(adtx_ftsz_tx2,"pt Arial");
                            _ctx.font = "bold ".concat(adtx_ftsz_tx2,"px Arial");
                            _ctx.shadowColor = "#000";
                            _ctx.shadowBlur = 0;
                            _ctx.shadowOffsetX = 1;
                            _ctx.shadowOffsetY = 0;
                            _ctx.lineWidth = 5;
                            _ctx.strokeText(adtx_tx2, adtx_x_tx2, adtx_y_tx2, adtx_w_tx2);
                            _ctx.shadowBlur = 0;
                            _ctx.fillStyle = "#FFF";
                            _ctx.fillText(adtx_tx2, adtx_x_tx2, adtx_y_tx2, adtx_w_tx2);
                        }
                            
                        var daul = _cvs.toDataURL();
                        _f_UpdCvs(daul);
                        
                        _f_Adtx_Rst_nHid("all",true);
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Prvs = function () {
        try {
            
            var mn = _f_GtNwMn(true);
            if (! mn ) return;
            mn = mn.val();
            switch (mn) {
                case "custom_filter" :
                        _ci = Caman(".jb-tqs-canvas", _img, function () {
                            this.revert();
                            EVENT.declareEvent("tqs.applyingEnd",null,{mod : mn});
                        });
                    break;
                case "custom_color" :
                        _ci = Caman(".jb-tqs-canvas", _img, function () {
                            this.revert();
                            EVENT.declareEvent("tqs.applyingEnd",null,{mod : mn});
                        });
                        /*
                         * ETAPE :
                         *  Récupérer les valeurs liées au statut précedent
                         */
                        $(".jb-tqs-cstmclr-rng").val(0);
                        $(".jb-tqs-cstmclr-rslt").text(0);
                    break;
                case "custom_cropping" :
                        $(".jb-tqs-canvas").one("crop.cropper",function(e){
                            EVENT.declareEvent("tqs.applyingEnd",null,{mod : mn});
                        }).cropper("reset");
                    break;
                case "custom_add_text" :
                    break;
                default :
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_LoadImg = function (file) {
        try {
            if ( KgbLib_CheckNullity(file) ) {
                return;
            }
            
            $(".jb-tqs-invitation-mx").addClass("this_hide");
            $(".jb-tqs-canvas").removeClass("this_hide");
            
            var URL = window.URL || window.webkitURL;
            if ( URL ) {
                //*
                if (! /^image\/\w+$/.test(file.type) ) {
                    alert("Ce fichier n'est pas une image !");
                    
                    $(".jb-tqs-invitation-mx").removeClass("this_hide");
                    $(".jb-tqs-canvas").addClass("this_hide");
                    
                    return;
                }
                //*/
                
                /*
                 * ETAPE :
                 *  On affiche la zone des options
                 */
                $(".jb-tqs-scrn-bdy-blcs[data-section='right']").removeClass("this_hide");
                
                /*
                 * ETAPE :
                 *  On affiche le bouton de suppression
                 */
                $(".jb-tqs-top-opt-actn[data-action='erase']").removeClass("this_hide");
                
                _file = file;
                _f_UpdCvs(URL.createObjectURL(file),true);
                
            }
        
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_UpdCvs = function (daul,isld) {
        //daul : DataUrL; isld : ISLoaD
        try {
            if ( KgbLib_CheckNullity(daul) ) {
                return;
            }
            
            var canvas = _cvs = $(".jb-tqs-canvas")[0];
            var ctx = _ctx = canvas.getContext('2d');
            img = new Image();
            img.onload = function() {
                $image = $(img);

                var dims = _f_CvsFit(img.width,img.height);
                /*
                 * On redimensionne le canvas en fonction de l'environnement en présence
                 */
                canvas.width = dims.width;
                canvas.height = dims.height;

                ctx.drawImage(img,0,0,dims.width,dims.height);
                _img = img;
                
                /*
                 * [DEPUIS 16-07-16]
                 *      On met à jour la zone qui contient les PREVIEW de texte
                 */
                if (! $(".jb-tqs-adtxt-prvw-mx").hasClass("this_hide") ) {
                    $(".jb-tqs-adtxt-prvw-mx").css({
                        height  : _cvs.height,
                        width   : _cvs.width
                    });
                    $(".jb-tqs-adtxt-prvw-bx").css({
                        "max-width"   : _cvs.width
                    });
                    
                    _f_Adtx_RplcElms();
                }

                var mn = _f_GtNwMn(true).val();
                if ( mn === "custom_cropping" ) {
                    $(".jb-tqs-canvas").one('built.cropper', function () {
//                            alert("CROPPER : Déclencher l'évènement de fin d'opération");
                        if ( isld ) {
                            EVENT.declareEvent("tqs.applyingEnd",null,{mod : mn});
                        } else {
                            EVENT.declareEvent("tqs.applyingEnd",null,{mod : mn});
                        }
                    }).cropper({
                        aspectRatio     : 1/1,
                        responsive      : false,
                        highlight       : false
                    }).cropper('reset').cropper('replace', img.src);
                } else {
//                        alert("CAMAN 2 : Déclencher l'évènement de fin d'opération");
                    EVENT.declareEvent("tqs.applyingEnd",null,{mod : mn});
                }

                /*
                 * On fait de tel sorte que CAMAN se reinitialise en détruisant toute référence à l'ancienne image
                 */
                $(".jb-tqs-canvas").removeAttr("data-caman-id");
                $(".jb-tqs-top-opt-act-ipt").val("");
                    
//                    URL.revokeObjectURL(img.src);

                
                /*
                 * ETAPE :
                 *      On ajoute "made on trenqr"
                 */
                var x = _cvs.width - 77, y = _cvs.height - 0;
                _ctx.textBaseline = "bottom";
                _ctx.font = "10px Arial";
                _ctx.shadowColor = "#000";
                _ctx.shadowBlur = 0;
                _ctx.shadowOffsetX = 1;
                _ctx.shadowOffsetY = 0;
                _ctx.lineWidth = 1;
                _ctx.strokeText("made on Trenqr", x, y);
                _ctx.shadowBlur = 0;
                _ctx.fillStyle = "#FFF";
                _ctx.fillText("made on Trenqr", x, y);
                
            };
            img.src = daul;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CvsFit =  function(w,h) {
        try {
            if ( KgbLib_CheckNullity(w) | KgbLib_CheckNullity(h) ) {
                return;
            }
            
            var hs = {
                "content_header"    : 75,
                "header"            : 60,
                "nav"               : 40,
                "padding"           : 50
            };
            /*
             * On calcule la largeur idéale à partir de la hauteur, pour que les dimensions puissent être en adéquation avec l'écran.
             */
            var wh = $(window).height();
            var wd__ = wh - ( ( hs.content_header * 2 ) + hs.header + hs.nav + hs.padding ) ;
            
            /*
             * ETAPE :
             *  On s'assure que la dimension de référence n'est pas trop grande.
             *  Ca pourrait être le cas par exemple pour les écrans ayant une grande hauteur : ecran mobil, ecran pivotant, ...
             */
            wd__ = ( wd__ > $(".jb-tqs-scrn-bdy-ctr-bdy").width() ) ? $(".jb-tqs-scrn-bdy-ctr-bdy").width() : wd__;
            
            /*
             * Détermination du coef.
             * On doit prendre en compte le cas de l'image rectangle ayant une hauteur plus grande que la largeur
             */
            var coef = ( h > w ) ? wd__/h : wd__/w;
                    
            /*
             * On renvoie les nouvelles dimensions
             */   
            var dims = {
                height  : h*coef,
                width   : w*coef
            };
            return dims;
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_MnSlctr = function () {
        try {
            
            var mn = _f_GtNwMn(true).val();
            /*
             * Enregistrer les modifications sur l'image ?
             */
            
            /*
             * ETAPE :
             *  Changer la zone des options
             */
            $(".jb-tqs-scrn-ctrl-grpact-bmx").addClass("this_hide");
            $(".jb-tqs-scrn-ctrl-grpact-bmx[data-scp='"+mn+"']").removeClass("this_hide");
            
            /*
             * ETAPE :
             *  
             */
            switch (mn) {
                case "custom_cropping" :
                        $(".jb-tqs-canvas").one('built.cropper', function () {
                            EVENT.declareEvent("tqs.menuSwitchingEnd",null,{mod : mn});
                        }).cropper({
                            aspectRatio : 1/1,
                            responsive  : false,
                            highlight   : false,
                            crop: function(e) {
                                _CpDs["x"] = e.x;
                                _CpDs["y"] = e.y;
                                _CpDs["width"] = e.width;
                                _CpDs["height"] = e.height;
                                _CpDs["rotate"] = e.rotate;
                                _CpDs["scaleX"] = e.scaleX;
                                _CpDs["scaleY"] = e.scaleY;
                            }
                        }).cropper('reset').cropper('replace', _img.src);
                        
                        /*
                         * [DEPUIS 16-07-16]
                         */
                        $(".jb-tqs-adtxt-prvw-mx").addClass("this_hide");
                        $(".jb-tqs-adtxt-prvw-bx").text("");
                        _f_Adtx_Rst_nHid("all",true);
                        
                        /*
                         * [DEPUIS 16-07-16]
                         */
                        $(".jb-tqs-pre-fnl-action[data-action='abort']").removeClass("this_hide");
                    break;
                case "custom_color" :
                case "custom_filter" :
                        $(".jb-tqs-canvas").cropper("destroy");
                        Caman(".jb-tqs-canvas", _img, function() {
                            this.revert(false);
                            this.render(function(){
                                EVENT.declareEvent("tqs.menuSwitchingEnd",null,{mod : mn});
                            });
                        });
                        /*
                         * [DEPUIS 16-07-16]
                         */
                        $(".jb-tqs-adtxt-prvw-mx").addClass("this_hide");
                        $(".jb-tqs-adtxt-prvw-bx").text("");
                        _f_Adtx_Rst_nHid("all",true);
                        
                        /*
                         * [DEPUIS 16-07-16]
                         */
                        $(".jb-tqs-pre-fnl-action[data-action='abort']").removeClass("this_hide");
                    break;
                case "custom_add_text" :
                        $(".jb-tqs-canvas").cropper("destroy");
                        Caman(".jb-tqs-canvas", _img, function() {
                            this.revert(false);
                            this.render(function(){
                                EVENT.declareEvent("tqs.menuSwitchingEnd",null,{mod : mn});
                            });
                        });
                        
                        /*
                         * [DEPUIS 16-07-16]
                         */
                        $(".jb-tqs-adtxt-prvw-mx").css({
                            height  : _cvs.height,
                            width   : _cvs.width
                        }).removeClass("this_hide");
                        $(".jb-tqs-adtxt-prvw-bx").css({
                            "max-width"   : _cvs.width
                        });
                        _f_Adtx_Rst_nHid("all",true);
                        
                        /*
                         * [DEPUIS 16-07-16]
                         */
                        $(".jb-tqs-pre-fnl-action[data-action='abort']").addClass("this_hide");
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_GtNwMn = function (aso) {
        //aso : get AsObject
        try {
            var $so = $(".jb-tqs-scrn-ctrl-mn-slct option:selected");
            return ( aso ) ? $so : $so.text();
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_RstCvs = function (x,ga) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(ga) ) {
                return;
            }
            
            _f_UpdCvs(URL.createObjectURL(_file));
            
            var mn = _f_GtNwMn(true);
            if (! mn ) return;
            mn = mn.val();
            switch (mn) {
                case "custom_filter" :
                        _ci = Caman(".jb-tqs-canvas", _img, function () {
                            this.revert();
                        });
                    break;
                case "custom_color" :
                        _ci = Caman(".jb-tqs-canvas", _img, function () {
                            this.revert();
                        });
                        $(".jb-tqs-cstmclr-rng").val(0);
                        $(".jb-tqs-cstmclr-rslt").text(0);
                    break;
                case "custom_cropping" :
                        $(".jb-tqs-canvas").cropper("reset");
                    break;
                case "custom_add_text" :
                        
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Adtx_Rst_nHid = function (scp,nHd) {
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            switch (scp) {
                case "all" :    
                        /*
                         * ETPAE :
                         *      On réinitliase les champs de "FORM"
                         */
                        $(".jb-tqs-cstmadtx-data-chg[data-action='chg_font_size']").prop('selectedIndex',0);
                        /*
                         * ETAPE :
                         *      On change la FONT-SIZE
                         */
                        $(".jb-tqs-adtxt-prvw-bx").css({ "font-size" : "" });
                        /*
                         * ETAPE :
                         *      On réinitialise les champs TEXTE
                         */
                        $(".jb-tqs-cstmadtx-data-chg[data-action='chg_pic_text']").val("");
                        
                        /*
                         * ETPAE :
                         *      On réinitialise les PREVIEW
                         */
                        $(".jb-tqs-adtxt-prvw-bx").text("");
                        if ( nHd ) {
                            $(".jb-tqs-adtxt-prvw-bx").addClass("this_hide");
                        }
                    break;
                case "form" :
                        /*
                         * ETPAE :
                         *      On réinitliase les champs de "FORM"
                         */
                        $(".jb-tqs-cstmadtx-data-chg[data-action='chg_font_size']").prop('selectedIndex',0);
                        /*
                         * ETAPE :
                         *      On change la FONT-SIZE
                         */
                        $(".jb-tqs-adtxt-prvw-bx").css({ "font-size" : "" });
                        
                        /*
                         * ETAPE :
                         *      On réinitialise les champs TEXTE
                         */
                        $(".jb-tqs-cstmadtx-data-chg[data-action='chg_pic_text']").val("");
                    break;
                case "prvw" :
                        /*
                         * ETPAE :
                         *      On réinitialise les PREVIEW
                         */
                        $(".jb-tqs-adtxt-prvw-bx").text("");
                        if ( nHd ) {
                            $(".jb-tqs-adtxt-prvw-bx").addClass("this_hide");
                        }
                    break;
                default: 
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Adtx_RplcElms = function () {
        try {
            
            var elms = $(".jb-tqs-adtxt-prvw-bx");
            $.each(elms,function(i,el){
                /*
                 * ETAPE :
                 *      Correction sur l'axe VERTICAL
                 */
                if ( $(el).position().top >= ( $(".jb-tqs-adtxt-prvw-mx").height() - $(el).height() ) ) {
                    $(el).stop(true,true).animate({
//                        top : ( $(".jb-tqs-adtxt-prvw-mx").height() - $(el).height() ).toString().concat("px")
                        top     : 0,
                        bottom  : 0
                    });
                }
                else if ( $(el).position().top <= 0 ) {
                    $(el).stop(true,true).animate({
                        top     : 0,
                        bottom  : 0
                    });
                }
                
                /*
                 * ETAPE :
                 *      Correction sur l'axe HORIZONTAL
                 */
                if ( $(el).position().left <= 0 ) {
                    $(el).stop(true,true).animate({
                        left    : 0,
                        right   : 0
                    });
                }
                else if ( $(el).position().left >= ( $(".jb-tqs-adtxt-prvw-mx").width() - $(el).width() ) ) {
                    $(el).stop(true,true).animate({
//                        left : ( $(".jb-tqs-adtxt-prvw-mx").width() - $(el).width() ).toString().concat("px")
                        left    : 0,
                        right   : 0
                    });
                }
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Erz = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * ETAPE :
             *      On clear l'image au niveau du canvas
             */
            _ctx.clearRect(0, 0, _cvs.width, _cvs.height);
            
            /*
             * ETAPE :
             *      On procède aux opération de réinitialisation en fonction du Menu actif
             */
            var mn = _f_GtNwMn(true);
            if (! mn ) return;
            mn = mn.val();
            switch (mn) {
                case "custom_filter" :
                    break;
                case "custom_color" :
                        $(".jb-tqs-cstmclr-rng").val(0);
                        $(".jb-tqs-cstmclr-rslt").text(0);
                    break;
                case "custom_cropping" :
                        $(".jb-tqs-canvas").cropper("destroy");
                        $(".cropper-hide").remove();
                    break;
                case "custom_add_text" :
                        $(".jb-tqs-adtxt-prvw-mx").addClass("this_hide");
                        _f_Adtx_Rst_nHid("all",true);
                    break;
                default :
                    return;
            }
            
            /*
             * ETAPE :
             *      On détuit l'ancien canvas et en le recrée
             */
            $(".jb-tqs-canvas").remove();
            $("<canvas/>",{
                id      : "tqs-canvas",
                class   : "jb-tqs-canvas this_hide"
            }).insertAfter(".jb-tqs-invitation-mx");
            
            /*
             * On retire l'image pour éviter de la garder en cache.
             */
            _cvs = null;
            _ctx = null;
            _img = null;
            _file = null;
                            
            /*
             * ETAPE :
             *      On masque la bouton et on l'unlock
             */
            $(x).addClass("this_hide");
            $(x).data("lk",0);
            
            /*
             * ETAPE : 
             *      On affiche la zone de départ
             */
            $(".jb-tqs-invitation-mx").removeClass("this_hide");
            
            /*
             * ETAPE : 
             *      On masque la zone des filtres
             */
            $(".jb-tqs-scrn-bdy-blcs[data-section='right']").addClass("this_hide");
            
            /*
             * ETAPE :
             *      On signale la fin de l'opération
             */
            EVENT.declareEvent("tqs.applyingEnd",null,{mod : mn});
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Dwnld = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            var mn = _f_GtNwMn(true);
            if (! mn ) return;
            mn = mn.val();
            switch (mn) {
                case "custom_cropping" :
                case "custom_color" :
                case "custom_filter" :
                case "custom_add_text" :
                        var b = $(".jb-tqs-canvas")[0].toDataURL(_file.type), lk = $(x)[0];
                        var fn = _file.name.replace(/^.*\/|\.[^.]*$/g, ''), xt = _file.type.replace(/^image\/(\w+)$/, ".$1");
                        xt = ( xt === ".jpeg" ) ? ".jpg" : xt;
                        lk.href = b;
                        lk.download = fn.concat("_by_trenqr_studio",xt);
                        setTimeout(function(){
                            lk.href = "javascript:;";
                            $(x).removeProp("download");
                            EVENT.declareEvent("tqr.downloadingEnd",null,{mod : mn});
                        },1000);
                    break;
                default :
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };

    
    /**********************************************************************************************************************************************************************/
    /***************************************************************************** VIEW SCOPE *****************************************************************************/
    /**********************************************************************************************************************************************************************/
    
    
    var _f_Dply_WP = function (shw) {
        try {
            if ( shw === true ) {
                $(".jb-tqs-wait-pnl-mx").removeClass("this_hide");
            } else {
                $(".jb-tqs-wait-pnl-mx").addClass("this_hide");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /**********************************************************************************************************************************************************************/
    /**************************************************************************** EVENTS SCOPE ****************************************************************************/
    /**********************************************************************************************************************************************************************/
    
    //    Caman.DEBUG = ('console' in window);
    /*
    Caman.Event.listen("processStart", function (job) {
        console.log("CAMAN (processStart) : ", job.name);
    });
    Caman.Event.listen("blockStarted", function (job) {
        console.log("CAMAN (blockStarted) : ", job.name);
    });
    Caman.Event.listen("blockFinished", function (job) {
        console.log("CAMAN (blockFinished) : ", job.name);
    });
    Caman.Event.listen("processComplete", function (job) {
        console.log("CAMAN (processComplete) : ", job.name);
    });
    //*/
    Caman.Event.listen("renderFinished", function () {
//        alert("CAMAN . : Déclencher l'évènement de fin d'opération");
        EVENT.declareEvent("tqs.renderingEnd",null,{mod : "custom_filter"});
    });
    
//    alert(Caman(".jb-tqs-canvas") instanceof Caman );
    //*
    $(".jb-tqs-canvas").on({
        'build.cropper': function (e) {
            Kxlib_DebugVars(["CROPPER : ", e.type]);
        },
        'cropstart.cropper': function (e) {
            Kxlib_DebugVars(["CROPPER : ", e.type, e.action]);
        },
        'cropmove.cropper': function (e) {
            Kxlib_DebugVars(["CROPPER : ", e.type, e.action]);
        },
        'cropend.cropper': function (e) {
            Kxlib_DebugVars(["CROPPER : ", e.type, e.action]);
        },
        'crop.cropper': function (e) {
            Kxlib_DebugVars(["CROPPER : ",e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY]);
        },
        'zoom.cropper': function (e) {
            Kxlib_DebugVars(["CROPPER : ",e.type, e.ratio]);
        },
        'built.cropper': function (e) {
            Kxlib_DebugVars(["CROPPER : ", e.type]);
//          alert("Redimensionnement appliqué");
        }
    });
    //*/
   
    $(tqse).on({
        "tqr.downloadingStart" : function (e) {
            _f_Dply_WP(true);
        }, 
        "tqr.downloadingEnd" : function (e) { 
            _f_Dply_WP();
        }, 
        "tqs.loadingStart" : function (e) {
            _f_Dply_WP(true);
        }, 
        "tqs.loadingEnd" : function (e) { 
            _f_Dply_WP();
        }, 
        "tqs.menuSwitchingStart" : function (e) { 
            _f_Dply_WP(true);
        }, 
        "tqs.menuSwitchingEnd" : function (e) { 
            _f_Dply_WP();
        }, 
        "tqs.renderingStart" : function (e) { 
            _f_Dply_WP(true);
        }, 
        "tqs.renderingEnd" : function (e) { 
            _f_Dply_WP();
        }, 
        "tqs.applyingStart" : function (e) { 
            _f_Dply_WP(true);
        }, 
        "tqs.applyingEnd" : function (e) { 
            _f_Dply_WP();
        }, 
        "tqs.abortingStart" : function (e) { 
            _f_Dply_WP(true);
        }, 
        "tqs.abortingEnd" : function (e) { 
            _f_Dply_WP();
        }, 
//        "tqs.savingStart" : function (e) { 
//        }, 
//        "tqs.savingEnd" : function (e) { 
//        }, 
        "tqs.resetingStart" : function (e) { 
            _f_Dply_WP(true);
        }, 
        "tqs.resetingEnd" : function (e) { 
            _f_Dply_WP();
        }
    });
    
    /**********************************************************************************************************************************************************************/
    /*************************************************************************** LISTERNERS SCOPE *************************************************************************/
    /**********************************************************************************************************************************************************************/

    
    $(".jb-tqs-top-opt-act-ipt").change(function(e){
        var file = this.files[0];
        
        //[NOTE 04-08-14] On est passé de this.file à this.value
        _f_LoadImg(file);
    });
    
    $(".jb-tqs-scrn-ctrl-mn-slct").change(function(e){
        var x = this, a = "menu_selector";
        
        _f_Action(this,"menu_selector");
    });
    
    $(".jb-tqs-cstmclr-rng").change(function(e){
        _f_Action(this);
    });
    
    $(".jb-tqs-cstmclr-actn-btn, .jb-tqs-crop-actn-btn, .jb-tqs-top-opt-actn:not([data-action=upload]), .jb-tqs-cstmfltr-actn-btn").click(function(e){
        if (! $(this).is(".jb-tqs-top-opt-actn[data-action=download]") ) {
            Kxlib_PreventDefault(e);
        }
        _f_Action(this);
    });
    
    $(".jb-tqs-pre-fnl-action").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $(".jb-tqs-cstmadtx-data-chg").change(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    $(".jb-tqs-cstmadtx-data-chg[data-fld='tx1'], .jb-tqs-cstmadtx-data-chg[data-fld='tx2']").keyup(function(e){
        
        _f_Action(this,null,e);
    });
    $(".jb-tqs-adtxt-prvw-bx").draggable({
        containment: "parent",
        start  : function(e,ui){
            $(ui.helper).addClass("ui-helper");
        }
    });
    
    $(window).resize(function(e){
        var pw = $(this).width();
        var mn = _f_GtNwMn(true).val();
        if ( _img ) {
            var dims = _f_CvsFit(_img.width,_img.height);
            Kxlib_DebugVars(["MENU : ", mn, "WIDTH : ",dims.width,"HEIGHT",dims.height]);
            /*
             * On redimensionne le canvas en fonction de l'environnement en présence
             */
            _cvs.width = dims.width;
            _cvs.height = dims.height;
            _ctx.drawImage(_img,0,0,dims.width,dims.height);
            
            /*
             * [DEPUIS 16-07-16]
             *      On met à jour la zone qui contient les PREVIEW de texte
             */
            if (! $(".jb-tqs-adtxt-prvw-mx").hasClass("this_hide") ) {
                $(".jb-tqs-adtxt-prvw-mx").css({
                    height  : _cvs.height,
                    width   : _cvs.width
                });
                $(".jb-tqs-adtxt-prvw-bx").css({
                    "max-width"   : _cvs.width
                });
                
                _f_Adtx_RplcElms();
            }
            
           /*
            * [NOTE 17-10-15]
            *  Cette solution n'est pas la plus optimale mais c'est la meilleure que j'ai trouvé après des longues heures de recherches et de tatonnement.
            *  Ce défaut au niveau du redimensionnement est un argument majeur en ce qui concerne la mise au rebus de ce module.
            */
            if ( mn && mn === "custom_cropping" ) {
                var $image = $(".jb-tqs-canvas");
                $image.cropper("destroy");
                
                clearTimeout(ti);
                var ti = setTimeout(function(){
//                    console.log("Finished with",pw,$(this).width(),parseFloat(pw)===parseFloat($(this).width()));
                    /*
                     * [NOTE]
                     *      La condition "screen.width === pw" règle un bogue spécifique à l'agrandissement maximal de la fenete.
                     *      C'est la seule solution que j'ai trouvée pour l'instant
                     */
                    if ( $(window).width() === pw || screen.width === pw ) {
//                        console.log("Finished with",cw,$(this).width(),parseFloat(cw)===parseFloat($(this).width()));
                        $image.cropper({
                            aspectRatio : 1/1,
                            highlight   : false
                        }).cropper("reset");
                    }
                },200);
            }
        }
    });
    
})();