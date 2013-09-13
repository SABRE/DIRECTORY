function showAdvancedSearch(item_type, template_id, load_cat) {
	
	var aux_data = "fnct=categories&type="+item_type;
	
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
			$("#advanced_search_category_dropdown").html(html)
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
		closeAdvancedSearch(item_type, template_id);
	}
    
	$('#advanced-search').slideDown('slow');
	$('#advanced-search-label').hide();
	$('#advanced-search-label-close').show();
}

function closeAdvancedSearch(item_type, template_id) {
	document.getElementById("advanced-search-button").onclick = function() {
		showAdvancedSearch(item_type, template_id, false);
	}
	$('#advanced-search').slideUp('slow');
	$('#advanced-search-label').show();
	$('#advanced-search-label-close').hide();
}