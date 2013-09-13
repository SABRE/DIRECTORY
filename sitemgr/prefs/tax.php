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
    # * FILE: /sitemgr/prefs/tax.php
    # ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
    # LOAD CONFIG
    # ----------------------------------------------------------------------------------------------------
    include("../../conf/loadconfig.inc.php");

    # ----------------------------------------------------------------------------------------------------
    # SESSION
    # ----------------------------------------------------------------------------------------------------
    sess_validateSMSession();
    permission_hasSMPerm();
	
	//increases frequently actions
	if (!isset($message)) system_setFreqActions('prefs_tax','prefstax');

    # ----------------------------------------------------------------------------------------------------
    # SUBMIT
    # ----------------------------------------------------------------------------------------------------
    extract($_POST);
    extract($_GET);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($payment_tax_status == "on") {
			if (!$payment_tax_label) {
				$error = true;
				$message = "&#149;&nbsp;".system_showText(LANG_SITEMGR_MSG_MAINLANGUAGE_REQUIRED)."<br />";
			}

			$payment_tax_value = str_replace(",",".",$payment_tax_value);

			$len = string_strlen($payment_tax_value);
			if ($payment_tax_value[$len-1]==".")
				$payment_tax_value .= "0";

			if (!$payment_tax_value && $payment_tax_value != 0) {
				$error = true;
				$message .= "&#149;&nbsp;".system_showText(LANG_SITEMGR_MSG_VALUE_REQUIRED)."<br />";
			} else {
				if (!is_numeric($payment_tax_value)) {
					$error = true;
					$message .= "&#149;&nbsp;".system_showText(LANG_SITEMGR_MSG_VALUE_MUST_BE_NUMERIC)."<br />";
				} else if ($payment_tax_value <= 0) {
					$error = true;
					$message .= "&#149;&nbsp;".system_showText(LANG_SITEMGR_MSG_MIN_VALUE)."<br />";
				}
			}
		}

		if ($error) {
			$messageStyle = "errorMessage";
		} else  {
			if (setting_get("payment_tax_status", $aux)) {
				setting_set("payment_tax_status", $payment_tax_status);
			} else {
				setting_new("payment_tax_status", $payment_tax_status);
			}

			if (setting_get("payment_tax_value", $aux)) {
				setting_set("payment_tax_value", $payment_tax_value);
			} else {
				setting_new("payment_tax_value", $payment_tax_value);
			}


            if (!$payment_tax_label) {
                if (customtext_get("payment_tax_label", $aux)) {
                    customtext_set("payment_tax_label", $payment_tax_label);
                } else {
                    customtext_new("payment_tax_label", $payment_tax_label);
                }
                $payment_tax_label = $payment_tax_label;
            } else {
                if (customtext_get("payment_tax_label", $aux)) {
                    customtext_set("payment_tax_label", $payment_tax_label);
                } else {
                    customtext_new("payment_tax_label", $payment_tax_label);
                }
            }

			$message = "&nbsp;".system_showText(LANG_SITEMGR_MSG_TAX_CHANGED);
			$messageStyle = "successMessage";
		}
	} else {
		setting_get("payment_tax_status", $payment_tax_status);
		setting_get("payment_tax_value", $payment_tax_value);
		customtext_get("payment_tax_label", $payment_tax_label);
	}

    # ----------------------------------------------------------------------------------------------------
    # HEADER
    # ----------------------------------------------------------------------------------------------------
    include(SM_EDIRECTORY_ROOT."/layout/header.php");

    # ----------------------------------------------------------------------------------------------------
    # NAVBAR
    # ----------------------------------------------------------------------------------------------------
    include(SM_EDIRECTORY_ROOT."/layout/navbar.php");
?>

	<div id="main-right">

		<div id="top-content">
			<div id="header-content">
				<h1><?=system_showText(LANG_SITEMGR_SETTINGS_SITEMGRSETTINGS)?> - <?=string_ucwords(system_showText(LANG_SITEMGR_SETTINGS_TAX))?></h1>
			</div>
		</div>

		<div id="content-content">

			<div class="default-margin">

				<? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>

				<br />

				<form name="tax_configuration" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">
					<? include(INCLUDES_DIR."/forms/form_tax.php"); ?>
				</form>

			</div>

		</div>

	</div>

<?
    # ----------------------------------------------------------------------------------------------------
    # FOOTER
    # ----------------------------------------------------------------------------------------------------
    include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>