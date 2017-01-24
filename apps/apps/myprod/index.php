<?php 

?>
<html>
    <head>
        <link  rel="stylesheet" type="text/css" href="../../css/myprod/style.css">
        <!--<link  rel="stylesheet" type="text/css" href="../../css/myprod/jquery-ui.min.css">-->
        <link  rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    </head>
    <body>
        <div id="myprod-page">
            <div id="myprod-stats-panel">
                <div id="mypd-stats-pnl">
                    <span id="mypd-stats-pnl-wait" class="jb-mypd-stats-pnl-wait this_hide">Patientez...</span>
                    <div id="mypd-stats-pnl-tle">Panneau de controle</div>
                    <div id="mypd-stats-pnl-frm">
                        <form>
                            <span class="mypd-stats-pnl-dtpkr-mx">
                                <input id="" class="mypd-stats-pnl-dtpkr jb-mypd-stats-pnl-dtpkr" data-purpose="start" type="text" placeholder="Debut" maxlength="70" >
                                <select class="jb-mypd-sta-pnl-dtpkr-slct" data-purpose="start">
                                    <option value="0">00:00 AM</option>
                                    <option value="1">01:00 AM</option>
                                    <option value="2">02:00 AM</option>
                                    <option value="3">03:00 AM</option>
                                    <option value="4">04:00 AM</option>
                                    <option value="5">05:00 AM</option>
                                    <option value="6">06:00 AM</option>
                                    <option value="7">07:00 AM</option>
                                    <option value="8">08:00 AM</option>
                                    <option value="9">09:00 AM</option>
                                    <option value="10">10:00 AM</option>
                                    <option value="11">11:00 AM</option>
                                    <option value="12">12:00 AM</option>
                                    <option value="13">13:00 PM</option>
                                    <option value="14">14:00 PM</option>
                                    <option value="15">15:00 PM</option>
                                    <option value="16">16:00 PM</option>
                                    <option value="17">17:00 PM</option>
                                    <option value="18">18:00 PM</option>
                                    <option value="19">19:00 PM</option>
                                    <option value="20">20:00 PM</option>
                                    <option value="21">21:00 PM</option>
                                    <option value="22">22:00 PM</option>
                                    <option value="23">23:00 PM</option>
                                </select>
                            </span>    
                            <span class="mypd-stats-pnl-dtpkr-mx" data-target='end'>
                                <input id="" class="mypd-stats-pnl-dtpkr jb-mypd-stats-pnl-dtpkr" data-purpose="end" type="text" placeholder="Fin" maxlength="70" disabled="true">
                                <select class="jb-mypd-sta-pnl-dtpkr-slct" data-purpose="end" disabled="true">
                                    <option value="0">00:00 AM</option>
                                    <option value="1">01:00 AM</option>
                                    <option value="2">02:00 AM</option>
                                    <option value="3">03:00 AM</option>
                                    <option value="4">04:00 AM</option>
                                    <option value="5">05:00 AM</option>
                                    <option value="6">06:00 AM</option>
                                    <option value="7">07:00 AM</option>
                                    <option value="8">08:00 AM</option>
                                    <option value="9">09:00 AM</option>
                                    <option value="10">10:00 AM</option>
                                    <option value="11">11:00 AM</option>
                                    <option value="12">12:00 AM</option>
                                    <option value="13">13:00 PM</option>
                                    <option value="14">14:00 PM</option>
                                    <option value="15">15:00 PM</option>
                                    <option value="16">16:00 PM</option>
                                    <option value="17">17:00 PM</option>
                                    <option value="18">18:00 PM</option>
                                    <option value="19">19:00 PM</option>
                                    <option value="20">20:00 PM</option>
                                    <option value="21">21:00 PM</option>
                                    <option value="22">22:00 PM</option>
                                    <option value="23">23:00 PM</option>
                                </select>
                            </span>
                            <button class="jb-launch" type="button">Rechercher</button>
                            <input type="reset" />
                        </form>
                    </div>
                    <div id="mypd-stats-pnl-ftr">
                        <!-- 
                            [DEPUIS 02-09-15] @author BOR
                            Ne sert à rien en l'état.
                        -->
                        <!--<button type="button">Actualiser les données</button>-->
                    </div>
                </div>
            </div>
            <table id="big-sam">
                <td class="big-sam-tr" colspan="3">STATISTIQUES D'ERREURS</td>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_err_gen_bgzy_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc" >Nombre d'erreurs signalées (manuellement)</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info" ><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span class="red">0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_err_gen_auto_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre d'erreurs détectées (automatique)</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span class="red">0</span></td> 
                </tr>
                <td class="big-sam-tr" colspan="3">STATISTIQUES DE COMPTES</td>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_gen_acc_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre de comptes enregistrés</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_atv_acc_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre de comptes actifs</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_zmb_acc_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre de comptes zombies</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_ded_acc_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre de comptes désactivés</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <td class="big-sam-tr" colspan="3">STATISTIQUES DE TENDANCES</td>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_gen_trd_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Tendances</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_atv_trd_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Tendances actifs</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_zmb_trd_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Tendances zombies</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_ded_trd_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Tendances désactivées</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <td class="big-sam-tr" colspan="3">STATISTIQUES D'ACTIVTE</td>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_gen_rct_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de commentaires</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_gen_art_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de publications</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_ustg_art_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de tags utilisateur dans les publications</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_ustg_rct_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de tags utilisateur dans les commentaires</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_gen_evl_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total d'évaluations</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_evl_actv_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total d'évaluations actifs</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_gen_mi">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Messages</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_mi_miorph">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Messages Mi-Orphelins</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_gen_rel_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Relations</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_rel_frds_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Relations Amis</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_rel_sfol_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Relations S_FOLW</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_rel_dfol_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Relations D_FOLW</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="simple" data-target="stats_acty_rel_void_cn">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">Nombre total de Relations terminées</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data"><span>0</span></td> 
                </tr>
                <td class="big-sam-tr" colspan="3">BEST PLAYERS : COMPTES</td>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-acc">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures comptes par nombre de points (Capital)</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Age</th>
                                <th>Sexe</th>
                                <th>Ville</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Capital</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-acc">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures comptes par nombre d'Abonnés</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Age</th>
                                <th>Sexe</th>
                                <th>Ville</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Nb. Abonnés</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>  
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-acc">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures comptes par nombre d'Abonnements</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Age</th>
                                <th>Sexe</th>
                                <th>Ville</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Nb. d'Abnmts</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>  
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-acc">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures comptes par nombre de publications</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Age</th>
                                <th>Sexe</th>
                                <th>Ville</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Nb. Pub.</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>  
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-acc">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures comptes par nombre de commentaires</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Age</th>
                                <th>Sexe</th>
                                <th>Ville</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Nb. Cmt.</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>  
                </tr>
                <td class="big-sam-tr" colspan="3">BEST PLAYERS : TENDANCES</td>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-tr">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures Tendances par nombre de Publications</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Admin</th>
                                <th>Sexe</th>
                                <th>Depuis</th>
                                <th>Accès</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Nb. Pub.</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-tr">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures Tendances par nombre de points (Capital)</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Admin</th>
                                <th>Depuis</th>
                                <th>Sexe</th>
                                <th>Accès</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Capital</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-tr">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures Tendances par nombre d'Abonnés</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Admin</th>
                                <th>Depuis</th>
                                <th>Sexe</th>
                                <th>Accès</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Nb. Abo.</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td> 
                </tr>
                <tr class="bgsm-datarow-mx jb-bgsm-datarow-mx" data-type="compound" data-scp="best-of-tr">
                    <td class="bgsm-datarow-desc jb-bgsm-datarow-desc">10 Meilleures Tendances par nombre de Commentaires</td>
                    <td class="bgsm-datarow-info jb-bgsm-datarow-info"><a class="bgsm-tr-info" href="javascript:;"><span>info</span><span class="bgsm-tr-info-txt this_hide">[TEXTE]</span></a></td>
                    <td class="bgsm-datarow-data jb-bgsm-datarow-data">
                        <table>
                            <tr>
                                <th>Admin</th>
                                <th>Depuis</th>
                                <th>Sexe</th>
                                <th>Accès</th>
                                <th>Pays</th>
                                <th>Lien vers</th>
                                <th>Nb. Cmt.</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td> 
                </tr>
            </table>
        </div>
        <div id="js-declare">
<!--            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
            <script src="//code.jquery.com/jquery-1.10.2.js"></script>
            <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
            <script src="../../js/myprod/jquery.ui.datepicker-fr.js"></script>
        
            <script src="../../js/myprod/env.vars.js?<?php echo time(); ?>"></script>
            <script src="../../js/myprod/ajax_rules.js?<?php echo time(); ?>"></script>
            <script src="../../js/myprod/kxlib.js?<?php echo time(); ?>"></script>
            <script src="../../js/myprod/kxdate.enty.js?<?php echo time(); ?>"></script>
            <script src="../../js/myprod/fr.dolphins.js?<?php echo time(); ?>"></script>
            <script src="../../js/myprod/script.js?<?php echo time(); ?>"></script>
        </div>
    </body>
</html>