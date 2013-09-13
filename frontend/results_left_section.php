<style>
    .form .text, .form .select, .form .radio, .form .checkbox, .form .textarea 
    {
        color: #000000 !important;
        font-size: 13px !important;
        padding:0px !important;
    }

    .side-bar .search-form {
        color: #262626;
        font-size: 13px;
        font-weight: bold;
        margin: 10px;
        text-transform: uppercase;
    }

    a.view-button {
        line-height: 0 !important;
        padding: 10px !important;
        color:#818181 !important;
    }
    .side-bar .search-button {
        background: url("<?= DEFAULT_URL . '/custom/domain_' . SELECTED_DOMAIN_ID . '/theme/default/' ?>schemes/default/images/newImages/update_search_result.png") no-repeat scroll 0 0 transparent;
        cursor: pointer;
        height: 42px;
        margin: 10px;
        width: 220px;
    }

    .side-bar .text-bg {
        margin: 0 10px !important;
    }

    .side-bar .keyword #keyword_go {
        width: 40px !important;
    }

    .side-bar .map-control a {
        color: #818181 !important;
        float: right !important;
        font-size: 10px !important;
        padding: 2px 10px 0 !important;
    }

    .main_zip_div{
        padding:10px;
    }
    .or_div_zip{
        border-bottom:1px dashed #818181;
        width:100px;
        padding-top:10px;
        float:left;
    }
