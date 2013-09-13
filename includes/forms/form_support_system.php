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
	# * FILE: /includes/forms/form_support_system.php
	# ----------------------------------------------------------------------------------------------------

?>
    <p class="informationMessage">eDirectory Version: <strong><?=VERSION?></strong></p>
    <br class="clear" />

    <table cellpadding="0" cellspacing="0" border="0" class="standard-table">
        <tr>
            <th colspan="2" class="standard-tabletitle">Current folder permissions <span>The system needs to be able to read and write files as the web user under the /bin and /custom folders. If these permissions are not set properly, the activation of eDirectory will NOT work.</span></th>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class="standard-table" style="margin-top:-10px">
        <tr>
            <th>Required Permission for both folders</th>
            <td>
                <?=$rightPerm?>
                <span>Having "<?=$rightPerm?>" as permission, means a file can be read and written by any user.</span>
            </td>
        </tr>
        <tr>
            <th>/custom</th>
            <td <?=$styleCustom?>>
                <?=$customPerm?>
                <span>Current permission for the /custom folder and its contents. This folder holds all the modifiable files such as uploaded files, themes, etc.</span>
            </td>
        </tr>
        <tr>
            <th>/bin</th>
            <td <?=$styleBin?>>
                <?=$binPerm?>
                <span>Current permission for the /bin folder and its contents. This folder holds the binary files required by our activation system validation.</span>
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" border="0" class="standard-table">
        <tr>
            <th colspan="2" class="standard-tabletitle">.htaccess Files <span>These files are responsible for most of the rewrites we have, such as friendly URLs, language URLs, etc.</span></th>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class="standard-table" style="margin-top:-10px">        
        <? if (count($arrayHtaccesMissing)) { ?>
            <tr>
                <th style="color: red; font-weight:bold;">Missing</th>
                <td><?=$arrayHtaccesMissing[0]?></td>
            </tr>
            <?
             array_shift($arrayHtaccesMissing);
            if (is_array($arrayHtaccesMissing) && $arrayHtaccesMissing[0]) { ?>
                <? foreach ($arrayHtaccesMissing as $htFile) { ?>
                <tr>
                    <th>&nbsp;</th>
                    <td><?=$htFile?></td>
                </tr>
                <? } ?>
            <? } ?>
            
        <? } else { ?>
            <tr>
                <th>&nbsp;</th>
                <td style="color: green">OK</td>
            </tr>
        <? } ?>
        
    </table>
    
    <table cellpadding="0" cellspacing="0" border="0" class="standard-table">
        <tr>
            <th colspan="2" class="standard-tabletitle">reg.bin <span>Part of the directory activation information relies on this binary file. The system needs to be able to read it.</span></th>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class="standard-table" style="margin-top:-10px">        
        <tr>
            <th style="color: red; font-weight:bold;">&nbsp;</th>
            <td>
                <?
                if (is_executable(BIN_PATH."/".BIN_SERVERTYPE."/reg.bin")) {
                    echo "<label style=\"color: green\">OK</label>";
                } else {
                    echo "<label style=\"color: red\">reg.bin is not executable.</label>";
                }
                ?>
            </td>
        </tr>
    </table>

    <form name="configChecker" id="configChecker" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">
                
        <input type="hidden" id="rewriteFile" name="rewriteFile" value="" />
        
        <table cellpadding="0" cellspacing="0" border="0" class="standard-table">
            <tr>
                <th colspan="2" class="standard-tabletitle">Time zone</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td>
                    <?=$timeZoneDropdown?>
                    <span>You can change the time zone for each domain. Choose the first option to restore the default time zone value.</span>
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td class="alg-r">
                    <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                        <tr>
                            <td>
                                <button type="button" class="input-button-form" onclick="JS_submit('timezone');">Save Settings</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr>
                <th colspan="2" class="standard-tabletitle">Default Search</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td>
                    <?=$defaultSearchDropdown?>
                    <span>You can change the default search behavior for each domain.</span>
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td class="alg-r">
                    <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                        <tr>
                            <td>
                                <button type="button" class="input-button-form" onclick="JS_submit('defaultsearch');">Save Settings</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr>
                <th colspan="2" class="standard-tabletitle">Constants (custom/domain_<?=SELECTED_DOMAIN_ID?>/conf/constants.inc.php)</th>
            </tr>
            <tr>
                <th>Cache Full</th>
                <td>
                    <input type="checkbox" name="const_cache_full" value="y" <? if (CACHE_FULL_FEATURE == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>The preferred caching mode – It will cache the page as a whole – a single static HTML file per page is generated.</span>
                </td>
            </tr>
            <tr>
                <th>Cache Partial</th>
                <td>
                    <input type="checkbox" name="const_cache_partial" value="y" <? if (CACHE_PARTIAL_FEATURE == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>This mode will divide the page in blocks and cache them individually. Not stable.</span>
                </td>
            </tr>
            <tr>
                <th>Front Search with boolean mode</th>
                <td>
                    <input type="checkbox" name="const_front_search" value="y" <? if (SEARCH_FORCE_BOOLEANMODE == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>Turn on this constant to use regExp in the front search. This can make the search more accurate in some cases.</span>
                </td>
            </tr>
            <tr>
                <th>Gallery - Free ratio</th>
                <td>
                    <input type="checkbox" name="const_free_ratio" value="y" <? if (GALLERY_FREE_RATIO == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>Turn on this constant to remove the crop for wide images. <strong>ATTENTION! The thumb preview in the upload window will not be shown when this constant is turned on.</strong></span>
                </td>
            </tr>
            <tr>
                <th>Save jpg as png</th>
                <td>
                    <input type="checkbox" name="const_jpg_as_png" value="y" <? if (FORCE_SAVE_JPG_AS_PNG == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>Turn on this constant to save jpg image as png. <strong>ATTENTION! This provides better quality images, but image files size will be larger.</strong></span>
                </td>
            </tr>
            <tr>
                <th>GeoIP</th>
                <td>
                    <input type="checkbox" name="const_geoip" value="y" <? if (GEOIP_FEATURE == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>Turn on this constant to enable the GeoIP feature.</span>
                </td>
            </tr>

            <tr>
                <th>&nbsp;</th>
                <td class="alg-r">
                    <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                        <tr>
                            <td>
                                <button type="button" class="input-button-form" onclick="JS_submit('constants');">Save Settings</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <th colspan="2" class="standard-tabletitle">Scalability (custom/domain_<?=SELECTED_DOMAIN_ID?>/conf/scalability.inc.php)</th>
            </tr>
            <tr>
                <th>Listing Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_listing" value="y" <? if (LISTING_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 100.000 listings and/or more than 50.000 listings on the highest level</span>
                </td>
            </tr>
            <tr>
                <th>Promotion Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_promotion" value="y" <? if (PROMOTION_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 50.000 promotions</span>
                </td>
            </tr>
            <tr>
                <th>Promotion Auto Complete (Front)</th>
                <td>
                    <input type="checkbox" name="scalability_promotion_autocomplete" value="y" <? if (PROMOTION_SCALABILITY_USE_AUTOCOMPLETE == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn <strong>OFF</strong> if edirectory has more than 50.000 promotions</span>
                </td>
            </tr>
            <tr>
                <th>Event Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_event" value="y" <? if (EVENT_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 100.000 events and/or more than 50.000 events on the highest level</span>
                </td>
            </tr>
            <tr>
                <th>Banner Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_banner" value="y" <? if (BANNER_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 50.000 banners</span>
                </td>
            </tr>
            <tr>
                <th>Classified Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_classified" value="y" <? if (CLASSIFIED_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 100.000 classifieds and/or more than 50.000 classifieds on the highest level</span>
                </td>
            </tr>
            <tr>
                <th>Article Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_article" value="y" <? if (ARTICLE_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 100.000 articles and/or more than 50.000 articles on the highest level</span>
                </td>
            </tr>
            <tr>
                <th>Blog Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_blog" value="y" <? if (BLOG_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 100.000 posts</span>
                </td>
            </tr>
            <tr>
                <th>Listing Category Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_listingcateg" value="y" <? if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 20 main listing categories</span>
                </td>
            </tr>
            <tr>
                <th>Event Category Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_eventcateg" value="y" <? if (EVENTCATEGORY_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 20 main event categories</span>
                </td>
            </tr>
            <tr>
                <th>Classified Category Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_classifiedcateg" value="y" <? if (CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 20 main classified categories</span>
                </td>
            </tr>
            <tr>
                <th>Article Category Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_articlecateg" value="y" <? if (ARTICLECATEGORY_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 20 main article categories</span>
                </td>
            </tr>
            <tr>
                <th>Blog Category Scalability</th>
                <td>
                    <input type="checkbox" name="scalability_blogcateg" value="y" <? if (BLOGCATEGORY_SCALABILITY_OPTIMIZATION == "on") { echo "checked"; } ?> class="inputCheck" />
                    <span>suggestion: turn on if edirectory has more than 20 main blog categories</span>
                </td>
            </tr>

            <tr>
                <th>&nbsp;</th>
                <td class="alg-r">
                    <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                        <tr>
                            <td>
                                <button type="button" class="input-button-form" onclick="JS_submit('scalability');">Save Settings</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </form>