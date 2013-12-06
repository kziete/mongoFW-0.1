<?php 

class Modelos{
	public static function id($hash=null){
		return new IdModel($hash);
	}
	public static function text($hash=null){
		return new TextModel($hash);
	}
	public static function tinymce($hash=null){
		return new RichTextModel($hash);
	}
	public static function file($hash=null){
		return new FileModel($hash);
	}
	public static function referencia($hash=null){
		return new ReferenciaModel($hash);
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
		if($index != -1){
			$data =  $this->model->getById($index);
			if(!$data)
				echo 'Sacame de Aca';
		}
		
		
		$camposHtml = array();
		$includes = array();

		foreach ($this->model as $k => $v) {

			$tmp = $v->getIncludes();
			foreach ($tmp as $kk => $vv) {
				$includes[$kk] = $vv;
			}

			$camposHtml[] = array(
				'nombre' => $k,
				'input' => $v->getInput($k,$data[$k])
			);
		}

		$tmp = array();
		foreach ($includes as $k => $v) {
			$tmp[] = $v;
		}

		$output = array(
			'modelo' => $this->adminName,
			'campos' => $camposHtml,
			'includes' => $tmp
		);
		return $this->mustacho->render('genericos/form.html',$output);
	}

	public function getGrid(){
		$paged = $this->model->getRowsPaged($_GET['p']);
		$data = $this->model->getReferencias($paged['cont']);

		$ordenado = array();
		foreach ($data as $k => $fila) {
			$ordenado[$k]['edit'] = '/admin/' . $this->adminName . '/' . $fila['id'];
			foreach ($this->mostrar as $campo) {
				$ordenado[$k]['outputs'][] = $this->model->{$campo}->getOutput($fila[$campo]);
				$filtros[] = $this->model->{$campo}->getFilter($campo,$_GET['filtro'][$campo]);
			}
		}
		$filtros = array();
		foreach ($this->mostrar as $campo) {
			$filtros[] = $this->model->{$campo}->getFilter($campo,$_GET['filtro'][$campo]);
		}

		$output = array(
			'cabecera' => $this->mostrar,
			'filtros' => $filtros,
			'datos' => $ordenado,
			'nav' => $paged['nav'],
			'url' => $paged['url']
		);
		return $this->mustacho->render('genericos/grid.html',$output);
	}
	public function save($index){		
		if($_POST['aceptar']){
			unset($_POST['aceptar']);
			$grabar = $this->prepararDatos($_POST);

			if($index != -1)
				$grabar['id'] = $index;

			$errores = $this->model->saveData($grabar);
			if(!$errores)
				$this->saveOk();
		}
	}
	public function saveOk(){
		header("Location: /admin/" . $this->adminName);
	}

	public function prepararDatos($post){
		$listo = array();
		foreach ($this->model as $k => $modelo) {
			$listo[$k] = $modelo->prepararDato($k,$post[$k]);
		}
		/*print_r($listo);
		exit();*/
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
			$mensajes[] = "error de válidacion, implementar algo bonito o con mas info";			

		$this->db->sql($sql);
		#si hay alguno problema se agregan mensajes al array de retorno
		return $mensajes;
	}
	public function getRows(){
		$sql = "select * from " . $this->table;
		$query = $this->db->sql($sql);

		return $this->db->fetch($query);
	}
	public function getRowsPaged($pagina){
		$pagina = $pagina ? $pagina : 1;
		$porpagina = 20;
		$sql = "select * from " . $this->table;
		$filtros = $_GET['filtro'];
		if(!empty($filtros)){

			$lista = array();
			foreach ($this as $k => $v) {
				if(in_array($k, array_keys($filtros)) && $filtros[$k])
					$lista[] = $v->getCondition($k,$filtros[$k]);
			}

			$where = join(' and ', $lista);
			if(!empty($lista))
				$sql .= ' where ' . $where;
		}
		return $this->db->sqlPaginado($sql,$pagina,$porpagina);
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
	public function getReferencias($data){
		if(empty($data))
			return $data;
		$buscar = array();
		foreach ($this as $k => $v) {
			if(is_object($v))
				//a futuro poner mas Tipos de modelos que necesiten la misma referenciacion
				if(in_array(get_class($v), array('ReferenciaModel'))){
					$buscar[$k] = array(
						'modelo' => $v->model->table,
						'label' => $v->label,
						'aBuscar' => array()
					);
				}
		}
		foreach ($data as $k => $v) {
			foreach ($buscar as $kk => $vv) {
				if(!in_array($v[$k], $buscar[$kk]['aBuscar']))
					$buscar[$kk]['aBuscar'][] = $v[$kk];
			}
		}
		foreach ($buscar as $fk_name => $v) {
			$query = $this->db->sql("select id," . $v['label'] . " from " . $v['modelo'] . " where id in(" . join(',', $v['aBuscar']) . ")");
			$tmp = $this->db->fetch($query);
			foreach ($tmp as $kk => $vv) {
				$lista[$vv['id']] = $vv[$v['label']];
			}

			foreach ($data as $llave => $info) {
				$data[$llave][$fk_name] = $lista[$info[$fk_name]];
			}
			
		}
		return $data;
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