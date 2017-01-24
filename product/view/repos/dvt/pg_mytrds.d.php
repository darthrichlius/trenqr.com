<div id="mytrds-mx" class="jb-mytrds-mx">
    <div id="mytrds-hdr">
        <div id="mytrds-hdr-fltrs">
            <span id="mytrds-hdr-f-lbl">Trier par : </span>
            <label class="mytrds-hdr-fil jb-mytrds-hdr-fil" href="javascipt:;">
                <input class="mytrds-hdr-fil-ipt jb-mytrds-hdr-fil-ipt" value="mine" type="checkbox" checked />
                Les Salons de @{wos/datx:oupsd}
            </label>
            <label  class="mytrds-hdr-fil jb-mytrds-hdr-fil" href="javascipt:;">
                <input class="mytrds-hdr-fil-ipt jb-mytrds-hdr-fil-ipt" value="abo" type="checkbox" checked />
                Les abonnéments de @{wos/datx:oupsd}
            </label>
        </div>
    </div>
    <div id="mytrds-body" class="jb-mytrds-body">
        <?php 
            $x = NULL;
            set_error_handler('exceptions_error_handler');
            try {
                $t = "{wos/datx:pg_trends_datas}";
                //$t = NULL; //DEV, TEST, DEBUG
                $x = unserialize(base64_decode($t));
//                                            $x = array_reverse($x);

                restore_error_handler();

            } catch (Exception $exc) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

                $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
            }
            
            /*
             * [DEPUIS 06-05-16]
             */
            $art_exist = ( count($x) ) ? TRUE : FALSE;
            
            if ( $x ) :
                foreach ($x as $k => $trend) : 
        ?>
        <div class="myts-mdl-mx jb-myts-mdl-mx" data-item="<?php echo ( key_exists("trd_eid", $trend) && isset($trend["trd_eid"]) ) ? $trend["trd_eid"] : ""; ?>" data-tba="<?php echo $trend["tba"]; ?>" time="<?php echo ( key_exists("trd_time", $trend) && isset($trend["trd_time"]) ) ? $trend["trd_time"] : ""; ?>">
            <?php if ( $trend["tba"] === "sbtrs" ) : ?>
            <div class="myts-mdl-purpz jb-myts-mdl-purpz">
                <a class="myts-mdl-purpz-u jb-myts-mdl-purpz-u" href="/{wos/datx:oupsd}">@{wos/datx:oupsd}</a>
                <span class="myts-mdl-purpz-tx jb-myts-mdl-purpz-tx" >est abonné(e) à ce Salon</span>
            </div>
            <?php endif; ?>
            <div class="myts-mdl-specs">
                <div class="">
                    <div>
                        <div class="myts-m-s-ubox">
                            <a class="myts-m-s-ubx-grp" href="<?php echo ( key_exists("trd_ohref", $trend) && isset($trend["trd_ohref"]) ) ? $trend["trd_ohref"] : ""; ?>">
                                <span class="myts-m-s-ubx-uppic-mx">
                                    <span class="myts-m-s-ubx-uppic-fade"></span>
                                    <img class="myts-m-s-ubx-uppic" src="<?php echo ( key_exists("trd_oppic", $trend) && isset($trend["trd_oppic"]) ) ? $trend["trd_oppic"] : ""; ?>" height="50" />
                                </span>
                                <span class="myts-m-s-ubx-upsd"><?php echo ( key_exists("trd_opsd", $trend) && isset($trend["trd_opsd"]) ) ? '@'.$trend["trd_opsd"] : ""; ?></span>
                            </a>
                        </div>
                        <div class="myts-m-s-ifs clearfix">
                            <span>Depuis&nbsp;</span>
                            <span class="myts-m-s-ifs-tm jb-myts-m-s-ifs-tm void" time="<?php echo $trend["trd_time"]; ?>"></span>
                        </div>
                    </div>
                    <!--<div class="myts-m-s-sep"></div>-->
                    <div class="myts-m-s-mtrx">
                        <ul class="myts-m-s-mtrx-lst-mx">
                            <li class="myts-m-s-mtrx-lst-ln" level="1">
                                <div class="myts-m-s-m-l-ln-sbwrp">
                                    <div class="myts-m-s-m-l-ln-nb" level="1"><?php echo ( key_exists("trd_posts_nb", $trend) && isset($trend["trd_posts_nb"]) ) ? $trend["trd_posts_nb"] : 0; ?></div>
                                    <div class="myts-m-s-m-l-ln-libwrp" level="_l1_1">
                                        <span class="myts-m-s-m-l-ln-libt">Contributions</span><br/>
                                        <!--<span class="myts-m-s-m-l-ln-lib">De tous</span>-->
                                    </div>
                                </div>
                            </li>
                            <li class="myts-m-s-mtrx-lst-ln" level="2">
                                <div class="myts-m-s-m-l-ln-sbwrp">
                                    <div class="myts-m-s-m-l-ln-nb" level="2"><?php echo ( key_exists("trd_octrib", $trend) && isset($trend["trd_octrib"]) ) ? $trend["trd_octrib"] : 0; ?></div>
                                    <div class="myts-m-s-m-l-ln-libwrp" level="_l2_1">
                                        <!--<span class="myts-m-s-m-l-ln-libt">Contributions</span><br/>-->
                                        <span class="myts-m-s-m-l-ln-lib">De @{wos/datx:oupsd}</span>
                                    </div>
                                </div>
                            </li>
