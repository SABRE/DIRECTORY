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
	# * FILE: /classes/class_InvoiceClassified.php
	# ----------------------------------------------------------------------------------------------------

	/**
	 * <code>
	 *		$invoiceClassifiedObj = new InvoiceClassified($id);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @package Classes
	 * @name InvoiceClassified
	 * @method InvoiceClassified
	 * @method makeFromRow
	 * @method Save
	 * @access Public
	 */
	class InvoiceClassified extends Handle {

		/**
		 * @var integer
		 * @access Private
		 */
		var $invoice_id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $classified_id;
		/**
		 * @var string
		 * @access Private
		 */
		var $classified_title;
		/**
		 * @var integer
		 * @access Private
		 */
		var $discount_id;
		/**
		 * @var integer
		 * @access Private
		 */
        var $level;
		/**
		 * @var string
		 * @access Private
		 */
		var $level_label;
		/**
		 * @var date
		 * @access Private
		 */
		var $renewal_date;
		/**
		 * @var real
		 * @access Private
		 */
		var $amount;

		/**
		 * <code>
		 *		$invoiceClassifiedObj = new InvoiceClassified($id);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name InvoiceClassified
		 * @access Public
		 * @param integer $var
		 */
		function InvoiceClassified($var="") {
			if (is_array($var) && ($var)) $this->makeFromRow($var);
		}

		/**
		 * <code>
		 *		$this->makeFromRow($row);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name makeFromRow
		 * @access Public
		 * @param array $row
		 */
		function makeFromRow($row="") {
			$this->invoice_id		= ($row["invoice_id"])			? $row["invoice_id"]		: ($this->invoice_id		? $this->invoice_id			: 0);
			$this->classified_id	= ($row["classified_id"])		? $row["classified_id"]		: ($this->classified_id		? $this->classified_id		: 0);
			$this->classified_title	= ($row["classified_title"])	? $row["classified_title"]	: ($this->classified_title	? $this->classified_title	: "");
			$this->discount_id		= ($row["discount_id"])			? $row["discount_id"]		: ($this->discount_id		? $this->discount_id		: "");
            $this->level            = ($row["level"])               ? $row["level"]             : ($this->level             ? $this->level              : 0);
			$this->level_label		= ($row["level_label"])			? $row["level_label"]		: ($this->level_label		? $this->level_label		: "");
			$this->renewal_date		= ($row["renewal_date"])		? $row["renewal_date"]		: ($this->renewal_date		? $this->renewal_date		: 0);
			$this->amount			= ($row["amount"])				? $row["amount"]			: ($this->amount			? $this->amount				: 0);
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$invoiceClassifiedObj->Save();
		 * <br /><br />
		 *		//Using this in InvoiceClassified() class.
		 *		$this->Save();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Save
		 * @access Public
		 */
		function Save() {

			$this->PrepareToSave();

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
//			$dbMain->close();
			unset($dbMain);

			$sql = "INSERT INTO Invoice_Classified"
				. " (invoice_id,"
				. " classified_id,"
				. " classified_title,"
				. " discount_id,"
                . " level,"
				. " level_label,"
				. " renewal_date,"
				. " amount"
				. " )"
				. " VALUES"
				. " ("
				. " $this->invoice_id,"
				. " $this->classified_id,"
				. " $this->classified_title,"
				. " $this->discount_id,"
                . " $this->level,"
				. " $this->level_label,"
				. " $this->renewal_date,"
				. " $this->amount"
				. " )";

			$dbObj->query($sql);

			$this->id = mysql_insert_id($dbObj->link_id);

			$this->PrepareToUse();

		}

	}

?>
