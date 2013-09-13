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
    # * FILE: /sitemgr/content/htmlsource.php
    # ----------------------------------------------------------------------------------------------------

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
    # SESSION
    # ----------------------------------------------------------------------------------------------------
    sess_validateSMSession();
    permission_hasSMPerm();
    
    extract($_GET);
    
    $availableFiles = system_getEditorAvailable();
    
    if (!in_array($file, $availableFiles) || !$file){
        $msgError = system_showText(LANG_SITEMGR_EDITOR_INVALID);
    } else {
        if ($file == "header.php" || $file == "footer.php"){
            $text = file_get_contents(system_getFrontendPath($file, "layout", true));
            $text = str_replace("# ----------------------------------------------------------------------------------------------------", "", $text);
            $text = str_replace("# * FILE: /layout/$file", "", $text);
            $text = str_replace("# * FILE: /theme/realestate/layout/$file", "", $text);
            $text = str_replace("# ----------------------------------------------------------------------------------------------------", "", $text);
        } elseif (string_strpos($file, "_") !== false && string_strpos($file, "css") === false){
            $text = file_get_contents(language_getFilePath(str_replace(".php", "", $file), false, false, false, SELECTED_DOMAIN_ID, true, true));
            $text = str_replace("# ----------------------------------------------------------------------------------------------------", "", $text);
            $text = str_replace("# * FILE: /lang/$file", "", $text);
            $text = str_replace("# ----------------------------------------------------------------------------------------------------", "", $text);
        } else {
            $text = file_get_contents(THEMEFILE_DIR."/".EDIR_THEME."/$file");
        }
    }
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<link href="<?=DEFAULT_URL;?>/<?=SITEMGR_ALIAS?>/layout/general_sitemgr.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
		<div class="wrapper">
            <? if ($msgError) { ?>
                <p class="errorMessage"><?=$msgError?></p>
            <? } else { ?>
                <textarea name="text" id="textarea" readonly="readonly" style="width:765px; height:450px;"><?php echo htmlspecialchars($text);?></textarea>
            <? } ?>
		</div>
	</body>
</html>