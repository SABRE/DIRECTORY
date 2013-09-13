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
# * FILE: /custom/domain_4/conf/constants.inc.php
# ----------------------------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------------------
# FLAGS - on/off
# ----------------------------------------------------------------------------------------------------
# ****************************************************************************************************
# MODULES
# NOTE: Do not alter this area of the code manually.
# Any changes will require eDirectory to be activated again.
# P.S.: you can turn off it any time.
# ****************************************************************************************************
define("EVENT_FEATURE", "on");
define("BANNER_FEATURE", "on");
define("CLASSIFIED_FEATURE", "off");
define("ARTICLE_FEATURE", "off");
define("PROMOTION_FEATURE", "on");
define("BLOG_FEATURE", "off");
define("ZIPCODE_PROXIMITY", "on");
# ****************************************************************************************************
# FEATURES
# NOTE: Do not alter this area of the code manually.
# Any changes will require eDirectory to be activated again.
# P.S.: you can turn off it any time.
# ****************************************************************************************************
define("CUSTOM_INVOICE_FEATURE", "on");
define("CLAIM_FEATURE", "on");
define("LISTINGTEMPLATE_FEATURE", "on");
define("MOBILE_FEATURE", "off");
define("MULTILANGUAGE_FEATURE", "on");
define("MAINTENANCE_FEATURE", "on");
# ****************************************************************************************************
# EXTRA FEATURES
# NOTE: Do not alter this area of the code manually.
# Any changes will require eDirectory to be activated again.
# P.S.: you can turn off it any time.
# ****************************************************************************************************
define("SITEMAP_FEATURE", "on");
# ****************************************************************************************************
# CUSTOMIZATIONS
# NOTE: Do not alter this area of the code manually.
# Any changes will require eDirectory to be activated again.
# ****************************************************************************************************
define("BRANDED_PRINT", "off");
# ****************************************************************************************************
# PAYMENT SYSTEM FEATURE
# NOTE: Do not alter this area of the code manually.
# Any changes will require eDirectory to be activated again.
# P.S.: you can turn off it any time.
# ****************************************************************************************************
define("PAYMENTSYSTEM_FEATURE", "on");
# ----------------------------------------------------------------------------------------------------
# EDIRECTORY TITLE
# ----------------------------------------------------------------------------------------------------
define("EDIRECTORY_TITLE", " ");
# ----------------------------------------------------------------------------------------------------
# GEO IP CONFIGURATION
# ----------------------------------------------------------------------------------------------------
define("GEOIP_FEATURE", "off");
# ----------------------------------------------------------------------------------------------------
# SHOW BANNER MODE
# NOTE: This flag is only to the front view
# ----------------------------------------------------------------------------------------------------
define("SHOW_INACTIVE_BANNER", "off");
# ----------------------------------------------------------------------------------------------------
# CACHE FULL SETTINGS
# ----------------------------------------------------------------------------------------------------
define("CACHE_FULL_FEATURE", "off"); //be sure that the constant below is also on if you turn this one on
define("CACHE_FULL_ZLIB_COMPRESSION_IF_AVAILABLE", "on"); //this constant must be on if CACHE_FULL_FEATURE is on
define("CACHE_FULL_VERBOSE_MODE", "off"); 
define("CACHE_FULL_LOG_EXPIRATION_QUERIES", "off"); 
define("CACHE_FULL_INCLUDE_CACHE_COMMENT_AT_PAGE", "off");
define("CACHE_FULL_FOR_LOGGED_MEMBERS", "on");
define("CACHE_FULL_REMOVE_FILES_WHEN_DISABLED", "on");
# ----------------------------------------------------------------------------------------------------
# CACHE FULL FEATURE CONTENT SETTINGS
# ----------------------------------------------------------------------------------------------------
define("CACHE_FULL_ALWAYS_FRESH_FEATURED_LISTING", "on");
define("CACHE_FULL_ALWAYS_FRESH_FEATURED_DEAL", "on");
define("CACHE_FULL_ALWAYS_FRESH_FEATURED_CLASSIFIED", "on");
define("CACHE_FULL_ALWAYS_FRESH_FEATURED_EVENT", "on");
define("CACHE_FULL_ALWAYS_FRESH_FEATURED_ARTICLE", "on");
# ----------------------------------------------------------------------------------------------------
# CACHE PARTIAL SETTINGS
# ----------------------------------------------------------------------------------------------------
define("CACHE_PARTIAL_FEATURE", "off");
# ----------------------------------------------------------------------------------------------------
# FRONT SEARCH
# ----------------------------------------------------------------------------------------------------
define("SEARCH_FORCE_BOOLEANMODE", "on");
# ----------------------------------------------------------------------------------------------------
# GALLERY IMAGES
#  - Turn on the constant GALLERY_FREE_RATIO to remove the crop for wide images.
#  - Remember to turn off the constant RESIZE_IMAGES_UPGRADE.
#  - ATTENTION! The thumb preview in the upload window will not be shown when this constant is turned on.
#  - You can also force all jpg images to be saved as png for better quality by turning on the constant FORCE_SAVE_JPG_AS_PNG.
# ----------------------------------------------------------------------------------------------------
define("GALLERY_FREE_RATIO", "off");
define("FORCE_SAVE_JPG_AS_PNG", "off");
# ----------------------------------------------------------------------------------------------------
# RESIZE IMAGES AFTER UPGRADE
#  on (DEFAULT) - all images will be stretched to fit the new dimensions
#  off - all images will keep the same size, but the layout can be affected
# ----------------------------------------------------------------------------------------------------
define("RESIZE_IMAGES_UPGRADE", "on");
# ----------------------------------------------------------------------------------------------------
# SITEMAP LINKS
#  - Turn on to add "www" to sitemap links.
# ----------------------------------------------------------------------------------------------------
define("SITEMAP_ADD_WWW", "off");
# ----------------------------------------------------------------------------------------------------
# MODULES ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_LISTING_MODULE", "listing");
define("ALIAS_PROMOTION_MODULE", "deal");
define("ALIAS_EVENT_MODULE", "event");
define("ALIAS_ARTICLE_MODULE", "article");
define("ALIAS_CLASSIFIED_MODULE", "classified");
define("ALIAS_BLOG_MODULE", "blog");
# ----------------------------------------------------------------------------------------------------
# BROWSE BY CATEGORY ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_CATEGORY_URL_DIVISOR", "guide");
# ----------------------------------------------------------------------------------------------------
# BROWSE BY LOCATION ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_LOCATION_URL_DIVISOR", "location");
# ----------------------------------------------------------------------------------------------------
# FACEBOOK SHARE ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_SHARE_URL_DIVISOR", "share");
# ----------------------------------------------------------------------------------------------------
# CLAIM ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_CLAIM_URL_DIVISOR", "claim");
# ----------------------------------------------------------------------------------------------------
# REVIEWS ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_REVIEW_URL_DIVISOR", "reviews");
# ----------------------------------------------------------------------------------------------------
# CHECKINS ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_CHECKIN_URL_DIVISOR", "checkins");
# ----------------------------------------------------------------------------------------------------
# BACKLINK ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_BACKLINK_URL_DIVISOR", "backlink");
# ----------------------------------------------------------------------------------------------------
# ALL CATEGORIES PAGE ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_ALLCATEGORIES_URL_DIVISOR", "allcategories");
# ----------------------------------------------------------------------------------------------------
# ALL LOCATIONS PAGE ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_ALLLOCATIONS_URL_DIVISOR", "alllocations");
# ----------------------------------------------------------------------------------------------------
# BLOG BROWSE BY DATE ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_ARCHIVE_URL_DIVISOR", "archive");
# ----------------------------------------------------------------------------------------------------
# ADVERTISE ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_ADVERTISE_URL_DIVISOR", "advertise");
# ----------------------------------------------------------------------------------------------------
# CONTACTUS ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_CONTACTUS_URL_DIVISOR", "contactus");
# ----------------------------------------------------------------------------------------------------
# FAQ ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_FAQ_URL_DIVISOR", "faq");
# ----------------------------------------------------------------------------------------------------
# SITEMAP ALIAS
# ----------------------------------------------------------------------------------------------------
define("ALIAS_SITEMAP_URL_DIVISOR", "sitemap");
# ----------------------------------------------------------------------------------------------------
# MODULES URLS
# ----------------------------------------------------------------------------------------------------
define("LISTING_FEATURE_NAME", "listing");
define("LISTING_FEATURE_NAME_PLURAL", LISTING_FEATURE_NAME."s");
define("LISTING_DEFAULT_URL", NON_SECURE_URL."/".ALIAS_LISTING_MODULE);

