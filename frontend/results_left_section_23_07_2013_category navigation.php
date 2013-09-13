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
	# * FILE: /frontend/result_filters.php
	# ----------------------------------------------------------------------------------------------------
	
	
	$mileRadiusArray = array(
								'' =>'Select mile radius',
								'10'=>'10 mile radius',
								'20'=>'20 mile radius',
								'50'=>'50 mile radius',
								'100'=>'100 mile radius',
								'200'=>'200 mile radius');
	
	
	
	$placeholder = system_showText(LANG_LABEL_SEARCHKEYWORD2);
	$category_id = (isset($category_id)&&$category_id!="")?$category_id:0;
	
	if(empty($_GET['category_id_sub']) && !empty($_GET['category_main_id']))
	{
		$category_id_sub = $category_id;
	}	
	$category_id = !empty($_GET['category_main_id'])? $_GET['category_main_id'] : $category_id ;
	
	
	
	$location1Obj = new Location1();
	$location1Data = $location1Obj->retrieveLocationByName("United States");
		
	if(!isset($location_1) || $location_1=="" || $location_1==0)
	{
		 $location_1 = $location1Data["id"];
	}
	 
	
	$searchByKeywordTip2 = system_showText(LANG_LABEL_SEARCHKEYWORDTIP2);
	if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) { 
		$module_categ = "listing";
        $advancedSearchItem = "listing";
		$action_adv = LISTING_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=listing';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", LISTING_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
		$module_categ = "listing";        
        $advancedSearchItem = "promotion";		
		$action_adv = PROMOTION_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=promotion';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", PROMOTION_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
		$module_categ = "event";
        $advancedSearchItem = "event";
		$action_adv = EVENT_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=event';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", EVENT_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
		$module_categ = "classified";
        $advancedSearchItem = "classified";
		$action_adv = CLASSIFIED_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=classified';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", CLASSIFIED_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
		$module_categ = "article";
        $advancedSearchItem = "article";
		$action_adv = ARTICLE_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=article';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", ARTICLE_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
		$module_categ = "blog";
        $advancedSearchItem = "blog";
		$action_adv = BLOG_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=blog';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", BLOG_DEFAULT_URL);
	}else{ 
		$module_categ = "listing";
        $advancedSearchItem = "listing";
		$action_adv = LISTING_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=listing';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", LISTING_DEFAULT_URL);
	}
	
	if(!$category_main_id && $category_id)
	{
		$itemType = ucfirst($module_categ)."Category";
		$catObj = new $itemType($category_id);
		if($catObj->getNumber("category_id")!=0)
		{
			$category_main_id = $catObj->getNumber("category_id");
			$catObj2 = new $itemType($catObj->getNumber("category_id"));
			if($catObj2->getNumber("category_id")!=0)
			{
				$category_main_id = $catObj2->getNumber("category_id");
				$category_id_sub= $catObj->getNumber("category_id");
			}
		}
	}
	
	

	$action_advL = LISTING_DEFAULT_URL."/results.php" ;
	$action_advP = PROMOTION_DEFAULT_URL."/results.php" ;
	$action_advE = EVENT_DEFAULT_URL."/results.php" ;
	$action_advC = CLASSIFIED_DEFAULT_URL."/results.php" ;
	$action_advA = ARTICLE_DEFAULT_URL."/results.php" ;
	$action_advB = BLOG_DEFAULT_URL."/results.php" ;
	
	$categoryFilterDD = "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-loading-location.gif\" alt=\"".system_showText(LANG_WAITLOADING)."\"/>";
	$categoryFilterSubDD = "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-loading-location.gif\" alt=\"".system_showText(LANG_WAITLOADING)."\"/>";
	$categoryFilterSubDD2 = "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-loading-location.gif\" alt=\"".system_showText(LANG_WAITLOADING)."\"/>";
	
	if($location_1>0)
		$js_fileLoader = system_scriptColectorOnReady("$('#location_filter_1').val('".$location_1."');$('#location_filter_1').change();",$js_fileLoader);
	if($location_2>0)
		$js_fileLoader = system_scriptColectorOnReady("setTimeout(function(){ $('#location_filter_2').val('".$location_2."');$('#location_filter_2').change();},1000);",$js_fileLoader);
	if($location_3>0)
		$js_fileLoader = system_scriptColectorOnReady("setTimeout(function(){ $('#location_filter_3').val('".$location_3."');$('#location_filter_3').change();},3000);",$js_fileLoader);
	if($location_4>0)
		$js_fileLoader = system_scriptColectorOnReady("setTimeout(function(){ $('#location_filter_4').val('".$location_4."');$('#location_filter_4').change();},6000);",$js_fileLoader);
	if($location_5>0)
		$js_fileLoader = system_scriptColectorOnReady("setTimeout(function(){ $('#location_filter_5').val('".$location_5."');$('#location_filter_5').change();},9000);",$js_fileLoader);

	if($category_main_id && $category_id_sub && strpos($_SERVER['REQUEST_UIR']))
		$js_fileLoader = system_scriptColectorOnReady("showAdvancedSearch('".$advancedSearchItem."', '".$aux_template_id."', false, true,".$category_main_id.",'main',".$category_main_id.",".$category_id_sub.",".$category_id_sub.",$category_id);",$js_fileLoader);
	else if($category_main_id)
		$js_fileLoader = system_scriptColectorOnReady("showAdvancedSearch('".$advancedSearchItem."', '".$aux_template_id."', false, true,".$category_main_id.",'main',".$category_main_id.",".$category_id.");",$js_fileLoader);
	else
		$js_fileLoader = system_scriptColectorOnReady("showAdvancedSearch('".$advancedSearchItem."', '".$aux_template_id."', false, true,".(is_array($category_id)?0:$category_id).",'main');",$js_fileLoader);
	$js_fileLoader = system_scriptColectorOnReady("$('#keyword_filter').watermark('".$placeholder."');",$js_fileLoader);
	$js_fileLoader = system_scriptColectorOnReady("showLocationsFilterTab('".$advancedSearchItem."','".$action_adv."','".(($zip!="")?"proximityFilter":"")."');",$js_fileLoader);
	$js_fileLoader = system_scriptColectorOnReady("$('#advanced-search-button').trigger('click');",$js_fileLoader);//this is a hack to ensure that the advanced search link will work properly
	
	
	$openSection = "locationFilter";
	if($zip!="")
		$openSection = "proximityFilter";
	if($advancedSearchItem == "blog"||$advancedSearchItem == "article")
		$openSection = "none";
	$actionFilter = $_SERVER["REQUEST_URI"];
	//locations
		$_non_default_locations = "";
		$_default_locations_info = "";
		if (EDIR_DEFAULT_LOCATIONS) {

			system_retrieveLocationsInfo ($_non_default_locations, $_default_locations_info);

			$last_default_location	  =	$_default_locations_info[count($_default_locations_info)-1]['type'];
			$last_default_location_id = $_default_locations_info[count($_default_locations_info)-1]['id'];

			if ($_non_default_locations) {
				$objLocationLabel = "Location".$_non_default_locations[0];
				${"Location".$_non_default_locations[0]} = new $objLocationLabel;
				${"Location".$_non_default_locations[0]}->SetString("location_".$last_default_location, $last_default_location_id);
				${"locations".$_non_default_locations[0]} = ${"Location".$_non_default_locations[0]}->retrieveLocationByLocation($last_default_location);
			}

		} else {
			$_non_default_locations = explode(",", EDIR_LOCATIONS);
			$objLocationLabel = "Location".$_non_default_locations[0];
			${"Location".$_non_default_locations[0]} = new $objLocationLabel;
			${"locations".$_non_default_locations[0]}  = ${"Location".$_non_default_locations[0]}->retrieveAllLocation();
		}
		//end locations
