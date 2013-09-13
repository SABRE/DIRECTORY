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
	# * FILE: /edir_core/article/results_articles.php
	# ----------------------------------------------------------------------------------------------------

	if ($show_results) {

		if (!$articles) {

			if ($search_lock) {?>
				<p class="errorMessage">
					<?=system_showText(LANG_MSG_LEASTONEPARAMETER)?>
				</p>
				<?
			} else {
				$db = db_getDBObject();
				if ($db->getRowCount("Article") > 0) { ?>
					<div class="resultsMessage">                      
                        <?
                        unset($aux_lang_msg_noresults);                        
                        $aux_lang_msg_noresults = str_replace("[EDIR_LINK_SEARCH_ERROR]", DEFAULT_URL."/".ALIAS_CONTACTUS_URL_DIVISOR.".php", LANG_SEARCH_NORESULTS);
                        echo $aux_lang_msg_noresults;
                        ?>
					</div>
                <? } else { ?>
					<p class="informationMessage">
						<?=system_showText(LANG_MSG_NOARTICLES);?>
					</p>
                <? }
			}
		} elseif ($articles){

			$level = new ArticleLevel(true);
			$count = 10;
			$ids_report_lote = "";

			foreach ($articles as $article) {
				$ids_report_lote .= $article->getString("id").",";
				$includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
				if(SELECTED_DOMAIN_ID > 0)
					include($includeUrl."includes/views/view_article_summary.php");
				else
					include(INCLUDES_DIR."/views/view_article_summary.php");
					
				$count--;
			}
			$ids_report_lote = string_substr($ids_report_lote, 0, -1);
			report_newRecord("article", $ids_report_lote, ARTICLE_REPORT_SUMMARY_VIEW, true);
		}
	}
?>