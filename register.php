<?php
declare(strict_types=1);

require_once "classes/database.php";
require_once "classes/patient.php";

$database = new Database();

$conn = $database->getConnection();

$patient = new Patient($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fname = trim($_POST["first_name"]);
    $lname = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $phone = trim($_POST["phone"]);

    if (
        $fname === "" ||
        $lname === "" ||
        $email === "" ||
        $password === "" ||
        $phone === ""
    ) {
        $message = "All fields are required";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    }
    else {
        if ($patient->register($fname, $lname, $email, $password, $phone)) {
            $message = "Registration successful";
        } else {
            $message = "Registration failed";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account | MedCare</title>
    <link rel="stylesheet" href="styling/style.css">
</head>
<body class="auth-page page-register">
<?php $pageArea = "auth"; $basePath = ""; ?>
<?php require "components/header.php"; ?>
<main class="container auth-content">
<section class="auth-panel" aria-labelledby="auth-heading">
    <p class="eyebrow">Patient access</p>
    <h1 id="auth-heading">Create your account</h1>
    <p class="page-description">Register with MedCare to book and track your clinic visits.</p>
<?php if ($message !== "") : ?>
    <p class="alert" role="alert" data-message="<?= htmlspecialchars($message) ?>"><?= $message ?></p>
<?php endif; ?>

<form class="auth-form" method="POST" action="">
    <div class="form-field">
    <label for="first_name">First Name</label>
    <input type="text" id="first_name" name="first_name">
</div>

    <div class="form-field">
    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name">
</div>

    <div class="form-field">
    <label for="email">Email</label>
    <input type="email" id="email" name="email">
</div>

    <div class="form-field">
    <label for="password">Password</label>
    <input type="password" id="password" name="password">
</div>

    <div class="form-field">
    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone">
</div>

    <button type="submit">Create account</button>
</form>

<p class="form-note">
    Already have an account?
    <a href="login.php">Login</a>
</p>
</section>
</main>
<?php require "components/footer.php"; ?>
</body>
</html>
