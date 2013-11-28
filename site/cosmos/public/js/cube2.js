$(function() 
	{
	window.x_rotate=0;
	window.y_rotate=0;
	window.z_rotate=0;
	
					
		function rotate(x,y,z)
							{
								$('#cube').css({
									'-webkit-transform':'  translateZ( -75px ) rotateX('+x+'deg) rotateY('+y+'deg) rotateZ('+z+'deg)'
								});
							}
							
		function show_right(side)
		{
		
		
			var k=0;
			switch(side)
				{
					case 'front':	y=0;x=0; break;
					case 'top':	y=0;x=-90; break;
					case 'back':	y=180;x=0; break;
					case 'bottom':	y=0;x=90; break;
					
				}
			
			setTimeout(function() 
				{

					k=k+2;
					if (k<=90)
					{
						switch(side)
						{
							case 'front':	y=y-2; break;
							case 'top':	x=x+2;y=y-2; break;
							case 'back':	y=y+2; break;
							case 'bottom':	x=x-2; y=y-2; break;
							
						}
						rotate(x,y,window.z_rotate);
						setTimeout(arguments.callee, 12);
					}
				}, 12);

			$('#cube').unbind('mousemove');				
		}		
		function show_top(side)
		{
		
		
			var k=0;
			switch(side)
				{
					case 'front':	y=0;x=0; break;
					case 'right':	y=-90;x=0; break;
					case 'back':	y=180;x=0;  break;
					case 'left':	y=90;x=0; break;
					
				}
			
			setTimeout(function() 
				{

					k=k+2;
					if (k<=90)
					{
						switch(side)
						{
							case 'front':	x=x-2; break;
							case 'right':	y=y+2;x=x-2; break;
							case 'back':	y=y-4;x=x-2; break;
							case 'left':	y=y-2;x=x-2; break;
						}
						rotate(x,y,window.z_rotate);
						setTimeout(arguments.callee, 12);
					}
				}, 12);

			$('#cube').unbind('mousemove');				
		}		
		function show_bottom(side)
		{
		
		
			var k=0;
			switch(side)
				{
					case 'back':	y=180;x=0; break;
					case 'front':	y=0;x=0; break;
					case 'right':	y=-90;x=0; break;
					case 'left':	y=90;x=0; break;
					
				}
			
			setTimeout(function() 
				{

					k=k+2;
					if (k<=90)
					{
						switch(side)
						{
							case 'back':	y=y-4; x=x+2; break;
							case 'front':	x=x+2; break;
							case 'right':	x=x+2; y=y+2; break;
							case 'left':	x=x+2; y=y-2; break;
							
						}
						rotate(x,y,window.z_rotate);
						setTimeout(arguments.callee, 12);
					}
				}, 12);

			$('#cube').unbind('mousemove');				
		}		
		function show_back(side)
		{
		
		
			var k=0;
			switch(side)
				{
					case 'right':	y=-90;x=0;// $('.back').removeClass('transform_back'); 
					break;
					case 'top':	y=0;x=-90; //$('.back').addClass('transform_back'); 
					break;
					case 'left':	y=90;x=0;// $('.back').removeClass('transform_back'); 
					break;
					case 'bottom':	y=0;x=90; //$('.back').addClass('transform_back'); 
					break;
					
				}
			
			
			setTimeout(function() 
				{

					k=k+2;
					if (k<=90)
					{
						switch(side)
						{
							case 'right': y=y-2; break;
							case 'top':	x=x+2; y=y+4; 	
							break;
							case 'left': y=y+2; break;
							case 'bottom': y=y+4;x=x-2; break;
							
						}
						rotate(x,y,window.z_rotate);
						setTimeout(arguments.callee, 12);
					}
				}, 12);
			
			$('#cube').unbind('mousemove');				
		}		
		function show_left(side)
		{
		
		
			var k=0;
			switch(side)
				{
					case 'back':	y=180;x=0; break;
					case 'front':	y=0;x=0; break;
					case 'top':	y=0;x=-90; break;
					case 'bottom':	y=0; x=90; break;
					
					
				}
			
			setTimeout(function() 
				{

					k=k+2;
					if (k<=90)
					{
						switch(side)
						{
							case 'back':	y=y-2; break;
							case 'front':	y=y+2; break;
							case 'top':	y=y+2;x=x+2; break;
							case 'bottom':	x=x-2; y=y+2; break;
						}
						rotate(x,y,window.z_rotate);
						setTimeout(arguments.callee, 12);
					}
				}, 12);

			$('#cube').unbind('mousemove');				
		}		
		function show_front(side)
		{
		
		
			var k=0;
			switch(side)
				{
					case 'left':	y=-270;x=0; break;
					case 'right':	y=-90;x=0; break;
					case 'bottom':	y=0;x=-270; break;
					case 'top':	y=0;x=-90; break;
					
				}
			
			
			setTimeout(function() 
					{

						k=k+2;
						if (k<=90)
						{
							switch(side)
							{
								case 'left':	y=y-2; break;
								case 'right':	y=y+2; break;
								case 'top':	x=x+2; break;
								case 'bottom':	x=x-2; break;
								
								
							}
							rotate(x,y,window.z_rotate);
							setTimeout(arguments.callee, 12);
						}
					}, 12);
			
			
								
			$('#cube').unbind('mousemove');				
		}	
			
					
		$('#cube').on('mousedown', function(e) 
		{		
			$('#cube').on('mousemove', function(t) 
			{	
				
				
				
					if(e.pageY-t.pageY>50) //вниз
					{
						var x=$("#current").attr("class");
						$("#current").removeAttr("id");
							switch(x)
							{
							
								case 'top': 						
								$('.front').attr({"id":"current"});
								show_front('top');
								break;
								case 'bottom': 						
								$('.back').attr({"id":"current"});
								show_back('bottom');
								break;
								case 'right': 							
								$('.bottom').attr({"id":"current"});
								show_bottom('right');
								break;
								case 'left': 							
								$('.bottom').attr({"id":"current"});
								show_bottom('left');
								break;
								case 'front': 							
								$('.bottom').attr({"id":"current"});
								show_bottom('front');
								break;
								case 'back': 							
								$('.bottom').attr({"id":"current"});
								show_bottom('back');
								break;
								case 'back transform_back': 							
								$('.top').attr({"id":"current"});
								show_top('back');
								break;
								
							 
								
							}	
							
								global_rotate('bot');										
					}
					if(e.pageY-t.pageY<-50) //наверх
					{
						var x=$("#current").attr("class");
						$("#current").removeAttr("id");
							
							
						switch(x)
							{
							
								case 'top': 						
								$('.back').attr({"id":"current"});
								show_back('top');
								break;
								case 'bottom': 						
								$('.front').attr({"id":"current"});
								show_front('bottom');
								break;
								case 'right': 							
								$('.top').attr({"id":"current"});
								show_top('right');
								break;
								case 'left': 							
								$('.top').attr({"id":"current"});
								show_top('left');
								break;
								case 'front': 							
								$('.top').attr({"id":"current"});
								show_top('front');
								break;
								case 'back': 							
								$('.top').attr({"id":"current"});
								show_top('back')
								break;
								case 'back transform_back': 							
								$('.bottom').attr({"id":"current"});
								show_bottom('back')
								break;
								
							 
								
							}	
							global_rotate('top'); 
					}
					if(e.pageX-t.pageX>50) //вправо
					{
						var x=$("#current").attr("class");
						$("#current").removeAttr("id");
						
						switch(x)
							{
							
								case 'top': 						
								$('.right').attr({"id":"current"});
								show_right('top');																				
								break;
								case 'bottom': 						
								$('.right').attr({"id":"current"});
								show_right('bottom');
								break;
								case 'right': 							
								$('.back').attr({"id":"current"});
								show_back('right');
								break;
								case 'left': 							
								$('.front').attr({"id":"current"});
								show_front('left');
								break;
								case 'front': 							
								$('.right').attr({"id":"current"});
								show_right('front');
								break;
								case 'back': 							
								$('.left').attr({"id":"current"});
								show_left('back');
								break;
								case 'back transform_back': 							
								$('.left').attr({"id":"current"});
								show_right('back');
								break;
								
								
								
							}
						
						
																	
					}
					if(e.pageX-t.pageX<-50) //влево
					{
						var x=$("#current").attr("class");
						$("#current").removeAttr("id");
						switch(x)
							{
							
								case 'top': 						
								$('.left').attr({"id":"current"});
								show_left('top');
								break;
								case 'bottom': 						
								$('.left').attr({"id":"current"});
								show_left('bottom');
								break;
								case 'right': 							
								$('.front').attr({"id":"current"});
								show_front('right');
								break;
								case 'left': 							
								$('.back').attr({"id":"current"});
								show_back('left');
								break;
								case 'front': 							
								$('.left').attr({"id":"current"});
								show_left('front');
								break;
								case 'back': 							
								$('.right').attr({"id":"current"});
								show_right('back');
								break;
								case 'back transform_back': 							
								$('.left').attr({"id":"current"});
								show_left('back');
								break;
								
								
								
							}
							global_rotate('left');
					}
			});

		});

	$('#cube').on('mouseup', function(e) 
			{	
			$('#cube').unbind('mousemove');

			});
	
	});