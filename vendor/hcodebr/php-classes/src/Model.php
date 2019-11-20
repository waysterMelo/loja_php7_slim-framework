<?php
namespace HCode;

class Model{
    private $values = [];

    //ver qd um metodo e chamado
    public function __call($name, $args){

        $method = substr($name,0,3);
        $fieldname  = substr($name,3, strlen($name));

        switch ($method){
            case 'get': return (isset($this->values[$fieldname])) ? $this->values[$fieldname] : null;
            break;
            case 'set': return $this->values[$fieldname] = $args[0];
            break;
        }
    }

    public function setData($data = array()){
        foreach ($data as $key => $value){
            $this->{"set".$key}($value);
        }
    }

    public function getValues(){
        return $this->values;
    }
}