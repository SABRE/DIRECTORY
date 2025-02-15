<?	
	/**
	*
	*	This file may not be redistributed in whole or part.
	* 	eDirectory IS NOT FREE SOFTWARE
	* 	http://www.edirectory.com | http://www.edirectory.com/license.html
	*
	*	@filesource /classes/class_pagination.php
	*	@author	Arca Solutions
	* 	@version eDirectory 5.2.03
	* 	@since April, 16, 2009
	*	@desc Class to create pagination with period. Reference: http://www.phpsnaps.com/snaps/view/php-pagination-class/
	*
	*/
	
	
	/**
	*	Class to create pagination
	* 	@package Classes
	*	@author Rodrigo Apetito		
	*/	
	class Pagination{
		
		var $show;
		var $aux_page_num;
		
		public function __construct(){
			if(SELECTED_DOMAIN_ID == 1){
				$this->show = 6;
			}elseif(SELECTED_DOMAIN_ID == 2){
				$this->show = 6;
			}elseif(SELECTED_DOMAIN_ID == 4){
				$this->show = 6;
			}else{
				$this->show = 3;
			}
		}
		
		/**
		*	Method to calculate and create a array with all information about pages
		* 	@desc Method to calculate and create a array with all information about pages
		*	@author Rodrigo Apetito	- Arca Solutions
		* 	@param numeric &$total_rows total of results to calculate pages
		* 	@param numeric $rows_per_page total of results in each page
		* 	@param numeric $page_num number of actual page
		* 	@package Classes		
		* 	@since July, 6, 2009
		*	@return array with pages		
		*/
		public function calculate_pages(&$total_rows, $rows_per_page, &$page_num){
			
			$arr = array();
			// calculate last page
			$last_page = ceil($total_rows / $rows_per_page);
			// make sure we are within limits
			$page_num = (int) $page_num;
			
			if ($page_num < 1)
			{
			   $page_num = 1;
			} 
			elseif ($page_num > $last_page)
			{
			   $page_num = $last_page;
			}
			$upto = ($page_num - 1) * $rows_per_page;
			$arr['limit'] = 'LIMIT '.$upto.',' .$rows_per_page;
			$arr['current'] = $page_num;
			
			// property aux_page_num to use in getCodeOfPagination
			$this->aux_page_num = $page_num;
			
			if ($page_num == 1)
				$arr['previous'] = $page_num;
			else
				$arr['previous'] = $page_num - 1;
			if ($page_num == $last_page)
				$arr['next'] = $last_page;
			else
				$arr['next'] = $page_num + 1;
			$arr['last'] = $last_page;
			$arr['info'] = 'Page ('.$page_num.' of '.$last_page.')';
			$arr['pages'] = $this->get_surrounding_pages($page_num, $last_page, $arr['next']);
			return $arr;
		}
		
		/**
		*	Method to calculate period of pages
		* 	@desc Method to calculate period of pages
		*	@author Rodrigo Apetito	- Arca Solutions
		* 	@param numeric $page_num number of actual page
		* 	@param numeric $last_page number of the last page
		* 	@param numeric $next number of next page
		* 	@package Classes		
		* 	@since July, 6, 2009
		*	@return array with pages on period
		*/
		function get_surrounding_pages($page_num, $last_page, $next){
			$arr = array();
			$show = $this->show; // how many boxes
			// at first
			if ($page_num == 1)
			{
				// case of 1 page only
				if ($next == $page_num) return array(1);
				for ($i = 0; $i < $show; $i++)
				{
					if ($i == $last_page) break;
					array_push($arr, $i + 1);
				}
				return $arr;
			}
			// at last
			if ($page_num == $last_page)
			{
				$start = $last_page - $show;
				if ($start < 1) $start = 0;
				for ($i = $start; $i < $last_page; $i++)
				{
					array_push($arr, $i + 1);
				}
				return $arr;
			}
			// at middle
			$start = $page_num - $show;
			if ($start < 1) $start = 0;
			for ($i = $start; $i < $page_num; $i++)
			{
				array_push($arr, $i + 1);
			}
			for ($i = ($page_num + 1); $i < ($page_num + $show); $i++)
			{
				if ($i == ($last_page + 1)) break;
				array_push($arr, $i);
			}
			return $arr;
		}
		
		
		/**
		 * Function to get HTML code of pagination
		 *
		 * @param array $array_pages
		 * @param string $aux_page_url
		 * @param string $aux_url
		 * @return string HTML code of pagination
		 */
		function getCodeOfPagination($array_pages, $aux_page_url, $aux_url = false, $force_total = false){
			
			unset($array_links);
			$array_links["total"] = ($force_total ? $force_total : $array_pages["total"]);
			$array_links["current"] = $array_pages["pages"]["current"];
			$array_links["last_page"] = $array_pages["pages"]["last"];
			
			if($array_pages["show_pages"]){

				if($array_pages["pages"]["previous"] != $this->aux_page_num){
					$array_links["previous"] = "<li><a href='".$aux_page_url.$array_pages["pages"]["previous"].($aux_url ? $aux_url : "")."'>".system_showText(LANG_PAGING_PREVIOUSPAGEMOBILE)."</a></li>";
				}
				
				if(!in_array(1,$array_pages["pages"]["pages"])){
					$array_links["first"] = "				
						<li><a href='".$aux_page_url."1".($aux_url ? $aux_url : "")."'>1</a></li>
						<li>&nbsp;....&nbsp;</li>";		
				}
				
				
				for($i=0;$i<count($array_pages["pages"]["pages"]);$i++){
					$aux_page_code .= "
					<li><a".($array_pages["pages"]["pages"][$i] == $this->aux_page_num ? " class=\"active\" " : "")." href='".$aux_page_url.$array_pages["pages"]["pages"][$i].($aux_url ? $aux_url : "")."'>".$array_pages["pages"]["pages"][$i]."</a></li>";					
				}
				$array_links["pages"] = $aux_page_code;
				
				if(!in_array($array_pages["pages"]["last"],$array_pages["pages"]["pages"])){
					$array_links["last"] = "
					<li>&nbsp;....&nbsp;</li>
					<li><a href='".$aux_page_url.$array_pages["pages"]["last"].($aux_url ? $aux_url : "")."'>".$array_pages["pages"]["last"]."</a></li>";					
				}
				if($array_pages["pages"]["next"] != $this->aux_page_num){
					$array_links["next"] = "						
					<li><a href='".$aux_page_url.$array_pages["pages"]["next"].($aux_url ? $aux_url : "")."'>".system_showText(LANG_PAGING_NEXTPAGEMOBILE)."</a></li>";
					
				}				
			}
			
			return $array_links;
						
		}
		
	function getCodeOfPaginationForListing($array_pages, $aux_page_url, $aux_url = false, $force_total = false){
			
			unset($array_links);
			$array_links["total"] = ($force_total ? $force_total : $array_pages["total"]);
			$array_links["current"] = $array_pages["pages"]["current"];
			$array_links["last_page"] = $array_pages["pages"]["last"];
			//print_r($array_pages);die;
			if($array_pages["show_pages"]){
				if($array_pages["pages"]["previous"] == $this->aux_page_num){
					$array_links["previous"] = "<img src='".DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/prev.png'."'/>";
				}else{
					$array_links["previous"] = "<a href='".$aux_page_url.$array_pages["pages"]["previous"].($aux_url ? $aux_url : "")."'></a>";
				}
				
				if(!in_array(1,$array_pages["pages"]["pages"])){
					$array_links["first"] = "				
						<a href='".$aux_page_url."1".($aux_url ? $aux_url : "")."'>1</a>
						<span>&nbsp;....&nbsp;</span>";		
				}
				
				//$aux_page_code .= "<li style='width:100%'>";
				for($i=0;$i<count($array_pages["pages"]["pages"]);$i++){
					$aux_page_code .= "
					<a".($array_pages["pages"]["pages"][$i] == $this->aux_page_num ? " class=\"active\" " : "")." href='".$aux_page_url.$array_pages["pages"]["pages"][$i].($aux_url ? $aux_url : "")."'>".$array_pages["pages"]["pages"][$i]."</a>";					
				}
				$array_links["pages"] = $aux_page_code;
				
				if(!in_array($array_pages["pages"]["last"],$array_pages["pages"]["pages"])){
					$array_links["last"] = "
					<span>&nbsp;....&nbsp;</span>
					<a href='".$aux_page_url.$array_pages["pages"]["last"].($aux_url ? $aux_url : "")."'>".$array_pages["pages"]["last"]."</a>"; /*</li> */					
				}
				
				if($array_pages["pages"]["next"] == $this->aux_page_num){
					$array_links["next"] = "<img src='".DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/next.png'."'/>";
				}else{
					$array_links["next"] = "<a href='".$aux_page_url.$array_pages["pages"]["next"].($aux_url ? $aux_url : "")."'></a>";
				}
			}
			//print_r($array_links);die;
			return $array_links;
						
		}
	}
?>