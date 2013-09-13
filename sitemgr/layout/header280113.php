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
	# * FILE: /sitemgr/layout/header.php
	# ----------------------------------------------------------------------------------------------------

	header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);

	header("Accept-Encoding: gzip, deflate");
	header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check", FALSE);
	header("Pragma: no-cache");
    
    setting_get("phpMailer_error", $phpMailer_error);
    setting_get("sitemgr_language", $sitemgr_language);
    $blockMenuTodo = todo_validatePage(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>

		<?
		customtext_get("header_title", $headertag_title);
		$headertag_title = (($headertag_title) ? ($headertag_title) : (EDIRECTORY_TITLE));
        $checkIE = is_ie(false, $ieVersion);
        if (string_strpos($_SERVER["PHP_SELF"], "content/navigation.php") !== false && $checkIE && $ieVersion == 9) {
            $loadNewJquery = true;
        } else {
            $loadNewJquery = false;
        }
		?>

		<title><?= ((string_strpos($_SERVER["PHP_SELF"], "registration.php")) ? '' : system_showText(LANG_SITEMGR_HOME_WELCOME). " - ") . $headertag_title?></title>

		<meta name="author" content="Arca Solutions" />

		<meta http-equiv="Content-Type" content="text/html; charset=<?=EDIR_CHARSET;?>" />

		<meta name="ROBOTS" content="noindex, nofollow" />

		<? if ($facebookScript) {
			echo Facebook::getMetaTags("admins", FACEBOOK_USER_ID);
			echo Facebook::getMetaTags("app_id", FACEBOOK_API_ID);
		} ?>

		<?=system_getNoImageStyle($cssfile = true);?>
        
        <?=system_getFavicon();?>

        <? /* JQUERY FANCYBOX STYLE*/?>
        <link rel="stylesheet" href="<?=DEFAULT_URL?>/scripts/jquery/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="all" />
		<? /* JQUERY Jcrop STYLE */?>
        <link rel="stylesheet" href="<?=DEFAULT_URL?>/scripts/jquery/jcrop/css/jquery.Jcrop.css" type="text/css" />
        <? /* GENERAL STYLE */?>
        <link href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/layout/general_sitemgr.css" rel="stylesheet" type="text/css" />
		<? /* LOGIN & FORGOT STYLE*/?>
		<? if ((string_strpos($_SERVER["PHP_SELF"], "/login.php") !== false) || (string_strpos($_SERVER["PHP_SELF"], "/".SITEMGR_ALIAS."/forgot.php") !== false)) { ?>
			<link href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/layout/login.css" rel="stylesheet" type="text/css" />
		<? } ?>
        <? /* JQUERY UI SMOOTHNESS STYLE */?>
        <link type="text/css" href="<?=DEFAULT_URL?>/scripts/jquery/jquery_ui/css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
		<? /* JQUERY AUTO COMPLETE STYLE  */?>
		<link type="text/css" href="<?=DEFAULT_URL?>/scripts/jquery/jquery.autocomplete.css" rel="stylesheet" media="all" />

        <script type="text/javascript">
		<!--
		DEFAULT_URL = "<?=DEFAULT_URL?>";
        SITEMGR_ALIAS = "<?=SITEMGR_ALIAS?>";
		-->
		</script>

        <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/common.js"></script>
		<script type="text/javascript" src="<?=language_getFilePath($sitemgr_language, true);?>"></script>
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/location.js"></script>
        
        <? if (!$loadNewJquery) { ?>
            <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery.js"></script>
        <? } else { ?>
        <? /*Loading the New Version of jQuery and UI just to navigation configuration work fine in IE9*/ ?>
            <link type="text/css" href="<?=DEFAULT_URL?>/scripts/jquery/jquery.1.5.2/jquery.ui.css" rel="stylesheet" />
            <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jquery.1.5.2/jquery.js"></script>
            <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jquery.1.5.2/jquery.ui.js"></script>
        <? } ?>
            
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/ajax-search.js"></script>
        <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jcrop/js/jquery.Jcrop.js"></script>
        <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/fancybox/jquery.fancybox-1.3.4.js"></script>
        
        <? if (!$loadNewJquery) { ?>
            <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jquery_ui/js/jquery-ui-1.7.2.custom.min.js"></script>
        <? } ?>
        
        <? if (EDIR_LANGUAGE != "en_us") { ?>
            <? /* DATA PICKER TRANSLATION */?>
            <script type="text/javascript" src="<?=language_getDatePickPath($sitemgr_language);?>"></script>
        <? } ?>
            
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jquery.maskedinput-1.3.min.js"></script>
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jquery.textareaCounter.plugin.js"></script>
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jquery.hoverIntent.js"></script>
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/jquery.cookie.min.js"></script>
        
		<? if (is_ie(true)) { ?>
            <!--fix ie6 z-index bug -->
            <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/bgiframe/jquery.bgiframe.js"></script>
            <script type="text/javascript" charset="utf-8">
                $(function() {
                    $('#userAgent').html(navigator.userAgent);
                });
            </script>
            <!-- endfix ie6 z-index bug-->
        <? } ?>
            
		<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/bulkupdate.js"></script>
        <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/domain.js"></script>
		
		<? if (string_strpos($_SERVER["PHP_SELF"], "colorscheme") !== false){ ?>
		
			<link rel="stylesheet" href="<?=DEFAULT_URL?>/scripts/jquery/colorpicker/css/colorpicker.css" type="text/css" />
			<link rel="stylesheet" href="<?=DEFAULT_URL?>/scripts/jquery/colorpicker/css/layout.css" type="text/css" />

			<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/colorpicker/colorpicker.js"></script>
			<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/colorpicker/eye.js"></script>
			<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/colorpicker/utils.js"></script>
			<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/jquery/colorpicker/layout.js?ver=1.0.2"></script>

		<? } ?>
            
        <? if (string_strpos($_SERVER["PHP_SELF"], "htmleditor") !== false){ ?>
            
            <script language="javascript" type="text/javascript" src="<?=DEFAULT_URL?>/scripts/editarea/edit_area/edit_area_full.js"></script>
        
        <? } ?>
            
        <? if (string_strpos($_SERVER["PHP_SELF"], "import/index.php") !== false) { ?>
            <script type="text/javascript" src="<?=DEFAULT_URL;?>/scripts/jquery/jquery.csv2table.js"></script>
        <? } ?>
            
        <? if (string_strpos($_SERVER["PHP_SELF"], "getstarted.php") !== false) { ?>
            <link rel="stylesheet" href="<?=DEFAULT_URL?>/scripts/jquery/progressbar/jquery.progressbar.css" type="text/css" />
            <script type="text/javascript" src="<?=DEFAULT_URL;?>/scripts/jquery/progressbar/jquery.progressbar.js"></script>
        <? } ?>
			
		
		<? //Clear Searchs ?>
		<script type="text/javascript">
			var show = false;

			function searchResetSitemgr(form) {
				tot = form.elements.length;
				for (i=0;i<tot;i++) {
					if (form.elements[i].type == 'text') {
						form.elements[i].value = "";
					} else if (form.elements[i].type == 'checkbox' || form.elements[i].type == 'radio') {
						form.elements[i].checked = false;
					} else if (form.elements[i].type == 'select-one') {
						form.elements[i].selectedIndex = 0;
					}
				}
			}

			function validateQuickSearch() {
				if ($('#QS_searchFor').val() == 'All') {
					if (($('#QS_keywords').val() == '')||($('#QS_keywords').val() == "<?=string_ucwords(system_showText(LANG_SITEMGR_SEARCH))?>")) {
                        fancy_alert('<?=system_showText(LANG_SITEMGR_SEARCH_FIELDS_EMPTY);?>', 'errorMessage', false, 450, 100, false);
                        return false;
					}
				}
				return true;
			}
            
            function searchSubmit () {
                if (validateQuickSearch()) {
                    if ($('#QS_keywords').val() == "<?=string_ucwords(system_showText(LANG_SITEMGR_SEARCH))?>"){
                        $("#QS_keywords").attr('value', '');
                    }
                    document.getElementById('formSearchHome').submit();
                }
            }
			
			function mainmenu(){
				$(" #topMainNav ul:first ").css({display: "none"});
				$(" #topMainNav .topMenu").hoverIntent(function(){
					if($(this).hasClass('accounts')){
						$(this).find('ul:first').css("right", "0px");
					} else if($(this).hasClass('domains')){
						$(this).find('ul:first').css("right", "0px");
					} else if($(this).hasClass('support')){
						$(this).find('ul:first').css("right", "0px");
					}

					$(this).find('a').addClass("header-topMainNavbar-Active");
					$(this).find('ul:first').css({visibility: "visible", display: "none"}).fadeIn(200);
					//fix ie6 z-index bug
					if ($.browser.msie && $.browser.version == 6){
						$(this).find('ul:first').bgiframe();
					}
				},function(){
					$(this).find('ul:first').css({visibility: "hidden"});
					$(this).find('a').removeClass("header-topMainNavbar-Active");
				});
			}
		
			function addClass(item) {
				$("#privateMenu_"+item).addClass('submenu_active');
			}

			function addClassMainHorizontalMenu(item) {
				$("#"+item).addClass('header-topMainNavbarActive');
			}

			$(document).ready(function(){
                
                mainmenu();
                
                $("#all-languages-button").hover(function() {
                    $('.all-languages').slideDown('slow');
                }, function() {
                    $('.all-languages').slideUp('slow');
                });
                
				$("#QS_keywords").focus(function() {
					$("#QS_keywords").attr('value', '');
				});

				$("#QS_keywords").blur(function() {
					if (!$("#QS_keywords").val())
						$("#QS_keywords").attr('value', '<?=string_ucwords(system_showText(LANG_SITEMGR_SEARCH))?>');
				});

				$("#searchLink").click(function () {
					if (show == false) {
						$("#searchAll").fadeIn('slow');
						show = true;
					} else {
						$("#searchAll").fadeOut('slow');
						show = false;
					}
				});
                
                $("a.fancy_window_feedback").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'autoDimensions'        : false,
                    'width'                 : 400,
                    'height'                : 470
                });
              
                $("a.fancy_window").fancybox({
                    'hideOnContentClick'	: false,
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'frameWidth'			: 560,
                    'frameHeight'			: 550
                });
                
                $("a.fancy_window_about").fancybox({
                    'hideOnContentClick'	: false,
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 595,
                    'height'                : 570,
                    'autoDimensions': false,
                    'autoScale': false
                });
                
                $("a.fancy_window_small").fancybox({
                    'hideOnContentClick'	: false,
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 550,
                    'height'                : 150
                });
                
                $("a.fancy_window_categPath").fancybox({
                    'hideOnContentClick'	: false,
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 300,
                    'height'                : 100
                });
                                              
                $("a.fancy_window_preview").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : <?=FANCYBOX_ITEM_PREVIEW_WIDTH?>,
                    'height'                : <?=FANCYBOX_ITEM_PREVIEW_HEIGHT?>
                });
                
                $("a.fancy_window_preview_small").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 800,
                    'height'                : 400
                });
                
                $("a.fancy_window_preview_banner").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 800,
                    'height'                : 210
                });
                
                $("a.fancy_window_custom").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 620,
                    'height'                : 370
                });
                
                $("a.fancy_window_invoice").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 680,
                    'height'                : 480
                });
                
                 $("a.fancy_window_navigation").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : <?=FANCYBOX_NAVIGATIONCONFIG_WIDTH?>,
                    'height'                : <?=FANCYBOX_NAVIGATIONCONFIG_HEIGHT?>
                });
                
                $("a.fancy_window_auto").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'autoDimensions'        : true
                });
                               
                $("a.fancy_window_htmleditor").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 780,
                    'height'                : 550
                });
                
                $("a.fancy_window_htmleditor2").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 400,
                    'height'                : 230,
                    'modal'                 : true
                });
                
                $("a.fancy_window_phpMailer").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 400,
                    'height'                : 230
                });

                $("a.fancy_window_cronlog").fancybox({
                    'overlayShow'			: true,
                    'overlayOpacity'		: 0.75,
                    'width'                 : 500,
                    'height'                : 330
                });
                
                <? if ($phpMailer_error && !DEMO_LIVE_MODE && string_strpos($_SERVER["PHP_SELF"], "/prefs/emailconfig.php") === false && string_strpos($_SERVER["PHP_SELF"], "/support/") === false && string_strpos($_SERVER["PHP_SELF"], "/registration.php") === false) { ?>
                    $("#phpMailer_window").attr("href", '<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/configMail.php');
                    $("#phpMailer_window").trigger("click");
                <? } ?>
               
			});
		</script>
        
        <? include(EDIRECTORY_ROOT."/includes/code/ie6pngfix.php"); ?>
	</head>

	<body>

	<?
	/** Float Layer *******************************************************************/
	$lang_layer = 1;
	$sitemgr = true;
	include(INCLUDES_DIR."/views/view_float_layer.php");
	/**********************************************************************************/

	/*
	 * Get Domains
	 */
	$domainDropDown = domain_getDropDown(DEFAULT_URL, $_SERVER["REQUEST_URI"], $_SERVER["QUERY_STRING"], SELECTED_DOMAIN_ID);
	?>
    <? if (is_ie(true)) { ?>
        <div class="browserMessage">
            <div class="wrapper">
				<?=system_showText(LANG_IE6_WARNING);?>
            </div>
        </div>
    <? } ?>
    
    <div class="site-content">
        
		<div class="wrapper">

			<div class="header">

                <div class="header-backdrop">

                    <div class="header-box">

                        <div class="logo">
                            <a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/index.php" class="logoLink" target="_parent" title="<?=EDIRECTORY_TITLE?>" <?=system_getHeaderLogo(true);?>>
                                <?="eDirectory"?>
                            </a>
                        </div>

                        <? if (string_strpos($_SERVER["PHP_SELF"], "registration.php") === false) { ?>
                            <? if ($_SESSION[SM_LOGGEDIN] == true) { ?>
                            <ul class="headerNav">
                                    <li class="headerNavTitle"><h2><?=system_showText(LANG_SITEMGR_OPTIONS);?></h2></li>
                                    <li><a href="<?=NON_SECURE_URL?>/"><?=system_showText(LANG_SITEMGR_VIEW_SITE)?></a></li>
                                    <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/manageaccount.php"><?=system_showText(LANG_SITEMGR_MENU_MYACCOUNT)?></a></li>
                                    <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/logout.php"><?=system_showText(LANG_SITEMGR_MENU_LOGOUT)?></a></li>
                            </ul>
                            <? } ?>
                        <? } ?>

                        <? if (!$_SESSION[SM_LOGGEDIN]) { ?>
                            <h1 class="standardTitle"><?=string_ucwords(system_showText(LANG_SITEMGR_SITE_SIGNIN))?></h1>
                        <? } ?>

                        <? if ($_SESSION[SM_LOGGEDIN] && string_strpos($_SERVER["PHP_SELF"], "registration.php") === false) { ?>
                            <h1 class="standardTitle"><?=LANG_SITEMGR_MANAGEMENT?></h1>
                        <? } ?>
                    </div>

                </div>

                <div class="header-nav">
                    <div class="header-nav-box">
       
                        <? if (sess_isSitemgrLogged() && (string_strpos($_SERVER["PHP_SELF"], "registration.php") === false)) { ?>

                            <ul class="header-topMainNavbar" id="topMainNav">
                              
                                <? if ($_SESSION["is_arcalogin"]) { ?>
                                    <li class="configChecker topMenu">
                                        <a id="MHMconfigChecker" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/index.php">Config Checker</a>
                                    </li>
                                <? } ?>
                              
                                <? if (!$blockMenuTodo) { ?>
    
                                    <li class="dashboard topMenu">
                                        <a id="MHMdashboard" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/"><?=system_showText(LANG_SITEMGR_DASHBOARD);?></a>
                                    </li>
    
    
                                    <? if (permission_hasSMPermSection(SITEMGR_PERMISSION_ACCOUNTS)) { ?>
                                        <li class="accounts topMenu">
                                            <a id="MHMaccounts" href="javascript:void(0);"><?=system_showText(LANG_SITEMGR_NAVBAR_ACCOUNTS)?></a>
                                            <ul style="visibility: hidden;" class="header-topMainNavbar-sub header-topMainNavbar-subTwoColumn">
                                                <li class="topMainNavbarTitle">
                                                    <p><?=(SOCIALNETWORK_FEATURE == "on" ? system_showText(LANG_SITEMGR_LABEL_SPONSOR) : system_showText(LANG_SITEMGR_SPONSORACCOUNTS));?></p>
                                                    <ul>
                                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/account/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?></a></li>
                                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/account/account.php"><?=system_showText(LANG_SITEMGR_ADD);?></a></li>
                                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/account/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?></a></li>
                                                    </ul>
                                                </li>

                                                <li class="topMainNavbarTitle">
                                                    <p><?=system_showText(LANG_SITEMGR_NAVBAR_SITEMGRACCOUNTS);?></p>
                                                    <ul>
                                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/smaccount/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?></a></li>
                                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/smaccount/smaccount.php"><?=system_showText(LANG_SITEMGR_ADD);?></a></li>
                                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/smaccount/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?></a></li>
                                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/manageaccount.php"><?=system_showText(LANG_SITEMGR_MENU_MYACCOUNT)?></a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    <? } ?>

                                    <? if (permission_hasSMPermSection(SITEMGR_PERMISSION_DOMAIN)) { ?>
                                        <li class="domains topMenu"><a id="MHMdomains" href="javascript:void(0);"><?=system_showText(LANG_SITEMGR_NAVBAR_DOMAIN_PLURAL);?></a>
                                            <ul style="visibility: hidden;" class="header-topMainNavbar-sub">
                                                <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/domain/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?></a></li>
                                                <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/domain/domain.php"><?=system_showText(LANG_SITEMGR_ADD);?></a></li>
                                            </ul>
                                        </li>
                                    <? } ?>
                                
                                <? } ?>

                                <li class="support topMenu"><a id="MHMsuport" href="javascript:void(0);"><?=system_showText(LANG_SITEMGR_SUPPORT)?></a>
                                    <ul style="visibility: hidden;" class="header-topMainNavbar-sub">
                                        <li><a href="http://support.edirectory.com/" target="_blank"><?=system_showText(LANG_SITEMGR_EDIRECTORYMANUAL)?></a></li>
                                        <li><a class="iframe fancy_window_feedback" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/feedback.php"><?=system_showText(LANG_SITEMGR_FEEDBACK)?></a></li>
                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/faq/faq.php"><?=system_showText(LANG_SITEMGR_MENU_FAQ)?></a></li>
                                        <? if (!$blockMenuTodo) { ?>
                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/sitemap.php"><?=system_showText(LANG_SITEMGR_LABEL_SITEMAP)?></a></li>
                                        <? } ?>
                                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/about.php" class="fancy_window_about"><?=system_showText(LANG_SITEMGR_MENU_ABOUT)?></a></li>
                                    </ul>
                                </li>
                            </ul>
    
                          <? } ?>

						  <?
                          $activeMenuAccounts = false;
                          $activeMenuDomains = false;
                          $activeMenuSuport = false;
                          $activeMenuDasboard = (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/index.php") || string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/dashboard.php"));
    
                          if (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/account")) $activeMenuAccounts = true;
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/account/account.php")) $activeMenuAccounts = true;
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/account/search.php")) $activeMenuAccounts = true;
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/smaccount")) $activeMenuAccounts = true;
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/smaccount/smaccount.php")) $activeMenuAccounts = true;
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/smaccount/search.php")) $activeMenuAccounts = true;
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/manageaccount.php")) $activeMenuAccounts = true;
    
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/domain")) $activeMenuDomains = true;
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/domain/domain.php")) $activeMenuDomains = true;
    
                          elseif (string_strpos($_SERVER["PHP_SELF"], "faq/faq.php")) $activeMenuSuport = true;
                          elseif (string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/sitemap.php")) $activeMenuSuport = true;
                          ?>
    
                          <? if ($activeMenuDasboard) { ?> <script type="text/javascript"> addClassMainHorizontalMenu('MHMdashboard'); </script><? } ?>
                          <? if ($activeMenuAccounts) { ?> <script type="text/javascript"> addClassMainHorizontalMenu('MHMaccounts'); </script><? } ?>
                          <? if ($activeMenuDomains) { ?> <script type="text/javascript"> addClassMainHorizontalMenu('MHMdomains'); </script><? } ?>
                          <? if ($activeMenuSuport) { ?> <script type="text/javascript"> addClassMainHorizontalMenu('MHMsuport'); </script><? } ?>
    
                          <?
                          $url_header = $_SERVER["PHP_SELF"];
                          $url_header = string_substr ($url_header, string_strlen ($url_header)-18, 18 );
                          ?>
                	</div>
                    
                    <a href="#" id="phpMailer_window" class="iframe fancy_window_phpMailer" style="display:none" title=""></a>
            	</div>
			</div>
            
			<span class="clear"></span>
            
			<div class="content">