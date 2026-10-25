<?php
declare(strict_types=1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic appointments | MedCare</title>
    <link rel="stylesheet" href="styling/style.css">
</head>
<body class="public-page page-landing">
<?php $pageArea = "public"; $basePath = ""; ?>
<?php require "components/header.php"; ?>
<main class="container">
<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">Clinic Appointment System</p>
        <h1>Your next appointment,<br>made simple.</h1>
        <p class="hero-description">Find a doctor, book a visit, and keep track of your appointments. MedCare brings it all together in one clear, convenient place.</p>
        <div class="button-row">
            <a class="button" href="patient/register.php">Create Account</a>
            <a class="button button-secondary" href="patient/login.php">Patient Login</a>
        </div>
        <p class="hero-note">Already registered? Sign in to manage your appointments.</p>
    </div>
    <div class="hero-visual">
        <img src="styling/clinic-doctor.jpg" alt="Doctor wearing blue medical scrubs">
        <div class="hero-caption">
            <strong>A clearer path to your next visit.</strong>
            <span>Appointments organized around you.</span>
        </div>
    </div>
</section>
<section class="how-it-works" aria-labelledby="steps-heading">
    <div class="section-heading">
        <div>
            <h2 id="steps-heading">Getting started with MedCare</h2>
            <p>Three simple steps to manage your clinic visits.</p>
        </div>
    </div>
    <ol class="steps-grid">
        <li><span class="step-number">01</span><h3>Find a doctor</h3><p>Browse doctors and review their specialties and contact details.</p></li>
        <li><span class="step-number">02</span><h3>Book an appointment</h3><p>Choose your date and time, and tell us the reason for your visit.</p></li>
        <li><span class="step-number">03</span><h3>Track your appointment</h3><p>View your appointments and follow the latest status updates.</p></li>
    </ol>
</section>
</main>
<?php require "components/footer.php"; ?>
</body>
</html>
