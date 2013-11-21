<?php 
require('../../config.php');
require(BASE_DIR . 'core/mustacho.php');
require(BASE_DIR . 'core/dbhelper.php');
$db = new DbHelper();

$mustacho->templateDir .= 'core/widgets/templates/';
require(BASE_DIR . 'core/modelo/modeloWrapper.php');


$m = new Mustacho();
$m->templateDir .= 'public/admin/templates/';

$modelos = array();
foreach($registradas as $k => $v){
  $modelos[$v] = new $v(); 
}


$hash = array(
	'disponibles' => $registradas
);

if($_GET['modelo'] && class_exists($_GET['modelo'])){	
	$modelo = $_GET['modelo'];
	$a = $modelos[$modelo]; 

	if($_REQUEST['index'])
		$hash['content'] = $a->getForm($_REQUEST['index']);
	else
		$hash['content'] = $m->render(
			'cascara_grid.html', 
			array(
				'modelo' => $modelo,
				'content' => $a->getGrid()
			)
		);
}else{
	$hash['content'] = $m->render('lista.html',$hash);
}
 
echo $m->render('contenedor.html',$hash);