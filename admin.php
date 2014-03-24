<?php

class Tabla1Admin extends AdminPadre{
	public $model;
	public $modelName = 'Tabla1';
	public $mostrar = array('campo1','campo2','campo3');
	public $categoria = 'Cat 1';

	public function __construct(){
		parent::__construct();
	}
}

class Tabla2Admin extends AdminPadre{
	public $model;
	public $modelName = 'Tabla2';
	public $mostrar = array('otro','fk_tabla1','multiple_tabla1');
	#public $categoria = 'Cat 1';
	#public $bloqueado = true;

	public function __construct(){
		parent::__construct();
	}
}

$registradas = array('Tabla1Admin','Tabla2Admin');
