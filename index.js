$(document).ready(function() {
	
	var tmp = [
		{
			id: 0,
			title: "Product 1",
			description: "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui."
		},{
			id: 1,
			title: "Product 2",
			description: "Elit non mi porta imon gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui."
		},{
			id: 2,
			title: "Product 3",
			description: "Non mi porta imon gravida at eget init metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui."
		},{
			id: 3,
			title: "1h med Christoffer",
			description: "Gör vad du vill, diverse sexuella tjänster erbjuds. Allt är möjligt, all-inclusive."
		},{
			id: 4,
			title: "1h med Jakob",
			description: "Gör vad du vill, diverse sexuella tjänster erbjuds. Grädde och extra vattenslang ingår i priset."
		}		
	];
	
	
	$.templates({
		products: 
			"<div class='col-6 col-sm-6 col-lg-4'>"+
				"<h2>{{>title}}</h2>"+
				"<p>{{>description}}</p>" + 
				"<p>" + 
					"<button type='button' class='btn btn-default btn-sm' href='/product.php?id={{>id}}'>View More <span class='glyphicon glyphicon-info-sign'></span> </button>"+
					"<button type='button' class='btn btn-default btn-sm addtocart' data-item='{{>id}}'>Add to cart <span class='glyphicon glyphicon-shopping-cart'></span></button>"+
				"</p>"+
				"<a id='product{{>id}}'></a>"+
			"</div>"
	});
	
	$("#products").html($.render.products(tmp));
	
	$(".addtocart").bind("click", function(event){
		var id = $(this).attr("data-item");
		alert("Product " + id + " added to your cart");
	});
	
	$("#clearcart").bind("click", function(){
		$("#cart").html("<li class='disabled'><a href='#'>Empty cart</a></li>");
	});
});