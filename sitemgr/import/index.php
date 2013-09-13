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
	# * FILE: /sitemgr/import/index.php
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

	extract($_GET);
	extract($_POST);

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");
    
    # ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    if (!$step) {
        $step = 1; //start new import
        //increases frequently actions
        system_setFreqActions("import_home", "import");
    }
    
    if (!$module) { //define listing as default module to start a new import
        $module = "listing";
    }
    
    if ($step >= 4) { //include code for import process
        
        if ($module == "event"){
            include(INCLUDES_DIR."/code/import_event.php");
            $includeFile = "import_event.php"; //ajax request
        } else {
            include(INCLUDES_DIR."/code/import.php");
            $includeFile = "import.php"; //ajax request
        }
        
    }
    
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $step == 4) {
        
        if (!$messageErrorUpload && $prev_step == 4) { //submit after Step 4, go to Step 5.
            $step = 5;
        }

    }
    
	# ----------------------------------------------------------------------------------------------------
	# FORM DEFINES
	# ----------------------------------------------------------------------------------------------------
	//Tabs controler
	unset($array_edir_import);
	unset($array_edir_importModule);
	unset($import_numbers);
	$num_import = 1;
	
    if ($step > 1 && !$_GET["step"]) { //Show only one tab (current module)
        
        $array_edir_import[] = @constant("LANG_".string_strtoupper($module)."_FEATURE_NAME_PLURAL");
        $array_edir_importModule[] = $module;
        $import_numbers[] = "0";
        $onclick = false;
        
    } else { //Show all available modules to import
        
        $onclick = true;
        $array_edir_import[] = LANG_LISTING_FEATURE_NAME_PLURAL;
        $array_edir_importModule[] = "listing";
        if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on"){
            $array_edir_import[] = LANG_EVENT_FEATURE_NAME_PLURAL;
            $array_edir_importModule[] = "event";
            $num_import++;
        }
        $import_numbers[] = "0";
        if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on"){
            $import_numbers[] = "1";
        }
        
    }
    
    if ($step == 3) { //get Default import settings

        if ($update_settings != "yes") {
            
            if ($_SERVER['REQUEST_METHOD'] == "POST" && $error_sameaccount) {
                
                if ($_POST["import_sameaccount_".$module]) ${"import_sameaccount_".$module} = "checked";
                if ($_POST["import_from_export_".$module]) ${"import_from_export_".$module} = "checked";
                if ($_POST["import_enable_active_".$module]) ${"import_enable_active_".$module} = "checked";
                if ($_POST["import_update_items_".$module]) ${"import_update_items_".$module} = "checked";
                if ($_POST["import_update_friendlyurl_".$module]) ${"import_update_friendlyurl_".$module} = "checked";
                if ($_POST["import_featured_categs_".$module]) ${"import_featured_categs_".$module} = "checked";
                
            } else {
                //Listing
                setting_get("import_sameaccount", $import_sameaccount_listing);
                if ($import_sameaccount_listing) $import_sameaccount_listing = "checked";

                setting_get("import_account_id", $account_id_listing);

                setting_get("import_from_export", $import_from_export_listing);
                if ($import_from_export_listing) $import_from_export_listing = "checked";

                setting_get("import_enable_listing_active", $import_enable_active_listing);
                if ($import_enable_active_listing) $import_enable_active_listing = "checked";

                setting_get("import_defaultlevel", $import_defaultlevel_listing);

                setting_get("import_update_listings", $import_update_items_listing);
                if ($import_update_items_listing) $import_update_items_listing = "checked";

                setting_get("import_update_friendlyurl", $import_update_friendlyurl_listing);
                if ($import_update_friendlyurl_listing) $import_update_friendlyurl_listing = "checked";

                setting_get("import_featured_categs", $import_featured_categs_listing);
                if ($import_featured_categs_listing) $import_featured_categs_listing = "checked";

                //Event
                setting_get("import_sameaccount_event", $import_sameaccount_event);
                if ($import_sameaccount_event) $import_sameaccount_event = "checked";

                setting_get("import_account_id_event",  $account_id_event);

                setting_get("import_from_export_event", $import_from_export_event);
                if ($import_from_export_event) $import_from_export_event = "checked";

                setting_get("import_enable_event_active", $import_enable_active_event);
                if ($import_enable_active_event) $import_enable_active_event = "checked";

                setting_get("import_defaultlevel_event", $import_defaultlevel_event);

                setting_get("import_update_events", $import_update_items_event);
                if ($import_update_items_event) $import_update_items_event = "checked";

                setting_get("import_update_friendlyurl_event", $import_update_friendlyurl_event);
                if ($import_update_friendlyurl_event) $import_update_friendlyurl_event = "checked";

                setting_get("import_featured_categs_event", $import_featured_categs_event);
                if ($import_featured_categs_event) $import_featured_categs_event = "checked";
            }
        } else {
            
            //Listing
            if ($import_sameaccount_listing) $import_sameaccount_listing = "checked";
            if ($import_from_export_listing) $import_from_export_listing = "checked";
            if ($import_enable_active_listing) $import_enable_active_listing = "checked";
            if ($import_update_items_listing) $import_update_items_listing = "checked";
            if ($import_update_friendlyurl_listing) $import_update_friendlyurl_listing = "checked";
            if ($import_featured_categs_listing) $import_featured_categs_listing = "checked";

            //Event
            if ($import_sameaccount_event) $import_sameaccount_event = "checked";
            if ($import_from_export_event) $import_from_export_event = "checked";
            if ($import_enable_active_event) $import_enable_active_event = "checked";
            if ($import_update_items_event) $import_update_items_event = "checked";
            if ($import_update_friendlyurl_event) $import_update_friendlyurl_event = "checked";
            if ($import_featured_categs_event) $import_featured_categs_event = "checked";
            
        }
    }

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <script language="javascript" type="text/javascript">

        function showImportFields(type1, type2, num_import, imports, module) {
            var arrImportNumbers = ('0,1').split(',');
            $("#module").attr("value", module);

            for (j=0;j<imports;j++) {
                i = arrImportNumbers[j];
                jQuery('#'+type1+'_'+i).css('display', 'none');
                jQuery('#'+type2+'_'+i).css('display', 'none');
                jQuery('#tab_'+type1+'_'+i).removeClass("tabActived");
            }    
            jQuery('#'+type1+'_'+num_import).css('display', '');
            jQuery('#'+type2+'_'+num_import).css('display', '');
            jQuery('#tab_'+type1+'_'+num_import).addClass("tabActived");

        }
        
        function JS_submit(step, sample, module) {
            if (sample) {
                if (module == "event") {
                   document.importsample_event.submit();   
                } else {
                    document.importsample.submit();  
                }
            } else {
                $("#step").attr("value", step);
                document.import_steps.submit();
            }
        }

    </script>

    <div id="main-right">

        <div id="top-content">
            <div id="header-content">
                <h1><?=string_ucwords(LANG_SITEMGR_NAVBAR_DATA_MANAGEMENT);?></h1>
            </div>
        </div>

        <div id="content-content">

            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

                <? include (INCLUDES_DIR."/tables/table_data_submenu.php"); ?>

                <div>
                    <ul class="import_steps">
                        <li <?=($step == 1 ? "class=\"step_active\"" : "")?>>
                            <? if ($step < 4) { ?>
                                <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/import/index.php"?>">
                            <? } ?>
                                <?=system_showText(LANG_SITEMGR_IMPORT_STEP);?> 1: <?=system_showText(LANG_SITEMGR_IMPORT_TYPE);?>
                            <? if ($step < 4) { ?>
                                </a>
                            <? } ?>
                        </li>

                        <li <?=($step == 2 ? "class=\"step_active\"" : "")?>>
                            <? if ($step < 4) { ?>
                                <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/import/index.php?step=2"?>">
                            <? } ?>
                                <?=system_showText(LANG_SITEMGR_IMPORT_STEP);?> 2: <?=system_showText(LANG_SITEMGR_IMPORT_DOWNLOAD);?>
                            <? if ($step < 4) { ?>
                                </a>
                            <? } ?>
                        </li>

                        <li <?=($step == 3 ? "class=\"step_active\"" : "")?>>
                            <? if ($step < 4) { ?>
                                <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/import/index.php?step=3"?>">
                            <? } ?>
                                <?=system_showText(LANG_SITEMGR_IMPORT_STEP);?> 3: <?=system_showText(LANG_SITEMGR_IMPORT_SETTINGS);?>
                            <? if ($step < 4) { ?>
                                </a>
                            <? } ?>
                        </li>

                        <li <?=($step == 4 ? "class=\"step_active\"" : "")?>>
                            <?=system_showText(LANG_SITEMGR_IMPORT_STEP);?> 4: <?=system_showText(LANG_SITEMGR_IMPORT_SELECTFILE);?>
                        </li>

                        <li <?=($step == 5 ? "class=\"step_active bd-0\"" : "class=\"bd-0\"")?>>
                            <?=system_showText(LANG_SITEMGR_IMPORT_STEP);?> 5: <?=system_showText(LANG_SITEMGR_IMPORT_PREVIEW);?> 
                        </li>
                    </ul>

                    <h1 class="import_steps_title">(<strong><?=system_showText(LANG_SITEMGR_IMPORT_STEP);?> <?=$step?></strong>) <?=system_showText(constant("LANG_SITEMGR_STEP".$step));?></h1>
                    
                    <? if ($num_import > 1 && $step == 1) { ?>
                    
                        <span class="import_steps_title_span"><?=system_showText(LANG_SITEMGR_STEP1_TIP);?></span>
                        
                    <? } elseif ($step == 4) { ?>
                        
                        <span class="import_steps_title_span"><?=system_showText(LANG_SITEMGR_STEP4_TIP);?></span>
                    
                    <? } elseif ($step == 5) { ?>
                        
                        <span class="import_steps_title_span"><?=system_showText(LANG_SITEMGR_STEP5_TIP);?></span>
                        
                    <? } ?>
                </div>

                <? if ($step < 4) { ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="standard-table import_steps_table">
                        <tr>
                            <th class="tabsBase">
                                <ul class="tabs">
                                    <? foreach ($import_numbers as $k=>$i) { ?>
                                        <li id="tab_importInfo_<?=$i?>" <?=($array_edir_importModule[$k] == $module) ? "class=\"tabActived\"" : ""?>><a href="javascript:void(0)" <?=($onclick ? "onclick=\"showImportFields('importInfo', 'extraMessage', '$i', '$num_import', '$array_edir_importModule[$k]')\"" : "style=\"cursor: default;\"")?>><?=$array_edir_import[$k]?></a></li>
                                    <? } ?>
                                </ul>
                            </th>
                        </tr>
                    </table>
                <? } ?>

                <? if ($step < 4) { ?>
                    <form name="import_steps" id="import_steps" class="import_steps_form" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">

                        <input type="hidden" id="step" name="step" value="<?=$step+1?>" />
                        <input type="hidden" id="prev_step" name="prev_step" value="<?=$step?>" />
                        <input type="hidden" id="module" name="module" value="<?=$module?>" />
                        <input type="hidden" name="update_settings" value="<?=$update_settings?>" />
                        
                        <? if ($step < 3) { ?>
                            <input type="hidden" name="import_from_export_<?=$module?>" value="<?=${"import_from_export_".$module}?>" />
                            <input type="hidden" name="import_enable_active_<?=$module?>" value="<?=${"import_enable_active_".$module}?>" />
                            <input type="hidden" name="import_update_items_<?=$module?>" value="<?=${"import_update_items_".$module}?>" />
                            <input type="hidden" name="import_update_friendlyurl_<?=$module?>" value="<?=${"import_update_friendlyurl_".$module}?>" />
                            <input type="hidden" name="import_featured_categs_<?=$module?>" value="<?=${"import_featured_categs_".$module}?>" />
                            <input type="hidden" name="import_defaultlevel_<?=$module?>" value="<?=${"import_defaultlevel_".$module}?>" />
                            <input type="hidden" name="import_sameaccount_<?=$module?>" value="<?=${"import_sameaccount_".$module}?>" />
                            <input type="hidden" name="account_id_<?=$module?>" value="<?=${"account_id_".$module}?>" />
                        <? } ?>
                        
                        <? include (INCLUDES_DIR."/forms/form_import_step_".$step.".php"); ?>

                    </form>
                <? } elseif ($step >= 4) { ?>
                
                    <form name="import_steps" id="import_steps" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">

                        <input type="hidden" id="step" name="step" value="4" />
                        <input type="hidden" id="module" name="module" value="<?=$module?>" />
                        <input type="hidden" name="update_settings" value="yes" />
                        
                        <input type="hidden" name="import_from_export_<?=$module?>" value="<?=${"import_from_export_".$module}?>" />
                        <input type="hidden" name="import_enable_active_<?=$module?>" value="<?=${"import_enable_active_".$module}?>" />
                        <input type="hidden" name="import_update_items_<?=$module?>" value="<?=${"import_update_items_".$module}?>" />
                        <input type="hidden" name="import_update_friendlyurl_<?=$module?>" value="<?=${"import_update_friendlyurl_".$module}?>" />
                        <input type="hidden" name="import_featured_categs_<?=$module?>" value="<?=${"import_featured_categs_".$module}?>" />
                        <input type="hidden" name="import_defaultlevel_<?=$module?>" value="<?=${"import_defaultlevel_".$module}?>" />
                        <input type="hidden" name="import_sameaccount_<?=$module?>" value="<?=${"import_sameaccount_".$module}?>" />
                        <input type="hidden" name="account_id_<?=$module?>" value="<?=${"account_id_".$module}?>" />
                        
                    </form>
                
                    <? include (INCLUDES_DIR."/forms/form_import_step_4.php"); ?>
                
                <? } ?>                 
                
                <? if ($step == 2) { ?>
                    <form name="importsample" id="importsample" action="importsample.php?type=listing" method="post" target="_blank">
                    </form>
                
                    <form name="importsample_event" id="importsample_event" action="importsample.php?type=event" method="post" target="_blank">
                    </form>
                <? } ?>
                
            </div>

        </div>

        <div id="bottom-content">&nbsp;</div>
    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>
