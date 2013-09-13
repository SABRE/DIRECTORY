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
	# * FILE: /functions/payment_funct.php
	# ----------------------------------------------------------------------------------------------------

	function payment_getRenewalPeriod($item) {
		return constant(string_strtoupper($item)."_RENEWAL_PERIOD");
	}

	function payment_getRenewalCycle($item) {
		return string_substr(constant(string_strtoupper($item)."_RENEWAL_PERIOD"), 0, string_strlen(constant(string_strtoupper($item)."_RENEWAL_PERIOD"))-1);
	}

	function payment_getRenewalUnit($item) {
		return string_substr(constant(string_strtoupper($item)."_RENEWAL_PERIOD"), string_strlen(constant(string_strtoupper($item)."_RENEWAL_PERIOD"))-1);
	}

	function payment_getRenewalUnitName($item) {
		$unit = payment_getRenewalUnit($item);
		if ($unit == "Y") $unitname = system_showText(LANG_YEAR);
		elseif ($unit == "M") $unitname = system_showText(LANG_MONTH);
		elseif ($unit == "D") $unitname = system_showText(LANG_DAY);
		return $unitname;
	}

	function payment_getRenewalUnitNamePlural($item) {
		$unit = payment_getRenewalUnit($item);
		if ($unit == "Y") $unitname = system_showText(LANG_YEAR_PLURAL);
		elseif ($unit == "M") $unitname = system_showText(LANG_MONTH_PLURAL);
		elseif ($unit == "D") $unitname = system_showText(LANG_DAY_PLURAL);
		return $unitname;
	}
	
	function payment_writeSettingPaymentFile($array_PaymentSetting) {
			
		$filePath = EDIRECTORY_ROOT.'/custom/domain_'.SELECTED_DOMAIN_ID.'/payment/payment.inc.php';
		
		if (!$file = fopen($filePath, 'w+')) {
			return false;
		}
		
		$buffer = "<?php".PHP_EOL;
		
		$buffer .= "\$payment_simplepayStatus = \"".$array_PaymentSetting['payment_simplepayStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_paypalStatus = \"".$array_PaymentSetting['payment_paypalStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_paypalapiStatus = \"".$array_PaymentSetting['payment_paypalapiStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_payflowStatus = \"".$array_PaymentSetting['payment_payflowStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_twocheckoutStatus = \"".$array_PaymentSetting['payment_twocheckoutStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_psigateStatus = \"".$array_PaymentSetting['payment_psigateStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_worldpayStatus = \"".$array_PaymentSetting['payment_worldpayStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_itransactStatus = \"".$array_PaymentSetting['payment_itransactStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_linkpointStatus = \"".$array_PaymentSetting['payment_linkpointStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_authorizeStatus = \"".$array_PaymentSetting['payment_authorizeStatus']."\";".PHP_EOL;
		$buffer .= "\$payment_pagseguroStatus = \"".$array_PaymentSetting['payment_pagseguroStatus']."\";".PHP_EOL.PHP_EOL;
		$buffer .= "\$payment_simplepayRecurring = \"".$array_PaymentSetting['payment_simplepayRecurring']."\";".PHP_EOL;
		$buffer .= "\$payment_paypalRecurring = \"".$array_PaymentSetting['payment_paypalRecurring']."\";".PHP_EOL;
		$buffer .= "\$payment_linkpointRecurring = \"".$array_PaymentSetting['payment_linkpointRecurring']."\";".PHP_EOL;
		$buffer .= "\$payment_authorizeRecurring = \"".$array_PaymentSetting['payment_authorizeRecurring']."\";".PHP_EOL.PHP_EOL;
		$buffer .= "\$period_renewalListing = \"".$array_PaymentSetting['renewal_periodListing']."\";".PHP_EOL;
		$buffer .= "\$period_renewalEvent = \"".$array_PaymentSetting['renewal_periodEvent']."\";".PHP_EOL;
		$buffer .= "\$period_renewalBanner = \"".$array_PaymentSetting['renewal_periodBanner']."\";".PHP_EOL;
		$buffer .= "\$period_renewalClassified = \"".$array_PaymentSetting['renewal_periodClassified']."\";".PHP_EOL;
		$buffer .= "\$period_renewalArticle = \"".$array_PaymentSetting['renewal_periodArticle']."\";".PHP_EOL.PHP_EOL;
		$buffer .= "# ****************************************************************************************************".PHP_EOL;
		$buffer .= "# CUSTOMIZATIONS".PHP_EOL;
		$buffer .= "# NOTE: The \$payment_currency in this file is only for the domain ".SELECTED_DOMAIN_ID."".PHP_EOL;
		$buffer .= "# Any changes will require an update in the table \"Setting_Payment\"".PHP_EOL;
		$buffer .= "# to set the property \"PAYMENT_CURRENCY\" with the value bellow on the domain ".SELECTED_DOMAIN_ID." database.".PHP_EOL;
		$buffer .= "# ****************************************************************************************************".PHP_EOL;
		$buffer .= "\$payment_currency = \"".$array_PaymentSetting['payment_currency']."\";".PHP_EOL.PHP_EOL;
		$buffer .= "\$currency_symbol = \"".$array_PaymentSetting['currency_symbol']."\";".PHP_EOL;
		$buffer .= "\$invoice_payment = \"".$array_PaymentSetting['invoice_payment']."\";".PHP_EOL;
		$buffer .= "\$manual_payment = \"".$array_PaymentSetting['manual_payment']."\";".PHP_EOL;
		
		$return_payment = fwrite($file, $buffer, strlen($buffer));
		
		fclose($file);
		
		return $return_payment;
	
	}
	
	function payment_verifyItensRenewal($itens) {
		$aux = $itens[0];
		$aux2 = true;
		$i = 1;
		while ($i < count($itens)) {
			if ($itens[$i] != $aux) {
				$aux2 = false;
			};
			$i++;
		}
		return $aux2;
	}

	function payment_calculateTax ($price, $tax, $formatValue = true, $amount = true) {
		if ($amount) {
			$value = ($price * (1 + $tax / 100));
			if ($formatValue) return format_money($value);
			else return $value;
		} else {
			$value = (($price * (1 + $tax / 100)) - $price);
			if ($formatValue) return format_money($value);
			else return $value;
		}
	}

	function payment_taxToPercentage ($tax_value, $total_value) {
		$value = (($tax_value * 100) / $total_value);
		return $value;
	}
    
    function payment_receiveInvoice($invoiceObj){
        
        domain_updateDashboard("revenue", "", $invoiceObj->getString("amount"), SELECTED_DOMAIN_ID);

        $invoiceObj->setString("payment_date", date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s"));
        $invoiceObj->Save(true);

        $dbMain = db_getDBObject(DEFAULT_DB, true);
        $db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
        $sql = "SELECT * FROM Invoice_Listing WHERE invoice_id = ".$invoiceObj->getString("id")."";
        $r = $db->query($sql);

        while($row = mysql_fetch_assoc($r)) $listing_ids[] = $row["listing_id"];

        if ($listing_ids) {

            $listingStatus = new ItemStatus();

            foreach ($listing_ids as $each_listing_id) $listings[] = new Listing($each_listing_id);

            if ($listings) foreach ($listings as $listing) {

                $sql = "UPDATE Invoice_Listing SET renewal_date = '".$listing->getNextRenewalDate()."' WHERE invoice_id = ".$invoiceObj->getString("id")." AND listing_id = ".$listing->getString("id")."";
                $r = $db->query($sql);

                $listing->setString("renewal_date", $listing->getNextRenewalDate());

                setting_get("listing_approve_paid", $listing_approve_paid);

                if ($listing_approve_paid){
                    $listing->setString("status", $listingStatus->getDefaultStatus());
                }else{
                    $listing->setString("status", "A");
                }

                $listing->Save();

            }

        }

        $sql = "SELECT * FROM Invoice_Event WHERE invoice_id = ".$invoiceObj->getString("id")."";
        $r = $db->query($sql);

        while($row = mysql_fetch_assoc($r)) $event_ids[] = $row["event_id"];

        if ($event_ids) {

            $eventStatus = new ItemStatus();

            foreach ($event_ids as $each_event_id) $events[] = new Event($each_event_id);

            if ($events) foreach ($events as $event) {

                $sql = "UPDATE Invoice_Event SET renewal_date = '".$event->getNextRenewalDate()."' WHERE invoice_id = ".$invoiceObj->getString("id")." AND event_id = ".$event->getString("id")."";
                $r = $db->query($sql);

                $event->setString("renewal_date", $event->getNextRenewalDate());

                setting_get("event_approve_paid",$event_approve_paid);

                if ($event_approve_paid){
                    $event->setString("status", $eventStatus->getDefaultStatus());
                }else{
                    $event->setString("status", "A");
                }

                $event->Save();

            }

        }

        $sql = "SELECT * FROM Invoice_Banner WHERE invoice_id = ".$invoiceObj->getString("id")."";
        $r = $db->query($sql);

        while ($row = mysql_fetch_assoc($r)) $banner_ids[] = $row["banner_id"];

        if ($banner_ids) {

            $bannerStatus = new ItemStatus();

            foreach ($banner_ids as $each_banner_id) $banners[] = new Banner($each_banner_id);

            if ($banners) foreach ($banners as $banner) {

                setting_get("banner_approve_paid", $banner_approve_paid);

                if($banner->getString("expiration_setting") == BANNER_EXPIRATION_IMPRESSION){

                    if ($banner_approve_paid){
                        $sql = "UPDATE Banner set impressions = impressions + ".$banner->getNumber("unpaid_impressions").", renewal_date = '0000-00-00', unpaid_impressions = 0 WHERE id = ".$banner->getNumber("id");
                    } else {
                        $sql = "UPDATE Banner set impressions = impressions + ".$banner->getNumber("unpaid_impressions").", renewal_date = '0000-00-00', unpaid_impressions = 0, status = 'A' WHERE id = ".$banner->getNumber("id");	
                    }
                    $result = $db->query($sql);

                } elseif ($banner->getString("expiration_setting") == BANNER_EXPIRATION_RENEWAL_DATE){

                    $sql = "UPDATE Invoice_Banner SET renewal_date = '".$banner->getNextRenewalDate()."' WHERE invoice_id = ".$invoiceObj->getString("id")." AND banner_id = ".$banner->getString("id")."";
                    $r = $db->query($sql);

                    $banner->setString("renewal_date", $banner->getNextRenewalDate());

                    if ($banner_approve_paid){
                        $banner->setString("status", $bannerStatus->getDefaultStatus());
                    }else{
                        $banner->setString("status", "A");
                    }

                    $banner->Save();

                }

            }

        }

        $sql = "SELECT * FROM Invoice_Classified WHERE invoice_id = ".$invoiceObj->getString("id")."";
        $r = $db->query($sql);

        while($row = mysql_fetch_assoc($r)) $classified_ids[] = $row["classified_id"];

        if ($classified_ids) {

            $classifiedStatus = new ItemStatus();

            foreach ($classified_ids as $each_classified_id) $classifieds[] = new Classified($each_classified_id);

            if ($classifieds) foreach ($classifieds as $classified) {

                $sql = "UPDATE Invoice_Classified SET renewal_date = '".$classified->getNextRenewalDate()."' WHERE invoice_id = ".$invoiceObj->getString("id")." AND classified_id = ".$classified->getString("id")."";
                $r = $db->query($sql);

                $classified->setString("renewal_date", $classified->getNextRenewalDate());
                setting_get("classified_approve_paid", $classified_approve_paid);

                if ($classified_approve_paid){
                    $classified->setString("status", $classifiedStatus->getDefaultStatus());
                }else{
                    $classified->setString("status", "A");
                }
                $classified->Save();

            }

        }

        $sql = "SELECT * FROM Invoice_Article WHERE invoice_id = ".$invoiceObj->getString("id")."";
        $r = $db->query($sql);

        while($row = mysql_fetch_assoc($r)) $article_ids[] = $row["article_id"];

        if ($article_ids) {

            $articleStatus = new ItemStatus();

            foreach ($article_ids as $each_article_id) $articles[] = new Article($each_article_id);

            if ($articles) foreach ($articles as $article) {

                $sql = "UPDATE Invoice_Article SET renewal_date = '".$article->getNextRenewalDate()."' WHERE invoice_id = ".$invoiceObj->getString("id")." AND article_id = ".$article->getString("id")."";
                $r = $db->query($sql);

                $article->setString("renewal_date", $article->getNextRenewalDate());

                setting_get("article_approve_paid",$article_approve_paid);

                if ($article_approve_paid){
                    $article->setString("status", $articleStatus->getDefaultStatus());
                }else{
                    $article->setString("status", "A");
                }
                $article->Save();

            }

        }

        $sql = "SELECT * FROM Invoice_CustomInvoice WHERE invoice_id = ".$invoiceObj->getString("id")."";
        $r = $db->query($sql);

        while($row = mysql_fetch_assoc($r)) {
            $custominvoice_ids[] = $row["custom_invoice_id"];
            $custominvoice_tax[] = $row["tax"];
        }

        if ($custominvoice_ids) {
            $k = 0;
            foreach ($custominvoice_ids as $each_custominvoice_id) $customInvoices[] = new CustomInvoice($each_custominvoice_id);

            if ($customInvoices) foreach ($customInvoices as $customInvoice) {

                $customInvoice->setString("paid", "y");

                $taxT = $custominvoice_tax[$k];
                $tax = payment_calculateTax($customInvoice->getNumber("subtotal"),$taxT,true,false);
                $k++;

                $customInvoice->setNumber("tax", $taxT);
                $customInvoice->setNumber("amount", $customInvoice->getNumber("subtotal") + $tax);
                $customInvoice->Save();
            }
        }

        $sql = "SELECT package_id FROM Invoice_Package WHERE invoice_id = ".$invoiceObj->getString("id")."";
        $r = $db->query($sql);

        while($row = mysql_fetch_assoc($r)) $package_id = $row["package_id"];

        if ($package_id) {

            $sql = "SELECT module_id, module, domain_id FROM PackageModules WHERE parent_domain_id = ".SELECTED_DOMAIN_ID." AND package_id = ".$package_id." AND account_id = ".$invoiceObj->getString("account_id");
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
    }
	
?>