/**
 * Travailler en ecoutant "mousedown" permet de regler certains problèmes
 * de priorité de traitement dans la file d'execution.
 * Exemple: On veut traiter un click sur un sub, or ce sub disparait dès qu'un 
 * autre element perd son focus. Aussi le click n'est jamais traité.
 * 
 * Ici on catch donc l'event et on décide de l'ordre de traitement !!
 * 
 * [NOTE 26-07-14] J'ai pu contourné le problème pour les nouveaux cas en empechant l'élément mère de blur()
 */
try {
    
    $(document).mousedown(function (e) {
        var $revcl = $(".kgb_el_can_revs");
        var $bfucl = $(".bind-fluser");
        /*
         * [DEPUIS 04-07-15] @BOR 
         * NOn utilisé et pour limiter les risques de sécurité
         */
    //    var $tesycl = $(".bind-testyfav"); 
        var $getlinkcl = $(".get-link-trig");
        var $delpost = $(".del-the-post");
        var $concTr = $(".bind-conxtr");
        var $delMytr = $(".bind-delmytr");
        var $pmGoVstdMytr = $(".bind-pm-gvstd");

    //    var $chTrCover = $(".bind-chtrcov"); //OLD
        var $chTrCover = $(".jb-tr-chcov-trg");
        var $delTrCvr = $(".bind-deltrcov");

        var $trOpFil = $(".bind-tr-filters");
        var $trOpEdit = $(".bind-tr-trpg-edit");
        var $trOpCr = $(".bind-tr-trpg-cr");

        //irr = IsRestrictedReserved; wchlop = WithChildrenOption
        var $irr = $(".jb-irr, .jb-irr-wchlop *");
        //rld =ReLoaD
        var $rld = $(".jb-rld");

        var $goto =  $(".bind-goto");

        var $chbxOpt =  $(".jb-chbx-opt-chc-tgr");

        var $AtHmMn =  $(".jb-tmlnr-athm-mn");

        var $flbmn =  $(".jb-flb-mn");

        var $tlkbOpt = $(".jb-tqr-tsty-art-opt").filter("[data-action='post-pin-start'],[data-action='post-del-start']");
//        var $tlkbOpt = $(".jb-tqr-tsty-art-opt[data-action='post-gotopt']");

        var $ubxMns = $(".jb-ubx-menu-choices");

        /***************** TQR WELECOME ******************/
        var $insMrTrg = $(".jb-ins-mr-trg");

    //    if ( $(".jb-ubx-menu-choices[data-action=open_frdreq]").is(e.target) ) alert("bof");
    
        if ( $revcl.is(e.target) ) {
            Revs_HandleClick(e);
        } else if ( $bfucl.is(e.target) ) {
            (new FPH_Receiver).Routeur(e.target);
        }
        /*
         * [DEPUIS 04-07-15] @BOR NOn utilisé et pour limiter les risques de sécurité
         */
        /*
        if ( $tesycl.is(e.target) ) {
            (new Testy_Receiver).Routeur(e.target);
        }
        //*/
        else if ( $getlinkcl.is(e.target) ) {
            (new RichPost_Receiver).Routeur(e.target);
        }
        else if ( $delpost.is(e.target) ) {
            (new RichPost_Receiver).Routeur(e.target);
        }
        else if ( $concTr.is(e.target) ) {
            (new TrOpe_Receiver).Routeur(e.target);
        }
        else if ( $delMytr.is(e.target) ) {
            (new TrOpe_Receiver).Routeur(e.target);
        }
        else if ( $chTrCover.is(e.target) ) {
    //        Kxlib_StopPropagation(e);
            Kxlib_PreventDefault(e);

    //        $(e.target).closest(".action_foll_choices").addClass("this_hide");
    //        $(e.target).closest(".action_trhome").find(".action_a").blur();
    //        (new TrHeader_Receiver).Routeur(e.target,e); //OBSELETE
        }
        else if ( $delTrCvr.is(e.target) ) {
    //        Kxlib_StopPropagation(e);
            (new TrHeader_Receiver).Routeur(e.target);
        }
        else if ( $trOpFil.is(e.target) ) {
            (new TrFilters_Receiver).Routeur(e.target);
        }
        else if ( $trOpEdit.is(e.target) ) {
            (new TrendEdit_Receiver).Routeur(e.target);
        } 
        else if ( $trOpCr.is(e.target) ) {
            (new TrendEdit_Receiver).Routeur(e.target);
        }
        else if ( $insMrTrg.is(e.target) ) {
            Kxlib_PreventDefault(e);
            (new Ins_Receiver).Routeur(e.target);
        }
        else if ( $irr.is(e.target) ) {
            Kxlib_PreventDefault(e);
            Kxlib_StopPropagation(e);

            //On redirige vers /login
            /*
             * On utilise ce mode plutot que celui qui simule une redirection pour donner la possibilité à l'utilisateur de revenir en arrière.
             * Il s'agit de garantir une bonne expérience utilisateur.
             */
            window.location.href = "/login";
            return false;
        }
        else if ( $rld.is(e.target) ) {
            Kxlib_PreventDefault(e);
            //On reload
            window.location.reload();
            return;
        }
        else if ( $goto.is(e.target) ) {
            //Aller vers 
            /*
             * [NOTE 20-05-15] @BOR
             * La ligne commentée entrainait un bogue car il n'y a pas de cible mais une selection de cibles.
             * Aussi, il prennait celle en haut de liste.
             */
            window.location.href = $(e.target).attr("href");
    //        window.location.href = $goto.attr("href");
        }
        else if ( $chbxOpt.is(e.target) ) {
            Kxlib_PreventDefault(e);
        }
        else if ( $AtHmMn.is(e.target) ) {
            (new ACHR_Receiver).Routeur(e.target);
        }
        else if ( $flbmn.is(e.target) ) {
            (new _FE_ENTY_FRD_RCVR).Routeur(e.target);
        }
        else if ( $tlkbOpt.is(e.target) ) {
            $(e.target).trigger("cusmclick",e);
        } 
        /*
         * [DEPUIS 12-06-16]
         */
        else if ( $ubxMns.is(e.target) ) {
//            console.log("EDITER : ",$(e.target).data("action"), $.inArray($(e.target).data("action"),["go_url","invite_friends"]),$.inArray($(e.target).data("action"),["open_frdreq","bugzy"]),!KgbLib_CheckNullity($(e.target).prop("href")));
            if ( $(e.target).data("action") && $.inArray($(e.target).data("action"),["go_url","invite_friends"]) !== -1 && !KgbLib_CheckNullity($(e.target).prop("href")) ) {
//                console.log("EDITER : ","GO URL !");
                var url = $(e.target).prop("href");
//                alert(url);
//                console.log("UBX_MENUS => ",url);
                window.location.href = url;
            }
            else if ( $(e.target).data("action") && $.inArray($(e.target).data("action"),["open_frdreq","bugzy"]) !== -1 ) {
                Kxlib_PreventDefault(e);
                $(e.target).trigger("tqr_cuev_click");
            }
                
        }
    });
} catch (ex) {
    console.log("A \"likely\" fatal error occurred while processing an onclick event.");
}

