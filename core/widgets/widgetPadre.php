<?php 

class WidgetPadre{
	protected $mustacho;
	protected $inputTemplate;
	protected $outputTemplate;
	protected $db;

	public function __construct(){
		global $mustacho;
		$this->mustacho = $mustacho;

		$this->inputTemplate = 'inputs/' . get_class($this) . '.html';
		$this->outputTemplate = 'outputs/' . get_class($this) . '.html';
	}

	public function input($hash){
		return $this->mustacho->render( $this->inputTemplate, $hash);
	}	
	public function getOutput($value){
		return $value;
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
}