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
	
	//setting_get("domain".SELECTED_DOMAIN_ID."_filter", $is_filter_on);
	//if($is_filter_on!="on")
	//{
	//	return;
	//}
	$placeholder = system_showText(LANG_LABEL_SEARCHKEYWORD2);
	$category_id = (isset($category_id)&&$category_id!="")?$category_id:0;
	if(!isset($category_id_sub) && isset($category_main_id))
	{
		$category_id_sub = $category_id;
	}	
	$location1Obj = new Location1();
	$location1Data = $location1Obj->retrieveLocationByName("United States");	
	if(!isset($location_1) || $location_1=="" || $location_1==0)
	{
		
		 $location_1 = $location1Data["id"];
	}
	$searchByKeywordTip2 = system_showText(LANG_LABEL_SEARCHKEYWORDTIP2);
	if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) {
		$advancedSearchItem = "listing";
		$action_adv = LISTING_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=listing';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", LISTING_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
		$advancedSearchItem = "promotion";		
		$action_adv = PROMOTION_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=promotion';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", PROMOTION_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
		$advancedSearchItem = "event";
		$action_adv = EVENT_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=event';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", EVENT_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
		$advancedSearchItem = "classified";
		$action_adv = CLASSIFIED_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=classified';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", CLASSIFIED_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
		$advancedSearchItem = "article";
		$action_adv = ARTICLE_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=article';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", ARTICLE_DEFAULT_URL);
	} elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
		$advancedSearchItem = "blog";
		$action_adv = BLOG_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=blog';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", BLOG_DEFAULT_URL);
	}else{
		$advancedSearchItem = "listing";
		$action_adv = LISTING_DEFAULT_URL."/results.php" ;
		$autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL.'?module=listing';
		//$advancedSearchPath = EDIRECTORY_FOLDER.str_replace(NON_SECURE_URL, "", LISTING_DEFAULT_URL);
	}
	
	if(!$category_main_id && $category_id)
	{
		$itemType = ucfirst($advancedSearchItem)."Category";
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
			if(document.search_form_filter.dist_loc.value!="" && document.search_form_filter.location_filter_3.value=="")
			{
				alert("<?=system_showText(LANG_SELECT_LOC3_FIRST)?>");
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
				if(typeof document.search_form.location_1 != 'undefined')
					document.search_form_filter.location_filter_1.value="";
				if(typeof document.search_form.location_2 != 'undefined')
					document.search_form_filter.location_filter_2.value="";
				if(typeof document.search_form.location_3 != 'undefined')
					document.search_form_filter.location_filter_3.value="";
				if(typeof document.search_form.location_4 != 'undefined')
					document.search_form_filter.location_filter_4.value="";
				if(typeof document.search_form.location_5 != 'undefined')
					document.search_form_filter.location_filter_5.value="";
					
			}
			
			document.search_form.submit();
		}
