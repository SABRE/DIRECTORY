

/* File: /scripts/location.js */

var filtered=false;var prev_filtered=false;function containerReload(){var Content;if(typeof in_banner!=undefined&&in_banner==true)
var item=true;if($.browser.msie&&$.browser.version==6){try{xmlhttp=new XMLHttpRequest();}
catch(ee){try{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");}
catch(e){try{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
catch(E){xmlhttp=false;}}}
try{if(filtered==true)
{Content=document.getElementById('LocationbaseAdvancedSearchFilter').innerHTML;document.getElementById('LocationbaseAdvancedSearchFilter').innerHTML="";document.getElementById('LocationbaseAdvancedSearchFilter').innerHTML=Content;filtered=false;}
else
{Content=document.getElementById('LocationbaseAdvancedSearch').innerHTML;document.getElementById('LocationbaseAdvancedSearch').innerHTML="";document.getElementById('LocationbaseAdvancedSearch').innerHTML=Content;}}catch(e){Content=document.getElementById('formsLocation').innerHTML;document.getElementById('formsLocation').innerHTML="";document.getElementById('formsLocation').innerHTML=Content;}}
if(item==true)
{setTimeout(function(){$("#category_id").change();},1000);}}
function loadLocationSitemgrMembers(url,edir_locations,level,childLevel,id){var edir_locations=edir_locations.split(',');if(!isNaN(id)){for(i=0;i<edir_locations.length;i++){if(edir_locations[i]>level){text=$("#l_location_"+edir_locations[i]).attr("text");$("#location_"+edir_locations[i]).html("<option id=\"l_location_"+edir_locations[i]+"\" value=\"\">"+text+"</option>");$('#div_location_'+edir_locations[i]).css('display','none');$('#new_location'+edir_locations[i]+'_field').attr('value','');$('#div_new_location'+edir_locations[i]+'_field').css('display','none');}}
$("#div_location_"+childLevel).css("display","");$('#location_'+childLevel).css('display','none');$('#div_img_loading_'+childLevel).css('display','');$('#box_no_location_found_'+childLevel).css('display','none');try{$('#div_select_'+childLevel).css('display','none');}catch(e){}
$.get(url+"/location.php",{id:id,level:level,childLevel:childLevel,type:'byId'},function(location){if(location!="empty"){var text=$("#l_location_"+childLevel).attr("text");$("#location_"+childLevel).html(location);$("#l_location_"+childLevel).html(text);$('#location_'+childLevel).css('display','');try{$('#div_select_'+childLevel).css('display','');}catch(e){}
display_level_limit=childLevel;}else{if(!id)
$("#div_location_"+childLevel).css("display",'none');else{try{$('#div_select_'+childLevel).css('display','');}catch(e){}
$('#box_no_location_found_'+childLevel).css('display','');}}
if(childLevel&&id)
$('#div_new_location'+childLevel+'_link').css('display','');else
$('#div_new_location'+childLevel+'_link').css('display','none');$('#div_img_loading_'+childLevel).css('display','none');});}
containerReload();}
function loadLocationSitemgrMembersFilter(url,edir_locations,level,childLevel,id){var edir_locations=edir_locations.split(',');if(!isNaN(id)){for(i=0;i<edir_locations.length;i++){if(edir_locations[i]>level){text=$("#l_location_filter_"+edir_locations[i]).attr("text");$("#location_filter_"+edir_locations[i]).html("<option id=\"l_location_filter_"+edir_locations[i]+"\" value=\"\">"+text+"</option>");$('#div_location_filter_'+edir_locations[i]).css('display','none');$('#new_location_filter_'+edir_locations[i]+'_field').attr('value','');$('#div_new_location_filter_'+edir_locations[i]+'_field').css('display','none');}}
$("#div_location_filter_"+childLevel).css("display","");$('#locations_filter_clear').css('display','');$('#location_filter_'+childLevel).css('display','none');$('#div_img_loading_filter_'+childLevel).css('display','');$('#box_no_location_filter_found_'+childLevel).css('display','none');try{$('#div_select_filter_'+childLevel).css('display','none');}catch(e){}
$.get(url+"/locationFilter.php",{id:id,level:level,childLevel:childLevel,type:'byId'},function(location){if(location!="empty"){var text=$("#l_location_filter_"+childLevel).attr("text");$("#location_filter_"+childLevel).html(location);$("#l_location_filter_"+childLevel).html(text);$('#location_filter_'+childLevel).css('display','');try{$('#div_select_filter_'+childLevel).css('display','');}catch(e){}
display_level_limit=childLevel;}else{if(!id)
$("#div_location_filter_"+childLevel).css("display",'none');else{try{$('#div_select_filter_'+childLevel).css('display','');}catch(e){}
$('#box_no_location_filter_found_'+childLevel).css('display','');}}
if(childLevel&&id)
$('#div_new_location_filter_'+childLevel+'_link').css('display','');else
$('#div_new_location_filter_'+childLevel+'_link').css('display','none');$('#div_img_loading_filter_'+childLevel).css('display','none');});}
filtered=true;containerReload();}
function loadLocation(url,edir_locations,level,childLevel,id,showClear){var aux_edir_locations=edir_locations;var edir_locations=edir_locations.split(',');if(!isNaN(id)){for(i=0;i<edir_locations.length;i++){if(edir_locations[i]>level){text=$("#l_location_"+edir_locations[i]).attr("text");$("#location_"+edir_locations[i]).html("<option id=\"l_location_"+edir_locations[i]+"\" value=\"\">"+text+"</option>");$('#div_location_'+edir_locations[i]).css('display','none');$('#new_location'+edir_locations[i]+'_field').attr('value','');$('#div_new_location'+edir_locations[i]+'_field').css('display','none');}}
$("#div_location_"+childLevel).css("display","");$('#location_'+childLevel).css('display','none');$('#location_'+level).attr('disabled','true');$('#div_img_loading_'+childLevel).css('display','');if($('#locations_clear')){$('#locations_clear').css('display','none');}
$('#box_no_location_found_'+childLevel).css('display','none');try{$('#div_select_'+childLevel).css('display','none');}catch(e){}
$.get(url+"/location.php",{id:id,level:level,childLevel:childLevel,type:'byId'},function(location){if(location!="empty"){var text=$("#l_location_"+childLevel).attr("text");$("#location_"+childLevel).html(location);$("#l_location_"+childLevel).html(text);$('#location_'+childLevel).css('display','');try{$('#div_select_'+childLevel).css('display','');}catch(e){}
display_level_limit=childLevel;}else{if(!id)
$("#div_location_"+childLevel).css("display",'none');else{try{$('#div_select_'+childLevel).css('display','');}catch(e){}
$('#box_no_location_found_'+childLevel).css('display','');}}
if(childLevel&&id)
$('#div_new_location'+childLevel+'_link').css('display','');else
$('#div_new_location'+childLevel+'_link').css('display','none');$('#location_'+level).attr('disabled','');$('#div_img_loading_'+childLevel).css('display','none');if($('#locations_clear')){$('#locations_clear').css('display','');}
if(location!="empty"){for(i=0;i<edir_locations.length;i++){if(edir_locations[i]!=childLevel){$('#div_location_'+edir_locations[i]).css('display','none');}}}else{$('#div_location_'+childLevel).css('display','none');}
fillLocations(aux_edir_locations);if(showClear){$('#locations_clear').css('display','');}});}
containerReload();}
function loadLocationFilter(url,edir_locations,level,childLevel,id,showClear){var aux_edir_locations=edir_locations;var edir_locations=edir_locations.split(',');if(!isNaN(id)){for(i=0;i<edir_locations.length;i++){if(edir_locations[i]>level){text=$("#l_location_filter_"+edir_locations[i]).attr("text");$("#location_filter_"+edir_locations[i]).html("<option id=\"l_location_filter_"+edir_locations[i]+"\" value=\"\">"+text+"</option>");$('#div_location_filter_'+edir_locations[i]).css('display','none');$('#new_location_filter_'+edir_locations[i]+'_field').attr('value','');$('#div_new_location_filter_'+edir_locations[i]+'_field').css('display','none');}}
$("#div_location_filter_"+childLevel).css("display","");$('#location_filter_'+childLevel).css('display','none');$('#location_filter_'+level).attr('disabled','true');$('#div_img_loading_filter_'+childLevel).css('display','');if($('#locations_filter_clear')){$('#locations_filter_clear').css('display','none');}
$('#box_no_location_filter_found_'+childLevel).css('display','none');try{$('#div_select_filter_'+childLevel).css('display','none');}catch(e){}
$.get(url+"/locationFilter.php",{id:id,level:level,childLevel:childLevel,type:'byId'},function(location){if(location!="empty"){var text=$("#l_location_filter_"+childLevel).attr("text");$("#location_filter_"+childLevel).html(location);$("#l_location_filter_"+childLevel).html(text);$('#location_filter_'+childLevel).css('display','');try{$('#div_select_filter_'+childLevel).css('display','');}catch(e){}
display_level_limit=childLevel;}else{if(!id)
$("#div_location_filter_"+childLevel).css("display",'none');else{try{$('#div_select_filter_'+childLevel).css('display','');}catch(e){}
$('#box_no_location_filter_found_'+childLevel).css('display','');}}
if(childLevel&&id)
$('#div_new_location_filter_'+childLevel+'_link').css('display','');else
$('#div_new_location_filter_'+childLevel+'_link').css('display','none');$('#location_filter_'+level).attr('disabled','');$('#div_img_loading_filter_'+childLevel).css('display','none');if($('#locations_filter_clear')){$('#locations_filter_clear').css('display','');}
if(location!="empty"){for(i=0;i<edir_locations.length;i++){if(edir_locations[i]!=childLevel){$('#div_location_filter_'+edir_locations[i]).css('display','none');}}}else{$('#div_location_filter_'+childLevel).css('display','none');}
fillLocations(aux_edir_locations);if(showClear){$('#locations_filter_clear').css('display','');}});}
filtered=true;containerReload();}
function loadLocationsChildtb(url,level,id,childLevel){if(!isNaN(id)){$.get(url+"/location.php",{id:id,level:level,childLevel:childLevel,type:'byId'},function(location){var text=$("#l_location_"+childLevel).attr("text");if(location!="empty"){$("#select_L"+childLevel).html(location);$("#l_location_"+childLevel).html(text);}else
$("#select_L"+childLevel).html('<option id=\"l_location_'+childLevel+'\" value=\"\">'+text+'</option>');});}
containerReload();}
function loadAllLocationstb(url,level){$.get(url+"/location.php",{level:level,type:'All'},function(location){if(location!="empty"){var text=$("#l_location_"+level).attr("text");alert('all text: '+text);$("#select_L"+level).html(location);$("#l_location_"+level).html(text);}});containerReload();}
function loadLocationsChild(url,level,id,childLevel){if(!isNaN(id)){$.get(url+"/location.php",{id:id,level:level,childLevel:childLevel,type:'byId'},function(location){var text=$("#l_location_"+childLevel).attr("text");if(location!="empty"){$("#default_L"+childLevel+"_id").html(location);$("#l_location_"+childLevel).html(text);}else
$("#default_L"+childLevel+"_id").html('<option id=\"l_location_'+childLevel+'\" value=\"\">'+text+'</option>');});}
containerReload();}
function loadAllLocations(url,level){$.get(url+"/location.php",{level:level,type:'All'},function(location){if(location!="empty"){var text=$("#l_location_"+level).attr("text");$("#default_L"+level+"_id").html(location);$("#l_location_"+level).html(text);}});containerReload();}
function formLocations_submit(level,form){if(level<=3){for(i=(level+1);i<=4;i++)
if($('#select_location'+i).val())
$('#select_location'+i).remove();}
form.submit();}
function showNewLocationField(level,edir_locations,back,text){var edir_locations=edir_locations.split(',');for(i=0;i<edir_locations.length;i++){if(edir_locations[i]>=level){$('#location_'+edir_locations[i]+' option[value=]').attr('selected',true);$('#div_location_'+edir_locations[i]).css('display','none');$('#new_location'+edir_locations[i]+'_field').attr('value','');$('#div_new_location'+edir_locations[i]+'_field').css('display','none');}}
$('#div_new_location'+level+'_field').css('display','');$('#div_new_location'+level+'_link').css('display','none');if(!back)
$('#div_new_location'+level+'_back').css('display','none');else
$('#div_new_location'+level+'_back').css('display','');if(text){$('#new_location'+level+'_field').val(text);}}
function hideNewLocationField(level,edir_locations){var edir_locations=edir_locations.split(',');for(i=0;i<edir_locations.length;i++){if(edir_locations[i]>=level){$('#location_'+edir_locations[i]+' option[value=]').attr('selected',true);$('#new_location'+edir_locations[i]+'_field').attr('value','');$('#div_new_location'+edir_locations[i]+'_field').css('display','none');}}
$('#div_location_'+level).css('display','');$('#div_new_location'+level+'_link').css('display','');}
function fillFieldWhere(location_title){if(document.getElementById("where")){if(document.getElementById("where").value!=''){document.getElementById("where").value+=', '+location_title;}else{document.getElementById("where").value+=location_title;}}}
function fillLocations(levels){var edir_locations=levels.split(',');if(edir_locations){if(document.getElementById("where")){document.getElementById("where").value="";}
if(document.getElementById("locations_default_where")){if(document.getElementById("locations_default_where").value){document.getElementById("where").value=document.getElementById("locations_default_where").value;}}
for(i=0;i<edir_locations.length;i++){if(filtered==true)
{if($("#location_filter_"+edir_locations[i]+" option:selected").val()>0){fillFieldWhere($("#location_filter_"+edir_locations[i]+" option:selected").text());}}
else
{if($("#location_"+edir_locations[i]+" option:selected").val()>0){fillFieldWhere($("#location_"+edir_locations[i]+" option:selected").text());}}}}
prev_filtered=filtered;}
function clearLocations(levels,has_default,last_default){var edir_locations=levels.split(',');var first_to_show=0;document.getElementById("where").value="";for(i=0;i<edir_locations.length;i++){if(i>first_to_show){$('#div_location_'+edir_locations[i]).css('display','none');}else{$('#div_location_'+edir_locations[i]).css('display','');$("#location_"+edir_locations[i]).val(0);}
if(has_default){if(edir_locations[i]==last_default){first_to_show=i+1;}}}
$('#locations_clear').css('display','none');}
function clearLocationsFilter(levels,has_default,last_default){var edir_locations=levels.split(',');var first_to_show=0;document.getElementById("where").value="";for(i=0;i<edir_locations.length;i++){if(i>first_to_show){$('#div_location_filter_'+edir_locations[i]).css('display','none');}else{$('#div_location_filter_'+edir_locations[i]).css('display','');$("#location_filter_"+edir_locations[i]).val(0);}
if(has_default){if(edir_locations[i]==last_default){first_to_show=i+1;}}}
$('#locations_filter_clear').css('display','none');}

/* File: /scripts/jquery/jquery.selectbox.js */
;(function($){$.fn.addOption=function()
{var add=function(el,v,t,sO)
{var option=document.createElement("option");option.value=v,option.text=t;var o=el.options;var oL=o.length;if(!el.cache)
{el.cache={};for(var i=0;i<oL;i++)
{el.cache[o[i].value]=i;}}
if(typeof el.cache[v]=="undefined")el.cache[v]=oL;el.options[el.cache[v]]=option;if(sO)
{option.selected=true;}};var a=arguments;if(a.length==0)return this;var sO=true;var m=false;var items,v,t;if(typeof(a[0])=="object")
{m=true;items=a[0];}
if(a.length>=2)
{if(typeof(a[1])=="boolean")sO=a[1];else if(typeof(a[2])=="boolean")sO=a[2];if(!m)
{v=a[0];t=a[1];}}
this.each(function()
{if(this.nodeName.toLowerCase()!="select")return;if(m)
{for(var item in items)
{add(this,item,items[item],sO);}}
else
{add(this,v,t,sO);}});return this;};$.fn.ajaxAddOption=function(url,params,select,fn,args)
{if(typeof(url)!="string")return this;if(typeof(params)!="object")params={};if(typeof(select)!="boolean")select=true;this.each(function()
{var el=this;$.getJSON(url,params,function(r)
{$(el).addOption(r,select);if(typeof fn=="function")
{if(typeof args=="object")
{fn.apply(el,args);}
else
{fn.call(el);}}});});return this;};$.fn.removeOption=function()
{var a=arguments;if(a.length==0)return this;var ta=typeof(a[0]);var v,index;if(ta=="string"||ta=="object"||ta=="function")
{v=a[0];if(v.constructor==Array)
{var l=v.length;for(var i=0;i<l;i++)
{this.removeOption(v[i],a[1]);}
return this;}}
else if(ta=="number")index=a[0];else return this;this.each(function()
{if(this.nodeName.toLowerCase()!="select")return;if(this.cache)this.cache=null;var remove=false;var o=this.options;if(!!v)
{var oL=o.length;for(var i=oL-1;i>=0;i--)
{if(v.constructor==RegExp)
{if(o[i].value.match(v))
{remove=true;}}
else if(o[i].value==v)
{remove=true;}
if(remove&&a[1]===true)remove=o[i].selected;if(remove)
{o[i]=null;}
remove=false;}}
else
{if(a[1]===true)
{remove=o[index].selected;}
else
{remove=true;}
if(remove)
{this.remove(index);}}});return this;};$.fn.sortOptions=function(ascending)
{var sel=$(this).selectedValues();var a=typeof(ascending)=="undefined"?true:!!ascending;this.each(function()
{if(this.nodeName.toLowerCase()!="select")return;var o=this.options;var oL=o.length;var sA=[];for(var i=0;i<oL;i++)
{sA[i]={v:o[i].value,t:o[i].text}}
sA.sort(function(o1,o2)
{o1t=o1.t.toLowerCase(),o2t=o2.t.toLowerCase();if(o1t==o2t)return 0;if(a)
{return o1t<o2t?-1:1;}
else
{return o1t>o2t?-1:1;}});for(var i=0;i<oL;i++)
{o[i].text=sA[i].t;o[i].value=sA[i].v;}}).selectOptions(sel,true);return this;};$.fn.selectOptions=function(value,clear)
{var v=value;var vT=typeof(value);if(vT=="object"&&v.constructor==Array)
{var $this=this;$.each(v,function()
{$this.selectOptions(this,clear);});};var c=clear||false;if(vT!="string"&&vT!="function"&&vT!="object")return this;this.each(function()
{if(this.nodeName.toLowerCase()!="select")return this;var o=this.options;var oL=o.length;for(var i=0;i<oL;i++)
{if(v.constructor==RegExp)
{if(o[i].value.match(v))
{o[i].selected=true;}
else if(c)
{o[i].selected=false;}}
else
{if(o[i].value==v)
{o[i].selected=true;}
else if(c)
{o[i].selected=false;}}}});return this;};$.fn.copyOptions=function(to,which)
{var w=which||"selected";if($(to).size()==0)return this;this.each(function()
{if(this.nodeName.toLowerCase()!="select")return this;var o=this.options;var oL=o.length;for(var i=0;i<oL;i++)
{if(w=="all"||(w=="selected"&&o[i].selected))
{$(to).addOption(o[i].value,o[i].text);}}});return this;};$.fn.containsOption=function(value,fn)
{var found=false;var v=value;var vT=typeof(v);var fT=typeof(fn);if(vT!="string"&&vT!="function"&&vT!="object")return fT=="function"?this:found;this.each(function()
{if(this.nodeName.toLowerCase()!="select")return this;if(found&&fT!="function")return false;var o=this.options;var oL=o.length;for(var i=0;i<oL;i++)
{if(v.constructor==RegExp)
{if(o[i].value.match(v))
{found=true;if(fT=="function")fn.call(o[i],i);}}
else
{if(o[i].value==v)
{found=true;if(fT=="function")fn.call(o[i],i);}}}});return fT=="function"?this:found;};$.fn.selectedValues=function()
{var v=[];this.selectedOptions().each(function()
{v[v.length]=this.value;});return v;};$.fn.selectedTexts=function()
{var t=[];this.selectedOptions().each(function()
{t[t.length]=this.text;});return t;};$.fn.selectedOptions=function()
{return this.find("option:selected");};})(jQuery);

/* File: /scripts/socialbookmarking.js */

function getAbsoluteTop(oElement){var iReturnValue=0;while(oElement!=null){iReturnValue+=oElement.offsetTop;oElement=oElement.offsetParent;}
return iReturnValue;}
function getAbsoluteLeft(oElement){var iReturnValue=0;while(oElement!=null){iReturnValue+=oElement.offsetLeft;oElement=oElement.offsetParent;}
return iReturnValue;}
function enableSocialBookMarking(id,module,url,comments){if(comments===undefined){comments=0;}
var left=0+getAbsoluteLeft(document.getElementById('link_social_'+id+module));var top=18+getAbsoluteTop(document.getElementById('link_social_'+id+module));$.ajax({type:"POST",url:url+"/includes/code/socialbookmarking_ajax.php",data:"id="+id+"&module="+module+"&comments="+comments,success:function(msg){$('#div_to_share').html(msg);}});$('#div_to_share').css('top',top+'px').css('left',left+"px").css('z-index','1000').show('fast');}
function disableSocialBookMarking(){$('#div_to_share').hide('fast');}

/* File: /scripts/contactclick.js */

function showPhone(listingid,default_url){try{xmlhttp=new XMLHttpRequest();}catch(exc){try{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");}catch(ex){try{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}catch(e){xmlhttp=false;}}}
if(xmlhttp){xmlhttp.open("GET",default_url+'/countphoneclick.php?listing_id='+listingid,true);xmlhttp.send(null);}
document.getElementById("phoneLink"+listingid).className="hide";document.getElementById("phoneNumber"+listingid).className="show-inline";}
function showFax(listingid,default_url){try{xmlhttp=new XMLHttpRequest();}catch(exc){try{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");}catch(ex){try{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}catch(e){xmlhttp=false;}}}
if(xmlhttp){xmlhttp.open("GET",default_url+'/countfaxclick.php?listing_id='+listingid,true);xmlhttp.send(null);}
document.getElementById("faxLink"+listingid).className="hide";document.getElementById("faxNumber"+listingid).className="show-inline";}

/* File: /scripts/float_layer.js */

document.onmousemove=captureMousePosition;var xMousePos=0;var yMousePos=0;function captureMousePosition(e){e=e||window.event;if(e.pageX||e.pageY){xMousePos=e.pageX;yMousePos=e.pageY;}else if(typeof(e.clientX)=='number'){xMousePos=event.clientX;yMousePos=event.clientY;}}
function enablePopupLayer(type,comment,listing_title,reply){var float_layer=document.getElementById("float_layer");var layer_reply='';float_layer.style.visibility='visible';if(type=='review'){float_layer.style.left=(xMousePos+20)+"px";float_layer.style.top=(yMousePos+10)+"px";if(reply){layer_reply='<br />'
+'<p><strong><?=system_showText(LANG_REPLYNOUN);?>: </strong></p>'
+'<p>'+reply+'</p>'
+'';}
float_layer.innerHTML=''
+'<h3>'+listing_title+'</h3>'
+'<p><strong><?=system_showText(LANG_REVIEW);?>: </strong></p>'
+'<p>'+comment+'</p>'
+layer_reply;}
else if(type=='langnav'){float_layer.style.left=(xMousePos-220)+"px";float_layer.style.top=(yMousePos+10)+"px";float_layer.innerHTML=$('#allLang').html();}}
function disablePopupLayer(keep){var float_layer=document.getElementById("float_layer");float_layer.style.visibility='hidden';if(!keep){float_layer.innerHTML='';}}

/* File: /scripts/jquery/jquery.accordion.js */

(function(jQuery){jQuery.fn.extend({accordion:function(){return this.each(function(){var $ul=$(this),elementDataKey='accordiated',activeClassName='active',activationEffect='slideToggle',panelSelector='ul, div',activationEffectSpeed='slow',itemSelector='li';if($ul.data(elementDataKey))
return false;$.each($ul.find('ul, li>div'),function(){$(this).data(elementDataKey,true);$(this).hide();});$.each($ul.find('h3'),function(){$(this).click(function(e){activate(this,activationEffect);return false;});$(this).bind('activate-node',function(){$ul.find(panelSelector).not($(this).parents()).not($(this).siblings()).slideUp(activationEffectSpeed);activate(this,'slideDown');});});var active=(location.hash)?$ul.find('a[href='+location.hash+']')[0]:$ul.find('li.current a')[0];if(active){activate(active,false);}
function activate(el,effect){$(el).parent(itemSelector).siblings().removeClass(activeClassName).children(panelSelector).slideUp(activationEffectSpeed);$(el).siblings(panelSelector)[(effect||activationEffect)](((effect=="show")?activationEffectSpeed:false),function(){if($(el).siblings(panelSelector).is(':visible')){$(el).parents(itemSelector).not($ul.parents()).addClass(activeClassName);}else{$(el).parent(itemSelector).removeClass(activeClassName);}
if(effect=='show'){$(el).parents(itemSelector).not($ul.parents()).addClass(activeClassName);}
$(el).parents().show();});}});}});})(jQuery);