?>
<script type="text/javascript">
		function showTab (type) {
			var activeTab = "#"+type+"Li";
			var activeTabContent = "#"+type+"Tab";
			$("#whereFilterUl li").removeClass("active"); //Remove any "active" class
			$(activeTab).addClass("active"); //Add "active" class to selected tab
			$("[id$=FilterTab]").hide(); //Hide all tab content
			$('[id$=FilterTab] input').val('');
			$('[id$=FilterTab] select').val('');
			if(type=="locationFilter")
			    $('#location_filter_1').val('<?=$location1Data["id"]?>');
			setTimeout(function(){$('#location_filter_1').change()},1000);
			$(activeTabContent).fadeIn(); //Fade in the active content
		}
		function showLocationsFilterTab(section, action, openTab) {

			//$('#keyword_filter').autocomplete('options','extraParams','{module:\''+section+'\'}');
	        
			var activeTab = "#locationFilterLi";
			var activeTabContent = "#locationFilterTab";
			if(openTab && openTab!="")
			{
				var activeTab = "#"+openTab+"Li";
				var activeTabContent = "#"+openTab+"Tab";
			}

			if(section == 'blog' || section == 'article')
			{
				$("#whereFilterUl li").hide();
				$("[id$=FilterTab]").hide();
			}
			else
			{
				$("#whereFilterUl li").show();
				$(activeTab).addClass("active"); 
				$(activeTabContent).fadeIn(); 
			}
			$("#search_form").attr("action", action);
			$("[id$=FV]").removeAttr('class');
			$("#"+section+"FV").attr('class','active');
			//$("#subCategoriesFilter").hide();
			
		}
		/*function checkValid(obj)
		{
			
				
		}*/




		
		function submitSearchFilter()
		{ 
			if(document.search_form_filter.zip_filter.value!="" && document.search_form_filter.zip_filter.value.length < 3)
			{
				alert("Enter at least the first 3 numbers");
				return false;
			} 
			
			if(document.search_form_filter.dist_loc.value!="" && (isNaN(document.search_form_filter.dist_loc.value)||(document.search_form_filter.dist_loc.value%1!==0)))
			{	
				alert("<?=system_showText(LANG_USE_ONLY_INTEGERS)?>");
				return false;
			}
			if(typeof document.search_form_filter.category_sub_filter_id2 != 'undefined' && document.search_form_filter.category_sub_filter_id2.value!="")
			{
				if(typeof document.search_form.category_id != 'undefined')
				{
					var element = document.getElementById("category_id");
					element.parentNode.removeChild(element);
				}

				var categ_elt = document.createElement("input");
				categ_elt.setAttribute('name','category_id');
				categ_elt.setAttribute('type','hidden');
				categ_elt.value = document.search_form_filter.category_sub_filter_id2.value;
				document.search_form.appendChild(categ_elt);
				
				var categ_elt_main = document.createElement("input");
				categ_elt_main.setAttribute('name','category_main_id');
				categ_elt_main.setAttribute('type','hidden');
				categ_elt_main.value = document.search_form_filter.category_filter_id.value;
				document.search_form.appendChild(categ_elt_main);

				var categ_elt_main = document.createElement("input");
				categ_elt_main.setAttribute('name','category_id_sub');
				categ_elt_main.setAttribute('type','hidden');
				categ_elt_main.value = document.search_form_filter.category_sub_filter_id.value;
				document.search_form.appendChild(categ_elt_main);
			}
			else if(typeof document.search_form_filter.category_sub_filter_id != 'undefined' && document.search_form_filter.category_sub_filter_id.value!="")
			{
				if(typeof document.search_form.category_id != 'undefined')
				{
					var element = document.getElementById("category_id");
					element.parentNode.removeChild(element);
				}

				var categ_elt = document.createElement("input");
				categ_elt.setAttribute('name','category_id');
				categ_elt.setAttribute('type','hidden');
				categ_elt.value = document.search_form_filter.category_sub_filter_id.value;
				document.search_form.appendChild(categ_elt);
				
				var categ_elt_main = document.createElement("input");
				categ_elt_main.setAttribute('name','category_main_id');
				categ_elt_main.setAttribute('type','hidden');
				categ_elt_main.value = document.search_form_filter.category_filter_id.value;
				document.search_form.appendChild(categ_elt_main);

				var categ_elt_main2 = document.createElement("input");
				categ_elt_main2.setAttribute('name','category_id_sub');
				categ_elt_main2.setAttribute('type','hidden');
				categ_elt_main2.value = "";
				document.search_form.appendChild(categ_elt_main2);
			}
			else if(typeof document.search_form_filter.category_filter_id != 'undefined' && document.search_form_filter.category_filter_id.value!="")
			{
				if(typeof document.search_form.category_id != 'undefined')
				{
					var element = document.getElementById("category_id");
					element.parentNode.removeChild(element);
				}
				var categ_elt = document.createElement("input");
				categ_elt.setAttribute('name','category_id');
				categ_elt.setAttribute('type','hidden');
				categ_elt.value = document.search_form_filter.category_filter_id.value;
				document.search_form.appendChild(categ_elt);

				var categ_elt_main = document.createElement("input");
				categ_elt_main.setAttribute('name','category_id_sub');
				categ_elt_main.setAttribute('type','hidden');
				categ_elt_main.value = "";
				document.search_form.appendChild(categ_elt_main);
			}

		        if(!(document.search_form_filter.keyword_filter.value=="<?=$placeholder?>"||document.search_form_filter.keyword_filter.value==""))
				document.search_form.keyword.value = document.search_form_filter.keyword_filter.value;

			var loc_search = false;

			if(document.search_form.action.indexOf("blog")!= -1 || document.search_form.action.indexOf("article") != -1 )
			{
				if(typeof document.search_form.location_1 != 'undefined')
				{
					var element = document.getElementById("location_1");
					element.parentNode.removeChild(element);
				}
				if(typeof document.search_form.location_2 != 'undefined')
				{
					var element = document.getElementById("location_2");
					element.parentNode.removeChild(element);
				}
				if(typeof document.search_form.location_3 != 'undefined')
				{
					var element = document.getElementById("location_3");
					element.parentNode.removeChild(element);
				}
				if(typeof document.search_form.location_4 != 'undefined')
				{
					var element = document.getElementById("location_4");
					element.parentNode.removeChild(element);
				}
				if(typeof document.search_form.location_5 != 'undefined')
				{
					var element = document.getElementById("location_5");
					element.parentNode.removeChild(element);
				}
				if(typeof document.search_form.zip_top != 'undefined')
				{
					var element = document.getElementById("zip_top");
					element.parentNode.removeChild(element);
				}
				if(typeof document.search_form.dist_top != 'undefined')
				{
					var element = document.getElementById("dist_top");
					element.parentNode.removeChild(element);
				}
				if(typeof document.search_form.dist_top_loc != 'undefined')
				{
					var element = document.getElementById("dist_top_loc");
					element.parentNode.removeChild(element);
				}
			}
			else
			{
				if(document.search_form_filter.zip_filter.value!="")
					document.search_form.zip_top.value = document.search_form_filter.zip_filter.value;
				if(document.search_form_filter.dist_filter.value!="")
					document.search_form.dist_top.value = document.search_form_filter.dist_filter.value;
				if(document.search_form_filter.dist_loc.value!="")
					document.search_form.dist_top_loc.value = document.search_form_filter.dist_loc.value;
				if( typeof document.search_form_filter.location_filter_1 != 'undefined' && document.search_form_filter.location_filter_1.value!="")
				{
					if(typeof document.search_form.location_1 != 'undefined')
					{
						var element = document.getElementById("location_1");
						element.parentNode.removeChild(element);
					}
					var loc_elt = document.createElement("input");
					loc_elt.setAttribute('name','location_1');
					loc_elt.setAttribute('type','hidden');
					loc_elt.value = document.search_form_filter.location_filter_1.value;
					document.search_form.appendChild(loc_elt);
					loc_search = true;
				}
				if( typeof document.search_form_filter.location_filter_2 != 'undefined' && document.search_form_filter.location_filter_2.value!="")
				{
					if(typeof document.search_form.location_2 != 'undefined')
					{
						var element = document.getElementById("location_2");
						element.parentNode.removeChild(element);
					}
					var loc_elt = document.createElement("input");
					loc_elt.setAttribute('name','location_2');
					loc_elt.setAttribute('type','hidden');
					loc_elt.value = document.search_form_filter.location_filter_2.value;
					document.search_form.appendChild(loc_elt);
					loc_search = true;
				}
				if( typeof document.search_form_filter.location_filter_3 != 'undefined' && document.search_form_filter.location_filter_3.value!="")
				{
					if(typeof document.search_form.location_3 != 'undefined')
					{
						var element = document.getElementById("location_3");
						element.parentNode.removeChild(element);
					}
					var loc_elt = document.createElement("input");
					loc_elt.setAttribute('name','location_3');
					loc_elt.setAttribute('type','hidden');
					loc_elt.value = document.search_form_filter.location_filter_3.value;
					document.search_form.appendChild(loc_elt);
					loc_search = true;
				}
				if( typeof document.search_form_filter.location_filter_4 != 'undefined' && document.search_form_filter.location_filter_4.value!="")
				{
					if(typeof document.search_form.location_4 != 'undefined')
					{
						var element = document.getElementById("location_4");
						element.parentNode.removeChild(element);
					}
					var loc_elt = document.createElement("input");
					loc_elt.setAttribute('name','location_4');
					loc_elt.setAttribute('type','hidden');
					loc_elt.value = document.search_form_filter.location_filter_4.value;
					document.search_form.appendChild(loc_elt);
					loc_search = true;
				}
				if( typeof document.search_form_filter.location_filter_5 != 'undefined' && document.search_form_filter.location_filter_5.value!="")
				{
					if(typeof document.search_form.location_5 != 'undefined')
					{
						var element = document.getElementById("location_5");
						element.parentNode.removeChild(element);
					}
					var loc_elt = document.createElement("input");
					loc_elt.setAttribute('name','location_5');
					loc_elt.setAttribute('type','hidden');
					loc_elt.value = document.search_form_filter.location_filter_5.value;
					document.search_form.appendChild(loc_elt);
					loc_search = true;
				}
				
			}
			
			if(loc_search == false && typeof document.search_form.where != 'undefined')	
			{
				document.search_form.where.value="";
				if(typeof document.search_form.location_1 != 'undefined' && document.search_form_filter.location_filter_1)
					document.search_form_filter.location_filter_1.value="";
				if(typeof document.search_form.location_2 != 'undefined' && document.search_form_filter.location_filter_2)
					document.search_form_filter.location_filter_2.value="";
				if(typeof document.search_form.location_3 != 'undefined' && document.search_form_filter.location_filter_3)
					document.search_form_filter.location_filter_3.value="";
				if(typeof document.search_form.location_4 != 'undefined' && document.search_form_filter.location_filter_4)
					document.search_form_filter.location_filter_4.value="";
				if(typeof document.search_form.location_5 != 'undefined' && document.search_form_filter.location_filter_5)
					document.search_form_filter.location_filter_5.value="";
					
			}
			
			document.search_form.submit();
		}
