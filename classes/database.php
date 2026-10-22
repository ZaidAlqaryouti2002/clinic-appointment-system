<?php

class Database
{
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "clinic_db";
    private $port = "3307";

    private $connection;

    public function __construct()
    {
        try {

            $this->connection = new PDO(
                "mysql:host=" . $this->host .
                ";port=" . $this->port .
                ";dbname=" . $this->dbname .
                ";charset=utf8mb4",
                $this->user,
                $this->password
            );

        } catch (PDOException $e) {

            echo "Connection failed: " . $e->getMessage();

        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

?>