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
    # * FILE: /layout/header.php
    # ----------------------------------------------------------------------------------------------------

    header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", FALSE);
    header("Pragma: no-cache");
    header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);

    //This function returns the variables to fill in the meta tags content below. Do not change this line.
    front_getHeaderTag($headertag_title, $headertag_author, $headertag_description, $headertag_keywords);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="en" lang="en">

    <head>

        <title><?=$headertag_title?></title>
        <meta name="author" content="<?=$headertag_author?>" />
        <meta name="description" content="<?=$headertag_description?>" />
        <meta name="keywords" content="<?=$headertag_keywords?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=<?=EDIR_CHARSET;?>" />
        
        <!-- This function returns the favicon tag. Do not change this line. -->
        <?=system_getFavicon();?>

        <!-- This function returns the search engine meta tags. Do not change this line. -->
        <?=front_searchMetaTag();?>

        <meta name="ROBOTS" content="index, follow" />

        <!-- This function includes all css files. Do not change this line. -->
        <!-- To change any style, it's better to edit the stylesheet files. -->
        <? front_themeFiles(); ?>

        <!-- This function returns the Default Image style. Do not change this line. -->
        <?=system_getNoImageStyle($cssfile = true);?>

        <!-- This function reads and includes all js and css files (minimized). Do not change this line. -->
        <? script_loader($js_fileLoader, $pag_content, $aux_module_per_page, $id, $aux_show_twitter); ?>
        
    </head>

    <body>
	
        <!-- This div is used for the share box, on results and detail pages. Do not change this line.  -->
        <div id="div_to_share" class="share-box" style="display: none"></div>

        <!-- Top navbar options  -->
        <?=front_navbarOptions(false);?>
        <?=front_navbarOptions(true);?>

        <!-- This function returns the code warning users to upgrade their browser if they are using Internet Explorer 6. Do not change this line.  -->
        <? front_includeFile("IE6alert.php", "layout", $js_fileLoader); ?>

        <!-- This function returns the top navbar code. Do not change this line.  -->
        <? front_includeFile("usernavbar.php", "layout", $js_fileLoader); ?>
               
        <div id="header-wrapper">
            	
            <div id="header">
		
                <!-- The code below returns the logo. -->
                <!-- The function "system_getHeaderLogo()" returns a inline style, like style="background-image: url(YOUR LOGO URL HERE)" -->
                <h1 class="logo">
                    <a id="logo-link" href="<?=NON_SECURE_URL?>" target="_parent" title="<?=EDIRECTORY_TITLE?>" <?=system_getHeaderLogo();?>>
                        <?=EDIRECTORY_TITLE?>
                    </a>
                </h1>

                <!-- This function returns the Top Banner, if it is available. Do not change this line.  -->
                <? front_includeBanner($category_id, $banner_section); ?>

                <!-- This function returns the Search Box code. Do not change this line.  -->
                <? front_includeSearch($hide_search, $browsebylocation, $browsebycategory, $keyword, $where, $screen, $letter, $js_fileLoader); ?>
                			
            </div>
			
        </div>
		
		<div id="navbar-wrapper">
		<div id="centerNavbar">
		<div id="leftNavbar"></div>
		<div class="Navbar">		
            <!-- You can add your own custom links below by adding a pair of <li></li> tags within the <ul> parent, place any content within. -->
            <!-- Do not change the if clause inside the <li> tags below. This clause controls when the menu shows as active or not. -->
		
            <ul id="navbar">
                <? include(system_getFrontendPath("header_menu.php", "layout")); ?>
            </ul>
		</div>
		<div id="rightNavbar"></div>
		</div>
		
        </div>

        <!-- DIV for the main content. This DIV is closed in the file footer.php -->
        <div class="content-wrapper">