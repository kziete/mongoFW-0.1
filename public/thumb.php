<?php 
require '../config.php';

$url = explode('/',$_SERVER['PATH_INFO']);

$url = array_slice($url, 1);

$_GET['w'] = $url[0];
$_GET['h'] = $url[1];
if($url[2] == 'crop'){
	$_GET['zc'] = true;
	$url = array_slice($url, 3);
	$_GET['src'] = '/' . join('/',$url);
}else{
	$url = array_slice($url, 2);
	$_GET['src'] = '/' . join('/',$url);	
}
#print_r($_GET);exit();
require BASE_DIR . 'core/libs/phpthumb/phpThumb.php';