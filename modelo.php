<?php 
#holanda!
#chao
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors',1);

/*
include_once('../mustacho.php');
$mustacho = new Mustacho;*/







class Tabla1 extends ModeloPadre{
	public function __construct(){
		$this->campo1 = Modelos::text(array());
		$this->campo2 = Modelos::text(array('max_length' => 256));
		$this->campo3 = Modelos::text(array('max_length' => 256));
		$this->campo4 = Modelos::text(array('max_length' => 256));
		parent::__construct();
	}
}


class Tabla1Admin extends AdminPadre{
	public $model;
	public $modelName = 'Tabla1';
	public $campos = array('campo1','campo2','campo3','campo4');

	public function __construct(){
		parent::__construct();
	}
}


$a = new Tabla1Admin();
#$a->getGrid();
$a->getForm();
