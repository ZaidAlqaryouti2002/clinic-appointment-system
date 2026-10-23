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
        <a href="home.php">Home</a>
        <a href="doctors.php">Doctors</a>
        <a href="appointments.php">My Appointments</a>
        <a href="profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </nav>

</body>
</html>