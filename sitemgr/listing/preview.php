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
	# * FILE: /sitemgr/listing/preview.php
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

	$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".LISTING_FEATURE_FOLDER;
	$url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
	$sitemgr = 1;

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_POST);
	extract($_GET);

	$url_search_params = system_getURLSearchParams((($_POST)?($_POST):($_GET)));

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	$error = false;

	if ($id) {
		$listing = new Listing($id);
		
		if ((!$listing->getNumber("id")) || ($listing->getNumber("id") <= 0)) {
			$error = true;
		}
	} else {
		$error = true;
	}

	header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);

	$levelObj = new ListingLevel();

	# ----------------------------------------------------------------------------------------------------
	# REVIEWS
	# ----------------------------------------------------------------------------------------------------
	if ($id)  $sql_where[] = " item_type = 'listing' AND item_id = ".db_formatNumber($id)." ";
	if (true) $sql_where[] = " review IS NOT NULL AND review != '' ";
	if (true) $sql_where[] = " approved = '1' ";
	if ($sql_where) $sqlwhere .= " ".implode(" AND ", $sql_where)." ";
	$pageObj  = new pageBrowsing("Review", $screen, 3, "added DESC", "", "", $sqlwhere);
	$reviewsArr = $pageObj->retrievePage("object");

	# ----------------------------------------------------------------------------------------------------
	# CHECK INS
	# ----------------------------------------------------------------------------------------------------
	if ($id)  $sql_where2[] = " item_id = ".db_formatNumber($id)." ";
	if (true) $sql_where2[] = " quick_tip IS NOT NULL AND quick_tip != '' ";
	if ($sql_where2) $sqlwhere2 .= " ".implode(" AND ", $sql_where2)." ";
	$pageObj  = new pageBrowsing("CheckIn", $screen, 3, "added DESC", "", "", $sqlwhere2);
	$checkinsArr = $pageObj->retrievePage("object");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=EDIR_CHARSET;?>" />
		<title><?=system_showText(LANG_SITEMGR_HOME_WELCOME) . " - " . system_showText(LANG_SITEMGR_LISTING_SING)?> -<?=string_ucwords(system_showText(LANG_SITEMGR_PREVIEW))?></title>
		<?
		include(THEMEFILE_DIR."/".EDIR_THEME."/".EDIR_THEME.".php");
		?>
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery.js"></script>
        <? if (USE_GALLERY_PLUGIN){ ?>
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/ad-gallery/jquery.ad-gallery.js"></script>
        <? } ?>
		<script language="javascript" type="text/javascript" src="<?=DEFAULT_URL?>/scripts/socialbookmarking.js"></script>
		<?=system_getNoImageStyle($cssfile = true);?>
    </head>
	
	<body class="import-body">

		<?
		if (!$error) {
			setting_get('commenting_edir', $commenting_edir);
			setting_get("review_listing_enabled", $review_enabled);
			$levelsWithReview = system_retrieveLevelsWithInfoEnabled("has_review");
			$levelObj = new ListingLevel();
			?>
			<div class="level level-preview">
				
				<div class="level-summary">	

					<p class="preview-desc"><?=system_showText(LANG_SITEMGR_SUMMARYPAGE);?></p>

					<?
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
                    
                     $includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
                    
                    $type = "summary";
                    
                    if(SELECTED_DOMAIN_ID > 0)
                        include($includeUrl."includes/views/view_listing_summary.php");
                    else
                        include(INCLUDES_DIR."/views/view_listing_summary.php");
		?>

				</div>

				<?
				/*
				 * Create new Listing Obj
				 */
				$listing = new Listing($id);
				$type = "detail";
				$typePreview = "detail"; 

				if ($levelObj->getDetail($listing->getNumber("level")) == "y") {
				?>
				<p class="preview-desc"><?=system_showText(LANG_SITEMGR_DETAILPAGE);?></p>
                                <?	if(SELECTED_DOMAIN_ID > 0){
								echo "<div class='detail-page'>";
								echo "<div class='below-section'>";
								include($includeUrl."includes/views/view_listing_detail.php");
								echo "</div>"
						?>
								<div class="sidebar">
                        		<? $signUpListing = true; include(system_getFrontendPath("detail_info.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								<? $signUpListing = true; include(system_getFrontendPath("detail_maps.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								<? $signUpListing = true; include(system_getFrontendPath("detail_deals.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								<? $signUpListing = true; include(system_getFrontendPath("detail_reviews.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								<? $signUpListing = true; include(system_getFrontendPath("detail_checkin.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								</div>
						<?		echo "</div>";
							}else{
								include(INCLUDES_DIR."/views/view_listing_detail.php");
							} 
						?>
				<? } ?>
			</div>
			<?
			
		} else {?>
			<p class="errorMessage"><?=system_showText(LANG_MSG_NOTFOUND);?></p>
		<? } ?>

	</body>
</html>
