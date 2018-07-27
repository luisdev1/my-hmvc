<?php

/*
 * Classe DB()
 * Classe de conexão. Padrão SingleTon.
 * Retorna um objeto PDO pelo método estático getConn();
 * @author Luciano Charles <souzacomprog@gmail.com>
 */

class DB
{

    private static $conn = null;

    /**
     * Retorna objeto PDO
     * @return null|PDO
     */
    private static function connect()
    {
        try {

            if (self::$conn == null) {
                global $db;
                global $config;

                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];

                self::$conn = new PDO("mysql:host=" . $config['db_host'] . "; dbname=" . $config['db_name'], $config['db_user'], $config['db_pass'], $options);

                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            throw new PDOException("Reveja suas credênciais de conexão ao banco de dados!<br />Contate o administrador!");
        }
        return self::$conn;
    }

    /**
     * Executa método connect()
     * @return null|PDO
     */
    public static function getConn()
    {
        return static::connect();
    }

    /**
     * Construtor do tipo privado previne que uma nova instância da
     * Classe seja criada através do operador `new` de fora dessa classe.
     */
    private function __construct()
    {

    }

    /**
     * Método clone do tipo privado previne a clonagem dessa instância
     * da classe
     *
     * @return void
     */
    private function __clone()
    {

    }

    /**
     * Método unserialize do tipo privado  para reestabelecer qualquer
     * conexão com banco de dados que podem ter sido perdidas
     * durante a serialização,
     * e realizar outras tarefas de reinicialização
     *
     * @return void
     */
    private function __wakeup()
    {
        static::getConn();
    }

}
