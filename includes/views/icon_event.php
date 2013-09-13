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
	# * FILE: /includes/views/icon_event.php
	# ----------------------------------------------------------------------------------------------------

	$icon_navbar = "";
	$icon_event_level = $event->getNumber("level");

	$type = "Event";
	if ($user) {

		$friend_link = DEFAULT_URL."/popup/popup.php?pop_type=event_emailform&amp;id=".$event->getNumber("id")."&amp;receiver=friend";
		if (sess_getAccountIdFromSession() && !$members) {
			$include_favorites_link = "javascript: void(0);";
			$include_favorites_click = "onclick=\"itemInQuicklist('add', '".sess_getAccountIdFromSession()."', '".$event->getNumber("id")."', 'event');\"";
		} else {
			$include_favorites_link =  DEFAULT_URL."/popup/popup.php?pop_type=profile_login&amp;destiny=".$_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];
			$includes_favorites_class = "fancy_window_login";
		}
        $removeFavoritesDiv = "";
        $removeFavoritesDivClass = "";
		if (sess_getAccountIdFromSession() || $members) {
			$remove_favorites_link = "javascript: void(0);";
			$remove_favorites_click = "onclick=\"itemInQuicklist('remove', '".sess_getAccountIdFromSession()."', '".$event->getNumber("id")."', 'event');\"";
            if ($members) {
                if (($id == sess_getAccountIdFromSession()) || ($members != "profile")) {
                    $removeFavoritesDivClass = "favoritesGrid";
                    $removeFavoritesDiv = " <div class=\"coverFavorites boxFavorites\">
                                                <span><a id=\"favoritesRemove_".$event->getNumber("id")."\" href=\"".$remove_favorites_link."\" ".$remove_favorites_click." ".$remove_favorites_style.">".system_showText(LANG_ICONQUICKLIST_REMOVE)."<img src=\"".DEFAULT_URL."/images/bt_delete.gif\" border=\"0\" alt=\"\" title=\"\"/></a></span>
                                            </div>";
                }
            }
		}
		$print_style = "";
		if (sess_validateSessionItens("event", "print")){
			$print_link = DEFAULT_URL."/popup/popup.php?pop_type=profile_login&amp;destiny=".$_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];
			$print_class = "class=\"fancy_window_login\"";		
		} else {
			$print_link = "javascript:window.print();";
		}
        
        $aux_friend_link = $friend_link;
		$friend_link = sess_validateSessionItens("event", "send_email_to_friend", false, $friend_link);
		$fancyiFrame = true;
        if ($aux_friend_link != $friend_link){
            $fancyiFrame = false;
        }

		// SOCIAL BOOKMARKING
		if (SOCIAL_BOOKMARKING == "on") {

			$eventLevelObj = new EventLevel();
			if ($eventLevelObj->getDetail($icon_event_level) == "y") {
				
                $sbmLink = EVENT_DEFAULT_URL."/".$event->getString("friendly_url").".html";
                $sbmLinkShare = EVENT_DEFAULT_URL."/".ALIAS_SHARE_URL_DIVISOR."/".$event->getString("friendly_url").".html";
				
			} else {
				$sbmLink = EVENT_DEFAULT_URL."/results.php?id=".$event->getNumber("id");
                $sbmLinkShare = EVENT_DEFAULT_URL."/".ALIAS_SHARE_URL_DIVISOR."/".$event->getString("friendly_url").".html";
			}
			$facebook    = "href=\"http://www.facebook.com/sharer.php?u=".$sbmLinkShare."&amp;t=".urlencode($event->getString("title"))."\" target=\"_blank\" title=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Facebook\"";
			$twitter     = "href=\"http://twitter.com/?status=".$sbmLink."\" target=\"_blank\" title=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Twitter\"";
			
			$detailLink = $sbmLink;
			unset($sbmLink);
			unset($eventLevelObj);
			$claim_style = "";
			$socialbookmarking_style = "";

		}

	} else {

		$friend_link = "javascript:void(0);";
		$include_favorites_link = "javascript:void(0);";
		$remove_favorites_link = "javascript:void(0);";
		$print_link = "javascript:void(0);";

		$friend_style = "style=\"cursor:default\"";
        $include_favorites_style = "style=\"cursor:default\"";
        $print_style = "style=\"cursor:default\"";
		$socialbookmarking_style = "style=\"cursor:default\"";
        
		// SOCIAL BOOKMARKING
		if (SOCIAL_BOOKMARKING == "on") {
            $facebook    = "href=\"javascript:void(0);\" style=\"cursor:default\"";
            $twitter     = "href=\"javascript:void(0);\" style=\"cursor:default\"";
		}
	}

	// SOCIAL BOOKMARKING IMAGES
	if (SOCIAL_BOOKMARKING == "on") {
		$facebook_imgE    = "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-share-facebook.png\" alt=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Facebook\" title=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Facebook\" />";
		$twitter_imgE     = "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-share-twitter.png\" alt=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Twitter\" title=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Twitter\" />";
		$share_icon		  = "<li><a ".($event->getNumber("id") ? "id=\"link_social_".htmlspecialchars($event->getNumber("id")).$type."\"" : "")." href=\"javascript:void(0);\" onclick=\"enableSocialBookMarking(".$event->getNumber("id").", '".$type."', '".DEFAULT_URL."');\" $socialbookmarking_style>".system_showText(string_strtolower(LANG_LABEL_SHARE))."</a></li>";
		
	}

	$links = "";
	$cFancyBox = "";
	if($user){
		$cFancyBox = ($fancyiFrame ? "iframe fancy_window_tofriend" : "fancy_window_login");
	}
	
	$links .= "<li><a href=\"".$friend_link."\" class=\"".$cFancyBox."\" ".$friend_style.">".system_showText(LANG_ICONEMAILTOFRIEND)."</a></li>";
	if ($members) {
		if (($id == sess_getAccountIdFromSession()) || ($members != "profile")) {
			$links .= "<li>|</li><li><a id=\"favoritesRemove_".$event->getNumber("id")."\" href=\"".$remove_favorites_link."\" ".$remove_favorites_click." ".$remove_favorites_style.">".system_showText(LANG_ICONQUICKLIST_REMOVE)."</a></li>";
		}
	}else {
		$links .= "<li>|</li><li><a ".($event->getNumber("id") ? "id=\"favorites_".$event->getNumber("id")."\"" : "")." href=\"".$include_favorites_link."\" class=\"".$includes_favorites_class."\" ".$include_favorites_click." ".$include_favorites_style.">".system_showText(LANG_ICONQUICKLIST_ADD)."</a></li>";
	}
	if (($level->getDetail($icon_event_level)) == "y" && ((string_strpos($_SERVER["REQUEST_URI"], ALIAS_EVENT_MODULE."/".$event->getString("friendly_url").".html") !== false) || ($typePreview == "detail"))) {
		$links .= "<li>|</li><li><a href=\"".$print_link."\" $print_class $print_style>".system_showText(LANG_ICONPRINT)."</a></li>";	
	}
	
	if (SOCIAL_BOOKMARKING == "on"){
		$twitterL = "<li class=\"icon\"><a ".$twitter." >".$twitter_imgE."</a></li>";
		$facebookL = "<li class=\"icon\"><a ".$facebook." >".$facebook_imgE."</a></li>";
	}
	
    $likeObj = share_getFacebookButton(true, "", "", "", $sbmLinkShare);
    unset($detailLink);

	$extraL = "<ul class=\"share-social\">";
	$extraL .= $twitterL;
	$extraL .= $facebookL;
	$extraL .= $share_icon;
	$extraL .= "</ul>";


	$icon_navbar = $extraL;
	$icon_navbar .= "<ul class=\"share-actions\">";
	$icon_navbar .= $links;
	$icon_navbar .= "</ul>";
?>