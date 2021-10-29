<?php


class Conexion
{
    private $con;

    public function __construct()
    {
    }

    function connect()
    {
        include_once dirname(__FILE__) . '/constantes.php';
        try {
            $this->con = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME .
                ';charset=utf8', DB_USERNAME, DB_PASSWORD);
            return $this->con;
        } catch (PDOException $e) {
            echo 'Error' . $e->getMessage();
            return null;
        }
    }

}