<?php 
header("X-Powered-By: Mongo Framework");
require('../config.php');

require(BASE_DIR . 'core/mustacho.class.php');
require(BASE_DIR . 'core/dbhelper.class.php');
$db = new DbHelper();

$mustacho->templateDir .= 'core/widgets/templates/';
require(BASE_DIR . 'core/modelo/modeloWrapper.php');
require(BASE_DIR . 'core/libs/Toro.php');

/*Vistas*/
require(BASE_DIR . 'core/vista.class.php');
require(BASE_DIR . 'core/admin/admin.php');
require(BASE_DIR . 'vistas.php');


/*Router*/
require(BASE_DIR . 'rutas.php');
