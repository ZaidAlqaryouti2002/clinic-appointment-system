<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["patient_id"])) {
    header("Location: ../login.php");
    exit;
}

$patientName = $_SESSION["patient_name"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Homepage</title>
</head>
<body>

    <h1>Welcome <?= htmlspecialchars($patientName) ?></h1>

    <nav>
        <a href="homepage.php">Home</a>
        <a href="patients-doctors.php">Doctors</a>
        <a href="all-appointments.php">My Appointments</a>
        <a href="../logout.php">Logout</a>
    </nav>

</body>
</html>