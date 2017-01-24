<div id="trpg-cr-ver-ovly-max" class="this_hide">
    <a id="tr-cr-v-ovly-cancel" class="tr-v-ovly-cancel" data-f="tr-cr-v-ovly-form" href="">{wos/deco:_Cancel}</a>
    <p class="tr-v-a-hder-title tr-v-ovly-hder-title">
        <span>{wos/deco:_trpg_Create_header}</span>
    </p>
    <p class="tr-v-ovly-hder-desc">
        <span>{wos/deco:_trpg_Create_intro}</span>
    </p>
    <form id="tr-cr-v-ovly-form" class="trpg-sp-form">
        <fieldset>
            <label>
                <span class="tr-v-a-label">{wos/deco:_Title}</span>
                <input class="jsbind-trpg_input_title" placeholder="Titre"></input>
            </label>
        </fieldset>
        <p class="errbar-trpg_input_title tr-v-ovly-hder-err this_hide"></p>
        <fieldset>
            <label>
                <span class="tr-v-a-label">{wos/deco:_Description}</span>
                <textarea id="tr-cr-v-ovly-desc" class="check_char jsbind-trpg_input_desc" placeholder="Description" data-target="intrpg-cr-ovly-char-cn" data-maxch="200"></textarea>
                <span id="intrpg-cr-ovly-char-cn" class="trpg-ed-cr-char-cn" data-init="200">200</span>
            </label>
        </fieldset>
        <p class="errbar-trpg_input_desc tr-v-ovly-hder-err this_hide"></p>
        <fieldset>
            <label>
                <span class="tr-v-a-label tr-v-a-label-select">{wos/deco:_Category}</span>
                <select class="jsbind-trpg_input_cat" required>
                    <option value='animals'>Animals</option>
                    <option value='humour' selected>Humour</option>
                    <option value='music'>Music</option>
                    <option value='politics'>Politics</option>
                    <option value='society'>Society</option>
                </select>
            </label>
        </fieldset>
        <fieldset>
            <label>
                <span class="tr-v-a-label tr-v-a-label-select">{wos/deco:_Participation}</span>
                <select class="jsbind-trpg_input_part" required>
                    <option value='pub' selected>{wos/deco:_Public}</option>
                    <option value='pri'>{wos/deco:_Private}</option>
                </select>
            </label>
            <a class="trpg-ed-cr-help" href="#" title="{wos/deco:_trpg_Create_part_about}" alt="Learn more about the concept of Participation in KOBOBO Trend">{wos/deco:_Learn_more}</a>
        </fieldset>
        <fieldset>
            <label>
                <span class="tr-v-a-label tr-v-a-label-select">{wos/deco:_Gratification}</span>
                <select class="jsbind-trpg_input_grat" required>
                    <option value="0" selected>{wos/deco:_Disable}</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='5'>5</option>
                    <option value='10'>10</option>
                </select>
            </label>
            <a class="trpg-ed-cr-help" href="#" title="{wos/deco:_trpg_Create_grat_about}" alt="Learn more about the concept of Gratification in KOBOBO Trend">{wos/deco:_Learn_more}</a>
        </fieldset>
        <fieldset class="tr-v-ovly-footer">
            <a id="tr-cr-v-ovly-reset" class="kxlib-reset-form" data-target="tr-cr-v-ovly-form" href="">{wos/deco:_Reset}</a>
            <a id="tr-cr-v-ovly-save" data-f="tr-cr-v-ovly-form" href="">{wos/deco:_trpg_Create_tr}</a>
        </fieldset>
    </form>
</div>