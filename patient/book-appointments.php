<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["patient_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../classes/database.php";
require_once "../classes/appointment.php";

$database = new Database();
$conn = $database->getConnection();

$appointment = new Appointment($conn);

$message = "";

$doctorId = $_GET["doctor_id"] ?? "";

if ($doctorId === "") {
    header("Location: doctors.php");
    exit;
}

$patientId = $_SESSION["patient_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $appointmentDate = $_POST["appointment_date"];
    $appointmentTime = $_POST["appointment_time"];
    $reason = trim($_POST["reason"]);

    if (
        $appointmentDate === "" ||
        $appointmentTime === "" ||
        $reason === ""
    ) {
        $message = "All fields are required";
    } else {

        if (
            $appointment->bookAppointment(
                $patientId,
                $doctorId,
                $appointmentDate,
                $appointmentTime,
                $reason
            )
        ) {
            $message = "Appointment booked successfully";
        } else {
            $message = "Appointment booking failed";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
</head>
<body>

    <h1>Book Appointment</h1>

    <?php if ($message !== ""): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Appointment Date</label>
        <input type="date" name="appointment_date" required>

        <label>Appointment Time</label>
        <input type="time" name="appointment_time" required>

        <label>Reason</label>
        <textarea name="reason" required></textarea>

        <button type="submit">Book Appointment</button>

    </form>

</body>
</html>