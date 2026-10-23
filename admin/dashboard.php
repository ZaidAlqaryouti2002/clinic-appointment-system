<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$adminName = $_SESSION["admin_name"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>

    <h1>Welcome <?= htmlspecialchars($adminName) ?></h1>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="doctors.php">Doctors</a>
        <a href="patients.php">Patients</a>
        <a href="appointments.php">Appointments</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <h2>Admin Dashboard</h2>

    <p>Manage doctors, patients, and appointments from here.</p>

</body>
</html>