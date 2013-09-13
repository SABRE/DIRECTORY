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
	# * FILE: /frontend/detail_maps.php
	# ----------------------------------------------------------------------------------------------------

    if ((ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) || $signUpListing) {
        $moduleMessage = $listingMsg;
        $moduleMaps = $listingtemplate_google_maps;
        $signUpListing = false;
    } elseif ((ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) || $signUpClassified) {
         $moduleMessage = $classifiedMsg;
        $moduleMaps = $classified_googlemaps;
        $signUpClassified = false;
    } elseif ((ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) || $signUpEvent) {
        $moduleMessage = $eventMsg;
        $moduleMaps = $event_googlemaps;
        $signUpEvent = false;
    }
	
	if ($tPreview) { ?>
		<div class="map">
            <img src="<?=THEMEFILE_URL."/".EDIR_THEME."/images/imagery/img-google-map-sample.gif"?>" alt="Sample" title=""/>
		</div>
	<? } else {
		if (!$moduleMessage && !$hideDetail){
			if (GOOGLE_MAPS_ENABLED == "on" && $mapObj->getString("value") == "on") { ?>
				<div id="map" class="map">&nbsp;</div>
                <?=$moduleMaps?>
			<? }
		}
	}
?>