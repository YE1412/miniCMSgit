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

});