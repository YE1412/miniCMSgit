var animation;
$(document).ready(function(){
    animation=new AnimationsUI();
    animation.intro();
	$( "body > .slider" ).on( "mouseenter", animation.showSliderNavigation);
		$( "body > .slider" ).on( "mouseleave", animation.hideSliderNavigation);
		
		$( "#top > div:first-child" ).on( "mouseenter", function(){
			$(this).css("border-color", "transparent transparent transparent rgb(255, 0, 0)");
		});
		$( "#top > div:first-child" ).on( "mouseleave", function(){
			$(this).css("border-color", "transparent transparent transparent rgb(0, 0, 0)");
		});
		$( "#top > div:last-child" ).on( "mouseenter", function(){
			$("#top > div:last-child > div").css("background-color", "rgb(255, 0, 0)");
		});
		$( "#top > div:last-child" ).on( "mouseleave", function(){
			$("#top > div:last-child > div").css("background-color", "rgb(0, 0, 0)");
		});
		
		$("#top > div:first-child" ).on("click", $.proxy(animation.play, animation));
		$("#top > div:last-child" ).on("click", $.proxy(animation.pause, animation));
		
		$("#right").on("click", function(e){
			e.preventDefault();
			animation.pause();
			animation.showSlide({direction: "next"});
		});
		$("#left").on("click", function(e){
			e.preventDefault();
			animation.pause();
			animation.showSlide({direction: "prev"});
		});
});