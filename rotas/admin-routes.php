<?php

use HCode\Model\Category;
use HCode\Model\Product;
use HCode\Model\Produtos;
use HCode\Model\User;
use HCode\Page;
use HCode\PageAdmin;

$app->get('/admin',function (){
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("index");
});

$app->get('/admin/login', function (){
    $page = new PageAdmin(["header" => false, "footer" => false]);
    $page->setTpl("login");
});

$app->post('/admin/login', function (){
    User::login($_POST['login'], $_POST['password']);
    header("Location: /admin");
    exit;
});

$app->get('/admin/logout', function (){
    User::verifyLogin();
    User::logout();
    header('Location: /admin/login');
    exit;
});

$app->get('/admin/forgot', function (){
    $page = new PageAdmin([
        "header"=>false,
        "footer"=>false
    ]);
    $page->setTpl("forgot");
});

$app->post('/admin/forgot', function (){
    $email = $_POST['email'];
    $user =  User::getForgot($email);
    header("Location: /admin/forgot/sent");
    exit;
});

$app->get('/admin/forgot/sent', function (){
    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);
    $page->setTpl("forgot-sent");
});

$app->get('/admin/forgot/reset',function (){
    $page = new PageAdmin([
        "header"=>false,
        "footer" => false
    ]);
    $page->setTpl("forgot-reset");
});

$app->post('/admin/forgot/success', function (){
    $_POST['senha'];
});