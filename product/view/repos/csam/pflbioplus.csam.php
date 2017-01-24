
<?php 
//    var_dump($pgvr, is_string($pgvr), strtoupper($pgvr) === "RO");
//    exit();
    $bio = "{wos/datx:outesty}";
    $uwbst = "{wos/datx:ouwbst}";
    
    $slient_state;
    $slient_class;
    if ( !( $bio && !empty($bio) && is_string($bio) ) && !( $uwbst && !empty($uwbst) && is_string($uwbst) ) ) {
        //sa : SilentAll
        $slient_class = $slient_state = "sa";
    } else if ( !( $bio && !empty($bio) && is_string($bio) ) ) {
        //sb : SilentBio
        $slient_class = $slient_state = "sb";
    } else if ( !( $uwbst && !empty($uwbst) && is_string($uwbst) ) ) {
        //sw : SilentWebsite
        $slient_class = $slient_state = "sw";
    }
?>

<div id="big-pfl-asd-box" class="jb-big-pfl-asd-box <?php echo $slient_class; ?>" data-silent-stt="<?php echo $slient_state; ?>">
    <div id="profil-bio-box" class="jb-pflbio-bx">
        <?php if ( $pgvr && is_string($pgvr) && strtoupper($pgvr) === "RO"  ) : ?>
        <div id="pfl-bio-own-alter" >
            <a id="pfl-bio-own-a-trg" class="jb-pflbio-own-a-trg" href="javascript:;" title="Editer les données de profil" role="button">
                <span id="pfl-bio-own-a-txt">Mofifier</span>
                <i class="fa fa-wrench"></i>
            </a>
            <div id="pfl-bio-own-a-chcs-mx"  class="jb-pfl-bio-own-a-chcs-mx this_hide">
                <a class="pfl-bio-own-a-chcs jb-pfl-bio-own-a-chcs" data-action="abort" href="javascript:;">Annuler</a>
                <a class="pfl-bio-own-a-chcs jb-pfl-bio-own-a-chcs" data-action="save" href="javascript:;">Valider</a>
            </div>
            <!--<a id="pfl-bio-own-a-trg" class="jb-pflbio-own-a-trg" href="javascript:;" >Change</a>-->
        </div>
        <?php endif; ?>
        <div id="pflbio-silentman-mx" class="jb-pflbio-silentman-mx <?php echo ( $slient_state === "sa" ) ? "" : "this_hide";?>">
            <i class="fa fa-user"></i>
            <span id="pflbio-sltmn-txt">Alias Silent User !</span>
        </div>
        <div id="profil-bio" class="jb-pflbio <?php echo ( in_array($slient_state,["sa","sb"]) ) ? "this_hide" : "";?>">
        <!--<div id="profil-bio" class="jb-pflbio <?php // echo ( !( $bio && !empty($bio) && is_string($bio) ) && !( $uwbst && !empty($uwbst) && is_string($uwbst) ) ) ? "this_hide" : "";?>">-->
            <span id="profil-bio-bio" class="jb-pfl-bio-bio" spellcheck="false">{wos/datx:outesty}</span>
            <textarea id="profil-bio-bio-txtarea" class="jb-pflbio-bio-txta this_hide" maxlength="140" placeholder="Biographie ou humeur du moment"></textarea>
            <span id="profil-bio-sep" class="jb-pfl-bio-sep"></span>
            <a id="profil-bio-author" class="jb-pfl-bio-author" data-cache="[{wos/datx:oueid}, {wos/datx:oufn}, {wos/datx:oupsd}, {wos/datx:ouppic}]" >@{wos/datx:oupsd}</a>
        </div>
        <div id="pfl-bio-ownr-wbst-bmx" class="jb-pfl-bio-o-w-bmx <?php echo ( in_array($slient_state,["sa","sw"]) ) ? "this_hide" : "";?>">
            <?php if ( $pgvr && is_string($pgvr) && strtoupper($pgvr) === "RO"  ) : ?>
            <div id="pfl-bio-ownr-wbst-mx" class="jb-pfl-bio-ownr-wbst-mx">
            <?php else : ?>
            <div id="pfl-bio-ownr-wbst-mx" >
            <?php endif; ?>
                <i class="fa fa-link"></i>
                <?php 

                    if (! empty($uwbst) ) {
                        $uwbst = preg_replace("/^https?:\/\/(?:www\.)?/i", "", $uwbst);
                        $uwbst = preg_replace("/^www\./i", "", $uwbst);

                        $mw__ = (! preg_match("/^https?:\/\//i",$uwbst) ) ? "http://".$uwbst : $uwbst;
                    } else {
                        $uwbst = "";
                        $mw__ = "";
                    }
                ?>
                <a id="pfl-bio-ownr-wbst-hrf" class="jb-pfl-bio-ownr-wbst-hrf" data-website="<?php echo $mw__; ?>" href="<?php echo $mw__; ?>" title="<?php echo $mw__; ?>" target="_blank"><?php echo $uwbst; ?></a>
                <!--<a id="pfl-bio-ownr-wbst-hrf" href="trenqr.com" title="trenqr.com">trenqr.com</a>-->
            </div>
             <?php if ( $pgvr && is_string($pgvr) && strtoupper($pgvr) === "RO"  ) : ?>    
            <div id="pfl-bio-ownr-wbst-ipt-mx" class="jb-pfl-bio-ownr-wbst-ipt-mx this_hide">
                <input id="pfl-bio-ownr-wbst-ipt" class="jb-pfl-bio-ownr-wbst-ipt" type="url" placeholder="Site Web"/>
            </div>
            <?php endif; ?>
        </div>
    </div>
