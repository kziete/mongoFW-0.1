<?php 

class WidgetPadre{
	protected $hash;
	protected $mustacho;
	protected $inputTemplate;
	protected $outputTemplate;
	protected $db;

	public function __construct($hash){
		$this->hash = $hash;
		global $mustacho;
		$this->mustacho = $mustacho;

		$this->inputTemplate = 'inputs/' . get_class($this) . '.html';
		$this->outputTemplate = 'outputs/' . get_class($this) . '.html';
	}
	public function getNombre($columna){
		return $this->hash['nombre'] ? $this->hash['nombre'] : $columna;
	}
	public function input($hash){
		return $this->mustacho->render( $this->inputTemplate, $hash);
	}	
	public function getOutput($fila,$name){
		return $fila[$name];
	}
	public function validar($value){
		if($this->hash['notnull'] && $value ==''){
			$this->error = "Este campo es obligatorio";
			return false;
		}
		if(method_exists($this, 'validarPropio'))
			if(!$this->validarPropio($value))
				return false;

		if(is_callable($this->hash['validar']))
			if($this->error = $this->hash['validar']($value))
				return false;


		return true;
	}
	public function prepararDato($name,$value){
		return $value;
	}
	public function getIncludes(){
		return array();
	}
	public function getFilter($name,$search){
		return '<input type="text" name="filtro[' . $name . ']" value="' . $search . '">';
	}
	public function getCondition($name,$search){
		return $name . " like '%" . $search . "%'";
	}
	public function getFieldType(){
		return "varchar(256)";
	}
	public function getAlters($name){
		return false;
	}
}