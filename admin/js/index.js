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

	$("div[id] form").each(function(ind){
		var l=$("div[id] form").length;
		var form=$(this);
		switch(ind){
			case l-1:
				form.css("background-color", "rgba(255, 255, 255, 0.91)");
				form.css("color", "#555");
				break;
			default:	
				if(form.children("div.pub").children("select").val()==0)
				{
					form.css("background-color", "rgba(191, 61, 61, 0.91)");
					form.css("color", "rgba(255, 255, 255, 1)");
				}
				break;
		}
	});
	$("div#etat").each(function(ind){
		if($(this).text()!="")
		{
			var text=$(this).text();
			console.log(text);
			if(text.indexOf("succès")!=-1)
			{
				$(this).css("background", "#299a0b"); /* Old browsers */
				$(this).css("background", "-moz-linear-gradient(top,  #299a0b 0%, #d0f45a 100%)"); /* FF3.6+ */
				$(this).css("background", "-webkit-gradient(linear, left top, left bottom, color-stop(0%,#299a0b), color-stop(100%,#d0f45a)"); /* Chrome,Safari4+ */
				$(this).css("background", "-webkit-linear-gradient(top,  #299a0b 0%,#d0f45a 100%)"); /* Chrome10+,Safari5.1+ */
				$(this).css("background", "-o-linear-gradient(top,  #299a0b 0%,#d0f45a 100%)"); /* Opera 11.10+ */
				$(this).css("background", "-ms-linear-gradient(top,  #299a0b 0%,#d0f45a 100%)"); /* IE10+ */
				$(this).css("background", "linear-gradient(to bottom,  #299a0b 0%,#d0f45a 100%)"); /* W3C */
				$(this).css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='#299a0b', endColorstr='#d0f45a',GradientType=0 )"); /* IE6-9 */
			}
			else
			{
				$(this).css("background","#ff1414"); /* Old browsers */
				$(this).css("background","-moz-linear-gradient(top,  #ff1414 0%, #ffb630 100%)"); /* FF3.6+ */
				$(this).css("background","-webkit-gradient(linear, left top, left bottom, color-stop(0%,#ff1414), color-stop(100%,#ffb630)"); /* Chrome,Safari4+ */
				$(this).css("background","-webkit-linear-gradient(top,  #ff1414 0%,#ffb630 100%)"); /* Chrome10+,Safari5.1+ */
				$(this).css("background","-o-linear-gradient(top,  #ff1414 0%,#ffb630 100%)"); /* Opera 11.10+ */
				$(this).css("background","-ms-linear-gradient(top,  #ff1414 0%,#ffb630 100%)"); /* IE10+ */
				$(this).css("background","linear-gradient(to bottom,  #ff1414 0%,#ffb630 100%)"); /* W3C */
				$(this).css("filter","progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff1414', endColorstr='#ffb630',GradientType=0 )"); /* IE6-9 */

			}
		}
		else
		{
			$(this).css("background", "none");
		}
	});
	
	$(".newItem").on("click", function(){
		var idPage, namePage, innerHtml;
		innerHtml="";
		idPage=$(this).parents("[data-id]");
		namePage=idPage.find("[name='name']");
		$.ajax({
			url: "module/getItem.php",
				async: false,
				data: {idPage:idPage.attr("data-id"), name:namePage.val(), orderDisp: idPage.children("form").children("div").length-9, id:idPage.children("form").children("div").length-9},
				method:"POST"
			}
		).done(function( data ) {
			innerHtml=data;
		});
		$(this).colorbox({innerWidth:480, innerHeight:390, html: innerHtml, title: function(){
					var pageName;
					pageName = "Insertion d'un élément dans la page "+$(this).attr("data-page");
					return pageName;
				}});
	});
	
	function fastCKE ( elem ) {
		CKEDITOR.replace( elem.attr("id") );
	}

	$("textarea[name^='contentHash']").each(function(ind){
        fastCKE($(this));
    });

	$("#sort").sortable({
		stop:  function(event, ui){
			var serial=$(this).sortable("toArray", { attribute: 'data-id'});
			$.ajax(
				"module/sort.php",
				{
					data: {order: serial},
					method: "POST", 
					success: function(result){
						
					}
				}
			);					
		}
	});

	$(document).on("change", ".item", function(event){
        switch($(this).val())
        {
            case "2":
            if($('input .contentItem').attr("type")!="file")
            {
                $('input.contentItem').attr({"type":"file",
                    "name": "contentItem[]", "required": "required" , "multiple": "multiple" });
            }
            break;
            default:
            if($('input.contentItem').attr("type")!="text")
            {
                $('input.contentItem').attr({"type": "text",
                    "name": "contentItem"});
                $('input.contentItem').removeAttr("multiple");
            }
            break;
        }
    });

    $(document).on("mouseenter", ".itemAdd", function(event){
    	$(this).children("button").css("display", "block");
    	
    });
    $(document).on("mouseleave", ".itemAdd", function(event){
    	$(this).children("button").css("display", "none");
    	
    });

    $(document).on("click", ".itemAdd button[aria-label]", function(event){
   		var idp, idi;
   		if($(this) != "")
   		{
      		idp=$(this).parents("[data-id]");
			idi=$(this).parents("[datas-id]").attr("datas-id");
			namep=idp.find("[name='name']");
			idp=$(this).parents("[data-id]");
   		}  	
   		//event.stopPropagation();

   		bootbox.confirm("Êtes-vous sûr de vouloir supprimer cet objet ?", function(result) {
			//console.log("Confirm result: "+result);
			if(result){
				$.ajax(
					"module/deleteItem.php",
					{
						data: {idPage: idp.attr("data-id"), idItem: idi, namePage: namep.val()},
						method: "POST", 
						success: function(res){
							console.log(res);
							switch(res){
								case "1":
									idp.find("form").get(1).submit();
									break;
								default:
									break;
							}
						}
					}
				);
			}

		}); 
    });
	
	activeLinks(document.title);
});

function activeLinks(title){
		var active;
	    $("div .list-group > input").each(function(ind){
	    		$(this).removeClass("active");
	    });
	    var disable=$("div .list-group > a").get(1);
	    $(disable).removeClass('active');
	    //console.log(title);
	    switch(title){
	    	case "Administration des Pages":
	    		active=$("div .list-group > input").get(0);
	    		$(active).addClass('active');
	    		break;
	    	case "Administration des Liens":
	    		active=$("div .list-group > input").get(1);
	    		$(active).addClass('active');
	    		break;
	    	case "Administration des Headers":
	    		active=$("div .list-group > input").get(2);
	    		$(active).addClass('active');
	    		break;
	    	case "Administration des Footers":
	    		active=$("div .list-group > input").get(3);
	    		$(active).addClass('active');
	    		break;
	    	case "Administration du Compte":
	    		active=$("div .list-group > a").get(1);
	    		$(active).addClass('active');
	    		break;
	    	default:
	    		break;
	    }
	}