<?php

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
	# * FILE: /includes/code/category.php
	# ----------------------------------------------------------------------------------------------------

	####################################################################################################
	### PAY ATTENTION - SAME CODE FOR LISTING, EVENT, CLASSIFIED, ARTICLE AND BLOG
	####################################################################################################

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	if ($_POST["title"]) {
		$_POST["title"] = trim($_POST["title"]);
		$_POST["title"] = preg_replace('/\s\s+/', ' ', $_POST["title"]);
	}
	extract($_GET);
	extract($_POST);
	
	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		if ($_POST["seo_description"]) $_POST["seo_description"] = str_replace('"', '', $_POST["seo_description"]);
        if ($_POST["seo_keywords"]) $_POST["seo_keywords"] = str_replace('"', '', $_POST["seo_keywords"]);

		if (validate_form("category", $_POST, $message_category)) {
			$_POST["featured"] = ($_POST["featured"] == "on" ? "y" : "n");
			$_POST["enabled"] = ($_POST["clickToDisable"] == "on" ? "n" : "y");

			$category = new $_POST["table_category"]($id);
			$category->makeFromRow($_POST);
			if (string_strlen($keywords)=="") $category->setString("keywords", "");

			if ($category_id && $_POST["featured"] && count($category->getFullPath()) == 2) {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				$sql = "SELECT featured FROM ".$_POST["table_category"]." WHERE id = $category_id";
				$result = $dbObj->query($sql);
				$row = mysql_fetch_assoc($result);
				$father_featured = $row["featured"];
				if ($father_featured == 'n') {
					$featuredMessage = 8;
				}
			}

			$category->Save();
			//Updating items fulltext fields
			if (@constant(string_strtoupper($_POST["table_category"])."_SCALABILITY_OPTIMIZATION") != 'on' && @constant(string_strtoupper(str_replace("Category", "", $_POST["table_category"]))."_SCALABILITY_OPTIMIZATION") != 'on') {
			   $category->updateFullTextItems();
			}

			if ($category->getNumber("active_".string_strtolower(str_replace("Category", "", $_POST["table_category"]))) > 0 && $_POST["clickToDisable"]) {
				$messageItems = true;
			}

			if ($_POST["category_id"]) {
				if ($_POST["id"]) {
					$message = 2;
					if ($_POST["clickToDisable"]){
						$langMessage = 6;
                    }
				} else {
					$message = 3;
					if ($_POST["clickToDisable"]){
						$langMessage = 6;
                    }
				}
			} else { 
				if ($_POST["id"]) {
					$message = 4;
					if ($_POST["clickToDisable"]){
						$langMessage = 7;
                    }
				} else {
					$message = 5;
					if ($_POST["clickToDisable"]){
						$langMessage = 7;
                    }
				}
			}

			if ($messageItems) {
				if ($langMessage == 7) {
                    $langMessage = 9;
                }
			}

			header("Location: $url_redirect/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&langmessage=".$langMessage."&featmessage=".$featuredMessage."&category_id=".$category_id."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : ""));
			exit;

		}

		// removing slashes added if required
		$_POST = format_magicQuotes($_POST);
		$_GET = format_magicQuotes($_GET);
		extract($_POST);
		extract($_GET);

	} 

	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
	if ($id) {
		$category = db_getFromDB(string_strtolower($table_category), "id", $id, 1, "", "object", SELECTED_DOMAIN_ID);
		$category->extract();
		$featured = ($featured == "y" ? "on" : "");
		$enabled = ($enabled == "y" ? "on" : "");
	} else {
        $enabled = ($_POST["clickToDisable"] == "on" ? "" : "on");
		$featured = "new";
	}

	extract($_POST);
	extract($_GET);

    $fatherCategoryArray = db_getFromDB(string_strtolower($table_category), "id", $category_id, 1, "", "array", SELECTED_DOMAIN_ID, false, "`id`, `title`");

	$featuredcategory = "";
	if (FEATURED_CATEGORY == "on") {
		setting_get(string_strtolower(str_replace("Category", "", $table_category))."_featuredcategory", $featuredcategory);
		if ($featuredcategory) {
			
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);

			$cat_level = 0;
			$category_id_aux = $fatherCategoryArray["id"];
			while($category_id_aux != 0) {
				$sql = "SELECT category_id FROM $table_category WHERE id = $category_id_aux";
				$result = $dbObj->query($sql);
				$row = mysql_fetch_assoc($result);
				$category_id_aux = $row["category_id"];
				$cat_level++;
			}

			if ($cat_level >= FEATUREDCATEGORY_LEVEL_AMOUNT) {
				$featuredcategory = "";
            }
		}
	}
?>