$(document).ready(function(){
	// var marginConnexion = ;
	$("#containerConnexion").css('margin-top',$(window).height()/4);
	$("#containerIndex").height($(window).height());
	$("#containerIndex > section > *").height($("#containerIndex").height()-34);
	$(window).resize(function(){
		$("#containerConnexion").css('margin-top',$(window).height()/4);
		$("#containerIndex").height($(window).height());
		$("#containerIndex > section > *").height($("#containerIndex").height()-34);
	});

	$("form div select").each(function(ind){
		var l=$("form div select").length;
		var form=$(this).parent().parent();
		//console.log(ind);
		switch(ind){
			case l-1:
				break;
			default:
				if($(this).val()==0)
				{
					form.css("background-color", "rgba(191, 61, 61, 0.91)");
					form.css("color", "rgba(255, 255, 255, 1)");
				
				}
				break;
		}	
	});
});