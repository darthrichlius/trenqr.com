<?php
    $part = "{wos/datx:trpart}";
?>

<section id="tqr-shcts-bmx" class="jb-tqr-shcts-bmx <?php echo ( $pgid && $pgid === "fksa" ) ? "fksa" : "" ?>" data-state="off">
    <div class="jb-tqr-shcts-tpgrp this_hide">
        <header class="tqr-shcts-hdr-mx">
            <div class="tqr-shcts-hdr-tle">Liste des raccourcis disponibles sur cette page.</div>
        </header>
        <div class="tqr-shcts-bdy-mx">
            <div class="tqr-shcts-bdy-lst jb-tqr-shcts-bdy-lst">
                <?php if ( $pgid && $pgvr && $pgid === "tmlnr" && $pgvr === "ro" && $sector !== "TR" ) : ?>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="add_post_xyz" data-action="add_post_xyz">Ajouter une publication</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="create_trend" data-action="create_trend">Créer une Tendance</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="newsfeed" data-action="newsfeed">Newsfeed</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="friend_mi" data-action="friend_mi">Messagerie Instantannée</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="new_search" data-action="new_search">Nouvelle recherche</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php elseif ( $pgid && $pgvr && $pgid === "tmlnr" && $pgvr === "ro" && $sector === "TR" ) : ?>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="newsfeed" data-action="newsfeed">Newsfeed</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="friend_mi" data-action="friend_mi">Messagerie Instantannée</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="new_search" data-action="new_search">Nouvelle recherche</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="go_home" href="/{wos/datx:cupsd}">Rentrer à la maison</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php elseif ( $pgid && $pgvr && $pgid === "tmlnr" && $pgvr === "ru" && $sector !== "TR" ) : ?>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="newsfeed" data-action="newsfeed">Newsfeed</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="friend_mi" data-action="friend_mi">Messagerie Instantannée</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="new_search" data-action="new_search">Nouvelle recherche</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="go_home" href="/{wos/datx:cupsd}">Rentrer à la maison</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php elseif ( $pgid && $pgvr && $pgid === "tmlnr" && $pgvr === "ru" && $sector === "TR" ) : ?>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="newsfeed" data-action="newsfeed">Newsfeed</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="friend_mi" data-action="friend_mi">Messagerie Instantannée</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="new_search" data-action="new_search">Nouvelle recherche</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="go_home" href="/{wos/datx:cupsd}">Rentrer à la maison</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php elseif ( $pgid && $pgvr && $pgid === "trpg" && $pgvr === "ro" ) : ?>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="add_post_trd" data-action="add_post_trd">Ajouter une publication</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="newsfeed" data-action="newsfeed">Newsfeed</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="friend_mi" data-action="friend_mi">Messagerie Instantannée</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="new_search" data-action="new_search">Nouvelle recherche</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="go_home" href="/{wos/datx:cupsd}">Rentrer à la maison</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php elseif ( $pgid && $pgvr && $pgid === "trpg" && $pgvr === "ru" && isset($part) && $part == "{wos/deco:_part_pub_code}" ) : ?>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="add_post_trd" data-action="add_post_trd">Ajouter une publication</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="newsfeed" data-action="newsfeed">Newsfeed</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="friend_mi" data-action="friend_mi">Messagerie Instantannée</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="new_search" data-action="new_search">Nouvelle recherche</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="go_home" href="/{wos/datx:cupsd}">Rentrer à la maison</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php elseif ( $pgid && $pgvr && $pgid === "trpg" && $pgvr === "ru" && isset($part)  && $part == "{wos/deco:_part_pri_code}" ) : ?>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="newsfeed" data-action="newsfeed">Newsfeed</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="friend_mi" data-action="friend_mi">Messagerie Instantannée</a>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="new_search" data-action="new_search">Nouvelle recherche</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="go_home" href="/{wos/datx:cupsd}">Rentrer à la maison</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php elseif ( $pgid && $pgid === "hview" ) : ?>
                <a class="tqr-shcts-ch cursor-pointer jb-tqr-shcts-ch" data-scp="newsfeed" data-action="newsfeed">Newsfeed</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="go_home" href="/{wos/datx:cupsd}">Rentrer à la maison</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php elseif ( $pgid && $pgid === "fksa" ) : ?>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="jump" href="" data-action="jump">Emmenez-moi ailleurs</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="invite_friend" href="/!/recommend-trenqr-image-trend-cool-community" data-action="invite_friend">Inviter un ami</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="go_home" href="/{wos/datx:cupsd}">Rentrer à la maison</a>
                <a class="tqr-shcts-ch jb-tqr-shcts-ch" data-scp="trenqr_studio" data-action="trenqr_studio" href="//studio.trenqr.com" target="_blank">Trenqr Studio</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <footer class="tqr-shcts-ftr-mx">
        <a class="tqr-shcts-ftr-tgr cursor-pointer jb-tqr-shcts-ftr-tgr" data-action="open">Raccourcis</a>
        <a class="tqr-shcts-ftr-clz cursor-pointer jb-tqr-shcts-ftr-clz this_hide" data-action="close">&times;</a>
    </footer>
</section>