define("PROMOTION_FEATURE_NAME", "deal");
define("PROMOTION_FEATURE_NAME_PLURAL", PROMOTION_FEATURE_NAME."s");
define("PROMOTION_DEFAULT_URL", NON_SECURE_URL."/".ALIAS_PROMOTION_MODULE);

define("EVENT_FEATURE_NAME", "event");
define("EVENT_FEATURE_NAME_PLURAL", EVENT_FEATURE_NAME."s");
define("EVENT_DEFAULT_URL", NON_SECURE_URL."/".ALIAS_EVENT_MODULE);

define("CLASSIFIED_FEATURE_NAME", "classified");
define("CLASSIFIED_FEATURE_NAME_PLURAL", CLASSIFIED_FEATURE_NAME."s");
define("CLASSIFIED_DEFAULT_URL", NON_SECURE_URL."/".ALIAS_CLASSIFIED_MODULE);

define("ARTICLE_FEATURE_NAME", "article");
define("ARTICLE_FEATURE_NAME_PLURAL", ARTICLE_FEATURE_NAME."s");
define("ARTICLE_DEFAULT_URL", NON_SECURE_URL."/".ALIAS_ARTICLE_MODULE);

define("BLOG_FEATURE_NAME", "blog");
define("BLOG_FEATURE_NAME_PLURAL", BLOG_FEATURE_NAME."s");
define("BLOG_DEFAULT_URL", NON_SECURE_URL."/".ALIAS_BLOG_MODULE);

define("BANNER_FEATURE_NAME", "banner");
define("BANNER_FEATURE_NAME_PLURAL", BANNER_FEATURE_NAME."s");
?>
