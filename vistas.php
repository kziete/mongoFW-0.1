<?php 
class Index extends Vista{
    function get() {


    	$hash = array(
    		'titulo' => 'Título'
    	);


    	$tabla1 = new Tabla1();

    	$data = $tabla1->getRows()->orderBy('id desc');#->rawData();#->filter(array('campo1' => 12345));
    	echo "debug\n";
    	
    	foreach($data as $k => $v)    		
    		print_r($v);
    	

    	#print_r($data[1]);
        #$this->armar('home.html',$hash);
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
		header("HTTP/1.0 404 Not Found");
		echo "404";
	}
}
