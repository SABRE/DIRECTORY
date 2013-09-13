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
    # * FILE: /sitemgr/content/htmlagree.php
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
    
    # ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    
    extract($_POST);
	if ($_SERVER['REQUEST_METHOD'] == "POST" && !DEMO_LIVE_MODE) {
        if (!setting_set("htmleditor_first_change", $htmleditor_first_change))
            if (!setting_new("htmleditor_first_change", $htmleditor_first_change))
                $error = true;
        if (!$htmleditor_first_change){
            $redirectHome = true;
    
        }
    }
?>
   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
        
        <meta name="author" content="Arca Solutions" />

		<meta http-equiv="Content-Type" content="text/html; charset=<?=EDIR_CHARSET;?>" />

		<meta name="ROBOTS" content="noindex, nofollow" />
        
		<link href="<?=DEFAULT_URL;?>/<?=SITEMGR_ALIAS?>/layout/general_sitemgr.css" rel="stylesheet" type="text/css"/>
        
        <? if ($htmleditor_first_change && !$error) { ?>
        
            <script language="javascript" type="text/javascript">
				parent.location.href = '<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor.php"?>';
			</script>
        
        <? } elseif ($redirectHome) { ?>
        
            <script language="javascript" type="text/javascript">
                parent.location.href = '<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/"?>';
            </script>
        
        <? } ?>
            
	</head>
	<body>
		<div class="wrapper htmlagree">
            <? if ($_SERVER['REQUEST_METHOD'] != "POST") { ?>
            <form name="htmleditor_firstchange" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">
                <p><?=system_showText(LANG_SITEMGR_EDITOR_TIP1);?></p>
                
                <p><?=system_showText(LANG_SITEMGR_EDITOR_TIP2);?></p>
                
                <p class="agreeterms">
					<strong><?=system_showText(LANG_SITEMGR_EDITOR_TIP3);?></strong>                
                	<span><input type="checkbox" name="htmleditor_first_change" id="htmleditor_first_change" value="no" class="inputCheck" /> <?=system_showText(LANG_SITEMGR_EDITOR_TIP4);?></span>
                </p>
               
                <p class="go-action">
                	<button type="submit" name="htmleditor_firstchange" value="Submit" class="input-button-form"><?=system_showText(LANG_BUTTON_CONTINUE)?></button>
                </p>    
            </form>
            <? } ?>
		</div>
	</body>
</html>