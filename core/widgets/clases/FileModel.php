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
		return '<a href="/archivos/' . $value . '" target="_blank">' . $value . '<a/>';
	}
	public function prepararDato($name,$value){
		if(!$_FILES[$name]['name'])
			return $_POST['file_' . $name];
		

		$file = $_FILES[$name];
		$nombre = $nombreLimpio = str_replace(' ', '-', $file['name']);
		$path = BASE_DIR . 'public/archivos/' . $nombre;
		while (file_exists($path)) {
			$nombre = substr(md5(time()), 0,4) . '_' . $nombreLimpio;
			$path = BASE_DIR . 'public/archivos/' . $nombre;
		}

		move_uploaded_file($file['tmp_name'], $path);
		return $nombre;
	}
}