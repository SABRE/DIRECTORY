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
	# * FILE: /includes/views/view_blog_category_detail.php
	# ----------------------------------------------------------------------------------------------------

?>

<p>
	<?
		$category = new BlogCategory($_GET["id"]);
		$arr_full_path = $category->getFullPath();
		foreach ($arr_full_path as $each_node) {
			$path[] = $each_node["title"];
		}
		$full_path = implode(" &rsaquo; ", $path);
		echo $full_path;
	?>
</p>