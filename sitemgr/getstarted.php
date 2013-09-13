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
	# * FILE: /sitemgr/getstarted.php
	# ----------------------------------------------------------------------------------------------------

    if ($_GET["domain_id"]) {
        define("SELECTED_DOMAIN_ID", $_GET["domain_id"]);
    }

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
    
    # ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    extract($_GET);
    
    if ($action == "ajax") {
        $perc = todo_updateItem("todo_".$item, true);
        echo $perc; exit;
    }
    
    todo_itensDone($stepDone, $finished);
    setting_get("percentage_todo", $percentage);
    
    //to do values
    setting_get("todo_invoice", $todo_invoice);
    setting_get("todo_email", $todo_email);
    setting_get("todo_paymentgateway", $todo_paymentgateway);
    setting_get("todo_googleads", $todo_googleads);
    setting_get("todo_googlemaps", $todo_googlemaps);
    setting_get("todo_googleanalytics", $todo_googleanalytics);
    setting_get("todo_headerlogo", $todo_headerlogo);
    setting_get("todo_noimage", $todo_noimage);
    setting_get("todo_claim", $todo_claim);
    setting_get("todo_emailconfig", $todo_emailconfig);
    setting_get("todo_approvalconfig", $todo_approvalconfig);
    setting_get("todo_locations", $todo_locations);
    setting_get("todo_theme", $todo_theme);
    setting_get("todo_pricing", $todo_pricing);
    setting_get("todo_emailnotification", $todo_emailnotification);
    setting_get("todo_langcenter", $todo_langcenter);
    setting_get("todo_levels", $todo_levels);

    # ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

