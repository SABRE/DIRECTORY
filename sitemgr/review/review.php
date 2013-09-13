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
    # * FILE: /sitemgr/review/review.php
    # ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
    # LOAD CONFIG
    # ----------------------------------------------------------------------------------------------------
    include("../../conf/loadconfig.inc.php");

    # ----------------------------------------------------------------------------------------------------
    # UPDATE REVIEW
    # ----------------------------------------------------------------------------------------------------
    
    if(string_strlen(trim($_POST["rating_".$_POST['idReview']])) > 0) {
		/*
		 * Get object name from item_type (listing, article, etc)
		 */
		$objName = string_strtolower($_POST["item_type"]);
		$objName = ucfirst($objName);
		/*
		 * Make a instance of Review object to save the changes and get AVG by item
		 */
		$reviewObj = new Review($_POST['idReview']);
		$reviewObj->setNumber("rating", $_POST["rating_".$_POST['idReview']]);
		$reviewObj->Save();
		$avg = $reviewObj->getRateAvgByItem($_POST["item_type"], $reviewObj->getNumber("item_id"));
        if (!is_numeric($avg)) $avg = 0;
		/*
		 * Updating the item AVG_REVIEW
		 */
		$itemObj = new $objName();
		$itemObj->setAvgReview($avg, $reviewObj->getNumber("item_id"));
    }
    
    if ($_POST["idReview"]) {
		$reviewObj = new Review($_POST['idReview']);
		$reviewObj->setString("reviewer_name", $_POST['reviewer_name']);
		$reviewObj->setString("reviewer_email", $_POST['reviewer_email']);
		$reviewObj->setString("reviewer_location", $_POST['reviewer_location']);
		$reviewObj->setString("review_title", $_POST['review_title']);
		$reviewObj->setString("review", $_POST['review']);
		$reviewObj->Save();

		$message = 5;
    } else $message = 6;
    
    $response = "?class=successMessage&message=$message";
    
    if (string_strpos($_SERVER["HTTP_REFERER"], "view.php")) {
        $response .= '&item_id='.$_POST['item_id'].'&item_type='.$_POST['item_type'].'&id='.$_POST['idReview'].'&screen='.$_POST['screen'].'&letter='.$_POST['letter'].'';
        header('Location: ' . DEFAULT_URL . '/'.SITEMGR_ALIAS.'/review/view.php'.$response);
    } else {
        $response .= ($_POST['filter_id'] ? '&filter_id=1&item_id='.$_POST['item_id'] : '')."&item_type=".$_POST['item_type']."&screen=".$_POST['screen']."&letter=".$_POST['letter'];
        header('Location: ' . DEFAULT_URL . '/'.SITEMGR_ALIAS.'/review/index.php'.$response);
    }

?>