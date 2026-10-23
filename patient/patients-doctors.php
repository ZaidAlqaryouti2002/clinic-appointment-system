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
?>\

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
</head>
<body>

    <h1>Doctors</h1>

    <nav>
        <a href="home.php">Home</a>
        <a href="doctors.php">Doctors</a>
        <a href="appointments.php">My Appointments</a>
        <a href="profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </nav>

<?php foreach ($doctors as $doctor): ?>

     <div>
        <img src="../uploads/doctors/<?= htmlspecialchars($doctor["image"]) ?>" 
             alt="Doctor Image"
             width="150"
        >
        
        <h3>
            Dr. <?= htmlspecialchars($doctor["first_name"]) ?>
            <?= htmlspecialchars($doctor["last_name"]) ?>
        </h3>

        <p>
            <?= htmlspecialchars($doctor["specialization"]) ?>
        </p>

        <p>
            <?= htmlspecialchars($doctor["email"]) ?>
        </p>

        <p>
            <?= htmlspecialchars($doctor["phone"]) ?>
        </p>

        <a href="book-appointment.php?doctor_id=<?= $doctor["doctor_id"] ?>">
            Book Appointment
        </a>
     </div>
<?php endforeach; ?>

</body>
</html>
