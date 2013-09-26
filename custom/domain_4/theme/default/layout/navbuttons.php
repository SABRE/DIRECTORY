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
		<ul class="navbuttons">
			<li id="nhdarchery"><a href="<?echo($navbuttonurl)?>listing/archery-firearms"><img src="<?echo($navbuttonurl)?>custom/domain_2/theme/default/schemes/default/images/iconography/ArcheryFirearms.png" alt="Archery and Firearms"/></a>
			</li>
			<li id="nhdclothing"><a href="<?echo($navbuttonurl)?>listing/clothing-equipment"><img src="<?echo($navbuttonurl)?>custom/domain_2/theme/default/schemes/default/images/iconography/ClothingEquipment.png" alt="Clothing and Equipment"/></a>
			</li>
			<li id="nhdassociations"><a href="<?echo($navbuttonurl)?>listing/associations-organizations"><img src="<?echo($navbuttonurl)?>custom/domain_2/theme/default/schemes/default/images/iconography/AssociationsOrganizations.png" alt="Associations and Organizations"/></a>
			</li>
			<li id="nhdguides"><a href="<?echo($navbuttonurl)?>listing/guides-outfitters"><img src="<?echo($navbuttonurl)?>custom/domain_2/theme/default/schemes/default/images/iconography/GuidesOutfitters.png" alt="Guides and Outfitters"/></a>
			</li>
			<li id="nhdtaxidermy"><a href="<?echo($navbuttonurl)?>listing/taxidermy-meat-processing"><img src="<?echo($navbuttonurl)?>custom/domain_2/theme/default/schemes/default/images/iconography/Taxidermy.png" alt="Taxidermy and Meat Processing"/></a>
			</li>
			<li id="nhdproperty"><a href="<?echo($navbuttonurl)?>listing/property"><img src="<?echo($navbuttonurl)?>custom/domain_2/theme/default/schemes/default/images/iconography/Property.png" alt="Property and Preserves"/></a>
			</li>
			<li id="nhddogs"><a href="<?echo($navbuttonurl)?>listing/hunting-dogs"><img src="<?echo($navbuttonurl)?>custom/domain_2/theme/default/schemes/default/images/iconography/HuntingDogs.png" alt="Hunting Dogs"/></a>
			</li>
		</ul>
	</div>