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
	# * FILE: /controller/blog/rewrite.php
	# ----------------------------------------------------------------------------------------------------

    $failure = false;
	$dbObj = db_getDBObject();

	$browsebycategory = false;
	$browsebyitem = false;
	$browsebydate = false;
	
	if ($_GET["url_full"] && !$fromRSS){
		$_GET["url_full"] = $_SERVER["REQUEST_URI"];
	}
    
	if ($_GET["url_full"] && (string_strpos($_GET["url_full"], 'results.php') !== false || string_strpos($_GET["url_full"], ALIAS_ARCHIVE_URL_DIVISOR) !== false)) {

		$url = string_replace_once(EDIRECTORY_FOLDER."/".ALIAS_BLOG_MODULE."/", "", $_GET["url_full"]);
		$parts = explode("/", $url);

		if (string_strpos($_GET["url_full"], ALIAS_CATEGORY_URL_DIVISOR) !== false && $parts[1] != ALIAS_CATEGORY_URL_DIVISOR){
			$browsebycategory = true;

			for ($i=1; $i < count($parts); $i++){
				$_GET["category".$i] = $parts[$i];
			}

		} else if (string_strpos($_GET["url_full"], ALIAS_ARCHIVE_URL_DIVISOR) !== false  && $parts[1] != ALIAS_ARCHIVE_URL_DIVISOR){
			$browsebydate = true;

			for ($i=1; $i < count($parts); $i++){
				$_GET["category".$i] = $parts[$i];
			}

		} else {
			if (!$parts[1]) {
				header("Location: ".BLOG_DEFAULT_URL."/results.php");
				exit;
			}

			if ($parts[1] != "empty"){
				$_GET["keyword"] = $parts[1];
				$_GET["keyword"] = str_replace("|2F","/", $_GET["keyword"]);
				$_GET["keyword"] = str_replace("|3F","\\", $_GET["keyword"]);
			} else {
				$_GET["keyword"] = "";
			}

			for ($i==2; $i<count($parts); $i++){
				switch($parts[$i]) {
					case 'page': $_GET["page"] = $parts[$i+1];
									break;
					case 'letter': $_GET["letter"] = $parts[$i+1];
									break;
					case 'orderby': $_GET["orderby"] = $parts[$i+1];
									break;
				}
			}
		}
	}
        else if($_GET["url_full"] && string_strpos($_GET["url_full"],'results.php')== FALSE)
        {
            $friendlyurl = true;
            $url = string_replace_once(EDIRECTORY_FOLDER."/".ALIAS_BLOG_MODULE."/", "", $_GET["url_full"]);
            $parts = explode("/", $url);
            $tempParts = array();

            for($i = 0; $i < count($parts); $i++)
            {
                if($parts[$i] != 'page' && $parts[$i] != 'letter' && $parts[$i] != 'orderby' && $parts[$i] != ''){
                    $tempParts[] = $parts[$i];
                }else{
                    $i = $i+1;
                }
            }
            $countTempParts = count($tempParts);
            if($tempParts){
                if($countTempParts == 1){
                    $sql = "SELECT id FROM BlogCategory WHERE friendly_url = '" . $tempParts[0] . "' AND enabled = 'y' LIMIT 1";
                    $result = $dbObj->query($sql);
                    if (mysql_num_rows($result) > 0) {
                        $aux = mysql_fetch_assoc($result);
                        $_GET["category_id"] = $aux["id"];
                    }else{
                        $failure = true;
                    }
                }elseif($countTempParts == 2){
                    $sql = "SELECT id FROM BlogCategory WHERE friendly_url = '" . $tempParts[0] . "' AND enabled = 'y' LIMIT 1";
                    $result = $dbObj->query($sql);
                    if (mysql_num_rows($result) > 0) {
                        $aux = mysql_fetch_assoc($result);
                        $_GET["category_id"] = $aux["id"];

                        $sqlLocation = "SELECT id FROM Location_3 WHERE friendly_url LIKE '".$tempParts[1]."'";
                        $dbObj_main = db_getDBObject(DEFAULT_DB, true);
                        $result = $dbObj_main->query($sqlLocation);
                        if (mysql_num_rows($result) > 0) {
                            $row = mysql_fetch_assoc($result);
                            $_GET["location_3"] = $row["id"];
                        }else{
                            unset($_GET["category_id"]);
                            $sql = "SELECT id FROM BlogCategory WHERE friendly_url = '" . $tempParts[1] . "' AND enabled = 'y' LIMIT 1";
                            $result = $dbObj->query($sql);
                            if (mysql_num_rows($result) > 0) {
                                $aux = mysql_fetch_assoc($result);
                                $_GET["category_id"] = $aux["id"];
                            }else{
                                $failure = true;
                            }
                        }
                    }else{
                        $failure = true;
                    }
                }elseif($countTempParts == 3){  
                    $sql = "SELECT id FROM BlogCategory WHERE friendly_url = '" . $tempParts[0] . "' AND enabled = 'y' LIMIT 1";
                    $result = $dbObj->query($sql);
                    if(mysql_num_rows($result) > 0){
                        if (mysql_num_rows($result) > 0) {
                            $aux = mysql_fetch_assoc($result);
                            $_GET["category_id"] = $aux["id"];
                            $sql = "SELECT id FROM BlogCategory WHERE friendly_url = '" . $tempParts[1] . "' AND enabled = 'y' LIMIT 1";
                            $result = $dbObj->query($sql);
                            if (mysql_num_rows($result) > 0) {
                                $aux = mysql_fetch_assoc($result);
                                $_GET["category_id"] = $aux["id"];
                                $sqlLocation = "SELECT id FROM Location_3 WHERE friendly_url LIKE '".$tempParts[2]."'";
                                $dbObj_main = db_getDBObject(DEFAULT_DB, true);
                                $result = $dbObj_main->query($sqlLocation);
                                if (mysql_num_rows($result) > 0) {
                                    $row = mysql_fetch_assoc($result);
                                    $_GET["location_3"] = $row["id"];
                                }else{
                                    $failure = true;
                                }
                            }else{
                                $failure = true;
                            }
                        }
                    }else{
                        $failure = true;
                    }
                }else{
                    $failure = true;
                }


                for ($i = $countTempParts; $i < count($parts); $i++) {
                    switch ($parts[$i]) {
                        case 'page': $_GET["page"] = $parts[$i + 1];
                            break;
                        case 'letter': $_GET["letter"] = $parts[$i + 1];
                            break;
                        case 'orderby': $_GET["orderby"] = $parts[$i + 1];
                            break;
                    }
                }
            }
        }
    
	if ($browsebycategory || $browsebydate){
		for ($i=1; $i < count($parts); $i++){
			$aux = $_GET["category".$i];
			if ($aux == "page"){
				$_GET["category".$i] = "";
				$_GET["page"] = $_GET["category".($i+1)];
				$_GET["category".($i+1)] = "";
				$i++;
			} else if ($aux == "letter"){
				$_GET["category".$i] = "";
				$_GET["letter"] = $_GET["category".($i+1)];
				$_GET["category".($i+1)] = "";
				$i++;
			} else if ($aux == "orderby"){
				$_GET["category".$i] = "";
				$_GET["orderby"] = $_GET["category".($i+1)];
				$_GET["category".($i+1)] = "";
				$i++;
			} else if ($aux == "year"){
				$_GET["category".$i] = "";
				$_GET["archive_year"] = $_GET["category".($i+1)];
				$_GET["category".($i+1)] = "";
				$i++;
			} else if ($aux == "month"){
				$_GET["category".$i] = "";
				$_GET["archive_month"] = $_GET["category".($i+1)];
				$_GET["category".($i+1)] = "";
				$i++;
			}
		}
	}

	##################################################
	# CATEGORY
	##################################################
	if ($_GET["category1"]) {
		$sql = "SELECT * FROM BlogCategory WHERE category_id = ".db_formatNumber("0")." AND friendly_url = ".db_formatString($_GET["category1"])." LIMIT 1";
		$result = $dbObj->query($sql);
		$aux = mysql_fetch_assoc($result);
		$_GET["category_id"] = $aux["id"];
		if (!$_GET["category_id"]) $failure = true;
	}
    
    if ($_GET["category2"] && $_GET["category_id"] && !$failure) {
        $sql = "SELECT * FROM BlogCategory WHERE category_id = ".db_formatNumber($_GET["category_id"])." AND friendly_url = ".db_formatString($_GET["category2"])." LIMIT 1";
        $result = $dbObj->query($sql);
        $aux = mysql_fetch_assoc($result);
        $_GET["category_id"] = $aux["id"];
        if (!$_GET["category_id"]) $failure = true;
    } elseif ($_GET["category2"]) {
        $failure = true;
    }

    if ($_GET["category3"] && $_GET["category_id"] && !$failure) {
        $sql = "SELECT * FROM BlogCategory WHERE category_id = ".db_formatNumber($_GET["category_id"])." AND friendly_url = ".db_formatString($_GET["category3"])." LIMIT 1";
        $result = $dbObj->query($sql);
        $aux = mysql_fetch_assoc($result);
        $_GET["category_id"] = $aux["id"];
        if (!$_GET["category_id"]) $failure = true;
    } elseif ($_GET["category3"]) {
        $failure = true;
    }

    if ($_GET["category4"] && $_GET["category_id"] && !$failure) {
        $browsebycategory = true;
        $sql = "SELECT * FROM BlogCategory WHERE category_id = ".db_formatNumber($_GET["category_id"])." AND friendly_url = ".db_formatString($_GET["category4"])." LIMIT 1";
        $result = $dbObj->query($sql);
        $aux = mysql_fetch_assoc($result);
        $_GET["category_id"] = $aux["id"];
        if (!$_GET["category_id"]) $failure = true;
    } elseif ($_GET["category4"]) {
        $failure = true;
    }

    if ($_GET["category5"] && $_GET["category_id"] && !$failure) {
        $sql = "SELECT * FROM BlogCategory WHERE category_id = ".db_formatNumber($_GET["category_id"])." AND friendly_url = ".db_formatString($_GET["category5"])." LIMIT 1";
        $result = $dbObj->query($sql);
        $aux = mysql_fetch_assoc($result);
        $_GET["category_id"] = $aux["id"];
        if (!$_GET["category_id"]) $failure = true;
    } elseif ($_GET["category5"]) {
        $failure = true;
    }

	##################################################

	##################################################
	# BLOG
	##################################################
	if (string_strpos($aux_array_url[$searchPos_2], ".html") !== false) {
        $post_url = string_replace_once(".html", "", $aux_array_url[$searchPos_2]);
		$browsebyitem = true;
		$sql = "SELECT Post.id as id FROM Post WHERE Post.friendly_url = ".db_formatString($post_url)." LIMIT 1";
		$result = $dbObj->query($sql);
		$aux = mysql_fetch_assoc($result);
		$_GET["id"] = $aux["id"];
		if (!$_GET["id"]) $failure = true;
	} elseif ($_GET["post"]) {
		$browsebyitem = true;
		$sql = "SELECT Post.id as id FROM Post WHERE Post.friendly_url = ".db_formatString($_GET["post"])." LIMIT 1";
		$result = $dbObj->query($sql);
		$aux = mysql_fetch_assoc($result);
		$_GET["id"] = $aux["id"];
		if (!$_GET["id"]) $failure = true;
	}
	##################################################


	##################################################
	# UNSETTING MODREWRITE TERMS
	##################################################
	if ($failure) {
		header("Location: ".BLOG_DEFAULT_URL."/");
		exit;
	} else {
		unset($failure);
		unset($dbObj);
		unset($sql);
		unset($result);
		unset($aux);
		unset($_GET["category1"]);
        unset($_GET["category2"]);
        unset($_GET["category3"]);
        unset($_GET["category4"]);
		unset($_GET["category5"]);
		unset($_GET["blog"]);
	}
	##################################################
?>