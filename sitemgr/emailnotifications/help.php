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
	# * FILE: /sitemgr/emailnotifications/help.php
	# ----------------------------------------------------------------------------------------------------
    header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);
	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	setting_get("payment_tax_status", $payment_tax_status);
	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	permission_hasSMPerm();
    
	# ----------------------------------------------------------------------------------------------------
	# AVAILABLE VARS
	# ----------------------------------------------------------------------------------------------------
	$defaultVAR = array	(
		"ACCOUNT_NAME"              =>  system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_ACCOUNT_NAME_HELP),
		"ACCOUNT_USERNAME"          =>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_ACCOUNT_USERNAME_HELP),
		"ACCOUNT_PASSWORD"          =>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_ACCOUNT_PASSWORD_HELP),
		"KEY_ACCOUNT"               =>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_KEY_ACCOUNT_HELP),
		"DEFAULT_URL"               =>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_DEFAULTURL_HELP)." (\"".DEFAULT_URL."\").",
		"SITEMGR_EMAIL"             =>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_SITEMGR_EMAIL_HELP),
		"EDIRECTORY_TITLE"          =>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_EDIRECTORY_TITLE_HELP)." (".EDIRECTORY_TITLE.").",
		"LISTING_TITLE"             =>	system_showText(LANG_SITEMGR_TITLE)." (\"".system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_LISTING_TITLE)."\").",
		"EVENT_TITLE"               =>	system_showText(LANG_SITEMGR_TITLE)." (\"".system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_EVENT_TITLE)."\").",
		"BANNER_TITLE"              =>	system_showText(LANG_SITEMGR_TITLE)." (\"".system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_BANNER_TITLE)."\").",
		"CLASSIFIED_TITLE"          =>	system_showText(LANG_SITEMGR_TITLE)." (\"".system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_CLASSIFIED_TITLE)."\").",
		"ARTICLE_TITLE"             =>	system_showText(LANG_SITEMGR_TITLE)." (\"".system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_ARTICLE_TITLE)."\").",
		"LISTING_RENEWAL_DATE"      =>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_LISTING_RENEWAL_DATE_HELP),
		"DAYS_INTERVAL"         	=>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_DAYS_INTERVAL),
		"CUSTOM_INVOICE_AMOUNT"     =>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_CUSTOM_INVOICE_AMOUNT),
		"ITEM_TITLE"                =>	system_showText(LANG_SITEMGR_TITLE)." (\"".system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_ITEM_TITLE)."\").",
		"ITEM_URL"                  =>	DEFAULT_URL."/item/title.html",
		"CUSTOM_INVOICE_TAX"        =>	system_showText(LANG_SITEMGR_SETTINGS_TAX_LABEL),
		"ARTICLE_DEFAULT_URL"       =>	ARTICLE_DEFAULT_URL,
		"CLASSIFIED_DEFAULT_URL"	=>	CLASSIFIED_DEFAULT_URL,
		"EVENT_DEFAULT_URL"         =>	EVENT_DEFAULT_URL,
		"LISTING_DEFAULT_URL"       =>	LISTING_DEFAULT_URL,
        "ACCOUNT_LOGIN_INFORMATION"	=>	system_showText(LANG_SITEMGR_EMAILNOTIFICATION_VAR_ACCOUNT_LOGIN_INFORMATION_HELP),
		"TABLE_STATS"               =>  LANG_SITEMGR_EMAILNOTIFICATION_VAR_TABLE_STATS
	);

	# ----------------------------------------------------------------------------------------------------
	# TABLE CONTENT
	# ----------------------------------------------------------------------------------------------------

	if($_REQUEST['id']){
        
        $id = $_REQUEST['id'];
        
        $dbMain = db_getDBObject(DEFAULT_DB, true);
        $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
        $sql = "SELECT use_variables FROM Email_Notification WHERE id = $id";
        $row = mysql_fetch_assoc($dbObj->query($sql));
        $variables = explode(",", $row["use_variables"]);
		
	}

?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	
    <head>
    
    	<title><?=system_showText(LANG_SITEMGR_HOME_WELCOME)?></title>

        <meta http-equiv="Content-Type" content="text/html; charset=<?=EDIR_CHARSET;?>" />
        
		<style type="text/css">
			body
			{
				position: relative;
			}
			div
			{
				width: auto;
				margin: 0px;
				padding: 0px;
			}
			.default_title
			{
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 10pt;
				font-weight: bold;
				color: #003365;
				clear: both;
				width: 500px;
				padding: 10px;
			}
			.default_text_settings 
			{
				font-family: Verdana, Arial, Sans-Serif;
				font-size: 8pt;
				color: #3B4B5B;
				text-align: justify;
				margin-top: 10px;
				background: #FBFBFB;
				border: 1px solid #E9E9E9;
			}
			.default_text_settings th
			{
				text-align: right;
				vertical-align: top;
			}
			.default_text
			{
				font-family: Verdana, Arial, Sans-Serif;
				font-size: 8pt;
				color: #3B4B5B;
				text-align: justify;
				float: left;
				width: 450px;
			}
			.default_button 
			{
				float: right;
			}
		</style>

	</head>

	<body>

		<div>
			<div class="default_text">
				<?=system_showText(LANG_SITEMGR_EMAILNOTIFICATION_HELP_USEVARIABLES)?>
			</div>
			<div class="default_title">
				<?=system_showText(LANG_SITEMGR_EMAILNOTIFICATION_HELP_VARIABLESDESCRIPTION)?>
			</div>
		</div>
		<div>
			<table border="0" cellpadding="2" cellspacing="2" class="default_text_settings">
			<? if ($variables) { ?>
				<? foreach ($variables as $var) { ?>
					<tr>
						<th><?=$var?></th>
						<td><?=$defaultVAR[$var]?></td>
					</tr>
				<? } ?>
			<? } ?>
			</table>
		</div>
	</body>

	</html>
