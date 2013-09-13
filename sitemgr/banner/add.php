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
	# * FILE: /sitemgr/banner/add.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (BANNER_FEATURE != "on") { exit; }

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

	//increases frequently actions
	if (!isset($operation)) system_setFreqActions('banner_add','BANNER_FEATURE');

	$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER;
	$url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
	$sitemgr = 1;

	$url_search_params = system_getURLSearchParams((($_POST)?($_POST):($_GET)));

	include(EDIRECTORY_ROOT."/includes/code/banner.php");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");
?>
<div id="main-right">
    <div id="top-content">
        <div id="header-content">
            <h1 class="highlight"><?=string_ucwords(system_showText(LANG_SITEMGR_ADD))?> <?=system_showText(LANG_SITEMGR_BANNER_SING)?></h1>
        </div>
    </div>
    <div id="content-content">
        <div class="default-margin">

        <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
        <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
        <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
        <?if (CUSTOM_BANNER_FEATURE != "on"){ ?>
                <p class="informationMessage">
                    <?=system_showText(LANG_SITEMGR_MODULE_UNAVAILABLE)?>
                </p>
            <? }else { ?>
        <? include(INCLUDES_DIR."/tables/table_banner_submenu.php"); ?>

        <div class="baseForm">
        <form name="banner" id="banner" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="sitemgr" id="sitemgr" value="<?=$sitemgr?>" />
            <input type="hidden" name="operation" value="add" />
            <?=system_getFormInputSearchParams((($_POST)?($_POST):($_GET)));?>
            <input type="hidden" name="letter" value="<?=$letter?>" />
            <input type="hidden" name="screen" value="<?=$screen?>" />
            <? include(INCLUDES_DIR."/forms/form_banner.php"); ?>
            <button type="submit" value="Submit" class="input-button-form"><?=system_showText(LANG_SITEMGR_SUBMIT)?></button>
            <button type="button" name="cancel" value="Cancel" class="input-button-form" onclick="document.getElementById('formbanneraddcancel').submit();"><?=system_showText(LANG_SITEMGR_CANCEL)?></button>
            </form>
            <form  id="formbanneraddcancel"action="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/<?=(($search_page) ? "search.php" : "index.php")?>" method="post" style="margin: 0;">
                <?=system_getFormInputSearchParams((($_POST)?($_POST):($_GET)));?>
                <input type="hidden" name="letter" value="<?=$letter?>" />
                <input type="hidden" name="screen" value="<?=$screen?>" />
            </form>
            </div>
        <? } ?>
        </div>
    </div>
    <div id="bottom-content">
        &nbsp;
    </div>
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
<? } ?>	
	function checkSkip(object)
	{
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

        //DATE PICKER
        <?
        if ( DEFAULT_DATE_FORMAT == "m/d/Y" ) $date_format = "mm/dd/yy";
        elseif ( DEFAULT_DATE_FORMAT == "d/m/Y" ) $date_format = "dd/mm/yy";
        if ($type == 4)     {  
        echo "setTimeout(function(){ fillBannerCategorySelect('".DEFAULT_URL."', document.banner.category_id, '".(isset($section)?$section:"listing")."', document.banner,".SELECTED_DOMAIN_ID.", 'banner');},500);";
        }
        ?>

        $('#renewal_date').datepicker({
            dateFormat: '<?=$date_format?>',
            changeMonth: true,
            changeYear: true,
            yearRange: '<?=date("Y")?>:<?=date("Y")+10?>'
        });
    });
</script>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>