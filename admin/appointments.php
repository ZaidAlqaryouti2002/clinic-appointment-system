<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/database.php";
require_once "../classes/appointment.php";

$database = new Database();
$conn = $database->getConnection();

$appointmentModel = new Appointment($conn);

$message = "";
$allowedStatuses = ["Pending", "Confirmed", "Completed", "Cancelled"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $appointmentId = (int) ($_POST["appointment_id"] ?? 0);
    $status = $_POST["status"] ?? "";

    if ($appointmentId === 0 || !in_array($status, $allowedStatuses)) {
        $message = "Invalid appointment data";
    } else {
        if ($appointmentModel->updateStatus($appointmentId, $status)) {
            $message = "Appointment status updated successfully";
        } else {
            $message = "Failed to update appointment status";
        }
    }
}

$appointments = $appointmentModel->getAllAppointments();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="admin-page page-appointments">
<div class="admin-layout">
    <?php require "../components/navbar.php"; ?>
    <div class="admin-content">
        <?php $pageArea = "admin"; $basePath = "../"; ?>
        <?php require "../components/header.php"; ?>
        <main class="dashboard-content">
<div class="page-heading"><h1>Manage Appointments</h1><p>Review clinic appointments and update their status.</p></div>

    

    <?php if ($message !== ""): ?>
        <p class="alert" role="alert" data-message="<?= htmlspecialchars($message) ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($appointments)): ?>

        <p class="empty-state">No appointments found.</p>

    <?php else: ?>

        <div class="table-scroll" role="region" aria-label="Manage Appointments" tabindex="0">
<table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Patient</th>
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
                        <td><?= $appointment["appointment_id"] ?></td>

                        <td>
                            <?= htmlspecialchars($appointment["patient_first_name"]) ?>
                            <?= htmlspecialchars($appointment["patient_last_name"]) ?>
                        </td>

                        <td>
                            Dr.
                            <?= htmlspecialchars($appointment["doctor_first_name"]) ?>
                            <?= htmlspecialchars($appointment["doctor_last_name"]) ?>
                        </td>

                        <td><?= htmlspecialchars($appointment["specialization"]) ?></td>
                        <td><?= htmlspecialchars($appointment["appointment_date"]) ?></td>
                        <td><?= htmlspecialchars($appointment["appointment_time"]) ?></td>
                        <td><?= htmlspecialchars($appointment["reason"]) ?></td>

                        <td>
                            <span class="status" data-status="<?= htmlspecialchars($appointment["status"]) ?>"><?= htmlspecialchars($appointment["status"]) ?></span>
                            <form class="status-form" method="POST" action="">
                                <input type="hidden" name="appointment_id" value="<?= $appointment["appointment_id"] ?>">

                                <select name="status" aria-label="Appointment status">
                                    <?php foreach ($allowedStatuses as $status): ?>
                                        <option
                                            value="<?= htmlspecialchars($status) ?>"
                                            <?= $appointment["status"] === $status ? "selected" : "" ?>
                                        >
                                            <?= htmlspecialchars($status) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</div>

    <?php endif; ?>
        </main>
        <?php require "../components/footer.php"; ?>
    </div>
</div>
</body>
</html>
