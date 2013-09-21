var CART = [];

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
			"<div class='col-6 col-sm-6 col-lg-4 product'>"+
				"<h2 class='product-header'>{{trunc:Title}}</h2>"+
				"{{if Image}}"+
					"<center>"+
					"<img src='{{>Image}}' alt='{{>Title}}' width='200' >"+
					"</center>"+
				"{{/if}}"+
				"<div class='product-info'>"+
				"<div class='product-description'><p>{{>Description}}</p></div>" + 
				"<p><b>{{>Price}} SEK</b></p>" + 
				"</div>"+
				"<p>"+ 
					"<button type='button' class='btn btn-default btn-sm showproduct' data-item='{{>ID}}'>View More <span class='glyphicon glyphicon-info-sign'></span> </button>"+
					"<button type='button' class='btn btn-default btn-sm addtocart' data-item='{{>ID}}'>Add to cart <span class='glyphicon glyphicon-shopping-cart'></span></button>"+
				"</p>"+
				"<a id='product{{>ID}}'></a>"+
			"</div>",
		cart : 
			"<li><b>{{>Title}}</b><br />{{>count}} * {{>price}} SEK = {{>prodtotal}} SEK</li>",
		showproduct: 
			"<div class='modal-header'>"+
				"<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>"+
				"<h4 class='modal-title'>{{>Title}}</h4>"+
			"</div>"+
			"<div class='modal-body'>"+
				"{{if Image}}"+
					"<center>"+
					"<img src='{{>Image}}' alt='{{>Title}}' width='400' >"+
					"</center><br />"+
				"{{/if}}"+
				"<b>Price:</b><br>" + 
				"<p>{{>Price}} SEK</p>" + 
				"<b>Description:</b><br>" + 
				"<p>{{>Description}}</p>" + 
			"</div>"+
			"<div class='modal-footer'>"+
				"<button type='button' class='btn btn-default addtocartmodal' data-item='{{>ID}}'>Add to cart <span class='glyphicon glyphicon-shopping-cart'></span></button>"+
				"<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+
			"</div>"
	});
	
	$.views.converters("trunc", function(val) {
		var size = 20;
		return (val.length > size) ? val.substring(0, size-3)+"..." : val; 
	});
	
	$("#products").html($.render.products(products));
	updateCart();
	
	$(".addtocart").bind("click", function(event){
		var id = $(this).attr("data-item");
		addToCart(id);
	});
	
	$(".showproduct").bind("click", function(){
		var id = $(this).attr("data-item");
		for(var i=0; i<products.length; i++){
			if(products[i].ID === id){
				$('#myModal').find(".modal-content:first").html($.render.showproduct(products[i]));
				$('#myModal').modal();
				$(".addtocartmodal").bind("click", function(event){
					var id = $(this).attr("data-item");
					addToCart(id);
				});
			}
		}
	});
	
	$("#cart_clear").bind("click", function(){
		clearCart();
	});
});

function updateCart(){
	var content = cart['content'];
	var size = cart['count'];
	var price = cart['price'];
	if(size === 0){
		$("#cart").html("<span class='emptycart'>Empty cart</span>");
	} else {
		$("#cart").html($.render.cart(cart['content']));
	}
	$("#cart_size").text(size);
	$("#cart_price").text(price);
}
function clearCart(){
	$.getJSON( "/cart.php?action=clear" , {})
	.done(function(data){
		if(data === false){
			console.log("Failed to clear cart");
		} else {
			console.log("Cleared cart: "+JSON.stringify(data));
			cart = data;
			updateCart();
		}
	});
}

function addToCart(id){
	$.getJSON( "/cart.php?action=add&pid="+id , {})
	.done(function(data){
		if(data === false){
			console.log("Failed to add to cart");
		} else {
			console.log("Added item to cart: "+JSON.stringify(data));
			cart = data;
			updateCart();
		}
	});
}

