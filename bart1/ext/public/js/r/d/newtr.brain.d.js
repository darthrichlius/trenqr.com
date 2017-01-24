/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function Brain_HandleTrend () {
    var gt = this;
    
    /********************** ACTION OVER TR LIST (Following Trends, My Trends) *********************/
    var _trgrObj; 
//    this.triggerObj; 
    var _bftrid;
//    this.bftrid;
    var _tgtBloc;
//    this.targetBloc;
    var _ua;
//    this.uaction;
    
    //L'Element Trend dans la Liste
    var _bfuElSel;
//    this.bfuElSel;
    
//    this.form = "newtr_form";
    var _form_sel = "#newtr_form";
//    this.form_sel = "#newtr_form";
    var _iptSl = "#newtr_form :input";
//    this.inputSel = "#newtr_form :input";
    var _errClass = "notFilled";
//    this.errClass = "notFilled";
    
    //URQUID => Verifier la disponibilité du titre
//    this.ckeckTitle_url = "http://127.0.0.1/korgb/ajax_test.php";
//    this.ckeckTitle_uq = "check_tr_title";
    
    
    //URQID => Se re-connecter à une Tendance que je suis suivais et que j'ai précédemment arreté de suivre
    this.recoTr_url = "http://127.0.0.1/korgb/ajax_test.php";
    this.recoTr_uq = "reco_tr";
    
    //URQID => Se connecter à une Tendance que suis le compte que je suis entrain de visiter
    this.conxTr_visit_url = "http://127.0.0.1/korgb/ajax_test.php";
    this.conxTr_visit_uq = "conx_tr_from_visit";
    
   var _f_TleAldyXsts = function () {
//    this.SignalTitleAlreadyExists = function () {
        //TODO : Signaler que le titre est déjà pris
        $(".jb-ntr-itr-tle").addClass(_errClass);
    };
    
    var _f_NwTrArt_ReBind = function ($e) {
//    this.NewTrArt_BindHandler = function ($e) {
        $($e).find(".npost_tr_trig").click(function(e) {
            var trid = $(e.target).data("trid");

            if ( KgbLib_CheckNullity(trid) ) { return; }

            Kxlib_PreventDefault(e);
            
            //La methode ci-dessous semble plus rapide que $("<span>") ou $("<span></span>")
            var $el = $("<span/>");
            $el.data("slave","brain_th-new_ml");
            $el.attr("id","brain_th-npost_tr");
            $el.data("title",$(e.target).attr("title"));
            $el.data("trid",trid);
            $el.data("isown", $(e.target).parent().parent().attr("id"));
            
            ( new BrainHandler())._f_ClkOnMn($el); 
        });
        
        $($e).find(".btmc_bot_btn").click(function(e){
            Kxlib_PreventDefault(e);

            gt.DelMyTr(e.target);
        });
    
        /****** REBIND BOUTON ACTION ******/
        $($e).find(".action_a").focusout(function(e){
            if ( e.target === this ) {
                Kxlib_StopPropagation(e);
//                Kxlib_DebugVars("Lost Focus!");
//                Kxlib_DebugVars("Event Was : "+e.type);
                $(this).parent().children(".action_foll_choices").addClass("this_hide");
                //La ligne ci-dessous causait un bug : too much recursion a cause d'un 'bubbling tree'
    //            $(this).blur();
            }
        });


        $($e).find(".action_a").click(function(e){
            Kxlib_PreventDefault(e);
    //        e.stopPropagation();

            $(this).focus();

            if(! $(this).parent().children(".action_foll_choices").hasClass("this_hide") ) {
                $(this).parent().children(".action_foll_choices").addClass("this_hide");
                $(this).blur();
//                Kxlib_DebugVars("Ready to retrun !");
                return;
            }
            $(".action_foll_choices").not(this).addClass("this_hide");
            $(this).parent().children(".action_foll_choices").toggleClass("this_hide");
        });

        $($e).find(".action_a").hover(function(){
//           Kxlib_DebugVars("Hover Action_a !");     
        },function(){

        });
        
        return $e;
    };
    
//    this.CreateTrendInMyTrendList = function (argv) {
//        var datas = argv;
//        
//        //TODO : Gérer le cas des nombres (Post Followers)
//        
//        var e = "<div id=\"mytr_model_id"+datas.tr_id+"\" class=\"brain_trch_mdl brainS_UnikMdl\" data-trid=\""+datas.tr_title+"\" data-isown=\"1\" data-title=\""+datas.tr_id+"\"  data-desc=\""+datas.tr_desc+"\" data-prevw=\"\" data-flwg=\""+datas.tr_flrNb+"\" data-postnb=\""+datas.tr_postNb+"\">";
//        e += "<div class=\"brain_trch_mdl_conf this_hide\">";
//        e += "<div class=\"btmc_top_max\">";
//        e += "<p class=\"btmc_top_text\">";
//        //Message temporaire
//        e += "This Trend has several interesting publications. Are you really sure to wipe it out?";
//        e += "</p>";
//        e += "</div>";
//        e += "<div class=\"btmc_bot_max\">";
//        e += "<div class=\"btmc_bot_btn_max\">";
//        e += "<a class=\"btmc_bot_btn\" data-ans=\"0\" data-target=\"mytr_model_id"+datas.tr_id+"\" href=\"\">No</a>";
//        e += "<a class=\"btmc_bot_btn\" data-ans=\"1\" data-target=\"mytr_model_id"+datas.tr_id+"\" href=\"\">Yes</a>";
//        e += "</div>";
//        e += "</div>";
//        e += "</div>";
//        e += "<a class=\'brain_trch_title npost_tr_trig\' data-trid=\""+datas.tr_id+"\" href=\'\' title=\""+datas.tr_title+"\">"+datas.tr_title+"</a>";
//        e += "<div class=\"trch_body_top\">";
//        e += "<p class=\"trch_mdl_header\">";
//        e += "<a class=\"trch_mdl_group_title npost_tr_trig\" data-trid=\""+datas.tr_id+"\" href=\"\" title=\""+datas.tr_title+"\">";
//        e += "<span class=\"trch_mdl_group_t_img_max\">";
//        e += "<img src=\""+datas.tr_cover+"\" />";
//        e += "</span>";
//        e += "<span class=\"trch_body_desc\">"+datas.tr_desc+"</span>";
//        e += "</a>";
//        e += "</p>";
//        e += "<div class=\"action_maximus action_trch\">";
//        e += "<a href=\"#\" class=\'action_a\'><span class=\'brain_sp_k\'>A</span><span class=\'brain_sp_action\'>ction<span></a>";
//        e += "<ul class=\'action_foll_choices this_hide\'>";
//        e += "<li><a href=\"\" class=\'afl_choice kgb_el_can_revs bind-delmytr\' data-tarbloc=\"brain_list_mytrs\" data-target=\'mytr_model_id"+datas.tr_id+"\' data-action=\'del_mytr\' alt=\"\">Delete</a></li>";
//        e += "</ul>";            
//        e += "</div>";
//        e += "<div class=\"trch_body_down\">";
//        e += "<p class=\"trch_b_d_post\">";
//        e += "<span class=\"trch_b_d_nbrB\">10</span>&nbsp;<span class=\"trch_b_d_nbrS\">k</span>&nbsp;<span class=\"trch_b_d_nbrInd trch_b_d_nbrInd_post\">Post</span>";
//        e += "</p>";
//        e += "<p class=\"trch_b_d_follg\">";
//        e += "<span class=\"trch_b_d_nbrB\">1,</span>&nbsp;<span class=\"trch_b_d_nbrS\">999m</span>&nbsp;<span class=\"trch_b_d_nbrInd\">Followers</span>";
//        e += "</p>";
//        e += "</div>";
//        e += "</div>";
//        e += "</div>";
//        
//        e = $.parseHTML(e);
//        
//        //On rebind les events
//        var $e = _f_NwTrArt_ReBind(e);
//        
//        //On ajoute dans la liste
//        $("#brain_list_mytrs").prepend($e);
//    };
    
    
    
    /**********************************************************************************************/
    /********************** ACTION OVER TR LIST (Following Trends, My Trends) *********************/
    
    //L'Element Trend dans la Liste
    
    
    
    //Permet de verifier qu'on peut atteindre le bloc contenant la cible
    //ET que l'on peut atteindre la cible dans ce bloc
    //NOTE (au 21/04/14) Fonction récupérée de FPH
    var _f_TgtRchblNAuthtic = function(bloc, id) {
//    this.IsTargetReachableNAuthentic = function(bloc, id) {
        /**
         * Si on arrive pas joindre l'element, une erreur est déclenchée et le script va s'arreter.
         * C'est dérangeant car on ne pourra avoir aucun retour. 
         * Le seul moyen est d'avoir un moyen pour l'user de remonter l'information.
         */
        var $o;
        
        //1: Check pour Container
        try {
            $o = $(bloc);
        } catch(e) {
            //TODO : Send error to server
//            Kxlib_DebugVars("Can't reach BFU element Conte !");
            return;
        }
        
        //2: Check por Target
        try {
            //Test
            $o = $(bloc+" "+id);
            //Affectation
            _bfuElSel = $(id);
        } catch(e) {
            //TODO : Send error to server
            Kxlib_DebugVars("Can't reach BFU element !");
            return;
        }
        /*
        var l = $o.html().length;
        
        if(! KgbLib_CheckNullity(l) ) {
            //On s'assure que les deux attributs existent
            try {
                //toString evite le cas ou on aurait 0 et qu'il le considérerait comme faux
                var _bfurel = $o.data("bfurel").toString();
                var _bftrid = $o.data("bftrid").toString();
            } catch(e) {
                //TODO : Send error to server
                Kxlib_DebugVars('BFU defective ! Miss attr : bfurel or bftrid');
                return;
            }
            
            
             * _bfurel peut être null. 
             * Exemple : On arrive sur un compte et on décide de Follow. 
             * On a aucun lien avec celui ci donc c'est vide.
             
            if (! _bftrid ) {
                //TODO : Send error to server
                Kxlib_DebugVars("Error : Element reached is not authentic. Miss bftrid");
                return;
            }
            
            this.ubfurel = _bfurel;
            this.bftrid = _bftrid;
        } 
        //*/
        return 1;
    };
    
    
    var _f_BackConxtr = function () {
//    this.Process_BackConxtr = function (){

        //On retire le badge
        _bfuElSel.find(".badge-conxtr-max").addClass("this_hide");
        
        //On change l'action
        if (! KgbLib_CheckNullity(_trgrObj) ) {
            $(_trgrObj).data("action","rhana_conxtr");
        }
        
        //TODO : Envoyer l'information au niveau du serveur
        _f_Srv_DisAbo(_bfuElSel);
    };
    
    
    var _f_RhnConxtr = function (){
//    this.Process_RhanaConxtr = function (){
        //On rajoute le badge
        _bfuElSel.find(".badge-conxtr-max").removeClass("this_hide");
        
        //On change l'action
        if (! KgbLib_CheckNullity(_trgrObj) ) {
            $(_trgrObj).data("action","back_conxtr");
        }
        
        //TODO : Envoyer l'information au niveau du serveur
        _f_Srv_Abo(_bfuElSel);
    };
    
    
    //STAY PUBLIC
    this.DelMyTr = function (x){
//    var _f_DelMyTr = function (x){
//    this.Process_DelMyTr = function (x){
        try {
            
            if (! KgbLib_CheckNullity(x) ) {
                var $tgr = $(x);
                var sl = Kxlib_ValidIdSel($tgr.data("target"));
                
                //Si selector existe
                if ( $(sl).length ) {
                    var $tar = $(sl);
//                Kxlib_DebugVars([typeof $tgr.data("ans"), $tgr.data("ans")],true);
                    if ( $tgr.data("ans") ) {
                        //Lancer la procédure de suppression
                        var i = $tar.data("trid"), s = $("<span/>");
                        
                        _f_Srv_DelMyTr($tar, i, s);
                        
                        $tar.addClass("this_hide");
                        $(s).on("operended", function(e, d) {
                            if ( KgbLib_CheckNullity(d) | !d.hasOwnProperty("o_cap") | !d.hasOwnProperty("o_pnb") | !d.hasOwnProperty("tr_nb") ) {
                                //TODO : Faire apparaitre une notification qui explique qu'il y a eu une erreur inattendue
                                return;
                            }
                            
                            //On retire définitivement la Tendance
                            $tar.remove();
                            
                            //On retire tous les Articles qui appartenaient à Tendance qui vient d'être supprimée
                            $(".jb-tmlnr-mdl-intr[data-tr=" + i + "]").remove();
                            
                            //TODO : Vérfier le nombre d'Articles restant et lancer la procédure pour faire apparaitre de nouveaux Articles grace à un "faux-"clic sur "Voir plus"
                            
                            //On met à jour le nombre de Tendances
                            $(".jb-acc-spec-trnb").text(d.tr_nb);
                            //On met à jour le nombre d'Articles
                            $(".jb-acc-spec-artnb").text(d.o_pnb);
                            //On met à jour le Capital de l'utilisateur
                            $(".jb-u-sp-cap-nb").text(d.o_cap);
                            
                            //On fait apparaitre la notification
                            var Nty = new Notifyzing();
                            Nty.FromUserAction("ua_del_mytr_tocome");
//                            Nty.FromUserAction("ua_del_mytr"); //[DEPUIS 11-06-15] @BOR
                        });
                        
                    } else {
                        $tar.find(".brain_trch_mdl_conf").addClass("this_hide");
                    }
                } 
            } else {       
                _bfuElSel.find(".brain_trch_mdl_conf").removeClass("this_hide");
            }
        } catch (ex) {
//            Kxlib_DebugVars([ex],true);
        }

    };
    
    var _f_RstCrTrForm = function(x) {
//    this.ResetCrTrForm = function(t) {
        try {
            
            if ( KgbLib_CheckNullity(x) ) { 
                return; 
            }
            
            Kxlib_ResetForm(x);
            
            //On reset la valeur de Catégorie et son preview
//        alert($(t).find(".jb-ntr-itr-catg option:selected").text());
            $(x).find(".jb-ntr-itr-catg option:selected").removeAttr("selected");
            $(x).find(".jb-ntr-itr-catg option:first").attr("selected", true);
//        alert($(t).find(".jb-ntr-itr-catg option:selected").text());
            $(".jb-cat-prw").text($(x).find(".jb-ntr-itr-catg option:selected").text());
//        $(".jb-cat-prw").text($(t).find("#newtr_cat option:first").text());
            
            //On reset la valeur de Participation et son preview
            $(x).find(".jb-ntr-itr-part option:first").attr("selected", true);
            $(".jb-part-prw").text($(x).find(".jb-ntr-itr-part option:first").text());
            
            //On remet à leur état d'origine tous les compteurs de caractères
            $(x).find(".check_char_rcv").each(function(ix) {
                var v = (!KgbLib_CheckNullity($(this).data("dft"))) ? $(this).data("dft") : "";
                $(this).text(v);
                $(this).removeClass("red");
            });
            
            //On retire le champ d'erreur
            $(".jb-ntr-ipt").removeClass("error_field");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /********************************************************************************************************************************************************/
    /******************************************************************** PROCESS SCOPE *********************************************************************/
    /********************************************************************************************************************************************************/
    var _f_Gdf = function() {
        var df = {
            "rgx_tle": /(?:(?=.*[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*).(?![\s]{5,})){20,}/i,
//            "rgx_tle": /(?=.*[a-z])(?:.*[a-zA-Z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]){20,}/i,
            "rgx_tle_cn": 100,
            "rgx_desc": /(?:(?=.*[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*).(?![\s]{5,})){20,}/i,
//            "rgx_desc": /(?=.*[a-z])(?:.*[a-zA-Z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]){20,}/i,
            "rgx_desc_cn": 200,
            /* [DEPUIS 15-06-15] @BOR
            "rgx_tle": /^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{20,100}$/i,
            "rgx_desc": /^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{20,200}$/i,
            //*/
            "part": ["_NTR_PART_PUB","_NTR_PART_PRI"]
        };
        
        return df;
    };
    
    this.ChkOper = function(x) {
//    this.CheckOperation = function(x) {
        
        //On considere que th (this) a été verifié au préalable
        //On aurait pu faire cela en une ligne
        if (! _f_IsTrgrAuthentic(x) ) { return; }
        
        //Maintenant qu'on s'est que tout est ok. On sauvegarde le declencheur.
        //On peut le réutiliser s'il a une fonction de switch
        if ( $(x).hasClass("kgb_el_can_revs") ) {
            _trgrObj = x;
        }
        
//        alert($(this.bftrid).html());
        
        if (! _f_TgtRchblNAuthtic(_tgtBloc, _bftrid) && 1 ) { return; }
        
        //TODO : Demander la confirmation de l'action
        
        switch( _ua ) {
            case "back_conxtr":
                    _f_BackConxtr();
                break;
            case "rhana_conxtr":
                    _f_RhnConxtr();
                break;
            case "del_mytr":
                    gt.DelMyTr();
                break;
            default :
                    return;
                break;
        }
    };
    
    var _f_CatgOnCh = function () {
        var v = $(".jb-ntr-itr-catg option:selected").val(); 
        if ( v.toString().toUpperCase() === "_NTR_CATG_OTHER" ) {
            _f_CatgList();
        }
    };
    
    var _f_CatgOnHvyChc = function (x) {
        if ( KgbLib_CheckNullity(x) | $(x).data("lk") === 1 ) {
            return;
        }
        try {
            
            var el = $(".jb-bn-ntr-catg-chs-bdy").find("input:radio[name=bn-ntr-catg-chcs]:checked");
            if (!$(el).length) {
                return;
            } else {
                var v = $(el).val(); 
                //On vérifie s'il existe déjà un "option" avec la valeur sélectionnée dans la shortlist
                if ($(".jb-ntr-itr-catg option[value='" + v + "']").length) {
//                $(".jb-ntr-itr-catg option:selected").removeProp("selected"); //NO, BUG on arrive plus à utiliser Plus...
                    $(".jb-ntr-itr-catg option[value='" + v + "']").prop("selected", true);
                    
                } else {
                    //on ajoute l'option
                    var sld = $("<option/>").attr({
                        value: v
                    }).text($(el).data("lbl"));
                    $(sld).prop("selected", true);
                    $(sld).insertBefore(".jb-ntr-itr-catg option[value=_NTR_CATG_OTHER]");
                    
                }
                
                $(".jb-cat-prw").text($(el).data("lbl"));
                //On change de vue
                _f_CatgSwList();
                //On fait disparaitre "Back"
                $(".jb-ntr-catg-clz").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_CatgList = function () {
        
        //On lock le bouton valider
        $(".jb-this-catg").data("lk",1);
        
        //On lock le bouton Back
        $(".jb-ntr-catg-clz").data("lk",1);
        
        //On fait apparaitre "Back"
        $(".jb-ntr-catg-clz").removeClass("this_hide");
        
        //On vide l'ancienne liste
        $(".jb-bn-ntr-catg-chs-bdy").find(".jb-bn-ntr-catg-chc-grp").remove();

        //On switch les zones
        _f_CatgSwList(true);
        
        //On fait apparaitre le spinner
        _f_Spinner("ntr_catg",true);
        
        //On fait une demande au serveur
        var s = $("<span/>");
        
        _f_Srv_PullCatgList(s);
        
        $(s).on("datasready",function(e,d){
            if ( KgbLib_CheckNullity(d) ) {
                //On ulock le bouton valider
                $(".jb-this-catg").data("lk",0);
                //On ulock le bouton Back
                $(".jb-ntr-catg-clz").data("lk",0);
                //On masque spinner
                _f_Spinner("ntr_catg");
            
                return;
            }
            
//            d.sort();
            
            //On masque spinner
            _f_Spinner("ntr_catg");
            
            //On construit la liste des catégories
            var  cn = 0;
            $.each(d,function(i,v) {
                var ipt = $("<input/>").attr({
                    id: "bn-ntr-catg-chc-n"+cn,
                    class: "bn-ntr-catg-chc-ipt",
                    name: "bn-ntr-catg-chcs",
                    value: i,
                    "data-lbl": v,
                    type: "radio"
                });
                var lbl = $("<label/>").attr({
                    class: "bn-ntr-catg-chcs-lbl",
                    for: "bn-ntr-catg-chc-n"+cn
                }).text(v);
                var grp = $("<div/>").attr({
                    class: "bn-ntr-catg-chc-grp jb-bn-ntr-catg-chc-grp"
                }).append(ipt,lbl);
                $(".jb-bn-ntr-catg-chs-bdy").append(grp);
                
                ++cn;
            });
            
            //On ulock le bouton valider
            $(".jb-this-catg").data("lk",0);
            //On ulock le bouton Back
            $(".jb-ntr-catg-clz").data("lk",0);
            
        });
    };
    
    var _f_NwTrd = function(x) {
//    this.HandleNewTrendProcess = function() {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
      
        if ( $(x).data("lk") === 1 ) {
            return;
        }
        
        //On lock le bouton
        $(x).data("lk",1);
        //On affiche le spinner
        _f_Spinner("ntr", true);
        
        //On vérifie les champs
        if (! _f_CheckFields() ) {
            //On unlock le bouton
            $(x).data("lk",0);
            //On masque le spinner
            _f_Spinner("ntr");
        
            return;
        } else {
            
//        if ( _f_ChkFrmScp() ) {
            //On prépare les données à envoyer au serveur
            var title = $(".jb-ntr-itr-tle").val(), desc = $(".jb-ntr-itr-desc").val(), cat = $(".jb-ntr-itr-catg").val(), part = $(".jb-ntr-itr-part").val();//, grat = $("#newtr_grat").val();
//            Kxlib_DebugVars([title,desc,cat,part],true);
            var Pack = {
                t:title,
                d:desc,
                c:cat,
                pt:part
            };
            
            var s = $("<span/>");
            
            _Srv_NwTrd(Pack,s,x);
            
            //On Reset le formulaire en attendant la réponse du serveur
//            _f_RstCrTrForm(Kxlib_ValidIdSel(_form_sel)); //NON : Autant laisser les données jusqu'à la redirection. De plus, si ça bogue et que les données disparaisse, l'user risque de ne rien comprendre surtout si on ne signale rien
            
            //TODO : Faire apparaitre une Loader avec la phrase "Création de la tendance en cours..."
            
            $(s).on("operended", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                /*
                 * [NOTE 13-09-14] @author L.C.
                 * Le serveur renvoie aussi tr_nb.
                 * Cela correspond au nombre de Tendances appartenant à l'utilisateur actif.
                 * Cette valeur n'est renvoyée que si l'utilisateur est sur son compte.
                 * Cependant, à la version vb1, l'utilisateur sera toujours renvoyé vers la page nouvellement construite. 
                 * Aussi, elle ne servira que pour de potentielles futures versions.
                 */
                
                //On vérifie si on a bien reçu l'url de la Tendance
                if ( !KgbLib_CheckNullity(d) && !KgbLib_CheckNullity(d.tul) ) {
                    
                    /*
                     * [DEPUIS 11-07-15] @BOR
                     * Pour améliorer l'expérience utilisateur :
                     *      (1) On indique que la Tendance a été créée
                     *      (2) On reset le formulaire
                     * On ne dirige pas l'utilisateur vers la liste car ça va à l'encontre de son droit de décider de ce qu'il faut faire ensuite.
                     * Par exemple, s'il veut en créer plusieurs à la suite, ce comportement ne lui plaira pas
                     */
                    var Nty = new Notifyzing();
                    Nty.FromUserAction("ua_new_mytr");
                    //On unlock le bouton Trigger
                    $(x).data("lk",0);
                    //On masque le spinner
                    _f_Spinner("ntr");
                    //On reset le formulaire
                    $(".jb-f_ntr_sub_rst").click();
                    
                    /*
//                    Kxlib_DebugVars(d.tul);
//                    return;
                    //On redirige vers la nouvelle Tendance
                    window.location.href = d.tul;
                    */
                } 
                /*
                else  {
                    // ... Sinon on reload la page. L'utilisateur pourra constater que dans la liste de ses Tendances si elle existe
                    location.reload();
                }
                */
            });
        }
    };
    
    var _f_CheckFields = function() {
        /*
         * Vérifie la validité de chaque champ pour le formulaire de création de Tendance
         */
        var fds = $(".jb-ntr-ipt"), ecn = 0;
        $.each(fds,function(x,v){
            if ( !$(v).data("ft") ) {
                return;
            }
            
            if (! _f_CheckField(v) ) {
                ++ecn;
            }
        });
        
        return ( ecn ) ? false : true;
        
    };
    
    var _f_CheckField = function (x) {
        if ( !x | !$(x).data("ft") ) {
            return;
        }
        
        var fd = $(x).data("ft"), v, ie = false;
        switch(fd.toLowerCase()) {
            case "title":
                    var v = $(x).val();
                    v = $(x).val();
                   
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ( v.length > _f_Gdf().rgx_tle_cn ) { 
                        /*
                         * [DEPUIS 15-06] @BOR
                         * La règle regex précédente ne permettait pas de s'assurer qu'on avait au moins 20 caractères de type lettre.
                         * J'ai pu créer une regex qui le permet en même temps qu'elle vérifie la taille totale. 
                         * Cependant, elle a une performance médiocre à tel point que le code crash à tous les coups.
                         * J'ai donc fait un compromis où  on teste les lettres mais pas le nombre.
                         */
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_tle.test(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                break;
            case "description":
                    var v = $(x).val();
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ( v.length > _f_Gdf().rgx_desc_cn ) { 
                        /*
                         * [DEPUIS 15-06] @BOR
                         * La règle regex précédente ne permettait pas de s'assurer qu'on avait au moins 20 caractères de type lettre.
                         * J'ai pu créer une regex qui le permet en même temps qu'elle vérifie la taille totale. 
                         * Cependant, elle a une performance médiocre à tel point que le code crash à tous les coups.
                         * J'ai donc fait un compromis où  on teste les lettres mais pas le nombre.
                         */
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_desc.test(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                break;
            case "category":
                //Controllée par le serveur
                break;
            case "participation":
                v = $(".jb-ntr-itr-part option:selected").val(); 
                if ( KgbLib_CheckNullity(v) ) {
                    ie = true;
                    $(x).addClass("error_field");
                } else if ( $.inArray(v,_f_Gdf().part) === -1  ) {
                    ie = true;
                    $(x).addClass("error_field");
                } else {
                    $(x).removeClass("error_field");
                }
                break;
            default:
                    return;
                break;
        }
        
        return ( ie ) ? false : true;
        
    };
    
    /**
     * Permet de s'assurer que la cible est authentique
     * @param {type} th
     * @returns {Number|undefined}
     */
    //NOTE (au 21/04/14) Fonction récupérée de FPH
    var _f_IsTrgrAuthentic = function (x) {
//    this.IsTriggerAuthentic = function (x) {
        if ( KgbLib_CheckNullity($(x).data("target")) ) {
            //L'erreur devra etre encoyé au server dans la version production
//            Kxlib_DebugVars("Error : Can't reach target");
            return;
        } else { 
            _bftrid = Kxlib_ValidIdSel($(x).data("target"));
        }

        if ( KgbLib_CheckNullity($(x).data("tarbloc")) ) {
            //L'erreur devra etre encoyé au server dans la version production
//            Kxlib_DebugVars("Error : Can't reach targetBloc");
            return;
        } else {
            _tgtBloc = Kxlib_ValidIdSel($(x).data("tarbloc"));
        }
        
        if( KgbLib_CheckNullity($(x).data("action")) ) {
            //L'erreur devra etre encoyé au server dans la version production
//            Kxlib_DebugVars("Error : Can't get access to uaction");
            return;
        } else {
            _ua = $(x).data("action");
        }
        
        return true;
    };
    
    /********************************************************************************************************************************************************/
    /********************************************************************** SERVER SCOPE ********************************************************************/
    /********************************************************************************************************************************************************/
    
    /*
     * (04-12-14)
     *  TODO : La méthode a été rafraichie mais pas testé.
     *  Certains codes font surement appel à un code futur.
     *  */
    //URQID => Se déconnecter d'une Tendance que je suis
    var _Ax_DisAbo = Kxlib_GetAjaxRules("TMLNR_BN_DISABO",Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_DisAbo = function (x) {
//    this.Ser_DoDisconectFromTr = function ($argv) {
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("trid")) ) { 
//        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("postnb")) ) { 
            return; 
        }
        
        var i = $(x).data("trid");
//        var i = $(x).data("postnb");
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                    
//                    $.each(datas.return,function(x,v){
//                        Kxlib_DebugVars([v.ueid],true);
//                    });
                    
                    if(! KgbLib_CheckNullity(datas.err) ) {
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            
                            switch (datas.err) {
                                case "__ERR_VOL_USER_GONE":
                                case "__ERR_VOL_ACC_GONE":
                                case "__ERR_VOL_CU_GONE":
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_DENY":
                                case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    return;
                                    break;
                                default:
                                        return;
                                    break;
                            }
                        }
                        return;
                    } else if (! KgbLib_CheckNullity(datas.return) ) {
                        $(_bftrid).data("isfolw","0");
                        var rds = [datas.return];
                        $(s).trigger("datasready",rds);
                    }
                } else return;
                
            } catch (e) {
//                Kxlib_DebugVars([e],true);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
        };
        
        var toSend = {
            "urqid": _Ax_DisAbo.urqid,
            "datas": {
                "tr_id": i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DisAbo.url, wcrdtl : _Ax_DisAbo.wcrdtl });
    };
    
    
    //URQID => Signaler au serveur de supprimer une Tendance
    var _Ax_DelMyTr = Kxlib_GetAjaxRules("TMLNR_DEL_MyTR", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_DelMyTr = function (de,i,s) {
//    this._Srv_DelMyTr = function (de,i,s) {
       if ( KgbLib_CheckNullity(de) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
                
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else return;
                
                if(! KgbLib_CheckNullity(d.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TR_GONE":
                                    $(de).remove();
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_FAILED" :
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    var rds = [d.return];
                    $(s).trigger("operended",rds);
                }
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                return;
            }
        };

        var onerror = function (a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
           
        /*
         * Pour finir d'être sur qu'il faudra bien renvoyer les données sur le nombre de Tendance, on renvie l'URL.
         * Elle sera analysée afin de décider s'il faut renvoyer les donnnées.
         */
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_DelMyTr.urqid,
            "datas": {
                "i": i,
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DelMyTr.url, wcrdtl : _Ax_DelMyTr.wcrdtl });
    };
    
    //URQID => Créer la nouvelle TENDANCE
    var _Ax_NewTrend = Kxlib_GetAjaxRules("TMLNR_BRAIN_NEWTREND", Kxlib_GetCurUserPropIfExist().upsd);
    var _Srv_NwTrd = function (o,s,x) {
//    this._Srv_NewTrend = function (o,s) {
        if ( KgbLib_CheckNullity(o) | KgbLib_CheckNullity(s) | KgbLib_CheckNullity(x) ) { 
            //On unlock
            $(x).data("lk",0);
            //On masque le spinner
            _f_Spinner("ntr");
            
            return;
        }
        
        var os = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else return;
                
                if(! KgbLib_CheckNullity(d.err) ) {
                    //On unlock le bouton
                    $(x).data("lk",0);
                    //On masque le spinner
                    _f_Spinner("ntr");
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_ACC_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_FAILED" :
                                    return;
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    Kxlib_AJAX_HandleFailed();
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    var rds = [d.return];
                    $(s).trigger("operended",rds);
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                
                //On unlock le bouton Trigger
                $(x).data("lk",0);
                //On masque le spinner
                _f_Spinner("ntr");
                
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var oe = function () {
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            
            //On unlock le bouton Trigger
            $(x).data("lk",0);
            //On masque le spinner
            _f_Spinner("ntr");
            
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_NewTrend.urqid,
            "datas": {
                "t": o.t,
                "d": o.d,
                "c": o.c,
                "pt": o.pt,
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, os, oe, { type : "post", url : _Ax_NewTrend.url, wcrdtl : _Ax_NewTrend.wcrdtl });
    };
    
    
    var _Ax_Abo = Kxlib_GetAjaxRules("TMLNR_BN_ABO",Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_Abo = function (x) {
//    this.Ser_DoConectAgainToTr = function ($argv) {
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("trid")) ) { 
//        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("postnb")) ) { 
            return; 
        }
        
        var i = $(x).data("trid");
//        var i = $(x).data("postnb");
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                    
//                    $.each(datas.return,function(x,v){
//                        Kxlib_DebugVars([v.ueid],true);
//                    });
                    
                    if(! KgbLib_CheckNullity(datas.err) ) {
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            
                            switch (datas.err) {
                                case "__ERR_VOL_USER_GONE":
                                case "__ERR_VOL_ACC_GONE":
                                case "__ERR_VOL_CU_GONE":
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_DENY":
                                case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    return;
                                    break;
                                default:
                                        return;
                                    break;
                            }
                        }
                        return;
                    } else if (! KgbLib_CheckNullity(datas.return) ) {
                        /*
                        $(_bftrid).data("isfolw",1);
                        var rds = [datas.return];
                        $(s).trigger("datasready",rds);
                        //*/
                    }
                } else return;
                
            } catch (e) {
//                Kxlib_DebugVars([e],true);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
            
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
        };

        var toSend = {
            "urqid": _Ax_Abo.urqid,
            "datas": {
                "tr_id": i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Abo.url, wcrdtl : _Ax_Abo.wcrdtl });
    };
    
    var _Ax_PullCatgList = Kxlib_GetAjaxRules("TMLNR_BRAIN_PLCATG",Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_PullCatgList = function (s) {
//    this.Ser_DoConectAgainToTr = function ($argv) {
        if ( KgbLib_CheckNullity(s) ) { 
            //On ulock le bouton valider
            $(".jb-this-catg").data("lk",0);
            //On ulock le bouton Back
            $(".jb-ntr-catg-clz").data("lk",0);
            //On masque spinner
            _f_Spinner("ntr_catg");
            
            return; 
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                    
                    if(! KgbLib_CheckNullity(datas.err) ) {
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            //On ulock le bouton valider
                            $(".jb-this-catg").data("lk",0);
                            //On ulock le bouton Back
                            $(".jb-ntr-catg-clz").data("lk",0);
                            //On masque spinner
                            _f_Spinner("ntr_catg");
                            
                            switch (datas.err) {
                                case "__ERR_VOL_USER_GONE":
                                case "__ERR_VOL_ACC_GONE":
                                case "__ERR_VOL_CU_GONE":
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_DENY":
                                case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    return;
                                    break;
                                default:
                                        return;
                                    break;
                            }
                        }
                        return;
                    } else if (! KgbLib_CheckNullity(datas.return) ) {
                        var rds = [datas.return];
                        $(s).trigger("datasready",rds);
                    }
                } else return;
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([e],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
            
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_PullCatgList.urqid,
            "datas": {
                "curl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PullCatgList.url, wcrdtl : _Ax_PullCatgList.wcrdtl });
    };
    
    /********************************************************************************************************************************************************/
    /********************************************************************** VIEW SCOPE **********************************************************************/
    /********************************************************************************************************************************************************/
    
    var _f_ChgOnSelect = function(x) {
//    this.HandleChangeOnSelect = function(x) {
        if ( KgbLib_CheckNullity(x) ) { return; }
        
        var s = Kxlib_ValidIdSel($(x).attr("id")+" option:selected");
        var v = $(s).text(), pv = Kxlib_ValidIdSel($(x).data("preview"));
        
        $(pv).html(v);
    };
    
    var _f_Spinner = function(scp,sw) {
        if ( KgbLib_CheckNullity(scp) ) { 
            return; 
        }
        
        var s;
        switch (scp.toLowerCase()) {
            case "ntr":
                    s = ".jb-nwtr-trg-spnr";
                break;
            case "ntr_catg":
                    s = ".jb-bn-ntr-catg-chs-spnr";
                break;
            default:
                return;
        }
        
        if ( sw ) {
            $(s).removeClass("this_hide");
        } else {
            $(s).addClass("this_hide");
        }
    };
    
    var _f_CatgSwList = function (sw) {
        if ( sw ) {
            $(".jb-bn-ntr-catg-chs-sprt").removeClass("this_hide");
            $(".jb-bn-newtr-mx").addClass("this_hide");
        } else {
            $(".jb-bn-ntr-catg-chs-sprt").addClass("this_hide");
            $(".jb-bn-newtr-mx").removeClass("this_hide");
        }
    };
    
    /********************************************************************************************************************************************************/
    /****************************************************************** LISTERNERS SCOPE ********************************************************************/
    /********************************************************************************************************************************************************/
    
    $(_iptSl).blur(function(){
        if ( $(this).val() !== "" ) {
            $(this).removeClass(_errClass);
        }
    });
    
    $(".jb-crt-trd-tgr").click(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_NwTrd(this);
    });
    
    $("#newtr_form select").change(function(e){
        _f_ChgOnSelect(this);
    });
    
    $("#reset_tr_btn").change(function(){
        var sl = Kxlib_ValidIdSel($(this).data("form"));
        
        $(sl).each(function(){
            this.reset();
	});
    });
    
    $(".btmc_bot_btn").click(function(e){
        Kxlib_PreventDefault(e);
        
        gt.DelMyTr(this);
    });
    
    $(".jb-f_ntr_sub_rst").click(function(e){
        Kxlib_PreventDefault(e);
        
        var t = Kxlib_ValidIdSel($(this).data("target"));
        _f_RstCrTrForm(t);
    });
    
    $(".jb-ntr-itr-catg").change(function(){
        _f_CatgOnCh();
    });
    
    $(".jb-this-catg").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_CatgOnHvyChc(this);
    });
    
    $(".jb-ntr-catg-clz").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_CatgSwList();
        //On fait disparaitre "Back"
        $(".jb-ntr-catg-clz").addClass("this_hide");
        
    });
    
}

new Brain_HandleTrend();

function TrOpe_Receiver (){
    this.Routeur = function (th){
        if ( KgbLib_CheckNullity(th) ) return; 
        
        var _Obj = new Brain_HandleTrend();
        _Obj.ChkOper(th);
    };
};