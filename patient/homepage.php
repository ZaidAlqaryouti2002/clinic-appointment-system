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

$appointmentModel = new Appointment($conn);

$patientId = $_SESSION["patient_id"];
$patientName = $_SESSION["patient_name"];

$upcomingAppointments = $appointmentModel->getPatientUpcomingAppointmentsCount($patientId);
$confirmedAppointments = $appointmentModel->getPatientConfirmedAppointmentsCount($patientId);
$pendingAppointments = $appointmentModel->getPatientPendingAppointmentsCount($patientId);
$nextAppointment = $appointmentModel->getNextPatientAppointment($patientId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient dashboard | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="patient-page page-home">
<?php $pageArea = "patient"; $basePath = "../"; ?>
<?php require "../components/header.php"; ?>
<main class="container">
<section class="welcome-section">
            <div><p class="eyebrow">Patient dashboard</p>
            <h1>Welcome, <?= htmlspecialchars($patientName) ?></h1>
            <p>Here is a quick overview of your appointments.</p></div>
            <a class="button" href="patients-doctors.php">Book Appointment</a>
        </section>

        <section class="stats-grid patient-stats" aria-label="Appointment overview">
            <div class="stat-card">
                <p>Upcoming Appointments</p>
                <h2><?= $upcomingAppointments ?></h2>
            </div>

            <div class="stat-card">
                <p>Confirmed Appointments</p>
                <h2><?= $confirmedAppointments ?></h2>
            </div>

            <div class="stat-card">
                <p>Pending Appointments</p>
                <h2><?= $pendingAppointments ?></h2>
            </div>
        </section>

        <section class="next-appointment">
            <div class="section-heading">
                <div><h2>Next Appointment</h2>
                <p>Your nearest scheduled visit</p></div>
                <a class="text-link" href="all-appointments.php">View all appointments</a>
            </div>

            <?php if ($nextAppointment): ?>
                <div class="next-appointment-details">
                    <div class="appointment-photo">
                    <?php if ($nextAppointment["image"] !== ""): ?>
                        <img
                            src="../uploads/doctors/<?= htmlspecialchars($nextAppointment["image"]) ?>"
                            alt="Doctor Image"
                        >
                    <?php endif; ?>
                    </div>
                    <div class="appointment-doctor">
                    <p class="specialization"><?= htmlspecialchars($nextAppointment["specialization"]) ?></p>

                    <h3>
                        Dr. <?= htmlspecialchars($nextAppointment["first_name"]) ?>
                        <?= htmlspecialchars($nextAppointment["last_name"]) ?>
                    </h3>

                    <p class="appointment-reason"><?= htmlspecialchars($nextAppointment["reason"]) ?></p>
                    </div>
                    <div class="appointment-schedule">
                    <div><span class="detail-label">Date</span><p><?= htmlspecialchars($nextAppointment["appointment_date"]) ?></p></div>
                    <div><span class="detail-label">Time</span><p><?= htmlspecialchars($nextAppointment["appointment_time"]) ?></p></div>
                    <div><span class="detail-label">Status</span><p><span class="status" data-status="<?= htmlspecialchars($nextAppointment["status"]) ?>"><?= htmlspecialchars($nextAppointment["status"]) ?></span></p></div>
                    </div>
                </div>
            <?php else: ?>
                <p class="empty-state">No upcoming appointments. Choose a doctor to plan your next visit.</p>
            <?php endif; ?>
        </section>
</main>
<?php require "../components/footer.php"; ?>
</body>
</html>
