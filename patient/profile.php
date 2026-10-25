<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["patient_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/database.php";
require_once "../classes/patient.php";

$database = new Database();
$conn = $database->getConnection();

$patientModel = new Patient($conn);

$patientId = $_SESSION["patient_id"];
$patientData = $patientModel->getPatientById($patientId);
$message = "";

if (!$patientData) {
    header("Location: ../logout.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fname = trim($_POST["first_name"]);
    $lname = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    if (
        $fname === "" ||
        $lname === "" ||
        $email === "" ||
        $phone === ""
    ) {
        $message = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    } else {
        try {
            if ($patientModel->updateProfile($patientId, $fname, $lname, $email, $phone)) {
                $_SESSION["patient_name"] = $fname;
                $message = "Profile updated successfully";
                $patientData = $patientModel->getPatientById($patientId);
            } else {
                $message = "Failed to update profile";
            }
        } catch (PDOException $e) {
            $message = "Profile update failed. The email may already be in use";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="patient-page page-profile">
<?php $pageArea = "patient"; $basePath = "../"; ?>
<?php require "../components/header.php"; ?>
<main class="container">
    <div class="page-heading">
        <h1>My Profile</h1>
        <p>Review and update your personal information.</p>
    </div>

    <?php if ($message !== ""): ?>
        <p class="alert" role="alert" data-message="<?= htmlspecialchars($message) ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form class="profile-form form-panel" method="POST" action="">
        <div class="form-field">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($patientData["first_name"]) ?>" required>
        </div>

        <div class="form-field">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($patientData["last_name"]) ?>" required>
        </div>

        <div class="form-field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($patientData["email"]) ?>" required>
        </div>

        <div class="form-field">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($patientData["phone"]) ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit">Save Changes</button>
            <a class="button button-secondary" href="homepage.php">Back to Home</a>
        </div>
    </form>
</main>
<?php require "../components/footer.php"; ?>
</body>
</html>
