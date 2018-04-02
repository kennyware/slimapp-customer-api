<?php

    class db{
        private $dbhost = "localhost";
        private $dbuser = "root";
        private $dbpass = "Tr33T0p";
        private $dbname = "slimapp";
        
        

        public function connect(){
            $dsn = "mysql:host=$this->dbhost;dbname=$this->dbname";
            $pdo = new PDO($dsn, $this->dbuser, $this->dbpass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
    }

?>