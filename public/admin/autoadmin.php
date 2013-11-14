<?php 
require('../../config.php');
require(BASE_DIR . 'core/mustacho.php');

$mustacho = new Mustacho();
$mustacho->templateDir .= 'core/widgets/templates/';
require(BASE_DIR . 'core/modelo/modeloWrapper.php');


$m = new Mustacho();
$m->templateDir .= 'public/admin/templates/';

$modelos = array();
foreach($registradas as $k => $v){
  $modelos[$v] = new $v(); 
}


if($_GET['modelo'] && class_exists($_GET['modelo'])){	

	$modelo = $_GET['modelo'];

	$a = $modelos[$modelo];
  
  echo $m->render(
    'contenedor.html',
    array(
      'content' => $a->getGrid(),
      'disponibles' => $registradas
    )
  );
}