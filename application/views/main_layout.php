<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<?php foreach($styles as $file):  // => $type) { echo HTML::style($file,array('media' => $type)), "\n"; }?>   
		<link href="<?php echo $file;?>" rel="stylesheet" />
	<?php endforeach; ?>  
    <?php //foreach($scripts as $file) { echo HTML::script($file), "\n"; }?>   
</head> 
	
<body> 
	<div id="header">
		<h1 class="logo">Генерация тестовых данных.</h1>  
	</div>
	<div id="main">
		<?php echo $body; ?> 
	</div>
	<div id="right"></div> 
	<div id="footer"></div> 
</body> 

</html>