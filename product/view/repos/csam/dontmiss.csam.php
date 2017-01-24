
<?php 
    $upsd = "{wos/datx:oupsd}";
    $uhref = ( $pgid && in_array($pgid, ["fksa","trpg"]) ) ? "href='/$upsd'": "";
//    var_dump(__LINE__,__FILE__,$pgid,$upsd,$uhref);
    
?>
<section id="tqr-dntmiss-bmx" class="jb-tqr-dntmiss-bmx">
    <header class="tqr-dntmiss-hdr">
        <div class="tqr-dntmiss-hdr-tle">{wos/deco:_DVT_DONTMISS_TX001}</div>
        <a class="tqr-dntmiss-hdr-clz cursor-pointer jb-kxlib-close" data-target="tqr-dntmiss-bmx">&times;</a>
    </header>
    <div class="tqr-dntmiss-bdy">
        <div class="tqr-dntmiss-ubx-bmx">
            <div class="tqr-dntmiss-ubx-i-bmx">
                <a class="tqr-dntmiss-ubx-i-mx" <?php echo $uhref; ?>>
                    <span class="tqr-dntmiss-ubx-i-fd"></span>
                    <?php if ( $pgid && in_array($pgid, ["fksa"]) ) : ?>
                    <img class="tqr-dntmiss-ubx-i" width="50" src="{wos/datx:art_oppic}?v={wos/systx:now}"/>
                    <?php else : ?>
                    <img class="tqr-dntmiss-ubx-i" width="50" src="{wos/datx:ouppic}?v={wos/systx:now}"/>
                    <?php endif; ?>
                </a>
            </div>
            <div class="tqr-dntmiss-ubx-pf-bmx">
                <div>
                    <a class="tqr-dntmiss-ubx-psd" <?php echo $uhref; ?>>@{wos/datx:oupsd}</a>
                </div>
                <div>
                    <a class="tqr-dntmiss-ubx-fn <?php echo $uhref; ?>">{wos/datx:oufn}</a>
                </div>
            </div>
        </div>
        <div class="tqr-dntmiss-bdy-flw-mx">
            <a class="tqr-dntmiss-bdy-flw" href="/login?redir_affair=_REDIR_AFTER_LGI&redir_url=/{wos/datx:oupsd}" role="button">{wos/deco:_DVT_DONTMISS_TX002}</a>
        </div>
    </div>
</section>
