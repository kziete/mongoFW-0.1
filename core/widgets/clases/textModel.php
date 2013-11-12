<?php 

class TextModel extends WidgetPadre{
	public $max_length;
	public function __construct($hash){
		parent::__construct($hash);

		$this->max_length = $hash['max_length'] ? $hash['max_length'] : 128;
	}
	public function getInput($campo=null){
		$hash = array(
			'placeholder' => $campo,
			'name' => $campo
		);	
		return parent::input($hash);
	}
	public function getOutput($value){
		return $value;
	}
}