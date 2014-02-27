var selectedsharingposition=document.getElementsByName('rearrange_settings[]');
var selectedcounterlist=document.getElementsByName('counter_rearrange_settings[]');
var selectedverticalsharingposition=document.getElementsByName('vertical_rearrange_settings[]');
var selectedverticalcounterlist=document.getElementsByName('vertical_counter_rearrange_settings[]');
document.write("<script type='text/javascript'>var islrsharing = true; var islrsocialcounter = true;</script>");
document.write("<script src='//share.loginradius.com/Content/js/LoginRadius.js' type='text/javascript'></script>");
window.onload = function (){
	$("#Form_Horizontalsharingtheme, #Form_Horizontalsharingtheme1").click(function(){
		displaysharing_horizontal(); 
	});  
	$("#Form_Horizontalsharingtheme2, #Form_Horizontalsharingtheme3").click(function(){
		hiddensharing_horizontal();
	}); 
	$("#Form_Horizontalsharingtheme4, #Form_Horizontalsharingtheme5").click(function(){
		displaycounter_horizontal();
	}); 
	$("#Form_verticalsharingtheme, #Form_verticalsharingtheme1").click(function(){
		displayvertical_sharing();
	}); 
	$("#Form_verticalsharingtheme2, #Form_verticalsharingtheme3").click(function(){
		displayvertical_counter();
	}); 
	sharingproviderlist();
	counterproviderlist();
	verticalsharingproviderlist();
	verticalcounterproviderlist();	
}
// check provider more then 9 select
function loginRadiusSharingLimit(elem){	
	var checkCount = selectedsharingposition.length;
	if(elem.checked){
// count checked providers
		checkCount++;
		if(checkCount >= 10){
			elem.checked = false;
//document.getElementById('rearrangedefault').style.display = 'block';
			jQuery("#loginRadiusSharingLimit").show('slow');
			setTimeout(function() {
			jQuery("#loginRadiusSharingLimit").hide('slow');
			}, 2000);
			return;
		}
	}
}

//socialshare rearange 
function lr_sortable(){
	jQuery("#sortable").sortable({
	revert: true
	});
}

function verticallr_sortable(){
	jQuery("#vertical_sortable").sortable({
	revert: true
	});
}

// JavaScript Document
function getXMLHttp() {
   var xmlHttp
	try {
	//Firefox, Opera 8.0+, Safari+
	xmlHttp = new XMLHttpRequest();
	}
	catch(e) {
	//Internet Explorer
		try {
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e) {
			try {
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e) {
				alert("Your browser does not support AJAX!")
				return false;
			}
		}
	}
	return xmlHttp;
}

function verticalsharingproviderlist() {
	var sharing =  $SS.Providers.More;
	var div = document.getElementById('socialshare_verticalproviders_list');
	for( var i= 0 ; i< sharing.length;i++){
		var label = document.createElement('label');
		label.setAttribute('for', 'loginRadiusLIcheckrearrange'+sharing[i]);
		label.setAttribute('class','CheckBoxLabel');
		var provider = document.createElement('input');
		provider.setAttribute('type', 'checkbox');
		provider.setAttribute('id', 'loginRadiusLIvertirearrange'+sharing[i].toLowerCase());
		provider.setAttribute('onchange', 'loginRadiusverticalSharingLimit(this); loginRadiusverticalRearrangeProviderList(this);');
		provider.setAttribute('name', 'Form/loginRadiusverticalrearrange[]');
		provider.setAttribute('value', sharing[i]);
		provider.setAttribute('class', 'form-checkbox');
		var labeltext = document.createTextNode(sharing[i]);   
		label.appendChild(provider);
		label.appendChild(labeltext);
		div.appendChild(label);
	}
	if(selectedverticalsharingposition.length >0) {
		for(var i = 0; i < selectedverticalsharingposition.length; i++){
			if(!selectedverticalsharingposition[i].checked){
				jQuery('#loginRadiusLIvertirearrange'+selectedverticalsharingposition[i].value.toLowerCase()).attr('checked','checked');
			}
		}
	}
}

