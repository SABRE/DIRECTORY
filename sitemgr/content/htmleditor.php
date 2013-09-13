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
    # * FILE: /sitemgr/content/htmleditor.php
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
	system_setFreqActions('content_htmleditor', 'content');
    
    # ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	include(INCLUDES_DIR."/code/editor.php");

    # ----------------------------------------------------------------------------------------------------
    # HEADER
    # ----------------------------------------------------------------------------------------------------
    include(SM_EDIRECTORY_ROOT."/layout/header.php");

    # ----------------------------------------------------------------------------------------------------
    # NAVBAR
    # ----------------------------------------------------------------------------------------------------
    include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>
    <? if ($htmleditor_first_change != "no" && !DEMO_LIVE_MODE) { ?>
        <a href="#" id="window_firstChange" class="iframe fancy_window_htmleditor2" style="display:none" title="<?=system_showText(LANG_SITEMGR_SETTINGS_HTMLEDITOR);?>"></a>
    <? } ?>

    <script language="javascript" type="text/javascript">
              
        function setEditor(value){
            if (value){
                location.href = '<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor.php?file="?>'+value;
            }
        }
        
        function show_source(){
            $("#info_window").trigger('click');
        }
        
        function download_source(){
            <? if (!DEMO_LIVE_MODE) { ?>
				document.location = "<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/htmleditor.php?download=1&file=<?=$file?>&fileType=<?=$fileType?>";
			<? } else { ?>
				livemodeMessage('<?=system_showText(LANG_SITEMGR_EDITOR_DEMO_MESSAGE);?>');
			<? } ?>
        }
        
        function openPreview() {
            var url = "<?=($previewURL."/index.php?$previewHash")?>";
            previewsection = window.open(url, "_blank");
            previewsection.focus();
        }
               
        var errorStatus = '';
        
        function validateSubmit(){
            var result_info;
            var aux_text = editAreaLoader.getValue("textarea");
            $("#button_submit").html("<?=LANG_WAITLOADING?>");
            $("#button_submit").attr("disabled", true);
            $("#text_temp").attr("value", aux_text);
            $.post("<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor_validate.php?"?>"+encodeURI(Math.random()), $("#htmleditor").serialize(), function(result) {
                result_info = result.split("||");
                if (result_info[0] == "error") {
                    $("#errorMessageAjax").css("display", "");
                    $("#errorMessageAjax").html(result_info[1]);
                    errorStatus = result_info[1];
                    $("#button_submit").attr("disabled", false);
                    $("#button_submit").html("<?=LANG_SITEMGR_EDITOR_SUBMITCHANGES?>");
                } else {
                    $.ajax({
                        url: "<?=DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/lang/editor/"?>"+result_info[1]+"?"+encodeURI(Math.random()),
                        error: function(xhr, statusText, errorThrown){
                            errorStatus = xhr.status;
                            errorStatus = errorStatus.toString();
                        },
                        success: function(html){
                            errorStatus = html;
                        },
                        complete: function(){
                            if (errorStatus.length > 1) {
                                $("#button_submit").attr("disabled", false);
                                $("#button_submit").html("<?=LANG_SITEMGR_EDITOR_SUBMITCHANGES?>");
                                fancy_alert("<?=system_showText(LANG_SITEMGR_EDITOR_PHPERROR)?>", 'errorMessage', false, 450, 100, false);
                            } else {
                                var aux_text = editAreaLoader.getValue("textarea");
                                $("#text_temp").attr("value", aux_text);
                                $("#from_ajax").attr("value", "1");
                                $.post("<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor.php?"?>"+encodeURI(Math.random()), $("#htmleditor").serialize(), function(result) {
                                    location.href = result;
                                });
                            }
                        }
                    });
                }
            });
        }
        
        <? if ($htmleditor_first_change != "no" && !DEMO_LIVE_MODE) { ?>
            $(document).ready(function() {
                $("#window_firstChange").attr("href", '<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/htmlagree.php');
                $("#window_firstChange").trigger("click");
            });
        <? } ?>
        
        <? if ($preview) { ?>
            $(document).ready(function() {
                openPreview();
            });
        <? } ?>
               
        editAreaLoader.init({
            id : "textarea",	
            syntax: "<?=$editorSyntax?>",			
            start_highlight: true,
            language: "<?=$editorLang?>",
            allow_toggle: false
        });
   
    </script>

	<div id="main-right">

		<div id="top-content">
			<div id="header-content">
				<h1><?=system_showText(LANG_SITEMGR_CONTENT_MANAGECONTENT)?> - <?=system_showText(LANG_SITEMGR_SETTINGS_HTMLEDITOR);?></h1>
			</div>
		</div>

		<div id="content-content">

			<div class="default-margin">
                
                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
				<? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
				<? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
                
				<? include(INCLUDES_DIR."/tables/table_content_submenu.php"); ?>
                
               <? if (is_numeric($message) && isset($msg_editor[$message])) { ?>
                    <p class="successMessage"><?=$msg_editor[$message]?></p>
                <? } elseif ($errorMessage) { ?>
                    <p class="errorMessage"><?=$errorMessage?></p>
                <? } ?>
                    
                <p id="errorMessageAjax" class="errorMessage" style="display:none"></p>
             				
                <form id="htmleditor" name="htmleditor" class="html-editor" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">
                    <? if ($preview) { ?>
                    <input type="hidden" name="cleanPreview" value="on"/>
                    <? } ?>
                    <input type="hidden" name="domain_id" value="<?=SELECTED_DOMAIN_ID?>">
                    <input type="hidden" id="from_ajax" name="from_ajax" value="0">
                    <? include(INCLUDES_DIR."/forms/form_content_editor.php"); ?>          
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