</style>
<?
    $mileRadiusArray = array(
        '' => 'Select mile radius',
        '10' => '10 mile radius',
        '20' => '20 mile radius',
        '50' => '50 mile radius',
        '100' => '100 mile radius',
        '200' => '200 mile radius'
    );
    $placeholder = system_showText(LANG_LABEL_SEARCHKEYWORD2);
    $category_id = (isset($category_id)&&$category_id!="")?$category_id:0;
    if(!isset($category_id_sub) && isset($category_main_id))
    {
            $category_id_sub = $category_id;
    }	
    $location1Obj = new Location1();
    $location1Data = $location1Obj->retrieveLocationByName("United States");	
    if(!isset($location_1) || $location_1=="" || $location_1==0)
    {

             $location_1 = $location1Data["id"];
    }
    
    $searchByKeywordTip2 = system_showText(LANG_LABEL_SEARCHKEYWORDTIP2);
    
    if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) 
    {
        $module_categ = "listing";
        $advancedSearchItem = "listing";
        $action_adv = LISTING_DEFAULT_URL . "/results.php";
        $autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL . '?module=listing';
    }
    elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) 
    {
        $module_categ = "listing";
        $advancedSearchItem = "promotion";
        $action_adv = PROMOTION_DEFAULT_URL . "/results.php";
        $autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL . '?module=promotion';
    } 
    elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) 
    {
        $module_categ = "event";
        $advancedSearchItem = "event";
        $action_adv = EVENT_DEFAULT_URL . "/results.php";
        $autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL . '?module=event';
    } 
    elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) 
    {
        $module_categ = "classified";
        $advancedSearchItem = "classified";
        $action_adv = CLASSIFIED_DEFAULT_URL . "/results.php";
        $autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL . '?module=classified';
    } 
    elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) 
    {
        $module_categ = "article";
        $advancedSearchItem = "article";
        $action_adv = ARTICLE_DEFAULT_URL . "/results.php";
        $autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL . '?module=article';
    } 
    elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) 
    {
        $module_categ = "blog";
        $advancedSearchItem = "blog";
        $action_adv = BLOG_DEFAULT_URL . "/results.php";
        $autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL . '?module=blog';
    }
    else 
    {
        $module_categ = "listing";
        $advancedSearchItem = "listing";
        $action_adv = LISTING_DEFAULT_URL . "/results.php";
        $autocomplete_keyword_url2 = AUTOCOMPLETE_KEYWORD_URL . '?module=listing';
    }
    
    if(!$category_main_id && $category_id)
    {
        $itemType = ucfirst($module_categ)."Category";
        $catObj = new $itemType($category_id);
        if($catObj->getNumber("category_id")!=0)
        {
            $category_main_id = $catObj->getNumber("category_id");
            $catObj2 = new $itemType($catObj->getNumber("category_id"));
            if($catObj2->getNumber("category_id")!=0)
            {
                $category_main_id = $catObj2->getNumber("category_id");
                $category_id_sub= $catObj->getNumber("category_id");
            }
        }
    }
    
    $action_advL = LISTING_DEFAULT_URL . "/results.php";
    $action_advP = PROMOTION_DEFAULT_URL . "/results.php";
    $action_advE = EVENT_DEFAULT_URL . "/results.php";
    $action_advC = CLASSIFIED_DEFAULT_URL . "/results.php";
    $action_advA = ARTICLE_DEFAULT_URL . "/results.php";
    $action_advB = BLOG_DEFAULT_URL . "/results.php";
    
    $listing_cookie_namespace = ACTUAL_MODULE_FOLDER."_result_cookie";
    $listing_cookie_value = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $_SESSION[$listing_cookie_namespace] = $listing_cookie_value;
    
    $categoryFilterDD = "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-loading-location.gif\" alt=\"".system_showText(LANG_WAITLOADING)."\"/>";
    $categoryFilterSubDD = "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-loading-location.gif\" alt=\"".system_showText(LANG_WAITLOADING)."\"/>";
    $categoryFilterSubDD2 = "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-loading-location.gif\" alt=\"".system_showText(LANG_WAITLOADING)."\"/>";
    
    if($location_1>0)
        $js_fileLoader = system_scriptColectorOnReady("$('#location_filter_1').val('".$location_1."');$('#location_filter_1').change();",$js_fileLoader);
    if($location_2>0)
        $js_fileLoader = system_scriptColectorOnReady("setTimeout(function(){ $('#location_filter_2').val('".$location_2."');$('#location_filter_2').change();},1000);",$js_fileLoader);
    if($location_3>0)
        $js_fileLoader = system_scriptColectorOnReady("setTimeout(function(){ $('#location_filter_3').val('".$location_3."');$('#location_filter_3').change();},3000);",$js_fileLoader);
    if($location_4>0)
        $js_fileLoader = system_scriptColectorOnReady("setTimeout(function(){ $('#location_filter_4').val('".$location_4."');$('#location_filter_4').change();},6000);",$js_fileLoader);
    if($location_5>0)
        $js_fileLoader = system_scriptColectorOnReady("setTimeout(function(){ $('#location_filter_5').val('".$location_5."');$('#location_filter_5').change();},9000);",$js_fileLoader);

    if($category_main_id && $category_id_sub && strpos($_SERVER['REQUEST_URI']))
        $js_fileLoader = system_scriptColectorOnReady("showAdvancedSearch('".$advancedSearchItem."', '".$aux_template_id."', false, true,".$category_main_id.",'main',".$category_main_id.",".$category_id_sub.",".$category_id_sub.",$category_id);",$js_fileLoader);
    else if($category_main_id)
        $js_fileLoader = system_scriptColectorOnReady("showAdvancedSearch('".$advancedSearchItem."', '".$aux_template_id."', false, true,".$category_main_id.",'main',".$category_main_id.",".$category_id.");",$js_fileLoader);
    else
        $js_fileLoader = system_scriptColectorOnReady("showAdvancedSearch('".$advancedSearchItem."', '".$aux_template_id."', false, true,".(is_array($category_id)?0:$category_id).",'main');",$js_fileLoader);
    $js_fileLoader = system_scriptColectorOnReady("$('#keyword_filter').watermark('".$placeholder."');",$js_fileLoader);
    $js_fileLoader = system_scriptColectorOnReady("showLocationsFilterTab('".$advancedSearchItem."','".$action_adv."','".(($zip!="")?"proximityFilter":"")."');",$js_fileLoader);
    $js_fileLoader = system_scriptColectorOnReady("$('#advanced-search-button').trigger('click');",$js_fileLoader);
    
    $openSection = "locationFilter";
    if ($zip != "")
        $openSection = "proximityFilter";
    
    if ($advancedSearchItem == "blog" || $advancedSearchItem == "article")
        $openSection = "none";
    
    $_non_default_locations = "";
    $_default_locations_info = "";
    
    $actionFilter = $action_adv;
    
    //$actionFilter = $_SERVER["REQUEST_URI"];
    
    if (EDIR_DEFAULT_LOCATIONS) 
    {
        system_retrieveLocationsInfo ($_non_default_locations, $_default_locations_info);
        $last_default_location	  =	$_default_locations_info[count($_default_locations_info)-1]['type'];
        $last_default_location_id = $_default_locations_info[count($_default_locations_info)-1]['id'];
        if ($_non_default_locations) 
        {
            $objLocationLabel = "Location".$_non_default_locations[0];
            ${"Location".$_non_default_locations[0]} = new $objLocationLabel;
            ${"Location".$_non_default_locations[0]}->SetString("location_".$last_default_location, $last_default_location_id);
            ${"locations".$_non_default_locations[0]} = ${"Location".$_non_default_locations[0]}->retrieveLocationByLocation($last_default_location);
        }
    }
    else
    {
        $_non_default_locations = explode(",", EDIR_LOCATIONS);
        $objLocationLabel = "Location".$_non_default_locations[0];
        ${"Location".$_non_default_locations[0]} = new $objLocationLabel;
        ${"locations".$_non_default_locations[0]}  = ${"Location".$_non_default_locations[0]}->retrieveAllLocation();
    }
