<?php 

class Modelos{
	public static function id($hash=null){
		return new IdModel($hash);
	}
	public static function text($hash=null){
		return new TextModel($hash);
	}
}


class AdminPadre{

	public function __construct(){		
		global $mustacho;
		$this->mustacho = $mustacho;

		$this->model = new $this->modelName;
	}
	public function getForm(){
		if($_POST['aceptar']){
			unset($_POST['aceptar']);
			$errores = $this->model->saveData($_POST);
			if(!$errores)
				$this->saveOk();
		}
		
		$camposHtml = array();
		foreach ($this->campos as $campo) {
			if($this->model->{$campo}->getInput()){
				$camposHtml[] = array(
					'nombre' => $campo,
					'input' => $this->model->{$campo}->getInput($campo)
				);
			}				
		}
		$output = array(
			'campos' => $camposHtml
		);
		echo $this->mustacho->render('genericos/form.html',$output);
	}

	public function getGrid(){
		$data = $this->model->getRows();
		$ordenado = array();
		foreach ($data as $k => $fila) {
			foreach ($this->campos as $campo) {
				$ordenado[$k][] = $this->model->{$campo}->getOutput($fila[$campo]);
			}
		}

		$output = array(
			'cabecera' => $this->campos,
			'datos' => $ordenado
		);

		echo $this->mustacho->render('genericos/grid.html',$output);
	}
	
	public function saveOk(){
		echo 'saveOk';
	}
}


class ModeloPadre{
	protected $table;
	public function __construct(){
		$this->table = get_class($this);
	}
	public function saveData($data){
		$mensajes = false;
		if($this->validar($data)){
			if($data['id']){
				$sql = SqlHelper::createUpdate($this->table, $data, "id =" . $data['id']);
			}else{
				$sql = SqlHelper::createInsert($this->table, $data);
			}
		}else
			$mensajes[] = "erro de validacion, implementar algo bonito o con mas info";			
		#ejecuto el sql de alguna manera
		echo $sql;
		#si hay alguno problema se agregan mensajes al array de retorno
		return $mensajes;
	}
	public function getRows(){
		//aca se hace el sql
		$sql = "select * from " . $this->table;

		//se retorna una matriz con los datos ()
		return array(
			array(
				'id' => 1,
				'campo1' => 'Juan',
				'campo2' => 'Perez'
			),
			array(
				'id' => 2,
				'campo1' => 'Carlos',
				'campo2' => 'Cruz'
			)
		);
	}
	public function validar($data){
		return true;
	}
}


class SqlHelper{
	public static function createInsert($table,$data){    
		$campos = array();
		$values = array();
		foreach($data as $k => $v){
			$campos[] = $k;
			$values[] = self::quote($v);
		}
		return "insert into $table (" . join(',',$campos) . ") values (" . join(',',$values) . ")";
	}
	public static function createUpdate($table, $data, $where){
		$update = array();
		foreach($data as $k => $v){
			$update[] = ($k . '=' . $this->quote($v));
		}
		return "update $table set " . join(',',$update) . " where $where";
	}
	public static function quote($string){
		return "'" . str_replace("'","''",$string) . "'"; 
	}
}