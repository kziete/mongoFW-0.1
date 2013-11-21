<?php 

class FileModel extends WidgetPadre{
	
	public function __construct($hash){
		parent::__construct($hash);		
	}
	public function getInput($campo=null,$value=null){
		$hash = array(
			'placeholder' => $campo,
			'name' => $campo,
			'value' => $value
		);	
		return parent::input($hash);
	}
	public function getOutput($value){
		return $value;
	}
	public function prepararDato($name,$value){
		print_r($_FILES);
		return $value;
	}
}