<?php
declare (strict_types = 1);
class Appointment{

private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

public function bookAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $reason)
{
    $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason)
    VALUES (?,?,?,?,?)";

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
}
?>
