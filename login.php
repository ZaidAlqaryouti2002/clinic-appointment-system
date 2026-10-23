<?php
declare(strict_types= 1);
session_start();

require_once("classes/database.php");
require_once("classes/patient.php");


$database = new Database();

$conn = $database->getConnection();

$patient = new Patient($conn);

$message = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email === "" || $password === ""){
        $message = "Email and Password are required";

    }
    else {
        $loggedpatient = $patient->login($email,$password);

        if($loggedpatient){
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
<?php if ($message !== ""): ?>
    <p><?= $message ?></p>
<?php endif; ?>
<form method="POST" action="">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>
