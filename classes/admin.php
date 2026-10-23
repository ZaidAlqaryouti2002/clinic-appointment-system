<?php
declare (strict_types= 1);
class Admin{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function login($email, $passsword){
        $query = "SELECT * FROM admins WHERE email = ?";
    }
}
?>