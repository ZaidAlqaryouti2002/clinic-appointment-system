<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/database.php";
require_once "../classes/patient.php";

$database = new Database();
$conn = $database->getConnection();

$patientModel = new Patient($conn);

$patients = $patientModel->getAllPatients();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
</head>
<body>

    <h1>Patients</h1>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="doctors.php">Doctors</a>
        <a href="patients.php">Patients</a>
        <a href="appointments.php">Appointments</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <?php if (empty($patients)): ?>

        <p>No patients found.</p>

    <?php else: ?>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered At</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($patients as $patient): ?>

                    <tr>
                        <td><?= $patient["patient_id"] ?></td>

                        <td>
                            <?= htmlspecialchars($patient["first_name"]) ?>
                            <?= htmlspecialchars($patient["last_name"]) ?>
                        </td>

                        <td><?= htmlspecialchars($patient["email"]) ?></td>

                        <td><?= htmlspecialchars($patient["phone"]) ?></td>

                        <td><?= htmlspecialchars($patient["created_at"]) ?></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>