<!--                            <li class="myts-m-s-mtrx-lst-ln" level="1">
                                <div class="myts-m-s-m-l-ln-sbwrp">
                                    <div class="myts-m-s-m-l-ln-nb" level="1">50</div>
                                    <div class="myts-m-s-m-l-ln-libwrp" level="_l1_1">
                                        <span class="myts-m-s-m-l-ln-libt">Points</span>
                                    </div>
                                </div>
                            </li>-->
                            <li class="myts-m-s-mtrx-lst-ln" level="1">
                                <div class="myts-m-s-m-l-ln-sbwrp">
                                    <div class="myts-m-s-m-l-ln-nb" level="1"><?php echo ( key_exists("trd_abos_nb", $trend) && isset($trend["trd_abos_nb"]) ) ? $trend["trd_abos_nb"] : 0; ?></div>
                                    <div class="myts-m-s-m-l-ln-libwrp" level="_l1_1">
                                        <span class="myts-m-s-m-l-ln-libt">Abonnés</span>
                                    </div>
                                </div>
                            </li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="myts-mdl-media">
                <div class="myts-m-md-cvr-mx">
                    <div class="myts-m-md-cvr-tle">
                        <a class="myts-m-md-cvr-tle-hrf" href="<?php echo ( key_exists("trd_href", $trend) && isset($trend["trd_href"]) ) ? $trend["trd_href"] : ""; ?>" title="<?php echo ( key_exists("trd_tle", $trend) && isset($trend["trd_tle"]) ) ? $trend["trd_tle"] : ""; ?>"><?php echo ( key_exists("trd_tle", $trend) && isset($trend["trd_tle"]) ) ? $trend["trd_tle"] : ""; ?></a>
                    </div>
                    <div class="myts-m-md-cvr-cvr">
                        <?php 
                            if ( key_exists("trd_cov_rp", $trend) && isset($trend["trd_cov_rp"]) ) :
                                $h_ =  intval(substr($trend["trd_cov_h"], 0, -2));
                                $t__ = intval(substr($trend["trd_cov_t"], 0, -2));
//                                $nt__ = intval((532/839)*$t__)+3;
                                $nt__ = intval((532/840)*$t__)-2;
