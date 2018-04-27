$(document).ready(function(){
	$(document).on('keyup', '#search-top', function(e){
		console.log("hello");
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