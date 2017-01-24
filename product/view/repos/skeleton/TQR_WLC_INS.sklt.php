<?php 
    $pgid = "{wos/datx:pagid}";
    $pgvr = "{wos/datx:pgakxver}";
    $sector = "{wos/datx:sector}";
    
    $rnlg = "{wos/datx:runlang}";
    
    /*
     * ETAPE :
     *      On détermine si on est dans le cas où il faut demander à l'utilisateur de remplir des données supplémentaires les données.
     *      En effet, ce cas signale que l'utilisateur a préalablement rentré des données au niveau d'un formulaire de PRE-INSCRIPTION ou via l'API de FACEBOOK.
     */
    $GOTO_STEP_2 = ( $sector && in_array($sector, ["ENTERCZ_PREFORM","ENTERCZ_INSAPI_FB"]) ) ? TRUE : FALSE;
        
    set_error_handler('exceptions_error_handler');
    try {
        $TEMP_INS_PREFORM = "{wos/datx:INS_PREFORM}";
        if ( $TEMP_INS_PREFORM ) {
            $INS_PREFORM = unserialize(base64_decode($TEMP_INS_PREFORM));
        }
        
        $TEMP_INS_WAPI_FB = "{wos/datx:INS_WAPI_FB}";
        if ( $TEMP_INS_WAPI_FB ) {
            $INS_WAPI_FB = unserialize(base64_decode($TEMP_INS_WAPI_FB));
        }
        
        if ( $INS_PREFORM ) {
            $fullname = $INS_PREFORM["user_name"];
            $pseudo = $INS_PREFORM["user_pseudo"];
            $email = $INS_PREFORM["user_email"];
            $password = $INS_PREFORM["user_pass"];
        } else if ( $INS_WAPI_FB["USER_DATAS"] && $sector !== "ENTERCZ_ACTIVE_FB_SSN" ) {
            
            $INS_WAPI_FB_2 = $INS_WAPI_FB["USER_DATAS"];
                    
            $fullname = $INS_WAPI_FB["USER_DATAS"]["user_name"];
            $borndate = $INS_WAPI_FB["USER_DATAS"]["user_bdate"];
            if ( $borndate && is_string($borndate) ) {
                $foo = explode("-",$borndate);
                $d = $foo[2];
                $m = $foo[1];
                $y = $foo[0];
                
                $borndate = [];
                $borndate["day"] = $d;
                $borndate["month"] = $m;
                $borndate["year"] = $y;
            }
            $gender = $INS_WAPI_FB["USER_DATAS"]["user_gender"];
            $email = $INS_WAPI_FB["USER_DATAS"]["user_email"];
        }
        
        
//        var_dump(__LINE__,$pgid,$pgvr,$sector);
//        var_dump(__LINE__,$INS_PREFORM,$INS_WAPI_FB);
//        var_dump(__LINE__,[$fullname,$borndate,$gender,$pseudo,$email,$password]);
//        exit();
        restore_error_handler();

    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
?>
<div s-id="TQR_WLC_INS">
<!--    <div class='jsw_main_warning' style="margin-top: 130px;">
        <div class='jsw_title'>Maintenance en cours</div>
        <div class='jsw_sub' style="font-size: 20px;">Suite à une mise à jour, cette page est momentanément indisponible. Le problème sera résolu dans quelques minutes. Veuillez nous en excuser.</div>
    </div>
-->
    <!--
    <div id="ins-famous-sprt" class="jb-ins-famous-sprt this_hide">
        <div id="ins-famous-mx" class="">
            <div id="ins-fams-tle">
                 <h1>HELLO !</h1>
            </div>
            <p>
               <span class="ins-famous-tqr">Trenqr</span> est une jeune plateforme. A ce stade, nous ne fournissons aucun compte spécialisé destiné aux personnes morales ou aux personnes physiques célèbres.  
               A ce titre, si vous créez un compte il sera assimilé à un compte standard. 
            </p>
            <p>
                Si un compte standard ne convient pas à vos activités, vous devriez tout de même créer un compte afin de protéger le nom  de votre marque. Cela, en attendant que la question soit résolue.
            </p>
            <div id="ins-fams-notes-mx">
                <h2 id="ins-fams-notes-tle">Remarques importantes</h2>
                <p>
                    Notre politique de lutte contre l'usurpation d'identité et pour la protection intellectuelle, entraine que certains noms d'utilisateurs peuvent être indisponibles. 
                    Par exemple, si vous essayez d’utiliser à tord des pseudos faisant référence à une personne connue telle que <q class="bold">@XavierNiel</q>, <q class="bold">@ElonMusk</q>, <q class="bold">@Rihanna</q> ou <q class="bold">@RichardBrandon</q>, votre demande pourrait être rejetée ou rectifiée ultérieurement.
                </p>
                <p id="ins-fams-notes-xpln">
                    Cette liste fictive ne reflète sans doute pas la réalité. Aussi, les noms cités ont été pris à titre d'exemple, de façon arbitraire.
                </p>
                <h2 id="ins-sprt-notes-tle">Support</h2>
                <p>
                    Pour toute autre demande relative à des pseudos vérrouillés ou aux personnes morales ou célèbres, vous pouvez contacter l’équipe Support. Celle-ci vous répondra dès que des informations seront disponibles.
                </p>   
                <h2 id="ins-fams-nwsltr-tle">Se tenir au courant</h2>
                <p>
                    Si vous souhaitez recevoir automatiquement des informations sur la résolution de cette question, vous pouvez vous abonnez à la newsletter. Pour ce faire, veuillez rentrer votre email dans le champ ci-dessous.
                </p>
                <div id="ins-fams-nwsltr-ipt-mx">
                    <input id="ins-fams-nwsltr-ipt" class="" type="email" placeholder="Votre email de contact"/>
                    <a id="ins-fams-nwsltr-go" class="lock jb-ins-fams-nwsltr-go" href="" title="Restez au courant" >Valider</a>
                </div>
                <div id="ins-fams-nwsltr-lg-mx">
                    <img width="70" height=70" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti.png" />
                </div>
            </div>
        </div>
    </div>
    -->
    {wos/dvt:header_default}
    <div id="ins_theater"></div>
    <div id="ins_screensize">
        <div id="ins-final-mx" class="jb-ins-final-mx this_hide">
            <div id="ins-final-spnr" class="jb-ins-fnl-spnr">
                <img src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
            </div>
            <div id="ins-final-spnr-msg" class="jb-ins-fnl-spnr-msg">
                <span id="ins-final-wait" class="jb-ins-final-wait">{wos/deco:_PG_SIGNUP_TX029}</span>
                <span id="ins-final-ourah" class="jb-ins-final-ourah this_hide"></span>
            </div>
            <ol id="ins-final-tasks-mx" class="ins-final-mb-mx">
                <h2 class="ins-final-mb-tle">{wos/deco:_PG_SIGNUP_PRCSG_TX001}</h2>
                <li class="ins-final-tks-this jb-ins-fnl-tks-this" data-target="form">
                    <span class="ins-final-tks-ol">1.</span> {wos/deco:_PG_SIGNUP_PRCSG_TX002}<span class="ins-fnl-tks-pndg">... </span>
                    <span class="ins-fnl-tks-spnr jb-ins-fnl-tks-spnr"><img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" /></span>
                    <span class="ins-fnl-tks-decs jb-ins-fnl-tks-decs this_hide"></span>
                </li>
                <li id="ins-fail-form" class="ins-fail-box jb-ins-fail-form this_hide">
                </li>
                <li class="ins-final-tks-this jb-ins-fnl-tks-this" data-target="ins_process">
                    <span class="ins-final-tks-ol">2.</span> {wos/deco:_PG_SIGNUP_PRCSG_TX003}<span class="ins-fnl-tks-pndg this_hide">... </span>
                    <span class="ins-fnl-tks-spnr jb-ins-fnl-tks-spnr this_hide">
                        <img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                    </span>
                    <span class="ins-fnl-tks-decs jb-ins-fnl-tks-decs this_hide"></span>
                </li>
                <li id="ins-fail-final" class="ins-fail-box jb-ins-fail-final this_hide">
                </li>
            </ol>
            <div id="ins-final-chcs-mx" class="ins-final-mb-mx jb-ins-fnl-next-mx this_hide">
                <h2 class="ins-final-mb-tle">{wos/deco:_PG_SIGNUP_PRCSG_TX005}</h2>
                <a class="ins-final-chc cursor-pointer" data-action="invite-friend" href="/!/recommend-trenqr-image-trend-cool-community" >{wos/deco:_PG_SIGNUP_PRCSG_TX006}</a>
                <a class="ins-final-chc cursor-pointer" data-action="popular-trend" href="" >{wos/deco:_PG_SIGNUP_PRCSG_TX007}</a>
                <a class="ins-final-chc cursor-pointer jb-ins-fnl-nxt-trg" data-action="go-home">{wos/deco:_PG_SIGNUP_PRCSG_TX008}</a>
            </div>
        </div>
        <div id="ins_content" class="jb-ins-form-mx">
            <div id="" style="width: 578px">
                <div id="ins_left">
                    <div id="ins_tooltip_wrapper">
                        <div id="ins_passwd_tooltip"><span class="clear"></span></div>
                        <span class="clear"></span>
                    </div>
                    <div id="ins_title" class="jb-ins-tle">
                        <div id="ins_title_content">
                            {wos/deco:_PG_SIGNUP_TX001}
                        </div>
                    </div>  
                    <?php if ( in_array($sector,["ENTERCZ_DIRECT","ENTERCZ_ACTIVE_FB_SSN"]) ) : ?>
                    <div id="ins-with-api-bmx" class="jb-ins-with-api-bmx ">
                        <div id="ins-with-api-mx">
                            <div id="ins-with-api-tle">
                                {wos/deco:_PG_SIGNUP_TX002}
                            </div>
                            <div id="ins-with-api-btns">
                                <a class="ins-with-api-btn" data-target="facebook" href="<?php 
                                    if ( $INS_WAPI_FB && isset($INS_WAPI_FB["LoginUrl"]) ) {
                                        echo htmlspecialchars($INS_WAPI_FB["LoginUrl"]); 
                                    }
                                ?>">facebook</a>
                            </div>
                        </div>
                        <div id="ins-which-type-sep-mx">
                            <div id="ins-which-type-sep">{wos/deco:_PG_SIGNUP_TX003}</div>
                            <div id="ins-which-type-is2" class="">
                                {wos/deco:_PG_SIGNUP_TX004}
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div id="ins-semi-form-hdr" class="jb-ins-semi-form-hdr this_hide">
                        <div id="ins-semi-form-h-tle" >Bonjour <span id="ins-semi-form-h-tle-fn"><?php echo ( $fullname ) ? : "" ?></span>, bienvenue :) !</div> 
                        <div id="ins-semi-form-h-subtle">Veuillez <span class="bold u">compléter</span> ou <span class="bold u">corriger</span> vos informations pour finaliser votre inscription</div>
                    </div>
                    <!-- 
                        [NOTE 28-05-16]
                            Il faut bien laisser autocomplete="off" sinon cela pourrait entrainer des erreurs.
                            En effet, les evenements ne se déclencheront plus normalement si l'utilisateur choisit une donnée de CACHE.
                            Cela aura des effets desastreux sur la vérification des champs selon le modèle établi.
                    -->
                    <form id="ins_form" action="/signup" method="POST" autocomplete="off">
                        <div id="ins_right" class="jb-ins-right-box">
                            <!--<div id="ins_right_top" class="jb-ins-infos-mx this_hide">-->
