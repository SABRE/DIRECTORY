<?php

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
	# * FILE: /functions/system_funct.php
	# ----------------------------------------------------------------------------------------------------

	function system_showPre($array, $label="") {
		echo "<pre>$label: ";
		var_dump($array);
		echo "</pre>";
	}

	function system_mail($to, $subject, $message, $from, $content_type = "text/plain", $cc = "", $bcc = "", &$error, $attachPath = "", $attachName = "", $reply = "") {
		$eDirMailerObj = new EDirMailer($to, $subject, $message, $from, $reply);
		$eDirMailerObj->SMTPKeepAlive = true;
		if ($content_type) $eDirMailerObj->setContentType($content_type);
		if ($cc) $eDirMailerObj->setCC($cc);
		if ($bcc) $eDirMailerObj->setBCC($bcc);
        if ($attachPath && $attachName) $eDirMailerObj->setAttachment($attachPath, $attachName);
		if (!$eDirMailerObj->send()) {
			$error = $eDirMailerObj->msgerror;
			return false;
		}
		return true;
	}

	function system_generatePassword() {
		$string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		srand((double)microtime()*1000000);
		for ($i=0; $i < 8; $i++) {
			$num   = rand() % string_strlen($string);
			$tmp   = string_substr($string, $num, 1);
			$pass .= $tmp;
		}
		return $pass;
	}

	function system_generateFileName() {
		$string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		srand((double)microtime()*1000000);
		for ($i=0; $i < 20; $i++) {
			$num = rand() % string_strlen($string);
			$tmp = string_substr($string, $num, 1);
			$name .= $tmp;
		}
		return $name;
	}

	function system_sendPassword($id, $emailTO, $username, $password, $name) {

		if ($emailNotificationObj = system_checkEmail($id)) {

            if (!$password) {
                $password = system_showText(LANG_PASSWORD_NOT_CHANGED);
            }
			setting_get("sitemgr_email", $sitemgr_email);
			$sitemgr_emails = explode(",", $sitemgr_email);

			if ($sitemgr_emails[0]) $sitemgr_email = $sitemgr_emails[0];

			$subject = $emailNotificationObj->getString("subject");
			$body    = $emailNotificationObj->getString("body");

			$subject = str_replace("ACCOUNT_NAME",     $name,            $subject);
			$subject = str_replace("ACCOUNT_USERNAME", $username,        $subject);
			$subject = str_replace("ACCOUNT_PASSWORD", $password,        $subject);
			$subject = str_replace("DEFAULT_URL",      DEFAULT_URL,      $subject);
			$subject = str_replace("SITEMGR_EMAIL",    $sitemgr_email,   $subject);
			$subject = str_replace("EDIRECTORY_TITLE", EDIRECTORY_TITLE, $subject);

			$body    = str_replace("ACCOUNT_NAME",     $name,            $body);
			$body    = str_replace("ACCOUNT_USERNAME", $username,        $body);
			$body    = str_replace("ACCOUNT_PASSWORD", $password,        $body);
			$body    = str_replace("DEFAULT_URL",      DEFAULT_URL,      $body);
			$body    = str_replace("SITEMGR_EMAIL",    $sitemgr_email,   $body);
			$body    = str_replace("EDIRECTORY_TITLE", EDIRECTORY_TITLE, $body);

			$body = html_entity_decode($body);
			$subject = html_entity_decode($subject);

			$error = false;
			system_mail($emailTO, $subject, $body, EDIRECTORY_TITLE." <$sitemgr_email>", $emailNotificationObj->getString("content_type"), "", $emailNotificationObj->getString("bcc"), $error);

		}

	}

	/**
	* Verify if email is Enabled or Disabled
	********************************************************************/
	function system_checkEmail($id) {
		$email = new EmailNotification($id);
		if ($email->getString("deactivate")) {
			return false;
		} else {
			return $email;
		}
	}

	/**
	* Replace the variables in the email body
	********************************************************************/
	function system_replaceEmailVariables($body, $id, $item="listing") {

		switch ($item) {
			case 'banner': $obj = new Banner($id); break;
			case 'classified': $obj = new Classified($id); break;
			case 'article': $obj = new Article($id); break;
			case 'event': $obj = new Event($id); break;
			case 'listing': $obj = new Listing($id); break;
			case 'promotion': $obj = new Promotion($id); break;
			case 'account': $acc = new Account($id);
            case 'post': $obj = new Post($id);
		}

		if (!isset($acc)) $acc = new Account($obj->getNumber('account_id'));
		$acc_cont = new Contact($acc->getNumber('id'));

		setting_get("sitemgr_email", $sitemgr_email);
		$sitemgr_emails = explode(",", $sitemgr_email);

		if ($sitemgr_emails[0]) $sitemgr_email = $sitemgr_emails[0];

		$body = str_replace("ACCOUNT_NAME",$acc_cont->getString('first_name').' '.$acc_cont->getString('last_name'),$body);
		$body = str_replace("ACCOUNT_USERNAME",$acc->getString('username'),$body);
		$body = str_replace("ACCOUNT_PASSWORD",$acc->getString('username'),$body);

		switch ($item) {
			case 'banner':
				$body = str_replace(array("ITEM_TITLE", "BANNER_TITLE"), $obj->getString('caption'), $body);
			break;
			case 'classified':
                $levelObj = new ClassifiedLevel();
                if ($levelObj->getDetail($obj->getString('level')) == "y") {
                    $detailLink = "".CLASSIFIED_DEFAULT_URL."/".$obj->getString("friendly_url").".html"; 
                } else {
                    $detailLink = CLASSIFIED_DEFAULT_URL."/results.php?id=".$obj->getString("id");
                }
				$body = str_replace(array("ITEM_TITLE", "CLASSIFIED_TITLE"), $obj->getString('title'), $body);
			break;
			case 'article':
				$detailLink = "".ARTICLE_DEFAULT_URL."/".$obj->getString("friendly_url").".html";
				$body = str_replace(array("ITEM_TITLE", "ARTICLE_TITLE"), $obj->getString('title'), $body);
			break;
			case 'event':
                $levelObj = new EventLevel();
                if ($levelObj->getDetail($obj->getString('level')) == "y") {
				    $detailLink = "".EVENT_DEFAULT_URL."/".$obj->getString("friendly_url").".html";
                } else {
                    $detailLink = EVENT_DEFAULT_URL."/results.php?id=".$obj->getString("id");
                }
				$body = str_replace(array("ITEM_TITLE", "EVENT_TITLE"), $obj->getString('title'), $body);
			break;
			case 'listing':
                $levelObj = new ListingLevel();
                if ($levelObj->getDetail($obj->getString('level')) == "y") {
				    $detailLink = "".LISTING_DEFAULT_URL."/".$obj->getString("friendly_url").".html";
                } else {
                    $detailLink = LISTING_DEFAULT_URL."/results.php?id=".$obj->getString("id");
                }
				$body = str_replace(array("ITEM_TITLE", "LISTING_TITLE"), $obj->getString('title'), $body);
			break;
			case 'promotion':
				$detailLink = "".PROMOTION_DEFAULT_URL."/results.php?id=".$obj->getNumber("id");
				$body = str_replace(array("ITEM_TITLE"), $obj->getString('name'), $body);
			break;
            case 'post':
				$detailLink = "".BLOG_DEFAULT_URL."/".$obj->getString("friendly_url").".html";
				$body = str_replace(array("ITEM_TITLE", "BLOG_TITLE"), $obj->getString('title'), $body);
            break;
		}

		if (isset($detailLink)) $body = str_replace("ITEM_URL", $detailLink, $body);

		$body = str_replace("ITEM_TYPE", $item, $body);

		$body = str_replace("ARTICLE_DEFAULT_URL",ARTICLE_DEFAULT_URL,$body);
		$body = str_replace("CLASSIFIED_DEFAULT_URL",CLASSIFIED_DEFAULT_URL,$body);
		$body = str_replace("EVENT_DEFAULT_URL",EVENT_DEFAULT_URL,$body);
		$body = str_replace("LISTING_DEFAULT_URL",LISTING_DEFAULT_URL,$body);

		$body = str_replace("EDIRECTORY_TITLE",EDIRECTORY_TITLE,$body);
		$body = str_replace("SITEMGR_EMAIL",$sitemgr_email,$body);
		$body = str_replace("DEFAULT_URL",NON_SECURE_URL,$body);

		return $body;

	}
    
    function system_notifySitemgr($sitemgr_notif_emails, $emailSubject, $emailContent, $addHTML = true) {
        
        setting_get("sitemgr_send_email", $sitemgr_send_email);
        setting_get("sitemgr_email", $sitemgr_email);
        $sitemgr_emails = explode(",", $sitemgr_email);
        
        if ($addHTML) {
            $emailContent = "
                    <html>
                        <head>
                            <style>
                                .email_style_settings{
                                    font-size:12px;
                                    font-family:Verdana, Arial, Sans-Serif;
                                    color:#000;
                                }
                            </style>
                        </head>
                        <body>
                            <div class=\"email_style_settings\">
                            $emailContent
                            </div>
                        </body>
                    </html>";
        }
        
        if ($sitemgr_send_email == "on") {
            if ($sitemgr_emails[0]) {
                foreach ($sitemgr_emails as $sitemgr_email) {
                    system_mail($sitemgr_email, $emailSubject, $emailContent, EDIRECTORY_TITLE." <$sitemgr_email>", "text/html", '', '', $error);
                }
            }
        }

        if ($sitemgr_notif_emails[0]) {
            foreach ($sitemgr_notif_emails as $sitemgr_notif_email) {
                system_mail($sitemgr_notif_email, $emailSubject, $emailContent, EDIRECTORY_TITLE." <$sitemgr_notif_email>", "text/html", '', '', $error);
            }
        }

    }

	function endKey($array){
		end($array);
		return key($array);
	}

	/**
	* This function is used by system_generateCategoryTreeRecursiveSort to help on the category ordering.
	********************************************************************/
	function system_generateCategoryTreeRecursiveSort($dad_id, $item, &$new_arr, &$ordered){
		for($j=0; $j < count($new_arr); $j++){
			if($new_arr[$j]["dad"] == $dad_id){
				$x = count($ordered);
				$ordered[$x]["id"] = $new_arr[$j]["id"];
				$ordered[$x]["dad"] = $new_arr[$j]["dad"];
				$ordered[$x]["title"] = $new_arr[$j]["title"];
				$ordered[$x]["active_".$item] = $new_arr[$j]["active_".$item];
				$ordered[$x++]["level"] = $new_arr[$j]["level"];
				system_generateCategoryTreeRecursiveSort($new_arr[$j]["id"], $item, $new_arr, $ordered);
			}
		}
	}

	/**
	* This function is used to generate a category tree based on 2 terms which are arrays.
	* It is also using styles from this project.
	* The first array contains the selected categories
	* The second array is generated by method getFullPath in Category class
	********************************************************************/
	function system_generateCategoryTree($categories_obj_arr, $arr_full_path, $item, $user=false) {

		$item_aux = "";
		if ($item == "promotion") {
			$item_aux = $item;
			$item = "listing";
		}

		$x=0; $y=0;

		for ($i=0; $i < count($arr_full_path); $i++) {

			for ($j=0; $j < count($arr_full_path[$i]); $j++) {

				if ($arr_full_path[$i][$j]["dad"] == 0) {

					$repeated = false;

					if ($dad_arr) {
						foreach ($dad_arr as $each_dad) {
							if ($each_dad["id"] == $arr_full_path[$i][$j]["id"]) {
								$repeated = true;
							}
						}
					}

					if (!$repeated) {

						if ($arr_full_path[$i][$j]["enabled"] == "y") {
							$dad_arr[$y]["id"] = $arr_full_path[$i][$j]["id"];
							$dad_arr[$y]["dad"] = $arr_full_path[$i][$j]["dad"];
							$dad_arr[$y]["title"] = $arr_full_path[$i][$j]["title"];
							$dad_arr[$y]["active_".$item] = $arr_full_path[$i][$j]["active_".($item == "blog" ? "post" : $item)];
							$dad_arr[$y++]["level"] = $arr_full_path[$i][$j]["level"];
						}
					}

				} else {

					$repeated = false;

					if ($new_arr) {
						foreach ($new_arr as $each_cat) {
							if ($each_cat["id"] == $arr_full_path[$i][$j]["id"]) {
								$repeated = true;
							}
						}
					}

					if (!$repeated) {

						if ($arr_full_path[$i][$j]["enabled"] == "y") {
							$new_arr[$x]["id"] = $arr_full_path[$i][$j]["id"];
							$new_arr[$x]["dad"] = $arr_full_path[$i][$j]["dad"];
							$new_arr[$x]["title"] = $arr_full_path[$i][$j]["title"];
							$new_arr[$x]["active_".$item] = $arr_full_path[$i][$j]["active_".($item == "blog" ? "post" : $item)];
							$new_arr[$x++]["level"] = $arr_full_path[$i][$j]["level"];
						}
					}

				}

			}

		}

		for ($i=0; $i < count($dad_arr); $i++) {

			$x = count($ordered);

			$ordered[$x]["id"] = $dad_arr[$i]["id"];
			$ordered[$x]["dad"] = $dad_arr[$i]["dad"];
			$ordered[$x]["title"] = $dad_arr[$i]["title"];
			$ordered[$x]["active_".$item] = $dad_arr[$i]["active_".$item];
			$ordered[$x++]["level"] = $dad_arr[$i]["level"];

			$dad_id = $dad_arr[$i]["id"];

			system_generateCategoryTreeRecursiveSort($dad_id, $item, $new_arr, $ordered);

		}

		for ($i=0; $i < count($ordered); $i++) {

			if ($item == "listing") $catObj = new ListingCategory($ordered[$i]["id"]);
			elseif ($item == "event") $catObj = new EventCategory($ordered[$i]["id"]);
			elseif ($item == "classified") $catObj = new ClassifiedCategory($ordered[$i]["id"]);
			elseif ($item == "article") $catObj = new ArticleCategory($ordered[$i]["id"]);
			elseif ($item == "blog") $catObj = new BlogCategory($ordered[$i]["id"]);
			$path_elem_arr = $catObj->getFullPath();

            if ($item_aux) {
                $href = "".constant(string_strtoupper($item_aux)."_DEFAULT_URL")."/".ALIAS_CATEGORY_URL_DIVISOR;
            } else {
                $href = "".constant(string_strtoupper($item)."_DEFAULT_URL")."/".ALIAS_CATEGORY_URL_DIVISOR;
            }

            if ($path_elem_arr) {
                foreach ($path_elem_arr as $each_category_node) {
                    $href .= "/".$each_category_node["friendly_url"];
                }
            }

			if ($user) {
				$linked_titles[] = "<li class=\"level-".$ordered[$i]["level"]."\"><a href=\"".$href."\">".string_htmlentities($ordered[$i]["title"]).(((!$item_aux) && (SHOW_CATEGORY_COUNT == "on"))?(" <span>(".$ordered[$i]["active_".$item].")</span>"):(""))."</a></li>";
			} else {
				$linked_titles[] = "<li class=\"level-".$ordered[$i]["level"]."\"><a href=\"javascript: void(0);\" style=\"cursor:default\">".string_htmlentities($ordered[$i]["title"]).(((!$item_aux) && (SHOW_CATEGORY_COUNT == "on"))?(" <span>(".$ordered[$i]["active_".$item].")</span>"):(""))."</a></li>";
			}

		}

		if(is_array($linked_titles)){
			$category_tree = "<ul class=\"list list-category\">".implode("", $linked_titles)."</ul>";
			return($category_tree);
		}else{
			return false;
		}


	}

	function system_generateAjaxAccountSearch($acct_search_table_title = LANG_SITEMGR_ACCOUNTSEARCH_SELECT_DEFAULT, $acct_search_field_name = "account_id", $acct_search_field_value = false, $acct_search_required_mark = false, $acct_search_form_width = "100%", $acct_search_cell_width = "105px", $custom = 0, $extra = false){

		if ($extra){
			$extraId = 2;
		} else {
			$extraId = "";
		}
		system_showTruncatedText($acct_search_field_value, 10);
		$form_html = "
				<div id=\"table_accounts_search$extraId\" style=\"display: none; width: ".$acct_search_form_width."\" class=\"table_accounts_search\">

					<input type=\"hidden\" name=\"acct_search_field_name$extraId\" id=\"acct_search_field_name$extraId\" value=\"".$acct_search_field_name."\" />

					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"searchAccount\">
						<tr>
							<th colspan=\"2\" class=\"searchAccountTitleAccount\">".$acct_search_table_title."</span></th>
						</tr>
						<tr>
							<th>".system_showText(LANG_SITEMGR_LABEL_COMPANY).": </th>
							<td>
								<input type=\"text\" id=\"acct_search_company$extraId\" style=\"width:250px\" name=\"acct_search_company$extraId\" value=\"\" OnKeyPress=\"if(event.keyCode == 13) { searchAccount(this.form, '".DEFAULT_URL."', $custom, ".($extraId ? $extraId : "0")."); }\" />
							</td>
						</tr>
						<tr>
							<th class=\"first_line\" style=\"padding-top: 10px\">".system_showText(LANG_SITEMGR_LABEL_USERNAME).": </th>
							<td style=\"padding-top: 10px\">
								<input type=\"text\" id=\"acct_search_username$extraId\" style=\"width:250px\" name=\"acct_search_username$extraId\" value=\"\" OnKeyPress=\"if(event.keyCode == 13) { searchAccount(this.form, '".DEFAULT_URL."', $custom, ".($extraId ? $extraId : "0")."); }\" />
							</td>
						</tr>
						<tr>
							<td colspan=\"2\" style=\"text-align: center; padding-bottom: 5px;\">
								<input style=\"width:80px\" class=\"input-button-form\" type=\"button\" name=\"acct_search_btn$extraId\" id=\"acct_search_btn$extraId\" value=\"".system_showText(LANG_SITEMGR_SEARCH)."\" onclick=\"searchAccount(this.form, '".DEFAULT_URL."', $custom, ".($extraId ? $extraId : "0").");\" />
								<input style=\"width:80px\" class=\"input-button-form\" type=\"button\" name=\"acct_reset_btn$extraId\" id=\"acct_reset_btn$extraId\" value=\"".system_showText(LANG_SITEMGR_CLEAR)."\" onclick=\"resetSearchAccount(".($extraId ? $extraId : "0").");\" />
								<input style=\"width:80px\" class=\"input-button-form\" type=\"button\" name=\"acct_cancel_btn$extraId\" id=\"acct_reset_btn$extraId\" value=\"".system_showText(LANG_SITEMGR_CANCEL)."\" onclick=\"cancelSearchAccount(".($extraId ? $extraId : "0").");\" />
								<input style=\"width:80px\" class=\"input-button-form\" type=\"button\" name=\"acct_empty_btn$extraId\" id=\"acct_empty_btn$extraId\" value=\"".system_showText(LANG_SITEMGR_ACCOUNTSEARCH_EMPTY)."\" onclick=\"emptySearchAccount(".($extraId ? $extraId : "0").");\" />
							</td>
						</tr>
                      	<tr>
							<td colspan=\"2\" style=\"padding: 0 10px 10px 10px;\">
								".(SOCIALNETWORK_FEATURE == 'on'?"<p class=\"informationMessage\">".system_showText(LANG_SITEMGR_MSG_YOUCANONLYSELECTSPONSO)."</p>":"")."
                                <div id=\"accounts_search$extraId\" class=\"div-accounts_search-form-listing accounts_search\"></div>
								<div id=\"accounts_search_loading$extraId\" class=\"div-accounts_search_loading-form-listing accounts_search_loading\">".system_showText(LANG_SITEMGR_WAITLOADING)."</div>
							</td>
						</tr>
					</table>

				</div>

				<div id=\"table_accounts$extraId\" class=\"table_accounts\">

					<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"standard-table\">
						<tr>
							<th>".((!$acct_search_required_mark) ? "" : "*")." ".system_showText(LANG_SITEMGR_LABEL_ACCOUNT).":</th>
							<td id=\"selected_account$extraId\" class\"selected_account\">";
		if($acct_search_field_value) {
			$accountObj = new Account($acct_search_field_value);
			$contactObj = new Contact($acct_search_field_value);
			$account = $accountObj->getString("username", true);
			$form_html .= "			<a style=\"vertical-align: top\" href='javascript:changeAccount(".($extraId ? $extraId : "0").")'><strong>".system_showAccountUserName($account)."</strong></a>";
			$form_html .= "			<input type=\"hidden\" id=\"".$acct_search_field_name."\" name=\"".$acct_search_field_name."\" value=\"".$acct_search_field_value."\" />";
		} else {
			$form_html .= "			<a style=\"vertical-align: middle\" href='javascript:changeAccount(".($extraId ? $extraId : "0").")' id=\"change_account_search$extraId\"><strong>".system_showText(LANG_SITEMGR_ACCOUNTSEARCH_CLICKHERE)."</strong></a>";
		}
		$form_html .= "
							</td>
						</tr>
					</table>

				</div>";

		return $form_html;
	}

	function getTreePath($catID, $section) {
		$strRet = "";
		$dbObj = db_getDBObject();
		if ($section == "listing") $sql = "SELECT category_id FROM ListingCategory WHERE id = ".$catID."";
		else $sql = "SELECT category_id FROM ".string_ucwords($section)."Category WHERE id = ".$catID."";
		$result = $dbObj->query($sql);
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				$strRet .= getTreePath($row["category_id"], $section);
			}
		}
		if ($catID) $strRet .= ",".$catID;
		return $strRet;
	}

	function getSubTree($catID, $section) {
		$strRet = "";
		$dbObj = db_getDBObject();
		if ($section == "listing") $sql = "SELECT id FROM ListingCategory WHERE category_id = ".db_formatNumber($catID)."";
		else $sql = "SELECT id FROM ".string_ucwords($section)."Category WHERE category_id = ".db_formatNumber($catID)."";
		$result = $dbObj->query($sql);
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				$strRet .= getSubTree($row["id"], $section);
			}
		}
		$strRet .= ",".$catID;
		return $strRet;
	}
    
    function system_retrieveAllCategoriesXML($table = "ListingCategory", $featured = "", $category_id = 0, $fields = false){
        
        $sql = "SELECT ".($fields ? implode(",", $fields) : "*")." FROM $table WHERE category_id = ".db_formatNUmber($category_id)."";

        if ($featured == "on"){
            $sql .= " AND featured = 'y'";
        }

        $sql .= " AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		
        return system_generateXML("categories", $sql, SELECTED_DOMAIN_ID);
    }
    
    function system_getAllCategoriesHierarchyXML($table = "ListingCategory", $featured = "", $category_id = 0, $id = 0, $domain_id = false){
        
        if ($table == "ListingCategory"){
            
            $sql = "SELECT 
						ListingCategory_1.id,
						ListingCategory_1.title,
						ListingCategory_1.page_title,
						ListingCategory_1.friendly_url,
						ListingCategory_1.category_id,
						ListingCategory_1.root_id,
						ListingCategory_1.left,
						ListingCategory_1.active_listing,
						ListingCategory_1.enabled,
						(	SELECT COUNT(ListingCategory_2.id)
							FROM
								ListingCategory ListingCategory_2
							WHERE ListingCategory_2.left < ListingCategory_1.left
							AND ListingCategory_2.right > ListingCategory_1.right
							AND ListingCategory_2.root_id = ListingCategory_1.root_id
						) level,
						(	SELECT
								COUNT(DISTINCT category_id) as max_sublevel
							FROM
								ListingCategory
							WHERE category_id IN (ListingCategory_1.id)
							AND id != ListingCategory_1.id
							AND title <> ''
                            AND enabled = 'y'
						) children
						FROM
							ListingCategory ListingCategory_1
						WHERE ListingCategory_1.root_id > 0
					";
					
			$sql .= " AND ListingCategory_1.category_id = ".$category_id;
			
			if ($id) {
				$sql .= " AND ListingCategory_1.id IN (".$id.")";
			}
			if ($featured == "on") {
				$sql .= " AND ListingCategory_1.featured = 'y'";
			}

			$sql .= " AND ListingCategory_1.title <> '' AND ListingCategory_1.enabled = 'y'";
			
			$sql .= " ORDER BY ListingCategory_1.title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
            
        } else {
            
            $sql = "SELECT * FROM $table WHERE category_id = $category_id";
			if ($featured == "on") $sql .= " AND featured = 'y'";
			$sql .= "  AND enabled = 'y' ORDER BY title";
        }
        
        return system_generateXML("categories", $sql, SELECTED_DOMAIN_ID);
        
    }

	function system_getListingStatus($force_count = false,$domain_id = SELECTED_DOMAIN_ID) {

		$status = array();

		$dbObj = db_getDBObJect(DEFAULT_DB,true);
		$dbObjSecond = db_getDBObjectByDomainID($domain_id,$dbObj);

		if (LISTING_SCALABILITY_OPTIMIZATION == "on" && !$force_count) {

			$sql = "SELECT * FROM ItemStatistic WHERE name LIKE 'l_%'";
			$r = $dbObjSecond->query($sql);
			if ($r) {
				while ($row = mysql_fetch_assoc($r)) {
					if ($row["value"] > 0) {
						$status[$row["name"]] = $row["value"];
					} else {
						$status[$row["name"]] = $row["value"];
					}
				}
			}

		} else {

			$sql = "SELECT COUNT(id) AS total FROM Listing_Summary WHERE status = ".db_formatString("P")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["l_pending"] = (int)$row["total"];

			if (!$force_count) {
				$sql = "SELECT COUNT(id) AS total FROM Listing_Summary WHERE renewal_date > NOW() AND renewal_date <= DATE_ADD(NOW(), INTERVAL ".DEFAULT_LISTING_DAYS_TO_EXPIRE." DAY)";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["l_expiring"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Listing_Summary WHERE status = ".db_formatString("E")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["l_expired"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Listing_Summary WHERE status = ".db_formatString("A")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["l_active"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Listing_Summary WHERE status = ".db_formatString("S")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["l_suspended"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total from Listing_Summary WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["l_added30"] = (int)$row["total"];
			}
		}

		unset($dbObj);
		unset($dbObjSecond);

		return $status;

	}

	function system_getEventStatus($force_count = false) {

		$status = array();

		$dbObj = db_getDBObJect(DEFAULT_DB,true);
		$dbObjSecond = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID,$dbObj);

		if (EVENT_SCALABILITY_OPTIMIZATION == "on" && !$force_count) {

			$sql = "SELECT * FROM ItemStatistic WHERE name LIKE 'e_%'";
			$r = $dbObjSecond->query($sql);
			if ($r) {
				while ($row = mysql_fetch_assoc($r)) {
					if ($row["value"] > 0) {
						$status[$row["name"]] = $row["value"];
					} else {
						$status[$row["name"]] = $row["value"];//
					}
				}
			}
		} else {

			$sql = "SELECT COUNT(id) AS total FROM Event WHERE status = ".db_formatString("P")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["e_pending"] = (int)$row["total"];

			if (!$force_count) {
				$sql = "SELECT COUNT(id) AS total FROM Event WHERE renewal_date > NOW() AND renewal_date <= DATE_ADD(NOW(), INTERVAL ".DEFAULT_EVENT_DAYS_TO_EXPIRE." DAY)";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["e_expiring"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Event WHERE status = ".db_formatString("E")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["e_expired"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Event WHERE status = ".db_formatString("A")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["e_active"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Event WHERE status = ".db_formatString("S")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["e_suspended"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total from Event WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["e_added30"] = (int)$row["total"];
			}

		}

		unset($dbObj);
		unset($dbObjSecond);

		return $status;

	}

	function system_getBannerStatus($force_count = false) {

		$status = array();

		$dbObj = db_getDBObJect(DEFAULT_DB,true);
		$dbObjSecond = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID,$dbObj);

		if (BANNER_SCALABILITY_OPTIMIZATION == "on" && !$force_count) {

			$sql = "SELECT * FROM ItemStatistic WHERE name LIKE 'b_%'";
			$r = $dbObjSecond->query($sql);
			if ($r) {
				while ($row = mysql_fetch_assoc($r)) {
					if ($row["value"] > 0) {
						$status[$row["name"]] = $row["value"];
					} else {
						$status[$row["name"]] = $row["value"];
					}
				}
			}

		} else {

			$sql = "SELECT COUNT(id) AS total FROM Banner WHERE status = ".db_formatString("P")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["b_pending"] = (int)$row["total"];

			if (!$force_count) {
				$sql = "SELECT COUNT(id) AS total FROM Banner WHERE status = ".db_formatString("E")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["b_expired"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Banner WHERE status = ".db_formatString("A")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["b_active"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Banner WHERE status = ".db_formatString("S")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["b_suspended"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total from Banner WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["b_added30"] = (int)$row["total"];
			}

		}

		unset($dbObj);
		unset($dbObjSecond);

		return $status;

	}

	function system_getClassifiedStatus($force_count = false) {

		$status = array();

		$dbObj = db_getDBObJect(DEFAULT_DB,true);
		$dbObjSecond = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID,$dbObj);

		if (CLASSIFIED_SCALABILITY_OPTIMIZATION == "on" && !$force_count) {

			$sql = "SELECT * FROM ItemStatistic WHERE name LIKE 'c_%'";
			$r = $dbObjSecond->query($sql);
			if ($r) {
				while ($row = mysql_fetch_assoc($r)) {
					if ($row["value"] > 0) {
						$status[$row["name"]] = $row["value"];
					} else {
						$status[$row["name"]] = $row["value"];
					}
				}
			}

		} else {

			$sql = "SELECT COUNT(id) AS total FROM Classified WHERE status = ".db_formatString("P")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["c_pending"] = (int)$row["total"];

			if (!$force_count) {
				$sql = "SELECT COUNT(id) AS total FROM Classified WHERE renewal_date > NOW() AND renewal_date <= DATE_ADD(NOW(), INTERVAL ".DEFAULT_CLASSIFIED_DAYS_TO_EXPIRE." DAY)";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["c_expiring"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Classified WHERE status = ".db_formatString("E")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["c_expired"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Classified WHERE status = ".db_formatString("A")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["c_active"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Classified WHERE status = ".db_formatString("S")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["c_suspended"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total from Classified WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["c_added30"] = (int)$row["total"];
			}
		}

		unset($dbObj);
		unset($dbObjSecond);

		return $status;

	}

	function system_getArticleStatus($force_count = false) {

		$status = array();

		$dbObj = db_getDBObJect();
		$dbObjSecond = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID,$dbObj);

		if (ARTICLE_SCALABILITY_OPTIMIZATION == "on" && !$force_count) {

			$sql = "SELECT * FROM ItemStatistic WHERE name LIKE 'a_%'";
			$r = $dbObjSecond->query($sql);
			if ($r) {
				while ($row = mysql_fetch_assoc($r)) {
					if ($row["value"] > 0) {
						$status[$row["name"]] = $row["value"];
					} else {
						$status[$row["name"]] = $row["value"];
					}
				}
			}

		} else {

			$sql = "SELECT COUNT(id) AS total FROM Article WHERE status = ".db_formatString("P")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["a_pending"] = (int)$row["total"];

			if (!$force_count) {
				$sql = "SELECT COUNT(id) AS total FROM Article WHERE renewal_date > NOW() AND renewal_date <= DATE_ADD(NOW(), INTERVAL ".DEFAULT_ARTICLE_DAYS_TO_EXPIRE." DAY)";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["a_expiring"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Article WHERE status = ".db_formatString("E")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["a_expired"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Article WHERE status = ".db_formatString("A")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["a_active"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total FROM Article WHERE status = ".db_formatString("S")."";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["a_suspended"] = (int)$row["total"];

				$sql = "SELECT COUNT(id) AS total from Article WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
				$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
				$status["a_added30"] = (int)$row["total"];
			}
		}

		unset($dbObj);
		unset($dbObjSecond);

		return $status;

	}

	function system_getStatus($force_count = false, $domain_id = SELECTED_DOMAIN_ID) {

		$status = array();

		$dbObj = db_getDBObJect(DEFAULT_DB,true);
		$dbObjSecond = db_getDBObjectByDomainID($domain_id,$dbObj);

		// LISTING
		unset($status_aux);
		$status_aux = system_getListingStatus($force_count,$domain_id);
		foreach ($status_aux as $name=>$value) {
			$status[$name] = $value;
		}

		// EVENT
		if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") {
			unset($status_aux);
			$status_aux = system_getEventStatus($force_count);
			foreach ($status_aux as $name=>$value) {
				$status[$name] = $value;
			}
		}

		// BANNER
		if (BANNER_FEATURE == "on" && CUSTOM_BANNER_FEATURE == "on") {
			unset($status_aux);
			$status_aux = system_getBannerStatus($force_count);
			foreach ($status_aux as $name=>$value) {
				$status[$name] = $value;
			}
		}

		// CLASSIFIED
		if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") {
			unset($status_aux);
			$status_aux = system_getClassifiedStatus($force_count);
			foreach ($status_aux as $name=>$value) {
				$status[$name] = $value;
			}
		}

		// ARTICLE
		if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") {
			unset($status_aux);
			$status_aux = system_getArticleStatus($force_count);
			foreach ($status_aux as $name=>$value) {
				$status[$name] = $value;
			}
		}

		// LISTING REVIEW
		$sql = "SELECT COUNT(*) AS total FROM Review WHERE approved = '0' AND item_type = 'listing'";
		$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
		$status["lr_pending"] = (int)$row["total"];

		// PROMOTION REVIEW
		if (PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on") {
			$sql = "SELECT COUNT(*) AS total FROM Review WHERE approved = '0'  AND item_type = 'promotion'";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["pr_pending"] = (int)$row["total"];
		}

		// ARTICLE REVIEW
		if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") {
			$sql = "SELECT COUNT(*) AS total FROM Review WHERE approved = '0'  AND item_type = 'article'";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["ar_pending"] = (int)$row["total"];
		}

		// COMMENT & REPLY
		if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") {
			$sql = "SELECT COUNT(*) AS total FROM Comments WHERE approved = '0'";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["cr_pending"] = (int)$row["total"];
		}

		if (!$force_count) {
			// MONEY
			$sql = "SELECT COUNT(*) AS total, SUM(transaction_amount) AS amount from Payment_Log WHERE transaction_datetime >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y")))."'";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["payment_amount"] = (float)$row["amount"];

			// INVOICE
			$sql = "SELECT COUNT(*) AS total, SUM(amount) AS amount FROM Invoice WHERE status = 'R' AND payment_date >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y")))."'";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["invoice_amount"] = (float)$row["amount"];

			$sql = "SELECT COUNT(*) AS total from Invoice WHERE status = ".db_formatString("P")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["i_pending"] = (int)$row["total"];

			$sql = "SELECT COUNT(*) AS total FROM Invoice WHERE expire_date > NOW() AND expire_date <= DATE_ADD(NOW(), INTERVAL 5 DAY) AND status = 'P'";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["i_expiring"] = (int)$row["total"];

			$sql = "SELECT COUNT(*) AS total from Invoice WHERE status = ".db_formatString("E")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["i_expired"] = (int)$row["total"];

			$sql = "SELECT COUNT(*) AS total from Invoice WHERE status = ".db_formatString("R")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["i_received"] = (int)$row["total"];

			$sql = "SELECT COUNT(*) AS total from Invoice WHERE status = ".db_formatString("S")."";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["i_suspended"] = (int)$row["total"];
		}

		// CUSTOM INVOICE
		if (PAYMENT_FEATURE == "on") {
			if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) {
				if (CUSTOM_INVOICE_FEATURE == "on") {
					$sql = "SELECT COUNT(*) AS total From CustomInvoice WHERE paid ='y'";
					$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
					$status["custominvoice_paid"] = (int)$row["total"];

					$sql = "SELECT COUNT(*) AS total From CustomInvoice WHERE paid !='y' AND sent!='y' AND completed='y'";
					$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
					$status["custominvoice_pending"] = (int)$row["total"];

					$sql = "SELECT COUNT(*) AS total From CustomInvoice WHERE paid !='y' AND sent='y' AND completed='y'";
					$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
					$status["custominvoice_sent"] = (int)$row["total"];
				}
			}
		}

		// CLAIM
		if (CLAIM_FEATURE) {
			$sql = "SELECT COUNT(*) AS total FROM Claim WHERE status = ".db_formatString("complete")." AND account_id > 0 AND listing_id > 0";
			$r = $dbObjSecond->query($sql); $row = mysql_fetch_assoc($r);
			$status["claim_complete"] = (int)$row["total"];
		}

		unset($dbObj);
		unset($dbObjSecond);

		return $status;

	}

	function system_countActiveListingByCategory($listingID = "", $category_id = false, $domain_id = false) {
		if (is_numeric($category_id) && $category_id > 0) {
			$listingCatObj = new ListingCategory();
			$listingCatObj->countActiveListingByCategory($category_id, $domain_id);
		} else {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id) {
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			} else {
				if (defined("SELECTED_DOMAIN_ID")) {
					$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$dbObj = db_getDBObject();
				}
				unset($dbMain);
			}

			if (is_numeric($listingID) && $listingID > 0) {
				$sqlCat = "	SELECT LC.`root_id` AS `category_id`
							FROM `ListingCategory` LC
							LEFT JOIN `Listing_Category` L_C ON (L_C.`category_id` = LC.`id`)
							WHERE L_C.`listing_id` = $listingID";
			} else {
				$sqlCat = "SELECT `id` AS `category_id` FROM `ListingCategory` WHERE `category_id` = 0";
			}
			$resCat = $dbObj->Query($sqlCat);
			if (mysql_num_rows($resCat) > 0) {
				$listingCatObj = new ListingCategory();
				while ($rowCat = mysql_fetch_assoc($resCat)) {
					$listingCatObj->countActiveListingByCategory($rowCat["category_id"], $domain_id);
				}
			}
		}
	}
    
    function system_countActivePostByCategory($postID = "", $category_id = false, $domain_id = false) {
		if (is_numeric($category_id) && $category_id > 0) {
			$postCatObj = new BlogCategory();
			$postCatObj->countActivePostByCategory($category_id, $domain_id);
		} else {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id) {
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			} else {
				if (defined("SELECTED_DOMAIN_ID")) {
					$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$dbObj = db_getDBObject();
				}
				unset($dbMain);
			}

			if (is_numeric($postID) && $postID > 0) {
				$sqlCat = "	SELECT BC.`root_id` AS `category_id`
							FROM `BlogCategory` BC
							LEFT JOIN `Blog_Category` B_C ON (B_C.`category_id` = BC.`id`)
							WHERE B_C.`post_id` = $postID";
			} else {
				$sqlCat = "SELECT `id` AS `category_id` FROM `BlogCategory` WHERE `category_id` = 0";
			}
			$resCat = $dbObj->Query($sqlCat);
			if (mysql_num_rows($resCat) > 0) {
				$postCatObj = new BlogCategory();
				while ($rowCat = mysql_fetch_assoc($resCat)) {
					$postCatObj->countActivePostByCategory($rowCat["category_id"], $domain_id);
				}
			}
		}
	}

	function system_countActiveItemByCategory($item, $id = "", $action = "", $category_id = false, $domain_id = false) {

		$dbMain = db_getDBObject(DEFAULT_DB, true);
		if ($domain_id) {
			$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
		} else {
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);
		}

        $table = ucfirst($item);

        if (($id) && (is_numeric($id)) && ($id > 0)) {
            $sql = "SELECT cat_1_id, parcat_1_level1_id, parcat_1_level2_id, parcat_1_level3_id, parcat_1_level4_id, cat_2_id, parcat_2_level1_id, parcat_2_level2_id, parcat_2_level3_id, parcat_2_level4_id, cat_3_id, parcat_3_level1_id, parcat_3_level2_id, parcat_3_level3_id, parcat_3_level4_id, cat_4_id, parcat_4_level1_id, parcat_4_level2_id, parcat_4_level3_id, parcat_4_level4_id, cat_5_id, parcat_5_level1_id, parcat_5_level2_id, parcat_5_level3_id, parcat_5_level4_id FROM ".$table." WHERE id = ".$id;
            $result = $dbObj->query($sql);
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_assoc($result);
                $category_id[] = $row["cat_1_id"];
                $category_id[] = $row["parcat_1_level1_id"];
                $category_id[] = $row["parcat_1_level2_id"];
                $category_id[] = $row["parcat_1_level3_id"];
                $category_id[] = $row["parcat_1_level4_id"];
                $category_id[] = $row["cat_2_id"];
                $category_id[] = $row["parcat_2_level1_id"];
                $category_id[] = $row["parcat_2_level2_id"];
                $category_id[] = $row["parcat_2_level3_id"];
                $category_id[] = $row["parcat_2_level4_id"];
                $category_id[] = $row["cat_3_id"];
                $category_id[] = $row["parcat_3_level1_id"];
                $category_id[] = $row["parcat_3_level2_id"];
                $category_id[] = $row["parcat_3_level3_id"];
                $category_id[] = $row["parcat_3_level4_id"];
                $category_id[] = $row["cat_4_id"];
                $category_id[] = $row["parcat_4_level1_id"];
                $category_id[] = $row["parcat_4_level2_id"];
                $category_id[] = $row["parcat_4_level3_id"];
                $category_id[] = $row["parcat_4_level4_id"];
                $category_id[] = $row["cat_5_id"];
                $category_id[] = $row["parcat_5_level1_id"];
                $category_id[] = $row["parcat_5_level2_id"];
                $category_id[] = $row["parcat_5_level3_id"];
                $category_id[] = $row["parcat_5_level4_id"];
                $category_id = array_unique($category_id);
            }
        } elseif (!$category_id) {
            $sql = "SELECT id FROM ".$table."Category ORDER BY id";
            $result = $dbObj->query($sql);
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    $category_id[] = $row["id"];
                }
            }
        }

        if ($category_id) {

            foreach ($category_id as $categoryid) {

                if ($categoryid > 0) {

                    $sql = "";
                    $sql .= " SELECT ";
                    $sql .= " COUNT(DISTINCT(id)) AS active".$item;
                    $sql .= " FROM ";
                    $sql .= " ".$table;
                    $sql .= " WHERE ";

                    $sql .= " (cat_1_id = '".$categoryid."' OR parcat_1_level1_id = '".$categoryid."' OR parcat_1_level2_id = '".$categoryid."' OR parcat_1_level3_id = '".$categoryid."' OR parcat_1_level4_id = '".$categoryid."' OR cat_2_id = '".$categoryid."' OR parcat_2_level1_id = '".$categoryid."' OR parcat_2_level2_id = '".$categoryid."' OR parcat_2_level3_id = '".$categoryid."' OR parcat_2_level4_id = '".$categoryid."' OR cat_3_id = '".$categoryid."' OR parcat_3_level1_id = '".$categoryid."' OR parcat_3_level2_id = '".$categoryid."' OR parcat_3_level3_id = '".$categoryid."' OR parcat_3_level4_id = '".$categoryid."' OR cat_4_id = '".$categoryid."' OR parcat_4_level1_id = '".$categoryid."' OR parcat_4_level2_id = '".$categoryid."' OR parcat_4_level3_id = '".$categoryid."' OR parcat_4_level4_id = '".$categoryid."' OR cat_5_id = '".$categoryid."' OR parcat_5_level1_id = '".$categoryid."' OR parcat_5_level2_id = '".$categoryid."' OR parcat_5_level3_id = '".$categoryid."' OR parcat_5_level4_id = '".$categoryid."') ";

                    if (($id) && (is_numeric($id)) && ($id > 0)) $sql .= " AND id = ".$id." ";
                    else $sql .= " AND status = 'A' ";

                    if ($table == "Event"){
                        $sql .= " AND ((end_date >= DATE_FORMAT(NOW(), '%Y-%m-%d') AND recurring = 'N') OR (recurring = 'Y' AND repeat_event = 'N' AND until_date >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (recurring = 'Y' AND repeat_event = 'Y'))";
                    } else if ($table == "Article"){
                        $sql .= " AND (publication_date <= DATE_FORMAT(NOW(), '%Y-%m-%d'))";
                    }

                    $result = $dbObj->query($sql);
                    if (mysql_num_rows($result) > 0) {
                        if ($row = mysql_fetch_assoc($result)) {
                            ${"active_".$item} = $row["active".$item];
                        }
                    }

                    if ($action == "inc") $sql = "UPDATE ".$table."Category SET active_".$item." = (active_".$item." + ".${"active_".$item}.") WHERE id = ".$categoryid;
                    elseif ($action == "dec") $sql = "UPDATE ".$table."Category SET active_".$item." = (active_".$item." - ".${"active_".$item}.") WHERE id = ".$categoryid;
                    else $sql = "UPDATE ".$table."Category SET active_".$item." = ".${"active_".$item}." WHERE id = ".$categoryid;

                    $dbObj->query($sql);

                }

            }

        }
	}

	function system_showBanner($banner_type = false, $banner_category_id = false, $banner_section = "general", $banner_amount = "1", $location = "") {

		if (BANNER_FEATURE == "on" && CUSTOM_BANNER_FEATURE == "on") {

			$dbObj = db_getDBObject();
			if (SHOW_INACTIVE_BANNER != "on") $wActive = " AND `active` = 'y' ";
			$sql = "SELECT value FROM BannerLevel WHERE name = ".db_formatString(str_replace("_", " ", string_strtolower($banner_type)))." AND theme = '".EDIR_THEME."' $wActive LIMIT 1";
                        
			$result = $dbObj->query($sql);
			if ($result) $row = mysql_fetch_assoc($result);
                        
//                        echo '<pre>';
//                        print_r($row);die;
                        
			if ($row["value"]) $banner_type = $row["value"];
			else $banner_type = false;

			$bannerObj = new Banner();

			
			if($banner_type==4)
			{
				if($location==""){
					$info = $bannerObj->randomRetrieveByCategoryLocation($banner_type, $banner_category_id, $banner_section, $banner_amount);
                                }else {	
					$info = $bannerObj->randomRetrieveByCategoryLocation($banner_type, $banner_category_id, $banner_section, $banner_amount,$location);
                                }
                                    
                        }
			else
                        {
                                
				$info = $bannerObj->randomRetrieve($banner_type, $banner_category_id, $banner_section, $banner_amount);
                        }
                        $banner = $bannerObj->makeBanner($info);

                        
    			for ($i=0; $i < count($info); $i++) {
				if ($info[$i]["expiration_setting"] == BANNER_EXPIRATION_IMPRESSION && $info[$i]["impressions"] > 0) {
					$sql = "UPDATE Banner SET impressions = impressions - 1 WHERE id = '".$info[$i]["id"]."'";
					$result = $dbObj->query($sql);
				}
				report_newRecord("banner", $info[$i]["id"], BANNER_REPORT_VIEW);
			}

		}
                     
		return $banner;

	}

	function system_getHeaderLogo($sitemgr = false) {
		$headerlogo = "";

		if (file_exists(EDIRECTORY_ROOT.IMAGE_HEADER_PATH)) {
			$headerlogo = "style=\"background-image: url('".DEFAULT_URL.IMAGE_HEADER_PATH."')\"";
		} else {
			if ($sitemgr) {
				$headerlogo = "style=\"background-image: url('".DEFAULT_URL."/".SITEMGR_ALIAS."/images/logo.png')\"";
			}
		}
		return $headerlogo;
	}

	function system_getHeaderMobileLogo() {
		$headerlogo = "";
		if (file_exists(EDIRECTORY_ROOT.MOBILE_LOGO_PATH)) {
			$headerlogo_path = MOBILE_LOGO_PATH;
		} else {
			$headerlogo_path = "/images/content/img_logo_mobile.gif";
		}
		$headerlogo = $headerlogo_path;
		return $headerlogo;
	}

	function system_getNoImageStyle($cssfile = false) {
		$noimagestyle = "";
		if ($cssfile) {
			if (file_exists(EDIRECTORY_ROOT.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_CSSEXT)) {
				$noimagestyle = "<link href=\"".DEFAULT_URL.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_CSSEXT."\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />";
			} else {
				$noimagestyle = "<link href=\"".DEFAULT_URL."/layout/general_noimage.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />";
			}
		} else {
			if (file_exists(EDIRECTORY_ROOT.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT)) {
				$noimagestyle = "background-image: url('".DEFAULT_URL.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT."')";
			} else {
				$noimagestyle = "background: #FFF url('".DEFAULT_URL."/images/bg_noimage.gif') 45% 50% no-repeat;";
			}
		}
		return $noimagestyle;
	}
    
    function system_getFavicon(){
        $favicon = "";

        setting_get("last_favicon_id", $last_favicon_id);

        if (!$last_favicon_id){
            setting_new("last_favicon_id", "1");
            $last_favicon_id = "1";
        }

        if (file_exists(EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/content_files/favicon_".$last_favicon_id.".ico")) {
            $favicon = "<link rel=\"Shortcut icon\" href=\"".DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/content_files/favicon_".$last_favicon_id.".ico\" type=\"image/x-icon\"/>";
        } else {
            if (BRANDED_PRINT == "on") { 
                $favicon ="<link rel=\"shortcut icon\" href=\"".DEFAULT_URL."/favicon.ico\" type=\"image/x-icon\"/>";
            }
        }
        
        return $favicon;
    }

	// ARRAY TO NAME-VALUE PAIRS
	function system_array2nvp($array, $separator = "&") {
		foreach ($array as $name=>$value) {
			$arrayNVP[] = $name."=".$value;
		}
		$nvpString = implode($separator, $arrayNVP);
		return $nvpString;
	}

	function system_getVideoSnippetCode($video_snippet, $video_snippet_width, $video_snippet_height, $forceResize = DETAIL_FORCE_VIDEORESIZE) {

		$video_resize = false;

		$prefix_video_snippet = "";
		$suffix_video_snippet = $video_snippet;

		while (($pos = string_strpos($suffix_video_snippet, "width")) !== false) {

			$prefix_video_snippet .= string_substr($suffix_video_snippet, 0, $pos);
			$suffix_video_snippet = string_substr($suffix_video_snippet, $pos);

			if (($pos = string_strpos($suffix_video_snippet, ">")) !== false) {

				$lookingfornumber = $suffix_video_snippet;
				while (!is_numeric($lookingfornumber[0])) {
					$lookingfornumber = string_substr($lookingfornumber, 1);
				}

				$widthnumber = "";
				while (is_numeric($lookingfornumber[0])) {
					$widthnumber .= $lookingfornumber[0];
					$lookingfornumber = string_substr($lookingfornumber, 1);
				}

				if ($widthnumber > $video_snippet_width || $forceResize) {
					$video_resize = true;
				}

				$prefix_video_snippet .= string_substr($suffix_video_snippet, 0, $pos);
				$suffix_video_snippet = string_substr($suffix_video_snippet, $pos);

			}

		}

		$prefix_video_snippet = "";
		$suffix_video_snippet = $video_snippet;

		while (($pos = string_strpos($suffix_video_snippet, "height")) !== false) {

			$prefix_video_snippet .= string_substr($suffix_video_snippet, 0, $pos);
			$suffix_video_snippet = string_substr($suffix_video_snippet, $pos);

			if (($pos = string_strpos($suffix_video_snippet, ">")) !== false) {

				$lookingfornumber = $suffix_video_snippet;
				while (!is_numeric($lookingfornumber[0])) {
					$lookingfornumber = string_substr($lookingfornumber, 1);
				}

				$heightnumber = "";
				while (is_numeric($lookingfornumber[0])) {
					$heightnumber .= $lookingfornumber[0];
					$lookingfornumber = string_substr($lookingfornumber, 1);
				}

				if ($heightnumber > $video_snippet_height || $forceResize) {
					$video_resize = true;
				}

				$prefix_video_snippet .= string_substr($suffix_video_snippet, 0, $pos);
				$suffix_video_snippet = string_substr($suffix_video_snippet, $pos);

			}

		}

		$prefix_video_snippet = "";
		$suffix_video_snippet = $video_snippet;

		if ($video_resize) {
			while ((($pos = string_strpos($suffix_video_snippet, "width")) !== false) || (($pos = string_strpos($suffix_video_snippet, "height")) !== false)) {
				$prefix_video_snippet .= string_substr($suffix_video_snippet, 0, $pos);
				$prefix_video_snippet .= " style=\"width: ".$video_snippet_width."px; height: ".$video_snippet_height."px;\" ";
				$suffix_video_snippet = string_substr($suffix_video_snippet, $pos);
				if (($pos = string_strpos($suffix_video_snippet, ">")) !== false) {
					$prefix_video_snippet .= string_substr($suffix_video_snippet, 0, $pos);
					$suffix_video_snippet = string_substr($suffix_video_snippet, $pos);
				}
			}
		}

		$video_snippet_code = $prefix_video_snippet.$suffix_video_snippet;
        
        if (string_strpos($video_snippet_code, "<iframe") !== false && string_strpos($video_snippet_code, "wmode") === false){ //new Youtube code (iframe) - need to insert "wmode" parameter, otherwise all popups will shown under the video

            $prefix_video_snippet = "";
            $suffix_video_snippet = $video_snippet_code;
            $video_url = "";
            
            // The Regular Expression filter to find the video URL
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

            // The Text you want to filter for urls
            $text = $suffix_video_snippet;

            // Check if there is a url in the text
            if(preg_match($reg_exUrl, $text, $url)) {
                $video_url = str_replace("'", "", $url[0]);
                $video_url = str_replace("\"", "", $video_url);
                $pos = string_strpos($suffix_video_snippet, $video_url);
                $prefix_video_snippet .= string_substr($suffix_video_snippet, 0, $pos);
                $suffix_video_snippet = string_substr($suffix_video_snippet, $pos+string_strlen($video_url));
                
                if (string_strpos($video_url, "?") !== false){
                    $video_snippet_code = $prefix_video_snippet.$video_url."&wmode=transparent".$suffix_video_snippet;
                } else {
                    $video_snippet_code = $prefix_video_snippet.$video_url."?wmode=transparent".$suffix_video_snippet;
                }
            }
            
        } elseif (string_strpos($video_snippet_code, "<object") !== false && string_strpos($video_snippet_code, "wmode=") === false){ //old Youtube code (object) - need to insert "wmode" parameter, otherwise all popups will shown under the video
            $video_snippet_code = str_replace("<embed ", "<embed wmode='transparent' ", $video_snippet_code);
        }

		return $video_snippet_code;

	}

	function system_getURLSearchParams($array) {
		$url_search_params = "";
		$array_search_params = array();
		if ($array) {
			if (count($array) > 0) {
				foreach ($array as $name=>$value) {
					$pos = string_strpos($name, "search_");
					$posLocation = string_strpos($name, "location_");
					if ((($pos !== false) && ($pos == 0)) || (($posLocation !== false) && ($posLocation == 0)) ) {
						if ($value) {
							$array_search_params[] = $name."=".urlencode($value);
						}
					}
				}
			}
		}
		if ($array_search_params) {
			if (count($array_search_params) > 0) {
				$url_search_params = implode("&", $array_search_params);
			}
		}
		return $url_search_params;
	}
    
    function system_getManageOrderBy($order, $table, $item_scalability, &$orderFields, $search = false){
        
        $orderBy = "";
        
        //Select fields
        if (!$orderFields) {
            $orderFields = "*";
        }
        
        //Ordination options
        $auxOrder = explode("_", $order);
        $order = $auxOrder[0]; //order option
        $option = $auxOrder[1]; //asc or desc
        
        $titleField = "";
        $levelField = "";
        
        //Modules exceptions
        if ($table == "Banner") {
            $titleField = "caption";
            $levelField = "level";
            if ($order == "level") { //Subquery to ready banner type as it's displayName instead of it's number
                $orderFields .= ", (SELECT displayName FROM BannerLevel WHERE BannerLevel.value = Banner.type AND BannerLevel.theme = '".EDIR_THEME."') AS level";
            }
        } elseif ($table == "Promotion") {
            $titleField = "name";
            $levelField = ""; //Promotion doesn't have level
        }  elseif ($table == "Blog") {
            $titleField = "title";
            $levelField = ""; //Blog doesn't have level
        } else {
            $titleField = "title";
            $levelField = "level";
        }
        
        //Order by
        if ($order) {
            if ($order == $titleField) { //order by title
                $orderBy = $titleField.($option == "asc" || $option == "desc" ? " ".$option : "");
                
            } elseif ($order == $levelField) { //order by level
                
                if ($table != "Banner") { //level numbers are inverted (level 10, Diamond, is higher than level 30, Gold) for all modules but Banners, so we need to invert the order so the ordination make sense
                    if ($option == "asc") {
                        $option = "desc";
                    } elseif ($option == "desc") {
                        $option = "asc";
                    }
                }
                
                $orderBy = $levelField.($option == "asc" || $option == "desc" ? " ".$option : "");
                
            } elseif ($order == "account" && $table != "Blog") { //order by account
                $orderBy = "account_name".($option == "asc" || $option == "desc" ? " ".$option : "");
                
                //Subquery to get the username according to the account_id. Order by account_id wouldn't make sense.
                $orderFields .= ", IF (account_id > 0 , (SELECT username FROM AccountProfileContact WHERE AccountProfileContact.account_id = $table.account_id), '') AS account_name";

            } elseif ($order == "status" && $table != "Promotion") { //order by status
                $orderBy = "status".($option == "asc" || $option == "desc" ? " ".$option : "");
                            
            } elseif ($order == "renewal" && $table != "Promotion" && $table != "Blog") { //order by renewal date
                $orderBy = "renewal_date".($option == "asc" || $option == "desc" ? " ".$option : "");
            
            } elseif ($order == "impressions" && $table == "Banner") { //order by banner impressions
                $orderBy = "impressions".($option == "asc" || $option == "desc" ? " ".$option : "");
            
            } elseif ($order == "startdate"  && $table == "Event") { //order by event start date
                $orderBy = "start_date".($option == "asc" || $option == "desc" ? " ".$option : "");
            }
        }
        
        //default ordination concatenated after order selected
        if ($search || string_strpos($_SERVER["PHP_SELF"], MEMBERS_ALIAS."/") !== false) {
            //Modules search and Members Area
            $extraOrder = array();
            $extraOrderStr = "";

            if ($table != "Promotion" && $table != "Blog" && $order != $levelField) {
                $extraOrder[] = ($table == "Banner" ? "type" : "level DESC");
            }

            if ($order != $titleField) {
                $extraOrder[] = $titleField;
            }

            if ($table != "Promotion" && $table != "Blog" && $order != "renewal") {
                $extraOrder[] = "renewal_date";
            }

            if ($extraOrder[0]) {
                $extraOrderStr = implode(", ", $extraOrder);
                $orderBy .= ($orderBy ? ", " : "").$extraOrderStr;
            }

        } elseif ($item_scalability != "on") {
            //Modules index - Sitemgr area
            $orderBy .= ($orderBy ? ", " : "");
            $orderBy .= "updated DESC".($order != $titleField ? ", $titleField" : "");  
        }

        return $orderBy;
        
    }

	function system_getFormInputSearchParams($array) {
		$url_search_params = "";
		$array_search_params = array();
		if ($array) {
			if (count($array) > 0) {
				foreach ($array as $name=>$value) {
					$pos = string_strpos($name, "search_");
					if (($pos !== false) && ($pos == 0)) {
						if ($value) {
							$array_search_params[] = "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\" />";
						}
					}
				}
			}
		}
		if ($array_search_params) {
			if (count($array_search_params) > 0) {
				$url_search_params = implode("\n", $array_search_params);
			}
		}
		return $url_search_params;
	}
	
	function system_getFormInputHiddenParams($array, $except = "") {
		$exceptArray = explode(",", $except);
		$url_hidden_params = "";
		$array_hidden_params = array();
		if ($array) {
			if (count($array) > 0) {
				foreach ($array as $name=>$value) {
					if ($value && (!in_array($name, $exceptArray))) {
						$array_hidden_params[] = "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\" />";
					}
					
				}
			}
		}
		if ($array_hidden_params) {
			if (count($array_hidden_params) > 0) {
				$url_hidden_params = implode("\n", $array_hidden_params);
			}
		}
		return $url_hidden_params;
	}

	function system_denyInjections($var, $text = false) {
		$var = strip_tags($var);
		$var_aux = urlencode($var);
        if ($text) {
            $var = htmlspecialchars_decode($var);
            $var = nl2br($var);
		} elseif ((string_strpos($var_aux, "%0") !== false) || (string_strpos($var_aux, "%1") !== false)){
            $var = "";
		}
		return $var;
	}

	function system_showFrontGallery($galleries = 0, $level = 0, $user = false, $imagesToShow = GALLERY_DETAIL_IMAGES, $type = "listing", $tPreview = false) {
       
        if ($tPreview) {
			$gallery_code_final = "	<ul>";
			$gallery_code_final .= "	<li>";
			$gallery_code_final .= "		<span class=\"no-image\" style=\"cursor: default;\">";
			$gallery_code_final .= "		</span>";
			$gallery_code_final .= "	</li>";
			$gallery_code_final .= "	<li>";
			$gallery_code_final .= "		<span class=\"no-image\" style=\"cursor: default;\">";
			$gallery_code_final .= "		</span>";
			$gallery_code_final .= "	</li>";
			$gallery_code_final .= "	<li class=\"pd-0\">";
			$gallery_code_final .= "		<span class=\"no-image\" style=\"cursor: default;\">";
			$gallery_code_final .= "		</span>";
			$gallery_code_final .= "	</li>";
			$gallery_code_final .= "</ul>";
			$gallery_code_final .= "<p class=\"caption\">";
			$gallery_code_final .= "	<a href=\"javascript:void(0);\" style=\"cursor: default;\">".system_showText(LANG_GALLERYCLICKHERE)."</a> ";
			$gallery_code_final .= "	".system_showText(LANG_GALLERYSLIDESHOWTEXT);
			$gallery_code_final .= "</p>";
		} else {
			$gallery_code_final = "";

			if (count($galleries)>0) {

				if ($type=="listing") $item_max_gallery = LISTING_MAX_GALLERY;
				elseif ($type=="event") $item_max_gallery = EVENT_MAX_GALLERY;
				elseif ($type=="classified") $item_max_gallery = CLASSIFIED_MAX_GALLERY;
				elseif ($type=="article") $item_max_gallery = ARTICLE_MAX_GALLERY;
				else return "";

				while (count($galleries) > $item_max_gallery) {
					array_pop($galleries);
				}

				foreach ($galleries as $each_gallery) {

					$gallery_code = "";

					$galleryObj = new Gallery($each_gallery);

					if ($galleryObj->getNumber("id") && $galleryObj->image && count($galleryObj->image) > 0) {

						if ($type=="listing") $galleryLevel = new ListingLevel();
						elseif ($type=="event") $galleryLevel = new EventLevel();
						elseif ($type=="classified") $galleryLevel = new ClassifiedLevel();
						elseif ($type=="article") $galleryLevel = new ArticleLevel();
						else return "";

						$maxImages = $galleryLevel->getImages($level);

						if (($maxImages) && (($maxImages > 0) || ($maxImages == -1))) {

							$totalImages = ($maxImages >= count($galleryObj->image)) ? count($galleryObj->image) : $maxImages;
							if ($maxImages == -1) $totalImages = count($galleryObj->image);

							$gallery_code .= "<ul>";

							$number_of_images = 0;

							$i = 0;
							for ($imgInd = 0; $imgInd < $totalImages; $imgInd++) {

								$presentImg = $galleryObj->image[$imgInd];

								$imageObj = new Image($presentImg["image_id"]);
								$imageThumbObj = new Image($presentImg["thumb_id"]);

								$slideshowpopup = "javascript:void(0);";
								$slideshowpopupc = "javascript:void(0);";
								$slideshowstyle = "style=\"cursor:default;\"";
								if ($user){
									$slideshowpopup = DEFAULT_URL."/popup/popup.php?pop_type=slideshow&amp;gallery_id=".$each_gallery."&amp;".$type."_level=".$level."&amp;image_id=".$presentImg["image_id"];
									$slideshowpopupc = DEFAULT_URL."/popup/popup.php?pop_type=slideshow&amp;gallery_id=".$each_gallery."&amp;".$type."_level=".$level;
									$slideshowstyle = "";
								}

								if ($imageObj->imageExists() && $imageThumbObj->imageExists()) {

									if ($number_of_images < $imagesToShow) {

										$gallery_code .= "<li ".(($imgInd == ($imagesToShow - 1)) ? ("class=\"pd-0\"") : ("")).">";
										$gallery_code .= "<a href=\"".$slideshowpopup."\" $slideshowstyle class=\"iframe fancy_window_gallery\">";
										$gallery_code .= $imageThumbObj->getTag(true, IMAGE_GALLERY_THUMB_WIDTH, IMAGE_GALLERY_THUMB_HEIGHT, $presentImg["thumb_caption"], true);
										$wrapWidth = (IMAGE_GALLERY_THUMB_WIDTH/5); // each character have a width of 5px
										$gallery_code .= "</a>";
										$gallery_code .= "</li>";

									}

									$number_of_images++;

									$i++;

								}

							}

							$gallery_code .= "</ul>";

							$gallery_code .= "<p class=\"caption\"><a href=\"".$slideshowpopupc."\" $slideshowstyle class=\"iframe fancy_window_gallery\">".system_showText(LANG_GALLERYCLICKHERE)."</a> ".system_showText(LANG_GALLERYSLIDESHOWTEXT)."</p>";
							
						}

						unset($galleryLevel);
						unset($galleryObj);

						if ($number_of_images==0) $gallery_code = "";

					}

					$gallery_code_final .= $gallery_code;

				}

			}
		}

		return $gallery_code_final;

	}
    
    function system_showFrontGalleryPlugin($galleries = 0, $level = 0, $user = false, $imagesToShow = GALLERY_DETAIL_IMAGES, $type = "listing", $tPreview = false, &$onlyMain = false) {
        
        if ($tPreview) {
            
            if ($type=="listing") $galleryLevel = new ListingLevel();
            elseif ($type=="event") $galleryLevel = new EventLevel();
            elseif ($type=="classified") $galleryLevel = new ClassifiedLevel();
            elseif ($type=="article") $galleryLevel = new ArticleLevel();
            else return "";
            
            $maxImages = $galleryLevel->getImages($level);
            $totalImages = ($maxImages >= 4) ? 4 : $maxImages;

            if ($maxImages && (($maxImages > 0) || ($maxImages == -1))){
                $gallery_code_final .= "<ul class=\"ad-thumb-list\">";
                for ($imgInd = 0; $imgInd < $totalImages; $imgInd++) {
                    $gallery_code_final .= "	<li>";
                    $gallery_code_final .= "		<span class=\"no-image\" style=\"cursor: default;\">";
                    $gallery_code_final .= "		</span>";
                    $gallery_code_final .= "	</li>";
                }
                $gallery_code_final .= "</ul>";
            }
		} else {
			$gallery_code_final = "";
			$gallery_code_final .= system_addGalleryScript();

			if (count($galleries)>0) {

				foreach ($galleries as $each_gallery) {

					$gallery_code = "";

					$galleryObjAux = new Gallery($each_gallery); //Gallery without the main image
					$galleryObj = new Gallery($each_gallery, SELECTED_DOMAIN_ID, true);
                    
                    if ($type=="listing") $galleryLevel = new ListingLevel();
                    elseif ($type=="event") $galleryLevel = new EventLevel();
                    elseif ($type=="classified") $galleryLevel = new ClassifiedLevel();
                    elseif ($type=="article") $galleryLevel = new ArticleLevel();
                    else return "";
                    
                    $maxImages = $galleryLevel->getImages($level);

                    if ($galleryObjAux->getNumber("id") && $galleryObjAux->image && count($galleryObjAux->image) > 0 && $maxImages && (($maxImages > 0) || ($maxImages == -1))){
                        $useGallery = true;
                    } else {
                        $useGallery = false;
                        $gallery_code_final = "";
                        $onlyMain = true;
                    }
                    
					if ($galleryObj->getNumber("id") && $galleryObj->image && count($galleryObj->image) > 0 && $useGallery) {

                        $hasMainImage = false;
                        for ($imgInd = 0; $imgInd < count($galleryObj->image); $imgInd++) {
                            if ($galleryObj->image[$imgInd]["image_default"] == "y"){
                               $hasMainImage = true;
                               break;
                            }                           
                        }

						if (($maxImages) && (($maxImages > 0) || ($maxImages == -1))) {

							$totalImages = ($maxImages >= count($galleryObj->image)) ? count($galleryObj->image) : $maxImages;
                            
                            if ($hasMainImage){
                                $totalImages++;
                            }
                            
							if ($maxImages == -1) $totalImages = count($galleryObj->image);

							$gallery_code .= "<div class=\"ad-image-wrapper image-shadow\">
                                              </div>
                                              <!---<div class=\"ad-controls\">
                                              </div>-->
                                              <div class=\"ad-nav\">
                                                <div class=\"ad-thumbs gallery\">
                                                    <ul class=\"ad-thumb-list\">";

							$number_of_images = 0;

							$i = 0;
							for ($imgInd = 0; $imgInd < $totalImages; $imgInd++) {

								$presentImg = $galleryObj->image[$imgInd];

								$imageObj = new Image($presentImg["image_id"]);
								$imageThumbObj = new Image($presentImg["thumb_id"]);

								if ($imageObj->imageExists() && $imageThumbObj->imageExists()) {

                                    $gallery_code .= "<li>";
                                    $gallery_code .= "<a href=\"".$imageObj->getPath()."\">";
                                    $gallery_code .= $imageThumbObj->getTag(true, IMAGE_GALLERY_THUMB_WIDTH, IMAGE_GALLERY_THUMB_HEIGHT, $presentImg["thumb_caption"], true, $presentImg["image_caption"]);
                                    $gallery_code .= "</a>";
                                    $gallery_code .= "</li>";

									$number_of_images++;

									$i++;

								}

							}

							$gallery_code .= "</ul></div></div>";

							
						}

						unset($galleryLevel);
						unset($galleryObj);

						if ($number_of_images==0) $gallery_code = "";

					}

					$gallery_code_final .= $gallery_code;

				}

			}
		}
        if (!$gallery_code && !$tPreview) $gallery_code_final = "";
		return $gallery_code_final;
    }
    
    function system_addGalleryScript(){
        $script = "";
        $script .= "<script type=\"text/javascript\">
                        //<![CDATA[
                            $(function() {
                            galleries = $('.ad-gallery').adGallery({
                                loader_image: '".DEFAULT_URL."/images/img_loading.gif"."',
                                width: ".IMAGE_LISTING_FULL_WIDTH.",
                                height: ".IMAGE_LISTING_FULL_HEIGHT.",
                                display_next_and_prev: false
                            });
                            });
                        //]]>
                    </script>";
        return $script;
    }

	function system_highlightFirstWord($word, $amount=1) {
		if ($amount <= 1) {
			if (($pos = string_strpos($word, " ")) !== false) {
				return "<span>".string_substr($word, 0, $pos)."</span>".string_substr($word, $pos);
			} else {
				return $word;
			}
		} else {
			$words = explode(" ", $word);
			if (count($words) >= 2) {
				if ($amount <= count($words)) {
					$words[$amount-1] = $words[$amount-1]."</span>";
				} else {
					$words[count($words)-1] = $words[count($words)-1]."</span>";
				}
				return "<span>".implode(" ", $words);
			} else {
				return $word;
			}
		}
	}

	function system_highlightLastWord($word, $amount=1) {
		if ($amount <= 1) {
			if (($pos = string_strrpos($word, " ")) !== false) {
				return string_substr($word, 0, $pos+1)."<span>".string_substr($word, $pos+1)."</span>";
			} else {
				return $word;
			}
		} else {
			$words = explode(" ", $word);
			if (count($words) >= 2) {
				if ($amount <= count($words)) {
					$words[count($words)-$amount] = "<span>".$words[count($words)-$amount];
				} else {
					$words[0] = "<span>".$words[0];
				}
				return implode(" ", $words)."</span>";
			} else {
				return $word;
			}
		}
	}

	function system_highlightWords($word) {
		return "<span>".$word."</span>";
	}

	function system_showText($text) {
		return $text;
	}

	function system_showDate($format_str, $time=false) {
		if (!string_strlen(trim($format_str))) return false;
		if (!$time) $time = mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'));
		$allow_datechars = array('d','D','j','l','N','S','w','z','W','F','m','M','n','t','L','o','Y','y','a','A','B','g','G','h','H','i','s','u','e','I','O','P','T','Z','c','r','U','\\');
		$month_names = explode(",", LANG_DATE_MONTHS);
		$weekday_names = explode(",", LANG_DATE_WEEKDAYS);
		$aux_format_str = $format_str;
		$buffer = "";
		for ($i=0; $i<string_strlen($aux_format_str); $i++) {
			if (in_array($aux_format_str[$i], $allow_datechars)) {
				//d -> Day of the month, 2 digits with leading zeros.
				if ($aux_format_str[$i] == "d") { $buffer .= date("d", $time); }
				//D -> A textual representation of a day, three letters.
				if ($aux_format_str[$i] == "D") { $buffer .= string_substr($weekday_names[date("j", $time)-1], 0, 3); }
				//j -> Day of the month without leading zeros.
				if ($aux_format_str[$i] == "j") { $buffer .= date("j", $time); }
				//l -> A full textual representation of the day of the week.
				if ($aux_format_str[$i] == "l") { $buffer .= $weekday_names[date("j", $time)-1]; }
				//N -> ISO-8601 numeric representation of the day of the week.
				if ($aux_format_str[$i] == "N") { $buffer .= date("N", $time); }
				//S -> English ordinal suffix for the day of the month, 2 characters.
				if ($aux_format_str[$i] == "S") { $buffer .= date("S", $time); }
				//w -> Numeric representation of the day of the week.
				if ($aux_format_str[$i] == "w") { $buffer .= date("w", $time); }
				//z -> The day of the year (starting from 0).
				if ($aux_format_str[$i] == "z") { $buffer .= date("z", $time); }
				//W -> ISO-8601 week number of year, weeks starting on Monday.
				if ($aux_format_str[$i] == "W") { $buffer .= date("W", $time); }
				//F -> A full textual representation of a month, such as January or March.
				if ($aux_format_str[$i] == "F") { $buffer .= string_ucwords($month_names[date("n", $time)-1]); }
				//m -> Numeric representation of a month, with leading zeros.
				if ($aux_format_str[$i] == "m") { $buffer .= date("m", $time); }
				//M -> A short textual representation of a month, three letters.
				if ($aux_format_str[$i] == "M") { $buffer .= date("M", $time); }
				//n -> Numeric representation of a month, without leading zeros.
				if ($aux_format_str[$i] == "n") { $buffer .= date("n", $time); }
				//t -> Number of days in the given month.
				if ($aux_format_str[$i] == "t") { $buffer .= date("t", $time); }
				//L -> Whether it's a leap year.
				if ($aux_format_str[$i] == "L") { $buffer .= date("L", $time); }
				//o -> ISO-8601 year number. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead.
				if ($aux_format_str[$i] == "o") { $buffer .= date("o", $time); }
				//Y -> A full numeric representation of a year, 4 digits.
				if ($aux_format_str[$i] == "Y") { $buffer .= date("Y", $time); }
				//y -> A two digit representation of a year.
				if ($aux_format_str[$i] == "y") { $buffer .= date("y", $time); }
				//a -> Lowercase Ante meridiem and Post meridiem.
				if ($aux_format_str[$i] == "a") { $buffer .= date("a", $time); }
				//A -> Uppercase Ante meridiem and Post meridiem.
				if ($aux_format_str[$i] == "A") { $buffer .= date("A", $time); }
				//B -> Swatch Internet time.
				if ($aux_format_str[$i] == "B") { $buffer .= date("B", $time); }
				//g -> 12-hour format of an hour without leading zeros.
				if ($aux_format_str[$i] == "g") { $buffer .= date("g", $time); }
				//G -> 24-hour format of an hour without leading zeros.
				if ($aux_format_str[$i] == "G") { $buffer .= date("G", $time); }
				//h -> 12-hour format of an hour with leading zeros.
				if ($aux_format_str[$i] == "h") { $buffer .= date("h", $time); }
				//H -> 24-hour format of an hour with leading zeros.
				if ($aux_format_str[$i] == "H") { $buffer .= date("H", $time); }
				//i -> Minutes with leading zeros.
				if ($aux_format_str[$i] == "i") { $buffer .= date("i", $time); }
				//s -> Seconds, with leading zeros.
				if ($aux_format_str[$i] == "s") { $buffer .= date("s", $time); }
				//u -> Microseconds.
				if ($aux_format_str[$i] == "u") { $buffer .= date("u", $time); }
				//e -> Timezone identifier.
				if ($aux_format_str[$i] == "e") { $buffer .= date("e", $time); }
				//I -> Whether or not the date is in daylight saving time.
				if ($aux_format_str[$i] == "I") { $buffer .= date("I", $time); }
				//O -> Difference to Greenwich time (GMT) in hours.
				if ($aux_format_str[$i] == "O") { $buffer .= date("O", $time); }
				//P -> Difference to Greenwich time (GMT) with colon between hours and minutes.
				if ($aux_format_str[$i] == "P") { $buffer .= date("P", $time); }
				//T -> Timezone abbreviation.
				if ($aux_format_str[$i] == "T") { $buffer .= date("T", $time); }
				//Z -> Timezone offset in seconds. The offset for timezones west of UTC is always negative, and for those east of UTC is always positive.
				if ($aux_format_str[$i] == "Z") { $buffer .= date("Z", $time); }
				//c -> ISO 8601 date.
				if ($aux_format_str[$i] == "c") { $buffer .= date("c", $time); }
				//r -> RFC 2822 formatted date.
				if ($aux_format_str[$i] == "r") { $buffer .= date("r", $time); }
				//U -> Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT).
				if ($aux_format_str[$i] == "U") { $buffer .= date("U", $time); }
				//\ -> escape.
				if ($aux_format_str[$i] == "\\") {
					$i++;
					$buffer .= $aux_format_str[$i];
				}
			} else {
				$buffer .= $aux_format_str[$i];
			}
		}
		return $buffer;
	}

	function system_itemRelatedCategories($item_id, $item_type, $user, $have_data = false, $data = false) {

		$return = "";
		$dbObj_main = db_getDBObject(DEFAULT_DB, true);
		$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbObj_main);

		if ($have_data) {

			if ($item_type == "deal" ){
				$itemObj = new Listing($data);
			}else if ($item_type == "listing"){
				$itemObj = new Listing($data);
			}elseif ($item_type == "event"){
				 $itemObj = new Event($data);
			}elseif ($item_type == "classified"){
				$itemObj = new Classified($data);
			}elseif ($item_type == "article"){
			 	$itemObj = new Article($data);
			}elseif ($item_type == "blog"){
			 	$itemObj = new Post($data);
			}
		} else {
    		if ($item_type == "event") $itemObj = new Event($item_id);
			elseif ($item_type == "classified") $itemObj = new Classified($item_id);
			elseif ($item_type == "article") $itemObj = new Article($item_id);
			elseif ($item_type == "blog") $itemObj = new Post($item_id);
		}


		if (($item_type == "listing" || $item_type == "deal") && $item_id) {

			$listingObj = new Listing();
			$categories = $listingObj->getCategories($have_data, $data, $item_id);

			for($i=0;$i<count($categories);$i++){
				if ($categories[$i]["title"] && $categories[$i]["enabled"] == "y") {
                    if ($item_type=='listing')
                        $urlToModule=LISTING_DEFAULT_URL;
                    else if ($item_type=='deal')
                        $urlToModule=PROMOTION_DEFAULT_URL;
					if ($user) {
						$categoriesString[] = "<a href=\"".$urlToModule."/".ALIAS_CATEGORY_URL_DIVISOR."/".$categories[$i]["friendly_url"]."\">".format_getString($categories[$i]["title"])."</a>";
					} else {
						$categoriesString[] = "<a href=\"javascript:void(0);\" style=\"cursor:default\">".format_getString($categories[$i]["title"])."</a>";
					}
				}
			}

			if ($categoriesString) {
				$return = system_showText(LANG_SEARCHRESULTS_CATEGORY)." ".implode(", ", $categoriesString);
			}

		} elseif ($item_type == "blog" && $item_id) {

			$postObj = new Post();
			$categories = $postObj->getCategories($have_data, $data, $item_id);

			for($i=0;$i<count($categories);$i++){
				if ($categories[$i]["title"] && $categories[$i]["enabled"] == "y") {
                    $urlToModule = BLOG_DEFAULT_URL;
					if ($user) {
						$categoriesString[] = "<a href=\"".$urlToModule."/".ALIAS_CATEGORY_URL_DIVISOR."/".$categories[$i]["friendly_url"]."\">".format_getString($categories[$i]["title"])."</a>";
					} else {
						$categoriesString[] = "<a href=\"javascript:void(0);\" style=\"cursor:default\">".format_getString($categories[$i]["title"])."</a>";
					}
				}
			}

			if ($categoriesString) {
				$return = system_showText(LANG_SEARCHRESULTS_CATEGORY)." ".implode(", ", $categoriesString);
			}

		} elseif ($itemObj && $itemObj->getNumber("id") && ($itemObj->getNumber("id")>0)) {

			$categories = $itemObj->getCategories($have_data, $data);
			if ($categories) {
				foreach ($categories as $category) {
					$treePath = getTreePath($category->getNumber("id"), $item_type);
					$treePath = string_substr($treePath, 1);
					if (string_strpos($treePath, ",") !== false) $mainCategoryID = string_substr($treePath, 0, string_strpos($treePath, ","));
					else $mainCategoryID = $treePath;
					if ($mainCategoryID) {
						$query = "SELECT * FROM ".string_ucwords($item_type)."Category WHERE id = ".$mainCategoryID;
						$result = $db->query($query);
						if (mysql_num_rows($result) > 0) {
							while ($row = mysql_fetch_assoc($result)) {
								$mainCategoryID = $row;
							}
						}
                        $mainCategoriesID[] = $mainCategoryID;
					}
				}

				if ($mainCategoriesID) {
					$mainCategoriesID = array_unique($mainCategoriesID);
					foreach ($mainCategoriesID as $mainCategoryID) {
						if ($item_type == "listing" || $item_type=='deal') {
							$mainCategoryObj = new ListingCategory($mainCategoryID);
							if ($mainCategoryObj->getString("title") && $mainCategoryObj->getString("enabled") == "y") {
                                if ($item_type=='listing')
                                    $urlToModule=LISTING_DEFAULT_URL;
                                else if ($item_type=='deal')
                                    $urlToModule=PROMOTION_DEFAULT_URL;
								if ($user) {
									$categoriesString[] = "<a href=\"".$urlToModule."/".ALIAS_CATEGORY_URL_DIVISOR."/".$mainCategoryObj->getString("friendly_url")."\">".$mainCategoryObj->getString("title")."</a>";
								} else {
									$categoriesString[] = "<a href=\"javascript:void(0);\" style=\"cursor:default\">".$mainCategoryObj->getString("title")."</a>";
								}
							}
						} elseif ($item_type == "event") {
							$mainCategoryObj = new EventCategory($mainCategoryID);
							if ($mainCategoryObj->getString("title") && $mainCategoryObj->getString("enabled") == "y") {
								if ($user) {
									$categoriesString[] = "<a href=\"".EVENT_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$mainCategoryObj->getString("friendly_url")."\">".$mainCategoryObj->getString("title")."</a>";
								} else {
									$categoriesString[] = "<a href=\"javascript:void(0);\" style=\"cursor:default\">".$mainCategoryObj->getString("title")."</a>";
								}
							}
						} elseif ($item_type == "classified") {
							$mainCategoryObj = new ClassifiedCategory($mainCategoryID);
							if ($mainCategoryObj->getString("title") && $mainCategoryObj->getString("enabled") == "y") {
								$categoryName = $mainCategoryObj->getString("title", true,50);
								if ($user) {
									$categoriesString[] = "<a href=\"".CLASSIFIED_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$mainCategoryObj->getString("friendly_url")."\" title=\"".$mainCategoryObj->getString("title")."\">".$categoryName."</a>";
								} else {
									$categoriesString[] = "<a href=\"javascript:void(0);\" style=\"cursor:default\">".$categoryName."</a>";
								}
							}
						} elseif ($item_type == "article") {
							$mainCategoryObj = new ArticleCategory($mainCategoryID);
							if ($mainCategoryObj->getString("title") && $mainCategoryObj->getString("enabled") == "y") {
								if ($user) {
									$categoriesString[] = "<a href=\"".ARTICLE_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$mainCategoryObj->getString("friendly_url")."\">".$mainCategoryObj->getString("title")."</a>";
								} else {
									$categoriesString[] = "<a href=\"javascript:void(0);\" style=\"cursor:default\">".$mainCategoryObj->getString("title")."</a>";
								}
							}
						}
					}
					if ($categoriesString) {
						$return = system_showText(LANG_SEARCHRESULTS_CATEGORY)." ".implode(", ", $categoriesString);
					}
				}
			}

		}

		return $return;
	}

    function system_accentOff($str) {

        $accents = array(
                        "A" => "/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/",
                        "a" => "/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/",
                        "C" => "/&Ccedil;/",
                        "c" => "/&ccedil;/",
                        "E" => "/&Egrave;|&Eacute;|&Ecirc;|&Euml;/",
                        "e" => "/&egrave;|&eacute;|&ecirc;|&euml;/",
                        "I" => "/&Igrave;|&Iacute;|&Icirc;|&Iuml;/",
                        "i" => "/&igrave;|&iacute;|&icirc;|&iuml;/",
                        "N" => "/&Ntilde;/",
                        "n" => "/&ntilde;/",
                        "O" => "/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/",
                        "o" => "/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/",
                        "U" => "/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/",
                        "u" => "/&ugrave;|&uacute;|&ucirc;|&uuml;/",
                        "Y" => "/&Yacute;/",
                        "y" => "/&yacute;|&yuml;/",
                        "a." => "/&ordf;/",
                        "o." => "/&ordm;/"
                        );

        return preg_replace(array_values($accents), array_keys($accents), string_htmlentities($str));
    }

	function system_showAccountUserName($username) {
		if (($pos = string_strpos($username, "::")) !== false) {
			$username = string_substr($username, $pos+2);
		}
		return $username;
	}
    
    function system_showAccountMessage($username) {
		if (($pos = string_strpos($username, "::")) !== false) {
			$auxUsername = explode("::", $username);
            $message = system_showText(LANG_SITEMGR_FOREIGN_ACC1)." ".ucfirst($auxUsername[0]).". ".system_showText(LANG_SITEMGR_FOREIGN_ACC2);
            return $message;
		} else {
            return false;
        }
	}

	function system_registerForeignAccount($authArray, $accountType, $attach_account = false, $email_notification = SYSTEM_NEW_PROFILE) {

		unset($foreignAccount);

		if (!$authArray) return false;
		if (!is_array($authArray)) return false;
		if (!$accountType) return false;

		unset($auth);

		if ($accountType == "openid") {
			if (!$authArray["openid_identity"]) return false;
			if ((string_strpos($authArray["openid_identity"], "http://") === false) && (string_strpos($authArray["openid_identity"], "https://") === false)) return false;
			$foreignAccount["username"] = $accountType."::".$authArray["openid_identity"];
			$openidURL = $authArray["openid_identity"];
			foreach($authArray as $key=>$value) {
				$auth[] = $key."=".$value;
				if ($key == "openid_sreg_email") {
					if ($value) {
						$foreignAccount["email"] = $value;
						$foreignAccount["foreignaccount_done"] = "y";
					}
				} elseif ($key == "openid_sreg_fullname") {
					if ($value) {
						if (string_strpos($value, " ") !== false) {
							$foreignAccount["first_name"] = string_substr($value, 0, string_strpos($value, " "));
							$foreignAccount["last_name"] = string_substr($value, string_strrpos($value, " ")+1);
						} else {
							$foreignAccount["last_name"] = $value;
						}
					}
				}
			}
		} elseif ($accountType == "facebook") {
			$thisusername = $authArray["first_name"].$authArray["last_name"];
			$thisusername = preg_replace('/[^0-9a-zA-Z]/i', '', $thisusername);
			$thisusername = string_strtolower($thisusername);
			$foreignAccount["facebook_username"] = $accountType."::".$thisusername."_".$authArray["uid"];

			if (!$attach_account){
				$foreignAccount["username"] = $accountType."::".$thisusername."_".$authArray["uid"];
				$foreignAccount["first_name"] = $authArray["first_name"];
				$foreignAccount["last_name"] = $authArray["last_name"];
			} else{
				/*
				 * Get account_id to update
				 */
				unset($accountObj);
				$accountObj = new Account($authArray["account_id"]);
				$foreignAccount["username"] = $accountObj->getNumber("username");

				/*
				 * Prepare $foreignAccount with edirectory information
				 */
				foreach ($accountObj as $key => $value) {
					$foreignAccount[$key] = $value;
				}

				unset($contactObj);
				$foreignAccount["facebook_username"] = $accountType."::".$thisusername."_".$authArray["uid"];

				$facebookUID = $authArray["uid"];

				$contactObj = new Contact($accountObj->getNumber("id"));
				foreach ($contactObj as $key => $value) {
					$foreignAccount[$key] = $value;
				}

				/*
				 * Check if needs do update on eDirectory account
				 */
				if($authArray["facebook_action"] == "facebook_import"){
					$foreignAccount["first_name"] = $authArray["first_name"];
					$foreignAccount["last_name"] = $authArray["last_name"];
				}

				$auxFirstName = $authArray["first_name"]; 
				$auxLastName = $authArray["last_name"]; 

				$foreignAccount["foreignaccount_done"] = "y";
			}

			foreach($authArray as $key=>$value) {
				$auth[] = $key."=".$value;
			}
		} elseif ($accountType == "google") {
			$foreignAccount["username"] = $accountType."::".$authArray["email"];
			$foreignAccount["first_name"] = $authArray["first_name"];
			$foreignAccount["last_name"] = $authArray["last_name"];
			foreach($authArray as $key=>$value) {
				$auth[] = $key."=".$value;
			}
		}

		$foreignAccount["foreignaccount"] = "y";
		$foreignAccount["foreignaccount_auth"] = implode(" || ", $auth);

		if ($accountType == "facebook"){
			$sql = "SELECT account_id FROM Profile WHERE facebook_uid = ".$authArray["uid"];

			$db = db_getDBObject(DEFAULT_DB, true);
			$result = $db->query($sql);

			if (mysql_num_rows($result)>0){
				$account = db_getFromDB("account", "facebook_username", db_formatString($foreignAccount["username"]));
			} else {
				$account = db_getFromDB("account", "username", db_formatString($foreignAccount["username"]));
			}

		} else {
			$account = db_getFromDB("account", "username", db_formatString($foreignAccount["username"]));
		}

		if (!($account->getNumber("id"))) {

			$info = image_getImageSizeByURL($authArray["picture"]);

			image_getNewDimension(100, 100, $info[0], $info[1], $newWidth, $newHeight);

			$account = new Account($foreignAccount);
			if ($authArray["email"]) $account->setString("foreignaccount_done", "y");
			$account->save();
			$account->setForeignAccountAuth($foreignAccount["foreignaccount_auth"]);
			
			$contact = new Contact($foreignAccount);
			$contact->setNumber("account_id", $account->getNumber("id"));
			if ($authArray["email"]) {
				$contact->setString("email", $authArray["email"]);
            }

			$contact->save();
			
			$profile = new Profile();
			
			####################################################################################################
			####################################################################################################
			####################################################################################################
			# E-mail notify
			setting_get("sitemgr_send_email",$sitemgr_send_email);
			setting_get("sitemgr_email",$sitemgr_email);
			$sitemgr_emails = explode(",",$sitemgr_email);
			setting_get("sitemgr_account_email",$sitemgr_account_email);
			$sitemgr_account_emails = explode(",",$sitemgr_account_email);
			// sending e-mail to user //////////////////////////////////////////////////////////////////////////
			if ($emailNotificationObj = system_checkEmail($email_notification)) {
				$subject = $emailNotificationObj->getString("subject");
				$body = $emailNotificationObj->getString("body");
				if ($accountType == "openid") $login_info = trim(system_showText(LANG_LABEL_OPENIDURL)).": ".$openidURL;
				if ($accountType == "facebook") $login_info = string_ucwords(system_showText(LANG_LABEL_FACEBOOK_ACCT)).": ".$contact->getString("email");
				if ($accountType == "google") $login_info = string_ucwords(system_showText(LANG_LABEL_GOOGLE_ACCT)).": ".$contact->getString("email");
				$body = str_replace("ACCOUNT_LOGIN_INFORMATION",$login_info,$body);
				$body = system_replaceEmailVariables($body, $account->getNumber("id"), 'account');
				$subject = system_replaceEmailVariables($subject, $account->getNumber("id"), 'account');
				$body = html_entity_decode($body);
				$subject = html_entity_decode($subject);
				system_mail($contact->getString("email"), $subject, $body, EDIRECTORY_TITLE." <$sitemgr_email>", $emailNotificationObj->getString("content_type"), "", $emailNotificationObj->getString("bcc"), $error);
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////
			// site manager warning message /////////////////////////////////////
			$sitemgr_msg = "
				<html>
					<head>
						<style>
							.email_style_settings{
								font-size:12px;
								font-family:Verdana, Arial, Sans-Serif;
								color:#000;
							}
						</style>
					</head>
					<body>
						<div class=\"email_style_settings\">
							Site Manager,<br /><br />
							A new account was created in ".EDIRECTORY_TITLE.".<br />
							Please review the account information below:<br /><br />";
							$sitemgr_msg .= "<b>Username: </b>".system_showAccountUserName($account->getString("username"))."<br />";
							$sitemgr_msg .= "<b>First Name: </b>".$contact->getString("first_name")."<br />";
							$sitemgr_msg .= "<b>Last Name: </b>".$contact->getString("last_name")."<br />";
							$sitemgr_msg .= "<b>Company: </b>".$contact->getString("company")."<br />";
							$sitemgr_msg .= "<b>Address: </b>".$contact->getString("address")." ".$contact->getString("address2")."<br />";
							$sitemgr_msg .= "<b>City: </b>".$contact->getString("city")."<br />";
							$sitemgr_msg .= "<b>State: </b>".$contact->getString("state")."<br />";
							$sitemgr_msg .= "<b>".string_ucwords(ZIPCODE_LABEL).": </b>".$contact->getString("zip")."<br />";
							$sitemgr_msg .= "<b>Phone: </b>".$contact->getString("phone")."<br />";
							$sitemgr_msg .= "<b>Fax: </b>".$contact->getString("fax")."<br />";
							$sitemgr_msg .= "<b>Email: </b>".$contact->getString("email")."<br />";
							$sitemgr_msg .= "<b>URL: </b>".$contact->getString("url")."<br />";
							$sitemgr_msg .="<br /><a href=\"".DEFAULT_URL."/".SITEMGR_ALIAS."/account/view.php?id=".$account->getNumber("id")."\" target=\"_blank\">".DEFAULT_URL."/".SITEMGR_ALIAS."/account/view.php?id=".$account->getNumber("id")."</a><br /><br />
						</div>
					</body>
				</html>";
			if ($sitemgr_send_email == "on") {
				if ($sitemgr_emails[0]) {
					foreach ($sitemgr_emails as $sitemgr_email) {
						system_mail($sitemgr_email, "[".EDIRECTORY_TITLE."] Account Notification", $sitemgr_msg, EDIRECTORY_TITLE." <$sitemgr_email>", "text/html", '', '', $error);
					}
				}
			}
			if ($sitemgr_account_emails[0]) {
				foreach ($sitemgr_account_emails as $sitemgr_account_email) {
					system_mail($sitemgr_account_email, "[".EDIRECTORY_TITLE."] Account Notification", $sitemgr_msg, EDIRECTORY_TITLE." <$sitemgr_account_email>", "text/html", '', '', $error);
				}
			}
			////////////////////////////////////////////////////////////////////
			####################################################################################################
			####################################################################################################
			####################################################################################################

		} else {
			$contact = new Contact($account->getNumber("id"));
			$profile = new Profile($account->getNumber("id"));

			if ($profile->getNumber("account_id") && $attach_account) {
				$foreignAccount["id"] = $account->getNumber("id");
				$info = image_getImageSizeByURL($authArray["picture"]);
				image_getNewDimension(100, 100, $info[0], $info[1], $newWidth, $newHeight);
				$account = new Account($foreignAccount);
				$account->save();
				$account->setForeignAccountAuth($foreignAccount["foreignaccount_auth"], $auxFirstName, $auxLastName);
				$contact = new Contact($foreignAccount);
				$contact->setNumber("account_id", $account->getNumber("id"));
				$contact->save();
			}
		}
		
		/*
		 * Update Account and Contact tables
		 */
		
		$profile->setNumber("account_id", $account->getNumber("id"));
		$profile->setString("facebook_uid", $authArray["uid"]);
		if (!$attach_account || ($attach_account && $authArray["facebook_action"] == "facebook_import")){
			$profile->setString("nickname", $authArray["nickname"] ? $authArray["nickname"] : $contact->getString("first_name")." ".$contact->getString("last_name"));
			$profile->setString("birth_city", $authArray["home_town"]);
			$profile->setString("favorite_books", $authArray["favorite_books"]);
			$profile->setString("favorite_movies", $authArray["favorite_movies"]);
			$profile->setString("favorite_musics", $authArray["favorite_musics"]);
			$profile->setString("personal_message", $authArray["personal_message"]);
			$profile->setString("favorite_sports", $authArray["favorite_sports"]);
			$profile->setDate("birth_date", $authArray["birthday_date"]);
			$profile->setString("facebook_image", $authArray["picture"]);
			$profile->setNumber("facebook_image_width", $newWidth ? $newWidth : 100);
			$profile->setNumber("facebook_image_height", $newHeight ? $newHeight : 100);
			$profile->setString("location", $authArray["location"]);
		}
		$profile->Save();

		$accDomain = new Account_Domain($account->getNumber("id"), SELECTED_DOMAIN_ID);
		$accDomain->Save();
		$accDomain->saveOnDomain($account->getNumber("id"), $account, $contact, $profile);

		if ($account->getNumber("id")) {
			if ($account_type == "facebook"){
				sess_registerAccountInSession($account->getString("facebook_username"), true);
			} else {
				sess_registerAccountInSession($account->getString("username"));
			}
			return true;
		}

		return false;

	}

    function system_addTinyMCE($lang, $mode, $theme, $field_name, $textRows, $textCols, $width, $content, $include_script = true) {
        ?>

        <!-- TinyMCE -->
        <?
        if ( $include_script ) { ?>
            <script type="text/javascript" src="<?=DEFAULT_URL?>/includes/tiny_mce/tiny_mce_src.js"></script> <?
        } ?>
        <script type="text/javascript">
            // Default skin
			var inlinePopUps = "inlinepopups,";
			if ($.browser.msie && $.browser.version == 9){
				inlinePopUps = "";
			}
            tinyMCE.init({
                // General options
                mode : "<?=$mode?>",
                elements : "<?=$field_name?>",
                theme : "<?=$theme?>",
                width: "<?=$width?>",
                plugins : "imagemanager,safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras," + inlinePopUps + "template,autosave",
//                language : '<?=$lang?>',
                language : 'en',
				extended_valid_elements : "iframe[src|width|height|name|align]",
                // Theme options
                theme_advanced_buttons1 : "formatselect,fontselect,fontsizeselect,|,undo,redo,|,bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pasteword,|,link,unlink,",
                theme_advanced_buttons2 : "anchor,image,media,emotions,tablecontrols,bullist,numlist,|,print,fullscreen,|,attribs,code,styleprops,preview,|,forecolor,backcolor",
                theme_advanced_buttons3 : "",
                theme_advanced_buttons4 : "",
                theme_advanced_buttons5 : "",

                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_resizing : false,
                convert_urls : false
            });

        </script>
        <!-- /TinyMCE -->
        <textarea id="<?=$field_name?>" name="<?=$field_name?>" rows="<?=$textRows?>" cols="<?=$textCols?>" style="width: 80%"><?=$content?></textarea>
        <?
    }

    function  system_displayTinyMCE($txId) {

    	$return_editor = "
    	<!-- TinyMCE -->
    	<script type=\"text/javascript\">

    		//tinyMCE.execCommand('mceRemoveControl', false, '$txId-1');
    		//tinyMCE.execCommand('mceFocus', false, '$txId');
    		tinyMCE.execCommand('mceAddControl', false, '$txId');

    	</script>
    	<!-- /TinyMCE -->";
    	echo $return_editor;
	}
	
	function system_getLastWeek(){

		$week = date('W');
		$year = date('Y');

		$lastweek = $week-1;

		if ($lastweek==0){
			$week = 52;
			$year--;
		}

		$lastweek = sprintf("%02d", $lastweek);
		for ($i=1; $i <= 7; $i++){
			$arrdays[] = strtotime("$year". "W$lastweek"."$i");
		}
		return $arrdays;

	}

	function system_getRevenue() {
		//$one_year_ago = date("Y-m-d H:i:s", strtotime("-1 years"));
		$one_month_ago = date("Y-m-d H:i:s", strtotime("-1 months"));
		$one_week_ago = date("Y-m-d H:i:s", strtotime("-1 weeks"));

		$dbObj = db_getDBObJect(DEFAULT_DB,true);
		$dbObjSecond = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID,$dbObj);
		
		/*
		 * Calculate last year revenue
		 */

//		$sql = "SELECT SUM(transaction_amount) AS total FROM Payment_Log WHERE transaction_status in ('Completed', 'Approved', 'Accepted', 'Success', 'SIMPLEPAYSUCCESS', 'Y') AND transaction_datetime > '".$one_year_ago."'";
//		$result = $dbObjSecond->query($sql);
//		if (mysql_num_rows($result) > 0) {
//			$row = mysql_fetch_assoc($result);
//			$total_payment_year = $row['total'];
//		}
//		$sql = "SELECT SUM(amount) AS total FROM Invoice WHERE status = 'R' AND payment_date > '".$one_year_ago."'";
//		$result = $dbObjSecond->query($sql);
//		if (mysql_num_rows($result) > 0) {
//			$row = mysql_fetch_assoc($result);
//			$total_invoice_year = $row['total'];
//		}
//		$total_year = $total_payment_year + $total_invoice_year;
		
		/*
		 * Calculate last month revenue
		 */

		$sql = "SELECT SUM(transaction_amount) AS total FROM Payment_Log WHERE transaction_status in ('Completed', 'Approved', 'Accepted', 'Success', 'SIMPLEPAYSUCCESS', 'Y') AND transaction_datetime > '".$one_month_ago."'";
		$result = $dbObjSecond->query($sql);
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			$total_payment_month = $row['total'];
		}
		$sql = "SELECT SUM(amount) AS total FROM Invoice WHERE status = 'R' AND payment_date > '".$one_month_ago."'";
		$result = $dbObjSecond->query($sql);
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			$total_invoice_month = $row['total'];
		}
		$total_month = $total_payment_month + $total_invoice_month;
		
		/*
		 * Calculate last week revenue
		 */

		$sql = "SELECT SUM(transaction_amount) AS total FROM Payment_Log WHERE transaction_status in ('Completed', 'Approved', 'Accepted', 'Success', 'SIMPLEPAYSUCCESS', 'Y') AND transaction_datetime > '".$one_week_ago."'";
		$result = $dbObjSecond->query($sql);
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			$total_payment_week = $row['total'];
		}
		$sql = "SELECT SUM(amount) AS total FROM Invoice WHERE status = 'R' AND payment_date > '".$one_week_ago."'";
		$result = $dbObjSecond->query($sql);
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			$total_invoice_week = $row['total'];
		}
		$total_week = $total_payment_week + $total_invoice_week;
		
		//$array_revenue["year"] = format_money($total_year);
		$array_revenue["month"] = format_money($total_month);
		$array_revenue["week"] = format_money($total_week);
		
		return $array_revenue;
    }

	function system_freqActions_returnLabelLink($session, &$label, &$link) {

		if ($session=="listing_manage") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".LISTING_FEATURE_FOLDER."/index.php";
		}elseif ($session=="listing_add") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".LISTING_FEATURE_FOLDER."/listinglevel.php";
		}elseif ($session=="listing_search") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".LISTING_FEATURE_FOLDER."/search.php";
		}elseif ($session=="listingcateg_manage") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/listingcategs/index.php";
		}elseif ($session=="listingcateg_add") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/listingcategs/category.php";
		}elseif ($session=="listing_featuredcateg") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_FEATUREDCATEGORY_PLURAL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/listingcategs/featured.php";
		}elseif ($session=="listingcateg_disabled") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_DISABLED2;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/listingcategs/disabled.php";
		}elseif ($session=="reviewlisting_manage") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_REVIEWS." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/review/index.php?item_type=listing";
		}elseif ($session=="claimlisting_manage") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_CLAIMED." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/claim/";
		}elseif ($session=="claimlisting_search") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_CLAIMED." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/claim/search.php";
		}elseif ($session=="listingtemplate_manage") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_MENU_TEMPLATES." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/listingtemplate/index.php";
		}elseif ($session=="listingtemplate_add") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_MENU_TEMPLATES." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/listingtemplate/template.php";
		}elseif ($session=="listingtemplate_search") {
			$label = LANG_SITEMGR_NAVBAR_LISTING." &rsaquo; ".LANG_SITEMGR_MENU_TEMPLATES." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/listingtemplate/search.php";
		}elseif ($session=="banner_manage") {
			$label = LANG_SITEMGR_NAVBAR_BANNER." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/index.php";
		}elseif ($session=="banner_add") {
			$label = LANG_SITEMGR_NAVBAR_BANNER." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/add.php";
		}elseif ($session=="banner_location_category") {
			$label = LANG_SITEMGR_NAVBAR_BANNER." &rsaquo; ".LANG_SITEMGR_MENU_FEATURED;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/featured.php";
		}elseif ($session=="banner_search") {
			$label = LANG_SITEMGR_NAVBAR_BANNER." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/search.php";
		}elseif ($session=="event_manage") {
			$label = LANG_SITEMGR_NAVBAR_EVENT." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".EVENT_FEATURE_FOLDER."/index.php";
		}elseif ($session=="event_add") {
			$label = LANG_SITEMGR_NAVBAR_EVENT." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".EVENT_FEATURE_FOLDER."/eventlevel.php";
		}elseif ($session=="event_search") {
			$label = LANG_SITEMGR_NAVBAR_EVENT." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".EVENT_FEATURE_FOLDER."/search.php";
		}elseif ($session=="eventcateg_manage") {
			$label = LANG_SITEMGR_NAVBAR_EVENT." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/eventcategs/index.php";
		}elseif ($session=="eventcateg_add") {
			$label = LANG_SITEMGR_NAVBAR_EVENT." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/eventcategs/category.php";
		}elseif ($session=="event_featuredcateg") {
			$label = LANG_SITEMGR_NAVBAR_EVENT." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_FEATUREDCATEGORY_PLURAL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/eventcategs/featured.php";
		}elseif ($session=="eventcateg_disabled") {
			$label = LANG_SITEMGR_NAVBAR_EVENT." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_DISABLED2;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/eventcategs/disabled.php";
		}elseif ($session=="classified_manage") {
			$label = LANG_SITEMGR_NAVBAR_CLASSIFIED." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".CLASSIFIED_FEATURE_FOLDER."/index.php";
		}elseif ($session=="classified_add") {
			$label = LANG_SITEMGR_NAVBAR_CLASSIFIED." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".CLASSIFIED_FEATURE_FOLDER."/classifiedlevel.php";
		}elseif ($session=="classified_search") {
			$label = LANG_SITEMGR_NAVBAR_CLASSIFIED." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".CLASSIFIED_FEATURE_FOLDER."/search.php";
		}elseif ($session=="classifiedcateg_manage") {
			$label = LANG_SITEMGR_NAVBAR_CLASSIFIED." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/classifiedcategs/index.php";
		}elseif ($session=="classifiedcateg_add") {
			$label = LANG_SITEMGR_NAVBAR_CLASSIFIED." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/classifiedcategs/category.php";
		}elseif ($session=="classified_featuredcateg") {
			$label = LANG_SITEMGR_NAVBAR_CLASSIFIED." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_FEATUREDCATEGORY_PLURAL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/classifiedcategs/featured.php";
		}elseif ($session=="classifiedcateg_disabled") {
			$label = LANG_SITEMGR_NAVBAR_CLASSIFIED." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_DISABLED2;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/classifiedcategs/disabled.php";
		}elseif ($session=="article_manage") {
			$label = LANG_SITEMGR_NAVBAR_ARTICLE." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".ARTICLE_FEATURE_FOLDER."/index.php";
		}elseif ($session=="article_add") {
			$label = LANG_SITEMGR_NAVBAR_ARTICLE." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".ARTICLE_FEATURE_FOLDER."/article.php";
		}elseif ($session=="article_search") {
			$label = LANG_SITEMGR_NAVBAR_ARTICLE." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".ARTICLE_FEATURE_FOLDER."/search.php";
		}elseif ($session=="articlecateg_manage") {
			$label = LANG_SITEMGR_NAVBAR_ARTICLE." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/articlecategs/index.php";
		}elseif ($session=="articlecateg_add") {
			$label = LANG_SITEMGR_NAVBAR_ARTICLE." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/articlecategs/category.php";
		}elseif ($session=="article_featuredcateg") {
			$label = LANG_SITEMGR_NAVBAR_ARTICLE." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_FEATUREDCATEGORY_PLURAL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/articlecategs/featured.php";
		}elseif ($session=="articlecateg_disabled") {
			$label = LANG_SITEMGR_NAVBAR_ARTICLE." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_DISABLED2;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/articlecategs/disabled.php";
		}elseif ($session=="reviewarticle_manage") {
			$label = LANG_SITEMGR_NAVBAR_ARTICLE." &rsaquo; ".LANG_SITEMGR_REVIEWS." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/review/index.php?item_type=article";
		}elseif ($session=="reviewpromotion_manage") {
			$label = LANG_SITEMGR_NAVBAR_PROMOTION." &rsaquo; ".LANG_SITEMGR_REVIEWS." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/review/index.php?item_type=promotion";
		}elseif ($session=="promotion_manage") {
			$label = LANG_SITEMGR_NAVBAR_PROMOTION." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".PROMOTION_FEATURE_FOLDER."/index.php";
		}elseif ($session=="promotion_add") {
			$label = LANG_SITEMGR_NAVBAR_PROMOTION." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".PROMOTION_FEATURE_FOLDER."/deal.php";
		}elseif ($session=="promotion_search") {
			$label = LANG_SITEMGR_NAVBAR_PROMOTION." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".PROMOTION_FEATURE_FOLDER."/search.php";
		}elseif ($session=="content_general") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_MENU_GENERAL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/";
		}elseif ($session=="content_htmleditor") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_SETTINGS_HTMLEDITOR;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor.php";
		}elseif ($session=="content_navigation") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_SETTINGS_NAVIGATION;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/navigation.php";
		}elseif ($session=="content_header") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_MENU_GENERAL." &rsaquo; ".LANG_SITEMGR_HEADER;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/content_header.php";
		}elseif ($session=="content_footer") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_MENU_GENERAL." &rsaquo; ".LANG_SITEMGR_FOOTER;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/content_footer.php";
		}elseif ($session=="content_noimage") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_MENU_GENERAL." &rsaquo; ".LANG_SITEMGR_CONTENT_DEFAULTIMAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/content_noimage.php";
		}elseif ($session=="content_icon") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_MENU_GENERAL." &rsaquo; ".LANG_SITEMGR_CONTENT_ICON;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/content_icon.php";
		}elseif ($session=="content_advertisement") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_ADVERTISEMENT;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/advertisement.php";
		}elseif ($session=="content_member") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_MEMBER;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/member.php";
		}elseif ($session=="content_custom") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_MENU_CUSTOM;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/client.php";
		}elseif ($session=="content_listing") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_LISTING;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/listing.php";
		}elseif ($session=="content_promotion") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_PROMOTION;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/deal.php";
		}elseif ($session=="content_event") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_EVENT;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/event.php";
		}elseif ($session=="content_classified") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_CLASSIFIED;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/classified.php";
		}elseif ($session=="content_article") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_ARTICLE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/article.php";
		}elseif ($session=="content_blog") {
			$label = LANG_SITEMGR_MENU_SITECONTENT." &rsaquo; ".LANG_SITEMGR_BLOG;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/content/blog.php";
		}elseif ($session=="seocenter_manage") {
			$label = LANG_SITEMGR_NAVBAR_SEOCENTER;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/seocenter.php";
		}elseif ($session=="account_manage") {
			$label = LANG_SITEMGR_NAVBAR_MEMBERACCOUNTS." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/account/index.php";
		}elseif ($session=="account_add") {
			$label = LANG_SITEMGR_NAVBAR_MEMBERACCOUNTS." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/account/account.php";
		}elseif ($session=="account_search") {
			$label =LANG_SITEMGR_NAVBAR_MEMBERACCOUNTS." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/account/search.php";
		}elseif ($session=="smaccount_manage") {
			$label = LANG_SITEMGR_NAVBAR_SITEMGRACCOUNTS." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/smaccount/index.php";
		}elseif ($session=="smaccount_add") {
			$label = LANG_SITEMGR_NAVBAR_SITEMGRACCOUNTS." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/smaccount/smaccount.php";
		}elseif ($session=="smaccount_search") {
			$label = LANG_SITEMGR_NAVBAR_SITEMGRACCOUNTS." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/smaccount/search.php";
		}elseif ($session=="location1_manage") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION1_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_1/index.php";
		}elseif ($session=="location1_add") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION1_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_1/location_1.php?operation=add";
		}elseif ($session=="location1_featured") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION1_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_LABEL_FEATURED;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_1/featured.php";
		}elseif ($session=="location2_manage") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION2_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_2/index.php";
		}elseif ($session=="location2_add") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION2_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_2/location_2.php?operation=add";
		}elseif ($session=="location2_featured") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION2_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_LABEL_FEATURED;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_2/featured.php";
		}elseif ($session=="location3_manage") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION3_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_3/index.php";
		}elseif ($session=="location3_add") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION3_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_3/location_3.php?operation=add";
		}elseif ($session=="location3_featured") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION3_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_LABEL_FEATURED;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_3/featured.php";
		}elseif ($session=="location4_manage") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION4_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_4/index.php";
		}elseif ($session=="location4_add") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION4_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_4/location_4.php?operation=add";
		}elseif ($session=="location4_featured") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION4_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_LABEL_FEATURED;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_4/featured.php";
		}elseif ($session=="location5_manage") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION5_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_5/index.php";
		}elseif ($session=="location5_add") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION5_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_5/location_5.php?operation=add";
		}elseif ($session=="location5_featured") {
			$label = constant("LANG_SITEMGR_NAVBAR_".LOCATION5_SYSTEM_PLURAL)." &rsaquo; ".LANG_SITEMGR_LABEL_FEATURED;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_5/featured.php";
		}elseif ($session=="import_home") {
			$label = LANG_SITEMGR_NAVBAR_DATA_MANAGEMENT." &rsaquo; ".LANG_SITEMGR_IMPORT;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/import/";
		}elseif ($session=="import_log") {
			$label = LANG_SITEMGR_NAVBAR_DATA_MANAGEMENT." &rsaquo; ".LANG_SITEMGR_IMPORT." &rsaquo; ".LANG_SITEMGR_LOG;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/import/importlog.php";
		}elseif ($session=="export_data") {
			$label = LANG_SITEMGR_NAVBAR_DATA_MANAGEMENT." &rsaquo; ".LANG_SITEMGR_EXPORT." &rsaquo; ".LANG_SITEMGR_DATA;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/export/";
		}elseif ($session=="export_paymentrecords") {
			$label = LANG_SITEMGR_NAVBAR_DATA_MANAGEMENT." &rsaquo; ".LANG_SITEMGR_EXPORT." &rsaquo; ".LANG_SITEMGR_EXPORT_PAYMENTRECORDS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/export/payment";
		}elseif ($session=="export_downloadfiles") {
			$label = LANG_SITEMGR_NAVBAR_DATA_MANAGEMENT." &rsaquo; ".LANG_SITEMGR_EXPORT." &rsaquo; ".LANG_SITEMGR_EXPORT_DOWNLOAD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/export/download.php";
		}elseif ($session=="transaction_history") {
			$label = LANG_SITEMGR_TRANSACTION." &rsaquo; ".LANG_SITEMGR_HISTORY;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/transactions/";
		}elseif ($session=="transaction_search") {
			$label = LANG_SITEMGR_TRANSACTION." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/transactions/search.php";
		}elseif ($session=="invoice_history") {
			$label = LANG_SITEMGR_INVOICE." &rsaquo; ".LANG_SITEMGR_HISTORY;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/invoices/";
		}elseif ($session=="invoice_search") {
			$label = LANG_SITEMGR_INVOICE." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/invoices/search.php";
		}elseif ($session=="custominvoice_manage") {
			$label = LANG_SITEMGR_CUSTOMINVOICE." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/custominvoices/index.php";
		}elseif ($session=="custominvoice_add") {
			$label = LANG_SITEMGR_CUSTOMINVOICE." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/custominvoices/custominvoice.php";
		}elseif ($session=="custominvoice_search") {
			$label = LANG_SITEMGR_CUSTOMINVOICE." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/custominvoices/search.php";
		}elseif ($session=="prefs_pricing") {
			$label = LANG_SITEMGR_PAYMENTSETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_PRICING;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/pricing.php";
		}elseif ($session=="prefs_paymentgateway") {
			$label = LANG_SITEMGR_PAYMENTSETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_PAYMENT_PAYMENTGATEWAY;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/paymentgateway.php";
		}elseif ($session=="prefs_invoiceinformation") {
			$label = LANG_SITEMGR_PAYMENTSETTINGS." &rsaquo; ".LANG_SITEMGR_INVOICEINFORMATION;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/invoice.php";
		}elseif ($session=="discountcode_manage") {
			$label = LANG_SITEMGR_PROMOTIONALCODE." &rsaquo; ".LANG_SITEMGR_MANAGE;;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/discountcode/index.php";
		}elseif ($session=="discountcode_add") {
			$label = LANG_SITEMGR_PROMOTIONALCODE." &rsaquo; ".LANG_SITEMGR_ADD;;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/discountcode/discountcode.php";
		}elseif ($session=="report_system") {
			$label = LANG_SITEMGR_NAVBAR_REPORTS." &rsaquo; ".LANG_SITEMGR_NAVBAR_SYSTEMREPORT;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/reports/systemreport.php";
		}elseif ($session=="report_statistic") {
			$label = LANG_SITEMGR_NAVBAR_REPORTS." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/reports/statisticreport.php";
		}elseif ($session=="prefs_theme") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_MENU_THEMES;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/theme.php";
		}elseif ($session=="prefs_signinoptions") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_MENU_LOGINOPTIONS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/foreignaccount.php";
		}elseif ($session=="prefs_langcenter") {
			$label = LANG_SITEMGR_NAVBAR_LANGUAGECENTER;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/langcenter/index.php";
		}elseif ($session=="prefs_faq") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_FREQUENTLYASKEDQUESTIONS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/faq.php";
		}elseif ($session=="prefs_faqadd") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_FREQUENTLYASKEDQUESTIONS." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/faqadd.php";
		}elseif ($session=="prefs_commenting") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_COMMENTING_OPTIONS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/comments.php";
		}elseif ($session=="prefs_robotsfilter") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_ROBOTS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/robotsfilter.php";
		}elseif ($session=="prefs_tax") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_TAX;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/tax.php";
		}elseif ($session=="prefs_maintenancemode") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SETTING_MAINTENANCE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/maintenance.php";
		}elseif ($session=="prefs_twitter") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_TWITTER;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/twittersettings.php";
		}elseif ($session=="prefs_featuredcategory") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_FEATUREDCATEGORY_PLURAL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/featuredcategory.php";
		}elseif ($session=="prefs_aprovalrequirement") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_APPROVAL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/approvalrequirement.php";
		}elseif ($session=="prefs_locations") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_NAVBAR_LOCATIONS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/location.php";
		}elseif ($session=="prefs_googlemaps") {
			$label = LANG_SITEMGR_NAVBAR_GOOGLESETTINGS." &rsaquo; ".LANG_SITEMGR_GOOGLEMAPS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/googleprefs/googlemaps.php";
		}elseif ($session=="prefs_googleads") {
			$label = LANG_SITEMGR_NAVBAR_GOOGLESETTINGS." &rsaquo; ".LANG_SITEMGR_GOOGLEADS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/googleprefs/googleads.php";
		}elseif ($session=="prefs_googleanalytics") {
			$label = LANG_SITEMGR_NAVBAR_GOOGLESETTINGS." &rsaquo; ".LANG_SITEMGR_GOOGLEANALYTICS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/googleprefs/googleanalytics.php";
		}elseif ($session=="prefs_systememail") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SYSTEMEMAIL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/email.php";
		}elseif ($session=="prefs_emailnotific") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ". LANG_SITEMGR_MENU_EMAILNOTIF;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/emailnotifications/";
		}elseif ($session=="prefs_emailsendconf") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_EMAILCONF_EMAILSENDINGCONFIGURATION;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/emailconfig.php";
		}elseif ($session=="prefs_designation") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_EDITORCHOICE_DESIGNATIONS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/editorchoice.php";
		}elseif ($session=="prefs_managelevel") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_LEVELS_MENULABEL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/levels.php";
		}elseif ($session=="prefs_promotion") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_PROMOTION;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/deal.php";
		}elseif ($session=="prefs_claim") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_CLAIM_CLAIMS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/claim.php";
		}elseif ($session=="prefs_api") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_API;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/api.php";
		}elseif ($session=="prefs_modules") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SETTINGS_MANAGE_MODULES;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/modules.php";
		}elseif ($session=="post_add") {
			$label = LANG_MENU_BLOG." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER."/blog.php";
		}elseif ($session=="blog_manage") {
			$label = LANG_MENU_BLOG." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER."/index.php";
		}elseif ($session=="post_search") {
			$label = LANG_MENU_BLOG." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER."/search.php";
		}elseif ($session=="blogcateg_manage") {
			$label = LANG_MENU_BLOG." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/blogcategs/index.php";
		}elseif ($session=="blogcateg_add") {
			$label = LANG_MENU_BLOG." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/blogcategs/category.php";
		}elseif ($session=="blog_featuredcateg") {
			$label = LANG_SITEMGR_BLOG." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_FEATUREDCATEGORY_PLURAL;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/blogcategs/featured.php";
		}elseif ($session=="blogcateg_disabled") {
			$label = LANG_SITEMGR_BLOG." &rsaquo; ".LANG_SITEMGR_CATEGORIES." &rsaquo; ".LANG_SITEMGR_DISABLED2;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/blogcategs/disabled.php";
		}elseif ($session=="comments_blog") {
			$label = LANG_MENU_BLOG." &rsaquo; ".LANG_BLOG_COMMENTS." &rsaquo; ".LANG_SITEMGR_MANAGE;;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER."/comments/index.php";
		}elseif ($session=="prefs_socialnetwork") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_SOCIALNETWORK;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/visitorprofile.php";
		}elseif ($session=="prefs_twilio") {
			$label = LANG_SITEMGR_MENU_SETTINGS." &rsaquo; ".LANG_SITEMGR_TWILIO;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/twilio.php";
		}elseif ($session=="domain_manage") {
			$label = LANG_SITEMGR_DOMAIN_PLURAL." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/domain/index.php";
		}elseif ($session=="domain_add") {
			$label = LANG_SITEMGR_DOMAIN_PLURAL." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/domain/domain.php";
		}elseif ($session=="package_add") {
			$label = LANG_SITEMGR_PACKAGE_PLURAL." &rsaquo; ".LANG_SITEMGR_ADD;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/package/package.php";
		}elseif ($session=="package_manage") {
			$label = LANG_SITEMGR_PACKAGE_PLURAL." &rsaquo; ".LANG_SITEMGR_MANAGE;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/package/index.php";
		}elseif ($session=="package_search") {
			$label = LANG_SITEMGR_PACKAGE_PLURAL." &rsaquo; ".LANG_SITEMGR_SEARCH;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/package/search.php";
		}elseif ($session=="package_reports") {
			$label = LANG_SITEMGR_PACKAGE_PLURAL." &rsaquo; ".LANG_SITEMGR_NAVBAR_REPORTS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/package/reports.php";
		}elseif ($session=="sugar") {
			$label = LANG_SITEMGR_PLUGINS." &rsaquo; ".LANG_SITEMGR_NAVBAR_SUGARCRM;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/plugins/index.php";
		}elseif ($session=="wordpress") {
			$label = LANG_SITEMGR_PLUGINS." &rsaquo; ".LANG_SITEMGR_NAVBAR_WORDPRESS;
			$link = DEFAULT_URL."/".SITEMGR_ALIAS."/plugins/index.php?type=1";
		}else{
			$label = "";
			$link = "";
		}
		$label=ucwords($label);
	}

	function system_setFreqActions($session,$module) {
		$smaccount_id = sess_getSMIdFromSession();
		if (!$smaccount_id) $smaccount_id = 0;
		$sql = "SELECT rate FROM Frequently_Actions WHERE smaccount_id = ".$smaccount_id." AND session = '".$session."' AND `domain_id` = ".SELECTED_DOMAIN_ID;
		$db = db_getDBObject(DEFAULT_DB, true);
		$r = mysql_fetch_assoc($db->query($sql));
		$rate = $r['rate'];

		if ($rate)
			$sql = "UPDATE Frequently_Actions SET rate = ".($rate+1).", module = '".$module."' WHERE smaccount_id = ".$smaccount_id." AND session = '".$session."' AND `domain_id` = ".SELECTED_DOMAIN_ID;
		else
			$sql = "INSERT INTO Frequently_Actions (smaccount_id, domain_id, session, rate,module) VALUES (".$smaccount_id.", ".SELECTED_DOMAIN_ID.", '".$session."', 1,'".$module."')";
		$db->query($sql);
	}

	function getModuleUrl() {
		$ItemPath = "";
		if (string_strpos($_SERVER["HTTP_REFERER"], str_replace(NON_SECURE_URL, "", LISTING_DEFAULT_URL)) !== false) {
			$ItemPath = str_replace(NON_SECURE_URL, "", LISTING_DEFAULT_URL)."/";
		} elseif (string_strpos($_SERVER["HTTP_REFERER"], str_replace(NON_SECURE_URL, "", ARTICLE_DEFAULT_URL)) !== false) {
			$ItemPath = str_replace(NON_SECURE_URL, "", ARTICLE_DEFAULT_URL)."/";
		}

		return string_substr($ItemPath, 1, -1);
	}

	function system_showFreqActionsList() {
		$smaccount_id = sess_getSMIdFromSession();
		if (!$smaccount_id) $smaccount_id = 0;
		$sql = "SELECT rate, session, module FROM Frequently_Actions WHERE smaccount_id = ".$smaccount_id." AND `domain_id` = ".SELECTED_DOMAIN_ID." ORDER BY rate DESC LIMIT 10";
		$db = db_getDBObject(DEFAULT_DB, true);
		$r = $db->query($sql);
		if (mysql_num_rows($r)) { ?>
			<div class="recentActions">
                <ul>
                    <h1><?=system_showText(LANG_SITEMGR_FREQUENTACTIONS)?></h1>
                    <?
                    while ($row = mysql_fetch_assoc($r)) {
                        if (string_strpos($row["module"],"_FEATURE")){
                            $customConstant = (defined("CUSTOM_".$row["module"]) ? constant("CUSTOM_".$row["module"]) : "on");
                            if (constant($row["module"])== "on" && $customConstant == "on"){
                                system_freqActions_returnLabelLink($row["session"], $label, $link);
                                if ($label && $link) {
                                    echo  '<li><a href="'.$link.'">'.$label.'</a></li>';
                                }
                            }
                        } else {
                            system_freqActions_returnLabelLink($row["session"], $label, $link);
                            if ($label && $link) {
                                echo  '<li><a href="'.$link.'">'.$label.'</a></li>';
                            }
                        }
                    } ?>
                </ul>
            </div>
        <?
		}
	}

	function system_changeFeaturedAtribute($table, $ids, $featured="y") {
		if (isset($table) && isset($ids)) {
			$sql = "UPDATE ".$table." SET featured = '".$featured."' WHERE id IN (".$ids.")";
			$dbMain = db_getDBObject(DEFAULT_DB,true);
			$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID,$dbMain);
			$db->query($sql);
		}
	}

	function system_changeAtributeById($table, $atribute, $ids, $value = '', $domain_id = 1) {
		if (isset($table) && isset($ids) && isset($atribute)) {
			$sql = "UPDATE ".$table." SET ".$atribute." = '".$value."' WHERE id IN (".$ids.")";
			$dbMain = db_getDBObJect(DEFAULT_DB,true);
			$db = db_getDBObjectByDomainID($domain_id,$dbMain);
			$db->query($sql);
		}
	}

	function system_retrieveLocationRelationship ($_locations, $_location_level, &$_location_father_level, &$_location_child_level) {
		$location_key = array_search ($_location_level, $_locations);
		if ($location_key!==false) {
			if ($location_key==0) $_location_father_level = false; else $_location_father_level = $_locations[$location_key-1];
			if ($location_key==(count($_locations)-1)) $_location_child_level = false; else $_location_child_level = $_locations[$location_key+1];
		}
	}

	function system_buildLocationNodeParams($array, $limit_level=false, &$retrieveLastLocationName=false) {
		$_link_params = false;
		if ($array) {
			if (count($array) > 0) {
				ksort($array);
				foreach ($array as $name=>$value) {
					$pos = string_strpos($name, "location_");
					if (($pos !== false) && ($pos == 0)) {
						if ($value) {
							if (!$limit_level)
								$_link_params .= $name."=".$value."&";
							else {
								$current_level = string_substr($name, -1);
								if ($current_level<$limit_level) {
									$_link_params .= $name."=".$value."&";
									if ($retrieveLastLocationName) {
										$_locations = explode(",", EDIR_LOCATIONS);
										system_retrieveLocationRelationship ($_locations, $current_level, $_location_father_level, $_location_child_level);
										//if ($_location_child_level==$limit_level) {
											$locationInfo = db_getFromDB('location'.$current_level, 'id', $value, 1, '', 'array');
											$retrieveLastLocationName = $locationInfo['name'];
										//}
									}
								}
							}
						}
					}
				}
				$_link_params = string_substr($_link_params, 0, -1);
			}
		}
		return $_link_params;
	}

	function system_buildLocationBreadCrumb($_locations, $array, $limit_level, $redirect = "index.php", $extraInfo=false) {
		// showing link to location root
		if ($limit_level != $_locations[0]) {
			?><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/locations/location_<?=$_locations[0]?>/index.php"><?
		}
		echo system_showText(LANG_SITEMGR_NAVBAR_LOCATIONS);
		if ($limit_level != $_locations[0]) {
			?></a> &raquo;<?
		}
		$_link_params = false;

		// filling the gaps of the url path ///////////////
		if ((is_array($array)) and (count($array) > 0)) {
			$aux_max_level = 1;
			foreach ($array as $name=>$value) {
				$pos = string_strpos($name, "location_");
				if ($pos !== false) {
					$current_level = string_substr($name, -1);
					if (($current_level > $aux_max_level) and (in_array($current_level, $_locations)))
						$aux_max_level = $current_level;
				}
			}

			if ($array["location_".$aux_max_level] > 0) {
				$aux_location_path = db_getFromDB("location".($aux_max_level), "id", $array["location_".$aux_max_level], 1, "", "array");

				foreach ($aux_location_path as $name=>$value) {
					$pos = string_strpos($name, "location_");
					if (($pos !== false) and ($value>0)) {
						if (in_array(string_substr($name, -1), $_locations))
							$array[$name] = $value;
					}
				}
			}

			// calculating the real limit level _ according to the path available
			$aux_location_father_level = false;
			$aux_location_child_level = false;
			system_retrieveLocationRelationship ($_locations, $aux_max_level, $aux_location_father_level, $aux_location_child_level);
			$limit_level = $aux_location_child_level;

			ksort($array);
		}
		///////////////////////////////////////////////////

		$aux_array_breadcrumb = array();
		if ($array) {
			if (count($array) > 0) {
				foreach ($array as $name=>$value) {
					$pos = string_strpos($name, "location_");
					if (($pos !== false) && ($pos == 0)) {
						if ($value) {
							$current_level = string_substr($name, -1);
							system_retrieveLocationRelationship ($_locations, $current_level, $_location_father_level, $_location_child_level);
							if ($_location_father_level) {
								$locationName = true;
								$nodeParams = system_buildLocationNodeParams($array, $current_level, $locationName);
								if ($locationName === true)
									$aux_array_breadcrumb[] = LANG_NA."&raquo;";
								else
									$aux_array_breadcrumb[] = "<a href=\"".DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_".$current_level."/".$redirect."?".$nodeParams."\">".$locationName."</a>&raquo;";
							}
							if ($_location_child_level == $limit_level) {
								$locationInfo = db_getFromDB('location'.$current_level, 'id', $value, 1, '', 'array');
								$aux_array_breadcrumb[] = $locationInfo['name'];
							}
						}
						else {
							$aux_array_breadcrumb[] = LANG_NA;
						}
					}
				}
			}
		}
		if (count($aux_array_breadcrumb) > 0)
			echo implode('&nbsp;', $aux_array_breadcrumb);

		return $_link_params;
	}

	function system_retrieveLocationLinkBackLevel($locationLevel, $locationSession, $_location_node_params, $operation) {
		$url = "".DEFAULT_URL."/".SITEMGR_ALIAS."/locations/location_".$locationLevel."/";
		if ($locationSession == "manage") $url .= "index.php"; else
		if ($locationSession == "add") $url .= "location_".$locationLevel.".php"; else
		if ($locationSession == "featured") $url .= "featured.php";
		$url .= ($_location_node_params?"?".$_location_node_params:"");
		if ($_location_node_params && $operation)
			$url .= "&";
		elseif (!$_location_node_params && $operation)
			$url .= "?";
		return $url;
	}

	function system_retrieveLocationsInfo (&$nonDefaultLocInfo, &$defaultLocInfo) {

		$defaultLoc      = explode(",", EDIR_DEFAULT_LOCATIONS);
		$defaultLocIds   = explode(",", EDIR_DEFAULT_LOCATIONIDS);
		$defaultLocNames = explode(",", EDIR_DEFAULT_LOCATIONNAMES);
		$defaultLocShow  = explode(",", EDIR_DEFAULT_LOCATIONSHOW);
		$locations       = explode(",", EDIR_LOCATIONS);

		//retrieve all non default location
		$locations = array_diff($locations, $defaultLoc);

		$nonDefaultLocInfo = "";
		foreach ($locations as $location)
			$nonDefaultLocInfo[] = $location;

		//retrieve arrays with default locations info
		$i=0;
		$defaultLocInfo = "";
		foreach ($defaultLoc as $location) {
			$defaultLocInfo[$i]['type'] = $location;
			$defaultLocInfo[$i]['id']   = $defaultLocIds[$i];
			$defaultLocInfo[$i]['name'] = $defaultLocNames[$i];
			$defaultLocInfo[$i]['show'] = $defaultLocShow[$i];
			$i++;
		}
	}

	function system_retrieveLocationsToShow($type="string") {
		$locations = explode(",", EDIR_LOCATIONS);
		if (EDIR_DEFAULT_LOCATIONS) {
			$defaultLocShow = explode(",", EDIR_DEFAULT_LOCATIONSHOW);
			for ($i=0; $i<count($defaultLocShow); $i++)
				if ($defaultLocShow[$i]=='n')
					unset ($locations[$i]);
		}
		if ($type=="string") {
			$locations = array_reverse ($locations);
			$return = implode(", ", $locations);
		} elseif ($type=="array") {
			$return = $locations;
		}
		return $return;
	}

	function system_retrieveLastDefaultLevel(&$last_default_level, &$last_default_id) {
		$last_default_level = false;
		$last_default_id = false;
		if (EDIR_DEFAULT_LOCATIONS) {
			$defaultLoc      = explode(",", EDIR_DEFAULT_LOCATIONS);
			$defaultLocIds   = explode(",", EDIR_DEFAULT_LOCATIONIDS);
			$last_default_level = array_pop($defaultLoc);
			$last_default_id = array_pop($defaultLocIds);
		}
	}

	function system_retrieveNonActivableLocations($domain_id = false) {
		$return = "";
		$dbMain = db_getDBObJect(DEFAULT_DB,true);
		$db = db_getDBObjectByDomainID($domain_id,$dbMain);
		$locations = explode(",", EDIR_LOCATIONS);
		$non_used_locations = array(1,2,3,4,5);
		$non_used_locations = array_diff($non_used_locations, $locations);
		$last_actived_location = array_pop($locations);
		$locations_to_check = array();
		foreach($non_used_locations as $each_non_used_locations)
			if( $each_non_used_locations < $last_actived_location )
				array_push($locations_to_check, $each_non_used_locations);

		if ($locations_to_check) {
			foreach ($locations_to_check as $each_location_to_check) {
				$found=false;
				$sql = "SELECT count(id) AS total FROM Listing WHERE location_".$each_location_to_check." = 0 ";
				$r = $db->query($sql);
				$row=mysql_fetch_assoc($r);
				if ($row['total'])
					$return[] = $each_location_to_check;
				else {
					$sql = "SELECT count(id) AS total FROM Classified WHERE location_".$each_location_to_check." = 0 ";
					$r = $db->query($sql);
					$row=mysql_fetch_assoc($r);
					if ($row['total'])
						$return[] = $each_location_to_check;
					else {
						$sql = "SELECT count(id) AS total FROM Event WHERE location_".$each_location_to_check." = 0 ";
						$r = $db->query($sql);
						$row=mysql_fetch_assoc($r);
						if ($row['total'])
							$return[] = $each_location_to_check;
					}
				}
			}
		}
		if ($return)
			$return = implode (",", $return);
		return $return;
	}

	function system_getURLLocationParams($array) {
		$url_params = "";
		$array_params = array();
		if ($array) {
			if (count($array) > 0) {
				foreach ($array as $name=>$value) {
					$pos = (string_strpos($name, "location_")!==false);
					if ($pos !== false) {
						if ($value) {
							$array_params[] = $name."=".$value;
						}
					}
				}
			}
		}
		if ($array_params) {
			if (count($array_params) > 0) {
				$url_params = implode("&", $array_params);
			}
		}
		return $url_params;
	}

    /*
     * Return an array with listing levels which have certain information enabled, like review, click to call and sms.
     */
    function system_retrieveLevelsWithInfoEnabled($info) {

		$array_call_levels = system_getListingLevelInformation($info);
        
		unset($return);
		foreach($array_call_levels as $key => $value){
			if($value == "y"){
				$return[] = $key;
			}
		}
		
		if(is_array($return)){
			return $return;
		}else{
			return false;
		}
		
    }

	function system_getLastDay($month = '', $year = '') {
	   if (empty($month)) {
	      $month = date('m');
	   }
	   if (empty($year)) {
	      $year = date('Y');
	   }
	   $result = strtotime("{$year}-{$month}-01");
	   $result = strtotime('-1 second', strtotime('+1 month', $result));
	   return date('Y-m-d', $result);
	}
	function system_showTruncatedText($text, $length, $extraChar = "...", $isClass = false) {
		unset($return);
		unset($tLen);
		unset($ecLen);
		$text = html_entity_decode($text);
		$tLen = string_strlen($text);
		if ($tLen > $length) {
			$ecLen = string_strlen($extraChar);
			$return = string_substr($text, 0, ($length - $ecLen)).$extraChar;
		} else {
			$return = $text;
		}
		return !$isClass? htmlspecialchars($return): $return;
	}

	/**
	 * <code
	 *		//Get the Time Stamp from a date and time
	 *		system_getTimeStamp($date, $time);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name system_getTimeStamp
	 * @access Public
	 * @param date $date
	 * @param time $time
	 * @return timestamp $timestamp
	 */
	function system_getTimeStamp($date, $time = false) {
		if (DEFAULT_DATE_FORMAT == "m/d/Y") {
			/*
			 * Explode the date into $month, $day and $year variables
			 */
			list ($month, $day, $year)= explode("/", $date);
		} elseif (DEFAULT_DATE_FORMAT == "d/m/Y") {
			/*
			 * Explode the date into $day, $month and $year variables
			 */
			list ($day, $month, $year)= explode("/", $date);
		}

		if ($time) {
			/*
			 * Explode the time into $hour, $minute and $second variables
			 */
			list($hour, $minute, $second) = explode(":", $time);
		} else {
			/*
			 * Create the $hour, $minute and $second variables with 0
			 */
			$hour = 0;
			$minute = 0;
			$second = 0;
		}
		/*
		 * Create the Time Stamp from Date and Time
		 */
		$timestamp = mktime((int)$hour, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year);
		return $timestamp;
	}

	/**
	 * <code>
	 *		//Get the number of days of a determined month
	 *		system_getMonthNumDays($date);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name system_getMonthNumDays
	 * @access Public
	 * @param date $date
	 * @return integer $daysInMonth
	 */
	function system_getMonthNumDays($date) {
		if (DEFAULT_DATE_FORMAT == "m/d/Y") {
			/*
			 * Explode the date into $month, $day and $year variables
			 */
			list ($month, $day, $year)= explode("/", $date);
		} elseif (DEFAULT_DATE_FORMAT == "d/m/Y") {
			/*
			 * Explode the date into $day, $month and $year variables
			 */
			list ($day, $month, $year)= explode("/", $date);
		}

		/*
		 * Using date funciton with "t" param to return the number of days in a month
		 */
		$daysInMonth = date("t", mktime(0, 0, 0, (int)$month, 1, (int)$year));
		return $daysInMonth;
	}

	/**
	 * <code>
	 *		//Get the difference in days beteween two dates
	 *		system_getDiffDays($timestamp_start, $timestamp_end);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name system_getDiffDays
	 * @access Public
	 * @param timestamp $timestamp_start
	 * @param timestamp $timestamp_end
	 * @return integer $numberOfDays
	 */
	function system_getDiffDays($timestamp_start, $timestamp_end) {
		/*
		 * Calculing the $diffdays with ($timestamp_start - $timestamp_end) / (60*60+24)
		 * $timestamp_start = Timestamp generated from start date
		 * $timestamp_end = Timestamp generated from end date
		 * (60*60*24) = Calculated Timestamp from a day
		 */
		$diffdays = ($timestamp_start - $timestamp_end) / (60*60*24);

		/*
		 * Get the absolute value from $diffdays
		 */
		$diffdays = abs($diffdays);

		/*
		 * Round the $diffdays
		 */
		$numberOfDays = floor($diffdays);
		return $numberOfDays;
	}

	/**
	 * <code>
	 *		//Get the week number from a date
	 *		system_getNumberWeek($date);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name system_getNumberWeek
	 * @access Public
	 * @param date $date
	 * @return integer $weekNumber
	 */
	function system_getNumberWeek($date) {
		if (DEFAULT_DATE_FORMAT == "m/d/Y") {
			/*
			 * Explode the date into $month, $day and $year variables
			 */
			list ($month, $day, $year)= explode("/", $date);
		} elseif (DEFAULT_DATE_FORMAT == "d/m/Y"){
			/*
			 * Explode the date into $day, $month and $year variables
			 */
			list ($day, $month, $year)= explode("/", $date);
		}

		/*
		 * Create the Time Stamp from Date
		 */
		$timestamp = mktime(0, 0, 0, (int)$month, (int)$day, (int)$year);

		/*
		 * Using date funciton with "W" param to return the week number of a timestamp
		 */
		$number = date("W", $timestamp);

		/*
		 * To fix a possible php bug
		 */
		if ($month == 1) {
			/*
			 * if month == 1 (January) and week number > 4, need to force the week number to be 0
			 */
			if ($number > 4) $number = 0;
		} else if ($month == 12) {
			/*
			 * if month == 12 (December) and week number < 4, need to force the week number to be the last week number of the year
			 */
			if ($number < 4) {
				$timestamp = mktime(0, 0, 0, (int)$month, (int)$day-7, (int)$year);
				$number = date("W", $timestamp) + 1;
			}
		}

		$weekNumber = $number + 1;
		return $weekNumber;
	}

	function system_checkDay($days) {
		$daysweek = explode(",",$days);
		$weekday_names = explode(",", LANG_DATE_WEEKDAYS);
		$weekend = false;
		$businessday = false;

		if ((count($daysweek)==2) && ($daysweek[0]=="1" && $daysweek[1]=="7")){ //weekends
			return LANG_EVERY2." ".LANG_EVENT_WEEKEND;
		}elseif ((count($daysweek)==5) && ($daysweek[0]=="2" && $daysweek[1]=="3" && $daysweek[2]=="4" && $daysweek[3]=="5" && $daysweek[4]=="6")){ //business days
			$str_date = LANG_EVERY2." ".system_showText(LANG_EVENT_BUSINESSDAY);
			return $str_date;
		}elseif (count($daysweek)==7){ //every day
			return LANG_EVERY2." ".LANG_DAY;
		}else { //other cases
			$str_date = "";
			for ($i=0;$i<count($daysweek);$i++){
				$str_date .= ucfirst($weekday_names[$daysweek[$i]-1]);
				if ($daysweek[$i+2]){
					$str_date .=", ";
				} else {
					$str_date .=" ".LANG_AND." ";
				}
			}
			$len = string_strlen(LANG_AND);
			$str_date = string_substr($str_date,0,-1-$len);

			return LANG_EVERY." ".$str_date;
		}
	}

	function system_getRecurringWeeks($weekdays){
		$array_weekdays = explode(",",$weekdays);
		$aux = 0;
		if (count($array_weekdays)==0){
			$aux = $array_weekdays[0];
			if ($aux == 1)   	$str = system_showText(LANG_FIRST_2);
			elseif($aux == 2)	$str = system_showText(LANG_SECOND_2);
			elseif($aux == 3)	$str = system_showText(LANG_THIRD_2);
			elseif($aux == 4)	$str = system_showText(LANG_FOURTH_2);
			elseif($aux == 5)   $str = system_showText(LANG_LAST);
			return $str;
		}else {
			$str_date = "";
			$weekday_names = explode(",", LANG_DATE_WEEKDAYS);
			if (count($array_weekdays)==5){
				return false;
			} else {
			for ($i=0;$i<count($array_weekdays);$i++){
				$aux = $array_weekdays[$i];
				if ($aux == 1)   	$str = system_showText(LANG_FIRST_2);
				elseif($aux == 2)	$str = system_showText(LANG_SECOND_2);
				elseif($aux == 3)	$str = system_showText(LANG_THIRD_2);
				elseif($aux == 4)	$str = system_showText(LANG_FOURTH_2);
				elseif($aux == 5)   $str = system_showText(LANG_LAST);
				$str_date .= $str;
				if ($array_weekdays[$i+2]){
					$str_date .=", ";
				} else {
					$str_date .=" ".LANG_AND." ";
				}
			}
			$len = string_strlen(LANG_AND);
			$str_date = string_substr($str_date,0,-1-$len);

			return $str_date;
			}


		}

	}

	/**
	 * Return the permission from a determined file or folder
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name system_checkPerm()
	 * @param varchar $src
     * @return integer $permission
     */
	function system_checkPerm ($src) {
		$permission = string_substr(decoct(fileperms($src)), 1);
		return $permission;
	}

 	/**
	 * Parse XML file to array
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name objectsIntoArray()
	 * @param array $arrObjData
	 * @param array $arrSkipIndices
     * @return array
     */
 	function objectsIntoArray($arrObjData, $arrSkipIndices = array()){
    	$arrData = array();

    	// if input is object, convert into array
    	if (is_object($arrObjData)) {
    	    $arrObjData = get_object_vars($arrObjData);
    	}

    	if (is_array($arrObjData)) {
      	  foreach ($arrObjData as $index => $value) {
         	   if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
         	   }
           	 if (in_array($index, $arrSkipIndices)) {
           	     continue;
           	 }
           	 $arrData[$index] = $value;
        	}
   	 	}
   	 	return $arrData;
	}
    
    function system_findTranslationFor($word, $language = EDIR_LANGUAGE, $languageFile = ""){
		if (!$language || !$word) {
			return false;
		}
        
        if (!$languageFile) {
            $languageFile = EDIRECTORY_ROOT."/lang/$language".".php";
        }

		if (file_exists($languageFile)){
			$fp = fopen($languageFile, 'r');
			if ($fp && filesize($languageFile)){
				$phptext = file_get_contents($languageFile);
				$startPos = string_strpos($phptext,$word."\",");

				$text1 = string_substr($phptext,$startPos,string_strlen($phptext));
                $text2 = string_substr($text1,0,string_strpos($text1,");"));
				$text2ARR = explode('",',$text2);
                
                $return_str = trim($text2ARR[1]);
                $return_str = string_substr($return_str, 1);
                $return_str = string_substr($return_str, 0, -1);
				return $return_str;
			} else{
				return false;
			}
		} else {
			return false;
		}

	}

	function system_increaseVisit($ip){

		$db = db_getDBObject(DEFAULT_DB, true);
		$sql = "SELECT domain_id FROM Report_Visit WHERE ip = $ip AND domain_id = ".SELECTED_DOMAIN_ID." AND date = CURDATE() LIMIT 1";
		$result = $db->query($sql);
		if (mysql_num_rows($result) == 0) {
			$sql = "INSERT INTO Report_Visit (domain_id, date, ip) VALUES (".SELECTED_DOMAIN_ID.", CURDATE(), $ip)";
			$db->query($sql);
		}


	}

	function system_getMonthVisits($domain_id, $total = false){

		$db = db_getDBObject(DEFAULT_DB, true);
		$month = date("m");
		if ($total){
			$sql = "SELECT id FROM Report_Visit WHERE MONTH(`date`) >= $month";
		} else {
			$sql = "SELECT id FROM Report_Visit WHERE MONTH(`date`) >= $month AND domain_id = $domain_id";
		}
		$number_visits = mysql_num_rows($db->query($sql));

		return $number_visits;
	}

	function system_logLocationChanges($location_id, $location_level, $parent_new_id, $parent_level, $update_childs=true) {

		// need to remove 's because system_logLocationChanges is called after a prepareToSave call
		$location_id = str_replace("'", '', $location_id);
		$parent_new_id = str_replace("'", '', $parent_new_id);

		$db = db_getDBObject(DEFAULT_DB, true);
		$month = date("m");
		if ($parent_level){
			$sql = "SELECT location_{$parent_level} FROM Location_{$location_level} WHERE id = {$location_id}";

			$result = $db->query($sql);
			$row = mysql_fetch_assoc($result);
			$parent_old_id = $row["location_{$parent_level}"];
		} else {
			$parent_old_id = 0;
		}
		

		if (($parent_old_id != $parent_new_id) && ($parent_old_id > 0)) {

			if ($update_childs) {
				$edir_all_locations = explode(",", EDIR_ALL_LOCATIONS);
				foreach ($edir_all_locations as $eachLevel) {
					if ($eachLevel > $location_level) {
						$locationObjName = "Location".$eachLevel;
						$childObj = new $locationObjName();
						$childObj->setNumber('location_'.$location_level, $location_id);
						$childArray = $childObj->retrieveLocationByLocation($location_level);

						if ((is_array($childArray)) and (count($childArray) > 0)) {
							foreach($childArray as $child_row) {
								$childObj = new $locationObjName($child_row);
								$childObj->setNumber('location_'.$parent_level, $parent_new_id);
								$childObj->Save();
							}
						}
					}
				}
			}

			$domains = new Domain();
			$array_domain_ids = $domains->getAllDomains(array('id'), 'A');
			foreach ($array_domain_ids as $domain_id) {
				$sql = "INSERT INTO LocationChangeLOG (domain_id, location_id, location_level, parent_old_id, parent_new_id, parent_level, modules_updated) values ";
				$sql .= "({$domain_id["id"]}, {$location_id}, {$location_level}, {$parent_old_id}, {$parent_new_id}, {$parent_level}, 'n')";
				$db->query($sql);
			}

			return true;
		} else {
			$domains = new Domain();
			$array_domain_ids = $domains->getAllDomains(array('id'), 'A');
			foreach ($array_domain_ids as $domain_id) {
				$sql = "INSERT INTO LocationChangeLOG (domain_id, location_id, location_level, parent_old_id, parent_new_id, parent_level, modules_updated) values ";
				$sql .= "({$domain_id["id"]}, {$location_id}, {$location_level}, {$parent_old_id}, {$parent_new_id}, {$parent_level}, 'n')";
				$db->query($sql);
			}

			return true;
		}

	}

    function is_ie($ie6=false, &$version = false){
        if ($ie6){
            if(preg_match('/(?i)msie [1-6]/',strtolower($_SERVER['HTTP_USER_AGENT'])) ) {
				$version = 6;
                return true;
			} else {
				return false;
			}
        } else {
            if(preg_match('/(?i)msie [1-7]/',strtolower($_SERVER['HTTP_USER_AGENT'])) ) {
				$version = 7;
                return true;
			} else if(preg_match('/(?i)msie [1-8]/',strtolower($_SERVER['HTTP_USER_AGENT'])) ) {
				$version = 8;
                return true;
			} else if(preg_match('/(?i)msie [1-9]/',strtolower($_SERVER['HTTP_USER_AGENT'])) ) {
				$version = 9;
                return true;
			} else {
				return false;
			}
        }
    }

	/**
	 * Fill up an Array of Javascript functions and throw it up on document.ready - jquery
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name scriptColector()
	 * @param string $file
     * @param array $arrayWJavascripts
     * @param boolean $optimzeit
     * @return array
     */
    function system_scriptColectorOnReady($content,$arrayWJavascripts=false,$optimzeit=true){
        if (!$optimzeit){
			?>
			<script type="text/javascript" ><?=$content?></script>
			<?
			$arrayWJavascripts['log'][] = "scriptColectorOnReady: Not optimized content";
		}else{
			$arrayWJavascripts['log'][] = "scriptColectorOnReady: Optimized content";
			$arrayWJavascripts['contentOnReady'][] = $content;
			return $arrayWJavascripts;
		}

    }

     /**
	 * javascript includes
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name scriptColector()
	 * @param string $file
     * @param array $arrayWJavascripts
     * @return array
     */
    function system_scriptColectorExternal($file,$arrayWJavascripts=false){
		if (!$arrayWJavascripts){
			$arrayWJavascripts = array();
		}
		$arrayWJavascripts['external'][] = $file;
		$arrayWJavascripts['log'][] = "scriptColectorExternal: Wrote file $file";
		return $arrayWJavascripts;
    }



    /**
	 * Fill up an Array of Javascript file names and minimize at the end
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name scriptColector()
	 * @param string $file
     * @param array $arrayWJavascripts
     * @param string $internalFunction
     * @param boolean $optimzeit
     * @param boolean $external
     * @return array
     */
    function system_scriptColector($file, $arrayWJavascripts = false, $internalFunction = false, $optimzeit = true, $external = false){

		if (!$optimzeit && $external) {
			$filename = $file;
		} else {
			$filename = DEFAULT_URL.$file;
		}
		
		if (!$optimzeit){
			?>
			<script src="<?=$filename;?>" type="text/javascript"><?=$internalFunction?></script>
			<?
			 $arrayWJavascripts['log'][] = "scriptColector: Not optimized $file ".($internalFunction?" with internal functions":'');
		} else {
			if (!$arrayWJavascripts)
				$arrayWJavascripts = array();

			$filename = EDIRECTORY_ROOT.$file;
			if (file_exists($filename)){
				$filesize = filesize($filename);
				$filemodification = date("dYHis", filemtime($filename));
				$arrayWJavascripts['name'][] = $file;
				$arrayWJavascripts['id'][] = $filemodification;
				$arrayWJavascripts['internalFunction'][] = $internalFunction;

			} else echo "error reading: $filename";
			return $arrayWJavascripts;
		}
	}
    
    function system_returnPageByURL() {
        
        if ($_SERVER['SCRIPT_NAME'] != EDIRECTORY_FOLDER."/index.php") { //physical pages (not built by modrewrite)
            return $_SERVER['SCRIPT_NAME'];
        } else {
            if (ACTUAL_MODULE_FOLDER == "") { //Home Page
                return EDIRECTORY_FOLDER."/index.php";
            } else { //Modules pages
                if (defined("ACTUAL_PAGE_NAME")) {
                    return ACTUAL_PAGE_NAME;
                } else {
                    return $_SERVER['SCRIPT_NAME'];
                }
            }
        }
        
    }

	 /**
	 * Write all javascript files on array after minimize it, creating a unique js file
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name scriptColector()
	 * @param array $jsArray
	 * @param boolean $skipOptimzation
	 */
	function system_renderJavascripts($jsArray,$skipOptimzation=false) {

		if ($skipOptimzation){
			$counter = 0;
			foreach ($jsArray['name'] as $script){
				?> <script type="text/javascript" src="<?=DEFAULT_URL?><?=$script?>"><?=$jsArray['internalFunction'][$counter++]?></script><?
			}
			$jsArray['log'][] = "renderJavascripts: skipping optimization if $script ";

			if (is_array($jsArray['contentOnReady'])){
				?>
				<script type="text/javascript">
					$(document).ready(function() {
						<?
						foreach ($jsArray['contentOnReady'] as $content){
							echo $content;
						}
						?>
					 });
				</script>
				<?
				$jsArray['log'][] = "renderJavascripts: Wrote contentOnReady ";
			}
		} else {

			$relativePath = DEFAULT_URL.'/custom/domain_'.SELECTED_DOMAIN_ID.'/tmp';
			$physicalPath = EDIRECTORY_ROOT.'/custom/domain_'.SELECTED_DOMAIN_ID.'/tmp';

			if (!is_dir($physicalPath)) mkdir($physicalPath);

			// check if file exists
			$fileNameId = 0;
			if ($jsArray['id'])
				foreach ($jsArray['id'] as $id)
					$fileNameId += (int)$id;

			$currentFileName = system_returnPageByURL();
			$currentFileName = str_replace('/','',$currentFileName);
			$currentFileName = str_replace('.','',$currentFileName);
			$fileNameId = $currentFileName.'_'.$fileNameId;

			$fileNametoInclude = $relativePath.'/min_'.$fileNameId.'.js';
			$fileNameId = $physicalPath.'/min_'.$fileNameId.'.js';

			if (file_exists($fileNameId) && (int)filesize($fileNameId)>0){
				// just add as normal javascript
				$jsArray['log'][] = "renderJavascripts: Has already optimized JS [$fileNametoInclude] ";
			}else{
				// build the file
				include_once(CLASSES_DIR."/class_miniJS.php");

				//remove any other minified
				foreach (glob($physicalPath."/min_$currentFileName*.js") as $deleteFilename)
				   @unlink($deleteFilename);

				$handle = fopen($fileNameId, 'w+');
				if ($jsArray['name']) foreach ($jsArray['name'] as $jsFile){
					fwrite($handle, "\n\n/* File: ".$jsFile." */\n");
					fwrite($handle, JSMin::minify(file_get_contents(EDIRECTORY_ROOT.$jsFile)));}

				 fclose($handle);

				 $jsArray['log'][] = "renderJavascripts: Built new optimzed JS [$jsFile]  ";
			}
			?>

			<script type="text/javascript" src="<?=$fileNametoInclude?>"></script>
			<?
			if(is_array($jsArray['internalFunction']) && $jsArray['internalFunction'][0]!='') { ?>
				<script type="text/javascript">
					<?
						$counter = 0;
						foreach ($jsArray['internalFunction'] as $internalScript){
							?><?=$internalScript?><?
						}
						$jsArray['log'][] = "renderJavascripts: Wrote internal functions";
					?>
				</script><?
			}

			if (is_array($jsArray['contentOnReady'])){
				include_once(CLASSES_DIR."/class_miniJS.php");

				$Fullcontent = "";
				foreach ($jsArray['contentOnReady'] as $content)
					$Fullcontent .= $content;

				if ($Fullcontent){
				?>

				<script type="text/javascript">
					//<![CDATA[
					$ = jQuery.noConflict();
					$(document).ready(function() {
						<?=JSMin::minify($Fullcontent); ?>
					});
					//]]>
				</script>

				<?
				}
				$jsArray['log'][] = "renderJavascripts: Minified content on ready";
			}

		}

		if (is_array($jsArray['external'])) {

			foreach ($jsArray['external'] as $file){
				?>  <script type="text/javascript" src="<?=$file?>"></script>  <?
			}
			$jsArray['log'][] = "renderJavascripts: Wrote external files";
		}

		if (SCRIPTCOLLECTOR_DEBUG=='on'){
			if (is_array($jsArray['log'])){
				echo implode("<br/>",$jsArray['log']);
			}
		}
	}

     /**
	 * Fill up an Array of CSS file names and minimize at the begginig
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name scriptColectorCSS()
	 * @param string $file
     * @param array $arrayWCSS
     * @param boolean $optimzeit
     * @return array
     */
    function system_scriptColectorCSS($file,$arrayWCSS=false,$optimzeit=true){

		if (!$optimzeit){
			?>
			<link type="text/css" href="<?=DEFAULT_URL?><?=$file?>" rel="stylesheet" />
			<?
		}else{
			if (!$arrayWCSS)
				$arrayWCSS = array();

			$filename = EDIRECTORY_ROOT.$file;
			if (file_exists($filename)){
				$filesize = filesize($filename);
				$filemodification = date ("dYHis", filemtime($filename));
				$arrayWCSS['name'][] = $file;
				$arrayWCSS['id'][] = $filemodification;

			} else echo "error reading: $filename";
			return $arrayWCSS;
		}
	}

	 /**
	 * Write all CSS files on array
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @name renderCSSs()
	 * @param array $jsArray
	 * @param boolean $skipOptimzation
	 */
	function system_renderCSSs($cssArray,$skipOptimzation=false){
		if ($skipOptimzation){
			$counter = 0;
			foreach ($cssArray['name'] as $file){
				?> <link type="text/css" href="<?=DEFAULT_URL?><?=$file?>" rel="stylesheet" /><?
			}
		} else {

			$relativePath = DEFAULT_URL.'/custom/domain_'.SELECTED_DOMAIN_ID.'/tmp';
			$physicalPath = EDIRECTORY_ROOT.'/custom/domain_'.SELECTED_DOMAIN_ID.'/tmp';

			if (!is_dir($physicalPath)) mkdir($physicalPath);

			// check if file exists
			$fileNameId = 0;
			if ($cssArray['id'])
				foreach ($cssArray['id'] as $id)
					$fileNameId += (int)$id;

			$currentFileName = system_returnPageByURL();
			$currentFileName = str_replace('/','',$currentFileName);
			$currentFileName = str_replace('.','',$currentFileName);
			$fileNameId=$currentFileName.'_'.$fileNameId;

			$fileNametoInclude = $relativePath.'/min_'.$fileNameId.'.css';
			$fileNameId = $physicalPath.'/min_'.$fileNameId.'.css';

			if (file_exists($fileNameId) && (int)filesize($fileNameId)>0){
				// just add as normal css
			}else{
				// build the file
				include_once(CLASSES_DIR."/class_miniJS.php");

				//remove any other minified
				$deleteFiles = glob($physicalPath."/min_$currentFileName*.css");
				if (is_array($deleteFiles) && $deleteFiles[0]) {
					foreach ($deleteFiles as $deleteFilename)
					   @unlink($deleteFilename);
				}


				$handle = fopen($fileNameId, 'w+');
				if ($cssArray['name'])foreach ($cssArray['name'] as $cssFile)
					fwrite($handle, JSMin::minify(file_get_contents(EDIRECTORY_ROOT.$cssFile)));

				 fclose($handle);
			}
			?>
			<link type="text/css" href="<?=$fileNametoInclude?>" rel="stylesheet" />
			<?
		}
	}

	 /**
	 * Generate a xml content from a sql command.
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.1.00
	 * @name system_generateXML()
	 * @param string $section "categories"
	 * @param string $sql ""
	 * @param integer $domain_id false
	 * @param string(xml) $xml_content
	 */
	function system_generateXML($section = "categories", $sql = "", $domain_id = false) {
		if (!$section || !$sql){
            return false;
        }

		$dbMain = db_getDBObject(DEFAULT_DB, true);
		if ($domain_id) {
            $dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
		} else {
            $dbObj = db_getDBObject();
		}
		unset($dbMain);

		$result = $dbObj->unbuffered_query($sql);

		if($result){
            unset($xml_content);
            $xml_content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
            $xml_content .= "<$section>";
            $hasCateg = false;
            while($row = mysql_fetch_assoc($result)){
                $xml_content .="<info>";
                $hasCateg = true;
                foreach ($row as $key => $value) {
                    if (is_string($value)){
                        $xml_content .="<$key>".format_getString($value)."</$key>";
                    }else if (is_numeric($value)){
                        $xml_content .="<$key>".$value."</$key>";
                    }
                }

                $xml_content .="</info>";
            }

            $xml_content .="</$section>";
            if (!$hasCateg) return false;
            return $xml_content;
		} else {
            return false;
		}
	}

	function system_getFormAction($action) {
		return $action;
	}
	
	function system_getAttachListingDropdown($account_id, $promotion_id, $i){
		
        $listingLevel = new ListingLevel();
        $levels = $listingLevel->getValues();
        $str_levels = "";

        foreach($levels as $level) {
            if ($listingLevel->getHasPromotion($level) == "y"){
               $str_levels	.= $level.",";
            }
        }

        $str_levels = string_substr($str_levels, 0, -1);

        // Construct the Listing Drop Down
        $listings = db_getFromDBBySQL("listing", "SELECT id, title, promotion_id, status, level FROM Listing_Summary WHERE account_id=".$account_id." AND level IN ($str_levels) ORDER BY title ", "array", false, SELECTED_DOMAIN_ID);

        $listingDropDown = "<select name=\"promotion_id_$i\" class=\"input-dd-form-listing\">";
        $listingDropDown .= "<option selected=\"selected\" value=\"".$promotion_id."||remove\">".system_showText(LANG_LABEL_CHOOSE_LISTING)."</option>";
        if ($listings) {
            foreach ($listings as $listing) {
                $val = $promotion_id."||".$listing["id"]."||".$listing["status"]."||".$listing["level"];
                $sel = "";
                if ($promotion_id == $listing["promotion_id"]) {
                    $sel = "selected";
                }
                $listingDropDown .= "<option value=\"".$val."\" $sel title=\"".$listing["title"]."\" >".system_showTruncatedText($listing["title"], 16)."</option>";
            }

        }
        $listingDropDown .= "</select>";
        return $listingDropDown;
	}
	
	function system_renameGalleryImages($image_id = 0, $thumb_id = 0, $account_id = 0, $galleryIDC = 0, $renameGallery = true){
		if ($image_id){

			$imageChange = new Image($image_id);
			if ($imageChange->imageExists()) {
				$oldPrefix = $imageChange->getString("prefix");
				$newPrefix = $account_id ? $account_id."_" : "sitemgr_";
				$img_type = string_strtolower($imageChange->getString("type"));
				$imageChange->setString("prefix",$newPrefix);
				$imageChange->Save();

				$dir = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/image_files";
				$imageOld = $dir."/".$oldPrefix."photo_".$image_id.".".$img_type;
				$imageNew = $dir."/".$newPrefix."photo_".$image_id.".".$img_type;
				rename($imageOld, $imageNew);
			}
		}

		if ($thumb_id){

			$thumbChange = new Image($thumb_id);
			if ($thumbChange->imageExists()) {
				$oldPrefix = $thumbChange->getString("prefix");
				$newPrefix = $account_id ? $account_id."_" : "sitemgr_";
				$img_type = string_strtolower($thumbChange->getString("type"));
				$thumbChange->setString("prefix",$newPrefix);
				$thumbChange->Save();

				$dir = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/image_files";
				$imageOld = $dir."/".$oldPrefix."photo_".$thumb_id.".".$img_type;
				$imageNew = $dir."/".$newPrefix."photo_".$thumb_id.".".$img_type;
				rename($imageOld, $imageNew);
			}
		}
		
		if ($galleryIDC && $renameGallery) {
			$galleryC = new Gallery($galleryIDC);

			if (count($galleryC->image) > 0) {
				for ($i=0; $i<count($galleryC->image); $i++) {
					$thumbObjC = new Image($galleryC->image[$i]["thumb_id"]);
					$imageObjC = new Image($galleryC->image[$i]["image_id"]);

					$thumb_idT = $galleryC->image[$i]["thumb_id"];
					$image_idT = $galleryC->image[$i]["image_id"];
					if ($thumbObjC->imageExists()) {
						$oldPrefix = $thumbObjC->getString("prefix");
						$newPrefix = $account_id ? $account_id."_" : "sitemgr_";
						$img_type = string_strtolower($thumbObjC->getString("type"));
						$thumbObjC->setString("prefix",$newPrefix);
						$thumbObjC->Save();

						$dir = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/image_files";
						$imageOld = $dir."/".$oldPrefix."photo_".$thumb_idT.".".$img_type;
						$imageNew = $dir."/".$newPrefix."photo_".$thumb_idT.".".$img_type;

						rename($imageOld, $imageNew);
					}
					if ($imageObjC->imageExists()) {
						$oldPrefix = $imageObjC->getString("prefix");
						$newPrefix = $_POST["account_id"] ? $_POST["account_id"]."_" : "sitemgr_";
						$img_type = string_strtolower($imageObjC->getString("type"));
						$imageObjC->setString("prefix",$newPrefix);
						$imageObjC->Save();

						$dir = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/image_files";
						$imageOld = $dir."/".$oldPrefix."photo_".$image_idT.".".$img_type;
						$imageNew = $dir."/".$newPrefix."photo_".$image_idT.".".$img_type;

						rename($imageOld, $imageNew);
					}
				}
			}
		}
	}
	
	function system_addItemGallery($gallery_hash, $title = "", &$galleryIDC, &$image_id, &$thumb_id, $blog = false){
		
		$dbMain = db_getDBObject(DEFAULT_DB, true);
		$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
		$sess_id = $gallery_hash;
		
		if (!$blog){
		
			$gallery = new Gallery($galleryIDC);
			if (!$galleryIDC){
				$aux = array("account_id"=>0,"title"=>$title,"entered"=>"NOW()","updated"=>"now()");
				$gallery->makeFromRow($aux);
				$gallery->save();
			}

			$sql = "SELECT 
						image_id,
						image_caption,
						thumb_id,
						thumb_caption,
						image_default
					FROM Gallery_Temp
					WHERE sess_id = '$sess_id'";
			$r = $dbObj->query($sql);
			while ($aux = mysql_fetch_array($r)){

				if ($aux["image_default"] == "y"){
					$image_id = $aux["image_id"];
					$thumb_id = $aux["thumb_id"];
				}
				$row["image_id"] = $aux["image_id"];
				$row['image_caption'] = $aux["image_caption"];
				$row['thumb_id'] = $aux["thumb_id"];
				$row['thumb_caption'] = $aux["thumb_caption"];
				$row['image_default'] = $aux["image_default"];
				$row['order'] = 0;
				$gallery->AddImage($row);
				$gallery->save();
				$galleryIDC = $gallery->id;
			}
			$sql = "DELETE FROM Gallery_Temp WHERE sess_id = '$sess_id'";
			$dbObj->query($sql);
		} else {
			$sql = "SELECT 
							image_id,
							image_caption,
							thumb_id,
							thumb_caption,
							image_default
						FROM Gallery_Temp
						WHERE sess_id = '$sess_id'";
			$r = $dbObj->query($sql);
			while ($aux = mysql_fetch_array($r)){
				$image_id=$aux["image_id"];
				$thumb_id=$aux["thumb_id"];
				$_POST["image_caption"] = $aux["image_caption"];
				$_POST["thumb_caption"] = $aux["thumb_caption"];
			}

			$sql = "DELETE FROM Gallery_Temp WHERE sess_id = '$sess_id'";

			$dbObj->query($sql);
		}
	}	
	
	/**
	 *	Function to prepare letters to pagination
	 * 	@desc Function to prepare letters do pagination
	 *	@author Rodrigo Apetito	- Arca Solutions
	 * 	@param object pageObj
	 * 	@param array searchReturn
	 * 	@param string paging_url
	 * 	@param string url_search_params
	 * 	@param string letter
	 * 	@filesource /functions/system_funct.php
 	 * 	@since July, 15, 2011
	 *	@return string with letters and links
	 */
	function system_prepareLetterToPagination($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, $fieldOnTable, $blog_module = false, $promotion_module = false, $listingForceJoin = false, $scalability = "off"){
		
		/*
		 * Get letters of events
		 */
		$letters = $pageObj->getString("letters");
		$module_letters = array();
		$module_not_letters = array();
		$aux_letters = array();
		$aux_letters = implode("','",$letters);
		$aux_letters = str_replace("#',", "", $aux_letters);
		$aux_letters .= "'";

		if ($scalability == "off"){
		
			$db = db_getDBObject();
			$sql = "SELECT SUBSTRING(".$fieldOnTable.",1,1) AS letter_field FROM ".$searchReturn["from_tables"].($searchReturn["where_clause"] ? " WHERE ".$searchReturn["where_clause"] : "")."  GROUP BY letter_field HAVING UPPER(letter_field) IN ($aux_letters)";
			$r = $db->query($sql);
			while($row = mysql_fetch_assoc($r)){
				$module_letters[] = $row["letter_field"];
			}
		} else {
			$module_letters = $letters;
		}

		if ($promotion_module){
			$auxID = "Promotion.id"; 
			$auxfieldOnTable = "Promotion.name";
		} else {
			if ($listingForceJoin == "on"){
				$auxID = "Listing_Summary.`id`";
			} else {
				$auxID = "`id`";
			}
			
			$auxfieldOnTable = $auxfieldOnTable;
		}
		
		if ($scalability == "off"){
            $sql = "SELECT $auxID FROM ".$searchReturn["from_tables"].($searchReturn["where_clause"] ? " WHERE ".$searchReturn["where_clause"]." AND $fieldOnTable REGEXP '^[^a-zA-Z].*$'" : " WHERE $auxfieldOnTable REGEXP '^[^a-zA-Z].*$'");
            $r = $db->query($sql);
			if (mysql_num_rows($r)) {
				$specialChar = true;
			}else {
				$specialChar = false;
			}
		} else {
			$specialChar = true;
		}
		
		unset($letters_menu);
		foreach ($letters as $each_letter) {
			$letters_menu .= "<li>";
			if ($_GET["url_full"] || $blog_module) {
				if($each_letter != "#"){
					if ( (in_array(strtoupper($each_letter), $module_letters)) || (in_array($each_letter, $module_letters)) ){
						$letters_menu .= "<a href=\"$paging_url".(($url_search_params) ? "$url_search_params" : "")."/letter/".$each_letter."\" ".(($each_letter == $letter) ? "class=\"active\"" : "" ).">".string_strtoupper($each_letter)."</a>";
					} else{
						$letters_menu .= "<span>".strtoupper($each_letter)."</span>";
					}
				} else{
					if ($specialChar){
						$letters_menu .= "<a href=\"$paging_url".(($url_search_params) ? "$url_search_params" : "")."/letter/no\" ".(($letter == "no") ? "class=\"active\"" : "" ).">".string_strtoupper($each_letter)."</a>";
					} else{
						$letters_menu .="<span>#</span>";
					}
				}

			}else{
				if ($each_letter == "#") {
					if ($specialChar){
						$letters_menu .= "<a href=\"$paging_url?letter=no".(($url_search_params) ? "&amp;$url_search_params" : "")."\" ".(($letter == "no") ? "class=\"active\"" : "" ).">".string_strtoupper($each_letter)."</a>";
					} else {
						$letters_menu .= "<span>#</span>";
					}
				} else {
					if ( (in_array(strtoupper($each_letter), $module_letters)) || (in_array($each_letter, $module_letters)) ){
						$letters_menu .= "<a href=\"$paging_url?letter=".$each_letter.(($url_search_params) ? "&amp;$url_search_params" : "")."\" ".(($each_letter == $letter) ? "class=\"active\"" : "" ).">".string_strtoupper($each_letter)."</a>";
					} else {
						$letters_menu .= "<span>".strtoupper($each_letter)."</span>";
					}
				}
			} 
			$letters_menu .="</li>";
		}
		return $letters_menu;
	}
	
	
	/**
	 *	Function to prepare to pagination
	 * 	@desc Function to prepare pagination
	 *	@author Rodrigo Apetito	- Arca Solutions
	 * 	@param string paging_url
	 * 	@param string url_search_params
	 * 	@param string letter
	 * 	@param Object pageObj
	 * 	@filesource /functions/system_funct.php
 	 * 	@since July, 15, 2011
	 *	@return array with content to pagination
	 */
	function system_preparePagination($paging_url, $url_search_params, $pageObj, $letter, $screen, $aux_items_per_page, $adv_search = false){
		if ($adv_search){
			$aux_page_url = $paging_url."?".$url_search_params;
		} else {
			$aux_page_url = $paging_url.$url_search_params;
		}
		
		if($letter){
			if ($adv_search){
				$aux_page_url .= "&amp;letter=".$letter;
			} else {
				if(substr($aux_page_url,strlen($aux_page_url)-1) != "/"){
					$aux_page_url .= "/letter/".$letter;
				}else{
					$aux_page_url .= "letter/".$letter;
				}
			}
		}
		
		if ($adv_search){
            if (substr($aux_page_url, -1) == "?") {
               $aux_page_url .= "screen="; 
            } else {
                $aux_page_url .= "&amp;screen=";
            }
		} else {
			if(substr($aux_page_url,strlen($aux_page_url)-1) != "/"){
				$aux_page_url .= "/page/";
			}else{
				$aux_page_url .= "page/";
			}
		}
		
		$array_pages_code = $pageObj->getPagination($screen, $aux_items_per_page, $aux_page_url);
		return $array_pages_code;
	}	 
	 
	function system_CallUrlByCURL($url,$referer,$parameters,$post_method = true){
	
        $agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
		
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_NOPROGRESS, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        
        if($post_method){
        
        	curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        
        }
        
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        curl_close ($ch);

		return $response;
	
	}
	
	/**
	 * Enable Deal Feature at sponsor area according to the user's listings
	 * 	@desc Enable Deal Feature at sponsor area according to the user's listings
	 * 	@param integer $user_id
	 * 	@return boolean
	 */
	function system_enableDealForUser($user_id){
		
		$level = new ListingLevel(true);
		$levelvalues = $level->getLevelValues();
		$str_levels = "";

		foreach($levelvalues as $value){
			unset($listingHasPromotion);
			$listingHasPromotion = $level->getHasPromotion($value);
			if ($listingHasPromotion == "y"){
				$str_levels .= $value.",";
			}
		}
		$str_levels = string_substr($str_levels, 0, -1);
		
		$dbMain = db_getDBObject(DEFAULT_DB, true);
		$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
		$sql = "SELECT id FROM Listing WHERE account_id = $user_id AND level IN ($str_levels)";
		$result = $dbObj->query($sql);
		if (mysql_numrows($result) > 0){
			return true;
		} else {
			return false;
		}
	}
	
	function system_hex2rgb($color) {
		
		$red	= string_substr($color, 0, 2);
		$green	= string_substr($color, 2, 2);
		$blue	= string_substr($color, 4, 2);

		/*
		 * Hexadecimal
		 */
		$red_hex = hexdec($red);
		$green_hex = hexdec($green);
		$blue_hex = hexdec($blue);
		
		return array (
		"red"=> $red_hex, 
		"green"=> $green_hex, 
		"blue"=> $blue_hex
		);
	}
	
	function system_advancedSearch_getCategories($type = "listing", $filter = false, $selected_category_id = 0, $show_type = "all", $main_id=0, $template_id=0,$sub_id=0){
		if ($type == "promotion"){
			$type = "listing";
		}
		$item_category_scalability = @constant(strtoupper($type)."CATEGORY_SCALABILITY_OPTIMIZATION");
		$table = ucfirst($type)."Category";
		$table_type = $type."category";
			
		/**
		 * Fields to get categories
		 */
		$fields = array();
		$fields[] = "id";
		$fields[] = "title";

		if(($show_type == "sub2" && $sub_id) || ($show_type=="sub" && $main_id))
		    $sql_categories = "SELECT id, title FROM $table WHERE id=".($show_type=="sub"?$main_id:$sub_id)." AND category_id = ".($show_type=="sub"?0:$main_id)." AND title <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		else 
                    $sql_categories = "SELECT id, title FROM $table WHERE category_id = 0 AND title <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;

		if ($sql_categories) {
			$categories = db_getFromDBXML($table, false, false, false, false, $fields, $sql_categories);
			$xml_categories = simplexml_load_string($categories);
			if(count($xml_categories->item) > 0) {
				for($i=0;$i<count($xml_categories->item);$i++){
					$category = array();
					foreach($xml_categories->item[$i]->children() as $key => $value){			
						$category[$key] = $value;
					}
					if (count($category > 0)) {
						
						if($show_type == "all"||$show_type == "main")
						{
							if ($item_category_scalability != "on") {
								$valueArray[] = "";
								$nameArray[]  = "---------------------------";
							}
							$valueArray[] = $category["id"];
							$nameArray[] = $category["title"];
						}
						if ($item_category_scalability != "on") {
							$sql_subcategories = "SELECT id, title FROM $table WHERE category_id = ".$category["id"]." AND title <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
							$subcategories = db_getFromDBXML($table, false, false, false, false, $fields, $sql_subcategories);
							$xml_subcategories = simplexml_load_string($subcategories);
							if ($subcategories) {
								if(count($xml_subcategories->item) > 0) {
									for($j=0;$j<count($xml_subcategories->item);$j++){
										$subcategory = array();
										foreach($xml_subcategories->item[$j]->children() as $key => $value) {
											$subcategory[$key] = $value;
										}
										if (count($subcategory > 0)) {
											if($show_type == "all"||$show_type == "sub"||$show_type == "sub2")
											{
												$valueArray[] = $subcategory["id"];
												$nameArray[] = " &raquo; ".$subcategory["title"];
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
		if ($item_category_scalability != "on") {
			//$valueArray[] = "";
			//$nameArray[] = "---------------------------";
		}

		if($filter)
		{
			if($show_type == "sub2")	
			{
				if((count($valueArray)==1 && $valueArray[0]=="")||empty($valueArray))
					return "empty";
				$categoryFilterSubDD2 = html_selectBoxCat("category_sub_filter_id2", $nameArray, $valueArray, ($selected_category_id!=0?$selected_category_id:""), "", "", system_showText(LANG_SEARCH_LABELCBSUBCATEGORY), $type);	
				return $categoryFilterSubDD2;
			}
			else if($show_type == "sub")	
			{
				if(count($valueArray)==1 && $valueArray[0]=="")
					return "empty";
				$categoryFilterSubDD = html_selectBoxCat("category_sub_filter_id", $nameArray, $valueArray, ($selected_category_id!=0?$selected_category_id:""), "onchange=\"showAdvancedSearch('".$type."', ".$template_id.",false, true, ".$selected_category_id.", 'sub2', ".$main_id.",this.value)\"", "", system_showText(LANG_SEARCH_LABELCBSUBCATEGORY), $type);	
				return $categoryFilterSubDD;
			}
			else		
			{
				$categoryFilterDD = html_selectBoxCat("category_filter_id", $nameArray, $valueArray, ($selected_category_id!=0?$selected_category_id:""), "onchange=\"showAdvancedSearch('".$type."', ".$template_id.",false, true, ".$selected_category_id.", 'sub', this.value)\"", "", system_showText(LANG_SEARCH_LABELCBCATEGORY), $type);	
				return $categoryFilterDD;
			}
		}
		
		$categoryDD = html_selectBoxCat("category_id", $nameArray, $valueArray, "", "", "", system_showText(LANG_SEARCH_LABELCBCATEGORY), $type);	
		return $categoryDD;
		
	}
	
	
	function system_ListingLevel_Constant(){
		
		if(defined('LISTING_LEVEL_INFORMATION')){
			return false;
		}
		
		unset($listingLevelObj, $array_listing_level);
		
		$listingLevelObj = new ListingLevel();
		$array_listing_level = $listingLevelObj->convertTableToArray();
		
		if(is_array($array_listing_level)){
			define("LISTING_LEVEL_INFORMATION", serialize($array_listing_level));
		}
		
	} 
	
	/*
	 * Function to get information about levels
	 */
	function system_getListingLevelInformation($index){

		if(!defined('LISTING_LEVEL_INFORMATION')){
			system_ListingLevel_Constant();
		}

		$aux_listinglevel_information = unserialize(LISTING_LEVEL_INFORMATION);
		$array_listinglevel_information = $aux_listinglevel_information[$index];

		if(is_array($array_listinglevel_information)){
			return $array_listinglevel_information;
		}else{
			return false;
		}

	}
    
    function system_updateMaptuningDate($table, $id, $maptuning_done){
        if ($maptuning_done == "y" && ($table == "Listing" || $table == "Classified" || $table == "Event") && $id){
            $dbObj_main = db_getDBObject(DEFAULT_DB, true);
            $db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbObj_main);
            
            $sql = "UPDATE $table SET maptuning_date = NOW() WHERE id = ".db_formatNumber($id);
            $db->query($sql);
        }
    }
    
    function system_getFrontendPath($file, $folder = "frontend", $original = false, $moduleFolder = false){
        $path = "";
        $preview = false;
        $previewHash = md5("sitemgrPreview");

        if (($file == "header.php" || $file == "footer.php") && (isset($_GET[$previewHash])) && file_exists(HTMLEDITOR_FOLDER."/".EDIR_THEME."/preview_".$file) && !$original){
            $preview = true;
        }
		
        if (($file == "header.php" || $file == "footer.php" || $file == "header_menu.php" || $file == "footer_menu.php") && $folder == "layout" && file_exists(HTMLEDITOR_FOLDER."/".EDIR_THEME."/".($preview ? "preview_" : "").$file) && !$original){
            $path = HTMLEDITOR_FOLDER."/".EDIR_THEME."/".($preview ? "preview_" : "").$file;
        } else {
            if (file_exists(THEMEFILE_DIR."/".EDIR_THEME."/$folder/$file")){
                $path = THEMEFILE_DIR."/".EDIR_THEME."/$folder/$file";
				
            } else {
                if ($moduleFolder) {
                    if (file_exists($moduleFolder."/$file")){
                        $path = $moduleFolder."/$file"; 
                    } else {
                        $path = EDIRECTORY_ROOT."/$folder/$file"; 
                    }
                } else {
                    $path = EDIRECTORY_ROOT."/$folder/$file"; 
		        }
            }
	    }
	    return $path;
    }
    
    function system_getStylePath($file, $theme, $relative = false, $original = false){
        $path = "";
        
        if (file_exists(HTMLEDITOR_THEMEFILE_DIR."/".$theme."/editor_$file") && !$original){
            $path = ($relative ? HTMLEDITOR_THEMEFILE_DIR : HTMLEDITOR_THEMEFILE_URL)."/".$theme."/editor_$file";
        } else {
            $path = ($relative ? THEMEFILE_DIR : THEMEFILE_URL)."/$theme/$file"; 
        }
        
        return $path;
    }
    
    function system_downloadFile($filePath, $name, $ext){
        
        $fileName = EXTRAFILE_DIR."/$name.zip";
		@unlink($fileName);
		$zipObj = new Zip();
		$zipObj->setZipFile($fileName);
        if (is_array($filePath)) {
            foreach($filePath as $file) {
                $fileContent = file_get_contents($file["file"]);
				$zipObj->addFile($fileContent, $file["name"]);
            }
        } else {
            $fileContent = file_get_contents($filePath);
            $zipObj->addFile($fileContent, $name.".".$ext);
        }
        $zipObj->finalize();
        $zipObj->sendZip($name.'.zip');
		exit;
    }
    
    function system_downloadAPIDoc(){
        $fileName = EXTRAFILE_DIR."/eDirectoryAPI.zip";
        @unlink($fileName);
		$zipObj = new Zip();
		$zipObj->setZipFile($fileName);
        
        $filename_plugin_readMe = EDIRAPI_FILE_PATH."/API_Documentation_V1.pdf";
        $file_name_readMe = "API_Documentation_V1.pdf";
        $fileContents_readMe_file = file_get_contents($filename_plugin_readMe);
        $zipObj->addFile($fileContents_readMe_file, $file_name_readMe);
        $zipObj->finalize();
        $zipObj->sendZip('eDirectoryAPI.zip');
    }
    
    function system_getThemeTemplate(){
        if(defined("USING_THEME_TEMPLATE")) return false;

        $dbMain = db_getDBObject(DEFAULT_DB, true);
        $dbDomain = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
        
        $sql = "SELECT id FROM ListingTemplate WHERE editable = 'n' AND theme = '".EDIR_THEME."'";
        $result = $dbDomain->query($sql);
        if (mysql_num_rows($result) > 0){
            $row = mysql_fetch_array($result);
            define("USING_THEME_TEMPLATE", true);
            define("THEME_TEMPLATE_ID", $row["id"]);
            
            $arrayFields = array();
            $auxListingTemplate = new ListingTemplate(THEME_TEMPLATE_ID);
            $fieldBedroom = $auxListingTemplate->getFieldByLabel("LANG_LABEL_TEMPLATE_BEDROOM");
            $fieldBathroom = $auxListingTemplate->getFieldByLabel("LANG_LABEL_TEMPLATE_BATHROOM");
            $fieldSquareFeet = $auxListingTemplate->getFieldByLabel("LANG_LABEL_TEMPLATE_SQUARE");
            $fieldPrice = $auxListingTemplate->getFieldByLabel("LANG_LABEL_TEMPLATE_PRICE");
            $fieldAcre = $auxListingTemplate->getFieldByLabel("LANG_LABEL_TEMPLATE_ACRES");
            if ($fieldBedroom || $fieldBathroom  || $fieldSquareFeet || $fieldPrice){
                $arrayFields["bedroom_field"] = $fieldBedroom;
                $arrayFields["bathroom_field"] = $fieldBathroom;
                $arrayFields["squarefeet_field"] = $fieldSquareFeet;
                $arrayFields["price_field"] = $fieldPrice;
                $arrayFields["acre_field"] = $fieldAcre;
                define("TEMPLATE_SUMMARY_FIELDS", serialize($arrayFields));
            } else {
                define("TEMPLATE_SUMMARY_FIELDS", false);
            }
            
            
            
        } else {
            define("USING_THEME_TEMPLATE", false);
        }
    }
    
    
    /**
     * Get fields to prepare form to module 
     * @param string $module 
     * @return array $array_fields
     */
    function system_getFormFields($module, $level){
        
        if(EDIR_THEME){
            $theme = EDIR_THEME;
        } else {
            $theme = "default";
        }
        
        $dbMain = db_getDBObject(DEFAULT_DB, true);
        $db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
        
        /**
         * Get fields 
         */
        if(is_string($module) && is_string($theme) && is_numeric($level)){
            
            $sql = "SELECT field FROM ".ucfirst($module)."Level_Field WHERE theme = '".string_strtolower($theme)."' AND level = ".$level;
            $result = $db->unbuffered_query($sql);
            if($result){
                unset($array_fields);
                while($row = mysql_fetch_assoc($result)){
                    $array_fields[] = $row["field"];
                }
                return $array_fields;
            } else {
                return false;
            }            
        } else {
            return false;
        }
    }
    
    function system_getLevelDetail($table){
        
        $arrayLevels = array();
        $dbMain = db_getDBObject(DEFAULT_DB, true);
        $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
        $sql = "SELECT value FROM $table WHERE detail = 'y' AND active = 'y' AND theme = ".db_formatString(EDIR_THEME)." ORDER BY value";
        $result = $dbObj->query($sql);
        if (mysql_num_rows($result) > 0){
            while ($row = mysql_fetch_assoc($result)){
              $arrayLevels[] = $row["value"];
            }
            return $arrayLevels;
        } else {
            return false;
        }
    }
    
    function system_sidebarInfo(&$label, &$extraFields){
        
        $extraFields = false;
        if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER){
            $extraFields = true;
            $label = system_showText(LANG_BROWSELISTINGS);
        } elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER){
            $label = system_showText(LANG_BROWSEEVENTS);
        } elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER){
            $label = system_showText(LANG_BROWSECLASSIFIEDS);
        } elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER){
            $label = system_showText(LANG_BROWSEARTICLES);
        } elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER){
            $label = system_showText(LANG_BROWSEPROMOTIONS);
        } elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER){
            $label = system_showText(LANG_BROWSEPOSTS);
        } else {
            $extraFields = true;
            $label = system_showText(LANG_BROWSELISTINGS);
        }
    }
        
    function system_getDropdownValues($template_id, $field, $block = 5, $inc = false){
        $dbMain = db_getDBObject(DEFAULT_DB, true);
        $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
        $fields = array();
        $dropdownValues = array();
        
        $themeSummaryFields = unserialize(TEMPLATE_SUMMARY_FIELDS);

        $sql = "SELECT min(CAST($field AS SIGNED INTEGER)) min_value, max(CAST($field AS SIGNED INTEGER)) max_value FROM Listing WHERE listingtemplate_id = $template_id AND $field > 0";

        $row = mysql_fetch_assoc($dbObj->query($sql));
        $interval = $row["max_value"] - $row["min_value"];
        if ($interval > 0){
            $sumBlock = round($interval/$block);
            if ($row["min_value"] > 0){
                if ($inc){
                    $fields[] = $row["min_value"]+1;
                } else {
                    $fields[] = $row["min_value"];
                }
            }
            for ($i = 1; $i < $block; $i++){
                if ($inc){
                    $fields[] = ($row["min_value"]+1) + $i*$sumBlock;
                } else {
                    $fields[] = $row["min_value"] + $i*$sumBlock;
                }
            }
            if ($row["max_value"] > 0){
                if ($inc){
                    $fields[] = $row["max_value"]+1;
                } else {
                    $fields[] = $row["max_value"];
                }
            }
        } elseif ($row["max_value"] == $row["min_value"]){
            if ($inc){
                $fields[] = $row["max_value"]+1;
            } else {
                $fields[] = ($row["max_value"] ? $row["max_value"] : 0);
            }
        }

        if (count($fields) > 0) {
            $dropdownValues[0][0] = "--------------";
            $dropdownValues[0][1] = "";
            for($i = 1; $i <= count($fields); $i++){
                if ($field == $themeSummaryFields["price_field"]){
                    $dropdownValues[$i][0] = CURRENCY_SYMBOL.format_money($fields[$i-1]);
                } elseif($field == $themeSummaryFields["squarefeet_field"]) {
                    $dropdownValues[$i][0] = $fields[$i-1];
                } elseif($field == $themeSummaryFields["acre_field"]) {
                    $dropdownValues[$i][0] = $fields[$i-1];
                } else {
                    $dropdownValues[$i][0] = $fields[$i-1];
                }
                $dropdownValues[$i][1] = $fields[$i-1];
            }
        }
        
        return $dropdownValues;        
        
    }
    
    function system_getEditorAvailable(){
        $availableFiles = array();
        $langs = explode(",", EDIR_LANGUAGES);
    
        //Header And Footer
//        $availableFiles[] = "header.php";
//        $availableFiles[] = "footer.php";

        //CSS Files
        $availableFiles[] = "advertise.css";
        $availableFiles[] = "blog.css";
        $availableFiles[] = "content_custom.css";
        $availableFiles[] = "detail.css";
        $availableFiles[] = "front.css";
        $availableFiles[] = "members.css";
        $availableFiles[] = "order.css";
        $availableFiles[] = "popup.css";
        $availableFiles[] = "print.css";
        $availableFiles[] = "profile.css";
        $availableFiles[] = "results.css";
        $availableFiles[] = "structure.css";

        //Lang Files
//        foreach($langs as $lang){
//            $availableFiles[] = $lang.".php";
//        }
        
        return $availableFiles;
    }
    
    function system_CreateZipFile($filePath, $name, $ext, $path){
        
        $fileName = $path."/$name.zip";
		@unlink($fileName);
		$zipObj = new Zip();
		$zipObj->setZipFile($fileName);
        $fileContent = file_get_contents($filePath);
        $zipObj->addFile($fileContent, $name.".".$ext);
        $zipObj->finalize();                
		return true;
    }
    
    function system_generateEdirLog($file_name, $message){
            
        if (ENABLE_LOG && LOG_SIZE_ROTATE && LOG_PATH) {

            /**
             *  File Rotate
             */
            $aux_file_name = LOG_PATH."/domain_".SELECTED_DOMAIN_ID."_".$file_name;
            if (file_exists($aux_file_name)) {

                $aux_filesize = filesize($aux_file_name);
                if (round($aux_filesize / 1048576, 2) >= LOG_SIZE_ROTATE ) {                

                    /**
                     * Zip file
                     */
                    $zipObj = new Zip();
                    system_CreateZipFile($aux_file_name, $file_name."_".date("Y")."-".date("M")."-".date("d")."-".date("H").":".date("i").":".date("s"), "zip", LOG_PATH);
                    $log_file = fopen($aux_file_name, 'w+');
                    
                } else {
                    $log_file = fopen($aux_file_name, 'a+');
                }

            } else {
                $log_file = fopen($aux_file_name, 'a+');
            }

            if ($log_file) {

                fwrite($log_file, "Date: ".date("Y")."-".date("M")."-".date("d")." - ".date("H").":".date("i").":".date("s")." - ".$message."\n");
                fclose($log_file);

            }
        }
    }
    
    function system_writeConstantsFile($filePath, $domain_id, $values) {
        
        if ($fileConst = fopen($filePath, "w+")) {
            $buffer = "";
            $buffer .= "<?".PHP_EOL;;
            $buffer .= "/*==================================================================*\\".PHP_EOL;
            $buffer .= "######################################################################".PHP_EOL;
            $buffer .= "#                                                                    #".PHP_EOL;
            $buffer .= "# Copyright 2005 Arca Solutions, Inc. All Rights Reserved.           #".PHP_EOL;
            $buffer .= "#                                                                    #".PHP_EOL;
            $buffer .= "# This file may not be redistributed in whole or part.               #".PHP_EOL;
            $buffer .= "# eDirectory is licensed on a per-domain basis.                      #".PHP_EOL;
            $buffer .= "#                                                                    #".PHP_EOL;
            $buffer .= "# ---------------- eDirectory IS NOT FREE SOFTWARE ----------------- #".PHP_EOL;
            $buffer .= "#                                                                    #".PHP_EOL;
            $buffer .= "# http://www.edirectory.com | http://www.edirectory.com/license.html #".PHP_EOL;
            $buffer .= "######################################################################".PHP_EOL;
            $buffer .= "\*==================================================================*/".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# * FILE: /custom/domain_$domain_id/conf/constants.inc.php".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# FLAGS - on/off".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "# MODULES".PHP_EOL;
            $buffer .= "# NOTE: Do not alter this area of the code manually.".PHP_EOL;
            $buffer .= "# Any changes will require eDirectory to be activated again.".PHP_EOL;
            $buffer .= "# P.S.: you can turn off it any time.".PHP_EOL;
            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "define(\"EVENT_FEATURE\", \"".($values["event_feature"] ? $values["event_feature"] : EVENT_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"BANNER_FEATURE\", \"".($values["banner_feature"] ? $values["banner_feature"] : BANNER_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"CLASSIFIED_FEATURE\", \"".($values["classified_feature"] ? $values["classified_feature"] : CLASSIFIED_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"ARTICLE_FEATURE\", \"".($values["article_feature"] ? $values["article_feature"] : ARTICLE_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"PROMOTION_FEATURE\", \"".($values["promotion_feature"] ? $values["promotion_feature"] : PROMOTION_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"BLOG_FEATURE\", \"".($values["blog_feature"] ? $values["blog_feature"] : BLOG_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"ZIPCODE_PROXIMITY\", \"".($values["zipproximity_feature"] ? $values["zipproximity_feature"] : ZIPCODE_PROXIMITY)."\");".PHP_EOL;

            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "# FEATURES".PHP_EOL;
            $buffer .= "# NOTE: Do not alter this area of the code manually.".PHP_EOL;
            $buffer .= "# Any changes will require eDirectory to be activated again.".PHP_EOL;
            $buffer .= "# P.S.: you can turn off it any time.".PHP_EOL;
            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "define(\"CUSTOM_INVOICE_FEATURE\", \"".($values["custominvoice_feature"] ? $values["custominvoice_feature"] : CUSTOM_INVOICE_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"CLAIM_FEATURE\", \"".($values["claim_feature"] ? $values["claim_feature"] : CLAIM_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"LISTINGTEMPLATE_FEATURE\", \"".($values["listingtemplate_feature"] ? $values["listingtemplate_feature"] : LISTINGTEMPLATE_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"MOBILE_FEATURE\", \"".($values["mobile_feature"] ? $values["mobile_feature"] : MOBILE_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"MULTILANGUAGE_FEATURE\", \"".($values["multilanguage_feature"] ? $values["multilanguage_feature"] : MULTILANGUAGE_FEATURE)."\");".PHP_EOL;
            $buffer .= "define(\"MAINTENANCE_FEATURE\", \"".($values["maintenance_feature"] ? $values["maintenance_feature"] : MAINTENANCE_FEATURE)."\");".PHP_EOL;

            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "# EXTRA FEATURES".PHP_EOL;
            $buffer .= "# NOTE: Do not alter this area of the code manually.".PHP_EOL;
            $buffer .= "# Any changes will require eDirectory to be activated again.".PHP_EOL;
            $buffer .= "# P.S.: you can turn off it any time.".PHP_EOL;
            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "define(\"SITEMAP_FEATURE\", \"".($values["sitemap_feature"] ? $values["sitemap_feature"] : SITEMAP_FEATURE)."\");".PHP_EOL;

            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "# CUSTOMIZATIONS".PHP_EOL;
            $buffer .= "# NOTE: Do not alter this area of the code manually.".PHP_EOL;
            $buffer .= "# Any changes will require eDirectory to be activated again.".PHP_EOL;
            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "define(\"BRANDED_PRINT\", \"".($values["branded_print"] ? $values["branded_print"] : BRANDED_PRINT)."\");".PHP_EOL;

            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "# PAYMENT SYSTEM FEATURE".PHP_EOL;
            $buffer .= "# NOTE: Do not alter this area of the code manually.".PHP_EOL;
            $buffer .= "# Any changes will require eDirectory to be activated again.".PHP_EOL;
            $buffer .= "# P.S.: you can turn off it any time.".PHP_EOL;
            $buffer .= "# ****************************************************************************************************".PHP_EOL;
            $buffer .= "define(\"PAYMENTSYSTEM_FEATURE\", \"".($values["paymentsystem_feature"] ? $values["paymentsystem_feature"] : PAYMENTSYSTEM_FEATURE)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# EDIRECTORY TITLE".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"EDIRECTORY_TITLE\", \"".($values["name"] ? $values["name"] : EDIRECTORY_TITLE)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# GEO IP CONFIGURATION".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"GEOIP_FEATURE\", \"".($values["geoip_feature"] ? $values["geoip_feature"] : GEOIP_FEATURE)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# SHOW BANNER MODE".PHP_EOL;
            $buffer .= "# NOTE: This flag is only to the front view".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"SHOW_INACTIVE_BANNER\", \"".($values["inactive_banner"] ? $values["inactive_banner"] : SHOW_INACTIVE_BANNER)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# CACHE FULL SETTINGS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_FEATURE\", \"".($values["cachefull_feature"] ? $values["cachefull_feature"] : CACHE_FULL_FEATURE)."\"); //be sure that the constant below is also on if you turn this one on".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_ZLIB_COMPRESSION_IF_AVAILABLE\", \"".($values["cachefull_zlib"] ? $values["cachefull_zlib"] : CACHE_FULL_ZLIB_COMPRESSION_IF_AVAILABLE)."\"); //this constant must be on if CACHE_FULL_FEATURE is on".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_VERBOSE_MODE\", \"".($values["cachefull_verbose"] ? $values["cachefull_verbose"] : CACHE_FULL_VERBOSE_MODE)."\"); ".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_LOG_EXPIRATION_QUERIES\", \"".($values["cachefull_queries"] ? $values["cachefull_queries"] : CACHE_FULL_LOG_EXPIRATION_QUERIES)."\"); ".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_INCLUDE_CACHE_COMMENT_AT_PAGE\", \"".($values["cachefull_comments"] ? $values["cachefull_comments"] : CACHE_FULL_INCLUDE_CACHE_COMMENT_AT_PAGE)."\");".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_FOR_LOGGED_MEMBERS\", \"".($values["members"] ? $values["members"] : CACHE_FULL_FOR_LOGGED_MEMBERS)."\");".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_REMOVE_FILES_WHEN_DISABLED\", \"".($values["disabled"] ? $values["disabled"] : CACHE_FULL_REMOVE_FILES_WHEN_DISABLED)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# CACHE FULL FEATURE CONTENT SETTINGS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_ALWAYS_FRESH_FEATURED_LISTING\", \"".($values["cachefull_refreshL"] ? $values["cachefull_refreshL"] : CACHE_FULL_ALWAYS_FRESH_FEATURED_LISTING)."\");".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_ALWAYS_FRESH_FEATURED_DEAL\", \"".($values["cachefull_refreshP"] ? $values["cachefull_refreshP"] : CACHE_FULL_ALWAYS_FRESH_FEATURED_DEAL)."\");".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_ALWAYS_FRESH_FEATURED_CLASSIFIED\", \"".($values["cachefull_refreshC"] ? $values["cachefull_refreshC"] : CACHE_FULL_ALWAYS_FRESH_FEATURED_CLASSIFIED)."\");".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_ALWAYS_FRESH_FEATURED_EVENT\", \"".($values["cachefull_refreshE"] ? $values["cachefull_refreshE"] : CACHE_FULL_ALWAYS_FRESH_FEATURED_EVENT)."\");".PHP_EOL;
            $buffer .= "define(\"CACHE_FULL_ALWAYS_FRESH_FEATURED_ARTICLE\", \"".($values["cachefull_refreshA"] ? $values["cachefull_refreshA"] : CACHE_FULL_ALWAYS_FRESH_FEATURED_ARTICLE)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# CACHE PARTIAL SETTINGS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"CACHE_PARTIAL_FEATURE\", \"".($values["cachepartial_feature"] ? $values["cachepartial_feature"] : CACHE_PARTIAL_FEATURE)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# FRONT SEARCH".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"SEARCH_FORCE_BOOLEANMODE\", \"".($values["search_booleanmode"] ? $values["search_booleanmode"] : SEARCH_FORCE_BOOLEANMODE)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# GALLERY IMAGES".PHP_EOL;
            $buffer .= "#  - Turn on the constant GALLERY_FREE_RATIO to remove the crop for wide images.".PHP_EOL;
            $buffer .= "#  - Remember to turn off the constant RESIZE_IMAGES_UPGRADE.".PHP_EOL;
            $buffer .= "#  - ATTENTION! The thumb preview in the upload window will not be shown when this constant is turned on.".PHP_EOL;
            $buffer .= "#  - You can also force all jpg images to be saved as png for better quality by turning on the constant FORCE_SAVE_JPG_AS_PNG.".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"GALLERY_FREE_RATIO\", \"".($values["free_ratio"] ? $values["free_ratio"] : GALLERY_FREE_RATIO)."\");".PHP_EOL;
            $buffer .= "define(\"FORCE_SAVE_JPG_AS_PNG\", \"".($values["jpg_as_png"] ? $values["jpg_as_png"] : FORCE_SAVE_JPG_AS_PNG)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# RESIZE IMAGES AFTER UPGRADE".PHP_EOL;
            $buffer .= "#  on (DEFAULT) - all images will be stretched to fit the new dimensions".PHP_EOL;
            $buffer .= "#  off - all images will keep the same size, but the layout can be affected".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"RESIZE_IMAGES_UPGRADE\", \"".($values["resize_images"] ? $values["resize_images"] : RESIZE_IMAGES_UPGRADE)."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# SITEMAP LINKS".PHP_EOL;
            $buffer .= "#  - Turn on to add \"www\" to sitemap links.".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"SITEMAP_ADD_WWW\", \"".($values["sitemap_www"] ? $values["sitemap_www"] : SITEMAP_ADD_WWW)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# MODULES ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_LISTING_MODULE\", \"".($values["alias_listing_module"] ? $values["alias_listing_module"] : ALIAS_LISTING_MODULE)."\");".PHP_EOL;
            $buffer .= "define(\"ALIAS_PROMOTION_MODULE\", \"".($values["alias_promotion_module"] ? $values["alias_promotion_module"] : ALIAS_PROMOTION_MODULE)."\");".PHP_EOL;
            $buffer .= "define(\"ALIAS_EVENT_MODULE\", \"".($values["alias_event_module"] ? $values["alias_event_module"] : ALIAS_EVENT_MODULE)."\");".PHP_EOL;
            $buffer .= "define(\"ALIAS_ARTICLE_MODULE\", \"".($values["alias_article_module"] ? $values["alias_article_module"] : ALIAS_ARTICLE_MODULE)."\");".PHP_EOL;
            $buffer .= "define(\"ALIAS_CLASSIFIED_MODULE\", \"".($values["alias_classified_module"] ? $values["alias_classified_module"] : ALIAS_CLASSIFIED_MODULE)."\");".PHP_EOL;
            $buffer .= "define(\"ALIAS_BLOG_MODULE\", \"".($values["alias_blog_module"] ? $values["alias_blog_module"] : ALIAS_BLOG_MODULE)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# BROWSE BY CATEGORY ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_CATEGORY_URL_DIVISOR\", \"".($values["alias_category_url_divisor"] ? $values["alias_category_url_divisor"] : ALIAS_CATEGORY_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# BROWSE BY LOCATION ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_LOCATION_URL_DIVISOR\", \"".($values["alias_location_url_divisor"] ? $values["alias_location_url_divisor"] : ALIAS_LOCATION_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# FACEBOOK SHARE ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_SHARE_URL_DIVISOR\", \"".($values["alias_share_url_divisor"] ? $values["alias_share_url_divisor"] : ALIAS_SHARE_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# CLAIM ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_CLAIM_URL_DIVISOR\", \"".($values["alias_claim_url_divisor"] ? $values["alias_claim_url_divisor"] : ALIAS_CLAIM_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# REVIEWS ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_REVIEW_URL_DIVISOR\", \"".($values["alias_review_url_divisor"] ? $values["alias_review_url_divisor"] : ALIAS_REVIEW_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# CHECKINS ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_CHECKIN_URL_DIVISOR\", \"".($values["alias_checkin_url_divisor"] ? $values["alias_checkin_url_divisor"] : ALIAS_CHECKIN_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# BACKLINK ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_BACKLINK_URL_DIVISOR\", \"".($values["alias_backlink_url_divisor"] ? $values["alias_backlink_url_divisor"] : ALIAS_BACKLINK_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# ALL CATEGORIES PAGE ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_ALLCATEGORIES_URL_DIVISOR\", \"".($values["alias_allcategories_url_divisor"] ? $values["alias_allcategories_url_divisor"] : ALIAS_ALLCATEGORIES_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# ALL LOCATIONS PAGE ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_ALLLOCATIONS_URL_DIVISOR\", \"".($values["alias_alllocations_url_divisor"] ? $values["alias_alllocations_url_divisor"] : ALIAS_ALLLOCATIONS_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# BLOG BROWSE BY DATE ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_ARCHIVE_URL_DIVISOR\", \"".($values["alias_archive_url_divisor"] ? $values["alias_archive_url_divisor"] : ALIAS_ARCHIVE_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# ADVERTISE ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_ADVERTISE_URL_DIVISOR\", \"".($values["alias_advertise_url_divisor"] ? $values["alias_advertise_url_divisor"] : ALIAS_ADVERTISE_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# CONTACTUS ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_CONTACTUS_URL_DIVISOR\", \"".($values["alias_contactus_url_divisor"] ? $values["alias_contactus_url_divisor"] : ALIAS_CONTACTUS_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# FAQ ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_FAQ_URL_DIVISOR\", \"".($values["alias_faq_url_divisor"] ? $values["alias_faq_url_divisor"] : ALIAS_FAQ_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# SITEMAP ALIAS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"ALIAS_SITEMAP_URL_DIVISOR\", \"".($values["alias_sitemap_url_divisor"] ? $values["alias_sitemap_url_divisor"] : ALIAS_SITEMAP_URL_DIVISOR)."\");".PHP_EOL;
            
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# MODULES URLS".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "define(\"LISTING_FEATURE_NAME\", \"".LISTING_FEATURE_NAME."\");".PHP_EOL;
            $buffer .= "define(\"LISTING_FEATURE_NAME_PLURAL\", LISTING_FEATURE_NAME.\"s\");".PHP_EOL;
            $buffer .= "define(\"LISTING_DEFAULT_URL\", NON_SECURE_URL.\"/\".ALIAS_LISTING_MODULE);".PHP_EOL.PHP_EOL;
            
            $buffer .= "define(\"PROMOTION_FEATURE_NAME\", \"".PROMOTION_FEATURE_NAME."\");".PHP_EOL;
            $buffer .= "define(\"PROMOTION_FEATURE_NAME_PLURAL\", PROMOTION_FEATURE_NAME.\"s\");".PHP_EOL;
            $buffer .= "define(\"PROMOTION_DEFAULT_URL\", NON_SECURE_URL.\"/\".ALIAS_PROMOTION_MODULE);".PHP_EOL.PHP_EOL;
            
            $buffer .= "define(\"EVENT_FEATURE_NAME\", \"".EVENT_FEATURE_NAME."\");".PHP_EOL;
            $buffer .= "define(\"EVENT_FEATURE_NAME_PLURAL\", EVENT_FEATURE_NAME.\"s\");".PHP_EOL;
            $buffer .= "define(\"EVENT_DEFAULT_URL\", NON_SECURE_URL.\"/\".ALIAS_EVENT_MODULE);".PHP_EOL.PHP_EOL;
            
            $buffer .= "define(\"CLASSIFIED_FEATURE_NAME\", \"".CLASSIFIED_FEATURE_NAME."\");".PHP_EOL;
            $buffer .= "define(\"CLASSIFIED_FEATURE_NAME_PLURAL\", CLASSIFIED_FEATURE_NAME.\"s\");".PHP_EOL;
            $buffer .= "define(\"CLASSIFIED_DEFAULT_URL\", NON_SECURE_URL.\"/\".ALIAS_CLASSIFIED_MODULE);".PHP_EOL.PHP_EOL;
                    
            $buffer .= "define(\"ARTICLE_FEATURE_NAME\", \"".ARTICLE_FEATURE_NAME."\");".PHP_EOL;
            $buffer .= "define(\"ARTICLE_FEATURE_NAME_PLURAL\", ARTICLE_FEATURE_NAME.\"s\");".PHP_EOL;
            $buffer .= "define(\"ARTICLE_DEFAULT_URL\", NON_SECURE_URL.\"/\".ALIAS_ARTICLE_MODULE);".PHP_EOL.PHP_EOL;
            
            $buffer .= "define(\"BLOG_FEATURE_NAME\", \"".BLOG_FEATURE_NAME."\");".PHP_EOL;
            $buffer .= "define(\"BLOG_FEATURE_NAME_PLURAL\", BLOG_FEATURE_NAME.\"s\");".PHP_EOL;
            $buffer .= "define(\"BLOG_DEFAULT_URL\", NON_SECURE_URL.\"/\".ALIAS_BLOG_MODULE);".PHP_EOL.PHP_EOL;
            
            $buffer .= "define(\"BANNER_FEATURE_NAME\", \"".BANNER_FEATURE_NAME."\");".PHP_EOL;
            $buffer .= "define(\"BANNER_FEATURE_NAME_PLURAL\", BANNER_FEATURE_NAME.\"s\");".PHP_EOL;
            
            $buffer .= "?>".PHP_EOL;

            fwrite($fileConst, $buffer, strlen($buffer));
            fclose($fileConst);
            return true;
        } else {
            return false;
        }
    }
    
    function system_writeScalabilityFile($filePath, $domain_id, $values) {
        
        if ($fileScal = fopen($filePath, "w+")) {
            $buffer = "";
            $buffer .= "<?".PHP_EOL;
            $buffer .= "/*==================================================================*\\".PHP_EOL;
            $buffer .= "######################################################################".PHP_EOL;
            $buffer .= "#                                                                    #".PHP_EOL;
            $buffer .= "# Copyright 2005 Arca Solutions, Inc. All Rights Reserved.           #".PHP_EOL;
            $buffer .= "#                                                                    #".PHP_EOL;
            $buffer .= "# This file may not be redistributed in whole or part.               #".PHP_EOL;
            $buffer .= "# eDirectory is licensed on a per-domain basis.                      #".PHP_EOL;
            $buffer .= "#                                                                    #".PHP_EOL;
            $buffer .= "# ---------------- eDirectory IS NOT FREE SOFTWARE ----------------- #".PHP_EOL;
            $buffer .= "#                                                                    #".PHP_EOL;
            $buffer .= "# http://www.edirectory.com | http://www.edirectory.com/license.html #".PHP_EOL;
            $buffer .= "######################################################################".PHP_EOL;
            $buffer .= "\*==================================================================*/".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# * FILE: /custom/domain_$domain_id/conf/scalability.inc.php".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# FLAGS - on/off".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 100.000 listings and/or more than 50.000 listings on the highest level".PHP_EOL;
            $buffer .= "define(\"LISTING_SCALABILITY_OPTIMIZATION\", \"".$values["listing_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 50.000 promotions".PHP_EOL;
            $buffer .= "define(\"PROMOTION_SCALABILITY_OPTIMIZATION\", \"".$values["promotion_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn off if edirectory has more than 50.000 promotions".PHP_EOL;
            $buffer .= "define(\"PROMOTION_SCALABILITY_USE_AUTOCOMPLETE\", \"".$values["promotion_auto_complete"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 100.000 events and/or more than 50.000 events on the highest level".PHP_EOL;
            $buffer .= "define(\"EVENT_SCALABILITY_OPTIMIZATION\", \"".$values["event_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 50.000 banners".PHP_EOL;
            $buffer .= "define(\"BANNER_SCALABILITY_OPTIMIZATION\", \"".$values["banner_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 100.000 classifieds and/or more than 50.000 classifieds on the highest level".PHP_EOL;
            $buffer .= "define(\"CLASSIFIED_SCALABILITY_OPTIMIZATION\", \"".$values["classified_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 100.000 articles and/or more than 50.000 articles on the highest level".PHP_EOL;
            $buffer .= "define(\"ARTICLE_SCALABILITY_OPTIMIZATION\", \"".$values["article_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 100.000 posts".PHP_EOL;
            $buffer .= "define(\"BLOG_SCALABILITY_OPTIMIZATION\", \"".$values["blog_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 20 main listing categories".PHP_EOL;
            $buffer .= "define(\"LISTINGCATEGORY_SCALABILITY_OPTIMIZATION\", \"".$values["listingcateg_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 20 main event categories".PHP_EOL;
            $buffer .= "define(\"EVENTCATEGORY_SCALABILITY_OPTIMIZATION\", \"".$values["eventcateg_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 20 main classified categories".PHP_EOL;
            $buffer .= "define(\"CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION\", \"".$values["classifiedcateg_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 20 main article categories".PHP_EOL;
            $buffer .= "define(\"ARTICLECATEGORY_SCALABILITY_OPTIMIZATION\", \"".$values["articlecateg_scalability"]."\");".PHP_EOL;

            $buffer .= "// suggestion: turn on if edirectory has more than 20 main blog categories".PHP_EOL;
            $buffer .= "define(\"BLOGCATEGORY_SCALABILITY_OPTIMIZATION\", \"".$values["blogcateg_scalability"]."\");".PHP_EOL;

            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "# AUTOMATIC FEATURES".PHP_EOL;
            $buffer .= "# ----------------------------------------------------------------------------------------------------".PHP_EOL;
            $buffer .= "// *** AUTOMATIC FEATURE *** (DONT CHANGE THESE LINES)".PHP_EOL;
            $buffer .= "if ((LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == \"on\") || (EVENTCATEGORY_SCALABILITY_OPTIMIZATION == \"on\") || (CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION == \"on\") || (ARTICLECATEGORY_SCALABILITY_OPTIMIZATION == \"on\") || (BLOGCATEGORY_SCALABILITY_OPTIMIZATION == \"on\")) {".PHP_EOL;
            $buffer .= "	define(\"CATEGORY_SCALABILITY_OPTIMIZATION\", \"on\");".PHP_EOL;
            $buffer .= "} else {".PHP_EOL;
            $buffer .= "	define(\"CATEGORY_SCALABILITY_OPTIMIZATION\", \"off\");".PHP_EOL;
            $buffer .= "}".PHP_EOL;
            $buffer .= "// *** AUTOMATIC FEATURE *** (DONT CHANGE THESE LINES)".PHP_EOL;
            $buffer .= "?>".PHP_EOL;

            fwrite($fileScal, $buffer, strlen($buffer));
            fclose($fileScal);
            return true;
        } else {
            return false;
        }
        
    }
        
    function system_retriveModuleByAliasURL() {
        
        if (string_strpos($_SERVER["REQUEST_URI"], "/".ALIAS_LISTING_MODULE."/") !== false) {
            return LISTING_FEATURE_FOLDER;
        } elseif (string_strpos($_SERVER["REQUEST_URI"], "/".ALIAS_EVENT_MODULE."/") !== false) {
            return EVENT_FEATURE_FOLDER;
        } elseif(string_strpos($_SERVER["REQUEST_URI"], "/".ALIAS_CLASSIFIED_MODULE."/") !== false) {
            return CLASSIFIED_FEATURE_FOLDER;
        } elseif(string_strpos($_SERVER["REQUEST_URI"], "/".ALIAS_ARTICLE_MODULE."/") !== false) {
            return ARTICLE_FEATURE_FOLDER;
        } elseif(string_strpos($_SERVER["REQUEST_URI"], "/".ALIAS_PROMOTION_MODULE."/") !== false) {
            return PROMOTION_FEATURE_FOLDER;
        } elseif(string_strpos($_SERVER["REQUEST_URI"], "/".ALIAS_BLOG_MODULE."/") !== false) {
            return BLOG_FEATURE_FOLDER;
        } else {
            return false;
        }
    }
    
    function system_CountCategoriestoAPP(){
        $db = db_getDBObject();

        // Count listing Category
        $sql = "select id from ListingCategory";
        $result = $db->query($sql);
        if($result){
            while($row = mysql_fetch_assoc($result)){
                unset($objectCategory);
                $objectCategory = new ListingCategory($row["id"]);
                $objectCategory->Save();

            }
        }


        // Count Article Category
        $sql = "select id from ArticleCategory";
        $result = $db->query($sql);
        if($result){
            while($row = mysql_fetch_assoc($result)){
                unset($objectCategory);
                $objectCategory = new ArticleCategory($row["id"]);
                $objectCategory->Save();

            }
        }


        // Count Classified Category
        $sql = "select id from ClassifiedCategory";
        $result = $db->query($sql);
        if($result){
            while($row = mysql_fetch_assoc($result)){
                unset($objectCategory);
                $objectCategory = new ClassifiedCategory($row["id"]);
                $objectCategory->Save();

            }
        }


        // Count Event Category
        $sql = "select id from EventCategory";
        $result = $db->query($sql);
        if($result){
            while($row = mysql_fetch_assoc($result)){
                unset($objectCategory);
                $objectCategory = new EventCategory($row["id"]);
                $objectCategory->Save();

            }
        }
    }
    
    
    /*Function is created by AShsihs*/
    function system_preparePaginationForListing($paging_url, $url_search_params, $pageObj, $letter, $screen, $aux_items_per_page, $adv_search = false){
		if ($adv_search){
			$aux_page_url = $paging_url."?".$url_search_params;
		} else {
			$aux_page_url = $paging_url.$url_search_params;
		}
		
		if($letter){
			if ($adv_search){
				$aux_page_url .= "&amp;letter=".$letter;
			} else {
				if(substr($aux_page_url,strlen($aux_page_url)-1) != "/"){
					$aux_page_url .= "/letter/".$letter;
				}else{
					$aux_page_url .= "letter/".$letter;
				}
			}
		}
		
		if ($adv_search){
            if (substr($aux_page_url, -1) == "?") {
               $aux_page_url .= "screen="; 
            } else {
                $aux_page_url .= "&amp;screen=";
            }
		} else {
			if(substr($aux_page_url,strlen($aux_page_url)-1) != "/"){
				$aux_page_url .= "/page/";
			}else{
				$aux_page_url .= "page/";
			}
		}
		
		$array_pages_code = $pageObj->getPaginationForListing($screen, $aux_items_per_page, $aux_page_url);
		
		return $array_pages_code;
	}

	
	/*Function is created */
	function system_advancedSearch_categoryListing($type = "listing", $filter = false, $selected_category_id = 0, $show_type = "all", $main_id=0, $template_id=0,$sub_id=0){
		
		$categoryCount = SHOW_CATEGORY_COUNT;
		
		if ($type == "promotion"){
			$type = "listing";
			$categoryCount = 'off';
		}
		$item_category_scalability = @constant(strtoupper($type)."CATEGORY_SCALABILITY_OPTIMIZATION");
		$table = ucfirst($type)."Category";
		$table_type = $type."category";
			
		/**
		 * Fields to get categories
		 */
		$fields = array();
		$fields[] = "id";
		$fields[] = "title";
		
		$categories = array();
		$subcategories = array();
		
		if(($show_type == "sub2" && $sub_id) || ($show_type=="sub" && $main_id))
		    $sql_categories = "SELECT id, title, friendly_url, active_".($type == "blog" ? "post" : $type)." FROM $table WHERE id=".($show_type=="sub"?$main_id:$sub_id)." AND category_id = ".($show_type=="sub"?0:$main_id)." AND title <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		else 
        	$sql_categories = "SELECT id, title, friendly_url, active_".($type == "blog" ? "post" : $type)." FROM $table WHERE category_id = 0 AND title <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
      	
		if ($sql_categories) {
			$categories = db_getCategory($table, false, false, false, false, $fields, $sql_categories);
			if(count($categories) > 0) {
				if ($item_category_scalability != "on") {
					for($i=0;$i<count($categories);$i++){
						
						$sql_subcategories = "SELECT id, title, friendly_url, active_".($type == "blog" ? "post" : $type)." FROM $table WHERE category_id = ".$categories[$i]["id"]." AND title <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
						$subcategories = db_getCategory($table, false, false, false, false, $fields, $sql_subcategories);
						$categories[$i]['subcategories'] = $subcategories;
					}
				}
			}
		}
		
		$categoryDD = html_lisitingCat("category_id", $categories, "", "", "", system_showText(LANG_SEARCH_LABELCBCATEGORY), $type,$selected_category_id,$sub_id,$categoryCount);	
		return $categoryDD;
		
	}
	
	function system_prepareLetterToPagination_Search($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, $fieldOnTable, $blog_module = false, $promotion_module = false, $listingForceJoin = false, $scalability = "off"){
	
		/*
		 * Get letters of events
		 */
		$letters = $pageObj->getString("letters");
                
		$module_letters = array();
		$module_not_letters = array();
		$aux_letters = array();
		$aux_letters = implode("','",$letters);
		$aux_letters = str_replace("#',", "", $aux_letters);
		$aux_letters .= "'";

		if ($scalability == "off"){
		
			$db = db_getDBObject();
			$sql = "SELECT SUBSTRING(".$fieldOnTable.",1,1) AS letter_field FROM ".$searchReturn["from_tables"].($searchReturn["where_clause"] ? " WHERE ".$searchReturn["where_clause"] : "")."  GROUP BY letter_field HAVING UPPER(letter_field) IN ($aux_letters)";
			$r = $db->query($sql);
			while($row = mysql_fetch_assoc($r)){
				$module_letters[] = $row["letter_field"];
			}
		} else {
			$module_letters = $letters;
		}

		if ($promotion_module){ 
			$auxID = "Promotion.id"; 
			$auxfieldOnTable = "Promotion.name";
		} else {
			if ($listingForceJoin == "on"){
				$auxID = "Listing_Summary.`id`";
			} else {
				$auxID = "`id`";
			}
			
			$auxfieldOnTable = $auxfieldOnTable;
		}
		
		if ($scalability == "off"){
            $sql = "SELECT $auxID FROM ".$searchReturn["from_tables"].($searchReturn["where_clause"] ? " WHERE ".$searchReturn["where_clause"]." AND $fieldOnTable REGEXP '^[^a-zA-Z].*$'" : " WHERE $auxfieldOnTable REGEXP '^[^a-zA-Z].*$'");
            $r = $db->query($sql);
			if (mysql_num_rows($r)) {
				$specialChar = true;
			}else {
				$specialChar = false;
			}
		} else {
			$specialChar = true;
		}
		
		unset($letters_menu);
		$letters_menu .= "<li>";
		foreach ($letters as $each_letter) {
			
			if ($_GET["url_full"] || $blog_module) {
				if($each_letter != "#"){
					if ( (in_array(strtoupper($each_letter), $module_letters)) || (in_array($each_letter, $module_letters)) ){
						$letters_menu .= "<a href=\"$paging_url".(($url_search_params) ? "$url_search_params" : "")."/letter/".$each_letter."\" ".(($each_letter == $letter) ? "class=\"active\"" : "" ).">".string_strtoupper($each_letter)."</a>";
					} else{
						$letters_menu .= "<span>".strtoupper($each_letter)."</span>";
					}
				} else{
					if ($specialChar){
						$letters_menu .= "<a href=\"$paging_url".(($url_search_params) ? "$url_search_params" : "")."/letter/no\" ".(($letter == "no") ? "class=\"active\"" : "" ).">".string_strtoupper($each_letter)."</a>";
					} else{
						$letters_menu .="<span>#</span>";
					}
				}

			}else{
				if ($each_letter == "#") {
					if ($specialChar){
						$letters_menu .= "<a href=\"$paging_url?letter=no".(($url_search_params) ? "&amp;$url_search_params" : "")."\" ".(($letter == "no") ? "class=\"active\"" : "" ).">".string_strtoupper($each_letter)."</a>";
					} else {
						$letters_menu .= "<span>#</span>";
					}
				} else {
					if ( (in_array(strtoupper($each_letter), $module_letters)) || (in_array($each_letter, $module_letters)) ){
						$letters_menu .= "<a href=\"$paging_url?letter=".$each_letter.(($url_search_params) ? "&amp;$url_search_params" : "")."\" ".(($each_letter == $letter) ? "class=\"active\"" : "" ).">".string_strtoupper($each_letter)."</a>";
					} else {
						$letters_menu .= "<span>".strtoupper($each_letter)."</span>";
					}
				}
			} 
			
		}
		$letters_menu .="</li>";
		return $letters_menu;
	}
	
	
	/*Function for image Gallery on the detail page*/
	
	function system_showFrontGallery_detail($galleries = 0, $level = 0, $user = false, $imagesToShow = GALLERY_DETAIL_IMAGES, $type = "listing", $tPreview = false) {
		if ($tPreview) {
			$gallery_code_final = "	<ul>";
			$gallery_code_final .= "	<li>";
			$gallery_code_final .= "		<span class=\"no-image\" style=\"cursor: default;\">";
			$gallery_code_final .= "		</span>";
			$gallery_code_final .= "	</li>";
			$gallery_code_final .= "	<li>";
			$gallery_code_final .= "		<span class=\"no-image\" style=\"cursor: default;\">";
			$gallery_code_final .= "		</span>";
			$gallery_code_final .= "	</li>";
			$gallery_code_final .= "	<li class=\"pd-0\">";
			$gallery_code_final .= "		<span class=\"no-image\" style=\"cursor: default;\">";
			$gallery_code_final .= "		</span>";
			$gallery_code_final .= "	</li>";
			$gallery_code_final .= "</ul>";
			/*$gallery_code_final .= "<p class=\"caption\">";
			$gallery_code_final .= "	<a href=\"javascript:void(0);\" style=\"cursor: default;\">".system_showText(LANG_GALLERYCLICKHERE)."</a> ";
			$gallery_code_final .= "	".system_showText(LANG_GALLERYSLIDESHOWTEXT);
			$gallery_code_final .= "</p>";*/
		} else {
			$gallery_code_final = "";
			
			if (count($galleries)>0) {

				if ($type=="listing") $item_max_gallery = LISTING_MAX_GALLERY;
				elseif ($type=="event") $item_max_gallery = EVENT_MAX_GALLERY;
				elseif ($type=="classified") $item_max_gallery = CLASSIFIED_MAX_GALLERY;
				elseif ($type=="article") $item_max_gallery = ARTICLE_MAX_GALLERY;
				else return "";

				while (count($galleries) > $item_max_gallery) {
					array_pop($galleries);
				}
					
				foreach ($galleries as $each_gallery) {

					$gallery_code = "";

					$galleryObj = new Gallery($each_gallery);

					if ($galleryObj->getNumber("id") && $galleryObj->image && count($galleryObj->image) > 0) {

						if ($type=="listing") $galleryLevel = new ListingLevel();
						elseif ($type=="event") $galleryLevel = new EventLevel();
						elseif ($type=="classified") $galleryLevel = new ClassifiedLevel();
						elseif ($type=="article") $galleryLevel = new ArticleLevel();
						else return "";

						$maxImages = $galleryLevel->getImages($level);

						if (($maxImages) && (($maxImages > 0) || ($maxImages == -1))) {

							$totalImages = ($maxImages >= count($galleryObj->image)) ? count($galleryObj->image) : $maxImages;
							if ($maxImages == -1) $totalImages = count($galleryObj->image);

							$gallery_code .= "<ul>";

							$number_of_images = 0;

							$i = 0;
							for ($imgInd = 0; $imgInd < $totalImages; $imgInd++) {

								$presentImg = $galleryObj->image[$imgInd];

								$imageObj = new Image($presentImg["image_id"]);
								$imageThumbObj = new Image($presentImg["thumb_id"]);

								$slideshowpopup = "javascript:void(0);";
								$slideshowpopupc = "javascript:void(0);";
								$slideshowstyle = "style=\"cursor:default;\"";
								if ($user){
									$slideshowpopup = DEFAULT_URL."/popup/popup.php?pop_type=slideshow&amp;gallery_id=".$each_gallery."&amp;".$type."_level=".$level."&amp;image_id=".$presentImg["image_id"];
									$slideshowpopupc = DEFAULT_URL."/popup/popup.php?pop_type=slideshow&amp;gallery_id=".$each_gallery."&amp;".$type."_level=".$level;
									$slideshowstyle = "";
								}

								if ($imageObj->imageExists() && $imageThumbObj->imageExists()) {

									if ($number_of_images < $imagesToShow) {

										$gallery_code .= "<li ".(($imgInd == ($imagesToShow - 1)) ? ("class=\"pd-0\"") : ("")).">";
										$gallery_code .= "<a href=\"".$slideshowpopup."\" $slideshowstyle class=\"iframe fancy_window_gallery\">";
										$gallery_code .= $imageThumbObj->getTag(true, IMAGE_GALLERY_THUMB_WIDTH, IMAGE_GALLERY_THUMB_HEIGHT, $presentImg["thumb_caption"], true);
										$wrapWidth = (IMAGE_GALLERY_THUMB_WIDTH/5); // each character have a width of 5px
										$gallery_code .= "</a>";
										$gallery_code .= "</li>";

									}

									$number_of_images++;

									$i++;

								}

							}

							$gallery_code .= "</ul>";

							/*$gallery_code .= "<p class=\"caption\"><a href=\"".$slideshowpopupc."\" $slideshowstyle class=\"iframe fancy_window_gallery\">".system_showText(LANG_GALLERYCLICKHERE)."</a> ".system_showText(LANG_GALLERYSLIDESHOWTEXT)."</p>";*/
							
						}

						unset($galleryLevel);
						unset($galleryObj);

						if ($number_of_images==0) $gallery_code = "";

					}

					$gallery_code_final .= $gallery_code;

				}

			}
		}

		return $gallery_code_final;

	}
?>