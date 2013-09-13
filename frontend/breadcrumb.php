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
	# * FILE: /frontend/breadcrumb.php
	# ----------------------------------------------------------------------------------------------------
    
	# ----------------------------------------------------------------------------------------------------
    #  CODE
    # ----------------------------------------------------------------------------------------------------
    include(EDIRECTORY_ROOT."/includes/code/breadcrumb.php");
   
    # ----------------------------------------------------------------------------------------------------

	$breadcrumb = new breadcrumb;
    
    /**
     * Change path to breadcrumb
     */
	if ($breadcrumbScriptPath) {
        $breadcrumb->scriptArray = explode("/", $breadcrumbScriptPath);
    }

    if ( $sub_folder ) {
    	$breadcrumb->removeDirs = explode("/", $sub_folder);
    	$breadcrumb->subFolder = $sub_folder;
	}
    $breadcrumb->hideHome=TRUE;
    $breadcrumb->dirformat='ucfirst';
    $breadcrumb->symbol='<span class="split">&nbsp;&nbsp;/&nbsp;&nbsp;</span>';
    $breadcrumb->showfile=FALSE;
    $breadcrumb->unlinkCurrentDir=TRUE;
    $breadcrumb->eDirCrumbs=array($category_url=>string_htmlentities($category_name),
                                  $location_url=>$location_name);

	$breadcrumb->eDirStructure($item_id, $section, $type);
	$show_breadcrumb = $breadcrumb->show_breadcrumb();
	$show_auxbreadcrumb = $breadcrumb->show_auxbreadcrumb();

    if ($show_breadcrumb) { ?>
		<p class="breadcrumb"><?=$breadcrumb->show_breadcrumb()?></p>
	<? } elseif ($show_auxbreadcrumb) { ?>
		<p class="breadcrumb"><?=$show_auxbreadcrumb?></p>
	<? }
  
?>