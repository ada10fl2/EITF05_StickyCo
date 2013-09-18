$(document).ready(function(){
	
	var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	
	$("#signup").submit(function(){
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
		var filter_user = /^[a-z0-9]{8,}/i;
		var filter_adr = /^[\w ]{4,20}$/i;
		
		bindError("#firstname", filter_first, "Must be 3-20 alphanumerical characters" );
		bindError("#lastname", filter_last, "Must be 3-20 alphanumerical characters" );
		bindError("#username", filter_user, "Must be 4-20 alphanumerical characters" );
		bindError("#address", filter_adr, "Must be 4-20 characters" );
		bindError("#password", enoughRegex, "Must be at least 6 characters long" );
		
		return true;	
	});
		
	$("#password").bind("keyup",function(){
		var elm = $(this);
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
		
		
		if(score >= 100){
			elm.parent().find(".help-block").text("Password strength: Strong");
			elm.parent().attr("class", "has-success");
		} else if (score >= 75){
			elm.parent().find(".help-block").text("Password strength: Medium");
			elm.parent().attr("class", "has-warning");
		} else if (score >= 50){
			elm.parent().find(".help-block").text("Password strength: Weak");
			elm.parent().attr("class", "has-error");
		} else {
			elm.parent().find(".help-block").text("Must be at least "+minPasswordLength+" characters long, with at least one digit and one uppercase");
			elm.parent().attr("class", "has-error");
		}
	});
		
	
});