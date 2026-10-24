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
    header("Location: patients-doctors.php");
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
    <title>Book Appointment | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="patient-page page-booking">
<?php $pageArea = "patient"; $basePath = "../"; ?>
<?php require "../components/header.php"; ?>
<main class="container">
<div class="page-heading"><h1>Book Appointment</h1><p>Choose a date and time, and tell us the reason for your visit.</p></div>

    <?php if ($message !== ""): ?>
        <p class="alert" role="alert" data-message="<?= htmlspecialchars($message) ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form class="booking-form form-panel" method="POST" action="">

        <div class="form-field">
    <label for="appointment_date">Appointment Date</label>
    <input type="date" id="appointment_date" name="appointment_date" required>
</div>

        <div class="form-field">
    <label for="appointment_time">Appointment Time</label>
    <input type="time" id="appointment_time" name="appointment_time" required>
</div>

        <div class="form-field">
    <label for="reason">Reason</label>
    <textarea id="reason" name="reason" required></textarea>
</div>

        <div class="form-actions"><button type="submit">Book Appointment</button><a class="button button-secondary" href="patients-doctors.php">Back to doctors</a></div>

    </form>
</main>
<?php require "../components/footer.php"; ?>
</body>
</html>
