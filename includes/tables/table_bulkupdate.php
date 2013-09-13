 <?

	/*==================================================================*\
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
	\*==================================================================*/

	# ----------------------------------------------------------------------------------------------------
	# * FILE: /includes/tables/table_bulkupdate.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	if (string_strpos($_SERVER["PHP_SELF"], LISTING_FEATURE_FOLDER)) {
		$bulkType = "listing";
	} else if (string_strpos($_SERVER["PHP_SELF"], CLASSIFIED_FEATURE_FOLDER)) {
		$bulkType = "classified";
	} else if (string_strpos($_SERVER["PHP_SELF"], EVENT_FEATURE_FOLDER)) {
		$bulkType = "event";
	} else if (string_strpos($_SERVER["PHP_SELF"], ARTICLE_FEATURE_FOLDER)) {
		$bulkType = "article";
	} else if (string_strpos($_SERVER["PHP_SELF"], BANNER_FEATURE_FOLDER)) {
		$bulkType = "banner";
	} else if (string_strpos($_SERVER["PHP_SELF"], PROMOTION_FEATURE_FOLDER)) {
		$bulkType = "promotion";
	} else if (string_strpos($_SERVER["PHP_SELF"], BLOG_FEATURE_FOLDER)) {
		$bulkType = "blog";
	}
	 
	// Status Drop Down
	$statusObj = new ItemStatus();
	unset($arrayValue);
	unset($arrayName);
	$arrayValue = $statusObj->getValues();
	$arrayName = $statusObj->getNames();
	unset($arrayValueDD);
	unset($arrayNameDD);
	for ($i=0; $i<count($arrayValue); $i++) {
		if ($arrayValue[$i] != "E") {
			$arrayValueDD[] = $arrayValue[$i];
			$arrayNameDD[] = $arrayName[$i];
		}
	}
	
	$statusDropDown = html_selectBox("status", $arrayNameDD, $arrayValueDD, "", "", "class='input-dd-form-search$bulkType'", "-- ".system_showText(LANG_LABEL_SELECT_ALLSTATUS)." --");
	
	if ($bulkType != "promotion" && $bulkType != "blog") {
		// Level Drop Down
		$dbMain = db_getDBObject(DEFAULT_DB, true);
		$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
		unset($dbMain);
		if ($bulkType != "article") {
			if ($bulkType != "banner") {
                $objLevelType = ucfirst($bulkType)."Level";
                $LevelObj = new $objLevelType(true);
                unset($levelStatus);
                foreach ($LevelObj->value as $k => $value) {
                    $arrayNameLL[] = ucfirst($LevelObj->name[$k]);
                    $arrayValueLL[] = $value;
                }
			} else {
                $LevelObj = new BannerLevel(true);
                unset($levelStatus);
                foreach ($LevelObj->value as $k => $value) {
                    $arrayNameLL[] = ucfirst($LevelObj->displayName[$k]);
                    $arrayValueLL[] = $value;
                }
			}
			if ($bulkType != "banner") {
				$levelDropDown = html_selectBox("level", $arrayNameLL, $arrayValueLL, "", "", "class='input-dd-form-search$bulkType'", "-- ".system_showText(LANG_LABEL_SELECT_ALLLEVELS)." --");
			} else {
				$levelDropDown = html_selectBox("level", $arrayNameLL, $arrayValueLL, "", "", "class='input-dd-form-search$bulkType'", "-- ".system_showText(LANG_LABEL_SELECT_ALLTYPES)." --");
			}
			
		}
	}
	
	if ($bulkType != "banner" && $bulkType != "promotion") {
		##################################################################################################################################
		# CATEGORY
		##################################################################################################################################

        $fields = "`id`, `title`";
        $orderby = "`title`";


        $item_scalability = @constant(strtoupper($bulkType)."CATEGORY_SCALABILITY_OPTIMIZATION");
        $useCategTree = false;
        if ($item_scalability == "on"){
            $useCategTree = true;
        } 
        
        if (!$useCategTree){
            if ($bulkType == "listing"){

                $fields = array("id", "title");

                $nameArray  = array();
                $valueArray = array();
                $valueCatArray = array();
                $str_cats = "";
                $resultArray = db_loadCategoriesDropdown("ListingCategory", $fields, 0, 1, "off", SELECTED_DOMAIN_ID, $str_cats, $orderby);
                $str_cats = string_substr($str_cats, 0, -1);

                $valueArray = $resultArray["values"];
                $nameArray = $resultArray["names"];

                $valueCatArray = explode(",",$str_cats);

                if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION != "on") {
                    $valueArray[] = "";
                    $nameArray[] = "--------------------------------------------------";
                }

            } else {

                $categories = db_getFromDB($bulkType."category", "category_id", 0, "all", $orderby, "object", SELECTED_DOMAIN_ID, false, $fields);
                $nameArray  = array();
                $valueArray = array();
                $nameCatArray = array();
                $valueCatArray = array();
                if ($categories) {
                    foreach ($categories as $category) {
                        if ($item_scalability != "on") {
                            $valueArray[] = "";
                            $nameArray[]  = "--------------------------------------------------";
                        }
                        $valueArray[] = $category->getNumber("id");
                        $nameArray[] = $category->getString("title");
                        if ($item_scalability != "on") {
                            $subcategories = db_getFromDB($bulkType."category", "category_id", $category->getNumber("id"), "all", $orderby, "object", SELECTED_DOMAIN_ID, false, $fields);
                            if ($subcategories) {
                                foreach ($subcategories as $subcategory) {
                                    $valueArray[] = $subcategory->getNumber("id");
                                    $nameArray[] = " &raquo; ".$subcategory->getString("title");
                                    $subcategories2 = db_getFromDB($bulkType."category", "category_id", $subcategory->getNumber("id"), "all", $orderby, "object", SELECTED_DOMAIN_ID, false, $fields);
                                    if ($subcategories2) {
                                        foreach ($subcategories2 as $subcategory2) {
                                            $valueArray[] = $subcategory2->getNumber("id");
                                            $nameArray[] = " &raquo;&raquo; ".$subcategory2->getString("title");
                                            $subcategories3 = db_getFromDB($bulkType."category", "category_id", $subcategory2->getNumber("id"), "all", $orderby, "object", SELECTED_DOMAIN_ID, false, $fields);
                                            if ($subcategories3) {
                                                foreach ($subcategories3 as $subcategory3) {
                                                    $valueArray[] = $subcategory3->getNumber("id");
                                                    $nameArray[] = " &raquo;&raquo;&raquo; ".$subcategory3->getString("title");
                                                    $subcategories4 = db_getFromDB($bulkType."category", "category_id", $subcategory3->getNumber("id"), "all", $orderby, "object", SELECTED_DOMAIN_ID, false, $fields);
                                                    if ($subcategories4) {
                                                        foreach ($subcategories4 as $subcategory4) {
                                                            $valueArray[] = $subcategory4->getNumber("id");
                                                            $nameArray[] = " &raquo;&raquo;&raquo;&raquo; ".$subcategory4->getString("title");
                                                            $nameCatArray[] = $subcategory4->getString("title");
                                                            $valueCatArray[] = $subcategory4->getNumber("id");
                                                        }
                                                    } else {
                                                        $nameCatArray[] = $subcategory3->getString("title");
                                                        $valueCatArray[] = $subcategory3->getNumber("id");
                                                    }
                                                }
                                            } else {
                                                $nameCatArray[] = $subcategory2->getString("title");
                                                $valueCatArray[] = $subcategory2->getNumber("id");
                                            }
                                        }
                                    } else {
                                        $nameCatArray[] = $subcategory->getString("title");
                                        $valueCatArray[] = $subcategory->getNumber("id");
                                    }
                                }
                            } else {
                                $nameCatArray[] = $category->getString("title");
                                $valueCatArray[] = $category->getNumber("id");
                            }
                        }
                    }
                }

                if ($item_scalability != "on") {
                    $valueArray[] = "";
                    $nameArray[] = "--------------------------------------------------";
                }
            }
        
            ///////////////////////////////

            $categoryDropDown = html_selectBox_BulkUpdate("add_category_id", $nameArray, $valueArray, "", "", "class='input-dd-form-search$bulkType'", "-- ".system_showText(LANG_LABEL_SELECT_ALLCATEGORIES)." --", $valueCatArray);
		}
        
		$arrayNameRC  = array();
		$arrayValueRC = array();
		$removeCatDropDown = html_selectBox("removecategory", $arrayNameRC, $arrayValueRC, "", "", "class='input-dd-form-settings'", " ");

        if ($useCategTree) { 
            $feedDropDown = "<select name='feed' id='feed' multiple size='5' style=\"width:500px\">";
            $feedDropDown .= "</select>";
        }
	} ?>

