var CART = [];

$(document).ready(function() {	
	
	$.templates({
		products: 
			"<div class='col-6 col-sm-6 col-lg-4 product'>"+
				"<h2 class='product-header'>{{trunc:Title}}</h2>"+
				"{{if Image}}"+
					"<center>"+
					"<img src='img/medium/{{>Image}}' alt='{{>Title}}' width='200' >"+
					"</center>"+
				"{{/if}}"+
				"<div class='product-info'>"+
				"<div class='product-description'><p>{{>Description}}</p></div>" + 
				"<p><b>{{>Price}} SEK</b></p>" + 
				"</div>"+
				"<p>"+ 
					"<button type='button' class='btn btn-default btn-sm showproduct' data-item='{{>ProductID}}'>View More <span class='glyphicon glyphicon-info-sign'></span> </button>"+
					"<button type='button' class='btn btn-default btn-sm addtocart' data-item='{{>ProductID}}'>Add to cart <span class='glyphicon glyphicon-shopping-cart'></span></button>"+
				"</p>"+
				"<a id='product{{>ProductID}}'></a>"+
			"</div>",
		cart : 
			"<tr><td colspan=3><b>{{>Title}}</b><td></tr>"+
				"<tr><td>{{if Image}}"+
					"<img src='img/small/{{>Image}}' alt='{{>Title}}' width='40' >"+
				"{{/if}}</td>"+
				"<td>{{>count}}st</td> <td>{{>price}} kr/st<td>  <td>{{>prodtotal}} kr</td><td>"+
				"<button type='button' class='btn btn-primary btn-xs addtocart' data-item='{{>ProductID}}'><span class='glyphicon glyphicon-plus'></span></button>"+
				"</td><td>"+
				"<button type='button' class='btn btn-primary btn-xs removefromcart' data-item='{{>ProductID}}'><span class='glyphicon glyphicon-minus'></span></button>"+
				"</td></tr>",
		showproduct: 
			"<div class='modal-header'>"+
				"<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>"+
				"<h4 class='modal-title'>{{>Title}}</h4>"+
			"</div>"+
			"<div class='modal-body'>"+
				"{{if Image}}"+
					"<center>"+
					"<img src='img/large/{{>Image}}' alt='{{>Title}}' width='400' >"+
					"</center><br />"+
				"{{/if}}"+
				"<b>Price:</b><br>" + 
				"<p>{{>Price}} SEK</p>" + 
				"<b>Description:</b><br>" + 
				"<p>{{>Description}}</p>" + 
			"</div>"+
			"<div class='modal-footer'>"+
				"<button type='button' class='btn btn-default addtocartmodal' data-item='{{>ProductID}}'>Add to cart <span class='glyphicon glyphicon-shopping-cart'></span></button>"+
				"<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+
			"</div>"
	});
	
	$.views.converters("trunc", function(val) {
		var size = 20;
		return (val.length > size) ? val.substring(0, size-3)+"..." : val; 
	});
	
	$("#products").html($.render.products(products));
	updateCart();
	

	
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
	$("#checkout").bind("click", function(){
		document.location = "/checkout.php";
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

	$(".addtocart").bind("click", function(event){
		var id = $(this).attr("data-item");
		addToCart(id);
	});
	$(".removefromcart").bind("click", function(event){
		var id = $(this).attr("data-item");
		removefromcart(id);
	});
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

function removefromcart(id){
	$.getJSON( "/cart.php?action=remove&pid="+id , {})
	.done(function(data){
		if(data === false){
			console.log("Failed to remove from cart cart");
		} else {
			console.log("Removed item from cart: "+JSON.stringify(data));
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

