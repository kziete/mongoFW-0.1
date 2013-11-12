<?php 

class WidgetPadre{
	protected $mustacho;
	protected $inputTemplate;
	protected $outputTemplate;

	public function __construct(){
		global $mustacho;
		$this->mustacho = $mustacho;
		$this->inputTemplate = 'inputs/' . get_class($this) . '.html';
		$this->outputTemplate = 'outputs/' . get_class($this) . '.html';
	}

	public function input($hash){
		return $this->mustacho->render( $this->inputTemplate, $hash);
	}
}