<? if ($bulkType != "blog") { ?>   

<? // Account Search Javascript /////////////////////////////////////////////////////////////////////// ?>
<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/accountsearch.js"></script>

<? } ?>

<? if ($bulkType == "banner") { ?>   
    
<?// Banner Javascript /////////////////////////////////////////////////////////////// ?>
<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/banner.js"></script>

<? } ?>

<table border="0" cellpadding="2" cellspacing="0" class="standard-table noMargin">
	<tr>
		<th>&nbsp;</th>
		<td><input type="checkbox" name="delete_all" id="delete_all" class="inputAlign" onclick="disableBulkOptions(document.getElementById('delete_all')); "/><?=system_showText(LANG_SITEMGR_DELETE_ALL_SELECTED)?><span>&nbsp;(<?=system_showText(LANG_SITEMGR_DELETEALL_INFO)?>)</span></td>
	</tr>
	
</table>

<?
if (!$user && $bulkType != "blog") {
	$acct_search_table_title = system_showText(LANG_SITEMGR_ACCOUNTSEARCH_SELECT_DEFAULT);
	$acct_search_field_name = "change_account_id";
	$acct_search_field_value = "";
	$acct_search_required_mark = false;
	$acct_search_form_width = "96%";
	$acct_search_cell_width = "";
	$return = system_generateAjaxAccountSearch($acct_search_table_title, $acct_search_field_name, $acct_search_field_value, $acct_search_required_mark, $acct_search_form_width,$acct_search_cell_width);
	echo "<div id=\"change_account\" style=\"display:none\">".$return."</div>";
}
?>
<div id="bulkDiv" style="display:none"></div>
<div id="bulkDiv2" style="display:none"></div>

