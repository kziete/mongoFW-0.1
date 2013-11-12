<?php 
require('../../config.php');
require(BASE_DIR . 'core/mustacho.php');

$mustacho->templateDir .= 'core/widgets/templates/';

require(BASE_DIR . 'core/modelo/modeloWrapper.php');

//echo 'autoadmin';