</script>

<form id="search_form_filter" class="form" action="<?=$actionFilter?>" method="get" name="search_form_filter">

	<div id="keywordFilter" class="keyword">
		<input type="text" name="keyword_filter" id="keyword_filter" value="<?=$keyword?>"/>
		<input type="button" id="keyword_go" />
	</div>
	<? include(system_getFrontendPath("results_letters.php"));?>
	<div style="border-bottom:3px solid #fff;padding-bottom:10px;"></div>
	<div class="search-form">Navigation</div>
	<div class="dropdown-bg"> 
		<select onchange="sendParameter(this.value);" class="dropdown">
				<option value="listing" <?=(($advancedSearchItem=="listing")?"selected=\"selected\"":"")?>>Directory</option>
			<? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
				<option value="event" <?=(($advancedSearchItem=="event")?"selected=\"selected\"":"")?>><?=ucwords(system_showText(EVENT_FEATURE_NAME))?></option>
			<?}?>
			<? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
				<option value="classified" <?=(($advancedSearchItem=="classified")?"selected=\"selected\"":"")?>><?=ucwords(system_showText(CLASSIFIED_FEATURE_NAME))?></option>
			<?}?>
			<? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
				<option value="article" <?=(($advancedSearchItem=="article")?"selected=\"selected\"":"")?>><?=ucwords(system_showText(ARTICLE_FEATURE_NAME))?></option>
			<?}?>
			<? if (PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on" && CUSTOM_PROMOTION_FEATURE == "on") { ?>
				<option value="promotion" <?=(($advancedSearchItem=="promotion")?"selected=\"selected\"":"")?>><?=ucwords(system_showText(PROMOTION_FEATURE_NAME))?></option>
			<?}?>
			<? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
				<option value="blog" <?=(($advancedSearchItem=="blog")?"selected=\"selected\"":"")?>><?=ucwords(system_showText(BLOG_FEATURE_NAME))?></option>
			<?}?>
		</select>
	</div>
	
	<? unset($showLoc);
		if ($_default_locations_info) {
			foreach ($_default_locations_info as $info) {
				if ($info["show"] == "y") {
					$showLoc = true;
					break;
				}
			}
		}
		if (${"locations".$_non_default_locations[0]} || $showLoc) { 
			$filter_search =  true;
			$adv_s = $advanced_search;
			$advanced_search = false;
		?>
		<div id="scrolltobutton" class="left">
			<div id="LocationbaseAdvancedSearchFilter">
				<? include(EDIRECTORY_ROOT."/includes/code/load_location.php"); ?>
			</div>
		</div>
		<? $advanced_search = $adv_s;}?>
		<div class="search-form">OR Zip code</div>
                <input type="text" name="zip" id="zip_filter" value="<?=$zip?>" class="text-bg" />
                Enter at least the first 3 numbers
		<div class="dropdown-bg">
			<select id="dist_filter" name="dist_loc" class="dropdown">
				<? foreach($mileRadiusArray as $mile => $mileValue){?>
					<?if($dist_loc==$mile){?>
						<option value="<?=$mile?>" selected="selected"><?=$mileValue?></option>
					<?}else{?>
						<option value="<?=$mile?>"><?=$mileValue?></option>
					<?}?>
				<?}?>
			</select>
		</div>
		<button type="button" onclick="submitSearchFilter()" class="search-button" ></button>
                <div style="border-bottom:3px solid #fff;padding-bottom:10px;"></div>
                <!--<div class="browse-category-box"></div>-->
                <input  type="hidden" name="category_filter_id" id="category_filter_id" value="<?php echo $category_id ; ?>" />
		<input  type="hidden" name="category_sub_filter_id" id="category_sub_filter_id" value="<?php echo $category_id_sub; ?>" />