<!--                            <div id="ins_right_top" class="jb-ins-infos-mx ">
                                <p id="ins_info_msg">
                                    <span class="jb-ins-infos-msg">
                                        Bienvenue sur la planète Trenqr. Ou Presque …<br/><br/>Cliquez sur le champ pour savoir pourquoi nous demandons ces informations.<br/>
                                    </span>
                                    <span id="ins-infos-msg-mr" class="jb-ins-infos-msg-mr">
                                        Cette courte étape ne prend en moyenne que <b style="color: orange">3 minutes</b>.<br/> Pour chaque section, des informations seront disponibles dans cette fenêtre, afin de vous guider. <br/><br/> Si cela vous prend plus de 3 minutes, ne vous inquiétez pas, ça restera entre nous !
                                    </span>
                                </p>
                                <div id="ins-r-t-btm">
                                    <a id="ins-r-t-btm-mr" class="jb-ins-mr-trg cursor-pointer" >Voir Plus</a>
                                </div>
                                <a id="ins-r-t-btm-mr-rst" class="jb-ins-mr-rst-trg" href="">Revenir au début</a>
                            </div>
-->
                            <div id="ins_right_bottom" class="jb-ins-asd-err-mx this_hide">
                                <p id="ins_error_msg" class="jb-ins-asd-err-msg"></p>
                                <span class="clear"></span>
                            </div>

                        </div>
                        <div id="ins-form-wait-panel" class="jb-ins-form-wt-pnl this_hide">
                            <span id="ins-form-wait-panel-txt">Patientez ...</span>
                        </div>
                        <section class="ins-form-prmy-sec jb-ins-form-prmy-sec" data-scp="waiting-room">
                            <div id="ins-prmy-sec-warom-txt-prmy" >{wos/deco:_PG_SIGNUP_TX029}</div>
                            <div id="ins-prmy-sec-warom-txt-sdry" >{wos/deco:_PG_SIGNUP_TX030}</div>
                        </section>
                        <section class="ins-form-prmy-sec jb-ins-form-prmy-sec this_hide" data-scp="form">
                            <!--<div id="ins_first_grp" class="ins_group jb-ins-group jb-ins-grp-fn" data-field="fullname">-->
                            <div id="" class="ins_group jb-ins-group jb-ins-grp-fn" data-field="fullname">
                                <!--<a id="ins_famous" class="jb-ins-fams-trg" href="#">Personalit&eacute;s ou Personnes morales</a>-->
                                <div id="ins_fn_spinner" class="spinner ins_spinner"></div>
                                <div class="ins_group_label" id="label_fullname" for="ins_input_fullname">
                                    <span>{wos/deco:_PG_SIGNUP_TX005}</span>
                                    <span class="ins-vald-mark jb-ins-vald-mark this_hide" title="Ce champ est valide"></span>
                                </div>
                                <div>
                                    <!--<input id="ins_input_fullname" class="ins_group_input ins_error_lock jb-ins-com-elt" data-target="fullname" spellcheck="false" type="text" value="{wos/datx:fn}" placeholder="Nom et Prénom" required>-->
                                    <input id="ins_input_fullname" class="ins_group_input ins_error_lock jb-ins-com-elt" data-target="fullname" spellcheck="false" type="text" value="<?php echo ( $fullname ) ? : "" ?>" placeholder="{wos/deco:_PG_SIGNUP_TX006}" required>
                                </div>
                            </div>
                            <div id="ins_birthday_group" class="ins_group jb-ins-group jb-ins-grp-bdt" data-field="borndate">
                                <div class="ins_group_label" id="label_birthday">
                                    <span>{wos/deco:_PG_SIGNUP_TX007}</span>
                                    <span></span>
                                    <span class="ins-vald-mark jb-ins-vald-mark jb-ins-vald-mark-bd this_hide"></span>
                                </div>
                                <div id="ins_birthday_wrapper" class="ins_date_group ins_error_lock jb-ins-bd-mx jb-ins-com-elt" data-target="borndate_master" tabindex="2">
                                    <?php if ( $borndate["day"] && $borndate["month"] && $borndate["year"] ) : ?>
                                        <span class='jb-ins-brnd-cache this_hide'>
                                            {
                                                "bd_day"      : "<?php echo $borndate["day"]; ?>",
                                                "bd_month"    : "<?php echo $borndate["month"]; ?>",
                                                "bd_year"     : "<?php echo $borndate["year"]; ?>"
                                            }
                                        </span>
                                    <?php endif; ?>
                                    <div class='ins_select_wrapper'>
                                        <select id="day" class="ins_group_select jb-ins-bd-elt jb-ins-bd-day jb-ins-com-elt" name="birthday_day" data-target="borndate" repuired>
                                            <option value="init" selected="1">{wos/deco:_PG_SIGNUP_TX008}</option>
                                            <option value="01">1</option>
                                            <option value="02">2</option>
                                            <option value="03">3</option>
                                            <option value="04">4</option>
                                            <option value="05">5</option>
                                            <option value="06">6</option>
                                            <option value="07">7</option>
                                            <option value="08">8</option>
                                            <option value="09">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                        </select>
                                    </div>
                                    <div class='ins_select_wrapper'>
                                        <select id="month" class="ins_group_select jb-ins-bd-elt jb-ins-bd-month jb-ins-com-elt" name="birthday_month" data-target="borndate" repuired>
                                            <option value="init" selected="1">{wos/deco:_PG_SIGNUP_TX009}</option>
                                            <option value="01">{wos/deco:_PG_SIGNUP_TX032}</option>
                                            <option value="02">{wos/deco:_PG_SIGNUP_TX033}</option>
                                            <option value="03">{wos/deco:_PG_SIGNUP_TX034}</option>
                                            <option value="04">{wos/deco:_PG_SIGNUP_TX035}</option>
                                            <option value="05">{wos/deco:_PG_SIGNUP_TX036}</option>
                                            <option value="06">{wos/deco:_PG_SIGNUP_TX037}</option>
                                            <option value="07">{wos/deco:_PG_SIGNUP_TX038}</option>
                                            <option value="08">{wos/deco:_PG_SIGNUP_TX039}</option>
                                            <option value="09">{wos/deco:_PG_SIGNUP_TX040}</option>
                                            <option value="10">{wos/deco:_PG_SIGNUP_TX041}</option>
                                            <option value="11">{wos/deco:_PG_SIGNUP_TX042}</option>
                                            <option value="12">{wos/deco:_PG_SIGNUP_TX043}</option>
                                        </select>
                                    </div>
                                    <div class='ins_select_wrapper'>
                                        <select id="year" class="ins_group_select jb-ins-bd-elt jb-ins-bd-year jb-ins-com-elt" name="birthday_year" data-target="borndate" repuired>
                                            <option value="init" selected="1">{wos/deco:_PG_SIGNUP_TX010}</option>
                                            <option value="2014" >2014</option>
                                            <option value="2013" >2013</option>
                                            <option value="2012" >2012</option>
                                            <option value="2011" >2011</option>
                                            <option value="2010" >2010</option>
                                            <option value="2009" >2009</option>
                                            <option value="2008" >2008</option>
                                            <option value="2007" >2007</option>
                                            <option value="2006" >2006</option>
                                            <option value="2005" >2005</option>
                                            <option value="2004" >2004</option>
                                            <option value="2003" >2003</option>
                                            <option value="2002" >2002</option>
                                            <option value="2001" >2001</option>
                                            <option value="2000" >2000</option>
                                            <option value="1999" >1999</option>
                                            <option value="1998" >1998</option>
                                            <option value="1997" >1997</option>
                                            <option value="1996" >1996</option>
                                            <option value="1995" >1995</option>
                                            <option value="1994" >1994</option>
                                            <option value="1993" >1993</option>
                                            <option value="1992" >1992</option>
                                            <option value="1991" >1991</option>
                                            <option value="1990" >1990</option>
                                            <option value="1989" >1989</option>
                                            <option value="1988" >1988</option>
                                            <option value="1987" >1987</option>
                                            <option value="1986" >1986</option>
                                            <option value="1985" >1985</option>
                                            <option value="1984" >1984</option>
                                            <option value="1983" >1983</option>
                                            <option value="1982" >1982</option>
                                            <option value="1981" >1981</option>
                                            <option value="1980" >1980</option>
                                            <option value="1979" >1979</option>
                                            <option value="1978" >1978</option>
                                            <option value="1977" >1977</option>
                                            <option value="1976" >1976</option>
                                            <option value="1975" >1975</option>
                                            <option value="1974" >1974</option>
                                            <option value="1973" >1973</option>
                                            <option value="1972" >1972</option>
                                            <option value="1971" >1971</option>
                                            <option value="1970" >1970</option>
                                            <option value="1969" >1969</option>
                                            <option value="1968" >1968</option>
                                            <option value="1967" >1967</option>
                                            <option value="1966" >1966</option>
                                            <option value="1965" >1965</option>
                                            <option value="1964" >1964</option>
                                            <option value="1963" >1963</option>
                                            <option value="1962" >1962</option>
                                            <option value="1961" >1961</option>
                                            <option value="1960" >1960</option>
                                            <option value="1959" >1959</option>
                                            <option value="1958" >1958</option>
                                            <option value="1957" >1957</option>
                                            <option value="1956" >1956</option>
                                            <option value="1955" >1955</option>
                                            <option value="1954" >1954</option>
                                            <option value="1953" >1953</option>
                                            <option value="1952" >1952</option>
                                            <option value="1951" >1951</option>
                                            <option value="1950" >1950</option>
                                            <option value="1949" >1949</option>
                                            <option value="1948" >1948</option>
                                            <option value="1947" >1947</option>
                                            <option value="1946" >1946</option>
                                            <option value="1945" >1945</option>
                                            <option value="1944" >1944</option>
                                            <option value="1943" >1943</option>
                                            <option value="1942" >1942</option>
                                            <option value="1941" >1941</option>
                                            <option value="1940" >1940</option>
                                            <option value="1939" >1939</option>
                                            <option value="1938" >1938</option>
                                            <option value="1937" >1937</option>
                                            <option value="1936" >1936</option>
                                            <option value="1935" >1935</option>
                                            <option value="1934" >1934</option>
                                            <option value="1933" >1933</option>
                                            <option value="1932" >1932</option>
                                            <option value="1931" >1931</option>
                                            <option value="1930" >1930</option>
                                            <option value="1929" >1929</option>
                                            <option value="1928" >1928</option>
                                            <option value="1927" >1927</option>
                                            <option value="1926" >1926</option>
                                            <option value="1925" >1925</option>
                                            <option value="1924" >1924</option>
                                            <option value="1923" >1923</option>
                                            <option value="1922" >1922</option>
                                            <option value="1921" >1921</option>
                                            <option value="1920" >1920</option>
                                            <option value="1919" >1919</option>
                                            <option value="1918" >1918</option>
                                            <option value="1917" >1917</option>
                                            <option value="1916" >1916</option>
                                            <option value="1915" >1915</option>
                                            <option value="1914" >1914</option>
                                            <option value="1913" >1913</option>
                                            <option value="1912" >1912</option>
                                            <option value="1911" >1911</option>
                                            <option value="1910" >1910</option>
                                            <option value="1909" >1909</option>
                                            <option value="1908" >1908</option>
                                            <option value="1907" >1907</option>
                                            <option value="1906" >1906</option>
                                            <option value="1905" >1905</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="ins_gender_group" class="ins_group jb-ins-group jb-ins-grp-gdr" data-field="gender">
                                <div class="ins_group_label" id="label_gender">
                                    {wos/deco:_PG_SIGNUP_TX011}
                                    <span class="ins-vald-mark jb-ins-vald-mark this_hide"></span>
                                </div>
                                <div id="ins_gender_wrapper" class="ins_error_lock jb-ins-gdr-mx" tabindex="3">
                                    <div class="ins_gender_radiobutton">
                                        <input id="ins_radio_m" class="jb-ins-com-elt jb-ins-gdr-ipt" data-target="gender" name="gender" type="radio" value="m" 
                                            <?php echo ( $gender && $gender === "male" ) ? "checked"  : "" ?> >
                                        <label class="jb-ins-gdr-lab" for="ins_radio_m">{wos/deco:_PG_SIGNUP_TX012}</label>
                                    </div>
                                    <div class="ins_gender_radiobutton">
                                        <input id="ins_radio_f" class="jb-ins-com-elt jb-ins-gdr-ipt" data-target="gender" name="gender" type="radio" value="f" 
                                            <?php echo ( $gender && $gender === "female" ) ? "checked"  : "" ?> >
                                        <label class="jb-ins-gdr-lab" for="ins_radio_f">{wos/deco:_PG_SIGNUP_TX013}</label>
                                    </div>
                                </div>
                            </div>
                            <div id="ins_city" class="ins_group jb-ins-group jb-ins-grp-cty" data-field="city">
                                <div id="ins_city_spinner" class="spinner ins_spinner jb-cty-ipt-spr"></div>
                                <div id="label_city" class="ins_group_label">
                                    <span>{wos/deco:_PG_SIGNUP_TX014}</span>
                                    <span class="ins-vald-mark jb-ins-vald-mark this_hide"></span>
                                </div>
                                <div>
                                    <input id="ins_input_city" class="ins_group_input ins_error_lock jb-ins-city-ipt jb-ins-com-elt" data-target="citysrh" spellcheck="false" autocomplete="off" type="text" placeholder="{wos/deco:_PG_SIGNUP_TX015}" required>
                                </div>
                                <div id="ins-city-list-mx" class="jb-cty-smpl-list-mx jb-cty-list-mx this_hide" data-obj="smpl">
                                <!-- <div id="ins-city-list-mx" class="jb-cty-smpl-list-mx jb-cty-list-mx" data-obj="smpl"> -->
                                    <ul id="ins-city-list" class="jb-ins-city-list">
                                        <?php // for($i=0;$i<10;$i++) : ?>
        <!--                                <li class="ins-city-list-row jb-cty-list-elt" data-target="smpl" data-ci="" data-ii="">
                                            <a class="ins-city-list-trg jb-city-list-trg clearfix2" data-obj="smpl"  data-ii="" title="120 000 hab." href="">
                                                <span class="jb-cy-list-cynm">Ville</span>
                                                <span>,</span>
                                                <span class="jb-cy-list-cycn">PY</span>
                                                <span class="cy-list-cycop-mx jb-cy-list-cycop-mx">
                                                    <span class="jb-cy-list-cycop-nb">7</span>
                                                    <span class="jb-cy-list-cycop-lib"> villes</span>
                                                </span>
                                            </a>
                                        </li> -->
                                        <?php // endfor; ?>
                                    </ul>
                                </div>
                                <!-- <div id='customlist'></div> -->
                                <!-- <div id='customlistmulti'></div> -->
        <!--                        <div id='city_tooltip_wrapper'>
                                    <div id='city_tooltip'>Plusieurs villes portent ce nom. Veuillez pr&eacute;ciser en utilisant le tableau de suggestions.<span class='clear'></span></div>
                                </div> -->
                                <div id="city_table_wrapper" class="jb-cty-cstm-list-mx jb-cty-list-mx this_hide" data-obj="cstm">
                                    <div id='city_table_details'>{wos/deco:_PG_SIGNUP_TX031}</div>
                                    <table id="city_table" class="jb-cty-cstm-list jb-ins-city-list">
                                        <tr class="jb-cty-cstm-list-hdr">
                                            <th>Ville</th>
                                            <th>Pays</th>
                                            <th>Population</th>
                                        </tr>
                                        <?php // for($i=0;$i<10;$i++) : ?>
        <!--                                <tr class="jb-cty-list-elt jb-city-list-trg" data-obj="cstm" data-ci="cyid" data-ii="cyid" data-cycop=1>
                                            <td class="jb-cy-list-cynm">Ville this_hide</td>
                                            <td class="jb-cy-list-cycn">FR</td>
                                            <td class="jb-cy-list-cypop">10<?php // echo $i; ?></td>
                                        </tr> -->
                                        <?php // endfor; ?>
                                    </table>
                                    <div id="cysrh-cstm-spnr-mx" class="jb-cysrh-cstm-spnr-mx this_hide">
                                        <img src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
                                    </div>
                                </div>
                                <!-- <span class='clear'></span> -->
                            </div>
                            <!-- 
                                [DEPUIS 19-11-15] @author BOR
                                    Je ne voyais pas à quoi ça servait. 
                                    De plus, le retrait de cette zone n'a causé aucun bogue de regression immédiat.
                            -->
                            <!-- <div id="ins_form_separator"></div> -->
                            <div class="ins_group jb-ins-group jb-ins-grp-psd" data-field="pseudo">
                                <div class="spinner ins_spinner" id="ins_pseudo_spinner"></div>
                                <div class="ins_group_label" id="label_nickname">
                                    <span>{wos/deco:_PG_SIGNUP_TX016}</span>
                                    <span class="ins-vald-mark jb-ins-vald-mark this_hide"></span>
                                </div>
                                <div>
                                    <!--<input id="ins_input_nickname" class="ins_group_input ins_error_lock jb-ins-com-elt" data-target="pseudo" spellcheck="false" name="ins_input_nickname" type="text"  value="{wos/datx:psd}" placeholder="Comment voulez-vous être reconnu sur Trenqr ?" required>-->
                                    <input id="ins_input_nickname" class="ins_group_input ins_error_lock jb-ins-com-elt" data-target="pseudo" spellcheck="false" name="ins_input_nickname" type="text"  value="<?php echo ( $pseudo ) ? : "" ?>" placeholder="{wos/deco:_PG_SIGNUP_TX017}" required>
                                </div>
                            </div>
                            <div class="ins_group jb-ins-group jb-ins-grp-eml" data-field="email">
                                <div class="spinner ins_spinner" id="ins_email_spinner"></div>
                                <label class="ins_group_label" id="label_mail" for="ins_input_mail">
                                    {wos/deco:_PG_SIGNUP_TX018}
                                    <span class="ins-vald-mark jb-ins-vald-mark this_hide"></span>
                                </label>
                                <div>
                                    <!--<input id="ins_input_mail" class="ins_group_input ins_error_lock jb-ins-eml-ipt jb-ins-com-elt" data-target="email" type="email" value="{wos/datx:email}" placeholder="Une adresse email valide et sécurisée" required>-->
                                    <input id="ins_input_mail" class="ins_group_input ins_error_lock jb-ins-eml-ipt jb-ins-com-elt" data-target="email" type="email" value="<?php echo ( $email ) ? : "" ?>" placeholder="{wos/deco:_PG_SIGNUP_TX019}" required>
                                </div>
                            </div>
                            <div class="ins_group jb-ins-group jb-ins-grp-confeml this_hide" data-field="email_conf">
                                <label class="ins_group_label" id="label_mail_confirmation" for="ins_input_mail_confirmation">Confirmation de l'adresse mail</label>
                                <!--<input id="ins_input_mail_confirmation" class="ins_group_input ins_error_lock jb-ins-emlcf-ipt jb-ins-com-elt" data-target="email_conf" type="email" required>-->
                                <input id="ins_input_mail_confirmation" class="ins_group_input ins_error_lock jb-ins-emlcf-ipt jb-ins-com-elt this_hide" data-target="email_conf" type="email" value="<?php echo ( $email ) ? : "" ?>" required>
                            </div>
                            <div class="ins_group jb-ins-group jb-ins-grp-pwd" data-field="password">
                                <span id="ins_passwd_strengh"><b class="passwd_str_fill jb-pwd-strength"></b></span>
                                <label class="ins_group_label" id="label_passwd" for="ins_input_passwd">
                                    <span>{wos/deco:_PG_SIGNUP_TX020}</span>
                                    <span class="ins-vald-mark jb-ins-vald-mark this_hide"></span>
                                </label>
                                <div>
                                    <!--<input id="ins_input_passwd" class="ins_group_input ins_error_lock jb-ins-pwd-ipt jb-ins-com-elt" data-target="pwd" name="ins_input_passwd" type="password" value="{wos/datx:hp}" placeholder="Plus c'est complexe mieux c'est !" required>-->
                                    <input id="ins_input_passwd" class="ins_group_input ins_error_lock jb-ins-pwd-ipt jb-ins-com-elt" data-target="pwd" name="ins_input_passwd" type="password" value="<?php echo ( $password ) ? : "" ?>" placeholder="{wos/deco:_PG_SIGNUP_TX021}" required>
                                </div>
                            </div>
                            <div class="ins_group jb-ins-group jb-ins-grp-conf-pwd this_hide" data-field="password_conf">
                                <label class="ins_group_label" id="label_passwd_confirmation" for="ins-fld-pwd-cnf">
                                    Confirmation du mot de passe
                                </label>
                                <div>
                                    <!--<input id="ins-fld-pwd-cnf" class="ins_group_input ins_error_lock jb-ins-pwdcf-ipt jb-ins-com-elt" type="password" data-target="pwd_conf" required>-->
                                    <input id="ins-fld-pwd-cnf" class="ins_group_input ins_error_lock jb-ins-pwdcf-ipt jb-ins-com-elt" type="password" data-target="pwd_conf" value="<?php echo ( $password ) ? : "" ?>" required>
                                </div>
                            </div>
                            <div id="ins-recaptcha" class="ins_group_label ins-recaptcha jb-ins-com-elt" data-target="recaptcha" >
                                <div class="g-recaptcha" data-sitekey="6LeA8Q0TAAAAAPqg7YU02r1qzm3X_UFjHEAB2mbk"></div>
                            </div>
                            <div  class="ins_group clearfix jb-ins-group jb-ins-grp-legals legals" data-field="legals">
                                <div id="ins_group_cgu" class="ins_error_lock">
                                    <input id="ins_cgu" class="jb-ins-cgu-ipt jb-ins-com-elt" data-target="cgu" type="checkbox">
                                    <label id="label_cgu" for="ins_cgu">
                                        {wos/deco:_PG_SIGNUP_TX023}
                                    </label>
                                </div>
                            </div>
                            <input id="ins_socialarea" type="hidden" value="fr">
                            <div id="ins-submit-wpr" class="ins_group">
                                <input id="ins_form_submit" class="jb-ins-submit" data-action="submit_start" type="submit" value="{wos/deco:_PG_SIGNUP_TX024}">
                            </div>
                        </section>
                    </form>
                </div>
                {wos/dvt:footer_default}
            </div>
