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
	# * FILE: /includes/forms/form_support_reset.php
	# ----------------------------------------------------------------------------------------------------

?>

    <table cellpadding="0" cellspacing="0" border="0" class="standard-table">
        <tr>
            <th colspan="2" class="standard-tabletitle">Language File</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>
                Rebuild language file (<i>custom/domain_<?=SELECTED_DOMAIN_ID?>/lang/language.inc.php</i>)
                <span>This may solve some problems related to the language files. Sometimes an issue may occur when copying them over to the /custom folder, so it might be necessary to run this tool to copy them again.</span>
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>
                            <button type="button" class="input-button-form <?=$classLang?>" <?=$onclickLang?>><?=($classLang ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <th colspan="2" class="standard-tabletitle">Theme and Image folder</th>
        </tr>
        <tr>
            <th>&nbsp;
                
            </th>
            <td>
                Update theme folder (<i>custom/domain_<?=SELECTED_DOMAIN_ID?>/theme/</i>) and images folder (<i>custom/domain_<?=SELECTED_DOMAIN_ID?>/images/</i>)
                <span>The same issue that affects the language file might also affect the themes, causing some layout (css) inconsistency. Updating the theme folder will copy the content from the root level to the /custom</span>
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>
                <p class="informationMessage">Please, use with <b>extreme caution</b>. Be aware that this step will overwrite all files on these folders. <strong>All changes/customizations will be lost!</strong></p>
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>
                            <button type="button" class="input-button-form <?=$classTheme?>" <?=$onclickTheme?>><?=($classTheme ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <th colspan="2" class="standard-tabletitle">Cache Files</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>
                Clear Cache Files (<i>custom/domain_<?=SELECTED_DOMAIN_ID?>/cache_full/</i> and <i>custom/domain_<?=SELECTED_DOMAIN_ID?>/cache_partial/</i>)
                <span>This will remove all cache files and may solve some problems related to inconsistent informations on frontend.</span>
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>
                            <button type="button" class="input-button-form <?=$classCache?>" <?=$onclickCache?>><?=($classCache ? "Updated!" : "Clear")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <th colspan="2" class="standard-tabletitle">Sign In Options - Current Values:</th>
        </tr>
        <tr>
            <th>OpenID 2.0</th>
            <td>
                <?=$foreignaccount_openid ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?>
                <span>Turns this sign in option ON/FF.</span>
            </td>
        </tr>
        <tr>
            <th>Google Account</th>
            <td>
                <?=$foreignaccount_google ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?>
                <span>Turns this sign in option ON/FF.</span>
            </td>
        </tr>
        <tr>
            <th>Facebook</th>
            <td>
                <?=$foreignaccount_facebook ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?>
                <span>Turns this sign in option ON/FF.</span>
            </td>
        </tr>
        <tr>
            <th>Facebook App ID</th>
            <td><?=$foreignaccount_facebook_apiid?></td>
        </tr>
        <tr>
            <th>Facebook App Secret</th>
            <td><?=$foreignaccount_facebook_apisecret?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Sign In Options Values
                            <button type="button" class="input-button-form <?=$classsignIn?>" <?=$onclicksignIn?>><?=($classsignIn ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <th colspan="2" class="standard-tabletitle">Twitter Options - Current Values:</th>
        </tr>
        <tr>
            <th>API Key</th>
            <td><?=$foreignaccount_twitter_apikey?></td>
        </tr>
        <tr>
            <th>Secret Key</th>
            <td><?=$foreignaccount_twitter_apisecret?></td>
        </tr>
        <tr>
            <th>API Key (mobile)</th>
            <td><?=$foreignaccount_twitter_mobile_apikey?></td>
        </tr>
        <tr>
            <th>Secret Key (mobile)</th>
            <td><?=$foreignaccount_twitter_mobile_apisecret?></td>
        </tr>
        <tr>
            <th>Twitter Account</th>
            <td><?=$twitter_account?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Twitter Options Values
                            <button type="button" class="input-button-form <?=$classtwitter?>" <?=$onclicktwitter?>><?=($classtwitter ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <th colspan="2" class="standard-tabletitle">Facebook Comments Options - Current Values:</th>
        </tr>
        <tr>
            <th>Facebook Comments</th>
            <td><?=$commenting_fb ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>App ID</th>
            <td><?=$foreignaccount_facebook_apiid?></td>
        </tr>
        <tr>
            <th>User ID</th>
            <td><?=$fb_user_id?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Facebook Comments Options Values
                            <button type="button" class="input-button-form <?=$classfbComments?>" <?=$onclickfbComments?>><?=($classfbComments ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <th colspan="2" class="standard-tabletitle">Click to Call and Send to Phone - Current Values:</th>
        </tr>
        <tr>
            <th>Click to Call</th>
            <td><?=$twilio_enabled_call ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>Send to Phone</th>
            <td><?=$twilio_enabled_sms ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>Twilio Account SID</th>
            <td><?=$twilio_account_sid?></td>
        </tr>
        <tr>
            <th>Twilio Auth Token</th>
            <td><?=$twilio_auth_token?></td>
        </tr>
        <tr>
            <th>Twilio Number</th>
            <td><?=$twilio_number?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Twilio Options Values
                            <button type="button" class="input-button-form <?=$classtwilio?>" <?=$onclicktwilio?>><?=($classtwilio ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <th colspan="2" class="standard-tabletitle">Google Maps - Current Values:</th>
        </tr>
        <tr>
            <th>Maps</th>
            <td><?=$google_maps ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>Google Maps Key</th>
            <td><?=$google_maps_key?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Google Maps Options Values
                            <button type="button" class="input-button-form <?=$classgmaps?>" <?=$onclickgmaps?>><?=($classgmaps ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <th colspan="2" class="standard-tabletitle">Google Ads - Current Values:</th>
        </tr>
        <tr>
            <th>Ads</th>
            <td><?=$google_ad_status ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>Google Ads Client</th>
            <td><?=$google_ad_client?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Google Ads Options Values
                            <button type="button" class="input-button-form <?=$classgads?>" <?=$onclickgads?>><?=($classgads ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <th colspan="2" class="standard-tabletitle">Google Analytics - Current Values:</th>
        </tr>
        <tr>
            <th>Google Analytics Account</th>
            <td><?=$google_analytics_account?></td>
        </tr>
        <tr>
            <th>Front</th>
            <td><?=$google_analytics_front ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>Members</th>
            <td><?=$google_analytics_members ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>Sitemgr</th>
            <td><?=$google_analytics_sitemgr ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Google Analytics Options Values
                            <button type="button" class="input-button-form <?=$classganalytics?>" <?=$onclickganalytics?>><?=($classganalytics ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <th colspan="2" class="standard-tabletitle">Footer Links - Current Values:</th>
        </tr>
        <tr>
            <th>Facebook</th>
            <td><?=$setting_facebook_link?></td>
        </tr>
        <tr>
            <th>Linkedin</th>
            <td><?=$setting_linkedin_link?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Footer links
                            <button type="button" class="input-button-form <?=$classfooter?>" <?=$onclickfooter?>><?=($classfooter ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <th colspan="2" class="standard-tabletitle">Sitemgr General E-mail - Current Values:</th>
        </tr>
        <tr>
            <th>E-mail</th>
            <td><?=$sitemgr_email?></td>
        </tr>
        <tr>
            <th>Send notifications to the e-mail above</th>
            <td><?=$send_email ? "<strong style=\"color: green\">ON</strong>" : "<strong style=\"color: red\">OFF</strong>"?></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear Sitemgr General E-mail Options
                            <button type="button" class="input-button-form <?=$classsystemEmail?>" <?=$onclicksystemEmail?>><?=($classsystemEmail ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <th colspan="2" class="standard-tabletitle">E-Mail Sending Configuration - Current Values:</th>
        </tr>
        <tr>
            <th>E-mail Method</th>
            <td><?=$emailconf_method?></td>
        </tr>
        <tr>
            <th>E-mail Host</th>
            <td><?=$emailconf_host?></td>
        </tr>
        <tr>
            <th>E-mail Port</th>
            <td><?=$emailconf_port?></td>
        </tr>
        <tr>
            <th>E-mail Auth</th>
            <td><?=$emailconf_auth?></td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td><?=$emailconf_email?></td>
        </tr>
        <tr>
            <th>E-mail Username</th>
            <td><?=$emailconf_username?></td>
        </tr>
        <tr>
            <th>E-mail Password</th>
            <td><?=$emailconf_password?></td>
        </tr>

        <tr>
            <th>&nbsp;</th>
            <td class="alg-r">
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                    <tr>
                        <td>Clear E-Mail Sending Configuration Options
                            <button type="button" class="input-button-form <?=$classsmtpEmail?>" <?=$onclicksmtpEmail?>><?=($classsmtpEmail ? "Updated!" : "Reset Settings")?></button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>