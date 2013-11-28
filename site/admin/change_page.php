<?php header('Content-type: text/html; charset=utf-8'); ?>
<!doctype html>
<html>
<head>
		<script language="Javascript" src="htmlbox/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script language="Javascript" src="htmlbox/htmlbox.colors.js" type="text/javascript"></script>
	<script language="Javascript" src="htmlbox/htmlbox.styles.js" type="text/javascript"></script>
	<script language="Javascript" src="htmlbox/htmlbox.syntax.js" type="text/javascript"></script>
	<script language="Javascript" src="htmlbox/xhtml.js" type="text/javascript"></script>
	<script language="Javascript" src="htmlbox/htmlbox.min.js" type="text/javascript"></script>
</head>
<body>
<a href="change_menu.php">Отмена</a>

	<textarea id='ha'></textarea>
<script language="Javascript" type="text/javascript">
$("#ha").css("height","100%").css("width","100%").htmlbox({
    toolbars:[
	    [
		// Cut, Copy, Paste
		"separator","cut","copy","paste",
		// Undo, Redo
		"separator","undo","redo",
		// Bold, Italic, Underline, Strikethrough, Sup, Sub
		"separator","bold","italic","underline","strike","sup","sub",
		// Left, Right, Center, Justify
		"separator","justify","left","center","right",
		// Ordered List, Unordered List, Indent, Outdent
		"separator","ol","ul","indent","outdent",
		// Hyperlink, Remove Hyperlink, Image
		"separator","link","unlink","image"
		
		],
		[// Show code
		"separator","code",
        // Formats, Font size, Font family, Font color, Font, Background
        "separator","formats","fontsize","fontfamily",
		"separator","fontcolor","highlight",
		],
		[
		//Strip tags
		"separator","removeformat","striptags","hr","paragraph",
		// Styles, Source code syntax buttons
		"separator","quote","styles","syntax"
		]
	],
	skin:"blue"
});


</script>

<script>
$('#ha_container').css('height','400px');
$('#ha').css('height','400px');

$('#ha_html').contents().find( "body" ).html(<?php print " '{$_POST['html']}' "?>);
   
	function change()
	{
	//alert($('#ha_html').contents().find( "body" ).html());
	var text=$('#ha_html').contents().find( "body" ).html();
	$("#pagehtml").attr('value',text);
	$("#forma").submit();
	}
	



</script>

<form id="forma" action="change_menu.php" method="post">
	<input name="change_page_id" type="hidden" value="<?php echo $_POST['change_page_id']; ?>">
	<input id="pagehtml" name="html" type="hidden" > 
</form>

<button onclick=" change()" >Изменить</button>



</body>

</html>