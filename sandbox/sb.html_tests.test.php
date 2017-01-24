<?php

/* 
 * Use this scope to test HTML templates.
 * Prefer short template.
 * 
 * Create your Template after this php scope.
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Teste des Templates </title>
        <meta charset="utf-8" />
        
        <!--<link rel="stylesheet" type="text/css" href="http://ext.ycgkit.com/public/css/r/c.c/com.css">-->
        <style>
            .center {
                text-align: center;
            }
            
            .this_hide {
                display: none;
            }
            
            
            /********************** DON CAPITAL *****************************/
            
            /********************** DEL ACCOUNT *****************************/
        </style>
    </head>
    <body>
        <!--
        <div id='new-acc-panel-mx' style='
             border: 1px solid #ccc; 
             border-radius: 2px; width: 700px; 
             height: 100%; 
             margin: 20px auto; 
             background-color: #003A78; 
             position: relative; 
             overflow: auto;'
             >
            <div id='n-a-pan-left' style='width: 150px; height: 100%; background-color: #003A78; float: left;'>
                <div id='n-a-pan-l-logo' style='margin: 20px 0px 0px 0px;'>
                    <div id='' style='text-align: center;'>
                        <a href='http://www.trenqr.com'><img height='50px' src='http://timg.ycgkit.com/files/img/r/logo_tqr_beta.png' /></a>
                    </div>
                    <div id='n-a-p-logo' style='
                         color: #fff; 
                         font-family: \'Century Gothic\'; 
                         font-size: 20px; 
                         text-align: center;'
                    ></div>
                </div>
            </div>
            <div id='n-a-pan-right' style='width: 550px; margin-left: 150px; height: 100%; position: relative;'>
                <div id='n-a-pan-r-body' style='
                     padding: 20px; 
                     background-color: #fff; 
                     font-family: Calibri, Arial, sans-serif; font-size: 15px;'
                     >
                    <p>Bonjour <b style='font-weight: bold;'>%{fullname}%</b>,</p>
                    <p>
                        Bienvenue sur Trenqr, bienvenue à bord ! Nous sommes heureux de vous compter parmi nous. 
                    </p>
                    <p>
                        Avec Trenqr, vous profiterez d'un univers spécialement conçu pour la convivialité, le partage et la découverte. 
                        Trenqr vous permet de vous exprimer, de partager votre quotidien ou vos idées en images, de rester au contact de vos proches et bien d'autres choses encore. 
                    </p>
                    <p>Si vous voulez en savoir plus sur Trenqr et son futur, vous pouvez lire la page « A Propos » qui vous permettra de mieux comprendre sa philosophie.</p>
                    <div style='text-align: center;'>
                        <a id='' class='btn-like' style='display: inline-block; border: 1px solid rgb(40,148,255); border-radius: 5px; padding: 5px 20px; color: rgb(40,148,255); font-size: 13px; text-decoration: none;' href='http://www.trenqr.com/about'>À Propos de Trenqr</a>
                    </div>
                    <p>
                        N’hésitez pas à profiter pleinement des <b style='color: purple;'>Tendances</b> pour partager vos idées, vos créations ou tout simplement imprimer votre marque sur Trenqr.
                    </p>
                    <p>
                        Enfin, pensez à inviter vos amis pour partager cette nouvelle expérience avec vous. Vous verrez, ils vous en remercierons. Car ensemble, vous partagerez une aventure enrichissante.
                    </p>
                    <p>
                        Pour le reste, nous espérons que vous vous plairez sur Trenqr, ça sera notre plus grande satisfaction.
                    </p>
                    <div style='text-align: center;'>
                        <a id='' class='btn-like' style='display: inline-block; border: 1px solid rgb(40,148,255); border-radius: 5px; padding: 5px 20px; color: rgb(40,148,255); font-size: 13px; text-decoration: none;' href='http://www.trenqr.com/@%{pseudo}%'>Accéder à mon compte</a>
                    </div>
                    <ul style='list-style: none; padding: 0;'>
                        <span>Rappel de vos identifiants de connexion</span> :
                        <li style='margin-left: 30px'>
                            <span style='display: inline-block; width: 90px; margin-left: 15px; font-weight: bold;'>Identifiant </span>
                            <span>: <a style='color: #1081F2;; font-weight: bold; text-decoration: none;' href='http://www.trenqr.com/@%{pseudo}%'>@%{pseudo}%</a> <span>(ou cette adresse email)</span></span>
                        </li>
                        <li style='margin-left: 30px'>
                            <span style='display: inline-block; width: 90px; margin-left: 15px; font-weight: bold;'>Mot de passe </span>
                            <span>: Vous seul(e) le connaissez</span>
                        </li>
                    </ul>
                    <p style='margin-top: 25px'>A très bientôt !</p>
                    <p>L'Équipe Trenqr</p>
                </div>
                <div id='n-a-pan-r-footer' style='height: 68px; padding: 10px; background-color: rgb(225,225,225); font-family: Calibri, Arial, sans-serif; font-size: 14px;'>
                    <p style='margin: 0;'>Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/login'>Connectez-vous</a></p>
                    <p style='margin: 0;'>Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/recovery/password'>Vous pouvez en créer un nouveau via ce lien</a></p>
                    <p style='margin: 0;'>Si vous considérez que vous avez reçu ce message par erreur, merci de nous le signaler : <a href='/...'>Cet email ne m’est pas destiné</a>.</p>
                </div>
            </div>
        </div>
        -->
        <!--
        <div id='new-acc-panel-mx' style='
             border: 1px solid #ccc; 
             border-radius: 2px; width: 700px; 
             height: 100%; 
             margin: 20px auto; 
             background-color: #003A78; 
             position: relative; 
             overflow: auto;'
             >
            <div id='n-a-pan-left' style='width: 150px; height: 100%; background-color: #003A78; float: left;'>
                <div id='n-a-pan-l-logo' style='margin: 20px 0px 0px 0px;'>
                    <div id='' style='text-align: center;'>
                        <a href='http://www.trenqr.com'><img height='50px' src='http://timg.ycgkit.com/files/img/r/logo_tqr_beta.png' /></a>
                    </div>
                    <div id='n-a-p-logo' style='
                         color: #fff; 
                         font-family: \'Century Gothic\'; 
                         font-size: 20px; 
                         text-align: center;'
                    ></div>
                </div>
            </div>
            <div id='n-a-pan-right' style='width: 550px; margin-left: 150px;'>
                <div id='n-a-pan-r-body' style='
                     padding: 20px; 
                     background-color: #fff; 
                     font-family: Calibri, Arial, sans-serif; font-size: 15px;'
                     >
                    <p>Bonjour <b style='font-weight: bold;'>%{fullname}%</b>,</p>
                    <p>
                        Suite à votre inscription sur Trenqr, vous recevez une donation de <b style='font-weight: bold;'>%{clcoins}% clcoins</b>. Cette donation fait partie de notre programme spécial <b id='ilmb' style='color: #007eff; font-style: italic;'>«I love my beta»</b>.
                    </p>
                    <p>
                        Ces points vous permettront de constituer votre premier capital, qui augmentera à mesure que vos publications recevront des appréciations positives.
                    </p>
                    <p>
                        Le champ d’application de votre capital, est pour l’heure, encore assez limité. Mais, nous ne pouvons que vous conseiller, de ne pas sous-estimer les possiblités qu'il pourra vous offrir.
                    </p>
                    <p>
                        Cependant, rien ne vous oblige à vous lancer dans une croisade pour accroitre vos points. Sachez par exemple, qu’<b style='font-weight: bold;'>il est possible d’utiliser pleinement Trenqr avec un capital nul</b>.
                    </p>
                    <p>
                        Il ne vous reste plus qu’à explorer l’univers Trenqr, en profitant de votre espace personnel et en jouissant des possibilités de partage qu’offrent les <b style='color: purple'>Tendances</b>.
                    </p>
                    <div style='text-align: center;'>
                        <a id='' class='btn-like' style='display: inline-block; border: 1px solid rgb(40,148,255); border-radius: 5px; padding: 5px 20px; color: rgb(40,148,255); font-size: 13px; text-decoration: none;' href='http://www.trenqr.com/@%{pseudo}%'>Accéder à mon compte</a>
                    </div>
                    <p>A vous de jouer !</p>
                </div>
                <div id='n-a-pan-r-footer' style='height: 68px; padding: 10px; background-color: rgb(225,225,225); font-family: Calibri, Arial, sans-serif; font-size: 14px;'>
                    <p style='margin: 0;'>Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/login'>Connectez-vous</a></p>
                    <p style='margin: 0;'>Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/recovery/password'>Vous pouvez créer un nouveau via ce lien</a></p>
                    <p style='margin: 0;'>Si vous considérez que vous avez reçu ce message par erreur, merci de nous le signaler : <a href='/...'>Cet email ne m’est pas destiné</a>.</p>
                </div>
            </div>
        </div>
    -->
    <!--
        <div id='new-acc-panel-mx' style='
             border: 1px solid #ccc; 
             border-radius: 2px; width: 700px; 
             height: 100%; 
             margin: 20px auto; 
             background-color: #003A78; 
             position: relative; 
             overflow: auto;'
             >
            <div id='n-a-pan-left' style='width: 150px; height: 100%; background-color: #003A78; float: left;'>
                <div id='n-a-pan-l-logo' style='margin: 20px 0px 0px 0px;'>
                    <div id='' style='text-align: center;'>
                        <a href='http://www.trenqr.com'><img height='50px' src='http://timg.ycgkit.com/files/img/r/logo_tqr_beta.png' /></a>
                    </div>
                    <div id='n-a-p-logo' style='
                         color: #fff; 
                         font-family: \'Century Gothic\'; 
                         font-size: 20px; 
                         text-align: center;'
                    ></div>
                </div>
            </div>
            <div id='n-a-pan-right' style='width: 550px; margin-left: 150px;'>
                <div id='n-a-pan-r-body' style='
                     padding: 20px; 
                     background-color: #fff; 
                     font-family: Calibri, Arial, sans-serif; font-size: 15px;'
                     >
                    <p>Bonjour <b style='font-weight: bold;'>%{fullname}%</b>,</p>
                    <p>
                        Nous vous confirmons l’enregistrement de la demande de suppression de votre compte. Demande qui a été effectuée à la date du <b style='font-weight: bold;'>%{date}%</b>. Nous confirmons aussi que vous avez décidé de nous quitter pour les raisons suivantes :
                    </p>
                    <p>
                        %{reasons}%
                    </p>
                    <p>
                        Conformément à nos conditions d’utilisation, <b style='font-weight: bold;'>la suppression de votre compte ne se fera pas avant un délai de 30 jours, à partir de la date de votre demande</b>.
                    </p>
                    <p>
                        Si vous n’êtes pas à l’origine de cette demande de suppression, pas de panique. <b style='font-weight: bold;'>Il vous suffit de vous connecter à votre compte et le processus de suppression sera automatiquement annulé</b>.
                    </p>
                    <div style='text-align: center;'>
                        <a id='' class='btn-like' style='display: inline-block; border: 1px solid rgb(40,148,255); border-radius: 5px; padding: 5px 20px; color: rgb(40,148,255); font-size: 13px; text-decoration: none;' href='http://www.trenqr.com/@%{pseudo}%'>Accéder à mon compte</a>
                    </div>
                    <p>
                        Croyez-le, nous sommes désolés que vous soyez obligé de nous quitter. Notre projet est de construire un Trenqr où tout le monde trouve sa place. Plutôt que de nous quitter, vous pouvez participez au programme <b id='ilmb' style='color: #007eff; font-style: italic;'>«I love my beta<sup>*</sup>»</b> pour nous faire part de ce que vous aimeriez changer dans Trenqr.
                    </p>
                    <div style='text-align: center;'>
                        <a id='' class='btn-like' style='display: inline-block; border: 1px solid rgb(40,148,255); border-radius: 5px; padding: 5px 20px; color: rgb(40,148,255); font-size: 13px; text-decoration: none;' href='http://www.trenqr.com/ilovemybeta'>Nous contacter</a>
                    </div>
                    <p>Dans tous les cas, nous serions heureux de vous revoir parmi, nous afin qu’ensemble, nous puissions continuer l’aventure pour un Trenqr à votre image.</p>
                    <p><b style='font-weight: bold;'>L’équipe Trenqr</b></p>
                    <p id='ilmb-star' style='margin: 10px 0px 0px 0px; padding: 0; color: #c0c0c0; font-size: 12px;'><sup>*</sup> A venir</p>
                </div>
               <div id='n-a-pan-r-footer' style='height: 68px; padding: 10px; background-color: rgb(225,225,225); font-family: Calibri, Arial, sans-serif; font-size: 14px;'>
                    <p style='margin: 0;'>Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/login'>Connectez-vous</a></p>
                    <p style='margin: 0;'>Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/recovery/password'>Vous pouvez créer un nouveau via ce lien</a></p>
                    <p style='margin: 0;'>Si vous considérez que vous avez reçu ce message par erreur, merci de nous le signaler : <a href='/...'>Cet email ne m’est pas destiné</a>.</p>
                </div>
            </div>
        </div> 
    -->
