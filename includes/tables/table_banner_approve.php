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
	# * FILE: /includes/tables/table_banner_approve.php
	# ----------------------------------------------------------------------------------------------------

?>
    <?
    
   include(INCLUDES_DIR."/tables/table_paging.php"); 
   ?>

	 <a href="<?=$paging_url."&reset=true"?>"><?=system_showText(LANG_SITEMGR_RESET_FEATURED)?></a>
     <table class="standard-tableTOPBLUE arrow-manage">

            <tr>
                <th style="width:auto;">
                    <span><?=system_showText(LANG_LABEL_CAPTION)?></span>
                </th>
                
                <? if (string_strpos($url_redirect, "/".SITEMGR_ALIAS."")) { ?>
                    <th style="width: 90px;">
                        <span><?=system_showText(LANG_LABEL_ACCOUNT);?></span>
                    </th>
                <? } ?>

                <th style="width: 105px;">
                    <span style="width: 93px;"><?=system_showText(LANG_LABEL_RENEWAL);?></span>
                </th>

                <th style="width: 90px;">
                    <span style="width: 75px;"><?=system_showText(LANG_LABEL_IMPRESSIONS)?></span>
                </th>
                
                <th style="width: 100px;">
                    <span><?=system_showText(LANG_SITEMGR_FEATURE_STATUS);?></span>
                </th>
                
				<th style="width: 100px;">
                    <span><?=system_showText(LANG_SITEMGR_FEATURE_ACTION);?></span>
                </th>

            </tr>

            <?
            $hascharge = false;
            $hastocheckout = false;
            $cont = 0;
            if ($banners) foreach ($banners as $each_banner) {
                $cont++;
                $bannerObj = new Banner($each_banner);
                if ($bannerObj->needToCheckOut() && ($bannerObj->getString("unpaid_impressions") > 0 || $bannerObj->getString("expiration_setting") == BANNER_EXPIRATION_RENEWAL_DATE)) {
                    if ($bannerObj->getPrice() > 0 && ($bannerObj->getString("unpaid_impressions") > 0 || $bannerObj->getString("expiration_setting") == BANNER_EXPIRATION_RENEWAL_DATE)) $hascharge = true;
                    $hastocheckout = true;
                }

                $id = $bannerObj->getNumber("id");

                $dbMain = db_getDBObject(DEFAULT_DB, true);
                $db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);

                // ---------------- //

                $sql = "SELECT payment_log_id FROM Payment_Banner_Log WHERE banner_id = $id ORDER BY renewal_date DESC LIMIT 1";
                $r   = $db->query($sql);
                $aux_transaction_data = mysql_fetch_assoc($r);

                if($aux_transaction_data) {
                    $sql = "SELECT id,transaction_datetime FROM Payment_Log WHERE id = {$aux_transaction_data["payment_log_id"]}";
                    $r = $db->query($sql);
                    $transaction_data = mysql_fetch_assoc($r);
                } else {
                    unset($transaction_data);
                }

                // ---------------- //

                $sql = "SELECT IB.invoice_id, IB.banner_id, I.id, I.status, I.payment_date FROM Invoice I, Invoice_Banner IB WHERE IB.banner_id = $id AND I.status = 'R' AND I.id = IB.invoice_id ORDER BY I.payment_date DESC LIMIT 1";
                $r   = $db->query($sql);
                $invoice_data = mysql_fetch_assoc($r);

                // ---------------- //

                list($t_month,$t_day,$t_year)     = explode("/",format_date($transaction_data["transaction_datetime"],DEFAULT_DATE_FORMAT,"datetime"));
                list($i_month,$i_day,$i_year)     = explode("/",format_date($invoice_data["payment_date"],DEFAULT_DATE_FORMAT,"datetime"));
                list($t_hour,$t_minute,$t_second) = explode(":",format_date($transaction_data["transaction_datetime"],"H:i:s","datetime"));
                list($i_hour,$i_minute,$i_second) = explode(":",format_date($invoice_data["payment_date"],"H:i:s","datetime"));

                $t_ts_date = mktime((int)$t_hour,(int)$t_minute,(int)$t_second,(int)$t_month,(int)$t_day,(int)$t_year);
                $i_ts_date = mktime((int)$i_hour,(int)$i_minute,(int)$i_second,(int)$i_month,(int)$i_day,(int)$i_year);

                if (PAYMENT_FEATURE == "on") {
                    if (((MANUALPAYMENT_FEATURE == "on") || (CREDITCARDPAYMENT_FEATURE == "on")) && (INVOICEPAYMENT_FEATURE == "on")) {
                        if($t_ts_date < $i_ts_date){
                            if($invoice_data["id"]) $history_lnk = DEFAULT_URL."/".SITEMGR_ALIAS."/invoices/view.php?id=".$invoice_data["id"];
                            else unset($history_lnk);
                        } else {
                            if($transaction_data["id"]) $history_lnk = DEFAULT_URL."/".SITEMGR_ALIAS."/transactions/view.php?id=".$transaction_data["id"];
                            else unset($history_lnk);
                        }
                    } elseif ((MANUALPAYMENT_FEATURE == "on") || (CREDITCARDPAYMENT_FEATURE == "on")) {
                        if($transaction_data["id"]) $history_lnk = DEFAULT_URL."/".SITEMGR_ALIAS."/transactions/view.php?id=".$transaction_data["id"];
                        else unset($history_lnk);
                    } elseif (INVOICEPAYMENT_FEATURE == "on") {
                        if($invoice_data["id"]) $history_lnk = DEFAULT_URL."/".SITEMGR_ALIAS."/invoices/view.php?id=".$invoice_data["id"];
                        else unset($history_lnk);
                    } else {
                        unset($history_lnk);
                    }
                } else {
                    unset($history_lnk);
                }

                ?>
                <tr>
                    <td>
                    	<a href="javascript:void(0)" onmouseover="showBanner(<?=$bannerObj->GetString("id")?>)" onmouseout="hideBanner(<?=$bannerObj->GetString("account_id")?>)">
	                        <? if (string_strpos($url_redirect, "/".SITEMGR_ALIAS."")) { ?>
	                        	<?=$bannerObj->getString("caption", true, 30);?>
	                        <? } else { ?>
	                            <?=$bannerObj->getString("caption", true, 40);?>
	                        <? } ?>
                        </a>
                        
                        <div style="display:none">
                        	<a id="bannerDiv<?=$bannerObj->GetString("id")?>"  href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER?>/preview.php?id=<?=$bannerObj->getNumber("id")?>" class="iframe fancy_window_preview_small">preview</a>
                        </div>
                    </td>
                    
                    <? if (string_strpos($url_redirect, "/".SITEMGR_ALIAS."")) { ?>
                    <td>
                        <? if ($bannerObj->GetString("account_id")) {
                            $account = db_getFromDB("account", "id", db_formatNumber($bannerObj->GetString("account_id")));
                            ?>
                            <a title="<?=system_showAccountUserName($account->getString("username"))?>" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/account/view.php?id=<?=$bannerObj->GetString("account_id")?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table">
                                <?=system_showTruncatedText(system_showAccountUserName($account->getString("username")), 15);?>
                            </a>
                        <? } else { ?>
                            <span title="<?=system_showText(LANG_SITEMGR_ACCOUNTSEARCH_NOOWNER)?>" style="cursor:default">
                                <em><?=system_showTruncatedText(LANG_SITEMGR_ACCOUNTSEARCH_NOOWNER, 15);?></em>
                            </span>
                        <? } ?>
                    </td>
                    <? } ?>
                    <td>
                        <?
                        if ($bannerObj->getString("expiration_setting") != BANNER_EXPIRATION_RENEWAL_DATE) {
                            $renewal_field = system_showText(LANG_LABEL_UNLIMITED);
                        } else {
                            if ($bannerObj->hasRenewalDate()) {
                                if ($bannerObj->getDate("renewal_date") == "00/00/0000") {
                                    $renewal_field = system_showText(LANG_LABEL_NEW);
                                } else {
                                    $renewal_field = $bannerObj->getDate("renewal_date");
                                }
                            } else {
                                $renewal_field = "---";
                            }
                        }
                        ?>
                        <span title="<?=$renewal_field?>" style="cursor:default"><?=$renewal_field;?></span>
                    </td>
                    <td>
                        <?
                        if ($bannerObj->getString("expiration_setting") != BANNER_EXPIRATION_IMPRESSION) {
                            $impressions_field = system_showText(LANG_LABEL_UNLIMITED);
                        } else {
                            if ($bannerObj->hasImpressions()) {
                                $impressions_field = $bannerObj->getString("impressions");
                            } else {
                                $impressions_field = "---";
                            }
                        }
                        ?>
                        <span title="<?=$impressions_field?>" style="cursor:default"><?=$impressions_field;?></span>
                    </td>
                    
                    <td id="banner_rowId_<?=$bannerObj->GetNumber("id")?>">
                        <?
                        	$status = new ItemStatus();
                            echo $status->getStatusWithStyle($bannerObj->GetString("approve_feature"));
                         ?>
                    </td>
                    
                    <td>
                       <a href="<?=$paging_url."&approve=".$bannerObj->GetNumber("id")?>"><?=system_showText(LANG_SITEMGR_APPROVE_FEATURED)?></a> | <a href="javascript:void(0)" onclick="denyFeature(<?=$bannerObj->GetNumber("id")?>)"><?=system_showText(LANG_SITEMGR_DENY_FEATURED)?></a>
                    </td>
                </tr>
                <?
            }
            ?>

        </table>

	