try {
    /*
     * [DEPUIS 30-07-16]
     */
//    window.hasfocus = true; 
    window.hasfocus = document.hasFocus();
    $(window).focus(function () {
//        console.log("HAS FOCUS !");
        
        window.hasfocus = true;
    }).blur(function () {
//        console.log("LOST FOCUS !");
        
        /*
         * [DEPUIS 30-07-16]
         *      On ne considère que le BLUR car c'est plus cohérent. 
         *      Car c'est la dernière fois que USER était là qui compte vraiment
         */
        var nw = new Date().getTime();
        $(".jb-bob-leponge").data("lafk",nw);
        window.hasfocus = false;
    });
} catch (ex) {
//    console.log("A \"likely\" fatal error occurred while processing an onclick event.");
}

/*
 * [DEPUIS 02-07-16]
 *  
 */
try {
    window.isUnloading = false;
    $(window).on('beforeunload', function(){
        window.isUnloading = true;
    });
} catch (ex) {
//    console.log("A \"likely\" fatal error occurred while handling an unload event.");
}


/*
 * [DEPUIS 11-06-16]
 *      Permet de connaitre les coordonnées de la souris
 *      
 * [NOTE 11-06-16 20:26]
 *      Je voulais m'en servir pour régler un BOGUE au niveau de la gestion de DRAG_IN/OUT sur FF
 */
try {
    var currMousePos = { x: -1, y: -1 };
    $(document).mousemove(function(e) {
        window.mouse_x = e.pageX;
        window.mouse_y = e.pageY;
    });
} catch (ex) {
//    console.log("A \"likely\" fatal error occurred while the processing an onmousemove event.",ex);
}

/*
 * [DEPUIS 11-06-16]
 *      Permet de savoir si la souris est dans l'espace de la PAGE.
 *      ATTENTION : Ne prend pas en compte le cas de l'évènement DRAG !!
 *      
 * [NOTE 11-06-16 20:26]
 *      Je voulais m'en servir pour régler un BOGUE au niveau de la gestion de DRAG_IN/OUT sur FF
 */
try {
    window.mouseOnGame = false;
    $(document).mouseleave(function(e) {
        window.mouseOnGame = false;
    });
    $(document).mouseenter(function(e) {
        window.mouseOnGame = true;
    });
} catch (ex) {
//    console.log("A \"likely\" fatal error occurred while the processing an onmousemove event.",ex);
}

