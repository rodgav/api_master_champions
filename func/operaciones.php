<?php

require_once dirname(__FILE__) . '/conexion.php';

class Operaciones
{
    private $con;
    private $ubicacion;
    private $link;

    public function __construct()
    {
        $db = new Conexion();
        $this->con = $db->connect();
        $this->ubicacion = dirname(__DIR__) . '/images/';
        $this->link = 'http://' . $_SERVER['SERVER_NAME'] . '/api_master_champions/images/';
    }

    private function closeCon()
    {
        $this->con = null;
    }

    private function closeStmt(PDOStatement $stmt)
    {
        $stmt->closeCursor();
        $stmt = null;
    }

    private function printError(array $errorInfo)
    {
        //print_r($errorInfo);
    }

    public function login($email, $password)
    {
        $stmt = $this->con->prepare('call login(?,?)');
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $password, PDO::PARAM_STR);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function createCategory($name)
    {
        $stmt = $this->con->prepare('call createCategory(?)');
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

    public function getCategorys()
    {
        $stmt = $this->con->prepare('call getCategorys()');
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function createProduct($idCategory, $image)
    {
        $anio = utf8_encode(strftime('%Y'));
        $mes = utf8_encode(strftime('%m'));
        $dia = utf8_encode(strftime('%d'));
        $hora = utf8_encode(strftime('%H:%M:%S'));
        $nameImage = $anio . $mes . $dia . $hora;
        $del = array(' ', '/', ':');
        $nameImage = str_replace($del, '', $nameImage);
        $link = $this->link . $nameImage . '.png';
        $ubicacion = $this->ubicacion . $nameImage . '.png';
        $stmt = $this->con->prepare('call createProduct(?,?)');
        $stmt->bindParam(1, $idCategory, PDO::PARAM_INT);
        $stmt->bindParam(2, $link, PDO::PARAM_STR);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            if (file_put_contents($ubicacion, base64_decode($image))) {
                $this->closeStmt($stmt);
                $this->closeCon();
                return true;
            } else {
                //$this->con->rollBack();
                $this->closeStmt($stmt);
                $this->closeCon();
                return false;
            }
        }
        return false;
    }

    public function getProducts10()
    {
        $stmt = $this->con->prepare('call getProducts10()');
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function getProducts($idCategory)
    {
        $stmt = $this->con->prepare('call getProducts(?)');
        $stmt->bindParam(1, $idCategory, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

}