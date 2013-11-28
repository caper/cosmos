$( document ).ready(function() {

		
		$( "#permit_step1" ).click(function() { //нажатие на кнопку подтверждения кода смс
			
			if ($("#permit_code").val()=="")
			{
				$("#sms_permit .error").replaceWith("<span class='error'>Введите код!</span>");
			}
			else 
			{ //тут переход на шаг 2,также использую settimeout для последовательных действий
				$("#sms_permit .error").empty();
				  $( "#sms_permit" ).animate({
					height: "0px",
					opacity: 0,
					display:'none',
				  }, 500 );		  
				$("html,body").animate({"scrollTop":0},500); 
				setTimeout(function(){
					$( "#sms_permit" ).css({'display':'none'});
					
					$( "#main" ).animate({ //свдигаю влево шаг 1
						left: '-900px',
						opacity: '0',
						}, 500 );
						
						setTimeout(function(){
							$( "#main2" ).css({'display':'block','opacity':'0'});
							$( "#main2" ).css('left','900px'); //ставлю на новое место шаг 2
							$( "#main2" ).animate({
							left: '0px',
							opacity: '1',
							}, 500 )
						},520);
			
				},500); 
			}
			
		});

});