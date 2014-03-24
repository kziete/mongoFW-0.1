<?php 
class Index extends Vista{
    function get() {
    	$hash = array(
    		'titulo' => 'Título'
    	);
    	#$tabla1 = new Tabla1();
    	#$data = $tabla1->getRows();
    	//print_r($data);
        $this->armar('home.html',$hash);
    }
}

class Probando extends Vista{
	function get(){
		$this->json(array(
			'titulo' => 'asd',
			'lista' => array(1,2,3)
		));
	}
}

class Error404{
	function get(){
		echo "404";
	}
}
