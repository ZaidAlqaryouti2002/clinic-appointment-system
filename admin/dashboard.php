<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/database.php";
require_once "../classes/patient.php";
require_once "../classes/doctor.php";
require_once "../classes/appointment.php";

$database = new Database();
$conn = $database->getConnection();

$patientModel = new Patient($conn);
$doctorModel = new Doctor($conn);
$appointmentModel = new Appointment($conn);

$totalPatients = $patientModel->getPatientsCount();
$totalDoctors = $doctorModel->getDoctorsCount();
$totalAppointments = $appointmentModel->getAppointmentsCount();
$pendingAppointments = $appointmentModel->getPendingAppointmentsCount();
$recentAppointments = $appointmentModel->getRecentAppointments();

$adminName = $_SESSION["admin_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin dashboard | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="admin-page page-dashboard">
<div class="admin-layout">
    <?php require "../components/navbar.php"; ?>
    <div class="admin-content">
        <?php $pageArea = "admin"; $basePath = "../"; ?>
        <?php require "../components/header.php"; ?>
        <main class="dashboard-content">
<div class="dashboard-heading">
    <p class="eyebrow">Clinic overview</p>
                <h1>Dashboard</h1>
                <p>Overview of clinic activity and records.</p>
            </div>


            <div class="stats-grid">

                <div class="stat-card">
                    <p>Total Patients</p>
                    <h2><?= $totalPatients ?></h2>
                </div>

                <div class="stat-card">
                    <p>Total Doctors</p>
                    <h2><?= $totalDoctors ?></h2>
                </div>

                <div class="stat-card">
                    <p>Total Appointments</p>
                    <h2><?= $totalAppointments ?></h2>
                </div>

                <div class="stat-card">
                    <p>Pending Appointments</p>
                    <h2><?= $pendingAppointments ?></h2>
                </div>

            </div>


            <section class="recent-appointments">

                <div class="section-heading">
                    <div>
                        <h2>Recent Appointments</h2>
                        <p>Latest appointment activity</p>
                    </div>

                    <a class="text-link" href="appointments.php">View all appointments</a>
                </div>


                <div class="table-scroll" role="region" aria-label="Recent appointments" tabindex="0">
<table>

                    <thead>
                        <tr>
                            <th scope="col">Patient</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($recentAppointments)): ?>
                        <tr><td colspan="5" class="empty-state">No appointments found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($recentAppointments as $appointment): ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    $appointment["patient_first_name"] .
                                    " " .
                                    $appointment["patient_last_name"]
                                ) ?>
                            </td>

                            <td>
                                Dr.
                                <?= htmlspecialchars(
                                    $appointment["doctor_first_name"] .
                                    " " .
                                    $appointment["doctor_last_name"]
                                ) ?>
                            </td>

                            <td><?= htmlspecialchars($appointment["appointment_date"]) ?></td>

                            <td><?= htmlspecialchars($appointment["appointment_time"]) ?></td>

                            <td>
                                <span class="status" data-status="<?= htmlspecialchars($appointment["status"]) ?>"><?= htmlspecialchars($appointment["status"]) ?></span>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>
</div>

            </section>
        </main>
        <?php require "../components/footer.php"; ?>
    </div>
</div>
</body>
</html>
