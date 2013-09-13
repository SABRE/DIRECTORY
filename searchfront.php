<?
/* ==================================================================*\
  ######################################################################
  #                                                                    #
  # Copyright 2005 Arca Solutions, Inc. All Rights Reserved.           #
  #                                                                    #
  # This file may not be redistributed in whole or part.               #
  # eDirectory is licensed on a per-domain basis.                      #
  #                                                                    #
  # ---------------- eDirectory IS NOT FREE SOFTWARE ----------------- #
  #                                                                    #
  # http://www.edirectory.com | http://www.edirectory.com/license.html #
  ######################################################################
  \*================================================================== */

# ----------------------------------------------------------------------------------------------------
# * FILE: /searchfront.php
# ----------------------------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------------------
# AUX
# ----------------------------------------------------------------------------------------------------
require(EDIRECTORY_ROOT . "/frontend/checkregbin.php");

# ----------------------------------------------------------------------------------------------------
# CODE
# ----------------------------------------------------------------------------------------------------

$action = NON_SECURE_URL . "/results.php";
$searchByKeywordTip = system_showText(LANG_LABEL_SEARCHKEYWORDTIP);
$autocomplete_keyword_url = AUTOCOMPLETE_KEYWORD_URL . '?module=listing';
$hasAdvancedSearch = false;
$hasWhereSearch = true;
$hasWhereSearchDisplay = true;

if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) {
    $searchByKeywordTip = system_showText(LANG_LABEL_SEARCHKEYWORDTIP_LISTING);
    $autocomplete_keyword_url = AUTOCOMPLETE_KEYWORD_URL . '?module=listing';
    $action = LISTING_DEFAULT_URL . "/results.php";
    $action_adv = LISTING_DEFAULT_URL . "/results.php";
    $hasAdvancedSearch = true;
    $advancedSearchItem = "listing";
    $advancedSearchPath = EDIRECTORY_FOLDER . str_replace(NON_SECURE_URL, "", LISTING_DEFAULT_URL);
} elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
    $searchByKeywordTip = system_showText(LANG_LABEL_SEARCHKEYWORDTIP_PROMOTION);
    $autocomplete_keyword_url = AUTOCOMPLETE_KEYWORD_URL . '?module=promotion';
    $action = PROMOTION_DEFAULT_URL . "/results.php";
    $action_adv = PROMOTION_DEFAULT_URL . "/results.php";
    $hasAdvancedSearch = true;
    $advancedSearchItem = "promotion";
    $advancedSearchPath = EDIRECTORY_FOLDER . str_replace(NON_SECURE_URL, "", PROMOTION_DEFAULT_URL);
} elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
    $searchByKeywordTip = system_showText(LANG_LABEL_SEARCHKEYWORDTIP_EVENT);
    $autocomplete_keyword_url = AUTOCOMPLETE_KEYWORD_URL . '?module=event';
    $action = EVENT_DEFAULT_URL . "/results.php";
    $action_adv = EVENT_DEFAULT_URL . "/results.php";
    $hasAdvancedSearch = true;
    $advancedSearchItem = "event";
    $advancedSearchPath = EDIRECTORY_FOLDER . str_replace(NON_SECURE_URL, "", EVENT_DEFAULT_URL);
} elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
    $searchByKeywordTip = system_showText(LANG_LABEL_SEARCHKEYWORDTIP_CLASSIFIED);
    $autocomplete_keyword_url = AUTOCOMPLETE_KEYWORD_URL . '?module=classified';
    $action = CLASSIFIED_DEFAULT_URL . "/results.php";
    $action_adv = CLASSIFIED_DEFAULT_URL . "/results.php";
    $hasAdvancedSearch = true;
    $advancedSearchItem = "classified";
    $advancedSearchPath = EDIRECTORY_FOLDER . str_replace(NON_SECURE_URL, "", CLASSIFIED_DEFAULT_URL);
} elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
    $searchByKeywordTip = system_showText(LANG_LABEL_SEARCHKEYWORDTIP_ARTICLE);
    $autocomplete_keyword_url = AUTOCOMPLETE_KEYWORD_URL . '?module=article';
    $action = ARTICLE_DEFAULT_URL . "/results.php";
    $action_adv = ARTICLE_DEFAULT_URL . "/results.php";
    $hasAdvancedSearch = true;
    $hasWhereSearchDisplay = false;
    $advancedSearchItem = "article";
    $advancedSearchPath = EDIRECTORY_FOLDER . str_replace(NON_SECURE_URL, "", ARTICLE_DEFAULT_URL);
} elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
    $searchByKeywordTip = system_showText(LANG_LABEL_SEARCHKEYWORDTIP_POST);
    $autocomplete_keyword_url = AUTOCOMPLETE_KEYWORD_URL . '?module=blog';
    $action = BLOG_DEFAULT_URL . "/results.php";
    $action_adv = BLOG_DEFAULT_URL . "/results.php";
    $hasAdvancedSearch = true;
    $hasWhereSearchDisplay = false;
    $advancedSearchItem = "blog";
    $advancedSearchPath = EDIRECTORY_FOLDER . str_replace(NON_SECURE_URL, "", BLOG_DEFAULT_URL);
} else {
    if (strpos($_SERVER["REQUEST_URI"], "profile/add") !== false)
        $hide_for_filter_search = true;
    $hasAdvancedSearch = true;
    $hasAdvancedSearchButton = true;
    $action_adv = LISTING_DEFAULT_URL . "/results.php";
}

