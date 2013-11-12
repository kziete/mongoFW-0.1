<?php 
require('../../config.php');
require(BASE_DIR . 'core/mustacho.php');

$mustacho->templateDir .= 'core/widgets/templates/';

require(BASE_DIR . 'core/modelo/modeloWrapper.php');



if($_GET['modelo'] && class_exists($_GET['modelo'])){	

	$modelo = $_GET['modelo'];

	$a = new $modelo();
	#$a->getGrid();
	$a->getForm();

}