</form>
<!-- <div id="resultsMap"></div>-->
<script>
$(document).ready(function(){

        $('#keyword_go').click(function(){
            $('.search-button').trigger('click');
	});	
	
        $('#zip_filter').click(function(){
            $('.clear-location').find('a').trigger('click');
	});
        
        /*var module = '<?php echo ACTUAL_MODULE_FOLDER?>';
	if(module == 'deal') 
		module = 'promotion';
	if(module.length > 0)
	{
		var aux_data = "fnct=categories&type="+module;
	}
	else
	{
		var aux_data = "fnct=categories&type=listing";
	}
	
	if( $('#category_filter_id').val()!="" ){
		aux_data = aux_data + "&category_id="+ $('#category_filter_id').val();
                
	}
	if( $('#category_sub_filter_id').val()!="" ){
		aux_data = aux_data + "&category_id_sub="+ $('#category_sub_filter_id').val();
	}
	
	$.ajax({
		  url: DEFAULT_URL+"/category_listing.php",
		  context: document.body,
		  data: aux_data,
		  success: function(html){
			$('.browse-category-box').html(html);
		}});
		
	category_switch();
        */

	if($('#location_filter_1').val()!=''){
		$('#location_filter_1').trigger('change');
	}

        
});
function sendParameter(value)
{
	listing_form_action(value); 
	var aux_data = "fnct=categories&type="+value;
	$.ajax({
		  url: DEFAULT_URL+"/category_listing.php",
		  context: document.body,
		  data: aux_data,
		  success: function(html){
		  $('.browse-category-box').html(html);
		}});
	
}

