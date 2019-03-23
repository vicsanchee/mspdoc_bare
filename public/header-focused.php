<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<title><?php echo(constant('APPLICATION_TITLE')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport"              content="width=device-width, initial-scale=1.0">
	<meta name="description"           content="<?php echo(constant('APPLICATION_TITLE')); ?>">
	<meta name="author"                content="<?php echo(constant('POWERED_BY')); ?>">

	<?php 
	echo include_css_files($GLOBALS['current_path'] . 'assets/css/styles.css'); 
	echo include_css_files($GLOBALS['current_path'] . 'assets/plugins/ladda/dist/ladda-themeless.min.css');
	?>
	
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
</head>
<body class="focusedform">