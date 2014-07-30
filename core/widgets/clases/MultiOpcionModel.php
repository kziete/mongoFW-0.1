<?php 
/**
* el $hash usa:
* @param opciones mapa con los valores y labels de cada opcion
* @param usarSelect boolean para forzar a que opcionModel se comporte siempre como select
* @param size integer tamaño del select, si es que se usara select
*/

class MultiOpcionModel extends WidgetPadre{
	public $opciones;
	public function __construct($hash){
		parent::__construct($hash);
		$this->opciones = $hash['opciones'];
	}
	public function getInput($campo=null,$value=null){
		$seleccionados = array();
		if($value)
			$seleccionados = explode('|',$value);
		$opcionesMustache = array();

		foreach ($this->opciones as $k => $v) {
			$opcionesMustache[] = array(
				'value' => $k,
				'label' => $v,
				'checked' => (in_array($k, $seleccionados))
			);
		}
		$hash = array(
			'name' => $campo,
			'opciones' => $opcionesMustache,
			'usarSelect' => (count($this->opciones) > 4) || $this->hash['usarSelect'],
			'size' => $this->hash['size'] ? $this->hash['size'] : 5
		);	
		return parent::input($hash);
	}

	public function getOutput($fila,$name){
		$value = $fila[$name];
		if($value){
			$seleccionados = explode('|',$value);
			$virtuales = array();
			foreach ($seleccionados as $v) {
				$virtuales[] = $this->opciones[$v];
			}
			return join(', ', $virtuales);
		}
	}
	
	public function getFilter($name,$search){
		$html .= '<select name="filtro[' . $name .']" onchange="this.form.submit()">' . "\n";
		$html .= '<option value="0"> -- Seleccionar -- </option>' . "\n";
		foreach ($this->opciones as $k => $v) {
			$html .= '<option value="' . $k . '" ' . ( ($search == $k) ? 'selected=""' : '' ) . '>' . $v . '</option>' . "\n";
		}
		$html .= '</select>' . "\n";
		return $html;
	}
	public function getCondition($name,$search){
		return MongoMisc::buscarConPipe($name,$search);
	}

	public function prepararDato($name,$value){
		return join('|',$value);
	}
}