<!--
        <div id='new-acc-panel-mx' style='
             border: 1px solid #ccc; 
             border-radius: 2px; width: 700px; 
             height: 100%; 
             margin: 20px auto; 
             background-color: #003A78; 
             position: relative; 
             overflow: auto;'
             >
            <div id='n-a-pan-left' style='width: 150px; height: 100%; background-color: #003A78; float: left;'>
                <div id='n-a-pan-l-logo' style='margin: 20px 0px 0px 0px;'>
                    <div id='' style='text-align: center;'>
                        <a href='http://www.trenqr.com'><img height='50px' src='http://timg.ycgkit.com/files/img/r/logo_tqr_beta.png' /></a>
                    </div>
                    <div id='n-a-p-logo' style='
                         color: #fff; 
                         font-family: \'Century Gothic\'; 
                         font-size: 20px; 
                         text-align: center;'
                    ></div>
                </div>
            </div>
            <div id='n-a-pan-right' style='width: 550px; margin-left: 150px;'>
                <div id='n-a-pan-r-body' style='
                     padding: 20px; 
                     background-color: #fff; 
                     font-family: Calibri, Arial, sans-serif; font-size: 15px;'
                     >
                    <p>Bonjour <b style='font-weight: bold;'>%{fullname}%</b>,</p>
                    <p>
                        Nous vous informons que l’<b style='color: #DF4961;'>accès à votre compte a été temporairement bloqué, à la suite de trop nombreuses tentatives de connexion erronées</b>.
                    </p>
                    <p>
                        Il s’agit d’une mesure de protection, destinée à protéger votre compte contre des tentatives d’accès frauduleuses.
                    </p>
                    <p>
                        La sécurité est l’une de nos priorités sur Trenqr. Nous nous attelons constamment à l'améliorer, de manière à ce que votre navigation reste la plus sûre possible. Cependant, tous nos efforts resteront vains sans votre pleine participation. Nous vous conseillons donc de suivre les règles de sécurité suivantes :
                    </p>
                    <ol>
                        <li>Ne communiquez sous aucun prétexte votre mot de passe à qui que ce soit</li>
                        <li>Utilisez un mot de passe difficilement devinable même (surtout) par vos proches</li>
                        <li>Utilisez un mot de passe avec au moins 8 caractères, au moins 2 caractères spéciaux et deux chiffres</li>
                        <li>Changez régulièrement votre mot de passe</li>
                        <li>Invitez les personnes de votre entourage, sur Trenqr, à suivre ces règles. Car si l’une d'entre elles se fait pirater, votre vie privée peut se retrouver exposée. Vous ne le souhaitez pas !</li>
                    </ol>
                    <p>
                        Ces règles ne sont pas exhaustives. Nous vous invitons donc à utiliser votre bon sens, pour limiter les risques de piratage de votre compte.
                    </p>
                    <p>
                        Sachez que <b style='font-weight: bold;'>vous ne pourrez pas vous connecter avant la fin du délai de « mise en protection »</b>. Cependant, vous pouvez dès à présent changer votre mot de passe et tenter de vous connecter, une fois que l’accès à votre compte sera à nouveau possible.
                    </p>
                    <div style='text-align: center;'>
                        <a id='' class='btn-like' style='display: inline-block; border: 1px solid rgb(40,148,255); border-radius: 5px; padding: 5px 20px; color: rgb(40,148,255); font-size: 13px; text-decoration: none;' href='http://www.trenqr.com/recovery/password'>Modifier votre mot de passe</a>
                    </div>
                    <p>Nous vous remercions de votre compréhension.</p>
                    <p>L'équipe Trenqr</p>
                </div>
                <div id='n-a-pan-r-footer' style='height: 68px; padding: 10px; background-color: rgb(225,225,225); font-family: Calibri, Arial, sans-serif; font-size: 14px;'>
                    <p style='margin: 0;'>Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/login'>Connectez-vous</a></p>
                    <p style='margin: 0;'>Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/recovery/password'>Vous pouvez créer un nouveau via ce lien</a></p>
                    <p style='margin: 0;'>Si vous considérez que vous avez reçu ce message par erreur, merci de nous le signaler : <a href='/...'>Cet email ne m’est pas destiné</a>.</p>
                </div>
            </div>
        </div>
      -->  
