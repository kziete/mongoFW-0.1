<?php 

class TextModel extends WidgetPadre{
	public $max_length;
	public function __construct($hash){
		parent::__construct($hash);
		$this->max_length = $hash['max_length'] ? $hash['max_length'] : 128;
	}
	public function getInput($campo=null,$value=null){
		$hash = array(
			'placeholder' => $campo,
			'name' => $campo,
			'value' => $value
		);	
		return parent::input($hash);
	}
}