$(document).ready(function(){
	
	var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	
	function validate(){
		var bindError = function(id, filter, error){
			var elm = $(id);
			if(filter.test(elm.val())){
				elm.parent().attr("class","has-success");
			} else {
				elm.parent().attr("class","has-error");
				elm.parent().find(".help-block").text(error);
			}
		}
		
		var filter_name = /^[\w ]{3,20}$/i;
		var filter_adr =  /^[\w ]{3,20}$/i;
		var filter_user = /^[\w]{4,20}$/i;
		
		bindError("#firstname", filter_name, "Must be 3-20 alphanumerical characters" );
		bindError("#lastname", filter_name, "Must be 3-20 alphanumerical characters" );
		bindError("#username", filter_user, "Must be at least 4 characters" );
		bindError("#address", filter_adr, "Must be at least 3 characters" );
		
		return true;
	}
	
	function validate_pass(elm){
		var elm = $("#password");
		var p = elm.val();
		var pch = p.split("");
		
		var minPasswordLength = 6;  
		var baseScore = pch.length >= minPasswordLength ? 50 : 0;  
		var num = {Excess:0,Upper:0,Numbers:0,Symbols:0};
		var bonus = {Excess:3,Upper:4,Numbers:5,Symbols:5,Combo:0,FlatLower:0,FlatNumber:0};
		
		for (i=0; i<pch.length;i++)  
		{  
			if (pch[i].match(/[A-Z]/g)) {num.Upper++;}  
			if (pch[i].match(/[0-9]/g)) {num.Numbers++;}  
			if (pch[i].match(/(.*[!,@,#,$,%,^,&,*,?,_,~])/)) {num.Symbols++;}   
		}
		num.Excess = pch.length - minPasswordLength;
		
		if (num.Upper && num.Numbers && num.Symbols){  
			bonus.Combo = 25;   
		} else if ((num.Upper && num.Numbers) || (num.Upper && num.Symbols) || (num.Numbers && num.Symbols)) {  
			bonus.Combo = 15;   
		}
		if (p.match(/^[\sa-z]+$/)) bonus.FlatLower = -15;  
		if (p.match(/^[\s0-9]+$/)) bonus.FlatNumber = -35;
		
		var score = baseScore + (num.Excess*bonus.Excess) + (num.Upper*bonus.Upper) + (num.Numbers*bonus.Numbers) +   
			(num.Symbols*bonus.Symbols) + bonus.Combo + bonus.FlatLower + bonus.FlatNumber;
		
		
		if(score >= 90){
			elm.parent().find(".help-block").text("Password strength: Strong");
			elm.parent().attr("class", "has-success bold");
		} else if (score >= 65){
			elm.parent().find(".help-block").text("Password strength: Medium");
			elm.parent().attr("class", "has-success");
		} else if (score >= 40){
			elm.parent().find(".help-block").text("Password strength: Weak");
			elm.parent().attr("class", "has-warning");
		} else {
			elm.parent().find(".help-block").text("Password strength is too weak...");
			elm.parent().attr("class", "has-error");
		}
		return true;
	};
	
	var fill = $("#firstname").val() + $("#lastname").val() + $("#username").val() + $("#password").val() + $("#address").val();
	if(fill.length > 0 || was_post){
		validate();
		validate_pass();
	}
	
	$("#signup").submit(function(){
		return validate();	
	});
	$(".validate").bind("focusout",function(){
		validate();
	});
	$("#password").bind("keyup",function(){
		return validate_pass();
	});
});