$( document ).ready(function() {

$( "#info" ).load( "reg/reg_rus.php" );


	$( "#country" )
	  .change(function () {
		var str = "";
		//$('#info').fadeOut('fast');
		$("#info").fadeTo("fast", 0);
		  //str += $( this ).text() + " ";
		var country= $( "#country option:selected" ).text();
		
		setTimeout(function(){
			switch(country)
			{
				case "Россия":
					
					$( "#info" ).load( "reg/reg_rus.php" );
					$("#country [value='Россия']").attr("selected", "selected");
					
					break;
				case "Украина":
					
					$( "#info" ).load( "reg/reg_ukr.php" );
					$("#country [value='Украина']").attr("selected", "selected");
					break;
					
			}
		},200);
		
		setTimeout(function(){$('#info').fadeTo('fast',1);},200);
	  })
			  
			  
		$( "#step1_finished" ).click(function() {
			$( "#sms_permit" ).css({'display':'block','opacity':'0'});
			  $( "#sms_permit" ).animate({
				height: "100px",
				opacity: 1,
			  }, 500 );
			  
			  var height= $("body").height(); 
			$("html,body").animate({"scrollTop":height},500); 
		
		});
		
		$( "#permit_step1" ).click(function() {
			
			
			  $( "#sms_permit" ).animate({
				height: "0px",
				opacity: 0,
				display:'none',
			  }, 500 );		  
			$("html,body").animate({"scrollTop":0},500); 
			setTimeout(function(){
				$( "#sms_permit" ).css({'display':'none'});
				/*
				$( "#main" ).animate({
				left: '-900px',
				opacity: '0',
				}, 500 );
				
				setTimeout(function(){
					$( "#main" ).load( "reg/step2.php" );
					$( "#main" ).css({'left':'900px'});
					
					
					
						$( "#main" ).animate({
						left: '0px',
						opacity: '1',
						}, 500 );
					
					
				},1200);
						
			},500);*/
			
				$( "#main" ).animate({
					left: '-900px',
					opacity: '0',
					}, 500 );
					
					setTimeout(function(){
						
						$( "#main" ).load( "reg/step2.php" );
						
						$( "#main" ).css('left','900px');
						$( "#main" ).animate({
						left: '0px',
						opacity: '1',
						}, 500 )
					},520);
			  
				
			});
			
			  
			
		});

	
		$( "#button_back" ).click(function() {

				
					
					$( "#main" ).animate({
					left: '900px',
					opacity: '0',
					}, 500 );
					
					setTimeout(function(){
						
						$( "#main" ).load( "reg/step1.php" );
						
						$( "#main" ).css('left','-900px');
						$( "#main" ).animate({
						left: '0px',
						opacity: '1',
						}, 500 )
					},520);
			  
				
			});

		
		 
			
	
});