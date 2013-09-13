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
	# * FILE: /classes/class_content.php
	# ----------------------------------------------------------------------------------------------------

	/**
	 * <code>
	 *		$contentObj = new Content($id);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @package Classes
	 * @name Content
	 * @method Content
	 * @method makeFromRow
	 * @method Save
	 * @method Delete
	 * @method isRepeated
	 * @method isRepeatedURL
	 * @method retrieveAllContents
	 * @method retrieveIDByType
	 * @method retrieveIDByURL
	 * @method retrieveContentByType
	 * @method retrieveContentByURL
	 * @method retrieveContentInfoByType
	 * @method retrieveContentInfoByURL
	 * @method getTimeString
	 * @access Public
	 */
	class Content extends Handle {

		/**
		 * @var integer
		 * @access Private
		 */
		var $id;
		/**
		 * @var date
		 * @access Private
		 */
		var $updated;
		/**
		 * @var string
		 * @access Private
		 */
		var $type;
		/**
		 * @var string
		 * @access Private
		 */
		var $title;
		/**
		 * @var string
		 * @access Private
		 */
		var $description;
		/**
		 * @var string
		 * @access Private
		 */
		var $keywords;
		/**
		 * @var string
		 * @access Private
		 */
		var $url;
		/**
		 * @var string
		 * @access Private
		 */
		var $content;
		/**
		 * @var string
		 * @access Private
		 */
		var $section;
		/**
		 * @var integer
		 * @access Private
		 */
		var $sitemap;

		/**
		 * <code>
		 *		$contentObj = new Content($id);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Content
		 * @access Public
		 * @param integer $var
		 */
		function Content($var='') {

			if (is_numeric($var) && ($var)) {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if (defined("SELECTED_DOMAIN_ID")) {
					$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$db = db_getDBObject();
				}

				unset($dbMain);

				$sql = "SELECT * FROM Content WHERE id = $var";

				$row = mysql_fetch_array($db->query($sql));
				$row['id'] = $var;
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
		 * @version 8.0.00
		 * @name makeFromRow
		 * @access Public
		 * @param array $row
		 */
		function makeFromRow($row='') {

			$this->id			= ($row['id'])			? $row['id']			: 0;
			$this->updated		= ($row['updated'])		? $row['updated']		: 0;
			$this->type			= ($row['type'])		? $row['type']			: "";
			$this->title		= ($row['title'])		? $row['title']			: "";
			$this->description	= ($row['description'])	? $row['description']	: "";
			$this->keywords		= ($row['keywords'])	? $row['keywords']		: "";
			$this->url			= ($row['url'])			? $row['url']			: "";
			$this->section		= ($row['section'])		? $row['section']		: "";
			$this->content		= ($row['content'])		? $row['content']		: "";
			$this->sitemap		= ($row['sitemap'])		? 1						: 0;

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->Save();
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->Save();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Save
		 * @access Public
		 */
		function Save() {

			$this->prepareToSave();

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			if ($this->id) {
                
                $sql =	"UPDATE Content SET"
							. " updated = NOW(), "
							. " type = $this->type,"
							. " title = $this->title,"
							. " description = $this->description,"
							. " keywords = $this->keywords,"
							. " url = $this->url,"
							. " section  = $this->section,"
							. " content = $this->content,"
							. " sitemap = $this->sitemap"
							. " WHERE id = $this->id";
                $dbObj->query($sql);

			} else {

				$sql =	"INSERT INTO Content"
							. " (type, "
							. " title, "
							. " description, "
							. " keywords, "
							. " url, "
							. " section, "
							. " content, "
							. " sitemap)"
							. " VALUES"
							. " ($this->type,"
							. " $this->title,"
							. " $this->description,"
							. " $this->keywords,"
							. " $this->url,"
							. " $this->section,"
							. " $this->content,"
							. " $this->sitemap)";

                $dbObj->query($sql);
                $this->id = mysql_insert_id($dbObj->link_id);

			}

			$this->prepareToUse();

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->Delete();
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Delete
		 * @access Public
		 */
		function Delete() {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			$sql = "DELETE FROM Content WHERE id = $this->id";
			$dbObj->query($sql);

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->isRepeated();
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->isRepeated();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name isRepeated
		 * @access Public
		 */
		function isRepeated(){
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "SELECT * FROM Content WHERE type = ".db_formatString($this->type)."";
			if($this->id) $sql .= " AND id != $this->id";
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) return true; else return false;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->isRepeatedURL();
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->isRepeatedURL();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name isRepeatedURL
		 * @access Public
		 */
		function isRepeatedURL() {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "SELECT * FROM Content WHERE url = ".db_formatString($this->url)."";
			if($this->id) $sql .= " AND id != $this->id";
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) return true; else return false;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->retrieveAllContents();
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->retrieveAllContents();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveAllContents
		 * @access Public
		 */
		function retrieveAllContents(){
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "SELECT * FROM Content ORDER BY type";
			$result = $dbObj->query($sql);
			while($row = mysql_fetch_assoc($result)) $data[] = $row;
			if ($data) return $data; else return false;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->retrieveIDByType($contenttype);
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->retrieveIDByType($contenttype);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveIDByType
		 * @access Public
		 * @param string $contenttype
		 */
		function retrieveIDByType($contenttype){
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "SELECT id FROM Content WHERE type = ".db_formatString($contenttype)."";
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) return $row["id"]; else return false;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->retrieveIDByURL($contenturl);
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->retrieveIDByURL($contenturl);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveIDByURL
		 * @access Public
		 * @param string $contenturl
		 */
		function retrieveIDByURL($contenturl){
			if (!$contenturl) return false;
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "SELECT id FROM Content WHERE url = ".db_formatString($contenturl)."";
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) return $row["id"]; else return false;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->retrieveContentByType($contenttype);
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->retrieveContentByType($contenttype);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveContentByType
		 * @access Public
		 * @param string $contenttype
		 */
		function retrieveContentByType($contenttype){
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			$sql = "SELECT content FROM Content WHERE type = ".db_formatString($contenttype)."";

			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) return $row["content"]; else return false;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->retrieveContentByURL($contenturl);
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->retrieveContentByURL($contenturl);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveContentByURL
		 * @access Public
		 * @param string $contenturl
		 */
		function retrieveContentByURL($contenturl){
			if (!$contenturl) return false;
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			$sql = "SELECT content FROM Content WHERE url = ".db_formatString($contenturl)."";

			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) return $row["content"]; else return false;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->retrieveContentInfoByType($contenttype);
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->retrieveContentInfoByType($contenttype);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveContentInfoByType
		 * @access Public
		 * @param string $contenttype
		 */
		function retrieveContentInfoByType($contenttype){
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			$sql = "SELECT * FROM Content WHERE type = ".db_formatString($contenttype)."";
			
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row){
				return $row;
			}else{
				return false;
			}
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$contentObj->retrieveContentInfoByURL($contenturl);
		 * <br /><br />
		 *		//Using this in Content() class.
		 *		$this->retrieveContentInfoByURL($contenturl);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveContentInfoByURL
		 * @access Public
		 * @param string $contenturl
		 */
		function retrieveContentInfoByURL($contenturl){
			if (!$contenturl) return false;
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			$sql = "SELECT * FROM Content WHERE url = ".db_formatString($contenturl)."";
            
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) return $row; else return false;
		}
	}
?>