<!--        <div id='new-acc-panel-mx' style='border: 1px solid #ccc; border-radius: 2px; width: 700px; min-height: 500px; height: 100%; margin: 20px auto; background-color: #003A78; position: relative; overflow: auto; color: #000;'>
            <div id='n-a-pan-left' style='width: 150px; height: 100%; background-color: #003A78; float: left;'>
                <div id='n-a-pan-l-logo' style='margin: 215px 0px 0px 0px;'>
                    <div id='' style='text-align: center;'>
                        <img height='50px' src='http://timg.ycgkit.com/files/img/r/logo_tqr_beta.png' />
                    </div>
                    <div id='n-a-p-logo' style='color: #fff; font-family: \'Century Gothic\'; font-size: 20px; text-align: center;'></div>
                </div>
            </div>
            <div id='n-a-pan-right' style='margin-left: 150px;'>
                <div id='n-a-pan-r-body' style='padding: 20px; background-color: #fff; font-family: Calibri, Arial, sans-serif; font-size: 15px;'>
                    <p>Bonjour <b style='font-weight: bold;'>%{fullname}%</b>,</p>
                    <p>
                        Suite à votre  demande, nous avons ouvert une procédure de <b style='font-weight: bold;'>réinitialisation de mot de passe</b>.
                    </p>
                    <p>
                        Pour changer votre mot de passe, il vous suffit de suivre les instructions qui vous serons indiqués après avoir cliqué sur le bouton ci-dessous :
                    </p>
                    <div style='text-align: center;'>
                        <a id='' class='btn-like' style='display: inline-block; border: 1px solid rgb(40,148,255); border-radius: 5px; padding: 5px 20px; color: rgb(40,148,255); font-size: 13px; text-decoration: none;' href='%{recovery_link}%'>Réinitialiser votre mot de passe</a>
                    </div>
                    <p>Vous utiliserez le <b>code de validation</b> ci-dessous (en respectant la casse) afin de valider l'opération : </p>
                    <div style='text-align: center;'>
                        <span id='' style='border-radius: 4px; background-color: #eee; padding: 10px 35px; font-size: 15px; font-weight: bold; letter-spacing: 1px;'>%secret_code%</span>
                    </div>
                    <p>
                        Si vous avez des difficultés à cliquer sur le bouton ou qu'il ne vous est pas accessible, vous pouvez utiliser le lien alternatif ci-dessous :
                    </p>
                    <div>
                        <a href='%{recovery_link}%'>%{recovery_link_public}%</a>
                    </div>
                    <p>
                        Si vous n’êtes pas à l’origine de cette demande ou que vous avez changé d’avis, vous pouvez nous le signaler en cliquant sur le lien ci-dessous :
                    </p>
                     <div>
                        <a href='%{recovery_cancel_link}%'>%{recovery_cancel_link_public}%</a>
                    </div>
                    <p>A très bientot</p>
                </div>
                <div id='n-a-pan-r-footer' style='height: 68px; padding: 10px; background-color: rgb(225,225,225); font-family: Calibri, Arial, sans-serif; font-size: 14px;'>
                    <p style='margin: 0;'>Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/login'>Connectez-vous</a></p>
                    <p style='margin: 0;'>Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/recovery/password'>Vous pouvez créer un nouveau via ce lien</a></p>
                    <p style='margin: 0;'>Si vous considérez que vous avez reçu ce message par erreur, merci de nous le signaler : <a href='/...'>Cet email ne m’est pas destiné</a>.</p>
                </div>
            </div>
        </div>-->
        <!--
        <div id='new-acc-panel-mx' style='border: 1px solid #ccc; border-radius: 2px; width: 700px; min-height: 500px; height: 100%; margin: 20px auto; background-color: #003A78; position: relative; overflow: auto; color: #000;'>
            <div id='n-a-pan-left' style='width: 150px; height: 100%; background-color: #003A78; float: left;'>
                <div id='n-a-pan-l-logo' style='margin: 215px 0px 0px 0px;'>
                    <div id='' style='text-align: center;'>
                        <img height='50px' src='http://timg.ycgkit.com/files/img/r/logo_tqr_beta.png' />
                    </div>
                    <div id='n-a-p-logo' style='color: #fff; font-family: \'Century Gothic\'; font-size: 20px; text-align: center;'></div>
                </div>
            </div>
            <div id='n-a-pan-right' style='margin-left: 150px;'>
                <div id='n-a-pan-r-body' style='padding: 20px; background-color: #fff; font-family: Calibri, Arial, sans-serif; font-size: 15px;'>
                    <p>Bonjour <b style='font-weight: bold;'>%{fullname}%</b>,</p>
                    <p>
                        Nous vous confirmons que votre <b style='font-weight: bold;'>demande de suppression de compte</b> a bien été prise en compte.<br/>
                        Pour des raisons de sécurité, nous vous transmettons les informations concernant cette demande :
                    </p>
                    <ul>
                        <li>Date de la demande : <b style='color: #3E3E3E; font-weight: bold;'>%{apply_date}%</b></li>
                        <li>Heure de la demande : <b style='color: #3E3E3E; font-weight: bold;'>%{apply_hour}%</b></li>
                        <li>Localisation (approximative): <b style='color: #3E3E3E; font-weight: bold;'>INDISPONIBLE</b></li>
                    </ul>
                    <p>
                        Conformément à notre politique de <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/privacy'>confidentialité et de gestion des données</a>, votre compte sera supprimé dans un <b style='font-weight: bold'>délai d’un mois</b> (valeur supérieure) à compter de la date de la demande.
                    </p>
                    <p>
                        <b style='font-weight: bold'>Si vous avez changé d’avis</b> ou que <b style='font-weight: bold'>vous n’êtes pas à l’origine de cette demande</b>, il vous suffit de vous connecter à nouveau à votre compte pour annuler la dite procédure. Cliquez sur le bouton ci-dessous pour vous reconnecter :
                    </p>
                    <div style='text-align: center;'>
                        <a id='' class='btn-like' style='display: inline-block; border: 1px solid rgb(40,148,255); border-radius: 5px; padding: 5px 20px; color: rgb(40,148,255); font-size: 13px; text-decoration: none;' href='www.trenqr.com/login'>Récupérer mon compte</a>
                    </div>
                    <p>
                        Il ne nous reste plus qu’à vous souhaiter bonne continuation et d'ajouter que nous regrettons que vous nous quittiez. Aussi, nous gardons espoir que vous puissiez retenter à nouveau l’aventure avec Trenqr.
                    </p>
                    <p>A très bientot</p>
                </div>
                <div id='n-a-pan-r-footer' style='height: 68px; padding: 10px; background-color: rgb(225,225,225); font-family: Calibri, Arial, sans-serif; font-size: 14px;'>
                    <p style='margin: 0;'>Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/login'>Connectez-vous</a></p>
                    <p style='margin: 0;'>Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='http://www.trenqr.com/recovery/password'>Vous pouvez créer un nouveau via ce lien</a></p>
                    <p style='margin: 0;'>Si vous considérez que vous avez reçu ce message par erreur, merci de nous le signaler : <a href='www.trenqr.com'>Cet email ne m’est pas destiné</a>.</p>
                </div>
            </div>
        </div>
        -->
        <!--
        <div style='font-family: Calibri, Arial, sans-serif' class="this_hide" >
            <p style='font-style: italic;'>Hey !</p>
            <p>
                Cet email est envoyé à la suite du déclenchement d'une erreur detectée depuis le produit <span style='color: rgb(28,140,255); font-weight: bold;'>Trenqr</span>.<br/>
                Ci-dessous les données relatives à l'erreur : 
            </p>
            <h2 style='color: #555;'>RAPPORT DÉTAILLÉ :</h2>
            <table style='border: 1px solid #000; border-collapse: collapse;'>
                <tr>
                    <th style='border-bottom: 1px solid #000; border-right: 1px solid #000; font-size: 20px; font-weight: bold; background-color: #ccc; text-align: center;'>PROPRIÉTÉS</th>
                    <th style='border-bottom: 1px solid #000; border-right: 1px solid #000; font-size: 20px; font-weight: bold; background-color: #ccc; text-align: center;'>VALEURS</th>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR CODE : </td>
                    <td style='color: #f00'>%{error_code}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR MESSAGE : </td>
                    <td style='color: #f00'>%{error_message}%</td>
                </tr>
                <tr style='color: #28b957; font-weight: bold; text-align: center;'>
                    <td>WHO STUFF</td>
                    <td></td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>SESSION_ID: </td>
                    <td>%{ssid}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>USER EID : </td>
                    <td>%{error_user_ueid}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>USER PSEUDO : </td>
                    <td>%{error_user_pseudo}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR LOCIP ADDR : </td>
                    <td>%{error_locip}%</td>
                </tr>
                <tr style='color: #28b957; font-weight: bold; text-align: center;'>
                    <td>WHERE STUFF</td>
                    <td></td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR SRVIP: </td>
                    <td>%{error_srvip}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR SRVNAME : </td>
                    <td>%{error_srvname}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR FILE : </td>
                    <td>%{error_file}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR CLASS : </td>
                    <td>%{error_class}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR FUNCTION : </td>
                    <td>%{error_function}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR LINE : </td>
                    <td>%{error_line}%</td>
                </tr>
                <tr style='color: #28b957; font-weight: bold; text-align: center;'>
                    <td>HOW STUFF</td>
                    <td></td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;' valign='top'>ERROR REFERER : </td>
                    <td>%{error_referer}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;' valign='top'>ERROR URI : </td>
                    <td>%{error_uri}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;' valign='top'>ERROR USER AGENT : </td>
                    <td>%{error_user_agent}%</td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;' valign='top'>ERROR TRACE : </td>
                    <td style='width: 500px;'>%{debug_print_trace}%</td>
                </tr>
                <tr style='color: #28b957; font-weight: bold; text-align: center;'>
                    <td>WHEN STUFF</td>
                    <td></td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR DATE : </td>
                    <td>%{error_date_tstamp}% (%{error_datetime}%)</td>
                </tr>
                <tr style='color: rgb(230,10,10); font-weight: bold; text-align: center;'>
                    <td>LOGGED STUFF</td>
                    <td></td>
                </tr>
                <tr>
                    <td style='font-size: 16px; font-weight: bold;'>ERROR LOG EID : </td>
                    <td>%{error_log_eid}%</td>
                </tr>
            </table>
        </div>
        <div style='font-family: Calibri, Arial, sans-serif' class="this_hide" >
            <p style='font-style: italic;'>Hey !</p>
            <p>
                Un nouveau signalement de bogue est disponible. Vérifiez les rapports avec le staut "Ouvert".
            </p>
        </div>
        -->
        <div id="new-acc-panel-mx" style="
            width: 100%; 
            min-width: 550px; 
            max-width: 650px; 
            height: 100%; 
            margin: 20px auto; 
            /*background-color: rgb(242,242,242);*/
            
            position: relative; 
            overflow: auto;
            color: #000;
            font-family: Arial, sans-serif;
        ">
            <div id='n-a-pan-right' 
                 style='
                    color: #000;
                '
            >
                <div id='n-a-pan-r-body' style='
                     padding: 20px; 
                     background-color: #fff; 
                     color: #000;
                     font-size: 15px;'
                >
                    <section id="tqr-rcmd-scn-cfrm-bmx" style="
                        width: 100%;
                        margin: 30px auto 0;
                        text-align: center;
                    ">
                        <div>
                            <a href='%{trenqr_http_root}%'><img height='50px' src='/bart1/timg/files/img/r/logo_tqr_beta_be.png' /></a>
                            <!-- <a href='%{trenqr_http_root}%'><img height='50px' src='%{trenqr_prod_img_root}%/r/logo_tqr_beta_be.png' /></a> -->
                        </div>
                        <div id="tqr-rcmd-scn-cfrm-mx" style="
                            border: 1px solid #6C7D92;
                            border-radius: 8px;
                            padding: 40px 30px;
                            background-color: rgba(242, 246, 247, 0.51);;
                            font-family: Arial, sans-serif;
                            text-align: center;
                        ">
                            <header id="tqr-rcmd-scn-cfrm-hdr-bmx">
                                <div id="tqr-rcmd-scn-cfrm-hdr-mx">
                                    <div id="tqr-rcmd-scn-cfrm-ppic-mx">
                                        <a id="tqr-rcmd-scn-cfrm-nm-ix" href="">
                                            <img id="tqr-rcmd-scn-cfrm-nm-i" width="80" height="80" src="//www.lorempixel.com/80/80/people" alt="" style="
                                                 border: 1px solid #bcbcbc;
                                                border-radius: 50%;
                                                box-shadow: 0 1px 20px 5px rgba(21,44,58,0.2);
                                            "/>
                                        </a>
                                    </div>
                                    <div id="tqr-rcmd-scn-cfrm-nm-mx">
                                        <a id="tqr-rcmd-scn-cfrm-nm" href="" style="text-decoration: none;">
                                            <div id="tqr-rcmd-scn-cfrm-psd" style="color: #5DA7EC;">@Dupont</div>
                                            <div id="tqr-rcmd-scn-cfrm-fn" style="
                                                 color: #186382;
                                                font-size: 20px;
                                                font-weight: bold;
                                            ">Dupont Agile</div>
                                        </a>
                                    </div>
                                </div>
                            </header>
                            <div id="tqr-rcmd-scn-cfrm-bdy-mx" style="
                                margin-top: 18px;
                            ">
                                <div id="tqr-rcmd-scn-cfrm-txt" style="
                                    color: #31314C;
                                    font-size: 15px;
                                ">
                                    <div id="tqr-rcmd-scn-cfrm-txt-hi" style="
                                        margin-bottom: 5px;
                                        text-align: left;
                                    ">Bonjour Louna,</div>
                                    <div id="tqr-rcmd-scn-cfrm-txt-then" style="
                                        text-align: left; 
                                    ">
                                        <p>
                                            <span style="font-weight:bold;">Dupont Agile</span> vous invite à essayer <a href="/" style="color: #3c96a5 !important;">trenqr.com</a>. 
                                            Il s'agit de la nouvelle communauté cool et tendance d'internet, qui vous permet de partager différemment votre quotidien en images, avec ceux qui vous sont proches et au-delà, dans un environnement beau, ludique et convivial.
                                        </p>
                                        <p>Vous pouvez en apprendre plus sur Trenqr en cliquant <a href="/?action=open-rich-core" style="color: #3c96a5 !important;">ici</a>.</p>
                                    </div>
                                    <div id="tqr-rcmd-scn-cfrm-txt-author" style="
                                        margin-bottom: 5px;
                                        text-align: left;
                                    ">- Dupont Agile -</div>
                                </div>
                                <div id="tqr-rcmd-scn-cfrm-btns-mx" style="
                                    margin-top: 30px;
                                ">
                                    <!-- A RETIRER POUR LE MODELE SAMPLE -->
                                    <div>
                                        <a class="tqr-rcmd-scn-cfrm-btn" data-action="ja" href="javascript:;" style="
                                            display: block;
                                            border: 1px solid;
                                            border-radius: 6px;
                                            width: 235px;
                                            margin: 0px auto;
                                            padding: 10px 0px;
                                            background-color: #004589;
                                            color: #FFF;
                                            text-decoration: none;
                                        ">Accepter l'invitation</a>
                                        <a class="tqr-rcmd-scn-cfrm-btn" data-action="nein" href="javascript:;" style="
                                            display: block;
                                            margin: 8px auto 0px;
                                            color: #3F729B;
                                            font-size: 14px;
                                            text-decoration: none;
                                        ">Refuser l'invitation</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div id='n-a-pan-r-footer' style='
                    padding: 10px; 
                    color: #000; 
               '>
                    <div id='' style='
                         text-align: center;
                    '>
                        <a href='%{trenqr_http_root}%'><img height='50px' src='/bart1/timg/files/img/r/fav2.png' /></a>
                        <!-- <a href='%{trenqr_http_root}%'><img height='50px' src='%{trenqr_prod_img_root}%/r/fav2.png' /></a> -->
                    </div>
                    <div id="" style="
                         margin-top: 30px;
                        padding: 0px 15px;
                    " >
                        <p style='
                            margin: 0;
                            font-size: 13px;
                        '>
                            Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='%{trenqr_login_link}%'>Connectez-vous</a>
                        </p>
                        <p style='
                           margin: 0;
                           font-size: 13px;
                        '>
                            Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='%{trenqr_start_rcvy_link}%'>Réinitialisez votre mot de passe via ce lien</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div id="tqr-confirm-acc-mx" style="
            width: 100%; 
            min-width: 550px; 
            max-width: 650px; 
            height: 100%; 
            margin: 20px auto; 
            
            position: relative; 
            overflow: auto;
            color: #000;
            font-family: Arial, sans-serif;
        ">
            <div id='n-a-pan-right' 
                 style='
                    color: #000;
                '
            >
                <div id='n-a-pan-r-body' style='
                     padding: 20px; 
                     background-color: #fff; 
                     color: #000;
                     font-size: 15px;'
                >
                    <section id="tqr-rcmd-scn-cfrm-bmx" style="
                        width: 100%;
                        margin: 30px auto 0;
                        text-align: center;
                    ">
                        <div>
                            <a href='%{trenqr_http_root}%'><img height='50px' src='/bart1/timg/files/img/r/logo_tqr_beta_be.png' /></a>
                            <!-- <a href='%{trenqr_http_root}%'><img height='50px' src='%{trenqr_prod_img_root}%/r/logo_tqr_beta_be.png' /></a> -->
                        </div>
                        <div id="tqr-rcmd-scn-cfrm-mx" style="
                            border: 1px solid #6C7D92;
                            border-radius: 8px;
                            padding: 40px 30px;
                            background-color: rgba(242, 246, 247, 0.51);;
                            font-family: Arial, sans-serif;
                            text-align: center;
                        ">
                            <header id="tqr-rcmd-scn-cfrm-hdr-bmx">
                                <div style="
                                    color: #767D84;
                                    font-family: 'Open sans', Arial, sans-serif;
                                    font-size: 26px;
                                ">Activez votre compte en validant votre email</div>
                            </header>
                            <div id="tqr-rcmd-scn-cfrm-bdy-mx" style="
                                margin-top: 18px;
                            ">
                                <div id="tqr-rcmd-scn-cfrm-txt" style="
                                    color: #31314C;
                                    font-size: 15px;
                                ">
                                    <div id="tqr-rcmd-scn-cfrm-txt-hi" style="
                                        margin-bottom: 5px;
                                        text-align: left;
                                        ">Bonjour <span style="font-weight: bold;">%{user}%</span>,</div>
                                    <div id="tqr-rcmd-scn-cfrm-txt-then" style="
                                        margin-top: 10px;
                                        text-align: left; 
                                    ">
                                        C'est presque fini ...Confirmez votre adresse email afin d'activer définitivement votre compte Trenqr. C'est simple, il vous suffit de cliquer sur le bouton ci-dessous :
                                    </div>
                                    <div style="
                                        margin-top: 20px;
                                    ">
                                        <a class="tqr-rcmd-scn-cfrm-btn" data-action="ja" href="javascript:;" style="
                                            display: block;
                                            border: 1px solid;
                                            border-radius: 6px;
                                            width: 235px;
                                            margin: 0px auto;
                                            padding: 10px 0px;
                                            background-color: #004589;
                                            color: #FFF;
                                            text-decoration: none;
                                        ">Confirmer maintenant</a>
                                    </div>
                                    <div style="
                                        margin-top: 20px;
                                        text-align: left;
                                    ">
                                        Si vous avez du mal à cliquer sur le bouton, cliquez sur le lien ci-dessous :
                                    </div>
                                    <div>
                                        <a class="tqr-rcmd-scn-cfrm-btn" data-action="nein" href="javascript:;" style="
                                            display: block;
                                            margin: 8px auto 0px;
                                            color: #3F729B;
                                            font-size: 14px;
                                            text-decoration: none;
                                            text-align: left;
                                        ">[Lien en clair]</a>
                                    </div>
                                    <div id="tqr-rcmd-scn-cfrm-txt-author" style="
                                        margin-top: 25px;
                                        text-align: left;
                                    ">L'équipe Trenqr</div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div id='n-a-pan-r-footer' style='
                    padding: 10px; 
                    color: #000; 
               '>
                    <div id='' style='
                         text-align: center;
                    '>
                        <a href='%{trenqr_http_root}%'><img height='50px' src='/bart1/timg/files/img/r/fav2.png' /></a>
                        <!--<a href='%{trenqr_http_root}%'><img height='50px' src='%{trenqr_prod_img_root}%/r/fav2.png' /></a>-->
                    </div>
                    <div id="" style="
                         margin-top: 30px;
                        padding: 0px 15px;
                    " >
                        <p style='
                            margin: 0;
                            font-size: 13px;
                        '>
                            Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='%{trenqr_login_link}%'>Connectez-vous</a>
                        </p>
                        <p style='
                           margin: 0;
                           font-size: 13px;
                        '>
                            Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='%{trenqr_start_rcvy_link}%'>Réinitialisez votre mot de passe via ce lien</a>
                        </p>
                    </div>
                </div>
            </div>
        <div id="new-acc-panel-mx" style="
            width: 100%; 
            min-width: 550px; 
            max-width: 650px; 
            height: 100%; 
            margin: 20px auto; 
            /*background-color: rgb(242,242,242);*/

            position: relative; 
            overflow: auto;
            color: #000;
            font-family: Arial, sans-serif;
        ">
            <div id="n-a-pan-right" style="
                color: #000;
            ">
                <div id="n-a-pan-r-body" style="
                     padding: 20px; 
                     background-color: #fff; 
                     color: #000;
                     font-size: 15px;"
                >
                    <section id="tqr-rcmd-scn-cfrm-bmx" style="
                        width: 100%;
                        margin: 30px auto 0;
                        text-align: center;
                    ">
                        <header style="
                             text-align: center;
                        ">
                            <a href="%{trenqr_http_root}%"><img height="50px" src="%{trenqr_prod_img_root}%/r/logo_tqr_beta_be.png" alt="trenqr" /></a>
                        </header>
                        <div id="tqr-rcmd-scn-cfrm-mx" style="
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            padding: 40px 30px;
                            background-color: rgba(230, 230, 230, 0.5);
                            font-family: Arial, sans-serif;
                            text-align: center;
                        ">
                            <div id="tqr-rcmd-scn-cfrm-hdr-bmx">
                                <div id="tqr-rcmd-scn-cfrm-hdr-mx">
                                    <div style="
                                        padding: 0 0 20px 0;
                                        color: #767D84;
                                        font-family:  'Open sans', Arial, sans-serif;
                                        font-size: 26px;
                                    ">Vous avez une nouvelle notification de</div>
                                    <div id="tqr-rcmd-scn-cfrm-ppic-mx">
                                        <a id="tqr-rcmd-scn-cfrm-nm-ix" href="%{act_uhrf}%">
                                            <img id="tqr-rcmd-scn-cfrm-nm-i" width="70" height="70" src="%{act_uppic}%" alt="%{act_ufn}% - %{act_upsd}%" style="
                                                 border: 1px solid #bcbcbc;
                                                border-radius: 15%;
                                            "/>
                                        </a>
                                    </div>
                                    <div id="tqr-rcmd-scn-cfrm-nm-mx">
                                        <a id="tqr-rcmd-scn-cfrm-nm" href="%{act_uhrf}%" style="text-decoration: none;">
                                            <div id="tqr-rcmd-scn-cfrm-psd" style="
                                                margin-top: 5px;

                                                color: #434850;
                                                font-size: 21px;
                                            ">%{act_ufn}%</div>
                                            <div id="tqr-rcmd-scn-cfrm-fn" style="
                                                display: block;
                                                color: #3f729b;
                                                font-size: 20px;
                                                font-weight: bold;
                                            ">@%{act_upsd}%</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div id="tqr-rcmd-scn-cfrm-bdy-mx" style="
                                margin-top: 18px;
                            ">
                                <div id="tqr-rcmd-scn-cfrm-txt" style="
                                    color: #31314C;
                                    font-size: 15px;
                                ">
                                    <div id="tqr-rcmd-scn-cfrm-txt-hi" style="
                                        margin-bottom: 5px;
                                        text-align: left;
                                    ">Bonjour @%{tag_upsd}%,</div>
                                    <div id="tqr-rcmd-scn-cfrm-txt-then" style="
                                        text-align: left; 
                                    ">
                                        <p>
                                            <a style="color: #3f729b; font-weight:bold;" href="%{act_uhrf}%">@%{act_upsd}%</a> %{action_sentence}%. 
                                        </p>
                                        <p
                                            style="
                                                border: 1px solid #ddd;
                                                border-radius: 6px;
                                                padding: 15px;
                                                box-sizing: border-box;
                                                background: #fff;

                                                color: #303742;
                                                font-size: 11px;
                                                font-style: italic;
                                                text-transform: uppercase;
                                        ">
                                            %{preview}%
                                        </p>
                                    </div>
                                </div>
                                <div id="tqr-rcmd-scn-cfrm-btns-mx" style="
                                    margin-top: 30px;
                                ">
                                    <div>
                                        <a class="tqr-rcmd-scn-cfrm-btn" data-action="ja" href="%{perma}%" style="
                                            display: block;
                                            border: 1px solid;
                                            border-radius: 6px;
                                            width: 235px;
                                            margin: 0px auto;
                                            padding: 10px 0px;
                                            background-color: #004589;

                                            color: #FFF;
                                            font-size: 18px;
                                            text-decoration: none;
                                        ">Afficher sur Trenqr</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div id="n-a-pan-r-footer" style="
                    padding: 10px; 
                    color: #000; 
               ">
                    <div id="" style="
                         text-align: center;
                    ">
                        <a href="%{trenqr_http_root}%"><img height="50px" src="%{trenqr_prod_img_root}%/r/fav2.png" alt="trenqr" /></a>
                    </div>
                    <div style="
                         margin-top: 30px;
                        padding: 0px 15px;
                    ">
                        <p style="
                            margin: 0;
                            font-size: 13px;
                        ">
                            Pour vous connecter, c"est par ici : <a style="color: rgb(40,148,255);" href="%{trenqr_login_link}%">Connectez-vous</a>
                        </p>
                        <p style="
                           margin: 0;
                           font-size: 13px;
                        ">
                            Vous avez oublié votre mot de passe ? <a style="color: rgb(40,148,255);" href="%{trenqr_start_rcvy_link}%">Réinitialisez votre mot de passe via ce lien</a>
                        </p>
                    </div>
                    <div>
                        <p style="
                            margin: 10px 0 0 0;
                            padding: 0 15px;
                            color: #a1a5af;
                            font-size: 11px;
                        " >
                            Ce message est destiné à : %{tag_ufn}% - (@%{tag_upsd}%), dont l'email est %{tag_ueml}%.
                        </p>
                        <p style="
                            margin: 3px 0 0 0;
                            padding: 0 15px;
                            color: #a1a5af;
                            font-size: 11px;
                        " >
                            <a style="color: rgb(40,148,255);" href="%{trenqr_login_link}%">Connectez-vous</a> à votre compte et modifiez vos paramètres si vous ne voulez plus recevoir de notifications par email.
                        </p>
                        <p style="
                            margin: 10px 0 0 0;
                            padding: 0 15px;
                            color: #a1a5af;
                            font-size: 11px;
                        " >
                            Trenqr™ est une marque déposée de DEUSLYNN ENTREPRISE.
                        </p>
                        <p style="
                            margin: 3px 0 0 0;
                            padding: 0 15px;
                            color: #a1a5af;
                            font-size: 11px;
                        " >
                            © 2016 DEUSLYNN ENTREPRISE, BP 8415 69359 Lyon, FRANCE
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div id="new-acc-panel-mx" style="
            width: 100%; 
            min-width: 550px; 
            max-width: 650px; 
            height: 100%; 
            margin: 20px auto; 
            /*background-color: rgb(242,242,242);*/
            
            position: relative; 
            overflow: auto;
            color: #000;
            font-family: Arial, sans-serif;
        ">
            <div id='n-a-pan-right' 
                 style='
                    color: #000;
                '
            >
                <div id='n-a-pan-r-body' style='
                     padding: 20px; 
                     background-color: #fff; 
                     color: #000;
                     font-size: 15px;'
                >
                    <section id="tqr-rcmd-scn-cfrm-bmx" style="
                        width: 100%;
                        margin: 30px auto 0;
                        text-align: center;
                    ">
                        <div style="
                             text-align: center;
                        ">
                            <a href='%{trenqr_http_root}%'><img height='50' src='/bart1/timg/files/img/r/logo_tqr_beta_be.png' /></a>
                            <!-- <a href='%{trenqr_http_root}%'><img height='50px' src='%{trenqr_prod_img_root}%/r/logo_tqr_beta_be.png' /></a> -->
                        </div>
                        <div id="tqr-rcmd-scn-cfrm-mx" style="
                            border-radius: 8px;
                            padding: 40px 30px;
                            
                            font-family: Arial, sans-serif;
                            text-align: center;
                        ">
                            <header id="tqr-rcmd-scn-cfrm-hdr-bmx">
                                <div id="tqr-rcmd-scn-cfrm-hdr-mx">
                                    <div style="
                                         padding: 0 0 20px 0;
                                        color: #767D84;
                                        font-family: 'Open sans', Arial, sans-serif;
                                        font-size: 25px;
                                    ">Une nouvelle version de Trenqr est disponible</div>
                                </div>
                            </header>
                            <div id="tqr-rcmd-scn-cfrm-bdy-mx" style="
                                margin-top: 18px;
                            ">
                                <div id="tqr-rcmd-scn-cfrm-txt" style="
                                    color: #31314C;
                                    font-size: 15px;
                                ">
                                    <div id="tqr-rcmd-scn-cfrm-txt-hi" style="
                                        margin-bottom: 5px;
                                        text-align: left;
                                    ">Bonjour Louna,</div>
                                    <div id="tqr-rcmd-scn-cfrm-txt-then" style="
                                        text-align: left; 
                                    ">
                                        <p>
                                            Nous avons le plaisir de vous annoncer que nous avons mis à jour Trenqr et que vous pouvez dès à présent profiter de cette nouvelle version.
                                        </p>
                                        <p>
                                            N'hésitez pas à visiter <a href='http://blog.trenqr.com' style='color: #2894ff;'>notre blog</a> pour accéder à notre actualité, nos <a href='' style='color: #2894ff;'>tutoriels</a> et bien plus encore.
                                        </p>
                                    </div>
