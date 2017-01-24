


<?php if (! ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
<div id="tqr-fstdvry-elmts" class="jb-tqr-fstdvry-elmts" data-page="tmlnr"> 
    <?php 
            
        if (! ( !empty($prefdcs) && is_array($prefdcs) && count($prefdcs)
                && key_exists("_PFOP_FSTCNX",$prefdcs) && strtoupper($prefdcs["_PFOP_FSTCNX"]["prfodtp_lib"]) === "_DEC_DSMA" )
        ) :
    ?>
    <div id="tqr-fdry-invit-bmx" class="jb-tqr-fdry-invit-bmx this_hide">
        <div id="tqr-fdry-invt-mx"> 
            <a id="tqr-fdry-invt-start-tgr" class="jb-tqr-fdry-invt-start-tgr" data-action="start" role="button" href="javascript:;">Lancer le turoriel</a>
            <div id="tqr-fdry-invt-bdy">
                <div id="tqr-fdry-invt-msg">
                    <p>Hey !</p>
                    <p>Bienvenue sur Trenqr, bienvenue chez vous.</p>
                    <p>Je suis là pour vous aider à prendre en main votre compte et m'assurer que vous partez du bon pied.</p>
                    <p>Si vous ne souhaitez pas lancer la visite tout de suite, vous pourrez toujours le faire ultérieurement.
                </div>
            </div>
            <div id="tqr-fdry-invt-ftr">
                <label id="tqr-fdry-invt-dsma-mx" class="">
                    <input id="tqr-fdry-invt-dsma-chkbx" class="jb-tqr-fdry-invt-dsma-chkbx" type="checkbox" />
                    <span id="tqr-fdry-invt-dsma-txt">Ne plus afficher</span>
                </label>
                <a id="tqr-fdry-invt-clz-tgr" class="jb-tqr-fdry-invt-clz-tgr" data-action="close" role="button" href="javascript:;">Fermer</a> 
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- CAUTION : NEVER REMOVE -->
    <a id="tqr-fdry-invt-start-tgr-alwz" class="jb-tqr-fdry-invt-strt-alwz this_hide" data-action="start" role="button" href="javascript:;"></a> 
    
    <div id="tqr-fstdvry-hdqtr-mx" class="jb-tqr-fry-hq-mx state-sleeping">
        <div id="tqr-fstdvry-hdqtr-hdr">
            <span id="tqr-fstdvry-hdqtr-tle">Panneau de controle</span>
        </div>
        <div id="tqr-fstdvry-hdqtr-bdy">
            <ul id="tqr-fstdvry-hq-opts">
                <li class="tqr-fstdvry-hq-opt" data-action="previous"> 
                    <a class="tqr-fstdvry-hq-opt-tgr cursor-pointer jb-tqr-fdry-hq-opt-tgr" data-action="previous" alt="Repartir à la note précédente" role="button" >Précédent</a>
                </li>
                <li class="tqr-fstdvry-hq-opt" data-action="stop">
                    <a class="tqr-fstdvry-hq-opt-tgr cursor-pointer jb-tqr-fdry-hq-opt-tgr" data-action="stop" alt="Arrêter la découverte animée" role="button" >Arrêter</a>
                </li>
                <li class="tqr-fstdvry-hq-opt" data-action="next">
                    <a class="tqr-fstdvry-hq-opt-tgr cursor-pointer jb-tqr-fdry-hq-opt-tgr" data-action="next" alt="Aller à la prochaine note" role="button" >Suivant</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tqr-fstdvry-box-lists">
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="home" data-index="_IX_HOME">
            <div class="tqr-fdry-bx-mx" data-target="home">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Bonjour et bienvenue sur Trenqr !
                    </p>
<!--                    <p>
                        Veuillez noter que Trenqr est en version bêta. 
                        Cela signifie que nous effectuons régulièrement des améliorations pour vous fournir la meilleure expérience possible.
                    </p>-->
                    <p>
                        Ce tutoriel interactif va vous permettre d'acquérir les bases, pour faciliter la prise en main de votre nouvel univers. 
                    </p>
                    <p>
                        Laissez vous guider. Appuyez sur <b>"Suivant"</b> dans la zone située au bas de l'écran, pour passer à la note suivante.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="newsfeed" data-index="_IX_NEWSFEED">
            <div class="tqr-fdry-bx-mx" data-target="newsfeed">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        <b>Newsfeed</b> est une interface simple, efficace et originale, qui vous permet de suivre en temps réel, les évènements se déroulant dans <b>votre entourage</b>, sans avoir à changer de page.
<!--                        <b>Newsfeed</b> vous permet de suivre en temps réel, les évènements se déroulant dans votre réseau, pour n'en rien manquer.-->
                    </p>
                    <p>
                        Grâce à <b>Newsfeed</b>, le récapitulatif des activités de vos amis, des comptes que vous suivez ou des Tendances qui vous intéressent, se retrouvent réunies en un seul endroit.
                        <!--L'activité de vos Relations, de vos Amis ou des Tendances qui vous intéressent, réunies en un seul endroit.-->
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="notification" data-index="_IX_NOTIFICATION">
            <div class="tqr-fdry-bx-mx" data-target="notification">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        À chaque nouvelle activité <u>vous concernant</u>, vous recevrez une notification via l'<b>Interface de Notification</b> aussi appelée <b>Postman</b>.
                    </p>
                    <p>
                        Il peut s'agir d'un évènement lié à votre compte, à une de vos publications ou à vos Tendances.
                    </p>
                    <p>
                        Par exemple, si une personne ajoute un commentaire à une de vos photos, une notification vous le signalera et vous pourrez le visualiser via cette interface.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="frdcenter" data-index="_IX_FRDCENTER">
            <div class="tqr-fdry-bx-mx" data-target="frdcenter">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        <!--L'interface <b>FriendCenter</b> concentre les principales opérations relatives à votre <b>cercle d'ami(e)s</b>, confirmé(e)s ou en devenir.-->
                        Cette interface vous permet de gérer votre <b>liste d'amis</b>, ainsi que les <b>demandes d'ami</b> qui vous seront envoyées.
                    </p>
                    <p>
                        À chaque fois que vous recevrez une nouvelle demande, elle vous sera notifiée et vous pourrez y répondre via cette interface, sans devoir changer de page.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="userbox" data-index="_IX_USERBOX">
            <div class="tqr-fdry-bx-mx" data-target="userbox">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Cet encadré affiche une partie de vos données de profil, pour que vous puissiez toujours vous y retrouver. 
                        L'avantage c'est qu'il vous suit partout.
                    </p>
                    <p>
                        En appuyant sur le bouton <b>"Editer"</b>, vous accédez à un menu, qui vous donnera accès à plus de fonctionnalités.
                        Vous pourrez par exemple : accéder à la page de gestion de compte, signaler un dysfonctionnement ou vous déconnecter.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="pflpic" data-index="_IX_PFLPIC">
            <div class="tqr-fdry-bx-mx" data-target="pflpic">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Vous êtes actuellement sur la page d'accueil de votre compte. Comment ai-je deviné ? Grâce à votre nom d'utilisateur et votre <b>image de profil</b>.
                    </p>
                    <p>
                        Votre image de profil est très souvent attachée à votre nom d'utilisateur, ce qui permet de vous identifier du premier coup d'oeil.
                    </p>
                    <p>
                        <!--Pour changer votre image de profil depuis cette page, il vous suffit de cliquer sur celle-ci, puis de choisir l'image éligible de votre choix.<br/>-->
                        Vous pouvez <b>changer votre image de profil</b> depuis cette page. Il vous suffit de <b>cliquer une fois dessus</b>, puis de choisir l'image éligible de votre choix.
                        C'est aussi simple que ça :)
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="athome" data-index="_IX_ATHOME">
            <div class="tqr-fdry-bx-mx" data-target="athome">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Eh oui, c'est chez vous ! <i class="fa fa-smile-o"></i>
                    </p>
                    <p>
                        En cliquant sur l'icône, vous accéderez à certaines fonctionnalités réservées à cette page.
                        Vous pourrez par exemple, accéder au bouton pour relancer cette visite animée autant de fois que vous le voudrez.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="cover" data-index="_IX_COVER">
            <div class="tqr-fdry-bx-mx" data-target="cover">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Ah ! Vous l'avez remarqué ? 
                        Pas le petit bonhomme dans le coin, mais plutôt le <b>bouton</b>, qui apparait au survol de la bannière. Essayez, vous verrez.
                    </p>
                    <p>
                        Il vous permet de lancer le processus de modification de votre <b>image de couverture</b>.
                    </p>
                    <p>
                        Pour supprimer votre bannière, il vous suffit d'utiliser le bouton situé juste au-dessus, afin de revenir à celle <b>par défaut</b>.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="hdrmn_myp" data-index="_IX_MENUS_MYP">
            <div class="tqr-fdry-bx-mx" data-target="hdrmn_myp">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Vous êtes actuellement sur cette page. Nos développeurs l'ont surnommé <b>XYZ</b>... mais vous pouvez l'appeler comme vous le souhaitez :)
                    </p>
