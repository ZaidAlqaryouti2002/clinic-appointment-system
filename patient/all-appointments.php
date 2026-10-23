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
    <title>My Appointments</title>
</head>
<body>

    <h1>My Appointments</h1>

    <nav>
        <a href="home.php">Home</a>
        <a href="doctors.php">Doctors</a>
        <a href="all-appointments.php">My Appointments</a>
        <a href="profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <?php if (empty($appointments)): ?>

        <p>No appointments found.</p>

    <?php else: ?>

        <table border="1">
            <thead>
                <tr>
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
                            <?= htmlspecialchars($appointment["status"]) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>