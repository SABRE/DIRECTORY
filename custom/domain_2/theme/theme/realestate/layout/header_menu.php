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
    # * FILE: /theme/realestate/layout/header_menu.php
    # ----------------------------------------------------------------------------------------------------
?>

    <!-- The code below controls the 'Home' section of the navigation. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
    <li <?=($activeMenuHome ? "class=\"menuActived\"" : "")?>>
        <a href="<?=NON_SECURE_URL?>">
            <?=system_showText(LANG_MENU_HOME);?>
        </a>
    </li>

    <!-- The code below controls the 'Listings' section of the navigation. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
    <li <?=((ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) ? "class=\"menuActived\"" : "")?>>
        <a href="<?=LISTING_DEFAULT_URL?>/">
            <?=system_showText(LANG_MENU_LISTING);?>
        </a>
    </li>

    <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
        <!-- The code below controls the 'Events' section of the navigation, if this module is available. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
        <li <?=((ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) ? "class=\"menuActived\"" : "")?>>
            <a href="<?=EVENT_DEFAULT_URL?>/">
                <?=system_showText(LANG_MENU_EVENT);?>
            </a>
        </li>
    <? } ?>

    <? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
        <!-- The code below controls the 'Classifieds' section of the navigation, if this module is available. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
        <li <?=((ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) ? "class=\"menuActived\"" : "")?>>
            <a href="<?=CLASSIFIED_DEFAULT_URL?>/">
                <?=system_showText(LANG_MENU_CLASSIFIED);?>
            </a>
        </li>
    <? } ?>

    <? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
        <!-- The code below controls the 'Articles' section of the navigation, if this module is available. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
        <li <?=((ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) ? "class=\"menuActived\"" : "")?>>
            <a href="<?=ARTICLE_DEFAULT_URL?>/">
                <?=system_showText(LANG_MENU_ARTICLE);?>
            </a>
        </li>
    <? } ?>

    <? if (PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on" && CUSTOM_PROMOTION_FEATURE == "on") { ?>
        <!-- The code below controls the 'Deals' section of the navigation, if this module is available. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
        <li <?=((ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) ? "class=\"menuActived\"" : "")?>>
            <a href="<?=PROMOTION_DEFAULT_URL?>/">
                <?=system_showText(LANG_MENU_PROMOTION);?>
            </a>
        </li>
    <? } ?>

    <? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
        <!-- The code below controls the 'Blog' section of the navigation, if this module is available. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
        <li <?=((ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) ? "class=\"menuActived\"" : "")?>>
            <a href="<?=BLOG_DEFAULT_URL?>/">
                <?=system_showText(LANG_MENU_BLOG);?>
            </a>
        </li>
    <? } ?>

    <!-- The code below controls the 'Advertise With Us' section of the navigation. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
    <li <?=((strpos($_SERVER["REQUEST_URI"], "/".ALIAS_ADVERTISE_URL_DIVISOR.".php") !== false) ? "class=\"menuActived\"" : "")?>>
        <a href="<?=NON_SECURE_URL?>/<?=ALIAS_ADVERTISE_URL_DIVISOR?>.php">
            <?=system_showText(LANG_MENU_ADVERTISE);?>
        </a>
    </li>

    <!-- The code below controls the 'Contact Us' section of the navigation. To change the word used, it's better to edit the language file corresponding to your language, for example EN_US.php. -->
    <li <?=((strpos($_SERVER["REQUEST_URI"], "/".ALIAS_CONTACTUS_URL_DIVISOR.".php") !== false) ? "class=\"menuActived\"" : "")?>>
        <a href="<?=NON_SECURE_URL?>/<?=ALIAS_CONTACTUS_URL_DIVISOR?>.php">
            <?=system_showText(LANG_MENU_CONTACT);?>
        </a>
    </li>