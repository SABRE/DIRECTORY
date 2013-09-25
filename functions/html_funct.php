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
	# * FILE: /functions/html_funct.php
	# ----------------------------------------------------------------------------------------------------

	function html_objectArraySelectBox($name, $objArray, $default="", $code="", $class="", $emptySelection="") {
		$htmlStr = "\n<select name=\"$name\" $code $class >\n";
		if(!empty($emptySelection)) {
			$htmlStr .= '<option value="">'.$emptySelection.'</option>'."\n";
		}
		if ($objArray != null) {
			foreach($objArray as $o) {
				$value = $o->getNumber("id");
				$label = $o->getString("name");
				if (!$label) {
					$label = $o->getString("title");
				}
				if (!$label) {
					$label = $o->getString("username");
				}
				$htmlStr .= "<option value=\"$value\"";
				if($default == $value) {
					$htmlStr .= " selected=\"selected\"";
				}
				$htmlStr .= ">$label</option>\n";
			}
		}
		$htmlStr .= "</select>\n";
		return $htmlStr;
	}

	/*
	* If you want a numeric sequence, use html_numSelectBox() instead.
	*/
	function html_selectBox($name, $nameArray, $valueArray, $selected, $code="", $class="", $emptyValue="", $disabled = false) {
		$htmlStr = "\n<select id=\"$name\" name=\"$name\" $code $class ".($disabled ? "disabled=\"disabled\"" : "").">\n";
		$count = count($nameArray);
		if($name=="search_status")
			$count = $count-2;
		if(!empty($emptyValue) && $count > 1) {
			$htmlStr .= "<option value=\"\">$emptyValue</option>\n";
		}
		for($i = 0; $i < $count; $i++) {
			$sel = "";
			if (($selected == $valueArray[$i]) && ($selected != "")) {
				$sel = "selected=\"selected\"";
			}
			$htmlStr .= "<option value=\"".$valueArray[$i]."\" $sel>".$nameArray[$i]."</option>\n";
					
		}
		$htmlStr .= "</select>\n";
		return $htmlStr;
	}

	function html_selectBox_BulkUpdate($name, $nameArray, $valueArray, $selected, $code="", $class="", $emptyValue="", $valueCategory) {
		
		$htmlStr = "\n<select id=\"$name\" name=\"$name\" $code $class >\n";
		$count = count($nameArray);
		if(!empty($emptyValue) && $count > 1) {
			$htmlStr .= "<option value=\"\">$emptyValue</option>\n";
		}
		for($i = 0; $i < $count; $i++) {
			$sel = "";
			if (($selected == $valueArray[$i]) && ($selected != "")) {
				$sel = "selected=\"selected\"";
			}
			if (!in_array($valueArray[$i], $valueCategory)) {
				$htmlStr .= "<option disabled value=\"".$valueArray[$i]."\" $sel>".$nameArray[$i]."</option>\n";
			} else {
				$htmlStr .= "<option value=\"".$valueArray[$i]."\" $sel>".$nameArray[$i]."</option>\n";
			}
			

		}
		$htmlStr .= "</select>\n";
		return $htmlStr;
	}
	
	function html_selectBoxCat($name, $nameArray, $valueArray, $selected, $code="", $class="", $emptyValue="", $local="") {
		
		$htmlStr = "\n<select class=\"select\" name=\"$name\" id=\"$name\" $code $class >\n";
		if(!empty($emptyValue)) {
			$htmlStr .= "<option value=\"\">$emptyValue</option>\n";
		}
		$count = count($nameArray);
		for($i = 0; $i < $count; $i++) {
			$sel = "";
			if (($selected == $valueArray[$i]) && ($selected != "")) {
				$sel = "selected=\"selected\"";
			}
			$dbObj = db_getDBObJect();
			if ($local == "" || $local == "listing") {
				$sql = "SELECT id, category_id FROM ListingCategory WHERE id = '$valueArray[$i]'";
			} else {
				if ($local == "event") {
					$sql = "SELECT id, category_id FROM EventCategory WHERE id = '$valueArray[$i]'";
				}
				if ($local == "classified") {
					$sql = "SELECT id, category_id FROM ClassifiedCategory WHERE id = '$valueArray[$i]'";
				}
				if ($local == "article") {
					$sql = "SELECT id, category_id FROM ArticleCategory WHERE id = '$valueArray[$i]'";
				}
                if ($local == "blog") {
					$sql = "SELECT id, category_id FROM BlogCategory WHERE id = '$valueArray[$i]'";
				}
			}
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			$id = $row["id"];
			$category_id = $row["category_id"];
			if (($id) || ($category_id)) {
				if ($category_id == 0){
					//category
					if (string_strlen($nameArray[$i]) > 99 ) {
						$label_1 = string_substr($nameArray[$i], 0, 99);
						$label_2 = string_substr($nameArray[$i], 99, string_strlen($nameArray[$i]));
						$htmlStr .= "<option value=\"".$valueArray[$i]."\" class=\"searchCategory\" $sel>".$label_1."</option>\n";
						$htmlStr .= "<option value=\"".$valueArray[$i]."\" class=\"searchCategory\" $sel>".$label_2."</option>\n";
					} else {
						$label = $nameArray[$i];
						$htmlStr .= "<option value=\"".$valueArray[$i]."\" class=\"searchCategory\" $sel>".$label."</option>\n";
					}
				} else {
					//sub-category
					if (string_strlen($nameArray[$i]) > 99 ) {
						$label_1 = string_substr($nameArray[$i], 0, 99);
						$label_2 = string_substr($nameArray[$i], 99, string_strlen($nameArray[$i]));
						$htmlStr .= "<option value=\"".$valueArray[$i]."\" class=\"searchSubcategory\" $sel>".$label_1."</option>\n";
						$htmlStr .= "<option value=\"".$valueArray[$i]."\" class=\"searchSubcategory\" $sel>".$label_2."</option>\n";
					} else {
						$label = $nameArray[$i];
						$htmlStr .= "<option value=\"".$valueArray[$i]."\" class=\"searchSubcategory\" $sel>".$label."</option>\n";
					}
					
				}
			} else {
				//separator
				$htmlStr .= "<option value=\"".$valueArray[$i]."\" class=\"searchSeparator\" $sel>&nbsp;</option>\n";
			}
		}
		$htmlStr .= "</select>\n";
                return $htmlStr;
	}

	/*
	* @name:   function html_numSelectBox
	* @since:  10/28/2005
	* @param:  array $options_array (all dropdown attributes. There is no limit for number of elements)
	* @param:  numeric $start (first sequence's #)
	* @param:  numeric $end (last sequence's #)
	* @param:  numeric $inc (increments - default:1 )
	* @param:  string $emptySelection (text to show when no item is selected)
	* @param:  numeric $zeroFill
	* @return: string "html select tag"
	*
	* The advantage of use this function is you dont need to give an arrya of values. 
	* You just need the 1st and the last numbers.
	* If $zeroFill is 0 (zero), no fill is done. Otherwise, if it is > 0, the given number is the number of positions that will be filled with left side zeros.
	*
	* This is an example for $options_array:
	* $options_array = array(
	*	'name' => 'my_number', 
	*	'class' => 'css_select',
	*	'style' => 'width:auto;',
	*	'tabindex' => '2',
	*	'onChange' => 'document.frmX.submit();',
	*	'selected' => '10',
	*	'emptyLabel' => '- Select -',
	*	'emptyValue' => '#'
	* );
	* 
	* All attributes are placed in the "select open" tag (<select>), except:
	* - "selected","emptySelection".
	*/
	function html_numSelectBox($options_array, $start, $end, $inc=1, $emptySelection="", $zeroFill=0) {
		$options = "";
		$htmlStr = "";
		foreach ($options_array as $key=>$value) {
			if ($key != "selected")
				$options .= "$key=\"$value\" ";
		}
		$htmlStr = "\n<select $options>\n";
		if(!empty($emptySelection)) {
			$htmlStr .= "<option value=\"\">$emptySelection</option>\n";
		}
		$zero = str_repeat("0", $zeroFill);
		for($i = $start; $i <= $end; $i+=$inc) {
			$j = string_substr($zero.$i, -$zeroFill);
			$sel = ($options_array["selected"] == $j) ? "selected=\"selected\"" : "";
			$htmlStr .= "<option value=\"$j\" $sel>$j</option>\n";
		}
		$htmlStr .= "</select>\n";
		return $htmlStr;
	}

	function html_locationSelectBox($type, $locations, $location_id="") {

		$l_name = "location_".$type;
		$l_label = constant("LANG_LABEL_SELECT_".constant("LOCATION".$type."_SYSTEM"));
		
		?>
		<select style="font: 8pt/18px Verdana, Arial, Helvetica, sans-serif" name="<?=$l_name?>" id="select_location<?=$type?>" class="" onchange="formLocations_submit(<?=$type?>, this.form)">
			<option value=""> -- <?=system_showText($l_label)?> -- </option>
			<?
			if ($locations) foreach ($locations as $location) {
				$selected = ($location_id == $location["id"]) ? "selected" : "";
				?><option <?=$selected?> value="<?=$location["id"]?>"><?=$location["name"]?></option><?
				unset($selected);
				}?>
		</select>
		<?
	}

	function html_protocolDropdown($url = "", $name = "url_protocol", $showftp = true, &$protocol_replace){
		
		$dropdown = "";
		$dropdown .= "<select name=\"$name\" class=\"httpSelect\">";
		$url_protocols = explode(",", URL_PROTOCOL);
		$sufix = "://";
		$protocol_replace = "" ;
		for ($i=0; $i<count($url_protocols); $i++) {
			$selected = false;
			$protocol = $url_protocols[$i];
			if ($showftp || (!$showftp && $protocol != "ftp")){
				if (isset($url)) {
					$_protocol = explode($sufix, $url);
					$_protocol = $_protocol[0];

					if ($_protocol == $protocol) {
						$selected = true;
						$protocol_replace = $_protocol.$sufix;
					}
				} else if (!$i) {
					$selected = true;
					$protocol_replace = $url_protocols[$i];
					$protocol_replace = $protocol_replace.$sufix;
				}
				$protocol .= $sufix;

				$dropdown .= "<option value=\"".$protocol."\" ".($selected == true  ? "selected=\"selected\"" : "").">".$protocol."</option>";
			}
		}
		$dropdown .= "</select>";
		return $dropdown;
	}
	
	/*Functions Created by Ashish Commented on 24-07-2013
	function  html_lisitingCat($name, $categories, $selected, $code="", $class="", $emptyValue="", $local="",$selected_category_id = null,$sub_id = null,$categoryCount) 
	{
	
		switch($local)
		{
			case 'listing':
				$module_url = LISTING_DEFAULT_URL;
			break;
			case 'event':
				$module_url = EVENT_DEFAULT_URL;
			break;
			case 'classified':
				$module_url = CLASSIFIED_DEFAULT_URL;
			break;
			case 'article':
				$module_url = ARTICLE_DEFAULT_URL;
			break;
			case 'blog':
				$module_url = BLOG_DEFAULT_URL;
			break;
			case 'promotion':
				$module_url = PROMOTION_DEFAULT_URL;
			break;
			default:
				$module_url = LISTING_DEFAULT_URL;
				
		}
		if (is_array($categories)) 
		{
			$htmlStr = '';
                        $htmlStr .= "<ul class=\"browse-category\">";
			for ($i = 0; $i < count($categories); $i++) 
			{
				
				if($selected_category_id != $categories[$i]["id"]){
					$main_cat_open = "main_cat";
					$subcat_display = "style='display:none;'";
					$main_add_class = null;
				}else{
					$main_cat_open = "main_cat2";
					$subcat_display = "style='display:block;'";
					$main_add_class = "Select";
				}
				
				$categoryLink =  $module_url."/".ALIAS_CATEGORY_URL_DIVISOR."/".$categories[$i]["friendly_url"];
				$htmlStr .= "<li><div class='".$main_cat_open."'></div>";
				$htmlStr .= "<a id='".$categories[$i]["id"]."' class='main_cat_link ".$main_add_class."' href='".$categoryLink."'>".system_showTruncatedText($categories[$i]["title"],25)."</a>";
				if($categoryCount=='on')
					$htmlStr .= "<span>(".$categories[$i]["active_".($local == "blog" ? "post" : $local)].")</span>";
				
				if(!empty($categories[$i]['subcategories']))
				{
					$htmlStr .= "<div class=\"sub_cat\" ".$subcat_display." >";
	                $htmlStr .= "<ul>";
	                for($j=0; $j < count($categories[$i]['subcategories']); $j++)
	                {
						$sub_add_class = ($sub_id != $categories[$i]["subcategories"][$j]["id"])?null : "Select" ;
	                	$subCategoryLink = $module_url."/".ALIAS_CATEGORY_URL_DIVISOR."/".$categories[$i]["friendly_url"]."/".$categories[$i]["subcategories"][$j]["friendly_url"];
	                	$htmlStr .= "<li>";
	                	$htmlStr .= "<a id='".$categories[$i]["subcategories"][$j]["id"]."' class='sub_cat_link ".$sub_add_class."' href='".$subCategoryLink."'>".system_showTruncatedText($categories[$i]["subcategories"][$j]["title"],25)."</a>";
	                	if($categoryCount=='on')
	                		$htmlStr .= "<span>(".$categories[$i]["subcategories"][$j]["active_".($local == "blog" ? "post" : $local)].")</span>";
	                	$htmlStr .= "</li>";
	                }
	                $htmlStr .= "</ul>";
	                //$htmlStr .= "<div style='clear:both;'></div>";
	                $htmlStr .= "</div>";
	            }
				$htmlStr .= "</li>";
			}
		}
		$htmlStr .= "</ul>";
		
		return $htmlStr;
	}*/
        
        function  html_lisitingCat($name, $categories, $selected, $code="", $class="", $emptyValue="", $local="",$selected_category_id = null,$sub_id = null,$categoryCount) 
	{
              switch($local)
		{
			case 'listing':
				$module_url = LISTING_DEFAULT_URL;
			break;
			case 'event':
				$module_url = EVENT_DEFAULT_URL;
			break;
			case 'classified':
				$module_url = CLASSIFIED_DEFAULT_URL;
			break;
			case 'article':
				$module_url = ARTICLE_DEFAULT_URL;
			break;
			case 'blog':
				$module_url = BLOG_DEFAULT_URL;
			break;
			case 'promotion':
				$module_url = PROMOTION_DEFAULT_URL;
			break;
			default:
				$module_url = LISTING_DEFAULT_URL;
				
		}
		if (is_array($categories)) 
		{
			$htmlStr = '';
                        $htmlStr .= "<h2>BROWSE BY CATEGORY<div class=\"border-right\" style=\"width:550px;\"></div></h2>";
			$htmlStr .= "<ul class=\"browse-category\">";
			for ($i = 0; $i < count($categories); $i++) 
			{
				
				if($selected_category_id != $categories[$i]["id"]){
					$main_cat_open = "main_cat";
					$subcat_display = "style='display:none;'";
					$main_add_class = null;
				}else{
					$main_cat_open = "main_cat2";
					$subcat_display = "style='display:none;'";
					$main_add_class = "Select";
				}
				
				$categoryLink =  $module_url."/".$categories[$i]["friendly_url"];
				$htmlStr .= "<li><div class='".$main_cat_open."'></div>";
				$htmlStr .= "<a id='".$categories[$i]["id"]."' class='main_cat_link ".$main_add_class."' href='".$categoryLink."'>".system_showTruncatedText($categories[$i]["title"],15)."</a>";
				if($categoryCount=='on')
					$htmlStr .= "<span>(".$categories[$i]["active_".($local == "blog" ? "post" : $local)].")</span>";
				
				if(!empty($categories[$i]['subcategories']))
				{
					$htmlStr .= "<div class=\"sub_cat\" ".$subcat_display." >";
                                        $htmlStr .= "<ul>";
                                        for($j=0; $j < count($categories[$i]['subcategories']); $j++)
                                        {
                                                $sub_add_class = ($sub_id != $categories[$i]["subcategories"][$j]["id"])?null : "Select" ;
                                                $subCategoryLink = $module_url."/".$categories[$i]["friendly_url"]."/".$categories[$i]["subcategories"][$j]["friendly_url"];
                                                $htmlStr .= "<li>";
                                                $htmlStr .= "<a id='".$categories[$i]["subcategories"][$j]["id"]."' class='sub_cat_link ".$sub_add_class."' href='".$subCategoryLink."'>".system_showTruncatedText($categories[$i]["subcategories"][$j]["title"],15)."</a>";
                                                if($categoryCount=='on')
                                                        $htmlStr .= "<span>(".$categories[$i]["subcategories"][$j]["active_".($local == "blog" ? "post" : $local)].")</span>";
                                                $htmlStr .= "</li>";
                                        }
                                        $htmlStr .= "</ul>";
                                        //$htmlStr .= "<div style='clear:both;'></div>";
                                        $htmlStr .= "</div>";
                                }
				$htmlStr .= "</li>";
                        }
                        $htmlStr .= "</ul>";
                }
		return $htmlStr;
	}
?>