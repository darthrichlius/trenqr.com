<ul id="user-id-card" data-cache="['{wos/datx:cueid}','{wos/datx:cufn}','{wos/datx:cupsd}','{wos/datx:cuppic}', '{wos/datx:cuhref}', '{wos/datx:cucityid}', '{wos/datx:urel}']">
    <li>
        <a id="hdr-user-ppic-wpr" class="" href="{wos/datx:cuhref}" alt="" title="">
            <img id="hdr-user-ppic" width="35" src="{wos/datx:cuppic}" />
        </a>
    </li>
    <li id="u-i-c-right">
        <a id="u-i-c-pseudo"href="{wos/datx:cuhref}" >{wos/datx:cupsd}</a>
        <span id="u-i-c-loc"><span>{wos/datx:cucity}</span>, <span>{wos/datx:cucn_fn2}</span></span>
        <a id="header-btn-handle" class="jb-hdr-btn-hdle cursor-pointer" title="{wos/deco:_userbox_Start_title}" alt="{wos/deco:_userbox_Start_alt}" href="javascript:;">
            <i class="fa fa-cog"></i>
            {wos/deco:_userbox_Start}
        </a>
        <ul id="handle-menu" class="ui-menu jb-handle-menu this_hide">
<!--            <li><a id="" class="jb-ubx-menu-choices" data-action="" href="/index.php?user={wos/datx:cupsd}&page=settings&urqid=settings&ups=xeu={wos/datx:cueid}.k=<?php echo session_id();?>" title="{wos/deco:_userbox_Settings_title}" alt="{wos/deco:_userbox_Settings_alt}">{wos/deco:_userbox_Settings}</a></li>-->
            <li><a id="" class="jb-ubx-menu-choices" data-action="go_url" href="/{wos/datx:cupsd}/settings/profile" title="{wos/deco:_userbox_Settings_title}" >{wos/deco:_userbox_Settings}</a></li>
            <li><a id="" class="jb-ubx-menu-choices" data-action="go_url" href="/{wos/datx:cupsd}/settings/security" title="{wos/deco:_userbox_Security_title}" >{wos/deco:_userbox_Security}</a></li>
            <li><a id="" class="jb-ubx-menu-choices" data-action="go_url" href="/logout" title="{wos/deco:_userbox_Logout_title}" >{wos/deco:_userbox_Logout}</a></li>
            <li class='menu-dropdown-divider'></li>
            <!--<li><a id="" class="jb-ubx-menu-choices" data-action="" href="" title="{wos/deco:_userbox_Team_title}" alt="{wos/deco:_userbox_Team_alt}">{wos/deco:_userbox_Team}</a></li>-->
            <li><a id="" class="jb-ubx-menu-choices" data-action="open_frdreq" href="javascript:;" title="{wos/deco:_userbox_FC_title}" >{wos/deco:_userbox_FC}</a></li>
            <li><a id="" class="jb-ubx-menu-choices" data-action="invite_friends" href="/!/recommend-trenqr-image-trend-cool-community" title="" >Inviter des amis</a></li>
            <li class='menu-dropdown-divider'></li>
            <li><a id="" class="jb-ubx-menu-choices" data-action="bugzy" href="javascript:;" title="{wos/deco:_userbox_BR_title}" >{wos/deco:_userbox_BR}</a></li>
            <li><a id="" class="jb-ubx-menu-choices" data-action="go_url" href="/{wos/datx:cupsd}/settings/about" title="{wos/deco:_userbox_About_title}" >{wos/deco:_userbox_About_title}</a></li>
        </ul>
    </li>
</ul>