if (!$browsebylocation && !$browsebycategory) {

    /*
     * Social network options
     */
    $useSocialNetworkLocation = false;
    if (sess_getAccountIdFromSession() && !$where) {
        $profileObj = new Profile(sess_getAccountIdFromSession());
        if ($profileObj->getString("location") && $profileObj->getString("usefacebooklocation")) {
            $where = $profileObj->getString("location");
            $useSocialNetworkLocation = true;
        }
    }

    /*
     * GeoIP
     */
    $waitGeoIP = false;

    if (!$useSocialNetworkLocation
            && !$where
            && GEOIP_FEATURE == "on"
            && $advancedSearchItem != "article"
            && $advancedSearchItem != "blog"
            && (!$screen || string_strpos($_SERVER["PHP_SELF"], "profile") > 0)
            && !$letter
            && (string_strpos($_SERVER["REQUEST_URI"], "results.php") === false)
    ) {

        $waitGeoIP = true;

        $where = system_showText(LANG_LABEL_WAIT_LOCATION);

        $js_fileLoader = system_scriptColectorOnReady("

				$.ajax({
				   type: \"GET\",
				   url: \"" . DEFAULT_URL . "/getGeoIP.php\",
				   success: function(msg){
					    $('#where').removeClass('ac_loading');
					    $('#where').attr('disabled', '');
						$('#where').attr('value', msg);
				   }
				 });

			", $js_fileLoader);
    }
}

$hasWhereSearch ? $auxScript = "document.getElementById('where').value" : $auxScript = "''";
?>

<script type="text/javascript">
    function checkSubmit(action)
    {
        if($("#advanced-search").css('display')!="block")
            $("#advanced-search").remove();
        $("#search_form").attr("action", action);
        $("#search_form").submit();
    }
    function checkValidDist(obj)
    {
        if(document.search_form.location_3.value=="")
            alert("<?= system_showText(LANG_SELECT_LOC3_FIRST) ?>");
        else if(isNaN(obj.value)||(obj.value%1!==0))
            alert("<?= system_showText(LANG_USE_ONLY_INTEGERS) ?>");
        return false;
				
    }
</script>
<form style="display:none" class="form" name="search_form" id="search_form" method="get" action="<?= $action; ?>" <?= (($hide_for_filter_search) ? "style=\"display:none\"" : "") ?>>

    <div id="search">
        <fieldset>
            <label><?= system_showText(LANG_LABEL_SEARCHKEYWORD); ?></label>
            <input type="text" name="keyword" id="keyword" value="<?= $keyword; ?>" />
            <p><?= $searchByKeywordTip ?></p>
        </fieldset>

<?
if (ACTUAL_MODULE_FOLDER != PROMOTION_FEATURE_FOLDER || (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER && PROMOTION_SCALABILITY_USE_AUTOCOMPLETE == "on")) {

    $js_fileLoader = system_scriptColectorOnReady("

                        $('#keyword,#keyword_filter').autocomplete(
                            '$autocomplete_keyword_url',
                                    {
                                        delay:1000,
                                        dataType: 'html',
                                        minChars:" . AUTOCOMPLETE_MINCHARS . ",
                                        matchSubset:0,
                                        selectFirst:0,
                                        matchContains:1,
                                        cacheLength:" . AUTOCOMPLETE_MAXITENS . ",
                                        autoFill:false,
                                        maxItemsToShow:" . AUTOCOMPLETE_MAXITENS . ",
                                        max:" . AUTOCOMPLETE_MAXITENS . "
                                    }
                            );

                ", $js_fileLoader);
}

//if ($hasWhereSearch) { 
?>

        <fieldset <?= ($hasWhereSearchDisplay ? "" : "style=\"display:none\"") ?>>
            <label><?= system_showText(LANG_LABEL_SEARCHWHERE); ?></label>
            <input type="text" name="where" id="where" value="<?= $where; ?>" <?= ($waitGeoIP ? "class=\"ac_loading\" disabled=\"disabled\"" : "") ?> />
            <p><?= system_showText(LANG_LABEL_SEARCHWHERETIP); ?></p>
        </fieldset>

        <?
        $js_fileLoader = system_scriptColectorOnReady("

					$('#where').autocomplete(
						'" . AUTOCOMPLETE_LOCATION_URL . "',
							{
								delay:1000,
								minChars:" . AUTOCOMPLETE_MINCHARS . ",
								matchSubset:0,
								selectFirst:0,
								matchContains:1,
								cacheLength:" . AUTOCOMPLETE_MAXITENS . ",
								autoFill:false,
								maxItemsToShow:" . AUTOCOMPLETE_MAXITENS . ",
								max:" . AUTOCOMPLETE_MAXITENS . "
							}
					 );

				", $js_fileLoader);

        //} 
        ?>
        <? if (!$keepFormOpen) { ?>
            <div class="left">
                <button type="button" id="buttonSearch" onclick="checkSubmit('<?= $action ?>');"><?= system_showText(LANG_BUTTON_SEARCH); ?></button>

            <?
            //if ($hasAdvancedSearch) {
            $aux_template_id = $template_id;
            ?>
                <a id="advanced-search-button" <?= ($hasAdvancedSearchButton ? "style=\"display:none\"" : "") ?> href="javascript:void(0);" onclick="showAdvancedSearch('<?= $advancedSearchItem ?>', '<?= $aux_template_id ?>', true);">
                    <span id="advanced-search-label"><?= system_showText(LANG_BUTTON_ADVANCEDSEARCH); ?></span>
                    <span id="advanced-search-label-close" style="display:none"><?= system_showText(LANG_BUTTON_ADVANCEDSEARCH_CLOSE); ?></span>
                </a>
                <? //}  ?>
            </div>
            <? } ?>

    </div>

<?
//if ($hasAdvancedSearch  && !$keepFormOpen) {
$template_id = $template_id ? $template_id : 0;
?>
    <div id="advanced-search" style="display:none;">
        <? include(EDIRECTORY_ROOT . "/advancedsearch.php") ?>
    </div>
<?
//} 
if (!$keepFormOpen) {
    ?>
    </form>
        <? } ?>