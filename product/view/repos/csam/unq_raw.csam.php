<!--
* Certaines données sont insérées en dur à titre temporaire. Elles seront changées au cours du process.
* Il faut savoir que les changements au niveau des paramètres comme 'data' ne sont pas visibles. Seules les valeurs par défaut le sont.
* L'avantage c'est qu'on peut ainsi masquer les données. Aussi, un profane ou une personne exterieure n'aura pas accès à ces données.
-->
<div id="unique-max" class="this_hide">
    <a id="unq-close-trg" class="jb-unq-close-trg" href=""></a>    
    <div id="unq-center" class="">
        <div id="unq-c-right">
            <div id="unq-c-a-permlink-sprt" class="jb-unq-prmlk-sprt this_hide">
                <div id="unq-c-a-pmlk-max" data-cache="[%permalink%]">
                    <div id="pmlk-wrap">
                        <div id="unq-c-a-pmlk-bd">
                            <a id="" class="css-unq-pmlk-choices jb-unq-pmlk-choices" data-action="skip" href="">&times;</a>
                            <div id="" class="css-unq-pmlk-ctr">
                                <span id="unq-pmlk-title">Share with anyone, whereever you want.</span>
                            </div>
                            <div id="unq-pmlk-outputbox" class="css-unq-pmlk-ctr">
                                <textarea id="unq-pmlk-output" class="" placeholder="" readonly></textarea>
                            </div>
                            <div id="css-unq-pmlk-chbox" class="">
                                <a id="" class="css-unq-pmlk-choices jb-unq-pmlk-choices" data-action="goto" href="javascript:;" role="button" title="Afficher l'image dans une page dédiée" >Aller vers</a>
                                <!--<a id="" class="css-unq-pmlk-choices jb-unq-pmlk-choices this_hide" data-action="copy" href="" >Copy to clipboard</a>-->
                                <!--<a id="" class="css-unq-pmlk-choices jb-unq-pmlk-choices" data-action="skip" href="">Retour</a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--
                :0_0 ->%itemid%
                :0_1 -> %itemref% L'identifiant au niveau de la page de l'Article chargée
                :0_2 -> %pg% La page où est située l'Article cible
                :1_0 -> %itemid%
                :1_1 -> %time%
                :2_0 -> %ueid%
                :2_1 -> %ufn%
                :2_2 -> %upsd%
                :2_3 -> %uppic%
            -->
            <div id="" class="unq-art-mdl jb-unq-art-mdl" data-item="[:0_0,:0_1]" data-cache="[:1_0,:1_1],[:2_0,:2_1,:2_2,:2_3]">
                <div id="unq-c-aside-max" class="clearfix">
                    <div id="unq-c-a-top">
                        <div id="unq-artdesc-box" class="unq-c-a-r-box jb-unq-artdesc-box clearfix">
                            <span>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel egestas ipsum. Cras posuere nisi nisi, a tristique sapien ullamcorper nec. 
                                <!--Nulla sagittis ligula eu pellentesque molestie. Aenean placerat ante a elit vulputate pretium. 
                                Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Suspendisse cursus nibh et tellus vulputate cursus id mollis lorem. 
                                Curabitur rutrum ut erat ut tristique. Aliquam iaculis nisl in purus facilisis posuere.-->
                            </span>
                        </div>
                        <div id="unq-user-box" class="unq-c-a-r-box">
                            <div class="jb-csam-eval-box css-eval-box css-eval-box-tmlnr css-eval-box-unq clearfix">
                                <span class="jb-csam-eval-oput css-csam-eval-oput" data-cache="[0,1,2,3,me]"><span>56</span> coo<i>!</i></span>
                                <div>
                                    <a id="" class="jb-csam-eval-choices jb-csam-eval-spcool css-csam-eval-chs css-c-e-chs-scl" data-action="rh_spcl" data-zr="rh_spcl" data-rev="bk_spcl" data-target="" data-xc="unq" title="SupaCool" href=""></a>
                                    <a id="" class="jb-csam-eval-choices jb-csam-eval-cool css-csam-eval-chs css-c-e-chs-cl" data-action="rh_cool" data-zr="rh_cool" data-rev="bk_cool" data-target="" data-xc="unq" title="J'adhère" href=""></a>
                                    <a id="" class="jb-csam-eval-choices jb-csam-eval-dislk css-csam-eval-chs css-c-e-chs-dsp" data-action="rh_dislk" data-zr="rh_dislk" data-rev="bk_dislk" data-target="" data-xc="unq" title="J'adhère pas" href=""></a>
                                </div>
                            </div>
                        </div>
                        <div id="unq-eval-box">
                            <a id="" class="unq-vip-eval-users jb-unq-vip-eval-users" href="" alt="Visit @VeryLongUser1 Timeline on Trenqr">@VeryLongUser1</a>, <a id="" class="unq-vip-eval-users jb-unq-vip-eval-users" href="" alt="Visit @AnUser2 Timeline on Trenqr">@AnUser2</a>, <a id="" class="unq-vip-eval-users jb-unq-vip-eval-users" href="" alt="Visit @User2 Timeline on Trenqr">@User2</a>  <span>and</span> <a id="" class="unq-vip-eval-users jb-unq-vip-eval-gusers this_noway" href=""><span id="jb-eval-gul">10</span><span>&nbsp;others</span></a> <span id="">had found it cool.</span>
                        </div>
                    </div>
                    <div id="unq-c-a-react-max" class="this_hide">
                        <div id="unq-c-a-react-box" class="jb-unq-c-a-r-bx">
                            <?php for($i=0; $i<3; $i++) : ?>
                            <!-- 
                            * [%userExtId%,%fullName%,%pseudo%,%time%]
                            * [u,f,p,t]
                            -->
                            <div id="unq-react-<?php echo $i; ?>" class="unq-react-mdl jb-unq-react-mdl" data-item="<?php echo $i; ?>" data-cache="[u,f,p,t]">
                                <div class="unq-react-left">
                                    <div>
                                        <!--<a href=""><img src="http://placehold.it/45x45"/></a>-->
                                        <a class="unq-react-upic-box" href="/@user1" alt="Visit User1 Timeline on Trenqr" style="background-image: url(http://lorempixel.com/45/45/people/<?php echo $i ?>);"></a>
                                    </div>
                                    <!-- <div class="unq-react-time">3m</div> -->
                                </div>
                                <div class="unq-react-txt">
                                    <a class="unq-react-auth" href="">@User<?php echo $i; ?></a>
                                    <span class="unq-react-txt-bb">Lorem ipsum dolor sit amet, <a id="" class="unq-vip-eval-users" href="">@VeryLongUser1</a> consectetur adipiscing elit. Maecenas a neque et lacus elementum aliquam.</span>
                                    <a class="unq-react-del jb-unq-react-del this_hide" data-target="unq-react-<?php echo $i; ?>" href="">Supprimer</a>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div id="unq-c-a-bot" class="this_hide">
                        <a id="unq-show-addrct-trg" class="jb-unq-show-addrct-trg" href="">
                            <img height="20" width="20" src="../public/img/final/3bar-bg.png"/>
                        </a>
                        <span id="unq-count-box" class="jb-unq-count-box">
                            <span id="jb-unq-c-b-nb">0</span>
                            <span>Comments</span>
                        </span>
                    </div>
                </div>
                <div id="unq-c-img-max">
                    <div id="unq-c-img-top">
                        <div id="unq-c-img-t-store">
                            <div id="tqr-tv-fscrn-bmx" class="jb-unq-tv-fscrn-bmx this_hide">
                                <a id="tqr-tv-fscrn-clz" class="cursor-pointer jb-unq-tv-fscrn-clz">&times;</a>
                                <div id="tqr-tv-fscrn-i-mx" class="jb-tqr-tv-fscrn-i-mx">
                                    <img id="tqr-tv-fscrn-i" class="jb-unq-tv-fscrn-i" src=""/>
                                    <span id="tqr-tv-fscrn-i-fd"></span>
                                </div>
                            </div>
                            <!-- On store les images -->
                            <img id="" class="unq-tv jb-unq-tv this_hide" src="http://placehold.it/620x620"/>
                            <!--<img id="" class="unq-tv this_hide" src="http://lorempixel.com/620/620" />-->
                            <span id="unq-tv-noload" class="this_hide"></span>
                            <span id="unq-tv-fade"></span>
                            <span id="unq-tv-nl-deco" class="this_hide">Source introuvable</span>
                            <span id="unq-art-time" class='kxlib_tgspy' data-tgs-crd='' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                                <span class='tgs-frm'></span>
                                <span class='tgs-val'></span>
                                <span class='tgs-uni'></span>
                            </span>
                            <a id="unq-art-pic-wide" class="cursor-pointer jb-unq-art-pic-wide"></a>
                        </div>
                        <!-- Fade sur image -->
                        <!-- Zones pour aller à Droite et Gauche -->
                    </div>
                    <!--  Pour faire apparaitre la zone d'ajout pour le commentaire, ajouter la classe 'appear' -->
                    <div id="unq-c-img-bot" class="">
                        <div id="unq-c-a-trttl" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris quis ipsum turpis. Fusce a volutpat.">
                            <a id="unq-c-a-tt-lk" href="http://127.0.0.1/korgb/pages/trend/pg_rest_see_trend_2.php" alt="Trend's title" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris quis ipsum turpis. Fusce a volutpat.">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris quis ipsum turpis. Fusce a volutpat.</a>
                        </div>
                        <div id="unq-c-a-addr" class="">
                            <div id="unq-c-a-addr-grp">
                                <!--<a id="unq-rst-add-rct" href="" title="Reset the form" alt="Reset add new reaction form on trenqr">Reset</a>-->
                                <textarea id="unq-add-rct-input"></textarea>
                                <a id="unq-add-rct-trg" class="jb-unq-add-rct-trg" href="" role="button" title="Add new reaction" >Do it</a>
                            </div>
                            <div id="unq-a-action">
                                <a id="unq-rst-add-rct" class="jb-unq-rst-add-rct" href="" role="button" title="{wos/deco:_Reset}">Effacer</a>
                                <a class="u-a-a-choices jb-u-a-a-choices" data-action="perma" href="" role="button" title="Afficher le lien permanent" >Permalien</a>
                                <a class="u-a-a-choices jb-u-a-a-choices" data-action="del_start" href="javascript:;" role="button" title="Supprimer la publication" ></a>
                                <!--<a class="u-a-a-choices jb-u-a-a-choices" data-action="perma" href="">Embed</a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="unq-c-left">
            <div id="unq-user-box-owr" class="jb-unq-u-bx-owr">
                <a id="unq-u-b-owr-grp" href=""
                    <span id="unq-u-b-owr-i-bx-fade"></span>
                    <span id="unq-u-b-owr-img-box"></span>
                    <!--<span id="unq-u-b-owr-img-box" style="background-image: url(http://placehold.it/70x70)"></span>-->
                    <!--<img id="unq-u-b-owr-img" src="http://placehold.it/70x70"/>-->
                    <span id="unq-u-b-owr-psd">@IamLouCarther</span>
                </a>
            </div>
        </div>
    </div>
</div>