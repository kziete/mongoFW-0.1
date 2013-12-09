<?php 

class AdminVista extends Vista{
	public function __construct(){

		//print_r($_SERVER);
		session_start();
		//unset($_SESSION['login']);
		parent::__construct();
		$this->m->templateDir = BASE_DIR . 'core/admin/templates/';

		$this->revisarPost();
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
}



class Admin extends AdminVista{
	public function __construct(){
		parent::__construct();
	}
	public function get(){
		global $registradas;
		$hash = array(
			'disponibles' => $registradas
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
			'content' => $a->getGrid()
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
			'content' => $a->getForm($index)
		);
		//$this->mostrar('contenedor.html',$hash);
		$this->armar('cascara_form.html', $hash);
	}
	public function post($modelo,$index){
		$a = new $modelo(); 
		$a->save($index);
	}
}