try {  
    jQuery.fn.rotate = function(degrees) {
        $(this).css({'transform' : 'rotate('+ degrees +'deg)'});
        return $(this);
    };
} catch (ex) {
//    console.log("A \"likely\" fatal error occurred while the processing an onmousemove event.",ex);
}

/*******************************************************************************************************/

var saveSelection, restoreSelection;

if (window.getSelection && document.createRange) {
    saveSelection = function(containerEl) {
        var range = window.getSelection().getRangeAt(0);
        var preSelectionRange = range.cloneRange();
        preSelectionRange.selectNodeContents(containerEl);
        preSelectionRange.setEnd(range.startContainer, range.startOffset);
        var start = preSelectionRange.toString().length;

        return {
            start: start,
            end: start + range.toString().length
        };
    };

    restoreSelection = function(containerEl, savedSel) {
        var charIndex = 0, range = document.createRange();
        range.setStart(containerEl, 0);
        range.collapse(true);
        var nodeStack = [containerEl], node, foundStart = false, stop = false;
        
        while (!stop && (node = nodeStack.pop())) {
            if (node.nodeType == 3) {
                var nextCharIndex = charIndex + node.length;
                if (!foundStart && savedSel.start >= charIndex && savedSel.start <= nextCharIndex) {
                    range.setStart(node, savedSel.start - charIndex);
                    foundStart = true;
                }
                if (foundStart && savedSel.end >= charIndex && savedSel.end <= nextCharIndex) {
                    range.setEnd(node, savedSel.end - charIndex);
                    stop = true;
                }
                charIndex = nextCharIndex;
            } else {
                var i = node.childNodes.length;
                while (i--) {
                    nodeStack.push(node.childNodes[i]);
                }
            }
        }

        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    }
} else if (document.selection && document.body.createTextRange) {
    saveSelection = function(containerEl) {
        var selectedTextRange = document.selection.createRange();
        var preSelectionTextRange = document.body.createTextRange();
        preSelectionTextRange.moveToElementText(containerEl);
        preSelectionTextRange.setEndPoint("EndToStart", selectedTextRange);
        var start = preSelectionTextRange.text.length;

        return {
            start: start,
            end: start + selectedTextRange.text.length
        }
    };

    restoreSelection = function(containerEl, savedSel) {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(containerEl);
        textRange.collapse(true);
        textRange.moveEnd("character", savedSel.end);
        textRange.moveStart("character", savedSel.start);
        textRange.select();
    };
}

/******************************************************************************************************/
/******************************************************************************************************/
/******************************************************************************************************/
/******************************************************************************************************/

function geneventhandler(){
    $("#start-here").click(function(){
//        alert("Lancer didactiel de premiere utilisation. \nRAPPEL : Ce bouton devra être remplacé en temps voulu!");
    });
};

function setSelectionRange(input, selectionStart, selectionEnd) {
//    alert("pol");

  if (input.setSelectionRange) {
    input.focus();
    input.setSelectionRange(selectionStart, selectionEnd);
  }
  else if (input.createTextRange) {
    var range = input.createTextRange();
    range.collapse(true);
    range.moveEnd('character', selectionEnd);
    range.moveStart('character', selectionStart);
    range.select();
  }
}


function getCaretCharacterOffsetWithin(element) {
    var caretOffset = 0;
    var doc = element.ownerDocument || element.document;
    var win = doc.defaultView || doc.parentWindow;
    var sel;
    if ( typeof win.getSelection != "undefined" ) {
        var range = win.getSelection().getRangeAt(0);
        var preCaretRange = range.cloneRange();
        preCaretRange.selectNodeContents(element);
        preCaretRange.setEnd(range.endContainer, range.endOffset);
        caretOffset = preCaretRange.toString().length;
    } else if ( (sel = doc.selection) && sel.type != "Control" ) {
        var textRange = sel.createRange();
        var preCaretTextRange = doc.body.createTextRange();
        preCaretTextRange.moveToElementText(element);
        preCaretTextRange.setEndPoint("EndToEnd", textRange);
        caretOffset = preCaretTextRange.text.length;
    }
    return caretOffset;
}


function showCaretPos() {
    var el = document.getElementById("npost_txt");
    //var caretPosEl = document.getElementById("clan");
    //caretPosEl.innerHTML = "Caret position: " + getCaretCharacterOffsetWithin(el);
    return getCaretCharacterOffsetWithin(el);
}


function handleHashtagInput (obj, k) {
    if ( k === 32 ) {
        var _rx = new RegExp(" #([a-zA-Z_]{2,}|[\d]+[a-zA-Z_]{2,})");
        var _m = $(obj).html();
        var _ar = _m.match(_rx);  
        
        if ( _ar ) {
            var savedSelection = saveSelection( document.getElementById("npost_txt") );
            _m = _m.replace(_rx, " <a class='kgb_blue_link gkb_blue_hyperlink' href='https://www.google.fr/?q=$1'>#$1</a>");
            
            $(obj).html(_m);
            restoreSelection(document.getElementById("npost_txt"), savedSelection);
//            $(obj).attr("contenteditable","false");
            //$(obj).focus();
            //alert($(obj).text());
        }
    }
}