function verticalcounterproviderlist (){
	var counter = $SC.Providers.All;
	var div = document.getElementById('socialcounter_verticalproviders_list');
	for( var i= 0 ; i< counter.length;i++){
		var value = counter[i].split(' ').join('');
		value = value.replace("++", "plusplus");
		value = value.replace("+", "plus");
		var label = document.createElement('label');
		label.setAttribute('for', 'loginRadiusverticalcounter'+counter[i]);
		label.setAttribute('class','CheckBoxLabel');
		var provider = document.createElement('input');
		provider.setAttribute('type', 'checkbox');
		provider.setAttribute('id', 'loginRadiusLIverticalcounter'+value);
		provider.setAttribute('name', 'Form/loginRadiusverticalcounter[]');
		provider.setAttribute('value', counter[i]);
		provider.setAttribute('class', 'form-checkbox');
		var labeltext = document.createTextNode(counter[i]);
		label.appendChild(provider);
		label.appendChild(labeltext);
		div.appendChild(label);	  
	}
	if(selectedverticalcounterlist.length >0) {
		for(var i = 0; i < selectedverticalcounterlist.length; i++){
			if(!selectedverticalcounterlist[i].checked){
				var value = selectedverticalcounterlist[i].value.split(' ').join('');
				value = value.replace("++", "plusplus");
				value = value.replace("+", "plus");
				jQuery('#loginRadiusLIverticalcounter'+value).attr('checked','checked');
			}
		}
	}
}
function loginRadiusverticalSharingLimit(elem){
	var checkCount = selectedverticalsharingposition.length;
	if(elem.checked){
// count checked providers
		checkCount++;
		if(checkCount >= 10){
			elem.checked = false;
			jQuery("#loginRadiusverticalSharingLimit").show('slow');
			setTimeout(function() {
			jQuery("#loginRadiusverticalSharingLimit").hide('slow');
			}, 2000);
			return;
		}
	}
}

function loginRadiusverticalRearrangeProviderList(elem)	{
	var ul = document.getElementById('vertical_sortable');
	if(elem.checked){
		var listItem = document.createElement('li');
		listItem.setAttribute('id', 'loginRadiusLIverticalrearrange'+elem.value.toLowerCase());
		listItem.setAttribute('title', elem.value);
		listItem.setAttribute('class', 'lrshare_iconsprite32 lrshare_'+elem.value.toLowerCase());
		// append hidden field
		var provider = document.createElement('input');
		provider.setAttribute('type', 'hidden');
		provider.setAttribute('name', 'vertical_rearrange_settings[]');
		provider.setAttribute('value', elem.value);
		listItem.appendChild(provider);
		ul.appendChild(listItem);
	}
	else{
		if(document.getElementById('loginRadiusLIverticalrearrange'+elem.value.toLowerCase())) {
			ul.removeChild(document.getElementById('loginRadiusLIverticalrearrange'+elem.value.toLowerCase()));
		}
	}
}


// prepare rearrange provider list
function loginRadiusRearrangeProviderList(elem){
	var ul = document.getElementById('sortable');
	if(elem.checked){
		var listItem = document.createElement('li');
		listItem.setAttribute('id', 'loginRadiusLIcheckrearrange'+elem.value.toLowerCase());
		listItem.setAttribute('title', elem.value);
		listItem.setAttribute('class', 'lrshare_iconsprite32 lrshare_'+elem.value.toLowerCase());
		// append hidden field
		var provider = document.createElement('input');
		provider.setAttribute('type', 'hidden');
		provider.setAttribute('name', 'rearrange_settings[]');
		provider.setAttribute('value', elem.value);
		listItem.appendChild(provider);
		ul.appendChild(listItem);
	}
	else{
		if(document.getElementById('loginRadiusLIcheckrearrange'+elem.value.toLowerCase())) {
			ul.removeChild(document.getElementById('loginRadiusLIcheckrearrange'+elem.value.toLowerCase()));
		}
	}
}