//                                var_dump(intval(532/839),substr($trend["trd_cov_h"], 0, -2),$h_,$t__,$nt__);
                        ?>
                        <a class="myts-m-md-cvr-cvr-hrf" href="<?php echo ( key_exists("trd_href", $trend) && isset($trend["trd_href"]) ) ? $trend["trd_href"] : ""; ?>" title="">
                            <img class="myts-m-md-cvr-cvr-img" width="532px" style="top:<?php echo $nt__."px"; ?>" src="<?php echo ( key_exists("trd_cov_rp", $trend) && isset($trend["trd_cov_rp"]) ) ? $trend["trd_cov_rp"] : ""; ?>"/>
                            <span class="myts-m-md-cvr-cvr-fade cover"></span>
                        </a>
                        <?php else : ?>
                        <a class="myts-m-md-cvr-cvr-hrf" href="<?php echo ( key_exists("trd_href", $trend) && isset($trend["trd_href"]) ) ? $trend["trd_href"] : ""; ?>">
                            <span class="myts-m-md-cvr-cvr-noone cover"><img src="{wos/sysdir:img_dir_uri}/r/3pt-w.png" /></span>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="myts-m-md-cvr-desc">
                        <a class="myts-m-md-cvr-desc-hrf" href="<?php echo ( key_exists("trd_href", $trend) && isset($trend["trd_href"]) ) ? $trend["trd_href"] : ""; ?>" title="<?php echo ( key_exists("trd_desc", $trend) && isset($trend["trd_desc"]) ) ? $trend["trd_desc"] : ""; ?>">
                            <?php echo ( key_exists("trd_desc", $trend) && isset($trend["trd_desc"]) ) ? $trend["trd_desc"] : ""; ?>
                        </a>
                    </div>
                </div>
                <div class="myts-m-md-sample-mx">
                    <?php
                        if ( key_exists("trd_fartis", $trend) && isset($trend["trd_fartis"]) && is_array(explode(",",$trend["trd_fartis"])) && count(explode(",",$trend["trd_fartis"])) ) :
                            $ids = explode(",",$trend["trd_fartis"]);
                            $cn__ = 0;
                    ?>
                        <ul class="myts-m-md-spl-lst">
                        <?php
                            foreach ($ids as $id) :
                                ++$cn__;
                               if ( $cn__ === 5 ) { break; }
                        ?>
                        <!-- Les données seront remplies en AJAX -->
                            <li class="myts-m-md-spl-lst-item-sprt">
                                <div class="myts-m-md-spl-lst-item-mx jb-myts-m-md-spl-lst-item-mx" data-item="<?php echo $id; ?>" >
                                    <a class="myts-m-md-spl-lst-item-hwrap jb-myts-mdl-i-hrf">
                                        <img class="jb-myts-mdl-i-spnr" src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" height="10" width="10"/>
                                        <span class="myts-m-md-cvr-cvr-fade sample"></span>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <span class="myts-m-s-m-noone">Aucune publication<br/>pour le moment</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else : ?>
        <div id="mytrds-bdy-noone-mx">
            <span class="mytrds-bdy-noone">Aucune Tendance ... <br/>pour l'instant !</span>
            <!-- EVOLUTION : Ajoutez une phrase "Encourager X à ajouter une Tendance" avec un bouton. -->
        </div>
        <?php endif; ?>
        <!-- Données disponibles pour : DEV, TEST, DEBUG -->
        <?php for($n_=0;$n_<0;$n_++ ) : ?>
        <div class="myts-mdl-mx jb-myts-mdl-mx">
            <div class="myts-mdl-specs">
                <div class="">
                    <div>
                        <div class="myts-m-s-ubox">
                            <a class="myts-m-s-ubx-grp" href="">
                                <img class="myts-m-s-ubx-uppic" src="http://www.lorempixel.com/50/50" />
                                <!--<img class="myts-m-s-ubx-uppic" src="http://www.placehold.it/50/50" />-->
                                <span class="myts-m-s-ubx-upsd">@Anonymous</span>
                            </a>
                        </div>
                        <div class="myts-m-s-ifs clearfix">
                            <span>Depuis Hier</span>
                        </div>
                    </div>
                    <!--<div class="myts-m-s-sep"></div>-->
                    <div class="myts-m-s-mtrx">
                        <ul class="myts-m-s-mtrx-lst-mx">
                            <li class="myts-m-s-mtrx-lst-ln" level="1">
                                <div class="myts-m-s-m-l-ln-sbwrp">
                                    <div class="myts-m-s-m-l-ln-nb" level="1">50</div>
                                    <div class="myts-m-s-m-l-ln-libwrp" level="_l1_1">
                                        <span class="myts-m-s-m-l-ln-libt">Contributions</span><br/>
                                        <!--<span class="myts-m-s-m-l-ln-lib">De tous</span>-->
                                    </div>
                                </div>
                            </li>
                            <li class="myts-m-s-mtrx-lst-ln" level="2">
                                <div class="myts-m-s-m-l-ln-sbwrp">
                                    <div class="myts-m-s-m-l-ln-nb" level="2">10</div>
                                    <div class="myts-m-s-m-l-ln-libwrp" level="_l2_1">
                                        <!--<span class="myts-m-s-m-l-ln-libt">Contributions</span><br/>-->
                                        <span class="myts-m-s-m-l-ln-lib">De @Mouna</span>
                                    </div>
                                </div>
                            </li>
                            <li class="myts-m-s-mtrx-lst-ln" level="1">
                                <div class="myts-m-s-m-l-ln-sbwrp">
                                    <div class="myts-m-s-m-l-ln-nb" level="1">50</div>
                                    <div class="myts-m-s-m-l-ln-libwrp" level="_l1_1">
                                        <span class="myts-m-s-m-l-ln-libt">Points</span>
                                    </div>
                                </div>
                            </li>
                            <li class="myts-m-s-mtrx-lst-ln" level="1">
                                <div class="myts-m-s-m-l-ln-sbwrp">
                                    <div class="myts-m-s-m-l-ln-nb" level="1">50</div>
                                    <div class="myts-m-s-m-l-ln-libwrp" level="_l1_1">
                                        <span class="myts-m-s-m-l-ln-libt">Abonnés</span>
                                    </div>
                                </div>
                            </li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="myts-mdl-media">
                <div class="myts-m-md-cvr-mx">
                    <div class="myts-m-md-cvr-tle">
                        <a class="myts-m-md-cvr-tle-hrf" href="">
                            Pour la fin des injustices contre les chiens
                        </a>
                    </div>
                    <div class="myts-m-md-cvr-cvr">
                        <a class="myts-m-md-cvr-cvr-hrf" href="">
                            <img class="myts-m-md-cvr-cvr-img" src="http://www.lorempixel.com/532/532/nature/<?php echo rand(1,10); ?>"/>
