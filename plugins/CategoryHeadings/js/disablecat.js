jQuery(document).ready(function(){
    var cats = $.parseJSON(gdn.definition('CatHeadings'));
	$.each(cats,function(i,v){
		if(v.lock==1)
			$('select#Form_CategoryID option[value="'+i+'"]').attr("disabled", true);
	});
	$('select#Form_CategoryID option').each(function(){
		if($(this).is(':selected') && $(this).is(':disabled')){
			$(this).removeAttr('selected');
			$(this).next().attr('selected', 'selected');
		}
		
	});
});