(function(){
    geneventhandler();
    
    $("#npost_txt").keyup(function(e){
        //alert(e.which);
        
        handleHashtagInput(this,e.which);
        //moveCaretToEnd(this);
        //var _p = showCaretPos();
        //setCaretPos(_p, _p);
        $(".gkb_blue_hyperlink").click(function(){
            //alert("marche");
        });
        
        $(".gkb_blue_hyperlink").hover(function(){
            $(this).toggleClass("gkb_blue_hyperlink_h");
        }, function(){
            $(this).toggleClass("gkb_blue_hyperlink_h");
        });
    });
    /*
    $(window).keydown(function(e){
        
        $("#npost_txt").attr("contenteditable","true");
        $("#npost_txt").focus();
        //alert(e.which);
    });
    */
})();

/********************************************************************************************************/
/********************************************************************************************************/


//Handle Action
//Cette variable sert de liant entre le Handler général d'Action et celui de SubAction ou Reverse
var _com_action_sub_has_prior = false;
(function() {
    
    $(".action_a").focusout(function(e){
//        return;
        if ( e.target === this ) {
            e.stopPropagation();
//            Kxlib_DebugVars([Lost Focus!"]);
//            Kxlib_DebugVars([Event Was : "+e.type]);
            $(this).parent().children(".action_foll_choices").addClass("this_hide");
            //La ligne ci-dessous causait un bug : too much recursion a cause d'un 'bubbling tree'
//            $(this).blur();
        }
    });


    $(".action_a").click(function(e){
        Kxlib_PreventDefault(e);
//        e.stopPropagation();
        
        $(this).focus();

        if(! $(this).parent().children(".action_foll_choices").hasClass("this_hide") ) {
            $(this).parent().children(".action_foll_choices").addClass("this_hide");
            $(this).blur();
//            Kxlib_DebugVars([Ready to retrun !"]);
            return;
        }
        $(".action_foll_choices").not(this).addClass("this_hide");
        $(this).parent().children(".action_foll_choices").toggleClass("this_hide");
    });
    
    $(".action_a").hover(function(){
//       Kxlib_DebugVars([Hover Action_a !"]);     
    },function(){

    });
})();


/********************************************************************************************************/
/********************************************************************************************************/
//HANDLE ERROR BAR 

$("#warning_bar_close_a").click(function(){
    $(this).parent().parent().toggleClass("this_hide");
});


/********************************************************************************************************/
/********************************************************************************************************/
//HANDLE CHARs COUNT on INPUT  

function CountChar_IsHash (e) {
    //Kxlib_DebugVars([.which]);
    //Qui peut aussi dire ctrl
    var _altgr = false;
//    var _hash = false;
    
    if ( e.type === "keydown" && e.which === 17 ) {
        _altgr = true;
//        Kxlib_DebugVars([altgr - true"]);
        return false;
    } else if ( e.type === "keyup" && e.which === 17 ) {
        _altgr = false;
//        Kxlib_DebugVars([altgr - false"]);
        return false;
    }
    
    if ( e.type === "keyup" && e.which === 51 ) {
        _hash = true;
//        Kxlib_DebugVars([hash - true"]);
        return true;
    } else if ( e.type === "keydown" && e.which === 51 ) {
        _hash = false;
//        Kxlib_DebugVars([hash - false"]);
        return false;
    }
    
    return false;
}
/*
function CountChar_SkipHash (o) {
    //O = l'objet texteArea
    /* Renvoie la longueur de la chaine où a été retiré le # pour 
//    var reg = new RegExp("(#)",'g'), p = t[--t.length];
    //Le fait d'écouter plusieurs events peut fausser les calculs. Aussi, certaines variables ne sont pas stockées (Ex: t.length)
    var p, t = $(o).val(), k = t.charAt(t.length-1);
//    Kxlib_DebugVars([Handle sharp : "+l]);
    if ( $(o).hasClass("skip_sharp") ) {
        if ( k === "#") {
            if ( t.length > 1 ) {
                //On vérifie si le caractère précédent existe. Rappel : LENGTH - 1 correspond au dernier caractère de la chaine
                p = t.charAt(t.length-2);

                if ( p === '#' )
                    return t.length;
                else {
                    var n = t.length-1;
                    return n;
                }
            } else return 0;
        } else return t.length;
    } else return t.length;
    
//    return t.replace(reg,"").length;
}
*/