<!--                    <p>
                        Vous y trouverez toutes vos publications, et bien d'autres informations et données relatives à votre compte.
                    </p>-->
                    <p>
                        On y trouve aussi bien, les publications de votre <b>vie privée</b> (accessibles seulement à vos amis) et <b>publique</b> (pour tout le monde), que celles que <b>vous ajoutez dans des Tendances</b>. 
                    </p>
                    <p>
                        C'est un moyen simple et efficace, de faire partager votre univers à votre réseau et aux visiteurs de votre compte.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="hdrmn_myt" data-index="_IX_MENUS_MYT">
            <div class="tqr-fdry-bx-mx" data-target="hdrmn_myt">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        En cliquant sur ce menu, vous vous retrouverez sur une page qui vous donnera accès à la <b>liste les Tendances que vous avez créées, ainsi que celles auxquelles vous êtes abonné(e)</b>.
                    </p> 
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="hdrmn_fav" data-index="_IX_MENUS_FAV">
            <div class="tqr-fdry-bx-mx" data-target="hdrmn_fav">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        En cliquant sur ce menu, vous vous retrouverez sur une page qui vous donnera accès à toutes les photos et vidéos que vous avez mises dans la <b>liste de vos plublications favorites</b>. 
                    </p> 
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="hdrstats" data-index="_IX_HDRSTATS">
            <div class="tqr-fdry-bx-mx" data-target="hdrstats">
                <div class="tqr-fdry-bx-msg">
                    <p>Cet encart vous permet d'accéder aux informations suivantes :</p>
                    <ul>
                        <li>Le nombre total de publications que vous avez ajoutées.</li>
                        <li>Le nombre de Tendances que vous avez créées.</li>
                        <li>Le nombre de Tendances auxquelles vous êtes abonnées. (En indice)</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="brain" data-index="_IX_BRAIN">
            <div class="tqr-fdry-bx-mx" data-target="brain">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Ce bouton vous donne accès à un <b>module de pilotage</b> convivial, qui vous permet de réaliser la plupart des tâches liées à la création et à la gestion de vos données sur Trenqr.
                    </p>
                    <p>
                        Ainsi, vous avez l'avantage d'accéder à une expérience d'utilisation plus optimisée.
                    </p>
                </div>
            </div>
        </div>
