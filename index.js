$(document).ready(function() {
	
	var tmp = [
		{
			id: 0,
			title: "Products 1",
			description: "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui."
		},
		{
			id: 1,
			title: "Products 2",
			description: "Elit non mi porta imon gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui."
		}	
	];
	
	
	$.templates({
		products: 
			"<div class='col-6 col-sm-6 col-lg-4'>"+
				"<h2>{{>title}}</h2>"+
				"<p>{{>description}}</p>" + 
				"<p><a class='btn btn-default' href='/product.php?id={{>id}}'>View More</a></p>"+
			"</div>"
	});
	
	$("#products").html($.render.products(tmp));
});