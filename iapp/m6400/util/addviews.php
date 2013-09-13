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
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/configuration.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# DEFINE
	# ----------------------------------------------------------------------------------------------------
	define(MAX_ITEM_PER_PAGE, 20);

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	header("Content-type: text/xml"); 
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_LISTINGHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";

	switch ($_GET["module"]) {
		case 0: //Listing
			unset($listing);
			$listing = new Listing($_GET["itemID"]);
			$listing->setNumberViews($_GET["itemID"]);
			break;
		case 1: //Event
			unset($event);
			$event = new Event($_GET["itemID"]);
			$event->setNumberViews($_GET["itemID"]);
			break;
		case 2: //Promotion
			unset($promotion);
			$promotion = new Promotion($_GET["itemID"]);
			$promotion->setNumberViews($_GET["itemID"]);
			break;
		case 3: //Classified
			unset($classified);
			$classified = new Classified($_GET["itemID"]);
			$classified->setNumberViews($_GET["itemID"]);
			break;
		case 4: //Article
			unset($article);
			$article = new Article($_GET["itemID"]);
			$article->setNumberViews($_GET["itemID"]);
			break;
		default:
		break;
	}
	

	
 
?> 
