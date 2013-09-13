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
	# * FILE: /includes/code/transacton_manage.php
	# ----------------------------------------------------------------------------------------------------

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        /*
        * Need to check if $bulkSubmit is equal to "Submit" or LANG_SITEMGR_SUBMIT to fix an IE7 bug
        */
        if ($hiddenValue == "Submit" || $bulkSubmit == "Submit" || $bulkSubmit == LANG_SITEMGR_SUBMIT) { //Bulk update

            if (string_strpos($_SERVER["PHP_SELF"], "transactions")) {
                $typeName = "PaymentLog";
                $page = "transactions";
                $fieldName = "transaction";
            } elseif (string_strpos($_SERVER["PHP_SELF"], "custominvoices")) {
                $typeName = "CustomInvoice";
                $page = "custominvoices";
                $fieldName = "custominvoice";
            } elseif (string_strpos($_SERVER["PHP_SELF"], "invoices")) {
                $typeName = "Invoice";
                $page = "invoices";
                $fieldName = "invoice";
            }

            $ids = $_POST[string_strtolower($fieldName)."_id"];
            $error_message = "";

            if ($ids) {
                if ($delete_all == "on") {
                    foreach ($ids as $id) {
                        $itemObj = new $typeName($id);
                        $itemObj->delete();
                    }
                    $success_delete = true;
                } else {
                    $error_message = 1;
                }
                
                if (string_strpos($_SERVER["PHP_SELF"], "search.php")) {
                    if ($error_message) {
                        unset($msg);
                        unset($message);
                    } else {
                        if ($success_delete) {
                            $msg = "successdel";
                        } else {
                            $msg = "success";
                        }
                    }
                } elseif (string_strpos($_SERVER["PHP_SELF"], "index.php")) {
                    if ($error_message) {
                        unset($msg);
                        unset($message);
                    } else {
                        if ($success_delete) {
                            $msgURL = "successdel";
                        } else {
                            $msgURL = "success";
                        }
                        header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/$page/index.php?msg=$msgURL&letter=$letter&screen=$screen");
                        exit;
                    }
                }
                
            } else {
                if ($delete_all) {
                    $error_message = 2;
                } else {
                    $error_message = 4;
                }
            }
		
            
        } elseif ($hiddenValue) { //Delete
            $id = intval($hiddenValue);
            if ($manage_type == "transaction") {
                $itemObj = new PaymentLog($id);
            } elseif ($manage_type == "invoice") {
                $itemObj = new Invoice($id);
            } elseif ($manage_type == "custominvoice") {
                $itemObj = new CustomInvoice($id);
            }
            $itemObj->delete();
            header("Location: $url_redirect/".(($search_page) ? "search.php" : "index.php")."?msg=1&screen=$screen".($url_search_params ? "&$url_search_params" : ""));
            exit;
        }
    }

?>
