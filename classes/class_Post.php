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
	# * FILE: /classes/class_Post.php
	# ----------------------------------------------------------------------------------------------------

	/**
	 * <code>
	 *		$postObj = new Post($id);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 9.5.00
	 * @package Classes
	 * @name Post
	 * @method Post
	 * @method makeFromRow
	 * @method Save
	 * @method Delete
	 * @method updateImage
	 * @method setFullTextSearch
	 * @method setNumberViews
	 * @method getCategories
	 * @method setCategories
     * @method updateCategoryStatusByID
	 * @method getFullPath
	 * @method getTimeString
	 * @method SaveWPToEdir
	 * @method deleteWPPost
	 * @method TrashedWPPost
	 * @method UntrashedWPPost
     * @method setWPCategories
	 * @method getFriendlyURL
	 * @access Public
	 */
	class Post extends Handle {

		/**
		 * @var integer
		 * @access Private
		 */
		var $id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $image_id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $thumb_id;
		/**
		 * @var date
		 * @access Private
		 */
		var $updated;
		/**
		 * @var date
		 * @access Private
		 */
		var $entered;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $title;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $seo_title;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $friendly_url;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $image_caption;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $thumb_caption;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $content;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $keywords;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $seo_keywords;
        /**
		 * @var varchar
		 * @access Private
		 */
		var $seo_abstract;
		/**
		 * @var char
		 * @access Private
		 */
		var $status;
		/**
		 * @var integer
		 * @access Private
		 */
		var $number_views;
		/**
		 * @var integer
		 * @access Private
		 */
		var $legacy_id;
		
		/**
		 * <code>
		 *		$postObj = new Post($id);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name Post
		 * @access Public
		 * @param mixed $var
		 */
		function Post($var="") {
			if (is_numeric($var) && ($var)) {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if (defined("SELECTED_DOMAIN_ID")) {
					$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$db = db_getDBObject();
				}

				unset($dbMain);
				$sql = "SELECT * FROM Post WHERE id = $var";
				$row = mysql_fetch_array($db->query($sql));
				$this->makeFromRow($row);
			} else {
                if (!is_array($var)) {
                    $var = array();
                }
				$this->makeFromRow($var);
			}
		}

		/**
		 * <code>
		 *		$this->makeFromRow($row);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name makeFromRow
		 * @access Public
		 * @param array $row
		 */
		function makeFromRow($row="") {

			$this->id				= ($row["id"])					? $row["id"]				: ($this->id					? $this->id                 : 0);
			$this->image_id			= ($row["image_id"])			? $row["image_id"]			: ($this->image_id				? $this->image_id           : 0);
			$this->thumb_id			= ($row["thumb_id"])			? $row["thumb_id"]			: ($this->thumb_id				? $this->thumb_id           : 0);
			$this->updated			= ($row["updated"])				? $row["updated"]			: ($this->updated				? $this->updated            : "");
			$this->entered			= ($row["entered"])				? $row["entered"]			: ($this->entered				? $this->entered            : "");
			$this->title			= ($row["title"])				? $row["title"]				: ($this->title					? $this->title              : "");
			$this->seo_title		= ($row["seo_title"])			? $row["seo_title"]			: ($this->seo_title				? $this->seo_title          : "");
			$this->friendly_url		= ($row["friendly_url"])		? $row["friendly_url"]		: "";
			$this->image_caption	= ($row["image_caption"])		? $row["image_caption"]     : ($this->image_caption         ? $this->image_caption		: "");
			$this->thumb_caption	= ($row["thumb_caption"])		? $row["thumb_caption"]     : ($this->thumb_caption         ? $this->thumb_caption		: "");
			$this->content			= ($row["content"])             ? $row["content"]			: "";
			$this->keywords         = ($row["keywords"])			? $row["keywords"]			: "";
			$this->seo_keywords     = ($row["seo_keywords"])		? $row["seo_keywords"]		: ($this->seo_keywords			? $this->seo_keywords		: "");
			$this->seo_abstract 	= ($row["seo_abstract"])		? $row["seo_abstract"]		: ($this->seo_abstract			? $this->seo_abstract		: "");
			$this->status			= ($row["status"])				? $row["status"]			: "A";
			$this->number_views		= ($row["number_views"])		? $row["number_views"]		: ($this->number_views			? $this->number_views		: 0);
			$this->legacy_id		= ($row["legacy_id"])			? $row["legacy_id"]			: ($this->legacy_id				? $this->legacy_id			: "");
		
		}		

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->Save();
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->Save();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name Save
		 * @access Public
		 */
		function Save() {
			
			$empty_legacy_id = false;
			
			if (!$this->legacy_id){
				$empty_legacy_id = true;
			}

			$this->prepareToSave();

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			$this->friendly_url = string_strtolower($this->friendly_url);
			
			if ($this->id) {

				$sql = "UPDATE Post SET"
					. " image_id         = $this->image_id,"
					. " thumb_id         = $this->thumb_id,"
					. " updated          = NOW(),"
					. " title            = $this->title,"
					. " seo_title        = $this->seo_title,"
					. " friendly_url     = $this->friendly_url,"
					. " image_caption	 = $this->image_caption,"
					. " thumb_caption    = $this->thumb_caption,"
					. " content          = $this->content,"
					. " keywords         = $this->keywords,"
					. " seo_keywords     = $this->seo_keywords,"
					. " seo_abstract     = $this->seo_abstract,"
					. " status           = $this->status,"
					. " number_views     = $this->number_views,"
					. " legacy_id		 = $this->legacy_id"
					. " WHERE id         = $this->id";

				$dbObj->query($sql);
                
                system_countActivePostByCategory($this->id);
                $this->updateCategoryStatusByID();

			} else {

				$sql = "INSERT INTO Post"
					. " (image_id,"
					. " thumb_id,"
					. " updated,"
					. " entered,"
					. " title,"
					. " seo_title,"
					. " friendly_url,"
					. " image_caption,"
					. " thumb_caption,"
					. " content,"
					. " keywords,"
					. " seo_keywords,"
					. " seo_abstract,"
					. " fulltextsearch_keyword,"
					. " status,"
					. " number_views,"
					. " legacy_id)"
					. " VALUES"
					. " ($this->image_id,"
					. " $this->thumb_id,"
					. " NOW(),"
					. " NOW(),"
					. " $this->title,"
					. " $this->title,"
					. " $this->friendly_url,"
					. " $this->image_caption,"
					. " $this->thumb_caption,"
					. " $this->content,"
					. " $this->keywords,"
					. " ".str_replace(" || ", ", ", $this->keywords).","
                    . " $this->seo_abstract,"
					. " '',"
					. " $this->status,"
					. " $this->number_views,"
					. " $this->legacy_id)";

				$dbObj->query($sql);
				$this->id = mysql_insert_id($dbObj->link_id);
				
				/*
				 * Legacy ID to Wordpress
				 */
				if($empty_legacy_id){
					unset($sql_legacy_id);
					$sql_legacy_id = "UPDATE Post SET legacy_id = 'ed_".$this->id."' WHERE id = ".$this->id;
					$dbObj->query($sql_legacy_id);
					
				}
                
                system_countActivePostByCategory($this->id);
			}

			$this->prepareToUse();
            $this->setFullTextSearch();
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->Delete();
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->Delete();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name Delete
		 * @access Public
		 */
		function Delete($domain_id = SELECTED_DOMAIN_ID) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
            
            ### POST CATEGORY STATUS
			if ($this->status != "P") {
				$sql = "UPDATE Post SET status = 'P' WHERE id = $this->id";
				$dbObj->query($sql);
			}
            
            if (SHOW_CATEGORY_COUNT == "on") system_countActivePostByCategory($this->id, false, $domain_id);
			
			### COMMENTS
			$sql = "SELECT id FROM Comments WHERE post_id = $this->id";
			$result = $dbObj->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$commentObj = new Comments($row["id"]);
				$commentObj->Delete();
			}

			### BLOG_CATEOGRY
			$sql = "DELETE FROM Blog_Category WHERE post_id = $this->id";
			$dbObj->query($sql);

			### IMAGE
			if ($this->image_id) {
				$image = new Image($this->image_id);
				if ($image) $image->Delete();
			}
			if ($this->thumb_id) {
				$image = new Image($this->thumb_id);
				if ($image) $image->Delete();
			}

			### POST
			$sql = "DELETE FROM Post WHERE id = $this->id";
			$dbObj->query($sql);

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->updateImage($imageArray);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->updateImage($imageArray);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name updateImage
		 * @access Public
		 * @param array $imageArray
		 */
		function updateImage($imageArray) {
			unset($imageObj);
			if ($this->image_id) {
				$imageobj = new Image($this->image_id);
				if ($imageobj) $imageobj->delete();
			}
			$this->image_id = $imageArray["image_id"];
			unset($imageObj);
			if ($this->thumb_id) {
				$imageObj = new Image($this->thumb_id);
				if ($imageObj) $imageObj->delete();
			}
			$this->thumb_id = $imageArray["thumb_id"];
			unset($imageObj);
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->setFullTextSearch();
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->setFullTextSearch();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name setFullTextSearch
		 * @access Public
		 */
		function setFullTextSearch() {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			if ($this->title) {
                $fulltextsearch_keyword[] = $this->title;
                $addkeyword=format_addApostWords($this->title);
                if ($addkeyword) $fulltextsearch_keyword[] = $addkeyword;
                unset($addkeyword);
			}

            if ($this->keywords) {
                $string=str_replace(" || ", " ", $this->keywords);
                $fulltextsearch_keyword[] = $string;
                $addkeyword=format_addApostWords($string);
                if ($addkeyword!='')  $fulltextsearch_keyword[] =$addkeyword;
                unset($addkeyword);
            }

			$categories = $this->getCategories(false, false, $this->id, true, true);
			if ($categories) {
				foreach ($categories as $category) {
					unset($parents);
					$category_id = $category->getNumber("id");
					while ($category_id != 0) {
						$sql = "SELECT * FROM BlogCategory WHERE id = $category_id";
						$result = $dbObj->query($sql);
						if (mysql_num_rows($result) > 0) {
							$category_info = mysql_fetch_assoc($result);
                            if ($category_info["enabled"] == "y") {
                                if ($category_info["title"]) {
                                    $fulltextsearch_keyword[] = $category_info["title"];
                                }

                                if ($category_info["keywords"]) {
                                    $fulltextsearch_keyword[] = $category_info["keywords"];
                                }
                            }
							$category_id = $category_info["category_id"];
						} else {
							$category_id = 0;
						}
					}
				}
			}

			if (is_array($fulltextsearch_keyword)) {
				$fulltextsearch_keyword_sql = db_formatString(implode(" ", $fulltextsearch_keyword));
				$sql = "UPDATE Post SET fulltextsearch_keyword = $fulltextsearch_keyword_sql WHERE id = $this->id";
				$result = $dbObj->query($sql);
			}

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->setNumberViews($id);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->setNumberViews($id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name setNumberViews
		 * @access Public
		 * @param integer $id
		 */
		function setNumberViews($id) {
			
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "UPDATE Post SET number_views = ".$this->number_views." + 1 WHERE Post.id = ".$id;
			$dbObj->query($sql);

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->getCategories(...);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->getCategories(...);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getCategories
		 * @access Public
		 * @param boolean $have_data
		 * @param array $data
		 * @param integer $id
		 * @param boolean $getAll
		 * @param boolean $object
		 * @param boolean $bulk
		 * @return array $categories
		 */
		function getCategories($have_data = false, $data = false, $id = false, $getAll = false, $object=false, $bulk=false) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			if ($have_data) {
				if ($data["cat_1_id"]) $ids[] = $data["cat_1_id"];
				if ($data["cat_2_id"]) $ids[] = $data["cat_2_id"];
				if ($data["cat_3_id"]) $ids[] = $data["cat_3_id"];
				if ($data["cat_4_id"]) $ids[] = $data["cat_4_id"];
				if ($data["cat_5_id"]) $ids[] = $data["cat_5_id"];

				if (is_array($ids)) {
					$ids = array_unique($ids);
					$sql = "SELECT * FROM BlogCategory WHERE id IN (".implode(",", $ids).")";
					$r = $dbObj->query($sql);
					while ($row = mysql_fetch_array($r)) {
						$categories[] = new BlogCategory($row);
					}
				}

			} else {
				if(!$id){
					$id = $this->id ;
				}
				if($id){

					$sql_main = "SELECT category.root_id,
										post_category.category_id
										FROM Blog_Category post_category
										INNER JOIN BlogCategory category ON category.id = post_category.category_id
										WHERE post_category.post_id = ".$id." AND root_id > 0";

					$result_main = $dbObj->unbuffered_query($sql_main);
					//if(mysql_num_rows($result_main) > 0){
					if($result_main){

						$aux_array_categories = array();
						while($row = mysql_fetch_assoc($result_main)){
							if (!$object && !$bulk) {
								$aux_array_categories[] = $row["root_id"];
							}
							if ($getAll) {
								$aux_array_categories[] = $row["category_id"];
							}
						}

						if(count($aux_array_categories) > 0){
							$sql = "SELECT	id,
											title,
											page_title,
											friendly_url,
											enabled,
											category_id
										FROM BlogCategory
										WHERE id IN (".implode(",",$aux_array_categories).")";
                                                        
                            if(!$object){
                                $result = $dbObj->unbuffered_query($sql);
                            }else{
                                $result = $dbObj->query($sql);
                            }
							
							if($result){
								$categories = array();
								while($row = mysql_fetch_assoc($result)){
									if ($object){
										$categories[] = new BlogCategory($row);
                                    } else {
										$categories[] = $row;
                                    }
								}
							}
						}
					}
				}
			}

			if (count($categories) > 0) {
				return $categories;
			} else {
				return false;
			}
		}
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->setCategories($categories);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->setCategories($categories);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name setCategories
		 * @access Public
		 * @param array $array
		 */
		function setCategories($array) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			if ($this->id) {
				system_countActivePostByCategory($this->id);

				$sql = "DELETE FROM Blog_Category WHERE post_id = ".$this->id;
				$dbObj->query($sql);

				if ($array) {
					foreach ($array as $category) {
						if ($category) {

							$lCatObj = new BlogCategory($category);
							unset($root_id, $left, $right);
							$root_id = $lCatObj->getNumber("root_id");
							$left = $lCatObj->getNumber("left");
							$right = $lCatObj->getNumber("right");

							unset($b_catObj);
							$b_catObj = new Blog_Category();
							$b_catObj->setNumber("post_id", $this->id);
							$b_catObj->setNumber("category_id", $category);
							$b_catObj->setString("status", $this->status);
							$b_catObj->setNumber("category_root_id", $root_id);
							$b_catObj->setNumber("category_node_left", $left);
							$b_catObj->setNumber("category_node_right", $right);
							$b_catObj->Save();
						}
					}
				}

				$this->setFullTextSearch();
				system_countActivePostByCategory($this->id);
			}
		}
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->updateCategoryStatusByID();
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->updateCategoryStatusByID();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name updateCategoryStatusByID
		 * @access Public
		 */
		function updateCategoryStatusByID(){
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql_update = "UPDATE Blog_Category SET status = $this->status WHERE post_id = $this->id";
			$dbObj->query($sql_update);
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->getFullPath();
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->getFullPath();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name getFullPath
		 * @access Public
		 * @return array $path
		 */
		function getFullPath() {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$category_id = $this->id;
			$i=0;
			while ($category_id != 0) {
				$sql = "SELECT * FROM BlogCategory WHERE id = $category_id";
				$result = $dbObj->query($sql);
				$row = mysql_fetch_assoc($result);
				$path[$i]["id"] = $row["id"];
				$path[$i]["dad"] = $row["category_id"];
				$path[$i]["title"] = $row["title"];
				$path[$i]["friendly_url"] = $row["friendly_url"];
				$path[$i]["active_post"] = $row["active_post"];
				$i++;
				$category_id = $row["category_id"];
			}
			if ($path) {
				$path = array_reverse($path);
				for($i=0; $i < count($path); $i++) $path[$i]["level"] = $i+1;
				return($path);
			} else {
				return false;
			}
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->getTimeString();
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->getTimeString();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name getTimeString
		 * @access Public
		 * @return varchar $str_time
		 */
		function getTimeString($timeField = "entered") {
			$str_time = "";

            if ($timeField == "entered"){
                $startTimeStr = explode(":", $this->getString("entered"));
            } else {
                $startTimeStr = explode(":", $this->getString("updated"));
            }
			$startTimeStr[0] = string_substr($startTimeStr[0],-2);
			if (CLOCK_TYPE == '24') {
				$start_time_hour = $startTimeStr[0];
			} elseif (CLOCK_TYPE == '12') {
				if ($startTimeStr[0] > "12") {
					$start_time_hour = $startTimeStr[0] - 12;
					$start_time_am_pm = "pm";
				} elseif ($startTimeStr[0] == "12") {
					$start_time_hour = 12;
					$start_time_am_pm = "pm";
				} elseif ($startTimeStr[0] == "00") {
					$start_time_hour = 12;
					$start_time_am_pm = "am";
				} else {
					$start_time_hour = $startTimeStr[0];
					$start_time_am_pm = "am";
				}
			}
			if ($start_time_hour < 10) $start_time_hour = "0".($start_time_hour+0);
			$start_time_min = $startTimeStr[1];
			$str_time .= $start_time_hour.":".$start_time_min." ".$start_time_am_pm;

			return $str_time;
		}
		
		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->SaveWPToEdir($wp_content);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->SaveWPToEdir($wp_content);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name SaveWPToEdir
		 * @access Public
		 * @return misc $wp_content
		 */
		function SaveWPToEdir($wp_content){
			
			if (!is_array($wp_content)) {
				$wp_content = unserialize($wp_content);
			}
			
			if (is_array($wp_content)) {
						
				/*
				 * Get Post ID using legacy ID
				 */
				$db = db_getDBObject();
				$sql = "SELECT * FROM Post WHERE legacy_id = '"."wp_".$wp_content["fields"]["ID"]."'";
				$result = $db->query($sql);
				if(mysql_num_rows($result)){
					$row = mysql_fetch_assoc($result);
					$this->makeFromRow($row);
				}
				
				$fields[0]["name"]		= "content";
				$fields[0]["content"]	= $wp_content["fields"]["post_content"];
				
				$fields[1]["name"]		= "title";
				$fields[1]["content"]	= $wp_content["fields"]["post_title"];
				
				$fields[2]["name"]		= "legacy_id";
				$fields[2]["content"]	= "wp_".$wp_content["fields"]["ID"];
				
				$fields[3]["name"]		= "entered";
				$fields[3]["content"]	= $wp_content["fields"]["post_date"];
				
				$blog_friendly_url = preg_replace("/[^".FRIENDLYURL_VALIDCHARS."]/", FRIENDLYURL_SEPARATOR, $wp_content["fields"]["post_title"]);
				$blog_friendly_url = string_strtolower(preg_replace("/[\\".FRIENDLYURL_SEPARATOR."]{2,}/", FRIENDLYURL_SEPARATOR, $blog_friendly_url));
				$fields[4]["name"]		= "friendly_url";
				$fields[4]["content"]	= $blog_friendly_url;
				
				$fields[5]["name"]		= "status";
				$fields[5]["content"]	= ($wp_content["fields"]["post_status"] == "publish" ? "A" : "S");
				
				for($i=0;$i<count($fields);$i++){
					$this->$fields[$i]["name"] = $fields[$i]["content"];
				}
				
				$this->Save();
				/*
				 * Save tags to post
				 */
				if (count($wp_content["fields"]["categories"])) {
					
					unset($category_ids);
					for($i=0;$i<count($wp_content["fields"]["categories"]); $i++){
						$category_ids[] = "'wp_".$wp_content["fields"]["categories"][$i]."'";
					}
					
					$this->setWPCategories($category_ids, $this->id);
					
				}
			}
		}
		
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->deleteWPPost($wp_fields);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->deleteWPPost($wp_fields);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name deleteWPPost
		 * @access Public
		 * @return misc $wp_fields
		 */
		function deleteWPPost($wp_fields){
			
			if ($wp_fields["fields"]["id"]) {
				
				$dbObj = db_getDBObject();
				$sql = "SELECT id FROM Post WHERE legacy_id = 'wp_".$wp_fields["fields"]["id"]."'";
				$result = $dbObj->query($sql);
				
				if(mysql_num_rows($result)){
					while($row = mysql_fetch_assoc($result)){
						$this->id = $row["id"];
						$this->Delete();
					}
				}
			}
		}
		
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->TrashedWPPost($wp_fields);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->TrashedWPPost($wp_fields);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name TrashedWPPost
		 * @access Public
		 * @return misc $wp_fields
		 */
		function TrashedWPPost($wp_fields){
			
			if($wp_fields["fields"]["id"]){
				
				$dbObj = db_getDBObject();
				$sql = "UPDATE Post SET status = 'S' WHERE legacy_id = 'wp_".$wp_fields["fields"]["id"]."'";
				$dbObj->query($sql);
                
                $sql = "SELECT id FROM Post WHERE legacy_id = 'wp_".$wp_fields["fields"]["id"]."'";
                $result = $dbObj->query($sql);
                while ($row = mysql_fetch_assoc($result)){
                    system_countActivePostByCategory($row["id"]);
                    $sql_update = "UPDATE Blog_Category SET status = 'S' WHERE post_id = ".db_formatNumber($row["id"]);
                    $dbObj->query($sql_update);
                }
			}
		}
		
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->UntrashedWPPost($wp_fields);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->UntrashedWPPost($wp_fields);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name UntrashedWPPost
		 * @access Public
		 * @return misc $wp_fields
		 */
		function UntrashedWPPost($wp_fields){
			
			if($wp_fields["fields"]["id"]){
				
				$dbObj = db_getDBObject();
				$sql = "UPDATE Post SET status = 'A' WHERE legacy_id = 'wp_".$wp_fields["fields"]["id"]."'";
				$result = $dbObj->query($sql);
				
				$sql = "SELECT id FROM Post WHERE legacy_id = 'wp_".$wp_fields["fields"]["id"]."'";
                $result = $dbObj->query($sql);
                while ($row = mysql_fetch_assoc($result)){
                    system_countActivePostByCategory($row["id"]);
                    $sql_update = "UPDATE Blog_Category SET status = 'A' WHERE post_id = ".db_formatNumber($row["id"]);
                    $dbObj->query($sql_update);
                }
			}
		}
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->setWPCategories($category_ids, $post_id);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->setWPCategories($category_ids, $post_id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name setWPCategories
         * @param array $category_ids
         * @param integer $post_id
		 * @access Public
		 */
		function setWPCategories($category_ids, $post_id){
			
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			$sql = "SELECT id FROM BlogCategory WHERE legacy_id IN (".implode(",", $category_ids).")";
			$result = $dbObj->query($sql);
            $arrayCateg = array();
			if(mysql_num_rows($result)){
				//Add categories
				while($row = mysql_fetch_assoc($result)) {
					$arrayCateg[] = $row["id"];
				}
                $this->setCategories($arrayCateg);
			}
		}
		
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$postObj->getFriendlyURL($mobile);
		 * <br /><br />
		 *		//Using this in Post() class.
		 *		$this->getFriendlyURL($mobile);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name getFriendlyURL
		 * @access Public
		 * @return boolean $mobile
		 */
		function getFriendlyURL($mobile = false){
        	if ($mobile) {
        		$aux_url = DEFAULT_URL."/mobile/".BLOG_FEATURE_FOLDER;
        	} else {
        		$aux_url = BLOG_DEFAULT_URL;
        	}
            
            return $aux_url."/".$this->friendly_url.".html";
		}
	}
?>