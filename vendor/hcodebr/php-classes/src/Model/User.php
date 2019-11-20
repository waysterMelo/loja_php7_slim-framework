<?php
namespace HCode\Model;

use HCode\DB\Sql;
use HCode\Mailer;
use HCode\Model;

class User extends Model{

    const SESSION = "User";
    const SECRET = "waysterenriquecr";

    public static function login($login, $password)
    {
        $sql = new Sql();
        $rs  = $sql->select("select * from tb_users where deslogin = :LOGIN", array(
            "LOGIN" => $login
        ));

        if (count($rs) === 0){
            throw  new \Exception("Senha Invalida", 1);
        }

        $data = $rs[0];

        if($password == $data['deslogin']){

            $user  = new User();
            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();
            return $user;

        }else {
            throw new \Exception("Usuario nao existente");
        }

    }
    public static function verifyLogin($isadmin = true)
    {
        if (User::checkLogin(false)){
            header("Location: /admin/login");
            exit;
        }
}
    public static function logout()
    {
        $_SESSION[User::SESSION] = NULL;
}
    public static function listAll()
    {
        $sql = new Sql();
       return $sql->select("select * from tb_users a inner join tb_persons b using (idperson) order by b.desperson");
    }
    public function save()
    {
        $sql= new Sql();
        $results =  $sql->select("CALL sp_users_save(:desperson,:deslogin,:despassword,:desemail,:nrphone,:inadmin)", array(
           ":desperson" => $this->getdesperson(),
            ":deslogin" => $this->getdeslogin(),
            ":despassword" => $this->getdespassword(),
            ":desemail" => $this->getdesemail(),
            ":nrphone" => $this->getnrphone(),
            ":inadmin" => $this->getinadmin()
        ));
        $this->setData($results[0]);
    }
    
    public function get($iduser)
    {
        $sql = new Sql();
        $results = $sql->select("select * from tb_users a inner join tb_persons b using(idperson)
        where a.iduser = :iduser", array(
            "iduser"=>$iduser
        ));
        $this->setData($results[0]);
    }
    public function update()
    {
        $sql= new Sql();
        $results =  $sql->select("CALL sp_usersupdate_save(:iduser,:desperson,:deslogin,:despassword,:desemail,:nrphone,:inadmin)", array(
            ":iduser"=>$this->getiduser(),
            ":desperson" => $this->getdesperson(),
            ":deslogin" => $this->getdeslogin(),
            ":despassword" => $this->getdespassword(),
            ":desemail" => $this->getdesemail(),
            ":nrphone" => $this->getnrphone(),
            ":inadmin" => $this->getinadmin()
        ));
        $this->setData($results[0]);
    }
    public function delete()
    {
        $sql = new Sql();
        $results = $sql->query("CALL sp_users_delete(:iduser)", array(":iduser"=>$this->getiduser()));

    }
    public static function getForgot($email)
    {
        $sql = new Sql();
        $rs = $sql->select("SELECT * FROM tb_persons a
        inner join tb_users b
        where a.desemail = :email", array(
            ":email"=>$email
        ));
        if (count($rs) === 0){
            throw new \Exception("Nao foi possivel recuperar senha");
        }else {
            $data = $rs[0];
            $rs2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
                ":iduser" => $data['iduser'],
                ":desip" => $_SERVER["REMOTE_ADDR"]
            ));
            if (count($rs2) === 0 ) {
                throw new \Exception("Não foi possivel recuperar a senha");
            }else{
                $datarecovery = $rs2[0];

                $code = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, User::SECRET,$datarecovery['dtrecovery'], MCRYPT_MODE_ECB));

                $link = "http://www.cursophp7.com/admin/forgot/reset?code=$code";

                $mailer = new Mailer($data["desemail"], $data["desperson"], 'Redefinir senha da loja', "forgot", array(
                    "name" => $data['desperson'],
                    "link" => $link
                ));

                $mailer->send();
                return $data;
            }
        }
    }
    public static function getFromSession()
    {
        $user = new User();
        if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0){
           $user->setData($_SESSION[User::SESSION]);
        }
        return $user;
    }

    public static function checkLogin($isadmin = true)
    {
        if (
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]['iduser'] > 0
            ||
            (bool)$_SESSION[User::SESSION]['inadmin'] !== $isadmin
        ){
            return false;

        }else{
            if ($isadmin === true && (bool)$_SESSION[User::SESSION]['inadmin'] === true){
                return true;

            }else if ($isadmin === false){
                return true;

            }else{
                return false;
            }
        }
    }
}
