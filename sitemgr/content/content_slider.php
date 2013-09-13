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
	# * FILE: /sitemgr/content/content_slider.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	permission_hasSMPerm();

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_POST);
	extract($_GET);	
	
	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	include(INCLUDES_DIR."/code/slider.php");
	
	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

<script type="text/javascript">
<!--
function JS_submit() {
	$("#submit_button").attr("value", 1);
	document.slider.submit();
}

function JS_submitDisable() {
	document.slider_disable.submit();
}
-->
</script>

	<div id="main-right">

		<div id="top-content">
			<div id="header-content">
				<h1><?=ucfirst(system_showText(LANG_SITEMGR_CONTENT_MANAGECONTENT))?> - <?=ucfirst(system_showText(LANG_SITEMGR_NAVBAR_SLIDER))?></h1>
			</div>
		</div>

		<div id="content-content">

			<div class="default-margin">

				<? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
				<? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
				<? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

				<? include(INCLUDES_DIR."/tables/table_content_submenu.php"); ?>
				
				<table border="0" cellspacing="0" cellpadding="0" class="standard-table">
					<tr>
						<th class="standard-tabletitle"><?=string_ucwords(system_showText(LANG_SITEMGR_SLIDER_MSG_CONFIGURE))?></th>
					</tr>
				</table>
				
				<? if ($_GET["s"]) { ?>
					<p class="successMessage">
						<?=system_showText(LANG_SITEMGR_SETTINGS_YOURSETTINGSWERECHANGED);?>
					</p>
				<? } ?>
				
				<p class="informationMessage">
					<?=system_showText(LANG_SITEMGR_SLIDER_MSG_DISABLE);?>
				</p>
				
				<form name="slider_disable" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">
					<input type="hidden" name="turn" value="turn" />
					<input type="hidden" name="domain_id" value="<?=SELECTED_DOMAIN_ID?>" />
					<? if ($slider_feature == "on") { ?>
						<button type="button" class="input-button-form" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "JS_submitDisable();"?>"><?=system_showText(LANG_SITEMGR_MAINTENANCETURNOFF)?></button>
					<? } else { ?>
						<button type="button" class="input-button-form" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "JS_submitDisable();"?>"><?=system_showText(LANG_SITEMGR_MAINTENANCETURNON)?></button>
					<? } ?>
				</form>
				
				<? if ($slider_feature == "on") { ?>
				
					<table border="0" cellspacing="0" cellpadding="0" class="standard-table">
						<tr>
							<th class="standard-tabletitle"><?=string_ucwords(system_showText(LANG_SITEMGR_SLIDER_MSG_CONTENT))?></th>
						</tr>
					</table>

					<? 
					if ($message) {

						?>
						<p class="<? if ($error) echo "errorMessage"; else echo "successMessage"; ?>"><?=$message?></p>
						<?
					} 
					?>
					<p class="contentSpace">
						<?=system_showText(LANG_SITEMGR_SLIDER_EXPLAIN_LINE_1);?> 
                        <? if (SLIDER_USE_NEWEST) { ?>
                        <br /><br />
						<?=system_showText(LANG_SITEMGR_SLIDER_EXPLAIN_LINE_3);?> <br />
						<?=system_showText(LANG_SITEMGR_SLIDER_EXPLAIN_LINE_4);?> <br />
                        <? } ?>
					</p>
					<form name="slider" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">
						<input type="hidden" name="number_of_items" value="<?=TOTAL_SLIDER_ITEMS?>" />
						<input type="hidden" name="settings" value="settings" />
						<input type="hidden" name="submit_button" id="submit_button" />
						
						<? include(INCLUDES_DIR."/forms/form_slider.php"); ?>
						
						<div class="divisor"></div>
						<span class="clear"></span>
						
						<button type="button" name="slider" value="Submit" class="input-button-form" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "JS_submit();"?>"><?=system_showText(LANG_SITEMGR_SUBMIT)?></button>
                        
                        
					</form>
				<? } ?>

			</div>

		</div>

	</div>

	<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
	?>

