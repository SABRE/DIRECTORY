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
	# * FILE: /includes/tables/table_lang_submenu.php
	# ----------------------------------------------------------------------------------------------------

    $openPMhome = string_strpos($_SERVER["PHP_SELF"], "index");
    $openPMadd = string_strpos($_SERVER["PHP_SELF"], "add");
    $openPMedit = string_strpos($_SERVER["PHP_SELF"], "edit");
    $openPMflag = string_strpos($_SERVER["PHP_SELF"], "flags");
    
?>

    <div class="submenu">
        <ul>
            <li id="privateMenu_home"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/langcenter/index.php"><?=system_showText(LANG_SITEMGR_LANGUAGE)?></a></li>
            <li id="privateMenu_edit"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/langcenter/edit.php"><?=system_showText(LANG_SITEMGR_LANGUAGES_EDIT)?></a></li>
            <li id="privateMenu_add"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/langcenter/add.php"><?=system_showText(LANG_SITEMGR_LANGUAGES_ADD)?></a></li>
            <li id="privateMenu_flag"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/langcenter/flags.php"><?=system_showText(LANG_SITEMGR_LANGUAGES_CHANGE)?></a></li>
        </ul>
    </div>
    <br clear="all">	

    <? if ($openPMhome) { ?> <script type="text/javascript"> addClass('home') </script><? } ?>
    <? if ($openPMadd) { ?> <script type="text/javascript"> addClass('add') </script><? } ?>
    <? if ($openPMedit) { ?> <script type="text/javascript"> addClass('edit') </script><? } ?>
    <? if ($openPMflag) { ?> <script type="text/javascript"> addClass('flag') </script><? } ?>