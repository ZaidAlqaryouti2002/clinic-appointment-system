<?php

declare(strict_types=1);

class Appointment
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function bookAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $reason)
    {
        $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $patient_id,
            $doctor_id,
            $appointment_date,
            $appointment_time,
            $reason
        ]);
    }

    public function getPatientAppointments($patient_id)
    {
        $query = "SELECT
                    appointments.appointment_id,
                    appointments.appointment_date,
                    appointments.appointment_time,
                    appointments.status,
                    appointments.reason,
                    doctors.first_name,
                    doctors.last_name,
                    doctors.specialization
                  FROM appointments
                  JOIN doctors
                  ON appointments.doctor_id = doctors.doctor_id
                  WHERE appointments.patient_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$patient_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAppointments()
    {
        $query = "SELECT
                    appointments.appointment_id,
                    appointments.appointment_date,
                    appointments.appointment_time,
                    appointments.status,
                    appointments.reason,
                    patients.first_name AS patient_first_name,
                    patients.last_name AS patient_last_name,
                    doctors.first_name AS doctor_first_name,
                    doctors.last_name AS doctor_last_name,
                    doctors.specialization
                  FROM appointments
                  JOIN patients
                  ON appointments.patient_id = patients.patient_id
                  JOIN doctors
                  ON appointments.doctor_id = doctors.doctor_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($appointmentId, $status)
    {
        $query = "UPDATE appointments
                  SET status = ?
                  WHERE appointment_id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([$status, $appointmentId]);
    }
}

?>
