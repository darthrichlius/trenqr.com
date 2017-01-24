<div id="trpg-ed-ver-ovly-max" class="jb-trpg-ed-ver-ovly-mx this_hide">
    <div class="trpg-conf-part-max this_hide">
        <div class="trpg-conf-part-dialog">
            <p class="trpg-c-p-d-m jb-trpg-c-p-d-m">
                Lorem ipsum dolor sit amet, consectetur adipiscing volutpat.
            </p>
            <div class="trpg-c-p-d-ch">
                <a class="trpg-c-p-d-ch-each" data-action="c" data-par="trpg-ed-ver-ovly-max" href="javascript:;">Annuler</a>
                <a class="trpg-c-p-d-ch-each" data-action="s" data-par="trpg-ed-ver-ovly-max" href="javascript:;">J'ai Compris</a>
            </div>    
        </div>
    </div>
    <a id="tr-ed-v-ovly-cancel" class="tr-v-ovly-cancel jb-tr-v-ovly-ccl" data-f="tr-e-v-ovly-form" href="javascript:;">&times;</a>
    <p class="tr-v-a-hder-title tr-v-ovly-hder-title">
        <span>Configuration</span>
    </p>
    <p class="tr-v-ovly-hder-desc">
        <span>Utiliser ce formulaire pour modifier les composantes de votre Tendance</span>
    </p>

    <form id="tr-e-v-ovly-form" class="trpg-sp-form" data-par="trpg-ed-ver-ovly-max">
        <fieldset>
            <label>
                <span class="tr-v-a-label">Titre</span>
                <input class="check_char jb-trpg-input-tle jb-trpg-ovly-ipt" data-ft="title" placeholder="Titre" data-target="intrpg-ed-ovly-chktr" data-maxch="100" disabled="true"></input>
                <span id="intrpg-ed-ovly-chktr" class="trpg-ed-cr-char-cn" data-init="100">100</span>
            </label>
        </fieldset>
        <p class="erb-trpg-input-tle tr-v-ovly-hder-err this_hide"></p>
        <fieldset>
            <label>
                <span class="tr-v-a-label">Description</span>
                <textarea id="tr-e-v-ovly-desc" class="check_char jb-trpg-input-desc jb-trpg-ovly-ipt" data-ft="description" placeholder="Description" data-target="intrpg-ed-ovly-char-cn" data-maxch="200" disabled="true"></textarea>
                <span id="intrpg-ed-ovly-char-cn" class="trpg-ed-cr-char-cn" data-init="200">200</span>
            </label>
        </fieldset>
        <p class="erb-trpg-input-desc tr-v-ovly-hder-err this_hide"></p>
            <!-- 
                [NOTE 05-12-14] @author L.C.
                Je ne me souviens plus du débat autour de la possibilité de charger la catégorie de la Tendance.
                Dans le doute, je m'abstiens. De plus, à cette date cela me fera moins travaillé car il aurait fallu ...
                ... créer une fenetre pour récupérer les catéories depuis le serveur 
            -->
<!--        <fieldset>
            <label>
                <span class="tr-v-a-label tr-v-a-label-select">Categorie</span>
                <select class="jb-trpg-input-cat" required>
                    <option value='animals'>Animaux</option>
                    <option value='humor' selected>Humour</option>
                    <option value='music'>Musique</option>
                    <option value='politics'>Politique</option>
                    <option value='society'>Société</option>
                </select>
            </label>
        </fieldset>-->
        <fieldset>
            <label>
                <span class="tr-v-a-label tr-v-a-label-select">Participation</span>
                <select class="jb-trpg-input-part jb-trpg-ovly-ipt" data-ft="participation" required disabled="true">
                    <option value="_NTR_PART_PUB" selected>Public</option>
                    <option value="_NTR_PART_PRI">Privé</option>
                </select>
            </label>
        </fieldset>
        <!-- 
        <fieldset>
            <label>
                <span class="tr-v-a-label tr-v-a-label-select">Gratification</span>
                <select class="jsbind-trpg_input_grat" required>
                    <option value="0" selected>Disable</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='5'>5</option>
                    <option value='10'>10</option>
                </select>
            </label>
        </fieldset>
        -->
        <fieldset class="tr-v-ovly-footer">
            <a id="tr-e-v-ovly-reset" class="jb-tr-e-v-ovly-rst" href="javascript:;">Réinitialiser</a>
            <a id="tr-e-v-ovly-save" class="jb-tr-e-v-ovly-save" data-f="tr-e-v-ovly-form" href="javascript:;">Enregistrer</a>
        </fieldset>
    </form>
</div>