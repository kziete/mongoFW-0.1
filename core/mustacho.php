<?php 
class Mustacho{
	public $templateDir = BASE_DIR;
	public $mustache;

	public function __construct($templateDir = false){
		require(BASE_DIR .'core/mustache/src/Mustache/Autoloader.php');
		Mustache_Autoloader::register();
		$this->mustache = new Mustache_Engine;
		$this->templateDir .= $templateDir;
	}

	public function render($template, $hash){
		return $this->mustache->render(
			file_get_contents($this->templateDir . $template), 
			$hash
		);
	}
}

$mustacho = new Mustacho();