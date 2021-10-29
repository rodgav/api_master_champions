<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Content-type: application/json;chartset=UTF-8");
header("Access-Control-Allow-Headers: *");

require_once dirname(__DIR__) . '/func/operaciones.php';

$headers = getallheaders();
$respuesta = array();
if (isset($headers['token'])) {
    $token = $headers['token'];
    require_once dirname(__DIR__) . '/func/constantes.php';
    if ($token == API_KEY) {
        $operations = new Operaciones();
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['accion'])) {
                $accion = $_GET['accion'];
                switch ($accion) {
                    case 'categorys':
                        $data = $operations->getCategorys();
                        if (count($data) > 0) {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Datos encontados';
                            $respuesta['categorys'] = $data;
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'products10':
                        $data = $operations->getProducts10();
                        if (count($data) > 0) {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Datos encontados';
                            $respuesta['products'] = $data;
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'products':
                        if (isset($_GET['idCategory'])) {
                            $data = $operations->getProducts($_GET['idCategory']);
                            if (count($data) > 0) {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Datos encontados';
                                $respuesta['products'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Faltan parametros';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta el parametro de acción';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['accion'])) {
                $accion = $_POST['accion'];
                switch ($accion) {
                    case 'login':
                        if (isset($_POST['email']) && isset($_POST['password'])) {
                            $data = $operations->login($_POST['email'], $_POST['password']);
                            if (count($data) > 0) {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Login correcto';
                                $respuesta['login'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Usuario y/o contraseña incorrecta';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'category':
                        if (isset($_POST['name'])) {
                            if ($operations->createCategory($_POST['name'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Categoria guardada';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'No se guardo la categoria';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'product':
                        if (isset($_POST['idCategory']) && isset($_POST['image'])) {
                            if ($operations->createProduct($_POST['idCategory'], $_POST['image'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Producto guardado';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'No se guardo el producto';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta el parametro de acción';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents('php://input'), $put_vars);

        } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            parse_str(file_get_contents('php://input'), $delete_vars);
        }
    } else {
        $respuesta['error'] = true;
        $respuesta['mensaje'] = 'Token incorrecto';
    }
} else {
    $respuesta['error'] = true;
    $respuesta['mensaje'] = 'Falta el token';
}
echo json_encode($respuesta, JSON_NUMERIC_CHECK);