$( document ).ready(function() {
   

	
		
		
		
			$( "#menu li span" ).on( "click", function() {
				var parent = $(this).parent();
				var child = $(parent).children("ul");
				if ($(child).css("display")=="none")
					$(child).fadeIn('fast');
				else 	$(child).fadeOut('fast');
				
			  });
	  
	  $( ".submenu li span" ).on( "click", function() {
				
		var child = $(this).children("ul");
		if ($(child).css("display")=="none")
			$(child).fadeIn('fast');
		else 	$(child).fadeOut('fast');
		
	  });
		
	

});
