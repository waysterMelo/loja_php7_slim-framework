<?php

namespace HCode\DB;

class Sql{

    const HOSTNAME = '127.0.0.1';
    const USERNAME = 'root';
    const PASSWORD = 'deus2019';
    const DBNAME = 'db_ecommerce';

    private $con;

    public function __construct()
    {
        $this->con = new \PDO(
            "mysql:dbname=".sql::DBNAME.";host=".sql::HOSTNAME,
                sql::USERNAME,
            sql::PASSWORD
        );
    }

    public function setParams($statement, $parameters=  array() ){
        foreach ($parameters as $key => $value){
            $this->bindParam($statement,$key,$value);
        }
    }

    public function bindParam($statement, $key, $value) {
        $statement->bindParam($key, $value);
    }

    public function query($rowQuery, $param = array()){
        $stmt = $this->con->prepare($rowQuery);
        $this->setParams($stmt, $param);
        $stmt->execute();
    }

    public function select($rowQuery, $params = array()):array
    {
        $stmt = $this->con->prepare($rowQuery);
        $this->setParams($stmt, $params);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

?>