<!--            <div id="ins_right" class="jb-ins-right-box">
                <div id="ins_right_top" class="jb-ins-infos-mx this_hide">
                <div id="ins_right_top" class="jb-ins-infos-mx ">
                    <p id="ins_info_msg">
                        <span class="jb-ins-infos-msg">
                            Bienvenue sur la planète Trenqr. Ou Presque …<br/><br/>Merci de remplir ce formulaire afin que nous puissions mieux faire connaissance.<br/>
                        </span>
                        <span id="ins-infos-msg-mr" class="jb-ins-infos-msg-mr">
                            Cette courte étape ne prend en moyenne que <b style="color: orange">3 minutes</b>.<br/> Pour chaque section, des informations seront disponibles dans cette fenêtre, afin de vous guider. <br/><br/> Si cela vous prend plus de 3 minutes, ne vous inquiétez pas, ça restera entre nous !
                        </span>
                    </p>
                    <div id="ins-r-t-btm">
                        <a id="ins-r-t-btm-mr" class="jb-ins-mr-trg cursor-pointer" >Voir Plus</a>
                    </div>
                    <a id="ins-r-t-btm-mr-rst" class="jb-ins-mr-rst-trg" href="">Revenir au début</a>
                </div>
                <div id="ins_right_bottom" class="jb-ins-asd-err-mx this_hide">
                    <p id="ins_error_msg" class="jb-ins-asd-err-msg"></p>
                    <span class="clear"></span>
                </div>
            </div>-->
            
        </div>
    </div>
    {wos/dvt:nolang}
    {wos/csam:notify_ua}
    
    <div id="tqr-ins-last-check-sprt" class="jb-tqr-ins-last-chk-sprt this_hide" >
        <section id="tqr-ins-last-check" class="jb-tqr-ins-last-chk">
            <header id="tqr-ins-last-chk-hdr">
                <div id="tqr-ins-last-chk-hdr-tle" class="classname">{wos/deco:_PG_SIGNUP_CNFRM_TX001}</div>
                <div id="tqr-ins-last-chk-hdr-sbtle" class="classname">{wos/deco:_PG_SIGNUP_CNFRM_TX002}</div>
            </header>
            <div id="tqr-ins-last-chk-bdy">
                <ul>
                    <li class="tqr-ins-last-chk-ln">
                        <label class="tqr-ins-last-chk-lbl">{wos/deco:_PG_SIGNUP_TX005}</label>
                        <input class="tqr-ins-last-chk-ipt jb-tqr-ins-last-chk-ipt" data-fld="fullname"  type="text" value="" readonly />
                    </li>
                    <li class="tqr-ins-last-chk-ln">
                        <label class="tqr-ins-last-chk-lbl">{wos/deco:_PG_SIGNUP_TX007}</label>
                        <input class="tqr-ins-last-chk-ipt jb-tqr-ins-last-chk-ipt" data-fld="borndate"  type="text" value="" readonly />
                    </li>
                    <!--<li class="tqr-ins-last-chk-xpln" >Nous recoltons cette donnée pour des <a href="/cgu" target="_blank">raisons légales</a></li>-->
                    <li class="tqr-ins-last-chk-ln">
                        <label class="tqr-ins-last-chk-lbl">{wos/deco:_PG_SIGNUP_TX011}</label>
                        <input class="tqr-ins-last-chk-ipt jb-tqr-ins-last-chk-ipt" data-fld="gender"  type="text" value="" readonly />
                    </li>
                    <li class="tqr-ins-last-chk-ln">
                        <label class="tqr-ins-last-chk-lbl">{wos/deco:_PG_SIGNUP_TX014}</label>
                        <input class="tqr-ins-last-chk-ipt jb-tqr-ins-last-chk-ipt" data-fld="citysrh"  type="text" value="" readonly />
                    </li>
                    <li class="tqr-ins-last-chk-ln">
                        <label class="tqr-ins-last-chk-lbl">{wos/deco:_PG_SIGNUP_TX018}</label>
                        <input class="tqr-ins-last-chk-ipt jb-tqr-ins-last-chk-ipt" data-fld="email"  type="email" value="" readonly />
                    </li>
                    <li class="tqr-ins-last-chk-ln">
                        <label class="tqr-ins-last-chk-lbl">{wos/deco:_PG_SIGNUP_TX016}</label>
                        <input class="tqr-ins-last-chk-ipt jb-tqr-ins-last-chk-ipt" data-fld="pseudo"  type="pseudo" value="" readonly />
                    </li>
                    <li class="tqr-ins-last-chk-ln">
                        <label class="tqr-ins-last-chk-lbl">{wos/deco:_PG_SIGNUP_TX020}</label>
                        <input class="tqr-ins-last-chk-ipt jb-tqr-ins-last-chk-ipt" data-fld="pwd"  type="password" value="" readonly />
                    </li>
                    <li class="tqr-ins-last-chk-xpln" data-fld="pwd">{wos/deco:_PG_SIGNUP_CNFRM_TX003}</li>
                </ul>
            </div>
            <footer id="tqr-ins-last-chk-ftr">
                <div id="tqr-ins-last-chk-fnl-dcs">
                    <a class="tqr-ins-last-chk-fnl-dc jb-tqr-ins-last-chk-fnl-dc" data-action="submit_back" data-css="submit_back" href="javascript:;">{wos/deco:_PG_SIGNUP_CNFRM_TX004}</a>
                    <a class="tqr-ins-last-chk-fnl-dc jb-tqr-ins-last-chk-fnl-dc" data-action="submit_finally" data-css="submit_finally" href="javascript:;">{wos/deco:_PG_SIGNUP_CNFRM_TX005}</a>
                </div>
            </footer>
        </section>
    </div>
    
    <div id="ins-intro-sprt" class="jb-ins-intro-sprt this_hide" data-uds-stor="">
        <a id="ins-intro-skip-btn" class="jb-ins-intro-skip-btn" href="javascript:;">{wos/deco:_PG_SIGNUP_INTRO_TX001}</a>
        
        <div id="ins-intro-wrng-mx" class="jb-ins-intro-wrng-mx jb-ins-intro-sub-zn this_hide" data-sec="intro-good-player">
            <div class="ins-intro-dlgbx-msg">
                <span>{wos/deco:_PG_SIGNUP_INTRO_TX002}</span><br/> 
                <span class="_inner_q">- {wos/deco:_PG_SIGNUP_INTRO_TX003} -</span>
            </div>
            <div class="ins-intro-dlgbx-chc-mx">
                <a class="ins-intro-dlgbx-chc jb-ins-intro-dlgbx-chc" data-sec="intro-good-player" data-val="n" href="javascript:;" role="button">{wos/deco:_PG_SIGNUP_INTRO_TX004}</a>
                <a class="ins-intro-dlgbx-chc jb-ins-intro-dlgbx-chc" data-sec="intro-good-player" data-val="y" href="javascript:;" role="button">{wos/deco:_PG_SIGNUP_INTRO_TX005}</a>
            </div>
        </div>
        
        <div id="ins-intro-frc-mx" class="jb-ins-intro-frc-mx jb-ins-intro-sub-zn this_hide" data-sec="intro-choose-fside">
            <div class="ins-intro-dlgbx-msg">
                <span>
                    {wos/deco:_PG_SIGNUP_INTRO_TX006}
                </span>
                <span class="_inner_q la-force">- {wos/deco:_PG_SIGNUP_INTRO_TX007} -</span>
            </div>
            <div class="ins-intro-dlgbx-chc-mx">
                <a class="ins-intro-dlgbx-chc jb-ins-intro-dlgbx-chc" data-sec="intro-choose-fside" data-val="dark-side" href="javascript:;" role="button" title="{wos/deco:_PG_SIGNUP_INTRO_TX008}" >
                    {wos/deco:_PG_SIGNUP_INTRO_TX009}
                    <span class="ins-intro-dlgbx-chc-tle dark">{wos/deco:_PG_SIGNUP_INTRO_TX008} !</span>
                </a>
                <a class="ins-intro-dlgbx-chc jb-ins-intro-dlgbx-chc" data-sec="intro-choose-fside" data-val="light-side" href="javascript:;" role="button" title="{wos/deco:_PG_SIGNUP_INTRO_TX010}" >
                    {wos/deco:_PG_SIGNUP_INTRO_TX011}
                    <span class="ins-intro-dlgbx-chc-tle light">{wos/deco:_PG_SIGNUP_INTRO_TX012}</span>
                </a>
            </div>
        </div>
        
        <div id="ins-intro-bmx" class="jb-ins-intro-bmx jb-ins-intro-sub-zn this_hide" data-sec="intro-launch-cinematic">
            <div id="ins-intro-par-wpr" class="jb-ins-intro-par-wpr play">
                <?php if ( $rnlg === "fr" ) : ?>
                <p class="ins-intro-par">
                    Dans une contrée lointaine, très lointaine…
                </p>
                <p class="ins-intro-par">
                    Voyageait <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="une certaine" data-male="un certain"></span>&nbsp;<span class="jb-ins-intro-cstm-datas" data-name="fn"></span>. 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="Une guerrière" data-male="Un guerrier"></span> 
                    impitoyable et redoutable, au sang-froid légendaire, 
                    digne <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="héritière" data-male="héritier"></span> 
                    de la célèbre Maison Vannister. 
                </p>
                <p class="ins-intro-par">
                    Une famille si hors du commun, que les légendes racontent que les mâles étaient dotés de roubignoles si exceptionnels, 
                    qu'ils pouvaient résister à un coup de masse de 5 kilos, sans pousser le moindre cri aigu ... Re-mar-qua-ble !
                </p>
                <p class="ins-intro-par">
                    Il se disait aussi que lorsqu’une femme de cette majestueuse famille pétaradait, 
                    il sentait si bon la rose, que vous pouviez prendre une grande inspiration et atteindre le Nirvana... !
                </p>
                <p class="ins-intro-par">
                    Bref... À cette époque, <span class="jb-ins-intro-cstm-datas" data-name="fn"></span> parcourait la galaxie 1-T-RN-T-2E, 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="accompagnée" data-male="accompagné"></span> de son valeureux cousin Tyrion, 
                    en direction de la planète Trenqr !
                </p>
                <p class="ins-intro-par">
                    Une planète dont on disait qu’elle vivait sous la haute protection d’un pêcheur Viking, bedonnant mais craint de tous.
                </p>
                <p class="ins-intro-par">
                    Un homme, certes de petite taille (1m05), mais d'une force ibracadabrante : Zlatan Ibraicodovitch.
                </p>
                <p class="ins-intro-par">
                    De la vie de <span class="jb-ins-intro-cstm-datas" data-name="fn"></span>, nous ne savons que peu de choses. 
                </p>
                <p class="ins-intro-par">
                    Excepté quelques histoires ramassées par-ci, par là.
                </p>
                <p class="ins-intro-par">
                    Par exemple, nous savons que sa planète était en voie de disparition, parce que ses habitants confondaient trop souvent, la poubelle jaune à la poubelle verte... 
                </p>
                <p class="ins-intro-par">
                    Une situation qui exaspérait le haut-commissaire au Conseil Intergalactique, un certain Eduardo Arturo Dovald Drump.
                </p>
                <p class="ins-intro-par">
                    Les écrits racontent que dans sa ville, <span class="jb-ins-intro-cstm-datas" data-name="ucy"></span>, <span class="jb-ins-intro-cstm-datas" data-name="fn"></span> 
                    était <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="crainte et respectée" data-male="craint et respecté"></span>, 
                    du fait de son appartenance à la confrérie des seigneurs du côté 
                    <span class="jb-ins-intro-cstm-datas" data-name="la-force-trick" data-light="lumineux" data-dark="obscure"></span> de la force.
                </p>
                <div>
                    <p class="ins-intro-par jb-ins-intro-cstm-datas" data-name="la-force-trick-story" data-scp="dark-side">
                        Mais récemment des chercheurs ont découvert qu'en réalité, 
                        il s'agissait plutôt d’une personne dormant rarement sans son petit nounours tout doux, et qui collectionnait des photos de chats trop meugnons.<br/> 
                        Le mythe s’effondre … !
                    </p>
                    <p class="ins-intro-par jb-ins-intro-cstm-datas" data-name="la-force-trick-story" data-scp="light-side">
                        Mais récemment des chercheurs ont découvert qu'en réalité, 
                        il s'agissait d’une personne plutôt sombre et austère, travaillant en sous-main pour le côté obscure. Pas bien … ! 
                    </p>
                </div>
                <p class="ins-intro-par">
                    Mais qu’est ce qui pouvait bien guider <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="cette femme surnommée" data-male="cet homme surnommé"></span> 
                    "<span class="jb-ins-intro-cstm-datas" data-name="ps"></span>" dans sa quête ? 
                </p>
                <p class="ins-intro-par">
                    Nous l’ignorons… pour l’instant !
                </p>
                <p class="ins-intro-par">
                    Cependant, du haut de ses <span class="jb-ins-intro-cstm-datas" data-name="age"></span> ans 
                    et malgré une petite appréhension,  
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="elle restait déterminée" data-male="il restait déterminé"></span> 
                    à aller au bout de sa mission d’exploration, destinée, bien évidemment, à SAUVER LE MONDE !
                </p>
                <p class="ins-intro-par">
                    C’était le point de départ d’une aventure qui s’annonçait épique et fantastique !
                </p>
                <p class="ins-intro-par last">
                    Hé <span class="jb-ins-intro-cstm-datas" data-name="fn"></span> ! Nous vous souhaitons la bienvenue sur Trenqr, le réseau social le plus fun du Monde !
                </p>
                <?php elseif ( $rnlg === "en" ) : ?>
                <p class="ins-intro-par">
                    Once upon a time, a long time ago in a galaxy far, far away…
                </p>
                <p class="ins-intro-par">
                    There was a certain&nbsp;<span class="jb-ins-intro-cstm-datas" data-name="fn"></span>. 
                    A ruthless and formidable warrior, known for <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="her" data-male="his"></span> legendary composure. 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="She" data-male="He"></span> was the worthy <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="heiress" data-male="heir"></span> of the famous Vannister family.  
                </p>
                <p class="ins-intro-par">
                    A family so uncommon that the legends pretend males have such exceptional balls that they could resist to a 5 kilos sledgehammer hit without pushing any squeal.
                </p>
                <p class="ins-intro-par">
                    Anyway ! At that time, <span class="jb-ins-intro-cstm-datas" data-name="fn"></span> was roaming a galaxy called INTO-NETS toward the planet Trenqr with 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="her" data-male="his"></span> "beloved" brother Tyrion Vannister !
                </p>
                <p class="ins-intro-par">
                    A planet that is said was living under the protection of a paunchy viking fisherman but feared by all.
                </p>
                <p class="ins-intro-par">
                    Yes he was a dwarf, but he has an ibracadable strength : his name was Zlatan Ibraicodovitch.
                </p>
                <p class="ins-intro-par">
                    From <span class="jb-ins-intro-cstm-datas" data-name="fn"></span>'s life, we know few
                </p>
                <p class="ins-intro-par">
                   Except for rumors picked here and there
                </p>
                <p class="ins-intro-par">
                    For instance, we know <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="her" data-male="his"></span> planet was endangered because its people have been confusing the yellow trash to the green one too often…
                </p>
                <p class="ins-intro-par">
                    A situation that infuriated the High Commissioner to the Intergalactic Council, the venerable Eduardo Arturo Dovald Drump.
                </p>
                <p class="ins-intro-par">
                    Moreover, the writings say that in <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="her" data-male="his"></span> city, <span class="jb-ins-intro-cstm-datas" data-name="ucy"></span>, 
                    <span class="jb-ins-intro-cstm-datas" data-name="fn"></span> was feared because of 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="her" data-male="his"></span> membership to the society of the lords of the 
                    <span class="jb-ins-intro-cstm-datas" data-name="la-force-trick" data-light="light" data-dark="dark"></span> side of the force.
                </p>
                <div>
                    <p class="ins-intro-par jb-ins-intro-cstm-datas" data-name="la-force-trick-story" data-scp="dark-side">
                        But recently researchers revealed <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="she" data-male="he"></span> 
                        was someone who cannot sleep without his fluffy teddy bear and who is passionate about collecting pictures of cute cats.<br/> 
                        The myth is busted… !
                    </p>
                    <p class="ins-intro-par jb-ins-intro-cstm-datas" data-name="la-force-trick-story" data-scp="light-side">
                        But recently researchers revealed <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="she" data-male="he"></span> was in fact a pretty dark and lugubrious person, working behind the scenes for the dark side.<br/> 
                        Nasty <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="girl" data-male="boy"></span> … !
                    </p>
                </div>
                <p class="ins-intro-par">
                    Anyway ! The genuine question is : what were the real motivations that was leading this mysterious 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="woman" data-male="man"></span> in 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="her" data-male="his"></span> quest ? 
                </p>
                <p class="ins-intro-par">
                    We don't know ... for now !
                </p>
                <p class="ins-intro-par">
                    However, despite <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="her" data-male="his"></span> 
                    <span class="jb-ins-intro-cstm-datas" data-name="age"></span> years old and a few apprehensions, 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="she" data-male="he"></span> was determined to complete 
                    <span class="jb-ins-intro-cstm-datas" data-name="gdr-chc-trick" data-fem="her" data-male="his"></span> exploratory mission intended, obviously, TO SAVE THE WORLD !
                </p>
                <p class="ins-intro-par">
                    It was the starting point of an adventure that promised to be epic and fantastic !
                </p>
                <p class="ins-intro-par last">
                    Hey <span class="jb-ins-intro-cstm-datas" data-name="fn"></span> ! Welcome on Trenqr, the coolest social network in the world that focuses on the fun side !
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if ( $sector && in_array($sector,["ENTERCZ_ACTIVE_FB_SSN","ENTERCZ_INSAPI_FB"]) && $INS_WAPI_FB_2 ) : ?>
    <div class="jb-ins-with-api-datas this_hide" data-api="fb">
        {
            "user_id"               : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_id"] ) ? $INS_WAPI_FB_2["user_id"] : ""; ?>",
            "user_name"             : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_name"] ) ? $INS_WAPI_FB_2["user_name"] : ""; ?>",
            "user_iceleb"           : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_iceleb"] ) ? $INS_WAPI_FB_2["user_iceleb"] : ""; ?>",
            "user_bdate"            : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_bdate"] ) ? $INS_WAPI_FB_2["user_bdate"] : ""; ?>",
            "user_range"            : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_range"] ) ? $INS_WAPI_FB_2["user_range"] : ""; ?>",
            "user_gender"           : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_gender"] ) ? $INS_WAPI_FB_2["user_gender"] : ""; ?>",
            "user_hometown"         : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_hometown"] ) ? $INS_WAPI_FB_2["user_hometown"] : ""; ?>",
            "user_email"            : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_email"] ) ? $INS_WAPI_FB_2["user_email"] : ""; ?>",
            "user_email_verified"   : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_email_verified"] ) ? $INS_WAPI_FB_2["user_email_verified"] : ""; ?>",
            "user_languages"        : "<?php echo ( $INS_WAPI_FB_2 && $INS_WAPI_FB_2["user_languages"] ) ? $INS_WAPI_FB_2["user_languages"] : ""; ?>"
        }
    </div>
    <?php endif; ?>
    
    <div id="tq-pg-env" class="jb-tq-pg-env this_hide">
        {
            "baseUrl"   : "{wos/sysdir:script_dir_uri}",
            "pageid"    : "{wos/datx:pagid}",
            "pgvr"      : "{wos/datx:pgakxver}",
            "sector"    : "{wos/datx:sector}"
        }
    </div>
    
    
    <!-- JS LOAD -->
    <div id="tqr-js-declare">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

        <script src="{wos/sysdir:script_dir_uri}/r/c.c/underscore-min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/notify.csam.js?{wos/systx:now}"></script>
        <!--<script src="{wos/sysdir:script_dir_uri}/w/d/ins_form_validator.d.js?{wos/systx:now}"></script>-->
        <!--<script src="{wos/sysdir:script_dir_uri}/w/d/ins_overlay.d.js?{wos/systx:now}"></script>-->
        <script src="{wos/sysdir:script_dir_uri}/r/csam/lgselect.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/mnfm.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/s/ins.s.js?{wos/systx:now}"></script>
<!--    <script src="{wos/sysdir:script_dir_uri}/w/d/ins_ajax_validation.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/d/ins_delay.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/d/ins_form_citycomplete.d.js?{wos/systx:now}"></script>-->
    </div>
</div>
