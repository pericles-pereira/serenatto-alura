<?php

use Periclesphp\SerenattoAlura\Repositorio\ProdutoRepositorio;

require_once 'vendor/autoload.php';
require_once 'src/conexao.php';

$repositorio = new ProdutoRepositorio($pdo);
$repositorio->deletar($_POST['id']);

header('Location: admin.php');