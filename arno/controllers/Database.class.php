<?php

class Database{
    private $username = null, $password=null, $dsn = null;

    public $database;

    public $errors;

    private static $dbIstance = null;


    private function __construct(){
        
        $this->username = Config::get('database_username');
        $this->password = Config::get('database_password');
        
        $this->dsn = 'mysql:host='.Config::get('database_host').';dbname='.Config::get('database_name');

        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

        try{
            $this->database = new PDO($this->dsn,$this->username, $this->password);
        }catch(PDOException $ex){
            $this->errors = $ex;
        }
        
    }


    public static function connect(){
       
        if(!isset(Database::$dbIstance)){
            Database::$dbIstance = new Database();
        }

        return Database::$dbIstance;
      
    }

    
    private function __clone(){}
    private function __wakeup(){}
}

?>