function sharingproviderlist(){
	var sharing =  $SS.Providers.More;
	var div = document.getElementById('socialshare_providers_list');
	for( var i= 0 ; i< sharing.length;i++){
		var label = document.createElement('label');
		label.setAttribute('for', 'loginRadiusLIcheckrearrange'+sharing[i]);
		label.setAttribute('class','CheckBoxLabel');
		var provider = document.createElement('input');
		provider.setAttribute('type', 'checkbox');
		provider.setAttribute('id', 'loginRadiusLIrearrange'+sharing[i].toLowerCase());
		provider.setAttribute('onchange', 'loginRadiusSharingLimit(this); loginRadiusRearrangeProviderList(this);');
		provider.setAttribute('name', 'Form/loginRadiusLIcheckrearrange[]');
		provider.setAttribute('value', sharing[i]);
		provider.setAttribute('class', 'form-checkbox');
		var labeltext = document.createTextNode(sharing[i]);   
		label.appendChild(provider);
		label.appendChild(labeltext);
		div.appendChild(label);
	}
	if(selectedsharingposition.length >0) {
		for(var i = 0; i < selectedsharingposition.length; i++){
			if(!selectedsharingposition[i].checked){
				jQuery('#loginRadiusLIrearrange'+selectedsharingposition[i].value.toLowerCase()).attr('checked','checked');
			}
		}
	}
}
// show Provider List for horizontal Social counter.
function counterproviderlist() {
	var counter = $SC.Providers.All;
	var div = document.getElementById('socialcounter_providers_list');
	for( var i= 0 ; i< counter.length;i++){
		var value = counter[i].split(' ').join('');
		value = value.replace("++", "plusplus");
		value = value.replace("+", "plus");
		var label = document.createElement('label');
		label.setAttribute('for', 'loginRadiuscounter'+counter[i]);
		label.setAttribute('class','CheckBoxLabel');
		var provider = document.createElement('input');
		provider.setAttribute('type', 'checkbox');
		provider.setAttribute('id', 'loginRadiusLIcounter'+value);
		//provider.setAttribute('onchange', 'loginRadiuscounterRearrangeProviderList(this);');
		provider.setAttribute('name', 'Form/loginRadiuscounter[]');
		provider.setAttribute('value', counter[i]);
		provider.setAttribute('class', 'form-checkbox');
		var labeltext = document.createTextNode(counter[i]);
		label.appendChild(provider);
		label.appendChild(labeltext);
		div.appendChild(label);	  
	}
	if(selectedcounterlist.length >0) {
		for(var i = 0; i < selectedcounterlist.length; i++){
			if(!selectedcounterlist[i].checked){
				var value = selectedcounterlist[i].value.split(' ').join('');
				value = value.replace("++", "plusplus");
				value = value.replace("+", "plus");
				jQuery('#loginRadiusLIcounter'+value).attr('checked','checked');
			}
		}
	}
}

$(document).ready(function() {	
$('#menu_tabs li a:not(:first)').addClass('inactive');
$('.container:not(:first)').hide();
$('#menu_tabs li a').click(function(){
var t = $(this).attr('href');
$('#menu_tabs li a').addClass('inactive');		
$(this).removeClass('inactive');
$('.container').hide();
$(t).fadeIn('slow');
return false;
})
});

function displayvertical_sharing(){
	$('#verticalrearrangedefaulttext').removeClass("element-invisible");
	$('#verticalrearrangedefault').removeClass("element-invisible");
	document.getElementById('rearrangedefault').style.display = 'none';
	$('#rearrangedefaulttext').addClass("element-invisible");
	$('#sortable').addClass("element-invisible");
	document.getElementById('verticalshareprovider').style.display="block";
	document.getElementById('socialshare_verticalproviders_list').style.display="block";
	document.getElementById('socialcounter_verticalproviders_list').style.display="none";
}

