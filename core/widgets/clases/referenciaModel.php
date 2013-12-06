<?php 

class ReferenciaModel extends WidgetPadre{
	public $max_length;
	public function __construct($hash){
		parent::__construct($hash);
		$this->model = new $hash['model']();
		$this->label = $hash['label'];
		$this->max_length = $hash['max_length'] ? $hash['max_length'] : 128;
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
}