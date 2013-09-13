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
	# * FILE: /includes/code/promotion_seocenter.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	if ($id) {
		$promotion = new Promotion($id);
		if (!($promotion->getNumber("id")) || ($promotion->getNumber("id") <= 0)) {
			header("Location: $url_redirect/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : "" )."");
			exit;
		}
	} else {
        header("Location: $url_redirect/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : "" )."");
        exit;
	}

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		$_POST["friendly_url"] = str_replace(".htm", "", $_POST["friendly_url"]);
        $_POST["friendly_url"] = str_replace(".html", "", $_POST["friendly_url"]);

        if ($_POST["seo_description"]) {
            $_POST["seo_description"] = str_replace(array("\r\n", "\n"), " ", $_POST["seo_description"]);
            $_POST["seo_description"] = str_replace("\"", "", $_POST["seo_description"]);
            $_POST["seo_keywords"] = str_replace("\"", "", $_POST["seo_keywords"]);
        }

		if (validate_form("promotionseocenter", $_POST, $message)) {
			foreach ($_POST as $key=>$value) {
				if (string_strpos($key, "seo") !== false || string_strpos($key, "friendly_url") !== false) {
					$promotion->setString($key, $value);
				}
			}

			$promotion->Save();
            $message = 3;
			header("Location: $url_redirect/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : ""));
			exit;
		}

		$_POST = format_magicQuotes($_POST);
		$_GET  = format_magicQuotes($_GET);
		extract($_POST);
		extract($_GET);

	}

	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
	$promotion->extract();
	extract($_POST);
	extract($_GET);

?>