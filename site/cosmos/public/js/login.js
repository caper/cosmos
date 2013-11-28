
$( document ).ready(function() {

  
   $('#id1,#id2,#id3,#id4,#id5,#id6,#id7,#id8,#id9,#id10,#id10_1,#id11,#id12,#id13').click(function(){
	//по нажатию кнопок
		var background=$(this).css("background");
		
		var name_id=$(this).attr("id");
		
		
		var line_name="#line"+name_id.substr(2, name_id.lenght); //номер в id линии такой же как у id кнопки  (#id11 => #line11)
		
		$('#line1,#line2,#line3,#line4,#line5,#line6,#line7,#line8,#line9,#line10,#line10_1,#line11,#line12,#line13').css('display','none');
		$('#subline9,#subline10,#subline10_1,#subline13,#subline6,#subline2').css('display','none');
		$("#subsubline6").css('display','none'); //скрываю все линии 
		
		
		$(line_name).css('display','block'); //показываю нужную линию
		
		if(name_id=="id9"||name_id=="id10"||name_id=="id10_1"||name_id=="id13"||name_id=="id6"||name_id=="id2")
		{
			//у некоторых кнопок есть дополнительный кусок линии,его тоже нужно показать
			var subline_name="#subline"+name_id.substr(2, name_id.lenght); 
			$(subline_name).css('display','block');
		}
		
		if (name_id=="id6") $("#subsubline6").css('display','block'); //открываю 3ий кусок линии
	
		//далее скрываю и открываю рабочее окно
		$('#work_window').fadeOut('fast'); 

		$('#work_window').empty();
		
		background=background.replace('right','left'); //делаю фон окна таким же,но градиент в противоположную сторону сторону
		setTimeout(function(){ //использую таймаут потому что так красивее помойму
		$("#work_window").css({"background":background}); 
			}, 200);
		$('#work_window').fadeIn('fast');
   });
   
   $('#btn2').click(function(){
	  setTimeout(function(){
	  $('#work_window').append('<iframe scrolling="no" src="/cabinet/lar/" width="100%" height="100%" align="left" frameborder="0" > </iframe>');	
		}, 200);
   }); 
   
    $('#btn4').click(function(){
	    $("#work_window").css("background",'none');
	    setTimeout(function(){ 
		    $('#work_window').append('<iframe src="http://www.gismeteo.ru/city/daily/4944/" width="100%" height="100%" align="left" frameborder="0" > </iframe>');
		}, 200);
	});
  
});



