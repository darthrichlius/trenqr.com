<!-- 
    [NOTE 19-04-15] @BOR
    Ce modèle est utilisé dans le cas où l'utilisateur courant n'est pas autorisé à accéder aux Articles IML pour le compte de l'utilisateur cible.
    On limite donc au maximum l'accès aux données y compris la date.
-->
<div 
    id="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" class="feeded_com_bloc_figs jb-tmlnr-mdl-std" 
     data-item="<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>"
>
    <div class="post-solo-in-acclist">
<!--        <div class="fcb_top">
            <div class="fcb_intop_time"></div>
            <div class='fcb_intop_left'>
                <span class="fcb_intop_in">in</span>
                <span class="fcb_intop_wa">MyLIFE</span>
            </div>
        </div>-->
        <div class="fcb_img_maximus lock" title="Vous devez être ami avec {wos/datx:oupsd} (@{wos/datx:oupsd}) pour accéder aux photos de sa vie"> 
            <div class="iml-lock-bgrd">
                <div class="iml-lock-bgrd-wpr">
                    <div>
                        <img class="iml-lock-img" src="{wos/sysdir:img_dir_uri}/r/frd-ctr-w.png" height="15"/>
                    </div>
                    <div>
                        <span class="iml-lock-txt">Privé</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="sp_iml_bot"></div>
    </div>
</div>