<!--                                    <div id="tqr-rcmd-scn-cfrm-txt-author" style="
                                        margin-bottom: 5px;
                                        text-align: left;
                                    ">- Dupont Agile -</div>-->
                                </div>
                                <div id="tqr-rcmd-scn-cfrm-btns-mx" style="
                                    margin-top: 50px;
                                ">
                                    <!-- A RETIRER POUR LE MODELE SAMPLE -->
                                    <div>
                                        <a class="tqr-rcmd-scn-cfrm-btn" data-action="ja" href="javascript:;" style="
                                            display: block;
                                            border: 1px solid;
                                            border-radius: 6px;
                                            width: 255px;
                                            margin: 0px auto;
                                            padding: 10px 0px;
                                            background-color: #004589;
                                            
                                            color: #FFF;
                                            font-size: 18px;
                                            text-decoration: none;
                                        ">Acceder au nouveau Trenqr</a>
                                        <a class="tqr-rcmd-scn-cfrm-btn" data-action="nein" href="javascript:;" style="
                                            display: block;
                                            margin: 15px auto 0px;
                                            color: #3F729B;
                                            font-size: 14px;
                                            text-decoration: none;
                                        ">Qu'est ce qui change avec cette nouvelle version ?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div id='n-a-pan-r-footer' style='
                    padding: 10px; 
                    color: #000; 
               '>
                    <div id='' style='
                         text-align: center;
                    '>
                        <a href='%{trenqr_http_root}%'><img height='50' src='/bart1/timg/files/img/r/fav2.png' /></a>
                        <!-- <a href='%{trenqr_http_root}%'><img height='50px' src='%{trenqr_prod_img_root}%/r/fav2.png' /></a> -->
                    </div>
                    <div id="" style="
                         margin-top: 30px;
                        padding: 0px 15px;
                    " >
                        <p style='
                            margin: 0;
                            font-size: 13px;
                        '>
                            Pour vous connecter, c'est par ici : <a style='color: rgb(40,148,255);' href='%{trenqr_login_link}%'>Connectez-vous</a>
                        </p>
                        <p style='
                           margin: 0;
                           font-size: 13px;
                        '>
                            Vous avez oublié votre mot de passe ? <a style='color: rgb(40,148,255);' href='%{trenqr_start_rcvy_link}%'>Réinitialisez votre mot de passe via ce lien</a>
                        </p>
<!--                        <p style='
                           margin: 0;
                           font-size: 13px;
                        '>
                            Vous avez besoin d'aide ou vous rencontrez des difficultés ? <a style='color: rgb(40,148,255);' href='%{trenqr_need_help_blog_link}%'>Obtenir de l'aide</a>
                        </p>-->
                    </div>
                </div>
            </div>
        </div>
        </div>
    </body>
</html>