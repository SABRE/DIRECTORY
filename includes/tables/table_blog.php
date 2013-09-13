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
	# * FILE: /includes/tables/table_blog.php
	# ----------------------------------------------------------------------------------------------------

	setting_get("commenting_fb", $commenting_fb);
	setting_get("wp_enabled", $wp_enabled);
	$itemCount = count($posts);
	if (BLOG_WITH_WORDPRESS == "on"){
		$wp_enabled = "";
	}
?>

    <script type="text/javascript">
        function getValuesBulkBlog(){
            if (document.getElementById('delete_all').checked){
                document.getElementById("bulkSubmit").value = "Submit";
                dialogBoxBulk('confirm','<?=system_showText(LANG_SITEMGR_BULK_DELETEQUESTION);?>','Submit','blog_setting','200','<?=system_showText(LANG_SITEMGR_OK);?>','<?=system_showText(LANG_SITEMGR_CANCEL);?>');
            } else {
                document.getElementById("bulkSubmit").value = "Submit";
                dialogBoxBulk('confirm','<?=system_showText(LANG_SITEMGR_BULK_DELETEQUESTION2);?>','Submit','blog_setting','180','<?=system_showText(LANG_SITEMGR_OK);?>','<?=system_showText(LANG_SITEMGR_CANCEL);?>');
            }
        }

        function confirmBulk(){

            <? if (BLOGCATEGORY_SCALABILITY_OPTIMIZATION == "on") { ?>
                feed = document.blog_setting.feed;
                return_categories = document.blog_setting.return_categories;
                if(return_categories.value.length > 0) return_categories.value="";

                for (i=0;i<feed.length;i++) {
                    if (!isNaN(feed.options[i].value)) {
                        if(return_categories.value.length > 0)
                        return_categories.value = return_categories.value + "," + feed.options[i].value;
                        else
                    return_categories.value = return_categories.value + feed.options[i].value;
                    }
                }   
            <? } ?>

            if (document.getElementById('delete_all').checked){
                document.getElementById("bulkSubmit").value = "Submit";
                dialogBoxBulk('confirm','<?=system_showText(LANG_SITEMGR_BULK_DELETEQUESTION);?>','Submit','blog_setting','200','<?=system_showText(LANG_SITEMGR_OK);?>','<?=system_showText(LANG_SITEMGR_CANCEL);?>');
            } else {
                document.getElementById("bulkSubmit").value = "Submit";
                dialogBoxBulk('confirm','<?=system_showText(LANG_SITEMGR_BULK_DELETEQUESTION2);?>','Submit','blog_setting','180','<?=system_showText(LANG_SITEMGR_OK);?>','<?=system_showText(LANG_SITEMGR_CANCEL);?>');
            }
        }
    </script>

    <? 
    
    //Success and Error Messages
    if (is_numeric($message) && isset($msg_post[$message])) {
        echo "<p class=\"successMessage\">".$msg_post[$message]."</p>";
    }
    if (is_numeric($error_message)) {
        echo "<p class=\"errorMessage\">".$msg_bulkupdate[$error_message]."</p>";
    } elseif ($error_msg) {
        echo "<p class=\"errorMessage\">".$error_msg."</p>";
    } elseif ($msg == "success") {
        echo "<p class=\"successMessage\">".LANG_MSG_BLOG_SUCCESSFULLY_UPDATE."</p>";
    } elseif ($msg == "successdel") {
        echo "<p class=\"successMessage\">".LANG_MSG_BLOG_SUCCESSFULLY_DELETE."</p>";
    }
    unset($msg);
    
    //Bulk update and Ordination validation
    $orderLinks = false;
    if ((!string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/search")) && (!string_strpos($_SERVER["PHP_SELF"], "getMoreResults"))) {

        $orderLinks = true; ?>

        <table class="bulkTable" border="0" cellpadding="2" cellspacing="2" >
            <tr>
                <td><a class="bulkUpdate" href="javascript:void(0)" onclick="showBulkUpdate( <?=RESULTS_PER_PAGE?>, 'blog', '<?=system_showText(LANG_SITEMGR_CLOSE_BULK);?>', '<?=system_showText(LANG_SITEMGR_BULK_UPDATE);?>')" id="open_bulk"><?=system_showText(LANG_SITEMGR_BULK_UPDATE);?></a></td>
            </tr>
        </table>

        <? if (string_strpos($_SERVER["PHP_SELF"], "/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER."/search") !== false) {
            $actionBulk = system_getFormAction($_SERVER["REQUEST_URI"]);
        } else {
            $actionBulk = system_getFormAction($_SERVER["PHP_SELF"]);
        } ?>

        <form name="blog_setting" id="blog_setting" action="<?=$actionBulk?>" method="post">

            <input type="hidden" name="bulkSubmit" id="bulkSubmit" value="" />

            <div id="table_bulk" style="display: none">
                
                <? include(INCLUDES_DIR."/tables/table_bulkupdate.php"); ?>
                
                <? if (string_strpos($_SERVER["PHP_SELF"], "search.php") == true) { ?>
                    <button type="button" name="bulkSubmit" value="Submit" class="input-button-form" onclick="javascript:getValuesBulkBlog();"><?=system_showText(LANG_SITEMGR_SUBMIT)?></button>
                <? } else { ?>
                    <button type="button" name="bulkSubmit" value="Submit" class="input-button-form" onclick="javascript:confirmBulk();"><?=system_showText(LANG_SITEMGR_SUBMIT)?></button>
                <? } ?>

            </div>
            
            <div id="idlist"></div>
            
        </form>
    
        <? include(INCLUDES_DIR."/tables/table_paging.php"); ?>

        <div id="bulk_check" style="display:none">
            
            <table class="bulkTable" border="0" cellpadding="2" cellspacing="2">
                <tr>
                    <th><input type="checkbox" id="check_all" name="check_all" onclick="checkAll('blog', document.getElementById('check_all'), false, <?=$itemCount?>); removeCategoryDropDown('blog', '<?=DEFAULT_URL?>');" /></th>
                    <td><a class="CheckUncheck" href="javascript:void(0);" onclick="checkAll('blog', document.getElementById('check_all'), true, <?=$itemCount?>); removeCategoryDropDown('blog', '<?=DEFAULT_URL?>');"><?=system_showText(LANG_CHECK_UNCHECK_ALL);?></a></td>
                </tr>
            </table>
            
        </div>
    <? } ?>

    <? if ((!isset($legend))||($legend)) { ?>
        <ul class="standard-iconDESCRIPTION">
            <li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
            <? if (!$wp_enabled){ ?>
            <li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT);?></li>
            <? } ?>
            <li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS);?></li>
            <li class="seo-icon"><?=system_showText(LANG_LABEL_SEO_TUNING);?></li>
            <? if ($commenting_fb == "on") { ?>
            <li class="facebook-icon"><?=system_showText(LANG_LABEL_FACEBOOK_COMMENTS);?></li>
            <? } ?>
            <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE);?></li>
        </ul>
    <? } ?>
    
    <form name="item_table">
        
        <table border="0" cellpadding="0" cellspacing="0" class="standard-tableTOPBLUE arrow-manage">

            <tr>
                <th style="width: auto;">
                    <span style="width: auto;"><?=system_showText(LANG_SITEMGR_BLOG_POST_TITLE);?></span>
                    <? if ($orderLinks) { ?>
                        <a href="<?=$paging_url."?order_by=title_".($order_by == "title_asc" ? "desc" : "asc")."&letter=$letter&screen=$screen".($url_search_params ? "&$url_search_params" : "")?>">
                            <img src="<?=DEFAULT_URL."/images/bg_arrow_".($order_by == "title_asc" ? "up" : "down").".png"?>" title="<?=system_showText(@constant("LANG_CLICK_ORDERTITLE".($order_by == "title_asc" ? "DESC" : "ASC")))?>" />
                        </a>
                    <? } ?>
                </th>

                <th style="width: 100px;">
                    <span><?=system_showText(LANG_LABEL_STATUS);?></span>
                    <? if ($orderLinks) { ?>
                        <a href="<?=$paging_url."?order_by=status_".($order_by == "status_asc" ? "desc" : "asc")."&letter=$letter&screen=$screen".($url_search_params ? "&$url_search_params" : "")?>">
                            <img src="<?=DEFAULT_URL."/images/bg_arrow_".($order_by == "status_asc" ? "up" : "down").".png"?>" title="<?=system_showText(@constant("LANG_CLICK_ORDERSTATUS".($order_by == "status_asc" ? "DESC" : "ASC")))?>" />
                        </a>
                    <? } ?>
                </th>

                <th style="width: 5%;"><?=system_showText(LANG_LABEL_OPTIONS)?></th>
            </tr>

            <?
            $cont = 0;
            if ($posts) foreach ($posts as $post_info) {
                $cont++;
                $id = $post_info->getNumber("id");
                ?>

                <tr>
                    <td>
                        <input type="checkbox" id="blog_id<?=$cont?>" name="item_check[]" value="<?=$id?>" class="inputCheck" style="display:none" onclick="removeCategoryDropDown('blog', '<?=DEFAULT_URL?>');"/>
                        <a title="<?=$post_info->getString("title");?>" href="<?=$url_redirect?>/view.php?id=<?=$id?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table">
                            <?=$post_info->getString("title", true, 100);?>
                        </a>				
                    </td>
                    <td>
                        <?
                        $status = new ItemStatus();
                        ?>
                        <a title="<?=$status->getStatus($post_info->getString("status"));?>" href="<?=$url_redirect?>/settings.php?id=<?=$id?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table"><?  echo $status->getStatusWithStyle($post_info->getString("status")); ?></a>
                    </td>

                    <td nowrap>

                        <a href="<?=$url_redirect?>/view.php?id=<?=$id?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table">
                            <img src="<?=DEFAULT_URL?>/images/bt_view.gif" border="0" alt="<?=system_showText(LANG_MSG_CLICK_TO_VIEW_THIS_POST)?>" title="<?=system_showText(LANG_MSG_CLICK_TO_VIEW_THIS_POST)?>" />
                        </a>
                        <? if (!$wp_enabled){ ?>
                        <a href="<?=$url_redirect?>/blog.php?id=<?=$id?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table">
                            <img src="<?=DEFAULT_URL?>/images/bt_edit.gif" border="0" alt="<?=system_showText(LANG_MSG_CLICK_TO_EDIT_THIS_POST)?>" title="<?=system_showText(LANG_MSG_CLICK_TO_EDIT_THIS_POST)?>" />
                        </a>
                        <? } ?>
                        <a href="<?=$url_redirect?>/report.php?id=<?=$id?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table">
                            <img src="<?=DEFAULT_URL?>/images/icon_traffic.gif" border="0" alt="<?=system_showText(LANG_MSG_CLICK_TO_VIEW_THIS_BLOG_REPORTS)?>" title="<?=system_showText(LANG_MSG_CLICK_TO_VIEW_THIS_BLOG_REPORTS)?>" />
                        </a>

                        <a href="<?=$url_redirect?>/seocenter.php?id=<?=$id?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table">
                            <img src="<?=DEFAULT_URL?>/images/icon_seo.gif" border="0" alt="<?=system_showText(LANG_MSG_CLICK_TO_EDIT_SEOCENTER)?>" title="<?=system_showText(LANG_MSG_CLICK_TO_EDIT_SEOCENTER)?>" />
                        </a>

                        <? if ($commenting_fb == "on") { ?>
                            <a href="<?=$url_redirect?>/facebook.php?id=<?=$id?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table">
                                <img src="<?=DEFAULT_URL?>/images/icon-facebook-comments.gif" border="0" alt="<?=system_showText(LANG_LABEL_FACEBOOK_COMMENTS)?>" title="<?=system_showText(LANG_LABEL_FACEBOOK_COMMENTS)?>" />
                            </a>
                        <? } ?>

                        <a href="<?=$url_redirect?>/delete.php?id=<?=$id?>&screen=<?=$screen?>&letter=<?=$letter?><?=(($url_search_params) ? "&$url_search_params" : "")?>" class="link-table">
                            <img src="<?=DEFAULT_URL?>/images/bt_delete.gif" border="0" alt="<?=system_showText(LANG_MSG_CLICK_TO_DELETE_THIS_POST)?>" title="<?=system_showText(LANG_MSG_CLICK_TO_DELETE_THIS_POST)?>" />
                        </a>

                    </td>
                </tr>

                <? } ?>

        </table>
        
    </form>
	
    <? if ((!isset($legend))||($legend)) { ?>
        <ul class="standard-iconDESCRIPTION">
            <li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
            <? if (!$wp_enabled){ ?>
            <li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT);?></li>
            <? } ?>
            <li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS);?></li>
            <li class="seo-icon"><?=system_showText(LANG_LABEL_SEO_TUNING);?></li>
            <? if ($commenting_fb == "on") { ?>
                <li class="facebook-icon"><?=system_showText(LANG_LABEL_FACEBOOK_COMMENTS);?></li>
            <? } ?>
            <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE);?></li>
        </ul>
    <? } ?>