?>
<script type="text/javascript">
function submitSearchFilter()
{ 
    $(".where").val($("#where").val()); 
    if(document.search_form_filter.zip_filter.value!="" && document.search_form_filter.zip_filter.value.length < 3)
    {
        alert("Enter at least the first 3 numbers");
        return false;
    } 

    if(document.search_form_filter.dist_loc.value!="" && (isNaN(document.search_form_filter.dist_loc.value)||(document.search_form_filter.dist_loc.value%1!==0)))
    {	
        alert("<?= system_showText(LANG_USE_ONLY_INTEGERS) ?>");
        return false;
    }
    if(typeof document.search_form_filter.category_sub_filter_id2 != 'undefined' && document.search_form_filter.category_sub_filter_id2.value!="")
    {
        if(typeof document.search_form_filter.category_id != 'undefined')
        {
                var element = document.getElementById("category_id");
                element.parentNode.removeChild(element);
        }

        var categ_elt = document.createElement("input");
        categ_elt.setAttribute('name','category_id');
        categ_elt.setAttribute('type','hidden');
        categ_elt.value = document.search_form_filter.category_sub_filter_id2.value;
        document.search_form_filter.appendChild(categ_elt);

        var categ_elt_main = document.createElement("input");
        categ_elt_main.setAttribute('name','category_main_id');
        categ_elt_main.setAttribute('type','hidden');
        categ_elt_main.value = document.search_form_filter.category_filter_id.value;
        document.search_form_filter.appendChild(categ_elt_main);

        var categ_elt_main = document.createElement("input");
        categ_elt_main.setAttribute('name','category_id_sub');
        categ_elt_main.setAttribute('type','hidden');
        categ_elt_main.value = document.search_form_filter.category_sub_filter_id.value;
        document.search_form_filter.appendChild(categ_elt_main);
    }
    else if(typeof document.search_form_filter.category_sub_filter_id != 'undefined' && document.search_form_filter.category_sub_filter_id.value!="")
    {
        if(typeof document.search_form_filter.category_id != 'undefined')
        {
                var element = document.getElementById("category_id");
                element.parentNode.removeChild(element);
        }

        var categ_elt = document.createElement("input");
        categ_elt.setAttribute('name','category_id');
        categ_elt.setAttribute('type','hidden');
        categ_elt.value = document.search_form_filter.category_sub_filter_id.value;
        document.search_form_filter.appendChild(categ_elt);

        var categ_elt_main = document.createElement("input");
        categ_elt_main.setAttribute('name','category_main_id');
        categ_elt_main.setAttribute('type','hidden');
        categ_elt_main.value = document.search_form_filter.category_filter_id.value;
        document.search_form_filter.appendChild(categ_elt_main);

        var categ_elt_main2 = document.createElement("input");
        categ_elt_main2.setAttribute('name','category_id_sub');
        categ_elt_main2.setAttribute('type','hidden');
        categ_elt_main2.value = "";
        document.search_form_filter.appendChild(categ_elt_main2);
    }
    else if(typeof document.search_form_filter.category_filter_id != 'undefined' && document.search_form_filter.category_filter_id.value!="")
    {
        if(typeof document.search_form_filter.category_id != 'undefined')
        {
                var element = document.getElementById("category_id");
                element.parentNode.removeChild(element);
        }
        var categ_elt = document.createElement("input");
        categ_elt.setAttribute('name','category_id');
        categ_elt.setAttribute('type','hidden');
        categ_elt.value = document.search_form_filter.category_filter_id.value;
        document.search_form_filter.appendChild(categ_elt);

        var categ_elt_main = document.createElement("input");
        categ_elt_main.setAttribute('name','category_id_sub');
        categ_elt_main.setAttribute('type','hidden');
        categ_elt_main.value = "";
        document.search_form_filter.appendChild(categ_elt_main);
    }
    
    if(!(document.search_form_filter.keyword_filter.value=="<?= $placeholder ?>"||document.search_form_filter.keyword_filter.value==""))
        document.search_form_filter.keyword.value = document.search_form_filter.keyword_filter.value;

    var loc_search = false;
    
    if(document.search_form_filter.action.indexOf("blog")!= -1 || document.search_form_filter.action.indexOf("article") != -1 )
    {
        if(typeof document.search_form_filter.location_1 != 'undefined')
        {
            var element = document.getElementById("location_1");
            element.parentNode.removeChild(element);
        }
        if(typeof document.search_form_filter.location_2 != 'undefined')
        {
            var element = document.getElementById("location_2");
            element.parentNode.removeChild(element);
        }
        if(typeof document.search_form_filter.location_3 != 'undefined')
        {
            var element = document.getElementById("location_3");
            element.parentNode.removeChild(element);
        }
        if(typeof document.search_form_filter.location_4 != 'undefined')
        {
            var element = document.getElementById("location_4");
            element.parentNode.removeChild(element);
        }
        if(typeof document.search_form_filter.location_5 != 'undefined')
        {
            var element = document.getElementById("location_5");
            element.parentNode.removeChild(element);
        }
        if(typeof document.search_form_filter.zip_top != 'undefined')
        {
            var element = document.getElementById("zip_top");
            element.parentNode.removeChild(element);
        }
        if(typeof document.search_form_filter.dist_top != 'undefined')
        {
            var element = document.getElementById("dist_top");
            element.parentNode.removeChild(element);
        }
        if(typeof document.search_form_filter.dist_top_loc != 'undefined')
        {
            var element = document.getElementById("dist_top_loc");
            element.parentNode.removeChild(element);
        }
    }
    else
    {
        if(document.search_form_filter.zip_filter.value!="")
            document.search_form_filter.zip_top.value = document.search_form_filter.zip_filter.value;
        if(document.search_form_filter.dist_filter.value!="")
            document.search_form_filter.dist_top.value = document.search_form_filter.dist_filter.value;
        if(document.search_form_filter.dist_loc.value!="")
            document.search_form_filter.dist_top_loc.value = document.search_form_filter.dist_loc.value;
        if( typeof document.search_form_filter.location_filter_1 != 'undefined' && document.search_form_filter.location_filter_1.value!="")
        {
            if(typeof document.search_form_filter.location_1 != 'undefined')
            {
                var element = document.getElementById("location_1");
                element.parentNode.removeChild(element);
            }
            var loc_elt = document.createElement("input");
            loc_elt.setAttribute('name','location_1');
            loc_elt.setAttribute('type','hidden');
            loc_elt.value = document.search_form_filter.location_filter_1.value;
            document.search_form_filter.appendChild(loc_elt);
            loc_search = true;
        }
        if( typeof document.search_form_filter.location_filter_2 != 'undefined' && document.search_form_filter.location_filter_2.value!="")
        {
            if(typeof document.search_form_filter.location_2 != 'undefined')
            {
                var element = document.getElementById("location_2");
                element.parentNode.removeChild(element);
            }
            var loc_elt = document.createElement("input");
            loc_elt.setAttribute('name','location_2');
            loc_elt.setAttribute('type','hidden');
            loc_elt.value = document.search_form_filter.location_filter_2.value;
            document.search_form_filter.appendChild(loc_elt);
            loc_search = true;
        }
        if( typeof document.search_form_filter.location_filter_3 != 'undefined' && document.search_form_filter.location_filter_3.value!="")
        {
            if(typeof document.search_form_filter.location_3 != 'undefined')
            {
                var element = document.getElementById("location_3");
                element.parentNode.removeChild(element);
            }
            var loc_elt = document.createElement("input");
            loc_elt.setAttribute('name','location_3');
            loc_elt.setAttribute('type','hidden');
            loc_elt.value = document.search_form_filter.location_filter_3.value;
            document.search_form_filter.appendChild(loc_elt);
            loc_search = true;
        }
        if( typeof document.search_form_filter.location_filter_4 != 'undefined' && document.search_form_filter.location_filter_4.value!="")
        {
            if(typeof document.search_form_filter.location_4 != 'undefined')
            {
                var element = document.getElementById("location_4");
                element.parentNode.removeChild(element);
            }
            var loc_elt = document.createElement("input");
            loc_elt.setAttribute('name','location_4');
            loc_elt.setAttribute('type','hidden');
            loc_elt.value = document.search_form_filter.location_filter_4.value;
            document.search_form_filter.appendChild(loc_elt);
            loc_search = true;
        }
        if( typeof document.search_form_filter.location_filter_5 != 'undefined' && document.search_form_filter.location_filter_5.value!="")
        {
            if(typeof document.search_form_filter.location_5 != 'undefined')
            {
                var element = document.getElementById("location_5");
                element.parentNode.removeChild(element);
            }
            var loc_elt = document.createElement("input");
            loc_elt.setAttribute('name','location_5');
            loc_elt.setAttribute('type','hidden');
            loc_elt.value = document.search_form_filter.location_filter_5.value;
            document.search_form_filter.appendChild(loc_elt);
            loc_search = true;
        }
    }
    if(loc_search == false && typeof document.search_form_filter.where != 'undefined')	
    {
        document.search_form_filter.where.value="";
        if(typeof document.search_form_filter.location_1 != 'undefined' && document.search_form_filter.location_filter_1)
            document.search_form_filter.location_filter_1.value="";
        if(typeof document.search_form_filter.location_2 != 'undefined' && document.search_form_filter.location_filter_2)
            document.search_form_filter.location_filter_2.value="";
        if(typeof document.search_form_filter.location_3 != 'undefined' && document.search_form_filter.location_filter_3)
            document.search_form_filter.location_filter_3.value="";
        if(typeof document.search_form_filter.location_4 != 'undefined' && document.search_form_filter.location_filter_4)
            document.search_form_filter.location_filter_4.value="";
        if(typeof document.search_form_filter.location_5 != 'undefined' && document.search_form_filter.location_filter_5)
            document.search_form_filter.location_filter_5.value="";

    }
}
</script>
<form onsubmit="return submitSearchFilter(); " id="search_form_filter" class="form" action="<?= $actionFilter ?>" method="get" name="search_form_filter">
    <div id="keywordFilter" class="keyword">
        <input type="text" name="keyword" id="keyword_filter" value="<?= $keyword ?>"/>
        <input type="button" id="keyword_go" />
    </div>
    <div class="border-separator"></div>
    <div class="search-form">FILTER</div>
    <div class="dropdown-bg"> 
        <select onchange="sendParameter(this.value);" class="dropdown">
            <option value="listing" <?= (($advancedSearchItem == "listing") ? "selected=\"selected\"" : "") ?>>Directory</option>
            <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
                <option value="event" <?= (($advancedSearchItem == "event") ? "selected=\"selected\"" : "") ?>><?= ucwords(system_showText(EVENT_FEATURE_NAME)) ?></option>
            <? } ?>
            <? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
                <option value="classified" <?= (($advancedSearchItem == "classified") ? "selected=\"selected\"" : "") ?>><?= ucwords(system_showText(CLASSIFIED_FEATURE_NAME)) ?></option>
            <? } ?>
            <? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
                <option value="article" <?= (($advancedSearchItem == "article") ? "selected=\"selected\"" : "") ?>><?= ucwords(system_showText(ARTICLE_FEATURE_NAME)) ?></option>
            <? } ?>
            <? if (PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on" && CUSTOM_PROMOTION_FEATURE == "on") { ?>
                <option value="promotion" <?= (($advancedSearchItem == "promotion") ? "selected=\"selected\"" : "") ?>><?= ucwords(system_showText(PROMOTION_FEATURE_NAME)) ?></option>
            <? } ?>
            <? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
                <option value="blog" <?= (($advancedSearchItem == "blog") ? "selected=\"selected\"" : "") ?>><?= ucwords(system_showText(BLOG_FEATURE_NAME)) ?></option>
            <? } ?>
        </select>
    </div>
    <div id="categoriesFilterWrap">
        <div id="categoriesFilter" class="dropdown-bg"><?= $categoryFilterDD; ?></div>
        <div id="subCategoriesFilter" class="dropdown-bg" <?= (($category_id && $category_main_id) ? "" : "style=\"display:none\"") ?>><?= $categoryFilterSubDD; ?></div>
        <div id="subCategoriesFilter2" class="dropdown-bg" <?= (($category_id && $category_main_id && $category_id_sub) ? "" : "style=\"display:none\"") ?>><?= $categoryFilterSubDD2; ?></div>
    </div>
    <div class="border-separator"></div>
    <?
    unset($showLoc);
    if ($_default_locations_info) {
        foreach ($_default_locations_info as $info) {
            if ($info["show"] == "y") {
                $showLoc = true;
                break;
            }
        }
    }
    
    if (${"locations" . $_non_default_locations[0]} || $showLoc) {
        $filter_search = true;
        $adv_s = $advanced_search;
        $advanced_search = false;
        ?>
        <div id="scrolltobutton">
            <div id="LocationbaseAdvancedSearchFilter">
                <? include(EDIRECTORY_ROOT . "/includes/code/load_location.php"); ?>
            </div>
        </div>
        <?
        $advanced_search = $adv_s;
    }
    ?>
    <div class="main_zip_div">
        <div class="or_div_zip"></div>
        <div class="search-form" style="float:left;line-height:1px;margin:10px 0px 10px 0px;">OR</div>
        <div class="or_div_zip"></div>
    </div>
    <input type="text" name="zip" id="zip_filter" value="<?= $zip ?>" class="text-bg" />
    <span style="padding-left:10px;color:#818181;">Enter at least the first 3 numbers</span>
    <div class="dropdown-bg">
        <select id="dist_filter" name="dist_loc" class="dropdown">
            <? foreach ($mileRadiusArray as $mile => $mileValue) { ?>
                <? if ($dist_loc == $mile) { ?>
                    <option value="<?= $mile ?>" selected="selected"><?= $mileValue ?></option>
                <? } else { ?>
                    <option value="<?= $mile ?>"><?= $mileValue ?></option>
                <? } ?>
            <? } ?>
        </select>
    </div>
    <div class="border-separator"></div>
    <button type="submit"  class="search-button" ></button>
    <div class="border-separator"></div>
    <input type="hidden" name="where" class="where" value="<?= $where; ?>" />