function listing_form_action(value)
{
	value =  value.toString();
	switch(value)
	{
	case 'event':
		var action = '<?php echo $action_advE; ?>';
		break;
	case 'classified':
		var action = '<?php echo $action_advC; ?>';
		break;
	case 'article':
		var action = '<?php echo  $action_advA; ?>';
		break;
	case 'promotion':
		var action = '<?php echo $action_advP; ?>';
		break;
	case 'blog':
		var action = '<?php echo $action_advB; ?>';
		break;
	default:
		var action = '<?php echo $action_advL; ?>';
		break;
	}
	action = action.toString();
	$("#search_form").attr("action", action);
		
}

function category_switch(){

	$('.browse-category a').live('click',function(e){
		e.preventDefault();
		var obj = this;
	    if( $(obj).hasClass('main_cat_link') ){

	    	$(obj).parent().find('.sub_cat a').removeClass('Select');
			$(".main_cat_link").removeClass('Select');
		    
			if($(obj).parent().find('.main_cat').length){
				var main_cat = $(obj).parent().find('.main_cat');
				$('.browse-category').find(".sub_cat").slideUp("slow");
				$('.browse-category').find(".main_cat2").removeClass("main_cat2").addClass("main_cat");
				$(main_cat).removeClass("main_cat").addClass("main_cat2");
				$(main_cat).parent("li").children("div.sub_cat").slideDown("slow");
				$(obj).addClass('Select');
				$("#category_filter_id").val($(obj).attr('id'));
                                $('.search-button').trigger('click');
			}else{	
				var main_cat2 = $(obj).parent().find('.main_cat2');
				$(main_cat2).removeClass("main_cat2").addClass("main_cat");
				$(main_cat2).parent("li").children("div.sub_cat").slideUp("slow");
				$("#category_filter_id").val('');
				
			}
			
			$("#category_sub_filter_id").val('');
		}else{
			$(".sub_cat_link").removeClass('Select');
			$(obj).addClass('Select');
			$("#category_sub_filter_id").val($(obj).attr('id'));
                        $('.search-button').trigger('click');
		} 
	});
	
}


$(".main_cat, .main_cat2").live("click", function(Event){

	$('.browse-category').find(".sub_cat a").removeClass('Select');
	$(".main_cat_link").removeClass('Select');
	
	if($(Event.target).hasClass("main_cat")){

		var main_cat = this;
		$('.browse-category').find(".sub_cat").slideUp("slow");
		$(main_cat).parent("li").find('.main_cat_link').addClass('Select');
		$('.browse-category').find(".main_cat2").removeClass("main_cat2").addClass("main_cat");
		$(main_cat).removeClass("main_cat").addClass("main_cat2");
		$(main_cat).parent("li").children("div.sub_cat").slideDown("slow");
		$("#category_filter_id").val($(main_cat).parent("li").find('.main_cat_link').attr('id'));
		
	}else{
		
		$(main_cat2).parent("li").find('.main_cat_link').removeClass('Select');
		var main_cat2  = this;
		$(main_cat2).removeClass("main_cat2").addClass("main_cat");
		$(main_cat2).parent("li").children("div.sub_cat").slideUp("slow");
		$("#category_filter_id").val('');
		
		
	}
	$("#category_sub_filter_id").val('');
	return false;	
});
</script>





