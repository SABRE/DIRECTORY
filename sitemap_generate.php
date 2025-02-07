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
	# * FILE: /sitemap_generate.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("./conf/loadconfig.inc.php");
	
	# ----------------------------------------------------------------------------------------------------
	# FILE HEADER
	# ----------------------------------------------------------------------------------------------------
	echo '<?xml version="1.0" encoding="UTF-8"?>';
    if (isset($_GET["news"])) {
        $fileXML = "indexnews.xml";
    } else {
        $fileXML = "index.xml";
    }
    
?>

    <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        <sitemap>
            <loc><?=DEFAULT_URL?>/custom/domain_<?=SELECTED_DOMAIN_ID?>/sitemap/<?=$fileXML?></loc>
        </sitemap>
    </sitemapindex>
