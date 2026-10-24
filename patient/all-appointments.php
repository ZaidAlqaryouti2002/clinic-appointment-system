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

$patientId = $_SESSION["patient_id"];

$appointments = $appointment->getPatientAppointments($patientId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="patient-page page-appointments">
<?php $pageArea = "patient"; $basePath = "../"; ?>
<?php require "../components/header.php"; ?>
<main class="container">
<div class="page-heading"><h1>My Appointments</h1><p>Review your appointment details and current status.</p></div>

    

    <?php if (empty($appointments)): ?>

        <p class="empty-state">No appointments found.</p>

    <?php else: ?>

        <div class="table-scroll" role="region" aria-label="My Appointments" tabindex="0">
<table>
            <thead>
                <tr>
                    <th scope="col">Doctor</th>
                    <th scope="col">Specialization</th>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Reason</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($appointments as $appointment): ?>

                    <tr>
                        <td>
                            Dr.
                            <?= htmlspecialchars($appointment["first_name"]) ?>
                            <?= htmlspecialchars($appointment["last_name"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment["specialization"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment["appointment_date"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment["appointment_time"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment["reason"]) ?>
                        </td>

                        <td>
                            <span class="status" data-status="<?= htmlspecialchars($appointment["status"]) ?>"><?= htmlspecialchars($appointment["status"]) ?></span>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
</div>

    <?php endif; ?>
</main>
<?php require "../components/footer.php"; ?>
</body>
</html>