?>
    <script type="text/javascript">
        
        var count = 0;
        var handle = 0;
        var maxPerc = <?=$percentage;?>
        
        function skeepTodo(setCookie) {
            if (setCookie) {
                $.cookie('skip_todo', 'true', {expires: 7, path: '/'});
            }
            location.href = '<?=DEFAULT_URL."/".SITEMGR_ALIAS;?>';
        }
        
        <? if ($finished) { ?>
            setTimeout("skeepTodo(false)", 5000);
        <? } ?>
        
        function updateBar() {

            $("#progressbar").reportprogress(++count);

            if ((count-1) == maxPerc) {
                clearInterval(handle);
                count = 0;
            }
        }
        
        function updateTodo(item) {
            
            $.get(DEFAULT_URL + "/" + SITEMGR_ALIAS + "/getstarted.php", {
                action: "ajax",
                item: item,
                domain_id: <?=SELECTED_DOMAIN_ID?>
            }, function (response) {
                
                $("#balloon").html(response+"%");
                $("#progress_bar").css("width", response+"%");
                
                $("#"+item).html("<span class=\"done\"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>");
                
                if (response == "100") {
                    $("#finished").css("display", "");
                    $('html, body, h1').animate({
                        scrollTop: $("#welcome").offset().top
                    }, 500);
                    setTimeout("skeepTodo(false)", 5000);
                }
            });
            
        }
        
        $(document).ready(function(){
            handle = setInterval("updateBar()", 25);
        });
        
    </script>
        
    <div id="content-content-home" class="get-started-content">
            
        <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
        <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
        <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
        
        <div class="welcome-box">
            
            <h1 id="welcome"><?=system_showtext(LANG_SITEMGR_TODO_WELCOME);?> <span>eDirectory</span></h1>
            
            <? if ($stepDone) {
                if ($finished) { ?>
                   <p class="successMessage"><?=system_showText(LANG_SITEMGR_TODO_FINISHED);?></p> 
                <? } else { ?>
                    <p class="successMessage"><?=system_showText(LANG_SITEMGR_TODO_ITEMDONE);?></p>
                <? }
            } ?>
                    
            <p id="finished" class="successMessage" style="display:none"><?=system_showText(LANG_SITEMGR_TODO_FINISHED);?></p>
            
            <p><?=system_showtext(LANG_SITEMGR_TODO_WELCOME_TIP);?></p>
            
            <div class="progress-box">
            
                <div id="progressbar">                
                    <div class="text"><?=system_showText(LANG_SITEMGR_TODO_PROGRESS)?></div>    
                    <div id="progress_bar" class="progress" style="width: 0%;">&nbsp;</div>    
                </div>
                
                <div id="balloon" class="balloon">&nbsp;</div>
                
            </div>
    
            <p class="continue-later">
            	<button type="button" name="continue_button" onclick="skeepTodo(true);"><?=system_showText(LANG_SITEMGR_TODO_CONTINUELATER)?></button>
            </p>
            
            <p class="note"><?=system_showText(LANG_SITEMGR_TODO_NEXTLOGIN)?></p>
            
        </div>
        
        <div class="todo_steps">
                    
            <div class="left">
                <h2>1. <?=system_showText(LANG_SITEMGR_TODO_STEP1);?></h2>
                
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?=system_showText(LANG_SITEMGR_TODO_TASK);?></th>
                        <th class="center"><?=system_showText(LANG_SITEMGR_TODO_COMPLETED);?></th>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/emailconfig.php"?>"><?=system_showText(LANG_SITEMGR_TODO_EMAILCONFIG);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_emailconfig == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="emailconfig">
                                    <input type="checkbox" name="todo_emailconfig" onchange="updateTodo('emailconfig')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/emailnotifications/"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPEMAILNOTIF);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_emailnotification == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="emailnotification">
                                    <input type="checkbox" name="todo_emailnotification" onchange="updateTodo('emailnotification')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/email.php"?>"><?=system_showText(LANG_SITEMGR_TODO_CONFADMINEMAIL);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_email == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="email">
                                    <input type="checkbox" name="todo_email" onchange="updateTodo('email')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                </table>
                
                <div class="stepTip">
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP1_TIP1);?></p>
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP1_TIP2);?></p>
                </div>
                
            </div>
                    
            <div class="right">
                <h2>2. <?=system_showText(LANG_SITEMGR_TODO_STEP2);?></h2>
                
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?=system_showText(LANG_SITEMGR_TODO_TASK);?></th>
                        <th class="center"><?=system_showText(LANG_SITEMGR_TODO_COMPLETED);?></th>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/googleprefs/googleads.php"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPGOOGLEADS);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_googleads == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="googleads">
                                    <input type="checkbox" name="todo_googleads" onchange="updateTodo('googleads')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/googleprefs/googlemaps.php"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPGOOGLEMAPS);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_googlemaps == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="googlemaps">
                                    <input type="checkbox" name="todo_googlemaps" onchange="updateTodo('googlemaps')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/googleprefs/googleanalytics.php"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPGOOGLEANALYTICS);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_googleanalytics == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="googleanalytics">
                                    <input type="checkbox" name="todo_googleanalytics" onchange="updateTodo('googleanalytics')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                </table>
                
                <div class="stepTip">
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP2_TIP1);?></p>
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP2_TIP2);?></p>
                </div>
                
            </div>
                    
            <div class="left">
                <h2>3. <?=system_showText(LANG_SITEMGR_TODO_STEP3);?></h2>
                
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?=system_showText(LANG_SITEMGR_TODO_TASK);?></th>
                        <th class="center"><?=system_showText(LANG_SITEMGR_TODO_COMPLETED);?></th>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/pricing.php"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPPRICEINFO);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_pricing == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="pricing">
                                    <input type="checkbox" name="todo_pricing" onchange="updateTodo('pricing')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/invoice.php"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPINVOICEINFO);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_invoice == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="invoice">
                                    <input type="checkbox" name="todo_invoice" onchange="updateTodo('invoice')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/paymentgateway.php"?>"><?=system_showText(LANG_SITEMGR_TODO_PAYMENTGATEWAY);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_paymentgateway == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="paymentgateway">
                                    <input type="checkbox" name="todo_paymentgateway" onchange="updateTodo('paymentgateway')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                </table>
                
                <div class="stepTip">
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP3_TIP1);?></p>
                </div>
                
            </div>
                    
            <div class="right">
                <h2>4. <?=system_showText(LANG_SITEMGR_TODO_STEP4);?></h2>
                
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?=system_showText(LANG_SITEMGR_TODO_TASK);?></th>
                        <th class="center"><?=system_showText(LANG_SITEMGR_TODO_COMPLETED);?></th>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/content_header.php"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPHEADERLOGO);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_headerlogo == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="headerlogo">
                                    <input type="checkbox" name="todo_headerlogo" onchange="updateTodo('headerlogo')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/content_noimage.php"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPNOIMAGE);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_noimage == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="noimage">
                                    <input type="checkbox" name="todo_noimage" onchange="updateTodo('noimage')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/theme.php"?>"><?=system_showText(LANG_SITEMGR_TODO_THEME);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_theme == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="theme">
                                    <input type="checkbox" name="todo_theme" onchange="updateTodo('theme')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                </table>
                
                <div class="stepTip">
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP4_TIP1);?></p>
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP4_TIP2);?></p>
                </div>
                
            </div>
                    
            <div class="full">
                <h2>5. <?=system_showText(LANG_SITEMGR_TODO_STEP5);?></h2>
                
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?=system_showText(LANG_SITEMGR_TODO_TASK);?></th>
                        <th class="center"><?=system_showText(LANG_SITEMGR_TODO_COMPLETED);?></th>
                        <th><?=system_showText(LANG_SITEMGR_TODO_TASK);?></th>
                        <th class="center"><?=system_showText(LANG_SITEMGR_TODO_COMPLETED);?></th>
                        <th><?=system_showText(LANG_SITEMGR_TODO_TASK);?></th>
                        <th class="center"><?=system_showText(LANG_SITEMGR_TODO_COMPLETED);?></th>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/levels.php"?>"><?=system_showText(LANG_SITEMGR_TODO_LEVELS);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_levels == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="levels">
                                    <input type="checkbox" name="todo_levels" onchange="updateTodo('levels')" >
                                </div>
                            <? } ?>
                        </td>
                        
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/approvalrequirement.php"?>"><?=system_showText(LANG_SITEMGR_TODO_CONFIGURE_APPROVAL);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_approvalconfig == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="approvalconfig">
                                    <input type="checkbox" name="todo_approvalconfig" onchange="updateTodo('approvalconfig')" >
                                </div>
                            <? } ?>
                        </td>
                        
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/langcenter/index.php"?>"><?=system_showText(LANG_SITEMGR_TODO_LANGCENTER);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_langcenter == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="langcenter">
                                    <input type="checkbox" name="todo_langcenter" onchange="updateTodo('langcenter')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/location.php"?>"><?=system_showText(LANG_SITEMGR_TODO_LOCATIONS);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_claim == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="claim">
                                    <input type="checkbox" name="todo_claim" onchange="updateTodo('claim')" >
                                </div>
                            <? } ?>
                        </td>
                        
                        <td>
                            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/prefs/claim.php"?>"><?=system_showText(LANG_SITEMGR_TODO_SETUPCLAIM);?></a>
                        </td>
                        <td align="center">
                            <? if ($todo_locations == "done") { ?>
                                <span class="done"><?=system_showText(LANG_SITEMGR_TODO_DONE)?></span>
                            <? } else { ?>
                                <div id="locations">
                                    <input type="checkbox" name="todo_locations" onchange="updateTodo('locations')" >
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                </table>
                
                <div class="stepTip">
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP5_TIP1);?></p>
                    <p><?=system_showText(LANG_SITEMGR_TODO_STEP5_TIP2);?></p>
                </div>
                
            </div>
                    
        </div>
                        
    </div>
    
    <div id="bottom-content-home">&nbsp;</div>

    <br clear="all" />

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>