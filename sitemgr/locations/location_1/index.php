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
	# * FILE: /sitemgr/locations/location_1/index.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	permission_hasSMPerm();

	$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_1";
	$url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
	$sitemgr = 1;

	# ----------------------------------------------------------------------------------------------------
	# LOCATION RELATIONSHIP
	# ----------------------------------------------------------------------------------------------------
	$_locations = explode(",", EDIR_LOCATIONS);
	$_location_level = 1;
	if (!in_array($_location_level, $_locations)) {
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
		exit;
	}
	$_location_node_params = system_buildLocationNodeParams($_GET);
	system_retrieveLocationRelationship ($_locations, $_location_level, $_location_father_level, $_location_child_level);

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_GET);
	extract($_POST);

	//increase frequent actions
	if (!isset($operation)&&!isset($letter)&&!isset($screen)) system_setFreqActions('location'.$_location_level.'_manage','location');

	// Page Browsing /////////////////////////////////////////
	$pageObj  = new pageBrowsing("Location_1", $screen, 50, "name, id", "name", $letter, ($_location_father_level!==false?"location_".$_location_father_level."=".${"location_".$_location_father_level}:false), "*", false, false, true);
	$locations = $pageObj->retrievePage();

	// N/A Location //////////////////////////////////////////
	$objLocationLabel = 'Location'.$_location_level;
	$location_na = new $objLocationLabel();
	$location_na->setString('name', LANG_NA);
	$aux_count = 0;
	foreach ($_locations as $i_child_level) {
		system_retrieveLocationRelationship ($_locations, $i_child_level, $i_location_father_level, $i_location_child_level);
		if (($i_location_child_level!==false) and ($_location_level < $i_location_child_level)) {
			$i_locations_child = db_getFromDB("location".($i_location_child_level), "location_".$_location_level, 0, "all");
			$aux_count += count($i_locations_child);
		}
	}
	if ($aux_count > 0)
		$locations[] = $location_na;
	//////////////////////////////////////////////////////////

	$paging_url = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_1/index.php";

	// Letters Menu
	$letters = $pageObj->getString("letters");
	foreach($letters as $each_letter){
		if ($each_letter == "#") {
			$letters_menu .= "<a href=\"$paging_url?letter=no\" ".(($letter == "no") ? "style=\"color:#EF413D\"" : "" ).">".string_strtoupper($each_letter)."</a>";
		} else {
			$letters_menu .= "<a href=\"$paging_url?letter=".$each_letter."\" ".(($each_letter == $letter) ? "style=\"color:#EF413D\"" : "" ).">".string_strtoupper($each_letter)."</a>";
		}
	}
	# PAGES DROP DOWN ----------------------------------------------------------------------------------------------
	$pagesDropDown = $pageObj->getPagesDropDown($_GET, $paging_url, $screen, system_showText(LANG_SITEMGR_PAGING_GOTOPAGE)." ", "this.form.submit();");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");
?>

    <script type="text/javascript" language="javascript">
        function updateFeatured(id, featured, level, row_id){
            var url_ajax = "<?=DEFAULT_URL?>"+"/<?=SITEMGR_ALIAS?>/locations/location_updateFeatured.php?id="+id+"&featured="+featured+"&level="+level+"&row_id="+row_id;
            loadOnDIV(url_ajax,'tableLocation_rowId_'+row_id);
        }
    </script>

    <div id="main-right">
        <div id="top-content">
            <div id="header-content">
                <h1>
                    <? system_buildLocationBreadCrumb ($_locations, $_GET, $_location_level);?>
                </h1>
            </div>
        </div>
        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
                <? include(INCLUDES_DIR."/tables/table_location_submenu.php");?>
                <br />

                <div class="tip-base">
                    <span class="warning" style="text-align: justify; font-size: 11px;"><a href="<?=DEFAULT_URL;?>/<?=SITEMGR_ALIAS?>/faq/faq.php?keyword=<?=urlencode("location");?>" target="_blank"><?=system_showText(LANG_SITEMGR_LOCATIONTIP)?></a></span>
                </div>
                <br />
                <? if (($locations) || (!$locations && isset($letter))) { ?>
                    <? include(INCLUDES_DIR."/tables/table_paging.php"); ?>
                <? } ?>

                <? include(INCLUDES_DIR."/tables/table_location.php"); ?>


                <br />
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