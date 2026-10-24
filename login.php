<?php
declare(strict_types=1);

session_start();

require_once("classes/database.php");
require_once("classes/patient.php");

$database = new Database();

$conn = $database->getConnection();

$patient = new Patient($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email === "" || $password === "") {
        $message = "Email and Password are required";
    } else {
        $loggedpatient = $patient->login($email, $password);

        if ($loggedpatient) {
            $_SESSION["patient_id"] = $loggedpatient["patient_id"];
            $_SESSION["patient_name"] = $loggedpatient["first_name"];

            header("Location: patient/homepage.php");
            exit;
        } else {
            $message = "Invalid Email or Password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient login | MedCare</title>
    <link rel="stylesheet" href="styling/style.css">
</head>
<body class="auth-page page-login">
<?php $pageArea = "auth"; $basePath = ""; ?>
<?php require "components/header.php"; ?>
<main class="container auth-content">
<section class="auth-panel" aria-labelledby="auth-heading">
    <p class="eyebrow">Patient access</p>
    <h1 id="auth-heading">Welcome back</h1>
    <p class="page-description">Sign in to MedCare to manage your appointments.</p>
<?php if ($message !== ""): ?>
    <p class="alert" role="alert" data-message="<?= htmlspecialchars($message) ?>"><?= $message ?></p>
<?php endif; ?>

<form class="auth-form" method="POST" action="">
    <div class="form-field">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
</div>

    <div class="form-field">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
</div>

    <button type="submit">Sign in</button>
</form>

<p class="form-note">
    Don't have an account?
    <a href="register.php">Register</a>
</p>
</section>
</main>
<?php require "components/footer.php"; ?>
</body>
</html>
