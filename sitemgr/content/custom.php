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
	# * FILE: /sitemgr/content/custom.php
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

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	
	// Default CSS class for message
	$message_style = "errorMessage";

	extract($_GET);
	extract($_POST);	

	if (($_SERVER['REQUEST_METHOD'] == "POST") && (!DEMO_LIVE_MODE)) {
		$tmptype = trim($_POST["type"]);
		if ($tmptype) {
			if (!$_REQUEST['id']) $contentObj = new Content();
			else $contentObj = new Content($_REQUEST["id"]);
			$contentObj->setString("type", trim($_POST["type"]));
			if ($_POST["sitemap"]) $contentObj->setNumber("sitemap", 1);
			else $contentObj->setNumber("sitemap", 0);
			
            $description = str_replace('"', '', $_POST["description"]);
            $keywords = str_replace('"', '', $_POST["keywords"]);
            
            $contentObj->setString("title", trim($_POST["title"]));
			$contentObj->setString("description", trim($description));
			$contentObj->setString("keywords", trim($keywords));
			$contentObj->setString("url", trim($_POST["url"]));
			$contentObj->setString("section", "client");
			$contentObj->setString("content", $_POST["content_html"]);
			if (!$contentObj->isRepeated()) {
				if ($contentObj->getString("url")) {
					if (!$contentObj->isRepeatedURL()) {
						$contentObj->Save();
                        $id = $contentObj->getNumber("id");
                        $message = 0;
						header("Location:".DEFAULT_URL."/".SITEMGR_ALIAS."/content/custom.php?id=$id&message=$message&message_style=successMessage");
						exit;
					} else {
                        $message = 1;
					}
				} else {
                    $message = 2;
				}
			} else {
                $message = 3;
			}
		} else {
            $message = 4;
		}
		$post_content = $_POST["content_html"];
	}

	if ($_REQUEST['id']) {
		$contentObj = new Content($_REQUEST["id"]);
		$defaultContentObj = new Content($_REQUEST["id"]);
		if (!$defaultContentObj->getNumber("id")) {
			header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/content/client.php");
			exit;
		}
		if ($defaultContentObj->getString("section") != "client") {
			header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/content/client.php");
			exit;
		}
		$type = $defaultContentObj->getString("type");
		$sitemap = $defaultContentObj->getString("sitemap");
		$title = $contentObj->getString("title");
		$description = $contentObj->getString("description");
		$keywords = $contentObj->getString("keywords");
		$url = $contentObj->getString("url");
		if ($post_content) $this_content = $post_content;
		else $this_content = $contentObj->getString("content", false);
	} else {
		$type = "";
		$sitemap = "";
		$description = "";
		$keywords = "";
		$url = "";
		if ($post_content) $this_content = $post_content;
		else $this_content = "";
	}

	$_GET = format_magicQuotes($_GET);
	extract($_GET);
	$_POST = format_magicQuotes($_POST);
	extract($_POST);

	$blockStandartFields = "";

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

<script type="text/javascript">
<!--
function JS_submit() {
	$("#submit_button").attr("value", 1);
	document.content.submit();
}
-->
</script>

