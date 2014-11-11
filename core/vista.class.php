<?php 

class Vista{
	protected $m;
	protected $db;
	public function __construct(){
		
		global $db;
		$this->db = $db;
		$this->m = new Mustacho();
		$this->m->templateDir .= 'templates/';
	}
	public function armar($template,$hash = array()){		
		$hash['content'] = $this->m->render($template,$hash);
		echo $this->m->render('contenedor.html',$hash);
	}
	public function mostrar($template,$hash = array()){		
		echo $this->m->render($template,$hash);
	}
	public function mostrarSinRender($template){
		echo file_get_contents($this->m->templateDir .$template, FILE_USE_INCLUDE_PATH);
	}

	public function getModulo($template,$hash = array()){		
		return $this->m->render($template,$hash);		
	}
	public function json($data){
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
