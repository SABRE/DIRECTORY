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
	# * FILE: /conf/payment_itransact.inc.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# ITRANSACT CONSTANTS
	# ----------------------------------------------------------------------------------------------------
	if (ITRANSACTPAYMENT_FEATURE == "on") {
		if (REALTRANSACTION == "on") {
			$itransact_vendorid = "";
			$dbObjPayment = db_getDBObject();
			$sql = "SELECT * FROM Setting_Payment WHERE name LIKE 'ITRANSACT_%'";
			$result = $dbObjPayment->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				if ($row["name"] == "ITRANSACT_VENDORID") $itransact_vendorid = crypt_decrypt($row["value"]);
			}
			unset($dbObjPayment);
			define("ITRANSACT_VENDORID", $itransact_vendorid);
		} else {
			define("ITRANSACT_VENDORID", "68705");
		}
		define("ITRANSACT_HOST", "https://secure.paymentclearing.com/cgi-bin/rc/ord.cgi");
		define("ITRANSACT_CURRENCY", PAYMENT_CURRENCY);
	}

?>