<div id="main-right">

	<div id="top-content">
		<div id="header-content">
			<h1><?=string_ucwords(system_showText(LANG_SITEMGR_CONTENT_MANAGECONTENT))?> - <?=string_ucwords(system_showText(LANG_SITEMGR_MENU_CUSTOM))?></h1>
		</div>
	</div>

	<div id="content-content">

		<? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
		<? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
		<? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

		<? include(INCLUDES_DIR."/tables/table_content_submenu.php"); ?>

		<form name="content" id="content" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">

			<input name="id" type="hidden" value="<?=$_REQUEST['id']?>" />
			<input type="hidden" name="submit_button" id="submit_button" />

			<div class="default-margin">

				<ul class="list-view">
					<? $backPage = "client.php"; ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/<?=$backPage?>"><?=system_showText(LANG_SITEMGR_MENU_BACK)?></a></li>
				</ul>

				<br />

				<div id="header-export">
					<? if (($id) && ($defaultContentObj->type)) { 
							echo "&nbsp;".$defaultContentObj->type;
						} else {
							echo "&nbsp; ".system_showText(LANG_SITEMGR_CONTENT_NEW_CUSTOMWEBPAGE);
						}
					?>
				</div>
                
                <a href="#" id="url_window" class="iframe fancy_window_small" style="display:none"></a>
				
				<? if(is_numeric($message)) { ?>
					<p class="<?=$message_style?>"><?=$msg_content[$message]?></p>
				<? } ?>

			</div>

			<table cellpadding="0" cellspacing="0" border="0" class="standard-table">
				<tr>
					<th>* <?=system_showText(LANG_SITEMGR_LABEL_PAGENAME)?>:</th>
					<td>
						<input type="text" name="type" class="inputExplode" value="<?=$type?>" maxlength="100" onblur="easyFriendlyUrl(this.value, 'url', '<?=FRIENDLYURL_VALIDCHARS?>', '<?=FRIENDLYURL_SEPARATOR?>');" />
					</td>
				</tr>
				<tr>
					<th colspan="2" class="standard-tabletitle">
						<?=system_showText(LANG_SITEMGR_SEOCENTER)?>
					</th>
				</tr>
				<? if ( SITEMAP_FEATURE == 'on' ) { ?>
						<tr>
							<th><?=system_showText(LANG_SITEMGR_LABEL_SITEMAP)?>:</th>
							<td>
								<input type="checkbox" class="inputCheck" name="sitemap" value="1" <? if ($sitemap) { echo "checked"; } ?> />
								<span>(<?=system_showText(LANG_SITEMGR_CONTENT_SITEMAP_CHECKBOX)?>)</span>
							</td>
						</tr>
				<? } ?>

				<?

				$domain = new Domain(SELECTED_DOMAIN_ID);
				$urlStr = DEFAULT_URL;
				$newurl = str_replace(DEFAULT_URL, $domain->getString("url").EDIRECTORY_FOLDER, $urlStr);

				$newurl = "http://".$newurl;
				?>

				<tr>
					<th><?=system_showText(LANG_SITEMGR_LABEL_URL)?>:</th>
					<td><?=$newurl?>/content/<input type="text" name="url" id="url" value="<?=$url?>" style="font-weight: normal; width: 100px;" onblur="easyFriendlyUrl(this.value, 'url', '<?=FRIENDLYURL_VALIDCHARS?>', '<?=FRIENDLYURL_SEPARATOR?>');" <?=$blockStandartFields?> />.html</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td><a href="javascript:void(0);" onclick="getURL('<?=$newurl?>');"style="text-decoration: underline;"><?=system_showText(LANG_SITEMGR_LABEL_GETURL)?></a></td>
				</tr>
				<tr>
					<th><?=system_showText(LANG_SITEMGR_TITLE)?>:</th>
					<td>
						<input type="text" class="inputExplode" name="title" value="<?=$title?>" maxlength="255" />
					</td>
				</tr>
				<tr>
					<th><?=system_showText(LANG_SITEMGR_LABEL_DESCRIPTION)?>:</th>
					<td>
						<textarea id="description" name="description" rows="5"><?=$description?></textarea>
					</td>
				</tr>
				<tr>
					<th><?=system_showText(LANG_SITEMGR_LABEL_KEYWORDS)?>:</th>
					<td>
						<textarea id="keywords" name="keywords" rows="5"><?=$keywords?></textarea>
					</td>
				</tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" class="standard-table">
				<tr>
					<th colspan="2" class="standard-tabletitle">
						<?=string_ucwords(system_showText(LANG_SITEMGR_CONTENT))?>
					</th>
				</tr>
			</table>

			<table class="standard-table">
				<tr>
					<td colspan="2" class="standard-tablenote" style="background:#FFF;">
                        <p class="warning"><strong><?=system_showText(LANG_SITEMGR_LABEL_NOTE)?>1:</strong>&nbsp;<?=system_showText(LANG_SITEMGR_ADDING_HIPERLINK_EDITOR)?></p>
						<p class="warning"><strong><?=system_showText(LANG_SITEMGR_LABEL_NOTE)?>2:</strong>&nbsp;<?=system_showText(LANG_SITEMGR_ADDING_EMBED_EDITOR)?></p>
					</td>
				</tr>
				<tr>
					<td colspan="2">
                        <? // TinyMCE Editor Init
							// getting content
							$content = $this_content;
                            // calling TinyMCE
                            system_addTinyMCE("", "exact", "advanced", "content_html", "30", "25", "100%", $content);
                        ?>
					</td>
				</tr>
			</table>

			<table style="margin: 0 auto 0 auto;">
				<tr>
					<td><button type="button" name="Save" value="Save" class="input-button-form" style="width:100px;" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "JS_submit();"?>"><?=system_showText(LANG_SITEMGR_SAVE)?></button></td>
				</tr>
			</table>

		</form>

	</div>

</div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>

<script language="javascript" type="text/javascript">
	
	var arrFields = ('description,keywords').split(',');
	
	$(document).ready(function(){
		
		for (j=0;j<arrFields.length;j++) {
			i = arrFields[j];
			var field_name = i;
			var count_field_name = 'remLen'+i;

			var options = {
						'maxCharacterSize': 250,
						'originalStyle': 'originalTextareaInfo',
						'warningStyle' : 'warningTextareaInfo',
						'warningNumber': 40,
						'displayFormat' : '<span><input readonly="readonly" type="text" id="'+count_field_name+'" name="'+count_field_name+'" size="3" maxlength="3" value="#left" class="textcounter" disabled="disabled" /> <?=system_showText(LANG_MSG_CHARS_LEFT)?> <?=system_showText(LANG_MSG_INCLUDING_SPACES_LINE_BREAKS)?></span>' 
				};
			$('#'+field_name).textareaCount(options);

		}
		
	});
	
	function getURL(newurl) {
		var url = document.getElementById('url').value;
		if (url){
            $("#url_window").attr("href", '<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/getcontenturl.php?c=' + url + '&newurl=' + newurl);
            $("#url_window").trigger('click');
		}else{
            fancy_alert('<?=$msg_content[2]?>', 'errorMessage', false, 450, 100, false);
		}
	}
</script>
