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
    <title>Patients | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="admin-page page-patients">
<div class="admin-layout">
    <?php require "../components/navbar.php"; ?>
    <div class="admin-content">
        <?php $pageArea = "admin"; $basePath = "../"; ?>
        <?php require "../components/header.php"; ?>
        <main class="dashboard-content">
<div class="page-heading"><h1>Patients</h1><p>Patient contact details and registration records.</p></div>

    

    <?php if (empty($patients)): ?>

        <p class="empty-state">No patients found.</p>

    <?php else: ?>

        <div class="table-scroll" role="region" aria-label="Patients" tabindex="0">
<table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Registered At</th>
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
</div>

    <?php endif; ?>
        </main>
        <?php require "../components/footer.php"; ?>
    </div>
</div>
</body>
</html>
