jQuery(document).ready(function(){
    var cats = $.parseJSON(gdn.definition('CatHeadings'));
	$.each(cats,function(i,v){
		var lc = $("<input />").attr({
			"type":"checkbox",
			"class":"LockCat",
			"name":"LockCat_"+i,
			"style":"vertical-align:middle;"	
		});
		if(v.lock==1)
			lc.attr("checked","checked");
		var hc = $("<input />").attr({
			"type":"checkbox",
			"class":"HeadCat",
			"name":"HeadCat_"+i,
			"style":"vertical-align:middle;"	
		});
		if(v.heading==1)
			hc.attr("checked","checked")
		var catops = $("<span class=\"CatHeadingOps\" />");
		catops.append(hc)
			.append($("<label style=\"vertical-align:middle;\">"+gdn.definition("Heading")+" </label>"))
			.append(lc)
			.append($("<label style=\"vertical-align:middle;\">"+gdn.definition("Lock")+" </label>"))
		$("#list_"+i+" .Buttons:first").prepend(catops);
	});
	$('.LockCat, .HeadCat').live('click',function(){
		var c = $(this).attr('name').split('_');
		var cat = c[0];
		var id = c[1];
		var on = $(this).is(':checked')?1:0;
		$.ajax({
			type: "POST",
			url: gdn.definition("WebRoot")+'/vanilla/settings/categoryheadings/'+cat+'/'+id+'/'+on,
			data: 'DeliveryType=BOOL&DeliveryMethod=JSON',
			dataType: 'json',         
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$.popup({}, XMLHttpRequest.responseText);
			},
			success: function(json) {
				
			}
		});
	});
});