</form>

<script>
$(document).ready(function(){
    $('#keyword_go').click(function(){
        $('.search-button').trigger('click');
    });	
    $('#keyword_filter').bind('keypress', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13) { 
            $('.search-button').trigger('click');
        }
    });
    $('#zip_filter').click(function(){
        $('.clear-location').find('a').trigger('click');
    });
    $('#zip_filter').watermark('Zip code');  
    
    
    
    $('#LocationbaseAdvancedSearchFilter select').change(function(){
    });
});
function sendParameter(value)
{
    listing_form_action(value); 
    showAdvancedSearch(value, '', false, true,0,'main')
}
function listing_form_action(value)
{
    value =  value.toString();
    switch(value)
    {
        case 'event':
            var action = '<?php echo $action_advE; ?>';
            break;
        case 'classified':
            var action = '<?php echo $action_advC; ?>';
            break;
        case 'article':
            var action = '<?php echo $action_advA; ?>';
            break;
        case 'promotion':
            var action = '<?php echo $action_advP; ?>';
            break;
        case 'blog':
            var action = '<?php echo $action_advB; ?>';
            break;
        default:
            var action = '<?php echo $action_advL; ?>';
            break;
    }
        action = action.toString();
        $("#search_form_filter").attr("action", action);
}
</script>