<!--                            <img class="myts-m-md-cvr-cvr-img" src="http://www.placehold.it/532/532"/>-->
                        </a>
                    </div>
                    <div class="myts-m-md-cvr-desc">
                        <a class="myts-m-md-cvr-desc-hrf" href="">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                            Aenean congue dolor id elementum sodales. Donec hendrerit, felis eget finibus placerat metus.
                        </a>
                    </div>
                </div>
                <div class="myts-m-md-sample-mx">
                    <ul class="myts-m-md-spl-lst this_hide">
                        <li class="myts-m-md-spl-lst-item-mx">
                            <a class="myts-m-md-spl-lst-item-hwrap" href="">
                                <img class="myts-m-md-spl-lst-item-i" src="http://www.lorempixel.com/103/103/cats/<?php echo rand(1,10); ?>"/>
                                <!--<img class="myts-m-md-spl-lst-item-i" src="http://www.placehold.it/103/103"/>-->
                            </a>
                        </li>
                        <li class="myts-m-md-spl-lst-item-mx">
                            <a class="myts-m-md-spl-lst-item-hwrap" href="">
                                <img class="myts-m-md-spl-lst-item-i" src="http://www.lorempixel.com/g/103/103/people/<?php echo rand(1,10); ?>"/>
                                <!--<img class="myts-m-md-spl-lst-item-i" src="http://www.placehold.it/103/103"/>-->
                            </a>
                        </li>
                        <li class="myts-m-md-spl-lst-item-mx">
                            <a class="myts-m-md-spl-lst-item-hwrap" href="">
                                <img class="myts-m-md-spl-lst-item-i" src="http://www.lorempixel.com/103/103/cats/<?php echo rand(1,10); ?>"/>
                                <!--<img class="myts-m-md-spl-lst-item-i" src="http://www.placehold.it/103/103"/>-->
                            </a>
                        </li>
                        <li class="myts-m-md-spl-lst-item-mx">
                            <a class="myts-m-md-spl-lst-item-hwrap" href="">
                                <img class="myts-m-md-spl-lst-item-i" src="http://www.lorempixel.com/g/103/103/cats/<?php echo rand(1,10); ?>"/>
                                <!--<img class="myts-m-md-spl-lst-item-i" src="http://www.placehold.it/103/103"/>-->
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</div>