//Version sélectionnée
function CountChar_SkipHash (o) {
    
    if ( typeof o === "undefined" ) {
        return;
    }
    
    /*
     * Le but étant de n'échapper que les '#' qui servent à reconnaitre les hashtags
     * */
    //rn = (rNormal) Pour les cas #oeeoeoe; rn = (rFake) Pour les cas ######
    var t = $(o).val();
    //var rn = new RegExp("(\#)([^\#|\s]+)",'g');// rf = new RegExp("((\#)([\#]+))",'g'), r = 0; //Pas necessaire
//    var rn1 = /(\#)(?![\#\s]+)(?=.+)/g; //[DEPUIS 16-11-15] Ne prend pas en compte le fait de ne pas avoir que des chiffres
    var rn1 = /(\#)(?=(?:(?=[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż])|(?:[\d_](?=[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż])))[a-z\d_ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]+)/gi;
    var rn2 = /(\@)(?=(?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/gi;
//    var rn2 = /(\@)(?![\@\s]+)(?=.+)/g; //[DEPUIS 18-11-15]
    
//    Kxlib_DebugVars([.match(reg).length]);
    if ( $(o).hasClass("skip_sharp") ) {
        try {
            /*
             * ETAPE :
             * On détecte la présence de caractères échapés
             */
            if ( t.indexOf('#') !== -1 | t.indexOf('@') !== -1 ) {
                //Si on a détecté au moins un '#' ou un '@'
//                var t__ = t.replace(/(\#)(?![\#\s]+)(?=.+)/g,"");
        
                t = t.replace(rn1,"");
                t = t.replace(rn2,"");

//                Kxlib_DebugVars([,t.length]);
                return t.length;
//                return t.replace(rn,"$2").length;
            } else {
                return t.length;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    } else {
        return t.length;
    }
}

function CountChar_SkipHash2 (t) {
    try {
        if (! ( typeof t === "string" && t ) ) {
            return;
        }

        /*
         * Le but étant de n'échapper que les '#' qui servent à reconnaitre les hashtags
         * */
        //rn = (rNormal) Pour les cas #oeeoeoe; rn = (rFake) Pour les cas ######
        //var rn = new RegExp("(\#)([^\#|\s]+)",'g');// rf = new RegExp("((\#)([\#]+))",'g'), r = 0; //Pas necessaire
        var rn1 = /(\#)(?![\#\s]+)(?=.+)/g;
        var rn2 = /(\@)(?![\@\s]+)(?=.+)/g;
    
//    Kxlib_DebugVars([.match(reg).length]);
  
        /*
         * ETAPE :
         * On détecte la présence de caractères échapés
         */
        if ( t.indexOf('#') !== -1 | t.indexOf('@') !== -1 ) {
            //Si on a détecté au moins un '#' ou un '@'
//                var t__ = t.replace(/(\#)(?![\#\s]+)(?=.+)/g,"");

            t = t.replace(rn1,"");
            t = t.replace(rn2,"");

//                Kxlib_DebugVars([,t.length]);
            return t.length;
//                return t.replace(rn,"$2").length;
        } else {
            return t.length;
        }
    } catch (ex) {
        Kxlib_DebugVars([ex,ex.lineNumber],true);
    }
}


function CountChar_HandleTypeInInput (e) {
//    alert(e.keyCode);
//    return;
    //*
    //    var nb = $(this).val().length || $(this).text().length - 45;
    
    var t = $(this), _nb = 0, _max = $(this).data("maxch"), _tarSel = Kxlib_ValidIdSel($(this).data("target"));
    
    _nb = CountChar_SkipHash(t);
    //var _newNb;
//    var _hash = CountChar_IsHash (e);
//    Kxlib_DebugVars([retrun : "+_nb]);
    var _newNb = _max - _nb;
    /*
//    if ( _hash ) {
    if ( e.type === "keyup" && e.which === 51 ) {
       _newNb += 1;
        Kxlib_DebugVars([hash" ]);
    } else {
        
    }
    //*/
    if( _newNb < 0 ) $(_tarSel).addClass("red");
    else $(_tarSel).removeClass("red");
    
    $(_tarSel).html(_newNb);
    //*/
}

//L'usage du Blur()ou Focus() sert entre autre a activé le comptage à distance sans rentrer une valeur
$(".check_char").bind("keyup keydown focus blur change",CountChar_HandleTypeInInput);

/********************************************************************************************************/
/********************************************************************************************************/
//HANDLE REVERSE TEXT
function Revs_HandleClick (e) {
    $obj = $(e.target);
    
    var _rvTxt = $obj.data("revs");
//    Kxlib_DebugVars([Jusqu'ici tout va bien"]);
    //*
    if (! KgbLib_CheckNullity(_rvTxt) ) {
//        Kxlib_DebugVars([Jusqu'ici tout va bien"]);
//       alert(_rvTxt); 
        var _txt = $obj.html();
        
        $obj.data("revs",_txt);
        $obj.html(_rvTxt);
//        alert("Reverse : "+$obj.data("revs")); 
//        alert("Value : "+$obj.html()); 
    }
    //*/
}
    
/* On ne peut pas uiliser cet Handler car il se declenche toujours pas le blur d'Action.
 * Il faut donc utiliser un Handler de souris pour contourner le problème   
$("a.kgb_el_can_revs").click(function(e){
    //Kxlib_PreventDefault(e);
    // e.stopPropagation();
    Kxlib_DebugVars([yo!']);
    //Revs_HandleClick(this);
});
*/

/****************************** COM LISTENERS ***************************/
$(".back_to_60s a").click(function(e){
    Kxlib_PreventDefault(e);

    $("#toptop").animatescroll({element:'.in_slave_list',padding:20});
    $(".in_slave_list").scrollTop(0);
    $(".in_slave_list").perfectScrollbar();
    $(".in_slave_list").perfectScrollbar("update");
});
    
/******************************* RESET FORM **************************/

$(".kxlib-reset-form").click(function(e){
    Kxlib_PreventDefault(e);
    try {
        var tar = $(this).data("target");
        Kxlib_ResetForm(tar);
    } catch(e) {
        //TODO : Envoyer l'erreur au serveur
        return;
    }
            
});

/******************************** CLOSE ********************************/
$(".jb-kxlib-close").click(function(e){
    Kxlib_PreventDefault(e);
    
    $(Kxlib_ValidIdSel($(this).data("target"))).addClass("this_hide");
});

/****************************** OTHERS *******************************/
$("a.this_noway, a.this_noway *").click(function(e){
    Kxlib_PreventDefault(e);
});


/****************************** CNXSGN OVLY *******************************/

$(".jb-cnxsgn-o-ws-tgr").click(function(e){
    Kxlib_PreventDefault(e);
    
    var h = $(".jb-cnxsgn-o-whysgn-mx").height();
    
    var t__;
    if ( $(".jb-cnxsgn-o-whysgn-mx").css("top") === "0px" | $(this).hasClass("closed") ) {
        t__ = h*-1;
        $(this).switchClass("closed","open");
    } else  {
        t__ = "0px";
        $(this).switchClass("open","closed");
    }
        
    $(".jb-cnxsgn-o-whysgn-mx").stop(true,true).animate({
        top : t__
    });
});

$(".jb-asdr-w-ads-wa-lk").click(function(e){
    Kxlib_PreventDefault(e);

    $(".jb-cnxsgn-ovly-sprt").removeClass("this_hide");
});

/****************************** ARTICLE SCOPE *******************************/
//console.log($(".fcb_img_maximus").length);
/*
$(".fcb_img_maximus").hover(function(){
    $(this).children("span").toggleClass("soft_fade");
    $(this).children("span").toggleClass("hard_fade");
    $(this).children(".bot_fade").toggleClass("bot_fade_sp");
});
//*/

/*
$(".fcb_img").off("hover").hover(function(){
            
    $(this).children(".fcb_img_link").toggleClass("fcb_img_link_hover");
    $(this).parent().children(".bot_fade").toggleClass("bot_fade_sp");
    $(this).parent().parent().children().find(".mdl-a-p-r-cat-p").toggleClass("mdl-a-p-r-cat-p-full");
    
    /*
     * [DEPUIS 23-11-15] @author BOR
     *      On gère le cas des bouton de partage sur les réseaux sociaux.
     */
    /*
    if ( $(this).has(".jb-tqr-artmdl-shron-tgr").length ) {
        if ( $(this).find(".jb-tqr-artmdl-shron-tgr").first().hasClass("this_hide") ) {
            $(this).find(".jb-tqr-artmdl-shron-tgr").removeClass("this_hide");
        } else {
            $(this).find(".jb-tqr-artmdl-shron-tgr").addClass("this_hide")
        }
    }
    //*
    
//    var $par = $(this).closest(".fcb_img_maximus");
    var $par = $(this).closest(".fcb_img");
    if ( $par.find(".jb-tqr-am-ax-box").length ) {
        if ( $par.find(".jb-tqr-am-ax-box").hasClass("this_hide") ) {
            $par.find(".jb-tqr-am-ax-box").removeClass("this_hide");
        } else {
            $par.find(".jb-tqr-am-ax-box").addClass("this_hide");
        }
    }
});
//*/

/*
 * [DEPUIS 10-05-16]
 */
$(".fcb_img").off("hover").hover(function(e){
    $(this).children(".fcb_img_link").addClass("fcb_img_link_hover");
    $(this).parent().children(".bot_fade").addClass("bot_fade_sp");
//    $(this).parent().parent().children().find(".mdl-a-p-r-cat-p").toggleClass("mdl-a-p-r-cat-p-full");
    
    var $par = $(this).closest(".fcb_img");
    /*
     * [DEPUIS 02-06-16]
     */
    if ( $par.find(".jb-tqr-artml-onhvr-tm").length ) {
        $par.find(".jb-tqr-artml-onhvr-tm").removeClass("this_hide");
    }
    if ( $par.find(".jb-tqr-am-ax-box").length ) {
        $par.find(".jb-tqr-am-ax-box").removeClass("this_hide");
    }
},function(e){
    $(this).children(".fcb_img_link").removeClass("fcb_img_link_hover");
    $(this).parent().children(".bot_fade").removeClass("bot_fade_sp");
//    $(this).parent().parent().children().find(".mdl-a-p-r-cat-p").toggleClass("mdl-a-p-r-cat-p-full");
    
    var $par = $(this).closest(".fcb_img");
    /*
     * [DEPUIS 02-06-16]
     */
    if ( $par.find(".jb-tqr-artml-onhvr-tm").length ) {
        $par.find(".jb-tqr-artml-onhvr-tm").addClass("this_hide");
    }
    if ( $par.find(".jb-tqr-am-ax-box").length ) {
        $par.find(".jb-tqr-am-ax-box").addClass("this_hide");
    }
});


/*
 * [DEPUIS 11-09-15] @author BOR
 */
$(".jb-tmlnr-loadm-trg[data-scp='ml'], .jb-tmlnr-loadm-trg[data-scp='tr'], .jb-tmlnr-loadm-trg[data-scp='fv']").hover(function(){
    $(this).closest(".jb-nwfd-loadm-box.tmlnr").addClass("hover");
},function(){
    $(this).closest(".jb-nwfd-loadm-box.tmlnr").removeClass("hover");
});
$(".jb-trpg-loadm-trg").hover(function(){
    $(this).closest(".jb-trpg-loadm-box").addClass("hover");
},function(){
    $(this).closest(".jb-trpg-loadm-box").removeClass("hover");
});

/*
 * [DEPUIS 19-09-15] @author BOR
 */
if ( $("div[s-id='TQR_WLC_INS']").length ) {
    $(".jb-tqr-why-signup-ftr-mxe").remove();
}
try {


    //alert(PgEnv && ( $.inArray(PgEnv.pgvr.toUpperCase(),["WU","WLC"]) !== -1) );
//    alert(( typeof PgEnv !== "undefined" && ( $.inArray(PgEnv.pgvr.toUpperCase(),["WU","WLC"]) !== -1) ) || ( $("div[s-id='TQR_WLC_CNX']").length ));
//    alert(( !KgbLib_CheckNullity(PgEnv) && ( $.inArray(PgEnv.pgvr.toUpperCase(),["WU","WLC"]) !== -1) ) || ( $("div[s-id='TQR_WLC_CNX']").length ));
    var btm = $(".jb-tqr-why-signup-bmx").outerHeight() - $(".jb-tqr-why-signup-hdr-mx").outerHeight();
    if ( ( typeof PgEnv !== "undefined" && ( $.inArray(PgEnv.pgvr.toUpperCase(),["WU","WLC"]) !== -1) ) || ( $("div[s-id='TQR_WLC_CNX']").length ) ) {
        $(".jb-tqr-why-signup-bmx").animate({
            bottom: (-1)*btm
        },function(){
            $(this).removeClass("this_invi");
            $(".jb-tqr-dntmiss-bmx").stop(true,true).animate({
                bottom: $(".jb-tqr-dntmiss-bmx").height()+8
            });
        });
    } else {
        $(".jb-tqr-why-signup-bmx").remove();
    }

    $(".jb-tqr-why-sgu-tle-tgr").click(function(e){
        Kxlib_PreventDefault(e);

        if ( $(".jb-tqr-why-signup-bmx").data("stt") === "hell" ) {
           /*
            * ETAPE :
            *      Eviter que l'utilisateur pense qu'il s'agit du bouton pour refermer la zone
            */
    //        $(".jb-tqr-why-sgu-clz").addClass("this_hide");
            $(".jb-tqr-why-signup-bmx").stop(true,true).animate({
                bottom: 0
            },function(){
                $(this).data("stt","heaven");
                $(".jb-tqr-dntmiss-bmx").stop(true,true).animate({
                    bottom: 8
                });
            });
        } else {
            var btm = $(".jb-tqr-why-signup-bmx").outerHeight() - $(".jb-tqr-why-signup-hdr-mx").outerHeight();
            $(".jb-tqr-why-signup-bmx").stop(true,true).animate({
                bottom: (-1)*btm
            },function(){
                $(this).data("stt","hell");
    //            $(".jb-tqr-why-sgu-clz").removeClass("this_hide");
                $(".jb-tqr-dntmiss-bmx").stop(true,true).animate({
                    bottom: $(".jb-tqr-dntmiss-bmx").height()+8
                });
            });
        }
    });
    $(".jb-tqr-why-sgu-clz").click(function(e){
        Kxlib_PreventDefault(e);

        if ( $(".jb-tqr-why-signup-bmx").data("stt") === "heaven" ) {
           var btm = $(".jb-tqr-why-signup-bmx").outerHeight() - $(".jb-tqr-why-signup-hdr-mx").outerHeight();
            $(".jb-tqr-why-signup-bmx").stop(true,true).animate({
                bottom: (-1)*btm
            },function(){
                $(this).data("stt","hell");
                $(".jb-tqr-dntmiss-bmx").stop(true,true).animate({
                    bottom: $(".jb-tqr-dntmiss-bmx").height()+8
                });
            });
        } else {
            $(".jb-tqr-why-signup-bmx").stop(true,true).fadeOut(1000).remove();
            $(".jb-tqr-dntmiss-bmx").stop(true,true).animate({
                bottom: 8
            });
        }

    });

    $(".jb-tqr-btm-lock-clz").click(function(e){
        Kxlib_PreventDefault(e);

        $(".jb-tqr-btm-lock-fd").stop(true,true).fadeOut(function(){
            $(this).addClass("this_hide").removeAttr("style");
        });
        $(".jb-tqr-btm-lock").stop(true,true).fadeOut(function(){
            $(this).addClass("this_hide").removeAttr("style");
        });
    });
} catch (ex) {
    alert(ex);
}
/*********************************************************************************************************************************************************/
/****************************************************************** EXTERNAL FRMK SCOPE ******************************************************************/
/*********************************************************************************************************************************************************/


/*\
|*|
|*|  :: cookies.js ::
|*|
|*|  A complete cookies reader/writer framework with full unicode support.
|*|
|*|  Revision #1 - September 4, 2014
|*|
|*|  https://developer.mozilla.org/en-US/docs/Web/API/document.cookie
|*|  https://developer.mozilla.org/User:fusionchess
|*|
|*|  This framework is released under the GNU Public License, version 3 or later.
|*|  http://www.gnu.org/licenses/gpl-3.0-standalone.html
|*|
|*|  Syntaxes:
|*|
|*|  * docCookies.setItem(name, value[, end[, path[, domain[, secure]]]])
|*|  * docCookies.getItem(name)
|*|  * docCookies.removeItem(name[, path[, domain]])
|*|  * docCookies.hasItem(name)
|*|  * docCookies.keys()
|*|  -------------------------------------------------------------------
|*|  Par Lou Carther    
|*|  Revision #1 - July 4, 2015
|*|  
|*|  * docCookies.getAllItems([JSONmode]) 
|*|
\*/

var docCookies = {
  getItem: function (sKey) {
    if (!sKey) { return null; }
    return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
  },
  setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
    if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
    var sExpires = "";
    if (vEnd) {
      switch (vEnd.constructor) {
        case Number:
          sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
          break;
        case String:
          sExpires = "; expires=" + vEnd;
          break;
        case Date:
          sExpires = "; expires=" + vEnd.toUTCString();
          break;
      }
    }
    document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
    return true;
  },
  removeItem: function (sKey, sPath, sDomain) {
    if (!this.hasItem(sKey)) { return false; }
    document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
    return true;
  },
  hasItem: function (sKey) {
    if (!sKey) { return false; }
    return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
  },
  keys: function () {
    var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
    for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) { aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]); }
    return aKeys;
  },
  getAllItems: function (JSONmode) {
      if (JSONmode&&document.cookie){
        var aKeys = this.keys(), vlky = {};
        for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) { vlky[decodeURIComponent(aKeys[nIdx])] = this.getItem(decodeURIComponent(aKeys[nIdx])); }
        return vlky;
      }else{
          return document.cookie;
      }
  }
};


(function(){
    
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Action = function(x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a : $(x).data("action"); 
            switch (_a.toLowerCase()) {
                case "sprt-close" :
                        _f_IO(x,false);
                    break;
                case "sprt-open" :
                        _f_IO(x,true);
                    break;
                default : 
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_IO = function (x,o) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( o ) {
                $(".jb-tqr-fly-room-sprt").removeClass("this_hide");
            } else {
                $(".jb-tqr-fly-room-sprt").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVER SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
            
            
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** VIEW SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    $(".jb-hm1-menus-ch[data-menu='jump'], .jb-h-c-b-explo-btn").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this,"sprt-open");
    });
    
    $(".jb-tqr-fly-room-clz").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $(".jb-tqr-fly-room-l-blc-a").hover(function(e){
        $(this).find(".jb-tqr-fly-room-l-blc-shw-n-smr").stop(true,true).delay(300).animate({
            top: "-150px"
        });
    },function(e){
        $(this).find(".jb-tqr-fly-room-l-blc-shw-n-smr").stop(true,true).delay(100).animate({
            top: "0"
        });
    });
    
    
})();