var CART = []; // ensure visible
var wasPosted = false; // ensure visible

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
	
	var validateCard = function() {
		var elm = $("#cc_nr");
		var parent = elm.parent();
		var org = elm.val();		
		var trim = org.trim();
		var value = org.replace(/\s+/g, '');
		
		var groups = trim.match(/\d\d\d\d/g);
		var newVal = (groups && value.length % 4 === 0) ? groups.join(" ") : org;
		if(value.length % 4 == 0 && value.length < 16) newVal += " ";
		
		elm.val(newVal); //Update after trim and rouping
		
		var pch = value.split("");
		
		var cardhelp = parent.find(".cardinfo");
		var cardimg = parent.find(".cardimg");
		
		var isShowingMaster = cardimg.hasClass('mastercard');
		var isShowingVisa = cardimg.hasClass('visacard');
		
		function clearImg(span){
			span.removeClass("mastercard").removeClass("visacard");
		}
		
		if(pch[0] == 4){ //VISA
			if(!isShowingVisa) {
				clearImg(cardimg);
				cardimg.addClass("visacard");
			}
			if(pch.length < 13 || pch.length > 16 || !validateLuhn(value)){
				cardhelp.text("detected, but it is not valid");
				parent.attr("class", "has-error");
			} else {
				parent.attr("class", "has-success");
				cardhelp.text("detected, fully valid!");
				return true;
			}
		}else if(pch[0] == 5 && pch[1]>0 && pch[1]<6) { //MASTER CARD
			cardhelp.text("Master Card");
			if (!isShowingMaster) {
				clearImg(cardimg);
				cardimg.addClass("mastercard");
			}
			if (pch.length < 16 || pch.length > 19 || !validateLuhn(value)){
				cardhelp.text("detected, but it is not valid");
				parent.attr("class", "has-error");
			} else {
				parent.attr("class", "has-success");
				cardhelp.text("detected, fully valid!");
				return true;
			}
		} else {
			cardhelp.text("Not a card");
			parent.attr("class", "has-error");
		}
		return false;
	};
	
	function wlkey(code){
		return code !== 8 && code !==9;
	}
	
	function ensureValidCard(yyid,filter_yy,mmid,filter_mm) {
		var mmval = $(mmid).val();
		var yyval = $(yyid).val();
		
		if( mmval.length === 0 ) return false;
		if( yyval.length === 0 ) return false;
		
		var valid = false; 
		var msg = "Enter a valid year/month";
		if(filter_yy.test(yyval) && filter_mm.test(mmval)){		
			var yy = parseInt(yyval);
			var mm = parseInt(mmval);
			var par = $(yyid).parent();
			var curMM = new Date().getMonth() + 1; //[0-11] + 1
			var curYY = new Date().getFullYear() - 2000;
			if(curYY < yy || curYY === yy && curMM <= mm){
				valid = true;	
			} else {
				msg = "Your card has expired";
			}			
		}
		
		var par = $(yyid).parent();
		if(valid === true) {
			par.attr("class","has-success");
		} else {
			par.attr("class","has-error");
			par.find(".help-block").text(msg);
		}
		
		return valid;
	}
	
	var timer = undefined;
	
	$("#cc_nr").bind("keyup", function(){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode < 48 || keycode > 57) return true; 
		if(timer) clearTimeout(timer);
		if($(this).val().split("").length >= 12){
			timer = setTimeout(validateCard, 10);
		} else {
			timer = setTimeout(validateCard, 2000);
		}
	});
	
	function validate(){
		var bindError = function(id, filter, error){
			var elm = $(id);
			if(filter.test(elm.val())){
				elm.removeClass("has-error");
				if(elm.parent().find(".has-error").length === 0) {
					elm.parent().attr("class","has-success");
				}
				return true;
			} else {
				elm.addClass("has-error");
				elm.parent().attr("class","has-error");
				elm.parent().find(".help-block").text(error);
				return false;
			}
		};
		var filter_name = /^[\w ]{3,45}$/i;
		var filter_adr = /^[\w \-,\.#()]{4,200}$/i;
		var filter_cvv = /^[\d]{3}$/;
		var filter_yy = /^[\d]{2}$/;
		var filter_mm = /^([0][1-9])|([1][0-2])$/;
		
		var valid = validateCard() &
			ensureValidCard("#cc_yy", filter_yy, "#cc_mm", filter_mm, "Your card is not valid") &
			bindError("#firstname", filter_name, "Must be 3-45 alphanumerical characters" ) &
			bindError("#lastname", filter_name, "Must be 3-45 alphanumerical characters" ) &
			bindError("#cc_cv", filter_cvv, "Must be 3 digits" ) & 
			bindError("#address", filter_adr, "Must be 4-200 characters (A-Z,a-z,0-9,' ','-','.',',','#','(',')')" );	
			
		return valid === 1;
	};
	
	$("#order").submit(validate);
	
	if(wasPosted) validate();
});

function validateLuhn (cc) {
	return !/^\d+$/.test(cc) || (cc.split('').reduce(function(sum, d, n){ 
            return sum + parseInt((n%2)? [0,2,4,6,8,1,3,5,7,9][d]: d); 
	}, 0)) % 10 == 0;
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

