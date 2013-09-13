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
	# * FILE: /edir_core/listing/results_listings.php
	# ----------------------------------------------------------------------------------------------------
	if ($show_results || $search_lock) {

		if (!$listings) {
		
			if ($search_lock) {
				?>
				<p class="errorMessage">
					<?=system_showText(LANG_MSG_LEASTONEPARAMETER)?>
				</p>
				<?
			} else {
				$db = db_getDBObject();
				if ($db->getRowCount("Listing_Summary") > 0) { ?>
					<div class="resultsMessage">
                        <?
                        unset($aux_lang_msg_noresults);                        
                        $aux_lang_msg_noresults = str_replace("[EDIR_LINK_SEARCH_ERROR]",DEFAULT_URL."/".ALIAS_CONTACTUS_URL_DIVISOR.".php", LANG_SEARCH_NORESULTS);
                        echo $aux_lang_msg_noresults;
                        ?>
					</div>
                <? } else { ?>
					<p class="informationMessage">
						<?=system_showText(LANG_MSG_NOLISTINGS);?>
					</p>
                <? }
			}
		} elseif ($listings) {
			
			$levelObj = new ListingLevel(true);
			
			$locationManager = new LocationManager();
			$mapNumber = 0;
			$count = 10;
			$ids_report_lote = "";
			
			/**
			 * This variable is used on view_listing_summary.php
			 */
			if (TWILIO_APP_ENABLED == "on"){
				if (TWILIO_APP_ENABLED_SMS == "on"){
					$levelsWithSendPhone = system_retrieveLevelsWithInfoEnabled("has_sms");
				}else{
					$levelsWithSendPhone = false;
				}
				if (TWILIO_APP_ENABLED_CALL == "on"){
					$levelsWithClicktoCall = system_retrieveLevelsWithInfoEnabled("has_call");
				}else{
					$levelsWithClicktoCall = false;
				}
			}else{
				$levelsWithSendPhone = false;
				$levelsWithClicktoCall = false;
			}
                       // echo '<pre>';
			//print_r($listings); die;
			
			$actualCounter=0;
                        
			foreach ($listings as $listing) {
				$ids_report_lote .= $listing["id"].",";
				if ($listing["latitude"] && $listing["longitude"]) {
					$mapNumber++;
				}
				
				$includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
				
				if(SELECTED_DOMAIN_ID > 0)
					include($includeUrl."includes/views/view_listing_summary.php");
				else
					include(INCLUDES_DIR."/views/view_listing_summary.php");
				
				
                if ($count%2 && ($count != 10) && ITEM_RESULTS_CLEAR){
                    echo "<br class=\"clear\" />";
                }
				$count--;
				$actualCounter++;
            }
			$ids_report_lote = string_substr($ids_report_lote, 0, -1);
			report_newRecord("listing", $ids_report_lote, LISTING_REPORT_SUMMARY_VIEW, true);
		}
	}
?>