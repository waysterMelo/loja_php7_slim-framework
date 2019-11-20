<?php

use HCode\Model\Cart;
use HCode\Model\Category;
use HCode\Model\Product;
use HCode\Model\User;
use HCode\Page;

// ROTA INICIAL
$app->get('/', function (){
    $pro = Product::listAll();
    $page = new Page();
    $page->setTpl("index", [
        "products"=>Product::checkList($pro)
    ]);
});
// FIN DA ROTA INICIAL

$app->get('/category/:idcategory',function ($idcategory){

    $page =  (isset($_GET['page'])) ? (int)$_GET['page'] : 1 ;

    $categories = new Category();

    $categories->get((int)$idcategory);

    $pagination = $categories->getprodutosPorPagina($page);

    $pages = [];
    for ($i = 1; $i <= $pagination['pages']; $i++){
        array_push($pages, [
           "link"=>'/category/'.$categories->getidcategory().'?page='.$i,
            "page"=>$i
        ]);
    }

    $page = new Page();

    $page->setTpl("category", array(
        "category"=>$categories->getValues(),
        "products"=>$pagination['data'],
        "pages"=>$pages,

    ));
});

$app->get('/products/:url', function ($url){
    $pro = new Product();
    $pro->getfromUrl($url);
    $page = new Page();
    $page->setTpl("product-detail", [
        'product'=>$pro->getValues(),
        'categories'=>$pro->getCategories()
    ]);
});

$app->get('/carrinho', function (){
    $cart = Cart::getFromSession();
    $page = new Page();
    $page->setTpl("cart", [
        'cart'=>$cart->getValues(),
        'products'=>$cart->getProdutos()
    ]);
});

$app->get('/carrinho/:id/add', function ($id){
    $pro = new Product();
    $pro->get((int)$id);
    $cart = Cart::getFromSession();
    $qtd = isset($_GET['qtd']) ? (int)$_GET['qtd'] : 1;
    for ($i = 0; $i < $qtd; $i++){
        $cart->addToCart($pro);
    }
    header("Location: /carrinho");
    exit();
});


$app->get('/carrinho/:id/remove', function ($idproduct){
    $pro = new Product();
    $pro->get((int)$idproduct);
    $cart = Cart::getFromSession();
    $cart->removeProduct($pro, true);
    header("Location: /carrinho");
    exit();
});

$app->get('/carrinho/:id/minus', function ($idproduct){
    $pro = new Product();
    $pro->get((int)$idproduct);
    $cart = Cart::getFromSession();
    $cart->removeProduct($pro);
    header("Location: /carrinho");
    exit();
});

$app->post('/cart/freight', function (){
   $cart = Cart::getFromSession();
   $cart->setFreight($_POST['zipcode']);
});

