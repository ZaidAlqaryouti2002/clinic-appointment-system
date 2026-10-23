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
    <title>Manage Appointments</title>
</head>
<body>

    <h1>Manage Appointments</h1>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="doctors.php">Doctors</a>
        <a href="patients.php">Patients</a>
        <a href="appointments.php">Appointments</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <?php if ($message !== ""): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($appointments)): ?>

        <p>No appointments found.</p>

    <?php else: ?>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
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
                            <form method="POST" action="">
                                <input type="hidden" name="appointment_id" value="<?= $appointment["appointment_id"] ?>">

                                <select name="status">
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

    <?php endif; ?>

</body>
</html>
