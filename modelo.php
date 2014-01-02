<?php 

class Tabla1 extends ModeloPadre{
	public function __construct(){
		$this->campo1 = Modelos::text(array(
			'nombre' => 'Nombre Bonito'
		));
		$this->campo2 = Modelos::file();
		$this->campo3 = Modelos::opcion(array(
			'opciones' => array(
				'a' => 'Primera',
				'b' => 'Segunda',
				'c' => 'Tercera'
			)
		));
		$this->campo4 = Modelos::tinymce(array('max_length' => 256));
		parent::__construct();
	}
}

class Tabla2 extends ModeloPadre{
	public function __construct(){
		$this->otro = Modelos::text(array(
			'validar' => function($value){
				#return "error Cuaaatico";
			}, 
			'notnull' => true
		));
		$this->fk_tabla1 = Modelos::referencia(array(
			'model' => 'Tabla1',
			'label' => 'campo1',
			#'notnull' => true
		));
		parent::__construct();
	}
}




class Tabla1Admin extends AdminPadre{
	public $model;
	public $modelName = 'Tabla1';
	public $mostrar = array('campo1','campo2','campo3');

	public function __construct(){
		parent::__construct();
	}
}

class Tabla2Admin extends AdminPadre{
	public $model;
	public $modelName = 'Tabla2';
	public $mostrar = array('otro','fk_tabla1');
	#public $bloqueado = true;

	public function __construct(){
		parent::__construct();
	}
}

$registradas = array('Tabla1Admin','Tabla2Admin');
