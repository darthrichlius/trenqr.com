
<?php
    
    set_error_handler('exceptions_error_handler');
    try {
       /*
        * EMAIL_CONFIRMATION
        */
        $ec_is_ecofirm = "{wos/datx:ec_is_ecofirm}";
        $ec_state = "{wos/datx:ec_state}";
        $ec_scope = "{wos/datx:ec_scope}";
        $ec_is_ecofirm = ( $ec_is_ecofirm === '1' ) ? TRUE : FALSE;
        
        $acc_lang = "{wos/datx:stgs_bdz_lg}";
//        echo $acc_lang;
//        exit();
        
        restore_error_handler();

    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
    
?>

<div s-id="TQR_GTPG_STGS">
        <!--[if lt IE 9]>
        <div id='oldie'></div>
        <![endif]-->
        <div id="stgs-vld-by-pwd-mx" class="jb-stgs-vbp-mx this_hide">
            <a id="stgs-vbp-ccl" class="jb-stgs-vbp-ccl" href="javascript:;">
                <img height="30" src="{wos/sysdir:img_dir_uri}/r/unqclo.png" />
            </a>
            <div id="stgs-vld-by-pwd-ctr" method="post" action="/{wos/datx:stgs_bdz_psd}/settings/profile">
                {wos/datx:stgs_vbp_action}
                <p id="stgs-vbp-hdr" class="jb-stgs-vbp-hdr">CONFIRMATION</p>
                <div id="stgs-vbp-ipt-mx" class="jb-stgs-vbp-ipt-mx">
                    <input id="stgs-vbp-ipt" class="jb-stgs-vbp-ipt" type="password" autocomplete="off" spellcheck="false" placeholder=""/>
                </div>
                <div id="stgs-vbp-sub-mx" class="jb-stgs-vbp-sub-mx">
                    <a id="stgs-vbp-sub" class="jb-stgs-vbp-sub" href="javascript:;">Valider</a>
                </div>
                <p id="stgs-vbp-xpln" class="jb-stgs-vbp-xpln">Veuillez rentrer votre mot de passe pour valider les modifications sur votre compte</p>
            </div>
        </div>
        {wos/csam:notify_ua}
        <!-- A Retirer pour une version avec Require -->
        {wos/dvt:frdcenter}
        <!-- A Retirer pour une version avec Require -->
        {wos/csam:bugzy}
        {wos/dvt:header_ro_nomn}
        
        <div id="nwfd-buffer" class="jb-nwfd-buffer this_hide">
                <!--
                [NOTE 19-11-14] @author Richard (phoenix) Lou CARTHER
                Permet de stocker les données en attente de présentation.
                Les données sont présentées sous forme de chaine JSON ce qui facilite leur traitement au niveau de la couche SCRIPT. 
                
                EXEMPLE : 
                    {
                       "eid1" : {
                           "attr1" : val1,
                           "attr2" : val2,
                           "attr3" : val3
                       },
                       "eid2" : {
                           "attr1" : val1,
                           "attr2" : val2,
                           "attr3" : val3
                       },
                       "eid3" : {
                           "attr1" : val1,
                           "attr2" : val2,
                           "attr3" : val3
                       }
                    }
             
             La zone est régulièrement mise à jour selon le script de vérification auprès du serveur.
            -->
            {
                "profile" : {
                    "fullname" : "{wos/datx:stgs_bdz_fn}",
                    "birthdate" : "{wos/datx:stgs_bdz_bd}",
                    "birthdate_tsp" : "{wos/datx:stgs_bdz_bd_tstamp}",
                    "bdy_d" : "{wos/datx:stgs_bdz_bd_d}",
                    "bdy_m" : "{wos/datx:stgs_bdz_bd_m}",
                    "bdy_y" : "{wos/datx:stgs_bdz_bd_y}",
                    "bdy_mod_rmn" : "{wos/datx:stgs_bdz_bd_rmn}",
                    "gender" : "{wos/datx:stgs_bdz_gdr}",
                    "gdr_mod_rmn" : "{wos/datx:stgs_bdz_gdr_rmn}",
                    "city": {
                        "i" : "{wos/datx:stgs_bdz_cyi}",
                        "n" : "{wos/datx:stgs_bdz_cyn}",
                        "cn" : "{wos/datx:stgs_bdz_cycncd}",
                        "city" : "{wos/datx:stgs_bdz_city}"
                    }
                },
                "account" : {
                    "pseudo" : "{wos/datx:stgs_bdz_psd}",
                    "email" : "{wos/datx:stgs_bdz_em}",
                    "lang" : "{wos/datx:stgs_bdz_lg}"
                },
                "security" : {
                    "login" : {
                        "enable_lg_psd" : "{wos/datx:stgs_bdz_ecwpsd}"
                    }
                },
                "about" : {
                    "prod_desc_st" : "{wos/datx:stgs_bdz_pddesc_st}",
                    "prod_desc_lg" : "{wos/datx:stgs_bdz_pddesc_lg}",
                    "prod_lib" : "{wos/datx:stgs_bdz_pdlib}",
                    "prod_ver" : "{wos/datx:stgs_bdz_pdver}",
                    "prod_runmode" : "{wos/datx:stgs_bdz_pdrm}"
                }
            }
         </div>
        <span id="page_section" data-section="{wos/datx:panel}" style="display: none;"></span>
        <div class="screensize">
            <?php if ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) : ?>
                {wos/csam:email_confirm}  
            <?php endif; ?>
            <?php if (! ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
            <div id="pfl_content" class="clearfix2">
                {wos/dvt:profile_rightbar}
                <div id="pfl-center"  class="clearfix2">
                    <div id="pfl_middle">
<!--                        <div id="pfl_content_profile" class="pfl_middle_content">
                            <div class="pfl_middle_title"><h1>Profil</h1></div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin gravida a sapien sit amet pretium. Vestibulum laoreet ipsum varius mi accumsan rhoncus. Duis sit amet ornare nisl, ac malesuada turpis. Ut commodo orci orci, facilisis pulvinar elit accumsan sagittis. Suspendisse pulvinar vitae lacus vitae cursus. Donec gravida, lectus ac viverra auctor, dolor massa ultrices nisl, ac vehicula lacus ipsum eu lectus. Sed suscipit orci massa. Morbi pellentesque dui arcu, sed mollis velit condimentum vitae. Nullam auctor a leo vel posuere. Suspendisse congue vel libero eleifend commodo. Integer quis egestas turpis.</p>
                            <p>Pellentesque rhoncus nisi eu eleifend condimentum. In hac habitasse platea dictumst. Proin vulputate, libero dapibus aliquam tristique, justo mi tempor ante, quis aliquam tortor nulla at purus. Pellentesque porta vestibulum mauris et consectetur. Fusce nisi magna, aliquam vel arcu a, lobortis condimentum metus. In eget pharetra mi, eu dignissim metus. Nam vitae sem interdum, tristique ligula rutrum, placerat nisl. Aenean rhoncus ullamcorper velit, in feugiat neque luctus vitae. Sed convallis tristique purus quis congue. Cras vitae enim quis urna ornare molestie. Nam vulputate vel est sit amet volutpat.</p>
                            <p>Cras porttitor nunc scelerisque facilisis lacinia. Aliquam et mattis nulla. Donec vel euismod diam. Donec semper ultricies risus, non consectetur purus placerat vel. Maecenas imperdiet lectus eget mollis rutrum. Pellentesque magna elit, tempus ac leo a, eleifend facilisis nisi. Nulla a velit mollis, tempus turpis non, faucibus tortor.</p>
                            <div class="pfl_remind_wrapper">
                                <input type="checkbox" class="pfl_remind_input" id="pfl_profile_remind" checked="1">
                                <label class="pfl_remind_label" for="pfl_profile_remind">Ne plus afficher</label>
                                <a class="pfl_remind_ok" href="#">J&apos;ai compris !</a>
                                <span class="clear"></span>
                            </div>
                            <span class="clear"></span>
                        </div>-->
<!--                        <div class="pfl_middle_content" id="pfl_content_account">
                            <div class="pfl_middle_title"><h1>Compte</h1></div>
                            <p>Etiam quis turpis hendrerit ligula ullamcorper vestibulum. Phasellus felis nulla, mollis tincidunt euismod eu, tempus sed velit. In id leo erat. Vestibulum dignissim est vel vehicula consequat. Nam ut nulla dictum, condimentum sem vitae, luctus massa. Donec eu ullamcorper neque. Vestibulum lobortis elit nulla, vel lobortis augue tincidunt et. Sed quis nibh at leo tincidunt accumsan. Cras nec tempor magna. Aliquam erat volutpat. Aliquam tristique interdum massa in imperdiet. Donec quis libero nec nisl tincidunt tincidunt a malesuada odio.</p>
                            <p>Nulla nec mi pharetra, ullamcorper nulla in, posuere lacus. Cras at quam ac arcu vehicula fringilla non quis quam. Mauris id tellus et lorem pulvinar sodales. Vestibulum sit amet ipsum nisl. Vestibulum suscipit aliquet vehicula. Quisque neque neque, condimentum ac leo ut, rhoncus feugiat lorem. Nulla pulvinar quam non semper auctor. Sed at varius augue. Curabitur fringilla velit ac justo tempor, nec feugiat erat consectetur. Donec suscipit porta placerat. Nunc ullamcorper imperdiet risus non dictum. Proin eget vestibulum ante.</p>
                            <p>Morbi gravida ultrices tellus. Suspendisse sollicitudin lacus at laoreet sagittis. Sed viverra sagittis tortor nec luctus. Pellentesque eget ante quam. Sed eget velit et nisl iaculis pretium tincidunt sit amet est. Aenean sodales vitae ligula congue eleifend. Curabitur ipsum ligula, commodo non erat non, rhoncus elementum purus.</p>
                            <span class="clear"></span>
                        </div>-->
<!--                        <div class="pfl_middle_content" id="pfl_content_security">
                            <div class="pfl_middle_title"><h1>S&eacute;curit&eacute;</h1></div>
                            <p>Aliquam hendrerit luctus eros eget vulputate. Curabitur ac viverra massa. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nulla tellus magna, adipiscing ac mi eget, sodales semper libero. Maecenas consectetur a quam ut dignissim. Phasellus imperdiet ipsum eu nibh porta, sed congue enim iaculis. Pellentesque in auctor massa, non consequat orci. Vestibulum varius leo eu tristique consectetur. Vestibulum fringilla at arcu blandit cursus. Nullam eu mauris tincidunt, dictum est in, faucibus mauris. Curabitur auctor ultrices risus, non hendrerit lectus iaculis ac. Quisque felis est, porttitor eget est at, bibendum dictum ligula.</p>
                            <p>Nunc pharetra a felis vitae mattis. Nulla a sem quis magna interdum sodales. Aliquam dapibus mauris eu laoreet aliquam. Sed quis auctor risus. Donec tempus gravida dapibus. Etiam at nunc pharetra, hendrerit orci id, hendrerit augue. Ut facilisis ultrices luctus. Nullam pellentesque, ipsum sit amet laoreet scelerisque, purus eros interdum elit, sed fringilla massa lacus a arcu. Quisque pulvinar tortor nec ante tincidunt porttitor. Proin ornare, magna vitae mattis cursus, ante leo posuere sem, eu sagittis diam nulla a orci. Vivamus hendrerit ipsum vitae velit pulvinar dignissim.</p>
                            <p>Integer ultricies euismod augue. Nam consequat non elit id rhoncus. Fusce at mauris sodales, scelerisque sapien non, auctor tortor. Fusce lobortis felis lacus, eget ullamcorper turpis sagittis ac. Ut gravida, magna molestie venenatis commodo, elit tortor ornare nunc, non tincidunt est purus ac massa. Fusce a pharetra nisl, quis accumsan velit. Praesent tellus quam, dapibus id magna non, ultrices fringilla nunc. Donec adipiscing sodales enim at gravida. Curabitur convallis, enim vitae rutrum rutrum, arcu sapien adipiscing orci, commodo ullamcorper leo nulla ornare metus. Etiam mi tortor, condimentum et venenatis nec, iaculis ut massa. Pellentesque vel commodo massa. Donec fermentum condimentum eros, ut dignissim metus placerat vitae. Nam nisl nibh, accumsan quis ligula in, mollis fringilla sem. Nam sagittis dui purus, eu tincidunt enim bibendum eget.</p>
                            <span class="clear"></span>
                        </div>-->
                        <?php 
                            $section = "{wos/datx:section}";
                        ?>
                        <?php if ( in_array($section,["profile","profil"]) ) : ?>
                        <div id="pfl_form_profile_div" class="pfl_middle_content jb-stgs-wdw active" data-wdw="profile">
                        <?php else : ?>    
                        <div id="pfl_form_profile_div" class="pfl_middle_content jb-stgs-wdw this_hide" data-wdw="profile">
                        <?php endif; ?>
                            <div class="pfl_middle_title">Param&egrave;tres du profil</div>
                            <div class="pfl_middle_subtitle">Param&egrave;tres g&eacute;n&eacute;raux</div>
                            <form id="pfl_form_profile" class="jb-stgs-form-pfl" autocomplete="off" action="">
                                <div class="clear_input_wrapper">
                                    <a class="stgs-back-orign jb-stgs-back-orign" data-target="jb-stgs-form-pfl" href="javascript:;" title="Valeurs d'origine">
                                        <img height="18px" src="{wos/sysdir:img_dir_uri}/r/arr-bck.png" />
                                    </a>
                                    <a class="clear_input_link jb-stgs-clear-form" data-target="jb-stgs-form-pfl" href="javascript:;" title="Vider le formulaire">x</a>
                                </div>
                                <div class="pfl_form_group">
                                    <label class="pfl_form_label" for="pfl_input_fullname">Nom complet</label>
                                    <input id="pfl_input_fullname" class="pfl_form_input stgs-pfl-fld stgs-fld-errchk jb-stgs-pfl-fld" data-ft="fullname" spellcheck="false" type="text" value="{wos/datx:stgs_bdz_fn}"/>
                                    <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="fullname"></p>
                                </div>
                                <div class="pfl_form_group">
                                    <label class="pfl_form_label">Date de naissance</label>
                                    <div id="pfl_birthday_date_group" class="pfl_date_group profile_error_checker" data-pfl="ulock" tabindex="2">
                                        <div id="pfl_input_birthdaydate" class="pfl_select_wrapper stgs-pfl-fld jb-stgs-pfl-fld" data-ft="birthday">
                                            <select id="pfl_day" class="stgs-fld-errchk jb-stgs-bdy jb-stgs-bdy-d" name="birthday_day">
                                                <option value="init" selected>Jour</option>
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
                                        <div class="pfl_select_wrapper">
                                            <select id="pfl_month" class="stgs-fld-errchk jb-stgs-bdy jb-stgs-bdy-m" name="birthday_month">
                                                <option value="init" selected>Mois</option>
                                                <option value="01">jan</option>
                                                <option value="02">f&eacute;v</option>
                                                <option value="03">mar</option>
                                                <option value="04">avr</option>
                                                <option value="05">mai</option>
                                                <option value="06">juin</option>
                                                <option value="07">juil</option>
                                                <option value="08">ao&ucirc;</option>
                                                <option value="09">sep</option>
                                                <option value="10">oct</option>
                                                <option value="11">nov</option>
                                                <option value="12">d&eacute;c</option>
                                            </select>
                                        </div>
                                        <div class="pfl_select_wrapper ">
                                            <select id="pfl_year" class="stgs-fld-errchk jb-stgs-bdy jb-stgs-bdy-y" name="birthday_year">
                                                <option value="init" selected>Ann&eacute;e</option>
                                                <option value="2014">2014</option>
                                                <option value="2013">2013</option>
                                                <option value="2012">2012</option>
                                                <option value="2011">2011</option>
                                                <option value="2010">2010</option>
                                                <option value="2009">2009</option>
                                                <option value="2008">2008</option>
                                                <option value="2007">2007</option>
                                                <option value="2006">2006</option>
                                                <option value="2005">2005</option>
                                                <option value="2004">2004</option>
                                                <option value="2003">2003</option>
                                                <option value="2002">2002</option>
                                                <option value="2001">2001</option>
                                                <option value="2000">2000</option>
                                                <option value="1999">1999</option>
                                                <option value="1998">1998</option>
                                                <option value="1997">1997</option>
                                                <option value="1996">1996</option>
                                                <option value="1995">1995</option>
                                                <option value="1994">1994</option>
                                                <option value="1993">1993</option>
                                                <option value="1992">1992</option>
                                                <option value="1991">1991</option>
                                                <option value="1990">1990</option>
                                                <option value="1989">1989</option>
                                                <option value="1988">1988</option>
                                                <option value="1987">1987</option>
                                                <option value="1986">1986</option>
                                                <option value="1985">1985</option>
                                                <option value="1984">1984</option>
                                                <option value="1983">1983</option>
                                                <option value="1982">1982</option>
                                                <option value="1981">1981</option>
                                                <option value="1980">1980</option>
                                                <option value="1979">1979</option>
                                                <option value="1978">1978</option>
                                                <option value="1977">1977</option>
                                                <option value="1976">1976</option>
                                                <option value="1975">1975</option>
                                                <option value="1974">1974</option>
                                                <option value="1973">1973</option>
                                                <option value="1972">1972</option>
                                                <option value="1971">1971</option>
                                                <option value="1970">1970</option>
                                                <option value="1969">1969</option>
                                                <option value="1968">1968</option>
                                                <option value="1967">1967</option>
                                                <option value="1966">1966</option>
                                                <option value="1965">1965</option>
                                                <option value="1964">1964</option>
                                                <option value="1963">1963</option>
                                                <option value="1962">1962</option>
                                                <option value="1961">1961</option>
                                                <option value="1960">1960</option>
                                                <option value="1959">1959</option>
                                                <option value="1958">1958</option>
                                                <option value="1957">1957</option>
                                                <option value="1956">1956</option>
                                                <option value="1955">1955</option>
                                                <option value="1954">1954</option>
                                                <option value="1953">1953</option>
                                                <option value="1952">1952</option>
                                                <option value="1951">1951</option>
                                                <option value="1950">1950</option>
                                                <option value="1949">1949</option>
                                                <option value="1948">1948</option>
                                                <option value="1947">1947</option>
                                                <option value="1946">1946</option>
                                                <option value="1945">1945</option>
                                                <option value="1944">1944</option>
                                                <option value="1943">1943</option>
                                                <option value="1942">1942</option>
                                                <option value="1941">1941</option>
                                                <option value="1940">1940</option>
                                                <option value="1939">1939</option>
                                                <option value="1938">1938</option>
                                                <option value="1937">1937</option>
                                                <option value="1936">1936</option>
                                                <option value="1935">1935</option>
                                                <option value="1934">1934</option>
                                                <option value="1933">1933</option>
                                                <option value="1932">1932</option>
                                                <option value="1931">1931</option>
                                                <option value="1930">1930</option>
                                                <option value="1929">1929</option>
                                                <option value="1928">1928</option>
                                                <option value="1927">1927</option>
                                                <option value="1926">1926</option>
                                                <option value="1925">1925</option>
                                                <option value="1924">1924</option>
                                                <option value="1923">1923</option>
                                                <option value="1922">1922</option>
                                                <option value="1921">1921</option>
                                                <option value="1920">1920</option>
                                                <option value="1919">1919</option>
                                                <option value="1918">1918</option>
                                                <option value="1917">1917</option>
                                                <option value="1916">1916</option>
                                                <option value="1915">1915</option>
                                                <option value="1914">1914</option>
                                                <option value="1913">1913</option>
                                                <option value="1912">1912</option>
                                                <option value="1911">1911</option>
                                                <option value="1910">1910</option>
                                                <option value="1909">1909</option>
                                                <option value="1908">1908</option>
                                                <option value="1907">1907</option>
                                                <option value="1906">1906</option>
                                                <option value="1905">1905</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="pfl_modrem_birthday" class="pfl_modrem_countdown">{wos/datx:stgs_bdz_bd_rmn}</div>
                                    <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="birthday"></p>
                                    <div id="pfl_modrem_birthdaytooltip" class="pfl_tooltip_modrem_wrapper">
                                        <div id="pfl_tooltip_modrem">Ce chiffre représente le nombre de fois que vous pouvez encore modifier cette information.<span class='clear'></span></div>
                                    </div>
                                </div>
                                <div class="pfl_form_group">
                                    <label class="pfl_form_label">Genre</label>
                                    <div id="pfl_gender_selector">
                                        <?php 
                                            $x = "{wos/datx:stgs_bdz_gdr}";
                                            if ( $x === "m" ) :
                                        ?>
                                        <span id="pfl_gender_male" class="jb-pfl-gdr-chs jb-pfl-gdr-m active" data-target="m">H</span>
                                        <span id="pfl_gender_female" class="jb-pfl-gdr-chs jb-pfl-gdr-f" data-target="f">F</span>
                                        <div id="slider_base" class="stgs-fld-errchk jb-pfl-gdr-sldr-ch stgs-pfl-fld jb-stgs-pfl-fld" data-ft="gender" data-target="f">
                                            <div id="slider_selector" class="jb-stgs-pfl-gdr male" data-target="f"></div>
                                        </div>
                                        <?php else : ?>
                                        <span id="pfl_gender_male" class="jb-pfl-gdr-chs jb-pfl-gdr-m" data-target="m">H</span>
                                        <span id="pfl_gender_female" class="jb-pfl-gdr-chs jb-pfl-gdr-f active" data-target="f">F</span>
                                        <div id="slider_base" class="stgs-fld-errchk jb-pfl-gdr-sldr-ch stgs-pfl-fld jb-stgs-pfl-fld" data-ft="gender" data-target="m" >
                                            <div id="slider_selector" class="jb-stgs-pfl-gdr female" data-target="m"></div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div id="pfl_modrem_gender" class="pfl_modrem_countdown">{wos/datx:stgs_bdz_gdr_rmn}</div>
                                    <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="gender"></p>
                                    <div class='pfl_tooltip_modrem_wrapper' id="pfl_modrem_gendertooltip">
                                        <div id='pfl_tooltip_modrem'>Ce chiffre représente le nombre de fois que vous pouvez encore modifier cette information.<span class='clear'></span></div>
                                    </div>
                                </div>
                                <div class="pfl_form_group">
                                    <div id="pfl_city_spinner" class="jb-stgs-cty-ipt-spnr this_hide"></div>
                                    <label class="pfl_form_label" for="pfl_input_city">Ville</label>
                                    <input id="pfl_input_city" class="pfl_form_input stgs-fld-errchk stgs-pfl-fld jb-stgs-pfl-fld jb-stgs-cty-ipt" data-ft="city" autocomplete="off" spellcheck="false" type="text" value="{wos/datx:stgs_bdz_city}" data-ci="{wos/datx:stgs_bdz_cyi}">
                                    
                                    <div id="stgs-city-list-mx" class="jb-cty-smpl-list-mx jb-cty-list-mx this_hide" data-obj="smpl">
                                    <!-- <div id="stgs-city-list-mx" class="jb-cty-smpl-list-mx jb-cty-list-mx" data-obj="smpl"> -->
                                        <ul id="stgs-city-list" class="jb-stgs-city-list">
                                            <?php // for($ix=0;$ix<10;$ix++) : ?>
<!--                                            <li class="stgs-city-list-row jb-cty-list-elt" data-target="smpl" data-ci="" data-ii="">
                                                <a class="stgs-city-list-trg jb-city-list-trg clearfix2" data-obj="smpl"  data-ii="" title="120 000 hab." href="">
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
                                    <div id="city_table_wrapper" class="jb-cty-cstm-list-mx jb-cty-list-mx this_hide" data-obj="cstm">
                                        <div id="city_table_details">Il existe plusieurs villes de ce nom. Pour trouver la votre, aidez-vous du pays et du nombre d'habitants</div>
                                        <table id="city_table" class="jb-cty-cstm-list jb-stgs-city-list">
                                            <tr class="jb-cty-cstm-list-hdr">
                                                <th>Ville</th>
                                                <th>Pays</th>
                                                <th>Population</th>
                                            </tr>
                                            <?php // for($i=0;$i<10;$i++) : ?>
<!--                                            <tr class="jb-cty-list-elt jb-city-list-trg" data-obj="cstm" data-ci="cyid" data-ii="cyid" data-cycop=1>
                                                <td class="jb-cy-list-cynm">Ville this_hide</td>
                                                <td class="jb-cy-list-cycn">FR</td>
                                                <td class="jb-cy-list-cypop">10<?php // echo $i; ?></td>
                                            </tr> -->
                                            <?php // endfor; ?>
                                        </table>
                                        <div id="cysrh-cstm-spnr-mx" class="jb-stgs-cty-cstm-spnr this_hide">
                                            <img src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
                                        </div>
                                    </div>
                                    <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="city"></p>
<!--                                    <div id="customlist"></div>
                                    <div id="customlistmulti"></div>
                                    <div id="pfl_city_tooltip_wrapper">
                                        <div id='pfl_city_tooltip'>Plusieurs villes portent ce nom. Veuillez pr&eacute;ciser en utilisant le tableau de suggestions.<span class='clear'></span></div>
                                    </div>
                                    
                                    <div id="pfl_city_table_wrapper">
                                        <div id='pfl_city_table_details'>Il existe plusieurs villes de ce nom. Pour trouver la votre, aidez-vous du pays et du nombre d'habitants</div>
                                        <table id="pfl_city_table">
                                            <tr>
                                                <th>Ville</th>
                                                <th>Pays</th>
                                                <th>Population</th>
                                            </tr>
                                        </table>
                                    </div>-->
                                    
                                </div>
                                <div id="pfl_profile_submit" class="pfl_form_group pfl_submit_group">
                                    <div id="pfl_ph_profile_wrapper" class="pfl_submit_ph_wrapper">
                                        <span class="stgs-pfl-spnr jb-stgs-pfl-spnr this_hide">
                                            <img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                                        </span>
                                        <a id="pfl_ph_profile" class="pfl_submit_ph jb-stgs-submit" data-target="profile" href="javascript:;">Sauvegarder</a>
                                    </div>
                                </div>
                            </form>
                            <span class="clear"></span>
                        </div>
                        <?php if ( in_array($section,["account","compte"]) ) : ?>
                        <div id="pfl_form_account_div" class="pfl_middle_content jb-stgs-wdw active" data-wdw="account">
                        <?php else : ?>    
                        <div id="pfl_form_account_div" class="pfl_middle_content jb-stgs-wdw this_hide" data-wdw="account">
                        <?php endif; ?>
                            <div class="pfl_middle_title">Param&egrave;tres du compte</div>
                            <div class="pfl_middle_subtitle">Param&egrave;tres g&eacute;n&eacute;raux</div>
                            <!--<div class="pfl_top_right_link"><a href="#">Je suis une personne c&eacute;l&egrave;bre</a></div>-->
                            <form id="pfl_form_account_classic" class="jb-stgs-form-acc" action="toto.php"  method="POST">
                                <div class="clear_input_wrapper">
                                    <a class="stgs-back-orign jb-stgs-back-orign" data-target="jb-stgs-form-acc" href="javascript:;" title="Valeurs d'origine">
                                        <img height="18px" src="{wos/sysdir:img_dir_uri}/r/arr-bck.png" />
                                    </a>
                                    <a class="clear_input_link jb-stgs-clear-form" data-target="jb-stgs-form-acc" href="javascript:;" title="Vider le formulaire">x</a>
                                </div>                            
                                <div class="pfl_form_group">
                                    <div id="" class="stgs-fld-asd-spnr jb-stgs-psd-ipt-spnr this_hide"></div>
                                    <label class="pfl_form_label" for="pfl_input_nickname">Pseudo</label>
                                    <input id="pfl_input_nickname" class="pfl_form_input stgs-fld-errchk jb-stgs-acc-fld jb-stgs-acc-fld-psd" data-ft="pseudo" spellcheck="false" type="text" value="{wos/datx:stgs_bdz_psd}">
                                    <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="pseudo"></p>
<!--                                    <div id='pfl_nickname_taken_wrapper'>
                                        <div id='pfl_nickname_taken'>Ce pseudo est d&eacute;j&agrave; pris par un autre utilisateur.<span class='clear'></span></div>
                                    </div>-->
                                </div>
                                <div class="pfl_form_group">
                                    <div id="" class="stgs-fld-asd-spnr jb-stgs-eml-ipt-spnr this_hide"></div>
                                    <label class="pfl_form_label" for="pfl_input_email">Email</label>
                                    <input id="pfl_input_email" class="pfl_form_input stgs-fld-errchk jb-stgs-acc-fld  jb-stgs-acc-fld-psd" data-ft="email" spellcheck="false" type="text" value="{wos/datx:stgs_bdz_em}" />
                                    <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="email"></p>
                                    <div id='pfl_email_basestatus' data-status=""></div>
<!--                                    <div id='pfl_email_taken_wrapper'>
                                        <div id='pfl_email_taken'>Cette adresse mail est d&eacute;j&agrave; utilis&eacute;e.<span class='clear'></span></div>
                                    </div>-->
                                </div>
                                <div class="pfl_form_group">
                                    <label class="pfl_form_label">Langue</label>
                                    <div class="pfl_select_wrapper">
                                        <select id="pfl_lang" name="lang" class="stgs-fld-errchk jb-stgs-acc-fld" data-ft="lang" tabindex="1">
                                            <!-- <option value="en" <?php echo ( $acc_lang && $acc_lang === "de" ) ? "selected" : ""; ?> >Deutsch</option>
                                            <option value="en" <?php echo ( $acc_lang && $acc_lang === "en" ) ? "selected" : ""; ?> >English</option>
                                            <option value="en" <?php echo ( $acc_lang && $acc_lang === "es" ) ? "selected" : ""; ?> >Español</option> -->
                                            <option value="fr" <?php echo ( $acc_lang && $acc_lang === "fr" ) ? "selected" : ""; ?> >Fran&ccedil;ais</option>
                                        </select>
                                    </div>
                                    <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="lang"></p>
                                </div>
                                <div id="pfl_account_submit" class="pfl_form_group pfl_submit_group">
                                    <div id="stgs-acc-sub-wpr" class="pfl_submit_ph_wrapper">
                                        <span class="stgs-pfl-spnr jb-stgs-acc-spnr this_hide">
                                            <img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                                        </span>
                                        <a id="pfl_ph_account" class="pfl_submit_ph jb-stgs-submit" data-target="account" href="javascript:;">Sauvegarder</a>
                                    </div>
    <!--                                <input type="submit" class="pfl_submit_btn" id="pfl_submit_account_classic" value="Sauvegarder">-->
                                    <div id='pfl_email_changewarning'>
                                        <div class='pfl_email_changewarning_txt'>Vous avez modifié votre email. Si vous sauvegardez en l'état, toutes les procédures de vérification liées à votre ancienne adresse seront annulées et vous devrez confirmer votre nouvelle adresse. Voulez-vous confirmer ?</div>
                                        <div class='pfl_email_changewarning_txt'><span id='pfl_email_reconfirm' class="pseudolink">Oui, je reconfirmerais mon email</span> - <span id='pfl_email_cancel' class='pseudolink'>Annuler</span></div>
                                    </div>
                                </div>
                            </form>
                            <div class="pfl_form_separator"></div>
                            <form id="pfl_form_account_passwd" class="jb-stgs-form-pwd" action="">
                                <div class="pfl_middle_subtitle">Changement du mot de passe</div>
                                <div class="clear_input_wrapper">
                                    <a class="clear_input_link jb-stgs-clear-form" data-target="jb-stgs-form-pwd" href="javascript:;" title="Vider le formulaire">x</a>
                                </div>
                                <div class="pfl_form_group">
                                    <label class="pfl_form_label" for="pfl_input_oldpw">Mot de passe actuel</label>
                                    <input id="pfl_input_oldpw" class="pfl_form_input stgs-fld-errchk jb-stgs-pwd-fld" data-ft="opassword" type="password">
                                </div>
                                <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="opassword"></p>
                                <div id="pfl_newpwd_margin" class="pfl_form_group" >
                                    <div id="pfl_passwd_str">
                                        <span class="">
                                            <b class="pfl_passwd_fill jb-pwd-strength"></b>
                                        </span>
                                    </div>
                                    <label class="pfl_form_label" for="pfl_input_newpw">Nouveau mot de passe</label>
                                    <input id="pfl_input_newpw"  class="pfl_form_input stgs-fld-errchk jb-stgs-pwd-fld jb-stgs-npwd-ipt" data-ft="npassword" type="password">
                                </div>
                                <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="npassword"></p>
                                <div class="pfl_form_group stgs-pwd-grp">
                                    <label class="pfl_form_label" for="pfl_input_pwconf">Confirmation</label>
                                    <input id="pfl_input_newpwconf" class="pfl_form_input stgs-fld-errchk jb-stgs-pwd-fld"  data-ft="npassword_c" type="password">
                                </div>
                                <p class="stgs-err-subhint jb-stgs-err-sbh" data-target="npassword_c"></p>
                                <div class="pfl_form_group pfl_submit_group">
                                    <span class="stgs-pfl-spnr jb-stgs-pwd-spnr this_hide">
                                        <img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                                    </span>
                                    <a id="pfl_submit_account_passwd" class="pfl_submit_btn jb-stgs-submit" data-target="password" href="javascript:;">Sauvegarder</a>
                                </div>
                            </form>
                        </div>
                        <!-- ###### -->
                        <?php if ( in_array($section,["security","securite"]) ) : ?>
                        <div id="pfl_form_security_div" class="pfl_middle_content jb-stgs-wdw active" data-wdw="security">
                        <?php else : ?>    
                        <div id="pfl_form_security_div" class="pfl_middle_content jb-stgs-wdw this_hide" data-wdw="security">
                        <?php endif; ?>
                            <div class="pfl_middle_title">Param&egrave;tres de s&eacute;curit&eacute;</div>
                            <div class="pfl_middle_subtitle">S&eacute;curit&eacute;s &agrave; la connexion</div>
                            <form id="pfl_form_security" action="#">
                                <div class="select_deselect_wrapper">
                                    <a href="#" class="select_deselect" id="secu_select">Tout s&eacute;lectionner</a> / <a href="#" id="secu_deselect" class="select_deselect">Tout d&eacute;selectionner</a>
                                </div>
                                <div class="pfl_form_group">
                                    <label class="pfl_form_label pfl-form-label-secu" for="pfl_input_cowithpseudo">Autoriser la connexion via pseudo</label>
                                    <?php 
                                        $sc1 = "{wos/datx:stgs_bdz_ecwpsd}";
                                        if ( $sc1 === "1" ) : 
                                    ?>
                                    <input id="pfl_input_cowithpseudo" class="pfl_form_input jb-stgs-sec-ecwpsd jb-stgs-seclog-fld" data-ft="secu_seclog_ecwpsd" type="checkbox" checked>
                                    <?php else : ?>
                                    <input id="pfl_input_cowithpseudo" class="pfl_form_input jb-stgs-sec-ecwpsd jb-stgs-seclog-fld" data-ft="secu_seclog_ecwpsd" type="checkbox">
                                    <?php endif; ?>
                                    <span class="pfl_input_explaination pfl_checkboxes_explaination">
                                        Vous permet de vous connecter avec votre pseudo, en plus de votre adresse mail.
                                    </span>
                                </div>
<!--                                <div class="pfl_form_group">
                                    <label class="pfl_form_label pfl-form-label-secu" for="pfl_input_thirdcriteria">Activer la v&eacute;rification via date de naissance</label>
                                    <?php 
                                        $sc2 = "{wos/datx:stgs_bdz_ecwpsd}";
                                        if ( $sc2 === "1" ) : 
                                    ?>
                                    <input id="pfl_input_thirdcriteria" class="pfl_form_input" type="checkbox" checked>
                                    <?php else : ?>
                                    <input id="pfl_input_thirdcriteria" class="pfl_form_input" type="checkbox">
                                    <?php endif; ?>
                                    <span class="pfl_input_explaination pfl_checkboxes_explaination">
                                        Pour plus de s&eacute;curit&eacute;, vous pouvez choisir d&apos;utiliser votre date de naissance comme information de connexion, en plus de votre identifiant et de votre mot de passe.
                                    </span>
                                </div>-->
                                    <!--  Trop complexe pour le rendre fonctionnel dans les temps-->
<!--                                <div class="pfl_form_group">
                                    <label class="pfl_form_label pfl-form-label-secu" for="pfl_input_thirdcriteria">Me prévenir par email à chaque connexion</label>
                                    <?php 
                                        $sc3 = "{wos/datx:stgs_bdz_nlg}";
                                        if ( $sc3 === "1" ) : 
                                    ?>
                                    <input id="pfl_input_thirdcriteria" class="pfl_form_input" type="checkbox" checked>
                                    <?php else : ?>
                                    <input id="pfl_input_thirdcriteria" class="pfl_form_input" type="checkbox">
                                    <?php endif; ?>
                                    <span class="pfl_input_explaination pfl_checkboxes_explaination">
                                        Vous serez averti à chaque fois qu'une connexion sera établie sur votre compte.
                                        Vous recevrez un email contenant les principales informations relatives à cette connexion.
                                    </span>
                                </div>-->
                                <div id="stgs-sec-sub-wpr" class="pfl_form_group pfl_submit_group">
                                    <span class="stgs-pfl-spnr jb-stgs-seclog-spnr this_hide">
                                        <img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                                    </span>
                                    <a id="pfl_submit_secu" class="pfl_submit_btn jb-stgs-submit" data-target="secu_login" href="javascript:;">Sauvegarder</a>
                                </div>
                            </form>
                                <div class="pfl_form_separator"></div>
                            <form id="pfl_form_security_locks" action="#">
                                <div id="pfl_delete_account">
                                    <a class="jb-stgs-lmenu" data-mn="delete" href="/settings/security/account">Supprimer mon compte</a>
                                </div>
                            </form>
                        </div>
                        <!-- ###### -->
                        <?php if ( in_array($section,["delete","suppression"]) ) : ?>
                        <div id="pfl_delete_account_div" class="pfl_middle_content jb-stgs-wdw active" data-wdw="delete">
                        <?php else : ?>    
                        <div id="pfl_delete_account_div" class="pfl_middle_content jb-stgs-wdw this_hide" data-wdw="delete">
                        <?php endif; ?>
                            <div id="pfl_delete_backlink">
                                <a class="jb-stgs-lmenu" data-mn="security" href="/settings/security" title="Retour" alt="Retour">x</a>
                            </div>
                            <div class="pfl_middle_title">Vous nous quittez ... ?</div>
                            <form id="stgs-del-form-mx" class="jb-stgs-del-form" action="" autocomplete="off">
                                <div id="stgs-del-intro-mx">
                                    <p id="stgs-del-ibeta">
                                        Vous utilisez une version dite <b>« beta »</b> de Trenqr. Elle est succeptible d'être instable et ne comprend pas toutes les fonctionnalités que nous voulons vous offrir.
                                        La version finale de Trenqr vous apportera plus de richesse, de stabilité et de diversité.
                                    </p>
                                    <p id="stgs-del-intro">
                                        Notre but est de créer un produit qui saura plaire au plus grand nombre, dans l'esprit que nous nous sommes fixé.
                                        Nous expliquer les raisons de votre départ et plus, nous permettra d'améliorer Trenqr et nous l'espérons, vous revoir parmi nous.
                                    </p>
                                </div>
                                <div class="stgs-del-minfm-grp jb-stgs-del-fld" data-ft="hikw">
                                    <h2 class="">Dites nous comment avez vous connu Trenqr : </h2>
                                    <div class="stgs-del-minfm-body">
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-hikw-1" class="jb-stgs-del-hikw" type="radio" name="sgtgs-del-hikw" value="SCHOOL">
                                            <label class="pfl_radio_label" for="sgtgs-del-hikw-1">{wos/deco:_HIKW_SCHOOL}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-hikw-2" class="jb-stgs-del-hikw" type="radio" name="sgtgs-del-hikw" value="WORKPL">
                                            <label class="pfl_radio_label" for="sgtgs-del-hikw-2">{wos/deco:_HIKW_WORKPL}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-hikw-3" class="jb-stgs-del-hikw" type="radio" name="sgtgs-del-hikw" value="RELATIVE">
                                            <label class="pfl_radio_label" for="sgtgs-del-hikw-3">{wos/deco:_HIKW_RELATIVE}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-hikw-4" class="jb-stgs-del-hikw" type="radio" name="sgtgs-del-hikw" value="SOCNET">
                                            <label class="pfl_radio_label" for="sgtgs-del-hikw-4">{wos/deco:_HIKW_SOCNET}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-hikw-5" class="jb-stgs-del-hikw" type="radio" name="sgtgs-del-hikw" value="WEBSIT">
                                            <label class="pfl_radio_label" for="sgtgs-del-hikw-5">{wos/deco:_HIKW_WEBSIT}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-hikw-6" class="jb-stgs-del-hikw" type="radio" name="sgtgs-del-hikw" value="MEDIA">
                                            <label class="pfl_radio_label" for="sgtgs-del-hikw-6">{wos/deco:_HIKW_MEDIA}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="stgs-del-minfm-grp jb-stgs-del-fld" data-ft="yilv">
                                    <h2 class="">Expliquez nous les raisons de votre départ</h2>
                                    <div class="stgs-del-minfm-body">
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-1" type="radio" name="sgtgs-del-yilv" value="MSFUNC">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-1">{wos/deco:_YILV_MSFUNC}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-2" type="radio" name="sgtgs-del-yilv" value="MSPHONE">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-2">{wos/deco:_YILV_MSPHONE}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-3" type="radio" name="sgtgs-del-yilv" value="MSENTOURAGE">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-3">{wos/deco:_YILV_MSENTOURAGE}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-4" type="radio" name="sgtgs-del-yilv" value="ERRNBG">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-4">{wos/deco:_YILV_ERRNBG}</label>
                                        </div> 
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-5" type="radio" name="sgtgs-del-yilv" value="MSFAV">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-5">{wos/deco:_YILV_MSFAV}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-6" type="radio" name="sgtgs-del-yilv" value="DESIGN">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-6">{wos/deco:_YILV_DESIGN}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-7" type="radio" name="sgtgs-del-yilv" value="CONCEPT">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-7">{wos/deco:_YILV_CONCEPT}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-8" type="radio" name="sgtgs-del-yilv" value="HTRUN">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-8">{wos/deco:_YILV_HTRUN}</label>
                                        </div>
                                        <div class="pfl_radio_group">
                                            <input id="sgtgs-del-yilv-9" class="jbsgtgs-del-yilv-ot" type="radio" name="sgtgs-del-yilv" value="OTHER">
                                            <label class="pfl_radio_label" for="sgtgs-del-yilv-9">Autre raison</label>
                                            <div id="pfl_delete_reason_wrapper" class="jb-sgtgs-del-yilv-ot-mx this_hide">
                                                <span class="stgs-del-lmt-chr">242 caractères</span>
                                                <textarea id="stgs-del-y-other-xpln" class="jb-stgs-del-y-other-xpln" maxlength="242"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="stgs-del-minfm-grp jb-stgs-del-fld" data-ft="ilbbif">
                                    <h2>Dites nous en quelques mots, ce qui pourrait vous faire revenir sur Trenqr (Facultatif)</h2>
                                    <div class="stgs-del-minfm-body">
                                        <span class="stgs-del-lmt-chr">242 caractères</span>
                                        <textarea id="" class="stgs-del-free-xpln jb-stgs-del-free-xpln" maxlength="242"></textarea>
                                    </div>
                                </div>
                                
<!--                                <div id='pfl_delete_account_panel'>
                                        <p>Saviez-vous que vous pouviez nous transmettre votre avis et votre ressenti sur Trenqr ?</p>
                                        <p>Si vous avez &eacute;galement des suggestions, n&apos;h&eacute;sitez pas &agrave; nous en faire part, car c&apos;est avec vous que nous allons am&eacute;liorer ce site !</p>
                                        <p>Vous pouvez le faire en suivant <a href='faq.php'>ce lien</a>.</p>
                                </div>-->
                                
<!--                                <div class="pfl_middle_subtitle">Confirmation de la suppression</div>
                                <div class="pfl_delete_wrapper">
                                    <label class='pfl_form_label pfl_delete_code_label' for='pfl_delete_code'>Mot de passe</label>
                                    <input data-secu='ulock' type='password' class='lock_error_checker' id='pfl_delete_code'>
                                </div>-->
                                <div id="stgs-del-lgls-mx">
                                    <input id="stgs-del-lgls-ipt" class="jb-stgs-del-fld" data-ft="delcf" type="checkbox"/>
                                    <label class="jb-stgs-del-lgls-ipt" for="stgs-del-lgls-ipt">
                                        Je comprends que mon compte ainsi que toutes les informations et contenus liés seront définitivement supprimés selon les dispositions établies au niveau de la <a href="javascript:;">politique de gestion des données</a>.
                                    </label>    
                                </div>
                                <div id="stgs-del-form-err-mx" class="jb-stgs-del-f-mx this_hide">
                                    <p id="stgs-del-form-errmsg" class="jb-stgs-del-f-emsg"></p>
                                </div>
                                <div class="pfl_form_group pfl_submit_group">
                                    <span class="stgs-pfl-spnr stgs-pfl-del-spnr jb-stgs-del-spnr this_hide">
                                        <img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                                    </span>
                                    <input id="pfl_delete_account_submit" class="pfl_submit_btn jb-stgs-submit" data-target="delete" type="submit"  value="Supprimer mon compte">
                                </div>
                            </form>
                        </div>
                        <?php if ( in_array($section,["about","apropos"]) ) : ?>
                        <div id="pfl_content_about" class="pfl_middle_content jb-stgs-wdw active" data-wdw="about">
                        <?php else : ?>    
                        <div id="pfl_content_about" class="pfl_middle_content jb-stgs-wdw this_hide" data-wdw="about">
                        <?php endif; ?>
                            <div class="pfl_middle_title"><h1>&Agrave; propos</h1></div>
                            <!--<p>Bienvenue sur Trenqr</p>-->
                            <p id="pfl_about_desc">{wos/datx:stgs_bdz_pddesc_st}Bienvenue sur Trenqr</p>
                            <p><span class="bold">Libell&eacute; : </span><a href="javascript:;">{wos/datx:stgs_bdz_pdlib}</a></p>
                            <p><span class="bold">Version : </span><span>{wos/datx:stgs_bdz_pdver}</span></p>
                            <p><span class="bold">Statut : </span><span>{wos/datx:stgs_bdz_pdrm}</span></p>
                            <div id="pfl_btn_team_wrapper"></div>
                            <span class="clear"></span>
                        </div>
                        {wos/dvt:profile_footer}
                    </div>
                    <div id="pfl_leftbar">
                        <div id="pfl_menu" class="clearfix2">
                            <div id="pfl_menu_separator"></div>
                            <ul id="pfl-menu-list">
                                <!-- FAIRE EVOLUER VERS :  Sécurité & données -->
                                <?php if ( in_array($section,["profile","profil"]) ) : ?>
                                <li><a id="pfl_leftlink_profile" class="stgs-lmenu jb-stgs-lmenu active" data-mn="profile" href="/{wos/datx:stgs_bdz_psd}/settings/profile">Profil</a></li>
                                <li><a id="pfl_leftlink_account" class="stgs-lmenu jb-stgs-lmenu" data-mn="account" href="/{wos/datx:stgs_bdz_psd}/settings/account">Compte</a></li>
                                <li><a id="pfl_leftlink_security" class="stgs-lmenu jb-stgs-lmenu" data-mn="security" href="/{wos/datx:stgs_bdz_psd}/settings/security">S&eacute;curit&eacute;</a></li>
                                <li><a id="pfl_leftlink_about" class="stgs-lmenu jb-stgs-lmenu" data-mn="about" href="/{wos/datx:stgs_bdz_psd}/settings/about">&Agrave; propos</a></li>
                                <?php elseif ( in_array($section,["account","compte"]) ) : ?>
                                <li><a id="pfl_leftlink_profile" class="stgs-lmenu jb-stgs-lmenu" data-mn="profile" href="/{wos/datx:stgs_bdz_psd}/settings/profile">Profil</a></li>
                                <li><a id="pfl_leftlink_account" class="stgs-lmenu jb-stgs-lmenu active" data-mn="account" href="/{wos/datx:stgs_bdz_psd}/settings/account">Compte</a></li>
                                <li><a id="pfl_leftlink_security" class="stgs-lmenu jb-stgs-lmenu" data-mn="security" href="/{wos/datx:stgs_bdz_psd}/settings/security">S&eacute;curit&eacute;</a></li>
                                <li><a id="pfl_leftlink_about" class="stgs-lmenu jb-stgs-lmenu" data-mn="about" href="/{wos/datx:stgs_bdz_psd}/settings/about">&Agrave; propos</a></li>
                                <?php elseif ( in_array($section,["security","securite"]) ) : ?>
                                <li><a id="pfl_leftlink_profile" class="stgs-lmenu jb-stgs-lmenu" data-mn="profile" href="/{wos/datx:stgs_bdz_psd}/settings/profile">Profil</a></li>
                                <li><a id="pfl_leftlink_account" class="stgs-lmenu jb-stgs-lmenu" data-mn="account" href="/{wos/datx:stgs_bdz_psd}/settings/account">Compte</a></li>
                                <li><a id="pfl_leftlink_security" class="stgs-lmenu jb-stgs-lmenu active" data-mn="security" href="/{wos/datx:stgs_bdz_psd}/settings/security">S&eacute;curit&eacute;</a></li>
                                <li><a id="pfl_leftlink_about" class="stgs-lmenu jb-stgs-lmenu" data-mn="about" href="/{wos/datx:stgs_bdz_psd}/settings/about">&Agrave; propos</a></li>
                                <?php elseif ( in_array($section,["about","apropos"]) ) : ?>
                                <li><a id="pfl_leftlink_profile" class="stgs-lmenu jb-stgs-lmenu" data-mn="profile" href="/{wos/datx:stgs_bdz_psd}/settings/profile">Profil</a></li>
                                <li><a id="pfl_leftlink_account" class="stgs-lmenu jb-stgs-lmenu" data-mn="account" href="/{wos/datx:stgs_bdz_psd}/settings/account">Compte</a></li>
                                <li><a id="pfl_leftlink_security" class="stgs-lmenu jb-stgs-lmenu" data-mn="security" href="/{wos/datx:stgs_bdz_psd}/settings/security">S&eacute;curit&eacute;</a></li>
                                <li><a id="pfl_leftlink_about" class="stgs-lmenu jb-stgs-lmenu active" data-mn="about" href="/{wos/datx:stgs_bdz_psd}/settings/about">&Agrave; propos</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div id="pfl_left_hint" class="jb-pfl-lm-hint"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
            <?php endif; ?>
    </div>
        
    <div class="pg-sts jb-pg-sts this_hide">
        <span class="jb-pg-sts-txt"></span>
    </div>
        
    <!-- JS LOAD -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
     
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/underscore-min.js"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxdate.enty.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/csam/notify.csam.js?{wos/systx:now}"></script>
    
    <script src="{wos/sysdir:script_dir_uri}/w/c.c/browserDetect.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/c.c/langselect.js?{wos/systx:now}"></script>
    
    <script src="{wos/sysdir:script_dir_uri}/r/d/header.d.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/csam/friends.csam.js?{wos/systx:now}" defer></script>
    <script src="{wos/sysdir:script_dir_uri}/r/csam/bugzy.csam.js?{wos/systx:now}" defer></script>
    <script src="{wos/sysdir:script_dir_uri}/r/csam/ec.csam.js?{wos/systx:now}"></script>
    
    <script src="{wos/sysdir:script_dir_uri}/r/s/settings.s.js?{wos/systx:now}"></script>
    
<!--    <script src="{wos/sysdir:script_dir_uri}/w/d/pfl_nav.d.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/d/pfl_form.d.js?{wos/systx:now}"></script>-->
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/pfl_ajax.d.js?{wos/systx:now}"></script>-->
</div>