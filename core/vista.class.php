<?php 

class Vista{
	protected $m;
	public function __construct(){
		$this->m = new Mustacho();
		$this->m->templateDir .= 'templates/';
	}
	public function armar($template,$hash){		
		$hash['content'] = $this->m->render($template,$hash);
		echo $this->m->render('contenedor.html',$hash);
	}
	public function mostrar($template,$hash){		
		echo $this->m->render($template,$hash);
	}
	public function getModulo($template,$hash){		
		return $this->m->render($template,$hash);		
	}
	public function json($data){
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
