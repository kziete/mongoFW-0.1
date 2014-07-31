<?php 

class ModeloPadre implements IteratorAggregate, ArrayAccess{
	protected $table;
	protected $db;

	protected $data = array();
	protected $filtros = array();
	protected $orden;


	public function __construct(){
		global $db;
		$this->db = $db;
		$this->table = get_class($this);
	}

	#IteratorAggregate
	public function getIterator(){
		if(empty($this->data))
			$this->makeQuery();

		return new MyIterator($this->data);
	}

	#ArrayAccess
	public function offsetGet($offset){
		if(empty($this->data))
			$this->makeQuery();
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}
	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}
	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}




	public function makeQuery(){
		$sql = "select * from " . $this->table;

		if(!empty($this->filtros))
			$sql .= ' where ' . join("=? and ",array_keys($this->filtros)) . "=?";			
		
		if($this->orden)
			$sql .= ' order by ' . $this->orden;


		$query = $this->db->sql($sql,$this->filtros);

		$this->data = $this->db->fetch($query);
	}

	public function saveData($data){
		$mensajes = false;
		if($this->validar($data)){
			if($data['id']){
				$sql = SqlHelper::createUpdate($this->table, $data, "id =" . $data['id']);
			}else{
				$sql = SqlHelper::createInsert($this->table, $data);
			}
			$this->db->sql($sql);
		}else
			$mensajes = "error de válidacion, implementar algo bonito o con mas info";			

		return $mensajes;
	}
	public function getRows(){
		$this->resetData();
		return $this;
	}
	public function filter($filtros=array()){
		$this->resetData();
		$this->filtros = $filtros;
		return $this;
	}

	public function orderBy($orden='id asc'){
		$this->resetData();
		$this->orden = $orden;
		return $this;
	}

	public function rawData(){
		if(empty($this->data))
			$this->makeQuery();
		return $this->data;
	}

	public function resetData(){
		$this->data = array();
	}

	public function getRowsOrderBy($campo='id', $modo='asc'){
		$sql = "select * from $this->table order by $campo $modo";
		$query = $this->db->sql($sql);

		return $this->db->fetch($query);
	}
	public function getRowsPaged($pagina){
		$pagina = $pagina ? $pagina : 1;
		$porpagina = 20;
		$sql = "select * from " . $this->table;
		$filtros = $_GET['filtro'];
		if(!empty($filtros)){

			$lista = array();
			foreach ($this as $k => $v) {
				if(in_array($k, array_keys($filtros)) && $filtros[$k])
					$lista[] = $v->getCondition($k,$filtros[$k]);
			}

			$where = join(' and ', $lista);
			if(!empty($lista))
				$sql .= ' where ' . $where;
		}
		return $this->db->sqlPaginado($sql,$pagina,$porpagina);
	}
	public function getById($index){
		$sql = "select * from " . $this->table  . " where id=" . $index;
		$query = $this->db->sql($sql);
		$data = $this->db->fetch($query);
		return $data[0];
	}
	public function getByFilter($filtros){
		$tmp = array();
		foreach ($filtros as $k => $v) {
			$tmp[] = "$k='$v'";
		}
		$sql = "select * from " . $this->table  . " where " . join(' and ', $tmp);
		$query = $this->db->sql($sql);
		return $this->db->fetch($query);
	}
	public function delete($index){
		$sql = "delete from " . $this->table . " where id=?";
		$query = $this->db->sql($sql,array($index));
	}
	public function validar($data){
		$ok = true;
		foreach ($this as $k => $v) {
			if(isset($data[$k]) && is_object($v) && method_exists($v, 'validar'))
				if(!$v->validar($data[$k]))
					$ok = false;
		}
		return $ok;
	}
	public function getReferencias($data){
		if(empty($data))
			return $data;
		$buscar = array();
		foreach ($this as $k => $v) {
			if(is_object($v))
				//a futuro poner mas Tipos de modelos que necesiten la misma referenciacion
				if(in_array(get_class($v), array('ReferenciaModel'))){
					$buscar[$k] = array(
						'modelo' => $v->model->table,
						'label' => $v->label,
						'aBuscar' => array()
					);
				}
		}
		foreach ($data as $k => $v) {
			foreach ($buscar as $kk => $vv) {
				if(!in_array($v[$k], $buscar[$kk]['aBuscar']))
					$buscar[$kk]['aBuscar'][] = $v[$kk];
			}
		}
		foreach ($buscar as $fk_name => $v) {
			$query = $this->db->sql("select id," . $v['label'] . " from " . $v['modelo'] . " where id in(" . join(',', $v['aBuscar']) . ")");
			$tmp = $this->db->fetch($query);
			foreach ($tmp as $kk => $vv) {
				$lista[$vv['id']] = $vv[$v['label']];
			}

			foreach ($data as $llave => $info) {
				$data[$llave][$fk_name.'_ref'] = $lista[$info[$fk_name]];
			}
			
		}
		return $data;
	}

	public function getCampos(){
		$lista = array();
		foreach($this as $k => $v)
			$lista[$k] = $v;

		print_r($lista);
		return $lista;
	}
}
