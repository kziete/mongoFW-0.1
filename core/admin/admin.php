<?php 
require(BASE_DIR . 'admin.php');

class Admin extends AdminVista{
	public function __construct(){
		parent::__construct();
	}
	public function get(){
		global $registradas;

		$tmp = array();
		foreach ($registradas as $k => $v) {
			$obj = new $v;

			if($obj->categoria)
				$tmp[$obj->categoria][] = $obj;
			else
				$tmp['Sin Categoría'][] = $obj;

		}
		$disp = array();
		foreach ($tmp as $k => $v) {
			$disp[] = array(
				'categoria' => $k,
				'lista' => $v
			);
		}

		$hash = array(
			'disponibles' => $disp
		);
		$this->armar('lista.html',$hash);
	}
}
class AdminLista extends AdminVista{
	public function __construct(){
		parent::__construct();
	}
	public function get($modelo){
		$a = new $modelo();
		$hash = array(
			'modelo' => $modelo,
			'content' => $a->getGrid(),
			'bloqueado' => $a->bloqueado,
			'nombre' => $a->nombre
		);
		$this->armar('cascara_grid.html', $hash);
	}
}

class AdminForm extends AdminVista{
	public function __construct(){
		parent::__construct();
	}
	public function get($modelo,$index){
		$a = new $modelo();
		if($_REQUEST['borrar']){
			$a->model->delete($index);
			header("Location: /admin/" . $modelo);
		}
		$hash = array(
			'modelo' => $modelo,			
			'content' => $a->getForm($index),
			'nombre' => $a->nombre
		);
		//$this->mostrar('contenedor.html',$hash);
		$this->armar('cascara_form.html', $hash);
	}

	public function post($modelo,$index){
		$a = new $modelo(); 
		if(!$a->save($index)){
			$hash = array(
				'modelo' => $modelo,			
				'content' => $a->getForm($index,true)
			);
			$this->armar('cascara_form.html', $hash);
		}		
	}
}



class AdminVista extends Vista{
	public function __construct(){

		//print_r($_SERVER);
		session_start();
		//unset($_SESSION['login']);
		parent::__construct();
		$this->m->templateDir = BASE_DIR . 'core/admin/templates/';

		$this->revisarPost();

		if($_GET['logout'])
			$this->logOut();

		if(!$_SESSION['login'])
			$this->pedirLogin();
	}

	public function pedirLogin($error = false){
		$this->mostrar('login.html',array('error' => $error));
		exit();
	}
	public function revisarPost(){
		if($_POST['login']){
			$db = new DbHelper();
			$user = $db->quote($_POST['user']);
			$pass = $db->quote($_POST['pass']);
			$query = $db->sql("select * from mongo_user where user='" . $user . "' and pass='" . $pass . "'");
			$data = $db->fetch($query);

			if(!empty($data)){
				$_SESSION['login'] = $data[0];
				header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}else{
				$this->pedirLogin("Usuario o Contraseña incorrecta :(");
			}
		}
	}
	public function logOut(){
		unset($_SESSION['login']);
		header("Location: http://" . $_SERVER['HTTP_HOST'] . '/admin');
		exit();
	}
}