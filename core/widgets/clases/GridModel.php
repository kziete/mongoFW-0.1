<?php 

class GridModel extends WidgetPadre{
	
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

	public function getIncludes(){
		return array(
			'json2' => '<script src="/admin_assets/js/json2.js"></script>',
			'grid' => '<script src="/admin_assets/js/grid.js"></script>'
		);
	}
	
	public function getFieldType(){
		return "text";
	}
}


  
  
  