</script>
<form id="search_form_filter" class="form" action="<?=$actionFilter?>" method="get" name="search_form_filter">
	<div id="resultFilters">
    <h3> What are you looking for? </h3>
    <p> Use this search box to search through our site and find what you are looking for. </p>
	
		<div id="moduleDiv">
			<div><a <?=(($advancedSearchItem=="listing")?"class=\"active\"":"")?> href="javascript:void(0)" id="listingFV" onclick="showAdvancedSearch('listing', '<?=$aux_template_id?>', false, true,0,'main');showLocationsFilterTab('listing','<?=$action_advL?>');" ><?=system_showText(LISTING_FEATURE_NAME)?></a></div>
			<div><a <?=(($advancedSearchItem=="event")?"class=\"active\"":"")?> href="javascript:void(0)" id="eventFV" onclick="showAdvancedSearch('event', '<?=$aux_template_id?>', false, true,0,'main');showLocationsFilterTab('event','<?=$action_advE?>');" ><?=system_showText(EVENT_FEATURE_NAME)?></a></div>
			<div><a <?=(($advancedSearchItem=="classified")?"class=\"active\"":"")?> href="javascript:void(0)" id="classifiedFV" onclick="showAdvancedSearch('classified', '<?=$aux_template_id?>', false, true,0,'main');showLocationsFilterTab('classified','<?=$action_advC?>');" ><?=system_showText(CLASSIFIED_FEATURE_NAME)?></a></div>
			<div><a <?=(($advancedSearchItem=="article")?"class=\"active\"":"")?> href="javascript:void(0)" id="articleFV" onclick="showAdvancedSearch('article', '<?=$aux_template_id?>', false, true,0,'main');showLocationsFilterTab('article','<?=$action_advA?>');" ><?=system_showText(ARTICLE_FEATURE_NAME)?></a></div>
			<div><a <?=(($advancedSearchItem=="promotion")?"class=\"active\"":"")?> href="javascript:void(0)" id="promotionFV" onclick="showAdvancedSearch('promotion', '<?=$aux_template_id?>', false, true,0,'main');showLocationsFilterTab('promotion','<?=$action_advP?>');" ><?=system_showText(PROMOTION_FEATURE_NAME)?></a></div>
			<div><a <?=(($advancedSearchItem=="blog")?"class=\"active\"":"")?> href="javascript:void(0)" id="blogFV" onclick="showAdvancedSearch('blog', '<?=$aux_template_id?>', false, true,0,'main');showLocationsFilterTab('blog','<?=$action_advB?>');" ><?=system_showText(BLOG_FEATURE_NAME)?></a></div>
			
		</div>
		
		<div id="categoriesFilterWrap">
			<div id="categoriesFilter" style="margin:14px 0 10px;"><?=$categoryFilterDD;?></div>
			<div id="subCategoriesFilter" <?=(($category_id&&$category_main_id)?"":"style=\"display:none\"")?>><?=$categoryFilterSubDD;?></div>
			
			<div id="subCategoriesFilter2" <?=(($category_id&&$category_main_id&&$category_id_sub)?"":"style=\"display:none\"")?>><?=$categoryFilterSubDD2;?></div>
		</div>
		
		<div id="keywordFilter" style="margin:10px 0 10px;">
			<fieldset>
				<label> </label>
				<input type="text" name="keyword_filter" id="keyword_filter" value="<?=$keyword?>" class="text" />
			</fieldset>
		</div>
		
		<div class="clear"></div>
		<?/*
                        
                 $js_fileLoader = system_scriptColectorOnReady("

                        $('#keyword_filter').autocomplete(
                            '".AUTOCOMPLETE_KEYWORD_URL."',
                                    {
                                        delay:1000,
                                        dataType: 'html',
                                        minChars:".AUTOCOMPLETE_MINCHARS.",
                                        matchSubset:0,
                                        extraParams:{module:'".$advancedSearchItem."'},
                                        selectFirst:0,
                                        matchContains:1,
                                        cacheLength:".AUTOCOMPLETE_MAXITENS.",
                                        autoFill:false,
                                        maxItemsToShow:".AUTOCOMPLETE_MAXITENS.",
                                        max:".AUTOCOMPLETE_MAXITENS."
                                    }
                            );

                ",$js_fileLoader);
                */        
		?>
		<ul id="whereFilterUl" <?=((!($advancedSearchItem=="article"||$advancedSearchItem=="blog"))?"":"style=\"display:none\"")?>>
			<li id="locationFilterLi" onclick="showTab('locationFilter')" <?=(($openSection=="locationFilter")?"class=\"active\"":"")?>><?=system_showText(LANG_LOCATION_FILTER_LABEL)?></li>
			<li id="proximityFilterLi" onclick="showTab('proximityFilter')" <?=(($openSection=="proximityFilter")?"class=\"active\"":"")?>><?=system_showText(LANG_PROXIMITY_FILTER_LABEL)?></li>
		</ul>
		<div class="clear"></div>
		<div id="locationFilterTab" <?=($openSection=="locationFilter"?"":"style=\"display:none\"")?>>
			<div>
	        	<div>
					<?=string_ucwords(ZIPCODE_UNIT_LABEL_PLURAL)." ".system_showText(LANG_SEARCH_LABELZIPCODE_OF)?>
	                <input type="text" name="dist_loc" id="dist_loc"  value="<?=$dist_loc?>" class="text" style="width:143px;" /><!-- onkeyup="checkValid(this)" -->
	            	
	            </div>
	            <div>
	            <?
					unset($showLoc);
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
						<div class="left">
							<div id="LocationbaseAdvancedSearchFilter">
								<? include(EDIRECTORY_ROOT."/includes/code/load_location.php"); ?>
							</div>
						</div>
				<? 
					$advanced_search = $adv_s;
				}?>
	            </div>
				
				
			</div>
			
			<div class="clear"></div>
		</div>
		<div id="proximityFilterTab" <?=($openSection=="proximityFilter"?"":"style=\"display:none\"")?>>
			
				<? if (ZIPCODE_PROXIMITY == "on") { ?>
	                <div>
						<?=string_ucwords(ZIPCODE_UNIT_LABEL_PLURAL)." ".system_showText(LANG_SEARCH_LABELZIPCODE_OF)?>
	                    <input type="text" id="dist_filter" name="dist" value="<?=$dist?>" class="text" />
	                    
	               </div>
	            <? } ?>
					<div>
					<?=(ZIPCODE_PROXIMITY == "on" ? string_ucwords(ZIPCODE_LABEL) : "")?>
					<input type="text" name="zip" id="zip_filter" value="<?=$zip?>" class="text" />
					
					</div>

		</div>
		<button type="button" id="buttonSearchFilter" onclick="submitSearchFilter()" ><?=system_showText(LANG_BUTTON_SEARCH);?></button>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</form>