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
	# * FILE: /classes/class_BlogCategory.php
	# ----------------------------------------------------------------------------------------------------

    /**
	 * <code>
	 *		$categObj = new BlogCategory($id);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 9.5.00
	 * @package Classes
	 * @name BlogCategory
	 * @method BlogCategory
	 * @method makeFromRow
	 * @method Save
	 * @method rebuildCategoryTree
	 * @method findRootCategoryId
	 * @method getHierarchy
	 * @method getHighestLevel
	 * @method Delete
	 * @method retrieveAllCategories
	 * @method retrieveAllCategoriesXML
	 * @method getAllCategoriesHierarchyXML
	 * @method retrieveAllSubCatById
	 * @method getLevel
	 * @method getFullPath
	 * @method updateFullTextItems
	 * @method setFeatured
	 * @method updateFullFriendlyURL
	 * @method countActivePostByCategory
	 * @method SaveWPToEdir
	 * @method deleteWPCategory
	 * @access Public
	 */
	class BlogCategory extends Handle {

        /**
		 * @var integer
		 * @access Private
		 */
		var $id;
        /**
		 * @var string
		 * @access Private
		 */
		var $title;
        /**
		 * @var string
		 * @access Private
		 */
		var $page_title;
        /**
		 * @var string
		 * @access Private
		 */
		var $friendly_url;
        /**
		 * @var integer
		 * @access Private
		 */
		var $category_id;
        /**
		 * @var char
		 * @access Private
		 */
		var $featured;
        /**
		 * @var string
		 * @access Private
		 */
		var $seo_description;
        /**
		 * @var string
		 * @access Private
		 */
		var $keywords;
        /**
		 * @var string
		 * @access Private
		 */
		var $seo_keywords;
        /**
		 * @var string
		 * @access Private
		 */
		var $content;
        /**
		 * @var integer
		 * @access Private
		 */
		var $active_post;
        /**
		 * @var integer
		 * @access Private
		 */
        var $left;
        /**
		 * @var integer
		 * @access Private
		 */
		var $right;
        /**
		 * @var integer
		 * @access Private
		 */
		var $root_id;
        /**
		 * @var string
		 * @access Private
		 */
		var $full_friendly_url;
        /**
		 * @var integer
		 * @access Private
		 */
		var $level;
        /**
		 * @var integer
		 * @access Private
		 */
		var $legacy_id;
         /**
		 * @var integer
		 * @access Private
		 */
		var $enabled;

        /**
		 * <code>
		 *		$categObj = new BlogCategory($id);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name BlogCategory
		 * @access Public
		 * @param integer $var
		 */
		function BlogCategory($var='') {
			if (is_numeric($var) && ($var)) {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if (defined("SELECTED_DOMAIN_ID")) {
					$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$db = db_getDBObject();
				}
				unset($dbMain);
				$sql = "SELECT * FROM BlogCategory WHERE id = $var";
				$row = mysql_fetch_array($db->unbuffered_query($sql));
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
		function makeFromRow($row='') {

			$this->id                   = ($row["id"])					? $row["id"]                    : ($this->id             ? $this->id				: 0);
			$this->title                = ($row["title"])				? $row["title"]                 : ($this->title			 ? $this->title             : "");
			$this->page_title           = ($row["page_title"])			? $row["page_title"]            : ($this->page_title     ? $this->page_title		: "");  
			$this->friendly_url         = ($row["friendly_url"])		? $row["friendly_url"]          : ($this->friendly_url   ? $this->friendly_url		: "");
			$this->category_id          = ($row["category_id"])			? $row["category_id"]           : ($this->category_id    ? $this->category_id		: 0);
			$this->featured             = ($row["featured"])			? $row["featured"]              : ($this->featured       ? $this->featured			: "n");
			$this->seo_description      = ($row["seo_description"])     ? $row["seo_description"]       : "";
			$this->keywords             = ($row["keywords"])			? $row["keywords"]              : ($this->keywords       ? $this->keywords			: "");
			$this->seo_keywords         = ($row["seo_keywords"])        ? $row["seo_keywords"]          : ($this->seo_keywords   ? $this->seo_keywords      : "");
			$this->content              = ($row["content"])             ? $row["content"]               : "";
			$this->active_post          = ($row["active_post"])			? $row["active_post"]           : ($this->active_post       ? $this->active_post    : 0);
			$this->left					= ($row["left"])				? $row["left"]					: ($this->left              ? $this->left			: 1);
			$this->right				= ($row["right"])				? $row["right"]					: ($this->right				? $this->right			: 2);
			$this->root_id				= ($row["root_id"])				? $row["root_id"]				: ($this->root_id			? $this->root_id		: 0);
			$this->full_friendly_url	= ($row["full_friendly_url"])   ? $row["full_friendly_url"]     : "";
            $this->level                = ($row["level"])				? $row["level"]                 : 0;
			$this->legacy_id            = ($row["legacy_id"])			? $row["legacy_id"]             : ($this->legacy_id         ? $this->legacy_id      : "");
            $this->enabled              = ($row["enabled"])             ? $row["enabled"]               : ($this->enabled           ? $this->enabled        : "n");

		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->Save();
		 * <br /><br />
		 *		//Using this in BlogCategory() class.
		 *		$this->Save();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name Save
         * @param boolean $update_friendlyurl
		 * @access Public
		 */
		function Save($update_friendlyurl = true) {
			
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

				$sql = "UPDATE BlogCategory SET"
					. " title = $this->title,"
					. " page_title = $this->page_title,"
					. " friendly_url = $this->friendly_url,"
					. " category_id = $this->category_id,"
					. " featured = $this->featured,"
					. " seo_description = $this->seo_description,"
					. " keywords = $this->keywords,"
					. " seo_keywords = $this->seo_keywords,"
					. " content = $this->content,"
                    . " enabled = $this->enabled,"    
					. " active_post = $this->active_post,"
					. " root_id = $this->root_id,"
                    . " level = $this->level,"    
					. " legacy_id = $this->legacy_id"
					. " WHERE id = $this->id";

				$dbObj->query($sql);

			} else {

				$sql = "INSERT INTO BlogCategory"
					. " (title,"
					. " page_title,"
					. " friendly_url,"
					. " category_id,"
					. " featured,"
					. " seo_description,"
					. " keywords,"
					. " seo_keywords,"
					. " content,"
                    . " enabled,"    
					. " active_post,"
                    . " level,"    
					. " legacy_id)"
					. " VALUES"
					. " ($this->title,"
					. " $this->page_title,"
					. " $this->friendly_url,"
					. " $this->category_id,"
					. " $this->featured,"
					. " $this->seo_description,"
					. " $this->keywords,"
					. " $this->seo_keywords,"
					. " $this->content,"
                    . " $this->enabled,"    
					. " $this->active_post,"
                    . " $this->level,"    
					. " $this->legacy_id)";

				$dbObj->query($sql);
				
				$this->id = mysql_insert_id($dbObj->link_id);
				
				/*
				 * Legacy ID to Wordpress
				 */
				if ($empty_legacy_id) {
					unset($sql_legacy_id);
					$sql_legacy_id = "UPDATE BlogCategory SET legacy_id = 'ed_".$this->id."' WHERE id = ".$this->id;
					$dbObj->query($sql_legacy_id);
				}
			}

            $this->root_id = $this->findRootCategoryId($this->id);
			$this->rebuildCategoryTree($this->root_id, 1);
			$this->prepareToUse();
            
            /*
			 * Update full path to categories
			 */
			if ($update_friendlyurl){
				$this->updateFullFriendlyURL();
			}
		}
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->rebuildCategoryTree($category_id, $node_left);
		 * <br /><br />
		 *		//Using this in BlogCategory() class.
		 *		$this->rebuildCategoryTree($category_id, $node_left);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
         * @name rebuildCategoryTree
         * @param integer $category_id
         * @param integer $node_left
         * @return integer
         * @access Public
		*/
		function rebuildCategoryTree($category_id, $node_left) {

			if (($category_id > 0) or ($this->id > 0)) {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if (defined("SELECTED_DOMAIN_ID")) {
					$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$dbObj = db_getDBObject();
				}

				// initializing variables
				$category_id = ($category_id>0)?$category_id:$this->id;
				$node_left = ($node_left>0)?$node_left:1;
				$root_category_id = $this->findRootCategoryId($category_id);

				// saving / adjusting root id
				$sql = 'UPDATE BlogCategory SET root_id = '.$root_category_id.' WHERE id='.$category_id;
				$dbObj->query($sql);

				// the right value of this node is the left value + 1
				$node_right = $node_left+1;

				// get all children of this node
				$sql = 'SELECT id FROM BlogCategory WHERE category_id= '.$category_id;
				$result = $dbObj->query($sql);
				//.' and root_category_id='.$root_category_id
				while ($row = mysql_fetch_assoc($result)) {
					// recursive execution of this function for each
					// child of this node
					// $node_right is the current right value, which is
					// incremented by the rebuild_tree function
					$node_right = $this->rebuildCategoryTree($row['id'], $node_right);
				}

				// we've got the left value, and now that we've processed
				// the children of this node we also know the right value
				$sql = 'UPDATE BlogCategory SET `left` = '.$node_left.', `right` = '.$node_right.', root_id = '.$root_category_id.' WHERE  id = '.$category_id;
				$dbObj->query($sql);
				$sql = 'UPDATE Blog_Category SET `category_node_left` = '.$node_left.', `category_node_right` = '.$node_right.', `category_root_id` = '.$root_category_id.' WHERE `category_id` = '.$category_id;
				$dbObj->query($sql);

				// return the right value of this node + 1
				return $node_right+1;
			}
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->findRootCategoryId($category_id);
		 * <br /><br />
		 *		//Using this in BlogCategory() class.
		 *		$this->findRootCategoryId($category_id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
         * @name findRootCategoryId
         * @param integer $category_id
         * @return integer
         * @access Public
		*/
		function findRootCategoryId($category_id) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			/*
			 * Remove "'" if need
			 */
			$category_id = str_replace("'","",$category_id);

			while($category_id != 0) {
				$sql = "SELECT category_id, id FROM BlogCategory WHERE id = $category_id";
				$result = $dbObj->query($sql);
				$row = mysql_fetch_assoc($result);
				$category_id = $row["category_id"];
				$root_category_id = $row["id"];
			}
			return $root_category_id;
		}


		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->getHierarchy($id, $get_parents, $get_children);
		 * <br /><br />
		 *		//Using this in BlogCategory() class.
		 *		$this->getHierarchy($id, $get_parents, $get_children);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
         * @name getHierarchy
         * @param integer $id
         * @param boolean $get_parents
         * @param boolean $get_children
         * @return string
         * @access Public
		*/
		function getHierarchy($id, $get_parents=false, $get_children=false){
			unset($dbObj, $string_hierarchy);
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "SELECT postcategory.id,
						   postcategory.root_id,
						   postcategory.left,
						   postcategory.right,
						   postcategory.category_id
						FROM BlogCategory postcategory
						WHERE postcategory.id = ".$id;
			
			$result = $dbObj->query($sql);

			if(mysql_num_rows($result) > 0){
				$aux_array = mysql_fetch_assoc($result);

				//To keep the old rules
				if (!$get_parents && !$get_children) {
					if ($aux_array["category_id"] == 0) {
						$get_parents = false;
						$get_children = true;
					}
					else {
						$get_parents = true;
						$get_children = false;
					}
				}
				
				if ($get_children) {
					// Get children
					$sql_aux = "SELECT postcategory.id
										  FROM BlogCategory postcategory
										  WHERE postcategory.root_id = ".$aux_array["root_id"]." AND
												postcategory.left    > ".$aux_array["left"]." AND
												postcategory.right   < ".$aux_array["right"];
				}
				else if ($get_parents) {
					// Get Parents
					$sql_aux = "SELECT postcategory.id
										  FROM BlogCategory postcategory
										  WHERE postcategory.root_id = ".$aux_array["root_id"]." AND
												postcategory.left    < ".$aux_array["left"]." AND
												postcategory.right   > ".$aux_array["right"];
				}

                //$result_hierarchy = $dbObj->query($sql_aux);
				$result_hierarchy = $dbObj->unbuffered_query($sql_aux);
                //if(mysql_num_rows($result_hierarchy) > 0){
                if($result_hierarchy){
                    unset($array_hierarchy);
                    while($row = mysql_fetch_assoc($result_hierarchy)){
                        $array_hierarchy[] = $row["id"];
                    }
                    if (is_array($array_hierarchy)){
                        $string_hierarchy = implode(',',$array_hierarchy);
                    }
				}
				if(string_strlen($string_hierarchy) > 0){
					$string_hierarchy .= ','.$id;
				}else{
					$string_hierarchy = $id;
				}
				return $string_hierarchy;
			}else{
				return false;
			}
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->getHighestLevel($id);
		 * <br /><br />
		 *		//Using this in BlogCategory() class.
		 *		$this->getHighestLevel($id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
         * @name getHighestLevel
         * @param integer $id
         * @return string
         * @access Public
		*/
		function getHighestLevel($id) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);
			
			$ids_children = $this->getHierarchy($id, false, true);
			$max_sublevel = 1;		
			
			if ($ids_children) {
				$sql = "SELECT 
						COUNT(DISTINCT category_id) as max_sublevel
						FROM
						BlogCategory
						WHERE
						id IN ($ids_children) AND
						id != ".$id."
						";
				$result_sublevels = $dbObj->query($sql);
				
				$row = mysql_fetch_array($result_sublevels);
				$max_sublevel += $row["max_sublevel"];
			}
			return $max_sublevel;			
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->Delete();
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name Delete
		 * @access Public
		 */
		function Delete() {

			if ($this->id != 0) {
                
                foreach($this->getFullPath() as $cat_path){
					$cat_id[] = $cat_path["id"];
				}

				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if (defined("SELECTED_DOMAIN_ID")) {
					$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$dbObj = db_getDBObject();
				}
				unset($dbMain);

				$category_ids = $this->getHierarchy($this->id, $get_parents=false, $get_children=true);

				if($category_ids){
					$sql = "SELECT post_id FROM Blog_Category WHERE category_id IN ($category_ids)";
					$posts_ids = array();
					$result = $dbObj->query($sql);
					while($row = mysql_fetch_assoc($result)){
						$posts_ids[] = $row["post_id"];				
							}

					$sql_delete = "DELETE FROM Blog_Category WHERE category_id IN ($category_ids)";
					$dbObj->query($sql_delete);

					$sql_delete = "DELETE FROM BlogCategory WHERE id IN ($category_ids)";
					$dbObj->query($sql_delete);
				}				
				$sql = "UPDATE Banner SET category_id = 0 WHERE category_id = $this->id AND section = 'blog'";
					$dbObj->query($sql);

				$this->updateFullTextItems($posts_ids);
				system_countActivePostByCategory("", $cat_id);

			}
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->retrieveAllCategories(featured);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name retrieveAllCategories
		 * @access Public
		 * @param char $featured
		 */
		function retrieveAllCategories($featured=''){
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);
			
			$sql = "SELECT * FROM BlogCategory WHERE category_id = '0'";
			if ($featured == "on") { 
                $sql .= " AND featured = 'y'";
            }
			$sql .= "  AND enabled = 'y' ORDER BY title";
			$result = $dbObj->unbuffered_query($sql);

			while($row = mysql_fetch_assoc($result)){
                $data[] = $row;
            }
			if ($data) {
                return $data; 
            } else {
                return false;
            }
		}
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->retrieveAllCategoriesXML($featured, $category_id);
		 * <br /><br />
		 *		//Using this in BlogCategory() class.
		 *		$this->retrieveAllCategoriesXML($featured, $category_id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
         * @name retrieveAllCategoriesXML
         * @param string $featured
         * @param integer $category_id
         * @return misc
         * @access Public
		*/
        function retrieveAllCategoriesXML($featured = "", $category_id = 0){
			$sql = "SELECT * FROM BlogCategory WHERE category_id = '".$category_id."'";
			
			if ($featured == "on"){
				$sql .= " AND featured = 'y'";
			}
			
			$sql .= "  AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;

			return system_generateXML("categories", $sql, SELECTED_DOMAIN_ID);
		}
		
		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->getAllCategoriesHierarchyXML($featured, $category_id, $id, $domain_id);
		 * <br /><br />
		 *		//Using this in BlogCategory() class.
		 *		$this->getAllCategoriesHierarchyXML($featured, $category_id, $id, $domain_id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
         * @name getAllCategoriesHierarchyXML
         * @param string $featured
         * @param integer $category_id
         * @param integer $id
         * @param integer $domain_id
         * @return misc
         * @access Public
		*/
		function getAllCategoriesHierarchyXML($featured="", $category_id=0, $id=0, $domain_id = false) {

			$sql = "SELECT 
						BlogCategory_1.id,
						BlogCategory_1.title,
						BlogCategory_1.page_title,
						BlogCategory_1.friendly_url,
						BlogCategory_1.category_id,
						BlogCategory_1.root_id,
						BlogCategory_1.left,
						BlogCategory_1.active_post,
						BlogCategory_1.enabled,
						(	SELECT COUNT(BlogCategory_2.id)
							FROM
								BlogCategory BlogCategory_2
							WHERE BlogCategory_2.left < BlogCategory_1.left
							AND BlogCategory_2.right > BlogCategory_1.right
							AND BlogCategory_2.root_id = BlogCategory_1.root_id
						) level,
						(	SELECT
								COUNT(DISTINCT category_id) as max_sublevel
							FROM
								BlogCategory
							WHERE category_id IN (BlogCategory_1.id)
							AND id != BlogCategory_1.id
							AND title <> ''
                            AND enabled = 'y'
						) children
						FROM
							BlogCategory BlogCategory_1
						WHERE BlogCategory_1.root_id > 0
					";
					
			$sql .= " AND BlogCategory_1.category_id = ".$category_id;
			
			if ($id) {
				$sql .= " AND BlogCategory_1.id IN (".$id.")";
			}
			if ($featured == "on") {
				$sql .= " AND BlogCategory_1.featured = 'y'";
			}

			$sql .= " AND BlogCategory_1.title <> '' AND BlogCategory_1.enabled = 'y'";
			
			$sql .= " ORDER BY BlogCategory_1.title LIMIT ".MAX_SHOW_ALL_CATEGORIES;

			return system_generateXML("categories", $sql, SELECTED_DOMAIN_ID);
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->retrieveAllSubCatById($id, $featured);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name retrieveAllSubCatById
		 * @access Public
		 * @param integer $id
		 * @param char $featured
		 */
		function retrieveAllSubCatById($id='', $featured=''){
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);
			
			$sql = "SELECT * FROM BlogCategory WHERE category_id = $id";
			if ($featured == "on") $sql .= " AND featured = 'y'";
			$sql .= "  AND enabled = 'y' ORDER BY title";

			$result = $dbObj->unbuffered_query($sql);
			while($row = mysql_fetch_assoc($result)){
                $data[] = $row;
            }
            if($data){
                return $data; 
            }else{
                return false;
            }
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->getLevel();
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name getLevel
		 * @access Public
		 */
		function getLevel() {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$cat_level = 0;
			$category_id = $this->getString("id");
			while($category_id != 0) {
				$sql = "SELECT category_id FROM BlogCategory WHERE id = $category_id";
				$result = $dbObj->unbuffered_query($sql);
				$row = mysql_fetch_assoc($result);
				$category_id = $row["category_id"];
				$cat_level++;
			}
			return $cat_level;
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->getFullPath();
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name getFullPath
		 * @access Public
		 */
		function getFullPath() {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

            $fields = "`id`, `category_id`, `active_post`, `featured`, `enabled`, `friendly_url`, `title`";

			$category_id = $this->id;
			$i=0;
			while ($category_id != 0) {
				$sql = "SELECT $fields FROM BlogCategory WHERE id = $category_id";
				//$result = $dbObj->query($sql);
				$result = $dbObj->unbuffered_query($sql);
				$row = mysql_fetch_assoc($result);
				$path[$i]["id"] = $row["id"];
				$path[$i]["dad"] = $row["category_id"];
				$path[$i]["title"] = $row["title"];
				$path[$i]["friendly_url"] = $row["friendly_url"];
				$path[$i]["active_post"] = $row["active_post"];
                $path[$i]["featured"] = $row["featured"];
				$path[$i]["enabled"] = $row["enabled"];
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
		 *		$categObj->updateFullTextItems();
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name updateFullTextItems
		 * @access Public
		 */
		function updateFullTextItems($posts_ids=false) {
		
			if (!$posts_ids) {
				
				if ($this->id) {		
					$category_ids = $this->getHierarchy($this->id, $get_parents=true, $get_children=false);
					$category_ids .= (string_strlen($category_ids) ? "," :"");
					$category_ids .= $this->getHierarchy($this->id, $get_parents=false, $get_children=true);

					if($category_ids){
						$dbMain = db_getDBObject(DEFAULT_DB, true);
						if (defined("SELECTED_DOMAIN_ID")) {
							$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
						} else {
							$dbObj = db_getDBObject();
						}
						unset($dbMain);

						$sql = "SELECT post_id FROM Blog_Category WHERE category_id IN ($category_ids)";
						$result = $dbObj->query($sql);
						
						while ($row = mysql_fetch_array($result)) {
							 if ($row['post_id']) {
								$postObj = new Post($row['post_id']);
								$postObj->setFullTextSearch();
								unset($postObj);
							 }
						}
					}
					return true;
				}
				return false;				
			}
			else {
				foreach ($posts_ids as $post_id) {
					 if ($post_id) {
						$postObj = new Post($post_id);
						$postObj->setFullTextSearch();
						unset($postObj);
					 }
				}
				return true;
			}
		}
		
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->setFeatured();
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name setFeatured
		 * @access Public
		 */
		function setFeatured() {
			if (!$this->id) return false;
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "UPDATE BlogCategory SET featured = 'y' WHERE id = $this->id";
			return $dbObj->query($sql);
		}
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->updateFullFriendlyURL();
		 * <br /><br />
		 *		//Using this in BlogCategory() class.
		 *		$this->updateFullFriendlyURL();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
         * @name updateFullFriendlyURL
         * @access Public
		*/
		function updateFullFriendlyURL(){

		 	$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$db = db_getDBObject();
			}
			unset($dbMain);

		 	 /*
		 	  * Get correct info of category
		 	  */
		 	 $sql = "SELECT BlogCategory.root_id,
		 	 				BlogCategory.left, 
		 	 				BlogCategory.right 
		 	 		FROM BlogCategory WHERE id = ".$this->root_id;
		 	 $result = $db->query($sql);
		 	 if(mysql_num_rows($result) > 0){
			 	 /*
			 	  * Get all children
			 	  */
		 	 	 $row_father = mysql_fetch_assoc($result);
			 	 $sql_children = "SELECT *
				 	 				 FROM BlogCategory
				 	 				WHERE BlogCategory.root_id=".$row_father["root_id"]." AND
				 	 					  BlogCategory.left >=".$row_father["left"]." AND
				 	 					  BlogCategory.right <=".$row_father["right"];
				 $result_children = $db->query($sql_children);
			 	 if(mysql_num_rows($result_children) > 0){
			 	 	while($row_children = mysql_fetch_assoc($result_children)){
			 	 		$cat_aux = new BlogCategory($row_children);
				 	 	$sql = "SELECT friendly_url
									FROM BlogCategory
									WHERE root_id = ".$cat_aux->root_id." AND
										  BlogCategory.left <= ".$cat_aux->left." AND
										  BlogCategory.right >= ".$cat_aux->right."
								ORDER BY root_id,
										 BlogCategory.left,
										 BlogCategory.right";

						$result = $db->query($sql);
						$lines = mysql_num_rows($result);
						if(mysql_num_rows($result) > 0){
							$aux_friendly_url = "";
							while($row = mysql_fetch_assoc($result)){
								$lines--;
								if($row["friendly_url"]){
									$aux_friendly_url  .= $row["friendly_url"].($lines > 0 ? "/":"");
								}
							}
		
							/*
							 * Save full friendly_url
							 */
							$sql_update = "UPDATE BlogCategory SET
								full_friendly_url = '".$aux_friendly_url."'
								WHERE id = ".$cat_aux->id;

							$db->query($sql_update);
						}
					}	
			 	} 	
		 	}
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->countActivePostByCategory($category_id, $domain_id);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name countActivePostByCategory
		 * @access Public
		 * @param integer $category_id
		 * @param integer $domain_id
		 */
		function countActivePostByCategory($category_id = "", $domain_id = false) {
			$category_id = ($category_id != "")? $category_id: $this->id;
			$active_posts = 0;

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id) {
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			} else {
				if (defined("SELECTED_DOMAIN_ID")) {
					$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$dbObj = db_getDBObject();
				}
				unset($dbMain);
			}
            
			// counting posts of this category
			$sql_counter = "SELECT count(distinct a.id) counter
			                 FROM Post a
			                    INNER JOIN Blog_Category b on (a.id = b.post_id)
			                    INNER JOIN BlogCategory c on (b.category_id = c.id)
			                 WHERE (a.status = 'A') 
			                   AND c.`left` >= (select cl.`left` from BlogCategory cl where cl.id = $category_id)
			                   AND c.`right` <= (select cr.`right` from BlogCategory cr where cr.id = $category_id)
			                   AND c.root_id = (select root.root_id from BlogCategory root where root.id = $category_id)";
			$r_counter = $dbObj->unbuffered_query($sql_counter);
			$row_counter = mysql_fetch_assoc($r_counter);
			$active_posts = $row_counter["counter"];

			// counting posts of all subcategories (not only the immediatelly below this)
			$sql_sub = "SELECT id FROM BlogCategory WHERE category_id = $category_id";
			$r_sub = $dbObj->query($sql_sub);
			while ($row_sub = mysql_fetch_assoc($r_sub)) {
				$this->countActivePostByCategory($row_sub["id"]);
			}

			$sql_update = "UPDATE BlogCategory SET active_post = ".$active_posts." WHERE id = ".$category_id;
			$dbObj->query($sql_update);
			if ($this->id == $category_id) {
				$this->active_post = $active_posts;
			}

			return $active_posts;
		}
		
		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->SaveWPToEdir($wp_content);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name SaveWPToEdir
		 * @access Public
		 * @param misc $wp_content
		 */
		function SaveWPToEdir($wp_content){
			
			if(!is_array($wp_content)){
				$wp_content = unserialize($wp_content);
			}

			if(is_array($wp_content)){
						
				/*
				 * Get category ID using legacy ID
				 */
				$db = db_getDBObject();
				$sql = "SELECT * FROM BlogCategory WHERE legacy_id = '"."wp_".$wp_content["fields"]["term_id"]."'";

				$result = $db->query($sql);
				if(mysql_num_rows($result)){
					$row = mysql_fetch_assoc($result);
					$this->makeFromRow($row);
				}
				
				if ($wp_content["fields"]["parent"]){
					
					$sql = "SELECT id, category_id, level FROM BlogCategory WHERE legacy_id = '"."wp_".$wp_content["fields"]["parent"]."'";
					$resultParent = $db->query($sql);
					if(mysql_num_rows($resultParent)){
						$rowParent = mysql_fetch_assoc($resultParent);
						$auxParent = $rowParent["category_id"];
						$lastParent = $rowParent["id"];
						$level = $rowParent["level"];
						if ($level >= 5){
							$level = 5;
							$lastParent = $auxParent;
						} else {
							
							$count = 2;
							while ($auxParent != 0){
								unset($rowParentAux);

								$sql = "SELECT category_id FROM BlogCategory WHERE id = ".$auxParent;
								$resultParentAux = $db->query($sql);
								if(mysql_num_rows($resultParentAux)){
									$rowParentAux = mysql_fetch_assoc($resultParentAux);
								}
								$auxParent = $rowParentAux["category_id"];
								$count++;

							}
							$level = $count;
						}

					}
					
				} else {
					$level = 1;
				}
				
				$fields[0]["name"]		= "title";
				$fields[0]["content"]	= $wp_content["fields"]["name"];
				
				$fields[1]["name"]		= "legacy_id";
				$fields[1]["content"]	= "wp_".$wp_content["fields"]["term_id"];
				
				$fields[2]["name"]		= "category_id";
				$fields[2]["content"]	= $lastParent;
				
				$fields[3]["name"]		= "friendly_url";
				$fields[3]["content"]	= $wp_content["fields"]["slug"];
							
				$fields[4]["name"]		= "page_title";
				$fields[4]["content"]	= $wp_content["fields"]["name"];
				
				$fields[5]["name"]		= "level";
				$fields[5]["content"]	= $level;

                $fields[6]["name"]		= "featured";
				$fields[6]["content"]	= "y";
                
                $fields[7]["name"]		= "enabled";
				$fields[7]["content"]	= "y";
				
				/*
				 * Check if needs create friendly_url
				 */
				if(!$fields[3]["content"]){
					$blogcategory_friendly_url = preg_replace("/[^".FRIENDLYURL_VALIDCHARS."]/", FRIENDLYURL_SEPARATOR, $wp_content["fields"]["slug"]);
					$blogcategory_friendly_url = string_strtolower(preg_replace("/[\\".FRIENDLYURL_SEPARATOR."]{2,}/", FRIENDLYURL_SEPARATOR, $blogcategory_friendly_url));
					$fields[3]["content"]	= $blog_friendly_url;
				}
				
				for($i = 0; $i < count($fields); $i++){
					$this->$fields[$i]["name"] = $fields[$i]["content"];
				}

				$this->Save();
				//Updating items fulltext fields
				if (BLOGCATEGORY_SCALABILITY_OPTIMIZATION != "on" && BLOG_SCALABILITY_OPTIMIZATION != "on") {
					$this->updateFullTextItems();
				}
			}
		}
		
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$categObj->deleteWPCategory($wp_fields);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.5.00
		 * @name deleteWPCategory
		 * @access Public
		 * @param misc $wp_fields
		 */
		function deleteWPCategory($wp_fields) {

			if ($wp_fields["fields"]["id"]) {
				
				$dbObj = db_getDBObject();
				
				$sql = "SELECT id FROM BlogCategory WHERE legacy_id = 'wp_".$wp_fields["fields"]["id"]."'";
				$result = $dbObj->query($sql);
				
				if(mysql_num_rows($result)){
					while($row = mysql_fetch_assoc($result)){
						$this->id = $row["id"];
						$this->Delete();
					}
				}
			}
		}
    }
?>