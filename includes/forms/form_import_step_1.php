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
	# * FILE: /includes/forms/form_import_step_1.php
	# ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
	# FORM DEFINES
	# ----------------------------------------------------------------------------------------------------
	//Listing
	$levelObj = new ListingLevel();
	setting_get("import_from_export", $import_from_export);
	setting_get("import_enable_listing_active", $import_enable_listing_active);
	setting_get("import_update_listings", $import_update_listings);
	setting_get("import_update_friendlyurl", $import_update_friendlyurl);
	setting_get("import_featured_categs", $import_featured_categs);
	setting_get("import_defaultlevel", $import_defaultlevel);
	setting_get("import_sameaccount", $import_sameaccount);
	setting_get("import_account_id", $import_account_id);
    $counterInfo = 1;
	
	if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on"){
		//Event
		$levelEventObj = new EventLevel();
		setting_get("import_from_export_event", $import_from_export_event);
		setting_get("import_enable_event_active", $import_enable_event_active);
		setting_get("import_update_events", $import_update_events);
        setting_get("import_update_friendlyurl_event", $import_update_friendlyurl_event);
		setting_get("import_featured_categs_event", $import_featured_categs_event);
		setting_get("import_defaultlevel_event", $import_defaultlevel_event);
		setting_get("import_sameaccount_event", $import_sameaccount_event);
		setting_get("import_account_id_event", $import_account_id_event);
        $counterInfoEvent = 1;
	}

?>

    <!-- LISTINGS -->
    
    <div class="import-holder">

    <div id="importInfo_0" <?=($module != "listing" ? "style=\"display: none;\"" : "")?>>

        <table class="standard-table import-table">
            <tr>
                <td class="standard-tablenote" style="text-align: justify;">
                    <?=system_showText(LANG_SITEMGR_IMPORT_REMEMBER)?>
                    
                    <br />
                    <? if ($import_from_export) { ?>
                        <?=$counterInfo.". ".system_showText(LANG_SITEMGR_IMPORT_FROMEXPORT)?>.<br />
                    <? $counterInfo++;
                    }
                    ?>

                    <? if ($import_enable_listing_active) { ?>
                        <?=$counterInfo.". ".system_showText(LANG_SITEMGR_IMPORT_ALLIMPORTEDASACTIVE)?><br />
                    <? $counterInfo++;
                    }
                    ?>

                    <? if ($import_update_listings) { ?>
                        <?=$counterInfo.". ".system_showText(LANG_SITEMGR_IMPORT_ALLIMPORTEDUPDATED)?><br />
                    <? $counterInfo++;
                    }
                    ?>
                        
                    <? if ($import_update_friendlyurl) { ?>
                        <?=$counterInfo.". ".system_showText(LANG_SITEMGR_IMPORT_UPDATEURL)?><br />
                    <? $counterInfo++;
                    }
                    ?>

                    <? if ($import_featured_categs) { ?>
                        <?=$counterInfo.". ".system_showText(LANG_SITEMGR_IMPORT_FEATURED_CATEGS)?><br />
                    <? $counterInfo++;
                    }
                    ?>

                    <?=$counterInfo.". ".system_showText(LANG_SITEMGR_IMPORT_DEFAULTLEVELSETTO)?> "<?=$levelObj->showLevel($import_defaultlevel)?>".<br />
                    <?
                    $counterInfo++;
                    if ($import_sameaccount) {
                        $sameAccountObj = new Account($import_account_id);
                        ?><?=$counterInfo.". ".system_showText(LANG_SITEMGR_IMPORT_IMPORTEDSAMEACCOUNT)?> <b><?=$sameAccountObj->getString('username')?>.</b><br /><?
                    } else {
                        ?><?=$counterInfo.". ".system_showText(LANG_SITEMGR_IMPORT_LISTINGWILLBEASSOCIATESEPARATEACCOUNT)?><br /><?
                    }
                    ?>
                    <br />
                    <br />
                    <?=system_showText(LANG_SITEMGR_STEP1_TIP2)?>
                </td>
            </tr>
        </table>
    </div>

    <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
        <!-- EVENTS -->
        <div id="importInfo_1" <?=($module != "event" ? "style=\"display: none;\"" : "")?>>
            <table class="standard-table import-table">
                <tr>
                    <td class="standard-tablenote" style="text-align: justify;">
                        <?=system_showText(LANG_SITEMGR_IMPORT_REMEMBER)?>
                        
                        <br />
                        <? if ($import_from_export_event) { ?>
                            <?=$counterInfoEvent.". ".system_showText(LANG_SITEMGR_IMPORT_FROMEXPORT)?>.<br />
                        <? $counterInfoEvent++;
                        }
                        ?>

                        <? if ($import_enable_event_active) { ?>
                            <?=$counterInfoEvent.". ".system_showText(LANG_SITEMGR_IMPORT_ALLIMPORTEDASACTIVE)?><br />
                        <?  $counterInfoEvent++;
                        }
                        ?>

                        <? if ($import_update_events) { ?>
                            <?=$counterInfoEvent.". ".system_showText(LANG_SITEMGR_IMPORT_ALLIMPORTEDUPDATED)?><br />
                        <? $counterInfoEvent++;
                        }
                        ?>
                            
                        <? if ($import_update_friendlyurl_event) { ?>
                            <?=$counterInfoEvent.". ".system_showText(LANG_SITEMGR_IMPORT_UPDATEURL)?><br />
                        <? $counterInfoEvent++;
                        }
                        ?>

                        <? if ($import_featured_categs_event) { ?>
                            <?=$counterInfoEvent.". ".system_showText(LANG_SITEMGR_IMPORT_FEATURED_CATEGS)?><br />
                        <? $counterInfoEvent++;
                        }
                        ?>

                        <?=$counterInfoEvent.". ".system_showText(LANG_SITEMGR_IMPORT_DEFAULTLEVELSETTO)?> "<?=$levelEventObj->showLevel($import_defaultlevel_event)?>".<br />
                        <?
                        $counterInfoEvent++;
                        if ($import_sameaccount_event) {
                            $sameAccountObj = new Account($import_account_id_event);
                            ?><?=$counterInfoEvent.". ".system_showText(LANG_SITEMGR_IMPORT_IMPORTEDSAMEACCOUNT)?> <b><?=$sameAccountObj->getString('username')?>.</b><br /><?
                        } else {
                            ?><?=$counterInfoEvent.". ".system_showText(LANG_SITEMGR_IMPORT_LISTINGWILLBEASSOCIATESEPARATEACCOUNT)?><br /><?
                        }
                        ?>
                        <br />
                        <br />
                        <?=system_showText(LANG_SITEMGR_STEP1_TIP2)?>
                    </td>
                </tr>
            </table>
        </div>
    <? } ?>
    
    </div>

    <button type="submit" name="submit_button" class="input-button-form right" value="Submit"><?=system_showText(LANG_SITEMGR_NEXT)?></button> 