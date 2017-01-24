<body>
    {wos/dvt:header_default}
    <div id="ins_theater"></div>
    <div id="ins_screensize">
        <div id="ins_content">
            <div class="ins_overlay" id="ins_overlay_famous">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin interdum diam eu gravida congue. Morbi id accumsan eros. Nam porta eros posuere elit dapibus, non dignissim elit iaculis. Ut dictum nisl ut enim lobortis, non pharetra ligula tincidunt. Quisque aliquam ultricies diam faucibus tempus. Nulla sit amet ultrices risus, eu vestibulum dolor. Maecenas convallis orci sem, vitae euismod augue ullamcorper ut. Donec nec felis non nisl varius imperdiet eu a nibh. Donec et tincidunt felis. Cras dui lacus, blandit eu egestas vitae, vestibulum quis ante. Nullam et ipsum in dui tempor blandit at vitae tortor. Phasellus hendrerit tortor placerat libero luctus, ut hendrerit erat euismod. Vestibulum ullamcorper volutpat sapien in laoreet. Mauris lacinia iaculis massa ut auctor. Morbi euismod purus at tortor scelerisque malesuada.<span class="clear"></span></div>
            <div class="ins_overlay" id="ins_overlay_beta"><p>Merci de vous impliquer dans ce projet par votre inscription :).</p><p>Cependant, n'oubliez pas que ce site est toujours en p&eacute;riode de b&ecirc;ta-test, et que certains bugs peuvent encore &ecirc;tre pr&eacute;sents.</p><p>N&apos;h&eacute;sitez pas &agrave; nous les reporter afin qu&apos;ils soient corrig&eacute;s le plus rapidement possible !<div class='ins_fuse'><div class='ins_fuse_fill'></div></div></div>
            <div class="ins_overlay" id="ins_overlay_knownuser">Cette adresse email est d&eacute;j&agrave; li&eacute;e &agrave; une proc&eacute;dure d&apos;inscription.<span class="clear"></span></div>
            <div class="ins_overlay" id="ins_overlay_report">Le report de votre inscription a bien &eacute;t&eacute; pris en compte. Nous vous avons envoy&eacute; un email contenant les instructions &agrave; suivre pour poursuivre votre inscription.<span class="clear"></span></div>
            <div class="ins_overlay" id="ins_overlay_resume">Votre adresse email est d&eacute;j&agrave; li&eacute;e &agrave; une proc&eacute;dure de pr&eacute;inscription. <a id="ins_resume_link">Cliquez ici</a> pour reprendre votre inscription l&agrave; o&ugrave; vous en &eacute;tiez.<span class="clear"></span></div>
            <div id="ins_left">
                <div id="ins_tooltip_wrapper">
                    <div id="ins_passwd_tooltip"><span class="clear"></span></div>
                    <span class="clear"></span>
                </div>
                <div id="ins_title">
                    <div id="ins_title_content">
                        Cr&eacute;ation de compte
                    </div>
                </div>
                <form id="ins_form" action="standby.php" method="POST">
                    <div id="ins_first_grp" class="ins_group">
                        <a id="ins_famous" href="#">Personalit&eacute;s ou Personnes morales</a>
                        <div class="ins_group_label" id="label_fullname" for="ins_input_fullname">Nom complet</div>
                        <input spellcheck="false" data-su="ulock" class="ins_group_input ins_error_lock" id="ins_input_fullname" type="text" value="{wos/datx:fn}">
                    </div>
                    <div class="ins_group" id="ins_birthday_group">
                        <div class="ins_group_label" id="label_birthday">Date de naissance</div>
                        <div id="ins_birthday_wrapper" data-su="ulock" class="ins_date_group ins_error_lock" tabindex="2">
                            <div class='ins_select_wrapper'>
                                <select name="birthday_day" id="day" class="ins_group_select">
                                    <option value="init" selected="1">Jour</option>
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
                                <select name="birthday_month" id="month" class="ins_group_select">
                                    <option value="init" selected="1">Mois</option>
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
                            <div class='ins_select_wrapper'>
                                <select name="birthday_year" id="year" class="ins_group_select">
                                    <option value="init" selected="1">Ann&eacute;e</option>
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
                    <div class="ins_group" id="ins_gender_group">
                        <div class="ins_group_label" id="label_gender">Genre</div>
                        <div id="ins_gender_wrapper" data-su="ulock" class="ins_error_lock" tabindex="3">
                            <div class="ins_gender_radiobutton"><input id="ins_radio_m" name="gender" type="radio" value="m" ><label for="ins_radio_m">Homme</label></div>
                            <div class="ins_gender_radiobutton"><input id="ins_radio_f" name="gender" type="radio" value="f" ><label for="ins_radio_f">Femme</label></div>
                        </div>
                    </div>
                    <div class="ins_group" id='ins_city'>
                        <div class="spinner ins_spinner" id="ins_city_spinner"></div>
                        <div class="ins_group_label" id="label_city">Ville</div>
                        <input spellcheck="false" autocomplete="off" data-check="true" data-cc="-1" data-su="ulock" id="ins_input_city" type="text" class="ins_group_input ins_error_lock">
                        <div id='customlist'></div>
                        <div id='customlistmulti'></div>
                        <div id='city_tooltip_wrapper'>
                            <div id='city_tooltip'>Plusieurs villes portent ce nom. Veuillez pr&eacute;ciser en utilisant le tableau de suggestions.<span class='clear'></span></div>
                        </div>
                        <div id="city_table_wrapper">
                            <div id='city_table_details'>Il existe plusieurs villes de ce nom. Pour trouver la votre, aidez-vous du pays et du nombre d'habitants</div>
                            <table id="city_table">
                                <tr>
                                    <th>Ville</th>
                                    <th>Pays</th>
                                    <th>Population</th>
                                </tr>
                            </table>
                        </div>
                        <span class='clear'></span>
                    </div>
                    <div id="ins_form_separator"></div>
                    <div class="ins_group">
                        <div class="spinner ins_spinner" id="ins_pseudo_spinner"></div>
                        <div class="ins_group_label" id="label_nickname">Pseudo</div>
                        <input spellcheck="false" data-su="ulock" id="ins_input_nickname" type="text" class="ins_group_input ins_error_lock" value="{wos/datx:p}">
                    </div>
                    <div class="ins_group">
                        <div class="spinner ins_spinner" id="ins_email_spinner"></div>
                        <label class="ins_group_label" id="label_mail" for="ins_input_mail">Adresse mail</label>
                        <input data-su="ulock" id="ins_input_mail" type="text" class="ins_group_input ins_error_lock" value="{wos/datx:em}">
                    </div>
                    <div class="ins_group">
                        <label class="ins_group_label" id="label_mail_confirmation" for="ins_input_mail_confirmation">Confirmation de l'adresse mail</label>
                        <input data-su="ulock" id="ins_input_mail_confirmation" type="text" class="ins_group_input ins_error_lock">
                    </div>
                    <div class="ins_group">
                        <span id="ins_passwd_strengh"><b class="passwd_str_fill"></b></span>
                        <label class="ins_group_label" id="label_passwd" for="ins_input_passwd">Mot de passe</label>
                        <input data-su="ulock" id="ins_input_passwd" type="password" class="ins_group_input ins_error_lock">
                    </div>
                    <div class="ins_group">
                        <label class="ins_group_label" id="label_passwd_confirmation" for="ins_input_passwd_confirmation">Confirmation du mot de passe</label>
                        <input data-su="ulock" id="ins_input_passwd_confirmation" type="password" class="ins_group_input ins_error_lock">
                    </div>
                    <div class="ins_group" id="ins_main_computer">
                        <div id="ins_mc_tooltip_wrapper">
                            <div id="ins_mc_tooltip">Nous vous d&eacute;conseillons de cocher cette case si vous ne vous trouvez pas sur votre ordinateur personnel.<span class="clear"></span></div>
                        </div>
                        <div id='mc_megabox'>
                        <input id="box_main_computer" type="checkbox">
                        <label id="label_main_computer" for="box_main_computer">Ceci est mon ordinateur principal (facultatif)</label>
                        </div>
                    </div>
                    <div  class="ins_group">
                        <div id="ins_group_cgu" class="ins_error_lock" data-su="ulock">
                        <input id="ins_cgu" type="checkbox">
                        <label id="label_cgu" for="ins_cgu">J'accepte les <a href="#">conditions g&eacute;n&eacute;rales d'utilisation</a> et l'utilisation des <a href='#'>cookies</a>, et je certifie avoir pris connaissance de la <a href="#">politique de confidentialit&eacute;</a>.</label>
                        </div>
                    </div>
                    <input id="ins_socialarea" type="hidden" value="fr">
                    <div class="ins_group"><input id="ins_form_submit" type="submit" value="S'inscrire"></div>        
                </form>
            </div>

            <div id="ins_right">
                <div id="ins_right_top">
                    <p id="ins_info_msg">Message de bienvenue.<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit. <a href="#">Aliquam faucibus</a> justo eu mauris pulvinar, eget pharetra leo adipiscing. Fusce ornare ligula quis mi cursus, at ultricies turpis cursus. Duis quis placerat lorem, quis iaculis elit. Nunc eros mauris, tincidunt blandit dolor id, consequat varius purus. Praesent erat ante, dignissim vitae neque eget, ullamcorper commodo nisi. Mauris ac velit justo. Aliquam pulvinar, neque vel tincidunt auctor, lectus magna tristique ligula, eget blandit odio est non tellus. Cras iaculis dui lectus, nec molestie metus consequat vel.</p>
                    <span class="clear"></span>
                </div>
                <div id="ins_right_middle">
                    <div id="ins_right_middle_link">
                        <a id="ins_report" href="#">Reporter l'inscription</a>
                        <div id="ins_delay_tooltip_wrapper">
                            <div id="ins_delay_tooltip"><span class="clear"></span></div>
                        </div>
                    </div>
                </div>
                <div id="ins_right_bottom">
                    <p id="ins_error_msg"></p>
                    <span class="clear"></span>
                </div>
            </div>
            {wos/dvt:footer_default}
        </div>
    </div>
    <!-- JS LOAD -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/c.c/fr.dolphins.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/d/ins_form_validator.d.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/d/ins_overlay.d.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/c.c/langselect.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/d/ins_ajax_validation.d.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/d/ins_delay.d.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/d//ins_form_citycomplete.d.js?{wos/systx:now}"></script>
</body>