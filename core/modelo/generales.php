<?php 

class Modelos{
	public static function id($hash=null){
		return new IdModel($hash);
	}
	public static function text($hash=null){
		return new TextModel($hash);
	}
	public static function file($hash=null){
		return new FileModel($hash);
	}
}


class AdminPadre{
	protected $adminName;
	protected $mustacho;
	protected $model;
	
	public function __construct(){		
		global $mustacho;
		$this->mustacho = $mustacho;
		$this->adminName = get_class($this);
		$this->model = new $this->modelName;
	}
	public function getForm($index){
		if($_POST['aceptar']){
			unset($_POST['aceptar']);
			if($index != -1)
				$_POST['id'] = $index;

			$grabar = $this->prepararDatos($_POST);
			$errores = $this->model->saveData($grabar);
			if(!$errores)
				$this->saveOk();
		}
		if($index != -1){
			$data =  $this->model->getById($index);
			if(!$data)
				echo 'Sacame de Aca';
		}
		
		
		$camposHtml = array();
		foreach ($this->model as $k => $v) {
			$camposHtml[] = array(
				'nombre' => $k,
				'input' => $v->getInput($k,$data[$k])
			);
		}
		/*foreach ($this->campos as $campo) {
			if($this->model->{$campo}->getInput()){
				$camposHtml[] = array(
					'nombre' => $campo,
					'input' => $this->model->{$campo}->getInput($campo,$data[$campo])
				);
			}				
		}*/
		$output = array(
			'campos' => $camposHtml
		);
		return $this->mustacho->render('genericos/form.html',$output);
	}

	public function getGrid(){
		$data = $this->model->getRows();
		$ordenado = array();
		foreach ($data as $k => $fila) {
			$ordenado[$k]['edit'] = '/admin/autoadmin.php?modelo=' . $this->adminName . '&index=' . $fila['id'];
			foreach ($this->mostrar as $campo) {
				$ordenado[$k]['outputs'][] = $this->model->{$campo}->getOutput($fila[$campo]);
			}
		}

		$output = array(
			'cabecera' => $this->mostrar,
			'datos' => $ordenado
		);
		return $this->mustacho->render('genericos/grid.html',$output);
	}
	
	public function saveOk(){
		header("Location: /admin/autoadmin.php?modelo=" . $this->adminName);
	}

	public function prepararDatos($post){
		$listo = array();
		foreach ($this->model as $k => $modelo) {
			$listo[$k] = $modelo->prepararDato($k,$post[$k]);
		}
		print_r($listo);
		exit();
		return $listo;
	}
}


class ModeloPadre{
	protected $table;
	protected $db;
	public function __construct(){
		global $db;
		$this->db = $db;
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

		$this->db->sql($sql);
		#si hay alguno problema se agregan mensajes al array de retorno
		return $mensajes;
	}
	public function getRows(){
		$sql = "select * from " . $this->table;
		$query = $this->db->sql($sql);

		return $this->db->fetch($query);
	}
	public function getById($index){
		$sql = "select * from " . $this->table  . " where id=" . $index;
		$query = $this->db->sql($sql);
		$data = $this->db->fetch($query);
		return $data[0];
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
			$update[] = ($k . '=' . self::quote($v));
		}
		return "update $table set " . join(',',$update) . " where $where";
	}
	public static function quote($string){
		return "'" . str_replace("'","''",$string) . "'"; 
	}
}