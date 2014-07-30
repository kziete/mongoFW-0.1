<?php
if(!file_exists(BASE_DIR . 'modelo.php')){
	echo "Nothing to do here!";
	exit();
}

require(BASE_DIR . 'core/modelo/generales.php');
require(BASE_DIR . 'core/modelo/modeloPadre.class.php');
require(BASE_DIR . 'core/widgets/widgetPadre.php');


foreach (glob(BASE_DIR . 'core/widgets/clases/*.php') as $file){
	include($file);
}

include(BASE_DIR . 'modelo.php');