<!--    <div id="pfl-bio-sukxbx-mx">
        <div>
            <i id="pfl-bio-sukxbx-lg" class="fa fa-trophy" aria-hidden="true"></i>
            <a id="pfl-bio-sukxbx-see" class="" href="javascript:;">
                <span id="pfl-bio-sukxbx-sukx-nb">0</span>
                <span id="pfl-bio-sukxbx-sukx-lib">succès</span>
            </a>
        </div>
    </div>-->
    <div id="user-socialprint-box" class="jb-usr-socprt-bx">
        <div class="u-sp-sin-box">
            <span class="u-sp-sin u-sp-sin-nb jb-u-sp-cap-nb">{wos/datx:oucapital}</span>
            <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_capital}</span>
        </div>
        <?php if ( $pgvr && is_string($pgvr) && in_array(strtoupper($pgvr),["RU","RO"])  ) : ?>
        <div class="u-sp-sin-box">
            <a class="u-sp-sin-box-a jb-tqr-rlc-act plfbio" data-action="rlc-sprt-opn" data-scp="follower" href="javascript:;" role="button">
                <span class="u-sp-sin u-sp-sin-nb jb-u-sp-flwr-nb">{wos/datx:oufolsnb}</span>
                <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_AboFollowers}</span>
            </a>
        </div>
        <div class="u-sp-sin-box">
            <a class="u-sp-sin-box-a jb-tqr-rlc-act plfbio" data-action="rlc-sprt-opn" data-scp="following" href="javascript:;" role="button">
                <span class="u-sp-sin u-sp-sin-nb jb-u-sp-flg-nb">{wos/datx:oufolgsnb}</span>
                <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_AboFollowing}</span>
            </a>
        </div>
        <?php else : ?>
        <div class="u-sp-sin-box">
            <span class="u-sp-sin-box-a wu plfbio" data-action="rlc-sprt-opn" data-scp="follower" href="javascript:;" role="button">
                <span class="u-sp-sin u-sp-sin-nb jb-u-sp-flwr-nb">{wos/datx:oufolsnb}</span>
                <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_AboFollowers}</span>
            </span>
        </div>
        <div class="u-sp-sin-box">
            <span class="u-sp-sin-box-a wu plfbio" data-action="rlc-sprt-opn" data-scp="following" href="javascript:;" role="button">
                <span class="u-sp-sin u-sp-sin-nb jb-u-sp-flg-nb">{wos/datx:oufolgsnb}</span>
                <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_AboFollowing}</span>
            </span>
        </div>
        <?php endif; ?>
    </div>
</div>