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
	# * FILE: /includes/tables/table_bulkupdatePayment.php
	# ----------------------------------------------------------------------------------------------------
?>

    <table border="0" cellpadding="2" cellspacing="0" class="standard-table noMargin">
        <tr>
            <th>&nbsp;</th>
            <td><input type="checkbox" name="delete_all" id="delete_all" class="inputAlign" onclick="disableBulkOptions(document.getElementById('delete_all')); "/><?=system_showText(LANG_SITEMGR_DELETE_ALL_SELECTED)?><span>&nbsp;(<?=system_showText(LANG_SITEMGR_DELETEALL_INFO)?>)</span></td>
        </tr>
    </table>