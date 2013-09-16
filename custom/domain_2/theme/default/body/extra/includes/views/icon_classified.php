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
	# * FILE: /includes/views/icon_classified.php
	# ----------------------------------------------------------------------------------------------------

    $icon_navbar = "";
	$icon_level = $classified->getNumber("level");

	$type = "Classified";
	if ($user) {

		$friend_link = DEFAULT_URL."/popup/popup.php?pop_type=classified_emailform&amp;id=".$classified->getNumber("id")."&amp;receiver=friend";
		if (sess_getAccountIdFromSession() && !$members) {
			$include_favorites_link = "javascript: void(0);";
			$include_favorites_click = "onclick=\"itemInQuicklist('add', '".sess_getAccountIdFromSession()."', '".$classified->getNumber("id")."', 'classified');\"";
		} else {
			$include_favorites_link =  DEFAULT_URL."/popup/popup.php?pop_type=profile_login&amp;destiny=".$_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];
			$includes_favorites_class = "fancy_window_login";
		}
        $removeFavoritesDiv = "";
        $removeFavoritesDivClass = "";
		if (sess_getAccountIdFromSession() || $members) {
			$remove_favorites_link = "javascript: void(0);";
			$remove_favorites_click = "onclick=\"itemInQuicklist('remove', '".sess_getAccountIdFromSession()."', '".$classified->getNumber("id")."', 'classified');\"";
            if ($members) {
                if (($id == sess_getAccountIdFromSession()) || ($members != "profile")) {
                    $removeFavoritesDivClass = "favoritesGrid";
                    $removeFavoritesDiv = " <div class=\"coverFavorites boxFavorites\">
                                                <span><a id=\"favoritesRemove_".$classified->getNumber("id")."\" href=\"".$remove_favorites_link."\" ".$remove_favorites_click." ".$remove_favorites_style.">".system_showText(LANG_ICONQUICKLIST_REMOVE)."<img src=\"".DEFAULT_URL."/images/bt_delete.gif\" border=\"0\" alt=\"\" title=\"\"/></a></span>
                                            </div>";
                }
            }
		}
		
		$print_style = "";
		if (sess_validateSessionItens("classified", "print")){
			$print_link = DEFAULT_URL."/popup/popup.php?pop_type=profile_login&amp;destiny=".$_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];
			$print_class = "class=\"fancy_window_login\"";		
		} else {
			$print_link = "javascript:window.print();";
		}
        
        $aux_friend_link = $friend_link;
		$friend_link = sess_validateSessionItens("classified", "send_email_to_friend", false, $friend_link);
        
        $fancyiFrame = true;
        if ($aux_friend_link != $friend_link){
            $fancyiFrame = false;
        }

        // SOCIAL BOOKMARKING
        if (SOCIAL_BOOKMARKING == "on") {

			$classifiedLevelObj = new ClassifiedLevel();
            if ($classifiedLevelObj->getDetail($icon_level) == "y") {
                $sbmLink = CLASSIFIED_DEFAULT_URL."/".$classified->getString("friendly_url").".html";
                $sbmLinkShare = CLASSIFIED_DEFAULT_URL."/".ALIAS_SHARE_URL_DIVISOR."/".$classified->getString("friendly_url").".html";
            } else {
                $sbmLink = CLASSIFIED_DEFAULT_URL."/results.php?id=".$classified->getNumber("id");
                $sbmLinkShare = CLASSIFIED_DEFAULT_URL."/".ALIAS_SHARE_URL_DIVISOR."/".$classified->getString("friendly_url").".html";
            }
			$facebook    = "href=\"http://www.facebook.com/sharer.php?u=".$sbmLinkShare."&amp;t=".urlencode($classified->getString("title"))."\" target=\"_blank\"";
            $twitter     = "href=\"http://twitter.com/?status=".$sbmLink."\" target=\"_blank\"";

			$socialbookmarking_style = "";
			$detailLink = $sbmLink;
			unset($sbmLink);
            unset($classifiedLevelObj);
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
		$facebook_imgE    	= "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-share-facebook.png\" alt=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Facebook\" title=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Facebook\"/>";
        $twitter_imgE     	= "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-share-twitter.png\" alt=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Twitter\" title=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Twitter\"/>";
		
		$share_icon			= "<li><a ".($classified->getNumber("id") ? "id=\"link_social_".htmlspecialchars($classified->getNumber("id")).$type."\"" : "")." href=\"javascript:void(0);\" onclick=\"enableSocialBookMarking('".$classified->getNumber("id")."', '".$type."', '".DEFAULT_URL."');\" $socialbookmarking_style>".system_showText(string_strtolower(LANG_LABEL_SHARE))."</a></li>";
	}

	$shareTemplate = "<a ".($classified->getNumber("id") ? "id=\"link_social_".htmlspecialchars($classified->getNumber("id")).$type."\"" : "")." href=\"javascript:void(0);\" onclick=\"enableSocialBookMarking('".$classified->getNumber("id")."', '".$type."', '".DEFAULT_URL."');\" $socialbookmarking_style title=\"Share this page\"></a>";
	
	$links = "";
	$cFancyBox = "";
	if($user){
		$cFancyBox = ($fancyiFrame ? "iframe fancy_window_tofriend" : "fancy_window_login");
	}

	$links .= "<li><a href=\"".$friend_link."\" class=\"".$cFancyBox."\" ".$friend_style.">".system_showText(LANG_ICONEMAILTOFRIEND)."</a></li>";
	if ($members) {
		if (($id == sess_getAccountIdFromSession()) || ($members != "profile")) {
			$links .= "<li>|</li><li><a id=\"favoritesRemove_".$classified->getNumber("id")."\" href=\"".$remove_favorites_link."\" ".$remove_favorites_click." ".$remove_favorites_style.">".system_showText(LANG_ICONQUICKLIST_REMOVE)."</a></li>";
			
			$favoriteTemplate = "<a id=\"favoritesRemove_".$classified->getNumber("id")."\" href=\"".$remove_favorites_link."\" ".$remove_favorites_click." ".$remove_favorites_style." title = \"Add to favorites\"></a>";
		}
	}else {
		$links .= "<li>|</li><li><a ".($classified->getNumber("id") ? "id=\"favorites_".$classified->getNumber("id")."\"" : "")." href=\"".$include_favorites_link."\" class=\"".$includes_favorites_class."\" ".$include_favorites_click." ".$include_favorites_style.">".system_showText(LANG_ICONQUICKLIST_ADD)."</a></li>";
		
		$favoriteTemplate = "<a ".($classified->getNumber("id") ? "id=\"favorites_".$classified->getNumber("id")."\"" : "")." href=\"".$include_favorites_link."\" class=\"".$includes_favorites_class."\" ".$include_favorites_click." ".$include_favorites_style." title=\"Add to favorites\"></a>";
	}
	if (($level->getDetail($icon_level) == "y") && ((string_strpos($_SERVER["REQUEST_URI"], ALIAS_CLASSIFIED_MODULE."/".$classified->getString("friendly_url").".html") !== false) || ($typePreview == "detail"))) {
		$links .= "<li>|</li><li><a href=\"".$print_link."\" $print_class $print_style>".system_showText(LANG_ICONPRINT)."</a></li>";	
	}
	
	if (SOCIAL_BOOKMARKING == "on"){
		/*$twitterL = "<li class=\"icon\"><a ".$twitter." >".$twitter_imgE."</a></li>";
		$facebookL = "<li class=\"icon\"><a ".$facebook." >".$facebook_imgE."</a></li>";*/
		$twitterL = "<a ".$twitter." >".$twitter_imgE."</a>";
		$facebookL = "<a ".$facebook." >".$facebook_imgE."</a>";
	}
	
	$likeObj = share_getFacebookButton(true, "", "", "", $sbmLinkShare);
	unset($detailLink);

	$extraL = "<ul class=\"share-social\">";
	$extraL .= $twitterL;
	$extraL .= $facebookL;
	$extraL .= $share_icon;
	$extraL .= "</ul>";

	$icon_navbar = $extraL."<ul class=\"share-actions\">".$links."</ul>";

?>