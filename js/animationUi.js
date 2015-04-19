var AnimationsUI=function(){
	this.animating=false;
	this.mouseOver = false;
	
	setInterval($.proxy(function () {
	//debugger;
	 	if (this.mouseOver === false) {
	 		this.showSlide({direction: "next"});
	 	}
	}, this), 3000);
}
AnimationsUI.prototype.intro=function(){
	$("#blocLeft").hide("slide", {direction: "left", easing:"easeInExpo"}, 3000);
	$("#blocRight").hide("slide", {direction: "right", easing:"easeInExpo"}, 3000);
}
AnimationsUI.prototype.rebond=function(e, obj){
	//debugger;
	e.preventDefault();
	$(obj).animate({top:"-=20"},{duration:500, easing:"easeInBounce", progress:function(){
			if($(obj).attr("id")=="candidat")
				$(obj).css('background-color','rgba(255, 34, 34, 0.56)');
			else
				$(obj).css('background-color','rgba(237, 242, 32, 0.56)');
	}
	});
}
AnimationsUI.prototype.descente=function(e, obj){
	e.preventDefault();
	$(obj).animate({top:"+=20"},{duration:500, easing:"easeOutBounce", progress:function(){
		$(obj).css('background-color','rgba(255, 34, 34, 0)');
	}
	});
}
AnimationsUI.prototype.hideShow=function(e, obj){
	//debugger;
	e.preventDefault();
	$(obj).children().animate({top:1},{duration:500, easing:"easeOutBounce"})
}
AnimationsUI.prototype.showHide=function(e, obj){
	e.preventDefault();
	$(obj).children().animate({top:-27},{duration:500, easing:"easeOutBounce"})
}
/* SLIDESHOW */
AnimationsUI.prototype.showSliderNavigation=function(e){
	e.peventDefault;
	//console.log(this);
	$("body > .slider div[id], body > .slider nav[id]").css("display", "block");
}
AnimationsUI.prototype.hideSliderNavigation=function(e){
	e.peventDefault;
	//console.log(this);
	$("body > .slider div[id], body > .slider nav[id]").css("display","none");
}
AnimationsUI.prototype.showSlide=function(params){
	//debugger;
	//console.log("appel");
	if(this.animating===true)
	{
		return true;
	}
	var currentSlide = $("body > .slider > div.active");
	var currentSlidePosition = $("body > .slider > div.active").index();
	var nextSlide;
	var that=this;
	currentSlide.css("z-index", "0");
	if(typeof params.direction !== "undefined")
	{
		var max=$("body > .slider > div").length-4;
		if(params.direction==="next")
		{
			switch(currentSlidePosition)
			{
				case max:
					nextSlide = $("body > .slider>div:first-child");
					break;
				default:
					nextSlide = $("body > .slider>div.active").next();
					break;
			}
			var currentSlideLeftPos="-100%";
		}
		else
		{
			console.log(currentSlidePosition+" "+max);
			switch(currentSlidePosition)
			{
				case 0:
					nextSlide = $("body > .slider>div:nth-child("+(max+1)+")");
					break;
				default:
					nextSlide = $("body > .slider>div.active").prev();
					break;
			}
			var currentSlideLeftPos="100%";
			nextSlide.css("left", "-100%");
		}
	}
	else if(typeof params.goTo !== "undefined")
	{
		if((params.goTo<0) || (params.goTo>$("body > .slider div > li").length))
		{
			that.animating=false;
			return;
		}
		var nextSlide = $("body > .slider > div").eq(params.goTo-1);
		if(params.goTo<currentSlidePosition)
		{
			nextSlide.css("left", "-100%");
			var currentSlideLeftPos="100%";
			
		}
		else if(params.goTo>currentSlidePosition)
		{
			nextSlide.css("left", "100%");
			var currentSlideLeftPos="-=100%";
		}
		else{
			that.animating=false;
			return;
		}
	}
	else
	{
		that.animating=false;
		return;
	}
	
	nextSlide.css("z-index","1");
	$(nextSlide).animate({left: 0}, {duration: 2000, easing: "swing", complete: function(){
					currentSlide.css("left", "");
					currentSlide.removeAttr("class");				
					$(this).addClass("active");
					$("body > .slider > nav > div:nth-child("+(currentSlidePosition+1)+")").css("background-color", "rgb(0, 0, 0)");
					
				}
	});
	$(currentSlide).animate({left: currentSlideLeftPos}, {duration: 2000, easing: "swing", progress: function(){
			that.animating=true;
	},complete: function(){			
					$(this).css("left", "");
					var ind=$(nextSlide).index()+1;
					//console.log(ind);
					$("body > .slider > nav > div:nth-child("+ind+")").css("background-color", "rgb(255, 0, 0)");
					that.animating=false;
				}
	});
}
AnimationsUI.prototype.play=function(e){
	this.mouseOver=false;
}
AnimationsUI.prototype.pause=function(e){
	this.mouseOver=true;
}