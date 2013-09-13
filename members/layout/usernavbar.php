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
	# * FILE: /members/layout/usernavbar.php
	# ----------------------------------------------------------------------------------------------------

?>

	<div id="user-navbar">
			
        <div class="wrapper">
        
            <? if (sess_getAccountIdFromSession()) {
                $accObj = new Account(sess_getAccountIdFromSession());
                if ((string_strpos($_SERVER["PHP_SELF"], "".MEMBERS_ALIAS."/signup") === false) && (string_strpos($_SERVER["PHP_SELF"], "".MEMBERS_ALIAS."/claim") === false)) {
                    ?>

                    <ul class="user-options">

                        <? if (!empty($_SESSION[SM_LOGGEDIN])) { ?>
                            <script language="javascript" type="text/javascript">
                                function sitemgrSection() {
                                    location = "<?=DEFAULT_URL?>/<?=MEMBERS_ALIAS?>/sitemgraccess.php?logout";
                                }
                            </script>
                            <li><a href="javascript:sitemgrSection();"><?=system_showText(LANG_LABEL_SITEMGR_SECTION);?></a></li>
                        <? } else { ?>
                            <li><a href="<?=((SSL_ENABLED == "on" && FORCE_MEMBERS_SSL == "on") ? SECURE_URL : NON_SECURE_URL)?>/<?=MEMBERS_ALIAS?>/logout.php"><?=system_showText(LANG_BUTTON_LOGOUT)?></a></li>
                        <? } ?>

                        <li><a href="<?=((SSL_ENABLED == "on" && FORCE_MEMBERS_SSL == "on") ? SECURE_URL : NON_SECURE_URL)?>/<?=MEMBERS_ALIAS?>/faq.php"><?=system_showText(LANG_MENU_FAQ);?></a></li>

                        <li><a href="<?=NON_SECURE_URL?>/"><?=system_showText(LANG_LABEL_BACK_TO_SEARCH);?></a></li>
                        <? if (SOCIALNETWORK_FEATURE == "on" && $contactWelcome["has_profile"] == "y") { ?>
                            <li><a href="<?=SOCIALNETWORK_URL?>/"><?=system_showText(LANG_LABEL_MYPROFILE)?></a></li>
                                <? if (empty($_SESSION[SM_LOGGEDIN])) { ?>
                                    <li class="welcome"><?=system_showText(LANG_LABEL_WELCOME)?> <strong><?=system_showTruncatedText($contactWelcome["nickname"], 20)?></strong>!</li>		
                                <? } ?>
                        <? } elseif (empty($_SESSION[SM_LOGGEDIN])) { ?>
                            <li class="welcome"><?=system_showText(LANG_LABEL_WELCOME)?> <strong><?=system_showTruncatedText($contactWelcome["first_name"]." ".$contactWelcome["last_name"], 20)?></strong>!</li>
                        <? } ?>

                    </ul>

                    <?
                }
            } ?>

            <script type="text/javascript">
                $("#all-languages-button").hover(function() {
                    $('.all-languages').slideDown('slow');
                        }, function() {
                    $('.all-languages').slideUp('slow');
                });
            </script>

            <? system_increaseVisit(db_formatString(getenv("REMOTE_ADDR"))); ?>

        </div>

    </div>