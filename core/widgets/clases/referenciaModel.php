<?php 

/**
* el $hash usa:
* @param model String con el nombre de la tabla a referenciar
* @param label String con el campo de la tabla a usar como Label
*/

class ReferenciaModel extends WidgetPadre{
	public function __construct($hash){
		parent::__construct($hash);
		$this->model = new $hash['model']();
		$this->label = $hash['label'];
	}
	public function getInput($campo=null,$value=null){
		$rows = $this->model->getRows();

		$opciones = array();
		foreach ($rows as $v) {
			$opciones[] = array(
				'id' => $v['id'],
				'label' => $v[$this->label],
				'selected' => $value == $v['id']
			);
		}

		$hash = array(
			'placeholder' => $campo,
			'name' => $campo,
			'opciones' => $opciones
		);	
		return parent::input($hash);
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
		return $name . "='" . $search . "'";
	}
	public function getFieldType(){
		return "bigint";
	}
	public function getAlters($name){
		return "ADD CONSTRAINT FOREIGN KEY fk_"
			. $this->hash['model'] 
			. " (". $name . ") " 
			. " REFERENCES " 
			.  $this->hash['model'] 
			. "(id) on delete cascade on update cascade;";
	}
	public function getOutput($fila,$name){
		return $fila[$name.'_ref'];
	}
}