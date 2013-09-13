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
    # * FILE: /layout/footer_menu.php
    # ----------------------------------------------------------------------------------------------------
?>

    <!-- The code below controls the 'Listings' section of the footer navigation. -->
    <li>
        <a href="<?=LISTING_DEFAULT_URL?>/">
            <?=system_showText(LANG_MENU_LISTING);?>
        </a>
    </li>

    <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
    <!-- The code below controls the 'Events' section of the footer navigation, if this module is available. -->
    <li>
        <a href="<?=EVENT_DEFAULT_URL?>/">
            <?=system_showText(LANG_MENU_EVENT);?>
        </a>
    </li>
    <? } ?>

    <? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
    <!-- The code below controls the 'Classifieds' section of the footer navigation, if this module is available. -->
    <li>
        <a href="<?=CLASSIFIED_DEFAULT_URL?>/">
            <?=system_showText(LANG_MENU_CLASSIFIED);?>
        </a>
    </li>
    <? } ?>

    <? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
    <!-- The code below controls the 'Articles' section of the footer navigation, if this module is available. -->
    <li>
        <a href="<?=ARTICLE_DEFAULT_URL?>/">
            <?=system_showText(LANG_MENU_ARTICLE);?>
        </a>
    </li>
    <? } ?>

    <? if (PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on" && CUSTOM_PROMOTION_FEATURE == "on") { ?>
    <!-- The code below controls the 'Deals' section of the footer navigation, if this module is available. -->
    <li>
        <a href="<?=PROMOTION_DEFAULT_URL?>/">
            <?=system_showText(LANG_MENU_PROMOTION);?>
        </a>
    </li>
    <? } ?>

    <? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
    <!-- The code below controls the 'Blog' section of the footer navigation, if this module is available. -->
    <li>
        <a href="<?=BLOG_DEFAULT_URL?>/">
            <?=system_showText(LANG_MENU_BLOG);?>
        </a>
    </li>
    <? } ?>