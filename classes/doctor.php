<?php

declare(strict_types=1);

class Doctor
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }


    // Get all doctors
    public function getAllDoctors()
    {
        $query = "SELECT * FROM doctors";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Get one doctor by ID
    public function getDoctorById($doctorId)
    {
        $query = "SELECT * FROM doctors WHERE doctor_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$doctorId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Add doctor
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


    // Update doctor
    public function updateDoctor($doctorId, $fname, $lname, $specialization, $phone, $email, $image)
    {
        $query = "UPDATE doctors
                  SET first_name = ?,
                      last_name = ?,
                      specialization = ?,
                      phone = ?,
                      email = ?,
                      image = ?
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


    // Delete doctor
    public function deleteDoctor($doctorId)
    {
        $query = "DELETE FROM doctors WHERE doctor_id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([$doctorId]);
    }


    // Upload doctor image
    public function uploadImage($file)
    {
        if ($file["error"] !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowedExtensions = ["jpg", "jpeg", "png", "webp"];

        $extension = strtolower(
            pathinfo($file["name"], PATHINFO_EXTENSION)
        );

        if (!in_array($extension, $allowedExtensions)) {
            return false;
        }

        $image = time() . "_" . basename($file["name"]);

        $targetPath = "../uploads/doctors/" . $image;

        if (move_uploaded_file($file["tmp_name"], $targetPath)) {
            return $image;
        }

        return false;
    }


    // Delete doctor image
    public function deleteImage($image)
    {
        if ($image === "") {
            return;
        }

        $imagePath = "../uploads/doctors/" . $image;

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }


    // Count doctors for dashboard
    public function getDoctorsCount()
    {
        $query = "SELECT COUNT(*) AS total FROM doctors";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result["total"];
    }
}

?>