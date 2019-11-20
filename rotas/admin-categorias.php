<?php


use HCode\DB\Sql;
use HCode\Model\Category;
use HCode\Model\Product;
use HCode\Model\User;
use HCode\PageAdmin;


$app->get('/admin/categories', function (){
    User::verifyLogin();
    $categories = Category::listAll();
    $page = new PageAdmin();
    $page->setTpl("categories", array(
        "categories" => $categories,
    ));
});

$app->get('/admin/categories/create', function (){
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("categories-create");
});

$app->post('/admin/categories/create', function (){
    User::verifyLogin();
    $categoria = new Category();
    $categoria->setData($_POST);
    $categoria->save();
    header("Location: /admin/categories");
    exit;
});

$app->get('/admin/categories/:idcategory/delete',function ($idcategory){
    User::verifyLogin();
    $category = new Category();
    $category->get((int)$idcategory);
    $category->delete();
    header("Location: /admin/categories");
    exit;
});

$app->get("/admin/categories/:idcategory", function($idcategory){
    User::verifyLogin();
    $category = new Category();
    $category->get((int)$idcategory);
    $page = new PageAdmin();
    $page->setTpl("categories-products",[
        "category"=>$category->getValues(),
        "productsRelated"=>$category->getProducts(),
        "productsNotRelated"=>$category->getProducts(false)
    ]);
});

$app->get('/admin/categories/:idcategory/products/:id/add', function ($idcategory,$id){
        User::verifyLogin();
        $cate = new Category();
        $cate->get((int)$idcategory);
        $pro = new Product();
        $pro->get((int)$id);
        $cate->addProduct($pro);
        header("Location: /admin/categories/".$idcategory);
        exit;
});

$app->get('/admin/categories/:idcategory/products/:id/remove', function ($idcategory,$id){
    User::verifyLogin();
    $cate = new Category();
    $cate->get((int)$idcategory);
    $pro = new Product();
    $pro->get((int)$id);
    $cate->removeProduct($pro);
    header("Location: /admin/categories/".$idcategory);
    exit;
});

