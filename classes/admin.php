<?php

declare(strict_types=1);

class Admin
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM admins WHERE email = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin["password"])) {
            return $admin;
        }

        return false;
    }
}

?>