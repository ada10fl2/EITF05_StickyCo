var CART = [];

$(document).ready(function() {	
	$.templates({
		cart : 
			"<tr><td colspan=3><b>{{>Title}}</b><td></tr>"+
				"<tr><td>{{if Image}}"+
					"<img src='img/small/{{>Image}}' alt='{{>Title}}' width='40' >"+
				"{{/if}}</td>"+
				"<td>{{>count}}st</td> <td>{{>price}} kr/st<td>  <td>{{>prodtotal}} kr</td><td>"+
				"<button type='button' class='btn btn-primary btn-xs addtocart' data-item='{{>ID}}'><span class='glyphicon glyphicon-plus'></span></button>"+
				"</td><td>"+
				"<button type='button' class='btn btn-primary btn-xs removefromcart' data-item='{{>ID}}'><span class='glyphicon glyphicon-minus'></span></button>"+
			"</td></tr>"
	});

	updateCart();
	$("#cart_clear").bind("click", function(){
		clearCart();

	});
	$("#creditcard").bind("change",function(){
		var elm = $(this);
		var parent = elm.parent();
		var value = elm.val().replace(/\s+/g, '');
		var pch = value.split("");
		
		elm.val(value); // Update after repalceAll
		
		var cardhelp = parent.find(".cardinfo");
		var cardimg = parent.find(".cardimg");
		
		var isShowingMaster = cardimg.hasClass('mastercard');
		var isShowingVisa = cardimg.hasClass('visacard');
		
		function clearImg(span){
			span
				.removeClass("mastercard")
				.removeClass("visacard");
		}
		
		if(pch[0] == 4){ //VISA
			if(!isShowingVisa) {
				clearImg(cardimg);
				cardimg.addClass("visacard");
			}
			if(pch.length < 13 || pch.length > 16 || !calculateCard(value)){
				cardhelp.text("detected, but it is not valid");
				parent.attr("class", "has-error");
			} else {
				parent.attr("class", "has-success");
				cardhelp.text("detected, fully valid!");
			}
		}else if(pch[0] == 5 && pch[1]>0 && pch[1]<6) { //MASTER CARD
			cardhelp.text("Master Card");
			if (!isShowingMaster) {
				clearImg(cardimg);
				cardimg.addClass("mastercard");
			}
			if (pch.length < 16 || pch.length > 19 || !calculateCard(value)){
				cardhelp.text("detected, but it is not valid");
				parent.attr("class", "has-error");
			} else {
				parent.attr("class", "has-success");
				cardhelp.text("detected, fully valid!");
			}
		} else {
			cardhelp.text("Not a card");
			parent.attr("class", "has-error");
		}
	});
	
	$("#order").submit(function(){
		var bindError = function(id, filter, error){
			var elm = $(id);
			if(filter.test(elm.val())){
				elm.parent().attr("class","has-success");
			} else {
				elm.parent().attr("class","has-error");
				elm.parent().find(".help-block").text(error);
			}
		}
		
		var filter_first = /^[\w ]{3,20}$/i;
		var filter_last = /^[\w ]{3,20}$/i;
		var filter_adr = /^[\w ]{4,20}$/i;
		
		bindError("#firstname", filter_first, "Must be 3-20 alphanumerical characters" );
		bindError("#lastname", filter_last, "Must be 3-20 alphanumerical characters" );
		bindError("#address", filter_adr, "Must be 4-20 characters" );
		
		return true;	
	});
	
});

function calculateCard(Luhn) {
	var sum = 0;
	for (i=0; i<Luhn.length; i++ ) {
		sum += parseInt(Luhn.substring(i,i+1));
	}
	
	var delta = new Array (0, 1, 2, 3, 4, -4, -3, -2, -1, 0);
	for (i=Luhn.length-1; i>=0; i-=2 ) {		
		var deltaIndex = parseInt(Luhn.substring(i, i + 1));
		var deltaValue = delta[deltaIndex];	
		sum += deltaValue;
	}	

	var mod10 = sum % 10;
	mod10 = 10 - mod10;	
	
	if (mod10 == 10) {		
		mod10 = 0;
	}
	
	return mod10;
}

function updateCart(){
	var content = cart['content'];
	var size = cart['count'];
	var price = cart['price'];
	if(size === 0){
		sendUserToIndex(); // Cart is empty so nothing to see here
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
		removeFromCart(id);
	});
}

function sendUserToIndex(){
	document.location = "/index.php";
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

function removeFromCart(id){
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

