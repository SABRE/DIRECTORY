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
	# * FILE: /sitemgr/blog/search.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (BLOG_FEATURE != "on") {
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
		exit;
	}
    
	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	permission_hasSMPerm();

	$url_redirect = DEFAULT_URL."/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER;
	$url_base = DEFAULT_URL."/".SITEMGR_ALIAS;
	$sitemgr = 1;

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	$_GET = format_magicQuotes($_GET);
	extract($_GET);
	$_POST = format_magicQuotes($_POST);
	extract($_POST);
    $dbMain = db_getDBObject(DEFAULT_DB, true);
	$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);

	//increases frequently actions
	if (!isset($acct_search_field_name)) system_setFreqActions('post_search','BLOG_FEATURE');

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------

	include(INCLUDES_DIR."/code/bulkupdate.php");

	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
	$fields = "`id`, `title`";
	$orderby = "`title`";

	##################################################################################################################################
	# CATEGORY
	##################################################################################################################################
	$orderby = "`title`";

	$fields = array("id", "title");
	$str_values = "";

	$nameArray  = array();
	$valueArray = array();

	$resultArray = db_loadCategoriesDropdown("BlogCategory", $fields, 0, 1, BLOGCATEGORY_SCALABILITY_OPTIMIZATION, SELECTED_DOMAIN_ID, $str_values, $orderby);

	$valueArray = $resultArray["values"];
	$nameArray = $resultArray["names"];

	if (BLOGCATEGORY_SCALABILITY_OPTIMIZATION != "on") {
		$valueArray[] = "";
		$nameArray[] = "--------------------------------------------------";
	}
	$categoryDropDown = html_selectBox("search_category_id", $nameArray, $valueArray, $search_category_id, "", "class='input-dd-form-post'", system_showText(LANG_LABEL_SELECT_ALLCATEGORIES));

	##################################################################################################################################
	# STATUS
	##################################################################################################################################

	$arrayNameDD = Array("Active", "Suspended", "Pending");
	$arrayValueDD = Array("A", "S", "P");
	$statusDropDown = html_selectBox("search_status", $arrayNameDD, $arrayValueDD, $search_status, "", "class='input-dd-form-searchpost'", "-- ".system_showText(LANG_LABEL_SELECT_ALLSTATUS)." --");

	/************************************************
	* @desc Category auxiliar code
	*************************************************/
	
	if($search_category_id) {
		$catObj = new BlogCategory();
		$parents_category_ids = $catObj->getHierarchy($search_category_id, $get_parents=true, $get_children=false);
		$parents_category_ids .= ",".$catObj->getHierarchy($search_category_id, $get_parents=false, $get_children=true);
		
		$sql = "SELECT 
				DISTINCT Post.id 
				FROM 
				Post 
				INNER JOIN Blog_Category ON (Post.id = Blog_Category.post_id) 
				WHERE
				Blog_Category.category_id IN (".$parents_category_ids.")
				";
		$rs = $db->query($sql);
		while ($row = mysql_fetch_assoc($rs)) $post_ids_from_category[] = $row["id"];
		$category_post_ids = ($post_ids_from_category) ? implode(",",$post_ids_from_category) : "'0'";
	}
	
	/************************************************
	* @desc Category auxiliar code
	************************************************/
	if ($category_post_ids) {
		$search_post_ids = $category_post_ids;
	}

	if ($search_title) {
        $search_title = str_replace("\\", "", $search_title);
        $search_for_keyword_fields[] = "Post.fulltextsearch_keyword";
        $sql_where[] = search_getSQLFullTextSearch($search_title, $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
    }
	if ($search_status) $sql_where[] = " status = '$search_status' ";
	if ($search_post_ids) $sql_where[] = " id IN ($search_post_ids) "; // search_post_ids

	if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";
	
	# ----------------------------------------------------------------------------------------------------
	# PAGE BROWSING
	# ----------------------------------------------------------------------------------------------------
	
	$_GET["search_page"] = "1";
	$url_search_params = system_getURLSearchParams((($_POST)?($_POST):($_GET)));

	$paging_url = DEFAULT_URL."/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER."/search.php";
    
    $manageOrder = system_getManageOrderBy($_POST["order_by"] ? $_POST["order_by"] : $_GET["order_by"], "Blog", BLOG_SCALABILITY_OPTIMIZATION, $manageFields, true);
    
    if (!$error_message && !$error_msg) {
		if ($_POST["screen"]) {
			if ($bulkSubmit) {
				unset($arrayURL);
				if ($status) $arrayURL[] = "search_status=$status";
				if ($add_category_id) $arrayURL[] = "search_category_id=$add_category_id";
				$arrayURL[] = "screen=1";
				$arrayURL[] = "letter=".$letter;
				$arrayURL[] = "search_submit=Search";
				$arrayURL[] = "msg=".$msg;
				$strURL = implode("&", $arrayURL);
				header("Location: ".$paging_url."?$strURL");
				exit;
			} else {
				$screen = $_POST["screen"];
			}
		} else if ($bulkSubmit) {
			unset($arrayURL);
			if ($status) $arrayURL[] = "search_status=$status";
			if ($add_category_id) $arrayURL[] = "search_category_id=$add_category_id";
			$arrayURL[] = "screen=1";
			$arrayURL[] = "letter=".$letter;
			$arrayURL[] = "search_submit=Search";
			$arrayURL[] = "msg=".$msg;
			$strURL = implode("&", $arrayURL);

			header("Location: ".$paging_url."?$strURL");
			exit;
		}
	}

    $pageObj = new pageBrowsing("Post", $screen, RESULTS_PER_PAGE, $manageOrder, "title", $letter, $where, $manageFields);
    $posts = $pageObj->retrievePage("object");
    
    
    // Letters Menu
    $letters = $pageObj->getString("letters");
    foreach ($letters as $each_letter) {
        if ($each_letter == "#") {
            $letters_menu .= "<a href=\"$paging_url?letter=no".(($url_search_params) ? "&$url_search_params" : "")."\" ".(($letter == "no") ? "class=\"firstLetter\"" : "" ).">".string_strtoupper($each_letter)."</a>";
        } else {
            $letters_menu .= "<a href=\"$paging_url?letter=".$each_letter.(($url_search_params) ? "&$url_search_params" : "")."\" ".(($each_letter == $letter) ? "style=\"color:#EF413D\"" : "" ).">".string_strtoupper($each_letter)."</a>";
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

	$_GET = format_magicQuotes($_GET);
	extract($_GET);
	$_POST = format_magicQuotes($_POST);
	extract($_POST);

?>

<div id="main-right">

	<div id="top-content">
		<div id="header-content">
			<h1><?=string_ucwords(system_showText(LANG_SITEMGR_MENU_SEARCH))?> <?=system_showText(LANG_SITEMGR_POST_BLOG_SING)?></h1>
		</div>
	</div>

	<div id="content-content">

		<div class="default-margin">

			<? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
			<? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
			<? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

			<? if (CUSTOM_BLOG_FEATURE != "on") { ?>
				<p class="informationMessage">
					<?=system_showText(LANG_SITEMGR_MODULE_UNAVAILABLE)?>
				</p>
			<? } else { ?>

                <? include(INCLUDES_DIR."/tables/table_blog_submenu.php"); ?>

                <br />

				<? if ($search_submit && !$back) { ?>

					<a class="backToSearch" href="<?=$url_redirect."/search.php?".$_SERVER["QUERY_STRING"]?>&back=search"><?=system_showText(LANG_SITEMGR_MENU_BACKTOSEARCH);?></a>
					<div class="header-form" id="search_blog" >
						<?=string_ucwords(system_showText(LANG_SITEMGR_RESULTS))?>
					</div>

					<? if ($posts) { ?>
						<? include(INCLUDES_DIR."/tables/table_blog.php"); ?>
					<? } else { ?>
						<? include(INCLUDES_DIR."/tables/table_paging.php"); ?>
						<p class="errorMessage"><?=system_showText(LANG_SITEMGR_NORESULTS)?></p>
					<? } ?>

				<? } elseif ($back == "search" || string_strpos($_SERVER["PHP_SELF"], BLOG_FEATURE_FOLDER)) { ?>

					<div class="header-form" id="search_blog" >
						<?=string_ucwords(system_showText(LANG_SITEMGR_MENU_SEARCH))?>
					</div>
					<form name="blog" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="get" id="search_blog_form">

						<? include(INCLUDES_DIR."/forms/form_searchblog.php"); ?>

						<table style="margin: 0 auto 0 auto;">
							<tr>
								<td>
									<button type="submit" name="search_submit" value="Search" class="input-button-form"><?=system_showText(LANG_SITEMGR_SEARCH)?></button>
								</td>
								<td>
									<button type="button" value="Clear" onclick="searchResetSitemgr(this.form);" class="input-button-form"><?=system_showText(LANG_SITEMGR_CLEAR)?></button>
								</td>
							</tr>
						</table>

					</form>
				<? }

			} ?>

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