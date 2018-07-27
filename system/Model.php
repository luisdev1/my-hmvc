<?php

/**
 * Classe que fornece as funções básicas aos modelos do sitema
 * @author Luis Eduardo <luisdesenvolvedor@gmail.com>
 */
class Model
{

    //Objeto DB
    protected $db;
    //Tabela relacionada ao model
    protected $table;
    //Quantidade de linhas no retorno (afetadas)
    private $row = 0;
    //Resultado QUERY
    private $result = array();

    /**
     * Inicializa o objeto de DB a partir da conexão global
     */
    public function __construct()
    {
        $this->db = DB::getConn();
    }

    /**
     * Retorna a quantidade de linhas afetadas pela QUERY
     * @return int Quantidade de linhas
     */
    public function getRowCount()
    {
        return $this->row;
    }

    /**
     * Retorna o resultado da QUERY
     * @return array Resultado da QUERY
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Faz a inserção de novos dados
     * @param  array $columns Colunas que irá receber os valores ['coluna1', 'coluna2']
     * @return boolean TRUE ou FALSE
     */
    public function insert(array $columns)
    {
        if (!empty($this->table) && (is_array($columns)) && count($columns) > 0) {
            $data = array();
            foreach (array_keys($columns) as $value) {
                $data[] = ":" . ($value) . "";
            }
            $sql = "INSERT INTO " . $this->table . "(" . implode(', ', array_keys($columns)) . ") VALUES (" . implode(', ', $data) . ")";

            $sql = $this->db->prepare($sql);

            for ($i = 0; $i < count($data); $i++) {
                $sql->bindValue($data[$i], trim(addslashes(array_values($columns)[$i])));
            }

            return $sql->execute();
        }
    }

    /**
     * Atualiza dados
     * @param  array $columns     Colunas a serem alteradas
     * @param  array  $where      Cláusula WHERE ['id' => 1, 'name' => 'test']
     * @param  string $where_cond Condição da cláusula WHERE
     * @return boolean TRUE ou FALSE
     */
    public function update(array $columns, array $where = array(), string $where_cond = 'AND')
    {

        if (!empty($this->table) && count($columns) > 0) {
            $update = array();
            foreach ($columns as $key => $value) {
                $update[] = $key . ' = ' . ':' . $key;
            }

            $sql = "UPDATE " . $this->table . " SET " . implode(', ', $update);

            if (count($where) > 0) {
                $data = array();
                foreach ($where as $key => $value) {
                    $data[] = $key . ' = ' . ":" . $key;
                }
                $sql .= " WHERE " . implode(' ' . $where_cond . ' ', $data);
            }

            $sql = $this->db->prepare($sql);

            for ($i = 0; $i < count($update); $i++) {
                $sql->bindValue(':' . array_keys($columns)[$i], trim(addslashes(array_values($columns)[$i])));
            }

            for ($j = 0; $j < count($data); $j++) {
                $sql->bindValue(':' . array_keys($where)[$j], trim(addslashes(array_values($where)[$j])));
            }

            return $sql->execute();
        }
    }

    /**
     * Seleciona dados
     * @param  mixed $columns     Colunas a serem selecionadas
     * @param  array  $where      Cláusula WHERE ['id' => 1, 'name' => 'test']
     * @param  string $where_cond Condição da cláusula WHERE
     */
    public function select($columns = '*', array $where = array(), string $where_cond = 'AND')
    {

        if (!empty($this->table)) {
            if (is_array($columns) && count($columns) > 0) {
                $columns = implode(", ", $columns);
            } else {
                $columns = '*';
            }

            $sql = "SELECT " . ($columns) . " FROM " . $this->table;

            $data = array();

            if (count($where) > 0) {
                foreach ($where as $key => $value) {
                    $data[] = $key . ' = ' . ":" . $key;
                }
                $sql .= " WHERE " . implode(' ' . $where_cond . ' ', $data);
            }

            $sql = $this->db->prepare($sql);

            for ($j = 0; $j < count($data); $j++) {
                $sql->bindValue(':' . array_keys($where)[$j], trim(addslashes(array_values($where)[$j])));
            }

            $sql->execute();

            $this->row = $sql->rowCount();

            $this->result = $sql->fetchAll();
        }
    }


}
