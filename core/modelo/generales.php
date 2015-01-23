<?php 

class Modelos{
	public static function id($hash=null){
		return new IdModel($hash);
	}
	public static function text($hash=null){
		return new TextModel($hash);
	}
	public static function textarea($hash=null){
		return new TextAreaModel($hash);
	}
	public static function tinymce($hash=null){
		return new RichTextModel($hash);
	}
	public static function file($hash=null){
		return new FileModel($hash);
	}
	public static function multiFile($hash=null){
		return new MultiFileModel($hash);
	}
	public static function grid($hash=null){
		return new GridModel($hash);
	}
	public static function opcion($hash=null){
		return new OpcionModel($hash);
	}
	public static function multiOpcion($hash=null){
		return new MultiOpcionModel($hash);
	}
	public static function referencia($hash=null){
		return new ReferenciaModel($hash);
	}
	public static function referenciaMultiple($hash=null){
		return new ReferenciaMultipleModel($hash);
	}
	public static function fecha($hash=null){
		return new FechaModel($hash);
	}
	public static function fechayhora($hash=null){
		return new FechaHoraModel($hash);
	}
}


class AdminPadre{
	public $adminName;
	protected $mustacho;
	protected $model;
	protected $post = null;
	
	public function __construct(){		
		global $mustacho;
		$this->mustacho = $mustacho;
		$this->adminName = get_class($this);
		$this->model = new $this->modelName;
		$this->nombre = $this->nombre ? $this->nombre : $this->modelName;
	}
	public function getForm($index,$error=false){
		if($index != -1){
			$data =  $this->model->getById($index);
			if(!$data)
				echo 'Sacame de Aca';
		}
		if($this->post != null)
			$data = $this->prepararDatos($this->post);
		
		$camposHtml = array();
		$includes = array();
		
		#$this->model->setIterable(false);

		//print_r(get_object_vars($this->model));

		foreach (get_object_vars($this->model) as $k => $v) {
			$tmp = $v->getIncludes();
			foreach ($tmp as $kk => $vv) {
				$includes[$kk] = $vv;
			}

			$camposHtml[] = array(
				'nombre' => $v->getNombre($k),
				'error' => $v->error ? $v->error : '',
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
			'includes' => $tmp,
			'error' => $error
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
				$ordenado[$k]['outputs'][] = $this->model->{$campo}->getOutput($fila,$campo);
				$filtros[] = $this->model->{$campo}->getFilter($campo,$_GET['filtro'][$campo]);
			}
		}
		$filtros = array();
		$cabecera = array();
		foreach ($this->mostrar as $campo) {
			$filtros[] = $this->model->{$campo}->getFilter($campo,$_GET['filtro'][$campo]);
			$cabecera[] = $this->model->{$campo}->getNombre($campo);
		}
		$output = array(
			'modelo' => $this->adminName,
			'cabecera' => $cabecera,
			'filtros' => $filtros,
			'datos' => $ordenado,
			'nav' => $paged['nav'],
			'nav_flag' => count($paged['nav']),
			'url' => $paged['url'],
			'bloqueado' => $this->bloqueado
		);
		return $this->mustacho->render('genericos/grid.html',$output);
	}
	public function save($index){		
		if($_POST['aceptar']){
			unset($_POST['aceptar']);
			$this->post = $_POST;
			$grabar = $this->prepararDatos($_POST);
			
			if($index != -1)
				$grabar['id'] = $index;

			$errores = $this->model->saveData($grabar);
			if(!$errores)
				$this->saveOk();
			else
				return false;
		}
	}
	public function saveOk(){
		header("Location: /admin/" . $this->adminName);
	}

	public function prepararDatos($post){
		$listo = array();
		foreach (get_object_vars($this->model) as $k => $modelo) {
			$listo[$k] = $modelo->prepararDato($k,$post[$k]);
		}
		/*print_r($listo);
		exit();*/
		return $listo;
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


class MyIterator implements Iterator
{
    private $var = array();
    
    public function __construct($array)
    {
        //if (is_array($array)) {
            $this->var = $array;
        //}

    }

    public function rewind()
    {
        #echo "rewinding\n";
        reset($this->var);
    }

    public function current()
    {
        $var = current($this->var);
        #echo "current: $var\n";
        return $var;
    }

    public function key()
    {
        $var = key($this->var);
        #echo "key: $var\n";
        return $var;
    }

    public function next()
    {
        $var = next($this->var);
        #echo "next: $var\n";
        return $var;
    }

    public function valid()
    {
        $key = key($this->var);
        $var = ($key !== NULL && $key !== FALSE);
        #echo "valid: $var\n";
        return $var;
    }

}
