$(function() 
	{
	window.x_rotate=0;
	window.y_rotate=0;
	
	function global_rotate(side)
					{
					
					
					function rotate(x,y)
							{
								$('#cube').css({
									'-webkit-transform':'  translateZ( -75px ) rotateX('+x+'deg) rotateY('+y+'deg)'
								});
							}
						
					var k=0;
						switch(side)
						{
						case 'bot':
							
							
							setTimeout(function() 
							{
									
									
									k++;
									if (k<=90)
									{
									window.x_rotate++;
									rotate(window.x_rotate,window.y_rotate);
									setTimeout(arguments.callee, 12);
									}
							}, 12);
							
							break;
						case 'top':
							
							
							setTimeout(function() 
							{
									
									
									k++;
									if (k<=90)
									{
									window.x_rotate--;
									rotate(window.x_rotate,window.y_rotate);
									setTimeout(arguments.callee, 12);
									}
							}, 12);
							
							break;
						case 'right':
							
							
							setTimeout(function() 
							{
									
									
									k++;
									if (k<=90)
									{
									window.y_rotate--;
									rotate(window.x_rotate,window.y_rotate);
									setTimeout(arguments.callee, 12);
									}
							}, 12);
							
							break;
						case 'left':
							
							
							setTimeout(function() 
							{
									
									
									k++;
									if (k<=90)
									{
									window.y_rotate++;
									rotate(window.x_rotate,window.y_rotate);
									setTimeout(arguments.callee, 12);
									}
							}, 12);
							
							break;
							
						}
					
					
					$('#cube').unbind('mousemove');		

				
				
					}
					
					
		$('#cube').on('mousedown', function(e) 
		{		
		
			
			$('#cube').on('mousemove', function(t) 
			{	
				x=$("#current").attr("class");
				//$("#x1").append(x+"<br>");
				//$("#current").removeAttr("id");
				
							
							
					if(e.pageY-t.pageY>50) //вверх
					{
					
					
						global_rotate('bot');
						
															
					}
					if(e.pageY-t.pageY<-50)//вниз
					{
						global_rotate('top');
					}
					if(e.pageX-t.pageX>50)//вправо
					{
						
									
					
						global_rotate('right');
							
						
																	
					}
					if(e.pageX-t.pageX<-50)//влево
					{
						global_rotate('left');
					}
			});

		});

	$('#cube').on('mouseup', function(e) 
			{	
			$('#cube').unbind('mousemove');

			});
	
	});