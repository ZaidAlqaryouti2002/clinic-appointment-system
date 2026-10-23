<?php

 declare(strict_types=1);

 class Patient {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function register($fname, $lname, $email, $password, $phone){
        $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

        $query = "INSERT INTO patients (first_name, last_name, email, password, phone)
        VALUES (? , ? , ? ,? ,?)";

        $stmt = $this->db->prepare($query);
        

        return $stmt->execute([$fname, $lname, $email, $hashedPassword, $phone]);
      
    }

    public function login($email, $password){
        $query = "SELECT * FROM patients WHERE email = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);

        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($patient && password_verify($password, $patient["password"])) {
            return $patient;
        }

        return false;
    }

    public function getPatientById($patientID){
        $query = "SELECT patient_id, first_name, last_name, email, phone, created_at
                  FROM patients
                  WHERE patient_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$patientID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllPatients()
{
    $query = "SELECT patient_id, first_name, last_name, email, phone, created_at
              FROM patients";

    $stmt = $this->db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 }

 ?>