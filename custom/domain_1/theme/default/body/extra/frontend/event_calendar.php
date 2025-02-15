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
	# * FILE: /frontend/event_calendar.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") {

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	if ($_GET["this_date"]) cal_display_month_domain("",$_GET["this_date"]);
	else cal_display_month_domain();

    ?>

	<script language="javascript" type="text/javascript">
		function set_cal_date(date) {
			var path = "";

			path = "<?=EVENT_DEFAULT_URL?>/results.php?this_date="+date+"&search_by_day=true";

			document.location.href = path;
		}
	</script>

    <? } ?>
