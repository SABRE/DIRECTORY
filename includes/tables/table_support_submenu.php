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
	# * FILE: /includes/tables/table_support_submenu.php
	# ----------------------------------------------------------------------------------------------------

    $openPMhome = string_strpos($_SERVER["PHP_SELF"], "index");
    $openPMreset = string_strpos($_SERVER["PHP_SELF"], "reset");
    $openPMcrontab = string_strpos($_SERVER["PHP_SELF"], "crontab");
    $openPMdomain = string_strpos($_SERVER["PHP_SELF"], "domain");
    $openPMcronlog = string_strpos($_SERVER["PHP_SELF"], "cronlog");
    $openPMimport = string_strpos($_SERVER["PHP_SELF"], "import");
    $openPMalias = string_strpos($_SERVER["PHP_SELF"], "alias");

?>

    <div class="submenu">
        <ul>
            <li id="privateMenu_home"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/index.php">System Settings</a></li>
            <li id="privateMenu_reset"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/reset.php">Reset Settings</a></li>
            <li id="privateMenu_crontab"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/crontab.php">Crontab</a></li>
            <li id="privateMenu_domain"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/domain.php">Domains</a></li>
            <li id="privateMenu_cronlog"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/cronlog.php">Cron Log</a></li>
            <li id="privateMenu_import"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/import.php">Import</a></li>
            <li id="privateMenu_alias"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/alias.php">Alias Options</a></li>
        </ul>
    </div>

    <br clear="all" style="height:0; line-height:0">

    <? if ($openPMhome) { ?> <script type="text/javascript"> addClass('home') </script><? } ?>
    <? if ($openPMreset) { ?> <script type="text/javascript"> addClass('reset') </script><? } ?>
    <? if ($openPMcrontab) { ?> <script type="text/javascript"> addClass('crontab') </script><? } ?>
    <? if ($openPMdomain) { ?> <script type="text/javascript"> addClass('domain') </script><? } ?>
    <? if ($openPMcronlog) { ?> <script type="text/javascript"> addClass('cronlog') </script><? } ?>
    <? if ($openPMimport) { ?> <script type="text/javascript"> addClass('import') </script><? } ?>
    <? if ($openPMalias) { ?> <script type="text/javascript"> addClass('alias') </script><? } ?>