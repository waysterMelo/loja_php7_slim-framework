<?php
namespace HCode\Model;

use HCode\DB\Sql;
use HCode\Model;

class Cart extends Model {

    const SESSION = "Cart";

    /*
   * retornar a sessao pelo id do carrinho
   */
    public static function getFromSession()
    {
        $cart = new Cart();

        if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0)
        {
            $cart->get((int)$_SESSION[Cart::SESSION]['idcart']);

        }else{

            $cart->getSessionId();

            if (!(int)$cart->getidcart() > 0){
                $data = [
                    'dessessionid'=>session_id()
                ];

                if (User::checkLogin(false)){
                    $user = User::getFromSession();
                    $data['iduser'] = $user->getiduser();
                }
                $cart->setData($data);
                $cart->save();
                $cart->setToSession();
            }
        }
        return $cart;
    }

    /*
     * pega o id do carrinho
     */
    public function get($idcart)
    {
        $sql = new Sql();
        $resultGetId = $sql->select("select * from tb_carts where idcart = :idcart", [
            ':idcart'=>$idcart]);

        if (count($resultGetId) > 0){
            $this->setData($resultGetId[0]);
        }
    }


    /*
     * inseri sessao no carrinho e o id do user
     *  */
    public function save()
    {
        $sql = new Sql();
        $results = $sql->select("CALL sp_carts_save(:idcart,:dessessionid, :iduser,
         :deszipcode, :vlfreight, :nrdays)", [
            ':idcart'=>$this->getidcart(),
            ':dessessionid'=>$this->getdessessionid(),
            ':iduser'=>$this->getiduser(),
            ':deszipcode'=>$this->getdeszipcode(),
            ':vlfreight'=>$this->getvlfreight(),
            ':nrdays'=>$this->getnrdays()
        ]);
        $this->setData($results[0]);
    }


    /*
     * retornar a sessao pelo dessessionid na tabela carrinhos
     * usa o metodo session_id() do php que retorna o id da session[]
    */
    public function getSessionId()
    {
        $sql = new Sql();
        $resultGetSessionId = $sql->select("select * from tb_carts where dessessionid = :dessessionid", [
            ':dessessionid'=>session_id()
        ]);

        if (count($resultGetSessionId) > 0){
            $this->setData($resultGetSessionId[0]);
        }
    }

        /*
         * colocar os dados do carrinho na sessao
         *
         */
    public function setToSession()
    {
        $_SESSION[Cart::SESSION] = $this->getValues();
    }

    /*
     * adiciona produtos no carrinho
     * */
    public function addToCart(Product $product)
    {
        $sql = new Sql();
        $sql->query("insert into tb_cartsproducts (idcart, idproduct) 
        values (:idcart, :idproduct)",
            [':idcart'=>$this->getidcart(),
            ':idproduct'=>$product->getid()
        ]);
    }

    public function removeProduct(Product $product, $all = false)
    {
        $sql = new Sql();

     if ($all){
         $sql->query("update tb_cartsproducts set dtremoved = NOW() where idcart = 
:idcart and idproduct = :idproduct and dtremoved is null", [
    ':idcart'=>$this->getidcart(), ':idproduct'=>$product->getid()
         ]);
     }else{
         $sql->query("update tb_cartsproducts set dtremoved = NOW() where idcart = 
:idcart and idproduct = :idproduct and dtremoved is null limit 1", [
             ':idcart'=>$this->getidcart(), ':idproduct'=>$product->getid()
         ]);
     }
    }

    public function getProdutos()
    {
        $sql = new Sql();
        $row = $sql->select("select b.id, b.descricao, b.price, b.width, b.height,
        b.length, b.weight, b.url, count(*) as qtd, SUM(b.price) as Total from tb_cartsproducts a 
        inner join tb_products b on a.idproduct = b.id 
        where a.idcart = :idcart and a.dtremoved is null 
        group by b.id, b.descricao, b.price, b.width, b.height,
        b.length, b.weight, b.url order by b.descricao", [
            'idcart'=>$this->getidcart()
        ]);
        return Product::checkList($row);
    }

        public function getProdutosTotal()
        {
            $sql = new Sql();
            $rs = $sql->select("select sum(price) as preco , sum(width) as tamanho, 
            sum(height) as altura, sum(length) as comprimento, sum(weight) as peso, count(*)
            as qtd from tb_products a 
            inner join tb_cartsproducts b on a.id = b.idproduct where b.idproduct = :idcart
            and dtremoved is null", [
                ':idcart'=>$this->getidcart()
            ]);
            if (count(rs) > 0){
                return $rs[0];
            }else{
                return [];
            }
        }

        public function setFreight($zipcode)
        {
            $newZipCode = str_replace('-', '', $zipcode);
            $total = $this->getProdutosTotal();

            if ($total['qtd'] > 0){
                simplexml_load_file("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?");

                $qs = http_build_query([
                    'nCdEmpresa'=> '',
                    'sDsSenha'=> '',
                    'nCdServico'=> '40010',
                    'sCepOrigem'=> '35720-000',
                    'sCepDestino'=> $newZipCode,
                    'nVlPeso'=>  2,
                    'nVlAltura'=> 1,
                    'nVlLargura'=> 12,
                    'nVlDiametro'=> 0,

                ]);
            }
        }

}