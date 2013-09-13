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
	# * FILE: /frontend/commentscontent.php
	# ----------------------------------------------------------------------------------------------------
	if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) {
        if (string_strpos($_SERVER["REQUEST_URI"], ALIAS_CHECKIN_URL_DIVISOR."/") !== false){
            $isCheckin = true;
            setting_get('commenting_edir', $commenting_edir);
            setting_get("review_listing_enabled", $review_enabled);
            $levelsWithReview = system_retrieveLevelsWithInfoEnabled("has_review");
        }
        $moduleName = "listing";
        $itemObj = $listingObj;
        $itemName = $itemObj->getString("title");
        $levelObj = new ListingLevel();
        
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
        } else {
            $levelsWithSendPhone = false;
            $levelsWithClicktoCall = false;
        }
    } elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
        $moduleName = "article";
        $itemObj = $articleObj;
        $itemName = $itemObj->getString("title");
        $level = new ArticleLevel();
        $pageReviews = true;
    } elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
        $moduleName = "promotion";
        $itemObj = $promotionObj;
        $itemName = $itemObj->getString("name");
        $pageReviews = true;
    }

    if (sess_validateSessionItens($moduleName, "see_comments")) {
        if ($error_message) { 
            echo "<p class=\"errorMessage\">".$error_message."</p>";
        } else { ?>

			<h2><?=system_showText(($isCheckin ? LANG_CHECKINSOF : LANG_REVIEWSOF))?> <?=$itemName?></h2>

			<?
			${$moduleName} = $itemObj;
			$user = true;
            $includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
			
			include($includeUrl . "includes/views/view_".$moduleName."_summary.php");
			
			//include(INCLUDES_DIR . "/views/view_".$moduleName."_summary.php");
            
			unset(${$moduleName});
			include(system_getFrontendPath("results_filter.php"));
			include(system_getFrontendPath("results_pagination.php"));
            
            if ($isCheckin){
                $reviewsArr = $checkinsArr;
            }

			if ($reviewsArr) {
				
				$totalReviewsPage = count($reviewsArr);
				
				echo "<div class=\"featured featured-review featured-review-detail\">";
				foreach ($reviewsArr as $each_rate) {
                    if ($isCheckin) {
                        if ($each_rate->getString("quick_tip")) {
                            $each_rate->extract();
                            include(INCLUDES_DIR."/views/view_checkin_detail.php");
                            echo $item_checkincomment;
                        }
                    } else {
                        if ($each_rate->getString("review")) {
                            $each_rate->extract();
                            include(INCLUDES_DIR."/views/view_review_detail.php");
                            echo $item_reviewcomment;
                        }
                    }
				}
				echo "</div>";
			} else {
				echo "<p class=\"informationMessage\">".system_showText(($isCheckin ? LANG_CHECKIN_NORECORD : LANG_REVIEW_NORECORD))."</p>";
			}

			unset($user);
		 }
	 } 
 ?>