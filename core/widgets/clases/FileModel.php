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
	public function getOutput($fila,$name){
		return '<a href="/archivos/' . $fila[$value] . '" target="_blank">' . $fila[$value] . '<a/>';
	}
	public function prepararDato($name,$value){
		if(!$_FILES[$name]['name'])
			return $_POST['file_' . $name];
		

		$file = $_FILES[$name];
		$nombre = $nombreLimpio = str_replace(' ', '_', $file['name']);

		if($this->hash['folder']){
			if(!is_dir(BASE_DIR . 'public/archivos/' . $this->hash['folder']))
				mkdir(BASE_DIR . 'public/archivos/' . $this->hash['folder'], 0777);			

			$nombre = $nombreLimpio = $this->hash['folder'] . '/' . $nombre ;
		}

		$path = BASE_DIR . 'public/archivos/' . $nombre;
		$info = pathinfo($nombreLimpio);

		$i = 0;
		while (file_exists($path)) {
			$nombre = $info['dirname'] . '/' . $info['filename'] . '(' . ++$i .').' . $info['extension'] ;
			$path = BASE_DIR . 'public/archivos/' . $nombre;
		}


		move_uploaded_file($file['tmp_name'], $path);
		return $nombre;
	}
}