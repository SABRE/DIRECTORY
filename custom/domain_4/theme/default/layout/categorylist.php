<?			
			$neworoldsite=$_SERVER['PHP_SELF'];
			if ( strstr( $neworoldsite, "9700" ) )
			{ $navbuttonurl = '/9700/'; 
			} 
			else
			{	$navbuttonurl = '/';
			
			 } 
			 ?>
	
	
	
	<div class="navigationbuttons">
		<h2>
			<span>Browse Directory by Category</span>
			<a class="view-more" href="<?echo($navbuttonurl)?>listing/allcategories.php">View all Categories</a>
		</h2>
		<? include(system_getFrontendPath("browsebycategory_listings.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
	</div>