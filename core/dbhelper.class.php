<?php 

require_once BASE_DIR . 'core/libs/adodb5/adodb.inc.php';

class DbHelper {

	private $ado;

	public function __construct(){  

		$this->ado = NewADOConnection('mysql');
		$this->ado->Connect(HOST, USER, PASSWORD, DATABASE);
		$this->ado->execute("SET NAMES utf8");
	}

	public function sql($sql){
		return $this->ado->Execute($sql);
	}

	public function fetch($query){
		$array = array();
		while ($row = $query->FetchRow()){
			$array[] = $row;
		}
		return $array;
	}

	public function insert($table, $array){
		$keys = array();
		$values = array();

		foreach ($array as $k => $v) {
			$keys[] = $k;
			$values[] = $v;
		}
		$sql = "INSERT INTO $table (" . join(',', $keys) . ") values ('" . join("','", $values) . "')";
		
		return $this->ado->Execute($sql);
	}

	public function update($table, $array, $condition=false){
		$set = '';
		foreach ($array as $k => $v) {
			if($set)
				$set .= ',';
			$set .= $k . "='" . $v . "'";
		}
		$sql = "UPDATE $table SET $set WHERE $condition";
		if($condition)
			return $this->ado->Execute($sql);
		else
			echo 'error - por fa, inplentenme un error log';
	}

	public function sqlPaginado($sql,$pagina_actual,$porpagina,$rango=3){


		$query = $this->ado->Execute($sql);
		$total = count($this->fetch($query));
		$paginas = ceil($total/$porpagina);

		$offset = ($pagina_actual-1)*$porpagina;
		$query = $this->ado->SelectLimit($sql,$porpagina,$offset);

		$return = array();		
		$return['cont'] = $this->fetch($query);
		if($paginas > 1){
			$tmp = array();
			for ($i=1; $i <= $paginas; $i++) {

				if(abs($pagina_actual - $i) > $rango)
					continue;

				$tmp[$i-1]['numero'] = $i;
				if($i == $pagina_actual)				
					$tmp[$i-1]['actual'] = true;
			}
			$return['nav'] = $tmp;
		}


		$tmp = array();
		foreach ($_GET as $k => $v) {
			if($v && $k != 'p')
				if(is_array($v))
					foreach ($v as $kk => $vv) {
						$tmp[] = $k . '[' .$kk . ']=' . $vv;
					}
				else
					$tmp[] = $k . '=' . $v;
		}
		$return['url'] = join('&',$tmp);
		return $return;
	}

	public function quote($string){
		return str_replace("'", "''", $string);
		#return $this->ado->qstr($string);
	}
}