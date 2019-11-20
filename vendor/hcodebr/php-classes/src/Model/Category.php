<?php
namespace HCode\Model;

use HCode\DB\Sql;
use HCode\Model;

class Category extends Model{

    public static function listAll(){
        $sql = new Sql();
        return $sql->select("select * from tb_categories order by descategory");
    }

    public function save(){
        $sql= new Sql();
        $results =  $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
           ":idcategory" => $this->getidcategory(),
            ":descategory" => $this->getdescategory()
        ));
        $this->setData($results[0]);
        Category::updateCategories();
    }

    public function get($idcategory){
        $sql = new Sql();
        $rs = $sql->select("select * from tb_categories where idcategory = :idcategory", [
            ":idcategory"=>$idcategory
        ]);
        $this->setData($rs[0]);
    }

    public function delete(){
        $sql = new Sql();
        $sql->query("delete from tb_categories where idcategory = :idcategory",[
            ":idcategory"=>$this->getidcategory()
        ]);
        Category::updateCategories();
    }

    public static function  updateCategories(){
        $categories = Category::listAll();
        $html = [];
        foreach ($categories as $row){
            array_push($html, '<li><a href="/category/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
        }
        file_put_contents($_SERVER['DOCUMENT_ROOT'] .
            DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR ."categories-menu.html", implode('', $html));
}

    public function getProducts($related = true){
    $sql = new Sql();
    if ($related === true){
       return $sql->select("select * from tb_products where id in(select a.id from 
        tb_products a inner join tb_categoriesproducts b on a.id = b.idproduct where
            b.idcategory = :idcategory)", array(
                ":idcategory"=>$this->getidcategory()));
    }else {
        return $sql->select("select * from tb_products where id NOT IN (select a.id from 
        tb_products a inner join tb_categoriesproducts b on a.id = b.idproduct where
            b.idcategory = :idcategory)", array(
            ":idcategory"=>$this->getidcategory()));
    }
}

    public function addProduct(Product $product){
    $sql = new Sql();
    $sql->query("insert into tb_categoriesproducts
    (idcategory, idproduct) values 
    (:idcategory, :idproduct)", [
    ':idcategory'=>$this->getidcategory(),
    ':idproduct'=>$product->getid()
    ]);
}

    public function removeProduct(Product $product){
        $sql = new Sql();
        $sql->query("delete from  tb_categoriesproducts where idcategory = :idcategory and idproduct = :idproduct", [
            ':idcategory'=>$this->getidcategory(),
            ':idproduct'=>$product->getid()
        ]);
    }

   public function getprodutosPorPagina($page = 1, $itens = 1){
        $start = ($page -1) * $itens;
        $sql= new Sql();
        $rs = $sql->select("select sql_calc_found_rows * from tb_products a
         inner join tb_categoriesproducts b on a.id = b.idproduct
         inner join tb_categories c on c.idcategory = b.idcategory
            where c.idcategory = :idcategory LIMIT $start, $itens", [
            ':idcategory'=>$this->getidcategory()
        ]);
        $total = $sql->select("select found_rows() as total");

            return [
                "data"=>Product::checkList($rs),
                "total"=>(int)$total[0]['total'],
                'pages'=>ceil($total[0]['total'] / $itens)
            ];
    }

}



