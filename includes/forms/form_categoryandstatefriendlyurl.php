<?  // Banner Javascript /////////////////////////////////////////////////////////////// ?>

<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/banner.js"></script>

<?  //////////////////////////////////////////////////////////////////////////////////// ?>
<? 
	echo "<p class=\"informationMessage\">* ".system_showText(LANG_LABEL_REQUIRED_FIELD)." </p>";
	if($message) { ?>
		<p class="successMessage"><?=$message?></p>
	<? } ?>
	<? if($error_message) { ?>
		<p class="errorMessage"><?=$error_message?></p>
	<? } ?>
<? //////////////////////////////////////////////////////////////////////////////////// ?>
<table cellpadding="0" cellspacing="0" border="0" class="standard-table">
    <tr>
        <th colspan="2" class="standard-tabletitle">Friendly URL Details</th>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="standard-table nomargin">
    <tr>
        <th><?=system_showText(LANG_SECTION);?>:</th>
            <td nowrap="nowrap" class="banner-section-type">
                <span><input type="radio" id="section" name="section" value="listing" <? if ($section == "listing" || ($type==4 && $section=="general")) echo "checked=\"checked\""; ?> onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>, 'banner');" class="inputAlign" checked="checked" /> <?=system_showText(LANG_LISTING_FEATURE_NAME);?></span>
                <? if (PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on") { ?>
                    <span><input type="radio" id="section" name="section" value="promotion" <? if ($section == "promotion") echo "checked=\"checked\""; ?> onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>, 'banner');" class="inputAlign" /> <?=system_showText(LANG_PROMOTION_FEATURE_NAME);?></span>
                <? } ?>
                <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
                    <span><input type="radio" id="section" name="section" value="event" <? if ($section == "event") echo "checked=\"checked\""; ?> onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>, 'banner');" class="inputAlign" /> <?=system_showText(LANG_EVENT_FEATURE_NAME);?></span>
                <? } ?>
                <? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
                    <span><input type="radio" id="section" name="section" value="classified" <? if ($section == "classified") echo "checked=\"checked\""; ?> onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>, 'banner');" class="inputAlign" /> <?=system_showText(LANG_CLASSIFIED_FEATURE_NAME);?></span>
                <? } ?>
                <? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
                    <span><input <?=($type==4?"disabled=\"true\"":"")?> type="radio" id="section" name="section" value="article" <? if ($section == "article") echo "checked=\"checked\""; ?> onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>, 'banner');" class="inputAlign" /> <?=system_showText(LANG_ARTICLE_FEATURE_NAME);?></span>
                <? } ?>
                <? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
                    <span><input <?=($type==4?"disabled=\"true\"":"")?> type="radio" id="section" name="section" value="blog" <? if ($section == "blog") echo "checked=\"checked\""; ?> onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>, 'banner');" class="inputAlign" /> <?=system_showText(LANG_BLOG_FEATURE_NAME);?></span>
                <? } ?>    
            </td>
    </tr>
    <tr>
        <th><?=system_showText(LANG_LABEL_CATEGORY)?>:</th>
        <td>
                <?=$categoryDropDown?>
        </td>
    </tr>
		
    <tr id="featureLocation" <?=((($approve_feature=="P" || $approve_feature=="D" ||$approve_feature=="O")&&$type==4)?"":"style=\"display:none\"")?>>
            <td colspan="2">
                    <?include(EDIRECTORY_ROOT."/includes/code/load_location.php");?>
            </td>
    </tr>
    
    <tr>
        <th style="vertical-align:top"><?=system_showText(LANG_LABEL_DESTINATION_URL)?>:</th>
        <td>
            <select name="destination_protocol" class="httpSelect">
                    <?
                    $url_protocols 	= explode(",", URL_PROTOCOL);
                    $sufix = "://";
                    for ($i=0; $i<count($url_protocols); $i++) {
                            $selected = false;
                            $protocol = $url_protocols[$i].$sufix;
                            if ($destination_protocol) {
                                    if (trim($protocol) == trim($destination_protocol)) {
                                            $selected = true;
                                    }
                            }
                            ?><option value="<?=$protocol?>"  <?=($selected==true  ? "selected=\"selected\"" : "")?> ><?=$protocol?></option><?
                    }
                    ?>
            </select>
            <input style="width:79%" type="text" name="destination_url" value="<?=$destination_url?>" class="input-form-banner" maxlength="500" />
            <span><?=system_showText(LANG_MSG_MAX_500_CHARS)?></span>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input type="checkbox" name="show_type" value="1" <?=($show_type=="1") ? "checked" : "";?> class="inputAlign" />Status
        </td>
    </tr>
</table>
<div style="margin-bottom: 10px;"></div>
		
    