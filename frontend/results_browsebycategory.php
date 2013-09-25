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
    # * FILE: /frontend/results_browsebycategory.php
    # ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
    # CODE
    # ----------------------------------------------------------------------------------------------------
	unset($catObj);
    $catObj = new $aux_CategoryObj($category_id);
    $subcategories = $catObj->retrieveAllSubCatById($category_id);
    $num_cols = $aux_CategoryNumColumn;
	
	$path_elem_arr = $catObj->getFullPath();
	
	/*
	 * Prepare URL with mode_rewrite ON
	 */
    $href = "";
    if ($path_elem_arr){
        foreach ($path_elem_arr as $each_category) {
            $friendly_url_path[] = $each_category["friendly_url"];
        }
    }

    $href = $aux_CategoryModuleURL."/".implode("/",$friendly_url_path);
	
	unset($categories_content);
	if ($subcategories){
                echo "<div class=\"browse-category-box\">";
                echo "<h2>BROWSE BY SUBCATEGORY<div class=\"border-right\"></div></h2>";
		echo "<ul class=\"browse-category\">";
		for ($j = 0; $j < count($subcategories); $j++) {
			if ($subcategories[$j]["friendly_url"]) {
				$aux_url_subcategory = $href."/".$subcategories[$j]["friendly_url"];
				?>
				<li>
					<a href="<?=$aux_url_subcategory?>" title = "<?=string_htmlentities($subcategories[$j]["title"])?>">
						<?
						echo system_showTruncatedText($subcategories[$j]["title"],20);
						?>
					</a>
					<?
					if ((SHOW_CATEGORY_COUNT == "on") && $aux_CategoryActiveField){
						echo " <span>(".$subcategories[$j][$aux_CategoryActiveField].")</span>";
					}
					?>
				</li>
				
				<?				
			}
		}
		echo "</ul>";
                echo "</div>";
	}
	
	echo $catObj->getString("content", false);
	
?>