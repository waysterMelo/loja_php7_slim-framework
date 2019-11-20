<?php

use HCode\Model\Product;
use HCode\Model\User;
use HCode\PageAdmin;

$app->get('/admin/products',function (){
    User::verifyLogin();
    $products = Product::listAll();
    $page = new PageAdmin();
    $page->setTpl("produtos",array(
        "produtos" => $products
    ));
});

$app->post('/admin/produtos/novo', function(){
    User::verifyLogin();
    $produto = new Product();
    $produto->setData($_POST);
    $produto->saveProduct();
    header("Location: /admin/products");
    exit;
});

$app->get('/admin/produtos/novo',function (){
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("produtos-create");
});

$app->get('/admin/produtos/:id', function ($id){
    User::verifyLogin();
    $pro = new Product();
    $pro->get((int)$id);
    $page = new PageAdmin();
    $page->setTpl('produtos-update',[
        "produtos"=>$pro->getValues()]);
});

$app->post('/admin/produtos/:id', function ($id){
    User::verifyLogin();
    $pro = new Product();
    $pro->get((int)$id);
    $pro->setData($_POST);
    $pro->saveProduct();
    $pro->setPhoto($_FILES["img"]);
    header("Location: /admin/products");
    exit;
});

$app->get('/admin/produtos/:id/delete', function ($id){
    User::verifyLogin();
    $pro = new Product();
    $pro->get((int)$id);
    $pro->delete();
    header("Location: /admin/products");
    exit;
});