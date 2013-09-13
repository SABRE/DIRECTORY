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
	# * FILE: /sitemgr/banner/featured.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (BANNER_FEATURE != "on") { exit; }

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	permission_hasSMPerm();

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_POST);
	extract($_GET);

	//increases frequently actions
	if (!isset($operation)) system_setFreqActions('banner_location_category','BANNER_FEATURE');

	$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER;
	$url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
	$sitemgr = 1;

	$url_search_params_prev = "";
	$connector="";
	$url_search_params = "";
	if(isset($_GET['letter']))
	{
		$connector=($url_search_params == "")?"":"&";
		$url_search_params = $connector."letter=".$_GET['letter'];
	}
	if(isset($_GET['screen']))
	{
		$connector=($url_search_params == "")?"":"&";
		$url_search_params .= $connector."screen=".$_GET['screen'];
	}
	if(isset($_GET['banner_to_approve']))
	{
		$connector=($url_search_params == "")?"":"&";
		$url_search_params .= $connector."banner_to_approve=1";
	}
	
	//echo $url_search_params;

    $show_country = 0;
	$using_location2 = 0;
 	$using_location3 = 1;
 	$added_info = "";
 	$choose_banner_to_approve="";
 	$loc = "";
 	$category_key = false;
 	$category_key_value = 0;
 	
 	if(!isset($section))
		$section = "listing";
    
    $letterField = "`caption`";
    
    $manageOrder = system_getManageOrderBy($_POST["order_by"] ? $_POST["order_by"] : $_GET["order_by"], "Banner", BANNER_SCALABILITY_OPTIMIZATION, $fields);
    
    $whereCond = " type = 4 AND approve_feature!='n'";
	$url_search_params_add = "";
	foreach($_GET as $key=>$value)
	{
		if($key == "prev")
		{
			
		}
		if(strpos($key,"ategory_")!==false)
		{
			$sections_arr = explode("_",$key);
			$whereCond .= " AND category_id = ".$value;
			$whereCond .= " AND section = '".$sections_arr[1]."'";
			$category_key = $sections_arr[1];
			$category_key_value = $value;
			$url_search_params_add .= ($url_search_params_add==""?$key."=".$value:"&".$key."=".$value);
			if($banner_to_approve)
			{
				$moduleCat = ucfirst($sections_arr[1])."Category";
				if($sections_arr[1]=="promotion")
					$moduleCat = "ListingCategory";
				$choose_banner_to_approve = $category_key;
				$catObj = new $moduleCat($value);
				$category_name = $catObj->getString("title");
				$added_info = ">> (".ucfirst($sections_arr[1]).") ".$category_name." in ";
			}
				
		}
		if($key=="location_1")
		{
			$whereCond .= " AND feature_onlocation1 = ".$value."";
			$loc = "location_1=".$location_1;
			$url_search_params_add .= ($url_search_params_add==""?$loc:"&".$loc);
			if($banner_to_approve)
			{
				$locObj = new Location1($location_1);
				$location_name = $locObj->getString("name");
			}
		}
		if($key=="location_2")
		{
			$whereCond .= " AND feature_onlocation2 = ".$value."";
			$loc .= "&location_2=".$location_2;
			$url_search_params_add .= ($url_search_params_add==""?$loc:"&".$loc);
			if($banner_to_approve)
			{
				$locObj = new Location2($location_2);
				$location_name = $locObj->getString("name").",".$location_name;
			}
		}
		if($key=="location_3")
		{
			$whereCond .= " AND feature_onlocation3 = ".$value."";
			$loc .= "&location_3=".$location_3;
			$url_search_params_add .= ($url_search_params_add==""?$loc:"&".$loc);
			if($banner_to_approve)
			{
				$locObj = new Location3($location_3);
				$location_name = $locObj->getString("name").",".$location_name;
			}
		}

	}
	
	if(isset($_GET['approve']) && isset($_GET['banner_to_approve']))
	{
		$bannerAppObj = new Banner($_GET['approve']);
		$bannerAppObj->setString("approve_feature", "O");
		$bannerAppObj->Save();
		$bannerAppObj->denyAllByCategoryLocation($category_key,$category_key_value,$whereCond,true);
		unset($bannerAppObj);
	}
	else if(isset($_GET['reset']) && isset($_GET['banner_to_approve']))
	{
		$bannerAppObj = new Banner();
		$bannerAppObj->resetAllByCategoryLocation($category_key,$category_key_value,$whereCond);
		unset($bannerAppObj);
	}
    
	$pageObj  = new pageBrowsing("Banner", $screen, RESULTS_PER_PAGE, ($_GET["newest"] ? "id DESC" : $manageOrder), $letterField, $letter, $whereCond, $fields);
	if($banner_to_approve)
	{
		$override = false;
		$added_info .= $location_name;
		$connector="";
		if(isset($_GET['banner_to_approve']))
		{
			$connector=($url_search_params_add == "")?"":"&";
			$url_search_params_add .= $connector."banner_to_approve=1";
		}
		//$paging_url = DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/featured.php";
	}
	else 
	{
		$override = true;
		//$paging_url = DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/featured.php?".$url_search_params_add;
	}
	$banners = $pageObj->retrievePage("array",false,$override,$loc,$category_key,$category_key_value,$show_country,$using_location2,$using_location3,$added_info,$choose_banner_to_approve);

	$paging_url = DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/featured.php?".$url_search_params_add;

	// Letters Menu
	$letters = $pageObj->getString("letters");
	foreach ($letters as $each_letter) {
		if ($each_letter == "#") {
			$letters_menu .= "<a href=\"$paging_url&letter=no\" ".(($letter == "no") ? "style=\"color:#EF413D\"" : "" ).">".string_strtoupper($each_letter)."</a>";
		} else {
			$letters_menu .= "<a href=\"$paging_url&letter=".$each_letter."\" ".(($each_letter == $letter) ? "style=\"color:#EF413D\"" : "" ).">".string_strtoupper($each_letter)."</a>";
		}
	}
	
	if($choose_banner_to_approve!="")
		$section = $choose_banner_to_approve;

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");
	