<table border="0" cellpadding="2" cellspacing="0" class="standard-table">
	<? if ($bulkType != "blog") { ?>
    <tr>
		<th>&nbsp;</th>
		<td><input type="checkbox" name="change_no_owner" id="change_no_owner" class="inputAlign" /><?=system_showText(LANG_SITEMGR_NOOWNER)?><span>&nbsp;(<?=system_showText(LANG_SITEMGR_ACCOUNTCHANGE_NOOWNER_INFO)?>)</span></td>
	</tr>
    <? } ?>

	<? if ($bulkType != "article" && $bulkType != "promotion" && $bulkType != "blog") { ?>
		<tr>
			<th><?=($bulkType != "banner") ? system_showText(LANG_SITEMGR_CHANGELEVEL) : system_showText(LANG_SITEMGR_CHANGETYPE)?>:</th>
			<td>
				<?=$levelDropDown?>
			</td>
		</tr>
	<? } ?>

	<? if ($bulkType != "promotion") { ?>
		<tr>
			<th><?=system_showText(LANG_SITEMGR_CHANGESTATUS)?>:</th>
			<td>
				<?=$statusDropDown?>
			</td>
		</tr>
	<? } ?>

	<? if ($bulkType != "promotion"  && $bulkType != "blog") { ?>
		<tr>
			<th><?=system_showText(LANG_SITEMGR_CHANGEEXPIRATIONDATE)?>:</th>
			<td>
				<input type="text" name="change_renewaldate" id="change_renewaldate" value="" class="input-form-settings" />
				<span>(<?=format_printDateStandard()?>)</span>
			</td>
		</tr>
	<? } ?>

	<? if ($bulkType == "banner") {
		$nameArray  = array();
		$valueArray = array();
		$categoryDropDown = html_selectBox("add_category_id", $nameArray, $valueArray, $search_category_id, "id=\"add_category_id\" disabled", "class='input-dd-form-banner' style='width: 350px;'", system_showText(LANG_LABEL_SELECT_ALLPAGESBUTITEMPAGES));
		
		?>
		<tr>
			<th><?=system_showText(LANG_SITEMGR_LABEL_SECTION)?>:</th>
			<td nowrap>

				<input id="section_general" type="radio" name="change_section" value="general" onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.add_category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>);" class="inputAlign" /> <?=system_showText(LANG_SITEMGR_LABEL_GENERALPAGES)?>

				<input id="section_listing" type="radio" name="change_section" value="listing" onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.add_category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>);" class="inputAlign" /> <?=string_ucwords(system_showText(LANG_SITEMGR_LISTING))?>

                <? if (PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on") { ?>
					<input id="section_promotion" type="radio" name="change_section" value="promotion" onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.add_category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>);" class="inputAlign" /> <?=string_ucwords(system_showText(LANG_SITEMGR_PROMOTION))?>
				<? } ?>
                
				<? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
					<input id="section_event" type="radio" name="change_section" value="event" onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.add_category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>);" class="inputAlign" /> <?=string_ucwords(system_showText(LANG_SITEMGR_EVENT))?>
				<? } ?>

				<? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
					<input id="section_classified" type="radio" name="change_section" value="classified" onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.add_category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>);" class="inputAlign" /> <?=string_ucwords(system_showText(LANG_SITEMGR_CLASSIFIED))?>
				<? } ?>

				<? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
					<input id="section_article" type="radio" name="change_section" value="article" onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.add_category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>);" class="inputAlign" /> <?=string_ucwords(system_showText(LANG_SITEMGR_ARTICLE))?>
				<? } ?>
                    
                <? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
					<input id="section_article" type="radio" name="change_section" value="blog" onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.add_category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>);" class="inputAlign" /> <?=string_ucwords(system_showText(LANG_SITEMGR_BLOG))?>
				<? } ?>    

				<input id="section_global" type="radio" name="change_section" value="global" onclick="fillBannerCategorySelect('<?=DEFAULT_URL?>', this.form.add_category_id, this.value, this.form, <?=SELECTED_DOMAIN_ID?>);" class="inputAlign" /> <?=system_showText(LANG_SITEMGR_BANNER_GLOBAL)?>

			</td>
		</tr>
	<? } ?>

	<tr>

	<? if ($bulkType != "promotion") { ?>

			<th><?=string_ucwords(system_showText($bulkType == "banner" ? LANG_SITEMGR_CATEGORY : LANG_SITEMGR_LANG_SITEMGR_ADDCATEGORY))?>:</th>
            <? if (!$useCategTree) { ?>
                <td><?=$categoryDropDown?></td>
            <? } else { ?>
                <td colspan="2" class="treeView">
                    <ul id="<?=$bulkType?>_categorytree_id_0" class="categoryTreeview">&nbsp;</ul>

                    <table width="100%" border="0" cellpadding="2" class="tableCategoriesADDED" cellspacing="0">
                        <tr>
                            <th colspan="2" class="tableCategoriesTITLE alignLeft"><strong><?=system_showText(LANG_CATEGORIES_SUBCATEGS);?>:</strong></th>
                        </tr>
                        <tr id="msgback2" class="informationMessageShort">
                            <td colspan="2" style="width: auto;"><p><img width="16" height="14" src="<?=DEFAULT_URL?>/images/icon_atention.gif" alt="<?=LANG_LABEL_ATTENTION?>" title="<?=LANG_LABEL_ATTENTION?>" /> <?=system_showText(LANG_MSG_CLICKADDTOSELECTCATEGORIES);?></p></td>
                        </tr>
                        <tr id="msgback" class="informationMessageShort" style="display:none">
                            <td colspan="2"><div><img width="16" height="14" src="<?=DEFAULT_URL?>/images/icon_atention.gif" alt="<?=LANG_LABEL_ATTENTION?>" title="<?=LANG_LABEL_ATTENTION?>" /></div><p><?=system_showText(LANG_MSG_CLICKADDTOADDCATEGORIES);?></p></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tableCategoriesCONTENT"><?=$feedDropDown?></td>
                        </tr>
                        <tr>
                            <td class="tableCategoriesBUTTONS" colspan="2">
                                <center>
                                    <button class="input-button-form" type="button" value="<?=system_showText(LANG_BUTTON_VIEWCATEGORYPATH)?>" onclick="JS_displayCategoryPath(document.<?=$bulkType?>_setting.feed, '<?=system_showText(LANG_MSG_SELECT_CATEGORY_FIRST)?>', '../<?=constant(strtoupper($bulkType)."_FEATURE_FOLDER");?>', 'info_window', false, 300, 100);"><?=system_showText(LANG_BUTTON_VIEWCATEGORYPATH)?></button>
                                    <button class="input-button-form" type="button" value="<?=system_showText(LANG_BUTTON_REMOVESELECTEDCATEGORY)?>" onclick="JS_removeCategory(document.<?=$bulkType?>_setting.feed, false);"><?=system_showText(LANG_BUTTON_REMOVESELECTEDCATEGORY)?></button>
                                </center>
                            </td>
                        </tr>
                    </table>
                </td>
            <? } ?>
            
		</tr>

		<tr id="tr_category" style="display: none">
			<th><?=string_ucwords(system_showText(LANG_SITEMGR_LANG_SITEMGR_REMOVECATEGORY))?>:</th>
			<td><div id="remove_category"></div></td>
		</tr>
	<? } ?>
        
