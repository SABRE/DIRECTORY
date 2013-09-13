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
	# * FILE: /members/banner/add.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (BANNER_FEATURE != "on" || CUSTOM_BANNER_FEATURE != "on") { exit; }

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSession();
	$acctId = sess_getAccountIdFromSession();

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_POST);
	extract($_GET);

	$url_base = "".DEFAULT_URL."/".MEMBERS_ALIAS."";
	$url_redirect = $url_base."/".BANNER_FEATURE_FOLDER;
	$members = 1;

	include(EDIRECTORY_ROOT."/includes/code/banner.php");

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

		<h2><?=system_showText(LANG_LABEL_ADDBANNER)?></h2>

		<form name="banner" id="banner" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="operation" value="add" />
			<input type="hidden" name="account_id" value="<?=$acctId?>" />
			<input type="hidden" name="type" value="<?=$type?>" />
			<input type="hidden" name="letter" value="<?=$letter?>" />
			<input type="hidden" name="screen" value="<?=$screen?>" />

			<? include(INCLUDES_DIR."/forms/form_banner.php"); ?>
			
			<div class="baseButtons floatButtons">

				<p class="standardButton">
					<button type="submit" name="submit" value="Submit"><?=system_showText(LANG_BUTTON_SUBMIT)?></button>
				</p>
				
			</div>

		</form>
		<form action="<?=DEFAULT_URL?>/<?=MEMBERS_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/index.php" method="post" style="margin: 0; padding: 0;">

			<input type="hidden" name="letter" value="<?=$letter?>" />
			<input type="hidden" name="screen" value="<?=$screen?>" />
			
			<div class="baseButtons floatButtons noPadding">

				<p class="standardButton">
					<button type="submit" value="Cancel"><?=system_showText(LANG_BUTTON_CANCEL)?></button>
				</p>
				
			</div>

		</form>

	</div>
	
	<script type="text/javascript">

	var skipArrListing = new Array();
	var skipArrClassified = new Array();
	var skipArrEvent = new Array();
	var skipArrPromotion = new Array();
	var in_banner = true;
<? if($skipItem){?> 
	<? foreach($skipItem as $key=>$value){?> 
		<? foreach($value as $val){?> 
			<? 	if($key=="listing"){?> 
		    	skipArrListing[skipArrListing.length] = <?=$val[0]?>;
		    	skipArrListing[skipArrListing.length] = <?=$val[1]?>;
			<? } ?>   
			<? 	if($key=="classified"){?> 
				skipArrClassified[skipArrClassified.length] = <?=$val[0]?>;
				skipArrClassified[skipArrClassified.length] = <?=$val[1]?>;
			<? } ?> 
			<? 	if($key=="event"){?> 
				skipArrEvent[skipArrEvent.length] = <?=$val[0]?>;
				skipArrEvent[skipArrEvent.length] = <?=$val[1]?>;
			<? } ?>  
			<? 	if($key=="promotion"){?> 
				skipArrPromotion[skipArrPromotion.length] = <?=$val[0]?>;
				skipArrPromotion[skipArrPromotion.length] = <?=$val[1]?>;
			<? } ?>  
		<? } ?>  
	<? } ?>
	<?}?>
	function checkSkip(object)
	{
		var item_app
		var compareArray = new Array();
		var k;
		if($("#type").val()==4)
		{
			$("[id^=option_L3_ID]").removeAttr('disabled');
			if($("input[id=section]:checked").val() == "listing")
				compareArray = skipArrListing;
			else if($("input[id=section]:checked").val() == "promotion")
				compareArray = skipArrPromotion;
			else if($("input[id=section]:checked").val() == "event")
				compareArray = skipArrEvent;
			else
				compareArray = skipArrClassified;
			if(compareArray.length>0)
			{
				var count_not = 0;
				for(k=0; k<compareArray.length; k=k+2)
				{
					if(object.value == compareArray[k])
					{
						$("#option_L3_ID"+compareArray[k+1]).attr('disabled', 'disabled');
						if(compareArray[k+1]==$("#location_3").val())
							$("#location_3").val('');
					}
					else
						count_not++;
					
				}
			}
		}
	}
	$(document).ready(function() {

		$("input[id=section]:checked").click();
		<?if ($type == 4)     {  
		        echo "setTimeout(function(){ fillBannerCategorySelect('".DEFAULT_URL."', document.banner.category_id, '".(isset($section)?$section:"listing")."', document.banner,".SELECTED_DOMAIN_ID.", 'banner');},500);";
		        }?>
    });
   
</script>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/footer.php");
?>