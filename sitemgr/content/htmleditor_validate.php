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
	# * FILE: /sitemgr/content/htmleditor_validate.php
	# ----------------------------------------------------------------------------------------------------

    if ($_POST["domain_id"]) {
        define("SELECTED_DOMAIN_ID", $_POST["domain_id"]);
    }

    # ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");
    
    header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);
	header("Accept-Encoding: gzip, deflate");
	header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check", FALSE);
	header("Pragma: no-cache");

    # ----------------------------------------------------------------------------------------------------
    # CODE
    # ----------------------------------------------------------------------------------------------------
    extract($_POST);

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
    
    if ($_SERVER['REQUEST_METHOD'] == "POST" && !DEMO_LIVE_MODE) {

        $errorFolder = false;
        $errorMessage = "";
        $text = $text_temp;

        if (!is_dir(HTMLEDITOR_LANG_FOLDER)) {
            //create folder custom/domain_x/lang/editor
            if (!mkdir(HTMLEDITOR_LANG_FOLDER)){
                $errorFolder = true;
            }
        }
        $filePath = HTMLEDITOR_LANG_FOLDER;

        if (!is_dir($filePath)) {
            if (!mkdir($filePath)){
                $errorFolder = true;
            }
        }
        
        $requiredInfo = array();
        $requiredInfo[] = "LANG_MENU_HOME";
        $requiredInfo[] = "LANG_MENU_LISTING";
        $requiredInfo[] = "LANG_MENU_EVENT";
        $requiredInfo[] = "LANG_MENU_BANNER";
        $requiredInfo[] = "LANG_MENU_CLASSIFIED";
        $requiredInfo[] = "LANG_MENU_ARTICLE";
        $requiredInfo[] = "LANG_MENU_PROMOTION";
        $requiredInfo[] = "LANG_MENU_BLOG";
        $requiredInfo[] = "LANG_MENU_ADVERTISE";
        $requiredInfo[] = "LANG_MENU_FAQ";
        $requiredInfo[] = "LANG_MENU_SITEMAP";
        $requiredInfo[] = "LANG_MENU_CONTACT";
        $requiredInfo[] = "LANG_ALT_LINKEDIN";
        $requiredInfo[] = "LANG_ALT_FACEBOOK";
        $requiredInfo[] = "LANG_LINKS";
        $requiredInfo[] = "LANG_FOOTER_CONTACT";
        $requiredInfo[] = "LANG_TWITTER";
        $requiredInfo[] = "LANG_FOLLOW_US_TWITTER";
        $requiredInfo[] = "LANG_FOLLOW_US";
                      
        if (get_magic_quotes_gpc()){
            $text = stripslashes($text);
        }
               
        foreach($requiredInfo as $word) {
            if (string_strpos($text, $word) === false){
                $errorRequired = true;
                $requiredWord[] = "\"".$word."\"";
            }
        }
        
        if ($errorFolder){
            $errorMessage = system_showText(LANG_SITEMGR_EDITOR_PERMERROR);
        } elseif ($errorRequired){
            $errorMessage = system_showText(LANG_SITEMGR_EDITOR_REQUIREDERROR)."<br /><br />";
            $errorMessage .= "define(\"LANG_MENU_LISTING\", \"<strong>Directory</strong>\");<br /><br />";
            $errorMessage .= system_showText(LANG_SITEMGR_EDITOR_REQUIREDERROR2)." ".implode(", ", $requiredWord).".";
        } elseif (!$text){
            $errorMessage = system_showText(LANG_SITEMGR_EDITOR_EMPTYERROR);
        }
       
        if (!$errorMessage){
            
            $filePrefix = "<? define(\"EDIR_CHARSET\", \"".EDIR_CHARSET."\");\n include_once(\"".EDIRECTORY_ROOT."/functions/string_funct.php\"); \n\n ini_set(\"display_errors\", \"1\");\n\n?>\n\n";
            file_put_contents($filePath."/temp_".$file, $filePrefix.$text);

            echo "ok||temp_".$file;
        } else {
            echo "error||".$errorMessage;
        }
	}
?>