function displayvertical_counter() {
	$('#verticalrearrangedefaulttext').addClass("element-invisible");
	$('#verticalrearrangedefault').addClass("element-invisible");
	document.getElementById('verticalshareprovider').style.display="block";
	document.getElementById('socialshare_verticalproviders_list').style.display="none";
	document.getElementById('socialcounter_verticalproviders_list').style.display="block";
	document.getElementById('rearrangedefault').style.display = 'none';
	$('#rearrangedefaulttext').addClass("element-invisible");
	$('#sortable').addClass("element-invisible");
}
function displaysharing_horizontal() {
	document.getElementById('rearrangedefault').style.display = 'block';
	$('#rearrangedefaulttext').removeClass("element-invisible");
	$('#sortable').removeClass("element-invisible");
	$('#socialshare_providers_list').removeClass("element-invisible");
	$('#socialcounter_providers_list').addClass("element-invisible");
	$('#shareprovider').removeClass("element-invisible");
	document.getElementById('verticalshareprovider').style.display="none";
	$('#verticalrearrangedefaulttext').addClass("element-invisible");
	$('#verticalrearrangedefault').addClass("element-invisible");
}

function hiddensharing_horizontal() {
	document.getElementById('rearrangedefault').style.display = 'none';
	$('#rearrangedefaulttext').addClass("element-invisible");
	$('#sortable').addClass("element-invisible");
	$('#shareprovider').addClass("element-invisible");
	$('#socialcounter_providers_list').addClass("element-invisible");
	document.getElementById('verticalshareprovider').style.display="none";
	$('#verticalrearrangedefaulttext').addClass("element-invisible");
	$('#verticalrearrangedefault').addClass("element-invisible");
}

function displaycounter_horizontal(){
	document.getElementById('rearrangedefault').style.display = 'none';
	$('#rearrangedefaulttext').addClass("element-invisible");
	$('#sortable').addClass("element-invisible");
	$('#shareprovider').removeClass("element-invisible");
	$('#socialshare_providers_list').addClass("element-invisible");
	$('#socialcounter_providers_list').removeClass("element-invisible");
	document.getElementById('verticalshareprovider').style.display="none";
	$('#verticalrearrangedefaulttext').addClass("element-invisible");
	$('#verticalrearrangedefault').addClass("element-invisible");
}

function Makevertivisible() {
	$('#shareprovider').addClass("element-invisible");
	var selecttheme=document.getElementsByName('Form/verticalsharingtheme');
	for(var i = 0; i < selecttheme.length; i++){
		if(selecttheme[i].checked){
			if(selecttheme[i].value == '16VerticlewithBox' ||selecttheme[i].value == '32VerticlewithBox') {
				displayvertical_sharing();
			}
			else {
				displayvertical_counter();
				}
		}
	}
	document.getElementById('vertical_sharing').style.display="block";
	document.getElementById('horizontal_sharing').style.display="none";
	document.getElementById('sharevertical').style.display="block";
	document.getElementById('sharehorizontal').style.display="none";
	jQuery("#arrow").addClass("arrownext");
	jQuery("#arrow").removeClass("arrowpre");
	document.getElementById('mymodal2').style.color = "#00CCFF";
	document.getElementById('mymodal1').style.color = "#000000";
}

function Makehorivisible() {
		var selecttheme=document.getElementsByName('Form/Horizontalsharingtheme');
	for(var i = 0; i < selecttheme.length; i++){
		if(selecttheme[i].checked){
			if(selecttheme[i].value == 'horizonSharing32' ||selecttheme[i].value == 'horizonSharing16') {
				displaysharing_horizontal();
			}
			else if(selecttheme[i].value == 'single-image-theme-large' ||selecttheme[i].value == 'single-image-theme-small'){
				hiddensharing_horizontal();
				}
				else {
				displaycounter_horizontal();	
				}
		}
	}
	document.getElementById('vertical_sharing').style.display="none";
	document.getElementById('horizontal_sharing').style.display="block";
	document.getElementById('sharehorizontal').style.display="block";
	document.getElementById('sharevertical').style.display="none";
	jQuery("#arrow").addClass("arrowpre");
	jQuery("#arrow").removeClass("arrownext");
	document.getElementById('mymodal1').style.color = "#00CCFF";
	document.getElementById('mymodal2').style.color = "#000000";
}




