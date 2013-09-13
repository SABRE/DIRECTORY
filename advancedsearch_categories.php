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
	# * FILE: /advancedsearch_categories.php
	# ----------------------------------------------------------------------------------------------------

	include("./conf/loadconfig.inc.php");

        header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);
	header("Accept-Encoding: gzip, deflate");
	header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check", FALSE);
	header("Pragma: no-cache");
	
	if($_GET["fnct"] && $_GET["fnct"] == "categories"){
		$show_type = "all";
		$main_id = 0;
		$template_id = 0;
		$selected_category_id = 0;
		$selected_category_id_sub = 0;
		if(isset($_GET['category_id']) && ($_GET['category_id']!=""||$_GET['category_id']!=0))
			$selected_category_id = $_GET['category_id'];
		if(isset($_GET['category_id_sub']) && ($_GET['category_id_sub']!=""||$_GET['category_id_sub']!=0))
			$selected_category_id_sub = $_GET['category_id_sub'];
		if(isset($_GET['show_type']))
			$show_type = $_GET['show_type'];
		if(isset($_GET['main_id']))
			$main_id = $_GET['main_id'];
		if(isset($_GET['template_id']) && !($_GET['template_id']==""||$_GET['template_id']==0) )
			$template_id = $_GET['template_id'];
                echo system_advancedSearch_getCategories($_GET["type"],(isset($_GET["filter"])?true:false),$selected_category_id,$show_type,$main_id,$template_id,$selected_category_id_sub);	
               
        }
?>