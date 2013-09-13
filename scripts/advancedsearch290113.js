function showAdvancedSearch(item_type, template_id, load_cat, filter, selectedId, show, main_id, selectedSub) {
	
	var aux_data = "fnct=categories&type="+item_type;
	
	if(filter)
	{
		aux_data = aux_data+"&filter=1";
		if(show)
			aux_data = aux_data+"&show_type="+show;
		if(main_id)
			aux_data = aux_data+"&main_id="+main_id;
	}
	
	if (load_cat){
		/*
		 * Load dropdown using ajax
		 */
		if (template_id > 0) {
			aux_data += "&template_id="+template_id;
		}

		$.ajax({
		  url: DEFAULT_URL+"/advancedsearch_categories.php",
		  context: document.body,
		  data: aux_data,
		  success: function(html){
			if(filter)
			{
				if(show=="sub")
				{
					$("#subCategoriesFilter").hide();
					if(html!="empty" && main_id!="")
					{
						$("#subCategoriesFilter").html(html);
						$("#subCategoriesFilter").show();
					}
				}
				else
				{
					$("#subCategoriesFilter").hide();
					$("#categoriesFilter").html(html);
					showAdvancedSearch(item_type, template_id, load_cat, filter, selectedSub, 'sub', selectedId);
					
				}
			}
			else
				$("#advanced_search_category_dropdown").html(html);
		  }
		});	
	}
	if (filter){
		/*
		 * Load dropdown using ajax
		 */
		if (template_id > 0) {
			aux_data += "&template_id="+template_id;
		}
		if(selectedId && selectedId!=0) {
			aux_data += "&category_id="+selectedId;
		}
		if(selectedSub && selectedSub!=0)
			aux_data += "&category_id_sub="+selectedSub;
		
		$.ajax({
		  url: DEFAULT_URL+"/advancedsearch_categories.php",
		  context: document.body,
		  data: aux_data,
		  success: function(html){
			if(filter)
			{
				if(show=="sub")
				{
					$("#subCategoriesFilter").hide();
					if(html!="empty" && main_id!="")
					{
						$("#subCategoriesFilter").html(html);
						$("#subCategoriesFilter").show();
					}
				
				}
				else
				{
					$("#subCategoriesFilter").hide();
					$("#categoriesFilter").html(html);
					showAdvancedSearch(item_type, template_id, load_cat, filter, selectedSub, 'sub', selectedId);
					
				}
			}
		  }
		});	
	}

	if (document.getElementById("locations_default_where")){
		if (document.getElementById("locations_default_where").value){
			if (document.getElementById("locations_default_where_replace").value == "yes"){
                document.getElementById("where").value = document.getElementById("locations_default_where").value;
            }
        }
	}
    
    document.getElementById("advanced-search-button").onclick = function() {
    	if(filter)
    		
    		closeAdvancedSearch(item_type, template_id, filter);
    	else
    		closeAdvancedSearch(item_type, template_id);
	}
    
    if(filter){}
    else
    {
		$('#advanced-search').slideDown('slow');
		$('#advanced-search-label').hide();
		$('#advanced-search-label-close').show();
    }
}

function closeAdvancedSearch(item_type, template_id, filter) {
	document.getElementById("advanced-search-button").onclick = function() {
		if(filter)
			showAdvancedSearch(item_type, template_id, filter);
		else
			showAdvancedSearch(item_type, template_id, false);
	}
	$('#advanced-search').slideUp('slow');
	$('#advanced-search-label').show();
	$('#advanced-search-label-close').hide();
}