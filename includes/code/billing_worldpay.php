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
	# * FILE: /includes/code/billing_worldpay.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# INCLUDE
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/conf/payment_worldpay.inc.php");

	extract($_POST);
	extract($_GET);

	if (string_strpos($_SERVER["PHP_SELF"], "callback.php") === false) {

		$payment_success = "y";

		$payment_message = "<p class=\"successMessage\">\n";
		if ($process == "claim") $payment_message .= system_showText(LANG_MSG_IF_TRANSACTION_WAS_CONFIRMED)."<br />\n".system_showText(LANG_MSG_YOUR_TRANSACTION_AFTER_PAYMENT_PROCESSED)."<br />\n(".system_showText(LANG_MSG_TRANSACTIONS_MAY_NOT_OCCUR_IMMEDIATELY).")<br />\n";
		else $payment_message .= system_showText(LANG_MSG_IF_TRANSACTION_WAS_CONFIRMED)."<br />\n".system_showText(LANG_LABEL_YOUR)." <a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/transactions/index.php\">".system_showText(LANG_LABEL_TRANSACTION_HISTORY)."</a> ".system_showText(LANG_MSG_AFTER_PAYMENT_IS_PROCESSED)."<br />\n(".system_showText(LANG_MSG_TRANSACTIONS_MAY_NOT_OCCUR_IMMEDIATELY).")\n";
		$payment_message .= $try_again_message."\n</p>";

	} else {

		$transaction_worldpay = $_POST;

		if ($transaction_worldpay["transStatus"]) {

			if ($transaction_worldpay["transStatus"] != "Y") {
			
				$payment_message = "<p class=\"errorMessage\">\n";

				$payment_message .= system_showText(LANG_LABEL_STATUS).": ".string_strtoupper(system_showText(LANG_LABEL_FAILURE)).".<br />\n";
				$payment_message .= system_showText(LANG_LABEL_TRANSACTION_ERROR).": ".system_showText(LANG_LABEL_TRANSACTION_CANCELED)."<br />\n";
				if ($process == "signup") $try_again_message = "<a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/".$process."/payment.php?payment_method=worldpay\">".system_showText(LANG_MSG_CLICK_HERE_TO_TRY_AGAIN)."</a>\n";
				elseif ($process == "claim") $try_again_message = "<a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/".$process."/payment.php?payment_method=worldpay&claimlistingid=".$claimlistingid."\">".system_showText(LANG_MSG_CLICK_HERE_TO_TRY_AGAIN)."</a>\n";
				else $try_again_message = "<a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/billing/index.php\">".system_showText(LANG_MSG_CLICK_HERE_TO_TRY_AGAIN)."</a>\n";

			} else {

				$transaction_worldpay["trans_id"]            = $transaction_worldpay["transId"];
				$transaction_worldpay["cart_id"]             = $transaction_worldpay["cartId"];
				$transaction_worldpay["inst_id"]             = $transaction_worldpay["instId"];
				$transaction_worldpay["trans_status"]        = $transaction_worldpay["transStatus"];
				$transaction_worldpay["trans_time"]          = date("Y-m-d H:i:s", ceil($transaction_worldpay["transTime"]/1000));
				$transaction_worldpay["currency"]            = $transaction_worldpay["authCurrency"];
				$transaction_worldpay["avs"]                 = $transaction_worldpay["AVS"];
				$transaction_worldpay["card_type"]           = $transaction_worldpay["cardType"];
				$transaction_worldpay["server_ip"]           = $transaction_worldpay["ipAddress"];
				$transaction_worldpay["client_ip"]           = $transaction_worldpay["MC_ip"];
				$transaction_worldpay["amount"]              = $transaction_worldpay["authAmount"];
				$transaction_worldpay["local_response_time"] = date("Y-m-d H:i:s");
				$transaction_worldpay["description"]         = $transaction_worldpay["desc"];
				$domain_id									 = $transaction_worldpay["MC_domain_id"];
				$package_id									 = $transaction_worldpay["MC_package_id"];
				$accountObj                          = new Account($MC_account_id);
				$account_id                        = $MC_account_id;
				$transaction["account_id"]           = $MC_account_id;
				$transaction["username"]             = $accountObj->getString("username");
				$transaction["ip"]                   = $_SERVER["REMOTE_ADDR"];
				$transaction["transaction_id"]       = $transaction_worldpay["trans_id"];
				$transaction["transaction_status"]   = $transaction_worldpay["trans_status"];
				$transaction["transaction_datetime"] = $transaction_worldpay["trans_time"];
				$transaction["transaction_tax"]		 = $transaction_worldpay["MC_tax"];
				$transaction["transaction_subtotal"] = $transaction_worldpay["MC_subtotal"];
				$transaction["transaction_amount"]   = $transaction_worldpay["amount"];
				$transaction["transaction_currency"] = WORLDPAY_CURRENCY;
				$transaction["system_type"]          = "worldpay";
				$transaction["recurring"]            = "n";
				$transaction["notes"]                = "";

				$transaction["listing_ids"]           = $MC_listing_ids;
				$transaction["listing_amounts"]       = $MC_listing_amounts;
				$transaction["event_ids"]             = $MC_event_ids;
				$transaction["event_amounts"]         = $MC_event_amounts;
				$transaction["banner_ids"]            = $MC_banner_ids;
				$transaction["banner_amounts"]        = $MC_banner_amounts;
				$transaction["classified_ids"]        = $MC_classified_ids;
				$transaction["classified_amounts"]    = $MC_classified_amounts;
				$transaction["article_ids"]           = $MC_article_ids;
				$transaction["article_amounts"]       = $MC_article_amounts;
				$transaction["custominvoice_ids"]     = $MC_custominvoice_ids;
				$transaction["custominvoice_amounts"] = $MC_custominvoice_amounts;

				$payment_success = "y";
				
				$payment_message = "<p class=\"successMessage\">\n";

				$payment_message .= system_showText(LANG_LABEL_STATUS).": ".system_showText(LANG_LABEL_APPROVED)."<br />\n";
				$payment_message .= system_showText(LANG_LABEL_TRANSACTION_CODE).": ".$transaction["transaction_id"]."<br />\n";
				$payment_message .= system_showText(LANG_LABEL_TRANSACTION_AMOUNT).": ".$transaction["transaction_amount"]."<br />\n";
				if ($process == "claim") $payment_message .= "<br />\n".system_showText(LANG_MSG_TRANSACTIONS_MAY_NOT_OCCUR_IMMEDIATELY)."<br />\n".system_showText(LANG_MSG_AFTER_PAYMENT_IS_PROCESSED_INFO_ABOUT)."<br />\n".system_showText(LANG_MSG_MAY_BE_FOUND_IN_TRANSACTION_HISTORY)."\n";
                else $payment_message .= "<br />\n".system_showText(LANG_MSG_TRANSACTIONS_MAY_NOT_OCCUR_IMMEDIATELY)."<br />\n".system_showText(LANG_MSG_AFTER_PAYMENT_IS_PROCESSED_INFO_ABOUT)."<br />\n".system_showText(LANG_MSG_MAY_BE_FOUND_IN_YOUR)." <a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/transactions/index.php\">".system_showText(LANG_LABEL_TRANSACTION_HISTORY)."</a>\n";

				$transaction["return_fields"] = system_array2nvp($transaction_worldpay, " || ");
				$paymentLogObj = new PaymentLog($transaction);
				$paymentLogObj->Save($domain_id);
				unset($_SESSION["worldpay_subtotal"],$_SESSION["worldpay_tax"]);

				$listing_ids = explode("::",$transaction["listing_ids"]);
				$listing_amounts = explode("::",$transaction["listing_amounts"]);
				$event_ids = explode("::",$transaction["event_ids"]);
				$event_amounts = explode("::",$transaction["event_amounts"]);
				$banner_ids = explode("::",$transaction["banner_ids"]);
				$banner_amounts = explode("::",$transaction["banner_amounts"]);
				$classified_ids = explode("::",$transaction["classified_ids"]);
				$classified_amounts = explode("::",$transaction["classified_amounts"]);
				$article_ids = explode("::",$transaction["article_ids"]);
				$article_amounts = explode("::",$transaction["article_amounts"]);
				$custominvoice_ids = explode("::",$transaction["custominvoice_ids"]);
				$custominvoice_amounts = explode("::",$transaction["custominvoice_amounts"]);

				if (!empty($listing_ids[0])) {

					$listingStatus = new ItemStatus();

					$amountAux = 0;
					$levelObj = new ListingLevel(false, $domain_id);
					foreach($listing_ids as $each_listing_id){

						$listingObj = new Listing($each_listing_id, $domain_id);
						$listingObj->setString("renewal_date", $listingObj->getNextRenewalDate());
						
						$dbMain = db_getDBObject(DEFAULT_DB, true);
						$dbObjCat = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
						
						setting_get("listing_approve_paid", $listing_approve_paid);
											
						if ($listing_approve_paid){
							$listingObj->setString("status", $listingStatus->getDefaultStatus());
						}else{
							$listingObj->setString("status", "A");
						}
						
						$listingObj->Save();

						$category_amount = 0;
						$sql = "SELECT category_id FROM Listing_Category WHERE listing_id = ".$listingObj->getString("id")."";
						$result = $dbObjCat->query($sql);
						if(mysql_num_rows($result)){
							while($row = mysql_fetch_assoc($result)){
								$category_amount++;
							}

						}

						$transaction_listing_log["payment_log_id"] = $paymentLogObj->getNumber("id");
						$transaction_listing_log["listing_id"]     = $each_listing_id;
						$transaction_listing_log["listing_title"]  = $listingObj->getString("title", false);
                        $transaction_listing_log["level"]          = $listingObj->getString("level");
						$transaction_listing_log["level_label"]    = $levelObj->showLevel($listingObj->getString("level"));
						$transaction_listing_log["renewal_date"]   = $listingObj->getString("renewal_date");
						$transaction_listing_log["discount_id"]    = $listingObj->getString("discount_id");
						$transaction_listing_log["categories"]     = ($category_amount) ? $category_amount : 0;
						$transaction_listing_log["amount"]         = str_replace(",","",$listing_amounts[$amountAux]);
						$amountAux++;

						$transaction_listing_log["extra_categories"] = 0;
						if (($category_amount > 0) && (($category_amount - $levelObj->getFreeCategory($listingObj->getString("level"))) > 0)) {
							$transaction_listing_log["extra_categories"] = $category_amount - $levelObj->getFreeCategory($listingObj->getString("level"));
						} else {
							$transaction_listing_log["extra_categories"] = 0;
						}

						$transaction_listing_log["listingtemplate_title"] = "";
						if (LISTINGTEMPLATE_FEATURE == "on") {
							if ($listingObj->getString("listingtemplate_id")) {
								$listingTemplateObj = new ListingTemplate($listingObj->getString("listingtemplate_id"), $domain_id);
								$transaction_listing_log["listingtemplate_title"] = $listingTemplateObj->getString("title", false);
							}
						}

						$paymentListingLogObj = new PaymentListingLog($transaction_listing_log, $domain_id);
						$paymentListingLogObj->Save();

					}

					unset($listingObj);

				}

				if (!empty($event_ids[0])) {

					$eventStatus = new ItemStatus();

					$amountAux = 0;
					foreach($event_ids as $each_event_id){

						$eventObj = new Event($each_event_id, $domain_id);
                        $levelObj = new EventLevel(false, $domain_id);
                        
						$eventObj->setString("renewal_date", $eventObj->getNextRenewalDate());
						setting_get("event_approve_paid", $event_approve_paid);
											
						if ($event_approve_paid){
							$eventObj->setString("status", $eventStatus->getDefaultStatus());
						}else
							$eventObj->setString("status", "A");
						
						$eventObj->Save();

						$transaction_event_log["payment_log_id"] = $paymentLogObj->getNumber("id");
						$transaction_event_log["event_id"]       = $each_event_id;
						$transaction_event_log["event_title"]    = $eventObj->getString("title",false);
                        $transaction_event_log["level"]          = $eventObj->getString("level");
						$transaction_event_log["level_label"]    = $levelObj->showLevel($eventObj->getString("level"));
						$transaction_event_log["renewal_date"]   = $eventObj->getString("renewal_date");
						$transaction_event_log["discount_id"]    = $eventObj->getString("discount_id");
						$transaction_event_log["amount"]         = str_replace(",","",$event_amounts[$amountAux]);
						$amountAux++;

						$paymentEventLogObj = new PaymentEventLog($transaction_event_log, $domain_id);
						$paymentEventLogObj->Save();

					}

					unset($eventObj);

				}

				if (!empty($banner_ids[0])) {

					$bannerStatus = new ItemStatus();

					$amountAux = 0;
					foreach($banner_ids as $each_banner_id){

						$bannerObj = new Banner($each_banner_id, $domain_id);
                        $levelObj = new BannerLevel(false, $domain_id);

						if($bannerObj->getString("expiration_setting") == BANNER_EXPIRATION_IMPRESSION){

							$dbMain = db_getDBObject(DEFAULT_DB, true);
							$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);

							setting_get("banner_approve_paid", $banner_approve_paid);

							if ($banner_approve_paid){
								$sql = "UPDATE Banner set impressions = impressions + ".$bannerObj->getNumber("unpaid_impressions").", renewal_date = '0000-00-00', unpaid_impressions = 0 WHERE id = ".$bannerObj->getNumber("id");
							}else{
								$sql = "UPDATE Banner set impressions = impressions + ".$bannerObj->getNumber("unpaid_impressions").", renewal_date = '0000-00-00', unpaid_impressions = 0, status = 'A' WHERE id = ".$bannerObj->getNumber("id");
							}

							$result = $dbObj->query($sql);

							$id = $bannerObj->getNumber("id");
							$unpaid_impressions[$id] = $bannerObj->getNumber("unpaid_impressions");

						} elseif ($bannerObj->getString("expiration_setting") == BANNER_EXPIRATION_RENEWAL_DATE){

							$bannerObj->setString("renewal_date", $bannerObj->getNextRenewalDate());
						
							setting_get("banner_approve_paid", $banner_approve_paid);
											
							if ($banner_approve_paid){
								$bannerObj->setString("status", $bannerStatus->getDefaultStatus());
							}else{
								$bannerObj->setString("status", "A");	
							}

							$bannerObj->Save();

						}

						$transaction_banner_log["payment_log_id"] = $paymentLogObj->getNumber("id");
						$transaction_banner_log["banner_id"]      = $each_banner_id;
						$transaction_banner_log["banner_caption"] = $bannerObj->getString("caption",false);
                        $transaction_banner_log["level"]          = $bannerObj->getString("type");
						$transaction_banner_log["level_label"]    = $levelObj->showLevel($bannerObj->getString("type"));
						$transaction_banner_log["renewal_date"]   = $bannerObj->getString("renewal_date");
						$transaction_banner_log["discount_id"]    = $bannerObj->getString("discount_id");
						$transaction_banner_log["impressions"]    = ($unpaid_impressions[$each_banner_id]) ? $unpaid_impressions[$each_banner_id] : 0;
						$transaction_banner_log["amount"]         = str_replace(",","",$banner_amounts[$amountAux]);
						$amountAux++;

						$paymentBannerLogObj = new PaymentBannerLog($transaction_banner_log, $domain_id);
						$paymentBannerLogObj->Save();

					}

					unset($bannerObj);

				}

				if (!empty($classified_ids[0])) {

					$classifiedStatus = new ItemStatus();

					$amountAux = 0;
					foreach($classified_ids as $each_classified_id){

						$classifiedObj = new Classified($each_classified_id, $domain_id);
                        $levelObj = new ClassifiedLevel(false, $domain_id);
                        
						$classifiedObj->setString("renewal_date", $classifiedObj->getNextRenewalDate());
						
						setting_get("classified_approve_paid", $classified_approve_paid);
											
						if ($classified_approve_paid){
							$classifiedObj->setString("status", $classifiedStatus->getDefaultStatus());
						}else{
							$classifiedObj->setString("status", "A");
						}
						
						$classifiedObj->save();

						$transaction_classified_log["payment_log_id"]   = $paymentLogObj->getNumber("id");
						$transaction_classified_log["classified_id"]    = $each_classified_id;
						$transaction_classified_log["classified_title"] = $classifiedObj->getString("title",false);
                        $transaction_classified_log["level"]            = $classifiedObj->getString("level");
						$transaction_classified_log["level_label"]      = $levelObj->showLevel($classifiedObj->getString("level"));
						$transaction_classified_log["renewal_date"]     = $classifiedObj->getString("renewal_date");
						$transaction_classified_log["discount_id"]      = $classifiedObj->getString("discount_id");
						$transaction_classified_log["amount"]           = str_replace(",","",$classified_amounts[$amountAux]);
						$amountAux++;

						$paymentClassifiedLogObj = new PaymentClassifiedLog($transaction_classified_log, $domain_id);
						$paymentClassifiedLogObj->Save();

					}

					unset($classifiedObj);

				}

				if (!empty($article_ids[0])) {

					$articleStatus = new ItemStatus();

					$amountAux = 0;
					foreach($article_ids as $each_article_id){

						$articleObj = new Article($each_article_id, $domain_id);
                        $levelObj = new ArticleLevel(false, $domain_id);
                        
						$articleObj->setString("renewal_date", $articleObj->getNextRenewalDate());
						
						setting_get("article_approve_paid",$article_approve_paid);
											
						if ($article_approve_paid){
							$articleObj->setString("status", $articleStatus->getDefaultStatus());
						}else{
							$articleObj->setString("status", "A");
						}
						
						$articleObj->Save();

						$transaction_article_log["payment_log_id"] = $paymentLogObj->getNumber("id");
						$transaction_article_log["article_id"]     = $each_article_id;
						$transaction_article_log["article_title"]  = $articleObj->getString("title",false);
                        $transaction_article_log["level"]          = $articleObj->getString("level");
						$transaction_article_log["level_label"]    = $levelObj->showLevel($articleObj->getString("level"));
						$transaction_article_log["renewal_date"]   = $articleObj->getString("renewal_date");
						$transaction_article_log["discount_id"]    = $articleObj->getString("discount_id");
						$transaction_article_log["amount"]         = str_replace(",","",$article_amounts[$amountAux]);
						$amountAux++;

						$paymentArticleLogObj = new PaymentArticleLog($transaction_article_log, $domain_id);
						$paymentArticleLogObj->Save();

					}

					unset($articleObj);

				}

				if (!empty($custominvoice_ids[0])) {

					$amountAux = 0;
					foreach($custominvoice_ids as $each_custominvoice_id){

						$customInvoiceObj = new CustomInvoice($each_custominvoice_id, $domain_id);
						if ($paymentLogObj->getNumber("transaction_tax") > 0) {
						   $cInvoiceAmount = payment_calculateTax($customInvoiceObj->getNumber("amount"), $paymentLogObj->getNumber("transaction_tax"));
						} else {
						   $cInvoiceAmount = $customInvoiceObj->getNumber("amount");
						 }
						$customInvoiceObj->setNumber("tax", $paymentLogObj->getNumber("transaction_tax"));
						$customInvoiceObj->setNumber("subtotal", $customInvoiceObj->getNumber("amount"));
						$customInvoiceObj->setNumber("amount", $cInvoiceAmount);
						$customInvoiceObj->setString("paid", "y");
						$customInvoiceObj->Save();

						$transaction_custominvoice_log["payment_log_id"]    = $paymentLogObj->getNumber("id");
						$transaction_custominvoice_log["custom_invoice_id"] = $each_custominvoice_id;
						$transaction_custominvoice_log["title"]             = $customInvoiceObj->getString("title");
						$transaction_custominvoice_log["date"]              = $customInvoiceObj->getString("date");
						$transaction_custominvoice_log["items"]             = $customInvoiceObj->getTextItems();
						$transaction_custominvoice_log["items_price"]       = $customInvoiceObj->getTextPrices();
						$transaction_custominvoice_log["amount"]            = $customInvoiceObj->getNumber("subtotal");
						$amountAux++;

						$paymentCustomInvoiceLogObj = new PaymentCustomInvoiceLog($transaction_custominvoice_log, $domain_id);
						$paymentCustomInvoiceLogObj->Save();

					}

					unset($customInvoiceObj);

				}

				if ($package_id) {

					/////////////////////////////
					$packageObj = new Package($package_id);
					$array_package_offers = $packageObj->getPackagesByDomainID();

					$auxitem_name = $array_package_offers[0]["items"][0]["module"];
					$auxpackage_name = $packageObj->getString("title");
					if ($auxitem_name) {
						switch($auxitem_name) {
								 case 'listing': $item_name = ucfirst(LANG_LISTING_FEATURE_NAME);
												 $level = new ListingLevel();
												 $item_levelName = ucfirst($level->getName($array_package_offers[0]["items"][0]["level"]));

												 $msg_packagetr = system_showText(LANG_ADD_A)." ".$item_name." - ".$item_levelName." ".system_showText(LANG_ON_SITES);

												 break;

								 case 'banner': $item_name = ucfirst(LANG_BANNER_FEATURE_NAME);
												 $level = new BannerLevel();
												 $item_levelName = ucfirst($level->getName($array_package_offers[0]["items"][0]["level"]));

												 $msg_packagetr = system_showText(LANG_ADD_A)." ".$item_name." - ".$item_levelName." ".system_showText(LANG_ON_SITES);

												 break;

								 case 'event': $item_name = ucfirst(LANG_EVENT_FEATURE_NAME);
												 $level = new EventLevel();
												 $item_levelName = ucfirst($level->getName($array_package_offers[0]["items"][0]["level"]));

												 $msg_packagetr = system_showText(LANG_ADD_AN)." ".$item_name." - ".$item_levelName." ".system_showText(LANG_ON_SITES);

												break;

								case 'classified': $item_name = ucfirst(LANG_CLASSIFIED_FEATURE_NAME);
													 $level = new ClassifiedLevel();
													 $item_levelName = ucfirst($level->getName($array_package_offers[0]["items"][0]["level"]));

													 $msg_packagetr = system_showText(LANG_ADD_A)." ".$item_name." - ".$item_levelName." ".system_showText(LANG_ON_SITES);

													break;

								case 'article': $item_name = ucfirst(LANG_ARTICLE_FEATURE_NAME);
												 $level = new ArticleLevel();
												 $item_levelName = ucfirst($level->getName($array_package_offers[0]["items"][0]["level"]));

												 $msg_packagetr = system_showText(LANG_ADD_AN)." ".$item_name." - ".$item_levelName." ".system_showText(LANG_ON_SITES);

												 break;

								case 'custom_package': $item_name = ucfirst(LANG_GIFT);
														break;

							}

							$auxdomains_names = "";
							$aux_package_item_price = "";
							foreach ($array_package_offers as $package_offer) {
								foreach ($package_offer['items'] as $package_offer_item) {
									if ($package_offer_item['domain_id']>0) {
										$aux_domain_obj = new Domain($package_offer_item['domain_id']);
										$auxdomains_names .= "&nbsp;&nbsp;&nbsp;-".$aux_domain_obj->getString('name')."<br />";
										$auxlevel_names .= $item_levelName."<br />";
									}

									if ($package_offer_item['price']==0) {
										$aux_package_item_price .= CURRENCY_SYMBOL." ".system_showText(LANG_FREE)."<br />";
									} else {
										$aux_package_item_price .= CURRENCY_SYMBOL." ".$package_offer_item['price']."<br />";
										$aux_package_total += $package_offer_item['price'];
									}
								}

								$auxdomains_names = string_substr($auxdomains_names, 0, -4);
								$auxlevel_names = string_substr($auxlevel_names, 0, -4);
								$aux_package_item_price = string_substr($aux_package_item_price, 0, -4);
							 }

								$sql = "SELECT module_id, module, domain_id FROM PackageModules WHERE parent_domain_id = ".$domain_id." AND package_id = ".$package_id." AND account_id = ".$account_id;

								$r = $dbMain->query($sql);
								$i=0;
								while($row = mysql_fetch_assoc($r)){
									$itemsInfo[$i]["module_id"] = $row["module_id"];
									$itemsInfo[$i]["module"] = $row["module"];
									$itemsInfo[$i]["domain_id"] = $row["domain_id"];
									$i++;
								}

								foreach($itemsInfo as $item){
									if ($item["module"] != "custom_package"){
										$className = ucfirst($item["module"]);
										$item_id = $item["module_id"];
										$domain_idItem = $item["domain_id"];

										$itemObj = new $className($item_id);

										$itemStatus = new ItemStatus();

										setting_get($item["module"]."_approve_paid", $item_approve_paid);

										if ($item_approve_paid){
											$stritemStatus = $itemStatus->getDefaultStatus();
										}else{
											$stritemStatus = "A";
										}


										$sql = "UPDATE $className SET status = ".db_formatString($stritemStatus).", renewal_date = ".db_formatString($itemObj->getNextRenewalDate())." WHERE id = ".$item_id;
										$dbItem = db_getDBObjectByDomainID($domain_idItem, $dbMain);
										$dbItem->query($sql);
									}

								}
					}

					//////////////////////////////

					$transaction_package_log["payment_log_id"]    = $paymentLogObj->getNumber("id");
					$transaction_package_log["package_id"]		  = $package_id;
					$transaction_package_log["package_title"]     = $packageObj->getString("title");
					$transaction_package_log["items"]             = $item_name." ".$item_levelName."\n".str_replace("-","",$auxdomains_names);
					$transaction_package_log["items_price"]       = str_replace(CURRENCY_SYMBOL." ", "",str_replace("<br />","\n",$aux_package_item_price));
					$transaction_package_log["amount"]            = payment_calculateTax(str_replace(",","",$aux_package_total), $payment_tax_value, false);

					$paymentPacakgeObj = new PaymentPackageLog($transaction_package_log, $domain_id);
					$paymentPacakgeObj->Save();

					unset($packageObj);

				}

				$paymentLogObj->sendNotification($domain_id, $package_id);

			}

			$payment_message .= $try_again_message."</p>";

		} else {
			$payment_message .= "<p class=\"errorMessage\">\n";
			$payment_message .= system_showText(LANG_LABEL_STATUS).":".system_showText(LANG_LABEL_ERROR)."<br />\n";
			$payment_message .= system_showText(LANG_MSG_PAYMENT_GATEWAY_COULD_NOT_RESPOND)."<br />\n";
			$payment_message .= "<a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/billing/index.php\">".system_showText(LANG_MSG_CLICK_HERE_TO_TRY_AGAIN)."</a></p>\n";
		}

		if ($payment_success != "y") {
			setting_get("sitemgr_email", $sitemgr_email);
			//$eDirMailerObj = new EDirMailer(EDIR_ADMIN_EMAIL, "WorldPay Error", $payment_message."\n".$_SERVER["SERVER_NAME"], $sitemgr_email);
			//$eDirMailerObj->send();
			exit;
		}

	}

?>