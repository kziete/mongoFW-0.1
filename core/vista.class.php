<?php 

class Vista{
	protected $m;
	public function __construct(){
		$this->m = new Mustacho();
		$this->m->templateDir .= 'public/templates/';
	}
	public function armar($template,$hash){
		$hash['content'] = $this->m->render($template,$hash);
		echo $this->m->render('contenedor.html',$hash);
	}
	public function mostrar($template,$hash){
		echo $this->m->render($template,$hash);
	}
}
