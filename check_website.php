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
	# * FILE: /check_website.php
	# ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("./conf/loadconfig.inc.php");
    
    extract($_POST);
    
    if ($url && $id){
        
        $listingObj = new Listing($id);
        
        $searchFor = LISTING_DEFAULT_URL."/".ALIAS_BACKLINK_URL_DIVISOR."/".$listingObj->getString("friendly_url").".html";

        $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $val_httpCode = false;
        $val_content = false;

        if($httpcode >= 200 && $httpcode < 400){
            $val_httpCode = true;
        }

        if (($output) && (string_strpos($output, $searchFor) !== false)){
            $val_content = true;
        }

        if ($val_httpCode && $val_content){
            echo "OK";
        } else {
            echo "ERROR";
        }
    } else {
        echo "ERROR";
    }