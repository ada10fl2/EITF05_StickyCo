var CART = {};

$(document).ready(function() {	
	
	var tmp = [
		{
			id: 0,
			title: "Product 1",
			description: "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui."
		}	
	];
	
	$.templates({
		products: 
			"<div class='col-6 col-sm-6 col-lg-4'>"+
				"<h2>{{>Title}}</h2>"+
				"{{if Img}}"+
					"<img src='{{>Img}}' alt='{{>Title}}'/>"+
				"{{/if}}"+
				"<p>{{>Description}}</p>" + 
				"<p>"+ 
					"<button type='button' class='btn btn-default btn-sm' href='/product.php?id={{>id}}'>View More <span class='glyphicon glyphicon-info-sign'></span> </button>"+
					"<button type='button' class='btn btn-default btn-sm addtocart' data-item='{{>id}}'>Add to cart <span class='glyphicon glyphicon-shopping-cart'></span></button>"+
				"</p>"+
				"<a id='product{{>id}}'></a>"+
			"</div>",
		cart : "test"
	});
	
	$("#products").html($.render.products(products));
	
	$(".addtocart").bind("click", function(event){
		var id = $(this).attr("data-item");
		addToCart(id);
	});
	
	$("#clearcart").bind("click", function(){
		updateCart();
	});
});

function updateCart(){
	$("#cart").html($.render.cart(CART));
}

function addToCart(id){
	CART.push(id);
	$.getJSON( "/addToCart.php?pid="+id , function(data, textStatus, jqXHR ){
		console.log("Added item to cart: "+JSON.stringify(data));
		CART = data;
		updateCart();
	});
}

