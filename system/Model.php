<?php 

/**
 * Classe que fornece as funções básicas aos modelos do sitema
 * @author Luis Eduardo <luisdesenvolvedor@gmail.com>
 */
class Model {

	//Objeto DB
	protected $db;

	//Tabelas relacionadas ao model, sendo uma primária outra secundária
	protected $table = array();

	//Quantidade de linhas no retorno (afetadas)
	private $row = 0;

	//Resultado QUERY
	private $result = array();	

	/**
	 * Inicializa o objeto de DB a partir da conexão global
	 */
	public function __construct() {
		global $db;
		$this->db = $db;
	}

	/**
	 * Retorna a quantidade de linhas afetadas pela QUERY
	 * @return int Quantidade de linhas
	 */
	public function getRowCount(){
		return $this->row;
	}

	/**
	 * Retorna o resultado da QUERY
	 * @return array Resultado da QUERY
	 */
	public function getResult(){
		return $this->result;
	}

	/**
	 * Faz a inserção de novos dados na tabela primária
	 * @param  array $columns Colunas que irá receber os valores ['coluna1', 'coluna2']
	 * @return boolean TRUE ou FALSE
	 */
	public function insert(array $columns){
		if(!empty($this->table[0]) && (is_array($columns)) && count($columns) > 0){
			$data = array();
			foreach(array_keys($columns) as $value){
				$data[] = ":".($value)."";
			} 
			$sql = "INSERT INTO " .$this->table[0] ."(".implode(', ', array_keys($columns)).") VALUES (".implode(', ', $data).")";

			$sql = $this->db->prepare($sql);

			for($i = 0; $i < count($data); $i++){
				$sql->bindValue($data[$i], trim(addslashes(array_values($columns)[$i])));
			}

			return $sql->execute();
		}
		
	}

	/**
	 * Atualiza dados da tabela primária
	 * @param  array $columns     Colunas a serem alteradas
	 * @param  array  $where      Cláusula WHERE ['id' => 1, 'name' => 'test']
	 * @param  string $where_cond Condição da cláusula WHERE
	 * @return boolean TRUE ou FALSE
	 */
	public function update(array $columns, array $where = array(), string $where_cond = 'AND'){

		if(!empty($this->table[0]) && count($columns) > 0){
			$update = array();
			foreach($columns as $key => $value){
				$update[] = $key . ' = ' . ':'.$key;
			}

			$sql = "UPDATE " .$this->table[0] ." SET ".implode(', ', $update);

			if(count($where) > 0){
				$data = array();
				foreach ($where as $key => $value) {
					$data[] = $key .' = '. ":".$key;
				}				
				$sql .= " WHERE ".implode(' '.$where_cond.' ', $data);
			}

			$sql = $this->db->prepare($sql);

			for($i = 0; $i < count($update); $i++){
				$sql->bindValue(':'.array_keys($columns)[$i], trim(addslashes(array_values($columns)[$i])));
			}			

			for($j = 0; $j < count($data); $j++){
				$sql->bindValue(':'.array_keys($where)[$j], trim(addslashes(array_values($where)[$j])));
			}

			return $sql->execute();
		}

	}


	/**
	 * Seleciona dados da tabela primária
	 * @param  mixed $columns     Colunas a serem selecionadas 
	 * @param  array  $where      Cláusula WHERE ['id' => 1, 'name' => 'test']
	 * @param  string $where_cond Condição da cláusula WHERE
	 */
	public function select($columns = '*', array $where = array(), string $where_cond = 'AND'){

		if(!empty($this->table[0])){
			if(is_array($columns) && count($columns) > 0){
				$columns = implode(", ", $columns);
			} else {
				$columns = '*';
			}

			$sql = "SELECT ".($columns)." FROM ".$this->table[0];

			$data = array();

			if(count($where) > 0){				
				foreach ($where as $key => $value) {
					$data[] = $key .' = '. ":".$key;
				}				
				$sql .= " WHERE ".implode(' '.$where_cond.' ', $data);
			}

			$sql = $this->db->prepare($sql);

			for($j = 0; $j < count($data); $j++){
				$sql->bindValue(':'.array_keys($where)[$j], trim(addslashes(array_values($where)[$j])));
			}

			$sql->execute();
			
			$this->row = $sql->rowCount();

			$this->result = $sql->fetchAll(PDO::FETCH_ASSOC);

		}

	}

	/**
	 * Seleciona dados da relação entre a tablea primária e secundária
	 * @param  array  $on             Cláusula ON
	 * @param  string $columns        Colunas a serem selecionadas 
	 * @param  string $direction_join Tipo de JOIN a ser utilizada [LEFT, RIGHT, INNER]
	 * @param  string $cond           Condição da cláusula ON	 
	 */
	public function selectJoin(array $on = array(), $columns = '*', string $direction_join = 'INNER',  string $cond = 'AND'){
		if(count($this->table) > 0){
			if(is_array($columns) && count($columns) > 0){
				$columns = implode(", ", $columns);
			} else {
				$columns = '*';
			}

			if(is_array($on) && count($on) > 0){
				$data = array();
				foreach ($on as $key => $value) {
					if(strpos($value, ':') == 0) {
						$value = substr($value, 1, mb_strlen($value, 'UTF-8'));
					}
					$data[] = $key . ' = ' .$value;
				}

				$sql = "SELECT ".($columns)." FROM ".$this->table[0] . " ".$direction_join." JOIN ". $this->table[1] ." ON ". implode(" ".$cond." ", $data);
			}

			echo $sql;exit;

			$sql = $this->db->prepare($sql);

			for($j = 0; $j < count($data); $j++){
				if(strpos(array_values($on)[$j], ':') == 0) {				
					$sql->bindValue(':'.array_keys($on)[$j], trim(addslashes(array_values($on)[$j])));
				}
			}

			$sql->execute();
			
			$this->row = $sql->rowCount();

			$this->result = $sql->fetchAll(PDO::FETCH_ASSOC);					

		}

	}

}