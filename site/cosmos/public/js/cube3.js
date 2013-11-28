$(function() 
	{
	window.x_rotate=0;
	window.y_rotate=0;
	window.z_rotate=0;
	
	function global_rotate(side)
					{
					
					function rotate(x,y,z)
							{
								$('#cube').css({
									'-webkit-transform':'  translateZ( -75px ) rotateX('+x+'deg) rotateY('+y+'deg) rotateZ('+z+'deg)'
								});
							}
						
					var k=0;
					var count_x=window.x_rotate / 90;
						switch(side)
						{
						case 'bot':
							
							
							setTimeout(function() 
							{
									
									
									k++;
									if (k<=90)
									{
									window.x_rotate++;
									rotate(window.x_rotate,window.y_rotate,window.z_rotate);
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
									rotate(window.x_rotate,window.y_rotate,window.z_rotate);
									setTimeout(arguments.callee, 12);
									}
							}, 12);
							
							break;
						case 'right':
							
							
							//alert(a%2);
							
							if(count_x%2==0)
							{	
							//alert((count_x/2)%2);
							//alert('1');
								if(count_x%4==0)
								{	
								//alert('2');
									setTimeout(function() 
									{					
											k++;
											if (k<=90)
											{
											window.y_rotate--;
											rotate(window.x_rotate,window.y_rotate,window.z_rotate);
											setTimeout(arguments.callee, 12);
											}
									}, 12);
								}	
								else
								{
								//alert('3');
									setTimeout(function() 
									{					
											k++;
											if (k<=90)
											{
											window.y_rotate++;
											rotate(window.x_rotate,window.y_rotate,window.z_rotate);
											setTimeout(arguments.callee, 12);
											}
									}, 12);
								}
							}
							else							
							{	
								if(count_x%4==1||count_x%4==-1)
								{	setTimeout(function() 
									{					
											k++;
											if (k<=90)
											{
											window.z_rotate--;
											rotate(window.x_rotate,window.y_rotate,window.z_rotate);
											setTimeout(arguments.callee, 12);
											}
									}, 12);
								}
								else
								{
									setTimeout(function() 
									{					
											k++;
											if (k<=90)
											{
											window.z_rotate++;
											rotate(window.x_rotate,window.y_rotate,window.z_rotate);
											setTimeout(arguments.callee, 12);
											}
									}, 12);
								}
							}	
							
							break;
						case 'left':
							
							if(count_x%2==0)
							{	
								if((count_x/2)%2==1||(count_x/2)%2==-1)
								{
									setTimeout(function() 
									{
											
											
											k++;
											if (k<=90)
											{
											window.y_rotate--;
											rotate(window.x_rotate,window.y_rotate,window.z_rotate);
											setTimeout(arguments.callee, 12);
											}
									}, 12);
								}
								else
								{
									setTimeout(function() 
									{
											
											
											k++;
											if (k<=90)
											{
											window.y_rotate++;
											rotate(window.x_rotate,window.y_rotate,window.z_rotate);
											setTimeout(arguments.callee, 12);
											}
									}, 12);
								}
							}
							else
							{
								if(count_x%4==1||count_x%4==-1)
								{	setTimeout(function() 
									{					
											k++;
											if (k<=90)
											{
											window.z_rotate++;
											rotate(window.x_rotate,window.y_rotate,window.z_rotate);
											setTimeout(arguments.callee, 12);
											}
									}, 12);
								}
								else
								{
									setTimeout(function() 
									{					
											k++;
											if (k<=90)
											{
											window.z_rotate--;
											rotate(window.x_rotate,window.y_rotate,window.z_rotate);
											setTimeout(arguments.callee, 12);
											}
									}, 12);
								}
							}
							break;
							
						case 'rightz':
							
							//alert(side);
							setTimeout(function() 
							{
									
									
									k++;
									if (k<=90)
									{
									window.z_rotate--;
									rotate(window.x_rotate,window.y_rotate,window.z_rotate);
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
				
				
				
					if(e.pageY-t.pageY>50) //вниз
					{
						var x=$("#current").attr("class");
						$("#current").removeAttr("id");

							
								global_rotate('bot');										
					}
					if(e.pageY-t.pageY<-50) //наверх
					{
						var x=$("#current").attr("class");
						$("#current").removeAttr("id");
							
	
								global_rotate('top'); 
		
								
					}
					if(e.pageX-t.pageX>50) //вправо
					{
						var x=$("#current").attr("class");
						$("#current").removeAttr("id");
						 global_rotate('right'); 
								
						
																	
					}
					if(e.pageX-t.pageX<-50) //влево
					{
						var x=$("#current").attr("class");
						$("#current").removeAttr("id");
						
								
							global_rotate('left');

					}
			});

		});

	$('#cube').on('mouseup', function(e) 
			{	
			$('#cube').unbind('mousemove');

			});
	
	});