$(document).ready(function(){
	//products -- advertising
	$(document).on('mouseenter', '.card-body', function(e){
		e.preventDefault();
		var cupon = $(this).closest(".card").find('.cupon-10');
		var price = $(this).find('span');
		var current_price = +price.data("price");
		var price_with_discount = current_price - current_price * 0.1;

		cupon.css('opacity',1);
		if ($(this).closest(".js-advert-left").length == 1){
			cupon.css('left', "100%");
			
		} else {
			cupon.css('left', "-100%");
			cupon.css('right', "100%");
		}

		price.text("$" + price_with_discount);
		price.addClass("price-cupon");
	});

	$(document).on('mouseleave', '.card-body', function(e){
		e.preventDefault();
		var cupon = $(this).closest(".card").find('.cupon-10');
		var is_left = ($(this).closest(".js-advert-left").length == 1);
		var price = $(this).find('span');

		var current_price = +price.data("price");

		cupon.css('opacity',0);
		setTimeout(function(){
			cupon.css('left','0');
			if (!is_left)
				cupon.css('right','0');
		},500);
		
		price.text("$"+current_price);
		price.removeClass("price-cupon");
	});
	//end - products -- advertising

	$(document).on('keyup', '#search-top', function(e){
		e.preventDefault();
		var dromdownEl = $(this).closest('form').find('.dropdown-menu');
		var inputVal = $(this).val();
		if (inputVal.trim() != ""){

			$.ajax({
			    type: "GET",
			    url: "/news/ajax/?tags[]="+inputVal,
			    success: function(data){

		    		dromdownEl.html(data);
			    	if (data != "")
			    		dromdownEl.addClass("show");
			    	else
			    		dromdownEl.removeClass("show");

			    }
			});		
		}
	});
	$(document).mouseup(function (e){
		var div = $("#form-search");
		if (!div.is(e.target)
		    && div.has(e.target).length === 0) { 
			$('.dropdown-menu').removeClass("show"); 
		}
	});	

	//subscriber
	setTimeout(function() { $('#subsriber').modal('show') }, 15000);

});


var inFormOrLink;
$(document).on('click', 'a', function() { inFormOrLink = true; });
$(document).on('submit', 'form', function() { inFormOrLink = true; });

$(window).bind('beforeunload', function(eventObject) {
    var returnValue = undefined;
    if (! inFormOrLink) {
        returnValue = "Do you really want to close?";
    }
    eventObject.returnValue = returnValue;
    return returnValue;
}); 