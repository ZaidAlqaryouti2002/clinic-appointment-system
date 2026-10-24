<?php
declare(strict_types= 1);

session_start();

if(!isset($_SESSION["patient_id"])){
    header("Location: ../login.php");
    exit;
}

require_once "../classes/database.php";
require_once "../classes/doctor.php";

$database = new Database();
$conn = $database->getConnection();

$doctor = new Doctor($conn);
$doctors = $doctor->getAllDoctors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="patient-page page-doctors">
<?php $pageArea = "patient"; $basePath = "../"; ?>
<?php require "../components/header.php"; ?>
<main class="container">
<div class="page-heading"><h1>Doctors</h1><p>Find a doctor and book your next clinic appointment.</p></div>

    

<div class="doctor-grid">
<?php foreach ($doctors as $doctor): ?>

     <article class="doctor-card">
        <div class="doctor-card-heading">
        <div class="doctor-photo">
        <?php if (!empty($doctor["image"])): ?>
            <img src="../uploads/doctors/<?= htmlspecialchars($doctor["image"]) ?>"
                 alt="Doctor Image"
                 width="150"
            >
        <?php else: ?>
            <span class="photo-placeholder">No photo</span>
        <?php endif; ?>
        </div>
        <div class="doctor-introduction">
        <h3>
            Dr. <?= htmlspecialchars($doctor["first_name"]) ?>
            <?= htmlspecialchars($doctor["last_name"]) ?>
        </h3>

        <p class="specialization">
            <?= htmlspecialchars($doctor["specialization"]) ?>
        </p>
        </div>
        </div>

        <p class="doctor-contact"><span class="detail-label">Email</span>
            <?= htmlspecialchars($doctor["email"]) ?>
        </p>

        <p class="doctor-contact"><span class="detail-label">Phone</span>
            <?= htmlspecialchars($doctor["phone"]) ?>
        </p>

        <a class="button" href="book-appointments.php?doctor_id=<?= $doctor["doctor_id"] ?>">
            Book Appointment
        </a>
     </article>
<?php endforeach; ?>
</div>


<?php if (empty($doctors)): ?>
    <p class="empty-state">No doctors found.</p>
<?php endif; ?>
</main>
<?php require "../components/footer.php"; ?>
</body>
</html>