</table>

<? if ($useCategTree) { ?>
    <a href="#" id="info_window" class="iframe fancy_window_categPath" style="display:none"></a>
    <input type="hidden" name="return_categories" value=""/>
    <script language="javascript" type="text/javascript" src="<?=DEFAULT_URL?>/scripts/categorytree.js"></script>
<? } ?>
<script type="text/javascript">
    
    <? if ($useCategTree) { ?>
    
    loadCategoryTree('all', '<?=$bulkType?>_', '<?=ucfirst($bulkType)?>Category', 0, 0, '<?=($bulkType == "listing" ? DEFAULT_URL."/".EDIR_CORE_FOLDER_NAME."/".LISTING_FEATURE_FOLDER : DEFAULT_URL)?>',<?=SELECTED_DOMAIN_ID?>);
    
    function JS_addCategory(id) {

		feed = document.<?=$bulkType?>_setting.feed;
		var text = $("#liContent"+id).html();
		var flag=true;
		for (i=0;i<feed.length;i++) 
            if (feed.options[i].value==id) 
                flag=false;

		if(text && id && flag){
			feed.options[feed.length] = new Option(text, id);
			$('#categoryAdd'+id).after("<span class=\"categorySuccessMessage\"><?=system_showText(LANG_MSG_CATEGORY_SUCCESSFULLY_ADDED)?></span>").css('display', 'none');
			$('.categorySuccessMessage').fadeOut(5000);
		} else {
			if (!flag) $('#categoryAdd'+id).after("</a> <span class=\"categoryErrorMessage\"><?=system_showText(LANG_MSG_CATEGORY_ALREADY_INSERTED)?></span> </li>");  
            else ('#categoryAdd'+id).after("</a> <span class=\"categoryErrorMessage\"><?=system_showText(LANG_MSG_SELECT_VALID_CATEGORY)?></span> </li>");
		}

	}
    
    <? } ?>
    
	$(document).ready(function() {
		//DATE PICKER
		<?
		if ( DEFAULT_DATE_FORMAT == "m/d/Y" ) $date_format = "mm/dd/yy";
		elseif ( DEFAULT_DATE_FORMAT == "d/m/Y" ) $date_format = "dd/mm/yy";
		?>

		$('#change_renewaldate').datepicker({
			dateFormat: '<?=$date_format?>',
			changeMonth: true,
			changeYear: true,
            yearRange: '<?=date("Y")?>:<?=date("Y")+10?>'
		});

		$('#change_expirationdate').datepicker({
			dateFormat: '<?=$date_format?>',
			changeMonth: true,
			changeYear: true,
            yearRange: '<?=date("Y")?>:<?=date("Y")+10?>'
		});
		
	});
</script>