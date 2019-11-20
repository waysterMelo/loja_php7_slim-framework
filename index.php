<?php

use Slim\Slim;

session_start();
require_once("vendor/autoload.php");

$app = new Slim();

$app->config('debug', true);

require_once "rotas/rotas-site.php";
require_once "rotas/admin-routes.php";
require_once "rotas/rota-users.php";
require_once "rotas/produtos-rota.php";
require_once "rotas/admin-categorias.php";
require_once "functions.php";

$app->run();