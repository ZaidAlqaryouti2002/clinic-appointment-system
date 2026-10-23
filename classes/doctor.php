<?php

declare(strict_types=1);

class Doctor
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function getAllDoctors()
    {
        $query = "SELECT * FROM doctors";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDoctorById($doctorId)
    {
        $query = "SELECT * FROM doctors WHERE doctor_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$doctorId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addDoctor($fname, $lname, $specialization, $phone, $email, $image)
    {
        $query = "INSERT INTO doctors
                  (first_name, last_name, specialization, phone, email, image)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $fname,
            $lname,
            $specialization,
            $phone,
            $email,
            $image
        ]);
    }

    public function updateDoctor($doctorId, $fname, $lname, $specialization, $phone, $email, $image)
    {
        $query = "UPDATE doctors
                  SET first_name = ?, last_name = ?, specialization = ?, phone = ?, email = ?, image = ?
                  WHERE doctor_id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $fname,
            $lname,
            $specialization,
            $phone,
            $email,
            $image,
            $doctorId
        ]);
    }

    public function deleteDoctor($doctorId)
    {
        $query = "DELETE FROM doctors WHERE doctor_id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([$doctorId]);
    }
}

?>
