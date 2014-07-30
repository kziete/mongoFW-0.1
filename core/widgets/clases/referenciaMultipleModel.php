<?php 
####
## Esta clase es bastarda, por que deberia usarse una tabla intermedia para las relaciones multiples

/**
* el $hash usa:
* @param model String con el nombre de la tabla a referenciar
* @param label String con el campo de la tabla a usar como Label
*/

class ReferenciaMultipleModel extends WidgetPadre{
	public function __construct($hash){
		parent::__construct($hash);
		$this->model = new $hash['model']();
		$this->label = $hash['label'];
	}
	public function getInput($campo=null,$value){

		$rows = $this->model->getRows();

		$opciones = array();
		$values = explode('|', $value);
		foreach ($rows as $v) {
			$opciones[] = array(
				'id' => $v['id'],
				'label' => $v[$this->label],
				'checked' => in_array($v['id'], $values)
			);
		}

		$hash = array(
			'placeholder' => $campo,
			'name' => $campo,
			'opciones' => $opciones
		);	
		return parent::input($hash);
	}

	public function getOutput($fila,$name){
		$value = $fila[$name];
		if($value){

			$seleccionados = explode('|',$value);
			$rows = $this->model->getRows();
			$tmp = array();
			foreach ($rows as $v) {
				$tmp[$v['id']] = $v[$this->label];
			}

			$virtuales = array();
			foreach ($seleccionados as $v) {
				$virtuales[] = $tmp[$v];
			}
			return join(', ', $virtuales);
		}
	}
	public function getFilter($name,$search){
		$opciones = $this->model->getRows();
		$html .= '<select name="filtro[' . $name .']" onchange="this.form.submit()">' . "\n";
		$html .= '<option value="0"> -- Seleccionar -- </option>' . "\n";
		foreach ($opciones as $v) {
			$html .= '<option value="' . $v['id'] . '" ' . ( ($search == $v['id']) ? 'selected=""' : '' ) . '>' . $v[$this->label] . '</option>' . "\n";
		}
		$html .= '</select>' . "\n";


		return $html;
	}
	public function getCondition($name,$search){
		return MongoMisc::buscarConPipe($name,$search);
	}
	public function prepararDato($name,$value){
		if($value)
			return join('|',$value);
	}
	public function getFieldType(){
		return "text";
	}
}