<!--        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="tqrstudio" data-index="_IX_TQRS">
            <div class="tqr-fdry-bx-mx" data-target="tqrstudio">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Trenqr Studio est un outil de design simple et efficace, qui vous permet de redimensionner, personnaliser et bonifier vos images. 
                        Vous pourrez ainsi les ajouter sur Trenqr ou les partager sur le support de votre choix.
                    </p>
                </div>
            </div>
        </div>-->
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="iml_addin_frd" data-index="_IX_ADD_IN_IML_FRD">
            <div class="tqr-fdry-bx-mx" data-target="iml_addin_frd">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Trenqr a été conçu pour être simple et efficace, afin de vous permettre de vous <b>concentrer sur le contenu</b>, plutôt que sur des manipulations complexes.
                    </p>
                    <p>
                        Vous voulez ajouter une photo ou une vidéo ?<br/> Ok, let's go ! 
                    </p>
                    <p>
                        Commencez par choisir le destinataire de votre publication. Ensuite, il ne vous reste plus qu'à ajouter votre fichier et le tour est joué !
                    </p>
                    <p>
                        S'il s'agit d'une photo <b>prise dans un cadre privé</b> ou dont vous voulez <b>limiter la propagation</b>, vous devriez peut-être ne la réserver qu'à vos amis. 
                        Pour cela, <b>choisissez cette option</b>.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="iml_addin_sod" data-index="_IX_ADD_IN_IML_SOD">
            <div class="tqr-fdry-bx-mx" data-target="iml_addin_sod">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Si vous voulez au contraire faire profiter votre photo ou votre vidéo <b>au plus de monde possible</b>, vous devriez choisir de l'ajouter en tant qu'une de vos <b>"photo du jour"</b>.
                    </p>
                    <p>
                        L'avantage est que votre publication ne sera visible que pendant <b>24 heures dans le Newsfeed</b>.
                    </p>
                    <p>
                        Cela permet de ne pas polluer vos amis ou ceux qui vous suivent, tout en les encourageant à venir vous rendre visite plus souvent sur votre compte.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="iml_addin_trd" data-index="_IX_ADD_IN_TRD">
            <div class="tqr-fdry-bx-mx" data-target="iml_addin_trd">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Si votre but est d'<b>ajouter votre publication dans une Tendance</b> qui vous appartient ou une Tendance publique à laquelle vous êtes abonné(e), choisissez cette option.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="iml_addin_hstd" data-index="_IX_ADD_IN_IML_HSTD">
            <div class="tqr-fdry-bx-mx" data-target="iml_addin_hstd">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Les photos et vidéos hébergées <b>ne sont pas repertoriées</b>. Cela signifie qu'elles n'apparaissent pas dans votre fil d'actualité ou dans Newsfeed.
                    </p>
                    <p>
                        Seuls vous et les personnes qui ont connaissance de son URL permanente peuvent voir et intéragir avec cette photos ou vidéos.
                    </p>
                    <p>
                        C'est une option <b>intéressante</b> et <b>utile</b> si vous voulez partager une publication sur Trenqr ou ailleurs, sans que cela n'interfère avec votre activité habituelle sur Trenqr.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="tlkbrd" data-index="_IX_TLKBRD">
            <div class="tqr-fdry-bx-mx" data-target="tlkbrd">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Le <b>Talkboard</b> est un module sommaire mais très interessant, qui vous permet de vous exprimer même quand vous n'avez pas de photos ou de vidéos.
                    </p>
                    <p>
                        De plus, vous avez la possibilité de le configurer très facilement, pour choisir qui peut lire ou ajouter les messages sur votre <b>Talkboard</b>.
                    </p>
                    <p>
                        Pratique, vous disposez de <b>messages prédéfinis</b> qui vous permettront de vous exprimer plus rapidement ou de trouver l'inspiration quand vous en manquez :)
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="lasta" data-index="_IX_LASTA">
            <div class="tqr-fdry-bx-mx" data-target="lasta">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Vos <b>récentes activités publiques</b> ainsi que celles des membres de <b>votre entourage</b>, s’affichent dans ces deux zones ci-dessous.
                    </p>
                    <p>
                        Par exemple, si vous ajoutez une appréciation à une publication : vous, vos amis et ceux qui visitent votre compte, pourront le voir.
                    </p>
                    <p>
                        Pour vous, l'avantage c'est de revoir vos dernières actions et pour les autres, de s'intéresser davantage à votre vie.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="asdbio" data-index="_IX_ASDBIO">
            <div class="tqr-fdry-bx-mx" data-target="asdbio">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Vous pouvez ajouter une <b>biographique</b> ou toutes autres informations qui pourraient êtres utiles ou intéressantes dans cette zone.
                    </p>
                    <p>
                        De plus, vous avez la possibilité d'insérer l'adresse de <b>votre site web</b> ou toute autre URL valide de votre choix.
                    </p>
                    <p>
                        Cliquez sur l'icône tout à droite ou faites un double-clic, pour compléter ou modifier ces informations.
                    </p>
                </div>
            </div>
        </div>

        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="asdstats" data-index="_IX_ASDSTATS">
            <div class="tqr-fdry-bx-mx" data-target="asdstats">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        <b>"Capital"</b> ou <b>"Social Appeal"</b> : Il s'agit du cumul des points que vous obtenez à chaque fois qu'un utilisateur ajoute une appréciation à votre publication.<br/>
                        Il est important de noter que <b>vous pouvez utiliser Trenqr même avec un "Capital" nul</b>.
                    </p>
                    <p>
                        <b>"Abonnés"</b> : Représente le nombre d'utilisateurs qui vous suivent. Appuyez sur la zone pour obtenir la liste de vos abonnés. 
                    </p>
                    <p>
                        <b>"Abonnements"</b> : Représente le nombre d'utilisateurs que vous suivez. Appuyez sur la zone pour obtenir la liste de vos abonnements. 
                    </p>
                </div>
            </div>
        </div>

        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="asdapps" data-index="_IX_ASDAPPS">
            <div class="tqr-fdry-bx-mx" data-target="asdapps">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Nous avons placé à cet endroit certains modules utiles au quotidien, pour qu'ils vous soit facilement accessibles.
                    </p>
                    <p>
                        Pour naviguer entre les modules, il vous suffit d'utiliser les sélecteurs situés sur le dessus.<br/>
                    </p>
                    <p>
                        Vous y retrouvez entre autres une <b>Messagerie Privée</b> pour communiquer avec vos amis mais aussi un <b>mini-module de recherche</b> pour retrouver des profils et des Tendances.
                    </p>
                    <p>
                        De plus, pour améliorer votre confort d'utilisation, vous avez la possibilité de "figer" la zone, en utilisant le bouton <i class="fa fa-lock"></i>.
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="tia" data-index="_IX_TIA">
            <div class="tqr-fdry-bx-mx" data-target="tia">
                <div class="tqr-fdry-bx-msg">
                    <p>
                       Trenqr veut vous proposer une expérience tout aussi sympa, authentique qu'<u>utile</u>. 
                       C'est la raison pour laquelle nous avons conçu TIA.
                    </p>
                    <p>
                        TIA est le <b>gestionnaire d'applications intégrées</b> de Trenqr. 
                        Il vous propose des applications pratiques et utiles pour votre quotidien.
                    </p>
                    <p>
                        Vous pouvez voir cela comme un monde dans un autre, qui vous permettra d'aller au delà de votre univers traditionnel.
                        Par exemple, grâce à l'application EXPLORER, vous pourrez sortir de vos habitudes et aller à la rencontre d'autres utilisateurs de Trenqr, voir ce qu'ils font et ce qu'ils disent.
                        C'est ça l'esprit Trenqr !
                    </p>
                </div>
            </div>
        </div>
        <div class="tqr-fstdvry-box-bmx jb-tqr-fry-bx-bmx this_hide" data-target="thend" data-index="_IX_THEND">
            <div class="tqr-fdry-bx-mx" data-target="thend">
                <div class="tqr-fdry-bx-msg">
                    <p>
                        Bravo <b style='color: #3f729b;'>@{wos/datx:oupsd}</b> ! Vous êtes arrivé(e) à la fin de cette petite visite.<br/>
                    </p>
                    <p>
                        Nous espérons qu'elle vous a été agréable et utile, pour la compréhension de Trenqr.<br/> 
                        Parlez-en autour de vous, vos amis seront impressionnés de vous savoir aussi calé sur Trenqr <i class="fa fa-graduation-cap"></i> :)
                    </p>
                    <p>
                        Si vous voulez en apprendre plus sur Trenqr, n'hésitez pas à visiter <a class="" href="http://blog.trenqr.com">notre blog</a> où vous trouverez des tutoriels vidéos et de l'actualité !
                    </p><!--
                    <div>
                        <a class="share-on-fb"></a>
                        <a class="share-on-twr"></a>
                        <a class="share-on-ptrt"></a>
                        <a class="share-on-gglp"></a>
                        <a class="share-on-tblr"></a>
                        <a class="share-on-rddt"></a>
                        <a class="share-on-lkin"></a>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>