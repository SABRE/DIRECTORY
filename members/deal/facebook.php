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
	# * FILE: /members/deal/facebook.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");
    
    # ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") { exit; }

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSession();
	$acctId = sess_getAccountIdFromSession();

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_GET);
	extract($_POST);

	$url_redirect = "".DEFAULT_URL."/".MEMBERS_ALIAS."/".PROMOTION_FEATURE_FOLDER;
	$url_base = "".DEFAULT_URL."/".MEMBERS_ALIAS."";
	$members = 1;

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	setting_get("commenting_fb", $commenting_fb);
	if (!$commenting_fb){
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".PROMOTION_FEATURE_FOLDER."/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : "")."");
		exit;
	}
	
	if ($id) {
		$promotion = new Promotion($id);
		if (sess_getAccountIdFromSession() != $promotion->getNumber("account_id")) {
			header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".PROMOTION_FEATURE_FOLDER."/index.php?screen=$screen&letter=$letter");
			exit;
		}
	} else {
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".PROMOTION_FEATURE_FOLDER."/index.php?screen=$screen&letter=$letter");
		exit;
	}

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <div class="content">

        <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
        <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
        <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

        <h2><?=system_showText(LANG_PROMOTION);?> - <?=string_ucwords(system_showText(LANG_LABEL_FACEBOOK_COMMENTS))?></h2>

        <form name="facebook_form" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">

            <?
            $detailLink = PROMOTION_DEFAULT_URL."/".ALIAS_SHARE_URL_DIVISOR."/".$promotion->getString("friendly_url").".html";

            setting_get("commenting_fb_number_comments", $commenting_fb_number_comments);

            ?>

            <script>
                (function(d){
                  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
                  js = d.createElement('script'); js.id = id; js.async = true;
                  js.src = "//connect.facebook.net/<?=EDIR_LANGUAGEFACEBOOK?>/all.js#xfbml=1";
                  d.getElementsByTagName('head')[0].appendChild(js);
                }(document));
            </script>

            <table cellpadding="0" cellspacing="0" border="0" class="standard-table">
                <tr>
                    <th class="standard-tabletitle"><?=string_ucwords(system_showText(LANG_LABEL_FACEBOOK_COMMENTS))?></th>
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <div class="fb-comments" data-href="<?=$detailLink?>" data-num-posts="<?=$commenting_fb_number_comments?>" data-width="500"></div>
                    </td>
                </tr>
            </table>

            <p class="standardButton">
                <button type="button" onclick="history.back();"><?=system_showText(LANG_LABEL_BACK)?></button>
            </p>

        </form>

    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/footer.php");
?>
