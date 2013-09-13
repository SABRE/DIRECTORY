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
    # * FILE: /sitemgr/content/navigation.php
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
	system_setFreqActions('content_navigation', 'content');
    
    # ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	include(INCLUDES_DIR."/code/navigation.php");

    # ----------------------------------------------------------------------------------------------------
    # HEADER
    # ----------------------------------------------------------------------------------------------------
    include(SM_EDIRECTORY_ROOT."/layout/header.php");

    # ----------------------------------------------------------------------------------------------------
    # NAVBAR
    # ----------------------------------------------------------------------------------------------------
    include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>
    <script language="javascript" type="text/javascript">

        $(function() {
            $("#sortable").sortable();
        });
        
        function JS_submit() {
            serialize();
            document.form_navigation.submit();
        }

        function enableCustomLink(liID) {
            if ($("#dropdown_link_to_"+liID).val() == "custom") {
                $("#custom_link_"+liID).attr('disabled', '');
                $("#custom_link_"+liID).css('background-color', '#FFFFFF');
            } else {
                $("#custom_link_"+liID).attr('disabled', 'true');
                $("#custom_link_"+liID).css('background-color', '#f0f0f0');
            }
        }

        function serialize() {
            var ids = $("#sortable").sortable("toArray");

            $("#SaveByAjax").val("false");
            $("#order_options").attr("value", ids);
            $("#navigation").submit();
        }

        function removeItem(id) {
            $("#"+id).remove();
        }

        function replaceAll(string, token, newtoken) {
            while (string.indexOf(token) != -1) {
                string = string.replace(token, newtoken);
            }
            return string;
        }

        function CreateNewItem() {
            var LiText = $("#aux_litext").val();
            LiText = replaceAll(LiText, "LI_ID", $("#aux_count_li").val());

            $("#sortable").append(LiText);
            $("#custom_link_"+$("#aux_count_li").val()).attr('disabled', '');
            var countLi = parseInt($("#aux_count_li").val());
            $("#aux_count_li").val(countLi+1);
        }

        function ViewTheSite() {
            $("#SaveByAjax").val("true");
            $("#order_options").attr("value", $("#sortable").sortable("toArray"));

            $.post("<?=$_SERVER["PHP_SELF"]?>", $("#form_navigation").serialize(), function(data){
                if (data == "ok") {
                    window.open("<?=$domainURL?>", "_blank");
                } else {
                    $("#auxErrorMessage").html(data);
                    $("#auxErrorMessage2").css("display", "none");
                    $("#auxErrorMessage").css("display", "");
                    $('html, body').animate({
						scrollTop: $('#auxErrorMessage').offset().top
					}, 500);
                }
            });
        }

        function ResetNavigation() {
            dialogBox('confirm', '<?=system_showText(LANG_SITEMGR_NAVIGATION_CONFIRM_RESET);?>', '', 'reset_navigation', '200', '<?=system_showText(LANG_SITEMGR_OK);?>', '<?=system_showText(LANG_SITEMGR_CANCEL);?>');
        }

        function ChangeArea(area) {
            document.location = "<?=$_SERVER["PHP_SELF"]?>?navigation_area="+area;
        }

    </script>

	<div id="main-right">

		<div id="top-content">
			<div id="header-content">
				<h1><?=system_showText(LANG_SITEMGR_CONTENT_MANAGECONTENT)?> - <?=system_showText(LANG_SITEMGR_SETTINGS_NAVIGATION);?></h1>
			</div>
		</div>

		<div id="content-content">

			<div class="default-margin">
                
                <? 
                require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); 
                require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); 
                require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); 
                
                include(INCLUDES_DIR."/tables/table_content_submenu.php"); 
                
                if ($errorMessage) { ?>
                    <p class="errorMessage" id="auxErrorMessage2"><?=$errorMessage?></p>
                <? } elseif ($successMessage == 1) { ?>
                    <p class="successMessage"><?=system_showText(LANG_SITEMGR_NAVIGATION_SUCCESS);?></p>
                <? } ?>
                <p class="errorMessage" id="auxErrorMessage" style="display:none"></p>
                
                <input type="hidden" name="aux_litext" id="aux_litext" value='<?=$aux_LI_code?>' />
             				
                <form id="form_navigation" name="form_navigation" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">
                    
                    <input type="hidden" name="domain_id" value="<?=SELECTED_DOMAIN_ID?>">
                    
                    <? include(INCLUDES_DIR."/forms/form_navigation.php"); ?>  
                    
                </form>
                    
                <form id="reset_navigation" name="reset_navigation" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">
                    <input type="hidden" name="resetNavigation" value="reset" />
                    <input type="hidden" name="area" value="<?=$navigation_area?>" />
                </form>

                <div class="navigation-msgs">
                    <p>
                        <a href="javascript:void(0);" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "ResetNavigation();"?>"><?=system_showText(LANG_SITEMGR_RESET_NAVIGATION)?></a>
                        <span><?=LANG_SITEMGR_RESET_NAVIGATION_TEXT?> </span>
                    </p>
                    
                    <p>
                        <a href="javascript:void(0);" class="viewsite" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "ViewTheSite();"?>"><?=system_showText(LANG_SITEMGR_VIEW_DOMAIN)?></a>
                        <span><?=system_showText(LANG_SITEMGR_VIEW_SITE_TEXT)?></span>
                    </p>
                </div>  
                    
			</div>
		</div>
	</div>

<?
    # ----------------------------------------------------------------------------------------------------
    # FOOTER
    # ----------------------------------------------------------------------------------------------------
    include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>