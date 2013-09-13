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
	# * FILE: /members/banner/edit.php
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

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	if ($id) {
		$banner = new Banner($id);
		if (sess_getAccountIdFromSession() != $banner->getNumber("account_id")) {
			header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".BANNER_FEATURE_FOLDER."/index.php?screen=$screen&letter=$letter");
			exit;
		}
	}
	else {
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".BANNER_FEATURE_FOLDER."/index.php?screen=$screen&letter=$letter");
		exit;
	}

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

		<? if ($process == "signup") { ?>
			<ul class="standardStep">
				<li class="standardStepAD"><?=system_showText(LANG_ENJOY_OUR_SERVICES)?></li>
				<li><span>1</span>&nbsp;<?=system_showText(LANG_LABEL_ORDER)?></li>
				<li><span>2</span>&nbsp;<?=system_showText(LANG_LABEL_CHECKOUT)?></li>
				<li class="stepActived"><span>3</span>&nbsp;<?=system_showText(LANG_LABEL_CONFIGURATION);?></li>
			</ul>
		<? } ?>

		<h2><?=system_showText(LANG_BANNER_EDIT);?></h2>

		<form name="banner" id="banner" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">

			<input type="hidden" name="process" id="process" value="<?=$process?>" />
			<input type="hidden" name="operation" value="update" />
			<input type="hidden" name="id" value="<?=$id?>" />
			<input type="hidden" name="account_id" value="<?=$acctId?>" />
			<input type="hidden" name="level" value="<?=$level?>" />
			<input type="hidden" name="letter" value="<?=$letter?>" />
			<input type="hidden" name="screen" value="<?=$screen?>" />

			<? include(INCLUDES_DIR."/forms/form_banner.php"); ?>
			
			<div class="baseButtons floatButtons">

				<p class="standardButton">
					<button type="submit" value="<?=system_showText(LANG_BUTTON_UPDATE)?>"><?=system_showText(LANG_BUTTON_UPDATE)?></button>
				</p>
				
			</div>

		</form>
		<form action="<?=DEFAULT_URL?>/<?=MEMBERS_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/index.php" method="post">

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
	<?if($banner->getString("approve_feature")=="O") {?>
			var skipCheck=<?=$category_id?>;
		<?}else{?>
			var skipCheck=false;
		<?}?>
	var type_item = <?=$type?>;
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
		
		var compareArray = new Array();
		var k;
		<? if($type==4){?>
		//if($("#type").val()==4)
		//{
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
					if(object.value == compareArray[k] && (skipCheck==false || skipCheck!=object.value))
					{
						$("#option_L3_ID"+compareArray[k+1]).attr('disabled', 'disabled');
						if(compareArray[k+1]==$("#location_3").val())
							$("#location_3").val('');
					}
					else
						count_not++;
					
				}
			}
		//}
		<?}?>
	}
	$(document).ready(function() {
<?
if ($type == 4)     {  
        echo "setTimeout(function(){ fillBannerCategorySelect('".DEFAULT_URL."', document.banner.category_id, '".(isset($section)?$section:"listing")."', document.banner,".SELECTED_DOMAIN_ID.", 'banner');},500);";
        if($location_3)  
        {
        	echo "setTimeout(function(){ $('#location_1').val(".$location_1.");$('#location_1').change();},1500);";   
        	echo "setTimeout(function(){ $('#location_3').val(".$location_3.");$('#location_3').change();},3300);";   
        }
        echo "setTimeout(function(){ $('#category_id').val(".$category_id.");$('#category_id').change();},3800);";   
        }?>
		$("input[id=section]:checked").click();
    });
   
</script>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/footer.php");
?>