?>
<script language="javascript" type="text/javascript">
	function showDiv (type) {
		var activeUl = "#li_" + type;
		var activeTabContent = "#tab_" + type;
		$("#NavUl li").removeClass("step_active"); //Remove any "active" class
		$(activeUl).addClass("step_active"); //Add "active" class to selected tab
		$(".module_tab").hide(); //Hide all tab content
		$(activeTabContent).fadeIn(); //Fade in the active content
	}

	function denyFeature(bannerId) {
		 var url_ajax = "<?=DEFAULT_URL?>"+"/<?=SITEMGR_ALIAS?>/banner/updateFeatured.php?id="+bannerId;
         loadOnDIV(url_ajax,'banner_rowId_'+bannerId);
	}

	function showBanner(bannerId) {
		$("#bannerDiv"+bannerId).trigger('click'); 
	}

	function hideBanner(bannerId) {
		$("#bannerDiv"+bannerId).hide(); 
	}
</script>
<div id="main-right">
    <div id="top-content">
        <div id="header-content">
            <h1 class="highlight"><?=(($added_info!="")?"<a href=\"".DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/featured.php\">":"")?> <?=string_ucwords(system_showText(LANG_SITEMGR_MENU_FEATURED))?> <?=system_showText(LANG_SITEMGR_BANNER_SING)?><?=($added_info!=""?"</a>":"")?> <?=$added_info?></h1>
        </div>
    </div>
    <div id="content-content">

            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

                <? include (INCLUDES_DIR."/tables/table_banner_submenu.php"); ?>

                <div>
					<ul id="NavUl" class="import_steps">
						
						<?if(($choose_banner_to_approve=="listing" && $section == "listing") || $choose_banner_to_approve==""){?>
	                        <li id="li_listing" <?=($section == "listing" ? "class=\"step_active\"" : "")?>>
	                             <a href="javascript:void(0)" onclick="showDiv('listing')"><?=system_showText(LANG_SITEMGR_LISTING);?></a>
	                        </li>
                        <?}?>

						<?if(($choose_banner_to_approve=="promotion" && $section == "promotion") || $choose_banner_to_approve==""){?>
	                        <li id="li_promotion" <?=($section == "promotion" ? "class=\"step_active\"" : "")?>>
	                             <a href="javascript:void(0)" onclick="showDiv('promotion')"><?=system_showText(LANG_SITEMGR_PROMOTION);?></a>
	                        </li>
                        <?}?>
                        
                        <?if(($choose_banner_to_approve=="classified" && $section == "event") || $choose_banner_to_approve==""){?>
	                        <li id="li_event" <?=($section == "event" ? "class=\"step_active\"" : "")?>>
	                             <a href="javascript:void(0)" onclick="showDiv('event')"><?=system_showText(LANG_SITEMGR_EVENT);?></a>
	                        </li>
                        <?}?>
                        
                        <?if(($choose_banner_to_approve=="classified" && $section == "classified") || $choose_banner_to_approve==""){?>
	                        <li id="li_classified" <?=($section == "classified" ? "class=\"step_active\"" : "")?>>
	                             <a href="javascript:void(0)" onclick="showDiv('classified')"><?=system_showText(LANG_SITEMGR_CLASSIFIED);?></a>
	                        </li>
                        <?}?>
                    </ul>
                    
                    <div class="module_tab" id="tab_listing" <?=($section == "listing" ? "style=\"\"" : "style=\"display:none\"")?>>
                    	<? unset($bannerList); if($banner_to_approve!=""){?>
                    		<? include(INCLUDES_DIR."/tables/table_banner_approve.php"); ?>
                    	<?}else{?>
                    		<? $bannerList[1]=0;if($banners['listing'])$bannerList = $banners['listing']; include(INCLUDES_DIR."/tables/table_category_location.php"); ?>
                    	<?}?>
                    </div>
                    
                    <div class="module_tab" id="tab_promotion" <?=($section == "promotion" ? "style=\"\"" : "style=\"display:none\"")?>>
                    	<? unset($bannerList);if($banner_to_approve!=""){?>
                    		<? include(INCLUDES_DIR."/tables/table_banner_approve.php"); ?>
                    	<?}else{?>
                    		<? $bannerList[1]=0;if($banners['promotion'])$bannerList = $banners['promotion']; include(INCLUDES_DIR."/tables/table_category_location.php"); ?>
                    	<?}?>
                    </div>
                    
                    <div class="module_tab" id="tab_event" <?=($section == "event" ? "style=\"\"" : "style=\"display:none\"")?>>
                    	<? unset($bannerList);if($banner_to_approve!=""){?>
                    		<? include(INCLUDES_DIR."/tables/table_banner_approve.php"); ?>
                    	<?}else{?>
                    		<? $bannerList[1]=0;if($banners['event'])$bannerList = $banners['event']; include(INCLUDES_DIR."/tables/table_category_location.php"); ?>
                    	<?}?>
                   	</div>
                    
                    <div class="module_tab" id="tab_classified" <?=($section == "classified" ? "style=\"\"" : "style=\"display:none\"")?>>
                    	<? unset($bannerList);if($banner_to_approve!=""){?>
                    		<? include(INCLUDES_DIR."/tables/table_banner_approve.php"); ?>
                    	<?}else{?>
                    		<? $bannerList[1]=0;if($banners['classified'])$bannerList = $banners['classified']; include(INCLUDES_DIR."/tables/table_category_location.php"); ?>
                    	<?}?>
                    </div>
                    
           		</div>
           	</div>
    </div>
    <div id="bottom-content">
        &nbsp;
    </div>
</div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>